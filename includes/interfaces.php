<?php

require_once 'includes/status_messages.php';
require_once 'config.php';

function DisplayInterfaces()
{   
    $model = getModel();
    $status = new StatusMessages();
	$com_proto = array('Modbus', 'Transparent', 'FX');
    $tcp_proto = array('Modbus', 'Transparent', 'S7', 'FX');

    if (!RASPI_MONITOR_ENABLED) {
        if (isset($_POST['saveinterfacesettings']) || isset($_POST['applyinterfacesettings'])) {
            saveInterfaceConfig($status, $model);
            
            if (isset($_POST['applyinterfacesettings'])) {
                sleep(2);
                exec('sudo /etc/init.d/dct restart > /dev/null');
            }
        }
    }

    echo renderTemplate('interfaces', compact('status', 'model', 'com_proto', 'tcp_proto'));
}

function saveComConfig($status, $model)
{   

    if ($model == "EG500") {
        $count = 2;
    } else {
        $count = 4;
    }

    for ($i = 1; $i <= $count; $i++) {
        exec('sudo /usr/local/bin/uci set dct.com.enabled' . $i . '=' .$_POST['com_enabled' . $i]);
        if ($_POST['com_enabled' . $i] == '1') {
            exec('sudo /usr/local/bin/uci set dct.com.baudrate' . $i . '=' .$_POST['baudrate' . $i]);
            exec('sudo /usr/local/bin/uci set dct.com.databit' . $i . '=' .$_POST['databit' . $i]);
            exec('sudo /usr/local/bin/uci set dct.com.stopbit' . $i . '=' .$_POST['stopbit' . $i]);
            exec('sudo /usr/local/bin/uci set dct.com.parity' . $i . '=' .$_POST['parity' . $i]);
            exec('sudo /usr/local/bin/uci set dct.com.frame_interval' . $i . '=' .$_POST['com_frame_interval' . $i]);
            exec('sudo /usr/local/bin/uci set dct.com.proto' . $i . '=' .$_POST['com_proto' . $i]);
            exec('sudo /usr/local/bin/uci set dct.com.cmd_interval' . $i . '=' .$_POST['com_cmd_interval' . $i]);
            exec('sudo /usr/local/bin/uci set dct.com.report_center' . $i . '=' .$_POST['com_report_center' . $i]);
        } 
    }
}

function saveTcpConfig($status)
{
    for ($i = 1; $i <= 5; $i++) {
        exec('sudo /usr/local/bin/uci set dct.tcp_server.enabled' . $i . '=' .$_POST['tcp_enabled' . $i]);
        if ($_POST['tcp_enabled' . $i] == '1') {
            exec('sudo /usr/local/bin/uci set dct.tcp_server.server_addr' . $i . '=' .$_POST['server_addr' . $i]);
            exec('sudo /usr/local/bin/uci set dct.tcp_server.server_port' . $i . '=' .$_POST['server_port' . $i]);
            exec('sudo /usr/local/bin/uci set dct.tcp_server.frame_interval' . $i . '=' .$_POST['tcp_frame_interval' . $i]);
            exec('sudo /usr/local/bin/uci set dct.tcp_server.proto' . $i . '=' .$_POST['tcp_proto' . $i]);
            exec('sudo /usr/local/bin/uci set dct.tcp_server.cmd_interval' . $i . '=' .$_POST['tcp_cmd_interval' . $i]);
            exec('sudo /usr/local/bin/uci set dct.tcp_server.report_center' . $i . '=' .$_POST['tcp_report_center' . $i]);
            exec('sudo /usr/local/bin/uci set dct.tcp_server.rack' . $i . '=' .$_POST['rack' . $i]);
            exec('sudo /usr/local/bin/uci set dct.tcp_server.slot' . $i . '=' .$_POST['slot' . $i]);
        }
    }
}

function saveInterfaceConfig($status, $model)
{
    $return = 1;
    $error = array();

    saveComConfig($status, $model);
    saveTcpConfig($status);
    
    exec('sudo /usr/local/bin/uci commit dct');

    $status->addMessage('dct configuration updated ', 'success');
    return true;
}

