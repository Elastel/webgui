<div class="tab-pane fade" id="server2">
  <div class="row">
    <div class="cbi-value">
      <label class="cbi-value-title"><?php echo _("Enabled"); ?></label>
      <input class="cbi-input-radio" id="enable2" name="enabled2" value="1" type="radio" checked onchange="enableServer(true, 2)">
      <label ><?php echo _("Enable"); ?></label>

      <input class="cbi-input-radio" id="disable2" name="enabled2" value="0" type="radio" onchange="enableServer(false, 2)">
      <label ><?php echo _("Disable"); ?></label>
    </div>

    <div id="page_server2" name="page_server2">
      <div class="cbi-value">
        <label class="cbi-value-title"><?php echo _("Protocol"); ?></label>
        <select id="protocol2" name="protocol2" class="cbi-input-select" onchange="protocolChange(2)">
          <option value="0" selected="">TCP</option>
          <option value="1">UDP</option>
          <option value="2">MQTT</option>
          <option value="3">HTTP</option>
          <option value="4">MODBUS TCP</option>
          <option value="5">TCP Server</option>
        </select>
      </div>

      <div name="page_encap2" id="page_encap2">
        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Encapsulation Type"); ?></label>
          <select id="encap2" name="encap2" class="cbi-input-select" onchange="encapChange(2)">
            <option value="0">Transparent</option>
            <option value="1" selected="">JSON</option>
            <option value="2">HJ212</option>
          </select>
        </div>
      </div>

      <div name="page_addr2" id="page_addr2">
        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Server Address"); ?></label>
          <input type="text" class="cbi-input-text" name="server_address2" id="server_address2" />
        </div>
      </div>

      <div name="page_url2" id="page_url2">
        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Http URL"); ?></label>
          <input type="text" class="cbi-input-text" name="http_url2" id="http_url2" />
        </div>
      </div>

      <div name="page_port2" id="page_port2">
        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Server Port"); ?></label>
          <input type="text" class="cbi-input-text" name="server_port2" id="server_port2" />
        </div>
      </div>

      <?php 
        exec("sudo uci get dct.basic.enabled", $has_cached); 
        exec("sudo uci get dct.basic.cache_enabled", $cache_enabled);

        if ($has_cached[0] == '1' && $cache_enabled[0] == '1') {
      ?>
        <div class="cbi-value" name="page_cache2" id="page_cache2">
          <label class="cbi-value-title"><?php echo _("Enable Cache"); ?></label>
          <input type="checkbox" class="cbi-input-checkbox" name="cache_enabled2" id="cache_enabled2" value="1"/>
          <label class="cbi-value-description"><?php echo _("Cache When Fails To Send"); ?></label>
        </div>
      <?php } ?>

      <div name="page_tcp2" id="page_tcp2">
        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Register Packet"); ?></label>
          <input type="text" class="cbi-input-text" name="register_packet2" id="register_packet2" />
          <label class="cbi-value-description"><?php echo _("Max 128 Bytes ASCII"); ?></label>
        </div>

        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Use HEX Format"); ?></label>
          <input type="checkbox" class="cbi-input-checkbox" name="register_packet_hex2" id="register_packet_hex2" value="1"/>
          <label class="cbi-value-description"><?php echo _("Default is ASCII"); ?></label>
        </div>

        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Heartbeat Packet"); ?></label>
          <input type="text" class="cbi-input-text" name="heartbeat_packet2" id="heartbeat_packet2" />
          <label class="cbi-value-description"><?php echo _("Max 128 Bytes ASCII"); ?></label>
        </div>

        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Use HEX Format"); ?></label>
          <input type="checkbox" class="cbi-input-checkbox" name="heartbeat_packet_hex2" id="heartbeat_packet_hex2" value="1"/>
          <label class="cbi-value-description"><?php echo _("Default is ASCII"); ?></label>
        </div>

        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Heartbeat Interval"); ?></label>
          <input type="text" class="cbi-input-text" name="heartbeat_interval2" id="heartbeat_interval2" />
          <label class="cbi-value-description"><?php echo _("Seconds, 0 means No Heartbeat"); ?></label>
        </div>
      </div>  

      <div name="page_mqtt2" id="page_mqtt2">
        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Heartbeat Interval"); ?></label>
          <input type="text" class="cbi-input-text" name="mqtt_heartbeat_interval2" id="mqtt_heartbeat_interval2" />
          <label class="cbi-value-description"><?php echo _("Seconds, 0 means Default Heartbeat"); ?></label>
        </div>

        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("MQTT Public Topic"); ?></label>
          <input type="text" class="cbi-input-text" name="mqtt_public_topic2" id="mqtt_public_topic2" />
        </div>

        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("MQTT Subscribe Topic"); ?></label>
          <input type="text" class="cbi-input-text" name="mqtt_subscribe_topic2" id="mqtt_subscribe_topic2" />
        </div>

        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("MQTT Username"); ?></label>
          <input type="text" class="cbi-input-text" name="mqtt_username2" id="mqtt_username2" />
        </div>

        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("MQTT Password"); ?></label>
          <input type="text" class="cbi-input-text" name="mqtt_password2" id="mqtt_password2" />
        </div>

        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Client ID"); ?></label>
          <input type="text" class="cbi-input-text" name="client_id2" id="client_id2" />
        </div>

        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Enable TLS/SSL"); ?></label>
          <input type="checkbox" class="cbi-input-checkbox" name="mqtt_tls_enabled2" id="mqtt_tls_enabled2" onchange="enableTls(2)" value="1"/>
        </div>

        <div name="page_mqtt_tls2" id="page_mqtt_tls2">
          <div class="cbi-value">
            <label class="cbi-value-title"><?php echo _("Certificate Type"); ?></label>
            <select id="certificate_type2" name="certificate_type2" class="cbi-input-select" onchange="cerChange(2)">
              <option value="0">CA signed server certificate</option>
              <option value="1">One-way certification</option>
              <option value="2">Two-way certification</option>
            </select>
          </div>

          <div name="page_one2" id="page_one2">
            <div class="cbi-value">
              <label class="cbi-value-title"><?php echo _("CA"); ?></label>
              <label for="ca_file2" class="cbi-file-lable">
                  <input type="button" class="cbi-file-btn" id="ca_btn2" value="<?php echo _("Choose file"); ?>">
                  <span id="ca_text2"><?php echo _("No file chosen"); ?></span>
                  <input type="file" class="cbi-file" name="ca_file2" id="ca_file2" onchange="caFileChange(2)">
              </label>
            </div>
          </div>

          <div name="page_two2" id="page_two2">
            <div class="cbi-value">
              <label class="cbi-value-title"><?php echo _("Public Certificate"); ?></label>
              <label class="cbi-file-lable" for="pubulic_cer2">
                  <input type="button" class="cbi-file-btn" id="cer_btn2" value="<?php echo _("Choose file"); ?>">
                  <span id="cer_text2"><?php echo _("No file chosen"); ?></span>
                  <input type="file" class="cbi-file" name="pubulic_cer2" id="pubulic_cer2"  onchange="cerFileChange(2)">
              </label>
            </div>
            <div class="cbi-value">
              <label class="cbi-value-title"><?php echo _("Private Key"); ?></label>
              <label class="cbi-file-lable" for="private_key2">
                  <input type="button" class="cbi-file-btn" id="key_btn2" value="<?php echo _("Choose file"); ?>">
                  <span id="key_text2"><?php echo _("No file chosen"); ?></span>
                  <input type="file" class="cbi-file" name="private_key2" id="private_key2" onchange="keyFileChange(2)">
              </label>
            </div>
          </div>
        </div>
      </div>

      <div name="page_json2" id="page_json2">
        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Enable Self Defined Variable"); ?></label>
          <input type="checkbox" class="cbi-input-checkbox" name="self_define_var2" id="self_define_var2" onchange="enableVar(2)" value="1"/>
        </div>

        <div name="page_var2" id="page_var2">
          <div class="cbi-value">
            <label class="cbi-value-title"><?php echo _("Variable Name1"); ?></label>
            <input type="text" class="cbi-input-text" name="var_name1_2" id="var_name1_2" />
          </div>

          <div class="cbi-value">
            <label class="cbi-value-title"><?php echo _("Variable Value1"); ?></label>
            <input type="text" class="cbi-input-text" name="var_value1_2" id="var_value1_2" />
          </div>

          <div class="cbi-value">
            <label class="cbi-value-title"><?php echo _("Variable Name2"); ?></label>
            <input type="text" class="cbi-input-text" name="var_name2_2" id="var_name2_2" />
          </div>

          <div class="cbi-value">
            <label class="cbi-value-title"><?php echo _("Variable Value2"); ?></label>
            <input type="text" class="cbi-input-text" name="var_value2_2" id="var_value2_2" />
          </div>

          <div class="cbi-value">
            <label class="cbi-value-title"><?php echo _("Variable Name3"); ?></label>
            <input type="text" class="cbi-input-text" name="var_name3_2" id="var_name3_2" />
          </div>

          <div class="cbi-value">
            <label class="cbi-value-title"><?php echo _("Variable Value3"); ?></label>
            <input type="text" class="cbi-input-text" name="var_value3_2" id="var_value3_2" />
          </div>
        </div><!-- /.page_var2 -->
      </div><!-- /.page_json2 -->

      <div name="page_hj212_2" id="page_hj212_2">
        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("MN"); ?></label>
          <input type="text" class="cbi-input-text" name="mn2" id="mn2" />
        </div>

        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("ST"); ?></label>
          <input type="text" class="cbi-input-text" name="st2" id="st2" />
          <label class="cbi-value-description"><?php echo _("2 Bytes Length"); ?></label>
        </div>

        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Password"); ?></label>
          <input type="text" class="cbi-input-text" name="password2" id="password2" />
          <label class="cbi-value-description"><?php echo _("6 Bytes Length"); ?></label>
        </div>

      </div><!-- /.page_hj212_2 -->
      <div name="page_status2" id="page_status2">
        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Connection Status"); ?></label>
          <?php 
            exec("uci -P /var/state get dct.connection.status1", $status);
          ?>
          <label id="connect_status2" name="connect_status2"><?php echo _(($status[0] != null) ? $status[0] : "-"); ?></label>
        </div>
      </div>
    </div><!-- /.page_server -->
  </div><!-- /.row -->
</div><!-- /.tab-pane | basic tab -->
<script type="text/javascript">
</script>



