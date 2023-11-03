<?php
function page_interface_com($num, $com_proto) {

if ($num == 1)
    $active = "active";
else
    $active = "fade";

echo "
    <div class=\"tab-pane $active\" id=\"com$num\">
        <div class=\"row\">
        <div class=\"cbi-value\">
            <label class=\"cbi-value-title\"> ";echo _("Enabled"); echo "</label>
            <input class=\"cbi-input-radio\" id=\"com_enable$num\" name=\"com_enabled$num\" value=\"1\" type=\"radio\" checked onchange=\"enableCom(true, $num)\">
            <label >";echo _("Enable"); echo "</label>

            <input class=\"cbi-input-radio\" id=\"com_disable$num\" name=\"com_enabled$num\" value=\"0\" type=\"radio\" onchange=\"enableCom(false, $num)\">
            <label >"; echo _("Disable"); echo "</label>
        </div>

        <div id=\"page_com$num\" name=\"page_com$num\">
        <div class=\"cbi-value\">
            <label class=\"cbi-value-title\">"; echo _("Baudrate"); echo "</label>
            <select id=\"baudrate$num\" name=\"baudrate$num\" class=\"cbi-input-select\">
                <option value=\"1200\">1200</option> 
                <option value=\"2400\">2400</option> 
                <option value=\"4800\">4800</option> 
                <option value=\"9600\" selected=\"\">9600</option>
                <option value=\"19200\">19200</option>
                <option value=\"38400\">38400</option>
                <option value=\"57600\">57600</option>
                <option value=\"115200\">115200</option>
                <option value=\"230400\">230400</option>
            </select>
        </div>

        <div class=\"cbi-value\">
            <label class=\"cbi-value-title\">"; echo _("Databit"); echo "</label>
            <select id=\"databit$num\" name=\"databit$num\" class=\"cbi-input-select\">
            <option value=\"7\">7</option>
            <option value=\"8\" selected=\"\">8</option>
            </select>
        </div>

        <div class=\"cbi-value\">
            <label class=\"cbi-value-title\">"; echo _("Stopbit"); echo "</label>
            <select id=\"stopbit$num\" name=\"stopbit$num\" class=\"cbi-input-select\">
                <option value=\"1\" selected=\"\">1</option>
                <option value=\"2\">2</option>
            </select>
        </div>

        <div class=\"cbi-value\">
            <label class=\"cbi-value-title\">"; echo _("Parity"); echo "</label>
            <select id=\"parity$num\" name=\"parity$num\" class=\"cbi-input-select\">
                <option value=\"N\" selected=\"\">None</option>
                <option value=\"O\">Odd</option>
                <option value=\"E\">Even</option>
            </select>
        </div>

        <div class=\"cbi-value\">
            <label class=\"cbi-value-title\">"; echo _("Frame Interval"); echo "</label>
            <input type=\"text\" class=\"cbi-input-text\" name=\"com_frame_interval$num\" id=\"com_frame_interval$num\" value=\"200\" />
            <label class=\"cbi-value-description\">"; echo _("ms"); echo "</label>
        </div>

        <div class=\"cbi-value\">
            <label class=\"cbi-value-title\">"; echo _("Protocol"); echo "</label>
            <select id=\"com_proto$num\" name=\"com_proto$num\" class=\"cbi-input-select\" onchange=\"comProtocolChange($num)\">";
            $i = 0;
            foreach($com_proto as $proto):
                if ($i == 0) {
                    echo "<option value=\"$i\" selected=\"\">$proto</option>";
                } else {
                    echo "<option value=\"$i\">$proto</option>";
                }
                $i++;
            endforeach;
echo "      </select>
        </div>

        <div class=\"cbi-value\" id=\"com_page_protocol_modbus$num\" name=\"com_page_protocol_modbus$num\">
            <label class=\"cbi-value-title\">"; echo _("Command Interval"); echo "</label>
            <input type=\"text\" class=\"cbi-input-text\" name=\"com_cmd_interval$num\" id=\"com_cmd_interval$num\" value=\"2\" />
            <label class=\"cbi-value-description\">"; echo _("ms"); echo "</label>
        </div>

        <div class=\"cbi-value\" id=\"com_page_protocol_transparent$num\" name=\"com_page_protocol_transparent$num\">
            <label class=\"cbi-value-title\">"; echo _("Reporting Center"); echo "</label>
            <input type=\"text\" class=\"cbi-input-text\" name=\"com_report_center$num\" id=\"com_report_center$num\" />
            <label class=\"cbi-value-description\">1-2-3-4-5</label>
        </div>
        </div><!-- /.page_com -->
    </div><!-- /.row -->
    </div><!-- /.tab-pane | basic tab -->
";
}

