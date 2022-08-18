<?php

require_once 'includes/status_messages.php';
require_once 'config.php';

function DisplayServer()
{   
    $status = new StatusMessages();

    if (!RASPI_MONITOR_ENABLED) {
        if (isset($_POST['saveserversettings']) || isset($_POST['applyserversettings'])) {
            saveServerConfig($status); 
            
            if (isset($_POST['applyserversettings'])) {
                sleep(2);
                exec('sudo /etc/init.d/dct restart > /dev/null');
            }
        }
    }

    echo renderTemplate('server', compact('status'));
}

function SaveServerUpload($status, $file, $num)
{
    define('KB', 1024);
    $tmp_destdir = '/tmp/';
    $auth_flag = 0;

    try {
        // If undefined or multiple files, treat as invalid
        if (!isset($file['error']) || is_array($file['error'])) {
            throw new RuntimeException('Invalid parameters');
        }

        $upload = \RaspAP\Uploader\Upload::factory('server' . $num, $tmp_destdir);
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
        $tmp_serverconfig = $results['full_path'];

        // Move processed file from tmp to destination
        system("sudo mv $tmp_serverconfig /etc/ssl/server" . $num . "/" . $file['name'], $return);

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

function saveServerConfig($status)
{

    $return = 1;
    $error = array();

    for ($i = 1; $i <= 5; $i++) {
        exec('sudo /usr/local/bin/uci set dct.server.enabled' . $i . '=' .$_POST['enabled' . $i]);
        if ($_POST['enabled' . $i] == '1') {
            
            if ($_POST['certificate_type' . $i] == '1' && $_POST['protocol' . $i] == '2') {
                if (strlen($_FILES['ca_file' . $i]['name']) > 0) {
                    if (is_uploaded_file($_FILES['ca_file' . $i]['tmp_name'])) {
                        SaveServerUpload($status, $_FILES['ca_file' . $i], $i);
                    }

                    exec('sudo /usr/local/bin/uci set dct.server.mqtt_ca' . $i . '=' . $_FILES['ca_file' . $i]['name']);
                }
                
            } else if ($_POST['certificate_type' . $i] == '2'  && $_POST['protocol' . $i] == '2') {
                if (strlen($_FILES['ca_file' . $i]['name']) > 0) {
                    if (is_uploaded_file($_FILES['ca_file' . $i]['tmp_name'])) {
                        SaveServerUpload($status, $_FILES['ca_file' . $i], $i);
                    }

                    exec('sudo /usr/local/bin/uci set dct.server.mqtt_ca' . $i . '=' . $_FILES['ca_file' . $i]['name']);
                }

                if (strlen($_FILES['pubulic_cer' . $i]['name']) > 0) {
                    if (is_uploaded_file($_FILES['pubulic_cer' . $i]['tmp_name'])) {
                        SaveServerUpload($status, $_FILES['pubulic_cer' . $i], $i);
                    }

                    exec('sudo /usr/local/bin/uci set dct.server.mqtt_cert' . $i . '=' . $_FILES['pubulic_cer' . $i]['name']);
                }

                if (strlen($_FILES['private_key' . $i]['name']) > 0) {
                    if (is_uploaded_file($_FILES['private_key' . $i]['tmp_name'])) {
                        SaveServerUpload($status, $_FILES['private_key' . $i], $i);
                    }

                    exec('sudo /usr/local/bin/uci set dct.server.mqtt_key' . $i . '=' . $_FILES['private_key' . $i]['name']);
                }
            }
            $serverInfo = array("proto", "encap_type", "server_addr", "http_url", "server_port", "cache_enabled", 
                "register_packet", "register_packet_hex", "heartbeat_packet", "heartbeat_packet_hex", "heartbeat_interval",
                "mqtt_heartbeat_interval", "mqtt_pub_topic", "mqtt_sub_topic", "mqtt_username", "mqtt_password", 
                "mqtt_client_id", "mqtt_tls_enabled", "certificate_type", "mqtt_ca", "mqtt_cert", "mqtt_key", 
                "self_define_var", "var_name1_", "var_value1_", "var_name2_", "var_value2_", "var_name3_", "var_value3_", 
                "mn", "st", "pw");

            foreach ($serverInfo as $info) {
                if ($info != "mqtt_ca" && $info != "mqtt_cert" && $info != "mqtt_key") {
                    exec('sudo /usr/local/bin/uci set dct.server.' . $info . $i . '=' .$_POST[$info . $i]);
                } 
            }

            // exec('sudo /usr/local/bin/uci set dct.server.proto' . $i . '=' .$_POST['protocol' . $i]);
            // exec('sudo /usr/local/bin/uci set dct.server.encap_type' . $i . '=' .$_POST['encap' . $i]);
            // exec('sudo /usr/local/bin/uci set dct.server.server_addr' . $i . '=' .$_POST['server_address' . $i]);
            // exec('sudo /usr/local/bin/uci set dct.server.http_url' . $i . '=' .$_POST['http_url' . $i]);
            // exec('sudo /usr/local/bin/uci set dct.server.server_port' . $i . '=' .$_POST['server_port' . $i]);
            // exec('sudo /usr/local/bin/uci set dct.server.cache_enabled' . $i . '=' .$_POST['cache_enabled' . $i]);
            // exec('sudo /usr/local/bin/uci set dct.server.register_packet' . $i . '=' .$_POST['register_packet' . $i]);
            // exec('sudo /usr/local/bin/uci set dct.server.register_packet_hex' . $i . '=' .$_POST['register_packet_hex' . $i]);
            // exec('sudo /usr/local/bin/uci set dct.server.heartbeat_packet' . $i . '=' .$_POST['heartbeat_packet' . $i]);
            // exec('sudo /usr/local/bin/uci set dct.server.heartbeat_packet_hex' . $i . '=' .$_POST['heartbeat_packet_hex' . $i]);
            // exec('sudo /usr/local/bin/uci set dct.server.heartbeat_interval' . $i . '=' .$_POST['heartbeat_interval' . $i]);
            // exec('sudo /usr/local/bin/uci set dct.server.mqtt_heartbeat_interval' . $i . '=' .$_POST['mqtt_heartbeat_interval' . $i]);
            // exec('sudo /usr/local/bin/uci set dct.server.mqtt_pub_topic' . $i . '=' .$_POST['mqtt_public_topic' . $i]);
            // exec('sudo /usr/local/bin/uci set dct.server.mqtt_sub_topic' . $i . '=' .$_POST['mqtt_subscribe_topic' . $i]);
            // exec('sudo /usr/local/bin/uci set dct.server.mqtt_username' . $i . '=' .$_POST['mqtt_username' . $i]);
            // exec('sudo /usr/local/bin/uci set dct.server.mqtt_password' . $i . '=' .$_POST['mqtt_password' . $i]);
            // exec('sudo /usr/local/bin/uci set dct.server.mqtt_client_id' . $i . '=' .$_POST['client_id' . $i]);
            // exec('sudo /usr/local/bin/uci set dct.server.mqtt_tls_enabled' . $i . '=' .$_POST['mqtt_tls_enabled' . $i]);
            // exec('sudo /usr/local/bin/uci set dct.server.certificate_type' . $i . '=' .$_POST['certificate_type' . $i]);
            // exec('sudo /usr/local/bin/uci set dct.server.self_define_var' . $i . '=' .$_POST['self_define_var' . $i]);
            // exec('sudo /usr/local/bin/uci set dct.server.var_name1_' . $i . '=' .$_POST['var_name1_' . $i]);
            // exec('sudo /usr/local/bin/uci set dct.server.var_value1_' . $i . '=' .$_POST['var_value1_' . $i]);
            // exec('sudo /usr/local/bin/uci set dct.server.var_name2_' . $i . '=' .$_POST['var_name2_' . $i]);
            // exec('sudo /usr/local/bin/uci set dct.server.var_value2_' . $i . '=' .$_POST['var_value2_' . $i]);
            // exec('sudo /usr/local/bin/uci set dct.server.var_name3_' . $i . '=' .$_POST['var_name3_' . $i]);
            // exec('sudo /usr/local/bin/uci set dct.server.var_value3_' . $i . '=' .$_POST['var_value3_' . $i]);
            // exec('sudo /usr/local/bin/uci set dct.server.mn' . $i . '=' .$_POST['mn' . $i]);
            // exec('sudo /usr/local/bin/uci set dct.server.st' . $i . '=' .$_POST['st' . $i]);
            // exec('sudo /usr/local/bin/uci set dct.server.pw' . $i . '=' .$_POST['password' . $i]);
        }
    }

    exec('sudo /usr/local/bin/uci commit dct');

    $status->addMessage('dct configuration updated ', 'success');
    return true;
}
