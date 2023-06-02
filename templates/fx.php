<?php ob_start() ?>
  <?php if (!RASPI_MONITOR_ENABLED) : ?>
      <div class="cbi-page-actions">
        <input type="submit" class="btn btn-outline btn-primary" value="<?php echo _("Save settings"); ?>" name="savefxsettings" />
        <input type="submit" class="btn btn-success" value="<?php echo _("Apply settings"); $msg=_("Restarting dct"); ?>" data-toggle="modal" data-target="#hostapdModal" name="applyfxsettings" />
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
          <?php echo _("FX Setting"); ?>
          </div>
        </div><!-- ./row -->
      </div><!-- ./card-header -->
      <div class="card-body">
          <?php $status->showMessages(); ?>
          <form method="POST" action="fx_conf" role="form">
          <input type="hidden" name="table_data" value="" id="hidTD">
          <?php echo CSRFTokenFieldTag() ?>       
            <div class="cbi-section cbi-tblsection" id="page_fx" name="page_fx">
              <table class="table cbi-section-table" name="table_fx" id="table_fx">
                <tr class="tr cbi-section-table-titles">
                  <th class="th cbi-section-table-cell"><?php echo _("Order"); ?></th>
                  <th class="th cbi-section-table-cell"><?php echo _("Device Name"); ?></th>
                  <th class="th cbi-section-table-cell"><?php echo _("Belonged Interface"); ?></th>
                  <th class="th cbi-section-table-cell" width="10%"><?php echo _("Factor Name"); ?></th>
                  <th class="th cbi-section-table-cell"><?php echo _("Register Type"); ?></th>
                  <th class="th cbi-section-table-cell"><?php echo _("Register Address"); ?></th>
                  <th class="th cbi-section-table-cell"><?php echo _("Count"); ?></th>
                  <th class="th cbi-section-table-cell"><?php echo _("Data Type"); ?></th>
                  <th class="th cbi-section-table-cell"><?php echo _("Reporting Center"); ?></th>
                  <th class="th cbi-section-table-cell" style="display:none"><?php echo _("Operator"); ?></th>
                  <th class="th cbi-section-table-cell" style="display:none"><?php echo _("Operation Expression"); ?></th>
                  <th class="th cbi-section-table-cell" style="display:none"><?php echo _("Operand"); ?></th>
                  <th class="th cbi-section-table-cell" style="display:none"><?php echo _("Accuracy"); ?></th>
                  <th class="th cbi-section-table-cell"><?php echo _("Enable"); ?></th>
                  <th class="th cbi-section-table-cell cbi-section-actions"></th>
                  <th class="th cbi-section-table-cell cbi-section-actions"></th>
                </tr>
                <tr class="tr cbi-section-table-descr">
                  <th class="th cbi-section-table-cell" ></th>
                  <th class="th cbi-section-table-cell" ></th>
                  <th class="th cbi-section-table-cell" ></th>
                  <th class="th cbi-section-table-cell" ><?php echo _("Multiple Factors Are Separated By Semicolon"); ?></th>
                  <th class="th cbi-section-table-cell" ></th>
                  <th class="th cbi-section-table-cell" ></th>
                  <th class="th cbi-section-table-cell" >1~120</th>
                  <th class="th cbi-section-table-cell" ></th>
                  <th class="th cbi-section-table-cell" ><?php echo _("Multiple Servers Are Separated By Minus"); ?></th>
                  <th class="th cbi-section-table-cell" style="display:none"></th>
                  <th class="th cbi-section-table-cell" style="display:none"></th>
                  <th class="th cbi-section-table-cell" style="display:none"></th>
                  <th class="th cbi-section-table-cell" style="display:none"></th>
                  <th class="th cbi-section-table-cell" ></th>
                  <th class="th cbi-section-table-cell cbi-section-actions"></th>
                  <th class="th cbi-section-table-cell cbi-section-actions"></th>
                </tr>
              </table>
              <div class="cbi-section-create">
                <input type="button" class="cbi-button-add" name="popBox" value="Add" onclick="addData()">
              </div>
            </div>
            <?php echo $buttons ?>
          </form>
      </div><!-- card-body -->
    </div><!-- card -->
  </div><!-- col-lg-12 -->
</div>

