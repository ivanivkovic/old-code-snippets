package main

import (
	"database/sql"
	"math"

	"go.uber.org/zap"
	"golang.org/x/net/context"

	"strconv"

	clogger "github.com/IDBMobileTechnology/idb-mobile/common/lib/go/logger"
	pb "github.com/IDBMobileTechnology/idb-mobile/domain/web/cms/website/proto"
	status "google.golang.org/grpc/status"

	"google.golang.org/grpc/codes"
	"gopkg.in/doug-martin/goqu.v4"
	_ "gopkg.in/doug-martin/goqu.v4/adapters/mysql"
)

type server struct {
	db *goqu.Database
}

func (s *server) CreateWebsite(ctx context.Context, req *pb.CreateWebsiteRequest) (*pb.CreateWebsiteResponse, error) {
	log := clogger.FromContext(ctx)

	if req.Name == "" {
		log.Error("error upon data validation: website name is blank!")
		return nil, status.Error(codes.Internal, "missing website name")

	} else if req.Domain == "" {
		log.Error("error upon data validation: website domain is blank!")
		return nil, status.Error(codes.Internal, "missing website domain")
	}

	query := s.db.From("cms.website").Insert(goqu.Record{
		"name":   req.Name,
		"domain": req.Domain,
	})
	insert, err := query.Exec()
	if err != nil {
		log.Error("error inserting website", zap.Error(err))
		return nil, status.Error(codes.Internal, "error inserting website")
	}

	id, err := insert.LastInsertId()
	if err != nil {
		log.Error("error fetching insert ID", zap.Error(err))
		return nil, status.Error(codes.Internal, "error fetching insert ID")
	}

	return &pb.CreateWebsiteResponse{Id: int32(id)}, nil
}

// CreateWebsiteContent links the content-service to the website.
// Optionally, a new name and description can be applied to the website content.
func (s *server) CreateWebsiteContent(ctx context.Context, req *pb.CreateWebsiteContentRequest) (*pb.CreateWebsiteContentResponse, error) {
	log := clogger.FromContext(ctx)

	if req.WebsiteId == 0 {
		log.Error("error upon data validation: website id is blank!")
		return nil, status.Error(codes.Internal, "missing website id")
	} else if req.CategoryId == 0 {
		log.Error("error upon data validation: website category id is blank!")
		return nil, status.Error(codes.Internal, "missing website category id")
	} else if req.ContentId == 0 {
		log.Error("error upon data validation: content id is blank!")
		return nil, status.Error(codes.Internal, "missing content id")
	}

	query := s.db.From("cms.website_content").Insert(goqu.Record{
		"website_id":  req.WebsiteId,
		"category_id": req.CategoryId,
		"content_id":  req.ContentId,
		"name":        req.Name,
		"description": req.Description,
	})

	insert, err := query.Exec()
	if err != nil {
		log.Error("error inserting website content", zap.Error(err))
		return nil, status.Error(codes.Internal, "error inserting website content")
	}

	id, err := insert.LastInsertId()
	if err != nil {
		log.Error("error fetching insert ID", zap.Error(err))
		return nil, status.Error(codes.Internal, "error fetching insert ID")
	}

	return &pb.CreateWebsiteContentResponse{Id: int32(id)}, nil
}

func (s *server) CreateWebsiteCategory(ctx context.Context, req *pb.CreateWebsiteCategoryRequest) (*pb.CreateWebsiteCategoryResponse, error) {
	log := clogger.FromContext(ctx)

	if req.Name == "" {
		log.Error("error upon data validation: website category name is blank!")
		return nil, status.Error(codes.Internal, "missing website category name")
	}

	query := s.db.From("cms.website_category").Insert(goqu.Record{
		"parent_id":   req.ParentId,
		"name":        req.Name,
		"description": req.Description,
	})
	insert, err := query.Exec()
	if err != nil {
		log.Error("error inserting website category", zap.Error(err))
		return nil, status.Error(codes.Internal, "error inserting website category")
	}

	id, err := insert.LastInsertId()
	if err != nil {
		log.Error("error fetching insert ID", zap.Error(err))
		return nil, status.Error(codes.Internal, "error fetching insert ID")
	}

	return &pb.CreateWebsiteCategoryResponse{Id: int32(id)}, nil
}

func (s *server) GetWebsiteContent(ctx context.Context, req *pb.GetWebsiteContentRequest) (*pb.WebsiteContent, error) {
	responseData := &pb.WebsiteContent{}

	log := clogger.FromContext(ctx)

	if req.Id == 0 {
		log.Error("error upon data validation: website content ID is blank!")
		return nil, status.Error(codes.Internal, "missing website content ID")
	}

	query, _, _ := s.db.From("cms.website_content").Where(goqu.Ex{"id": req.Id}).ToSql()
	err := s.db.Db.QueryRowContext(ctx, query).Scan(
		&responseData.Id,
		&responseData.CategoryId,
		&responseData.ContentId,
		&responseData.WebsiteId,
		&responseData.Name,
		&responseData.Description,
	)
	if err != nil {
		if err == sql.ErrNoRows {
			log.Error("no rows in result set", zap.Error(err))
			return nil, status.Error(codes.NotFound, "no results")
		}

		log.Error("error querying websites", zap.Error(err))
		return nil, status.Error(codes.Internal, "error querying website content")
	}

	return responseData, nil
}

