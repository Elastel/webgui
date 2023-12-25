<?php 
  ob_start();
  if (!RASPI_MONITOR_ENABLED) :
    BtnSaveApplyCustom('savesettings', 'applysettings');
  endif;
  $msg = _('Restarting lorawan');
  page_progressbar($msg, _("Executing lorawan start"));
  $buttons = ob_get_clean(); 
  ob_end_clean();
?>

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

