<?php 
  ob_start();
  if (!RASPI_MONITOR_ENABLED) :
    BtnSaveApplyCustom('savembusclisettings', 'applymbusclisettings');
  endif;
  $msg = _('Restarting Mbus Client');
  page_progressbar($msg, _("Executing dct start"));
  $buttons = ob_get_clean(); 
  ob_end_clean();
?>

<style>
  #output {
    font-family: Arial, sans-serif;
  }

  #output table {
    border-collapse: collapse;
    width: 100%;
    margin-top: 10px;
  }

  #output th, 
  #output td {
    border: 1px solid #ccc;
    padding: 6px 10px;
    text-align: left;
  }

  #output th {
    background: #f4f4f4;
  }

  #output .section {
    margin-bottom: 20px;
  }

  #output .title {
    font-weight: bold;
    margin-bottom: 8px;
}
</style>

<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row">
          <div class="col">
          <?php echo _("Mbus Rules"); ?>
          </div>
        </div><!-- ./row -->
      </div><!-- ./card-header -->
      <div class="card-body">
          <?php $status->showMessages(); ?>
          <form method="POST" action="mbuscli_conf" role="form">
            <?php echo \ElastPro\Tokens\CSRF::hiddenField(); ?>
              <input type="hidden" name="table_data" value="" id="hidTD_mbuscli">
              <input type="hidden" name="option_list_mbuscli" value="" id="option_list_mbuscli">
              <div class="cbi-section cbi-tblsection" id="page_mbuscli" name="page_mbuscli">
                <?php
                $arr= array(
                  array("name"=>"Order",                "style"=>"", "descr"=>"", "ctl"=>"input"),
                  array("name"=>"Device Name",          "style"=>"", "descr"=>"", "ctl"=>"input"),
                  array("name"=>"Belonged Interface",   "style"=>"", "descr"=>"", "ctl"=>"select"),
                  array("name"=>"Tag Name",             "style"=>"", "descr"=>"", "ctl"=>"input"),
                  array("name"=>"Address",              "style"=>"", "descr"=>"", "ctl"=>"input"),
                  array("name"=>"ID",                   "style"=>"", "descr"=>"", "ctl"=>"input"),
                  array("name"=>"Data Type",            "style"=>"", "descr"=>"", "ctl"=>"select"),
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
                page_table_title('mbuscli', $arr);
                ?>
                <div class="cbi-section-create">
                  <input type="button" class="cbi-button-add" name="popBox" value="Add" onclick="addData('mbuscli')">
                  <?php conf_im_ex('mbuscli'); ?>
                </div>
              </div>
            <?php echo $buttons ?>
          </form>
      </div><!-- card-body -->
    </div><!-- card -->
  </div><!-- col-lg-12 -->
</div>

<?php page_im_ex('Mbuscli');?>
<div id="popLayer"></div>
<div id="popBox" style="overflow:auto">
  <input hidden="hidden" name="page_type" id="page_type" value="0">
  <h4><?php echo _("Mbus Rules Setting"); ?></h4>
  <div class="cbi-section">
    <?php
      $table_name = 'mbuscli';
      InputControlCustom(_('Order'), $table_name.'.order', $table_name.'.order');

      InputControlCustom(_('Device Name'), $table_name.'.device_name', $table_name.'.device_name');

      $interface_list = get_belonged_interface(ComProtoEnum::COM_PROTO_MBUS, -1);
      SelectControlCustom(_('Belonged Interface'), $table_name.'.belonged_com', $interface_list, $interface_list[0], $table_name.'.belonged_com');

      InputControlCustom(_('Tag Name'), $table_name.'.factor_name', $table_name.'.factor_name');

      InputControlCustom(_('Address'), $table_name.'.address', $table_name.'.address');

      InputControlCustom(_('ID'), $table_name.'.id', $table_name.'.id');

      $data_type_list = ["Double", "String"];
      SelectControlCustom(_('Data Type'), $table_name.'.data_type', $data_type_list, $data_type_list[0], $table_name.'.data_type');

      InputControlCustom(_('Reporting Center'), $table_name.'.server_center', $table_name.'.server_center', _('Multiple Servers Are Separated By Minus'));

      $operator_list = [_('None'), '+', '-', '*', '/', _('Expression')];
      SelectControlCustom(_('Operator'), $table_name.'.operator', $operator_list, $operator_list[0], $table_name.'.operator', _('0 + - * /'), "selectOperator('mbuscli')");
    
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
    <button class="cbi-button cbi-button-positive important" onclick="saveData('mbuscli')"><?php echo _("Save"); ?></button>
  </div>
</div><!-- popBox -->
</br>
<div name="mbus_scan" id="mbus_scan">
  <div class="cbi-value">
    <h4><?php echo _("Tip: Use an Mbus address scan to identify the data that needs to be collected.");?></h4>
  </div>
  <div class="cbi-value">
    <a><?php echo _("Interface:");?></a>
    <select id="scan_interface" class="cbi-input-select" name="scan_interface" style="width: 100%; max-width: 10rem; min-width: 5rem;">
    <?php
      foreach ($interface_list as $key => $value) {
        echo "<option value='$key'>$value</option>";
      }
    ?>
    </select>
    &nbsp;&nbsp;&nbsp;
    <a><?php echo _("Address:");?></a>
    <input type="text" class="cbi-input-text" id="scan_address" name="scan_address" value="" style="width: 100%; max-width: 10rem; min-width: 5rem;" placeholder="<?php echo _("Enter address");?>">
    <button class="cbi-button cbi-button-positive important" id="btn_scan" onclick="mbusScan()"><?php echo _("Scan"); ?></button>
  </div>
  <div class="cbi-value" id="output"></div>
</div>