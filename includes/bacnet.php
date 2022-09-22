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

    echo renderTemplate("bacnet", compact('status'));
}

function saveBACnetConfig($status)
{
    exec("sudo /usr/local/bin/uci set dct.bacnet.enabled=" . $_POST['enabled']);
    exec("sudo /usr/local/bin/uci set dct.bacnet.port=" .$_POST['port']);
    exec("sudo /usr/local/bin/uci set dct.bacnet.device_id=" .$_POST['device_id']);
    exec("sudo /usr/local/bin/uci set dct.bacnet.object_name=" .$_POST['object_name']);
    exec("sudo /usr/local/bin/uci commit dct");

    if ($_POST['port'] == NULL || (int)($_POST['port']) > 65535 || (int)($_POST['device_id']) > 65535) {
        return false;
    }

    $status->addMessage('BACnet configuration updated ', 'success');
    return true;
}

