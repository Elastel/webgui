#!/usr/bin/expect -f

set num [lindex $argv 0]
set username [lindex $argv 1]
set password [lindex $argv 2]
set interval [lindex $argv 3]

spawn /usr/sbin/noip2 -C -c /etc/ddns.conf

expect "By typing the number associated with it.*"
send "0\r"

expect "Please enter the login/email string for no-ip.com"
send "$username\r"

expect "Please enter the password for user 'info@elastel.com'"
send "$password\r"

expect "Please enter an update interval:*"
send "$interval\r"

expect "Do you wish to run something at successful update?*"
send "\r"

interact
