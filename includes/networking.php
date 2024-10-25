<?php

require_once 'includes/status_messages.php';
require_once 'includes/internetRoute.php';
require_once 'config.php';
/**
 *
 *
 */
function DisplayNetworkingConfig()
{
    $model = getModel();
    $status = new StatusMessages();
    if (!RASPI_MONITOR_ENABLED) {
        if (isset($_POST['savenetworksettings']) || isset($_POST['applynetworksettings'])) {
            saveStaticConfig($status);
            saveLteConfig($status);
            exec('sudo /usr/local/bin/uci commit network');

            if ($model != 'EG324L' && $model != 'EC212') {
                if ($_POST['wan-multi'] == '1') {
                    exec('sudo cp /var/www/html/config/raspap-br0-member-eth0.network /etc/systemd/network/');
                } else {
                    exec('sudo rm /etc/systemd/network/raspap-br0-member-eth0.network');
                }
            } else {
                if ($_POST['wan-multi'] == '1') {
                    exec('brctl addif br0 eth0');
                } else {
                    exec('brctl delif br0 eth0');
                }
            }
            

            if (isset($_POST['applynetworksettings'])) {
                if ($model != 'EG324L' && $model != 'EC212') {
                    exec('cat /sys/class/net/eth0/address', $cur_wired_mac);
                    if ($cur_wired_mac[0] != $_POST['wired_mac']) {
                        exec('sudo ifconfig eth0 down');
                        exec('sudo ifconfig eth0 hw ether ' . $_POST['wired_mac']);
                        exec('sudo ifconfig eth0 up');
                    }

                    if ($_POST['wan-multi'] == '1') {
                        exec('sudo systemctl restart systemd-networkd.service');
                    } else {
                        exec('sudo systemctl restart systemd-networkd.service');
                        exec('sudo /usr/sbin/brctl delif br0 eth0');
                        exec('sudo systemctl stop dhcpcd.service');
                        sleep(1);
                        exec('sudo systemctl start dhcpcd.service');
                    }
                } else {
                    exec('sudo /etc/init.d/S80dhcpcd restart');
                }
                

                if ($_POST['adapter-ip'] == '0') {
                    // add dns to resolv.conf
                    if ($_POST['DNS1'] !== '' || $_POST['DNS2'] !== '') {
                        $orgin_data = file_get_contents('/etc/resolv.conf');
                        $new_data .= $orgin_data;
                        $new_data .= 'nameserver ' . $_POST['DNS1'] . PHP_EOL . 'nameserver ' . $_POST['DNS2'] . PHP_EOL;
                        file_put_contents('/tmp/resolv.conf', $new_data);
                        system('sudo cp /tmp/resolv.conf /etc/resolv.conf');
                    }

                    exec('sudo ifconfig eth0 down');
                    sleep(2);
                    exec('sudo ifconfig eth0 up ' . $_POST['StaticIP']);
                }

                exec('sudo /etc/init.d/lte restart > /dev/null');
                exec('sudo /etc/init.d/failover restart > /dev/null');
            }
        }
    }

    $wired_interface = ['eth0'];
    $lte_interface = ['wwan0'];
    $lte_enabled = 0;
    exec('ls /sys/class/net | grep -v lo', $interfaces);
    foreach( $interfaces as $k=>$v) {
        if($v == 'wwan0') {
            $lte_enabled = 1;
        }
    }

    exec('uci get network.wan.mac', $mac_conf);
    if ($mac_conf[0] != '') {
        $wired_mac = $mac_conf[0];
    } else {
        $wired_mac = exec('cat /sys/class/net/eth0/address');
    }

    $lte_mac = exec('cat /sys/class/net/wwan0/address');

    // $routeInfo = getRouteInfo(true);
    echo renderTemplate('networking', compact(
        'status',
        'wired_interface',
        'lte_interface',
        //'routeInfo',
        'lte_enabled',
        'wired_mac',
        'lte_mac'
    ));
}

/**
 * Saves a static ip configuration
 *
 * @return object $status
 */
