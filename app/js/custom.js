function msgShow(retcode,msg) {
    if(retcode == 0) { var alertType = 'success';
    } else if(retcode == 2 || retcode == 1) {
        var alertType = 'danger';
    }
    var htmlMsg = '<div class="alert alert-'+alertType+' alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+msg+'</div>';
    return htmlMsg;
}

function createNetmaskAddr(bitCount) {
  var mask=[];
  for(i=0;i<4;i++) {
    var n = Math.min(bitCount, 8);
    mask.push(256 - Math.pow(2, 8-n));
    bitCount -= n;
  }
  return mask.join('.');
}

function loadSummary(strInterface) {
    $.post('ajax/networking/get_ip_summary.php',{interface:strInterface},function(data){
        jsonData = JSON.parse(data);
        console.log(jsonData);
        if(jsonData['return'] == 0) {
            $('#'+strInterface+'-summary').html(jsonData['output'].join('<br />'));
        } else if(jsonData['return'] == 2) {
            $('#'+strInterface+'-summary').append('<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+jsonData['output'].join('<br />')+'</div>');
        }
    });
}

function getAllInterfaces() {
    $.get('ajax/networking/get_all_interfaces.php',function(data){
        jsonData = JSON.parse(data);
        $.each(jsonData,function(ind,value){
            loadSummary(value)
        });
    });
}

function setupTabs() {
    $('a[data-toggle="tab"]').on('shown.bs.tab',function(e){
        var target = $(e.target).attr('href');
        if(!target.match('summary')) {
            var int = target.replace("#","");
            loadCurrentSettings(int);
        }
    });
}

$(document).on("click", ".js-add-dhcp-static-lease", function(e) {
    e.preventDefault();
    var container = $(".js-new-dhcp-static-lease");
    var mac = $("input[name=mac]", container).val().trim();
    var ip  = $("input[name=ip]", container).val().trim();
    var comment = $("input[name=comment]", container).val().trim();
    if (mac == "" || ip == "") {
        return;
    }
    var row = $("#js-dhcp-static-lease-row").html()
        .replace("{{ mac }}", mac)
        .replace("{{ ip }}", ip)
        .replace("{{ comment }}", comment);
    $(".js-dhcp-static-lease-container").append(row);

    $("input[name=mac]", container).val("");
    $("input[name=ip]", container).val("");
    $("input[name=comment]", container).val("");
});

$(document).on("click", ".js-remove-dhcp-static-lease", function(e) {
    e.preventDefault();
    $(this).parents(".js-dhcp-static-lease-row").remove();
});

$(document).on("submit", ".js-dhcp-settings-form", function(e) {
    $(".js-add-dhcp-static-lease").trigger("click");
});

$(document).on("click", ".js-add-dhcp-upstream-server", function(e) {
    e.preventDefault();

    var field = $("#add-dhcp-upstream-server-field")
    var row = $("#dhcp-upstream-server").html().replace("{{ server }}", field.val())

    if (field.val().trim() == "") { return }

    $(".js-dhcp-upstream-servers").append(row)

    field.val("")
});

$(document).on("click", ".js-remove-dhcp-upstream-server", function(e) {
    e.preventDefault();
    $(this).parents(".js-dhcp-upstream-server").remove();
});

$(document).on("submit", ".js-dhcp-settings-form", function(e) {
    $(".js-add-dhcp-upstream-server").trigger("click");
});

/**
 * mark a form field, e.g. a select box, with the class `.js-field-preset`
 * and give it an attribute `data-field-preset-target` with a text field's
 * css selector.
 *
 * now, if the element marked `.js-field-preset` receives a `change` event,
 * its value will be copied to all elements matching the selector in
 * data-field-preset-target.
 */
$(document).on("change", ".js-field-preset", function(e) {
    var selector = this.getAttribute("data-field-preset-target")
    var value = "" + this.value
    var syncValue = function(el) { el.value = value }

    if (value.trim() === "") { return }

    document.querySelectorAll(selector).forEach(syncValue)
});

$(document).on("click", "#gen_wpa_passphrase", function(e) {
    $('#txtwpapassphrase').val(genPassword(63));
});

// Enable Bootstrap tooltips
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})

function genPassword(pwdLen) {
    var pwdChars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
    var rndPass = Array(pwdLen).fill(pwdChars).map(function(x) { return x[Math.floor(Math.random() * x.length)] }).join('');
    return rndPass;
}

function setupBtns() {
    $('#btnSummaryRefresh').click(function(){getAllInterfaces();});
    $('.intsave').click(function(){
        var int = $(this).data('int');
        saveNetworkSettings(int);
    });
    $('.intapply').click(function(){
        applyNetworkSettings();
    });
}

function setCSRFTokenHeader(event, xhr, settings) {
    var csrfToken = $('meta[name=csrf_token]').attr('content');
    if (/^(POST|PATCH|PUT|DELETE)$/i.test(settings.type)) {
        xhr.setRequestHeader("X-CSRF-Token", csrfToken);
    }
}

function contentLoaded() {
    pageCurrent = window.location.href.split("/").pop();
    switch(pageCurrent) {
        case "network_conf":
            //getAllInterfaces();
            //setupTabs();
            //setupBtns();
            loadInterfaceWiredSelect();
            break;
        case "hostapd_conf":
            loadChannel();
            break;
        case "dhcpd_conf":
            loadInterfaceDHCPSelect();
            break;
        case "basic_conf":
            loadBasicConfig();
            break;
        case "interfaces_conf":
            loadInterfacesConfig();
            break;
        case "modbus_conf":
            loadModbusConfig();
            break;
        case "s7_conf":
            loadS7Config();
            break;
		case "fx_conf":
            loadFxConfig();
            break;
        case "io_conf":
            loadADCConfig();
            loadDIConfig();
            loadDOConfig();
            break;
        case "server_conf":
            loadServerConfig();
            break;
        case "ddns":
            loadDDNSConfig();
            break;
        case "opcua":
            loadOpcuaConfig();
            break;
        case "bacnet":
            loadBACnetConfig();
            break;
        case "datadisplay":
            loadDataDisplay();
            break;
        case "lorawan_conf":
            loadDataLorawan();
            break;
        case "openvpn":
            loadOpenvpn();
            break;
        case "wireguard":
            loadWireguard();
            break;
        case "gps":
            loadGps();
            break;
    }
}

function loadGps() {
    $.get('ajax/service/get_service.php?type=gps', function(data) {
        //console.log(data);
        jsonData = JSON.parse(data);
        var arr = ['output_mode', 'server_addr', 'server_port', 'report_mode', 'register_packet',
        'heartbeat_packet', 'report_interval', 'heartbeat_interval', 'baudrate', 'databit', 'stopbit',
        'parity'];

        $('#enabled').val(jsonData.enabled);
        if (jsonData.enabled == '1') {
            for(var key in jsonData){ 
                if (key == null) {
                    return true;    // continue: return true; break: return false
                }
                $('#' + key).val(jsonData[key]); 
            }

            $('#page_gps').show();
            if (jsonData['output_mode'] == 'network') {
                $('#gps_network').show();
                $('#tcp_status').show();
                $('#gps_serial').hide();
                $('#gps_report').show();
            } else if (jsonData['output_mode'] == 'serial') {
                $('#gps_network').hide();
                $('#tcp_status').hide();
                $('#gps_serial').show();
                $('#gps_report').show();
            } else {
                $('#gps_network').hide();
                $('#tcp_status').hide();
                $('#gps_serial').hide();
                $('#gps_report').hide();
            }
        } else {
            $('#page_gps').hide();
        }
    })
}

function loadWireguard() {
    $.get('ajax/networking/get_wgcfg.php?type=settings', function(data) {
        //console.log(data);
        jsonData = JSON.parse(data);
        if (jsonData['type'] != 'off') {
            if (jsonData['type'] == 'config') {
                $('#page_config').show();
                $('#page_role').show();
                $('#page_wg').hide();
            } else {
                $('#page_config').hide();
                $('#page_role').hide();
                $('#page_wg').show();
            }

            if (jsonData['role'] == 'client') {
                $('#page_client').show();
                $('#page_server').hide();
            } else {
                $('#page_client').hide();
                $('#page_server').show();
            }
        } else {
            $('#page_role').hide();
            $('#page_config').hide();
            $('#page_wg').hide();
        }

        for(var key in jsonData){ 
            if (key == null) {
                return true;    // continue: return true; break: return false
            }
            //console.log(key + ":" + jsonData[key]);
            if (key == 'wg') {
                $('#' + key + '_text').html(jsonData[key]); 
            } else {
                $('#' + key).val(jsonData[key]); 
            }
        }
        
    });
}

function getOpenvpnStatus() {
    $.get('ajax/openvpn/get_openvpnstatus.php', function(data) {
        //console.log(data);
        jsonData = JSON.parse(data);
        for(var key in jsonData){ 
            if (key == null) {
                return true;    // continue: return true; break: return false
            }
            //console.log(key + ":" + jsonData[key]);

            $('#' + key).html(jsonData[key]); 
        }
    });
}

function loadOpenvpn() {
    getOpenvpnStatus();
    setInterval(getOpenvpnStatus, 60000);
    $.get('ajax/openvpn/get_openvpncfg.php', function(data) {
        //console.log(data);
        jsonData = JSON.parse(data);
        if (jsonData['type'] != 'off') {
            $('#page_role').show();
            if (jsonData['type'] == 'config') {
                $('#page_config').show();
                $('#page_ovpn').hide();
            } else {
                $('#page_config').hide();
                $('#page_ovpn').show();
            }

            if (jsonData['role'] == 'client') {
                $('#page_client').show();
                $('#page_server').hide();
            } else {
                $('#page_client').hide();
                $('#page_server').show();
            }

            if (jsonData['auth_type'] == 'cert') {
                $('#page_cert').show();
                $('#page_user_pass').hide();
                if (jsonData['role'] == 'server') {
                    $('#page_dh').show();
                } else {
                    $('#page_dh').hide();
                }
            } else {
                $('#page_user_pass').show();
                if (jsonData['role'] == 'server') {
                    $('#page_cert').show();
                    $('#page_dh').show();
                } else {
                    $('#page_cert').hide();
                    $('#page_dh').hide();
                }
            }

            for(var key in jsonData){ 
                if (key == null) {
                    return true;    // continue: return true; break: return false
                }
                //console.log(key + ":" + jsonData[key]);
                if (key == 'ca' || key == 'ta' || key == 'cert' || key == 'key' || key == 'ovpn' || key == 'dh') {
                    $('#' + key + '_text').html(jsonData[key]); 
                } else {
                    $('#' + key).val(jsonData[key]); 
                }
            }
        } else {
            $('#page_role').hide();
            $('#page_config').hide();
            $('#page_ovpn').hide();
        }
        
    });
}

function loadDataLorawan(){
    $.get('ajax/networking/get_loragw.php?type=lorawan', function(data) {
        jsonData = JSON.parse(data);
        var general = ['server_address', 'serv_port_up', 'serv_port_down', 'gateway_ID',
        'keepalive_interval', 'stat_interval'];

        if (jsonData['type'] == '1') {
            $('#type').val('1');
        } else {
            $('#type').val('0');
        }

        general.forEach(function (info) {
            if (info == null) {
                return true;    // continue: return true; break: return false
            }

            $('#' + info).val(jsonData[info]);
        })

        var radio = ['radio0_enable', 'radio0_frequency', 'radio0_tx', 'radio0_tx_min', 'radio0_tx_max',
        'radio1_enable', 'radio1_frequency', 'radio1_tx'];

        radio.forEach(function (info) {
            if (info == null) {
                return true;    // continue: return true; break: return false
            }

            if (info == 'radio0_enable' || info == 'radio0_tx' || info == 'radio1_enable' ||
                info == 'radio1_tx') {
                $('#' + info).prop('checked', (jsonData[info] == '1') ? true:false);
            } else {
                $('#' + info).val(jsonData[info]);
            }
        })

        if (jsonData['radio0_enable'] == '1') {
            $('#page_radio0').show();
        } else {
            $('#page_radio0').hide();
        }

        if (jsonData['radio0_tx'] == '1') {
            $('#page_radio0_tx').show();
        } else {
            $('#page_radio0_tx').hide();
        }

        if (jsonData['radio1_enable'] == '1') {
            $('#page_radio1').show();
        } else {
            $('#page_radio1').hide();
        }

        var channels = ['channel_enable', 'channel_radio', 'channel_if'];
        for (var i = 0; i < 8; i++) {
            channels.forEach(function (info) {
                if (info == null) {
                    return true;    // continue: return true; break: return false
                }

                if (info == 'channel_enable') {
                    $('#' + info + i).prop('checked', (jsonData[info + i] == '1') ? true:false);
                } else {
                    $('#' + info + i).val(jsonData[info + i]);
                }
            })
        }
    });
}

