<?php 
  ob_start();
  if (!RASPI_MONITOR_ENABLED) :
    BtnSaveApplyCustom('saveiec104settings', 'applyiec104settings');
  endif;
  $msg = _('Restarting dct');
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
          <?php echo _("IEC104 Setting"); ?>
          </div>
        </div><!-- ./row -->
      </div><!-- ./card-header -->
      <div class="card-body">
          <?php $status->showMessages(); ?>
          <form method="POST" action="iec104_conf" role="form">
            <input type="hidden" name="table_data" value="" id="hidTD_iec104">
            <input type="hidden" name="option_list_iec104" value="" id="option_list_iec104">
            <?php echo CSRFTokenFieldTag();
            $arr= array(
              array("name"=>"Order",                "style"=>"", "descr"=>""),
              array("name"=>"Device Name",          "style"=>"", "descr"=>""),
              array("name"=>"Belonged Interface",   "style"=>"", "descr"=>""),
              array("name"=>"Factor Name",          "style"=>"", "descr"=>"Multiple Factors Are Separated By Semicolon"),
              array("name"=>"Type ID",              "style"=>"", "descr"=>""),
              array("name"=>"Start IOA",            "style"=>"", "descr"=>"0~255"),
              array("name"=>"Common Address",       "style"=>"", "descr"=>""),
              // array("name"=>"Data Type",            "style"=>"", "descr"=>""),
              array("name"=>"Reporting Center",     "style"=>"", "descr"=>"Multiple Servers Are Separated By Minus"),
              array("name"=>"Operator",             "style"=>"display:none", "descr"=>"0 + - * /"),
              array("name"=>"Operation Expression", "style"=>"display:none", "descr"=>""),
              array("name"=>"Operand",              "style"=>"display:none", "descr"=>""),
              array("name"=>"Accuracy",             "style"=>"display:none", "descr"=>"0~6"),
              array("name"=>"SMS&Email Reporting",  "style"=>"display:none", "descr"=>""),
              array("name"=>"Report Type",          "style"=>"display:none", "descr"=>""),
              array("name"=>"Alarm Up Limit",       "style"=>"display:none", "descr"=>""),
              array("name"=>"Alarm Down Limit",     "style"=>"display:none", "descr"=>""),
              array("name"=>"Phone Number",         "style"=>"display:none", "descr"=>""),
              array("name"=>"Email",                "style"=>"display:none", "descr"=>""),
              array("name"=>"Contents",             "style"=>"display:none", "descr"=>""),
              array("name"=>"Enable",               "style"=>"", "descr"=>""),
            );
            ?>
            <div class="cbi-section cbi-tblsection" id="page_iec104" name="page_iec104">
              <?php page_table_title('iec104', $arr); ?>
              <div class="cbi-section-create">
                <input type="button" class="cbi-button-add" name="popBox" value="Add" onclick="addData('iec104')">
                <?php conf_im_ex('Iec104'); ?>
              </div>
            </div>
            <?php echo $buttons ?>
          </form>
      </div><!-- card-body -->
    </div><!-- card -->
  </div><!-- col-lg-12 -->
</div>

<?php page_im_ex('Iec104');?>
<div id="popLayer"></div>
<div id="popBox" style="overflow:auto">
  <input hidden="hidden" name="page_type" id="page_type" value="0">
  <h4><?php echo _("Iec104 Rules Setting"); ?></h4>
  <div class="cbi-section">
    <?php
      $table_name = 'iec104';
      InputControlCustom(_('Order'), $table_name.'.order', $table_name.'.order');

      InputControlCustom(_('Device Name'), $table_name.'.device_name', $table_name.'.device_name');

      $interface_list = get_belonged_interface(-1, TcpProtoEnum::TCP_PROTO_IEC104);
      SelectControlCustom(_('Belonged Interface'), $table_name.'.belonged_com', $interface_list, $interface_list[0], $table_name.'.belonged_com');

      InputControlCustom(_('Factor Name'), $table_name.'.factor_name', $table_name.'.factor_name', _('Multiple Factors Are Separated By Semicolon'));
      
      SelectControlCustom(_('Type ID'), $table_name.'.type_id', $type_id_list, $type_id_list[0], $table_name.'.type_id');

      InputControlCustom(_('Start IOA'), $table_name.'.start_addr', $table_name.'.start_addr', _('0~65535'));

      InputControlCustom(_('Common Address'), $table_name.'.common_addr', $table_name.'.common_addr');

      // SelectControlCustom(_('Data Type'), $table_name.'.data_type', $data_type_list, $data_type_list[0], $table_name.'.data_type');

      InputControlCustom(_('Reporting Center'), $table_name.'.server_center', $table_name.'.server_center', _('Multiple Servers Are Separated By Minus'));

      $operator_list = [_('None'), '+', '-', '*', '/', _('Expression')];
      SelectControlCustom(_('Operator'), $table_name.'.operator', $operator_list, $operator_list[0], $table_name.'.operator', _('0 + - * /'), "selectOperator('$table_name')");
    
      echo '<div name="page_operand" id="page_operand">';
      InputControlCustom(_('Operand'), $table_name.'.operand', $table_name.'.operand');
      echo '</div>';

      echo '<div name="page_ex" id="page_ex">';
      InputControlCustom(_('Operation Expression'), $table_name.'.ex', $table_name.'.ex', _('(x + 10) * 10,  x is collected data'));
      echo '</div>';

      $accuracy_list = ['0', '1', '2', '3', '4', '5', '6'];
      SelectControlCustom(_('Accuracy'), $table_name.'.accuracy', $accuracy_list, $accuracy_list[0], $table_name.'.accuracy', _('0 + - * /'));

      CheckboxControlCustom(_('SMS&Email Reporting'), $table_name.'.sms_reporting', $table_name.'.sms_reporting', null, null, "enableAlarm('$table_name')");

      echo '<div name="page_sms" id="page_sms">';
      $report_type = ['Change reporting', 'Alarm reporting'];
      SelectControlCustom(_('Report Type'), $table_name.'.report_type', $report_type, $report_type[0], $table_name.'.report_type', null, "selectReportType('$table_name')");
      
      echo '<div name="page_alarm" id="page_alarm">';
      InputControlCustom(_('Alarm Up Limit'), $table_name.'.alarm_up', $table_name.'.alarm_up');

      InputControlCustom(_('Alarm Down Limit'), $table_name.'.alarm_down', $table_name.'.alarm_down');
      echo '</div>';
      InputControlCustom(_('Phone Number'), $table_name.'.phone_num', $table_name.'.phone_num', _('Multiple Phones Are Separated By Comma'));

      InputControlCustom(_('Email'), $table_name.'.email', $table_name.'.email', _('Multiple emails Are Separated By Comma'));
      
      InputControlCustom(_('Contents'), $table_name.'.contents', $table_name.'.contents');

      InputControlCustom(_('Retry Interval'), $table_name.'.retry_interval', $table_name.'.retry_interval', _('Minutes, it must be a multiple of collect period'));

      InputControlCustom(_('Again Interval'), $table_name.'.again_interval', $table_name.'.again_interval', _('Minutes, it must be a multiple of collect period'));
      echo '</div>';

      CheckboxControlCustom(_('Enable'), $table_name.'.enabled', $table_name.'.enabled', 'checked');
    ?>
  </div>

  <div class="right">
    <button class="cbi-button" onclick="closeBox()"><?php echo _("Dismiss"); ?></button>
    <button class="cbi-button cbi-button-positive important" onclick="saveData('iec104')"><?php echo _("Save"); ?></button>
  </div>
</div><!-- popBox -->