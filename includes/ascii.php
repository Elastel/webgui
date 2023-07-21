<?php

require_once 'includes/status_messages.php';
require_once 'config.php';

/**
 * Displays info about the RaspAP project
 */
function DisplayAscii()
{
    $status = new StatusMessages();

    if (!RASPI_MONITOR_ENABLED) {
        if (isset($_POST['saveasciisettings']) || isset($_POST['applyasciisettings'])) {
            saveAsciiConfig($status);
            
            if (isset($_POST['applyasciisettings'])) {
                sleep(2);
                exec('sudo /etc/init.d/dct restart > /dev/null');
            }
        }
    }

    echo renderTemplate("ascii", compact('status'));
}

function saveAsciiConfig($status)
{
    $data = $_POST['table_data'];
    $arr = json_decode($data, true);
    $i = 0;

    exec("sudo /usr/sbin/uci_get_count dct ascii", $count);

    if ($count[0] == null || strlen($count[0]) <= 0) {
        $count[0] = 0;
    }

    foreach ($arr as $list=>$things) {
        if (is_array($things)) {
            exec("sudo /usr/local/bin/uci delete dct.@ascii[$i]");
            exec("sudo /usr/local/bin/uci add dct ascii");
            foreach ($things as $key=>$val) {
                if ($key == "enabled") {
                    if ($val == "true") {
                        exec("sudo /usr/local/bin/uci set dct.@ascii[$i].$key=1");
                    } else {
                        exec("sudo /usr/local/bin/uci set dct.@ascii[$i].$key=0");
                    }
                } else {
                    exec("sudo /usr/local/bin/uci set dct.@ascii[$i].$key='$val'");
                }  
            }
        }
        $i++;
    }

    if (number_format($count[0]) > $i) {
        for ($j = $i; $j < number_format($count[0]); $j++) {
            exec("sudo /usr/local/bin/uci delete dct.@ascii[$i]");
        }
    }

    exec('sudo /usr/local/bin/uci commit dct');

    $status->addMessage('dct configuration updated ', 'success');
    return true;
}
