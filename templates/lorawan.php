<?php ob_start() ?>
  <?php if (!RASPI_MONITOR_ENABLED) : ?>
      <div class="cbi-page-actions">
        <input type="submit" class="btn btn-outline btn-primary" value="<?php echo _("Save settings"); ?>" name="savesettings" />
        <input type="submit" class="btn btn-success" value="<?php echo _("Apply settings"); $msg=_("Restarting lorawan"); ?>" data-toggle="modal" data-target="#hostapdModal" name="applysettings" />
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
          <div class="col-md-12 mb-3 mt-1"><?php echo _("Executing lorawan start") ?>...</div>
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
          <?php echo _("LoRaWan"); ?>
          </div>
        </div><!-- ./row -->
      </div><!-- ./card-header -->
      <div class="card-body">
          <?php $status->showMessages(); ?>
          <form role="form" action="lorawan_conf" enctype="multipart/form-data" method="POST">
          <?php echo CSRFTokenFieldTag() ?>

          <div class="cbi-section">
            <ul class="nav nav-tabs">
                <li role="presentation" class="nav-item"><a class="nav-link active" href="#general" aria-controls="general" role="tab" data-toggle="tab"><?php echo _("General"); ?></a></li>
                <li role="presentation" class="nav-item"><a class="nav-link" href="#radio" aria-controls="radio" role="tab" data-toggle="tab"><?php echo _("Radio"); ?></a></li>
                <li role="presentation" class="nav-item"><a class="nav-link" href="#channels" aria-controls="channels" role="tab" data-toggle="tab"><?php echo _("Channels"); ?></a></li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <?php echo renderTemplate("lorawan/general", $__template_data); ?>
                <?php echo renderTemplate("lorawan/radio", $__template_data); ?>
                <?php echo renderTemplate("lorawan/channels", $__template_data); ?>
            </div>
          </div>
          <?php echo $buttons ?>
          </form>
      </div><!-- card-body -->
    </div><!-- card -->
  </div><!-- col-lg-12 -->
</div>

