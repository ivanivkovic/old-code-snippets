package main

import (
	"context"
	"encoding/json"
	"errors"
	"io"
	"net/http"
	"strings"

	"cloud.google.com/go/storage"
	"go.uber.org/zap"

	_ "github.com/go-sql-driver/mysql"
	"gopkg.in/doug-martin/goqu.v4"
	_ "gopkg.in/doug-martin/goqu.v4/adapters/mysql"
)

// Server dependency data
type server struct {
	db     *goqu.Database
	ctx    context.Context
	bucket *storage.BucketHandle
	log    *zap.Logger
}

// Upload image for the content ID & update content.image_url
func (s *server) upload(w http.ResponseWriter, r *http.Request) {
	// Data validation
	if r.Method != "POST" {
		w.WriteHeader(http.StatusMethodNotAllowed)
		writeJSON("405", "only POST method allowed", w, s.log, errors.New("only POST method allowed"))
		return
	}

	r.ParseMultipartForm(10240)

	f, handler, err := r.FormFile("upload_file")
	if err != nil {
		w.WriteHeader(http.StatusInternalServerError)
		writeJSON("500", "file processing error", w, s.log, err)
		return
	}
	defer f.Close()

	if handler.Size == 0 {
		w.WriteHeader(http.StatusBadRequest)
		writeJSON("400", "missing upload file", w, s.log, errors.New("missing upload file"))
		return
	}
	if err := r.ParseForm(); err != nil {
		w.WriteHeader(http.StatusInternalServerError)
		writeJSON("500", "failed to parse form", w, s.log, err)
		return
	}
	if len(r.FormValue("content_id")) == 0 || r.FormValue("content_id") == "0" {
		w.WriteHeader(http.StatusBadRequest)
		writeJSON("400", "missing content ID", w, s.log, err)
		return
	}

	// Upload to Google Storage
	fileName := handler.Filename
	object := s.bucket.Object(fileName)
	wc := object.NewWriter(s.ctx)

	_, err = io.Copy(wc, f)
	if err != nil {
		w.WriteHeader(http.StatusInternalServerError)
		writeJSON("500", "failed to copy file", w, s.log, err)
		return
	}
	if err := wc.Close(); err != nil {
		w.WriteHeader(http.StatusInternalServerError)
		writeJSON("500", "failed to close bucket writer", w, s.log, err)
		return
	}

	// Get uploaded file public URL
	data, err := object.Attrs(s.ctx)
	if err != nil {
		w.WriteHeader(http.StatusInternalServerError)
		writeJSON("500", "failed to obtain upload metadata", w, s.log, err)
		return
	}

	// Update link to database
	update := map[string]string{"content_url": data.MediaLink}
	query := s.db.From("cms.content").Where(goqu.Ex{"id": r.FormValue("content_id")}).Update(update)
	if _, err := query.Exec(); err != nil {
		w.WriteHeader(http.StatusInternalServerError)
		writeJSON("500", "error updating content url", w, s.log, err)
		return
	}

	w.WriteHeader(http.StatusOK)
	writeJSON("200", "content URL uploaded", w, s.log, nil)
}

func writeJSON(status, msg string, w http.ResponseWriter, log *zap.Logger, err error) {
	if err != nil {
		log.Error(msg, zap.Error(err))
	} else {
		log.Info(msg)
	}

	response := map[string]string{"httpStatus": status, "message": strings.Title(msg)}
	jsonString, err := json.Marshal(response)
	if err != nil {
		log.Error("failed to generate json response:", zap.Error(err))
		return
	}

	w.Write([]byte(jsonString))
}
