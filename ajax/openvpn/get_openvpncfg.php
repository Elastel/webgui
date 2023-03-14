<?php

require '../../includes/csrf.php';
require_once '../../includes/config.php';

exec("sudo /usr/local/bin/uci get openvpn.openvpn.type", $type);
$vpndata['type'] = $type[0];

// fetch dhcpcd.conf settings for interface
if ($type[0] == 'config') {
    $vpndata['type'] = 'config';
    exec("sudo /usr/local/bin/uci get openvpn.openvpn.role", $role);
    $vpndata['role'] = $role[0];
    exec("sudo /usr/local/bin/uci get openvpn.openvpn.auth_type", $auth_type);
    $vpndata['auth_type'] = $auth_type[0];
    
    $conf = file_get_contents('/etc/openvpn/' . $role[0] . '/' . $role[0] . '.conf');
    preg_match('/proto\s(.*)/', $conf, $proto);
    preg_match('/port\s(.*)/', $conf, $port);
    preg_match('/dev\s(.*)/', $conf, $dev);
    preg_match('/cipher\s(.*)/', $conf, $cipher);
    preg_match('/comp-lzo\s(.*)/', $conf, $lzo);
    if ($lzo[1] == 'yes' || $lzo[1] == 'no' || $lzo[1] == 'adaptive') {
        $vpndata['comp-lzo'] = $lzo[1];
    } else {
        $vpndata['comp-lzo'] = 'no';
    }

    preg_match('/ca\s\/etc\/openvpn\/(.*)/', $conf, $ca_path);
    if ($ca_path != NULL) {
        $arrca = explode("/", $ca_path[1]);
        if (count($arrca) >= 2) {
            $vpndata['ca'] = $arrca[1];
        } 
    }

    if ($role[0] == 'client') {
        preg_match('/tls-auth\s(.*)\s1/', $conf, $ta_path);
        $vpndata['ta'] = $ta_path[1];

        preg_match('/remote\s(.*)/', $conf, $remote);
        $vpndata['vpn_server'] = $remote[1];
    } else {
        preg_match('/tls-auth\s(.*)\s0/', $conf, $ta_path);
        $vpndata['ta'] = $ta_path[1];

        preg_match('/server\s(.*)/', $conf, $server);
        $vpndata['server'] = $server[1];
        if (isset($vpndata['server'])) {
            $arrServer = explode(" ", $vpndata['server']);
            if (count($arrServer) >= 2) {
                $vpndata['tunnel_subnet'] = $arrServer[0];
                $vpndata['tunnel_mask'] = $arrServer[1];
            }
        }

        preg_match('/keepalive\s(.*)/', $conf, $keepalive);
        $vpndata['keepalive'] = $keepalive[1];

        preg_match('/dh\s\/etc\/openvpn\/(.*)/', $conf, $dh_path);
        if ($dh_path != NULL) {
            $arrdh = explode("/", $dh_path[1]);
            if (count($arrdh) >= 2) {
                $vpndata['dh'] = $arrdh[1];
            } 
        }
    }

    preg_match('/cert\s\/etc\/openvpn\/(.*)/', $conf, $cert_path);
    if ($cert_path != NULL) {
        $arrcert = explode("/", $cert_path[1]);
        if (count($arrcert) >= 2) {
            $vpndata['cert'] = $arrcert[1];
        } 
    }

    preg_match('/key\s\/etc\/openvpn\/(.*)/', $conf, $key_path);
    if ($key_path != NULL) {
        $arrkey = explode("/", $key_path[1]);
        if (count($arrcert) >= 2) {
            $vpndata['key'] = $arrkey[1];
        } 
    }
    if ($auth_type[0] == 'user_pass') {
        exec("sudo /usr/local/bin/uci get openvpn.openvpn.username", $username);
        exec("sudo /usr/local/bin/uci get openvpn.openvpn.password", $password);
        $vpndata['username'] = $username[0];
        $vpndata['password'] = $password[0];
    }

    $vpndata['proto'] = $proto[1];
    $vpndata['port'] = $port[1];
    $vpndata['dev'] = $dev[1];
    $vpndata['cipher'] = $cipher[1];
} else if ($type[0] == 'ovpn') {
    $vpndata['type'] = 'ovpn';
    exec("sudo /usr/local/bin/uci get openvpn.openvpn.role", $role);
    $vpndata['role'] = $role[0];
    exec('basename /etc/openvpn/' . $role[0] . '/*.ovpn', $ovpn_file);
    if (file_exists('/etc/openvpn/' . $role[0] . '/' . $ovpn_file[0])) {
        $vpndata['ovpn'] = $ovpn_file[0];
    }
} else {
    $vpndata['type'] = 'off';
}

echo json_encode($vpndata);
