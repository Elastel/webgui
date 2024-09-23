<?php

require_once 'includes/status_messages.php';
require_once 'config.php';

function DisplayInterfaces()
{   
    $model = getModel();
    $status = new StatusMessages();

    if (!RASPI_MONITOR_ENABLED) {
        if (isset($_POST['saveinterfacesettings']) || isset($_POST['applyinterfacesettings'])) {
            saveInterfaceConfig($status, $model);
            
            if (isset($_POST['applyinterfacesettings'])) {
                sleep(2);
                exec('sudo /etc/init.d/dct restart > /dev/null');
            }
        }
    }

    echo renderTemplate('interfaces', compact('status', 'model'));
}
/*
exec("sudo /usr/local/bin/uci set dct.opcua.security_policy=" .$_POST['security_policy']);
        if ($_POST['security_policy'] != '0') {
            if (strlen($_FILES['certificate']['name']) > 0) {
                if (is_uploaded_file($_FILES['certificate']['tmp_name'])) {
                    saveFileUpload($status, $_FILES['certificate']);
                }
                $certName = $_FILES['certificate']['name'];
                exec("sudo /usr/local/bin/uci set dct.opcua.certificate='$certName'");
            }

            // get uri
            if ($_POST['uri'] == null) {
                exec("sudo /usr/local/bin/uci get dct.opcua.certificate", $certFile);
                $uri_path = "/etc/ssl/opcua/$certFile[0]";
                if (!is_dir($uri_path)) {
                    exec("data=$(openssl x509 -in $uri_path -inform der -noout -text | grep URI) && echo $" . '{data#*URI:}' . " | awk -F ' ' '{print $0}'", $uri);
                    if (strlen($uri[0]) > 0) {
                        exec("sudo /usr/local/bin/uci set dct.opcua.uri=$uri[0]");
                    }
                }
            } else {
                exec("sudo /usr/local/bin/uci set dct.opcua.uri=" .$_POST['uri']);
            }

            if (strlen($_FILES['private_key']['name']) > 0) {
                if (is_uploaded_file($_FILES['private_key']['tmp_name'])) {
                    saveFileUpload($status, $_FILES['private_key']); 
                }

                $keyName = $_FILES['private_key']['name'];
                exec("sudo /usr/local/bin/uci set dct.opcua.private_key='$keyName'");
            }

            if (strlen($_FILES['trust_crt']['name'][0]) > 0) {
                $count = count($_FILES['trust_crt']['name']);
                for ($i = 0; $i < $count; $i++) {
                    if (is_uploaded_file($_FILES['trust_crt']['tmp_name'][$i])) {
                        $tmp_config = $_FILES['trust_crt']['tmp_name'][$i];
                        system("sudo mv $tmp_config /etc/ssl/opcua/" . $_FILES['trust_crt']['name'][$i]);
                        system("sudo chmod 644 /etc/ssl/opcua/" . $_FILES['trust_crt']['name'][$i]);
                        $trustName .= $_FILES['trust_crt']['name'][$i];
                        if ($i < ($count - 1))
                            $trustName .= ";";
                    }    
                }

                exec("sudo /usr/local/bin/uci set dct.opcua.trust_crt='$trustName'");
            }
        }
    }
*/
function saveComConfig($status, $model)
{   

    if ($model == "EG500") {
        $count = 2;
    } else {
        $count = 4;
    }

    $data = array();
    $arr_option = array();
    $arr_key = array();

    if (file_exists('/etc/elastel_config.json')) {
        $fileContent = file_get_contents('/etc/elastel_config.json');
        $config = json_decode($fileContent, true);
    }
    
    if (array_key_exists('com_key', $config)) {
        $arr_key = $config['com_key'];
    } else {
        $arr_key = $config['com_option'];
    }

    $arr_option = $config['com_option'];

    for ($i = 1; $i <= $count; $i++) {
        for ($j = 0; $j < count($arr_option); $j++) {
            $data[$arr_option[$j] . $i] = $_POST[$arr_key[$j] . $i];
        }

        if ($arr_key[$j] == 'com_enabled' && $_POST[$arr_key[$j] . $i] != '1') {
            break;
        }
    }

    $json_data = json_encode($data);
    
    file_put_contents(ELASTEL_DCT_CONFIG_JSON, '');
    file_put_contents(ELASTEL_DCT_CONFIG_JSON, $json_data);
    exec('sudo /usr/sbin/set_config ' . ELASTEL_DCT_CONFIG_JSON . ' dct com');
}

