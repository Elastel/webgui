<div class="tab-pane fade" id="tcp2">
  <div class="row">
    <div class="cbi-value">
      <label class="cbi-value-title"><?php echo _("Enabled"); ?></label>
      <input class="cbi-input-radio" id="tcp_enable2" name="tcp_enabled2" value="1" type="radio" checked onchange="enableTcp2(true)">
      <label ><?php echo _("Enable"); ?></label>

      <input class="cbi-input-radio" id="tcp_disable2" name="tcp_enabled2" value="0" type="radio" onchange="enableTcp2(false)">
      <label ><?php echo _("Disable"); ?></label>
    </div>

    <div id="page_tcp2" name="page_tcp2">
      <div class="cbi-value">
        <label class="cbi-value-title"><?php echo _("Server Address"); ?></label>
        <input type="text" class="cbi-input-text" name="server_addr2" id="server_addr2" />
      </div>

      <div class="cbi-value">
        <label class="cbi-value-title"><?php echo _("Server Port"); ?></label>
        <input type="text" class="cbi-input-text" name="server_port2" id="server_port2" />
      </div>

      <div class="cbi-value">
        <label class="cbi-value-title"><?php echo _("Frame Interval"); ?></label>
        <input type="text" class="cbi-input-text" name="tcp_frame_interval2" id="tcp_frame_interval2" value="200" />
        <label class="cbi-value-description"><?php echo _("ms"); ?></label>
      </div>

      <div class="cbi-value">
        <label class="cbi-value-title"><?php echo _("Protocol"); ?></label>
        <select id="tcp_proto2" name="tcp_proto2" class="cbi-input-select" onchange="tcpProtocolChange2(this)">
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

      <div class="cbi-value" id="tcp_page_protocol_modbus2" name="tcp_page_protocol_modbus2">
        <label class="cbi-value-title"><?php echo _("Command Interval"); ?></label>
        <input type="text" class="cbi-input-text" name="tcp_cmd_interval2" id="tcp_cmd_interval2" value="2" />
        <label class="cbi-value-description"><?php echo _("ms"); ?></label>
      </div>

      <div class="cbi-value" id="tcp_page_protocol_transparent2" name="tcp_page_protocol_transparent2">
        <label class="cbi-value-title"><?php echo _("Reporting Center"); ?></label>
        <input type="text" class="cbi-input-text" name="tcp_report_center2" id="tcp_report_center2" />
        <label class="cbi-value-description"><?php echo _("1-2-3-4-5"); ?></label>
      </div>

      <div id="tcp_page_protocol_s72" name="tcp_page_protocol_s72">
        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Rack"); ?></label>
          <input type="text" class="cbi-input-text" name="rack2" id="rack2" />
        </div>
        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Slot"); ?></label>
          <input type="text" class="cbi-input-text" name="slot2" id="slot2" />
        </div>
      </div>
      <div class="cbi-value">
        <label class="cbi-value-title"><?php echo _("Connection Status"); ?></label>
        <?php 
            exec("uci -P /var/state get dct.connection.tcp_status1", $status);
        ?>
        <label id="connect_status2" name="connect_status2"><?php echo _(($status[0] != null) ? $status[0] : "-"); ?></label>
      </div>
    </div><!-- /.page_tcp -->
  </div><!-- /.row -->
</div><!-- /.tab-pane | basic tab -->
<script type="text/javascript">
  function enableTcp2(state) {
    if (state) {
      $('#page_tcp2').show();

      tcpProtocolChange2(state);
    } else {
      $('#page_tcp2').hide();
    }
  }

  function tcpProtocolChange2(that) {
    var protocol = document.getElementById("tcp_proto2").value;

    if (protocol == "0" || protocol == "3") {
      $('#tcp_page_protocol_modbus2').show();
      $('#tcp_page_protocol_transparent2').hide(); 
      $('#tcp_page_protocol_s72').hide(); 
    } else if (protocol == "1") {
      $('#tcp_page_protocol_modbus2').hide();
      $('#tcp_page_protocol_transparent2').show(); 
      $('#tcp_page_protocol_s72').hide(); 
    } else {
      $('#tcp_page_protocol_modbus2').hide();
      $('#tcp_page_protocol_transparent2').hide(); 
      $('#tcp_page_protocol_s72').show(); 
    }
  }

</script>

