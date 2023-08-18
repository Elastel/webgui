<?php ob_start() ?>
  <?php if (!RASPI_MONITOR_ENABLED) : ?>
      <div class="cbi-page-actions">
        <input type="submit" class="btn btn-outline btn-primary" value="<?php echo _("Save settings"); ?>" name="savebacclisettings" />
        <input type="submit" class="btn btn-success" value="<?php echo _("Apply settings"); $msg=_("Restarting BACnet Client"); ?>" data-toggle="modal" data-target="#hostapdModal" name="applybacclisettings" />
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
          <?php echo _("BACnet Client(BBMD)"); ?>
          </div>
        </div><!-- ./row -->
      </div><!-- ./card-header -->
      <div class="card-body">
          <?php $status->showMessages(); ?>
          <form method="POST" action="bacnet_client" role="form">
          <?php echo CSRFTokenFieldTag() ?>
            <div class="cbi-section cbi-tblsection">
              <div class="cbi-value">
                <label class="cbi-value-title"><?php echo _("BACnet Client"); ?></label>
                <input class="cbi-input-radio" id="bacnet_enable" name="enabled" value="1" type="radio" checked onchange="enableBACnet(true)">
                <label ><?php echo _("Enable"); ?></label>

                <input class="cbi-input-radio" id="bacnet_disable" name="enabled" value="0" type="radio" onchange="enableBACnet(false)">
                <label ><?php echo _("Disable"); ?></label>
              </div>
          
              <div id="page_bacnet" name="page_bacnet">
                <!-- <div class="cbi-value">
                  <label class="cbi-value-title"><?php echo _("Ip Address"); ?></label>
                  <input type="text" class="cbi-input-text" name="ip_address" id="ip_address"/>
                </div> -->
                <div class="cbi-value">
                  <label class="cbi-value-title"><?php echo _("Port"); ?></label>
                  <input type="text" class="cbi-input-text" name="port" id="port"/>
                  <label class="cbi-value-description"><?php echo _("1~65535"); ?></label>
                </div> 

                <div class="cbi-value">
                  <label class="cbi-value-title"><?php echo _("Device ID"); ?></label>
                  <input type="text" class="cbi-input-text" name="device_id" id="device_id"/>
                  <label class="cbi-value-description"><?php echo _("1~65535"); ?></label>
                </div>

                <div class="cbi-value">
                  <label class="cbi-value-title"><?php echo _("Name"); ?></label>
                  <input type="text" class="cbi-input-text" name="name" id="name"/>
                </div>

                <input type="hidden" name="table_data" value="" id="hidTD">
                <div class="cbi-section cbi-tblsection" id="page_baccli" name="page_baccli">
                  <table class="table cbi-section-table" name="table_baccli" id="table_baccli">
                    <tr class="tr cbi-section-table-titles">
                      <th class="th cbi-section-table-cell"><?php echo _("Order"); ?></th>
                      <th class="th cbi-section-table-cell"><?php echo _("Device Name"); ?></th>
                      <th class="th cbi-section-table-cell"><?php echo _("Factor Name"); ?></th>
                      <!-- <th class="th cbi-section-table-cell"><?php echo _("Object Name"); ?></th> -->
                      <th class="th cbi-section-table-cell"><?php echo _("Object Identifier"); ?></th>
                      <!-- <th class="th cbi-section-table-cell"><?php echo _("Object Type"); ?></th> -->
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
                      <!-- <th class="th cbi-section-table-cell" ></th> -->
                      <th class="th cbi-section-table-cell" ></th>
                      <!-- <th class="th cbi-section-table-cell" ></th> -->
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
  <h4><?php echo _("Bacnet Client Object Setting"); ?></h4>
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
      <label class="cbi-value-title" for="widget.factor_name"><?php echo _("Factor Name"); ?></label>
      <input id="widget.factor_name" type="text" class="cbi-input-text">
      <label class="cbi-value-description"><?php echo _("Multiple Factors Are Separated By Semicolon"); ?></label>
    </div>

    <!-- <div class="cbi-value">
      <label class="cbi-value-title" for="widget.object_name"><?php echo _("Object Name"); ?></label>
      <input id="widget.object_name" type="text" class="cbi-input-text">
    </div> -->

    <div class="cbi-value">
      <label class="cbi-value-title" for="widget.object_id"><?php echo _("Object Identifier"); ?></label>
      <input id="widget.object_id" type="text" class="cbi-input-text">
    </div>

    <!-- <div class="cbi-value">
      <label class="cbi-value-title" for="widget.object_type"><?php echo _("Object Type"); ?></label>
      <select id="widget.object_type" class="cbi-input-select">
        <option value="0">Analog Input</option>
        <option value="1">Analog Output</option>
        <option value="2">Analog Value</option>
        <option value="3">Binary Input</option>
        <option value="4">Binary Output</option>
        <option value="5">Binary Value</option>
      </select>
    </div> -->

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
    <button class="cbi-button cbi-button-positive important" onclick="saveDataBaccli()"><?php echo _("Save"); ?></button>
  </div>
</div><!-- popBox -->
<script type="text/javascript">
  function enableBACnet(state) {
      if (state) {
        $('#page_bacnet').show();
      } else {
        $('#page_bacnet').hide();
      }
  }

</script>

