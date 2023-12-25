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
        case "ascii_conf":
            loadAsciiConfig();
            break;
        case "s7_conf":
            loadS7Config();
            break;
		case "fx_conf":
            loadFxConfig();
            break;
        case "mc_conf":
            loadMcConfig();
            break;
        case "io_conf":
            loadADCConfig();
            loadDIConfig();
            loadDOConfig();
            break;
        case "baccli_conf":
            loadBACnetClientConfig();
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
        case "firewall_conf":
            loadFirewall();
            break;
    }
}

function loadFirewall() {
    $('#loading').show();
    $.get('ajax/networking/get_firewall.php', function(data) {
        //console.log(data);
        jsonData = JSON.parse(data);
        // general
        var arrGeneral = ['synflood_protect', 'drop_invalid', 'input', 'output', 'forward'];

        arrGeneral.forEach(function (info) {
            if (info == null) {
                return true;    // continue: return true; break: return false
            }

            if ( info == 'synflood_protect' || info == 'drop_invalid') {
                $('#' + info).prop('checked', (jsonData[info] == '1') ? true:false);
            } else {
                $('#' + info).val(jsonData[info]);
            }
        })

        // forwards
        var arrForwards = ['name', 'proto', 'src_port', 'dest_ip', 'dest_port', 'enabled'];
        var forwardsCount = jsonData['forwards.count'];
        for (var i = 0; i < Number(forwardsCount); i++) {
            var table = document.getElementsByTagName("table")[0];
            var forwardsHtml = "<tr  class=\"tr cbi-section-table-descr\">\n";
            arrForwards.forEach(function (info) {
                if (info == null) {
                    return true;    // continue: return true; break: return false
                }

                forwardsHtml += "        <td style='text-align:center'>" + (jsonData['forwards.' + info][i] != null ? jsonData['forwards.' + info][i] : "-") + "</td>\n";
            })
            forwardsHtml += "        <td><a href=\"javascript:void(0);\" onclick=\"editForwards(this);\" >Edit</a></td>\n" +
                            "        <td><a href=\"javascript:void(0);\" onclick=\"delForwards(this);\" >Del</a></td>\n" +
                            "    </tr>";

            table.innerHTML += forwardsHtml;
        }

        result = getTableDataForwards();
        var dataForwards = JSON.stringify(result);
        $('#hidForwards').val(dataForwards);

        // traffic
        var arrTraffic = ['name', 'proto', 'rule', 'src_mac', 'src_ip', 'src_port',
                        'dest_ip', 'dest_port', 'action', 'enabled'];
        var trafficCount = jsonData['traffic.count'];
        for (var i = 0; i < Number(trafficCount); i++) {
            var table = document.getElementsByTagName("table")[1];
            var trafficHtml = "<tr  class=\"tr cbi-section-table-descr\">\n";
            arrTraffic.forEach(function (info) {
                if (info == null) {
                    return true;    // continue: return true; break: return false
                }

                trafficHtml += "        <td style='text-align:center'>" + (jsonData['traffic.' + info][i] != null ? jsonData['traffic.' + info][i] : "-") + "</td>\n";
            })
            trafficHtml += "        <td><a href=\"javascript:void(0);\" onclick=\"editTraffic(this);\" >Edit</a></td>\n" +
                            "        <td><a href=\"javascript:void(0);\" onclick=\"delTraffic(this);\" >Del</a></td>\n" +
                            "    </tr>";

            table.innerHTML += trafficHtml;
        }

        result = getTableDataTraffic();
        var dataTraffic = JSON.stringify(result);
        $('#hidTraffic').val(dataTraffic);
        $('#loading').hide();
    })
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
            $('#gps_enable').prop('checked', true);
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
            $('#gps_disable').prop('checked', true);
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
        // console.log(data);
        jsonData = JSON.parse(data);
        if (jsonData['type'] != 'off') {
            $('#page_role').show();
            if (jsonData['type'] == 'config') {
                $('#page_config').show();
                $('#page_ovpn').hide();
                $('#page_user_pwd').show();
            } else {
                $('#page_config').hide();
                $('#page_ovpn').show();
                $('#page_user_pwd').show();
            }

            if (jsonData['role'] == 'client') {
                $('#page_client').show();
                $('#page_server').hide();
            } else {
                $('#page_client').hide();
                $('#page_server').show();
            }

            if (jsonData['role'] == 'server') {
                $('#page_dh').show();
            } else {
                $('#page_dh').hide();
            }

            for(var key in jsonData){ 
                if (key == null) {
                    return true;    // continue: return true; break: return false
                }
                //console.log(key + ":" + jsonData[key]);
                if (key == 'ca' || key == 'ta' || key == 'cert' || key == 'key' || key == 'ovpn' || key == 'dh') {
                    $('#' + key + '_text').html(jsonData[key]); 
                } else if (key == 'comp_lzo' || key == 'enable_auth') {
                    $('#' + key).prop('checked', (jsonData[key] == '1') ? true:false);
                } else {
                    $('#' + key).val(jsonData[key]); 
                }
            }
        } else {
            $('#page_role').hide();
            $('#page_config').hide();
            $('#page_ovpn').hide();
            $('#page_user_pwd').hide();
        }
        
    });
}

