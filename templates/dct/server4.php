<div class="tab-pane fade" id="server4">
  <div class="row">
    <div class="cbi-value">
      <label class="cbi-value-title"><?php echo _("Enabled"); ?></label>
      <input class="cbi-input-radio" id="enable4" name="enabled4" value="1" type="radio" checked onchange="enableServer(true, 4)">
      <label ><?php echo _("Enable"); ?></label>

      <input class="cbi-input-radio" id="disable4" name="enabled4" value="0" type="radio" onchange="enableServer(false, 4)">
      <label ><?php echo _("Disable"); ?></label>
    </div>

    <div id="page_server4" name="page_server4">
      <div class="cbi-value">
        <label class="cbi-value-title"><?php echo _("Protocol"); ?></label>
        <select id="proto4" name="proto4" class="cbi-input-select" onchange="protocolChange(4)">
          <option value="0" selected="">TCP</option>
          <option value="1">UDP</option>
          <option value="2">MQTT</option>
          <option value="3">HTTP</option>
          <option value="4">MODBUS TCP</option>
          <option value="5">TCP Server</option>
        </select>
      </div>

      <div name="page_encap4" id="page_encap4">
        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Encapsulation Type"); ?></label>
          <select id="encap_type4" name="encap_type4" class="cbi-input-select" onchange="encapChange(4)">
            <option value="0">Transparent</option>
            <option value="1" selected="">JSON</option>
            <option value="2">HJ212</option>
          </select>
        </div>
      </div>

      <div name="page_addr4" id="page_addr4">
        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Server Address"); ?></label>
          <input type="text" class="cbi-input-text" name="server_addr4" id="server_addr4" />
        </div>
      </div>

      <div name="page_url4" id="page_url4">
        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Http URL"); ?></label>
          <input type="text" class="cbi-input-text" name="http_url4" id="http_url4" />
        </div>
      </div>

      <div name="page_port4" id="page_port4">
        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Server Port"); ?></label>
          <input type="text" class="cbi-input-text" name="server_port4" id="server_port4" />
        </div>
      </div>

      <?php 
        exec("sudo uci get dct.basic.enabled", $has_cached); 
        exec("sudo uci get dct.basic.cache_enabled", $cache_enabled);

        if ($has_cached[0] == '1' && $cache_enabled[0] == '1') {
      ?>
        <div class="cbi-value" name="page_cache4" id="page_cache4">
          <label class="cbi-value-title"><?php echo _("Enable Cache"); ?></label>
          <input type="checkbox" class="cbi-input-checkbox" name="cache_enabled4" id="cache_enabled4" value="1"/>
          <label class="cbi-value-description"><?php echo _("Cache When Fails To Send"); ?></label>
        </div>
      <?php } ?>

      <div name="page_tcp4" id="page_tcp4">
        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Register Packet"); ?></label>
          <input type="text" class="cbi-input-text" name="register_packet4" id="register_packet4" />
          <label class="cbi-value-description"><?php echo _("Max 128 Bytes ASCII"); ?></label>
        </div>

        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Use HEX Format"); ?></label>
          <input type="checkbox" class="cbi-input-checkbox" name="register_packet_hex4" id="register_packet_hex4" value="1"/>
          <label class="cbi-value-description"><?php echo _("Default is ASCII"); ?></label>
        </div>

        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Heartbeat Packet"); ?></label>
          <input type="text" class="cbi-input-text" name="heartbeat_packet4" id="heartbeat_packet4" />
          <label class="cbi-value-description"><?php echo _("Max 128 Bytes ASCII"); ?></label>
        </div>

        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Use HEX Format"); ?></label>
          <input type="checkbox" class="cbi-input-checkbox" name="heartbeat_packet_hex4" id="heartbeat_packet_hex4" value="1"/>
          <label class="cbi-value-description"><?php echo _("Default is ASCII"); ?></label>
        </div>

        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Heartbeat Interval"); ?></label>
          <input type="text" class="cbi-input-text" name="heartbeat_interval4" id="heartbeat_interval4" />
          <label class="cbi-value-description"><?php echo _("Seconds, 0 means No Heartbeat"); ?></label>
        </div>
      </div>  

      <div name="page_mqtt4" id="page_mqtt4">
        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Heartbeat Interval"); ?></label>
          <input type="text" class="cbi-input-text" name="mqtt_heartbeat_interval4" id="mqtt_heartbeat_interval4" />
          <label class="cbi-value-description"><?php echo _("Seconds, 0 means Default Heartbeat"); ?></label>
        </div>

        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("MQTT Public Topic"); ?></label>
          <input type="text" class="cbi-input-text" name="mqtt_pub_topic4" id="mqtt_pub_topic4" />
        </div>

        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("MQTT Subscribe Topic"); ?></label>
          <input type="text" class="cbi-input-text" name="mqtt_sub_topic4" id="mqtt_sub_topic4" />
        </div>

        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("MQTT Username"); ?></label>
          <input type="text" class="cbi-input-text" name="mqtt_username4" id="mqtt_username4" />
        </div>

        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("MQTT Password"); ?></label>
          <input type="text" class="cbi-input-text" name="mqtt_password4" id="mqtt_password4" />
        </div>

        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Client ID"); ?></label>
          <input type="text" class="cbi-input-text" name="mqtt_client_id4" id="mqtt_client_id4" />
        </div>

        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Enable TLS/SSL"); ?></label>
          <input type="checkbox" class="cbi-input-checkbox" name="mqtt_tls_enabled4" id="mqtt_tls_enabled4" onchange="enableTls(4)" value="1"/>
        </div>

        <div name="page_mqtt_tls4" id="page_mqtt_tls4">
          <div class="cbi-value">
            <label class="cbi-value-title"><?php echo _("Certificate Type"); ?></label>
            <select id="certificate_type4" name="certificate_type4" class="cbi-input-select" onchange="cerChange(4)">
              <option value="0">CA signed server certificate</option>
              <option value="1">One-way certification</option>
              <option value="2">Two-way certification</option>
            </select>
          </div>

          <div name="page_one4" id="page_one4">
            <div class="cbi-value">
              <label class="cbi-value-title"><?php echo _("CA"); ?></label>
              <label for="mqtt_ca4" class="cbi-file-lable">
                  <input type="button" class="cbi-file-btn" id="ca_btn4" value="<?php echo _("Choose file"); ?>">
                  <span id="ca_text4"><?php echo _("No file chosen"); ?></span>
                  <input type="file" class="cbi-file" name="mqtt_ca4" id="mqtt_ca4" onchange="caFileChange(4)">
              </label>
            </div>
          </div>

          <div name="page_two4" id="page_two4">
            <div class="cbi-value">
              <label class="cbi-value-title"><?php echo _("Public Certificate"); ?></label>
              <label class="cbi-file-lable" for="mqtt_cert4">
                  <input type="button" class="cbi-file-btn" id="cer_btn4" value="<?php echo _("Choose file"); ?>">
                  <span id="cer_text4"><?php echo _("No file chosen"); ?></span>
                  <input type="file" class="cbi-file" name="mqtt_cert4" id="mqtt_cert4"  onchange="cerFileChange(4)">
              </label>
            </div>
            <div class="cbi-value">
              <label class="cbi-value-title"><?php echo _("Private Key"); ?></label>
              <label class="cbi-file-lable" for="mqtt_key4">
                  <input type="button" class="cbi-file-btn" id="key_btn4" value="<?php echo _("Choose file"); ?>">
                  <span id="key_text4"><?php echo _("No file chosen"); ?></span>
                  <input type="file" class="cbi-file" name="mqtt_key4" id="mqtt_key4" onchange="keyFileChange(4)">
              </label>
            </div>
          </div>
        </div>
      </div>

      <div name="page_json4" id="page_json4">
        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Enable Self Defined Variable"); ?></label>
          <input type="checkbox" class="cbi-input-checkbox" name="self_define_var4" id="self_define_var4" onchange="enableVar(4)" value="1"/>
        </div>

        <div name="page_var4" id="page_var4">
          <div class="cbi-value">
            <label class="cbi-value-title"><?php echo _("Variable Name1"); ?></label>
            <input type="text" class="cbi-input-text" name="var_name1_4" id="var_name1_4" />
          </div>

          <div class="cbi-value">
            <label class="cbi-value-title"><?php echo _("Variable Value1"); ?></label>
            <input type="text" class="cbi-input-text" name="var_value1_4" id="var_value1_4" />
          </div>

          <div class="cbi-value">
            <label class="cbi-value-title"><?php echo _("Variable Name2"); ?></label>
            <input type="text" class="cbi-input-text" name="var_name2_4" id="var_name2_4" />
          </div>

          <div class="cbi-value">
            <label class="cbi-value-title"><?php echo _("Variable Value2"); ?></label>
            <input type="text" class="cbi-input-text" name="var_value2_4" id="var_value2_4" />
          </div>

          <div class="cbi-value">
            <label class="cbi-value-title"><?php echo _("Variable Name3"); ?></label>
            <input type="text" class="cbi-input-text" name="var_name3_4" id="var_name3_4" />
          </div>

          <div class="cbi-value">
            <label class="cbi-value-title"><?php echo _("Variable Value3"); ?></label>
            <input type="text" class="cbi-input-text" name="var_value3_4" id="var_value3_4" />
          </div>
        </div><!-- /.page_var4 -->
      </div><!-- /.page_json4 -->

      <div name="page_hj212_4" id="page_hj212_4">
        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("MN"); ?></label>
          <input type="text" class="cbi-input-text" name="mn4" id="mn4" />
        </div>

        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("ST"); ?></label>
          <input type="text" class="cbi-input-text" name="st4" id="st4" />
          <label class="cbi-value-description"><?php echo _("2 Bytes Length"); ?></label>
        </div>

        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Password"); ?></label>
          <input type="text" class="cbi-input-text" name="password4" id="password4" />
          <label class="cbi-value-description"><?php echo _("6 Bytes Length"); ?></label>
        </div>

      </div><!-- /.page_hj212_4 -->
      <div name="page_status4" id="page_status4">
        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Connection Status"); ?></label>
          <?php 
            exec("uci -P /var/state get dct.connection.status3", $status);
          ?>
          <label id="connect_status4" name="connect_status4"><?php echo _(($status[0] != null) ? $status[0] : "-"); ?></label>
        </div>
      </div>
    </div><!-- /.page_server -->
  </div><!-- /.row -->
</div><!-- /.tab-pane | basic tab -->
<script type="text/javascript">
</script>



