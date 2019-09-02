package main

import (
	"database/sql"
	"math"

	"go.uber.org/zap"
	"golang.org/x/net/context"

	"strconv"

	clogger "github.com/IDBMobileTechnology/idb-mobile/common/lib/go/logger"
	pb "github.com/IDBMobileTechnology/idb-mobile/domain/web/cms/content/proto"
	status "google.golang.org/grpc/status"

	"google.golang.org/grpc/codes"
	"gopkg.in/doug-martin/goqu.v4"
	_ "gopkg.in/doug-martin/goqu.v4/adapters/mysql"
)

type server struct {
	db *goqu.Database
}

func (s *server) CreateContent(ctx context.Context, req *pb.CreateContentRequest) (*pb.CreateContentResponse, error) {
	log := clogger.FromContext(ctx)

	if req.Name == "" {
		log.Error("error upon data validation: content name is blank!")
		return nil, status.Error(codes.Internal, "missing content name")

	} else if req.CategoryId == 0 {
		log.Error("error upon data validation: category ID is blank!")
		return nil, status.Error(codes.Internal, "missing category ID")

	} else if req.TypeId == 0 {
		log.Error("error upon data validation: content type ID is blank!")
		return nil, status.Error(codes.Internal, "missing content type ID")
	}

	query := s.db.From("cms.content").Insert(goqu.Record{
		"category_id":   req.CategoryId,
		"name":          req.Name,
		"type_id":       req.TypeId,
		"description":   req.Description,
		"thumbnail_url": req.ThumbnailUrl,
		"content_url":   req.ContentUrl,
		"size":          req.Size,
	})

	insert, err := query.Exec()
	if err != nil {
		log.Error("error inserting content", zap.Error(err))
		return nil, status.Error(codes.Internal, "error inserting content")
	}

	id, err := insert.LastInsertId()
	if err != nil {
		log.Error("error fetching insert ID", zap.Error(err))
		return nil, status.Error(codes.Internal, "error fetching insert ID")
	}

	return &pb.CreateContentResponse{Id: int32(id)}, nil
}

func (s *server) CreateCategory(ctx context.Context, req *pb.CreateCategoryRequest) (*pb.CreateCategoryResponse, error) {
	log := clogger.FromContext(ctx)

	if req.Name == "" {
		log.Error("error upon data validation: category name is blank!")
		return nil, status.Error(codes.Internal, "missing category name")
	}

	query := s.db.From("cms.content_category").Insert(goqu.Record{
		"parent_id":   req.ParentId,
		"name":        req.Name,
		"description": req.Description,
	})
	insert, err := query.Exec()
	if err != nil {
		log.Error("error inserting category", zap.Error(err))
		return nil, status.Error(codes.Internal, "error inserting category")
	}

	id, err := insert.LastInsertId()
	if err != nil {
		log.Error("error fetching insert ID", zap.Error(err))
		return nil, status.Error(codes.Internal, "error fetching insert ID")
	}

	return &pb.CreateCategoryResponse{Id: int32(id)}, nil
}

func (s *server) CreateContentType(ctx context.Context, req *pb.CreateContentTypeRequest) (*pb.CreateContentTypeResponse, error) {
	log := clogger.FromContext(ctx)

	if req.Name == "" {
		log.Error("error upon data validation: content type name is blank!")
		return nil, status.Error(codes.Internal, "missing content type name")
	}

	query := s.db.From("cms.content_type").Insert(goqu.Record{
		"name":        req.Name,
		"description": req.Description,
	})
	insert, err := query.Exec()
	if err != nil {
		log.Error("error inserting content type", zap.Error(err))
		return nil, status.Error(codes.Internal, "error inserting content type")
	}

	id, err := insert.LastInsertId()
	if err != nil {
		log.Error("error fetching insert ID", zap.Error(err))
		return nil, status.Error(codes.Internal, "error fetching insert ID")
	}

	return &pb.CreateContentTypeResponse{Id: int32(id)}, nil
}

