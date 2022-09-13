<?php

require_once 'includes/status_messages.php';
require_once 'config.php';

function DisplayDDNS()
{   
    $status = new StatusMessages();

    if (!RASPI_MONITOR_ENABLED) {
        if (isset($_POST['saveddnssettings']) || isset($_POST['applyddnssettings'])) {
            $ret = saveDDNSConfig($status);
            if ($ret == false) {
                $status->addMessage('Error data', 'danger');
            } else {
                if (isset($_POST['applyddnssettings'])) {
                    if ($_POST['enabled'] == '0') {
                        exec('sudo /etc/init.d/ddns stop');
                    } else {
                        exec("ip route | grep default | awk {'print $5'}", $interface);
                        $total_interface = sizeof($interface);
                        for ($i = 0; $i < $total_interface; $i++) {
                            if ($interface[$i] == $_POST['interface']) {
                                break;
                            }
                        }

                        if ($_POST['interval'] == NULL) {
                            $interval = 30;
                        } else {
                            $interval = $_POST['interval'];
                        }

                        exec('sudo rm /etc/ddns.conf');
                        if ($total_interface > 0) {
                            exec('sudo /etc/init.d/ddns stop');
                            exec('sudo /usr/sbin/set_noip.sh ' . $i . ' ' . $_POST['username'] . ' ' . $_POST['password']. ' ' . $interval . ' > /dev/null');
                            sleep(2);
                            if (!is_file('/etc/ddns.conf')) {
                                $status->addMessage("Failed to restart DDNS", 'danger');
                            } else {
                                //exec('sudo /usr/sbin/noip2 -c /etc/ddns.conf');
                                exec('sudo /etc/init.d/ddns restart');
                            }
                        } else {
                            $status->addMessage("No network, failed to restart DDNS.", 'danger');
                        }  
                    }   
                }
            }
        }
    }

    exec("sudo /usr/sbin/noip2 -S -c /etc/ddns.conf", $hostname);

    echo renderTemplate("ddns", compact('status', 'hostname'));
}

function saveDDNSConfig($status)
{

    $return = 1;
    $error = array();

    exec("sudo /usr/local/bin/uci set ddns.ddns.enabled=" . $_POST['enabled']);
    if ($_POST['enabled'] == "1") {
        exec("sudo /usr/local/bin/uci set ddns.ddns.interface=" .$_POST['interface']);
        exec("sudo /usr/local/bin/uci set ddns.ddns.server_type=" .$_POST['server_type']);
        exec("sudo /usr/local/bin/uci set ddns.ddns.username=" .$_POST['username']);
        exec("sudo /usr/local/bin/uci set ddns.ddns.password=" .$_POST['password']);
        exec("sudo /usr/local/bin/uci set ddns.ddns.interval=" .$_POST['interval']);
        if ($_POST['username'] == NULL || $_POST['password'] == NULL) {
            return false;
        }
    }
    
    exec("sudo /usr/local/bin/uci commit ddns");

    $status->addMessage('DDNS configuration updated ', 'success');
    return true;
}