function page_interface_tcp($num, $tcp_proto) {
  if ($num == 1)
    $active = "active";
  else
    $active = "fade";

echo "<div class=\"tab-pane $active\" id=\"tcp$num\">
        <div class=\"row\">
          <div class=\"cbi-value\">
            <label class=\"cbi-value-title\">"; echo _("Enabled"); echo"</label>
            <input class=\"cbi-input-radio\" id=\"tcp_enable$num\" name=\"tcp_enabled$num\" value=\"1\" type=\"radio\" checked onchange=\"enableTcp(true, $num)\">
            <label >"; echo _("Enable"); echo"</label>

            <input class=\"cbi-input-radio\" id=\"tcp_disable$num\" name=\"tcp_enabled$num\" value=\"0\" type=\"radio\" onchange=\"enableTcp(false, $num)\">
            <label >"; echo _("Disable"); echo"</label>
          </div>

          <div id=\"page_tcp$num\" name=\"page_tcp$num\">
            <div class=\"cbi-value\">
              <label class=\"cbi-value-title\">"; echo _("Server Address"); echo"</label>
              <input type=\"text\" class=\"cbi-input-text\" name=\"server_addr$num\" id=\"server_addr$num\" />
            </div>

            <div class=\"cbi-value\">
              <label class=\"cbi-value-title\">"; echo _("Server Port"); echo"</label>
              <input type=\"text\" class=\"cbi-input-text\" name=\"server_port$num\" id=\"server_port$num\" />
            </div>

            <div class=\"cbi-value\">
              <label class=\"cbi-value-title\">"; echo _("Frame Interval"); echo"</label>
              <input type=\"text\" class=\"cbi-input-text\" name=\"tcp_frame_interval$num\" id=\"tcp_frame_interval$num\" value=\"200\" />
              <label class=\"cbi-value-description\">"; echo _("ms"); echo"</label>
            </div>

            <div class=\"cbi-value\">
              <label class=\"cbi-value-title\">"; echo _("Protocol"); echo"</label>
              <select id=\"tcp_proto$num\" name=\"tcp_proto$num\" class=\"cbi-input-select\" onchange=\"tcpProtocolChange($num)\">";
                 $i = 0;
                foreach($tcp_proto as $proto):
                  if ($i == 0) {
                    echo "<option value=\"$i\" selected=\"\">$proto</option>";
                  } else { 
                    echo "<option value=\"$i\">$proto</option>";
                  }
                  $i++;
                endforeach;
echo         "</select>
            </div>

            <div class=\"cbi-value\" id=\"tcp_page_protocol_modbus$num\" name=\"tcp_page_protocol_modbus$num\">
              <label class=\"cbi-value-title\">"; echo _("Command Interval"); echo"</label>
              <input type=\"text\" class=\"cbi-input-text\" name=\"tcp_cmd_interval$num\" id=\"tcp_cmd_interval$num\" value=\"2\" />
              <label class=\"cbi-value-description\">"; echo _("ms"); echo"</label>
            </div>

            <div class=\"cbi-value\" id=\"tcp_page_protocol_transparent$num\" name=\"tcp_page_protocol_transparent$num\">
              <label class=\"cbi-value-title\">"; echo _("Reporting Center"); echo"</label>
              <input type=\"text\" class=\"cbi-input-text\" name=\"tcp_report_center$num\" id=\"tcp_report_center$num\" />
              <label class=\"cbi-value-description\">1-2-3-4-5</label>
            </div>

            <div id=\"tcp_page_protocol_s7$num\" name=\"tcp_page_protocol_s7$num\">
              <div class=\"cbi-value\">
                <label class=\"cbi-value-title\">"; echo _("Rack"); echo "</label>
                <input type=\"text\" class=\"cbi-input-text\" name=\"rack$num\" id=\"rack$num\" />
              </div>
              <div class=\"cbi-value\">
              <label class=\"cbi-value-title\">"; echo _("Slot"); echo "</label>
              <input type=\"text\" class=\"cbi-input-text\" name=\"slot$num\" id=\"slot$num\" />
              </div>
            </div>

            <div class=\"cbi-value\">
              <label class=\"cbi-value-title\">"; echo _("Connection Status"); echo "</label>";
              $count = $num - 1;
              exec("uci -P /var/state get dct.connection.tcp_status$count", $status);
echo         "<label id=\"connect_status$num\" name=\"connect_status$num\">"; echo _(($status[0] != null) ? $status[0] : "-"); echo "</label>
            </div>
          </div><!-- /.page_tcp -->
        </div><!-- /.row -->
      </div><!-- /.tab-pane | basic tab -->";
}

