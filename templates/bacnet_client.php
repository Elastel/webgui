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

<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row">
          <div class="col">
          <?php echo _("BACnet Client(BBMD)"); ?>
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
                InputControlCustom(_('Port'), 'port', 'port', _('1~65535'));

                InputControlCustom(_('Device ID'), 'device_id', 'device_id', _('1~65535'));

                InputControlCustom(_('Name'), 'name', 'name');
              ?>

                <input type="hidden" name="table_data" value="" id="hidTD_baccli">
                <input type="hidden" name="option_list_baccli" value="" id="option_list_baccli">
                <div class="cbi-section cbi-tblsection" id="page_baccli" name="page_baccli">
                  <?php
                  $arr= array(
                    array("name"=>"Order",                "style"=>"", "descr"=>"", "ctl"=>"input"),
                    array("name"=>"Device Name",          "style"=>"", "descr"=>"", "ctl"=>"input"),
                    array("name"=>"Factor Name",          "style"=>"", "descr"=>"Multiple Factors Are Separated By Semicolon", "ctl"=>"input"),
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

      InputControlCustom(_('Object Identifier'), $table_name.'.object_id', $table_name.'.object_id');

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
