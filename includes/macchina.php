<?php

require_once 'includes/status_messages.php';
require_once 'config.php';

function DisplayMacchina()
{   
    $status = new StatusMessages();

    if (!RASPI_MONITOR_ENABLED) {
        if (isset($_POST['savesettings']) || isset($_POST['applysettings'])) {
            $ret = saveConfig($status);

            if (isset($_POST['applysettings'])) {
                if ($_POST['enabled'] == '0') {
                    exec('sudo /etc/init.d/macchina stop');
                } else {
                    exec('sudo /etc/init.d/macchina restart');
                }
            }
        }
    }

    if (is_file("/etc/WebTunnelAgent.properties")) {
        exec("cat /etc/WebTunnelAgent.properties | grep 'webtunnel.domain ' | awk -F'= ' '{print $2}'", $uuid);
        exec("sudo /usr/local/bin/uci get macchina.macchina.mac_config", $file_name);
    }

    exec("pgrep WebTunnelAgent", $run_status);
    exec("sudo /usr/local/bin/uci get macchina.macchina.enabled", $enabled);

    echo renderTemplate("macchina", compact('status', 'uuid', 'run_status', 'file_name', 'enabled'));
}

function SaveUpload($status, $file)
{
    define('KB', 1024);
    $tmp_destdir = '/tmp/';
    $auth_flag = 0;

    try {
        // If undefined or multiple files, treat as invalid
        if (!isset($file['error']) || is_array($file['error'])) {
            throw new RuntimeException('Invalid parameters');
        }

        $upload = \RaspAP\Uploader\Upload::factory('macchina', $tmp_destdir);
        $upload->set_max_file_size(128*KB);
        $upload->set_allowed_mime_types(array('text/plain'));
        $upload->file($file);

        $validation = new validation;
        $upload->callbacks($validation, array('check_name_length'));
        $results = $upload->upload();

        if (!empty($results['errors'])) {
            throw new RuntimeException($results['errors'][0]);
        }

        // Valid upload, get file contents
        $tmp_config = $results['full_path'];
        // Move processed file from tmp to destination
        system("sudo mv $tmp_config /etc/WebTunnelAgent.properties", $return);
        return $status;

    } catch (RuntimeException $e) {
        $status->addMessage($e->getMessage(), 'danger');
        return $status;
    }
}

function saveConfig($status)
{
    $return = 1;
    $error = array();

    if (is_uploaded_file($_FILES['mac_config']['tmp_name'])) {
        SaveUpload($status, $_FILES['mac_config']);
    }

    exec("sudo /usr/local/bin/uci set macchina.macchina.enabled=" . $_POST['enabled']);
    if ($_POST['enabled'] == "1") {
        if (is_uploaded_file($_FILES['mac_config']['tmp_name'])) {
            exec("sudo rm /etc/WebTunnelAgent.properties");
            SaveUpload($status, $_FILES['mac_config']);
        }
        if (is_file("/etc/WebTunnelAgent.properties")) {
            exec("sudo /usr/local/bin/uci set macchina.macchina.mac_config=" . $_FILES['mac_config']['name']);
        }
    }

    exec("sudo /usr/local/bin/uci commit macchina");

    $status->addMessage('Macchina configuration updated ', 'success');
    return true;
}

