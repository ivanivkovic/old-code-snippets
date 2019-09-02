#include "database.h"

Database::Database(string server, string user, string password, string database)
{
	_conn = mysql_init(NULL);

	if(_conn)
	{
		if(!mysql_real_connect(_conn, server.c_str(), user.c_str(), password.c_str(), database.c_str(), 0, NULL, 0))
		{
			log_last_error();
		}
		else
		{
			_connected = true;
		}
	}
	else
	{
		_last_error = "MySQL initialization failed! Is your MySQL connector correctly installed?";
	}
}

Database::Database(string server, string user, string password)
{
	_conn = mysql_init(NULL);

	if(_conn)
	{
		if(!mysql_real_connect(_conn, server.c_str(), user.c_str(), password.c_str(), NULL, 0, NULL, 0))
		{
			log_last_error();
		}
		else
		{
			_connected = true;
		}
	}
	else
	{
		_last_error = "MySQL initialization failed! Is your MySQL connector correctly installed?";
	}
}

Database::~Database()
{
	mysql_close(_conn);
}

void Database::log_last_error()
{
	_last_error = mysql_error(_conn);
	_last_errno = mysql_errno(_conn);
}

string Database::last_error()
{
	return _last_error;
}

unsigned int Database::last_errno()
{
	return _last_errno;
}

bool Database::connected()
{
	return _connected;
}

bool Database::execute(string query)
{
	if(!mysql_query(_conn, query.c_str()))
	{
		return true;
	}
	else
	{
		log_last_error();
		return false;
	}
}

vector<MYSQL_ROW> Database::fetch_by_query(string query)
{
	MYSQL_RES *result;
	MYSQL_ROW row;

	vector<MYSQL_ROW> rows = {};

	if(mysql_query(_conn, query.c_str()))
	{
		log_last_error();
	}
	else
	{
    		result = mysql_store_result(_conn);

		while ((row = mysql_fetch_row(result)) != NULL)
    		{
			rows.push_back(row);
		}

		mysql_free_result(result);
	}

	return rows;
}