function page_server($num) {
  if ($num == 1)
    $active = "active";
  else
    $active = "fade";

echo "<div class=\"tab-pane $active\" id=\"server$num\">
        <div class=\"row\">
          <div class=\"cbi-value\">
            <label class=\"cbi-value-title\">"; echo _("Enabled"); echo "</label>
            <input class=\"cbi-input-radio\" id=\"enable$num\" name=\"enabled$num\" value=\"1\" type=\"radio\" checked onchange=\"enableServer(true, $num)\">
            <label >"; echo _("Enable"); echo "</label>

            <input class=\"cbi-input-radio\" id=\"disable$num\" name=\"enabled$num\" value=\"0\" type=\"radio\" onchange=\"enableServer(false, $num)\">
            <label >"; echo _("Disable"); echo "</label>
          </div>

          <div id=\"page_server$num\" name=\"page_server$num\">
            <div class=\"cbi-value\">
              <label class=\"cbi-value-title\">"; echo _("Protocol"); echo "</label>
              <select id=\"proto$num\" name=\"proto$num\" class=\"cbi-input-select\" onchange=\"protocolChange($num)\">
                <option value=\"0\" selected=\"\">TCP</option>
                <option value=\"1\">UDP</option>
                <option value=\"2\">MQTT</option>
                <option value=\"3\">HTTP</option>
                <option value=\"4\">MODBUS TCP</option>
                <option value=\"5\">TCP Server</option>
              </select>
            </div>

            <div name=\"page_encap$num\" id=\"page_encap$num\">
              <div class=\"cbi-value\">
                <label class=\"cbi-value-title\">"; echo _("Encapsulation Type"); echo "</label>
                <select id=\"encap_type$num\" name=\"encap_type$num\" class=\"cbi-input-select\" onchange=\"encapChange($num)\">
                  <option value=\"0\">Transparent</option>
                  <option value=\"1\" selected=\"\">JSON</option>
                  <option value=\"2\">HJ212</option>
                </select>
              </div>
            </div>

            <div name=\"page_addr$num\" id=\"page_addr$num\">
              <div class=\"cbi-value\">
                <label class=\"cbi-value-title\">"; echo _("Server Address"); echo "</label>
                <input type=\"text\" class=\"cbi-input-text\" name=\"server_addr$num\" id=\"server_addr$num\" />
              </div>
            </div>

            <div name=\"page_url$num\" id=\"page_url$num\">
              <div class=\"cbi-value\">
                <label class=\"cbi-value-title\">"; echo _("Http URL"); echo "</label>
                <input type=\"text\" class=\"cbi-input-text\" name=\"http_url$num\" id=\"http_url$num\" />
              </div>
            </div>

            <div name=\"page_port$num\" id=\"page_port$num\">
              <div class=\"cbi-value\">
                <label class=\"cbi-value-title\">"; echo _("Server Port"); echo "</label>
                <input type=\"text\" class=\"cbi-input-text\" name=\"server_port$num\" id=\"server_port$num\" />
              </div>
            </div>";

            exec("sudo uci get dct.basic.enabled", $has_cached); 
            exec("sudo uci get dct.basic.cache_enabled", $cache_enabled);

            if ($has_cached[0] == '1' && $cache_enabled[0] == '1') {
              echo "<div class=\"cbi-value\" name=\"page_cache$num\" id=\"page_cache$num\">
                      <label class=\"cbi-value-title\">"; echo _("Enable Cache"); echo "</label>
                      <input type=\"checkbox\" class=\"cbi-input-checkbox\" name=\"cache_enabled$num\" id=\"cache_enabled$num\" value=\"1\"/>
                      <label class=\"cbi-value-description\">"; echo _("Cache When Fails To Send"); echo "</label>
                    </div>";
            }
                
    echo   "<div name=\"page_tcp$num\" id=\"page_tcp$num\">
              <div class=\"cbi-value\">
                <label class=\"cbi-value-title\">"; echo _("Register Packet"); echo "</label>
                <input type=\"text\" class=\"cbi-input-text\" name=\"register_packet$num\" id=\"register_packet$num\" />
                <label class=\"cbi-value-description\">"; echo _("Max 128 Bytes ASCII"); echo "</label>
              </div>

              <div class=\"cbi-value\">
                <label class=\"cbi-value-title\">"; echo _("Use HEX Format"); echo "</label>
                <input type=\"checkbox\" class=\"cbi-input-checkbox\" name=\"register_packet_hex$num\" id=\"register_packet_hex$num\" value=\"1\"/>
                <label class=\"cbi-value-description\">"; echo _("Default is ASCII"); echo "</label>
              </div>

              <div class=\"cbi-value\">
                <label class=\"cbi-value-title\">"; echo _("Heartbeat Packet"); echo "</label>
                <input type=\"text\" class=\"cbi-input-text\" name=\"heartbeat_packet$num\" id=\"heartbeat_packet$num\" />
                <label class=\"cbi-value-description\">"; echo _("Max 128 Bytes ASCII"); echo "</label>
              </div>

              <div class=\"cbi-value\">
                <label class=\"cbi-value-title\">"; echo _("Use HEX Format"); echo "</label>
                <input type=\"checkbox\" class=\"cbi-input-checkbox\" name=\"heartbeat_packet_hex$num\" id=\"heartbeat_packet_hex$num\" value=\"1\"/>
                <label class=\"cbi-value-description\">"; echo _("Default is ASCII"); echo "</label>
              </div>

              <div class=\"cbi-value\">
                <label class=\"cbi-value-title\">"; echo _("Heartbeat Interval"); echo "</label>
                <input type=\"text\" class=\"cbi-input-text\" name=\"heartbeat_interval$num\" id=\"heartbeat_interval$num\" />
                <label class=\"cbi-value-description\">"; echo _("Seconds, 0 means No Heartbeat"); echo "</label>
              </div>
            </div>  

            <div name=\"page_mqtt$num\" id=\"page_mqtt$num\">
              <div class=\"cbi-value\">
                <label class=\"cbi-value-title\">"; echo _("Heartbeat Interval"); echo "</label>
                <input type=\"text\" class=\"cbi-input-text\" name=\"mqtt_heartbeat_interval$num\" id=\"mqtt_heartbeat_interval$num\" />
                <label class=\"cbi-value-description\">"; echo _("Seconds, 0 means Default Heartbeat"); echo "</label>
              </div>

              <div class=\"cbi-value\">
                <label class=\"cbi-value-title\">"; echo _("MQTT Public Topic"); echo "</label>
                <input type=\"text\" class=\"cbi-input-text\" name=\"mqtt_pub_topic$num\" id=\"mqtt_pub_topic$num\" />
              </div>

              <div class=\"cbi-value\">
                <label class=\"cbi-value-title\">"; echo _("MQTT Subscribe Topic"); echo "</label>
                <input type=\"text\" class=\"cbi-input-text\" name=\"mqtt_sub_topic$num\" id=\"mqtt_sub_topic$num\" />
              </div>

              <div class=\"cbi-value\">
                <label class=\"cbi-value-title\">"; echo _("MQTT Username"); echo "</label>
                <input type=\"text\" class=\"cbi-input-text\" name=\"mqtt_username$num\" id=\"mqtt_username$num\" />
              </div>

              <div class=\"cbi-value\">
                <label class=\"cbi-value-title\">"; echo _("MQTT Password"); echo "</label>
                <input type=\"text\" class=\"cbi-input-text\" name=\"mqtt_password$num\" id=\"mqtt_password$num\" />
              </div>

              <div class=\"cbi-value\">
                <label class=\"cbi-value-title\">"; echo _("Client ID"); echo "</label>
                <input type=\"text\" class=\"cbi-input-text\" name=\"mqtt_client_id$num\" id=\"mqtt_client_id$num\" />
              </div>

              <div class=\"cbi-value\">
                <label class=\"cbi-value-title\">"; echo _("Enable TLS/SSL"); echo "</label>
                <input type=\"checkbox\" class=\"cbi-input-checkbox\" name=\"mqtt_tls_enabled$num\" id=\"mqtt_tls_enabled$num\" onchange=\"enableTls($num)\" value=\"1\"/>
              </div>

              <div name=\"page_mqtt_tls$num\" id=\"page_mqtt_tls$num\">
                <div class=\"cbi-value\">
                  <label class=\"cbi-value-title\">"; echo _("Certificate Type"); echo "</label>
                  <select id=\"certificate_type$num\" name=\"certificate_type$num\" class=\"cbi-input-select\" onchange=\"cerChange($num)\">
                    <option value=\"0\">CA signed server certificate</option>
                    <option value=\"1\">One-way certification</option>
                    <option value=\"2\">Two-way certification</option>
                  </select>
                </div>

                <div name=\"page_one$num\" id=\"page_one$num\">
                  <div class=\"cbi-value\">
                    <label class=\"cbi-value-title\">"; echo _("CA"); echo "</label>
                    <label for=\"mqtt_ca$num\" class=\"cbi-file-lable\">
                        <input type=\"button\" class=\"cbi-file-btn\" id=\"ca_btn$num\" value=\""; echo _("Choose file"); echo "\">
                        <span id=\"ca_text$num\">"; echo _("No file chosen"); echo "</span>
                        <input type=\"file\" class=\"cbi-file\" name=\"mqtt_ca$num\" id=\"mqtt_ca$num\" onchange=\"caFileChange($num)\">
                    </label>
                  </div>
                </div>

                <div name=\"page_two$num\" id=\"page_two$num\">
                  <div class=\"cbi-value\">
                    <label class=\"cbi-value-title\">"; echo _("Public Certificate"); echo "</label>
                    <label class=\"cbi-file-lable\" for=\"mqtt_cert$num\">
                        <input type=\"button\" class=\"cbi-file-btn\" id=\"cer_btn$num\" value=\""; echo _("Choose file"); echo "\">
                        <span id=\"cer_text$num\">"; echo _("No file chosen"); echo "</span>
                        <input type=\"file\" class=\"cbi-file\" name=\"mqtt_cert$num\" id=\"mqtt_cert$num\"  onchange=\"cerFileChange($num)\">
                    </label>
                  </div>
                  <div class=\"cbi-value\">
                    <label class=\"cbi-value-title\">"; echo _("Private Key"); echo "</label>
                    <label class=\"cbi-file-lable\" for=\"mqtt_key$num\">
                        <input type=\"button\" class=\"cbi-file-btn\" id=\"key_btn$num\" value=\""; echo _("Choose file"); echo "\">
                        <span id=\"key_text$num\">"; echo _("No file chosen"); echo "</span>
                        <input type=\"file\" class=\"cbi-file\" name=\"mqtt_key$num\" id=\"mqtt_key$num\" onchange=\"keyFileChange($num)\">
                    </label>
                  </div>
                </div>
              </div>
            </div>

            <div name=\"page_json$num\" id=\"page_json$num\">
              <div class=\"cbi-value\">
                <label class=\"cbi-value-title\">"; echo _("Enable Self Defined Variable"); echo "</label>
                <input type=\"checkbox\" class=\"cbi-input-checkbox\" name=\"self_define_var$num\" id=\"self_define_var$num\" onchange=\"enableVar($num)\" value=\"$num\"/>
              </div>

              <div name=\"page_var$num\" id=\"page_var$num\">
                <div class=\"cbi-value\">
                  <label class=\"cbi-value-title\">"; echo _("Variable Name1"); echo "</label>
                  <input type=\"text\" class=\"cbi-input-text\" name=\"var_name1_$num\" id=\"var_name1_$num\" />
                </div>

                <div class=\"cbi-value\">
                  <label class=\"cbi-value-title\">"; echo _("Variable Value1"); echo "</label>
                  <input type=\"text\" class=\"cbi-input-text\" name=\"var_value1_$num\" id=\"var_value1_$num\" />
                </div>

                <div class=\"cbi-value\">
                  <label class=\"cbi-value-title\">"; echo _("Variable Name2"); echo "</label>
                  <input type=\"text\" class=\"cbi-input-text\" name=\"var_name2_$num\" id=\"var_name2_$num\" />
                </div>

                <div class=\"cbi-value\">
                  <label class=\"cbi-value-title\">"; echo _("Variable Value2"); echo "</label>
                  <input type=\"text\" class=\"cbi-input-text\" name=\"var_value2_$num\" id=\"var_value2_$num\" />
                </div>

                <div class=\"cbi-value\">
                  <label class=\"cbi-value-title\">"; echo _("Variable Name3"); echo "</label>
                  <input type=\"text\" class=\"cbi-input-text\" name=\"var_name3_$num\" id=\"var_name3_$num\" />
                </div>

                <div class=\"cbi-value\">
                  <label class=\"cbi-value-title\">"; echo _("Variable Value3"); echo "</label>
                  <input type=\"text\" class=\"cbi-input-text\" name=\"var_value3_$num\" id=\"var_value3_$num\" />
                </div>
              </div><!-- /.page_var1 -->
            </div><!-- /.page_json1 -->

            <div name=\"page_hj212_$num\" id=\"page_hj212_$num\">
              <div class=\"cbi-value\">
                <label class=\"cbi-value-title\">"; echo _("MN"); echo "</label>
                <input type=\"text\" class=\"cbi-input-text\" name=\"mn$num\" id=\"mn$num\" />
              </div>

              <div class=\"cbi-value\">
                <label class=\"cbi-value-title\">"; echo _("ST"); echo "</label>
                <input type=\"text\" class=\"cbi-input-text\" name=\"st$num\" id=\"st$num\" />
                <label class=\"cbi-value-description\">"; echo _("2 Bytes Length"); echo "</label>
              </div>

              <div class=\"cbi-value\">
                <label class=\"cbi-value-title\">"; echo _("Password"); echo "</label>
                <input type=\"text\" class=\"cbi-input-text\" name=\"password$num\" id=\"password$num\" />
                <label class=\"cbi-value-description\">"; echo _("6 Bytes Length"); echo "</label>
              </div>

            </div><!-- /.page_hj212_1 -->
            <div name=\"page_status$num\" id=\"page_status$num\">
              <div class=\"cbi-value\">
                <label class=\"cbi-value-title\">"; echo _("Connection Status"); echo "</label>";
                $count = $num - 1;
                exec("uci -P /var/state get dct.connection.status$count", $status);
                echo "<label id=\"connect_status$num\" name=\"connect_status$num\">"; echo _(($status[0] != null) ? $status[0] : "-"); echo "</label>
              </div>
            </div>
          </div><!-- /.page_server -->
        </div><!-- /.row -->
      </div><!-- /.tab-pane | basic tab -->";
}