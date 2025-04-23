<?php 
  ob_start();
  if (!RASPI_MONITOR_ENABLED) :
    BtnSaveApplyCustom('savebacclisettings', 'applybacclisettings');
  endif;
  $msg = _('Restarting BACnet Rules');
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
          <?php echo _("BACnet Rules"); ?>
          </div>
        </div><!-- ./row -->
      </div><!-- ./card-header -->
      <div class="card-body">
          <?php $status->showMessages(); ?>
          <form method="POST" action="baccli_conf" role="form">
          <?php echo \ElastPro\Tokens\CSRF::hiddenField(); ?>
            <input type="hidden" name="table_data" value="" id="hidTD_baccli">
            <input type="hidden" name="option_list_baccli" value="" id="option_list_baccli">
            <div class="cbi-section cbi-tblsection" id="page_baccli" name="page_baccli">
              <?php
              $arr= array(
                array("name"=>"Order",                "style"=>"", "descr"=>"", "ctl"=>"input"),
                array("name"=>"Device Name",          "style"=>"", "descr"=>"", "ctl"=>"input"),
                array("name"=>"Belonged Interface",   "style"=>"", "descr"=>"", "ctl"=>"select"),
                array("name"=>"Tag Name",          "style"=>"", "descr"=>"", "ctl"=>"input"),
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
  <h4><?php echo _("BACnet Rules Object Setting"); ?></h4>
  <div class="cbi-section">
    <?php
      $table_name = 'baccli';
      InputControlCustom(_('Order'), $table_name.'.order', $table_name.'.order');

      InputControlCustom(_('Device Name'), $table_name.'.device_name', $table_name.'.device_name');

      $interface_list = get_belonged_interface(ComProtoEnum::COM_PROTO_BACNET, TcpProtoEnum::TCP_PROTO_BACNET);
      SelectControlCustom(_('Belonged Interface'), $table_name.'.belonged_com', $interface_list, $interface_list[0], $table_name.'.belonged_com');

      InputControlCustom(_('Tag Name'), $table_name.'.factor_name', $table_name.'.factor_name');
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
    <button class="cbi-button cbi-button-positive important" onclick="saveData('baccli')"><?php echo _("Save"); ?></button>
  </div>
</div><!-- popBox -->
<script>
</script>

