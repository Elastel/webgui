  <?php ob_start() ?>
    <?php if (!RASPI_MONITOR_ENABLED) : ?>
      <div class="cbi-page-actions">
        <input type="submit" class="btn btn-outline btn-primary" name="savewgsettings" value="<?php echo _("Save settings"); ?>">
        <input type="submit" class="btn btn-success" name="applywgsettings" value="<?php echo _("Apply settings"); $msg=_("Restarting Wireguard"); ?>" data-toggle="modal" data-target="#hostapdModal" />
      </div>
    <?php endif ?>
    <div class="modal fade" id="hostapdModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <div class="modal-title" id="ModalLabel"><i class="fas fa-sync-alt mr-2"></i><?php echo $msg ?></div>
          </div>
          <div class="modal-body">
            <div class="col-md-12 mb-3 mt-1"><?php echo _("Executing Wireguard restart") ?>...</div>
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
              <?php echo _("WireGuard"); ?>
            </div>
            <div class="col">
              <button class="btn btn-light btn-icon-split btn-sm service-status float-right">
                <span class="icon text-gray-600"><i class="fas fa-circle service-status-<?php echo $serviceStatus ?>"></i></span>
                <span class="text service-status">wg <?php echo _($serviceStatus) ?></span>
              </button>
            </div>
          </div><!-- /.row -->
        </div><!-- /.card-header -->
        <div class="card-body">
        <?php $status->showMessages(); ?>
          <form role="form" action="/wireguard" enctype="multipart/form-data" method="POST">
            <?php echo CSRFTokenFieldTag() ?>
            <!-- Nav tabs -->
            <ul class="nav nav-tabs">
                <li class="nav-item"><a class="nav-link active" id="settingstab" href="#wgsettings" data-toggle="tab"><?php echo _("Settings"); ?></a></li>
                <li class="nav-item"><a class="nav-link" id="statustab" href="#wgstatus" data-toggle="tab"><?php echo _("Status"); ?></a></li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
              <?php echo renderTemplate("wg/settings", $__template_data) ?>
              <?php echo renderTemplate("wg/status", $__template_data) ?>
            </div><!-- /.tab-content -->

          <?php echo $buttons ?>
          </form>
        </div><!-- /.card-body -->
      </div><!-- /.card -->
    </div><!-- /.col-lg-12 -->
  </div><!-- /.row -->