func (s *server) GetContent(ctx context.Context, req *pb.GetContentRequest) (*pb.Content, error) {
	log := clogger.FromContext(ctx)

	responseData := &pb.Content{}

	if req.Id == 0 {
		log.Error("insufficient data provided")
		return nil, status.Error(codes.Internal, "bad request - insufficient data provided")
	}

	query, _, _ := s.db.From("cms.content").Where(goqu.Ex{"id": req.Id}).ToSql()
	err := s.db.Db.QueryRowContext(ctx, query).Scan(
		&responseData.Id,
		&responseData.CategoryId,
		&responseData.Name,
		&responseData.TypeId,
		&responseData.Description,
		&responseData.ThumbnailUrl,
		&responseData.ContentUrl,
		&responseData.Size,
	)
	if err != nil {
		if err == sql.ErrNoRows {
			return nil, status.Error(codes.NotFound, "no results")
		}

		log.Error("error querying content", zap.Error(err))
		return nil, status.Error(codes.Internal, "error querying content")
	}

	return responseData, nil
}

// GetContents can receive any of the following: category id, type id or name.
// Multiple crtieria can be applied for a more specific search.
// If no parameters set, will return a list based on default paging.
func (s *server) GetContents(ctx context.Context, req *pb.GetContentsRequest) (*pb.GetContentsResponse, error) {
	log := clogger.FromContext(ctx)

	selectCount := `SELECT COUNT(*) AS Count FROM cms.content `
	selectData := `SELECT * FROM cms.content `
	where := ``

	// Processing content by filters
	if req.CategoryId != 0 || req.TypeId != 0 || req.Name != "" {

		where += `WHERE `
		and := false

		if req.CategoryId != 0 {
			where += `category_id="` + ToString(req.CategoryId) + `" `
			and = true
		}
		if req.TypeId != 0 {
			if and == true {
				where += `AND `
			}

			where += `type_id="` + ToString(req.TypeId) + `" `
			and = true
		}
		if req.Name != "" {
			if and == true {
				where += `AND `
			}

			where += `name LIKE '%` + req.Name + `%' `
			and = true
		}
	}

	// Paging defaults
	if req.PageNum < 1 {
		req.PageNum = 1
	}
	if req.PageSize < 1 {
		req.PageSize = 10
	}

	pageOffset := (req.PageNum - 1) * req.PageSize
	limit := `LIMIT ` + ToString(req.PageSize) + ` OFFSET ` + ToString(pageOffset)
	count := 0.0

	// Getting data count for correct paging
	err := s.db.Db.QueryRowContext(ctx, selectCount+where).Scan(&count)
	if err != nil {
		log.Error("error querying content", zap.Error(err))
		return nil, status.Error(codes.Internal, "error querying content")
	}

	// Actual data query
	rows, err := s.db.Db.QueryContext(ctx, selectData+where+limit)
	if err != nil {
		log.Error("error querying content", zap.Error(err))
		return nil, status.Error(codes.Internal, "error querying content")
	}

	responseParent := &pb.GetContentsResponse{}
	responseData := &pb.Content{}

	for rows.Next() {
		err = rows.Scan(
			&responseData.Id,
			&responseData.CategoryId,
			&responseData.Name,
			&responseData.TypeId,
			&responseData.Description,
			&responseData.ThumbnailUrl,
			&responseData.ContentUrl,
			&responseData.Size,
		)
		if err != nil {
			log.Error("error scanning results", zap.Error(err))
			return nil, status.Error(codes.Internal, "error scanning results")
		}

		responseParent.Results = append(responseParent.Results, responseData)
		responseData = &pb.Content{}
	}
	if len(responseParent.Results) == 0 {
		log.Error("no rows in result set", zap.Error(err))
		return nil, status.Error(codes.NotFound, "not found")
	}

	d := float64(float64(count) / float64(req.PageSize))
	responseParent.PagesCount = int32(math.Ceil(d))

	return responseParent, nil
}

