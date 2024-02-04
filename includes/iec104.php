<?php

require_once 'includes/status_messages.php';
require_once 'config.php';

/**
 * Displays info about the RaspAP project
 */
function DisplayIEC104()
{
    $status = new StatusMessages();
    $data_type_list = array('Bit', 'Int', 'Float');
    $type_id_list = array('1'=>'M_SP_NA_1', '30'=>'M_SP_TB_1', '3'=>'M_DP_NA_1', '31'=>'M_DP_TB_1', '5'=>'M_ST_NA_1', '32'=>'M_ST_TB_1',
    '7'=>'M_BO_NA_1', '33'=>'M_BO_TB_1', '9'=>'M_ME_NA_1', '34'=>'M_ME_TD_1', '21'=>'M_ME_ND_1', '11'=>'M_ME_NB_1', '35'=>'M_ME_TE_1', '13'=>'M_ME_NC_1', 
    '36'=>'M_ME_TF_1'); // , '15'=>'M_IT_NA_1', '37'=>'M_IT_TB_1', '38'=>'M_EP_TD_1');

    if (!RASPI_MONITOR_ENABLED) {
        if (isset($_POST['saveiec104settings']) || isset($_POST['applyiec104settings'])) {
            saveIec104Config($status, $data_type_list, $type_id_list);
            
            if (isset($_POST['applyiec104settings'])) {
                sleep(2);
                exec('sudo /etc/init.d/dct restart > /dev/null');
            }
        }
    }

    if ( isset($_POST['upload']) ) {
        if (strlen($_FILES['upload_file']['name']) > 0) {
            if (is_uploaded_file($_FILES['upload_file']['tmp_name'])) {
                save_import_file('iec104', $status, $_FILES['upload_file']);
            } else {
                $status->addMessage('fail to upload file', 'danger');
            }
        }
    }

    echo renderTemplate("iec104", compact('status', 'data_type_list', 'type_id_list'));
}

function saveIec104Config($status, $data_type_list, $type_id_list)
{
    $data = $_POST['table_data'];
    $arr = json_decode($data, true);
    $i = 0;

    exec('sudo /usr/sbin/uci_get_count dct iec104', $count);

    if ($count[0] == null || strlen($count[0]) <= 0) {
        $count[0] = 0;
    }

    foreach ($arr as $list=>$things) {
        if (is_array($things)) {
            exec("sudo /usr/local/bin/uci delete dct.@iec104[$i]");
            exec("sudo /usr/local/bin/uci add dct iec104");
            foreach ($things as $key=>$val) {
                if ($key == "data_type") {
                    $data_type_num = array_search($val, $data_type_list);
                    exec("sudo /usr/local/bin/uci set dct.@iec104[$i].$key=$data_type_num");
                } else if ($key == "type_id") {
                    $type_id = array_search($val, $type_id_list);
                    exec("sudo /usr/local/bin/uci set dct.@iec104[$i].$key=$type_id");
                } else {
                    exec("sudo /usr/local/bin/uci set dct.@iec104[$i].$key='$val'");
                }  
            }
        }
        $i++;
    }

    if (number_format($count[0]) > $i) {
        for ($j = $i; $j < number_format($count[0]); $j++) {
            exec("sudo /usr/local/bin/uci delete dct.@iec104[$i]");
        }
    }

    exec('sudo /usr/local/bin/uci commit dct');

    $status->addMessage('dct configuration updated ', 'success');
    return true;
}
