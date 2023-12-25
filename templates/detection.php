<?php 
  ob_start();
  if (!RASPI_MONITOR_ENABLED) :
    BtnSaveApplyCustom('savedetectionsettings', 'applydetectionsettings');
  endif;
  $msg = _('Restarting failover');
  page_progressbar($msg, _("Executing failover start"));
  $buttons = ob_get_clean(); 
  ob_end_clean();
?>

<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row">
          <div class="col">
          <?php echo _("Online Detection"); ?>
          </div>
        </div><!-- ./row -->
      </div><!-- ./card-header -->
      <div class="card-body">
          <?php $status->showMessages(); ?>
          <form method="POST" action="detection_conf" role="form">
          <?php echo CSRFTokenFieldTag() ?>
            <div class="cbi-section cbi-tblsection">
                <div id="page_detection" name="page_detection">
                    <div class="cbi-value">
                        <label class="cbi-value-title">Primary Detection Server</label>
                        <input type="text" class="cbi-input-text" name="primary_addr" id="primary_addr" 
                        value="<?php echo ($primary_addr[0] != null ? $primary_addr[0] : ""); ?>" />
                    </div>
                    
                    <div class="cbi-value">
                        <label class="cbi-value-title">Second Detection Server</label>
                        <input type="text" class="cbi-input-text" name="secondary_addr" id="secondary_addr" 
                        value="<?php echo ($secondary_addr[0] != null ? $secondary_addr[0] : ""); ?>" />
                    </div>

                    <div class="cbi-value">
                        <label class="cbi-value-title">Enable Reboot</label>
                        <input type="checkbox" class="cbi-input-checkbox" onchange="enableReboot(this)" name="enabled_reboot" id="enabled_reboot" 
                        value="1" <?php echo ($enabled_reboot[0] == 1 ? 'checked' : ""); ?> />
                    </div>
                    <div class="cbi-value" id="page_reboot" name="page_reboot" <?php if ($enabled_reboot[0] != 1) { ?> style="display: none;" <?php } ?> >
                        <label class="cbi-value-title">Reboot After Interval</label>
                        <input type="text" class="cbi-input-text" name="reboot_inter" id="reboot_inter" 
                        value="<?php echo ($reboot_inter[0] != null ? $reboot_inter[0] : ""); ?>" />
                        <label class="cbi-value-description">Minutes</label>
                    </div>

                </div>
            </div>
            <?php echo $buttons ?>
          </form>
      </div><!-- card-body -->
    </div><!-- card -->
  </div><!-- col-lg-12 -->
</div>
<script type="text/javascript">
    function enableReboot(checkbox) {
        if (checkbox.checked == true) {
            $('#page_reboot').show();
        } else {
            $("#page_reboot").hide();
        }
    } 
</script>

