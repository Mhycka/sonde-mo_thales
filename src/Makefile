CFLAGS = -O2 -mfpu=vfp -mfloat-abi=hard -w -g
LFLAGS = -lcgicc -lsqlite3 
CROSS_COMPILE = /home/dev/buildroot/buildroot-2022.02.2/output/host/bin/arm-buildroot-linux-uclibcgnueabihf-
CC = $(CROSS_COMPILE)gcc
CPP = $(CROSS_COMPILE)g++
TARGETS = $(patsubst %.cpp,%,$(wildcard *.cpp))

all: $(TARGETS)

%: %.cpp
	$(CPP) $(CFLAGS) $(LFLAGS) -o $@ $<

%: %.c
	$(CC) $(CFLAGS) $(LFLAGS) -o $@ $<

clean:
	$(RM) $(TARGETS)

scp: $(TARGETS)
	scp $(TARGETS) root@10.30.1.110:/tmp

test: scp
	ssh root@10.30.1.110 /tmp/test2

install: $(TARGETS)
	echo TODO
	#cp $(TARGETS) ../monOverlay/usr/bin/
	#make -C ../buildrootsdfsd all

