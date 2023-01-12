<?php ob_start() ?>
  <?php if (!RASPI_MONITOR_ENABLED) : ?>
      <div class="cbi-page-actions">
        <input type="submit" class="btn btn-outline btn-primary" value="<?php echo _("Save settings"); ?>" name="savesettings" />
        <input type="submit" class="btn btn-success" value="<?php echo _("Apply settings"); $msg=_("Restarting Macchina"); ?>" data-toggle="modal" data-target="#hostapdModal" name="applysettings" />
      </div>
  <?php endif ?>
  <!-- Modal -->
  <div class="modal fade" id="hostapdModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <div class="modal-title" id="ModalLabel"><i class="fas fa-sync-alt mr-2"></i><?php echo $msg ?></div>
        </div>
        <div class="modal-body">
          <div class="col-md-12 mb-3 mt-1"><?php echo _("Executing Macchina start") ?>...</div>
          <div class="progress" style="height: 20px;">
            <div class="progress-bar bg-info" role="progressbar" id="progressBar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="9"></div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline btn-primary" data-dismiss="modal"><?php echo _("Close"); ?></button>
        </div>
      </div>
    </div>
  </div>
<?php $buttons = ob_get_clean(); ob_end_clean() ?>

<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row">
          <div class="col">
          <?php echo _("Macchina"); ?>
          </div>
        </div><!-- ./row -->
      </div><!-- ./card-header -->
      <div class="card-body">
          <?php $status->showMessages(); ?>
          <form role="form" action="macchina" enctype="multipart/form-data" method="POST">
          <?php echo CSRFTokenFieldTag() ?>
            <div class="cbi-section cbi-tblsection">
                <div class="cbi-value">
                    <label class="cbi-value-title"><?php echo _("Macchina"); ?></label>
                    <input class="cbi-input-radio" id="macchina_enable" name="enabled" value="1" type="radio" 
                    <?php if ($enabled[0] == 1) { ?> checked <?php } ?> onchange="enableMacchina(true)">
                    <label ><?php echo _("Enable"); ?></label>

                    <input class="cbi-input-radio" id="macchina_disable" name="enabled" value="0" type="radio" 
                    <?php if ($enabled[0] == 0) { ?> checked <?php } ?> onchange="enableMacchina(false)">
                    <label ><?php echo _("Disable"); ?></label>
                </div>
                <div id="page_macchina" name="page_macchina" <?php if ($enabled[0] != 1) { ?> style="display: none;" <?php } ?>>
                    <div class="cbi-value">
                        <label class="cbi-value-title"><?php echo _("Domain"); ?></label>
                        <input type="text" class="cbi-input-text" name="domain" id="domain" value="<?php echo _(($domain[0] != null) ? $domain[0] : ""); ?>"/>
                    </div>

                    <div class="cbi-value">
                        <label class="cbi-value-title"><?php echo _("Device ID"); ?></label>
                        <input type="text" class="cbi-input-text" name="device_id" id="device_id" value="<?php echo _(($device_id[0] != null) ? $device_id[0] : ""); ?>"/>
                    </div>

                    <div class="cbi-value">
                        <label class="cbi-value-title"><?php echo _("URL"); ?></label>
                        <input type="text" class="cbi-input-text" name="url" id="url" value="<?php echo _(($url[0] != null) ? $url[0] : ""); ?>"/>
                    </div>

                    <div class="cbi-value">
                      <label class="cbi-value-title"><?php echo _("Enabled http"); ?></label>
                      <input type="checkbox" class="cbi-input-checkbox" name="enabled_http" id="enabled_http" value="1" <?php echo _(($enabled_http[0] == 1) ? "checked" : ""); ?>/>
                    </div>

                    <div class="cbi-value">
                      <label class="cbi-value-title"><?php echo _("Enabled SSH"); ?></label>
                      <input type="checkbox" class="cbi-input-checkbox" name="enabled_ssh" id="enabled_ssh" value="1" <?php echo _(($enabled_ssh[0] == 1) ? "checked" : ""); ?>/>
                    </div>

                    <div class="cbi-value">
                        <label class="cbi-value-title"><?php echo _("Status:"); ?></label>
                        <label class="info-label" id="run_status" name="run_status">
                          <?php echo _(($run_status[0] != null) ? "<font color=\"green\">Runing</font>" : "<font color=\"red\">Stop</font>"); ?>
                        </label>
                    </div>

                    <!-- <div class="cbi-value">
                        <label class="cbi-value-title"><?php echo _("Account Login:"); ?></label>
                        <a href="https://reflector.remote.macchina.io/my-devices/login" target="_blank"><?php echo _("macchina.io"); ?></a>
                        &nbsp&nbsp&nbsp&nbsp
                        <label class="info-label"><?php echo _("Configuration file can be downloaded by the url."); ?></label>
                    </div> -->
                </div>
            </div>
            <?php echo $buttons ?>
          </form>
      </div><!-- card-body -->
    </div><!-- card -->
  </div><!-- col-lg-12 -->
</div>
<script type="text/javascript">
  function enableMacchina(state) {
      if (state) {
        $('#page_macchina').show();
      } else {
        $('#page_macchina').hide();
      }
  }
</script>

