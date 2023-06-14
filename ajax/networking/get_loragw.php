<?php

require '../../includes/csrf.php';
require_once '../../includes/config.php';

$type = $_GET['type'];

if (isset($type)) {
    if ($type == "lorawan") {
        $json_string = file_get_contents("/etc/global_conf.json");
        $data = json_decode($json_string, true);

        exec("sudo /usr/local/bin/uci get loragw.loragw.type", $type);
        $json_strings['type'] = $type[0];

        $general = array('server_address', 'serv_port_up', 'serv_port_down', 'gateway_ID',
        'keepalive_interval', 'stat_interval');

        foreach ($general as $info) {
            if ($data['gateway_conf'][$info] != '') {
                $json_strings[$info] = $data['gateway_conf'][$info];
            }
        }

        if ($data['SX130x_conf']['radio_0']['enable'] == 'true') {
            $json_strings['radio0_enable'] = '1';
            $json_strings['radio0_frequency'] = $data['SX130x_conf']['radio_0']['freq'];
            if ( $data['SX130x_conf']['radio_0']['tx_enable'] == 'true') {
                $json_strings['radio0_tx'] = '1';
                if ($data['SX130x_conf']['radio_0']['tx_freq_min'] != '') {
                    $json_strings['radio0_tx_min'] = $data['SX130x_conf']['radio_0']['tx_freq_min'];
                }

                if ($data['SX130x_conf']['radio_0']['tx_freq_max'] != '') {
                    $json_strings['radio0_tx_max'] = $data['SX130x_conf']['radio_0']['tx_freq_max'];
                }
            } else {
                $json_strings['radio0_tx'] = '0';
            }
        } else {
            $json_strings['radio0_enable'] = '0';
        }

        if ($data['SX130x_conf']['radio_1']['enable'] == 'true') {
            $json_strings['radio1_enable'] = '1';
            $json_strings['radio1_frequency'] = $data['SX130x_conf']['radio_1']['freq'];
            if ( $data['SX130x_conf']['radio_1']['tx_enable'] == 'true') {
                $json_strings['radio1_tx'] = '1';
            } else {
                $json_strings['radio1_tx'] = '0';
            }
        } else {
            $json_strings['radio1_enable'] = '0';
        }

        $channels = array('channel_enable', 'channel_radio', 'channel_if');
        for ($i = 0; $i < 8; $i++) {
            if ($data['SX130x_conf']['chan_multiSF_' . $i]['enable'] == 'true') {
                $json_strings['channel_enable' . $i] = '1';
            } else {
                $json_strings['channel_enable' . $i] = '0';
            }

            $json_strings['channel_radio' . $i] = $data['SX130x_conf']['chan_multiSF_' . $i]['radio'];
            $json_strings['channel_if' . $i] = $data['SX130x_conf']['chan_multiSF_' . $i]['if'];

        }

        exec('sudo /usr/local/bin/uci get loragw.loragw.frequency', $freq);
        $json_strings['frequency'] = $freq[0] != null ? $freq[0] : '0';
    }

    echo json_encode($json_strings);
}