function loadDataLorawan(){
    $.get('ajax/networking/get_loragw.php?type=lorawan', function(data) {
        jsonData = JSON.parse(data);

        var type = jsonData['type'];

        if (type != null) {
            $('#type').val(type);
        } else {
            $('#type').val('0');
        }

        typeChangeLorawan();
        
        var general = ['server_address', 'serv_port_up', 'serv_port_down', 'gateway_ID',
        'keepalive_interval', 'stat_interval', 'frequency'];

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

        // $general = array('protocol', 'uri', 'auth_mode', 'client_token');
        if (type == '2')
            $('#gateway_ID').val(jsonData['gateway_ID_station']);

        $('#protocol').val(jsonData['protocol']);
        $('#uri').val(jsonData['uri']);
        $('#auth_mode').val(jsonData['auth_mode']);
        modeChange();
    
        if (jsonData['lora_ca']) {
            $('#ca_text').html(jsonData['lora_ca']);
        }

        if (jsonData['lora_crt']) {
            $('#crt_text').html(jsonData['lora_crt']);
        }

        if (jsonData['lora_key']) {
            $('#key_text').html(jsonData['lora_key']);
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
        $('#enabled').val(jsonData.enabled);
        if (jsonData.enabled == '1') {
            $('#page_opcua').show();
            $('#opcua_enable').prop('checked', true);

			for(var key in jsonData){
                if (key == null) {
                    return true;    // continue: return true; break: return false
                }
                if (key == 'anonymous' || key == 'enable_database') {
                    $('#' + key).prop('checked', (jsonData[key] == '1') ? true:false);
                } else if (key == 'certificate') {
                    if (jsonData[key]) {
                        $('#cert_text').html(jsonData[key]);
                    }
                } else if (key == 'private_key') {
                    if (jsonData[key]) {
                        $('#key_text').html(jsonData[key]);
                    }
                } else if (key == 'trust_crt') {
                    if (jsonData[key]) {
                        $('#trust_text').html(jsonData[key]);
                    }
                } else {
                    $('#' + key).val(jsonData[key]);
                }
            }
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
        var jsonData = JSON.parse(data);
        loadChannelSelect(jsonData);
    });
}

function loadBasicConfig() {
    $('#loading').show();
    $.get('ajax/dct/get_dctcfg.php?type=basic',function(data){
        var jsonData = JSON.parse(data);
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
    $.get('ajax/dct/get_dctcfg.php?type=interface',function(data) {
        var jsonData = JSON.parse(data);
        var arrCom = ['baudrate', 'databit', 'stopbit', 'parity', 'com_frame_interval',
					'com_proto', 'com_cmd_interval', 'com_report_center'];

        for (var i = 1; i <= 4; i++) {
            $('#com_enabled' + i).val(jsonData['com_enabled' + i]);
            if (jsonData['com_enabled' + i] == '1') {
                $('#page_com' + i).show();
                $('#com_enable' + i).prop('checked', true);

                arrCom.forEach(function (info) {
                    if (jsonData[info + i] == null) {
                        return true;    // continue: return true; break: return false
                    }

                    $('#' + info + i).val(jsonData[info + i]);
                })

                if (jsonData['com_proto' + i] == '1') {
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
            $('#tcp_enabled' + i).val(jsonData['tcp_enabled' + i]);
            if (jsonData['tcp_enabled' + i] == '1') {
                $('#page_tcp' + i).show();
                $('#tcp_enable' + i).prop('checked', true);

                arrTcp.forEach(function (info) {
                    if (jsonData[info + i] == null) {
                        return true;    // continue: return true; break: return false
                    }

                    $('#' + info + i).val(jsonData[info + i]);
                })

                if (jsonData['tcp_proto' + i] == '2') {
                    //$('#rack' + i).val(jsonData.rack[i]);
                    //$('#slot' + i).val(jsonData.slot[i]);
                    $('#tcp_page_protocol_modbus' + i).hide();
                    $('#tcp_page_protocol_transparent' + i).hide(); 
                    $('#tcp_page_protocol_s7' + i).show(); 
                } else if (jsonData['tcp_proto' + i] == '1') {
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
    var table_name = 'modbus';
    $.get('ajax/dct/get_dctcfg.php?type=' + table_name,function(data){
        var jsonData = JSON.parse(data);
        var option_list = ['order', 'device_name', 'belonged_com', 'factor_name', 'device_id', 
                        'function_code', 'reg_addr', 'reg_count', 'data_type', 'server_center', 
                        'operator', 'operand', 'ex', 'accuracy', 'enabled'];
        var data_type_value = ['Unsigned 16Bits AB', 'Unsigned 16Bits BA', 'Signed 16Bits AB', 'Signed 16Bits BA',
                            'Unsigned 32Bits ABCD', 'Unsigned 32Bits BADC', 'Unsigned 32Bits CDAB', 'Unsigned 32Bits DCBA',
                            'Signed 32Bits ABCD', 'Signed 32Bits BADC', 'Signed 32Bits CDAB', 'Signed 32Bits DCBA',
                            'Float ABCD', 'Float BADC', 'Float CDAB', 'Float DCBA'];

        
        var len = Number(jsonData.length);
        for (var i = 0; i < len; i++) {
            var table = document.getElementById("table_" + table_name);
            var contents = '';
            contents += '<tr  class="tr cbi-section-table-descr">\n';
            for (key in jsonData[i]) {
                if (key == 'operator' || key == 'operand' || key == 'ex' || key == 'accuracy') {
                    contents += '   <td style="display:none" name="'+key+'">'+ (jsonData[i][key] != null ? jsonData[i][key] : "-") +'</td>\n';
                } else if (key == 'data_type') {
                    contents += '   <td style="text-align:center" name="'+key+'">'+ (data_type_value[Number(jsonData[i][key])]) +'</td>\n';
                } else if (key == 'enabled') {
                    contents += '   <td style="text-align:center"><input type="checkbox" name="' + key + '" ' + (jsonData[i][key] == '1' ? 'checked' : ' ') + ' onclick="updateData(\''+table_name+'\')"></td>\n';
                } else {
                    contents += '   <td style="text-align:center" name="'+key+'">'+ (jsonData[i][key] != null ? jsonData[i][key] : "-") +'</td>\n';
                }
            }
            contents += '   <td><a href="javascript:void(0);" onclick="editData(this, \''+table_name+'\');" >Edit</a></td>\n' +
                '       <td><a href="javascript:void(0);" onclick="delData(this, \''+table_name+'\');" >Del</a></td>\n' +
                '   </tr>';
            table.innerHTML += contents;
        }

        var result = get_table_data(table_name, option_list);
        var json_data = JSON.stringify(result);
        $('#hidTD_'+table_name).val(json_data);
        $('#option_list_'+table_name).val(option_list);
        $('#loading').hide();
    });
}

function loadAsciiConfig() {
    $('#loading').show();
    var table_name = 'ascii';
    $.get('ajax/dct/get_dctcfg.php?type=' + table_name,function(data){
        var jsonData = JSON.parse(data);
        var option_list = ['order', 'device_name', 'belonged_com', 'factor_name', 'tx_cmd', 
                        'cmd_format', 'server_center', 'enabled'];

        var len = Number(jsonData.length);

        for (var i = 0; i < len; i++) {
            var table = document.getElementById("table_" + table_name);

            var contents = '';
            contents += '<tr  class="tr cbi-section-table-descr">\n';
            for (key in jsonData[i]) {
                if (key == 'operator' || key == 'operand' || key == 'ex' || key == 'accuracy') {
                    contents += '   <td style="display:none" name="'+key+'">'+ (jsonData[i][key] != null ? jsonData[i][key] : "-") +'</td>\n';
                } else if (key == 'data_type') {
                    contents += '   <td style="text-align:center" name="'+key+'">'+ (data_type_value[Number(jsonData[i][key])]) +'</td>\n';
                } else if (key == 'enabled') {
                    contents += '   <td style="text-align:center"><input type="checkbox" name="' + key + '" ' + (jsonData[i][key] == '1' ? 'checked' : ' ') + ' onclick="updateData(\''+table_name+'\')"></td>\n';
                } else {
                    contents += '   <td style="text-align:center" name="'+key+'">'+ (jsonData[i][key] != null ? jsonData[i][key] : "-") +'</td>\n';
                }
            }
            contents += '   <td><a href="javascript:void(0);" onclick="editData(this, \''+table_name+'\');" >Edit</a></td>\n' +
                '       <td><a href="javascript:void(0);" onclick="delData(this, \''+table_name+'\');" >Del</a></td>\n' +
                '   </tr>';
            table.innerHTML += contents;
        }

        var result = get_table_data(table_name, option_list);
        var json_data = JSON.stringify(result);
        $('#hidTD_'+table_name).val(json_data);
        $('#option_list_'+table_name).val(option_list);
        $('#loading').hide();
    });
}

function loadS7Config() {
    $('#loading').show();
    var table_name = 's7';
    $.get('ajax/dct/get_dctcfg.php?type=' + table_name,function(data){
        var jsonData = JSON.parse(data);
        var option_list = ['order', 'device_name', 'belonged_com', 'factor_name', 'reg_type', 
                        'reg_addr', 'reg_count', 'word_len', 'server_center', 
                        'operator', 'operand', 'ex', 'accuracy', 'enabled'];
        var reg_type_value = ['I', 'Q', 'M', 'DB', 'V', 'C', 'T'];
        var word_len_value = ['Bit', 'Byte', 'Word', 'DWord', 'Real', 'Counter', 'Timer'];
        var len = Number(jsonData.length);

        for (var i = 0; i < len; i++) {
            var table = document.getElementById("table_" + table_name);
            var contents = '';
            contents += '<tr  class="tr cbi-section-table-descr">\n';
            for (key in jsonData[i]) {
                if (key == 'operator' || key == 'operand' || key == 'ex' || key == 'accuracy') {
                    contents += '   <td style="display:none" name="'+key+'">'+ (jsonData[i][key] != null ? jsonData[i][key] : "-") +'</td>\n';
                } else if (key == 'reg_type') {
                    contents += '   <td style="text-align:center" name="'+key+'">'+ (reg_type_value[Number(jsonData[i][key])]) +'</td>\n';
                } else if (key == 'word_len') {
                    contents += '   <td style="text-align:center" name="'+key+'">'+ (word_len_value[Number(jsonData[i][key])]) +'</td>\n';
                } else if (key == 'enabled') {
                    contents += '   <td style="text-align:center"><input type="checkbox" name="' + key + '" ' + (jsonData[i][key] == '1' ? 'checked' : ' ') + ' onclick="updateData(\''+table_name+'\')"></td>\n';
                } else {
                    contents += '   <td style="text-align:center" name="'+key+'">'+ (jsonData[i][key] != null ? jsonData[i][key] : "-") +'</td>\n';
                }
            }
            contents += '   <td><a href="javascript:void(0);" onclick="editData(this, \''+table_name+'\');" >Edit</a></td>\n' +
                '       <td><a href="javascript:void(0);" onclick="delData(this, \''+table_name+'\');" >Del</a></td>\n' +
                '   </tr>';
            table.innerHTML += contents;
        }

        var result = get_table_data(table_name, option_list);
        var json_data = JSON.stringify(result);
        $('#hidTD_'+table_name).val(json_data);
        $('#option_list_'+table_name).val(option_list);
        $('#loading').hide();
    });
}

function loadFxConfig() {
    $('#loading').show();
    var table_name = 'fx';
    $.get('ajax/dct/get_dctcfg.php?type=' + table_name,function(data){
        var jsonData = JSON.parse(data);
        var option_list = ['order', 'device_name', 'belonged_com', 'factor_name', 'reg_type', 
                        'reg_addr', 'reg_count', 'data_type', 'server_center', 
                        'operator', 'operand', 'ex', 'accuracy', 'enabled'];
        var reg_type_value = ['X', 'Y', 'M', 'S', 'D'];
        var data_type_value = ['Bit', 'Byte', 'Word', 'DWord', 'Real'];
        var len = Number(jsonData.length);

        for (var i = 0; i < len; i++) {
            var table = document.getElementById("table_" + table_name);
            var contents = '';
            contents += '<tr  class="tr cbi-section-table-descr">\n';
            for (key in jsonData[i]) {
                if (key == 'operator' || key == 'operand' || key == 'ex' || key == 'accuracy') {
                    contents += '   <td style="display:none" name="'+key+'">'+ (jsonData[i][key] != null ? jsonData[i][key] : "-") +'</td>\n';
                } else if (key == 'reg_type') {
                    contents += '   <td style="text-align:center" name="'+key+'">'+ (reg_type_value[Number(jsonData[i][key])]) +'</td>\n';
                } else if (key == 'data_type') {
                    contents += '   <td style="text-align:center" name="'+key+'">'+ (data_type_value[Number(jsonData[i][key])]) +'</td>\n';
                } else if (key == 'enabled') {
                    contents += '   <td style="text-align:center"><input type="checkbox" name="' + key + '" ' + (jsonData[i][key] == '1' ? 'checked' : ' ') + ' onclick="updateData(\''+table_name+'\')"></td>\n';
                } else {
                    contents += '   <td style="text-align:center" name="'+key+'">'+ (jsonData[i][key] != null ? jsonData[i][key] : "-") +'</td>\n';
                }
            }
            contents += '   <td><a href="javascript:void(0);" onclick="editData(this, \''+table_name+'\');" >Edit</a></td>\n' +
                '       <td><a href="javascript:void(0);" onclick="delData(this, \''+table_name+'\');" >Del</a></td>\n' +
                '   </tr>';
            table.innerHTML += contents;
        }

        var result = get_table_data(table_name, option_list);
        var json_data = JSON.stringify(result);
        $('#hidTD_'+table_name).val(json_data);
        $('#option_list_'+table_name).val(option_list);
        $('#loading').hide();
    });
}

function loadMcConfig() {
    $('#loading').show();
    var table_name = 'mc';
    $.get('ajax/dct/get_dctcfg.php?type=' + table_name, function(data){
        var jsonData = JSON.parse(data);
        var option_list = ['order', 'device_name', 'belonged_com', 'factor_name', 'data_area', 
                        'start_addr', 'reg_count', 'data_type', 'server_center', 
                        'operator', 'operand', 'ex', 'accuracy', 'enabled'];
        var data_type_value = ['Bit', 'Int', 'Float'];
        var len = Number(jsonData.length);

        for (var i = 0; i < len; i++) {
            var table = document.getElementById("table_" + table_name);
            var contents = '';
            contents += '<tr  class="tr cbi-section-table-descr">\n';
            for (key in jsonData[i]) {
                if (key == 'operator' || key == 'operand' || key == 'ex' || key == 'accuracy') {
                    contents += '   <td style="display:none" name="'+key+'">'+ (jsonData[i][key] != null ? jsonData[i][key] : "-") +'</td>\n';
                } else if (key == 'data_type') {
                    contents += '   <td style="text-align:center" name="'+key+'">'+ (data_type_value[Number(jsonData[i][key])]) +'</td>\n';
                } else if (key == 'enabled') {
                    contents += '   <td style="text-align:center"><input type="checkbox" name="' + key + '" ' + (jsonData[i][key] == '1' ? 'checked' : ' ') + ' onclick="updateData(\''+table_name+'\')"></td>\n';
                } else {
                    contents += '   <td style="text-align:center" name="'+key+'">'+ (jsonData[i][key] != null ? jsonData[i][key] : "-") +'</td>\n';
                }
            }
            contents += '   <td><a href="javascript:void(0);" onclick="editData(this, \''+table_name+'\');" >Edit</a></td>\n' +
                '       <td><a href="javascript:void(0);" onclick="delData(this, \''+table_name+'\');" >Del</a></td>\n' +
                '   </tr>';
            table.innerHTML += contents;
        }

        var result = get_table_data(table_name, option_list);
        var json_data = JSON.stringify(result);
        $('#hidTD_'+table_name).val(json_data);
        $('#option_list_'+table_name).val(option_list);
        $('#loading').hide();
    });
}

function loadADCConfig() {
    $('#loading').show();
    var table_name = 'adc';
    $.get('ajax/dct/get_dctcfg.php?type=' + table_name,function(data){
        var jsonData = JSON.parse(data);
        var option_list = ['device_name', 'index', 'factor_name', 'cap_type', 
                        'range_down', 'range_up', 'server_center', 
                        'operator', 'operand', 'ex', 'accuracy', 'enabled'];
        var cap_type_value = ['4-20mA', '0-10V'];
        var len = Number(jsonData.length);
        var model = document.getElementById("model").value;

        if (model == "EG500") {
            for (var i = 0; i < len; i++) {
                var table = document.getElementById("table_" + table_name);
                var contents = '';
                contents += '<tr  class="tr cbi-section-table-descr">\n';
                for (key in jsonData[i]) {
                    if (key == 'operator' || key == 'operand' || key == 'ex' || key == 'accuracy') {
                        contents += '   <td style="display:none" name="'+key+'">'+ (jsonData[i][key] != null ? jsonData[i][key] : "-") +'</td>\n';
                    } else if (key == 'cap_type') {
                        contents += '   <td style="text-align:center" name="'+key+'">'+ (cap_type_value[Number(jsonData[i][key])]) +'</td>\n';
                    } else if (key == 'enabled') {
                        contents += '   <td style="text-align:center"><input type="checkbox" name="' + key + '" ' + (jsonData[i][key] == '1' ? 'checked' : ' ') + ' onclick="updateData(\''+table_name+'\')"></td>\n';
                    } else {
                        contents += '   <td style="text-align:center" name="'+key+'">'+ (jsonData[i][key] != null ? jsonData[i][key] : "-") +'</td>\n';
                    }
                }
                contents += '   <td><a href="javascript:void(0);" onclick="editData(this, \''+table_name+'\');" >Edit</a></td>\n' +
                    '       <td><a href="javascript:void(0);" onclick="delData(this, \''+table_name+'\');" >Del</a></td>\n' +
                    '   </tr>';
                table.innerHTML += contents;
            }

            var result = get_table_data(table_name, option_list);
            var json_data = JSON.stringify(result);
            $('#hidTD_'+table_name).val(json_data);
            $('#option_list_'+table_name).val(option_list);
        }

        $('#loading').hide();
    });
}

function loadDIConfig() {
    $('#loading').show();
    var table_name = 'di';
    $.get('ajax/dct/get_dctcfg.php?type=' + table_name,function(data){
        var jsonData = JSON.parse(data);
        var option_list = ['device_name', 'index', 'factor_name', 'mode', 
                        'count_method', 'debounce_interval', 'server_center', 
                        'operator', 'operand', 'ex', 'accuracy', 'enabled'];
        var model = document.getElementById("model").value;
        var table_num = [];
        if (model == "EG500") {
            table_num = 1;
        } else if (model == "EG410") {
            table_num = 0;
        }

        var mode_value = ['Counting Mode', 'Status Mode'];
        var method_value = ['Rising Edge', 'Falling Edge'];
        var len = Number(jsonData.length);

        for (var i = 0; i < len; i++) {
            var table = document.getElementById("table_" + table_name);
            var contents = '';
            contents += '<tr  class="tr cbi-section-table-descr">\n';
            for (key in jsonData[i]) {
                if (key == 'operator' || key == 'operand' || key == 'ex' || key == 'accuracy') {
                    contents += '   <td style="display:none" name="'+key+'">'+ (jsonData[i][key] != null ? jsonData[i][key] : "-") +'</td>\n';
                } else if (key == 'mode') {
                    contents += '   <td style="text-align:center" name="'+key+'">'+ (mode_value[Number(jsonData[i][key])]) +'</td>\n';
                } else if (key == 'count_method') {
                    contents += '   <td style="text-align:center" name="'+key+'">'+ (method_value[Number(jsonData[i][key])]) +'</td>\n';
                } else if (key == 'enabled') {
                    contents += '   <td style="text-align:center"><input type="checkbox" name="' + key + '" ' + (jsonData[i][key] == '1' ? 'checked' : ' ') + ' onclick="updateData(\''+table_name+'\')"></td>\n';
                } else {
                    contents += '   <td style="text-align:center" name="'+key+'">'+ (jsonData[i][key] != null ? jsonData[i][key] : "-") +'</td>\n';
                }
            }
            contents += '   <td><a href="javascript:void(0);" onclick="editData(this, \''+table_name+'\');" >Edit</a></td>\n' +
                '       <td><a href="javascript:void(0);" onclick="delData(this, \''+table_name+'\');" >Del</a></td>\n' +
                '   </tr>';
            table.innerHTML += contents;
        }

        var result = get_table_data(table_name, option_list);
        var json_data = JSON.stringify(result);
        $('#hidTD_'+table_name).val(json_data);
        $('#option_list_'+table_name).val(option_list);

        $('#loading').hide();
    });
}

function loadDOConfig() {
    $('#loading').show();
    var table_name = 'do';
    $.get('ajax/dct/get_dctcfg.php?type=' + table_name,function(data){
        var jsonData = JSON.parse(data);
        var option_list = ['device_name', 'index', 'factor_name', 'init_status', 
                        'cur_status', 'server_center', 'operator', 'operand', 'ex', 'accuracy', 'enabled'];
        var model = document.getElementById("model").value;
        var table_num = [];
        if (model == "EG500") {
            table_num = 2;
        } else if (model == "EG410") {
            table_num = 1;
        }
        var status_value = ['Open', 'Close'];
        var len = Number(jsonData.length);

        for (var i = 0; i < len; i++) {
            var table = document.getElementById("table_" + table_name);
            var contents = '';
            contents += '<tr  class="tr cbi-section-table-descr">\n';
            for (key in jsonData[i]) {
                if (key == 'operator' || key == 'operand' || key == 'ex' || key == 'accuracy') {
                    contents += '   <td style="display:none" name="'+key+'">'+ (jsonData[i][key] != null ? jsonData[i][key] : "-") +'</td>\n';
                } else if (key == 'init_status' || key == 'cur_status') {
                    contents += '   <td style="text-align:center" name="'+key+'">'+ (status_value[Number(jsonData[i][key])]) +'</td>\n';
                } else if (key == 'enabled') {
                    contents += '   <td style="text-align:center"><input type="checkbox" name="' + key + '" ' + (jsonData[i][key] == '1' ? 'checked' : ' ') + ' onclick="updateData(\''+table_name+'\')"></td>\n';
                } else {
                    contents += '   <td style="text-align:center" name="'+key+'">'+ (jsonData[i][key] != null ? jsonData[i][key] : "-") +'</td>\n';
                }
            }
            contents += '   <td><a href="javascript:void(0);" onclick="editData(this, \''+table_name+'\');" >Edit</a></td>\n' +
                '       <td><a href="javascript:void(0);" onclick="delData(this, \''+table_name+'\');" >Del</a></td>\n' +
                '   </tr>';
            table.innerHTML += contents;
        }

        var result = get_table_data(table_name, option_list);
        var json_data = JSON.stringify(result);
        $('#hidTD_'+table_name).val(json_data);
        $('#option_list_'+table_name).val(option_list);

        $('#loading').hide();
    });
}

function loadBACnetClientConfig() {
    $('#loading').show();
    var table_name = 'baccli';
    $.get('ajax/dct/get_dctcfg.php?type=' + table_name,function(data){
        var jsonData = JSON.parse(data);
        if (jsonData == null)
            return;

        var arr = ['ip_address', 'port', 'device_id', 'name'];

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

        var tmpData = jsonData.baccli;
        var option_list = ['order', 'device_name', 'factor_name', 'object_id', 
                        'server_center', 'operator', 'operand', 'ex', 'accuracy', 'enabled'];
        var len = Number(tmpData.length);

        for (var i = 0; i < len; i++) {
            var table = document.getElementById("table_" + table_name);
            var contents = '';
            contents += '<tr  class="tr cbi-section-table-descr">\n';
            for (key in tmpData[i]) {
                if (key == 'operator' || key == 'operand' || key == 'ex' || key == 'accuracy') {
                    contents += '   <td style="display:none" name="'+key+'">'+ (tmpData[i][key] != null ? tmpData[i][key] : "-") +'</td>\n';
                } else if (key == 'enabled') {
                    contents += '   <td style="text-align:center"><input type="checkbox" name="' + key + (table_name == 'baccli' ? '_baccli' : '') + '" ' +
                    (tmpData[i][key] == '1' ? 'checked' : ' ') + ' onclick="updateData(\''+table_name+'\')"></td>\n';
                } else {
                    contents += '   <td style="text-align:center" name="'+key+'">'+ (tmpData[i][key] != null ? tmpData[i][key] : "-") +'</td>\n';
                }
            }
            contents += '   <td><a href="javascript:void(0);" onclick="editData(this, \''+table_name+'\');" >Edit</a></td>\n' +
                '       <td><a href="javascript:void(0);" onclick="delData(this, \''+table_name+'\');" >Del</a></td>\n' +
                '   </tr>';
            table.innerHTML += contents;
        }

        var result = get_table_data(table_name, option_list);
        var json_data = JSON.stringify(result);
        $('#hidTD_'+table_name).val(json_data);
        $('#option_list_'+table_name).val(option_list);

        $('#loading').hide();
    });
}

function loadServerConfig() {
    $('#loading').show();
    $.get('ajax/dct/get_dctcfg.php?type=server',function(data){
        var jsonData = JSON.parse(data);

        var arr = ["proto", "encap_type", "server_addr", "http_url", "server_port", "cache_enabled", 
        "register_packet", "register_packet_hex", "heartbeat_packet", "heartbeat_packet_hex", "heartbeat_interval",
        "mqtt_heartbeat_interval", "mqtt_pub_topic", "mqtt_sub_topic", "mqtt_username", "mqtt_password", 
        "mqtt_client_id", "mqtt_tls_enabled", "certificate_type", "mqtt_ca", "mqtt_cert", "mqtt_key", 
        "self_define_var", "var_name1_", "var_value1_", "var_name2_", "var_value2_", "var_name3_", "var_value3_", 
        "mn", "st", "pw"];

        for (var i = 1; i <= 5; i++) {
            $('#enabled' + i).val(jsonData['enabled' + i]);
            if (jsonData['enabled' + i] == '1') {
                $('#page_server' + i).show();
                $('#enable' + i).prop('checked', true);
                arr.forEach(function (info) {
                    if (info == "cache_enabled" || info == "register_packet_hex" || info == "heartbeat_packet_hex" ||
                        info == "mqtt_tls_enabled" ||  info == "self_define_var") {
                        $('#' + info + i).prop('checked', (jsonData[info + i] == '1') ? true:false);
                    } else if (info == "mqtt_ca") {
                        if (jsonData['mqtt_ca' + i]) {
                            $('#ca_text' + i).html(jsonData['mqtt_ca' + i]);
                        }
                    } else if (info == "mqtt_cert") {
                        if (jsonData['mqtt_cert' + i]) {
                            $('#cer_text' + i).html(jsonData['mqtt_cert' + i]);
                        }
                    } else if (info == "mqtt_key") {
                        if (jsonData['mqtt_key' + i]) {
                            $('#key_text' + i).html(jsonData['mqtt_key' + i]);
                        }
                    } else {
                        $('#' + info + i).val(jsonData[info + i]);
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

// $(document).ready(function(){
//   $("#PanelManual").hide();
// });

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

function selectOperator(table_name) {
    if (document.getElementById(table_name+'.operator')) {
        var operator = document.getElementById(table_name+'.operator').value;

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
}

function openBox(table_name) {
    $('#popBox').show();
    $('#popLayer').show();
    document.getElementById("popBox").scrollTop = 0;
    selectOperator(table_name);
}

function openConfBox() {
    $('#confBox').show();
    $('#confLayer').show();
    document.getElementById("confBox").scrollTop = 0;
}

function closeBox() {
    $('#popBox').hide();
    $('#popLayer').hide();
}

function closeConfBox() {
    $('#confBox').hide();
    $('#confLayer').hide();
}

function addData(table_name) {
    openBox(table_name);
    document.getElementById("page_type").value = "0"; /* 0 is add. other is edit */
}

function conf_im_ex(conf_name) {
    document.getElementById('title').innerHTML = conf_name + ' Configure Import Export';
    openConfBox();
    if (conf_name == "ADC") {
        document.getElementById("page_im_ex_name").value = "adc";
    } else if (conf_name == "DI") {
        document.getElementById("page_im_ex_name").value = "di";
        selectMode();
    } else if (conf_name == "DO") {
        document.getElementById("page_im_ex_name").value = "do";
    }
}

// modbus
function get_table_data(table_name, option_list) {
    var tr = $('#table_' + table_name + ' tr');
    var result = [];
    for (var i = 2; i < tr.length; i++) {
        var tds = $(tr[i]).find("td");
        if (tds.length > 0) {
            var tmp = [];
            var num = 0;
            tmp += '{';
            option_list.forEach(function (option) {
                if (option == 'enabled') {
                    tmp += '"' + option + '":"' + ($($(tds[num++]).find('input'))[0].checked ? 1 : 0) + '",';
                } else {
                    tmp += '"' + option + '":"' + $(tds[num++]).html() + '",';
                }
            })

            tmp = tmp.slice(-1) === "," ? tmp.slice(0, -1) + "}" : tmp + "}";
            var obj = JSON.parse(tmp);
            result.push(obj);
        }
    }

    return result;
}

function saveData(table_name) {
    var result = [];
    var option_value = [];
    var io_type;
    var data_type_value = [];
    var reg_type_value = [];
    var word_len_value = [];
    var cap_type_value = ['4-20mA', '0-10V'];
    var mode_value = ['Counting Mode', 'Status Mode'];
    var count_method_value = ['Rising Edge', 'Falling Edge'];
    var status_value = ['Open', 'Close'];

    if (table_name == 'modbus') {
        data_type_value = ['Unsigned 16Bits AB', 'Unsigned 16Bits BA', 'Signed 16Bits AB', 'Signed 16Bits BA',
        'Unsigned 32Bits ABCD', 'Unsigned 32Bits BADC', 'Unsigned 32Bits CDAB', 'Unsigned 32Bits DCBA',
        'Signed 32Bits ABCD', 'Signed 32Bits BADC', 'Signed 32Bits CDAB', 'Signed 32Bits DCBA',
        'Float ABCD', 'Float BADC', 'Float CDAB', 'Float DCBA'];
    } else if (table_name == 'fx') {
        data_type_value = ['Bit', 'Byte', 'Word', 'DWord', 'Real'];
        reg_type_value = ['X', 'Y', 'M', 'S', 'D'];
    } else if (table_name == 's7') {
        reg_type_value = ['I', 'Q', 'M', 'DB', 'V', 'C', 'T'];
        word_len_value = ['Bit', 'Byte', 'Word', 'DWord', 'Real', 'Counter', 'Timer'];
    } else if (table_name == 'mc') {
        data_type_value = ['Bit', 'Int', 'Float'];
    }

    var page_type = document.getElementById("page_type").value;
    var tmp = $('#option_list_'+table_name).val();
    var option_list = tmp.split(",");

    if (table_name == 'adc' || table_name == 'di' || table_name == 'do') {
        io_type = table_name;
        table_name = 'io';
    }

    option_list.forEach(function (option) {
        if (option == 'data_type') {
            option_value[option] = data_type_value[Number(document.getElementById(table_name + '.'  + option).value)];
        } else if (option == 'reg_type') {
            option_value[option] = reg_type_value[Number(document.getElementById(table_name + '.'  + option).value)];
        } else if (option == 'word_len') {
            option_value[option] = word_len_value[Number(document.getElementById(table_name + '.'  + option).value)];
        } else if (option == 'cap_type') {
            option_value[option] = cap_type_value[Number(document.getElementById(table_name + '.'  + option).value)];
        } else if (option == 'mode') {
            option_value[option] = mode_value[Number(document.getElementById(table_name + '.'  + option).value)];
        } else if (option == 'count_method') {
            option_value[option] = count_method_value[Number(document.getElementById(table_name + '.'  + option).value)];
        } else if (option == 'init_status') {
            option_value[option] = status_value[Number(document.getElementById(table_name + '.'  + option).value)];
        } else if (option == 'cur_status') {
            option_value[option] = status_value[Number(document.getElementById(table_name + '.'  + option).innerHTML)];
        } else if (option == 'enabled') {
            option_value[option] = document.getElementById(table_name + '.'  + option).checked ? '1' : '0';
        } else if (option == 'index') {
            option_value[option] = document.getElementById(table_name + '.'  + option + '.' + io_type).value;
        } else {
            option_value[option] = document.getElementById(table_name + '.'  + option).value;
        }
        
    })

    if (option_value['belonged_com'] == "No Interface Is Enabled") {
        alert("No Interface Is Enabled, please enabled the interface first.");
        return;
    }

    if (table_name == 'io')
        table_name = io_type;

    var table = document.getElementById("table_" + table_name);
    if (page_type == "0") {
        var contents = '';
        contents += '<tr  class="tr cbi-section-table-descr">\n';
        option_list.forEach(function(option){
            // console.log(option);
            if (option == 'operator' || option == 'operand' || option == 'ex' || option == 'accuracy') {
                contents += '   <td style="display:none" name="'+option+'">'+ (option_value[option].length > 0 ? option_value[option] : "-") +'</td>\n';
            } else if (option == 'enabled') {
                contents += '   <td style="text-align:center"><input type="checkbox" name="' + option + (table_name == 'baccli' ? '_baccli' : '') +'" ' +
                (option_value[option] == '1' ? 'checked' : ' ') + ' onclick="updateData(\''+table_name+'\')"></td>\n';
            } else {
                contents += '   <td style="text-align:center" name="'+option+'">'+ (option_value[option].length > 0 ? option_value[option] : "-") +'</td>\n';
            }
        })
        contents += '   <td><a href="javascript:void(0);" onclick="editData(this, \''+table_name+'\');" >Edit</a></td>\n' +
            '       <td><a href="javascript:void(0);" onclick="delData(this, \''+table_name+'\');" >Del</a></td>\n' +
            '   </tr>';
        table.innerHTML += contents;
    } else {
        var num = 0;
        option_list.forEach(function (option){
            if (option == 'enabled') {
                // 获取所有的checkbox元素
                var checkboxes = table.getElementsByTagName("input");
                // 遍历checkbox元素
                for (var i = 0; i < checkboxes.length; i++) {
                    // 判断是否为checkbox类型
                    if (checkboxes[i].type === "checkbox") {
                        if (i == Number(page_type) - 2) {
                            checkboxes[i].checked = option_value[option] == '1' ? true : false;
                            break;
                        }    
                    }
                }
            } else {
                table.rows[Number(page_type)].cells[num++].innerHTML = (option_value[option].length > 0 ? option_value[option] : "-");
            }
        })
    }

    result = get_table_data(table_name, option_list);
    // console.log(result);
    var json_data = JSON.stringify(result);
    $('#hidTD_'+table_name).val(json_data);
    closeBox();
}

function updateData(table_name) {
    var tmp = $('#option_list_'+table_name).val();
    var option_list = tmp.split(",");

    var result = get_table_data(table_name, option_list);
    var json_data = JSON.stringify(result);
    $('#hidTD_'+table_name).val(json_data);
}

function delData(object, table_name) {
    var table = object.parentNode.parentNode.parentNode;
    var tr = object.parentNode.parentNode;
    var tmp = $('#option_list_'+table_name).val();

    var option_list = tmp.split(",");
    table.removeChild(tr);

    var result = get_table_data(table_name, option_list);
    var json_data = JSON.stringify(result);
    $('#hidTD_'+table_name).val(json_data);
}

function editData(object, table_name) {
    var row = $(object).parent().parent().parent().prevAll().length + 1;
    document.getElementById("page_type").value = row;
    var num = 0;
    var tmp = $('#option_list_'+table_name).val();
    var option_list = tmp.split(",");
    var value = $(object).parent().parent().find("td");
    var io_type;

    if (table_name == 'adc' || table_name == 'di' || table_name == 'do') {
        io_type = table_name;
        table_name = 'io';
    }

    option_list.forEach(function(option) {
        if (option == 'data_type' || option == 'reg_type' || option == 'word_len' || option == 'cap_type' ||
            option == 'cap_type' || option == 'mode' || option == 'count_method' || option == 'init_status') {
            setSelectByText(table_name + '.'  + option, value.eq(num++).text());
        } else if (option == 'index') {
            document.getElementById(table_name + '.'  + option + '.' + io_type).value = value.eq(num++).text();
        } else if (option == 'enabled') {
            document.getElementById(table_name + '.'  + option).checked = value.eq(num++).find("input")[0].checked;
        } else {
            document.getElementById(table_name + '.'  + option).value = value.eq(num++).text();
        }
    })
    
    openBox(table_name);
    if (table_name == 'io')
        switchPage('btn' + io_type.toUpperCase());
}

function selectMode() {
    var mode = document.getElementById("io.mode").value;

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

function addDataIO(object, table_name) {
    openBox(table_name);
    document.getElementById("page_type").value = "0"; /* 0 is add. other is edit */
    var name = object.name;
    switchPage(name);
}

function saveDataIO() {
    var page_name = document.getElementById("page_name").value;

    if (page_name == "0") {
        saveData('adc');
    } else if (page_name == "1") {
        saveData('di');
    } else {
        saveData('do');
    }

    closeBox();
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

function comProtocolChange(num) {
    var numStr = num.toString();
    var protocol = document.getElementById("com_proto" + numStr).value;

    if (protocol == "1") {
        $('#com_page_protocol_modbus' + numStr).hide();
        $('#com_page_protocol_transparent' + numStr).show();
    } else {
        $('#com_page_protocol_modbus' + numStr).show();
        $('#com_page_protocol_transparent' + numStr).hide();
    }
}

function tcpProtocolChange(num) {
    var numStr = num.toString();
    var protocol = document.getElementById('tcp_proto' + numStr).value;

    if (protocol == '1') {
        $('#tcp_page_protocol_modbus' + numStr).hide();
        $('#tcp_page_protocol_transparent' + numStr).show();
        $('#tcp_page_protocol_s7' + numStr).hide();
    } else if (protocol == '2') {
        $('#tcp_page_protocol_modbus' + numStr).hide();
        $('#tcp_page_protocol_transparent' + numStr).hide();
        $('#tcp_page_protocol_s7' + numStr).show();
    } else {
        $('#tcp_page_protocol_modbus' + numStr).show();
        $('#tcp_page_protocol_transparent' + numStr).hide();
        $('#tcp_page_protocol_s7' + numStr).hide();
    }
}

function enableCom(state, num) {
    var numStr = num.toString();

    if (state) {
        $('#page_com' + numStr).show();
        comProtocolChange(num);
    } else {
        $('#page_com' + numStr).hide();
    }
}

function enableTcp(state, num) {
    var numStr = num.toString();

    if (state) {
        $('#page_tcp' + numStr).show();
        tcpProtocolChange(num);
    } else {
        $('#page_tcp' + numStr).hide();
    }
}

function freqPlanChange() {
    var a = document.getElementById('frequency').value;
    if (a == '0') { // EU868
        $('#radio0_frequency').val('867500000');
        $('#radio0_tx_min').val('863000000');
        $('#radio0_tx_max').val('870000000');
        $('#radio1_frequency').val('868500000');
    } else if (a == '1') { // CN490
        $('#radio0_frequency').val('471400000');
        $('#radio0_tx_min').val('500000000');
        $('#radio0_tx_max').val('510000000');
        $('#radio1_frequency').val('475000000');
    } else if (a == '2') { // US915
        $('#radio0_frequency').val('904300000');
        $('#radio0_tx_min').val('923000000');
        $('#radio0_tx_max').val('928000000');
        $('#radio1_frequency').val('905000000');
    } else if (a == '3') { // AU915
        $('#radio0_frequency').val('917200000');
        $('#radio0_tx_min').val('915000000');
        $('#radio0_tx_max').val('928000000');
        $('#radio1_frequency').val('917900000');
    }  else if (a == '4') { // AS923
        $('#radio0_frequency').val('922300000');
        $('#radio0_tx_min').val('920000000');
        $('#radio0_tx_max').val('924000000');
        $('#radio1_frequency').val('923100000');
    }
}

// Forwards
function openBoxForwards() {
    $('#forwards_popBox').show();
    $('#forwards_popLayer').show();
    document.getElementById("forwards_popBox").scrollTop = 0;
}

function closeBoxForwards() {
    $('#forwards_popBox').hide();
    $('#forwards_popLayer').hide();
}

function addDataForwards() {
    openBoxForwards();
    document.getElementById("forwards.page_type").value = "0"; /* 0 is add. other is edit */
}

function getTableDataForwards() {
    var tr = $("#table_forwards tr");
    var result = [];
    for (var i = 2; i < tr.length; i++) {
        var tds = $(tr[i]).find("td");
        if (tds.length > 0) {
            result.push({
                'name':$(tds[0]).html(), 
                'proto':$(tds[1]).html(),
                'src_port':$(tds[2]).html(),
                'dest_ip':$(tds[3]).html(),
                'dest_port':$(tds[4]).html(),
                'enabled':$(tds[5]).html()
            });
        }
    }

    return result;
}
  
function delForwards(object) {
    var table = object.parentNode.parentNode.parentNode;
    var tr = object.parentNode.parentNode;
    table.removeChild(tr);

    var result = getTableDataForwards();
    var json_data = JSON.stringify(result);
    $('#hidForwards').val(json_data);
}

function saveForwards() {
    var name = document.getElementById("forwards.name").value;
    var proto = document.getElementById("forwards.proto").value;
    var src_port = document.getElementById("forwards.src_port").value;
    var dest_ip = document.getElementById("forwards.dest_ip").value;
    var dest_port = document.getElementById("forwards.dest_port").value;
    var enabled = document.getElementById("forwards.enabled").checked;
    var page_type = document.getElementById("forwards.page_type").value;

    if (proto == 'any' || proto == 'icmp' || proto == 'gre') {
        src_port = '-';
        dest_port = '-';
    }

    if (page_type == "0") {
        var table = document.getElementsByTagName("table")[0];
        table.innerHTML += "<tr  class=\"tr cbi-section-table-descr\">\n" +
            "        <td style='text-align:center'>"+ (name.length > 0 ? name : "-") +"</td>\n" +
            "        <td style='text-align:center'>"+ (proto.length > 0 ? proto : "-") +"</td>\n" +
            "        <td style='text-align:center'>"+ (src_port.length > 0 ? src_port : "-") +"</td>\n" +
            "        <td style='text-align:center'>"+ (dest_ip.length > 0 ? dest_ip : "-") +"</td>\n" +
            "        <td style='text-align:center'>"+ (dest_port.length > 0 ? dest_port : "-") +"</td>\n" +
            "        <td style='text-align:center'>"+ enabled +"</td>\n" +
            "        <td><a href=\"javascript:void(0);\" onclick=\"editForwards(this);\" >Edit</a></td>\n" +
            "        <td><a href=\"javascript:void(0);\" onclick=\"delForwards(this);\" >Del</a></td>\n" +
            "    </tr>";
    } else {
        var table = document.getElementById("table_forwards");
        var num = 0;
        table.rows[Number(page_type)].cells[num++].innerHTML = (name.length > 0 ? name : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = (proto.length > 0 ? proto : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = (src_port.length > 0 ? src_port : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = (dest_ip.length > 0 ? dest_ip : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = (dest_port.length > 0 ? dest_port : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = enabled;
    }

    result = getTableDataForwards();
    var json_data = JSON.stringify(result);
    $('#hidForwards').val(json_data);
    closeBoxForwards();
}

function editForwards(object) {
    var row = $(object).parent().parent().parent().prevAll().length + 1;
    var num = 0;
    document.getElementById("forwards.page_type").value = row;

    var value = $(object).parent().parent().find("td");
    var name = value.eq(num++).text();
    var proto = value.eq(num++).text();
    var src_port = value.eq(num++).text();
    var dest_ip = value.eq(num++).text();
    var dest_port = value.eq(num++).text();
    var enabled = value.eq(num++).text();

    document.getElementById("forwards.name").value = name;
    document.getElementById("forwards.proto").value = proto;
    document.getElementById("forwards.src_port").value = src_port;
    document.getElementById("forwards.dest_ip").value = dest_ip;
    document.getElementById("forwards.dest_port").value = dest_port;
    if (enabled == "true") {
        document.getElementById("forwards.enabled").checked = true;
    } else {
        document.getElementById("forwards.enabled").checked = false;
    }

    if (proto == 'tcp udp' || proto == 'tcp' || proto == 'udp' || proto == 'stcp') {
        $('#pageEport').show();
        $('#pageIPort').show();
    } else {
        $('#pageEport').hide();
        $('#pageIPort').hide();
    }

    openBoxForwards();
}

// traffic
function openBoxTraffic() {
    $('#traffic_popBox').show();
    $('#traffic_popLayer').show();
    document.getElementById("traffic_popBox").scrollTop = 0;
}

function closeBoxTraffic() {
    $('#traffic_popBox').hide();
    $('#traffic_popLayer').hide();
}

function addDataTraffic() {
    openBoxTraffic();
    document.getElementById("traffic.page_type").value = "0"; /* 0 is add. other is edit */
}

function getTableDataTraffic() {
    var tr = $("#table_traffic tr");
    var result = [];
    for (var i = 2; i < tr.length; i++) {
        var tds = $(tr[i]).find("td");
        if (tds.length > 0) {
            result.push({
                'name':$(tds[0]).html(), 
                'proto':$(tds[1]).html(),
                'rule':$(tds[2]).html(),
                'src_mac':$(tds[3]).html(),
                'src_ip':$(tds[4]).html(),
                'src_port':$(tds[5]).html(),
                'dest_ip':$(tds[6]).html(),
                'dest_port':$(tds[7]).html(),
                'action':$(tds[8]).html(),
                'enabled':$(tds[9]).html()
            });
        }
    }

    return result;
}
  
function delTraffic(object) {
    var table = object.parentNode.parentNode.parentNode;
    var tr = object.parentNode.parentNode;
    table.removeChild(tr);

    var result = getTableDataTraffic();
    var json_data = JSON.stringify(result);
    $('#hidTraffic').val(json_data);
}

function saveTraffic() {
    var name = document.getElementById("traffic.name").value;
    var proto = document.getElementById("traffic.proto").value;
    var rule = document.getElementById("traffic.rule").value;
    var src_mac = document.getElementById("traffic.src_mac").value;
    var src_ip = document.getElementById("traffic.src_ip").value;
    var src_port = document.getElementById("traffic.src_port").value;
    var dest_ip = document.getElementById("traffic.dest_ip").value;
    var dest_port = document.getElementById("traffic.dest_port").value;
    var action = document.getElementById("traffic.action").value;
    var enabled = document.getElementById("traffic.enabled").checked;
    var page_type = document.getElementById("traffic.page_type").value;

    if (proto == 'any' || proto == 'icmp' || proto == 'gre') {
        src_port = '-';
        dest_port = '-';
    }

    if (page_type == "0") {
        var table = document.getElementsByTagName("table")[1];
        table.innerHTML += "<tr  class=\"tr cbi-section-table-descr\">\n" +
            "        <td style='text-align:center'>"+ (name.length > 0 ? name : "-") +"</td>\n" +
            "        <td style='text-align:center'>"+ (proto.length > 0 ? proto : "-") +"</td>\n" +
            "        <td style='text-align:center'>"+ (rule.length > 0 ? rule : "-") +"</td>\n" +
            "        <td style='text-align:center'>"+ (src_mac.length > 0 ? src_mac : "-") +"</td>\n" +
            "        <td style='text-align:center'>"+ (src_ip.length > 0 ? src_ip : "-") +"</td>\n" +
            "        <td style='text-align:center'>"+ (src_port.length > 0 ? src_port : "-") +"</td>\n" +
            "        <td style='text-align:center'>"+ (dest_ip.length > 0 ? dest_ip : "-") +"</td>\n" +
            "        <td style='text-align:center'>"+ (dest_port.length > 0 ? dest_port : "-") +"</td>\n" +
            "        <td style='text-align:center'>"+ (action.length > 0 ? action : "-") +"</td>\n" +
            "        <td style='text-align:center'>"+ enabled +"</td>\n" +
            "        <td><a href=\"javascript:void(0);\" onclick=\"editTraffic(this);\" >Edit</a></td>\n" +
            "        <td><a href=\"javascript:void(0);\" onclick=\"delTraffic(this);\" >Del</a></td>\n" +
            "    </tr>";
    } else {
        var table = document.getElementById("table_traffic");
        var num = 0;
        table.rows[Number(page_type)].cells[num++].innerHTML = (name.length > 0 ? name : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = (proto.length > 0 ? proto : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = (rule.length > 0 ? rule : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = (src_mac.length > 0 ? src_mac : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = (src_ip.length > 0 ? src_ip : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = (src_port.length > 0 ? src_port : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = (dest_ip.length > 0 ? dest_ip : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = (dest_port.length > 0 ? dest_port : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = (action.length > 0 ? action : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = enabled;
    }

    result = getTableDataTraffic();
    var json_data = JSON.stringify(result);
    $('#hidTraffic').val(json_data);
    closeBoxTraffic();
}

function editTraffic(object) {
    var row = $(object).parent().parent().parent().prevAll().length + 1;
    var num = 0;
    document.getElementById("traffic.page_type").value = row;

    var value = $(object).parent().parent().find("td");

    var arrTraffic = ['name', 'proto', 'rule', 'src_mac', 'src_ip', 'src_port', 
                        'dest_ip', 'dest_port', 'action', 'enabled'];
    
    arrTraffic.forEach(function (info) {
        if (info == null) {
            return true;    // continue: return true; break: return false
        }

        var tmp = value.eq(num++).text();
        if (info == 'enabled') {
            if (tmp == 'true') {
                document.getElementById('traffic.' + info).checked = true;
            } else {
                document.getElementById('traffic.' + info).checked = false;
            }
        } else {
            document.getElementById('traffic.' + info).value = tmp;
        }
    })

    var proto = document.getElementById("traffic.proto").value;
    if (proto == 'tcp udp' || proto == 'tcp' || proto == 'udp' || proto == 'stcp') {
        $('#pageSrcPort').show();
        $('#pageDestPort').show();
    } else {
        $('#pageSrcPort').hide();
        $('#pageDestPort').hide();
    }

    openBoxTraffic();
}

// bacnet_client
function getTableDataBaccli() {
    var tr = $("#table_baccli tr");
    var result = [];
    for (var i = 2; i < tr.length; i++) {
        var tds = $(tr[i]).find("td");
        if (tds.length > 0) {
            var j = 0;
            result.push({
                'order':$(tds[j++]).html(), 
                'device_name':$(tds[j++]).html(),
                'factor_name':$(tds[j++]).html(),
                'object_id':$(tds[j++]).html(),
                'server_center':$(tds[j++]).html(),
                'operator':$(tds[j++]).html(),
                'operand':$(tds[j++]).html(),
                'ex':$(tds[j++]).html(),
                'accuracy':$(tds[j++]).html(),
                'enabled':$(tds[j++]).html()
            });
        }
    }

    return result;
}

function saveDataBaccli() {
    var result = [];
    var order = document.getElementById("widget.order").value;
    var device_name = document.getElementById("widget.device_name").value;
    var factor_name = document.getElementById("widget.factor_name").value;
    var object_id = document.getElementById("widget.object_id").value;
    var server_center = document.getElementById("widget.server_center").value;
    var operator = document.getElementById("widget.operator").value;
    var operand = document.getElementById("widget.operand").value;
    var ex = document.getElementById("widget.ex").value;
    var accuracy = document.getElementById("widget.accuracy").value;
    var enabled = document.getElementById("widget.enabled").checked;
    var page_type = document.getElementById("page_type").value;

    if (page_type == "0") {
        var table = document.getElementsByTagName("table")[0];
        table.innerHTML += "<tr  class=\"tr cbi-section-table-descr\">\n" +
            "        <td style='text-align:center' name='order'>"+ (order.length > 0 ? order : "-") + "</td>\n" +
            "        <td style='text-align:center' name='device_name'>"+ (device_name.length > 0 ? device_name : "-") +"</td>\n" +
            "        <td style='text-align:center' name='factor_name'>"+ (factor_name.length > 0 ? factor_name : "-") +"</td>\n" +
            "        <td style='text-align:center' name='object_id'>"+ (object_id.length > 0 ? object_id : "-") +"</td>\n" +
            "        <td style='text-align:center' name='server_center'>"+ (server_center.length > 0 ? server_center : "-") +"</td>\n" +
            "        <td style='display:none' name='operator'>"+ operator +"</td>\n" +
            "        <td style='display:none' name='operand'>"+ (operand.length > 0 ? operand : "-") +"</td>\n" +
            "        <td style='display:none' name='ex'>"+ (ex.length > 0 ? ex : "-") +"</td>\n" +
            "        <td style='display:none' name='accuracy'>"+ accuracy +"</td>\n" +
            "        <td style='text-align:center' name='enabled'>"+ enabled +"</td>\n" +
            "        <td><a href=\"javascript:void(0);\" onclick=\"editDataBaccli(this);\" >Edit</a></td>\n" +
            "        <td><a href=\"javascript:void(0);\" onclick=\"delDataBaccli(this);\" >Del</a></td>\n" +
            "    </tr>";
    } else {
        var table = document.getElementById("table_baccli");
        var num = 0;
        table.rows[Number(page_type)].cells[num++].innerHTML = (order.length > 0 ? order : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = (device_name.length > 0 ? device_name : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = (factor_name.length > 0 ? factor_name : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = (object_id.length > 0 ? object_id : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = (server_center.length > 0 ? server_center : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = operator;
        table.rows[Number(page_type)].cells[num++].innerHTML = (operand.length > 0 ? operand : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = (ex.length > 0 ? ex : "-");
        table.rows[Number(page_type)].cells[num++].innerHTML = accuracy;
        table.rows[Number(page_type)].cells[num++].innerHTML = enabled;
    }

    result = getTableDataBaccli();
    var json_data = JSON.stringify(result);
    $('#hidTD').val(json_data);
    closeBox();
}

function delDataBaccli(object) {
    var table = object.parentNode.parentNode.parentNode;
    var tr = object.parentNode.parentNode;
    table.removeChild(tr);

    var result = getTableDataBaccli();
    var json_data = JSON.stringify(result);
    $('#hidTD').val(json_data);
}

function editDataBaccli(object) {
    var row = $(object).parent().parent().parent().prevAll().length + 1;
    document.getElementById("page_type").value = row;
    var num = 0;
    var value = $(object).parent().parent().find("td");
    var order = value.eq(num++).text();
    var device_name = value.eq(num++).text();
    var factor_name = value.eq(num++).text();
    var object_id = value.eq(num++).text();
    var server_center = value.eq(num++).text();
    var operator = value.eq(num++).text();
    var operand = value.eq(num++).text();
    var ex = value.eq(num++).text();
    var accuracy = value.eq(num++).text();
    var enabled = value.eq(num++).text();

    document.getElementById("widget.order").value = order;
    document.getElementById("widget.device_name").value = device_name;
    document.getElementById("widget.factor_name").value = factor_name;
    document.getElementById("widget.object_id").value = object_id;
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

    openBox('widget');
}

function enableBACnet(state) {
    if (state) {
      $('#page_bacnet').show();
    } else {
      $('#page_bacnet').hide();
    }
}

$(document).ready(function(){
    $('.sidebar li a').each(function(){
        if ($($(this))[0].href == String(window.location)) {
        $(this).parent().addClass('active');
        }
    });

    $('.nav-item').each(function() {
        if ($(this).hasClass('active')) {
            var id = $($(this))[0].id;
            if (id == "dct_basic" || id == "interfaces" || id == "modbus" || id == "s7" ||
                id == "server" || id == "io" || id == "bacnet" || id == "fx" || id == "datadisplay" ||
                id == "opcua" || id == "mc" || id == "ascii" || id == "bacnet_client") {
                $('#navbar-collapse-dct').addClass('show')
                $('#dct').removeClass('collapsed');
            } else if (id == "ddns" || id == "macchina") {
                $('#navbar-collapse-remote').addClass('show');
                $('#remote').removeClass('collapsed');
            } else if (id == "wan" || id == "lan" || id == "wifi" || id == "wifi_client" || 
            id == "online_detection" || id == "lorawan" || id == "firewall") {
                $('#navbar-collapse-network').addClass('show');
                $('#network').removeClass('collapsed');
            } else if (id == "openvpn" || id == "wireguard") {
                $('#navbar-collapse-vpn').addClass('show');
                $('#vpn').removeClass('collapsed');
            } else if (id == "terminal" || id == "gps" || id == "nodered" || id == "docker") {
                $('#navbar-collapse-services').addClass('show');
                $('#services').removeClass('collapsed');
            }
        }
    });

    function itemChange(id) {
        var idArr = ['dct', 'remote', 'network', 'vpn', 'services'];
        if (id.includes('page_')) {
        var key = id.slice(5);
        // console.log(key);
        if (idArr.includes(key)) {
            idArr.forEach(function (info) {
                if (id != 'page_' + info) {
                // console.log("info:" + info);
                if ($('#navbar-collapse-' + info).hasClass('show')) {
                    $('#navbar-collapse-' + info).removeClass('show');
                    $('#' + info).addClass('collapsed');
                }
                }
            });
        }
        }
    }

    $('.nav-item').click(function() {
        var id = $($(this))[0].id;
        itemChange(id);
    });
});

function typeChangeLorawan() {
    var type = document.getElementById('type').value;
    if (type == '0') {
        $('#page_eui').hide();
        $('#page_packet_forwarder').hide();
        $('#page_basic_station').hide();
    } else if (type == '1') {
        $('#page_eui').show();
        $('#page_packet_forwarder').show();
        $('#page_basic_station').hide();
    } else if (type == '2') {
        modeChange();
        $('#page_eui').show();
        $('#page_packet_forwarder').hide();
        $('#page_basic_station').show();
    }
}

function modeChange() {
    var mode = document.getElementById('auth_mode').value;

    if (mode == '0') {
        $('#page_one').hide();
        $('#page_two').hide();
    } else if (mode == '1') {
        $('#page_one').show();
        $('#page_two').show();
    } else if (mode == '2') {
        $('#page_one').hide();
        $('#page_two').show();
    }
}

function caFileChangeLora() {
    $('#ca_text').html($('#lora_ca')[0].files[0].name);
}

function cerFileChangeLora() {
    $('#crt_text').html($('#lora_crt')[0].files[0].name);
}

function keyFileChangeLora() {
    $('#key_text').html($('#lora_key')[0].files[0].name);
}

function downloadFile(conf_name) {
    let lowerConfName;
    if (conf_name == 'IO') {
        lowerConfName = document.getElementById("page_im_ex_name").value;
    } else {
        lowerConfName = conf_name.toLowerCase();
    }
    
    var req = new XMLHttpRequest();
    var url = 'ajax/dct/get_dctcfg.php?type=download_' + lowerConfName;
    req.open('get', url, true);
    req.responseType = 'blob';
    req.setRequestHeader('Content-type', 'text/plain; charset=UTF-8');
    req.onreadystatechange = function (event) {
        if(req.readyState == 4 && req.status == 200) {
            var blob = req.response;
            var link=document.createElement('a');
            link.href=window.URL.createObjectURL(blob);
            const now = new Date();
            const year = now.getFullYear();
            const month = ('0' + (now.getMonth() + 1)).slice(-2);
            const day = ('0' + now.getDate()).slice(-2);
            const hours = ('0' + now.getHours()).slice(-2);
            const minutes = ('0' + now.getMinutes()).slice(-2);
            const seconds = ('0' + now.getSeconds()).slice(-2);
            const formattedTime = year + month + day + hours + minutes;
            link.download = lowerConfName + '_' + formattedTime + '.csv';
            link.click();
        }
    }
    req.send();
}

function enableBasic(state) {
    if (state) {
      $('#page_basic').show();
      enableCache(document.getElementById('cache_enabled'));
      enableMinuteData(document.getElementById('minute_enabled'));
    } else {
      $('#page_basic').hide();
    }
}

function enableCache(checkbox) {
    if (checkbox.checked == true) {
        $("#page_cache_days").show();
    } else {
        $("#page_cache_days").hide();
    }
}

function enableMinuteData(checkbox) {
    if (checkbox.checked == true) {
        $("#page_minute_data").show();
    } else {
        $("#page_minute_data").hide();
    }
}

function anonymousCheck(check) {
    if (check.checked == true)  {
      $('#page_anonymous').hide();
    } else {
      $('#page_anonymous').show();
    }
  }

  function enableOpcua(state) {
    if (state) {
      $('#page_opcua').show();
      if ($('#security_policy').val() == "0") {
        $('#page_security').hide();
      } else {
        $('#page_security').show();
      }

      if ($('#anonymous').is(':checked')) {
        $('#page_anonymous').hide();
      } else {
        $('#page_anonymous').show();
      }
    } else {
      $('#page_opcua').hide();
    }
  }

  function securityChange(state) {
    if (state.value == '0') {
      $('#page_security').hide();
    } else {
      $('#page_security').show();
    }
  }

  function certChange() {
    $('#cert_text').html($('#certificate')[0].files[0].name);
  }

  function keyChange() {
    $('#key_text').html($('#private_key')[0].files[0].name);
  }

  function trustChange() {
    var file = $('#trust_crt')[0].files;
    var str = '';
    for (var i = 0, len = file.length; i < len; i++) {
      str += file[i].name;
      if (i < len - 1)
        str += ";";
    }

    $('#trust_text').html(str);
  }