function saveStaticConfig($status)
{
    $iface0 = $_POST['interface0'];
    $return = 1;

    if ($iface0 == 'eth0') {
        exec('sudo uci set network.wan.device=eth0');
        if ($_POST['Metric'] !== '') {
            exec('sudo /usr/local/bin/uci set network.wan.metric=' . $_POST['Metric']);
        } else {
            exec('sudo /usr/local/bin/uci set network.wan.metric=202');
        }

        if (filter_var($_POST['wired_mac'], FILTER_VALIDATE_MAC)) {
            exec('sudo /usr/local/bin/uci set network.wan.mac=' . $_POST['wired_mac']);
        }

        if ($_POST['wan-multi'] == '1') {
            exec('sudo uci set network.wan.wan_multi=1');
        } else {
            exec('sudo uci set network.wan.wan_multi=0');
        }

        // handle disable dhcp option
        if ($_POST['adapter-ip'] == '1') {
            exec('sudo uci set network.wan.proto=dhcp');
            // remove dhcp configs for selected interface
            // $return = removeDHCPConfig($iface0,$status);
            updateDHCPConfigMetric($iface0,$status);
        } else {
            //$status->addMessage('updateDHCPConfig');
            exec('sudo uci set network.wan.proto=static');
            $errors = validateDHCPInputNetwork();
            if (empty($errors)) {
                $return = updateDHCPConfigNetwork($iface0, $status);
            } else {
                $status->addMessage($errors, 'danger');
            }
            return true;
        }
    }
}

function saveLteConfig($status)
{
    $iface = $_POST['interface'];
    $return = 1;

    if ($iface == 'wwan0') {
        $auth_type = $_POST['auth_type'];
        $error = array();

        if ($auth_type != 'none') {
            $errors = validateLteInputNetwork();
        }

        if (empty($errors)) {
            $return = updateLteConfigNetwork($iface, $status);
        } else {
            $status->addMessage($errors, 'danger');
        }

        $status->addMessage('LTE configuration for '.$iface.' updated.', 'success');
        return true;
    }
}

/**
 * Validates DHCP user input from the $_POST object
 *
 * @return string $errors
 */
function validateDHCPInputNetwork()
{
    define('IFNAMSIZ', 16);
    $iface0 = $_POST['interface0'];
    if (!preg_match('/^[a-zA-Z0-9]+$/', $iface0)
        || strlen($iface0) >= IFNAMSIZ
    ) {
        $errors .= _('Invalid interface name.').'<br />'.PHP_EOL;
    }
    if (!filter_var($_POST['StaticIP'], FILTER_VALIDATE_IP) && !empty($_POST['StaticIP'])) {
        $errors .= _('Invalid static IP address.').'<br />'.PHP_EOL;
    }
    if (!filter_var($_POST['SubnetMask'], FILTER_VALIDATE_IP) && !empty($_POST['SubnetMask'])) {
        $errors .= _('Invalid subnet mask.').'<br />'.PHP_EOL;
    }
    if (!filter_var($_POST['DefaultGateway'], FILTER_VALIDATE_IP) && !empty($_POST['DefaultGateway'])) {
        $errors .= _('Invalid default gateway.').'<br />'.PHP_EOL;
        var_dump($_POST['DefaultGateway']);
        die();
    }

    return $errors;
}

function validateLteInputNetwork()
{
    define('IFNAMSIZ', 16);
    $iface = $_POST['interface'];
    if (!preg_match('/^[a-zA-Z0-9]+$/', $iface)
        || strlen($iface) >= IFNAMSIZ
    ) {
        $errors .= _('Invalid interface name.').'<br />'.PHP_EOL;
    }
    if (empty($_POST['username'])) {
        $errors .= _('Invalid PAP/CHAP username.').'<br />'.PHP_EOL;
    }
    if (empty($_POST['password'])) {
        $errors .= _('Invalid PAP/CHAP password.').'<br />'.PHP_EOL;
    }

    return $errors;
}

/**
 * Updates a dhcp configuration
 *
 * @param string $iface0
 * @param object $status
 * @return boolean $result
 */
