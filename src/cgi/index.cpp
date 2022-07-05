#include <iostream>
#include <vector>
#include <string>

#include "cgicc/Cgicc.h"
#include "cgicc/HTTPHTMLHeader.h"
#include "cgicc/HTMLClasses.h"
#include "sqlite3.h"

#define DATABASE "/data/mydatabase.sqlite3"

using namespace std;
using namespace cgicc;

int main(int argc,  char **argv)
{
   try {
      Cgicc cgi;

        cout << HTTPContentHeader("application/json") << endl;
        cout << endl; // mandatory

	sqlite3 *db;
	sqlite3_stmt *stmt;
	char comma= ' ';
	int rc = sqlite3_open(DATABASE,&db);
	if( rc )
	{
		std::cerr <<  "Can't open database: " <<  sqlite3_errmsg(db) << std::endl;
		std::exit(rc);
	}

      cout << "[";


      sqlite3_prepare_v2(db,"select temperature, pressure, date, timesecond from data_bmp180",-1,&stmt, NULL);

      while ( (rc = sqlite3_step(stmt)) == SQLITE_ROW)
      {
        	cout << comma <<"\n\t {\"time in second\": \"" << sqlite3_column_int(stmt,3) <<  "\", \"date\": \"" << sqlite3_column_text(stmt,2) <<  "\", \"temperature\": \"" << sqlite3_column_double(stmt,0) << "\", \"pression\": \"" << sqlite3_column_double(stmt,1) << "\"}";
      		comma = ',';
	}

      sqlite3_finalize(stmt);
      sqlite3_close(db);

      cout << endl << "]";

   }
   catch(exception& e) {
      // handle any errors - omitted for brevity
   }
}
           
