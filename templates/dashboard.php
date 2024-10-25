<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row">
          <div class="col">
            <?php echo _("Dashboard"); ?>
          </div>
          <div class="col">
            <button class="btn btn-light btn-icon-split btn-sm service-status float-right">
              <span class="icon"><i class="fas fa-circle service-status-<?php echo $statusIcon ?>"></i></span>
              <span class="text service-status"><?php echo _($ifaceStatus) ?></span>
            </button>
          </div>
        </div><!-- /.row -->
      </div><!-- /.card-header -->
      <div class="card-body">
        <div class="cbi-section cbi-tblsection" name="system">
          <h4><?php echo _("System") ;?></h4>
          <div class="system">
          <table class="table cbi-section-table">
            <tr class="tr cbi-section-table-titles">
              <td style="text-align:left;width:30%;font-weight:bold; padding:0.5rem; font-size:0.8rem"><?php echo _("Hostname"); ?></td>
              <td style="text-align:left; padding:0.5rem; font-size:0.8rem"><?php echo htmlspecialchars($cur_hostname, ENT_QUOTES); ?></td>
            </tr>
            <tr class="tr cbi-section-table-titles">
              <td style="text-align:left;width:30%;font-weight:bold; padding:0.5rem; font-size:0.8rem"><?php echo _("Model"); ?></td>
              <td style="text-align:left; padding:0.5rem; font-size:0.8rem"><?php echo htmlspecialchars((getTarget() != null) ? getTarget() : getModel(), ENT_QUOTES); ?></td>
            </tr>
            <tr class="tr cbi-section-table-titles">
              <td style="text-align:left;width:30%;font-weight:bold; padding:0.5rem; font-size:0.8rem"><?php echo _("Revision"); ?></td>
              <td style="text-align:left; padding:0.5rem; font-size:0.8rem"><?php echo htmlspecialchars(get_revison(), ENT_QUOTES); ?></td>
            </tr>
            <tr class="tr cbi-section-table-titles">
              <td style="text-align:left;width:30%;font-weight:bold; padding:0.5rem; font-size:0.8rem"><?php echo _("Kernel Version"); ?></td>
              <td style="text-align:left; padding:0.5rem; font-size:0.8rem"><?php echo htmlspecialchars($kernel_version, ENT_QUOTES); ?></td>
            </tr>
            <tr class="tr cbi-section-table-titles">
              <td style="text-align:left;width:30%;font-weight:bold; padding:0.5rem; font-size:0.8rem"><?php echo _("SN"); ?></td>
              <td style="text-align:left; padding:0.5rem; font-size:0.8rem"><?php echo htmlspecialchars($sn, ENT_QUOTES); ?></td>
            </tr>
            <tr class="tr cbi-section-table-titles">
              <td style="text-align:left;width:30%;font-weight:bold; padding:0.5rem; font-size:0.8rem"><?php echo _("Update Date"); ?></td>
              <td style="text-align:left; padding:0.5rem; font-size:0.8rem"><?php echo htmlspecialchars($fw_date, ENT_QUOTES); ?></td>
            </tr>
            <tr class="tr cbi-section-table-titles">
              <td style="text-align:left;width:30%;font-weight:bold; padding:0.5rem; font-size:0.8rem"><?php echo _("Local Time"); ?></td>
              <td style="text-align:left; padding:0.5rem; font-size:0.8rem" name="local_time" id="local_time"><?php echo htmlspecialchars($local_time, ENT_QUOTES); ?></td>
            </tr>
            <tr class="tr cbi-section-table-titles">
              <td style="text-align:left;width:30%;font-weight:bold; padding:0.5rem; font-size:0.8rem"><?php echo _("Uptime"); ?></td>
              <td style="text-align:left; padding:0.5rem; font-size:0.8rem"  name="uptime" id="uptime"><?php echo htmlspecialchars($uptime, ENT_QUOTES); ?></td>
            </tr>
            <!-- <tr class="tr cbi-section-table-titles">
              <td style="text-align:left;width:30rem;font-weight:bold"><?php echo _("Memory Used"); ?></td>
              
              <td style="text-align:left"  name="memory" id="memory">
                <div class="progress mb-2" style="height: 1.5rem;">
                  <div class="progress-bar bg-<?php echo htmlspecialchars($memused_status, ENT_QUOTES); ?>"
                      role="progressbar" aria-valuenow="<?php echo htmlspecialchars($memused, ENT_QUOTES); ?>" aria-valuemin="0" aria-valuemax="100"
                      style="width: <?php echo htmlspecialchars($memused, ENT_QUOTES); ?>%"><?php echo htmlspecialchars($memused, ENT_QUOTES); ?>%
                  </div>
                </div>
              </td>
            </tr>
            <tr class="tr cbi-section-table-titles">
              <td style="text-align:left;width:30rem;font-weight:bold"><?php echo _("CPU Load"); ?></td>
              
              <td style="text-align:left"  name="cpu_load" id="cpu_load">
                <div class="progress mb-2" style="height: 1.5rem;">
                  <div class="progress-bar bg-<?php echo htmlspecialchars($cpuload_status, ENT_QUOTES); ?>"
                      role="progressbar" aria-valuenow="<?php echo htmlspecialchars($cpuload, ENT_QUOTES); ?>" aria-valuemin="0" aria-valuemax="100"
                      style="width: <?php echo htmlspecialchars($cpuload, ENT_QUOTES); ?>%"><?php echo htmlspecialchars($cpuload, ENT_QUOTES); ?>%
                  </div>
                </div>
              </td>
            </tr>
            <tr class="tr cbi-section-table-titles">
              <td style="text-align:left;width:30rem;font-weight:bold"><?php echo _("CPU Temp"); ?></td>
              
              <td style="text-align:left"  name="cpu_temp" id="cpu_temp">
                <div class="progress mb-4" style="height: 1.5rem;">
                  <div class="progress-bar bg-<?php echo htmlspecialchars($cputemp_status, ENT_QUOTES); ?>"
                      role="progressbar" aria-valuenow="<?php echo htmlspecialchars($cputemp, ENT_QUOTES); ?>" aria-valuemin="0" aria-valuemax="100"
                      style="width: <?php echo htmlspecialchars(($cputemp*1.2), ENT_QUOTES); ?>%"><?php echo htmlspecialchars($cputemp, ENT_QUOTES); ?>°C
                  </div>
                </div>
              </td>
            </tr> -->
          </table>
          </div>
        </div>
        <div class="cbi-section cbi-tblsection" name="network">
          <h4><?php echo _("Network") ;?></h4>
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
                      </div>
                    </div>
                    </br>
                    </br>
                  <?php } ?>
                  <?php endforeach ?>
                  <?php if ($lteInfo['interface'] == "wwan0" || $lteInfo['interface'] == "usb0") { ?>
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
                          <div class="col-xs-3" style="color: #858796; width: 7rem"><?php echo _("IMEI:"); ?></div><div class="col-xs-3"><?php echo $lteInfo["imei"]; ?></div>
                        </div>
                        <div class="row mb-1">
                          <div class="col-xs-3" style="color: #858796; width: 7rem"><?php echo _("SIM:"); ?></div><div class="col-xs-3"><?php echo $lteInfo["sim"]; ?></div>
                        </div>
                        <div class="row mb-1">
                          <div class="col-xs-3" style="color: #858796; width: 7rem"><?php echo _("Status:"); ?></div><div class="col-xs-3"><?php echo $lteInfo["lte_status"]; ?></div>
                        </div>
                        <div class="row mb-1">
                          <div class="col-xs-3" style="color: #858796; width: 7rem"><?php echo _("Uptime:"); ?></div><div class="col-xs-3"><?php echo $lteInfo["uptime"]; ?></div>
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

                          <?php $leases_count = 0; foreach (array_slice($leases,0, 2) as $leases) : ?>
                          <tr>
                              <?php $props = explode(' ', $leases); if (sizeof($props) > 2) $leases_count++; ?>
                              <td><?php echo htmlspecialchars($props[3], ENT_QUOTES) ?></td>
                              <td><?php echo htmlspecialchars($props[2], ENT_QUOTES) ?></td>
                              <td><?php echo htmlspecialchars($props[1], ENT_QUOTES) ?></td>
                          </tr>
                          <?php endforeach ?>
                      </tbody>
                    </table>
                    <?php if (sizeof($clients) > 3 || $leases_count > 3) : ?>
                        <div class="col-lg-12 float-right">
                          <a class="btn btn-outline-info" role="button" href="<?php echo $moreLink ?>"><?php echo _("More");?>  <i class="fas fa-chevron-right"></i></a>
                        </div>
                    <?php elseif (sizeof($clients) == 0 && $leases_count == 0) : ?>
                        <div class="col-lg-12 mt-3"><?php echo _("No connected devices");?></div>
                    <?php endif; ?>
                  </div><!-- /.table-responsive -->
                </div><!-- /.card-body -->
              </div><!-- /.card -->
            </div><!-- /.col-md-6 -->
          </div><!-- /.row -->
        </div>
        <div class="col-lg-12 mt-3">
          <div class="row">
            <form action="dashboard" method="POST">
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
