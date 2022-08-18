<?php ob_start() ?>
  <?php if (!RASPI_MONITOR_ENABLED) : ?>
      <div class="cbi-page-actions">
        <input type="submit" class="btn btn-outline btn-primary" value="<?php echo _("Save settings"); ?>" name="saveserversettings" />
        <input type="submit" class="btn btn-success" value="<?php echo _("Apply settings"); $msg=_("Restarting dct"); ?>" data-toggle="modal" data-target="#hostapdModal" name="applyserversettings" />
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
          <?php echo _("Server Setting"); ?>
          </div>
        </div><!-- ./row -->
      </div><!-- ./card-header -->
      <div class="card-body">
          <?php $status->showMessages(); ?>
          <form role="form" action="server_conf" enctype="multipart/form-data" method="POST">
          <?php echo CSRFTokenFieldTag() ?>
          <div class="cbi-section cbi-tblsection">
            <h4>Server Setting</h4>
            <ul class="nav nav-tabs">
              <li role="presentation" class="nav-item"><a class="nav-link active" href="#server1" aria-controls="server1" role="tab" data-toggle="tab"><?php echo _("Server1 Setting"); ?></a></li>
              <li role="presentation" class="nav-item"><a class="nav-link" href="#server2" aria-controls="server2" role="tab" data-toggle="tab"><?php echo _("Server2 Setting"); ?></a></li>
              <li role="presentation" class="nav-item"><a class="nav-link" href="#server3" aria-controls="server3" role="tab" data-toggle="tab"><?php echo _("Server3 Setting"); ?></a></li>
              <li role="presentation" class="nav-item"><a class="nav-link" href="#server4" aria-controls="server4" role="tab" data-toggle="tab"><?php echo _("Server4 Setting"); ?></a></li>
              <li role="presentation" class="nav-item"><a class="nav-link" href="#server5" aria-controls="server5" role="tab" data-toggle="tab"><?php echo _("Server5 Setting"); ?></a></li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <?php echo renderTemplate("dct/server1", $__template_data); ?>
                <?php echo renderTemplate("dct/server2", $__template_data); ?>
                <?php echo renderTemplate("dct/server3", $__template_data); ?>
                <?php echo renderTemplate("dct/server4", $__template_data); ?>
                <?php echo renderTemplate("dct/server5", $__template_data); ?>
            </div><!-- /.tab-content -->
          </div>
          <?php echo $buttons ?>
          </form>
      </div><!-- card-body -->
    </div><!-- card -->
  </div><!-- col-lg-12 -->
</div>