func (s *server) GetContentType(ctx context.Context, req *pb.GetContentTypeRequest) (*pb.ContentType, error) {
	log := clogger.FromContext(ctx)

	responseData := &pb.ContentType{}

	if req.Id == 0 {
		log.Error("insufficient data provided")
		return nil, status.Error(codes.Internal, "bad request - missing content type ID")
	}

	query, _, _ := s.db.From("cms.content_type").Where(goqu.Ex{"id": req.Id}).ToSql()
	err := s.db.Db.QueryRowContext(ctx, query).Scan(&responseData.Id, &responseData.Name, &responseData.Description)
	if err != nil {
		if err == sql.ErrNoRows {
			log.Error("no rows in result set", zap.Error(err))
			return nil, status.Error(codes.NotFound, "no results")
		}

		log.Error("error querying content type", zap.Error(err))
		return nil, status.Error(codes.Internal, "error querying content type")
	}

	return responseData, nil
}

func (s *server) GetAllContentTypes(ctx context.Context, req *pb.GetAllContentTypesRequest) (*pb.GetAllContentTypesResponse, error) {
	log := clogger.FromContext(ctx)

	responseParent := &pb.GetAllContentTypesResponse{}
	responseData := &pb.ContentType{}

	query, _, _ := s.db.From("cms.content_type").ToSql()
	rows, err := s.db.Db.QueryContext(ctx, query)
	if err != nil {
		log.Error("error querying content types", zap.Error(err))
		return nil, status.Error(codes.Internal, "error querying content types")
	}

	for rows.Next() {
		err = rows.Scan(
			&responseData.Id,
			&responseData.Name,
			&responseData.Description,
		)
		if err != nil {
			log.Error("error scanning database results", zap.Error(err))
			return nil, status.Error(codes.Internal, "error scanning database results")
		}

		responseParent.Results = append(responseParent.Results, responseData)
		responseData = &pb.ContentType{}
	}
	if len(responseParent.Results) == 0 {
		log.Error("no rows in result set", zap.Error(err))
		return nil, status.Error(codes.NotFound, "not found")
	}

	return responseParent, nil
}

func (s *server) GetCategory(ctx context.Context, req *pb.GetCategoryRequest) (*pb.Category, error) {
	log := clogger.FromContext(ctx)

	responseData := &pb.Category{}

	if req.Id == 0 {
		log.Error("insufficient data provided")
		return nil, status.Error(codes.Internal, "bad request - missing category ID")
	}

	query, _, _ := s.db.From("cms.content_category").Where(goqu.Ex{"id": req.Id}).ToSql()
	err := s.db.Db.QueryRowContext(ctx, query).Scan(&responseData.Id, &responseData.ParentId, &responseData.Name, &responseData.Description)
	if err != nil {
		if err == sql.ErrNoRows {
			log.Error("no rows in result set", zap.Error(err))
			return nil, status.Error(codes.NotFound, "no results")
		}

		log.Error("error querying content type", zap.Error(err))
		return nil, status.Error(codes.Internal, "error querying content type")
	}

	return responseData, nil
}

func (s *server) GetAllCategories(ctx context.Context, req *pb.GetAllCategoriesRequest) (*pb.GetAllCategoriesResponse, error) {
	log := clogger.FromContext(ctx)

	responseParent := &pb.GetAllCategoriesResponse{}
	responseData := &pb.Category{}

	query, _, _ := s.db.From("cms.content_category").ToSql()
	rows, err := s.db.Db.QueryContext(ctx, query)
	if err != nil {
		log.Error("error querying content types", zap.Error(err))
		return nil, status.Error(codes.Internal, "error querying content types")
	}

	for rows.Next() {
		err = rows.Scan(
			&responseData.Id,
			&responseData.ParentId,
			&responseData.Name,
			&responseData.Description,
		)
		if err != nil {
			log.Error("error scanning database results", zap.Error(err))
			return nil, status.Error(codes.Internal, "error scanning database results")
		}

		responseParent.Results = append(responseParent.Results, responseData)
		responseData = &pb.Category{}
	}
	if len(responseParent.Results) == 0 {
		log.Error("no rows in result set", zap.Error(err))
		return nil, status.Error(codes.NotFound, "not found")
	}

	return responseParent, nil
}

