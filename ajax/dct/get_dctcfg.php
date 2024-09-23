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
} else if (strstr($type, 'bacdiscover')) {
    exec("uci get dct.bacnet_client.enabled", $enable);
    if ($enable[0] != '0') {
        exec("sudo /usr/sbin/bacnet_update");
        exec('cat /tmp/bacdiscover', $data);
        if ($data[0] != null) {
            $dctdata = json_decode($data[0]);
            echo json_encode($dctdata);
        }
    }
} else {
    if (file_exists('/etc/elastel_config.json')) {
        $fileContent = file_get_contents('/etc/elastel_config.json');
        $config = json_decode($fileContent, true);
    }
       
    if (file_exists('/tmp/factor_list'))
        $fileContentFactor = file_get_contents('/tmp/factor_list');

    if ($type == 'interface') {
        exec("/usr/sbin/get_config dct name $type 5", $data);
        $dctdata[$type] = $data[0];
        $dctdata['com_option'] = $config['com_key'];
        $dctdata['tcp_server_option'] = $config['tcp_server_key'];
        echo json_encode($dctdata);
    } else if ($type == 'server') {
        exec("/usr/sbin/get_config dct name $type 5", $data);
    } else if ($type == 'modbus' || $type == 'ascii' || $type == 's7'|| $type == 'fx' ||
             $type == 'mc' || $type == 'adc' || $type == 'di' || $type == 'do' || 
             $type == 'iec104' || $type == 'opcuacli' || $type == 'dnp3cli') {
        exec("/usr/sbin/get_config dct type $type 1", $data);
    } else if ($type == 'dnp3') {
        exec("/usr/sbin/get_config dct name dnp3_server 1", $tmp1);
        exec("/usr/sbin/get_config dct type dnp3 1", $tmp2);
        $dctdata['option'] = $config['dnp3_server_option'];
        $dctdata['option_list'] = $config['dnp3_option'];
        if (strlen($fileContentFactor) > 0)
            $dctdata['factor_list'] = json_decode($fileContentFactor, true);

        if ($tmp1)
            $dctdata[$type.'_server'] = $tmp1[0];

        if ($tmp2)
            $dctdata[$type] = $tmp2[0];
        
        echo json_encode($dctdata);
    } else {
        exec("/usr/sbin/get_config dct name $type 1", $data);
    }
        
    
    if ($type != 'dnp3' && $type != 'interface') {
        $dctdata = json_decode($data[0]);
        echo json_encode($dctdata);
    }
}
