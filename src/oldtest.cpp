#include <iostream>
#include <fstream>
#include <chrono>
#include <thread>
#include <ctime>
#include <cstring>

#define PRESSURE "/sys/bus/iio/devices/iio:device0/in_pressure_input"
#define TEMPERATURE "/sys/bus/iio/devices/iio:device0/in_temp_input"

using namespace std;

int main() {

	float temp;
	float pressure;


	while(1){
		time_t now = time(0);
		tm *ltm = localtime(&now);
		string dt  = to_string( 5+ltm->tm_hour) +  ':' + to_string(30+ltm->tm_min) + ':' + to_string(ltm->tm_sec);

		std::ifstream inputTemp(TEMPERATURE);
		inputTemp >> temp;
		temp  = temp/1000;

		std::ifstream inputPress(PRESSURE);
		inputPress >> pressure;

		cout << dt<< endl;;
		cout << "La temperature : " << temp << endl;
		cout << "La pression    : " << pressure << endl;
		cout << endl;

		std::this_thread::sleep_for(std::chrono::milliseconds(2000));
	}
	return 0;
}
