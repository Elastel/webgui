<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row">
    <div class="col">
      <i class="fas fa-tachometer-alt fa-fw mr-2"></i><?php echo _("Dashboard"); ?>
    </div>
    <div class="col">
      <button class="btn btn-light btn-icon-split btn-sm service-status float-right">
        <span class="icon"><i class="fas fa-circle service-status-<?php echo $statusIcon ?>"></i></span>
        <span class="text service-status"><?php echo strtolower($ap_iface) .' '. _($ifaceStatus) ?></span>
      </button>
    </div>
        </div><!-- /.row -->
      </div><!-- /.card-header -->

      <div class="card-body">
        <div class="row">
          <div class="col-sm-6 align-items-stretch">
            <div class="card h-100">
              <div class="card-body wireless">
              <?php foreach($routeInfo as $route): ?>
                <?php if ($route['interface'] == "eth0") { ?>
                  <h4 class="card-title"><?php echo _("Wired Network Status"); ?></h4>
                <?php } ?>
                <?php if ($route['interface'] == "eth0") {?>
                  <div class="row ml-1">
                    <div class="col-sm">
                      <div class="row mb-1">
                        <div class="col-xs-3" style="color: #858796; width: 7rem"><?php echo _("Interface:"); ?></div><div class="col-xs-3"><?php echo $route['interface']; ?></div>
                      </div>
                      <div class="row mb-1">
                        <div class="col-xs-3" style="color: #858796; width: 7rem"><?php echo _("Ip Address:"); ?></div><div class="col-xs-3"><?php echo $route['ip-address']; ?></div>
                      </div>
                      <div class="row mb-1">
                        <div class="col-xs-3" style="color: #858796; width: 7rem"><?php echo _("Gateway:"); ?></div><div class="col-xs-3"><?php echo $route['gateway']; ?></div>
                      </div>
                      <div class="row mb-1">
                        <div class="col-xs-3" style="color: #858796; width: 7rem"><?php echo _("Netmask:"); ?></div><div class="col-xs-3"><?php echo $route["netmask"]; ?></div>
                      </div>
                      <div class="row mb-1">
                        <?php if ( $route["mac"] != null && $route["mac"] != ' ') {  ?>
                        <div class="col-xs-3" style="color: #858796; width: 7rem"><?php echo _("MAC:"); ?></div><div class="col-xs-3" > <?php echo $route["mac"]; ?></div>
                        <?php } ?>
                      </div>
                      <div class="row mb-1">
                        <?php if ( $route["dns1"] != null && $route["dns1"] != ' ') { ?>
                        <div class="col-xs-3" style="color: #858796; width: 7rem"><?php echo _("DNS1:"); ?></div><div class="col-xs-3" > <?php echo $route["dns1"]; ?></div>
                        <?php } ?>
                      </div>
                      <div class="row mb-1">
                        <?php if ( $route["dns2"] != null && $route["dns2"] != ' ') {  ?>
                        <div class="col-xs-3" style="color: #858796; width: 7rem"><?php echo _("DNS2:"); ?></div><div class="col-xs-3" > <?php echo $route["dns2"]; ?></div>
                        <?php } ?>
                      </div>
                    </div>
                  </div>
                  </br>
                  </br>
                <?php } ?>
                <?php endforeach ?>
                <?php if ($lteInfo['interface'] == "wwan0") { ?>
                  <h4 class="card-title"><?php echo _("LTE Network Status"); ?></h4>
                  <div class="row ml-1">
                    <div class="col-sm">
                      <div class="row mb-1">
                        <div class="col-xs-3" style="color: #858796; width: 7rem"><?php echo _("Interface:"); ?></div><div class="col-xs-3"><?php echo $lteInfo['interface']; ?></div>
                      </div>
                      <div class="row mb-1">
                        <div class="col-xs-3" style="color: #858796; width: 7rem"><?php echo _("Ip Address:"); ?></div><div class="col-xs-3"><?php echo $lteInfo['ip_address']; ?></div>
                      </div>
                      <div class="row mb-1">
                        <div class="col-xs-3" style="color: #858796; width: 7rem"><?php echo _("Netmask:"); ?></div><div class="col-xs-3"><?php echo $lteInfo['netmask']; ?></div>
                      </div>
                      <div class="row mb-1">
                        <div class="col-xs-3" style="color: #858796; width: 7rem"><?php echo _("Signal:"); ?></div><div class="col-xs-3"><?php echo $lteInfo["signal"]; ?></div>
                      </div>
                      <div class="row mb-1">
                        <div class="col-xs-3" style="color: #858796; width: 7rem"><?php echo _("Operator:"); ?></div><div class="col-xs-3"><?php echo $lteInfo["operator"]; ?></div>
                      </div>
                      <div class="row mb-1">
                        <div class="col-xs-3" style="color: #858796; width: 7rem"><?php echo _("ICCID:"); ?></div><div class="col-xs-3"><?php echo $lteInfo["iccid"]; ?></div>
                      </div>
                      <div class="row mb-1">
                        <div class="col-xs-3" style="color: #858796; width: 7rem"><?php echo _("IMSI:"); ?></div><div class="col-xs-3"><?php echo $lteInfo["imsi"]; ?></div>
                      </div>
                      <div class="row mb-1">
                        <div class="col-xs-3" style="color: #858796; width: 7rem"><?php echo _("Status:"); ?></div><div class="col-xs-3"><?php echo $lteInfo["lte_status"]; ?></div>
                      </div>
                    </div>
                  </div>
                <?php } ?>
                <?php if ($wifiInfo['interface'] == "wlan0") { ?>
                  <h4 class="card-title"><?php echo _("WIFI Network Status"); ?></h4>
                  <div class="row ml-1">
                    <div class="col-sm">
                      <div class="row mb-1">
                        <div class="col-xs-3" style="color: #858796; width: 7rem"><?php echo _("Interface:"); ?></div><div class="col-xs-3"><?php echo $wifiInfo['interface']; ?></div>
                      </div>
                      <!-- <div class="row mb-1">
                        <div class="col-xs-3" style="color: #858796; width: 7rem"><?php //echo _("SSID:"); ?></div><div class="col-xs-3"><?php //echo $wifiInfo['ssid']; ?></div>
                      </div> -->
                      <div class="row mb-1">
                        <div class="col-xs-3" style="color: #858796; width: 7rem"><?php echo _("Ip Address:"); ?></div><div class="col-xs-3"><?php echo $wifiInfo['ip']; ?></div>
                      </div>
                      <div class="row mb-1">
                        <div class="col-xs-3" style="color: #858796; width: 7rem"><?php echo _("Netmask:"); ?></div><div class="col-xs-3"><?php echo $wifiInfo['netmask']; ?></div>
                      </div>
                      <div class="row mb-1">
                        <div class="col-xs-3" style="color: #858796; width: 7rem"><?php echo _("Gateway:"); ?></div><div class="col-xs-3"><?php echo $wifiInfo["gateway"]; ?></div>
                      </div>
                    </div>
                  </div>
                <?php } ?>
              </div><!-- /.card-body -->
            </div><!-- /.card -->
          </div><!-- /.col-md-6 -->
          <div class="col-sm-6">
            <div class="card h-100 mb-3">
              <div class="card-body">
                <h4 class="card-title"><?php echo _("Connected Devices"); ?></h4>
                <div class="table-responsive">
                  <table class="table table-hover">
                    <thead>
                      <tr>
                          <th><?php echo _("Host name"); ?></th>
                          <th><?php echo _("IP Address"); ?></th>
                          <th><?php echo _("MAC Address"); ?></th>
                      </tr>
                    </thead>
                    <tbody>
                        <?php foreach (array_slice($clients,0, 2) as $client) : ?>
                        <tr>
                            <?php $props = explode(' ', $client) ?>
                            <td><?php echo htmlspecialchars($props[3], ENT_QUOTES) ?></td>
                            <td><?php echo htmlspecialchars($props[2], ENT_QUOTES) ?></td>
                            <td><?php echo htmlspecialchars($props[1], ENT_QUOTES) ?></td>
                        </tr>
                        <?php endforeach ?>

                        <?php foreach (array_slice($leases,0, 2) as $leases) : ?>
                        <tr>
                            <?php $props = explode(' ', $leases) ?>
                            <td><?php echo htmlspecialchars($props[3], ENT_QUOTES) ?></td>
                            <td><?php echo htmlspecialchars($props[2], ENT_QUOTES) ?></td>
                            <td><?php echo htmlspecialchars($props[1], ENT_QUOTES) ?></td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                  </table>
                  <?php if (sizeof($clients) > 3 || sizeof($leases) > 3) : ?>
                      <div class="col-lg-12 float-right">
                        <a class="btn btn-outline-info" role="button" href="<?php echo $moreLink ?>"><?php echo _("More");?>  <i class="fas fa-chevron-right"></i></a>
                      </div>
                  <?php elseif (sizeof($clients) ==0 && sizeof($leases) ==0) : ?>
                      <div class="col-lg-12 mt-3"><?php echo _("No connected devices");?></div>
                  <?php endif; ?>
                </div><!-- /.table-responsive -->
              </div><!-- /.card-body -->
            </div><!-- /.card -->
          </div><!-- /.col-md-6 -->
        </div><!-- /.row -->

        <div class="col-lg-12 mt-3">
          <div class="row">
            <form action="wlan0_info" method="POST">
                <?php echo CSRFTokenFieldTag() ?>
              <button type="button" onClick="window.location.reload();" class="btn btn-outline btn-primary"><i class="fas fa-sync-alt"></i> <?php echo _("Refresh") ?></a>
            </form>
          </div>
        </div>

      </div><!-- /.card-body -->
    </div><!-- /.card -->
  </div><!-- /.col-lg-12 -->
</div><!-- /.row -->
<script type="text/javascript"<?php //echo ' nonce="'.$csp_page_nonce.'"'; ?>>
// js translations:
var t = new Array();
t['send'] = '<?php echo addslashes(_('Send')); ?>';
t['receive'] = '<?php echo addslashes(_('Receive')); ?>';
</script>
