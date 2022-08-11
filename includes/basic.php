<?php

require_once 'includes/status_messages.php';
require_once 'config.php';

function DisplayBasic()
{   
    $status = new StatusMessages();

    if (!RASPI_MONITOR_ENABLED) {
        if (isset($_POST['savebasicsettings']) || isset($_POST['applybasicsettings'])) {
            saveBasicConfig($status);  
            
            if (isset($_POST['applybasicsettings'])) {
                sleep(2);
                exec('sudo /etc/init.d/dct restart > /dev/null');
            }
        }
    }

    echo renderTemplate("basic", compact('status'));
}

function saveBasicConfig($status)
{

    $return = 1;
    $error = array();

    exec("sudo /usr/local/bin/uci set dct.basic.enabled=" .$_POST['enabled']);
    if ($_POST['enabled'] == "1") {
        exec("sudo /usr/local/bin/uci set dct.basic.collect_period=" .$_POST['collect_period']);
        exec("sudo /usr/local/bin/uci set dct.basic.report_period=" .$_POST['report_period']);
        if ($_POST['cache_enabled'] == "1") {
            exec("sudo /usr/local/bin/uci set dct.basic.cache_enabled=" .$_POST['cache_enabled']);
        } else {
            exec("sudo /usr/local/bin/uci set dct.basic.cache_enabled=0");
        }
        exec("sudo /usr/local/bin/uci set dct.basic.cache_day=" .$_POST['cache_day']);
        if ($_POST['minute_enabled'] == "1") {
            exec("sudo /usr/local/bin/uci set dct.basic.minute_enabled=" .$_POST['minute_enabled']);
        } else {
            exec("sudo /usr/local/bin/uci set dct.basic.minute_enabled=0");
        }
        exec("sudo /usr/local/bin/uci set dct.basic.minute_period=" .$_POST['minute_period']);

        if ($_POST['hour_enabled'] == "1") {
            exec("sudo /usr/local/bin/uci set dct.basic.hour_enabled=" .$_POST['hour_enabled']);
        } else {
            exec("sudo /usr/local/bin/uci set dct.basic.hour_enabled=0");
        }
        if ($_POST['day_enabled'] == "1") {
            exec("sudo /usr/local/bin/uci set dct.basic.day_enabled=" .$_POST['day_enabled']);
        } else {
            exec("sudo /usr/local/bin/uci set dct.basic.day_enabled=0");
        }
    }
    

    exec("sudo /usr/local/bin/uci commit dct");

    $status->addMessage('dct configuration updated ', 'success');
    return true;
 
}

