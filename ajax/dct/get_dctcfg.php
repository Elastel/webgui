<?php
require '../../includes/csrf.php';
require_once '../../includes/config.php';

$type = $_GET['type'];

if ($type == 'datadisplay') {
    exec('cat /tmp/webshow', $dctdata);
    if ($dctdata[0] != NULL){
        echo $dctdata[0];
    }  else {
        echo "{}";
    }
} else if (strstr($type, 'download')) {
    $arr = explode("_", $type);
    exec('sudo conf_im_ex export ' . $arr[1]);
    exec('cat /tmp/config_export.csv', $data);
    echo implode(PHP_EOL, $data);
} else {
    if ($type == 'interface' || $type == 'server')
        exec("/usr/sbin/get_config dct name $type 5", $data);
    else if ($type == 'modbus' || $type == 'ascii' || $type == 's7'|| $type == 'fx' ||
             $type == 'mc' || $type == 'adc' || $type == 'di' || $type == 'do' || 
             $type == 'iec104')
        exec("/usr/sbin/get_config dct type $type 1", $data);
    else
        exec("/usr/sbin/get_config dct name $type 1", $data);
    
    $dctdata = json_decode($data[0]);
    echo json_encode($dctdata);
}
