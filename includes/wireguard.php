<?php

require_once 'includes/status_messages.php';
require_once 'config.php';

/**
 * Displays wireguard server & peer configuration
 */
function DisplayWireGuardConfig()
{
    $status = new StatusMessages();
    if (!RASPI_MONITOR_ENABLED) {
        if (isset($_POST['savewgsettings']) || isset($_POST['applywgsettings'])){
            $optRules     = '1';
            $optLogEnable = $_POST['wgLogEnable'];
            $type         = $_POST['type'];
            $role         = $_POST['role'];
            exec("sudo /usr/local/bin/uci set wireguard.wg.type=" . $type);
            exec("sudo /usr/local/bin/uci set wireguard.wg.role=" . $role);

            if ($type == 'wg') {
                if (is_uploaded_file($_FILES["wgFile"]["tmp_name"])) {
                    exec("sudo /usr/local/bin/uci set wireguard.wg.wg_file=" . $_FILES['wgFile']['name']);
                    SaveWireGuardUpload($status, $_FILES['wgFile'], $optRules);
                } 
            } else {
                exec("sudo /usr/local/bin/uci del wireguard.wg.wg_file");
                SaveWireGuardConfig($status, $role);
            }

            if (isset($_POST['applywgsettings'])) {
                $status->addMessage('Attempting to stop WireGuard', 'info');
                exec('sudo /bin/systemctl stop wg-quick@wg0', $return);
                exec('sudo /bin/systemctl disable wg-quick@wg0', $return);
                sleep(1);
                if ($type != 'off') {
                    $status->addMessage('Attempting to start WireGuard', 'info');
                    exec('sudo /bin/systemctl enable wg-quick@wg0', $return);
                    exec('sudo /bin/systemctl start wg-quick@wg0', $return);
                } else {
                    # remove selected conf + keys
                    system('sudo rm '. RASPI_WIREGUARD_PATH .'wg-server-private.key', $return);
                    system('sudo rm '. RASPI_WIREGUARD_PATH .'wg-server-public.key', $return);
                    system('sudo rm '. RASPI_WIREGUARD_CONFIG, $return);
                }

                foreach ($return as $line) {
                    $status->addMessage($line, 'info');
                }
            }
        }

        CheckWireGuardLog( $optLogEnable, $status );
        exec("sudo /usr/local/bin/uci commit wireguard");
    }

    exec('sudo cat '. RASPI_WIREGUARD_CONFIG, $return);
    $conf = ParseConfig($return);
    $wg_srvpubkey = exec('sudo cat '. RASPI_WIREGUARD_PATH .'wg-server-public.key', $return);
    $wg_srvport = ($conf['ListenPort'] == '') ? getDefaultNetValue('wireguard','server','ListenPort') : $conf['ListenPort'];
    $wg_srvipaddress = ($conf['Address'] == '') ? getDefaultNetValue('wireguard','server','Address') : $conf['Address'];
    $wg_srvdns = ($conf['DNS'] == '') ? getDefaultNetValue('wireguard','server','DNS') : $conf['DNS'];
    $wg_peerpubkey = exec('sudo cat '. RASPI_WIREGUARD_PATH .'wg-peer-public.key', $return);
    $wg_pendpoint = ($conf['Endpoint'] == '') ? getDefaultNetValue('wireguard','peer','Endpoint') : $conf['Endpoint'];
    $wg_pallowedips = ($conf['AllowedIPs'] == '') ? getDefaultNetValue('wireguard','peer','AllowedIPs') : $conf['AllowedIPs'];
    $wg_pkeepalive = ($conf['PersistentKeepalive'] == '') ? getDefaultNetValue('wireguard','peer','PersistentKeepalive') : $conf['PersistentKeepalive'];
    if (sizeof($conf) >0) {
        $wg_senabled = true;
    }

    // fetch service status
    exec('pidof wg-crypt-wg0 | wc -l', $wgstatus);
    $serviceStatus = $wgstatus[0] == 0 ? "down" : "up";
    $wg_state = ($wgstatus[0] > 0);
    $public_ip = get_public_ip();

    echo renderTemplate(
        "wireguard", compact(
            "status",
            "wg_state",
            "serviceStatus",
            "public_ip",
            "optRules",
            "optLogEnable",
            "peer_id",
            "wg_srvpubkey",
            "wg_srvport",
            "wg_srvipaddress",
            "wg_srvdns",
            "wg_senabled",
            "wg_penabled",
            "wg_pipaddress",
            "wg_plistenport",
            "wg_peerpubkey",
            "wg_pendpoint",
            "wg_pallowedips",
            "wg_pkeepalive"
        )
    );
}

