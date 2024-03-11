<?php

require_once 'includes/status_messages.php';
require_once 'config.php';

function DisplayInterfaces()
{   
    $model = getModel();
    $status = new StatusMessages();
	$com_proto = array('Modbus', 'Transparent', 'FX', 'MC', 'ASCII');
    $tcp_proto = array('Modbus', 'Transparent', 'S7', 'FX', 'MC', 'ASCII');

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

    $data = array();

    for ($i = 1; $i <= $count; $i++) {
        $data['enabled' . $i] = $_POST['com_enabled' . $i] ?? '0';
        if ($_POST['com_enabled' . $i] == '1') {
            $data['baudrate' . $i] = $_POST['baudrate' . $i];
            $data['databit' . $i] = $_POST['databit' . $i];
            $data['stopbit' . $i] = $_POST['stopbit' . $i];
            $data['parity' . $i] = $_POST['parity' . $i];
            $data['frame_interval' . $i] = $_POST['com_frame_interval' . $i];
            $data['proto' . $i] = $_POST['com_proto' . $i];
            $data['cmd_interval' . $i] = $_POST['com_cmd_interval' . $i];
            $data['report_center' . $i] = $_POST['com_report_center' . $i];
        }
    }

    $json_data = json_encode($data);
    file_put_contents(ELASTEL_DCT_CONFIG_JSON, '');
    file_put_contents(ELASTEL_DCT_CONFIG_JSON, $json_data);
    exec('sudo /usr/sbin/set_config ' . ELASTEL_DCT_CONFIG_JSON . ' dct com');
}

function saveTcpConfig($status)
{
    for ($i = 1; $i <= 5; $i++) {
        $data['enabled' . $i] = $_POST['tcp_enabled' . $i] ?? '0';
        if ($_POST['tcp_enabled' . $i] == '1') {
            $data['server_addr' . $i] = $_POST['server_addr' . $i];
            $data['server_port' . $i] = $_POST['server_port' . $i];
            $data['frame_interval' . $i] = $_POST['tcp_frame_interval' . $i];
            $data['proto' . $i] = $_POST['tcp_proto' . $i];
            $data['cmd_interval' . $i] = $_POST['tcp_cmd_interval' . $i];
            $data['report_center' . $i] = $_POST['tcp_report_center' . $i];
            $data['rack' . $i] = $_POST['rack' . $i];
            $data['slot' . $i] = $_POST['slot' . $i];
        }
    }

    $json_data = json_encode($data);
    file_put_contents(ELASTEL_DCT_CONFIG_JSON, '');
    file_put_contents(ELASTEL_DCT_CONFIG_JSON, $json_data);
    exec('sudo /usr/sbin/set_config ' . ELASTEL_DCT_CONFIG_JSON . ' dct tcp_server');
}

function saveInterfaceConfig($status, $model)
{
    saveComConfig($status, $model);
    saveTcpConfig($status);

    $status->addMessage('dct configuration updated ', 'success');
}

