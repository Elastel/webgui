<div class="tab-pane active" id="tcp1">
  <div class="row">
    <div class="cbi-value">
      <label class="cbi-value-title"><?php echo _("Enabled"); ?></label>
      <input class="cbi-input-radio" id="tcp_enable1" name="tcp_enabled1" value="1" type="radio" checked onchange="enableTcp1(true)">
      <label ><?php echo _("Enable"); ?></label>

      <input class="cbi-input-radio" id="tcp_disable1" name="tcp_enabled1" value="0" type="radio" onchange="enableTcp1(false)">
      <label ><?php echo _("Disable"); ?></label>
    </div>

    <div id="page_tcp1" name="page_tcp1">
      <div class="cbi-value">
        <label class="cbi-value-title"><?php echo _("Server Address"); ?></label>
        <input type="text" class="cbi-input-text" name="server_addr1" id="server_addr1" />
      </div>

      <div class="cbi-value">
        <label class="cbi-value-title"><?php echo _("Server Port"); ?></label>
        <input type="text" class="cbi-input-text" name="server_port1" id="server_port1" />
      </div>

      <div class="cbi-value">
        <label class="cbi-value-title"><?php echo _("Frame Interval"); ?></label>
        <input type="text" class="cbi-input-text" name="tcp_frame_interval1" id="tcp_frame_interval1" value="200" />
        <label class="cbi-value-description"><?php echo _("ms"); ?></label>
      </div>

      <div class="cbi-value">
        <label class="cbi-value-title"><?php echo _("Protocol"); ?></label>
        <select id="tcp_proto1" name="tcp_proto1" class="cbi-input-select" onchange="tcpProtocolChange1(this)">
          <?php $i = 0; ?>
          <?php foreach($tcp_proto as $proto): ?>
            <?php if ($i == 0) { ?>
              <option value="<?php echo $i ?>" selected=""><?php echo $proto ?></option>
            <?php } else {?> 
              <option value="<?php echo $i ?>"><?php echo $proto ?></option>
            <?php } ?> 
            <?php $i++; ?>
          <?php endforeach ?>
        </select>
      </div>

      <div class="cbi-value" id="tcp_page_protocol_modbus1" name="tcp_page_protocol_modbus1">
        <label class="cbi-value-title"><?php echo _("Command Interval"); ?></label>
        <input type="text" class="cbi-input-text" name="tcp_cmd_interval1" id="tcp_cmd_interval1" value="2" />
        <label class="cbi-value-description"><?php echo _("ms"); ?></label>
      </div>

      <div class="cbi-value" id="tcp_page_protocol_transparent1" name="tcp_page_protocol_transparent1">
        <label class="cbi-value-title"><?php echo _("Reporting Center"); ?></label>
        <input type="text" class="cbi-input-text" name="tcp_report_center1" id="tcp_report_center1" />
        <label class="cbi-value-description"><?php echo _("1-2-3-4-5"); ?></label>
      </div>

      <div id="tcp_page_protocol_s71" name="tcp_page_protocol_s71">
        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Rack"); ?></label>
          <input type="text" class="cbi-input-text" name="rack1" id="rack1" />
        </div>
        <div class="cbi-value">
        <label class="cbi-value-title"><?php echo _("Slot"); ?></label>
        <input type="text" class="cbi-input-text" name="slot1" id="slot1" />
        </div>
      </div>

      <div class="cbi-value">
        <label class="cbi-value-title"><?php echo _("Connection Status"); ?></label>
        <?php 
            exec("uci -P /var/state get dct.connection.tcp_status0", $status);
        ?>
        <label id="connect_status1" name="connect_status1"><?php echo _(($status[0] != null) ? $status[0] : "-"); ?></label>
      </div>
    </div><!-- /.page_tcp -->
  </div><!-- /.row -->
</div><!-- /.tab-pane | basic tab -->
<script type="text/javascript">
  function enableTcp1(state) {
    if (state) {
      $('#page_tcp1').show();

      tcpProtocolChange1(state);
    } else {
      $('#page_tcp1').hide();
    }
  }

  function tcpProtocolChange1(that) {
    var protocol = document.getElementById("tcp_proto1").value;

    if (protocol == "0" || protocol == "3") {
      $('#tcp_page_protocol_modbus1').show();
      $('#tcp_page_protocol_transparent1').hide(); 
      $('#tcp_page_protocol_s71').hide(); 
    } else if (protocol == "1") {
      $('#tcp_page_protocol_modbus1').hide();
      $('#tcp_page_protocol_transparent1').show(); 
      $('#tcp_page_protocol_s71').hide(); 
    } else {
      $('#tcp_page_protocol_modbus1').hide();
      $('#tcp_page_protocol_transparent1').hide(); 
      $('#tcp_page_protocol_s71').show(); 
    }
  }

</script>
