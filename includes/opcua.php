<?php

require_once 'includes/status_messages.php';
require_once 'config.php';

function DisplayOpcua()
{   
    $status = new StatusMessages();

    if (!RASPI_MONITOR_ENABLED) {
        if (isset($_POST['savesettings']) || isset($_POST['applysettings'])) {
            $ret = saveOpcuaConfig($status);
            if ($ret == false) {
                $status->addMessage('Error data', 'danger');
            } else {
                if (isset($_POST['applysettings'])) {
                    exec('sudo /etc/init.d/dct restart >/dev/null'); 
                }
            }
        }
    }

    echo renderTemplate("opcua", compact('status'));
}

function saveFileUpload($status, $file)
{
    define('KB', 1024);
    $tmp_destdir = '/tmp/';
    $auth_flag = 0;

    try {
        // If undefined or multiple files, treat as invalid
        if (!isset($file['error']) || is_array($file['error'])) {
            throw new RuntimeException('Invalid parameters');
        }

        $upload = \RaspAP\Uploader\Upload::factory('opcua', $tmp_destdir);
        $upload->set_max_file_size(64*KB);
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

        $path = "/etc/ssl/opcua";
        if (!is_dir($path)) {
            exec("sudo /bin/mkdir -p " . $path);
        }

        // Move processed file from tmp to destination
        system("sudo mv $tmp_config $path/" . $file['name'], $return);

        // if ($return ==0) {
        //     $status->addMessage('mqtt certificate uploaded successfully', 'info');
        // } else {
        //     $status->addMessage('Unable to save mqtt certificate', 'danger');
        // }
        return $status;

    } catch (RuntimeException $e) {
        $status->addMessage($e->getMessage(), 'danger');
        return $status;
    }
}

function saveOpcuaConfig($status)
{
    exec("sudo /usr/local/bin/uci set dct.opcua.enabled=" . $_POST['enabled']);
    if ($_POST['enabled'] == "1") {
        exec("sudo /usr/local/bin/uci set dct.opcua.port=" .$_POST['port']);
        exec("sudo /usr/local/bin/uci set dct.opcua.anonymous=" .$_POST['anonymous']);
        if ($_POST['anonymous'] != "1") {
            exec("sudo /usr/local/bin/uci set dct.opcua.username=" .$_POST['username']);
            exec("sudo /usr/local/bin/uci set dct.opcua.password=" .$_POST['password']);
        }
        
        exec("sudo /usr/local/bin/uci set dct.opcua.security_policy=" .$_POST['security_policy']);
        if ($_POST['security_policy'] != '0') {
            if (strlen($_FILES['certificate']['name']) > 0) {
                if (is_uploaded_file($_FILES['certificate']['tmp_name'])) {
                    saveFileUpload($status, $_FILES['certificate']);
                }
                $certName = $_FILES['certificate']['name'];
                exec("sudo /usr/local/bin/uci set dct.opcua.certificate=" . $certName);
            }

            if (strlen($_FILES['private_key']['name']) > 0) {
                if (is_uploaded_file($_FILES['private_key']['tmp_name'])) {
                    saveFileUpload($status, $_FILES['private_key']); 
                }

                $keyName = $_FILES['private_key']['name'];
                exec("sudo /usr/local/bin/uci set dct.opcua.private_key=" . $keyName);
            }
        }
    }
    exec("sudo /usr/local/bin/uci commit dct");

    if ($_POST['enabled'] == "1") {
        if ($_POST['port'] == NULL || (int)($_POST['port']) > 65535 || (int)($_POST['device_id']) > 65535) {
            return false;
        }
    }
    
    $status->addMessage('OPC UA configuration updated ', 'success');
    return true;
}
