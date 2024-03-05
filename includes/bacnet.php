<?php

require_once 'includes/status_messages.php';
require_once 'config.php';

function DisplayBACnet()
{   
    $status = new StatusMessages();

    if (!RASPI_MONITOR_ENABLED) {
        if (isset($_POST['savebacnetsettings']) || isset($_POST['applybacnetsettings'])) {
            $ret = saveBACnetConfig($status);
            if ($ret == false) {
                $status->addMessage('Error data', 'danger');
            } else {
                if (isset($_POST['applybacnetsettings'])) {
                    exec('sudo /etc/init.d/dct restart >/dev/null'); 
                }
            }
        }
    }

    exec("ip -o link show | awk -F': ' '{print $2}'", $tmp);
    sort($tmp);

    $interface_list = array();
    foreach ($tmp as $value) {
        if ($value == 'eth1' || $value == 'docker0' ||  $value == 'lo' ||
            strstr($value, 'veth') != NULL)
            continue;

        $interface_list["$value"] = $value;
    }

    echo renderTemplate("bacnet", compact('status', 'interface_list'));
}

function saveBACnetConfig($status)
{
    exec("sudo /usr/local/bin/uci set dct.bacnet.enabled=" . $_POST['enabled']);
    exec("sudo /usr/local/bin/uci set dct.bacnet.ifname=" .$_POST['ifname']);
    exec("sudo /usr/local/bin/uci set dct.bacnet.port=" .$_POST['port']);
    exec("sudo /usr/local/bin/uci set dct.bacnet.device_id=" .$_POST['device_id']);
    exec("sudo /usr/local/bin/uci set dct.bacnet.object_name=" .$_POST['object_name']);
    exec("sudo /usr/local/bin/uci commit dct");

    if ($_POST['enabled'] == "1") {
        if ($_POST['port'] == NULL || (int)($_POST['port']) > 65535 || (int)($_POST['device_id']) > 65535) {
            return false;
        }
    }
    
    $status->addMessage('BACnet configuration updated ', 'success');
    return true;
}

