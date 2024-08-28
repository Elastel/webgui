<?php 
  ob_start();
  if (!RASPI_MONITOR_ENABLED) :
    BtnSaveApplyCustom('savebacclisettings', 'applybacclisettings');
  endif;
  $msg = _('Restarting BACnet Client');
  page_progressbar($msg, _("Executing dct start"));
  $buttons = ob_get_clean(); 
  ob_end_clean();
?>

<style>
    /* 基本样式 */
    .dropdown {
        position: relative;
        display: inline-block;
        width: 200px;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: white;
        border: 1px solid #ddd;
        border-radius: 4px;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        z-index: 1;
        width: 20rem;
        max-height: 150px;
        overflow-y: auto;
        left: 18.8rem
    }

    .dropdown-content div {
        padding: 10px;
        cursor: pointer;
    }

    .dropdown-content div:hover {
        background-color: #f1f1f1;
    }

    .show {
        display: block;
    }
</style>

<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row">
          <div class="col">
          <?php echo _("BACnet Client"); ?>
          </div>
        </div><!-- ./row -->
      </div><!-- ./card-header -->
      <div class="card-body">
          <?php $status->showMessages(); ?>
          <form method="POST" action="baccli_conf" role="form">
          <?php echo CSRFTokenFieldTag() ?>
            <div class="cbi-section cbi-tblsection">
              <?php 
                RadioControlCustom(_('BACnet Client'), 'enabled', 'bacnet', 'enableBACnet');
          
                echo '<div id="page_bacnet" name="page_bacnet">';
                $proto = array('BACnet/IP', 'BACnet/MSTP');
                SelectControlCustom(_('Protocol'), 'proto', $proto, $proto[0], 'proto', null, "bacnetProtocolChange()");

                echo '<div id="page_proto_ip" name="page_proto_ip">';
                SelectControlCustom(_('Interface'), 'ifname', $interface_list, $interface[0], 'ifname');
                // InputControlCustom(_('Remote IP'), 'ip_address', 'ip_address', _('If not, leave blank'));
                InputControlCustom(_('Port'), 'port', 'port', _('1~65535'));

                CheckboxControlCustom(_('BBMD'), 'bbmd_enabled', 'bbmd_enabled', null, null, 'enableBBMD()');
                echo '<div id="page_bbmd" name="page_bbmd">';
                InputControlCustom(_('Remote BBMD IP'), 'bbmd_ip', 'bbmd_ip');
                InputControlCustom(_('BBMD Port'), 'bbmd_port', 'bbmd_port', _('1~65535'));
                InputControlCustom(_('Registration Time'), 'bbmd_time', 'bbmd_time', _('minutes'));
                echo '</div>';
                echo '</div>';

                echo '<div id="page_proto_mstp" name="page_proto_mstp">';
                exec("cat /etc/fw_model", $model);
                
                if ($model[0] == "EG324" || $model[0] == "EG324L") {
                  $comlist = array('COM1'=>'COM1', 'COM2'=>'COM2');
                } else {
                  $comlist = array('COM1'=>'COM1');
                }
                SelectControlCustom(_('Interface'), 'interface', $comlist, $comlist[0], 'interface');

                $baudrate_list = array('1200'=>'1200', '2400'=>'2400', '4800'=>'4800', '9600'=>'9600', '19200'=>'19200', '38400'=>'38400',
                '57600'=>'57600', '115200'=>'115200', '230400'=>'230400');
                SelectControlCustom(_('Baudrate'), 'baudrate', $baudrate_list, $baudrate_list['38400'], 'baudrate');
                InputControlCustom(_('Source Address'), 'mac', 'mac');
                InputControlCustom(_('Max Master'), 'max_master', 'max_master', _('1~127'));
                InputControlCustom(_('Frames'), 'frames', 'frames', _('1~127'));
                echo '</div>';

                InputControlCustom(_('Device ID'), 'device_id', 'device_id', _('1~65535'));
                $collect_mode = array('poll'=>'poll', 'cov'=>'cov');
                SelectControlCustom(_('Collect Mode'), 'collect_mode', $collect_mode, $collect_mode[0], 'collect_mode');
              ?>

                <input type="hidden" name="table_data" value="" id="hidTD_baccli">
                <input type="hidden" name="option_list_baccli" value="" id="option_list_baccli">
                <div class="cbi-section cbi-tblsection" id="page_baccli" name="page_baccli">
                  <?php
                  $arr= array(
                    array("name"=>"Order",                "style"=>"", "descr"=>"", "ctl"=>"input"),
                    array("name"=>"Device Name",          "style"=>"", "descr"=>"", "ctl"=>"input"),
                    array("name"=>"Factor Name",          "style"=>"", "descr"=>"Multiple Factors Are Separated By Semicolon", "ctl"=>"input"),
                    array("name"=>"Object Device ID",            "style"=>"", "descr"=>"", "ctl"=>"input"),
                    array("name"=>"Object Identifier",    "style"=>"", "descr"=>"", "ctl"=>"input"),
                    array("name"=>"Reporting Center",     "style"=>"", "descr"=>"Multiple Servers Are Separated By Minus", "ctl"=>"input"),
                    array("name"=>"Operator",             "style"=>"display:none", "descr"=>"0 + - * /", "ctl"=>"select"),
                    array("name"=>"Operation Expression", "style"=>"display:none", "descr"=>"", "ctl"=>"input"),
                    array("name"=>"Operand",              "style"=>"display:none", "descr"=>"", "ctl"=>"input"),
                    array("name"=>"Accuracy",             "style"=>"display:none", "descr"=>"0~6", "ctl"=>"select"),
                    array("name"=>"Enable",               "style"=>"", "descr"=>"", "ctl"=>"check"),
                  );
                  page_table_title('baccli', $arr);
                  ?>
                  <div class="cbi-section-create">
                    <input type="button" class="cbi-button-add" name="popBox" value="Add" onclick="addData('baccli')">
                    <?php conf_im_ex('Baccli'); ?>
                  </div>
                </div>
              </div>
            </div>
            <?php echo $buttons ?>
          </form>
      </div><!-- card-body -->
    </div><!-- card -->
  </div><!-- col-lg-12 -->
