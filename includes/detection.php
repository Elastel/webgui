<?php

require_once 'includes/status_messages.php';
require_once 'config.php';

function DisplayDetectionConfig()
{   
    $status = new StatusMessages();

    if (!RASPI_MONITOR_ENABLED) {
        if (isset($_POST['savedetectionsettings']) || isset($_POST['applydetectionsettings'])) {
            saveDetectionConfig($status);  
            
            if (isset($_POST['applydetectionsettings'])) {
                sleep(2);
                exec('sudo /etc/init.d/failover restart > /dev/null');
            }
        }
    }
    
    exec("sudo /usr/local/bin/uci get network.detection.primary_addr", $primary_addr);
    exec("sudo /usr/local/bin/uci get network.detection.secondary_addr", $secondary_addr);
    exec("sudo /usr/local/bin/uci get network.detection.enabled_reboot", $enabled_reboot);
    exec("sudo /usr/local/bin/uci get network.detection.reboot_inter", $reboot_inter);

    echo renderTemplate("detection", compact(
        'status', 
        'primary_addr', 
        'secondary_addr', 
        'enabled_reboot', 
        'reboot_inter'
    ));
}

function saveDetectionConfig($status)
{

    $return = 1;
    $error = array();

    exec("sudo /usr/local/bin/uci set network.detection.primary_addr=" .$_POST['primary_addr']);
    exec("sudo /usr/local/bin/uci set network.detection.secondary_addr=" .$_POST['secondary_addr']);
    if ($_POST['enabled_reboot'] == "1") {
        exec("sudo /usr/local/bin/uci set network.detection.enabled_reboot=" .$_POST['enabled_reboot']);
        exec("sudo /usr/local/bin/uci set network.detection.reboot_inter=" .$_POST['reboot_inter']);
    } else {
        exec("sudo /usr/local/bin/uci set network.detection.enabled_reboot=0");
    }
    
    exec("sudo /usr/local/bin/uci commit network");

    $status->addMessage('configuration updated ', 'success');
    return true;
 
}

