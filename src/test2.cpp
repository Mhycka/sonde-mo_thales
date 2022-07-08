#include <iostream>
#include <fstream>
#include <chrono>
#include <thread>
#include <ctime>
#include <csignal>
#include <cstring>
#include "sqlite3.h"

using namespace std;

#define DATABASE "/data/mydatabase.sqlite3"
#define PRESSURE "/sys/bus/iio/devices/iio:device0/in_pressure_input"
#define TEMPERATURE "/sys/bus/iio/devices/iio:device0/in_temp_input"
#define FREQUENCY "/data/config_bmp180todb"

sqlite3 *db;
void signalHandler( int signum ) {
   cout << "Interrupt signal (" << signum << ") received.\n";
   sqlite3_close(db);
   exit(signum);
}

int main()
{
    signal(SIGINT, signalHandler);

    int rc;
    char *errmsg;
    sqlite3_stmt *stmt;
    float temp, pressure;
    int freq;

    rc = sqlite3_open(DATABASE, &db);
    if( rc )
    {
        std::cerr <<  "Can't open database: " <<  sqlite3_errmsg(db) << std::endl;
        std::exit(rc);
    }


    while(true)
    {
	    std::ifstream inputFreq(FREQUENCY);
	    inputFreq >> freq;
	    if(freq == 0)
	    	freq = 60000;

            std::ifstream inputTemp(TEMPERATURE);
            inputTemp >> temp;
	    temp = temp/1000;

            std::ifstream inputPress(PRESSURE);
            inputPress >> pressure;
	    pressure = pressure * 10;
            char* sql = "insert into data_bmp180 (temperature, pressure, date, timesecond) values(round(?,2), round(?,2), datetime('now','localtime'), strftime('%s'));";
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

            std::this_thread::sleep_for(std::chrono::milliseconds(freq));
    }
    return 0;
}
