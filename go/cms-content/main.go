package main

import (
	"database/sql"
	"fmt"
	"os"
	"os/signal"

	"github.com/spf13/viper"
	"go.uber.org/zap"
	"go.uber.org/zap/zapgrpc"
	"google.golang.org/grpc/grpclog"

	cgrpc "github.com/IDBMobileTechnology/idb-mobile/common/lib/go/grpc"
	clogger "github.com/IDBMobileTechnology/idb-mobile/common/lib/go/logger"
	ctracer "github.com/IDBMobileTechnology/idb-mobile/common/lib/go/tracer"
	pb "github.com/IDBMobileTechnology/idb-mobile/domain/web/cms/content/proto"

	_ "github.com/go-sql-driver/mysql"
	"gopkg.in/doug-martin/goqu.v4"
	_ "gopkg.in/doug-martin/goqu.v4/adapters/mysql"
)

func main() {
	if err := loadConfig(); err != nil {
		panic(err)
	}

	log := clogger.NewFromEnv(viper.GetString("APP_ENV"))
	grpclog.SetLogger(zapgrpc.NewLogger(log))
	defer log.Sync()

	tracer, closer, err := ctracer.NewFromEnv(
		viper.GetString("APP_NAME"),
		viper.GetString("JAEGER_AGENT_ADDR"),
		viper.GetString("APP_ENV"),
	)
	if err != nil {
		log.Fatal("error creating tracer", zap.Error(err))
	}
	defer closer.Close()

	// Mysql init
	sqlDb, err := sql.Open("mysql", viper.GetString("MYSQL_ADDR"))
	if err != nil {
		log.Fatal("error connecting to mysql", zap.Error(err))
	}
	defer sqlDb.Close()

	// Initial database creation
	if err = createDB(sqlDb); err != nil {
		log.Fatal("unable to create database upon load", zap.Error(err))
	}

	// Test connection
	if err = sqlDb.Ping(); err != nil {
		log.Fatal("unable to ping database", zap.Error(err))
	}

	// Database helper called
	db := goqu.New("mysql", sqlDb)

	// GRPC init
	s := cgrpc.NewServer(log, tracer)
	pb.RegisterContentServiceServer(s, &server{db})
	defer s.GracefulStop()

	log.Info("server has started")

	go func() {
		if err := cgrpc.StartServer(s, viper.GetString("APP_ADDR")); err != nil {
			log.Fatal("error starting server", zap.Error(err))
		}
	}()

	sig := make(chan os.Signal, 1)
	signal.Notify(sig, os.Interrupt)
	<-sig
}

func loadConfig() error {
	viper.SetDefault("APP_ADDR", ":50051")
	viper.SetDefault("APP_NAME", "web/cms-content")
	viper.SetDefault("APP_ENV", "development")

	viper.AutomaticEnv()

	if !viper.IsSet("MYSQL_ADDR") {
		return fmt.Errorf("missing mandatory parameter: MYSQL_ADDR")
	}

	return nil
}

func createDB(sqlDb *sql.DB) error {
	s := make([]string, 0)

	s = append(s, `SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";`)
	s = append(s, `SET time_zone = "+00:00";`)
	s = append(s, `CREATE DATABASE IF NOT EXISTS cms COLLATE utf8_general_ci DEFAULT charset=utf8;`)
	s = append(s, `USE cms;`)
	s = append(s, `CREATE TABLE IF NOT EXISTS content
		(
			id            INT(11) NOT NULL auto_increment,
			category_id   INT(11) NOT NULL,
			name          VARCHAR(256) NOT NULL,
			type_id       INT(11) NOT NULL,
			description   MEDIUMTEXT NOT NULL,
			thumbnail_url VARCHAR(2000) NOT NULL,
			content_url   VARCHAR(2000) NOT NULL,
			size          INT(11) NOT NULL,
			PRIMARY KEY (id),
			INDEX(category_id),
			INDEX(type_id)
		)
		engine=innodb;`)
	s = append(s, `CREATE TABLE IF NOT EXISTS content_category
		(
			id          INT(11) NOT NULL auto_increment,
			parent_id   INT(11) DEFAULT NULL,
			name        VARCHAR(256) NOT NULL,
			description MEDIUMTEXT NOT NULL,
			PRIMARY KEY (id)
		)
		engine=innodb;`)
	s = append(s, `CREATE TABLE IF NOT EXISTS content_type
		(
			id		INT(11) NOT NULL auto_increment,
			name		VARCHAR(256) NOT NULL,
			description	MEDIUMTEXT NOT NULL,
			PRIMARY KEY (id)
		)
		engine=innodb;`)

	txn, err := sqlDb.Begin()
	if err != nil {
		return err
	}

	for _, q := range s {
		_, err := txn.Exec(q)
		if err != nil {
			return err
		}
	}

	return txn.Commit()
}
