package main

import (
	"fmt"
	"os"
	"os/signal"

	"github.com/spf13/viper"
	"go.uber.org/zap"
	"go.uber.org/zap/zapgrpc"
	"google.golang.org/grpc/grpclog"
	gomail "gopkg.in/gomail.v2"

	cgrpc "github.com/IDBMobileTechnology/idb-mobile/common/lib/go/grpc"
	clogger "github.com/IDBMobileTechnology/idb-mobile/common/lib/go/logger"
	ctracer "github.com/IDBMobileTechnology/idb-mobile/common/lib/go/tracer"
	pb "github.com/IDBMobileTechnology/idb-mobile/common/services/mailer/proto"
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

	d := gomail.NewPlainDialer(
		viper.GetString("MS_HOST"),
		viper.GetInt("MS_PORT"),
		viper.GetString("MS_USERNAME"),
		viper.GetString("MS_PASSWORD"))

	s := cgrpc.NewServer(log, tracer)
	pb.RegisterMailerServer(s, &server{d})
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
	viper.SetDefault("APP_NAME", "common/mailer")
	viper.SetDefault("APP_ENV", "development")

	viper.SetDefault("MS_PORT", 587)

	viper.AutomaticEnv()

	mandatoryVars := [3]string{"MS_USERNAME", "MS_PASSWORD", "MS_HOST"}

	for _, param := range mandatoryVars {
		if !viper.IsSet(param) {
			return fmt.Errorf("missing mandatory parameter: %s", param)
		}
	}

	return nil
}
