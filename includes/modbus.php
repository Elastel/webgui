<?php

require_once 'includes/status_messages.php';
require_once 'config.php';

/**
 * Displays info about the RaspAP project
 */
function DisplayModbus()
{
    $status = new StatusMessages();

    if (!RASPI_MONITOR_ENABLED) {
        if (isset($_POST['savemodbussettings']) || isset($_POST['applymodbussettings'])) {
            saveModbusConfig($status);
            
            if (isset($_POST['applymodbussettings'])) {
                sleep(2);
                exec('sudo /etc/init.d/dct restart > /dev/null');
            }
        }
    }

    echo renderTemplate("modbus", compact('status'));
}

function saveModbusConfig($status)
{
    $data = $_POST['table_data'];
    $arr = json_decode($data, true);
    $i = 0;
    $data_type_value = array("Unsigned 16Bits AB", "Unsigned 16Bits BA", "Signed 16Bits AB", "Signed 16Bits BA",
    "Unsigned 32Bits ABCD", "Unsigned 32Bits BADC", "Unsigned 32Bits CDAB", "Unsigned 32Bits DCBA",
    "Signed 32Bits ABCD", "Signed 32Bits BADC", "Signed 32Bits CDAB", "Signed 32Bits DCBA",
    "Float ABCD", "Float BADC", "Float CDAB", "Float DCBA");

    exec("sudo /usr/sbin/uci_get_count dct modbus", $count);

    if ($count[0] == null || strlen($count[0]) <= 0) {
        $count[0] = 0;
    }

    foreach ($arr as $list=>$things) {
        if (is_array($things)) {
            exec("sudo /usr/local/bin/uci delete dct.@modbus[$i]");
            exec("sudo /usr/local/bin/uci add dct modbus");
            foreach ($things as $key=>$val) {
                if ($key == "data_type") {
                    $data_type_num = array_search($val, $data_type_value);
                    exec("sudo /usr/local/bin/uci set dct.@modbus[$i].$key=$data_type_num");
                } else if ($key == "enabled") {
                    if ($val == "true") {
                        exec("sudo /usr/local/bin/uci set dct.@modbus[$i].$key=1");
                    } else {
                        exec("sudo /usr/local/bin/uci set dct.@modbus[$i].$key=0");
                    }
                } else {
                    exec("sudo /usr/local/bin/uci set dct.@modbus[$i].$key='$val'");
                }  
            }
        }
        $i++;
    }

    if (number_format($count[0]) > $i) {
        for ($j = $i; $j < number_format($count[0]); $j++) {
            exec("sudo /usr/local/bin/uci delete dct.@modbus[$i]");
        }
    }

    exec('sudo /usr/local/bin/uci commit dct');

    $status->addMessage('dct configuration updated ', 'success');
    return true;
}
