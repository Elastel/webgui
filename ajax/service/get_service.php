<?php

require '../../includes/csrf.php';
require_once '../../includes/config.php';

$type = $_GET['type'];

if ($type == "gps") {
    $arrInfo = array('output_mode', 'server_addr', 'server_port', 'report_mode', 'register_packet',
        'heartbeat_packet', 'report_interval', 'heartbeat_interval', 'baudrate', 'databit', 'stopbit',
        'parity', 'accuracy');

    exec("/usr/local/bin/uci get gps.conf.enabled", $enabled);
    $servicedata['enabled'] = $enabled[0];
    if ($enabled[0] == "1") {
        foreach ($arrInfo as $info) {
            unset($val);
            exec("sudo /usr/local/bin/uci get gps.conf." . $info, $val);
            $servicedata[$info] = $val[0];
        }
    } 
} else if ($type == "bacnet_router") {
    $arrInfo = array('mode', 'ifname', 'port', 'interface', 'baudrate', 'mac',
    'max_master', 'frames');

    exec("/usr/local/bin/uci get bacnet_router.bacnet.enabled", $enabled);
    $servicedata['enabled'] = $enabled[0];
    if ($enabled[0] == "1") {
        foreach ($arrInfo as $info) {
            unset($val);
            exec("sudo /usr/local/bin/uci get bacnet_router.bacnet." . $info, $val);
            $servicedata[$info] = $val[0];
        }
    } 
} else if ($type == "chirpstack") {
    $new_region = $_GET['region'];
    exec('sudo grep -r "event_topic_template" /etc/chirpstack-gateway-bridge/chirpstack-gateway-bridge.toml', $tmp);
    preg_match('/"([^"]*)"/', $tmp[0], $cur_region);
    $array = explode("/", $cur_region[1]);
    unset($cur_region);
    $cur_region = $array[0];

    exec("sudo sed -i 's/$cur_region/$new_region/g' /etc/chirpstack-gateway-bridge/chirpstack-gateway-bridge.toml");
    $servicedata['region'] = "eu868";

    exec('sudo systemctl restart chirpstack.service');
    exec('sudo systemctl restart chirpstack-gateway-bridge.service');
}

echo json_encode($servicedata);