/**
 * Validates uploaded .conf file, adds iptables post-up and
 * post-down rules.
 *
 * @param  object $status
 * @param  object $file
 * @param  boolean $optRules
 * @return object $status
 */
function SaveWireGuardUpload($status, $file, $optRules)
{
    define('KB', 1024);
    $tmp_destdir = '/tmp/';
    $auth_flag = 0;

    try {
        // If undefined or multiple files, treat as invalid
        if (!isset($file['error']) || is_array($file['error'])) {
            throw new RuntimeException('Invalid parameters');
        }

        $upload = \RaspAP\Uploader\Upload::factory('wg',$tmp_destdir);
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
        $tmp_wgconfig = $results['full_path'];
        $tmp_contents = file_get_contents($tmp_wgconfig);

        // Set iptables rules
        if (isset($optRules) && !preg_match('/PostUp|PostDown/m',$tmp_contents)) {
            $rules[] = 'PostUp = '.getDefaultNetValue('wireguard','server','PostUp');
            $rules[] = 'PostDown = '.getDefaultNetValue('wireguard','server','PostDown');
            $rules[] = '';
            $rules = join(PHP_EOL, $rules);
            $rules = preg_replace('/wlan0/m', $_SESSION['ap_interface'], $rules);
            $tmp_contents = preg_replace('/^\s*$/ms', $rules, $tmp_contents, 1);
            file_put_contents($tmp_wgconfig, $tmp_contents);
        }

        // Move processed file from tmp to destination
        system("sudo mv $tmp_wgconfig ". RASPI_WIREGUARD_CONFIG, $return);

        if ($return ==0) {
            $status->addMessage('WireGuard configuration uploaded successfully', 'info');
        } else {
            $status->addMessage('Unable to save WireGuard configuration', 'danger');
        }
        return $status;

    } catch (RuntimeException $e) {
        $status->addMessage($e->getMessage(), 'danger');
        return $status;
    }
}

/**
 * Validate user input, save wireguard configuration
 *
 * @param object $status
 * @return boolean
 */
