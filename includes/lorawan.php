<?php

require_once 'includes/status_messages.php';
require_once 'config.php';

function DisplayLorawan()
{   
    $status = new StatusMessages();

    if (!RASPI_MONITOR_ENABLED) {
        if (isset($_POST['savesettings']) || isset($_POST['applysettings'])) {
            saveLorawanConfig($status);  
            
            if (isset($_POST['applysettings'])) {
                sleep(1);
                exec('sudo /etc/init.d/loragw restart > /dev/null');
            }
        }
    }

    echo renderTemplate("lorawan", compact('status'));
}

function saveLorawanConfig($status)
{
    $general = array('server_address', 'serv_port_up', 'serv_port_down', 'gateway_ID',
        'keepalive_interval', 'stat_interval');

    $radio = array('radio0_enable', 'radio0_frequency', 'radio0_tx', 'radio0_tx_min', 'radio0_tx_max',
        'radio1_enable', 'radio1_frequency', 'radio1_tx');

    if ($_POST['type'] == "1") {
        $json_string = file_get_contents("/etc/global_conf.json");
        $data = json_decode($json_string, true);
        foreach ($general as $info) {
            if ($_POST[$info] != '') {
                if ($info == 'serv_port_up' || $info == 'serv_port_down' || 
                    $info == 'keepalive_interval' || $info == 'stat_interval') {
                        $data['gateway_conf'][$info] = intval($_POST[$info]);
                } else {
                    $data['gateway_conf'][$info] = $_POST[$info];
                }
            }
        }

        if ($_POST['radio0_enable'] == '1') {
            $data['SX130x_conf']['radio_0']['enable'] = true;
            if ($_POST['radio0_frequency'] != '') {
                $data['SX130x_conf']['radio_0']['freq'] = intval($_POST['radio0_frequency']);
            }

            if ($_POST['radio0_tx'] == '1') {
                $data['SX130x_conf']['radio_0']['tx_enable'] = true;
                if ($_POST['radio0_tx_min'] != '') {
                    $data['SX130x_conf']['radio_0']['tx_freq_min'] = intval($_POST['radio0_tx_min']);
                }

                if ($_POST['radio0_tx_max'] != '') {
                    $data['SX130x_conf']['radio_0']['tx_freq_max'] = intval($_POST['radio0_tx_max']);
                }
            } else {
                $data['SX130x_conf']['radio_0']['tx_enable'] = false;
            }
        } else {
            $data['SX130x_conf']['radio_0']['enable'] = false;
        }

        if ($_POST['radio1_enable'] == '1') {
            $data['SX130x_conf']['radio_1']['enable'] = true;
            if ($_POST['radio1_frequency'] != '') {
                $data['SX130x_conf']['radio_1']['freq'] = intval($_POST['radio1_frequency']);
            }

            if ($_POST['radio1_tx'] == '1') {
                $data['SX130x_conf']['radio_1']['tx_enable'] = true;
            } else {
                $data['SX130x_conf']['radio_1']['tx_enable'] = false;
            }
        } else {
            $data['SX130x_conf']['radio_1']['enable'] = false;
        }
        
        $channels = array('channel_enable', 'channel_radio', 'channel_if');
        for ($i = 0; $i < 8; $i++) {
            if ($_POST['channel_enable' . $i] == '1') {
                $data['SX130x_conf']['chan_multiSF_' . $i]['enable'] = true;
                if ($_POST['channel_radio' . $i] != '') {
                    $data['SX130x_conf']['chan_multiSF_' . $i]['radio'] = intval($_POST['channel_radio' . $i]);
                }

                if ($_POST['channel_if' . $i] != '') {
                    $data['SX130x_conf']['chan_multiSF_' . $i]['if'] = intval($_POST['channel_if' . $i]);
                }
            } else {
                $data['SX130x_conf']['chan_multiSF_' . $i]['enable'] = false;
            }
        }

        $json_strings = json_encode($data);
        file_put_contents("/tmp/global_conf.json", $json_strings);
        exec("sudo mv /tmp/global_conf.json /etc/global_conf.json");

        $status->addMessage('lorawan configuration updated ', 'success');
    } else {
        exec("sudo /usr/local/bin/uci get loragw.loragw.type", $cur_type);
        if ($cur_type[0] != "1") {
            $status->addMessage('Type service must be lorawan service first ', 'danger');
        }
    }

    exec("sudo /usr/local/bin/uci set loragw.loragw.type=" .$_POST['type']);
    exec("sudo /usr/local/bin/uci set loragw.loragw.frequency=" .$_POST['frequency']);
    exec("sudo /usr/local/bin/uci commit loragw");
}