</div>

<?php page_im_ex('Baccli');?>
<div id="popLayer"></div>
<div id="popBox" style="overflow:auto">
  <input hidden="hidden" name="page_type" id="page_type" value="0">
  <h4><?php echo _("Bacnet Client Object Setting"); ?></h4>
  <div class="cbi-section">
    <?php
      $table_name = 'baccli';
      InputControlCustom(_('Order'), $table_name.'.order', $table_name.'.order');

      InputControlCustom(_('Device Name'), $table_name.'.device_name', $table_name.'.device_name');

      InputControlCustom(_('Factor Name'), $table_name.'.factor_name', $table_name.'.factor_name', _('Multiple Factors Are Separated By Semicolon'));
    ?>

    <div class="cbi-value">
        <input type="hidden" name="bacnet_discover_data" value="" id="bacnet_discover_data">
        <label class="cbi-value-title"><?php echo _("Object Device ID"); ?></label>
        <input type="text" class="cbi-input-text" name="baccli.object_device_id" id="baccli.object_device_id" oninput="filterFunction()">
        <div id="deviceIdList" class="dropdown-content"></div>
        <button class="btn rounded-right btn_bacdiscover" type="button"><i class="fas fa-sync"></i></button>
    </div>

    <div class="cbi-value">
        <label class="cbi-value-title"><?php echo _("Object Identifier"); ?></label>
        <input type="text" class="cbi-input-text" name="baccli.object_id" id="baccli.object_id" oninput="filterFunctionObject()">
        <div id="objectIdList" class="dropdown-content"></div>
    </div>

    <?php
      //InputControlCustom(_('Object Device ID'), $table_name.'.object_device_id', $table_name.'.object_device_id');

      // InputControlCustom(_('Object Identifier'), $table_name.'.object_id', $table_name.'.object_id');

      InputControlCustom(_('Reporting Center'), $table_name.'.server_center', $table_name.'.server_center', _('Multiple Servers Are Separated By Minus'));

      $operator_list = [_('None'), '+', '-', '*', '/', _('Expression')];
      SelectControlCustom(_('Operator'), $table_name.'.operator', $operator_list, $operator_list[0], $table_name.'.operator', _('0 + - * /'), "selectOperator('baccli')");
    
      echo '<div name="page_operand" id="page_operand">';
      InputControlCustom(_('Operand'), $table_name.'.operand', $table_name.'.operand');
      echo '</div>';

      echo '<div name="page_ex" id="page_ex">';
      InputControlCustom(_('Operation Expression'), $table_name.'.ex', $table_name.'.ex', _('(x + 10) * 10,  x is collected data'));
      echo '</div>';

      $accuracy_list = ['0', '1', '2', '3', '4', '5', '6'];
      SelectControlCustom(_('Accuracy'), $table_name.'.accuracy', $accuracy_list, $accuracy_list[0], $table_name.'.accuracy', _('0 + - * /'));

      CheckboxControlCustom(_('Enable'), $table_name.'.enabled', $table_name.'.enabled', 'checked');
    ?>
  </div>

  <div class="right">
    <button class="cbi-button" onclick="closeBox()"><?php echo _("Dismiss"); ?></button>
    <button class="cbi-button cbi-button-positive important" onclick="saveData('baccli')"><?php echo _("Save"); ?></button>
  </div>
</div><!-- popBox -->
<script>
</script>

