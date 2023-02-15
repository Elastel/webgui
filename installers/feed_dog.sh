#!/bin/sh

while true ;do
	echo 1 > /sys/class/gpio/gpio25/value
	sleep 0.02
	echo 0 > /sys/class/gpio/gpio25/value
	sleep 2
done
