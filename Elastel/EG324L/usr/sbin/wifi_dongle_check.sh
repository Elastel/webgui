#!/bin/bash

while true; do
	WIFI_DONGLE=$(lsusb | grep -c 0bda)
	if [ $WIFI_DONGLE == "1" ] ; then
		#echo "wifi dongle is up"
		if pgrep hostapd > /dev/null ; then
			echo "hostapd is running"
		else
			#echo "hostapd is not running"
			/etc/init.d/S50hostapd restart
			/etc/init.d/S80dnsmasq restart
		fi
	else
		#echo "wifi dongle is down"
		if pgrep hostapd > /dev/null ; then
			#echo "hostapd is running"
			/etc/init.d/S50hostapd stop
		#else
			#echo "hostapd is not running"
		fi
	fi
	
	sleep 1
done