function SaveWireGuardConfig($status, $role)
{
    // Set defaults
    $good_input = true;
    $peer_id = 1;
    // Validate server input

    if (isset($_POST['wg_srvport'])) {
        if (strlen($_POST['wg_srvport']) > 5 || !is_numeric($_POST['wg_srvport'])) {
            $status->addMessage('Invalid value for server local port', 'danger');
            $good_input = false;
        }
    }
    if (isset($_POST['wg_plistenport'])) {
        if (strlen($_POST['wg_plistenport']) > 5 || !is_numeric($_POST['wg_plistenport'])) {
            $status->addMessage('Invalid value for peer local port', 'danger');
            $good_input = false;
        }
    }
    if (isset($_POST['wg_srvipaddress'])) {
        if (!validateCidr($_POST['wg_srvipaddress'])) {
            $status->addMessage('Invalid value for IP address', 'danger');
            $good_input = false;
        }
    }
    if (isset($_POST['wg_srvdns'])) {
        if (!filter_var($_POST['wg_srvdns'],FILTER_VALIDATE_IP)) {
            $status->addMessage('Invalid value for DNS', 'danger');
            $good_input = false;
        }
    }

    // Validate peer input
    if (isset($_POST['wg_pipaddress'])) {
        if (!validateCidr($_POST['wg_pipaddress'])) {
            $status->addMessage('Invalid value for peer IP address', 'danger');
            $good_input = false;
        }
    }
    if (isset($_POST['wg_pendpoint']) && strlen(trim($_POST['wg_pendpoint']) >0 )) {
        $wg_pendpoint_seg = substr($_POST['wg_pendpoint'],0,strpos($_POST['wg_pendpoint'],':'));
        if (!filter_var($wg_pendpoint_seg,FILTER_VALIDATE_IP)) {
            $status->addMessage('Invalid value for endpoint address', 'danger');
            $good_input = false;
        }
    }
    if (isset($_POST['wg_pallowedips']) && strlen(trim($_POST['wg_pallowedips']) >0)) {
        if (!validateCidr($_POST['wg_pallowedips'])) {
            $status->addMessage('Invalid value for allowed IPs', 'danger');
            $good_input = false;
        }
    }
    if (isset($_POST['wg_pkeepalive']) && strlen(trim($_POST['wg_pkeepalive']) >0 )) {
        if (strlen($_POST['wg_pkeepalive']) > 4 || !is_numeric($_POST['wg_pkeepalive'])) {
            $status->addMessage('Invalid value for persistent keepalive', 'danger');
            $good_input = false;
        }
    }

    // Save settings
    if ($good_input) {
        // fetch peer private key from filesystem 
        $wg_svrprivkey = exec('sudo cat '. RASPI_WIREGUARD_PATH .'wg-server-private.key', $return);
        $config = [];
        $config[] = '[Interface]';
        $config[] = 'Address = '.trim($_POST['wg_srvipaddress']);
        $config[] = 'PrivateKey = '.$wg_svrprivkey;
        $config[] = 'ListenPort = '.$_POST['wg_srvport'];
        $config[] = '';
        $config[] = '[Peer]';
        $config[] = 'PublicKey = '.$_POST['wg-peer'];
        $config[] = 'AllowedIPs = '.$_POST['wg_pallowedips'];
        if ($role == 'client') {
            $config[] = 'Endpoint = '.$_POST['wg_pendpoint'];
        }
        if ($_POST['wg_pkeepalive'] !== '') {
            $config[] = 'PersistentKeepalive = '.trim($_POST['wg_pkeepalive']);
        }
        $config[] = '';
        $config = join(PHP_EOL, $config);

        if ($_POST['wg-server'] !== '') {
            file_put_contents("/tmp/wg-server-public.key", $_POST['wg-server']);
            system('sudo mv /tmp/wg-server-public.key '.RASPI_WIREGUARD_PATH.'wg-server-public.key', $return);
        }
        
        file_put_contents("/tmp/wg-peer-public.key", $_POST['wg-peer']);
        system('sudo mv /tmp/wg-peer-public.key '.RASPI_WIREGUARD_PATH.'wg-peer-public.key', $return);

        file_put_contents("/tmp/wgdata", $config);
        system('sudo cp /tmp/wgdata '.RASPI_WIREGUARD_PATH.'wg0.conf', $return);

        foreach ($return as $line) {
            $status->addMessage($line, 'info');
        }
        if ($return == 0) {
            $status->addMessage('WireGuard configuration updated successfully', 'success');
        } else {
            $status->addMessage('WireGuard configuration failed to be updated', 'danger');
        }
    }
}

/**
 *
 * @return object $status
 */
function CheckWireGuardLog( $opt, $status )
{
   // handle log option
    if ( $opt == "1") {
        exec("sudo journalctl --identifier wg-quick > /tmp/wireguard.log");
        $status->addMessage('WireGuard debug log updated', 'success');
    }
    return $status;
}

