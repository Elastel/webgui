<div class="tab-pane fade" id="server3">
  <div class="row">
    <div class="cbi-value">
      <label class="cbi-value-title"><?php echo _("Enabled"); ?></label>
      <input class="cbi-input-radio" id="enable3" name="enabled3" value="1" type="radio" checked onchange="enableServer(true, 3)">
      <label ><?php echo _("Enable"); ?></label>

      <input class="cbi-input-radio" id="disable3" name="enabled3" value="0" type="radio" onchange="enableServer(false, 3)">
      <label ><?php echo _("Disable"); ?></label>
    </div>

    <div id="page_server3" name="page_server3">
      <div class="cbi-value">
        <label class="cbi-value-title"><?php echo _("Protocol"); ?></label>
        <select id="proto3" name="proto3" class="cbi-input-select" onchange="protocolChange(3)">
          <option value="0" selected="">TCP</option>
          <option value="1">UDP</option>
          <option value="2">MQTT</option>
          <option value="3">HTTP</option>
          <option value="4">MODBUS TCP</option>
          <option value="5">TCP Server</option>
        </select>
      </div>

      <div name="page_encap3" id="page_encap3">
        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Encapsulation Type"); ?></label>
          <select id="encap_type3" name="encap_type3" class="cbi-input-select" onchange="encapChange(3)">
            <option value="0">Transparent</option>
            <option value="1" selected="">JSON</option>
            <option value="2">HJ212</option>
          </select>
        </div>
      </div>

      <div name="page_addr3" id="page_addr3">
        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Server Address"); ?></label>
          <input type="text" class="cbi-input-text" name="server_addr3" id="server_addr3" />
        </div>
      </div>

      <div name="page_url3" id="page_url3">
        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Http URL"); ?></label>
          <input type="text" class="cbi-input-text" name="http_url3" id="http_url3" />
        </div>
      </div>

      <div name="page_port3" id="page_port3">
        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Server Port"); ?></label>
          <input type="text" class="cbi-input-text" name="server_port3" id="server_port3" />
        </div>
      </div>

      <?php 
        exec("sudo uci get dct.basic.enabled", $has_cached); 
        exec("sudo uci get dct.basic.cache_enabled", $cache_enabled);

        if ($has_cached[0] == '1' && $cache_enabled[0] == '1') {
      ?>
        <div class="cbi-value" name="page_cache3" id="page_cache3">
          <label class="cbi-value-title"><?php echo _("Enable Cache"); ?></label>
          <input type="checkbox" class="cbi-input-checkbox" name="cache_enabled3" id="cache_enabled3" value="1"/>
          <label class="cbi-value-description"><?php echo _("Cache When Fails To Send"); ?></label>
        </div>
      <?php } ?>

      <div name="page_tcp3" id="page_tcp3">
        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Register Packet"); ?></label>
          <input type="text" class="cbi-input-text" name="register_packet3" id="register_packet3" />
          <label class="cbi-value-description"><?php echo _("Max 128 Bytes ASCII"); ?></label>
        </div>

        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Use HEX Format"); ?></label>
          <input type="checkbox" class="cbi-input-checkbox" name="register_packet_hex3" id="register_packet_hex3" value="1"/>
          <label class="cbi-value-description"><?php echo _("Default is ASCII"); ?></label>
        </div>

        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Heartbeat Packet"); ?></label>
          <input type="text" class="cbi-input-text" name="heartbeat_packet3" id="heartbeat_packet3" />
          <label class="cbi-value-description"><?php echo _("Max 128 Bytes ASCII"); ?></label>
        </div>

        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Use HEX Format"); ?></label>
          <input type="checkbox" class="cbi-input-checkbox" name="heartbeat_packet_hex3" id="heartbeat_packet_hex3" value="1"/>
          <label class="cbi-value-description"><?php echo _("Default is ASCII"); ?></label>
        </div>

        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Heartbeat Interval"); ?></label>
          <input type="text" class="cbi-input-text" name="heartbeat_interval3" id="heartbeat_interval3" />
          <label class="cbi-value-description"><?php echo _("Seconds, 0 means No Heartbeat"); ?></label>
        </div>
      </div>  

      <div name="page_mqtt3" id="page_mqtt3">
        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Heartbeat Interval"); ?></label>
          <input type="text" class="cbi-input-text" name="mqtt_heartbeat_interval3" id="mqtt_heartbeat_interval3" />
          <label class="cbi-value-description"><?php echo _("Seconds, 0 means Default Heartbeat"); ?></label>
        </div>

        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("MQTT Public Topic"); ?></label>
          <input type="text" class="cbi-input-text" name="mqtt_pub_topic3" id="mqtt_public_topic3" />
        </div>

        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("MQTT Subscribe Topic"); ?></label>
          <input type="text" class="cbi-input-text" name="mqtt_sub_topic3" id="mqtt_sub_topic3" />
        </div>

        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("MQTT Username"); ?></label>
          <input type="text" class="cbi-input-text" name="mqtt_username3" id="mqtt_username3" />
        </div>

        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("MQTT Password"); ?></label>
          <input type="text" class="cbi-input-text" name="mqtt_password3" id="mqtt_password3" />
        </div>

        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Client ID"); ?></label>
          <input type="text" class="cbi-input-text" name="mqtt_client_id3" id="mqtt_client_id3" />
        </div>

        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Enable TLS/SSL"); ?></label>
          <input type="checkbox" class="cbi-input-checkbox" name="mqtt_tls_enabled3" id="mqtt_tls_enabled3" onchange="enableTls(3)" value="1"/>
        </div>

        <div name="page_mqtt_tls3" id="page_mqtt_tls3">
          <div class="cbi-value">
            <label class="cbi-value-title"><?php echo _("Certificate Type"); ?></label>
            <select id="certificate_type3" name="certificate_type3" class="cbi-input-select" onchange="cerChange(3)">
              <option value="0">CA signed server certificate</option>
              <option value="1">One-way certification</option>
              <option value="2">Two-way certification</option>
            </select>
          </div>

          <div name="page_one3" id="page_one3">
            <div class="cbi-value">
              <label class="cbi-value-title"><?php echo _("CA"); ?></label>
              <label for="mqtt_ca3" class="cbi-file-lable">
                  <input type="button" class="cbi-file-btn" id="ca_btn3" value="<?php echo _("Choose file"); ?>">
                  <span id="ca_text3"><?php echo _("No file chosen"); ?></span>
                  <input type="file" class="cbi-file" name="mqtt_ca3" id="mqtt_ca3" onchange="caFileChange(3)">
              </label>
            </div>
          </div>

          <div name="page_two3" id="page_two3">
            <div class="cbi-value">
              <label class="cbi-value-title"><?php echo _("Public Certificate"); ?></label>
              <label class="cbi-file-lable" for="mqtt_cert3">
                  <input type="button" class="cbi-file-btn" id="cer_btn3" value="<?php echo _("Choose file"); ?>">
                  <span id="cer_text3"><?php echo _("No file chosen"); ?></span>
                  <input type="file" class="cbi-file" name="mqtt_cert3" id="mqtt_cert3"  onchange="cerFileChange(3)">
              </label>
            </div>
            <div class="cbi-value">
              <label class="cbi-value-title"><?php echo _("Private Key"); ?></label>
              <label class="cbi-file-lable" for="mqtt_key3">
                  <input type="button" class="cbi-file-btn" id="key_btn3" value="<?php echo _("Choose file"); ?>">
                  <span id="key_text3"><?php echo _("No file chosen"); ?></span>
                  <input type="file" class="cbi-file" name="mqtt_key3" id="mqtt_key3" onchange="keyFileChange(3)">
              </label>
            </div>
          </div>
        </div>
      </div>

      <div name="page_json3" id="page_json3">
        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Enable Self Defined Variable"); ?></label>
          <input type="checkbox" class="cbi-input-checkbox" name="self_define_var3" id="self_define_var3" onchange="enableVar(3)" value="1"/>
        </div>

        <div name="page_var3" id="page_var3">
          <div class="cbi-value">
            <label class="cbi-value-title"><?php echo _("Variable Name1"); ?></label>
            <input type="text" class="cbi-input-text" name="var_name1_3" id="var_name1_3" />
          </div>

          <div class="cbi-value">
            <label class="cbi-value-title"><?php echo _("Variable Value1"); ?></label>
            <input type="text" class="cbi-input-text" name="var_value1_3" id="var_value1_3" />
          </div>

          <div class="cbi-value">
            <label class="cbi-value-title"><?php echo _("Variable Name2"); ?></label>
            <input type="text" class="cbi-input-text" name="var_name2_3" id="var_name2_3" />
          </div>

          <div class="cbi-value">
            <label class="cbi-value-title"><?php echo _("Variable Value2"); ?></label>
            <input type="text" class="cbi-input-text" name="var_value2_3" id="var_value2_3" />
          </div>

          <div class="cbi-value">
            <label class="cbi-value-title"><?php echo _("Variable Name3"); ?></label>
            <input type="text" class="cbi-input-text" name="var_name3_3" id="var_name3_3" />
          </div>

          <div class="cbi-value">
            <label class="cbi-value-title"><?php echo _("Variable Value3"); ?></label>
            <input type="text" class="cbi-input-text" name="var_value3_3" id="var_value3_3" />
          </div>
        </div><!-- /.page_var3 -->
      </div><!-- /.page_json3 -->

      <div name="page_hj212_3" id="page_hj212_3">
        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("MN"); ?></label>
          <input type="text" class="cbi-input-text" name="mn3" id="mn3" />
        </div>

        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("ST"); ?></label>
          <input type="text" class="cbi-input-text" name="st3" id="st3" />
          <label class="cbi-value-description"><?php echo _("2 Bytes Length"); ?></label>
        </div>

        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Password"); ?></label>
          <input type="text" class="cbi-input-text" name="password3" id="password3" />
          <label class="cbi-value-description"><?php echo _("6 Bytes Length"); ?></label>
        </div>

      </div><!-- /.page_hj212_3 -->
      <div name="page_status3" id="page_status3">
        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Connection Status"); ?></label>
          <?php 
            exec("uci -P /var/state get dct.connection.status2", $status);
          ?>
          <label id="connect_status3" name="connect_status3"><?php echo _(($status[0] != null) ? $status[0] : "-"); ?></label>
        </div>
      </div>
    </div><!-- /.page_server -->
  </div><!-- /.row -->
</div><!-- /.tab-pane | basic tab -->
<script type="text/javascript">
</script>



