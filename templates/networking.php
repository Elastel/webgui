<?php ob_start() ?>
  <?php if (!RASPI_MONITOR_ENABLED) : ?>
      <input type="submit" class="btn btn-outline btn-primary" value="<?php echo _("Save settings"); ?>" name="savenetworksettings" />
      <input type="submit" class="btn btn-success" value="<?php echo _("Apply settings"); $msg=_("Restarting networking"); ?>" data-toggle="modal" data-target="#hostapdModal" name="applynetworksettings" />
  <?php endif ?>
  <!-- Modal -->
  <div class="modal fade" id="hostapdModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <div class="modal-title" id="ModalLabel"><i class="fas fa-sync-alt mr-2"></i><?php echo $msg ?></div>
          </div>
          <div class="modal-body">
            <div class="col-md-12 mb-3 mt-1"><?php echo _("Executing networking service start") ?>...</div>
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
            <?php echo _("WAN"); ?>
          </div>
        </div><!-- ./row -->
      </div><!-- ./card-header -->

      <div class="card-body">
        <?php $status->showMessages(); ?>
        <form role="form" action="network_conf" method="POST">
          <?php echo CSRFTokenFieldTag() ?>
        <ul class="nav nav-tabs">
          <li role="presentation" class="nav-item"><a class="nav-link active" href="#wired" aria-controls="wired" role="tab" data-toggle="tab"><?php echo _("Wired"); ?></a></li>
          <?php if ($lte_enabled == '1') { ?>
          <li role="presentation" class="nav-item"><a class="nav-link" href="#lte" aria-controls="lte" role="tab" data-toggle="tab"><?php echo _("LTE"); ?></a></li>
          <?php } ?>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <?php echo renderTemplate("networking/wired", $__template_data) ?>
            <?php if ($lte_enabled == '1') { 
              echo renderTemplate("networking/lte", $__template_data);
            } ?>
        </div><!-- /.tab-content -->

        <?php echo $buttons ?>
        </form>
      </div><!-- /.card-body -->
    </div><!-- /.card -->
  </div><!-- /.col-lg-12 -->
</div><!-- /.row -->