// GetWebsiteContents can receive either Id or any of the following: category id, website id.
// Multiple crtieria can be applied for a more specific search.
// If no parameters set, will return a list based on default paging.
func (s *server) GetWebsiteContents(ctx context.Context, req *pb.GetWebsiteContentsRequest) (*pb.GetWebsiteContentsResponse, error) {
	log := clogger.FromContext(ctx)

	selectCount := `SELECT COUNT(*) AS Count FROM cms.website_content `
	selectData := `SELECT cms.website_content.* FROM cms.website_content `
	where := ``

	//	Processing content by filters
	if req.CategoryId != 0 || req.WebsiteId != 0 {

		where += `WHERE `
		and := false

		if req.CategoryId != 0 {
			where += `category_id="` + ToString(req.CategoryId) + `" `
			and = true
		}

		if req.WebsiteId != 0 {
			if and == true {
				where += `AND `
			}
			where += `website_id="` + ToString(req.WebsiteId) + `" `
			and = true
		}
	}

	//Paging defaults
	if req.PageNum < 1 {
		req.PageNum = 1
	}
	if req.PageSize < 1 {
		req.PageSize = 10
	}

	pageOffset := (req.PageNum - 1) * req.PageSize
	limit := `LIMIT ` + ToString(req.PageSize) + ` OFFSET ` + ToString(pageOffset)
	count := 0.0

	//Getting data count for correct paging
	err := s.db.Db.QueryRowContext(ctx, selectCount+where).Scan(&count)
	if err != nil {
		if err == sql.ErrNoRows {
			log.Error("no rows in result set", zap.Error(err))
			return nil, status.Error(codes.NotFound, "not found")
		}

		log.Error("error querying website category", zap.Error(err))
		return nil, status.Error(codes.Internal, "error querying website content")
	}

	//Actual data query
	rows, err := s.db.Db.QueryContext(ctx, selectData+where+limit)
	if err != nil {
		log.Error("error querying website content", zap.Error(err))
		return nil, status.Error(codes.Internal, "error querying website content")
	}

	responseParent := &pb.GetWebsiteContentsResponse{}
	responseData := &pb.WebsiteContent{}

	for rows.Next() {
		err = rows.Scan(
			&responseData.Id,
			&responseData.CategoryId,
			&responseData.ContentId,
			&responseData.WebsiteId,
			&responseData.Name,
			&responseData.Description,
		)
		if err != nil {
			log.Error("error scanning results", zap.Error(err))
			return nil, status.Error(codes.Internal, "error scanning results")
		}

		responseParent.Results = append(responseParent.Results, responseData)
		responseData = &pb.WebsiteContent{}
	}
	if len(responseParent.Results) == 0 {
		log.Error("no rows in result set", zap.Error(err))
		return nil, status.Error(codes.NotFound, "no results")
	}

	d := float64(float64(count) / float64(req.PageSize))
	responseParent.PagesCount = int32(math.Ceil(d))

	return responseParent, nil
}

func (s *server) GetWebsiteCategory(ctx context.Context, req *pb.GetWebsiteCategoryRequest) (*pb.WebsiteCategory, error) {
	log := clogger.FromContext(ctx)

	responseData := &pb.WebsiteCategory{}

	if req.Id == 0 {
		log.Error("error upon data validation: website category ID is blank!")
		return nil, status.Error(codes.Internal, "missing website category ID")
	}

	query, _, _ := s.db.From("cms.website_category").Where(goqu.Ex{"id": req.Id}).ToSql()
	err := s.db.Db.QueryRowContext(ctx, query).Scan(&responseData.Id, &responseData.ParentId, &responseData.Name, &responseData.Description)
	if err != nil {
		if err == sql.ErrNoRows {
			log.Error("no rows in result set", zap.Error(err))
			return nil, status.Error(codes.NotFound, "no results")
		}

		log.Error("error querying website category", zap.Error(err))
		return nil, status.Error(codes.Internal, "error querying website category")
	}

	return responseData, nil
}

func (s *server) GetAllWebsiteCategories(ctx context.Context, req *pb.GetAllWebsiteCategoriesRequest) (*pb.GetAllWebsiteCategoriesResponse, error) {
	log := clogger.FromContext(ctx)

	responseParent := &pb.GetAllWebsiteCategoriesResponse{}
	responseData := &pb.WebsiteCategory{}

	query, _, _ := s.db.From("cms.website_category").ToSql()
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
		responseData = &pb.WebsiteCategory{}
	}
	if len(responseParent.Results) == 0 {
		log.Error("no rows in result set", zap.Error(err))
		return nil, status.Error(codes.NotFound, "no results")
	}

	return responseParent, nil
}

