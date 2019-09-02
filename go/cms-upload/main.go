package main

import (
	"context"
	"database/sql"
	"fmt"
	"net/http"
	"os"
	"os/signal"

	clogger "github.com/IDBMobileTechnology/idb-mobile/common/lib/go/logger"

	"cloud.google.com/go/storage"
	"github.com/spf13/viper"
	"go.uber.org/zap"

	_ "github.com/go-sql-driver/mysql"
	"gopkg.in/doug-martin/goqu.v4"
	_ "gopkg.in/doug-martin/goqu.v4/adapters/mysql"
)

func main() {
	// Loading config variables
	if err := loadConfig(); err != nil {
		panic(err)
	}

	// Creating logger
	log := clogger.NewFromEnv(viper.GetString("APP_ENV"))
	defer log.Sync()

	ctx := context.Background()

	// Mysql init
	sqlDb, err := sql.Open("mysql", viper.GetString("MYSQL_ADDR"))
	if err != nil {
		log.Fatal("error connecting to mysql", zap.Error(err))
	}
	defer sqlDb.Close()

	// Test connection
	if err = sqlDb.Ping(); err != nil {
		log.Fatal("unable to ping database", zap.Error(err))
	}

	// Database helper
	db := goqu.New("mysql", sqlDb)

	// Google Storage Client & Bucket
	client, err := storage.NewClient(ctx)
	if err != nil {
		log.Fatal("error creating Google Storage client", zap.Error(err))
	}
	bucket := client.Bucket(viper.GetString("GOOGLE_BUCKET_NAME"))

	// Server init
	server := &server{db, ctx, bucket, log}
	http.HandleFunc("/upload", server.upload)
	log.Info("server has started")
	go func() {
		if err = http.ListenAndServe(viper.GetString("APP_ADDR"), nil); err != nil {
			log.Fatal("unable to start server", zap.Error(err))
		}
	}()

	sig := make(chan os.Signal, 1)
	signal.Notify(sig, os.Interrupt)
	<-sig
}

func loadConfig() error {
	viper.SetDefault("APP_ADDR", ":8080")
	viper.SetDefault("APP_NAME", "web/cms-upload")
	viper.SetDefault("APP_ENV", "development")

	viper.AutomaticEnv()

	mandatoryVars := [2]string{"MYSQL_ADDR", "GOOGLE_BUCKET_NAME"}
	for _, param := range mandatoryVars {
		if !viper.IsSet(param) {
			return fmt.Errorf("missing mandatory parameter: %s", param)
		}
	}

	return nil
}
