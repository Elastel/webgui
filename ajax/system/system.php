<?php

require_once '../../includes/autoload.php';
require_once '../../includes/CSRF.php';
require_once '../../includes/config.php';

$type = $_GET['type'];
exec("uci -P /var/state get network.wan.link", $network_status);

if ($type == "node_online_update") {
    if ($network_status[0] != 'none') {
        exec('cd /var/www/html; sudo git fetch origin');
    }

    exec('cat /var/www/html/.git/refs/remotes/origin/$(git branch --show-current)', $new_node);
    $data['new_node'] = $new_node[0];

    exec('cat /var/www/html/.git/refs/heads/$(git branch --show-current)', $cur_node);
    $data['cur_node'] = $cur_node[0];
} else if ($type == "update_node") {
    if ($network_status[0] != 'none') {
        exec('cd /var/www/html; sudo git fetch origin');
        exec('cd /var/www/html; sudo git reset --hard origin/$(git branch --show-current)');
        exec('cd /var/www/html; sudo git pull origin $(git branch --show-current)');
        // check current node update
        exec('cat /var/www/html/.git/refs/remotes/origin/$(git branch --show-current)', $new_node);
        exec('cat /var/www/html/.git/refs/heads/$(git branch --show-current)', $cur_node);
        if ($new_node[0] == $cur_node[0]) {
            exec('sudo git checkout *; sudo /var/www/html/update 2>&1', $info);
            $data['log'] = $info[0];
        } else {
            $data['error'] = "Fail to update node";
        }  
    } else {
        $data['error'] = 'No network!';
    }
} else if ($type == "reset_configs") {
    exec('cd /var/www/html; sudo git checkout *');
    exec('sudo /var/www/html/update reset 2>&1');
} else if ($type == "download_backup") {
    exec('sudo rm -f /tmp/backup.tar.gz');
    exec('sudo /var/www/html/installers/backup.sh');
    $file_path = '/tmp/backup.tar.gz';

    if (file_exists($file_path)) {
        $file_name = basename($file_path);
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $file_name . '"');
        header('Content-Length: ' . filesize($file_path));
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');

        ob_clean();
        flush();
        readfile($file_path);
    }
} else if ($type == "action_backup") {
    $file = "/tmp/backup.tar.gz";
    if (file_exists($file)) {
        exec("sudo tar -xzvf /tmp/backup.tar.gz -C /; sudo sync");
        $data = ['success' => true, 'message' => 'Configuration restored successfully'];
        sleep(5);
        exec("sudo reboot");
    } else {
        $data = ['success' => false, 'message' => 'File does not exist'];
    }
}

if ($type != "download_backup") {
    echo json_encode($data);
}
