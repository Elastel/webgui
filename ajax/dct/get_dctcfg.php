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
} else {
    if ($type == 'interface' || $type == 'server')
        exec("/usr/sbin/get_config dct name $type 5", $data);
    else if ($type == 'modbus' || $type == 'ascii' || $type == 's7'|| $type == 'fx' ||
             $type == 'mc' || $type == 'adc' || $type == 'di' || $type == 'do')
        exec("/usr/sbin/get_config dct type $type 1", $data);
    else
        exec("/usr/sbin/get_config dct name $type 1", $data);
    
    $dctdata = json_decode($data[0]);
    echo json_encode($dctdata);
}
