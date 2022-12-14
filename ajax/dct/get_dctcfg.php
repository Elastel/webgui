<?php

require '../../includes/csrf.php';
require_once '../../includes/config.php';

$type = $_GET['type'];

if ($type == "basic") {
    $arrInfo = array('collect_period', 'report_period', 'cache_enabled', 'cache_day', 'minute_enabled',
        'minute_period', 'hour_enabled', 'day_enabled');

    exec("/usr/local/bin/uci get dct.basic.enabled", $enabled);
    $dctdata['enabled'] = $enabled[0];
    if ($enabled[0] == "1") {
        foreach ($arrInfo as $info) {
            unset($val);
            exec("sudo /usr/local/bin/uci get dct.basic." . $info, $val);
            $dctdata[$info] = $val[0];
        }
    } 
} else if ($type == 'interfaces') {
    $x = 1;
    $arrInfo = array('baudrate', 'databit', 'stopbit', 'parity', 'frame_interval',
        'proto', 'cmd_interval', 'report_center');

    for ($i = 0; $i < 4; $i++) {
        exec('sudo /usr/local/bin/uci get dct.com.enabled' . ($i + $x), $com_enabled);
        $dctdata['com_enabled'][($i + $x)] = $com_enabled[$i];
        if ($com_enabled[$i] == '1') {
            foreach ($arrInfo as $info) {
                unset($val);
                exec('sudo /usr/local/bin/uci get dct.com.' . $info . ($i + $x), $val);
                if ($info == 'frame_interval' || $info == 'proto' || $info == 'cmd_interval'
                    ||  $info == 'report_center') {
                    $dctdata['com_' . $info][($i + $x)] = $val[0];
                } else {
                    $dctdata[$info][($i + $x)] = $val[0];
                }
            }
        }
    }

    $arrInfo = array('server_addr', 'server_port', 'frame_interval', 'proto', 'cmd_interval', 'report_center', 
    'rack', 'slot');

    for ($i = 0; $i < 5; $i++) {
        exec('/usr/local/bin/uci get dct.tcp_server.enabled' . ($i + $x), $tcp_enabled);
        $dctdata['tcp_enabled'][($i + $x)] = $tcp_enabled[$i];
        if ($tcp_enabled[$i] == '1') {
            foreach ($arrInfo as $info) {
                unset($val);
                exec('sudo /usr/local/bin/uci get dct.tcp_server.' . $info . ($i + $x), $val);
                if ($info == 'frame_interval' || $info == 'proto' || $info == 'cmd_interval' 
                    ||  $info == 'report_center') {
                    $dctdata['tcp_' . $info][($i + $x)] = $val[0];
                } else {
                    $dctdata[$info][($i + $x)] = $val[0];
                }
            }
        }
    }
} else if ($type == "modbus") {
    $data_type_value = array("Unsigned 16Bits AB", "Unsigned 16Bits BA", "Signed 16Bits AB", "Signed 16Bits BA",
    "Unsigned 32Bits ABCD", "Unsigned 32Bits BADC", "Unsigned 32Bits CDAB", "Unsigned 32Bits DCBA",
    "Signed 32Bits ABCD", "Signed 32Bits BADC", "Signed 32Bits CDAB", "Signed 32Bits DCBA",
    "Float ABCD", "Float BADC", "Float CDAB", "Float DCBA");

    exec("sudo /usr/sbin/uci_get_count modbus", $modbus_count);
    $dctdata['count'] = $modbus_count[0];
    
    for ($i = 0; $i < number_format($modbus_count[0]); $i++) {
        exec("sudo /usr/local/bin/uci get dct.@modbus[$i].order", $order);
        exec("sudo /usr/local/bin/uci get dct.@modbus[$i].device_name", $device_name);
        exec("sudo /usr/local/bin/uci get dct.@modbus[$i].belonged_com", $belonged_com);
        exec("sudo /usr/local/bin/uci get dct.@modbus[$i].factor_name", $factor_name);
        exec("sudo /usr/local/bin/uci get dct.@modbus[$i].device_id", $device_id);
        exec("sudo /usr/local/bin/uci get dct.@modbus[$i].function_code", $function_code);
        exec("sudo /usr/local/bin/uci get dct.@modbus[$i].reg_addr", $reg_addr);
        exec("sudo /usr/local/bin/uci get dct.@modbus[$i].reg_count", $reg_count);
        exec("sudo /usr/local/bin/uci get dct.@modbus[$i].data_type", $data_type);
        exec("sudo /usr/local/bin/uci get dct.@modbus[$i].server_center", $server_center);
        exec("sudo /usr/local/bin/uci get dct.@modbus[$i].operator", $operator);
        exec("sudo /usr/local/bin/uci get dct.@modbus[$i].operand", $operand);
        exec("sudo /usr/local/bin/uci get dct.@modbus[$i].ex", $ex);
        exec("sudo /usr/local/bin/uci get dct.@modbus[$i].accuracy", $accuracy);
        exec("sudo /usr/local/bin/uci get dct.@modbus[$i].enabled", $enabled);

        $dctdata['order'][$i] = $order[$i];
        $dctdata['device_name'][$i] = $device_name[$i];
        $dctdata['belonged_com'][$i] = $belonged_com[$i];
        $dctdata['factor_name'][$i] = $factor_name[$i];
        $dctdata['device_id'][$i] = $device_id[$i];
        $dctdata['function_code'][$i] = $function_code[$i];
        $dctdata['reg_addr'][$i] = $reg_addr[$i];
        $dctdata['reg_count'][$i] = $reg_count[$i];
        $dctdata['data_type'][$i] = $data_type_value[number_format($data_type[$i])];
        $dctdata['server_center'][$i] = $server_center[$i];
        $dctdata['operator'][$i] = $operator[$i];
        $dctdata['operand'][$i] = $operand[$i];
        $dctdata['ex'][$i] = $ex[$i];
        $dctdata['accuracy'][$i] = $accuracy[$i];
        $dctdata['enabled'][$i] = ($enabled[$i] == '1') ? 'true' : 'false';
    }
} else if ($type == "s7") {
    $reg_type_value = array("I", "Q", "M", "DB", "V", "C", "T");
    $word_len_value = array("Bit", "Byte", "Word", "DWord", "Real", "Counter", "Timer");

    exec("sudo /usr/sbin/uci_get_count s7", $s7_count);
    $dctdata['count'] = $s7_count[0];
    
    for ($i = 0; $i < number_format($s7_count[0]); $i++) {
        exec("sudo /usr/local/bin/uci get dct.@s7[$i].order", $order);
        exec("sudo /usr/local/bin/uci get dct.@s7[$i].device_name", $device_name);
        exec("sudo /usr/local/bin/uci get dct.@s7[$i].belonged_com", $belonged_com);
        exec("sudo /usr/local/bin/uci get dct.@s7[$i].factor_name", $factor_name);
        exec("sudo /usr/local/bin/uci get dct.@s7[$i].reg_type", $reg_type);
        exec("sudo /usr/local/bin/uci get dct.@s7[$i].reg_addr", $reg_addr);
        exec("sudo /usr/local/bin/uci get dct.@s7[$i].reg_count", $reg_count);
        exec("sudo /usr/local/bin/uci get dct.@s7[$i].word_len", $word_len);
        exec("sudo /usr/local/bin/uci get dct.@s7[$i].server_center", $server_center);
        exec("sudo /usr/local/bin/uci get dct.@s7[$i].operator", $operator);
        exec("sudo /usr/local/bin/uci get dct.@s7[$i].operand", $operand);
        exec("sudo /usr/local/bin/uci get dct.@s7[$i].ex", $ex);
        exec("sudo /usr/local/bin/uci get dct.@s7[$i].accuracy", $accuracy);
        exec("sudo /usr/local/bin/uci get dct.@s7[$i].enabled", $enabled);

        $dctdata['order'][$i] = $order[$i];
        $dctdata['device_name'][$i] = $device_name[$i];
        $dctdata['belonged_com'][$i] = $belonged_com[$i];
        $dctdata['factor_name'][$i] = $factor_name[$i];
        $dctdata['reg_type'][$i] = $reg_type_value[number_format($reg_type[$i])];
        $dctdata['reg_addr'][$i] = $reg_addr[$i];
        $dctdata['reg_count'][$i] = $reg_count[$i];
        $dctdata['word_len'][$i] = $word_len_value[number_format($word_len[$i])];
        $dctdata['server_center'][$i] = $server_center[$i];
        $dctdata['operator'][$i] = $operator[$i];
        $dctdata['operand'][$i] = $operand[$i];
        $dctdata['ex'][$i] = $ex[$i];
        $dctdata['accuracy'][$i] = $accuracy[$i];
        $dctdata['enabled'][$i] = ($enabled[$i] == '1') ? 'true' : 'false';
    }
} else if ($type == "adc") {
    $cap_type_value = array("4-20mA", "0-10V");

    exec("sudo /usr/sbin/uci_get_count adc", $count);
    $dctdata['count'] = $count[0];
    
    for ($i = 0; $i < number_format($count[0]); $i++) {
        exec("sudo /usr/local/bin/uci get dct.@adc[$i].device_name", $device_name);
        exec("sudo /usr/local/bin/uci get dct.@adc[$i].index", $index);
        exec("sudo /usr/local/bin/uci get dct.@adc[$i].factor_name", $factor_name);
        exec("sudo /usr/local/bin/uci get dct.@adc[$i].cap_type", $cap_type);
        exec("sudo /usr/local/bin/uci get dct.@adc[$i].range_down", $range_down);
        exec("sudo /usr/local/bin/uci get dct.@adc[$i].range_up", $range_up);
        exec("sudo /usr/local/bin/uci get dct.@adc[$i].server_center", $server_center);
        exec("sudo /usr/local/bin/uci get dct.@adc[$i].operator", $operator);
        exec("sudo /usr/local/bin/uci get dct.@adc[$i].operand", $operand);
        exec("sudo /usr/local/bin/uci get dct.@adc[$i].ex", $ex);
        exec("sudo /usr/local/bin/uci get dct.@adc[$i].accuracy", $accuracy);
        exec("sudo /usr/local/bin/uci get dct.@adc[$i].enabled", $enabled);

        $dctdata['device_name'][$i] = $device_name[$i];
        $dctdata['index'][$i] = $index[$i];
        $dctdata['factor_name'][$i] = $factor_name[$i];
        $dctdata['cap_type'][$i] = $cap_type_value[number_format($cap_type[$i])];
        $dctdata['range_down'][$i] = $range_down[$i];
        $dctdata['range_up'][$i] = $range_up[$i];
        $dctdata['server_center'][$i] = $server_center[$i];
        $dctdata['operator'][$i] = $operator[$i];
        $dctdata['operand'][$i] = $operand[$i];
        $dctdata['ex'][$i] = $ex[$i];
        $dctdata['accuracy'][$i] = $accuracy[$i];
        $dctdata['enabled'][$i] = ($enabled[$i] == '1') ? 'true' : 'false';
    }
} else if ($type == "di") {
    $mode_value = array("Counting Mode", "Status Mode");
    $method_value = array("Rising Edge", "Falling Edge");

    exec("sudo /usr/sbin/uci_get_count di", $count);
    $dctdata['count'] = $count[0];
    
    for ($i = 0; $i < number_format($count[0]); $i++) {
        exec("sudo /usr/local/bin/uci get dct.@di[$i].device_name", $device_name);
        exec("sudo /usr/local/bin/uci get dct.@di[$i].index", $index);
        exec("sudo /usr/local/bin/uci get dct.@di[$i].factor_name", $factor_name);
        exec("sudo /usr/local/bin/uci get dct.@di[$i].mode", $mode);
        exec("sudo /usr/local/bin/uci get dct.@di[$i].count_method", $count_method);
        exec("sudo /usr/local/bin/uci get dct.@di[$i].debounce_interval", $debounce_interval);
        exec("sudo /usr/local/bin/uci get dct.@di[$i].server_center", $server_center);
        exec("sudo /usr/local/bin/uci get dct.@di[$i].operator", $operator);
        exec("sudo /usr/local/bin/uci get dct.@di[$i].operand", $operand);
        exec("sudo /usr/local/bin/uci get dct.@di[$i].ex", $ex);
        exec("sudo /usr/local/bin/uci get dct.@di[$i].accuracy", $accuracy);
        exec("sudo /usr/local/bin/uci get dct.@di[$i].enabled", $enabled);

        $dctdata['device_name'][$i] = $device_name[$i];
        $dctdata['index'][$i] = $index[$i];
        $dctdata['factor_name'][$i] = $factor_name[$i];
        $dctdata['mode'][$i] = $mode_value[number_format($mode[$i])];
        $dctdata['count_method'][$i] = $method_value[number_format($count_method[$i])];
        $dctdata['debounce_interval'][$i] = $debounce_interval[$i];
        $dctdata['server_center'][$i] = $server_center[$i];
        $dctdata['operator'][$i] = $operator[$i];
        $dctdata['operand'][$i] = $operand[$i];
        $dctdata['ex'][$i] = $ex[$i];
        $dctdata['accuracy'][$i] = $accuracy[$i];
        $dctdata['enabled'][$i] = ($enabled[$i] == '1') ? 'true' : 'false';
    }
} else if ($type == "do") {
    $status_value = array("Open", "Close");

    exec("sudo /usr/sbin/uci_get_count do", $count);
    $dctdata['count'] = $count[0];
    
    for ($i = 0; $i < number_format($count[0]); $i++) {
        exec("sudo /usr/local/bin/uci get dct.@do[$i].device_name", $device_name);
        exec("sudo /usr/local/bin/uci get dct.@do[$i].index", $index);
        exec("sudo /usr/local/bin/uci get dct.@do[$i].factor_name", $factor_name);
        exec("sudo /usr/local/bin/uci get dct.@do[$i].init_status", $init_status);
        exec("sudo /usr/local/bin/uci get dct.@do[$i].server_center", $server_center);
        exec("sudo /usr/local/bin/uci get dct.@do[$i].operator", $operator);
        exec("sudo /usr/local/bin/uci get dct.@do[$i].operand", $operand);
        exec("sudo /usr/local/bin/uci get dct.@do[$i].ex", $ex);
        exec("sudo /usr/local/bin/uci get dct.@do[$i].accuracy", $accuracy);
        exec("sudo /usr/local/bin/uci get dct.@do[$i].enabled", $enabled);

        $dctdata['device_name'][$i] = $device_name[$i];
        $dctdata['index'][$i] = $index[$i];
        $dctdata['factor_name'][$i] = $factor_name[$i];
        $dctdata['init_status'][$i] = $status_value[number_format($init_status[$i])];
        $num = substr($index[$i], -1);
        exec("sudo /usr/sbin/read_do $num[0]", $string);
        $cur_status = substr($string[0], -1);
        $dctdata['cur_status'][$i] = $cur_status[0] == 0 ? 'Open' : 'Close';

        $dctdata['server_center'][$i] = $server_center[$i];
        $dctdata['operator'][$i] = $operator[$i];
        $dctdata['operand'][$i] = $operand[$i];
        $dctdata['ex'][$i] = $ex[$i];
        $dctdata['accuracy'][$i] = $accuracy[$i];
        $dctdata['enabled'][$i] = ($enabled[$i] == '1') ? 'true' : 'false';
    }
} else if ($type == 'server') {
    $serverInfo = array('proto', 'encap_type', 'server_addr', 'http_url', 'server_port', 'cache_enabled', 
    'register_packet', 'register_packet_hex', 'heartbeat_packet', 'heartbeat_packet_hex', 'heartbeat_interval',
    'mqtt_heartbeat_interval', 'mqtt_pub_topic', 'mqtt_sub_topic', 'mqtt_username', 'mqtt_password', 
    'mqtt_client_id', 'mqtt_tls_enabled', 'certificate_type', 'mqtt_ca', 'mqtt_cert', 'mqtt_key', 
    'self_define_var', 'var_name1_', 'var_value1_', 'var_name2_', 'var_value2_', 'var_name3_', 'var_value3_', 
    'mn', 'st', 'pw');

    for ($i = 1; $i < 6; $i++) {
        unset($enabled);
        exec('/usr/local/bin/uci get dct.server.enabled' . $i, $enabled);
        $dctdata['enabled'][$i] = $enabled[0];
        if ($enabled[0] == '1') {
            foreach ($serverInfo as $info) {
                unset($val);
                exec('sudo /usr/local/bin/uci get dct.server.' . $info . $i, $val);
                $dctdata[$info][$i] = $val[0];

                if ($info == 'mqtt_ca') {
                    $ca_file_dir = '/etc/ssl/server' . $i . '/' . $val[0];
                    if (file_exists($ca_file_dir) && strlen($val[0]) > 0) {
                        $dctdata['ca_file_exists'][$i] = '1';
                    }else{
                        $dctdata['ca_file_exists'][$i] = '0';
                    }
                } else if ($info == 'mqtt_cert') {
                    $cer_file_dir = '/etc/ssl/server' . $i . '/' . $val[0];
                    if (file_exists($cer_file_dir) && strlen($val[0]) > 0) {
                        $dctdata['cer_file_exists'][$i] = '1';
                    }else{
                        $dctdata['cer_file_exists'][$i] = '0';
                    }
                } else if ($info == 'mqtt_key') {
                    $key_file_dir = '/etc/ssl/server' . $i . '/' . $val[0];
                    if (file_exists($key_file_dir) && strlen($val[0]) > 0) {
                        $dctdata['key_file_exists'][$i] = '1';
                    }else{
                        $dctdata['key_file_exists'][$i] = '0';
                    }
                }
            }
        }
    }
} else if ($type == 'bacnet') {
    $arr = array('port', 'device_id', 'object_name');
    unset($enabled);
    exec('/usr/local/bin/uci get dct.bacnet.enabled', $enabled);
    $dctdata['enabled'] = $enabled[0];
    if ($enabled[0] == '1') {
        foreach ($arr as $info) {
            unset($val);
            exec('sudo /usr/local/bin/uci get dct.bacnet.' . $info, $val);
            $dctdata[$info] = $val[0];
        }
    }
}

echo json_encode($dctdata);