function getWebshowDate() {
    $.get('ajax/dct/get_dctcfg.php?type=datadisplay', function(data) {
        jsonData = JSON.parse(data);
        var num = 0;
        var table = document.getElementsByTagName("table")[0]; 
        var data = [];
        var flag = 0;
        if ($('table tr').length) {
            for (var key in jsonData) {
                $('#' + key ).html(jsonData[key]);
                num++;
            }
        } else {
            for (var key in jsonData) {
                //console.log(key + ":" + jsonData[key]);
                if ((num % 4) == 0) {
                    data += "<tr class=\"tr cbi-section-table-descr\" style='border:0;'>\n"
                }

                data += "<td style='border:0'>\n";
                data += "<label class='table-label-key' id=" + key + "1 >" + key + ":" + "</label>\n";
                data += "<label class='table-label-value' id=" + key + " >" + jsonData[key] + "</label>\n";
                data += "</td>\n";

                num++;
                if ( num > 0 && ((num % 4) == 0)) {
                    flag = 1;
                    data += "</tr>\n";
                    flag = 0;
                }
            }

            if (flag == 0 && num > 0) {
                data += "</tr>\n";
            }

            table.innerHTML += data;
            if (num > 0) {
                $('#msg').hide();
            } else {
                $('#msg').html("Data collection in progress, please check later...");
            }
        }
    });
}

function loadDataDisplay() {
    getWebshowDate();
    setInterval(getWebshowDate, 1000);
}

function loadBACnetConfig() {
    $.get('ajax/dct/get_dctcfg.php?type=bacnet',function(data){
        jsonData = JSON.parse(data);
        var arr = ['port', 'device_id', 'object_name'];

        $('#enabled').val(jsonData.enabled);
        if (jsonData.enabled == '1') {
            $('#page_bacnet').show();
            $('#bacnet_enable').prop('checked', true);

            arr.forEach(function (info) {
                if (info == null) {
                    return true;    // continue: return true; break: return false
                }

                $('#' + info).val(jsonData[info]);
            })
        } else {
            $('#page_bacnet').hide();
            $('#bacnet_disable').prop('checked', true);
        }
    });
}

function loadOpcuaConfig() {
    $.get('ajax/dct/get_dctcfg.php?type=opcua',function(data){
        jsonData = JSON.parse(data);
        var arr = ['port', 'anonymous', 'username', 'password', 'security_policy',
        'certificate', 'private_key'];

        $('#enabled').val(jsonData.enabled);
        if (jsonData.enabled == '1') {
            $('#page_opcua').show();
            $('#opcua_enable').prop('checked', true);

            arr.forEach(function (info) {
                if (info == null) {
                    return true;    // continue: return true; break: return false
                }

                if (info == "anonymous") {
                    $('#' + info).prop('checked', (jsonData[info] == '1') ? true:false);
                } else if (info == "certificate") {
                    if (jsonData[info]) {
                        $('#cert_text').html(jsonData[info]);
                    }
                } else if (info == "private_key") {
                    if (jsonData[info]) {
                        $('#key_text').html(jsonData[info]);
                    }
                }  else {
                    $('#' + info).val(jsonData[info]);
                }
            })
        } else {
            $('#page_opcua').hide();
            $('#opcua_disable').prop('checked', true);
        }

        if (jsonData['anonymous'] != '1') {
            $('#page_anonymous').show();
        } else {
            $('#page_anonymous').hide();
        }

        if (jsonData['security_policy'] == '0') {
            $('#page_security').hide();
        } else {
            $('#page_security').show();
        }
    });
}

function loadDDNSConfig() {
    $.get('ajax/networking/get_ddnscfg.php?type=ddns',function(data){
        jsonData = JSON.parse(data);
        var arr = ['interface', 'server_type', 'username', 'password', 'hostname', 'interval'];

        $('#enabled').val(jsonData.enabled);
        if (jsonData.enabled == '1') {
            $('#page_ddns').show();
            $('#ddns_enable').prop('checked', true);

            arr.forEach(function (info) {
                if (info == null) {
                    return true;    // continue: return true; break: return false
                }
                
                $('#' + info).val(jsonData[info]);
            })
        } else {
            $('#page_ddns').hide(); 
            $('#ddns_disable').prop('checked', true);
        }
    });
}

function loadWifiStations(refresh) {
    return function() {
        var complete = function() { $(this).removeClass('loading-spinner'); }
        var qs = refresh === true ? '?refresh' : '';
        $('.js-wifi-stations')
            .addClass('loading-spinner')
            .empty()
            .load('ajax/networking/wifi_stations.php'+qs, complete);
    };
}
$(".js-reload-wifi-stations").on("click", loadWifiStations(true));

/*
Populates the wired network form fields
Option toggles are set dynamically depending on the loaded configuration
*/
function loadInterfaceWiredSelect() {
    var strInterface = $('#cbxdhcpiface').val();
    $.get('ajax/networking/get_netcfg.php?iface='+strInterface,function(data){
        jsonData = JSON.parse(data);
        $('#txtipaddress').val(jsonData.StaticIP);
        $('#txtsubnetmask').val(jsonData.SubnetMask);
        $('#txtgateway').val(jsonData.StaticRouters);
        $('#default-route').prop('checked', jsonData.DefaultRoute);
        $('#txtdns1').val(jsonData.StaticDNS1);
        $('#txtdns2').val(jsonData.StaticDNS2);
        $('#txtmetric').val(jsonData.Metric);
        $('#txtapn').val(jsonData.Apn);
        $('#txtpin').val(jsonData.Pin);
        $('#txtusername').val(jsonData.ApnUser);
        $('#txtpassword').val(jsonData.ApnPass);
        $('#auth_type').val(jsonData.AuthType);
        $('#wan-multi').prop('checked', (jsonData.wan_multi == '1') ? true : false);
        $('#lte_metric').val(jsonData.lte_metric);

        if (jsonData.StaticIP !== null && jsonData.StaticIP !== '') {
            $('#chkstatic').closest('.btn').button('toggle');
            $('#chkstatic').closest('.btn').button('toggle').blur();
            $('#chkstatic').blur();
            $('#chkfallback').prop('disabled', true);
            $('#static_ip').show(); 
        } else {
            $('#chkdhcp').closest('.btn').button('toggle');
            $('#chkdhcp').closest('.btn').button('toggle').blur();
            $('#chkdhcp').blur();
            $('#chkfallback').prop('disabled', false);
            $('#static_ip').hide();
        }

        if (jsonData.AuthType == 'none') {
            $('#username').hide();
            $('#password').hide();
        } else {
            $('#username').show();
            $('#password').show();
        }
    });
}

/*
Populates the DHCP server form fields
Option toggles are set dynamically depending on the loaded configuration
*/
function loadInterfaceDHCPSelect() {
    var strInterface = $('#cbxdhcpiface').val();
    $.get('ajax/networking/get_netcfg.php?iface='+strInterface,function(data){
        jsonData = JSON.parse(data);
        $('#dhcp-iface')[0].checked = jsonData.DHCPEnabled;
        $('#txtipaddress').val(jsonData.StaticIP);
        $('#txtsubnetmask').val(jsonData.SubnetMask);
        $('#txtgateway').val(jsonData.StaticRouters);
        // $('#chkfallback')[0].checked = jsonData.FallbackEnabled;
        $('#default-route').prop('checked', jsonData.DefaultRoute);
        $('#txtrangestart').val(jsonData.RangeStart);
        $('#txtrangeend').val(jsonData.RangeEnd);
        $('#txtrangeleasetime').val(jsonData.leaseTime);
        $('#txtdns1').val(jsonData.DNS1);
        $('#txtdns2').val(jsonData.DNS2);
        $('#cbxrangeleasetimeunits').val(jsonData.leaseTimeInterval);
        $('#no-resolv')[0].checked = jsonData.upstreamServersEnabled;
        $('#cbxdhcpupstreamserver').val(jsonData.upstreamServers[0]);
        $('#txtmetric').val(jsonData.Metric);

        // if (jsonData.StaticIP !== null && jsonData.StaticIP !== '' && !jsonData.FallbackEnabled) {
        //     $('#chkstatic').closest('.btn').button('toggle');
        //     $('#chkstatic').closest('.btn').button('toggle').blur();
        //     $('#chkstatic').blur();
        //     $('#chkfallback').prop('disabled', true);
        // } else {
        //     $('#chkdhcp').closest('.btn').button('toggle');
        //     $('#chkdhcp').closest('.btn').button('toggle').blur();
        //     $('#chkdhcp').blur();
        //     $('#chkfallback').prop('disabled', false);
        // }
        // if (jsonData.FallbackEnabled || $('#chkdhcp').is(':checked')) {
        //     $('#dhcp-iface').prop('disabled', true);
        // }
    });
}

function setDHCPToggles(state) {
    if ($('#chkfallback').is(':checked') && state) {
        $('#chkfallback').prop('checked', state);
    }
    if ($('#dhcp-iface').is(':checked') && !state) {
        $('#dhcp-iface').prop('checked', state);
    }

    $('#chkfallback').prop('disabled', state);
    $('#dhcp-iface').prop('disabled', !state);
    //$('#dhcp-iface').prop('checked', state);
}

function loadChannel() {
    $.get('ajax/networking/get_channel.php',function(data){
        jsonData = JSON.parse(data);
        loadChannelSelect(jsonData);
    });
}

function loadBasicConfig() {
    $('#loading').show();
    $.get('ajax/dct/get_dctcfg.php?type=basic',function(data){
        jsonData = JSON.parse(data);
        var arr = ['collect_period', 'report_period', 'cache_enabled', 'cache_day', 'minute_enabled',
        'minute_period', 'hour_enabled', 'day_enabled'];

        $('#enabled').val(jsonData.enabled);
        if (jsonData.enabled == '1') {
            $('#page_basic').show();
            $('#basic_enable').prop('checked', true);

            arr.forEach(function (info) {
                if (info == null) {
                    return true;    // continue: return true; break: return false
                }
    
                if (info == 'cache_enabled' || info == 'minute_enabled' || info == 'hour_enabled' || 
                    info == 'day_enabled') {
                    $('#' + info).prop('checked', (jsonData[info] == '1') ? true:false);
                } else {
                    $('#' + info).val(jsonData[info]);
                }
            })
            
            if (jsonData.cache_enabled == '1') {
                $('#page_cache_days').show();
            } else {
                $('#page_cache_days').hide();
            }

            if (jsonData.minute_enabled == '1') {
                $('#page_minute_data').show();
            } else {
                $('#page_minute_data').hide();
            }
        } else {
            $('#page_basic').hide(); 
            $('#basic_disable').prop('checked', true);
        }

        $('#loading').hide();
    });
}

function loadInterfacesConfig() {
    $('#loading').show();
    $.get('ajax/dct/get_dctcfg.php?type=interfaces',function(data){
        jsonData = JSON.parse(data);
        var arrCom = ['baudrate', 'databit', 'stopbit', 'parity', 'com_frame_interval',
                    'com_proto', 'com_cmd_interval', 'com_report_center'];

        for (var i = 1; i <= 4; i++) {
            $('#com_enabled' + i).val(jsonData.com_enabled[i]);
            if (jsonData.com_enabled[i] == '1') {
                $('#page_com' + i).show();
                $('#com_enable' + i).prop('checked', true);

                arrCom.forEach(function (info) {
                    if (jsonData[info][i] == null) {
                        return true;    // continue: return true; break: return false
                    }

                    $('#' + info + i).val(jsonData[info][i]);
                })

                if (jsonData.com_proto[i] == '1') {
                    //$('#com_report_center' + i).val(jsonData.com_report_center[i]);
                    $('#com_page_protocol_modbus' + i).hide();
                    $('#com_page_protocol_transparent' + i).show();
                } else {
                    //$('#com_cmd_interval' + i).val(jsonData.com_cmd_interval[i]);
                    $('#com_page_protocol_modbus' + i).show();
                    $('#com_page_protocol_transparent' + i).hide();
                }
            } else {
                $('#page_com' + i).hide(); 
                $('#com_disable' + i).prop('checked', true);
            }
        }

        var arrTcp = ['server_addr', 'server_port', 'tcp_frame_interval', 'tcp_proto', 'tcp_cmd_interval', 
                    'tcp_report_center', 'rack', 'slot'];

        for (var i = 1; i <= 5; i++) {
            $('#tcp_enabled' + i).val(jsonData.tcp_enabled[i]);
            if (jsonData.tcp_enabled[i] == '1') {
                $('#page_tcp' + i).show();
                $('#tcp_enable' + i).prop('checked', true);

                arrTcp.forEach(function (info) {
                    if (jsonData[info][i] == null) {
                        return true;    // continue: return true; break: return false
                    }

                    $('#' + info + i).val(jsonData[info][i]);
                })

                if (jsonData.tcp_proto[i] == '2') {
                    //$('#rack' + i).val(jsonData.rack[i]);
                    //$('#slot' + i).val(jsonData.slot[i]);
                    $('#tcp_page_protocol_modbus' + i).hide();
                    $('#tcp_page_protocol_transparent' + i).hide(); 
                    $('#tcp_page_protocol_s7' + i).show(); 
                } else if (jsonData.tcp_proto[i] == '1') {
                    //$('#tcp_report_center' + i).val(jsonData.tcp_report_center[i]);
                    $('#tcp_page_protocol_modbus' + i).hide();
                    $('#tcp_page_protocol_transparent' + i).show(); 
                    $('#tcp_page_protocol_s7' + i).hide(); 
                } else {
                    //$('#tcp_cmd_interval' + i).val(jsonData.tcp_cmd_interval[i]);
                    $('#tcp_page_protocol_modbus' + i).show();
                    $('#tcp_page_protocol_transparent' + i).hide(); 
                    $('#tcp_page_protocol_s7' + i).hide(); 
                }
            } else {
                $('#page_tcp' + i).hide(); 
                $('#tcp_disable' + i).prop('checked', true);
            }
        }

        $('#loading').hide();
    });
}

