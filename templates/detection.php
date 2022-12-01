<?php ob_start() ?>
  <?php if (!RASPI_MONITOR_ENABLED) : ?>
      <div class="cbi-page-actions">
        <input type="submit" class="btn btn-outline btn-primary" value="<?php echo _("Save settings"); ?>" name="savedetectionsettings" />
        <input type="submit" class="btn btn-success" value="<?php echo _("Apply settings"); $msg=_("Restarting failover"); ?>" data-toggle="modal" data-target="#hostapdModal" name="applydetectionsettings" />
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
          <div class="col-md-12 mb-3 mt-1"><?php echo _("Executing failover start") ?>...</div>
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

