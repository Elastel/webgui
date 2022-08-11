<div class="tab-pane active" id="server1">
  <div class="row">
    <div class="cbi-value">
      <label class="cbi-value-title"><?php echo _("Enabled"); ?></label>
      <input class="cbi-input-radio" id="enable1" name="enabled1" value="1" type="radio" checked onchange="enableServer(true, 1)">
      <label ><?php echo _("Enable"); ?></label>

      <input class="cbi-input-radio" id="disable1" name="enabled1" value="0" type="radio" onchange="enableServer(false, 1)">
      <label ><?php echo _("Disable"); ?></label>
    </div>

    <div id="page_server1" name="page_server1">
      <div class="cbi-value">
        <label class="cbi-value-title"><?php echo _("Protocol"); ?></label>
        <select id="protocol1" name="protocol1" class="cbi-input-select" onchange="protocolChange(1)">
          <option value="0" selected="">TCP</option>
          <option value="1">UDP</option>
          <option value="2">MQTT</option>
          <option value="3">HTTP</option>
          <option value="4">MODBUS TCP</option>
          <option value="5">TCP Server</option>
        </select>
      </div>

      <div name="page_encap1" id="page_encap1">
        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Encapsulation Type"); ?></label>
          <select id="encap1" name="encap1" class="cbi-input-select" onchange="encapChange(1)">
            <option value="0">Transparent</option>
            <option value="1" selected="">JSON</option>
            <option value="2">HJ212</option>
          </select>
        </div>
      </div>

      <div name="page_addr1" id="page_addr1">
        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Server Address"); ?></label>
          <input type="text" class="cbi-input-text" name="server_address1" id="server_address1" />
        </div>
      </div>

      <div name="page_url1" id="page_url1">
        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Http URL"); ?></label>
          <input type="text" class="cbi-input-text" name="http_url1" id="http_url1" />
        </div>
      </div>

      <div name="page_port1" id="page_port1">
        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Server Port"); ?></label>
          <input type="text" class="cbi-input-text" name="server_port1" id="server_port1" />
        </div>
      </div>

      <?php 
        exec("sudo uci get dct.basic.enabled", $has_cached); 
        exec("sudo uci get dct.basic.cache_enabled", $cache_enabled);

        if ($has_cached[0] == '1' && $cache_enabled[0] == '1') {
      ?>
        <div class="cbi-value" name="page_cache1" id="page_cache1">
          <label class="cbi-value-title"><?php echo _("Enable Cache"); ?></label>
          <input type="checkbox" class="cbi-input-checkbox" name="cache_enabled1" id="cache_enabled1" value="1"/>
          <label class="cbi-value-description"><?php echo _("Cache When Fails To Send"); ?></label>
        </div>
      <?php } ?>
          
      <div name="page_tcp1" id="page_tcp1">
        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Register Packet"); ?></label>
          <input type="text" class="cbi-input-text" name="register_packet1" id="register_packet1" />
          <label class="cbi-value-description"><?php echo _("Max 128 Bytes ASCII"); ?></label>
        </div>

        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Use HEX Format"); ?></label>
          <input type="checkbox" class="cbi-input-checkbox" name="register_packet_hex1" id="register_packet_hex1" value="1"/>
          <label class="cbi-value-description"><?php echo _("Default is ASCII"); ?></label>
        </div>

        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Heartbeat Packet"); ?></label>
          <input type="text" class="cbi-input-text" name="heartbeat_packet1" id="heartbeat_packet1" />
          <label class="cbi-value-description"><?php echo _("Max 128 Bytes ASCII"); ?></label>
        </div>

        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Use HEX Format"); ?></label>
          <input type="checkbox" class="cbi-input-checkbox" name="heartbeat_packet_hex1" id="heartbeat_packet_hex1" value="1"/>
          <label class="cbi-value-description"><?php echo _("Default is ASCII"); ?></label>
        </div>

        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Heartbeat Interval"); ?></label>
          <input type="text" class="cbi-input-text" name="heartbeat_interval1" id="heartbeat_interval1" />
          <label class="cbi-value-description"><?php echo _("Seconds, 0 means No Heartbeat"); ?></label>
        </div>
      </div>  

      <div name="page_mqtt1" id="page_mqtt1">
        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Heartbeat Interval"); ?></label>
          <input type="text" class="cbi-input-text" name="mqtt_heartbeat_interval1" id="mqtt_heartbeat_interval1" />
          <label class="cbi-value-description"><?php echo _("Seconds, 0 means Default Heartbeat"); ?></label>
        </div>

        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("MQTT Public Topic"); ?></label>
          <input type="text" class="cbi-input-text" name="mqtt_public_topic1" id="mqtt_public_topic1" />
        </div>

        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("MQTT Subscribe Topic"); ?></label>
          <input type="text" class="cbi-input-text" name="mqtt_subscribe_topic1" id="mqtt_subscribe_topic1" />
        </div>

        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("MQTT Username"); ?></label>
          <input type="text" class="cbi-input-text" name="mqtt_username1" id="mqtt_username1" />
        </div>

        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("MQTT Password"); ?></label>
          <input type="text" class="cbi-input-text" name="mqtt_password1" id="mqtt_password1" />
        </div>

        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Client ID"); ?></label>
          <input type="text" class="cbi-input-text" name="client_id1" id="client_id1" />
        </div>

        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Enable TLS/SSL"); ?></label>
          <input type="checkbox" class="cbi-input-checkbox" name="mqtt_tls_enabled1" id="mqtt_tls_enabled1" onchange="enableTls(1)" value="1"/>
        </div>

        <div name="page_mqtt_tls1" id="page_mqtt_tls1">
          <div class="cbi-value">
            <label class="cbi-value-title"><?php echo _("Certificate Type"); ?></label>
            <select id="certificate_type1" name="certificate_type1" class="cbi-input-select" onchange="cerChange(1)">
              <option value="0">CA signed server certificate</option>
              <option value="1">One-way certification</option>
              <option value="2">Two-way certification</option>
            </select>
          </div>

          <div name="page_one1" id="page_one1">
            <div class="cbi-value">
              <label class="cbi-value-title"><?php echo _("CA"); ?></label>
              <label for="ca_file1" class="cbi-file-lable">
                  <input type="button" class="cbi-file-btn" id="ca_btn1" value="<?php echo _("Choose file"); ?>">
                  <span id="ca_text1"><?php echo _("No file chosen"); ?></span>
                  <input type="file" class="cbi-file" name="ca_file1" id="ca_file1" onchange="caFileChange(1)">
              </label>
            </div>
          </div>

          <div name="page_two1" id="page_two1">
            <div class="cbi-value">
              <label class="cbi-value-title"><?php echo _("Public Certificate"); ?></label>
              <label class="cbi-file-lable" for="pubulic_cer1">
                  <input type="button" class="cbi-file-btn" id="cer_btn1" value="<?php echo _("Choose file"); ?>">
                  <span id="cer_text1"><?php echo _("No file chosen"); ?></span>
                  <input type="file" class="cbi-file" name="pubulic_cer1" id="pubulic_cer1"  onchange="cerFileChange(1)">
              </label>
            </div>
            <div class="cbi-value">
              <label class="cbi-value-title"><?php echo _("Private Key"); ?></label>
              <label class="cbi-file-lable" for="private_key1">
                  <input type="button" class="cbi-file-btn" id="key_btn1" value="<?php echo _("Choose file"); ?>">
                  <span id="key_text1"><?php echo _("No file chosen"); ?></span>
                  <input type="file" class="cbi-file" name="private_key1" id="private_key1" onchange="keyFileChange(1)">
              </label>
            </div>
          </div>
        </div>
      </div>

      <div name="page_json1" id="page_json1">
        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Enable Self Defined Variable"); ?></label>
          <input type="checkbox" class="cbi-input-checkbox" name="self_define_var1" id="self_define_var1" onchange="enableVar(1)" value="1"/>
        </div>

        <div name="page_var1" id="page_var1">
          <div class="cbi-value">
            <label class="cbi-value-title"><?php echo _("Variable Name1"); ?></label>
            <input type="text" class="cbi-input-text" name="var_name1_1" id="var_name1_1" />
          </div>

          <div class="cbi-value">
            <label class="cbi-value-title"><?php echo _("Variable Value1"); ?></label>
            <input type="text" class="cbi-input-text" name="var_value1_1" id="var_value1_1" />
          </div>

          <div class="cbi-value">
            <label class="cbi-value-title"><?php echo _("Variable Name2"); ?></label>
            <input type="text" class="cbi-input-text" name="var_name2_1" id="var_name2_1" />
          </div>

          <div class="cbi-value">
            <label class="cbi-value-title"><?php echo _("Variable Value2"); ?></label>
            <input type="text" class="cbi-input-text" name="var_value2_1" id="var_value2_1" />
          </div>

          <div class="cbi-value">
            <label class="cbi-value-title"><?php echo _("Variable Name3"); ?></label>
            <input type="text" class="cbi-input-text" name="var_name3_1" id="var_name3_1" />
          </div>

          <div class="cbi-value">
            <label class="cbi-value-title"><?php echo _("Variable Value3"); ?></label>
            <input type="text" class="cbi-input-text" name="var_value3_1" id="var_value3_1" />
          </div>
        </div><!-- /.page_var1 -->
      </div><!-- /.page_json1 -->

      <div name="page_hj212_1" id="page_hj212_1">
        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("MN"); ?></label>
          <input type="text" class="cbi-input-text" name="mn1" id="mn1" />
        </div>

        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("ST"); ?></label>
          <input type="text" class="cbi-input-text" name="st1" id="st1" />
          <label class="cbi-value-description"><?php echo _("2 Bytes Length"); ?></label>
        </div>

        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Password"); ?></label>
          <input type="text" class="cbi-input-text" name="password1" id="password1" />
          <label class="cbi-value-description"><?php echo _("6 Bytes Length"); ?></label>
        </div>

      </div><!-- /.page_hj212_1 -->
      <div name="page_status1" id="page_status1">
        <div class="cbi-value">
          <label class="cbi-value-title"><?php echo _("Connection Status"); ?></label>
          <?php 
            exec("uci -P /var/state get dct.connection.status0", $status);
          ?>
          <label id="connect_status1" name="connect_status1"><?php echo _(($status[0] != null) ? $status[0] : "-"); ?></label>
        </div>
      </div>
    </div><!-- /.page_server -->
  </div><!-- /.row -->
</div><!-- /.tab-pane | basic tab -->
<script type="text/javascript">
</script>

