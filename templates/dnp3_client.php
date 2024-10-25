<?php 
  ob_start();
  if (!RASPI_MONITOR_ENABLED) :
    BtnSaveApplyCustom('savednp3clisettings', 'applydnp3clisettings');
  endif;
  $msg = _('Restarting DNP3 Client');
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
          <?php echo _("DNP3 Rules"); ?>
          </div>
        </div><!-- ./row -->
      </div><!-- ./card-header -->
      <div class="card-body">
          <?php $status->showMessages(); ?>
          <form method="POST" action="dnp3cli_conf" role="form">
          <?php echo CSRFTokenFieldTag() ?>
            <div class="cbi-section cbi-tblsection">
              <input type="hidden" name="table_data" value="" id="hidTD_dnp3cli">
              <input type="hidden" name="option_list_dnp3cli" value="" id="option_list_dnp3cli">
              <div class="cbi-section cbi-tblsection" id="page_dnp3cli" name="page_dnp3cli">
                <?php
                $arr= array(
                  array("name"=>"Order",                "style"=>"", "descr"=>"", "ctl"=>"input"),
                  array("name"=>"Device Name",          "style"=>"", "descr"=>"", "ctl"=>"input"),
                  array("name"=>"Belonged Interface",   "style"=>"", "descr"=>"", "ctl"=>"select"),
                  array("name"=>"Tag Name",             "style"=>"", "descr"=>"", "ctl"=>"input"),
                  array("name"=>"Group ID",             "style"=>"", "descr"=>"", "ctl"=>"select"),
                  array("name"=>"Number of Points",     "style"=>"", "descr"=>"0~100", "ctl"=>"input"),
                  array("name"=>"Reporting Center",     "style"=>"", "descr"=>"Multiple Servers Are Separated By Minus", "ctl"=>"input"),
                  array("name"=>"Operator",             "style"=>"display:none", "descr"=>"0 + - * /", "ctl"=>"select"),
                  array("name"=>"Operation Expression", "style"=>"display:none", "descr"=>"", "ctl"=>"input"),
                  array("name"=>"Operand",              "style"=>"display:none", "descr"=>"", "ctl"=>"input"),
                  array("name"=>"Accuracy",             "style"=>"display:none", "descr"=>"0~6", "ctl"=>"select"),
                  array("name"=>"SMS&Email Reporting",  "style"=>"display:none", "descr"=>"", "ctl"=>""),
                  array("name"=>"Report Type",          "style"=>"display:none", "descr"=>"", "ctl"=>""),
                  array("name"=>"Alarm Up Limit",       "style"=>"display:none", "descr"=>"", "ctl"=>""),
                  array("name"=>"Alarm Down Limit",     "style"=>"display:none", "descr"=>"", "ctl"=>""),
                  array("name"=>"Phone Number",         "style"=>"display:none", "descr"=>"", "ctl"=>""),
                  array("name"=>"Email",                "style"=>"display:none", "descr"=>"", "ctl"=>""),
                  array("name"=>"Contents",             "style"=>"display:none", "descr"=>"", "ctl"=>""),
                  array("name"=>"Enable",               "style"=>"", "descr"=>"", "ctl"=>"check"),
                );
                page_table_title('dnp3cli', $arr);
                ?>
                <div class="cbi-section-create">
                  <input type="button" class="cbi-button-add" name="popBox" value="Add" onclick="addData('dnp3cli')">
                  <?php conf_im_ex('dnp3cli'); ?>
                </div>
              </div>
            </div>
            <?php echo $buttons ?>
          </form>
      </div><!-- card-body -->
    </div><!-- card -->
  </div><!-- col-lg-12 -->
</div>

<?php page_im_ex('Dnp3cli');?>
<div id="popLayer"></div>
<div id="popBox" style="overflow:auto">
  <input hidden="hidden" name="page_type" id="page_type" value="0">
  <h4><?php echo _("DNP3 Rules Setting"); ?></h4>
  <div class="cbi-section">
    <?php
      $table_name = 'dnp3cli';
      InputControlCustom(_('Order'), $table_name.'.order', $table_name.'.order');

      InputControlCustom(_('Device Name'), $table_name.'.device_name', $table_name.'.device_name');

      $interface_list = get_belonged_interface(ComProtoEnum::COM_PROTO_DNP3, TcpProtoEnum::TCP_PROTO_DNP3);
      SelectControlCustom(_('Belonged Interface'), $table_name.'.belonged_com', $interface_list, $interface_list[0], $table_name.'.belonged_com');

      InputControlCustom(_('Tag Name'), $table_name.'.factor_name', $table_name.'.factor_name');

      $group_id_list = ['BINARR_INPUT' => 'BINARR_INPUT', 'DOUBLE_INPUT' => 'DOUBLE_INPUT', 
                      'BINARY_OUTPUT' => 'BINARY_OUTPUT', 'COUNTER_INPUT' => 'COUNTER_INPUT', 
                      'ANALOG_INPUT' => 'ANALOG_INPUT', 'ANALOG_OUTPUTS' => 'ANALOG_OUTPUTS'];
      SelectControlCustom(_('Group ID'), $table_name.'.group_id', $group_id_list, $group_id_list['ANALOG_INPUT'], $table_name.'.group_id');

      InputControlCustom(_('Number of Points'), $table_name.'.point_number', $table_name.'.point_number', '0~100');

      InputControlCustom(_('Reporting Center'), $table_name.'.server_center', $table_name.'.server_center', _('Multiple Servers Are Separated By Minus'));

      $operator_list = [_('None'), '+', '-', '*', '/', _('Expression')];
      SelectControlCustom(_('Operator'), $table_name.'.operator', $operator_list, $operator_list[0], $table_name.'.operator', _('0 + - * /'), "selectOperator('dnp3cli')");
    
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
    <button class="cbi-button cbi-button-positive important" onclick="saveData('dnp3cli')"><?php echo _("Save"); ?></button>
  </div>
</div><!-- popBox -->