func (s *server) UpdateContent(ctx context.Context, req *pb.Content) (*pb.UpdateContentResponse, error) {
	log := clogger.FromContext(ctx)

	if req.Id == 0 {
		log.Error("error upon data validation: content ID is blank!")
		return nil, status.Error(codes.Internal, "missing content ID")
	}
	if req.CategoryId == 0 && req.ThumbnailUrl == "" && req.TypeId == 0 && req.Name == "" && req.Description == "" && req.ContentUrl == "" && req.Size == 0 {
		log.Error("insufficient data provided")
		return nil, status.Error(codes.Internal, "bad request - insufficient data provided")
	}

	data := make(map[string]string)

	if req.ThumbnailUrl != "" {
		data["thumbnail_url"] = req.ThumbnailUrl
	}
	if req.CategoryId != 0 {
		data["category_id"] = ToString(req.CategoryId)
	}
	if req.TypeId != 0 {
		data["type_id"] = ToString(req.TypeId)
	}
	if req.Name != "" {
		data["name"] = req.Name
	}
	if req.Description != "" {
		data["description"] = req.Description
	}
	if req.ContentUrl != "" {
		data["content_url"] = req.ContentUrl
	}
	if req.Size != 0 {
		data["size"] = ToString(req.Size)
	}

	query := s.db.From("cms.content").Where(goqu.Ex{"id": req.Id}).Update(data)
	if _, err := query.Exec(); err != nil {
		log.Error("error updating content", zap.Error(err))
		return nil, status.Error(codes.Internal, "error updating content")
	}

	return &pb.UpdateContentResponse{}, nil
}

func (s *server) UpdateCategory(ctx context.Context, req *pb.Category) (*pb.UpdateCategoryResponse, error) {
	log := clogger.FromContext(ctx)

	if req.Id == 0 {
		log.Error("error upon data validation: category ID is blank!")
		return nil, status.Error(codes.Internal, "missing category ID")
	}
	if req.Name == "" && req.Description == "" {
		log.Error("insufficient data provided")
		return nil, status.Error(codes.Internal, "bad request - insufficient data provided")
	}

	data := make(map[string]string)

	if req.Name != "" {
		data["name"] = req.Name
	}
	if req.Description != "" {
		data["description"] = req.Description
	}
	if req.ParentId != 0 {
		data["parent_id"] = ToString(req.ParentId)
	}

	query := s.db.From("cms.content_category").Where(goqu.Ex{"id": req.Id}).Update(data)
	if _, err := query.Exec(); err != nil {
		log.Error("error updating category", zap.Error(err))
		return nil, status.Error(codes.Internal, "error updating category")
	}

	return &pb.UpdateCategoryResponse{}, nil
}

func (s *server) UpdateContentType(ctx context.Context, req *pb.ContentType) (*pb.UpdateContentTypeResponse, error) {
	log := clogger.FromContext(ctx)

	if req.Id == 0 {
		log.Error("error upon data validation: content type ID is blank!")
		return nil, status.Error(codes.Internal, "missing content type ID")
	}
	if req.Name == "" && req.Description == "" {
		log.Error("insufficient data provided")
		return nil, status.Error(codes.Internal, "bad request - insufficient data provided")
	}

	data := make(map[string]string)

	if req.Name != "" {
		data["name"] = req.Name
	}
	if req.Description != "" {
		data["description"] = req.Description
	}

	query := s.db.From("cms.content_type").Where(goqu.Ex{"id": req.Id}).Update(data)
	if _, err := query.Exec(); err != nil {
		log.Error("error updating content type", zap.Error(err))
		return nil, status.Error(codes.Internal, "error updating content type")
	}

	return &pb.UpdateContentTypeResponse{}, nil
}

// ToString converts int32 keys to strings
func ToString(n int32) string {
	return strconv.Itoa(int(n))
}
