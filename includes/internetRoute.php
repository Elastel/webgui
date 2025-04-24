<?php

/*
 * Fetches details of the kernel routing table
 *
 * @param boolean $checkAccesss Perform connectivity test
 * @return string
 */
function getRouteInfo($checkAccess)
{
    $model = getModel();
    $rInfo = array();
    // get all default routes
    exec('ip route list |  sed -rn "s/default via (([0-9]{1,3}\.){3}[0-9]{1,3}).*dev (\w*).*src (([0-9]{1,3}\.){3}[0-9]{1,3}).*/\3 \4 \1/p"', $routes);

    if (!empty($routes)) {
        foreach ($routes as $i => $route) {
            $prop = explode(' ', $route);
            $rInfo[$i]["interface"] = $prop[0];
            $rInfo[$i]["ip-address"] = $prop[1];
            $rInfo[$i]["gateway"] = $prop[2];
            if ($model != "EG324L" && $model != "EC212") {
                exec('ifconfig ' . $prop[0] . ' | grep -oP "(?<=netmask )([0-9]{1,3}\.){3}[0-9]{1,3}"', $netmask);
            } else {
                exec('ifconfig ' . $prop[0] . ' | grep -Eo "([0-9]+[.]){3}[0-9]+" | grep "255.255"', $netmask);
            }
            
            $rInfo[$i]["netmask"] = $netmask[0];
            exec('cat /sys/class/net/' . $prop[0] . '/address', $mac);
            $rInfo[$i]["mac"] = $mac[0];
        }
    } else {
        $rInfo = array("error" => "No route to the internet found");
    }
    return $rInfo;
}

