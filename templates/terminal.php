<?php ob_start() ?>
  <?php if (!RASPI_MONITOR_ENABLED) : ?>
      <div class="cbi-page-actions">
        <input type="submit" class="btn btn-outline btn-primary" value="<?php echo _("Save settings"); ?>" name="saveterminalsettings" />
        <input type="submit" class="btn btn-success" value="<?php echo _("Apply settings"); $msg=_("Restarting terminal"); ?>" data-toggle="modal" data-target="#hostapdModal" name="applyterminalsettings" />
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
          <div class="col-md-12 mb-3 mt-1"><?php echo _("Executing terminal start") ?>...</div>
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
          <?php echo _("Terminal"); ?>
          </div>
        </div><!-- ./row -->
      </div><!-- ./card-header -->
      <div class="card-body">
          <?php $status->showMessages(); ?>
          <form method="POST" action="terminal" enctype="multipart/form-data" role="form">
          <?php echo CSRFTokenFieldTag() ?>
            <label style="font-size:1rem"><?php echo _("Config"); ?></label>
            <div class="cbi-section cbi-tblsection">
              <div class="cbi-value">
                  <label class="cbi-value-title"><?php echo _("Port"); ?></label>
                  <input type="text" class="cbi-input-text" name="port" id="port" value="<?php echo $port[0]; ?>" />
                  <label class="cbi-value-description"><?php echo _("0~65535"); ?></label>
              </div>

              <div class="cbi-value">
                  <label class="cbi-value-title"><?php echo _("Interface"); ?></label>
                  <?php SelectorOptionsCustom('interface', $interface_list, $interface[0], 'interface') ?>
              </div>
            </div>
            <?php echo $buttons ?>

            <div class="cbi-value">
              <label class="cbi-value-title"><?php echo _("Upload Files"); ?></label>
              <label for="upload" class="cbi-file-lable">
                  <input type="button" class="cbi-file-btn" id="upload_btn" value="<?php echo _("Choose file"); ?>">
                  <span id="upload_text"><?php echo _("No file chosen"); ?></span>
                  <input type="file" class="cbi-file" name="upload_file" id="upload_file" onchange="fileChange()">
              </label>
              <input type="submit" class="btn btn-success" style="margin-left:12rem;"  value="<?php echo _("Upload"); ?>" name="upload">
            </div>
          </form>
          <div class="cbi-value">
            <label style="font-size:1rem"><?php echo _("Terminal"); ?></label>
            <iframe src="<?php echo 'http://' . $ip[0] . ':' . $port[0] ?>" style="width: 100%; min-height: 500px; border: 2px solid #000; border-radius: 3px; resize: vertical;"></iframe>
          </div>
      </div><!-- card-body -->
    </div><!-- card -->
  </div><!-- col-lg-12 -->
</div>

<script type="text/javascript">
  function fileChange() {
    $('#upload_text').html($('#upload_file')[0].files[0].name);
  }
</script>

