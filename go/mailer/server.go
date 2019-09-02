package main

import (
	"go.uber.org/zap"
	"golang.org/x/net/context"
	"gopkg.in/gomail.v2"

	clogger "github.com/IDBMobileTechnology/idb-mobile/common/lib/go/logger"
	pb "github.com/IDBMobileTechnology/idb-mobile/common/services/mailer/proto"
	status "google.golang.org/grpc/status"

	"google.golang.org/grpc/codes"
)

type server struct {
	d *gomail.Dialer
}

func (s *server) SendEmail(ctx context.Context, req *pb.SendEmailRequest) (*pb.SendEmailResponse, error) {

	log := clogger.FromContext(ctx)

	if req.ContentType == "" {
		req.ContentType = "text/html"
	}

	m := gomail.NewMessage()
	m.SetHeader("From", req.From)
	m.SetHeader("To", req.To)
	m.SetHeader("Subject", req.Subject)
	m.SetBody(req.ContentType, req.Message)

	if err := s.d.DialAndSend(m); err != nil {
		log.Error("error sending mail", zap.Error(err))

		return nil, status.Error(codes.Internal, err.Error())
	}

	return &pb.SendEmailResponse{}, nil
}
