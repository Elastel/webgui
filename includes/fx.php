<?php

require_once 'includes/status_messages.php';
require_once 'config.php';

/**
 * Displays info about the RaspAP project
 */
function DisplayFx()
{
    $status = new StatusMessages();

    if (!RASPI_MONITOR_ENABLED) {
        if (isset($_POST['savefxsettings']) || isset($_POST['applyfxsettings'])) {
            saveFxConfig($status);
            
            if (isset($_POST['applyfxsettings'])) {
                sleep(2);
                exec('sudo /etc/init.d/dct restart > /dev/null');
            }
        }
    }

    echo renderTemplate("fx", compact('status'));
}

function saveFxConfig($status)
{
    $data = $_POST['table_data'];
    $arr = json_decode($data, true);
    $i = 0;

    $reg_type_value = array("X", "Y", "M", "S", "D");
    $data_type_value = array("Bit", "Byte", "Word", "DWord", "Real");

    exec("sudo /usr/sbin/uci_get_count dct fx", $count);

    if ($count[0] == null || strlen($count[0]) <= 0) {
        $count[0] = 0;
    }

    foreach ($arr as $list=>$things) {
        if (is_array($things)) {
            exec("sudo /usr/local/bin/uci delete dct.@fx[$i]");
            exec("sudo /usr/local/bin/uci add dct fx");
            foreach ($things as $key=>$val) {
                if ($key == "reg_type") {
                    $reg_type_num = array_search($val, $reg_type_value);
                    exec("sudo /usr/local/bin/uci set dct.@fx[$i].$key=$reg_type_num");
                } else if ($key == "data_type") {
                    $data_type_num = array_search($val, $data_type_value);
                    exec("sudo /usr/local/bin/uci set dct.@fx[$i].$key=$data_type_num");
                } else if ($key == "enabled") {
                    if ($val == "true") {
                        exec("sudo /usr/local/bin/uci set dct.@fx[$i].$key=1");
                    } else {
                        exec("sudo /usr/local/bin/uci set dct.@fx[$i].$key=0");
                    }
                } else {
                    exec("sudo /usr/local/bin/uci set dct.@fx[$i].$key='$val'");
                }  
            }
        }
        $i++;
    }

    if (number_format($count[0]) > $i) {
        for ($j = $i; $j < number_format($count[0]); $j++) {
            exec("sudo /usr/local/bin/uci delete dct.@fx[$i]");
        }
    }

    exec('sudo /usr/local/bin/uci commit dct');

    $status->addMessage('dct configuration updated ', 'success');
    return true;
}
