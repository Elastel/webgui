<?php ob_start() ?>
  <?php if (!RASPI_MONITOR_ENABLED) : ?>
      <div class="cbi-page-actions">
        <input type="submit" class="btn btn-outline btn-primary" value="<?php echo _("Save settings"); ?>" name="savebacnetsettings" />
        <input type="submit" class="btn btn-success" value="<?php echo _("Apply settings"); $msg=_("Restarting BACnet Server"); ?>" data-toggle="modal" data-target="#hostapdModal" name="applybacnetsettings" />
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
          <div class="col-md-12 mb-3 mt-1"><?php echo _("Executing dct start") ?>...</div>
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
          <?php echo _("BACnet Server"); ?>
          </div>
        </div><!-- ./row -->
      </div><!-- ./card-header -->
      <div class="card-body">
          <?php $status->showMessages(); ?>
          <form method="POST" action="bacnet" role="form">
          <?php echo CSRFTokenFieldTag() ?>
            <div class="cbi-section cbi-tblsection">
              <div class="cbi-value">
                <label class="cbi-value-title"><?php echo _("BACnet Server"); ?></label>
                <input class="cbi-input-radio" id="bacnet_enable" name="enabled" value="1" type="radio" checked onchange="enableBACnet(true)">
                <label ><?php echo _("Enable"); ?></label>

                <input class="cbi-input-radio" id="bacnet_disable" name="enabled" value="0" type="radio" onchange="enableBACnet(false)">
                <label ><?php echo _("Disable"); ?></label>
              </div>
          
              <div id="page_bacnet" name="page_bacnet">
                <div class="cbi-value">
                  <label class="cbi-value-title"><?php echo _("Port"); ?></label>
                  <input type="text" class="cbi-input-text" name="port" id="port"/>
                  <label class="cbi-value-description"><?php echo _("1~65535"); ?></label>
                </div> 

                <div class="cbi-value">
                  <label class="cbi-value-title"><?php echo _("Device ID"); ?></label>
                  <input type="text" class="cbi-input-text" name="device_id" id="device_id"/>
                  <label class="cbi-value-description"><?php echo _("1~65535"); ?></label>
                </div>

                <div class="cbi-value">
                  <label class="cbi-value-title"><?php echo _("Object Name"); ?></label>
                  <input type="text" class="cbi-input-text" name="object_name" id="object_name"/>
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
  function enableBACnet(state) {
      if (state) {
        $('#page_bacnet').show();
      } else {
        $('#page_bacnet').hide();
      }
  }

</script>

