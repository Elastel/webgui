<div class="tab-pane active" id="com1">
  <div class="row">
    <div class="cbi-value">
      <label class="cbi-value-title"><?php echo _("Enabled"); ?></label>
      <input class="cbi-input-radio" id="com_enable1" name="com_enabled1" value="1" type="radio" checked onchange="enableCom1(true)">
      <label ><?php echo _("Enable"); ?></label>

      <input class="cbi-input-radio" id="com_disable1" name="com_enabled1" value="0" type="radio" onchange="enableCom1(false)">
      <label ><?php echo _("Disable"); ?></label>
    </div>

    <div id="page_com1" name="page_com1">
      <div class="cbi-value">
        <label class="cbi-value-title"><?php echo _("Baudrate"); ?></label>
        <select id="baudrate1" name="baudrate1" class="cbi-input-select">
          <option value="1200">1200</option>
          <option value="2400">2400</option>
          <option value="4800">4800</option>
          <option value="9600" selected="">9600</option>
          <option value="19200">19200</option>
          <option value="38400">38400</option>
          <option value="57600">57600</option>
          <option value="115200">115200</option>
          <option value="230400">230400</option>
        </select>
      </div> 

      <div class="cbi-value">
        <label class="cbi-value-title"><?php echo _("Databit"); ?></label>
        <select id="databit1" name="databit1" class="cbi-input-select">
          <option value="7">7</option>
          <option value="8" selected="">8</option>
        </select>
      </div>

      <div class="cbi-value">
        <label class="cbi-value-title"><?php echo _("Stopbit"); ?></label>
        <select id="stopbit1" name="stopbit1" class="cbi-input-select">
          <option value="1" selected="">1</option>
          <option value="2">2</option>
        </select>
      </div>

      <div class="cbi-value">
        <label class="cbi-value-title"><?php echo _("Parity"); ?></label>
        <select id="parity1" name="parity1" class="cbi-input-select">
          <option value="N" selected="">None</option>
          <option value="O">Odd</option>
          <option value="E">Even</option>
        </select>
      </div>

      <div class="cbi-value">
        <label class="cbi-value-title"><?php echo _("Frame Interval"); ?></label>
        <input type="text" class="cbi-input-text" name="com_frame_interval1" id="com_frame_interval1" />
        <label class="cbi-value-description"><?php echo _("ms"); ?></label>
      </div>

      <div class="cbi-value">
        <label class="cbi-value-title"><?php echo _("Protocol"); ?></label>
        <select id="com_protocol1" name="com_protocol1" class="cbi-input-select" onchange="protocolChange1(this)">
          <option value="0" selected="">Modbus</option>
          <option value="1">Transparent</option>
        </select>
      </div>

      <div class="cbi-value" id="com_page_protocol_modbus1" name="com_page_protocol_modbus1">
        <label class="cbi-value-title"><?php echo _("Command Interval"); ?></label>
        <input type="text" class="cbi-input-text" name="com_command_interval1" id="com_command_interval1" />
        <label class="cbi-value-description"><?php echo _("ms"); ?></label>
      </div>

      <div class="cbi-value" id="com_page_protocol_transparent1" name="com_page_protocol_transparent1">
        <label class="cbi-value-title"><?php echo _("Reporting Center"); ?></label>
        <input type="text" class="cbi-input-text" name="com_reporting_center1" id="com_reporting_center1" />
        <label class="cbi-value-description"><?php echo _("1-2-3-4-5"); ?></label>
      </div>
    </div><!-- /.page_com -->
  </div><!-- /.row -->
</div><!-- /.tab-pane | basic tab -->
<script type="text/javascript">
  function enableCom1(state) {
    if (state) {
      $('#page_com1').show();

      protocolChange1(state);
    } else {
      $('#page_com1').hide();
    }
  }

  function protocolChange1(that) {
    var protocol = document.getElementById("com_protocol1").value;

    if (protocol == "0") {
      $('#com_page_protocol_modbus1').show();
      $('#com_page_protocol_transparent1').hide();
    } else {
      $('#com_page_protocol_modbus1').hide();
      $('#com_page_protocol_transparent1').show();
    }
  }

</script>
