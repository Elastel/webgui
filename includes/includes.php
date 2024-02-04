<?php
require 'includes/csrf.php';
ensureCSRFSessionToken();
require_once 'includes/config.php';
require_once 'includes/defaults.php';
require_once RASPI_CONFIG.'/raspap.php';
require_once 'includes/locale.php';
require_once 'includes/functions.php';
require_once 'includes/dct.php';
require_once 'includes/dashboard.php';
require_once 'includes/authenticate.php';
require_once 'includes/admin.php';
require_once 'includes/dhcp.php';
require_once 'includes/hostapd.php';
require_once 'includes/adblock.php';
require_once 'includes/system.php';
require_once 'includes/sysstats.php';
require_once 'includes/configure_client.php';
require_once 'includes/networking.php';
require_once 'includes/themes.php';
require_once 'includes/data_usage.php';
require_once 'includes/about.php';
require_once 'includes/openvpn.php';
require_once 'includes/wireguard.php';
require_once 'includes/torproxy.php';
require_once 'includes/basic.php';
require_once 'includes/interfaces.php';
require_once 'includes/modbus.php';
require_once 'includes/s7.php';
require_once 'includes/fx.php';
require_once 'includes/io.php';
require_once 'includes/server.php';
require_once 'includes/ddns.php';
require_once 'includes/bacnet.php';
require_once 'includes/datadisplay.php';
require_once 'includes/detection.php';
require_once 'includes/macchina.php';
require_once 'includes/opcua.php';
require_once 'includes/lorawan.php';
require_once 'includes/terminal.php';
require_once 'includes/gps.php';
require_once 'includes/mc.php';
require_once 'includes/firewall.php';
require_once 'includes/ascii.php';
require_once 'includes/bacnet_client.php';
require_once 'includes/nodered.php';
require_once 'includes/docker.php';
require_once 'includes/iec104.php';

$config = getConfig();
$model = getModel();
$output = $return = 0;
$page = $_SERVER['PATH_INFO'];

$theme_url = getThemeOpt();
$toggleState = getSidebarState();
//$bridgedEnabled = getBridgedState();

?>