function loadModbusConfig() {
    $('#loading').show();
    $.get('ajax/dct/get_dctcfg.php?type=modbus',function(data){
        jsonData = JSON.parse(data);
        for (var i = 0; i < Number(jsonData.count); i++) {
            var table = document.getElementsByTagName("table")[0];
            table.innerHTML += "<tr  class=\"tr cbi-section-table-descr\">\n" +
                "        <td style='text-align:center' name='order'>"+ (jsonData.order[i] != null ? jsonData.order[i] : "-") + "</td>\n" +
                "        <td style='text-align:center' name='device_name'>"+ (jsonData.device_name[i] != null ? jsonData.device_name[i] : "-") +"</td>\n" +
                "        <td style='text-align:center' name='belonged_com'>"+ (jsonData.belonged_com[i] != null ? jsonData.belonged_com[i] : "-") +"</td>\n" +
                "        <td style='text-align:center' name='factor_name'>"+ (jsonData.factor_name[i] != null ? jsonData.factor_name[i] : "-") +"</td>\n" +
                "        <td style='text-align:center' name='device_id'>"+ (jsonData.device_id[i] != null ? jsonData.device_id[i] : "-") +"</td>\n" +
                "        <td style='text-align:center' name='function_code'>"+ (jsonData.function_code[i] != null ? jsonData.function_code[i] : "-") +"</td>\n" +
                "        <td style='text-align:center' name='reg_addr'>"+ (jsonData.reg_addr[i] != null ? jsonData.reg_addr[i] : "-") +"</td>\n" +
                "        <td style='text-align:center' name='reg_count'>"+ (jsonData.reg_count[i] != null ? jsonData.reg_count[i] : "-") +"</td>\n" +
                "        <td style='text-align:center' name='data_type'>"+ jsonData.data_type[i] +"</td>\n" +
                "        <td style='text-align:center' name='server_center'>"+ (jsonData.server_center[i] != null ? jsonData.server_center[i] : "-") +"</td>\n" +
                "        <td style='display:none' name='operator'>"+ jsonData.operator[i] +"</td>\n" +
                "        <td style='display:none' name='operand'>"+ (jsonData.operand[i] != null ? jsonData.operand[i] : "-") +"</td>\n" +
                "        <td style='display:none' name='ex'>"+ (jsonData.ex[i] != null ? jsonData.ex[i] : "-") +"</td>\n" +
                "        <td style='display:none' name='accuracy'>"+ jsonData.accuracy[i] +"</td>\n" +
                "        <td style='text-align:center' name='enabled'>"+ jsonData.enabled[i] +"</td>\n" +
                "        <td><a href=\"javascript:void(0);\" onclick=\"editData(this);\" >Edit</a></td>\n" +
                "        <td><a href=\"javascript:void(0);\" onclick=\"delData(this);\" >Del</a></td>\n" +
                "    </tr>";
        }

        var result = get_table_data();
        var json_data = JSON.stringify(result);
        $('#hidTD').val(json_data);

        $('#loading').hide();
    });
}

function loadS7Config() {
    $('#loading').show();
    $.get('ajax/dct/get_dctcfg.php?type=s7',function(data){
        jsonData = JSON.parse(data);
        $.get('ajax/dct/get_dctcfg.php?type=s7',function(data){
            jsonData = JSON.parse(data);
            for (var i = 0; i < Number(jsonData.count); i++) {
                var table = document.getElementsByTagName("table")[0];
                table.innerHTML += "<tr  class=\"tr cbi-section-table-descr\">\n" +
                    "        <td style='text-align:center' name='order'>"+ (jsonData.order[i] != null ? jsonData.order[i] : "-") + "</td>\n" +
                    "        <td style='text-align:center' name='device_name'>"+ (jsonData.device_name[i] != null ? jsonData.device_name[i] : "-") +"</td>\n" +
                    "        <td style='text-align:center' name='belonged_com'>"+ (jsonData.belonged_com[i] != null ? jsonData.belonged_com[i] : "-") +"</td>\n" +
                    "        <td style='text-align:center' name='factor_name'>"+ (jsonData.factor_name[i] != null ? jsonData.factor_name[i] : "-") +"</td>\n" +
                    "        <td style='text-align:center' name='reg_type'>"+ (jsonData.reg_type[i] != null ? jsonData.reg_type[i] : "-") +"</td>\n" +
                    "        <td style='text-align:center' name='reg_addr'>"+ (jsonData.reg_addr[i] != null ? jsonData.reg_addr[i] : "-") +"</td>\n" +
                    "        <td style='text-align:center' name='reg_count'>"+ (jsonData.reg_count[i] != null ? jsonData.reg_count[i] : "-") +"</td>\n" +
                    "        <td style='text-align:center' name='word_len'>"+ jsonData.word_len[i] +"</td>\n" +
                    "        <td style='text-align:center' name='server_center'>"+ (jsonData.server_center[i] != null ? jsonData.server_center[i] : "-") +"</td>\n" +
                    "        <td style='display:none' name='operator'>"+ jsonData.operator[i] +"</td>\n" +
                    "        <td style='display:none' name='operand'>"+ (jsonData.operand[i] != null ? jsonData.operand[i] : "-") +"</td>\n" +
                    "        <td style='display:none' name='ex'>"+ (jsonData.ex[i] != null ? jsonData.ex[i] : "-") +"</td>\n" +
                    "        <td style='display:none' name='accuracy'>"+ jsonData.accuracy[i] +"</td>\n" +
                    "        <td style='text-align:center' name='enabled'>"+ jsonData.enabled[i] +"</td>\n" +
                    "        <td><a href=\"javascript:void(0);\" onclick=\"editS7Data(this);\" >Edit</a></td>\n" +
                    "        <td><a href=\"javascript:void(0);\" onclick=\"delS7Data(this);\" >Del</a></td>\n" +
                    "    </tr>";
            }

            var result = get_table_data_s7();
            var json_data = JSON.stringify(result);
            $('#hidTD').val(json_data);
        });

        $('#loading').hide();
    });
}

function loadFxConfig() {
    $('#loading').show();
    $.get('ajax/dct/get_dctcfg.php?type=fx',function(data){
        jsonData = JSON.parse(data);
        $.get('ajax/dct/get_dctcfg.php?type=fx',function(data){
            jsonData = JSON.parse(data);
            for (var i = 0; i < Number(jsonData.count); i++) {
                var table = document.getElementsByTagName("table")[0];
                table.innerHTML += "<tr  class=\"tr cbi-section-table-descr\">\n" +
                    "        <td style='text-align:center' name='order'>"+ (jsonData.order[i] != null ? jsonData.order[i] : "-") + "</td>\n" +
                    "        <td style='text-align:center' name='device_name'>"+ (jsonData.device_name[i] != null ? jsonData.device_name[i] : "-") +"</td>\n" +
                    "        <td style='text-align:center' name='belonged_com'>"+ (jsonData.belonged_com[i] != null ? jsonData.belonged_com[i] : "-") +"</td>\n" +
                    "        <td style='text-align:center' name='factor_name'>"+ (jsonData.factor_name[i] != null ? jsonData.factor_name[i] : "-") +"</td>\n" +
                    "        <td style='text-align:center' name='reg_type'>"+ (jsonData.reg_type[i] != null ? jsonData.reg_type[i] : "-") +"</td>\n" +
                    "        <td style='text-align:center' name='reg_addr'>"+ (jsonData.reg_addr[i] != null ? jsonData.reg_addr[i] : "-") +"</td>\n" +
                    "        <td style='text-align:center' name='reg_count'>"+ (jsonData.reg_count[i] != null ? jsonData.reg_count[i] : "-") +"</td>\n" +
                    "        <td style='text-align:center' name='data_type'>"+ jsonData.data_type[i] +"</td>\n" +
                    "        <td style='text-align:center' name='server_center'>"+ (jsonData.server_center[i] != null ? jsonData.server_center[i] : "-") +"</td>\n" +
                    "        <td style='display:none' name='operator'>"+ jsonData.operator[i] +"</td>\n" +
                    "        <td style='display:none' name='operand'>"+ (jsonData.operand[i] != null ? jsonData.operand[i] : "-") +"</td>\n" +
                    "        <td style='display:none' name='ex'>"+ (jsonData.ex[i] != null ? jsonData.ex[i] : "-") +"</td>\n" +
                    "        <td style='display:none' name='accuracy'>"+ jsonData.accuracy[i] +"</td>\n" +
                    "        <td style='text-align:center' name='enabled'>"+ jsonData.enabled[i] +"</td>\n" +
                    "        <td><a href=\"javascript:void(0);\" onclick=\"editFxData(this);\" >Edit</a></td>\n" +
                    "        <td><a href=\"javascript:void(0);\" onclick=\"delFxData(this);\" >Del</a></td>\n" +
                    "    </tr>";
            }

            var result = getTableDataFx();
            var json_data = JSON.stringify(result);
            $('#hidTD').val(json_data);
        });

        $('#loading').hide();
    });
}

function loadADCConfig() {
    $('#loading').show();
    $.get('ajax/dct/get_dctcfg.php?type=adc',function(data){
        jsonData = JSON.parse(data);
        var model = document.getElementById("model").value;
        if (model == "EG500") {
            for (var i = 0; i < Number(jsonData.count); i++) {
                var table = document.getElementsByTagName("table")[0];
                table.innerHTML += "<tr  class=\"tr cbi-section-table-descr\">\n" +
                    "        <td style='text-align:center' name='device_name'>"+ (jsonData.device_name[i] != null ? jsonData.device_name[i] : "-") +"</td>\n" +
                    "        <td style='text-align:center' name='index'>"+ jsonData.index[i] + "</td>\n" +
                    "        <td style='text-align:center' name='factor_name'>"+ (jsonData.factor_name[i] != null ? jsonData.factor_name[i] : "-") +"</td>\n" +
                    "        <td style='text-align:center' name='cap_type'>"+ jsonData.cap_type[i] + "</td>\n" +
                    "        <td style='text-align:center' name='range_down'>"+ (jsonData.range_down[i] != null ? jsonData.range_down[i] : "-") +"</td>\n" +
                    "        <td style='text-align:center' name='range_up'>"+ (jsonData.range_up[i] != null ? jsonData.range_up[i] : "-") +"</td>\n" +
                    "        <td style='text-align:center' name='server_center'>"+ (jsonData.server_center[i] != null ? jsonData.server_center[i] : "-") +"</td>\n" +
                    "        <td style='display:none' name='operator'>"+ jsonData.operator[i] +"</td>\n" +
                    "        <td style='display:none' name='operand'>"+ (jsonData.operand[i] != null ? jsonData.operand[i] : "-") +"</td>\n" +
                    "        <td style='display:none' name='ex'>"+ (jsonData.ex[i] != null ? jsonData.ex[i] : "-") +"</td>\n" +
                    "        <td style='display:none' name='accuracy'>"+ jsonData.accuracy[i] +"</td>\n" +
                    "        <td style='text-align:center' name='enabled'>"+ jsonData.enabled[i] +"</td>\n" +
                    "        <td><a href=\"javascript:void(0);\" onclick=\"editDataADC(this);\" >Edit</a></td>\n" +
                    "        <td><a href=\"javascript:void(0);\" onclick=\"delDataADC(this);\" >Del</a></td>\n" +
                    "    </tr>";
            }

            var result = getTableDataADC();
            var json_data = JSON.stringify(result);
            $('#hidTDADC').val(json_data);
        }

        $('#loading').hide();
    });
}

