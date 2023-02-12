<div class="tab-pane active" id="general">
  <div class="row">
    <div class="cbi-value">
        <label class="cbi-value-title"><?php echo _("Type Service"); ?></label>
        <select id="type" name="type" class="cbi-input-select">
            <option value="0">Disabled</option>
            <option value="1">LoRaWan Service</option>
        </select>
    </div>

    <div class="cbi-value">
        <label class="cbi-value-title"><?php echo _("Server Address"); ?></label>
        <input type="text" class="cbi-input-text" name="server_address" id="server_address" value="127.0.0.1" />
    </div>

    <div class="cbi-value">
        <label class="cbi-value-title"><?php echo _("Server port for upstream"); ?></label>
        <input type="text" class="cbi-input-text" name="serv_port_up" id="serv_port_up" value="1700" />
    </div>

    <div class="cbi-value">
        <label class="cbi-value-title"><?php echo _("Server port for downstream"); ?></label>
        <input type="text" class="cbi-input-text" name="serv_port_down" id="serv_port_down" value="1700" />
    </div>

    <div class="cbi-value">
        <label class="cbi-value-title"><?php echo _("Gateway ID"); ?></label>
        <input type="text" class="cbi-input-text" name="gateway_ID" id="gateway_ID" value="" />
    </div>

    <div class="cbi-value">
        <label class="cbi-value-title"><?php echo _("Keepalive interval in seconds"); ?></label>
        <input type="text" class="cbi-input-text" name="keepalive_interval" id="keepalive_interval" value="10" />
    </div>

    <div class="cbi-value">
        <label class="cbi-value-title"><?php echo _("Status interval in seconds"); ?></label>
        <input type="text" class="cbi-input-text" name="stat_interval" id="stat_interval" value="30" />
    </div>

    <!-- <div class="cbi-value">
        <label class="cbi-value-title"><?php echo _("Frequency Plan"); ?></label>
        <select id="frequency" name="frequency" class="cbi-input-select">
            <option value="0"><?php echo _("Europe 868MHz(863~870)--EU868"); ?></option>
            <option value="1"><?php echo _("China 490MHz--CN490"); ?></option>
            <option value="2"><?php echo _("United States 915MHz(902~928)--US915"); ?></option>
            <option value="3"><?php echo _("Asia 920~923MHz--AS923"); ?></option>
        </select>
    </div> -->
  </div><!-- /.row -->
</div><!-- /.tab-pane | basic tab -->
<script type="text/javascript">
</script>
