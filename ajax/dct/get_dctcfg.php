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
    exec("/usr/sbin/get_config dct $type", $data);
    $dctdata = json_decode($data[0]);
    echo json_encode($dctdata);
}
