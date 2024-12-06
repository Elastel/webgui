<?php

require_once 'includes/status_messages.php';
require_once 'config.php';

function DisplayModbusRouter()
{   
    $status = new StatusMessages();

    if (!RASPI_MONITOR_ENABLED) {
        if (isset($_POST['savemodbusroutersettings']) || isset($_POST['applymodbusroutersettings'])) {
            $ret = saveModbusRouterConfig($status);
            if ($ret == false) {
                $status->addMessage('Error data', 'danger');
            } else {
                if (isset($_POST['applymodbusroutersettings'])) {
                    exec('sudo /etc/init.d/modbus_router restart >/dev/null'); 
                }
            }
        }
    }

    exec("pgrep router-modbus", $pid);
    if ($pid != null) {
        $routerStatus = "Running";
        $statusIcon = "up";
    } else {
        $routerStatus = "Stop";
        $statusIcon = "down";
    }

    $arrInfo = array('mode', 'address', 'port', 'com', 'baudrate', 'databit',
    'stopbit', 'parity');

    exec("/usr/local/bin/uci get modbus_router.modbus.enabled", $enabled);
    $modbusRouterConf['enabled'] = $enabled[0];
    if ($enabled[0] == "1") {
        foreach ($arrInfo as $info) {
            unset($val);
            exec("sudo /usr/local/bin/uci get modbus_router.modbus." . $info, $val);
            $modbusRouterConf[$info] = $val[0];
        }
    } 

    echo renderTemplate("modbus_router", compact('status', 'routerStatus', 'statusIcon', 'modbusRouterConf'));
}

function saveModbusRouterConfig($status)
{
    exec("sudo /usr/local/bin/uci set modbus_router.modbus.enabled=" . $_POST['enabled']);
    exec("sudo /usr/local/bin/uci set modbus_router.modbus.mode=" . $_POST['mode']);
    exec("sudo /usr/local/bin/uci set modbus_router.modbus.address=" .$_POST['address']);
    exec("sudo /usr/local/bin/uci set modbus_router.modbus.port=" .$_POST['port']);
    exec("sudo /usr/local/bin/uci set modbus_router.modbus.com=" .$_POST['com']);
    exec("sudo /usr/local/bin/uci set modbus_router.modbus.baudrate=" .$_POST['baudrate']);
    exec("sudo /usr/local/bin/uci set modbus_router.modbus.databit=" .$_POST['databit']);
    exec("sudo /usr/local/bin/uci set modbus_router.modbus.stopbit=" .$_POST['stopbit']);
    exec("sudo /usr/local/bin/uci set modbus_router.modbus.parity=" .$_POST['parity']);
    exec("sudo /usr/local/bin/uci commit modbus_router");

    $status->addMessage('Modbus router configuration updated ', 'success');
    return true;
}