func (s *server) GetAllWebsites(ctx context.Context, req *pb.GetAllWebsitesRequest) (*pb.GetAllWebsitesResponse, error) {
	log := clogger.FromContext(ctx)

	responseParent := &pb.GetAllWebsitesResponse{}
	responseData := &pb.Website{}

	query, _, _ := s.db.From("cms.website").ToSql()
	rows, err := s.db.Db.Query(query)
	if err != nil {
		log.Error("error querying websites", zap.Error(err))
		return nil, status.Error(codes.Internal, "error querying websites")
	}

	for rows.Next() {
		err = rows.Scan(
			&responseData.Id,
			&responseData.Domain,
			&responseData.Name,
		)
		if err != nil {
			log.Error("error scanning database results", zap.Error(err))
			return nil, status.Error(codes.Internal, "error scanning database results")
		}

		responseParent.Results = append(responseParent.Results, responseData)
		responseData = &pb.Website{}
	}
	if len(responseParent.Results) == 0 {
		log.Error("no rows in result set", zap.Error(err))
		return nil, status.Error(codes.NotFound, "no results")
	}

	return responseParent, nil
}

func (s *server) GetWebsite(ctx context.Context, req *pb.GetWebsiteRequest) (*pb.Website, error) {
	log := clogger.FromContext(ctx)

	responseData := &pb.Website{}

	if req.Id == 0 {
		log.Error("error upon data validation: website ID is blank!")
		return nil, status.Error(codes.Internal, "missing website ID")
	}

	query, _, _ := s.db.From("cms.website").Where(goqu.Ex{"id": req.Id}).ToSql()
	err := s.db.Db.QueryRowContext(ctx, query).Scan(&responseData.Id, &responseData.Domain, &responseData.Name)
	if err != nil {
		if err == sql.ErrNoRows {
			log.Error("no rows in result set", zap.Error(err))
			return nil, status.Error(codes.NotFound, "no results")
		}

		log.Error("error querying websites", zap.Error(err))
		return nil, status.Error(codes.Internal, "error querying websites")
	}

	return responseData, nil
}

func (s *server) UpdateWebsite(ctx context.Context, req *pb.Website) (*pb.UpdateWebsiteResponse, error) {
	log := clogger.FromContext(ctx)

	if req.Id == 0 {
		log.Error("error upon data validation: website ID is blank!")
		return nil, status.Error(codes.Internal, "missing website ID")
	}
	if req.Name == "" && req.Domain == "" {
		log.Error("insufficient data provided")
		return nil, status.Error(codes.Internal, "bad request - insufficient data provided")
	}

	data := make(map[string]string)

	if req.Name != "" {
		data["name"] = req.Name
	}
	if req.Domain != "" {
		data["domain"] = req.Domain
	}

	query := s.db.From("cms.website").Where(goqu.Ex{"id": req.Id}).Update(data)
	if _, err := query.Exec(); err != nil {
		log.Error("error updating websites", zap.Error(err))
		return nil, status.Error(codes.Internal, "error updating websites")
	}

	return &pb.UpdateWebsiteResponse{}, nil
}

func (s *server) UpdateWebsiteCategory(ctx context.Context, req *pb.WebsiteCategory) (*pb.UpdateWebsiteCategoryResponse, error) {
	log := clogger.FromContext(ctx)

	if req.Id == 0 {
		log.Error("error upon data validation: website category ID is blank!")
		return nil, status.Error(codes.Internal, "missing website category ID")
	}
	if req.Name == "" && req.Description == "" && req.ParentId == 0 {
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

	query := s.db.From("cms.website_category").Where(goqu.Ex{"id": req.Id}).Update(data)
	if _, err := query.Exec(); err != nil {
		log.Error("error updating website category", zap.Error(err))
		return nil, status.Error(codes.Internal, "error updating website category")
	}

	return &pb.UpdateWebsiteCategoryResponse{}, nil
}

func (s *server) UpdateWebsiteContent(ctx context.Context, req *pb.WebsiteContent) (*pb.UpdateWebsiteContentResponse, error) {
	log := clogger.FromContext(ctx)

	if req.Id == 0 {
		log.Error("error upon data validation: content type ID is blank!")
		return nil, status.Error(codes.Internal, "missing website content ID")
	}
	if req.Name == "" && req.Description == "" && req.CategoryId == 0 && req.ContentId == 0 && req.WebsiteId == 0 {
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
	if req.CategoryId != 0 {
		data["category_id"] = ToString(req.CategoryId)
	}
	if req.ContentId != 0 {
		data["content_id"] = ToString(req.ContentId)
	}
	if req.WebsiteId != 0 {
		data["website_id"] = ToString(req.WebsiteId)
	}

	query := s.db.From("cms.website_content").Where(goqu.Ex{"id": req.Id}).Update(data)
	if _, err := query.Exec(); err != nil {
		log.Error("error updating website content", zap.Error(err))
		return nil, status.Error(codes.Internal, "error updating website content")
	}

	return &pb.UpdateWebsiteContentResponse{}, nil
}

// ToString converts int32 keys to strings
func ToString(n int32) string {
	return strconv.Itoa(int(n))
}