<div id="popLayer"></div>
<div id="popBox" style="overflow:auto">
  <input hidden="hidden" name="page_type" id="page_type" value="0">
  <h4><?php echo _("FX Rules Setting"); ?></h4>
  <div class="cbi-section">
    <div class="cbi-value">
      <label class="cbi-value-title" for="widget.order"><?php echo _("Order"); ?></label>
      <input id="widget.order" type="text" class="cbi-input-text">
    </div>

    <div class="cbi-value">
      <label class="cbi-value-title" for="widget.device_name"><?php echo _("Device Name"); ?></label>
      <input id="widget.device_name" type="text" class="cbi-input-text">
    </div>

    <div class="cbi-value">
      <label class="cbi-value-title" for="widget.belonged_com"><?php echo _("Belonged Interface"); ?></label>
      <select id="widget.belonged_com" class="cbi-input-select">
      <?php
        exec("sudo uci get dct.com.enabled1", $com1_enable);
        exec("sudo uci get dct.com.enabled2", $com2_enable);
        exec("sudo uci get dct.com.enabled3", $com3_enable);
        exec("sudo uci get dct.com.enabled4", $com4_enable);
        
        exec("sudo uci get dct.com.proto1", $com1_proto);
        exec("sudo uci get dct.com.proto2", $com2_proto);
        exec("sudo uci get dct.com.proto3", $com3_proto);
        exec("sudo uci get dct.com.proto4", $com4_proto);

        exec("sudo uci get dct.tcp_server.enabled1", $tcp1_enable);
        exec("sudo uci get dct.tcp_server.enabled2", $tcp2_enable);
        exec("sudo uci get dct.tcp_server.enabled3", $tcp3_enable);
        exec("sudo uci get dct.tcp_server.enabled4", $tcp4_enable);
        exec("sudo uci get dct.tcp_server.enabled5", $tcp5_enable);
        
        exec("sudo uci get dct.tcp_server.proto1", $tcp1_proto);
        exec("sudo uci get dct.tcp_server.proto2", $tcp2_proto);
        exec("sudo uci get dct.tcp_server.proto3", $tcp3_proto);
        exec("sudo uci get dct.tcp_server.proto4", $tcp4_proto);
        exec("sudo uci get dct.tcp_server.proto5", $tcp5_proto);
      ?>
      <?php if ($com1_enable[0] == "1" && $com1_proto[0] == "2") { ?>
        <option value="COM1">COM1</option>
      <?php } ?>
      <?php if ($com2_enable[0] == "1" && $com2_proto[0] == "2") { ?>
        <option value="COM2">COM2</option>
      <?php } ?>
      <?php if ($com3_enable[0] == "1" && $com3_proto[0] == "2") { ?>
        <option value="COM3">COM3</option>
      <?php } ?>
      <?php if ($com4_enable[0] == "1" && $com4_proto[0] == "2") { ?>
        <option value="COM4">COM4</option>
      <?php } ?>  
      <?php if ($tcp1_enable[0] == "1" && $tcp1_proto[0] == "3") { ?>
        <option value="TCP1">TCP1</option>
      <?php } ?>
      <?php if ($tcp2_enable[0] == "1" && $tcp2_proto[0] == "3") { ?>
        <option value="TCP2">TCP2</option>
      <?php } ?>
      <?php if ($tcp3_enable[0] == "1" && $tcp3_proto[0] == "3") { ?>
        <option value="TCP3">TCP3</option>
      <?php } ?>
      <?php if ($tcp4_enable[0] == "1" && $tcp4_proto[0] == "3") { ?>
        <option value="TCP4">TCP4</option>
      <?php } ?>
      <?php if ($tcp5_enable[0] == "1" && $tcp5_proto[0] == "3") { ?>
        <option value="TCP5">TCP5</option>
      <?php } ?>

      <?php if (($com1_enable[0] == null || $com1_enable[0]  == "0"  || $com1_proto[0] != "2") &&
        ($com2_enable[0] == null || $com2_enable[0]  == "0" || $com2_proto[0] != "2") &&
        ($com3_enable[0] == null || $com3_enable[0]  == "0" || $com3_proto[0] != "2") &&
        ($com4_enable[0] == null || $com4_enable[0]  == "0" || $com4_proto[0] != "2") &&
        ($tcp1_enable[0] == null || $tcp1_enable[0]  == "0" || $tcp1_proto[0] != "3") &&
        ($tcp2_enable[0] == null || $tcp2_enable[0]  == "0" || $tcp2_proto[0] != "3") &&
        ($tcp3_enable[0] == null || $tcp3_enable[0]  == "0" || $tcp3_proto[0] != "3") &&
        ($tcp4_enable[0] == null || $tcp4_enable[0]  == "0" || $tcp4_proto[0] != "3") &&
        ($tcp5_enable[0] == null || $tcp5_enable[0]  == "0" || $tcp5_proto[0] != "3")) { 
      ?>
        <option value="<?php echo _("No Interface Is Enabled") ?>"><?php echo _("No Interface Is Enabled") ?></option>
      <?php } ?>
      </select>
    </div>
    
    <div class="cbi-value">
      <label class="cbi-value-title" for="widget.factor_name"><?php echo _("Factor Name"); ?></label>
      <input id="widget.factor_name" type="text" class="cbi-input-text">
      <label class="cbi-value-description"><?php echo _("Multiple Factors Are Separated By Semicolon"); ?></label>
    </div>

    <div class="cbi-value">
      <label class="cbi-value-title" for="widget.reg_type"><?php echo _("Register Type"); ?></label>
      <select id="widget.reg_type" class="cbi-input-select">
        <option value="0">X</option>
        <option value="1">Y</option>
        <option value="2">M</option>
        <option value="3">S</option>
        <option value="4">D</option>
      </select>
    </div>

    <div class="cbi-value">
      <label class="cbi-value-title" for="widget.reg_addr"><?php echo _("Register Address"); ?></label>
      <input id="widget.reg_addr" type="text" class="cbi-input-text">
    </div>

    <div class="cbi-value">
      <label class="cbi-value-title" for="widget.reg_count"><?php echo _("Count"); ?></label>
      <input id="widget.reg_count" type="text" class="cbi-input-text">
      <label class="cbi-value-description">1~120</label>
    </div>

    <div class="cbi-value">
      <label class="cbi-value-title" for="widget.data_type"><?php echo _("Data Type"); ?></label>
      <select id="widget.data_type" class="cbi-input-select">
        <option value="0">Bit</option>
        <option value="1">Byte</option>
        <option value="2">Word</option>
        <option value="3">DWord</option>
        <option value="4">Real</option>
      </select>
    </div>

    <div class="cbi-value">
      <label class="cbi-value-title" for="widget.server_center"><?php echo _("Reporting Center"); ?></label>
      <input id="widget.server_center" type="text" class="cbi-input-text">
      <label class="cbi-value-description"><?php echo _("Multiple Servers Are Separated By Minus"); ?></label>
    </div>

    <!-- <div class="cbi-value">
      <label class="cbi-value-title" for="widget.unit"><?php //echo _("Unit"); ?></label>
      <input id="widget.unit" type="text" class="cbi-input-text">
      <label class="cbi-value-description"><?php //echo _("Multiple Units Are Separated By Semicolon"); ?></label>
    </div> -->

    <div class="cbi-value">
      <label class="cbi-value-title" for="widget.operator"><?php echo _("Operator"); ?></label>
      <select id="widget.operator" class="cbi-input-select"  onchange="selectOperator(this)">
        <option value="0">None</option>
        <option value="1">+</option>
        <option value="2">-</option>
        <option value="3">*</option>
        <option value="4">/</option>
        <option value="5"><?php echo _("Expression"); ?></option>
      </select>
      <label class="cbi-value-description">0 + - * /</label>
    </div>

    <div class="cbi-value" name="page_operand" id="page_operand">
      <label class="cbi-value-title" for="widget.operand"><?php echo _("Operand"); ?></label>
      <input id="widget.operand" type="text" class="cbi-input-text">
    </div>
    
    <div class="cbi-value"  name="page_ex" id="page_ex">
      <label class="cbi-value-title" for="widget.ex"><?php echo _("Operation Expression"); ?></label>
      <input id="widget.ex" type="text" class="cbi-input-text">
      <label class="cbi-value-description"><?php echo _("(x + 10) * 10,  x is collected data"); ?></label>
    </div>

    <div class="cbi-value">
      <label class="cbi-value-title" for="widget.accuracy"><?php echo _("Accuracy"); ?></label>
      <select id="widget.accuracy" class="cbi-input-select">
        <option value="0">0</option>
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
        <option value="6">6</option>
      </select>
      <label class="cbi-value-description">0~6</label>
    </div>

    <div class="cbi-value">
      <label class="cbi-value-title" for="widget.enabled"><?php echo _("Enable"); ?></label>
      <input type="checkbox" id="widget.enabled" value="1" checked="" data-widget-id="widget.enabled">
    </div>
  </div>

  <div class="right">
    <button class="cbi-button" onclick="closeBox()"><?php echo _("Dismiss"); ?></button>
    <button class="cbi-button cbi-button-positive important" onclick="saveFxData()"><?php echo _("Save"); ?></button>
  </div>
</div><!-- popBox -->