<?php 
  ob_start();
  if (!RASPI_MONITOR_ENABLED) :
    BtnSaveApplyCustom('savebacnetsettings', 'applybacnetsettings');
  endif;
  $msg = _('Restarting BACnet Server');
  page_progressbar($msg, _("Executing dct start"));
  $buttons = ob_get_clean(); 
  ob_end_clean();
?>

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
          <?php echo CSRFTokenFieldTag();
            echo '<div class="cbi-section cbi-tblsection">';
            RadioControlCustom(_('BACnet Server'), 'enabled', 'bacnet', 'enableBACnet');
      
            echo '<div id="page_bacnet" name="page_bacnet">';
            InputControlCustom(_('Port'), 'port', 'port', _('1~65535'));

            InputControlCustom(_('Device ID'), 'device_id', 'device_id', _('1~65535'));

            InputControlCustom(_('Object Name'), 'object_name', 'object_name');
            
            echo '</div>
            </div>';

            echo $buttons; 
          ?>
          </form>
      </div><!-- card-body -->
    </div><!-- card -->
  </div><!-- col-lg-12 -->
</div>