function loadDIConfig() {
    $('#loading').show();
    $.get('ajax/dct/get_dctcfg.php?type=di',function(data){
        jsonData = JSON.parse(data);
        var model = document.getElementById("model").value;
        var table_num = [];
        if (model == "EG500") {
            table_num = 1;
        } else if (model == "EG410") {
            table_num = 0;
        }

        for (var i = 0; i < Number(jsonData.count); i++) {
            var table = document.getElementsByTagName("table")[table_num];
            table.innerHTML += "<tr  class=\"tr cbi-section-table-descr\">\n" +
                "        <td style='text-align:center' name='device_name'>"+ (jsonData.device_name[i] != null ? jsonData.device_name[i] : "-") +"</td>\n" +
                "        <td style='text-align:center' name='index'>"+ jsonData.index[i] +"</td>\n" +
                "        <td style='text-align:center' name='factor_name'>"+ (jsonData.factor_name[i] != null ? jsonData.factor_name[i] : "-") +"</td>\n" +
                "        <td style='text-align:center' name='mode'>"+ jsonData.mode[i] +"</td>\n" +
                "        <td style='text-align:center' name='count_method'>" + jsonData.count_method[i] + "</td>\n" +
                "        <td style='text-align:center' name='debounce_interval'>"+ (jsonData.debounce_interval[i] != null ? jsonData.debounce_interval[i] : "-") +"</td>\n" +
                "        <td style='text-align:center' name='server_center'>"+ (jsonData.server_center[i] != null ? jsonData.server_center[i] : "-") +"</td>\n" +
                "        <td style='display:none' name='operator'>"+ jsonData.operator[i] +"</td>\n" +
                "        <td style='display:none' name='operand'>"+ (jsonData.operand[i] != null ? jsonData.operand[i] : "-") +"</td>\n" +
                "        <td style='display:none' name='ex'>"+ (jsonData.ex[i] != null ? jsonData.ex[i] : "-") +"</td>\n" +
                "        <td style='display:none' name='accuracy'>"+ jsonData.accuracy[i] +"</td>\n" +
                "        <td style='text-align:center' name='enabled'>"+ jsonData.enabled[i] +"</td>\n" +
                "        <td><a href=\"javascript:void(0);\" onclick=\"editDataDI(this);\" >Edit</a></td>\n" +
                "        <td><a href=\"javascript:void(0);\" onclick=\"delDataDI(this);\" >Del</a></td>\n" +
                "    </tr>";
        }

        var result = getTableDataDI();
        var json_data = JSON.stringify(result);
        $('#hidTDDI').val(json_data);

        $('#loading').hide();
    });
}

function loadDOConfig() {
    $('#loading').show();
    $.get('ajax/dct/get_dctcfg.php?type=do',function(data){
        jsonData = JSON.parse(data);
        var model = document.getElementById("model").value;
        var table_num = [];
        if (model == "EG500") {
            table_num = 2;
        } else if (model == "EG410") {
            table_num = 1;
        }

        for (var i = 0; i < Number(jsonData.count); i++) {
            var table = document.getElementsByTagName("table")[table_num];
            table.innerHTML += "<tr  class=\"tr cbi-section-table-descr\">\n" +
                "        <td style='text-align:center' name='device_name'>"+ (jsonData.device_name[i] != null ? jsonData.device_name[i] : "-") +"</td>\n" +
                "        <td style='text-align:center' name='index'>"+ jsonData.index[i] + "</td>\n" +
                "        <td style='text-align:center' name='factor_name'>"+ (jsonData.factor_name[i] != null ? jsonData.factor_name[i] : "-") +"</td>\n" +
                "        <td style='text-align:center' name='init_status'>"+ jsonData.init_status[i] + "</td>\n" +
                "        <td style='text-align:center' name='cur_status'>"+ jsonData.cur_status[i] + "</td>\n" +
                "        <td style='text-align:center' name='server_center'>"+ (jsonData.server_center[i] != null ? jsonData.server_center[i] : "-") +"</td>\n" +
                "        <td style='display:none' name='operator'>"+ jsonData.operator[i] +"</td>\n" +
                "        <td style='display:none' name='operand'>"+ (jsonData.operand[i] != null ? jsonData.operand[i] : "-") +"</td>\n" +
                "        <td style='display:none' name='ex'>"+ (jsonData.ex[i] != null ? jsonData.ex[i] : "-") +"</td>\n" +
                "        <td style='display:none' name='accuracy'>"+ jsonData.accuracy[i] +"</td>\n" +
                "        <td style='text-align:center' name='enabled'>"+ jsonData.enabled[i] +"</td>\n" +
                "        <td><a href=\"javascript:void(0);\" onclick=\"editDataDO(this);\" >Edit</a></td>\n" +
                "        <td><a href=\"javascript:void(0);\" onclick=\"delDataDO(this);\" >Del</a></td>\n" +
                "    </tr>";
        }

        var result = getTableDataDO();
        var json_data = JSON.stringify(result);
        $('#hidTDDO').val(json_data);

        $('#loading').hide();
    });
}

function loadServerConfig() {
    $('#loading').show();
    $.get('ajax/dct/get_dctcfg.php?type=server',function(data){
        jsonData = JSON.parse(data);

        var arr = ["proto", "encap_type", "server_addr", "http_url", "server_port", "cache_enabled", 
        "register_packet", "register_packet_hex", "heartbeat_packet", "heartbeat_packet_hex", "heartbeat_interval",
        "mqtt_heartbeat_interval", "mqtt_pub_topic", "mqtt_sub_topic", "mqtt_username", "mqtt_password", 
        "mqtt_client_id", "mqtt_tls_enabled", "certificate_type", "mqtt_ca", "mqtt_cert", "mqtt_key", 
        "self_define_var", "var_name1_", "var_value1_", "var_name2_", "var_value2_", "var_name3_", "var_value3_", 
        "mn", "st", "pw"];

        for (var i = 1; i <= 5; i++) {
            $('#enabled' + i).val(jsonData.enabled[i]);
            if (jsonData.enabled[i] == '1') {
                $('#page_server' + i).show();
                $('#enable' + i).prop('checked', true);
                arr.forEach(function (info) {
                    if (info == "cache_enabled" || info == "register_packet_hex" || info == "heartbeat_packet_hex" ||
                        info == "mqtt_tls_enabled" ||  info == "self_define_var") {
                        $('#' + info + i).prop('checked', (jsonData[info][i] == '1') ? true:false);
                    } else if (info == "mqtt_ca") {
                        if (jsonData.ca_file_exists[i] == '1') {
                            $('#ca_text' + i).html(jsonData.mqtt_ca[i]);
                        }
                    } else if (info == "mqtt_cert") {
                        if (jsonData.cer_file_exists[i] == '1') {
                            $('#cer_text' + i).html(jsonData.mqtt_cert[i]);
                        }
                    } else if (info == "mqtt_key") {
                        if (jsonData.key_file_exists[i] == '1') {
                            $('#key_text' + i).html(jsonData.mqtt_key[i]);
                        }
                    } else {
                        $('#' + info + i).val(jsonData[info][i]);
                    }
                    protocolChange(i);
                });           
            } else {
                $('#page_server' + i).hide(); 
                $('#disable' + i).prop('checked', true);
            }
        }

        $('#loading').hide();
    });
}

$('#hostapdModal').on('shown.bs.modal', function (e) {
    var seconds = 9;
    var countDown = setInterval(function(){
      if(seconds <= 0){
        clearInterval(countDown);
      }
      var pct = Math.floor(100-(seconds*100/9));
      document.getElementsByClassName('progress-bar').item(0).setAttribute('style','width:'+Number(pct)+'%');
      seconds --;
    }, 1000);
});

$('#configureClientModal').on('shown.bs.modal', function (e) {
});

$('#ovpn-confirm-delete').on('click', '.btn-delete', function (e) {
    var cfg_id = $(this).data('recordId');
    $.post('ajax/openvpn/del_ovpncfg.php',{'cfg_id':cfg_id},function(data){
        jsonData = JSON.parse(data);
        $("#ovpn-confirm-delete").modal('hide');
        var row = $(document.getElementById("openvpn-client-row-" + cfg_id));
        row.fadeOut( "slow", function() {
            row.remove();
        });
    });
});

$('#ovpn-confirm-delete').on('show.bs.modal', function (e) {
    var data = $(e.relatedTarget).data();
    $('.btn-delete', this).data('recordId', data.recordId);
});

$('#ovpn-confirm-activate').on('click', '.btn-activate', function (e) {
    var cfg_id = $(this).data('record-id');
    $.post('ajax/openvpn/activate_ovpncfg.php',{'cfg_id':cfg_id},function(data){
        jsonData = JSON.parse(data);
        $("#ovpn-confirm-activate").modal('hide');
        setTimeout(function(){
            window.location.reload();
        },300);
    });
});

$('#ovpn-confirm-activate').on('shown.bs.modal', function (e) {
    var data = $(e.relatedTarget).data();
    $('.btn-activate', this).data('recordId', data.recordId);
});

$('#ovpn-userpw,#ovpn-certs').on('click', function (e) {
    if (this.id == 'ovpn-userpw') {
        $('#PanelCerts').hide();
        $('#PanelUserPW').show();
    } else if (this.id == 'ovpn-certs') {
        $('#PanelUserPW').hide();
        $('#PanelCerts').show();
    }
});

$(document).ready(function(){
  $("#PanelManual").hide();
});

$('#wg-upload,#wg-manual').on('click', function (e) {
    if (this.id == 'wg-upload') {
        $('#PanelManual').hide();
        $('#PanelUpload').show();
    } else if (this.id == 'wg-manual') {
        $('#PanelUpload').hide();
        $('#PanelManual').show();
    }
});

// Add the following code if you want the name of the file appear on select
$(".custom-file-input").on("change", function() {
  var fileName = $(this).val().split("\\").pop();
  $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
});

/*
Sets the wirelss channel select options based on hw_mode and country_code.

Methodology: In North America up to channel 11 is the maximum allowed WiFi 2.4Ghz channel,
except for the US that allows channel 12 & 13 in low power mode with additional restrictions.
Canada allows channel 12 in low power mode. Because it's unsure if low powered mode can be
supported the channels are not selectable for those countries. Also Uzbekistan and Colombia
allow up to channel 11 as maximum channel on the 2.4Ghz WiFi band.
Source: https://en.wikipedia.org/wiki/List_of_WLAN_channels
Additional: https://git.kernel.org/pub/scm/linux/kernel/git/sforshee/wireless-regdb.git
*/
function loadChannelSelect(selected) {
    // Fetch wireless regulatory data
    $.getJSON("config/wireless.json", function(json) {
        var hw_mode = $('#cbxhwmode').val();
        var country_code = $('#cbxcountries').val();
        var channel_select = $('#cbxchannel');
        var data = json["wireless_regdb"];
        var selectablechannels = Array.range(1,14);

        // Assign array of countries to valid frequencies (channels)
        var countries_2_4Ghz_max11ch = data["2_4GHz_max11ch"].countries;
        var countries_2_4Ghz_max14ch = data["2_4GHz_max14ch"].countries;
        var countries_5Ghz_max48ch = data["5Ghz_max48ch"].countries;

        // Map selected hw_mode and country to determine channel list
        if (hw_mode === 'a') {
            selectablechannels = data["5Ghz_max48ch"].channels;
        } else if (($.inArray(country_code, countries_2_4Ghz_max11ch) !== -1) && (hw_mode !== 'ac') ) {
            selectablechannels = data["2_4GHz_max11ch"].channels;
        } else if (($.inArray(country_code, countries_2_4Ghz_max14ch) !== -1) && (hw_mode === 'b')) {
            selectablechannels = data["2_4GHz_max14ch"].channels;
        } else if (($.inArray(country_code, countries_5Ghz_max48ch) !== -1) && (hw_mode === 'ac')) {
            selectablechannels = data["5Ghz_max48ch"].channels;
        }

        // Set channel select with available values
        selected = (typeof selected === 'undefined') ? selectablechannels[0] : selected;
        channel_select.empty();
        $.each(selectablechannels, function(key,value) {
            channel_select.append($("<option></option>").attr("value", value).text(value));
        });
        channel_select.val(selected);
    });
}

/* Updates the selected blocklist
 * Request is passed to an ajax handler to download the associated list.
 * Interface elements are updated to indicate current progress, status.
 */
function updateBlocklist() {
    var blocklist_id = $('#cbxblocklist').val();
    if (blocklist_id == '') { return; }
    $('#cbxblocklist-status').find('i').removeClass('fas fa-check').addClass('fas fa-cog fa-spin');
    $('#cbxblocklist-status').removeClass('check-hidden').addClass('check-progress');
    $.post('ajax/adblock/update_blocklist.php',{ 'blocklist_id':blocklist_id },function(data){
        var jsonData = JSON.parse(data);
        if (jsonData['return'] == '0') {
            $('#cbxblocklist-status').find('i').removeClass('fas fa-cog fa-spin').addClass('fas fa-check');
            $('#cbxblocklist-status').removeClass('check-progress').addClass('check-updated').delay(500).animate({ opacity: 1 }, 700);
            $('#'+blocklist_id).text("Just now");
        }
    })
}

function clearBlocklistStatus() {
    $('#cbxblocklist-status').removeClass('check-updated').addClass('check-hidden');
}

