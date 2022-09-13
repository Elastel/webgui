<?php

require_once 'includes/status_messages.php';
require_once 'config.php';

/**
 * Displays info about the RaspAP project
 */
function DisplayIO()
{
    $status = new StatusMessages();
    $model = getModel();

    if (!RASPI_MONITOR_ENABLED) {
        if (isset($_POST['saveIOsettings']) || isset($_POST['applyIOsettings'])) {
            saveIOConfig($status, $model);
            
            if (isset($_POST['applyIOsettings'])) {
                sleep(2);
                exec('sudo /etc/init.d/dct restart > /dev/null');
            }
        }
    }

    echo renderTemplate("io", compact('status'), "model");
}

function saveADC($status)
{
    $data = $_POST['tableDataADC'];
    $arr = json_decode($data, true);
    $i = 0;
    $cap_type_value = array("4-20mA", "0-10V");

    exec("sudo /usr/sbin/uci_get_count adc", $count);

    if ($count[0] == null || strlen($count[0]) <= 0) {
        $count[0] = 0;
    }

    foreach ($arr as $list=>$things) {
        if (is_array($things)) {
            exec("sudo /usr/local/bin/uci delete dct.@adc[$i]");
            exec("sudo /usr/local/bin/uci add dct adc");
            foreach ($things as $key=>$val) {
                if ($key == "cap_type") {
                    $cap_type_num = array_search($val, $cap_type_value);
                    exec("sudo /usr/local/bin/uci set dct.@adc[$i].$key=$cap_type_num");
                } else if ($key == "enabled") {
                    if ($val == "true") {
                        exec("sudo /usr/local/bin/uci set dct.@adc[$i].$key=1");
                    } else {
                        exec("sudo /usr/local/bin/uci set dct.@adc[$i].$key=0");
                    }
                } else {
                    exec("sudo /usr/local/bin/uci set dct.@adc[$i].$key='$val'");
                }  
            }
        }
        $i++;
    }

    if (number_format($count[0]) > $i) {
        for ($j = $i; $j < number_format($count[0]); $j++) {
            exec("sudo /usr/local/bin/uci delete dct.@adc[$i]");
        }
    }
}

function saveDI($status)
{
    $data = $_POST['tableDataDI'];
    $arr = json_decode($data, true);
    $i = 0;
    $mode_value = array("Counting Mode", "Status Mode");
    $method_value = array("Rising Edge", "Falling Edge");

    exec("sudo /usr/sbin/uci_get_count di", $count);

    if ($count[0] == null || strlen($count[0]) <= 0) {
        $count[0] = 0;
    }

    foreach ($arr as $list=>$things) {
        if (is_array($things)) {
            exec("sudo /usr/local/bin/uci delete dct.@di[$i]");
            exec("sudo /usr/local/bin/uci add dct di");
            foreach ($things as $key=>$val) {
                if ($key == "mode") {
                    $mode_num = array_search($val, $mode_value);
                    exec("sudo /usr/local/bin/uci set dct.@di[$i].$key=$mode_num");
                } else if ($key == "count_method") {
                    $method_num = array_search($val, $method_value);
                    exec("sudo /usr/local/bin/uci set dct.@di[$i].$key=$method_num");
                } else if ($key == "enabled") {
                    if ($val == "true") {
                        exec("sudo /usr/local/bin/uci set dct.@di[$i].$key=1");
                    } else {
                        exec("sudo /usr/local/bin/uci set dct.@di[$i].$key=0");
                    }
                } else {
                    exec("sudo /usr/local/bin/uci set dct.@di[$i].$key='$val'");
                }  
            }
        }
        $i++;
    }

    if (number_format($count[0]) > $i) {
        for ($j = $i; $j < number_format($count[0]); $j++) {
            exec("sudo /usr/local/bin/uci delete dct.@di[$i]");
        }
    }
}

function saveDO($status)
{
    $data = $_POST['tableDataDO'];
    $arr = json_decode($data, true);
    $i = 0;
    $status_value = array("Open", "Close");

    exec("sudo /usr/sbin/uci_get_count do", $count);

    if ($count[0] == null || strlen($count[0]) <= 0) {
        $count[0] = 0;
    }

    foreach ($arr as $list=>$things) {
        if (is_array($things)) {
            exec("sudo /usr/local/bin/uci delete dct.@do[$i]");
            exec("sudo /usr/local/bin/uci add dct do");
            foreach ($things as $key=>$val) {
                if ($key == "init_status") {
                    $status_num = array_search($val, $status_value);
                    exec("sudo /usr/local/bin/uci set dct.@do[$i].$key=$status_num");
                } else if ($key == "enabled") {
                    if ($val == "true") {
                        exec("sudo /usr/local/bin/uci set dct.@do[$i].$key=1");
                    } else {
                        exec("sudo /usr/local/bin/uci set dct.@do[$i].$key=0");
                    }
                } else {
                    exec("sudo /usr/local/bin/uci set dct.@do[$i].$key='$val'");
                }  
            }
        }
        $i++;
    }

    if (number_format($count[0]) > $i) {
        for ($j = $i; $j < number_format($count[0]); $j++) {
            exec("sudo /usr/local/bin/uci delete dct.@do[$i]");
        }
    }
}

function saveIOConfig($status, $model)
{
    if ($model == "EG500") {
        saveADC($status);
    }

    saveDI($status);
    saveDO($status);
    
    exec('sudo /usr/local/bin/uci commit dct');

    $status->addMessage('dct configuration updated ', 'success');
    return true;
}