function saveFileUploadInterface($status, $file, $index)
{
    define('KB', 1024);
    $tmp_destdir = '/tmp/';
    $auth_flag = 0;

    try {
        // If undefined or multiple files, treat as invalid
        if (!isset($file['error']) || is_array($file['error'])) {
            throw new RuntimeException('Invalid parameters');
        }

        $upload = \RaspAP\Uploader\Upload::factory('interfaces', $tmp_destdir);
        $upload->set_max_file_size(64*KB);
        $upload->set_allowed_mime_types(array('text/plain', 'application/octet-stream'));
        $upload->file($file);

        $validation = new validation;
        $upload->callbacks($validation, array('check_name_length'));
        $results = $upload->upload();

        if (!empty($results['errors'])) {
            throw new RuntimeException($results['errors'][0]);
        }

        // Valid upload, get file contents
        $tmp_config = $results['full_path'];

        $path = "/etc/ssl/interfaces$index";
        if (!is_dir($path)) {
            exec("sudo /bin/mkdir -p " . $path);
        }

        // Move processed file from tmp to destination
        system("sudo mv $tmp_config $path/" . $file['name'], $return);

        return $status;

    } catch (RuntimeException $e) {
        $status->addMessage($e->getMessage(), 'danger');
        return $status;
    }
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
            $data['anonymous' . $i] = $_POST['anonymous' . $i];
            $data['username' . $i] = $_POST['username' . $i];
            $data['password' . $i] = $_POST['password' . $i];
            $data['slave_address' . $i] = $_POST['tcp_slave_address' . $i];
            $data['master_address' . $i] = $_POST['tcp_master_address' . $i];
            
            $data['security_policy' . $i] = $_POST['security_policy' . $i];
            if ($data['security_policy' . $i] != '0') {
                if (strlen($_FILES['certificate' . $i]['name']) > 0) {
                    if (is_uploaded_file($_FILES['certificate' . $i]['tmp_name'])) {
                        saveFileUploadInterface($status, $_FILES['certificate' . $i], $i);
                    }
                    $certName = $_FILES['certificate' . $i]['name'];
                    $data['certificate' . $i] = $certName;
                }

                // get uri
                if ($_POST['uri'] == null) {
                    $certFile = $data['certificate' . $i];
                    $uri_path = "/etc/ssl/interfaces$i/$certFile";
                    if (!is_dir($uri_path)) {
                        exec("data=$(openssl x509 -in $uri_path -inform der -noout -text | grep URI) && echo $" . '{data#*URI:}' . " | awk -F ' ' '{print $0}'", $uri);
                        if (strlen($uri[0]) > 0) {
                            $data['uri' . $i] = $uri[0];
                        }
                    }
                } else {
                    $data['uri' . $i] = $_POST['uri' . $i];
                }

                if (strlen($_FILES['private_key' . $i]['name']) > 0) {
                    if (is_uploaded_file($_FILES['private_key' . $i]['tmp_name'])) {
                        saveFileUpload($status, $_FILES['private_key' . $i]); 
                    }
    
                    $keyName = $_FILES['private_key' . $i]['name'];
                    $data['private_key' . $i] = $keyName;
                }
    
                if (strlen($_FILES['trust_crt' . $i]['name'][0]) > 0) {
                    $count = count($_FILES['trust_crt' . $i]['name']);
                    for ($j = 0; $j < $count; $j++) {
                        if (is_uploaded_file($_FILES['trust_crt' . $i]['tmp_name'][$j])) {
                            $tmp_config = $_FILES['trust_crt' . $i]['tmp_name'][$j];
                            system("sudo mv $tmp_config /etc/ssl/interfaces$i/" . $_FILES['trust_crt' . $i]['name'][$j]);
                            system("sudo chmod 644 /etc/ssl/interfaces$i/" . $_FILES['trust_crt' . $i]['name'][$j]);
                            $trustName .= $_FILES['trust_crt' . $i]['name'][$j];
                            if ($i < ($count - 1))
                                $trustName .= ";";
                        }    
                    }
                    
                    $data['trust_crt' . $i] = $trustName;
                }
            }
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

