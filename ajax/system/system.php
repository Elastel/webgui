<?php

require '../../includes/csrf.php';
require_once '../../includes/config.php';

$type = $_GET['type'];
exec("uci -P /var/state get network.wan.link", $network_status);

if ($type == "node_online_update") {
    if ($network_status[0] != 'none')
      exec('sudo git fetch origin');

    exec('cat /var/www/html/.git/refs/remotes/origin/EG-develop', $new_node);
    $data['new_node'] = $new_node[0];

    exec('cat /var/www/html/.git/refs/heads/EG-develop', $cur_node);
    $data['cur_node'] = $cur_node[0];
} else if ($type == "update_node") {
    exec('cd /var/www/html');
    if ($network_status[0] != 'none') {
        exec('sudo git pull origin EG-develop');
        // check current node update
        exec('cat /var/www/html/.git/refs/remotes/origin/EG-develop', $new_node);
        exec('cat /var/www/html/.git/refs/heads/EG-develop', $cur_node);
        if ($new_node[0] == $cur_node[0]) {
            exec('sudo /var/www/html/update 2>&1', $info);
            $data['log'] = $info[0];
        } else {
            $data['error'] = "Fail to update node";
        }  
    } else {
        $data['error'] = 'No network!';
    }
} else if ($type == "reset_configs") {
    exec('cd /var/www/html');
    exec('sudo /var/www/html/update reset 2>&1');
}

echo json_encode($data);