function updateDHCPConfigNetwork($iface0,$status)
{
    $cfg[] = '# RaspAP '.$iface0.' configuration';
    $cfg[] = 'interface '.$iface0;
    if (isset($_POST['StaticIP'])) {
        $mask = ($_POST['SubnetMask'] !== '' && $_POST['SubnetMask'] !== '0.0.0.0') ? '/'.mask2cidr($_POST['SubnetMask']) : null;
        $cfg[] = 'static ip_address='.$_POST['StaticIP'].$mask;
    }
    if (isset($_POST['DefaultGateway'])) {
      $cfg[] = 'static routers='.$_POST['DefaultGateway'];
    }
    if ($_POST['DNS1'] !== '' || $_POST['DNS2'] !== '') {
        $cfg[] = 'static domain_name_server='.$_POST['DNS1'].' '.$_POST['DNS2'];
    }
    if ($_POST['Metric'] !== '') {
      $cfg[] = 'metric '.$_POST['Metric'];
    }
    if ($_POST['Fallback'] == 1) {
        $cfg[] = 'profile static_'.$iface0;
        $cfg[] = 'fallback static_'.$iface0;
    }

    // $cfg[] = $_POST['DefaultRoute'] == '1' ? 'gateway' : 'nogateway';
    $orgin_str = file_get_contents(RASPI_DHCPCD_CONFIG);
    $count = strpos($orgin_str, 'denyinterfaces');
    if ($_POST['wan-multi'] == '1') {
        $dhcp_cfg = substr_replace($orgin_str, 'denyinterfaces eth1 wlan0 eth0' . PHP_EOL, number_format($count), 31);
    } else {
        $dhcp_cfg = substr_replace($orgin_str, 'denyinterfaces eth1 wlan0     ' . PHP_EOL, number_format($count), 31);
    }

    if (!preg_match('/^interface\s'.$iface0.'$/m', $dhcp_cfg)) {
        $cfg[] = PHP_EOL;
        $cfg = join(PHP_EOL, $cfg);
        $dhcp_cfg .= $cfg;
        $status->addMessage('DHCP configuration for '.$iface0.' added.', 'success');
    } else {
        $cfg = join(PHP_EOL, $cfg);
        $dhcp_cfg = preg_replace('/^#\sRaspAP\s'.$iface0.'\s.*?(?=\s*^\s*$)/ms', $cfg, $dhcp_cfg, 1);
        $status->addMessage('DHCP configuration for '.$iface0.' updated.', 'success');
    }
    file_put_contents('/tmp/dhcpddata', $dhcp_cfg);
    system('sudo cp /tmp/dhcpddata '.RASPI_DHCPCD_CONFIG, $result);

    return $result;
}

function updateDHCPConfigMetric($iface0,$status)
{
    $cfg[] = '# RaspAP '.$iface0.' configuration';
    $cfg[] = 'interface '.$iface0;

    if ($_POST['Metric'] !== '') {
      $cfg[] = 'metric '.$_POST['Metric'];
    }

    // $cfg[] = $_POST['DefaultRoute'] == '1' ? 'gateway' : 'nogateway';
    $orgin_str = file_get_contents(RASPI_DHCPCD_CONFIG);
    $count = strpos($orgin_str, 'denyinterfaces');
    if ($_POST['wan-multi'] == '1') {
        $dhcp_cfg = substr_replace($orgin_str, 'denyinterfaces eth1 wlan0 eth0' . PHP_EOL, number_format($count), 31);
    } else {
        $dhcp_cfg = substr_replace($orgin_str, 'denyinterfaces eth1 wlan0     ' . PHP_EOL, number_format($count), 31);
    }

    if (!preg_match('/^interface\s'.$iface0.'$/m', $dhcp_cfg)) {
        $cfg[] = PHP_EOL;
        $cfg = join(PHP_EOL, $cfg);
        $dhcp_cfg .= $cfg;
        $status->addMessage('DHCP configuration for '.$iface0.' added.', 'success');
    } else {
        $cfg = join(PHP_EOL, $cfg);
        $dhcp_cfg = preg_replace('/^#\sRaspAP\s'.$iface0.'\s.*?(?=\s*^\s*$)/ms', $cfg, $dhcp_cfg, 1);
        $status->addMessage('DHCP configuration for '.$iface0.' updated.', 'success');
    }
    file_put_contents('/tmp/dhcpddata', $dhcp_cfg);
    system('sudo cp /tmp/dhcpddata '.RASPI_DHCPCD_CONFIG, $result);

    return $result;
}

function updateLteConfigNetwork($iface0, $status)
{
    if ($_POST['lte_metric'] != '') {
        exec('sudo /usr/local/bin/uci set network.swan.metric=' .$_POST['lte_metric']);
    } else {
        exec('sudo /usr/local/bin/uci set network.swan.metric=207');
    }

    exec('sudo /usr/local/bin/uci set network.swan.apn=' .$_POST['apn']);
    exec('sudo /usr/local/bin/uci set network.swan.pincode=' .$_POST['pin']);
    exec('sudo /usr/local/bin/uci set network.swan.auth=' .$_POST['auth_type']);
    if ($_POST['auth_type'] == 'none') {
        exec('sudo /usr/local/bin/uci delete network.swan.username');
        exec('sudo /usr/local/bin/uci delete network.swan.password');
    } else {
        exec('sudo /usr/local/bin/uci set network.swan.username=' .$_POST['username']);
        exec('sudo /usr/local/bin/uci set network.swan.password=' .$_POST['password']);
    }

    return $result;
}
