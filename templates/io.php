<?php ob_start() ?>
  <?php if (!RASPI_MONITOR_ENABLED) : ?>
      <div class="cbi-page-actions">
        <input type="submit" class="btn btn-outline btn-primary" value="<?php echo _("Save settings"); ?>" name="saveIOsettings" />
        <input type="submit" class="btn btn-success" value="<?php echo _("Apply settings"); $msg=_("Restarting dct"); ?>" data-toggle="modal" data-target="#hostapdModal" name="applyIOsettings" />
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
          <?php echo _("IO Setting"); ?>
          </div>
        </div><!-- ./row -->
      </div><!-- ./card-header -->
      <div class="card-body">
          <?php $status->showMessages(); ?>
          <form method="POST" action="io_conf" role="form">
            <?php echo CSRFTokenFieldTag() ?>       
              <div class="cbi-section cbi-tblsection" id="pageADC" name="pageADC">
                <input type="hidden" name="tableDataADC" value="" id="hidTDADC">
                <h4><?php echo _("ADC Setting"); ?></h4>
                <table class="table cbi-section-table" name="tableADC" id="tableADC">
                  <tr class="tr cbi-section-table-titles">
                    <th class="th cbi-section-table-cell"><?php echo _("Device Name"); ?></th>
                    <th class="th cbi-section-table-cell"><?php echo _("ADC Channel"); ?></th>
                    <th class="th cbi-section-table-cell"><?php echo _("Factor Name"); ?></th>
                    <th class="th cbi-section-table-cell"><?php echo _("Capture Type"); ?></th>
                    <th class="th cbi-section-table-cell"><?php echo _("Range Down"); ?></th>
                    <th class="th cbi-section-table-cell"><?php echo _("Range Up"); ?></th>
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
                    <th class="th cbi-section-table-cell" ></th>
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
                  <input type="button" class="cbi-button-add" name="btnADC" value="ADD" onclick="addDataIO(this)">
                </div>
              </div>

              <div class="cbi-section cbi-tblsection" id="pageDI" name="pageDI">
                <input type="hidden" name="tableDataDI" value="" id="hidTDDI">
                <h4><?php echo _("DI Setting"); ?></h4>
                <table class="table cbi-section-table" name="tableDI" id="tableDI">
                  <tr class="tr cbi-section-table-titles">
                    <th class="th cbi-section-table-cell"><?php echo _("Device Name"); ?></th>
                    <th class="th cbi-section-table-cell"><?php echo _("DI Channel"); ?></th>
                    <th class="th cbi-section-table-cell"><?php echo _("Factor Name"); ?></th>
                    <th class="th cbi-section-table-cell"><?php echo _("Mode"); ?></th>
                    <th class="th cbi-section-table-cell"><?php echo _("Count Method"); ?></th>
                    <th class="th cbi-section-table-cell"><?php echo _("Debounce Interval"); ?></th>
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
                    <th class="th cbi-section-table-cell" ></th>
                    <th class="th cbi-section-table-cell" ></th>
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
                  <input type="button" class="cbi-button-add" name="btnDI" value="ADD" onclick="addDataIO(this)">
                </div>
              </div>

              <div class="cbi-section cbi-tblsection" id="pageDO" name="pageDO">
                <input type="hidden" name="tableDataDO" value="" id="hidTDDO">
                <h4><?php echo _("DO Setting"); ?></h4>
                <table class="table cbi-section-table" name="tableDO" id="tableDO">
                  <tr class="tr cbi-section-table-titles">
                    <th class="th cbi-section-table-cell"><?php echo _("Device Name"); ?></th>
                    <th class="th cbi-section-table-cell"><?php echo _("DO Channel"); ?></th>
                    <th class="th cbi-section-table-cell"><?php echo _("Factor Name"); ?></th>
                    <th class="th cbi-section-table-cell"><?php echo _("Init Status"); ?></th>
                    <th class="th cbi-section-table-cell"><?php echo _("Current Status"); ?></th>
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
                    <th class="th cbi-section-table-cell" ></th>
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
                  <input type="button" class="cbi-button-add" name="btnDO" value="ADD" onclick="addDataIO(this)">
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
  <input hidden="hidden" name="page_name" id="page_name" value="0">
  <h4 name="popBoxTitle" id="popBoxTitle"><?php echo _("ADC Setting"); ?></h4>
  <div class="cbi-section">
    <div class="cbi-value">
      <label class="cbi-value-title" for="widget.device_name"><?php echo _("Device Name"); ?></label>
      <input id="widget.device_name" type="text" class="cbi-input-text">
    </div>

    <div class="cbi-value" name="pageIndexADC" id="pageIndexADC">
      <label class="cbi-value-title" for="widget.index.adc"><?php echo _("ADC Channel"); ?></label>
      <select id="widget.index.adc" class="cbi-input-select">
        <option value="ADC0">ADC0</option>
        <option value="ADC1">ADC1</option>
        <option value="ADC1">ADC2</option>
      </select>
    </div>

    <div class="cbi-value" name="pageIndexDI" id="pageIndexDI">
      <label class="cbi-value-title" for="widget.index.di"><?php echo _("DI Channel"); ?></label>
      <select id="widget.index.di" class="cbi-input-select">
        <option value="DI0">DI0</option>
        <option value="DI1">DI1</option>
        <option value="DI2">DI2</option>
        <option value="DI3">DI3</option>
        <option value="DI4">DI4</option>
        <option value="DI5">DI5</option>
      </select>
    </div>

    <div class="cbi-value" name="pageIndexDO" id="pageIndexDO">
      <label class="cbi-value-title" for="widget.index.do"><?php echo _("DO Channel"); ?></label>
      <select id="widget.index.do" class="cbi-input-select">
        <option value="DO0">DO0</option>
        <option value="DO1">DO1</option>
        <option value="DO2">DO2</option>
        <option value="DO3">DO3</option>
        <option value="DO4">DO4</option>
        <option value="DO5">DO5</option>
      </select>
    </div>

    <div class="cbi-value">
      <label class="cbi-value-title" for="widget.factor_name"><?php echo _("Factor Name"); ?></label>
      <input id="widget.factor_name" type="text" class="cbi-input-text">
      <label class="cbi-value-description"><?php echo _("Multiple Factors Are Separated By Semicolon"); ?></label>
    </div>

    <div name="pageADCMod" id="pageADCMod">
      <div class="cbi-value">
        <label class="cbi-value-title" for="widget.cap_type"><?php echo _("Capture Type"); ?></label>
        <select id="widget.cap_type" class="cbi-input-select">
          <option value="0">4-20mA</option>
          <option value="1">0-10V</option>
        </select>
      </div>

      <div class="cbi-value">
        <label class="cbi-value-title" for="widget.range_down"><?php echo _("Range Down"); ?></label>
        <input id="widget.range_down" type="text" class="cbi-input-text">
      </div>

      <div class="cbi-value">
        <label class="cbi-value-title" for="widget.range_up"><?php echo _("Range Up"); ?></label>
        <input id="widget.range_up" type="text" class="cbi-input-text">
      </div>
    </div>

    <div name="pageDIMod" id="pageDIMod">
      <div class="cbi-value">
        <label class="cbi-value-title" for="widget.mode"><?php echo _("Mode"); ?></label>
        <select id="widget.mode" class="cbi-input-select"  onchange="selectMode()">
          <option value="0"><?php echo _("Counting Mode"); ?></option>
          <option value="1"><?php echo _("Status Mode"); ?></option>
        </select>
      </div>

      <div  id="pageCount" name="pageCount">
        <div class="cbi-value">
          <label class="cbi-value-title" for="widget.count_method"><?php echo _("Count Method"); ?></label>
          <select id="widget.count_method" class="cbi-input-select">
            <option value="0"><?php echo _("Rising Edge"); ?></option>
            <option value="1"><?php echo _("Falling Edge"); ?></option>
          </select>
        </div>

        <div class="cbi-value">
          <label class="cbi-value-title" for="widget.debounce_interval"><?php echo _("Debounce Interval"); ?></label>
          <input id="widget.debounce_interval" type="text" class="cbi-input-text">
          <label class="cbi-value-description"><?php echo _("ms"); ?></label>
        </div>
      </div>
    </div>

    <div name="pageDOMod" id="pageDOMod">
      <div class="cbi-value">
        <label class="cbi-value-title" for="widget.init_status"><?php echo _("Init Status"); ?></label>
        <select id="widget.init_status" class="cbi-input-select">
          <option value="0"><?php echo _("Open"); ?></option>
          <option value="1"><?php echo _("Close"); ?></option>
        </select>
      </div>

      <div class="cbi-value">
        <label class="cbi-value-title"><?php echo _("Current Status"); ?></label>
        <label id="widget.cur_status" name="widget.cur_status" value="-">-</label>
      </div>
    </div>

    <div class="cbi-value">
      <label class="cbi-value-title" for="widget.server_center"><?php echo _("Reporting Center"); ?></label>
      <input id="widget.server_center" type="text" class="cbi-input-text">
      <label class="cbi-value-description"><?php echo _("Multiple Servers Are Separated By Minus"); ?></label>
    </div>

    <div class="cbi-value">
      <label class="cbi-value-title" for="widget.operator"><?php echo _("Operator"); ?></label>
      <select id="widget.operator" class="cbi-input-select"  onchange="selectOperator()">
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
    <button class="cbi-button cbi-button-positive important" onclick="saveDataIO()"><?php echo _("Save"); ?></button>
  </div>
</div><!-- popBox -->
<script type="text/javascript">
  
</script>
