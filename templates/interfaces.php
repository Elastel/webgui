<?php ob_start() ?>
  <?php if (!RASPI_MONITOR_ENABLED) : ?>
      <div class="cbi-page-actions">
        <input type="submit" class="btn btn-outline btn-primary" value="<?php echo _("Save settings"); ?>" name="saveinterfacesettings" />
        <input type="submit" class="btn btn-success" value="<?php echo _("Apply settings"); $msg=_("Restarting dct"); ?>" data-toggle="modal" data-target="#hostapdModal" name="applyinterfacesettings" />
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
          <?php echo _("Interface Setting"); ?>
          </div>
        </div><!-- ./row -->
      </div><!-- ./card-header -->
      <div class="card-body">
          <?php $status->showMessages(); ?>
          <form method="POST" action="interfaces_conf" role="form">
          <?php echo CSRFTokenFieldTag() ?>

          <div class="cbi-section" data-idref="e02cc6f3">
            <h4>Serial Port Setting</h4>
            <ul class="nav nav-tabs">
              <li role="presentation" class="nav-item"><a class="nav-link active" href="#com1" aria-controls="com1" role="tab" data-toggle="tab"><?php echo _("COM1/RS485"); ?></a></li>
              <li role="presentation" class="nav-item"><a class="nav-link" href="#com2" aria-controls="com2" role="tab" data-toggle="tab"><?php echo _("COM2/RS485"); ?></a></li>
              <li role="presentation" class="nav-item"><a class="nav-link" href="#com3" aria-controls="com3" role="tab" data-toggle="tab"><?php echo _("COM3/RS485/RS232"); ?></a></li>
              <li role="presentation" class="nav-item"><a class="nav-link" href="#com4" aria-controls="com4" role="tab" data-toggle="tab"><?php echo _("COM4/RS485/RS232"); ?></a></li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <?php echo renderTemplate("dct/interface_com1", $__template_data); ?>
                <?php echo renderTemplate("dct/interface_com2", $__template_data); ?>
                <?php echo renderTemplate("dct/interface_com3", $__template_data); ?>
                <?php echo renderTemplate("dct/interface_com4", $__template_data); ?>
            </div><!-- /.tab-content -->
          </div>

          <div class="cbi-section" data-idref="e02cc6f3">
            <h4>TCP Server Setting</h4>
            <ul class="nav nav-tabs">
              <li role="presentation" class="nav-item"><a class="nav-link active" href="#tcp1" aria-controls="tcp1" role="tab" data-toggle="tab"><?php echo _("TCP Server1"); ?></a></li>
              <li role="presentation" class="nav-item"><a class="nav-link" href="#tcp2" aria-controls="tcp2" role="tab" data-toggle="tab"><?php echo _("TCP Server2"); ?></a></li>
              <li role="presentation" class="nav-item"><a class="nav-link" href="#tcp3" aria-controls="tcp3" role="tab" data-toggle="tab"><?php echo _("TCP Server3"); ?></a></li>
              <li role="presentation" class="nav-item"><a class="nav-link" href="#tcp4" aria-controls="tcp4" role="tab" data-toggle="tab"><?php echo _("TCP Server4"); ?></a></li>
              <li role="presentation" class="nav-item"><a class="nav-link" href="#tcp5" aria-controls="tcp5" role="tab" data-toggle="tab"><?php echo _("TCP Server5"); ?></a></li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <?php echo renderTemplate("dct/interface_tcp1", $__template_data); ?>
                <?php echo renderTemplate("dct/interface_tcp2", $__template_data); ?>
                <?php echo renderTemplate("dct/interface_tcp3", $__template_data); ?>
                <?php echo renderTemplate("dct/interface_tcp4", $__template_data); ?>
                <?php echo renderTemplate("dct/interface_tcp5", $__template_data); ?>
            </div><!-- /.tab-content -->
          </div>
          <?php echo $buttons ?>
          </form>
      </div><!-- card-body -->
    </div><!-- card -->
  </div><!-- col-lg-12 -->
</div>

