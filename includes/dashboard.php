<?php

require_once 'includes/config.php';
require_once 'includes/wifi_functions.php';
require_once 'includes/functions.php';

/**
 * Show dashboard page.
 */
function DisplayDashboard(&$extraFooterScripts)
{
    getWifiInterface();
    $status = new StatusMessages();
    // Need this check interface name for proper shell execution.
    if (!preg_match('/^([a-zA-Z0-9]+)$/', $_SESSION['wifi_client_interface'])) {
        $status->addMessage(_('Interface name invalid.'), 'danger');
        $status->showMessages();
        return;
    }

    if (!function_exists('exec')) {
        $status->addMessage(_('Required exec function is disabled. Check if exec is not added to php disable_functions.'), 'danger');
        $status->showMessages();
        return;
    }
    exec('ip a show '.$_SESSION['ap_interface'], $stdoutIp);
    $stdoutIpAllLinesGlued = implode(" ", $stdoutIp);
    $stdoutIpWRepeatedSpaces = preg_replace('/\s\s+/', ' ', $stdoutIpAllLinesGlued);

    preg_match('/state (UP|DOWN)/i', $stdoutIpWRepeatedSpaces, $matchesState) || $matchesState[1] = 'unknown';
    $interfaceState = $matchesState[1];

    // brought in from template
    $clients = array();
    $moreLink = array();
    $apInterface = array();
    if (file_exists(RASPI_CONFIG.'/hostapd.ini')) {
        $arrHostapdConf = parse_ini_file(RASPI_CONFIG.'/hostapd.ini');
        $bridgedEnable = $arrHostapdConf['BridgedEnable'];
        $clientInterface = $_SESSION['wifi_client_interface'];
        $apInterface = $_SESSION['ap_interface'];
        $MACPattern = '"([[:xdigit:]]{2}:){5}[[:xdigit:]]{2}"';

        $moreLink = "dhcpd_conf";
        exec('cat ' . RASPI_DNSMASQ_LEASES . '| grep -E $(iw dev ' . $apInterface . ' station dump | grep -oE ' . $MACPattern . ' | paste -sd "|")', $clients);
    }
    
    exec("sudo uci get -P /var/state/ network.wan.link", $cur_interface);
    if ($cur_interface[0] == "eth0") {
        $ifaceStatus = "Wired";
        $statusIcon = "up";
    } else if ($cur_interface[0] == "wlan0") {
        $ifaceStatus = "WIFI";
        $statusIcon = "up";
    } else if ($cur_interface[0] == "wwan0") {
        $ifaceStatus = "LTE";
        $statusIcon = "up";
    } else {
        $ifaceStatus = "No network";
        $statusIcon = "down";
    }
    
    exec('cat ' . RASPI_DNSMASQ_LEASES, $leases);
    // fetch dhcpcd.conf settings for interface
    $conf = file_get_contents(RASPI_DHCPCD_CONFIG);
    preg_match('/^#\sRaspAP\seth0\s.*?(?=\s*+$)/ms', $conf, $matched);
    preg_match('/metric\s(\d*)/', $matched[0], $metric);
    preg_match('/static\sip_address=(.*)/', $matched[0], $static_ip);
    preg_match('/static\srouters=(.*)/', $matched[0], $static_routers);
    preg_match('/static\sdomain_name_server=(.*)/', $matched[0], $static_dns);
    // preg_match('/fallback\sstatic_'.$interface.'/', $matched[0], $fallback);
    preg_match('/(?:no)?gateway/', $matched[0], $gateway);
    $dhcpdata['Metric'] = $metric[1];
    $dhcpdata['StaticIP'] = strpos($static_ip[1],'/') ?  substr($static_ip[1], 0, strpos($static_ip[1],'/')) : $static_ip[1];
    $dhcpdata['SubnetMask'] = cidr2mask($static_ip[1]);
    $dhcpdata['StaticRouters'] = $static_routers[1];
    $dhcpdata['StaticDNS'] = $static_dns[1];
    if (isset($dhcpdata['StaticDNS'])) {
        $arrStaticDns = explode(" ", $dhcpdata['StaticDNS']);
        if (count($arrStaticDns) == 1) {
            $dhcpdata['StaticDNS1'] = $arrStaticDns[0];
        } else if (count($arrStaticDns) >= 2) {
            $dhcpdata['StaticDNS1'] = $arrStaticDns[0];
            $dhcpdata['StaticDNS2'] = $arrStaticDns[1];
        }
    }

    if ($dhcpdata['StaticIP'] == null || $dhcpdata['StaticIP'] == ' ') { 
        $routeInfo = getRouteInfo(true);
    } else {
        $routeInfo = array();
        $routeInfo[0]['interface'] = 'eth0';
        $routeInfo[0]['ip-address'] = $dhcpdata['StaticIP'];
        $routeInfo[0]['gateway'] = $dhcpdata['StaticRouters'];
        $routeInfo[0]['netmask'] = $dhcpdata['SubnetMask'];
        // $routeInfo[0]['dns1'] = $dhcpdata['StaticDNS1'];
        // $routeInfo[0]['dns2'] = $dhcpdata['StaticDNS2'];
        exec('cat /sys/class/net/eth0/address', $mac);
        $routeInfo[0]['mac'] = $mac[0];
    }
    
    exec('ip route | grep "default"  | grep -c "wwan0"', $enabled);
    $lteInfo = array();
    if ($enabled[0] == "1") {
        exec('ifconfig wwan0 | grep -oP "(?<=inet )([0-9]{1,3}\.){3}[0-9]{1,3}"', $ip_address);
        exec('ifconfig wwan0 | grep -oP "(?<=netmask )([0-9]{1,3}\.){3}[0-9]{1,3}"', $netmask);
        exec('uci -P /var/state/ get dangle.dev.signal', $signal);
        exec('uci -P /var/state/ get dangle.dev.service', $operator);
        exec('uci -P /var/state/ get dangle.dev.iccid', $iccid);
        exec('uci -P /var/state/ get dangle.dev.imei', $imsi);
        $lte_status="connected";

        $lteInfo["interface"] = 'wwan0';
        $lteInfo["ip_address"] = $ip_address[0];
        $lteInfo["netmask"] = $netmask[0];
        $lteInfo["signal"] = $signal[0];
        $lteInfo["operator"] = $operator[0];
        $lteInfo["iccid"] = $iccid[0];
        $lteInfo["imsi"] = $imsi[0];
        $lteInfo["lte_status"] = $lte_status;
    }

    exec('ip route | grep "default"  | grep -c "wlan0"', $wifi_enabled);
    $wifiInfo = array();
    if ($wifi_enabled[0] == "1") {
        // exec("/bin/cat /etc/wpa_supplicant/wpa_supplicant.conf | grep ssid | awk -F \\\" '{ print $2 }'", $ssid);
        exec('ifconfig wlan0 | grep -oP "(?<=inet )([0-9]{1,3}\.){3}[0-9]{1,3}"', $wifi_ip);
        exec('ifconfig wlan0 | grep -oP "(?<=netmask )([0-9]{1,3}\.){3}[0-9]{1,3}"', $wifi_netmask);
        exec("ip route show | grep default | grep wlan0 | awk '{print $3}'", $wifi_gateway);

        $wifiInfo["interface"] = 'wlan0';
        // $wifiInfo["ssid"] = $ssid[0];
        $wifiInfo["ip"] = $wifi_ip[0];
        $wifiInfo["netmask"] = $wifi_netmask[0];
        $wifiInfo["gateway"] = $wifi_gateway[0];
    }

    echo renderTemplate(
        "dashboard", compact(
            "clients",
            "moreLink",
            "ifaceStatus",
            "status",
            "leases",
            "routeInfo",
            "lteInfo",
            "statusIcon",
            "wifiInfo"
        )
    );
}

