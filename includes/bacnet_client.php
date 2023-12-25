<?php

require_once 'includes/status_messages.php';
require_once 'config.php';

function DisplayBACnetClient()
{   
    $status = new StatusMessages();

    if (!RASPI_MONITOR_ENABLED) {
        if (isset($_POST['savebacclisettings']) || isset($_POST['applybacclisettings'])) {
            $ret = saveBACnetClientConfig($status);
            if ($ret == false) {
                $status->addMessage('Error data', 'danger');
            } else {
                if (isset($_POST['applybacclisettings'])) {
                    exec('sudo /etc/init.d/dct restart >/dev/null'); 
                }
            }
        }
    }

    if ( isset($_POST['upload']) ) {
        if (strlen($_FILES['upload_file']['name']) > 0) {
            if (is_uploaded_file($_FILES['upload_file']['tmp_name'])) {
                save_import_file('baccli', $status, $_FILES['upload_file']);
            } else {
                $status->addMessage('fail to upload file', 'danger');
            }
        }
    }

    echo renderTemplate("bacnet_client", compact('status'));
}

function saveBACnetClientConfig($status)
{
    exec("sudo /usr/local/bin/uci set dct.bacnet_client.enabled=" . $_POST['enabled']);
    exec("sudo /usr/local/bin/uci set dct.bacnet_client.ip_address=" .$_POST['ip_address']);
    exec("sudo /usr/local/bin/uci set dct.bacnet_client.port=" .$_POST['port']);
    exec("sudo /usr/local/bin/uci set dct.bacnet_client.device_id=" .$_POST['device_id']);
    exec("sudo /usr/local/bin/uci set dct.bacnet_client.name=" .$_POST['name']);

    $data = $_POST['table_data'];
    $arr = json_decode($data, true);
    $i = 0;

    exec("sudo /usr/sbin/uci_get_count dct baccli", $count);

    if ($count[0] == null || strlen($count[0]) <= 0) {
        $count[0] = 0;
    }

    foreach ($arr as $list=>$things) {
        if (is_array($things)) {
            exec("sudo /usr/local/bin/uci delete dct.@baccli[$i]");
            exec("sudo /usr/local/bin/uci add dct baccli");
            foreach ($things as $key=>$val) {
                exec("sudo /usr/local/bin/uci set dct.@baccli[$i].$key='$val'");
            }
        }
        $i++;
    }

    if (number_format($count[0]) > $i) {
        for ($j = $i; $j < number_format($count[0]); $j++) {
            exec("sudo /usr/local/bin/uci delete dct.@baccli[$i]");
        }
    }

    exec("sudo /usr/local/bin/uci commit dct");

    if ($_POST['enabled'] == "1") {
        if ($_POST['port'] == NULL || (int)($_POST['port']) > 65535 || (int)($_POST['device_id']) > 65535) {
            return false;
        }
    }
    
    $status->addMessage('BACnet Client configuration updated ', 'success');
    return true;
}