// Handler for the wireguard generate key button
$('.wg-keygen').click(function(){
    var entity_pub = $(this).parent('div').prev('input[type="text"]');
    var entity_priv = $(this).parent('div').next('input[type="hidden"]');
    var updated = entity_pub.attr('name')+"-pubkey-status";
    $.post('ajax/networking/get_wgkey.php',{'entity':entity_pub.attr('name') },function(data){
        var jsonData = JSON.parse(data);
        entity_pub.val(jsonData.pubkey);
        $('#' + updated).removeClass('check-hidden').addClass('check-updated').delay(500).animate({ opacity: 1 }, 700);
    })
})

// Handler for wireguard client.conf download
$('.wg-client-dl').click(function(){
    var req = new XMLHttpRequest();
    var url = 'ajax/networking/get_wgcfg.php?type=download';
    req.open('get', url, true);
    req.responseType = 'blob';
    req.setRequestHeader('Content-type', 'text/plain; charset=UTF-8');
    req.onreadystatechange = function (event) {
        if(req.readyState == 4 && req.status == 200) {
            var blob = req.response;
            var link=document.createElement('a');
            link.href=window.URL.createObjectURL(blob);
            link.download = 'client.conf';
            link.click();
        }
    }
    req.send();
})

// Event listener for Bootstrap's form validation
window.addEventListener('load', function() {
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.getElementsByClassName('needs-validation');
    // Loop over them and prevent submission
    var validation = Array.prototype.filter.call(forms, function(form) {
        form.addEventListener('submit', function(event) {
          //console.log(event.submitter);
          if (form.checkValidity() === false) {
            event.preventDefault();
            event.stopPropagation();
          }
          form.classList.add('was-validated');
        }, false);
    });
}, false);

// Static Array method
Array.range = (start, end) => Array.from({length: (end - start)}, (v, k) => k + start);

$(document).on("click", ".js-toggle-password", function(e) {
    var button = $(e.target)
    var field  = $(button.data("target"));
    if (field.is(":input")) {
        e.preventDefault();

        if (!button.data("__toggle-with-initial")) {
            button.data("__toggle-with-initial", button.text())
        }

        if (field.attr("type") === "password") {
            button.text(button.data("toggle-with"));
            field.attr("type", "text");
        } else {
            button.text(button.data("__toggle-with-initial"));
            field.attr("type", "password");
        }
    }
});

$(function() {
    $('#theme-select').change(function() {
        var theme = themes[$( "#theme-select" ).val() ]; 
        set_theme(theme);
   });
});

function set_theme(theme) {
    $('link[title="main"]').attr('href', 'app/css/' + theme);
    // persist selected theme in cookie 
    setCookie('theme',theme,90);
}

$(function() {
    $('#night-mode').change(function() {
        var state = $(this).is(':checked');
        if (state == true && getCookie('theme') != 'lightsout.css') {
            set_theme('lightsout.css');
        } else {
            set_theme('custom.php');
        }
   });
});

function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
    var regx = new RegExp(cname + "=([^;]+)");
    var value = regx.exec(document.cookie);
    return (value != null) ? unescape(value[1]) : null;
}

// Define themes
var themes = {
    "default": "custom.php",
    "hackernews" : "hackernews.css",
    "lightsout" : "lightsout.css",
}

// Toggles the sidebar navigation.
// Overrides the default SB Admin 2 behavior
$("#sidebarToggleTopbar").on('click', function(e) {
    $("body").toggleClass("sidebar-toggled");
    $(".sidebar").toggleClass("toggled d-none");
});

// Overrides SB Admin 2
$("#sidebarToggle, #sidebarToggleTop").on('click', function(e) {
    var toggled = $(".sidebar").hasClass("toggled");
    // Persist state in cookie
    setCookie('sidebarToggled',toggled, 90);
});

$(function() {
    if ($(window).width() < 768) {
        $('.sidebar').addClass('toggled');
        setCookie('sidebarToggled',false, 90);
    }
});

$(window).on("load resize",function(e) {
    if ($(window).width() > 768) {
        $('.sidebar').removeClass('d-none d-md-block');
        if (getCookie('sidebarToggled') == 'false') {
            $('.sidebar').removeClass('toggled');
        }
    }
});

// Adds active class to current nav-item
$(window).bind("load", function() {
    var url = window.location;
    $('ul.navbar-nav a').filter(function() {
      return this.href == url;
    }).parent().addClass('active');
});

$(document)
    .ajaxSend(setCSRFTokenHeader)
    .ready(contentLoaded)
    .ready(loadWifiStations());

function enableServer(state, num) {
    if (state) {
        $('#page_server' + num).show();
        protocolChange(num);
    } else {
        $('#page_server' + num).hide();
    }
}

function protocolChange(num) {
    var protocol = document.getElementById('proto' + num).value;

    enableTls(num);
    cerChange(num);
    enableVar(num);
    encapChange(num);

    if (protocol == '0' || protocol == '1') {
        $('#page_mqtt' + num).hide();
        $('#page_url' + num).hide(); 
        $('#page_tcp' + num).show(); 
        $('#page_addr' + num).show(); 
        $('#page_port' + num).show(); 
        $('#page_encap' + num).show(); 
        $('#page_status' + num).show();
        $('#page_cache' + num).show();
    } else if (protocol == '2') {
        $('#page_mqtt' + num).show();
        $('#page_url' + num).hide(); 
        $('#page_tcp' + num).hide(); 
        $('#page_addr' + num).show(); 
        $('#page_port' + num).show(); 
        $('#page_encap' + num).show(); 
        $('#page_status' + num).show();
        $('#page_cache' + num).show();
    } else if (protocol == '3')  {
        $('#page_mqtt' + num).hide();
        $('#page_url' + num).show(); 
        $('#page_tcp' + num).hide(); 
        $('#page_addr' + num).hide(); 
        $('#page_port' + num).show(); 
        $('#page_encap' + num).show(); 
        $('#page_status' + num).hide();
        $('#page_cache' + num).hide();
    } else if (protocol == '4' || protocol == '5') {
        $('#page_mqtt' + num).hide();
        $('#page_url' + num).hide(); 
        $('#page_tcp' + num).hide(); 
        $('#page_addr' + num).hide(); 
        $('#page_port' + num).show(); 
        $('#page_encap' + num).hide(); 
        $('#page_status' + num).hide();
        $('#page_json' + num).hide();
        $('#page_hj212_' + num).hide();
        $('#page_cache' + num).hide();
    }
}

function encapChange(num) {
    var encap_type = document.getElementById('encap_type' + num).value;

    if (encap_type == 0) {
        $('#page_json' + num).hide();
        $('#page_hj212_' + num).hide();
    } else if (encap_type == 1) {
        $('#page_json' + num).show();
        $('#page_hj212_' + num).hide();
    } else if (encap_type == 2) {
        $('#page_json' + num).hide();
        $('#page_hj212_' + num).show();
    }
}

function enableVar(num) {
    var enable_var = document.getElementById('self_define_var' + num).checked;

    if (enable_var == true) {
        $('#page_var' + num).show();
    } else {
        $('#page_var' + num).hide();
    }
}

function enableTls(num) {
    var enable_tls = document.getElementById('mqtt_tls_enabled' + num).checked;

    if (enable_tls == true) {
        $('#page_mqtt_tls' + num).show();
    } else {
        $('#page_mqtt_tls' + num).hide();
    }
}

function cerChange(num) {
    var cer_type = document.getElementById('certificate_type' + num).value;

    if (cer_type == '0') {
        $('#page_one' + num).hide();
        $('#page_two' + num).hide(); 
    } else if (cer_type == '1') {
        $('#page_one' + num).show();
        $('#page_two' + num).hide(); 
    } else {
        $('#page_one' + num).show();
        $('#page_two' + num).show(); 
    }
}

function caFileChange(num) {
    $('#ca_text' + num).html($('#mqtt_ca' + num)[0].files[0].name);
}

function cerFileChange(num) {
    $('#cer_text' + num).html($('#mqtt_cert' + num)[0].files[0].name);
}

function keyFileChange(num) {
    $('#key_text' + num).html($('#mqtt_key' + num)[0].files[0].name);
}

function openBox() {
    $('#popBox').show();
    $('#popLayer').show();
    document.getElementById("popBox").scrollTop = 0;
    selectOperator();
};

function closeBox() {
    $('#popBox').hide();
    $('#popLayer').hide();
}

function selectOperator() {
    var operator = document.getElementById("widget.operator").value;

    $('#page_operand').hide();
    $('#page_ex').hide();
    if (operator == "0") {
        $('#page_operand').hide();
        $('#page_ex').hide();
    } else if (operator == "5") {
        $('#page_operand').hide();
        $('#page_ex').show();
    } else {
        $('#page_operand').show();
        $('#page_ex').hide();
    }
}

function addData() {
    openBox();
    document.getElementById("page_type").value = "0"; /* 0 is add. other is edit */
}

function get_table_data() {
    var tr = $("#table_modbus tr");
    var result = [];
    for (var i = 2; i < tr.length; i++) {
        var tds = $(tr[i]).find("td");
        if (tds.length > 0) {
        result.push({
            'order':$(tds[0]).html(), 
            'device_name':$(tds[1]).html(),
            'belonged_com':$(tds[2]).html(),
            'factor_name':$(tds[3]).html(),
            'device_id':$(tds[4]).html(),
            'function_code':$(tds[5]).html(),
            'reg_addr':$(tds[6]).html(),
            'reg_count':$(tds[7]).html(),
            'data_type':$(tds[8]).html(),
            'server_center':$(tds[9]).html(),
            'operator':$(tds[10]).html(),
            'operand':$(tds[11]).html(),
            'ex':$(tds[12]).html(),
            'accuracy':$(tds[13]).html(),
            'enabled':$(tds[14]).html()
        });
        }
    }

    return result;
}

