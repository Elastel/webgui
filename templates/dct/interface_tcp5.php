<div class="tab-pane fade" id="tcp5">
  <div class="row">
    <div class="cbi-value">
      <label class="cbi-value-title"><?php echo _("Enabled"); ?></label>
      <input class="cbi-input-radio" id="tcp_enable5" name="tcp_enabled5" value="1" type="radio" checked onchange="enableTcp5(true)">
      <label ><?php echo _("Enable"); ?></label>

      <input class="cbi-input-radio" id="tcp_disable5" name="tcp_enabled5" value="0" type="radio" onchange="enableTcp5(false)">
      <label ><?php echo _("Disable"); ?></label>
    </div>

    <div id="page_tcp5" name="page_tcp5">
      <div class="cbi-value">
        <label class="cbi-value-title"><?php echo _("Server Address"); ?></label>
        <input type="text" class="cbi-input-text" name="server_addr5" id="server_addr5" />
      </div>

      <div class="cbi-value">
        <label class="cbi-value-title"><?php echo _("Server Port"); ?></label>
        <input type="text" class="cbi-input-text" name="server_port5" id="server_port5" />
      </div>

      <div class="cbi-value">
        <label class="cbi-value-title"><?php echo _("Frame Interval"); ?></label>
        <input type="text" class="cbi-input-text" name="tcp_frame_interval5" id="tcp_frame_interval5" value="200" />
        <label class="cbi-value-description"><?php echo _("ms"); ?></label>
      </div>

      <div class="cbi-value">
        <label class="cbi-value-title"><?php echo _("Protocol"); ?></label>
        <select id="tcp_proto5" name="tcp_proto5" class="cbi-input-select" onchange="tcpProtocolChange5(this)">
          <option value="0" selected="">Modbus</option>
          <option value="1">Transparent</option>
          <option value="2">S7</option>
        </select>
      </div>

      <div class="cbi-value" id="tcp_page_protocol_modbus5" name="tcp_page_protocol_modbus5">
        <label class="cbi-value-title"><?php echo _("Command Interval"); ?></label>
        <input type="text" class="cbi-input-text" name="tcp_cmd_interval5" id="tcp_cmd_interval5" value="2" />
        <label class="cbi-value-description"><?php echo _("ms"); ?></label>
      </div>

      <div class="cbi-value" id="tcp_page_protocol_transparent5" name="tcp_page_protocol_transparent5">
        <label class="cbi-value-title"><?php echo _("Reporting Center"); ?></label>
        <input type="text" class="cbi-input-text" name="tcp_report_center5" id="tcp_report_center5" />
        <label class="cbi-value-description"><?php echo _("1-2-3-4-5"); ?></label>
      </div>

      <div id="tcp_page_protocol_s75" name="tcp_page_protocol_s75">
        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Rack"); ?></label>
          <input type="text" class="cbi-input-text" name="rack5" id="rack5" />
        </div>
        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Slot"); ?></label>
          <input type="text" class="cbi-input-text" name="slot5" id="slot5" />
        </div>
      </div>
      <div class="cbi-value">
        <label class="cbi-value-title"><?php echo _("Connection Status"); ?></label>
        <?php 
            exec("uci -P /var/state get dct.connection.tcp_status4", $status);
        ?>
        <label id="connect_status5" name="connect_status5"><?php echo _(($status[0] != null) ? $status[0] : "-"); ?></label>
      </div>
    </div><!-- /.page_tcp -->
  </div><!-- /.row -->
</div><!-- /.tab-pane | basic tab -->
<script type="text/javascript">
  function enableTcp5(state) {
    if (state) {
      $('#page_tcp5').show();

      tcpProtocolChange5(state);
    } else {
      $('#page_tcp5').hide();
    }
  }

  function tcpProtocolChange5(that) {
    var protocol = document.getElementById("tcp_proto5").value;

    if (protocol == "0") {
      $('#tcp_page_protocol_modbus5').show();
      $('#tcp_page_protocol_transparent5').hide(); 
      $('#tcp_page_protocol_s75').hide(); 
    } else if (protocol == "1") {
      $('#tcp_page_protocol_modbus5').hide();
      $('#tcp_page_protocol_transparent5').show(); 
      $('#tcp_page_protocol_s75').hide(); 
    } else {
      $('#tcp_page_protocol_modbus5').hide();
      $('#tcp_page_protocol_transparent5').hide(); 
      $('#tcp_page_protocol_s75').show(); 
    }
  }

</script>
