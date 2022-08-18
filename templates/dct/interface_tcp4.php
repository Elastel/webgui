<div class="tab-pane fade" id="tcp4">
  <div class="row">
    <div class="cbi-value">
      <label class="cbi-value-title"><?php echo _("Enabled"); ?></label>
      <input class="cbi-input-radio" id="tcp_enable4" name="tcp_enabled4" value="1" type="radio" checked onchange="enableTcp4(true)">
      <label ><?php echo _("Enable"); ?></label>

      <input class="cbi-input-radio" id="tcp_disable4" name="tcp_enabled4" value="0" type="radio" onchange="enableTcp4(false)">
      <label ><?php echo _("Disable"); ?></label>
    </div>

    <div id="page_tcp4" name="page_tcp4">
      <div class="cbi-value">
        <label class="cbi-value-title"><?php echo _("Server Address"); ?></label>
        <input type="text" class="cbi-input-text" name="server_addr4" id="server_addr4" />
      </div>

      <div class="cbi-value">
        <label class="cbi-value-title"><?php echo _("Server Port"); ?></label>
        <input type="text" class="cbi-input-text" name="server_port4" id="server_port4" />
      </div>

      <div class="cbi-value">
        <label class="cbi-value-title"><?php echo _("Frame Interval"); ?></label>
        <input type="text" class="cbi-input-text" name="tcp_frame_interval4" id="tcp_frame_interval4" value="200" />
        <label class="cbi-value-description"><?php echo _("ms"); ?></label>
      </div>

      <div class="cbi-value">
        <label class="cbi-value-title"><?php echo _("Protocol"); ?></label>
        <select id="tcp_proto4" name="tcp_proto4" class="cbi-input-select" onchange="tcpProtocolChange4(this)">
          <option value="0" selected="">Modbus</option>
          <option value="1">Transparent</option>
          <option value="2">S7</option>
        </select>
      </div>

      <div class="cbi-value" id="tcp_page_protocol_modbus4" name="tcp_page_protocol_modbus4">
        <label class="cbi-value-title"><?php echo _("Command Interval"); ?></label>
        <input type="text" class="cbi-input-text" name="tcp_cmd_interval4" id="tcp_cmd_interval4" value="2" />
        <label class="cbi-value-description"><?php echo _("ms"); ?></label>
      </div>

      <div class="cbi-value" id="tcp_page_protocol_transparent4" name="tcp_page_protocol_transparent4">
        <label class="cbi-value-title"><?php echo _("Reporting Center"); ?></label>
        <input type="text" class="cbi-input-text" name="tcp_report_center4" id="tcp_report_center4" />
        <label class="cbi-value-description"><?php echo _("1-2-3-4-5"); ?></label>
      </div>

      <div id="tcp_page_protocol_s74" name="tcp_page_protocol_s74">
        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Rack"); ?></label>
          <input type="text" class="cbi-input-text" name="rack4" id="rack4" />
        </div>
        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Slot"); ?></label>
          <input type="text" class="cbi-input-text" name="slot4" id="slot4" />
        </div>
      </div>
      <div class="cbi-value">
        <label class="cbi-value-title"><?php echo _("Connection Status"); ?></label>
        <?php 
            exec("uci -P /var/state get dct.connection.tcp_status3", $status);
        ?>
        <label id="connect_status4" name="connect_status4"><?php echo _(($status[0] != null) ? $status[0] : "-"); ?></label>
      </div>
    </div><!-- /.page_tcp -->
  </div><!-- /.row -->
</div><!-- /.tab-pane | basic tab -->
<script type="text/javascript">
  function enableTcp4(state) {
    if (state) {
      $('#page_tcp4').show();

      tcpProtocolChange4(state);
    } else {
      $('#page_tcp4').hide();
    }
  }

  function tcpProtocolChange4(that) {
    var protocol = document.getElementById("tcp_proto4").value;

    if (protocol == "0") {
      $('#tcp_page_protocol_modbus4').show();
      $('#tcp_page_protocol_transparent4').hide(); 
      $('#tcp_page_protocol_s74').hide(); 
    } else if (protocol == "1") {
      $('#tcp_page_protocol_modbus4').hide();
      $('#tcp_page_protocol_transparent4').show(); 
      $('#tcp_page_protocol_s74').hide(); 
    } else {
      $('#tcp_page_protocol_modbus4').hide();
      $('#tcp_page_protocol_transparent4').hide(); 
      $('#tcp_page_protocol_s74').show(); 
    }
  }

</script>
