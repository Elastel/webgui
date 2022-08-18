<div class="tab-pane fade" id="server5">
  <div class="row">
    <div class="cbi-value">
      <label class="cbi-value-title"><?php echo _("Enabled"); ?></label>
      <input class="cbi-input-radio" id="enable5" name="enabled5" value="1" type="radio" checked onchange="enableServer(true, 5)">
      <label ><?php echo _("Enable"); ?></label>

      <input class="cbi-input-radio" id="disable5" name="enabled5" value="0" type="radio" onchange="enableServer(false, 5)">
      <label ><?php echo _("Disable"); ?></label>
    </div>

    <div id="page_server5" name="page_server5">
      <div class="cbi-value">
        <label class="cbi-value-title"><?php echo _("Protocol"); ?></label>
        <select id="proto5" name="proto5" class="cbi-input-select" onchange="protocolChange(5)">
          <option value="0" selected="">TCP</option>
          <option value="1">UDP</option>
          <option value="2">MQTT</option>
          <option value="3">HTTP</option>
          <option value="4">MODBUS TCP</option>
          <option value="5">TCP Server</option>
        </select>
      </div>

      <div name="page_encap5" id="page_encap5">
        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Encapsulation Type"); ?></label>
          <select id="encap_type5" name="encap_type5" class="cbi-input-select" onchange="encapChange(5)">
            <option value="0">Transparent</option>
            <option value="1" selected="">JSON</option>
            <option value="2">HJ212</option>
          </select>
        </div>
      </div>

      <div name="page_addr5" id="page_addr5">
        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Server Address"); ?></label>
          <input type="text" class="cbi-input-text" name="server_addr5" id="server_addr5" />
        </div>
      </div>

      <div name="page_url5" id="page_url5">
        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Http URL"); ?></label>
          <input type="text" class="cbi-input-text" name="http_url5" id="http_url5" />
        </div>
      </div>

      <div name="page_port5" id="page_port5">
        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Server Port"); ?></label>
          <input type="text" class="cbi-input-text" name="server_port5" id="server_port5" />
        </div>
      </div>

      <?php 
        exec("sudo uci get dct.basic.enabled", $has_cached); 
        exec("sudo uci get dct.basic.cache_enabled", $cache_enabled);

        if ($has_cached[0] == '1' && $cache_enabled[0] == '1') {
      ?>
        <div class="cbi-value" name="page_cache5" id="page_cache5">
          <label class="cbi-value-title"><?php echo _("Enable Cache"); ?></label>
          <input type="checkbox" class="cbi-input-checkbox" name="cache_enabled5" id="cache_enabled5" value="1"/>
          <label class="cbi-value-description"><?php echo _("Cache When Fails To Send"); ?></label>
        </div>
      <?php } ?>

      <div name="page_tcp5" id="page_tcp5">
        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Register Packet"); ?></label>
          <input type="text" class="cbi-input-text" name="register_packet5" id="register_packet5" />
          <label class="cbi-value-description"><?php echo _("Max 128 Bytes ASCII"); ?></label>
        </div>

        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Use HEX Format"); ?></label>
          <input type="checkbox" class="cbi-input-checkbox" name="register_packet_hex5" id="register_packet_hex5" value="1"/>
          <label class="cbi-value-description"><?php echo _("Default is ASCII"); ?></label>
        </div>

        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Heartbeat Packet"); ?></label>
          <input type="text" class="cbi-input-text" name="heartbeat_packet5" id="heartbeat_packet5" />
          <label class="cbi-value-description"><?php echo _("Max 128 Bytes ASCII"); ?></label>
        </div>

        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Use HEX Format"); ?></label>
          <input type="checkbox" class="cbi-input-checkbox" name="heartbeat_packet_hex5" id="heartbeat_packet_hex5" value="1"/>
          <label class="cbi-value-description"><?php echo _("Default is ASCII"); ?></label>
        </div>

        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Heartbeat Interval"); ?></label>
          <input type="text" class="cbi-input-text" name="heartbeat_interval5" id="heartbeat_interval5" />
          <label class="cbi-value-description"><?php echo _("Seconds, 0 means No Heartbeat"); ?></label>
        </div>
      </div>  

      <div name="page_mqtt5" id="page_mqtt5">
        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Heartbeat Interval"); ?></label>
          <input type="text" class="cbi-input-text" name="mqtt_heartbeat_interval5" id="mqtt_heartbeat_interval5" />
          <label class="cbi-value-description"><?php echo _("Seconds, 0 means Default Heartbeat"); ?></label>
        </div>

        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("MQTT Public Topic"); ?></label>
          <input type="text" class="cbi-input-text" name="mqtt_pub_topic5" id="mqtt_pub_topic5" />
        </div>

        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("MQTT Subscribe Topic"); ?></label>
          <input type="text" class="cbi-input-text" name="mqtt_sub_topic5" id="mqtt_sub_topic5" />
        </div>

        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("MQTT Username"); ?></label>
          <input type="text" class="cbi-input-text" name="mqtt_username5" id="mqtt_username5" />
        </div>

        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("MQTT Password"); ?></label>
          <input type="text" class="cbi-input-text" name="mqtt_password5" id="mqtt_password5" />
        </div>

        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Client ID"); ?></label>
          <input type="text" class="cbi-input-text" name="mqtt_client_id5" id="mqtt_client_id5" />
        </div>

        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Enable TLS/SSL"); ?></label>
          <input type="checkbox" class="cbi-input-checkbox" name="mqtt_tls_enabled5" id="mqtt_tls_enabled5" onchange="enableTls(5)" value="1"/>
        </div>

        <div name="page_mqtt_tls5" id="page_mqtt_tls5">
          <div class="cbi-value">
            <label class="cbi-value-title"><?php echo _("Certificate Type"); ?></label>
            <select id="certificate_type5" name="certificate_type5" class="cbi-input-select" onchange="cerChange(5)">
              <option value="0">CA signed server certificate</option>
              <option value="1">One-way certification</option>
              <option value="2">Two-way certification</option>
            </select>
          </div>

          <div name="page_one5" id="page_one5">
            <div class="cbi-value">
              <label class="cbi-value-title"><?php echo _("CA"); ?></label>
              <label for="mqtt_ca5" class="cbi-file-lable">
                  <input type="button" class="cbi-file-btn" id="ca_btn5" value="<?php echo _("Choose file"); ?>">
                  <span id="ca_text5"><?php echo _("No file chosen"); ?></span>
                  <input type="file" class="cbi-file" name="mqtt_ca5" id="mqtt_ca5" onchange="caFileChange(5)">
              </label>
            </div>
          </div>

          <div name="page_two5" id="page_two5">
            <div class="cbi-value">
              <label class="cbi-value-title"><?php echo _("Public Certificate"); ?></label>
              <label class="cbi-file-lable" for="mqtt_cert5">
                  <input type="button" class="cbi-file-btn" id="cer_btn5" value="<?php echo _("Choose file"); ?>">
                  <span id="cer_text5"><?php echo _("No file chosen"); ?></span>
                  <input type="file" class="cbi-file" name="mqtt_cert5" id="mqtt_cert5"  onchange="cerFileChange(5)">
              </label>
            </div>
            <div class="cbi-value">
              <label class="cbi-value-title"><?php echo _("Private Key"); ?></label>
              <label class="cbi-file-lable" for="mqtt_key5">
                  <input type="button" class="cbi-file-btn" id="key_btn5" value="<?php echo _("Choose file"); ?>">
                  <span id="key_text5"><?php echo _("No file chosen"); ?></span>
                  <input type="file" class="cbi-file" name="mqtt_key5" id="mqtt_key5" onchange="keyFileChange(5)">
              </label>
            </div>
          </div>
        </div>
      </div>

      <div name="page_json5" id="page_json5">
        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Enable Self Defined Variable"); ?></label>
          <input type="checkbox" class="cbi-input-checkbox" name="self_define_var5" id="self_define_var5" onchange="enableVar(5)" value="1"/>
        </div>

        <div name="page_var5" id="page_var5">
          <div class="cbi-value">
            <label class="cbi-value-title"><?php echo _("Variable Name1"); ?></label>
            <input type="text" class="cbi-input-text" name="var_name1_5" id="var_name1_5" />
          </div>

          <div class="cbi-value">
            <label class="cbi-value-title"><?php echo _("Variable Value1"); ?></label>
            <input type="text" class="cbi-input-text" name="var_value1_5" id="var_value1_5" />
          </div>

          <div class="cbi-value">
            <label class="cbi-value-title"><?php echo _("Variable Name2"); ?></label>
            <input type="text" class="cbi-input-text" name="var_name2_5" id="var_name2_5" />
          </div>

          <div class="cbi-value">
            <label class="cbi-value-title"><?php echo _("Variable Value2"); ?></label>
            <input type="text" class="cbi-input-text" name="var_value2_5" id="var_value2_5" />
          </div>

          <div class="cbi-value">
            <label class="cbi-value-title"><?php echo _("Variable Name3"); ?></label>
            <input type="text" class="cbi-input-text" name="var_name3_5" id="var_name3_5" />
          </div>

          <div class="cbi-value">
            <label class="cbi-value-title"><?php echo _("Variable Value3"); ?></label>
            <input type="text" class="cbi-input-text" name="var_value3_5" id="var_value3_5" />
          </div>
        </div><!-- /.page_var5 -->
      </div><!-- /.page_json5 -->

      <div name="page_hj212_5" id="page_hj212_5">
        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("MN"); ?></label>
          <input type="text" class="cbi-input-text" name="mn5" id="mn5" />
        </div>

        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("ST"); ?></label>
          <input type="text" class="cbi-input-text" name="st5" id="st5" />
          <label class="cbi-value-description"><?php echo _("2 Bytes Length"); ?></label>
        </div>

        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Password"); ?></label>
          <input type="text" class="cbi-input-text" name="password5" id="password5" />
          <label class="cbi-value-description"><?php echo _("6 Bytes Length"); ?></label>
        </div>

      </div><!-- /.page_hj212_5 -->
      <div name="page_status5" id="page_status5">
        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Connection Status"); ?></label>
          <?php 
            exec("uci -P /var/state get dct.connection.status4", $status);
          ?>
          <label id="connect_status5" name="connect_status5"><?php echo _(($status[0] != null) ? $status[0] : "-"); ?></label>
        </div>
      </div>
    </div><!-- /.page_server -->
  </div><!-- /.row -->
</div><!-- /.tab-pane | basic tab -->
<script type="text/javascript">
</script>



