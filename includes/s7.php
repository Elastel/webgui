<?php

require_once 'includes/status_messages.php';
require_once 'config.php';

/**
 * Displays info about the RaspAP project
 */
function DisplayS7()
{
    $status = new StatusMessages();

    if (!RASPI_MONITOR_ENABLED) {
        if (isset($_POST['saves7settings']) || isset($_POST['applys7settings'])) {
            saveS7Config($status);
            
            if (isset($_POST['applys7settings'])) {
                sleep(2);
                exec('sudo /etc/init.d/dct restart > /dev/null');
            }
        }
    }

    if ( isset($_POST['upload']) ) {
        if (strlen($_FILES['upload_file']['name']) > 0) {
            if (is_uploaded_file($_FILES['upload_file']['tmp_name'])) {
                save_import_file('s7', $status, $_FILES['upload_file']);
            } else {
                $status->addMessage('fail to upload file', 'danger');
            }
        }
    }


    echo renderTemplate("s7", compact('status'));
}

function saveS7Config($status)
{
    $data = $_POST['table_data'];
    $arr = json_decode($data, true);
    $i = 0;

    $reg_type_value = array("I", "Q", "M", "DB", "V", "C", "T");
    $word_len_value = array("Bit", "Byte", "Word", "DWord", "Real", "Counter", "Timer");

    exec("sudo /usr/sbin/uci_get_count dct s7", $count);

    if ($count[0] == null || strlen($count[0]) <= 0) {
        $count[0] = 0;
    }

    foreach ($arr as $list=>$things) {
        if (is_array($things)) {
            exec("sudo /usr/local/bin/uci delete dct.@s7[$i]");
            exec("sudo /usr/local/bin/uci add dct s7");
            foreach ($things as $key=>$val) {
                if ($key == "reg_type") {
                    $reg_type_num = array_search($val, $reg_type_value);
                    exec("sudo /usr/local/bin/uci set dct.@s7[$i].$key=$reg_type_num");
                } else if ($key == "word_len") {
                    $word_len_num = array_search($val, $word_len_value);
                    exec("sudo /usr/local/bin/uci set dct.@s7[$i].$key=$word_len_num");
                } else {
                    exec("sudo /usr/local/bin/uci set dct.@s7[$i].$key='$val'");
                }  
            }
        }
        $i++;
    }

    if (number_format($count[0]) > $i) {
        for ($j = $i; $j < number_format($count[0]); $j++) {
            exec("sudo /usr/local/bin/uci delete dct.@s7[$i]");
        }
    }

    exec('sudo /usr/local/bin/uci commit dct');

    $status->addMessage('dct configuration updated ', 'success');
    return true;
}
