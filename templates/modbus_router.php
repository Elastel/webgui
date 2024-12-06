<?php 
  ob_start();
  if (!RASPI_MONITOR_ENABLED) :
    BtnSaveApplyCustom('savemodbusroutersettings', 'applymodbusroutersettings');
  endif;
  $msg = _('Restarting Modbus Router');
  page_progressbar($msg, _("Executing Modbus Router start"));
  $buttons = ob_get_clean(); 
  ob_end_clean();
?>

<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row">
          <div class="col">
          <?php echo _("Modbus Router"); ?>
          </div>
          <div class="col">
            <button class="btn btn-light btn-icon-split btn-sm service-status float-right">
              <span class="icon"><i class="fas fa-circle service-status-<?php echo $statusIcon ?>"></i></span>
              <span class="text service-status"><?php echo _($routerStatus) ?></span>
            </button>
          </div>
        </div><!-- ./row -->
      </div><!-- ./card-header -->
      <div class="card-body">
          <?php $status->showMessages(); ?>
          <form method="POST" action="modbus_router" role="form">
          <?php echo CSRFTokenFieldTag();
            echo '<div class="cbi-section cbi-tblsection">';
            RadioControlCustom(_('Modbus Router'), 'enabled', 'modbus', 'enableModbus', NULL, $modbusRouterConf['enabled']);

            echo '<div id="page_modbus" name="page_modbus">';

            $mode = array('Modbus RTU To Modbus TCP', 'Modbus TCP To Modbus RTU');
            SelectControlCustom(_('Mode'), 'mode', $mode, ($modbusRouterConf['mode'] != NULL) ? $mode[$modbusRouterConf['mode']] : $mode[0], 'mode');
            echo '<h5>'._("Modbus TCP Settings").'</h5>';
            InputControlCustom(_('IP Address'), 'address', 'address', NULL, $modbusRouterConf['address']);
            InputControlCustom(_('Port'), 'port', 'port', _('1~65535'), ($modbusRouterConf['port'] != NULL) ? $modbusRouterConf['port'] : '502');

            echo '<h5>'._("Modbus RTU Settings").'</h5>';
            exec("cat /etc/fw_model", $model);
            
            if ($model[0] == "EG324") {
              $comlist = array('/dev/ttyAMA0'=>'COM1', '/dev/ttyAMA1'=>'COM2', '/dev/ttyAMA2'=>'COM3', '/dev/ttyAMA3'=>'COM4');
            } else if ($model[0] == "EG324L") {
              $comlist = array('/dev/ttyS1'=>'COM1', '/dev/ttyS2'=>'COM2', '/dev/ttyS3'=>'COM3', '/dev/ttyS4'=>'COM4');
            } else if ($model[0] == "EC212") {
              $comlist = array('/dev/ttyS1'=>'COM1', '/dev/ttyS2'=>'COM2');
            } else {
              $comlist = array('/dev/ttyACM0'=>'COM1', '/dev/ttyACM1'=>'COM2');
            }
            SelectControlCustom(_('COM Interface'), 'com', $comlist, ($modbusRouterConf['com'] != NULL) ? $comlist[$modbusRouterConf['com']] : $comlist[0], 'com');

            $baudrate_list = array('1200'=>'1200', '2400'=>'2400', '4800'=>'4800', '9600'=>'9600', '19200'=>'19200', '38400'=>'38400',
            '57600'=>'57600', '115200'=>'115200', '230400'=>'230400');
            SelectControlCustom(_('Baudrate'), 'baudrate', $baudrate_list, ($modbusRouterConf['baudrate'] != NULL) ? $modbusRouterConf['baudrate'] : $baudrate_list['115200'], 'baudrate');

            $databit_list = array('7'=>'7', '8'=>'8');
            SelectControlCustom(_('Databit'), 'databit', $databit_list, ($modbusRouterConf['databit'] != NULL) ? $modbusRouterConf['databit'] : $databit_list['8'], 'databit'.$num);

            $stopbit_list = array('1'=>'1', '2'=>'2');
            SelectControlCustom(_('Stopbit'), 'stopbit', $stopbit_list, ($modbusRouterConf['stopbit'] != NULL) ? $modbusRouterConf['stopbit'] : $stopbit_list['1'], 'stopbit'.$num);

            $parity_list = array('N'=>'None', 'O'=>'Odd', 'E'=>'Even');
            SelectControlCustom(_('Parity'), 'parity', $parity_list, ($modbusRouterConf['parity'] != NULL) ? $parity_list[$modbusRouterConf['parity']] : $parity_list['N'], 'parity'.$num);
            echo '</div>';
            echo '</div>';

            echo $buttons; 
          ?>
          </form>
      </div><!-- card-body -->
    </div><!-- card -->
  </div><!-- col-lg-12 -->
</div>
<script>
  var a = "<?php echo $modbusRouterConf['enabled']; ?>";
  if (a == '1') {
    document.getElementById('page_modbus').style.display = 'block';
  } else {
    document.getElementById('page_modbus').style.display = 'none';
  }
</script>

