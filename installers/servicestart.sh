#!/bin/bash
# When wireless client AP or Bridge mode is enabled, this script handles starting
# up network services in a specific order and timing to avoid race conditions.

PATH=/usr/local/sbin:/usr/local/bin:/sbin:/bin:/usr/sbin:/usr/bin
NAME=raspapd
DESC="Service control for RaspAP"
CONFIGFILE="/etc/raspap/hostapd.ini"
DAEMONPATH="/lib/systemd/system/raspapd.service"
OPENVPNENABLED=$(pidof openvpn | wc -l)

positional=()
while [[ $# -gt 0 ]]
do
key="$1"

case $key in
    -i|--interface)
    interface="$2"
    shift # past argument
    shift # past value
    ;;
    -s|--seconds)
    seconds="$2"
    shift
    shift
    ;;
    -a|--action)
    action="$2"
    shift
    shift
    ;;
esac
done
set -- "${positional[@]}"

echo "Stopping network services..."
if [ $OPENVPNENABLED -eq 1 ]; then
    systemctl stop openvpn-client@client
fi
systemctl stop systemd-networkd
systemctl stop hostapd.service
systemctl stop dnsmasq.service
systemctl stop dhcpcd.service

if [ "${action}" = "stop" ]; then
    echo "Services stopped. Exiting."
    exit 0
fi

# if [ -f "$DAEMONPATH" ] && [ ! -z "$interface" ]; then
#     echo "Changing RaspAP Daemon --interface to $interface"
#     sed -i "s/\(--interface \)[[:alnum:]]*/\1$interface/" "$DAEMONPATH"
# fi

echo "Stopping systemd-networkd"
systemctl stop systemd-networkd

echo "Restarting eth0 interface..."
ip link set down eth1
ip link set up eth1

echo "Removing uap0 interface..."
iw dev uap0 del

echo "Enabling systemd-networkd"
systemctl start systemd-networkd
systemctl enable systemd-networkd

# Start services, mitigating race conditions
echo "Starting network services..."
systemctl start hostapd.service
sleep "${seconds}"

systemctl start dhcpcd.service
sleep "${seconds}"

systemctl start dnsmasq.service

if [ $OPENVPNENABLED -eq 1 ]; then
    systemctl start openvpn-client@client
fi

# @mp035 found that the wifi client interface would stop every 8 seconds
# for about 16 seconds. Reassociating seems to solve this
if [ "${config[WifiAPEnable]}" = 1 ]; then
    echo "Reassociating wifi client interface..."
    sleep "${seconds}"
    wpa_cli -i ${config[WifiManaged]} reassociate
fi

echo "RaspAP service start DONE"