function saveData() {
    var result = [];
    var order = document.getElementById("widget.order").value;
    var device_name = document.getElementById("widget.device_name").value;
    var belonged_com = document.getElementById("widget.belonged_com").value;
    var factor_name = document.getElementById("widget.factor_name").value;
    var device_id = document.getElementById("widget.device_id").value;
    var function_code = document.getElementById("widget.function_code").value;
    var reg_addr = document.getElementById("widget.reg_addr").value;
    var reg_count = document.getElementById("widget.reg_count").value;
    var data_type = document.getElementById("widget.data_type").value;
    var server_center = document.getElementById("widget.server_center").value;
    var operator = document.getElementById("widget.operator").value;
    var operand = document.getElementById("widget.operand").value;
    var ex = document.getElementById("widget.ex").value;
    var accuracy = document.getElementById("widget.accuracy").value;
    var enabled = document.getElementById("widget.enabled").checked;
    var page_type = document.getElementById("page_type").value;
    var data_type_value = new Array("Unsigned 16Bits AB", "Unsigned 16Bits BA", "Signed 16Bits AB", "Signed 16Bits BA",
                            "Unsigned 32Bits ABCD", "Unsigned 32Bits BADC", "Unsigned 32Bits CDAB", "Unsigned 32Bits DCBA",
                            "Signed 32Bits ABCD", "Signed 32Bits BADC", "Signed 32Bits CDAB", "Signed 32Bits DCBA",
                            "Float ABCD", "Float BADC", "Float CDAB", "Float DCBA");
    var data_type_num = Number(data_type);
    data_type = data_type_value[data_type_num];

    if (belonged_com == "No Interface Is Enabled") {
        alert("No Interface Is Enabled, please enabled the interface first.");
        return;
    }

    if (page_type == "0") {
        var table = document.getElementsByTagName("table")[0];
        table.innerHTML += "<tr  class=\"tr cbi-section-table-descr\">\n" +
            "        <td style='text-align:center' name='order'>"+ (order.length > 0 ? order : "-") + "</td>\n" +
            "        <td style='text-align:center' name='device_name'>"+ (device_name.length > 0 ? device_name : "-") +"</td>\n" +
            "        <td style='text-align:center' name='belonged_com'>"+ (belonged_com.length > 0 ? belonged_com : "-") +"</td>\n" +
            "        <td style='text-align:center' name='factor_name'>"+ (factor_name.length > 0 ? factor_name : "-") +"</td>\n" +
            "        <td style='text-align:center' name='device_id'>"+ (device_id.length > 0 ? device_id : "-") +"</td>\n" +
            "        <td style='text-align:center' name='function_code'>"+ (function_code.length > 0 ? function_code : "-") +"</td>\n" +
            "        <td style='text-align:center' name='reg_addr'>"+ (reg_addr.length > 0 ? reg_addr : "-") +"</td>\n" +
            "        <td style='text-align:center' name='reg_count'>"+ (reg_count.length > 0 ? reg_count : "-") +"</td>\n" +
            "        <td style='text-align:center' name='data_type'>"+ data_type +"</td>\n" +
            "        <td style='text-align:center' name='server_center'>"+ (server_center.length > 0 ? server_center : "-") +"</td>\n" +
            "        <td style='display:none' name='operator'>"+ operator +"</td>\n" +
            "        <td style='display:none' name='operand'>"+ (operand.length > 0 ? operand : "-") +"</td>\n" +
            "        <td style='display:none' name='ex'>"+ (ex.length > 0 ? ex : "-") +"</td>\n" +
            "        <td style='display:none' name='accuracy'>"+ accuracy +"</td>\n" +
            "        <td style='text-align:center' name='enabled'>"+ enabled +"</td>\n" +
            "        <td><a href=\"javascript:void(0);\" onclick=\"editData(this);\" >Edit</a></td>\n" +
            "        <td><a href=\"javascript:void(0);\" onclick=\"delData(this);\" >Del</a></td>\n" +
            "    </tr>";
    } else {
        var table = document.getElementById("table_modbus");
        var num = 0;
        table.rows[Number(page_type)].cells[num++].innerHTML = (order.length > 0 ? order : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = (device_name.length > 0 ? device_name : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = (belonged_com.length > 0 ? belonged_com : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = (factor_name.length > 0 ? factor_name : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = (device_id.length > 0 ? device_id : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = (function_code.length > 0 ? function_code : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = (reg_addr.length > 0 ? reg_addr : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = (reg_count.length > 0 ? reg_count : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = data_type;
        table.rows[Number(page_type)].cells[num++].innerHTML = (server_center.length > 0 ? server_center : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = operator;
        table.rows[Number(page_type)].cells[num++].innerHTML = (operand.length > 0 ? operand : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = (ex.length > 0 ? ex : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = accuracy;
        table.rows[Number(page_type)].cells[num++].innerHTML = enabled;
    }

    result = get_table_data();
    var json_data = JSON.stringify(result);
    $('#hidTD').val(json_data);
    closeBox();
}

function delData(object) {
    var table = object.parentNode.parentNode.parentNode;
    var tr = object.parentNode.parentNode;
    table.removeChild(tr);

    var result = get_table_data();
    var json_data = JSON.stringify(result);
    $('#hidTD').val(json_data);
}

function editData(object) {
    var row = $(object).parent().parent().parent().prevAll().length + 1;
    document.getElementById("page_type").value = row;
    var num = 0;
    var value = $(object).parent().parent().find("td");
    var order = value.eq(num++).text();
    var device_name = value.eq(num++).text();
    var belonged_com = value.eq(num++).text();
    var factor_name = value.eq(num++).text();
    var device_id = value.eq(num++).text();
    var function_code = value.eq(num++).text();
    var reg_addr = value.eq(num++).text();
    var reg_count = value.eq(num++).text();
    var data_type = value.eq(num++).text();
    var server_center = value.eq(num++).text();
    var operator = value.eq(num++).text();
    var operand = value.eq(num++).text();
    var ex = value.eq(num++).text();
    var accuracy = value.eq(num++).text();
    var enabled = value.eq(num++).text();

    document.getElementById("widget.order").value = order;
    document.getElementById("widget.device_name").value = device_name;
    document.getElementById("widget.belonged_com").value = belonged_com;
    document.getElementById("widget.factor_name").value = factor_name;
    document.getElementById("widget.device_id").value = device_id;
    document.getElementById("widget.function_code").value = function_code;
    document.getElementById("widget.reg_addr").value = reg_addr;
    document.getElementById("widget.reg_count").value = reg_count;
    setSelectByText("widget.data_type", data_type);
    document.getElementById("widget.server_center").value = server_center;
    document.getElementById("widget.operator").value = operator;
    document.getElementById("widget.operand").value = operand;
    document.getElementById("widget.ex").value = ex;
    document.getElementById("widget.accuracy").value = accuracy;
    if (enabled == "true") {
        document.getElementById("widget.enabled").checked = true;
    } else {
        document.getElementById("widget.enabled").checked = false;
    }

    openBox();
}

// S7
function get_table_data_s7() {
    var tr = $("#table_s7 tr");
    var result = [];
    for (var i = 2; i < tr.length; i++) {
        var tds = $(tr[i]).find("td");
        if (tds.length > 0) {
            result.push({
                'order':$(tds[0]).html(), 
                'device_name':$(tds[1]).html(),
                'belonged_com':$(tds[2]).html(),
                'factor_name':$(tds[3]).html(),
                'reg_type':$(tds[4]).html(),
                'reg_addr':$(tds[5]).html(),
                'reg_count':$(tds[6]).html(),
                'word_len':$(tds[7]).html(),
                'server_center':$(tds[8]).html(),
                'operator':$(tds[9]).html(),
                'operand':$(tds[10]).html(),
                'ex':$(tds[11]).html(),
                'accuracy':$(tds[12]).html(),
                'enabled':$(tds[13]).html()
            });
        }
    }

    return result;
}
  
function delS7Data(object) {
    var table = object.parentNode.parentNode.parentNode;
    var tr = object.parentNode.parentNode;
    table.removeChild(tr);

    var result = get_table_data_s7();
    var json_data = JSON.stringify(result);
    $('#hidTD').val(json_data);
}
  
function saveS7Data() {
    var order = document.getElementById("widget.order").value;
    var device_name = document.getElementById("widget.device_name").value;
    var belonged_com = document.getElementById("widget.belonged_com").value;
    var factor_name = document.getElementById("widget.factor_name").value;
    var reg_type = document.getElementById("widget.reg_type").value;
    var reg_addr = document.getElementById("widget.reg_addr").value;
    var reg_count = document.getElementById("widget.reg_count").value;
    var word_len = document.getElementById("widget.word_len").value;
    var server_center = document.getElementById("widget.server_center").value;
    var operator = document.getElementById("widget.operator").value;
    var operand = document.getElementById("widget.operand").value;
    var ex = document.getElementById("widget.ex").value;
    var accuracy = document.getElementById("widget.accuracy").value;
    var enabled = document.getElementById("widget.enabled").checked;
    var page_type = document.getElementById("page_type").value;

    var reg_type_value = new Array("I", "Q", "M", "DB", "V", "C", "T");
    var reg_type_num = Number(reg_type);
    reg_type = reg_type_value[reg_type_num]; 

    var word_len_value = new Array("Bit", "Byte", "Word", "DWord", "Real", "Counter", "Timer");
    var word_len_num = Number(word_len);
    word_len = word_len_value[word_len_num];

    if (belonged_com == "No Interface Is Enabled") {
        alert("No Interface Is Enabled, please enabled the interface first.");
        return;
    }

    if (page_type == "0") {
        var table = document.getElementsByTagName("table")[0];
        table.innerHTML += "<tr  class=\"tr cbi-section-table-descr\">\n" +
            "        <td style='text-align:center'>"+ (order.length > 0 ? order : "-") +"</td>\n" +
            "        <td style='text-align:center'>"+ (device_name.length > 0 ? device_name : "-") +"</td>\n" +
            "        <td style='text-align:center'>"+ (belonged_com.length > 0 ? belonged_com : "-") +"</td>\n" +
            "        <td style='text-align:center'>"+ (factor_name.length > 0 ? factor_name : "-") +"</td>\n" +
            "        <td style='text-align:center'>"+ (reg_type.length > 0 ? reg_type : "-") +"</td>\n" +
            "        <td style='text-align:center'>"+ (reg_addr.length > 0 ? reg_addr : "-") +"</td>\n" +
            "        <td style='text-align:center'>"+ (reg_count.length > 0 ? reg_count : "-") +"</td>\n" +
            "        <td style='text-align:center'>"+ word_len +"</td>\n" +
            "        <td style='text-align:center'>"+ (server_center.length > 0 ? server_center : "-") +"</td>\n" +
            "        <td style='display:none'>"+ operator +"</td>\n" +
            "        <td style='display:none'>"+ (operand.length > 0 ? operand : "-") +"</td>\n" +
            "        <td style='display:none'>"+ (ex.length > 0 ? ex : "-") +"</td>\n" +
            "        <td style='display:none'>"+ accuracy +"</td>\n" +
            "        <td style='text-align:center'>"+ enabled +"</td>\n" +
            "        <td><a href=\"javascript:void(0);\" onclick=\"editS7Data(this);\" >Edit</a></td>\n" +
            "        <td><a href=\"javascript:void(0);\" onclick=\"delS7Data(this);\" >Del</a></td>\n" +
            "    </tr>";
    } else {
        var table = document.getElementById("table_s7");
        var num = 0;
        table.rows[Number(page_type)].cells[num++].innerHTML = (order.length > 0 ? order : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = (device_name.length > 0 ? device_name : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = (belonged_com.length > 0 ? belonged_com : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = (factor_name.length > 0 ? factor_name : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = (reg_type.length > 0 ? reg_type : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = (reg_addr.length > 0 ? reg_addr : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = (reg_count.length > 0 ? reg_count : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = word_len;
        table.rows[Number(page_type)].cells[num++].innerHTML = (server_center.length > 0 ? server_center : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = operator;
        table.rows[Number(page_type)].cells[num++].innerHTML = (operand.length > 0 ? operand : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = (ex.length > 0 ? ex : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = accuracy;
        table.rows[Number(page_type)].cells[num++].innerHTML = enabled;
    }

    result = get_table_data_s7();
    var json_data = JSON.stringify(result);
    $('#hidTD').val(json_data);
    closeBox();
}
  
function editS7Data(object) {
    var row = $(object).parent().parent().parent().prevAll().length + 1;
    var num = 0;
    document.getElementById("page_type").value = row;

    var value = $(object).parent().parent().find("td");
    var order = value.eq(num++).text();
    var device_name = value.eq(num++).text();
    var belonged_com = value.eq(num++).text();
    var factor_name = value.eq(num++).text();
    var reg_type = value.eq(num++).text();
    var reg_addr = value.eq(num++).text();
    var reg_count = value.eq(num++).text();
    var word_len = value.eq(num++).text();
    var server_center = value.eq(num++).text();
    var operator = value.eq(num++).text();
    var operand = value.eq(num++).text();
    var ex = value.eq(num++).text();
    var accuracy = value.eq(num++).text();
    var enabled = value.eq(num++).text();

    document.getElementById("widget.order").value = order;
    document.getElementById("widget.device_name").value = device_name;
    document.getElementById("widget.belonged_com").value = belonged_com;
    document.getElementById("widget.factor_name").value = factor_name;
    setSelectByText("widget.reg_type", reg_type);
    document.getElementById("widget.reg_addr").value = reg_addr;
    document.getElementById("widget.reg_count").value = reg_count;
    setSelectByText("widget.word_len", word_len);
    document.getElementById("widget.server_center").value = server_center;
    document.getElementById("widget.operator").value = operator;
    document.getElementById("widget.operand").value = operand;
    document.getElementById("widget.ex").value = ex;
    document.getElementById("widget.accuracy").value = accuracy;
    if (enabled == "true") {
        document.getElementById("widget.enabled").checked = true;
    } else {
        document.getElementById("widget.enabled").checked = false;
    }

    openBox();
}

// FX
function getTableDataFx() {
    var tr = $("#table_fx tr");
    var result = [];
    for (var i = 2; i < tr.length; i++) {
        var tds = $(tr[i]).find("td");
        if (tds.length > 0) {
            result.push({
                'order':$(tds[0]).html(), 
                'device_name':$(tds[1]).html(),
                'belonged_com':$(tds[2]).html(),
                'factor_name':$(tds[3]).html(),
                'reg_type':$(tds[4]).html(),
                'reg_addr':$(tds[5]).html(),
                'reg_count':$(tds[6]).html(),
                'data_type':$(tds[7]).html(),
                'server_center':$(tds[8]).html(),
                'operator':$(tds[9]).html(),
                'operand':$(tds[10]).html(),
                'ex':$(tds[11]).html(),
                'accuracy':$(tds[12]).html(),
                'enabled':$(tds[13]).html()
            });
        }
    }

    return result;
}
  
function delFxData(object) {
    var table = object.parentNode.parentNode.parentNode;
    var tr = object.parentNode.parentNode;
    table.removeChild(tr);

    var result = getTableDataFx();
    var json_data = JSON.stringify(result);
    $('#hidTD').val(json_data);
}
  
function saveFxData() {
    var order = document.getElementById("widget.order").value;
    var device_name = document.getElementById("widget.device_name").value;
    var belonged_com = document.getElementById("widget.belonged_com").value;
    var factor_name = document.getElementById("widget.factor_name").value;
    var reg_type = document.getElementById("widget.reg_type").value;
    var reg_addr = document.getElementById("widget.reg_addr").value;
    var reg_count = document.getElementById("widget.reg_count").value;
    var data_type = document.getElementById("widget.data_type").value;
    var server_center = document.getElementById("widget.server_center").value;
    var operator = document.getElementById("widget.operator").value;
    var operand = document.getElementById("widget.operand").value;
    var ex = document.getElementById("widget.ex").value;
    var accuracy = document.getElementById("widget.accuracy").value;
    var enabled = document.getElementById("widget.enabled").checked;
    var page_type = document.getElementById("page_type").value;

    var reg_type_value = new Array("X", "Y", "M", "S", "D");
    var reg_type_num = Number(reg_type);
    reg_type = reg_type_value[reg_type_num]; 

    var data_type_value = new Array("Bit", "Byte", "Word", "DWord", "Real");
    var data_type_num = Number(data_type);
    data_type = data_type_value[data_type_num];

    if (belonged_com == "No Interface Is Enabled") {
        alert("No Interface Is Enabled, please enabled the interface first.");
        return;
    }

    if (page_type == "0") {
        var table = document.getElementsByTagName("table")[0];
        table.innerHTML += "<tr  class=\"tr cbi-section-table-descr\">\n" +
            "        <td style='text-align:center'>"+ (order.length > 0 ? order : "-") +"</td>\n" +
            "        <td style='text-align:center'>"+ (device_name.length > 0 ? device_name : "-") +"</td>\n" +
            "        <td style='text-align:center'>"+ (belonged_com.length > 0 ? belonged_com : "-") +"</td>\n" +
            "        <td style='text-align:center'>"+ (factor_name.length > 0 ? factor_name : "-") +"</td>\n" +
            "        <td style='text-align:center'>"+ (reg_type.length > 0 ? reg_type : "-") +"</td>\n" +
            "        <td style='text-align:center'>"+ (reg_addr.length > 0 ? reg_addr : "-") +"</td>\n" +
            "        <td style='text-align:center'>"+ (reg_count.length > 0 ? reg_count : "-") +"</td>\n" +
            "        <td style='text-align:center'>"+ data_type +"</td>\n" +
            "        <td style='text-align:center'>"+ (server_center.length > 0 ? server_center : "-") +"</td>\n" +
            "        <td style='display:none'>"+ operator +"</td>\n" +
            "        <td style='display:none'>"+ (operand.length > 0 ? operand : "-") +"</td>\n" +
            "        <td style='display:none'>"+ (ex.length > 0 ? ex : "-") +"</td>\n" +
            "        <td style='display:none'>"+ accuracy +"</td>\n" +
            "        <td style='text-align:center'>"+ enabled +"</td>\n" +
            "        <td><a href=\"javascript:void(0);\" onclick=\"editFxData(this);\" >Edit</a></td>\n" +
            "        <td><a href=\"javascript:void(0);\" onclick=\"delFxData(this);\" >Del</a></td>\n" +
            "    </tr>";
    } else {
        var table = document.getElementById("table_fx");
        var num = 0;
        table.rows[Number(page_type)].cells[num++].innerHTML = (order.length > 0 ? order : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = (device_name.length > 0 ? device_name : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = (belonged_com.length > 0 ? belonged_com : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = (factor_name.length > 0 ? factor_name : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = (reg_type.length > 0 ? reg_type : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = (reg_addr.length > 0 ? reg_addr : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = (reg_count.length > 0 ? reg_count : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = data_type;
        table.rows[Number(page_type)].cells[num++].innerHTML = (server_center.length > 0 ? server_center : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = operator;
        table.rows[Number(page_type)].cells[num++].innerHTML = (operand.length > 0 ? operand : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = (ex.length > 0 ? ex : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = accuracy;
        table.rows[Number(page_type)].cells[num++].innerHTML = enabled;
    }

    result = getTableDataFx();
    var json_data = JSON.stringify(result);
    $('#hidTD').val(json_data);
    closeBox();
}
  
function editFxData(object) {
    var row = $(object).parent().parent().parent().prevAll().length + 1;
    var num = 0;
    document.getElementById("page_type").value = row;

    var value = $(object).parent().parent().find("td");
    var order = value.eq(num++).text();
    var device_name = value.eq(num++).text();
    var belonged_com = value.eq(num++).text();
    var factor_name = value.eq(num++).text();
    var reg_type = value.eq(num++).text();
    var reg_addr = value.eq(num++).text();
    var reg_count = value.eq(num++).text();
    var data_type = value.eq(num++).text();
    var server_center = value.eq(num++).text();
    var operator = value.eq(num++).text();
    var operand = value.eq(num++).text();
    var ex = value.eq(num++).text();
    var accuracy = value.eq(num++).text();
    var enabled = value.eq(num++).text();

    document.getElementById("widget.order").value = order;
    document.getElementById("widget.device_name").value = device_name;
    document.getElementById("widget.belonged_com").value = belonged_com;
    document.getElementById("widget.factor_name").value = factor_name;
    setSelectByText("widget.reg_type", reg_type);
    document.getElementById("widget.reg_addr").value = reg_addr;
    document.getElementById("widget.reg_count").value = reg_count;
    setSelectByText("widget.data_type", data_type);
    document.getElementById("widget.server_center").value = server_center;
    document.getElementById("widget.operator").value = operator;
    document.getElementById("widget.operand").value = operand;
    document.getElementById("widget.ex").value = ex;
    document.getElementById("widget.accuracy").value = accuracy;
    if (enabled == "true") {
        document.getElementById("widget.enabled").checked = true;
    } else {
        document.getElementById("widget.enabled").checked = false;
    }

    openBox();
}

function selectMode() {
    var mode = document.getElementById("widget.mode").value;

    if (mode == "0") {
		$('#pageCount').show();
    } else {
		$('#pageCount').hide();
    }
}

function switchPage(name) {
    if (name == "btnADC") {
        document.getElementById("popBoxTitle").innerHTML="ADC Setting";
        document.getElementById("page_name").value = "0"; /* 0 is ADC. 1 is DI, 2 is DO */
        $('#pageIndexADC').show();
        $('#pageIndexDI').hide();
        $('#pageIndexDO').hide();
        $('#pageADCMod').show();
        $('#pageDIMod').hide();
        $('#pageDOMod').hide();

    } else if (name == "btnDI") {
        document.getElementById("popBoxTitle").innerHTML="DI Setting";
        document.getElementById("page_name").value = "1";
        $('#pageIndexADC').hide();
        $('#pageIndexDI').show();
        $('#pageIndexDO').hide();
        $('#pageADCMod').hide();
        $('#pageDIMod').show();
        $('#pageDOMod').hide();
        selectMode();
    } else if (name == "btnDO") {
        document.getElementById("popBoxTitle").innerHTML="DO Setting";
        document.getElementById("page_name").value = "2";
        $('#pageIndexADC').hide();
        $('#pageIndexDI').hide();
        $('#pageIndexDO').show();
        $('#pageADCMod').hide();
        $('#pageDIMod').hide();
        $('#pageDOMod').show();
    }
}

function addDataIO(object) {
    openBox();
    document.getElementById("page_type").value = "0"; /* 0 is add. other is edit */
    var name = object.name;
    switchPage(name);
}

function getTableDataADC() {
    var tr = $("#tableADC tr");
    var result = [];
    var num = 0;

    for (var i = 2; i < tr.length; i++) {
        var tds = $(tr[i]).find("td");
        num = 0;
        if (tds.length > 0) {
        result.push({
            'device_name':$(tds[num++]).html(),
            'index':$(tds[num++]).html(),
            'factor_name':$(tds[num++]).html(),
            'cap_type':$(tds[num++]).html(),
            'range_down':$(tds[num++]).html(),
            'range_up':$(tds[num++]).html(),
            'server_center':$(tds[num++]).html(),
            'operator':$(tds[num++]).html(),
            'operand':$(tds[num++]).html(),
            'ex':$(tds[num++]).html(),
            'accuracy':$(tds[num++]).html(),
            'enabled':$(tds[num++]).html()
        });
        }
    }

    return result;
}

function getTableDataDI() {
    var tr = $("#tableDI tr");
    var result = [];
    var num = 0;

    for (var i = 2; i < tr.length; i++) {
        var tds = $(tr[i]).find("td");
        num = 0;
        if (tds.length > 0) {
        result.push({
            'device_name':$(tds[num++]).html(),
            'index':$(tds[num++]).html(),
            'factor_name':$(tds[num++]).html(),
            'mode':$(tds[num++]).html(),
            'count_method':$(tds[num++]).html(),
            'debounce_interval':$(tds[num++]).html(),
            'server_center':$(tds[num++]).html(),
            'operator':$(tds[num++]).html(),
            'operand':$(tds[num++]).html(),
            'ex':$(tds[num++]).html(),
            'accuracy':$(tds[num++]).html(),
            'enabled':$(tds[num++]).html()
        });
        }
    }

    return result;
}

function getTableDataDO() {
    var tr = $("#tableDO tr");
    var result = [];
    var num = 0;

    for (var i = 2; i < tr.length; i++) {
        var tds = $(tr[i]).find("td");
        num = 0;
        if (tds.length > 0) {
        result.push({
            'device_name':$(tds[num++]).html(),
            'index':$(tds[num++]).html(),
            'factor_name':$(tds[num++]).html(),
            'init_status':$(tds[num++]).html(),
            'cur_status':$(tds[num++]).html(),
            'server_center':$(tds[num++]).html(),
            'operator':$(tds[num++]).html(),
            'operand':$(tds[num++]).html(),
            'ex':$(tds[num++]).html(),
            'accuracy':$(tds[num++]).html(),
            'enabled':$(tds[num++]).html()
        });
        }
    }

    return result;
}

function saveDataADC() {
    var result = [];
    var device_name = document.getElementById("widget.device_name").value;
    var index = document.getElementById("widget.index.adc").value;
    var factor_name = document.getElementById("widget.factor_name").value;
    var cap_type = document.getElementById("widget.cap_type").value;
    var range_down = document.getElementById("widget.range_down").value;
    var range_up = document.getElementById("widget.range_up").value;
    var server_center = document.getElementById("widget.server_center").value;
    var operator = document.getElementById("widget.operator").value;
    var operand = document.getElementById("widget.operand").value;
    var ex = document.getElementById("widget.ex").value;
    var accuracy = document.getElementById("widget.accuracy").value;
    var enabled = document.getElementById("widget.enabled").checked;
    var page_type = document.getElementById("page_type").value;
    var cap_type_value = new Array("4-20mA", "0-10V");

    var cap_type_num = Number(cap_type);
    cap_type = cap_type_value[cap_type_num];

    if (page_type == "0") {
        var table = document.getElementsByTagName("table")[0];
        table.innerHTML += "<tr  class=\"tr cbi-section-table-descr\">\n" +
            "        <td style='text-align:center' name='device_name'>"+ (device_name.length > 0 ? device_name : "-") +"</td>\n" +
            "        <td style='text-align:center' name='index'>"+ (index.length > 0 ? index : "-") +"</td>\n" +
            "        <td style='text-align:center' name='factor_name'>"+ (factor_name.length > 0 ? factor_name : "-") +"</td>\n" +
            "        <td style='text-align:center' name='cap_type'>"+ (cap_type.length > 0 ? cap_type : "-") +"</td>\n" +
            "        <td style='text-align:center' name='range_down'>"+ (range_down.length > 0 ? range_down : "-") +"</td>\n" +
            "        <td style='text-align:center' name='range_up'>"+ (range_up.length > 0 ? range_up : "-") +"</td>\n" +
            "        <td style='text-align:center' name='server_center'>"+ (server_center.length > 0 ? server_center : "-") +"</td>\n" +
            "        <td style='display:none' name='operator'>"+ operator +"</td>\n" +
            "        <td style='display:none' name='operand'>"+ (operand.length > 0 ? operand : "-") +"</td>\n" +
            "        <td style='display:none' name='ex'>"+ (ex.length > 0 ? ex : "-") +"</td>\n" +
            "        <td style='display:none' name='accuracy'>"+ accuracy +"</td>\n" +
            "        <td style='text-align:center' name='enabled'>"+ enabled +"</td>\n" +
            "        <td><a href=\"javascript:void(0);\" onclick=\"editDataADC(this);\" >Edit</a></td>\n" +
            "        <td><a href=\"javascript:void(0);\" onclick=\"delDataADC(this);\" >Del</a></td>\n" +
            "    </tr>";
    } else {
        var table = document.getElementById("tableADC");
        var num = 0;
        table.rows[Number(page_type)].cells[num++].innerHTML = (device_name.length > 0 ? device_name : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = (index.length > 0 ? index : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = (factor_name.length > 0 ? factor_name : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = (cap_type.length > 0 ? cap_type : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = (range_down.length > 0 ? range_down : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = (range_up.length > 0 ? range_up : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = (server_center.length > 0 ? server_center : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = operator;
        table.rows[Number(page_type)].cells[num++].innerHTML = (operand.length > 0 ? operand : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = (ex.length > 0 ? ex : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = accuracy;
        table.rows[Number(page_type)].cells[num++].innerHTML = enabled;
    }

    result = getTableDataADC();
    var json_data = JSON.stringify(result);
    $('#hidTDADC').val(json_data);
}

function saveDataDI() {
    var result = [];
    var device_name = document.getElementById("widget.device_name").value;
    var index = document.getElementById("widget.index.di").value;
    var factor_name = document.getElementById("widget.factor_name").value;
    var mode = document.getElementById("widget.mode").value;
    var count_method = document.getElementById("widget.count_method").value;
    var debounce_interval = document.getElementById("widget.debounce_interval").value;
    var server_center = document.getElementById("widget.server_center").value;
    var operator = document.getElementById("widget.operator").value;
    var operand = document.getElementById("widget.operand").value;
    var ex = document.getElementById("widget.ex").value;
    var accuracy = document.getElementById("widget.accuracy").value;
    var enabled = document.getElementById("widget.enabled").checked;
    var page_type = document.getElementById("page_type").value;
    var mode_value = new Array("Counting Mode", "Status Mode");
    var mode_num = Number(mode);

    var method_value = new Array("Rising Edge", "Falling Edge");
    var method_num = Number(count_method);

    var model = document.getElementById("model").value;
    var table_num = [];
    if (model == "EG500") {
        table_num = 1;
    } else if (model == "EG410") {
        table_num = 0;
    }

    if (mode == "1") {
        count_method = "-";
        debounce_interval = "-";
    } else {
        count_method = method_value[method_num];
    }

    mode = mode_value[mode_num];

    if (page_type == "0") {
        var table = document.getElementsByTagName("table")[table_num];
        table.innerHTML += "<tr  class=\"tr cbi-section-table-descr\">\n" +
            "        <td style='text-align:center' name='device_name'>"+ (device_name.length > 0 ? device_name : "-") +"</td>\n" +
            "        <td style='text-align:center' name='index'>"+ (index.length > 0 ? index : "-") +"</td>\n" +
            "        <td style='text-align:center' name='factor_name'>"+ (factor_name.length > 0 ? factor_name : "-") +"</td>\n" +
            "        <td style='text-align:center' name='mode'>"+ (mode.length > 0 ? mode : "-") +"</td>\n" +
            "        <td style='text-align:center' name='count_method'>"+ (count_method.length > 0 ? count_method : "-") +"</td>\n" +
            "        <td style='text-align:center' name='debounce_interval'>"+ (debounce_interval.length > 0 ? debounce_interval : "-") +"</td>\n" +
            "        <td style='text-align:center' name='server_center'>"+ (server_center.length > 0 ? server_center : "-") +"</td>\n" +
            "        <td style='display:none' name='operator'>"+ operator +"</td>\n" +
            "        <td style='display:none' name='operand'>"+ (operand.length > 0 ? operand : "-") +"</td>\n" +
            "        <td style='display:none' name='ex'>"+ (ex.length > 0 ? ex : "-") +"</td>\n" +
            "        <td style='display:none' name='accuracy'>"+ accuracy +"</td>\n" +
            "        <td style='text-align:center' name='enabled'>"+ enabled +"</td>\n" +
            "        <td><a href=\"javascript:void(0);\" onclick=\"editDataDI(this);\" >Edit</a></td>\n" +
            "        <td><a href=\"javascript:void(0);\" onclick=\"delDataDI(this);\" >Del</a></td>\n" +
            "    </tr>";
    } else {
        var table = document.getElementById("tableDI");
        var num = 0;
        table.rows[Number(page_type)].cells[num++].innerHTML = (device_name.length > 0 ? device_name : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = (index.length > 0 ? index : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = (factor_name.length > 0 ? factor_name : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = (mode.length > 0 ? mode : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = (count_method.length > 0 ? count_method : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = (debounce_interval.length > 0 ? debounce_interval : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = (server_center.length > 0 ? server_center : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = operator;
        table.rows[Number(page_type)].cells[num++].innerHTML = (operand.length > 0 ? operand : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = (ex.length > 0 ? ex : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = accuracy;
        table.rows[Number(page_type)].cells[num++].innerHTML = enabled;
    }

    result = getTableDataDI();
    var json_data = JSON.stringify(result);
    $('#hidTDDI').val(json_data);
}

function saveDataDO() {
    var result = [];
    var device_name = document.getElementById("widget.device_name").value;
    var index = document.getElementById("widget.index.do").value;
    var factor_name = document.getElementById("widget.factor_name").value;
    var init_status = document.getElementById("widget.init_status").value;
    var cur_status = document.getElementById("widget.cur_status").innerHTML;
    var server_center = document.getElementById("widget.server_center").value;
    var operator = document.getElementById("widget.operator").value;
    var operand = document.getElementById("widget.operand").value;
    var ex = document.getElementById("widget.ex").value;
    var accuracy = document.getElementById("widget.accuracy").value;
    var enabled = document.getElementById("widget.enabled").checked;
    var page_type = document.getElementById("page_type").value;
    var status_value = new Array("Open", "Close");
    var status_num = Number(init_status);

    var model = document.getElementById("model").value;
    var table_num = [];
    if (model == "EG500") {
        table_num = 2;
    } else if (model == "EG410") {
        table_num = 1;
    }

    init_status = status_value[status_num];

    if (page_type == "0") {
        var table = document.getElementsByTagName("table")[table_num];
        table.innerHTML += "<tr  class=\"tr cbi-section-table-descr\">\n" +
            "        <td style='text-align:center' name='device_name'>"+ (device_name.length > 0 ? device_name : "-") +"</td>\n" +
            "        <td style='text-align:center' name='index'>"+ (index.length > 0 ? index : "-") +"</td>\n" +
            "        <td style='text-align:center' name='factor_name'>"+ (factor_name.length > 0 ? factor_name : "-") +"</td>\n" +
            "        <td style='text-align:center' name='init_status'>"+ (init_status.length > 0 ? init_status : "-") +"</td>\n" +
            "        <td style='text-align:center' name='cur_status'>"+ (cur_status.length > 0 ? cur_status : "-") +"</td>\n" +
            "        <td style='text-align:center' name='server_center'>"+ (server_center.length > 0 ? server_center : "-") +"</td>\n" +
            "        <td style='display:none' name='operator'>"+ operator +"</td>\n" +
            "        <td style='display:none' name='operand'>"+ (operand.length > 0 ? operand : "-") +"</td>\n" +
            "        <td style='display:none' name='ex'>"+ (ex.length > 0 ? ex : "-") +"</td>\n" +
            "        <td style='display:none' name='accuracy'>"+ accuracy +"</td>\n" +
            "        <td style='text-align:center' name='enabled'>"+ enabled +"</td>\n" +
            "        <td><a href=\"javascript:void(0);\" onclick=\"editDataDO(this);\" >Edit</a></td>\n" +
            "        <td><a href=\"javascript:void(0);\" onclick=\"delDataDO(this);\" >Del</a></td>\n" +
            "    </tr>";
    } else {
        var table = document.getElementById("tableDO");
        var num = 0;
        table.rows[Number(page_type)].cells[num++].innerHTML = (device_name.length > 0 ? device_name : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = (index.length > 0 ? index : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = (factor_name.length > 0 ? factor_name : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = (init_status.length > 0 ? init_status : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = (cur_status.length > 0 ? cur_status : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = (server_center.length > 0 ? server_center : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = operator;
        table.rows[Number(page_type)].cells[num++].innerHTML = (operand.length > 0 ? operand : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = (ex.length > 0 ? ex : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = accuracy;
        table.rows[Number(page_type)].cells[num++].innerHTML = enabled;
    }

    result = getTableDataDO();
    var json_data = JSON.stringify(result);
    $('#hidTDDO').val(json_data); 
}

function saveDataIO() {
    var page_name = document.getElementById("page_name").value;

    if (page_name == "0") {
        saveDataADC();
    } else if (page_name == "1") {
        saveDataDI();
    } else {
        saveDataDO();
    }

    closeBox();
}

function delDataADC(object) {
    var table = object.parentNode.parentNode.parentNode;
    var tr = object.parentNode.parentNode;
    table.removeChild(tr);

    var result = getTableDataADC();
    var json_data = JSON.stringify(result);
    $('#hidTDADC').val(json_data);
}

function delDataDI(object) {
    var table = object.parentNode.parentNode.parentNode;
    var tr = object.parentNode.parentNode;
    table.removeChild(tr);

    var result = getTableDataDI();
    var json_data = JSON.stringify(result);
    $('#hidTDDI').val(json_data);
}

function delDataDO(object) {
    var table = object.parentNode.parentNode.parentNode;
    var tr = object.parentNode.parentNode;
    table.removeChild(tr);

    var result = getTableDataDO();
    var json_data = JSON.stringify(result);
    $('#hidTDDO').val(json_data);
}

function editDataADC(object) {
    var row = $(object).parent().parent().parent().prevAll().length + 1;
    document.getElementById("page_type").value = row;
    var num = 0;
    var value = $(object).parent().parent().find("td");
    var device_name = value.eq(num++).text();
    var index = value.eq(num++).text();
    var factor_name = value.eq(num++).text();
    var cap_type = value.eq(num++).text();
    var range_down = value.eq(num++).text();
    var range_up = value.eq(num++).text();
    var server_center = value.eq(num++).text();
    var operator = value.eq(num++).text();
    var operand = value.eq(num++).text();
    var ex = value.eq(num++).text();
    var accuracy = value.eq(num++).text();
    var enabled = value.eq(num++).text();

    document.getElementById("widget.device_name").value = device_name;
    document.getElementById("widget.index.adc").value = index;
    document.getElementById("widget.factor_name").value = factor_name;
    setSelectByText("widget.cap_type", cap_type);
    document.getElementById("widget.range_down").value = range_down;
    document.getElementById("widget.range_up").value = range_up;
    document.getElementById("widget.server_center").value = server_center;
    document.getElementById("widget.operator").value = operator;
    document.getElementById("widget.operand").value = operand;
    document.getElementById("widget.ex").value = ex;
    document.getElementById("widget.accuracy").value = accuracy;
    if (enabled == "true") {
        document.getElementById("widget.enabled").checked = true;
    } else {
        document.getElementById("widget.enabled").checked = false;
    }

    openBox();
    switchPage("btnADC");
}

function editDataDI(object) {
    var row = $(object).parent().parent().parent().prevAll().length + 1;
    document.getElementById("page_type").value = row;
    var num = 0;
    var value = $(object).parent().parent().find("td");
    var device_name = value.eq(num++).text();
    var index = value.eq(num++).text();
    var factor_name = value.eq(num++).text();
    var mode = value.eq(num++).text();
    var count_method = value.eq(num++).text();
    var debounce_interval = value.eq(num++).text();
    var server_center = value.eq(num++).text();
    var operator = value.eq(num++).text();
    var operand = value.eq(num++).text();
    var ex = value.eq(num++).text();
    var accuracy = value.eq(num++).text();
    var enabled = value.eq(num++).text();

    document.getElementById("widget.device_name").value = device_name;
    document.getElementById("widget.index.di").value = index;
    document.getElementById("widget.factor_name").value = factor_name;
    setSelectByText("widget.mode", mode);
    setSelectByText("widget.count_method", count_method);
    document.getElementById("widget.debounce_interval").value = debounce_interval;
    document.getElementById("widget.server_center").value = server_center;
    document.getElementById("widget.operator").value = operator;
    document.getElementById("widget.operand").value = operand;
    document.getElementById("widget.ex").value = ex;
    document.getElementById("widget.accuracy").value = accuracy;
    if (enabled == "true") {
        document.getElementById("widget.enabled").checked = true;
    } else {
        document.getElementById("widget.enabled").checked = false;
    }

    openBox();
    switchPage("btnDI");
}

function editDataDO(object) {
    var row = $(object).parent().parent().parent().prevAll().length + 1;
    document.getElementById("page_type").value = row;
    var num = 0;
    var value = $(object).parent().parent().find("td");
    var device_name = value.eq(num++).text();
    var index = value.eq(num++).text();
    var factor_name = value.eq(num++).text();
    var init_status = value.eq(num++).text();
    var cur_status = value.eq(num++).text();
    var server_center = value.eq(num++).text();
    var operator = value.eq(num++).text();
    var operand = value.eq(num++).text();
    var ex = value.eq(num++).text();
    var accuracy = value.eq(num++).text();
    var enabled = value.eq(num++).text();

    document.getElementById("widget.device_name").value = device_name;
    document.getElementById("widget.index.do").value = index;
    document.getElementById("widget.factor_name").value = factor_name;
    setSelectByText("widget.init_status", init_status);
    document.getElementById("widget.cur_status").innerHTML = cur_status;
    document.getElementById("widget.server_center").value = server_center;
    document.getElementById("widget.operator").value = operator;
    document.getElementById("widget.operand").value = operand;
    document.getElementById("widget.ex").value = ex;
    document.getElementById("widget.accuracy").value = accuracy;
    if (enabled == "true") {
        document.getElementById("widget.enabled").checked = true;
    } else {
        document.getElementById("widget.enabled").checked = false;
    }

    openBox();
    switchPage("btnDO");
}

function setSelectByText(id, text)
{
    var select = document.getElementById(id);

    for (var i = 0; i < select.options.length; i++){  
        if (select.options[i].text == text){  
            select.options[i].selected = true;  
            break;  
        }  
    }  
}
