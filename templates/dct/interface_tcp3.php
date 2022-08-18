<div class="tab-pane fade" id="tcp3">
  <div class="row">
    <div class="cbi-value">
      <label class="cbi-value-title"><?php echo _("Enabled"); ?></label>
      <input class="cbi-input-radio" id="tcp_enable3" name="tcp_enabled3" value="1" type="radio" checked onchange="enableTcp3(true)">
      <label ><?php echo _("Enable"); ?></label>

      <input class="cbi-input-radio" id="tcp_disable3" name="tcp_enabled3" value="0" type="radio" onchange="enableTcp3(false)">
      <label ><?php echo _("Disable"); ?></label>
    </div>

    <div id="page_tcp3" name="page_tcp3">
      <div class="cbi-value">
        <label class="cbi-value-title"><?php echo _("Server Address"); ?></label>
        <input type="text" class="cbi-input-text" name="server_addr3" id="server_addr3" />
      </div>

      <div class="cbi-value">
        <label class="cbi-value-title"><?php echo _("Server Port"); ?></label>
        <input type="text" class="cbi-input-text" name="server_port3" id="server_port3" />
      </div>

      <div class="cbi-value">
        <label class="cbi-value-title"><?php echo _("Frame Interval"); ?></label>
        <input type="text" class="cbi-input-text" name="tcp_frame_interval3" id="tcp_frame_interval3" value="200" />
        <label class="cbi-value-description"><?php echo _("ms"); ?></label>
      </div>

      <div class="cbi-value">
        <label class="cbi-value-title"><?php echo _("Protocol"); ?></label>
        <select id="tcp_proto3" name="tcp_proto3" class="cbi-input-select" onchange="tcpProtocolChange3(this)">
          <option value="0" selected="">Modbus</option>
          <option value="1">Transparent</option>
          <option value="2">S7</option>
        </select>
      </div>

      <div class="cbi-value" id="tcp_page_protocol_modbus3" name="tcp_page_protocol_modbus3">
        <label class="cbi-value-title"><?php echo _("Command Interval"); ?></label>
        <input type="text" class="cbi-input-text" name="tcp_cmd_interval3" id="tcp_cmd_interval3" value="2" />
        <label class="cbi-value-description"><?php echo _("ms"); ?></label>
      </div>

      <div class="cbi-value" id="tcp_page_protocol_transparent3" name="tcp_page_protocol_transparent3">
        <label class="cbi-value-title"><?php echo _("Reporting Center"); ?></label>
        <input type="text" class="cbi-input-text" name="tcp_report_center3" id="tcp_report_center3" />
        <label class="cbi-value-description"><?php echo _("1-2-3-4-5"); ?></label>
      </div>

      <div id="tcp_page_protocol_s73" name="tcp_page_protocol_s73">
        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Rack"); ?></label>
          <input type="text" class="cbi-input-text" name="rack3" id="rack3" />
        </div>
        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Slot"); ?></label>
          <input type="text" class="cbi-input-text" name="slot3" id="slot3" />
        </div>
      </div>
      <div class="cbi-value">
        <label class="cbi-value-title"><?php echo _("Connection Status"); ?></label>
        <?php 
            exec("uci -P /var/state get dct.connection.tcp_status2", $status);
        ?>
        <label id="connect_status3" name="connect_status3"><?php echo _(($status[0] != null) ? $status[0] : "-"); ?></label>
      </div>
    </div><!-- /.page_tcp -->
  </div><!-- /.row -->
</div><!-- /.tab-pane | basic tab -->
<script type="text/javascript">
  function enableTcp3(state) {
    if (state) {
      $('#page_tcp3').show();

      tcpProtocolChange3(state);
    } else {
      $('#page_tcp3').hide();
    }
  }

  function tcpProtocolChange3(that) {
    var protocol = document.getElementById("tcp_proto3").value;

    if (protocol == "0") {
      $('#tcp_page_protocol_modbus3').show();
      $('#tcp_page_protocol_transparent3').hide(); 
      $('#tcp_page_protocol_s73').hide(); 
    } else if (protocol == "1") {
      $('#tcp_page_protocol_modbus3').hide();
      $('#tcp_page_protocol_transparent3').show(); 
      $('#tcp_page_protocol_s73').hide(); 
    } else {
      $('#tcp_page_protocol_modbus3').hide();
      $('#tcp_page_protocol_transparent3').hide(); 
      $('#tcp_page_protocol_s73').show(); 
    }
  }

</script>

