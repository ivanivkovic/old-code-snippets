#include <string>
#include <vector>
#include <mysql/mysql.h>

using namespace std;

class Database
{
	public:
		Database(string server, string user, string password);
		Database(string server, string user, string password, string database);
		~Database();
		
		string last_error();
		unsigned int last_errno();
		void log_last_error();
		vector<MYSQL_ROW> fetch_by_query(string query);
		bool execute(string query);
		bool connected();

	private:
		MYSQL *_conn;
		string _last_error;
		unsigned int _last_errno;
		bool _connected = false;
};
