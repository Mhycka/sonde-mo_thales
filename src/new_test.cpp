#include <iostream>
#include <fstream>
#include <chrono>
#include <thread>
#include <ctime>
#include <csignal>
#include <cstring>
#include "sqlite3.h"

#define DATABASE "/data/mydatabase.sqlite3"
#define PRESSURE "/sys/bus/iio/devices/iio:device0/in_pressure_input"
#define TEMPERATURE "/sys/bus/iio/devices/iio:device0/in_temp_input"

using namespace std;


void signalHandler( int signum ) {
   cout << "Interrupt signal (" << signum << ") received.\n";

   exit(signum);
}

int main()
{
    signal(SIGINT, signalHandler);


    sqlite3 *db;
    int rc;
    char *errmsg;
    sqlite3_stmt *stmt;
    float temp, pressure;

    rc = sqlite3_open(DATABASE, &db);
    if( rc )
    {
        std::cerr <<  "Can't open database: " <<  sqlite3_errmsg(db) << std::endl;
        std::exit(rc);
    }


    while(true)
    {
            std::ifstream inputTemp(TEMPERATURE);
            inputTemp >> temp;

            std::ifstream inputPress(PRESSURE);
            inputPress >> pressure;

            char* sql = "insert into data_bmp180 (id, date, temperature, pressure) values(NULL, datetime('now','localtime'), round(?,2), round(?,2));";
            rc = sqlite3_prepare(db, sql, -1, &stmt, NULL);
            if( rc != SQLITE_OK )
            {
                std::cerr <<  "SQL prepare error "  << std::endl;
                std::exit(rc);
            }

            rc = sqlite3_bind_double(stmt, 1, temp);
            rc = sqlite3_bind_double(stmt, 2, pressure);

            rc = sqlite3_step(stmt);
            if ( rc != SQLITE_DONE)
            {
                std::cerr << "SQL step error " << std::endl;
                std::exit(rc);
            }


            std::this_thread::sleep_for(std::chrono::milliseconds(1500));
    }
    return 0;
}
