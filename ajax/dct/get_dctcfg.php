<?php

require '../../includes/csrf.php';
require_once '../../includes/config.php';

$type = $_GET['type'];

if ($type == "basic") {
    exec("/usr/local/bin/uci get dct.basic.enabled", $enabled);
    $dctdata['enabled'] = $enabled[0];
    if ($enabled[0] == "1") {
        exec("sudo /usr/local/bin/uci get dct.basic.collect_period", $collect_period);
        exec("sudo /usr/local/bin/uci get dct.basic.report_period", $report_period);
        exec("sudo /usr/local/bin/uci get dct.basic.cache_enabled", $cache_enabled);
        exec("sudo /usr/local/bin/uci get dct.basic.cache_day", $cache_day);
        exec("sudo /usr/local/bin/uci get dct.basic.minute_enabled", $minute_enabled);
        exec("sudo /usr/local/bin/uci get dct.basic.minute_period", $minute_period);
        exec("sudo /usr/local/bin/uci get dct.basic.hour_enabled", $hour_enabled);
        exec("sudo /usr/local/bin/uci get dct.basic.day_enabled", $day_enabled);

        $dctdata['collect_period'] = $collect_period[0];
        $dctdata['report_period'] = $report_period[0];
        $dctdata['cache_enabled'] = $cache_enabled[0];
        $dctdata['cache_day'] = $cache_day[0];
        $dctdata['minute_enabled'] = $minute_enabled[0];
        $dctdata['minute_period'] = $minute_period[0];
        $dctdata['hour_enabled'] = $hour_enabled[0];
        $dctdata['day_enabled'] = $day_enabled[0];
    } 
} else if ($type == 'interfaces') {
    $x = 1;
    $j = 0;
    for ($i = 0; $i < 4; $i++) {
        exec('sudo /usr/local/bin/uci get dct.com.enabled' . ($i + $x), $com_enabled);
        $dctdata['com_enabled'][($i + $x)] = $com_enabled[$i];
        if ($com_enabled[$i] == '1') {
            exec('sudo /usr/local/bin/uci get dct.com.baudrate' . ($i + $x), $baudrate);
            exec('sudo /usr/local/bin/uci get dct.com.databit' . ($i + $x), $databit);
            exec('sudo /usr/local/bin/uci get dct.com.stopbit' . ($i + $x), $stopbit);
            exec('sudo /usr/local/bin/uci get dct.com.parity' . ($i + $x), $parity);
            exec('sudo /usr/local/bin/uci get dct.com.frame_interval' . ($i + $x), $com_frame_interval);
            exec('sudo /usr/local/bin/uci get dct.com.proto' . ($i + $x), $com_protocol);
            if ($com_protocol[$j] == "0") {
                exec('sudo /usr/local/bin/uci get dct.com.cmd_interval' . ($i + $x), $com_command_interval);
                $dctdata['com_command_interval'][($i + $x)] = $com_command_interval[$j];
            } else {
                exec('sudo /usr/local/bin/uci get dct.com.report_center' . ($i + $x), $com_reporting_center);
                $dctdata['com_reporting_center'][($i + $x)] = $com_reporting_center[$j];
            }
            
            $dctdata['baudrate'][($i + $x)] = $baudrate[$j];
            $dctdata['databit'][($i + $x)] = $databit[$j];
            $dctdata['stopbit'][($i + $x)] = $stopbit[$j];
            $dctdata['parity'][($i + $x)] = $parity[$j];
            $dctdata['com_frame_interval'][($i + $x)] = $com_frame_interval[$j];
            $dctdata['com_protocol'][($i + $x)] = $com_protocol[$j];
            $j++;
        }
    }

    $j = 0;
    for ($i = 0; $i < 5; $i++) {
        exec('/usr/local/bin/uci get dct.tcp_server.enabled' . ($i + $x), $tcp_enabled);
        $dctdata['tcp_enabled'][($i + $x)] = $tcp_enabled[$i];
        if ($tcp_enabled[$i] == '1') {
            exec('sudo /usr/local/bin/uci get dct.tcp_server.server_addr' . ($i + $x), $server_address);
            exec('sudo /usr/local/bin/uci get dct.tcp_server.server_port' . ($i + $x), $server_port);
            exec('sudo /usr/local/bin/uci get dct.tcp_server.frame_interval' . ($i + $x), $tcp_frame_interval);
            exec('sudo /usr/local/bin/uci get dct.tcp_server.proto' . ($i + $x), $tcp_protocol);

            if ($tcp_protocol[$j] == "0") {
                exec('sudo /usr/local/bin/uci get dct.tcp_server.cmd_interval' . ($i + $x), $tcp_command_interval);
                $dctdata['tcp_command_interval'][($i + $x)] = $tcp_command_interval[$j];
            } else if ($tcp_protocol[$j] == "1") {
                exec('sudo /usr/local/bin/uci get dct.tcp_server.report_center' . ($i + $x), $tcp_reporting_center);
                $dctdata['tcp_reporting_center'][($i + $x)] = $tcp_reporting_center[$j];
            } else {
                exec('sudo /usr/local/bin/uci get dct.tcp_server.rack' . ($i + $x), $rack);
                exec('sudo /usr/local/bin/uci get dct.tcp_server.slot' . ($i + $x), $slot);
                $dctdata['rack'][($i + $x)] = $rack[$j];
                $dctdata['slot'][($i + $x)] = $slot[$j];
            }

            $dctdata['server_address'][($i + $x)] = $server_address[$j];
            $dctdata['server_port'][($i + $x)] = $server_port[$j];
            $dctdata['tcp_frame_interval'][($i + $x)] = $tcp_frame_interval[$j];
            $dctdata['tcp_protocol'][($i + $x)] = $tcp_protocol[$j];

            $j++;
        }
    }
} else if ($type == "modbus") {
    $data_type_value = array("Unsigned 16Bits AB", "Unsigned 16Bits BA", "Signed 16Bits AB", "Signed 16Bits BA",
    "Unsigned 32Bits ABCD", "Unsigned 32Bits BADC", "Unsigned 32Bits CDAB", "Unsigned 32Bits DCBA",
    "Signed 32Bits ABCD", "Signed 32Bits BADC", "Signed 32Bits CDAB", "Signed 32Bits DCBA",
    "Float ABCD", "Float BADC", "Float CDAB", "Float DCBA");

    exec("sudo /usr/local/bin/uci get dct.basic.modbus_count", $modbus_count);
    $dctdata['modbus_count'] = $modbus_count[0];
    
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

    exec("sudo /usr/local/bin/uci get dct.basic.s7_count", $s7_count);
    $dctdata['s7_count'] = $s7_count[0];
    
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
} else if ($type == "server") {
    $x = 1;
    $j = 0;
    for ($i = 0; $i < 5; $i++) {
        exec("/usr/local/bin/uci get dct.server.enabled" . ($i + $x), $enabled);
        $dctdata['enabled'][($i + $x)] = $enabled[$i];
        if ($enabled[$i] == "1") {
            exec("sudo /usr/local/bin/uci get dct.server.protocol" . ($i + $x), $protocol);
            exec("sudo /usr/local/bin/uci get dct.server.encap_type" . ($i + $x), $encap);
            exec("sudo /usr/local/bin/uci get dct.server.server_addr" . ($i + $x), $server_address);
            exec("sudo /usr/local/bin/uci get dct.server.http_url" . ($i + $x), $http_url);
            exec("sudo /usr/local/bin/uci get dct.server.server_port" . ($i + $x), $server_port);
            exec("sudo /usr/local/bin/uci get dct.server.cache_enabled" . ($i + $x), $cache_enabled);
            exec("sudo /usr/local/bin/uci get dct.server.register_packet" . ($i + $x), $register_packet);
            exec("sudo /usr/local/bin/uci get dct.server.register_packet_hex" . ($i + $x), $register_packet_hex);
            exec("sudo /usr/local/bin/uci get dct.server.heartbeat_packet" . ($i + $x), $heartbeat_packet);
            exec("sudo /usr/local/bin/uci get dct.server.heartbeat_packet_hex" . ($i + $x), $heartbeat_packet_hex);
            exec("sudo /usr/local/bin/uci get dct.server.heartbeat_interval" . ($i + $x), $heartbeat_interval);
            exec("sudo /usr/local/bin/uci get dct.server.mqtt_heartbeat_interval" . ($i + $x), $mqtt_heartbeat_interval);
            exec("sudo /usr/local/bin/uci get dct.server.mqtt_pub_topic" . ($i + $x), $mqtt_public_topic);
            exec("sudo /usr/local/bin/uci get dct.server.mqtt_sub_topic" . ($i + $x), $mqtt_subscribe_topic);
            exec("sudo /usr/local/bin/uci get dct.server.mqtt_username" . ($i + $x), $mqtt_username);
            exec("sudo /usr/local/bin/uci get dct.server.mqtt_password" . ($i + $x), $mqtt_password);
            exec("sudo /usr/local/bin/uci get dct.server.mqtt_client_id" . ($i + $x), $client_id);
            exec("sudo /usr/local/bin/uci get dct.server.mqtt_tls_enabled" . ($i + $x), $mqtt_tls_enabled);
            exec("sudo /usr/local/bin/uci get dct.server.certificate_type" . ($i + $x), $certificate_type);
            exec("sudo /usr/local/bin/uci get dct.server.mqtt_ca" . ($i + $x), $ca_file);
            exec("sudo /usr/local/bin/uci get dct.server.mqtt_cert" . ($i + $x), $pubulic_cer);
            exec("sudo /usr/local/bin/uci get dct.server.mqtt_key" . ($i + $x), $private_key);
            exec("sudo /usr/local/bin/uci get dct.server.self_define_var" . ($i + $x), $self_define_var);
            exec("sudo /usr/local/bin/uci get dct.server.var_name1_" . ($i + $x), $var_name1);
            exec("sudo /usr/local/bin/uci get dct.server.var_value1_" . ($i + $x), $var_value1);
            exec("sudo /usr/local/bin/uci get dct.server.var_name2_" . ($i + $x), $var_name2);
            exec("sudo /usr/local/bin/uci get dct.server.var_value2_" . ($i + $x), $var_value2);
            exec("sudo /usr/local/bin/uci get dct.server.var_name3_" . ($i + $x), $var_name3);
            exec("sudo /usr/local/bin/uci get dct.server.var_value3_" . ($i + $x), $var_value3);
            exec("sudo /usr/local/bin/uci get dct.server.mn" . ($i + $x), $mn);
            exec("sudo /usr/local/bin/uci get dct.server.st" . ($i + $x), $st);
            exec("sudo /usr/local/bin/uci get dct.server.pw" . ($i + $x), $password);

            $dctdata['protocol'][($i + $x)] = $protocol[$j];
            $dctdata['encap'][($i + $x)] = $encap[$j];
            $dctdata['server_address'][($i + $x)] = $server_address[$j];
			$dctdata['http_url'][($i + $x)] = $http_url[$j];
            $dctdata['server_port'][($i + $x)] = $server_port[$j];
            $dctdata['cache_enabled'][($i + $x)] = $cache_enabled[$j];
            $dctdata['register_packet'][($i + $x)] = $register_packet[$j];
            $dctdata['register_packet_hex'][($i + $x)] = $register_packet_hex[$j];
            $dctdata['heartbeat_packet'][($i + $x)] = $heartbeat_packet[$j];
            $dctdata['heartbeat_packet_hex'][($i + $x)] = $heartbeat_packet_hex[$j];
            $dctdata['heartbeat_interval'][($i + $x)] = $heartbeat_interval[$j];
            $dctdata['mqtt_heartbeat_interval'][($i + $x)] = $mqtt_heartbeat_interval[$j];
            $dctdata['mqtt_public_topic'][($i + $x)] = $mqtt_public_topic[$j];
            $dctdata['mqtt_subscribe_topic'][($i + $x)] = $mqtt_subscribe_topic[$j];
            $dctdata['mqtt_username'][($i + $x)] = $mqtt_username[$j];
            $dctdata['mqtt_password'][($i + $x)] = $mqtt_password[$j];
            $dctdata['client_id'][($i + $x)] = $client_id[$j];
            $dctdata['mqtt_tls_enabled'][($i + $x)] = $mqtt_tls_enabled[$j];
            $dctdata['certificate_type'][($i + $x)] = $certificate_type[$j];
            $dctdata['ca_file'][($i + $x)] = $ca_file[$j];
            $dctdata['pubulic_cer'][($i + $x)] = $pubulic_cer[$j];
            $dctdata['private_key'][($i + $x)] = $private_key[$j];
            $dctdata['self_define_var'][($i + $x)] = $self_define_var[$j];
            $dctdata['var_name1'][($i + $x)] = $var_name1[$j];
            $dctdata['var_value1'][($i + $x)] = $var_value1[$j];
            $dctdata['var_name2'][($i + $x)] = $var_name2[$j];
            $dctdata['var_value2'][($i + $x)] = $var_value2[$j];
            $dctdata['var_name3'][($i + $x)] = $var_name3[$j];
            $dctdata['var_value3'][($i + $x)] = $var_value3[$j];
            $dctdata['mn'][($i + $x)] = $mn[$j];
            $dctdata['st'][($i + $x)] = $st[$j];
            $dctdata['password'][($i + $x)] = $password[$j];
            $j++;

            $ca_file_dir = '/etc/ssl/server' . ($i + $x) . '/' . $ca_file[$i];
            if (file_exists($ca_file_dir) && strlen($ca_file[$i]) > 0) {
                $dctdata['ca_file_exists'][($i + $x)] = '1';
            }else{
                $dctdata['ca_file_exists'][($i + $x)] = '0';
            }

            $cer_file_dir = '/etc/ssl/server' . ($i + $x) . '/' . $pubulic_cer[$i];
            if (file_exists($cer_file_dir) && strlen($pubulic_cer[$i]) > 0) {
                $dctdata['cer_file_exists'][($i + $x)] = '1';
            }else{
                $dctdata['cer_file_exists'][($i + $x)] = '0';
            }

            $key_file_dir = '/etc/ssl/server' . ($i + $x) . '/' . $private_key[$i];
            if (file_exists($key_file_dir) && strlen($private_key[$i]) > 0) {
                $dctdata['key_file_exists'][($i + $x)] = '1';
            }else{
                $dctdata['key_file_exists'][($i + $x)] = '0';
            }
        }
    }
}

echo json_encode($dctdata);
