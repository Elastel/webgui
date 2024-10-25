/*
version: 1.0.0
*/

function doesColumnExist(tableId, columnName) {
    var table = document.getElementById(tableId);
    var headers = table.querySelectorAll('th');
    for (var i = 0; i < headers.length; i++) {
        if (headers[i].textContent.trim() === columnName) {
            return true;
        }
    }
    return false;
}

function insertColumn(tableId, name, headerName, newHeaderName) {
    if (doesColumnExist(tableId, newHeaderName))
        return;

    var table = document.getElementById(tableId);
    var rows = table.getElementsByTagName('tr');
    var th_num = 0;

    var headers = table.getElementsByTagName('th');
    var columnIndex = 0;
    
    for (var i = 0; i < headers.length; i++) {
        if (headers[i].textContent === headerName) {
            columnIndex = i + 1;
        } else if (headers[i].textContent === 'Source Object') {
            columnIndex = i + 1;
        }
    }

    for (var i = 0; i < rows.length; i++) {
        var row = rows[i];
        var td = document.createElement('td');
        td.setAttribute('name', name);
        td.style.fontWeight = "bold";
        td.style.color = "blue";
        td.style.textAlign = 'center';
        td.innerHTML = '-';

        if (row.getElementsByTagName('th').length > 0) {
            var th = document.createElement('th');
            th.classList.add("th");
            th.classList.add("cbi-section-table-cell");
            if (th_num == 0) {
                th.innerHTML = newHeaderName;
                th_num++;
            }

            row.insertBefore(th, row.cells[columnIndex]);
        } else {
            row.insertBefore(td, row.cells[columnIndex]);
        }
    }
}

function deleteColumnByHeader(tableId, headerName) {
    if (!doesColumnExist(tableId, headerName))
        return;

    var table = document.getElementById(tableId);
    const cells = table.getElementsByTagName('th');
    for (let i = 0; i < cells.length; i++) {
        if (cells[i].textContent === headerName) {
            const columnIndex = i;
            const rows = table.rows;
            for (let j = 0; j < rows.length; j++) {
                rows[j].deleteCell(columnIndex);
            }
            break;
        }
    }
}

/*basic*/
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

/*interfaces*/
function loadInterfacesConfig() {
    $('#loading').show();
    $.get('ajax/dct/get_dctcfg.php?type=interface',function(data) {
        // console.log(data);
        var interface_data = JSON.parse(data);
        var arrCom = interface_data.com_option;
        var jsonData = JSON.parse(interface_data.interface);

        if (arrCom.length > 0) {
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

                    comProtocolChange(i);
                } else {
                    $('#page_com' + i).hide(); 
                    $('#com_disable' + i).prop('checked', true);
                }
            }
        }
        
        var arrTcp = interface_data.tcp_server_option;

        if (arrTcp.length > 0) {
           for (var i = 1; i <= 5; i++) {
                $('#tcp_enabled' + i).val(jsonData['tcp_enabled' + i]);
                if (jsonData['tcp_enabled' + i] == '1') {
                    $('#page_tcp' + i).show();
                    $('#tcp_enable' + i).prop('checked', true);

                    arrTcp.forEach(function (info) {
                        if (jsonData[info + i] == null) {
                            return true;    // continue: return true; break: return false
                        }
                        if (info == 'anonymous') {
                            $('#' + info + i).prop('checked', jsonData[info + i] == 1 ? true : false);
                        } else if (info == 'certificate') {
                            if (jsonData[info + i]) {
                                $('#cert_text' + i).html(jsonData[info + i]);
                            }
                        } else if (info == 'private_key') {
                            if (jsonData[info + i]) {
                                $('#key_text' + i).html(jsonData[info + i]);
                            }
                        } else if (info == 'trust_crt') {
                            if (jsonData[info + i]) {
                                $('#trust_text' + i).html(jsonData[info + i]);
                            }
                        } else {
                            $('#' + info + i).val(jsonData[info + i]);
                        } 
                    })

                    tcpProtocolChange(i);
                    if (jsonData['security_policy' + i] == '0') {
                        $('#page_security' + i).hide();
                    } else {
                        $('#page_security' + i).show();
                    }
                } else {
                    $('#page_tcp' + i).hide(); 
                    $('#tcp_disable' + i).prop('checked', true);
                }
            } 
        }
        

        $('#loading').hide();
    });
}

function comProtocolChange(num) {
    var numStr = num.toString();
    var selectElement = document.getElementById('com_proto' + numStr);
    var selectedOption = selectElement.options[selectElement.selectedIndex];
    var selectedText = selectedOption.text;

    if (selectedText == 'Transparent') {
        $('#com_page_protocol_modbus' + numStr).hide();
        $('#com_page_protocol_transparent' + numStr).show();
        $('#com_page_protocol_dnp3' + numStr).hide();
    } else if (selectedText == 'DNP3') {
        $('#com_page_protocol_modbus' + numStr).hide();
        $('#com_page_protocol_transparent' + numStr).hide();
        $('#com_page_protocol_dnp3' + numStr).show();
    } else {
        $('#com_page_protocol_modbus' + numStr).show();
        $('#com_page_protocol_transparent' + numStr).hide();
        $('#com_page_protocol_dnp3' + numStr).hide();
    }
}

function tcpProtocolChange(num) {
    var numStr = num.toString();
    var selectElement = document.getElementById('tcp_proto' + numStr);
    var selectedOption = selectElement.options[selectElement.selectedIndex];
    var selectedText = selectedOption.text;

    if (selectedText == 'Transparent') {
        $('#tcp_page_protocol_modbus' + numStr).hide();
        $('#tcp_page_protocol_transparent' + numStr).show();
        $('#tcp_page_protocol_s7' + numStr).hide();
        $('#tcp_page_protocol_opcua' + numStr).hide();
        $('#tcp_page_protocol_dnp3' + numStr).hide();
    } else if (selectedText == 'S7') {
        $('#tcp_page_protocol_modbus' + numStr).hide();
        $('#tcp_page_protocol_transparent' + numStr).hide();
        $('#tcp_page_protocol_opcua' + numStr).hide();
        $('#tcp_page_protocol_s7' + numStr).show();
        $('#tcp_page_protocol_dnp3' + numStr).hide();
    } else if (selectedText == 'OPCUA') {
        $('#tcp_page_protocol_modbus' + numStr).hide();
        $('#tcp_page_protocol_transparent' + numStr).hide();
        $('#tcp_page_protocol_s7' + numStr).hide();
        $('#tcp_page_protocol_opcua' + numStr).show();
        $('#tcp_page_protocol_dnp3' + numStr).hide();
        anonymousCheckTcp(numStr);
        securityChangeTcp(numStr)
    } else if (selectedText == 'DNP3') {
        $('#tcp_page_protocol_modbus' + numStr).hide();
        $('#tcp_page_protocol_transparent' + numStr).hide();
        $('#tcp_page_protocol_s7' + numStr).hide();
        $('#tcp_page_protocol_opcua' + numStr).hide();
        $('#tcp_page_protocol_dnp3' + numStr).show();
        anonymousCheckTcp(numStr);
        securityChangeTcp(numStr)
    }  else {
        $('#tcp_page_protocol_modbus' + numStr).show();
        $('#tcp_page_protocol_transparent' + numStr).hide();
        $('#tcp_page_protocol_opcua' + numStr).hide();
        $('#tcp_page_protocol_s7' + numStr).hide();
        $('#tcp_page_protocol_dnp3' + numStr).hide();
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

function securityChangeTcp(num) {
    if ($('#security_policy' + num).val() == '0')  {
        $('#page_security' + num).hide();
    } else {
        $('#page_security' + num).show();
    }
}

function certChangeTcp(num) {
    $('#cert_text' + num).html($('#certificate' + num)[0].files[0].name);
}

function keyChangeTcp(num) {
    $('#key_text' + num).html($('#private_key' + num)[0].files[0].name);
}

function trustChangeTcp(num) {
    var file = $('#trust_crt' + num)[0].files;
    var str = '';
    for (var i = 0, len = file.length; i < len; i++) {
        str += file[i].name;
        if (i < len - 1)
        str += ";";
    }

    $('#trust_text' + num).html(str);
}

function anonymousCheckTcp(num) {
    if ($('#anonymous' + num).is(':checked'))  {
        $('#page_anonymous' + num).hide();
    } else {
        $('#page_anonymous' + num).show();
    }
}

/*rule common*/
function openBox(table_name) {
    $('#popBox').show();
    $('#popLayer').show();
    document.getElementById("popBox").scrollTop = 0;
    selectOperator(table_name);
}

function closeBox() {
    $('#popBox').hide();
    $('#popLayer').hide();
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

function enableAlarm(table_name) {
    var checkbox = document.getElementById(table_name+'.sms_reporting')
    if (checkbox != null) {
        if (checkbox.checked == true) {
            $('#page_sms').show();
            selectReportType(table_name);
        } else {
            $('#page_sms').hide();
        }
    }
}

function selectReportType(table_name) {
    if (document.getElementById(table_name+'.report_type')) {
        var operator = document.getElementById(table_name+'.report_type').value;

        $('#page_alarm').hide();
        if (operator == "0") {
            $('#page_alarm').hide();
        } else {
            $('#page_alarm').show();
        }
    }
}

function addSectionTable(table_name, jsonData, option_list) {
    var mode = 0;
    var data_type_value = [];
    var reg_type_value = [];
    var word_len_value = [];
    var cap_type_value = ['4-20mA', '0-10V'];
    var mode_value = ['Counting Mode', 'Status Mode'];
    var count_method_value = ['Rising Edge', 'Falling Edge'];
    var status_value = ['Open', 'Close'];
    var type_id_list = {'1':'M_SP_NA_1', '30':'M_SP_TB_1', '3':'M_DP_NA_1', '31':'M_DP_TB_1', '5':'M_ST_NA_1', '32':'M_ST_TB_1',
    '7':'M_BO_NA_1', '33':'M_BO_TB_1', '9':'M_ME_NA_1', '34':'M_ME_TD_1', '21':'M_ME_ND_1', '11':'M_ME_NB_1', '35':'M_ME_TE_1', '13':'M_ME_NC_1', 
    '36':'M_ME_TF_1', '15':'M_IT_NA_1', '37':'M_IT_TB_1', '38':'M_EP_TD_1'};

    if (option_list != null)
        $('#option_list_'+table_name).val(option_list);

    if (jsonData == null)
        return;

    if (table_name == 'modbus' || table_name == 'modbus_slave_point') {
        data_type_value = ['Bit', 'Unsigned 16Bits AB', 'Unsigned 16Bits BA', 'Signed 16Bits AB', 'Signed 16Bits BA',
        'Unsigned 32Bits ABCD', 'Unsigned 32Bits BADC', 'Unsigned 32Bits CDAB', 'Unsigned 32Bits DCBA',
        'Signed 32Bits ABCD', 'Signed 32Bits BADC', 'Signed 32Bits CDAB', 'Signed 32Bits DCBA',
        'Float ABCD', 'Float BADC', 'Float CDAB', 'Float DCBA'];
    } else if (table_name == 'fx') {
        data_type_value = ['Bit', 'Byte', 'Word', 'DWord', 'Real'];
        reg_type_value = ['X', 'Y', 'M', 'S', 'D'];
    } else if (table_name == 's7') {
        reg_type_value = ['I', 'Q', 'M', 'DB', 'V', 'C', 'T'];
        word_len_value = ['Bit', 'Byte', 'Word', 'DWord', 'Real', 'Counter', 'Timer'];
    } else if (table_name == 'mc' || table_name == 'iec104') {
        data_type_value = ['Bit', 'Int', 'Float'];
    } else if (table_name == 'opcuacli') {
        data_type_value = ['Bool', 'Byte', 'Int16', 'UInt16', 'Int32', 'UInt32', 'Float', 'String'];
    }
    
    var len = Number(jsonData.length);
    for (var i = 0; i < len; i++) {
        var table = document.getElementById("table_" + table_name);
        var contents = '';
        contents += '<tr  class="tr cbi-section-table-descr">\n';
        
        if (jsonData[i].hasOwnProperty('mode')) {
            mode = Number(jsonData[i]['mode']);
        }

        option_list.forEach(function(key){
            if (!jsonData[i].hasOwnProperty(key)) {
                if (key == 'operator' || key == 'operand' || key == 'ex' || key == 'accuracy' ||
                key == 'report_type' || key == 'alarm_up' || key == 'alarm_down' || key == 'phone_num' || 
                key == 'email' || key == 'contents' || key == 'retry_interval' || key == 'again_interval') {
                    contents += '   <td style="display:none" name="'+key+'">-</td>\n';
                } else if (key == 'enabled' || key == 'sms_reporting') {
                    contents += '   <td style="' + ((key == 'enabled') ? 'text-align:center' : 'display:none') + '"><input type="checkbox" name="' +
                             key + (table_name == 'baccli' ? '_baccli' : '') + '" ' + (jsonData[i][key] == '1' ? 'checked' : ' ') + 
                             ' onclick="updateData(\''+table_name+'\')"></td>\n';
                } else {
                    contents += '   <td style="text-align:center" name="'+key+'">-</td>\n';
                }
                
                return;
            }

            if (key == "tx_cmd") {
                if (jsonData[i][key].includes('\r\n')) {
                    jsonData[i][key] = jsonData[i][key].replace(/\r/g, "\\\\r").replace(/\n/g, "\\\\n");
                } else if (jsonData[i][key].includes('\r')) {
                    jsonData[i][key] = jsonData[i][key].replace(/\r/g, "\\\\r");
                } else if (jsonData[i][key].includes('\n')) {
                    jsonData[i][key] = jsonData[i][key].replace(/\n/g, "\\\\n");
                }
            }

            if (key == 'operator' || key == 'operand' || key == 'ex' || key == 'accuracy' ||
            key == 'report_type' || key == 'alarm_up' || key == 'alarm_down' || key == 'phone_num' || 
            key == 'email' || key == 'contents' || key == 'retry_interval' || key == 'again_interval') {
                contents += '   <td style="display:none" name="'+key+'">'+ (jsonData[i][key] != null ? jsonData[i][key] : "-") +'</td>\n';
            } else if (key == 'type_id') {
                contents += '   <td style="text-align:center" name="'+key+'">'+ (type_id_list[Number(jsonData[i][key])]) +'</td>\n';
            } else if (key == 'data_type') {
                contents += '   <td style="text-align:center" name="'+key+'">'+ (data_type_value[Number(jsonData[i][key])]) +'</td>\n';
            } else if (key == 'reg_type') {
                contents += '   <td style="text-align:center" name="'+key+'">'+ (reg_type_value[Number(jsonData[i][key])]) +'</td>\n';
            } else if (key == 'word_len') {
                contents += '   <td style="text-align:center" name="'+key+'">'+ (word_len_value[Number(jsonData[i][key])]) +'</td>\n';
            } else if (key == 'cap_type') {
                contents += '   <td style="text-align:center" name="'+key+'">'+ (cap_type_value[Number(jsonData[i][key])]) +'</td>\n';
            } else if (key == 'mode') {
                contents += '   <td style="text-align:center" name="'+key+'">'+ (mode_value[Number(jsonData[i][key])]) +'</td>\n';
            } else if (key == 'count_method') {
                contents += ('   <td style="text-align:center" name="'+key+'">'+ (((mode == 1) ? '-' : count_method_value[Number(jsonData[i][key])])) +'</td>\n');
            } else if (key == 'init_status') {
                contents += '   <td style="text-align:center" name="'+key+'">'+ (status_value[Number(jsonData[i][key])]) +'</td>\n';
            } else if (key == 'cur_status') {
                var cur_status = jsonData[i][key];
                if (cur_status == '0' || cur_status == '1')
                    cur_status = status_value[Number(cur_status)];
                
                contents += '   <td style="text-align:center" name="'+key+'">'+ cur_status +'</td>\n';
            } else if (key == 'enabled' || key == 'sms_reporting') {
                contents += '   <td style="' + ((key == 'enabled') ? 'text-align:center' : 'display:none') + '"><input type="checkbox" name="' +
                             key + ((table_name == 'baccli' && key == 'enabled') ? '_baccli' : '') + '" ' + (jsonData[i][key] == '1' ? 'checked' : ' ') + 
                             ' onclick="updateData(\''+table_name+'\')"></td>\n';
            } else {
                contents += '   <td style="text-align:center" name="'+key+'">'+ ((mode == 1 && key == 'debounce_interval') ? '-' : jsonData[i][key]) +'</td>\n';
            }

        })
        contents += '   <td><a href="javascript:void(0);" onclick="editData(this, \''+table_name+'\');" >Edit</a></td>\n' +
            '       <td><a href="javascript:void(0);" onclick="delData(this, \''+table_name+'\');" >Del</a></td>\n' +
            '   </tr>';
        table.innerHTML += contents;
    }

    insertColumn("table_" + table_name, 'cur_value', 'Tag Name', 'Current Value');

    var result = get_table_data(table_name, option_list);
    var json_data = JSON.stringify(result);
    $('#hidTD_'+table_name).val(json_data);
}

/*datadisplay*/
function getRealtimeData() {
    $.get('ajax/dct/get_dctcfg.php?type=datadisplay', function(data) {
        jsonData = JSON.parse(data);
        //console.log(jsonData);

        const trList = document.querySelectorAll('table tr');
        var dnp3 = document.getElementById('option_list_dnp3');
        var modbus_slave = document.getElementById('option_list_modbus_slave_point');
        trList.forEach((tr) => {
            var cur_value = '';
            if (dnp3 || modbus_slave) {
                if (tr.querySelector('td[name="source_object"]')) {
                    var factor = tr.querySelector('td[name="source_object"]').innerHTML;
                    factor = factor.substring(factor.indexOf('-') + 1)
                    if (jsonData.hasOwnProperty(factor)) {
                        cur_value += jsonData[factor];
                    }
                }
            } else {
                if (tr.querySelector('td[name="factor_name"]')) {
                    //console.log(tr.querySelector('td[name="factor_name"]').innerHTML);
                    var factor = tr.querySelector('td[name="factor_name"]').innerHTML;
                    var factorList = factor.split(';');
                    factorList.forEach((key) => {
                        // console.log(key);
                        if (jsonData.hasOwnProperty(key)) {
                            cur_value += jsonData[key] + ';';
                        }    
                    })

                    if (cur_value.slice(-1) === ';') {
                        cur_value = cur_value.slice(0, -1);
                    }
                }
            }
            
            if (tr.querySelector('td[name="cur_value"]')) {
                tr.querySelector('td[name="cur_value"]').innerHTML = cur_value.length > 0 ? cur_value : '-';
            }
        });
    });
}

function loadRealtimeData() {
    getRealtimeData();
    setInterval(getRealtimeData, 1000);
}

/*modbus*/
function loadModbusConfig() {
    $('#loading').show();
    var table_name = 'modbus';
    $.get('ajax/dct/get_dctcfg.php?type=' + table_name,function(data){
        var jsonData = JSON.parse(data);
        var option_list = ['order', 'device_name', 'belonged_com', 'factor_name', 'device_id', 
                        "function_code", 'reg_addr', 'reg_count', 'data_type', 'server_center', 
                        'operator', 'operand', 'ex', 'accuracy', 'sms_reporting',
                        'report_type', 'alarm_up', 'alarm_down', 'phone_num', 
                        'email', 'contents', 'retry_interval', 'again_interval', 'enabled'];

        addSectionTable(table_name, jsonData, option_list);
    });

    loadRealtimeData();
    $('#loading').hide();
}

/*ascii*/
function loadAsciiConfig() {
    $('#loading').show();
    var table_name = 'ascii';
    $.get('ajax/dct/get_dctcfg.php?type=' + table_name,function(data){
        console.log(data);
        var jsonData = JSON.parse(data);
        var option_list = ['order', 'device_name', 'belonged_com', 'factor_name', 'tx_cmd', 
                        'cmd_format', 'server_center', 'enabled'];

        addSectionTable(table_name, jsonData, option_list);
    });

    loadRealtimeData();
    $('#loading').hide();
}

/*s7*/
function loadS7Config() {
    $('#loading').show();
    var table_name = 's7';
    $.get('ajax/dct/get_dctcfg.php?type=' + table_name,function(data){
        var jsonData = JSON.parse(data);
        var option_list = ['order', 'device_name', 'belonged_com', 'factor_name', 'reg_type', 
                        'reg_addr', 'reg_count', 'word_len', 'server_center', 
                        'operator', 'operand', 'ex', 'accuracy', 'sms_reporting',
                        'report_type', 'alarm_up', 'alarm_down', 'phone_num', 
                        'email', 'contents', 'retry_interval', 'again_interval', 'enabled'];
        addSectionTable(table_name, jsonData, option_list);
    });

    loadRealtimeData();
    $('#loading').hide();
}

/*FX*/
function loadFxConfig() {
    $('#loading').show();
    var table_name = 'fx';
    $.get('ajax/dct/get_dctcfg.php?type=' + table_name,function(data){
        var jsonData = JSON.parse(data);
        var option_list = ['order', 'device_name', 'belonged_com', 'factor_name', 'reg_type', 
                        'reg_addr', 'reg_count', 'data_type', 'server_center', 
                        'operator', 'operand', 'ex', 'accuracy', 'sms_reporting',
                        'report_type', 'alarm_up', 'alarm_down', 'phone_num', 
                        'email', 'contents', 'retry_interval', 'again_interval', 'enabled'];
        addSectionTable(table_name, jsonData, option_list);
    });

    loadRealtimeData();
    $('#loading').hide();
}

/*MC*/
function loadMcConfig() {
    $('#loading').show();
    var table_name = 'mc';
    $.get('ajax/dct/get_dctcfg.php?type=' + table_name, function(data){
        var jsonData = JSON.parse(data);
        var option_list = ['order', 'device_name', 'belonged_com', 'factor_name', 'data_area', 
                        'start_addr', 'reg_count', 'data_type', 'server_center', 
                        'operator', 'operand', 'ex', 'accuracy', 'sms_reporting',
                        'report_type', 'alarm_up', 'alarm_down', 'phone_num', 
                        'email', 'contents', 'retry_interval', 'again_interval', 'enabled'];
        addSectionTable(table_name, jsonData, option_list);
    });

    loadRealtimeData();
    $('#loading').hide();
}

/*IEC104*/
function loadIec104Config() {
    $('#loading').show();
    var table_name = 'iec104';
    $.get('ajax/dct/get_dctcfg.php?type=' + table_name,function(data){
        var jsonData = JSON.parse(data);
        var option_list = ['order', 'device_name', 'belonged_com', 'factor_name', 'type_id', 
                        'start_addr', 'common_addr', 'server_center',
                        'operator', 'operand', 'ex', 'accuracy', 'sms_reporting',
                        'report_type', 'alarm_up', 'alarm_down', 'phone_num', 
                        'email', 'contents', 'retry_interval', 'again_interval', 'enabled'];
        addSectionTable(table_name, jsonData, option_list);
    });

    loadRealtimeData();
    $('#loading').hide();
}

/*IO*/
function loadADCConfig() {
    $('#loading').show();
    var table_name = 'adc';
    $.get('ajax/dct/get_dctcfg.php?type=' + table_name,function(data){
        var jsonData = JSON.parse(data);
        var option_list = ['device_name', 'index', 'factor_name', 'cap_type', 
                        'range_down', 'range_up', 'server_center', 
                        'operator', 'operand', 'ex', 'accuracy', 'sms_reporting',
                        'report_type', 'alarm_up', 'alarm_down', 'phone_num', 
                        'email', 'contents', 'retry_interval', 'again_interval', 'enabled'];
        var model = document.getElementById("model").value;

        if (model == "EG500") {
            addSectionTable(table_name, jsonData, option_list);
        }

        loadRealtimeData();
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
                        'operator', 'operand', 'ex', 'accuracy', 'sms_reporting',
                        'report_type', 'alarm_up', 'alarm_down', 'phone_num', 
                        'email', 'contents', 'retry_interval', 'again_interval', 'enabled'];

        addSectionTable(table_name, jsonData, option_list);

        loadRealtimeData();
        $('#loading').hide();
    });
}

function loadDOConfig() {
    $('#loading').show();
    var table_name = 'do';
    $.get('ajax/dct/get_dctcfg.php?type=' + table_name,function(data){
        var jsonData = JSON.parse(data);
        var option_list = ['device_name', 'index', 'factor_name', 'init_status', 
                        'cur_status', 'server_center', 'operator', 'operand', 'ex',
                         'accuracy', 'sms_reporting',
                         'report_type', 'alarm_up', 'alarm_down', 'phone_num', 
                         'email', 'contents', 'retry_interval', 'again_interval', 'enabled'];

        addSectionTable(table_name, jsonData, option_list);

        loadRealtimeData();
        $('#loading').hide();
    });
}

/*OPCUA Rules*/
function loadOpcuaClientConfig(){
    $('#loading').show();
    var table_name = 'opcuacli';
    $.get('ajax/dct/get_dctcfg.php?type=' + table_name,function(data){
        var jsonData = JSON.parse(data);
        var option_list = ['order', 'device_name', 'belonged_com', 'factor_name', 'node_name', 
                        'data_type', 'server_center', 'operator', 'operand', 'ex', 'accuracy', 'sms_reporting',
                        'report_type', 'alarm_up', 'alarm_down', 'phone_num', 
                        'email', 'contents', 'retry_interval', 'again_interval', 'enabled'];

        addSectionTable(table_name, jsonData, option_list);
    });

    loadRealtimeData();
    $('#loading').hide();
}

/*DNP3 client*/
function loadDnp3ClientConfig(){
    $('#loading').show();
    var table_name = 'dnp3cli';
    $.get('ajax/dct/get_dctcfg.php?type=' + table_name,function(data){
        var jsonData = JSON.parse(data);
        var option_list = ['order', 'device_name', 'belonged_com', 'factor_name', 'group_id', 
                        'point_number', 'server_center', 'operator', 'operand', 'ex', 'accuracy', 'sms_reporting',
                        'report_type', 'alarm_up', 'alarm_down', 'phone_num', 
                        'email', 'contents', 'retry_interval', 'again_interval', 'enabled'];
        
        addSectionTable(table_name, jsonData, option_list);
    });

    loadRealtimeData();
    $('#loading').hide();
}

function get_bacnet_server_discover(callback) {
    $.get('ajax/dct/get_dctcfg.php?type=bacdiscover', function(data) {
        callback(data);
    })
}

function updateDeviceIdList() {
    var data = null;
    const options_device_id = [];

    const device_id_list = document.getElementById('deviceIdList');

    get_bacnet_server_discover(function(data) {
        if (data && data != 'null') {
            $('#bacnet_discover_data').val(data);
            var jsonData = JSON.parse(data);
            for (var i = 0; i < jsonData.length; i++) {
                options_device_id[i] = jsonData[i].device_id;
            }

            device_id_list.innerHTML = '';
            options_device_id.forEach(option => {
                const div = document.createElement('div');
                div.textContent = option;
                div.onclick = () => selectItem(option);
                device_id_list.appendChild(div);
            });
        } else {
            $('#bacnet_discover_data').val("");
            device_id_list.innerHTML = '';
        }
    });
}

function selectItem(value) {
    const input = document.getElementById('baccli.object_device_id');
    const device_id_list = document.getElementById('deviceIdList');

    input.value = value;
    device_id_list.classList.remove('show');
}

function selectItemObject(value) {
    const input = document.getElementById('baccli.object_id');
    const device_id_list = document.getElementById('objectIdList');

    input.value = value;
    device_id_list.classList.remove('show');
}

function filterFunction() {
    const input = document.getElementById('baccli.object_device_id');
    const device_id_list = document.getElementById('deviceIdList');
    const options_device_id = [];
    var data = document.getElementById('bacnet_discover_data').value;

    if (data.length < 3)
        return;

    // console.log(data);
    var jsonData = JSON.parse(data);

    for (var i = 0; i < jsonData.length; i++) {
        options_device_id[i] = jsonData[i].device_id;
    }

    // console.log(options_device_id);
    // const filter = input.value.toLowerCase();
    filteredOptions = options_device_id;
    //const filteredOptions = options_device_id.filter(option => option.toLowerCase().includes(filter));
    device_id_list.innerHTML = '';
    if (filteredOptions.length > 0) {
        filteredOptions.forEach(option => {
            const div = document.createElement('div');
            div.textContent = option;
            div.onclick = () => selectItem(option);
            device_id_list.appendChild(div);
        });
        device_id_list.classList.add('show');
    } else {
        device_id_list.classList.remove('show');
    }
}

function filterFunctionObject() {
    const object_id_list = document.getElementById('objectIdList');
    const options_object_id = [];
    var cur_device_id = document.getElementById('baccli.object_device_id');
    if (!cur_device_id.value) {
        return;
    } else {
        cur_device_id = cur_device_id.value;
    }

    var data = document.getElementById('bacnet_discover_data').value;
    if (data.length < 3)
        return;

    // console.log(data);
    var jsonData = JSON.parse(data);
    var jsonObject = '';

    for (var i = 0; i < jsonData.length; i++) {
        if (jsonData[i].device_id == cur_device_id) {
            jsonObject = jsonData[i].object_identifier;
            break;
        }
    }

    if (jsonObject.length > 0) {
        for (var i = 0; i < jsonObject.length; i++) {
            options_object_id[i] = jsonObject[i];
        }
    } else {
        return;
    }

    // console.log(options_device_id);
    // const filter = input.value.toLowerCase();
    filteredOptions = options_object_id;
    //const filteredOptions = options_device_id.filter(option => option.toLowerCase().includes(filter));
    object_id_list.innerHTML = '';
    if (filteredOptions.length > 0) {
        filteredOptions.forEach(option => {
            const div = document.createElement('div');
            div.textContent = option;
            div.onclick = () => selectItemObject(option);
            object_id_list.appendChild(div);
        });
        object_id_list.classList.add('show');
    } else {
        object_id_list.classList.remove('show');
    }
}

/*BACnet Rules*/
function loadBACnetClientConfig() {
    $('#loading').show();
    var table_name = 'baccli';
    $.get('ajax/dct/get_dctcfg.php?type=' + table_name,function(data){
        var jsonData = JSON.parse(data);
        if (jsonData == null)
            return;

        var arr = ['proto', 'ifname', 'ip_address', 'port', 'bbmd_enabled', 'bbmd_ip', 
            'bbmd_port', 'bbmd_time', 'interface', 'baudrate', 'mac', 
            'max_master', 'frames', 'device_id', 'collect_mode'];

        $('#enabled').val(jsonData.enabled);
        if (jsonData.enabled == '1') {
            $('#page_bacnet').show();
            $('#bacnet_enable').prop('checked', true);

            arr.forEach(function (info) {
                if (info == null) {
                    return true;    // continue: return true; break: return false
                }
                if (info == 'bbmd_enabled') {
                    $('#' + info).prop('checked', (jsonData[info] == '1') ? true:false);
                } else {
                    $('#' + info).val(jsonData[info]);
                }
            })
        } else {
            $('#page_bacnet').hide();
            $('#bacnet_disable').prop('checked', true);
        }

        bacnetProtocolChange();

        var tmpData = jsonData.baccli;
        var option_list = ['order', 'device_name', 'factor_name', 'object_device_id', 'object_id', 
                        'server_center', 'operator', 'operand', 'ex', 'accuracy', 'enabled'];

        addSectionTable(table_name, tmpData, option_list);

        loadRealtimeData();
        $('#loading').hide();
    });

    const input = document.getElementById('baccli.object_device_id');
    const input_object = document.getElementById('baccli.object_id');
    const device_id_list = document.getElementById('deviceIdList');
    const object_id_list = document.getElementById('objectIdList');

    input.addEventListener('focus', () => {
        filterFunction();
    });

    input_object.addEventListener('focus', () => {
        filterFunctionObject();
    });

    document.addEventListener('click', (event) => {
        const escapedId = CSS.escape('baccli.object_device_id');
        const escapedIdObject = CSS.escape('baccli.object_id');
        if (!event.target.matches(`#${escapedId}`)) {
            device_id_list.classList.remove('show');
        }

        if (!event.target.matches(`#${escapedIdObject}`)) {
            object_id_list.classList.remove('show');
        }
    });

    // updateDeviceIdList();
}

$('.btn_bacdiscover').click(function(){
    // console.log("btn_bacdiscover");
    updateDeviceIdList();
})

function enableBACnet(state) {
    if (state) {
      $('#page_bacnet').show();
    } else {
      $('#page_bacnet').hide();
    }
}

function bacnetProtocolChange()
{
    if ($('#proto').val() == '0') {
        $('#page_proto_ip').show();
        $('#page_proto_mstp').hide();
    } else {
        $('#page_proto_ip').hide();
        $('#page_proto_mstp').show();
    }
    enableBBMD();
}

function enableBBMD() {
    var state = document.getElementById('bbmd_enabled');
    if (state) {
        var checked = state.checked;

        if (checked) {
            $('#page_bbmd').show();
        } else {
            $('#page_bbmd').hide();
        }
    }
}

/*reporting server*/
function loadServerConfig() {
    $('#loading').show();
    $.get('ajax/dct/get_dctcfg.php?type=server',function(data){
        var jsonData = JSON.parse(data);

        var arr = ["proto", "encap_type", "server_addr", "http_url", "server_port", "cache_enabled", 
        "register_packet", "register_packet_hex", "heartbeat_packet", "heartbeat_packet_hex", "heartbeat_interval",
        "mqtt_heartbeat_interval", "mqtt_pub_topic", "mqtt_sub_topic", "mqtt_username", "mqtt_password", "sparkplug_group_id",
        "sparkplug_node_id", "sparkplug_device_id", "mqtt_client_id", "mqtt_tls_enabled", "certificate_type", "mqtt_ca", "mqtt_cert", "mqtt_key", 
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

function enableServer(state, num) {
    if (state) {
        $('#page_server' + num).show();
        protocolChange(num);
    } else {
        $('#page_server' + num).hide();
    }
}

function protocolChange(num) {
    var selectElement = document.getElementById('proto' + num);
    var selectedOption = selectElement.options[selectElement.selectedIndex];
    var selectedText = selectedOption.text;

    enableTls(num);
    cerChange(num);
    if (selectedText != 'SparkPlugB') {
        enableVar(num);
        encapChange(num);
    } else {
        $('#page_json' + num).hide();
        $('#page_hj212_' + num).hide();
    }
    
    if (selectedText == 'TCP' || selectedText == 'UDP') {
        $('#page_mqtt' + num).hide();
        $('#page_url' + num).hide(); 
        $('#page_tcp' + num).show(); 
        $('#page_addr' + num).show(); 
        $('#page_port' + num).show(); 
        $('#page_encap' + num).show(); 
        $('#page_status' + num).show();
        $('#page_cache' + num).show();
    } else if (selectedText == 'MQTT' || selectedText == 'SparkPlugB') {
        $('#page_mqtt' + num).show();
        $('#page_url' + num).hide(); 
        $('#page_tcp' + num).hide(); 
        $('#page_addr' + num).show(); 
        $('#page_port' + num).show(); 
        $('#page_status' + num).show();
        $('#page_cache' + num).show();
        if (selectedText == 'MQTT') {
            $('#page_encap' + num).show();
            $('#page_topic' + num).show();
            $('#page_sparkplug' + num).hide();
        } else {
            $('#page_encap' + num).hide();
            $('#page_topic' + num).hide();
            $('#page_sparkplug' + num).show();
        }
    } else if (selectedText == 'HTTP')  {
        $('#page_mqtt' + num).hide();
        $('#page_url' + num).show(); 
        $('#page_tcp' + num).hide(); 
        $('#page_addr' + num).hide(); 
        $('#page_port' + num).show(); 
        $('#page_encap' + num).show(); 
        $('#page_status' + num).hide();
        $('#page_cache' + num).hide();
    } else if (selectedText == 'MODBUS TCP' || selectedText == 'TCP Server') {
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

/*BACnet Server*/
function loadBACnetConfig() {
    $.get('ajax/dct/get_dctcfg.php?type=bacnet',function(data){
        jsonData = JSON.parse(data);
        var arr = ['proto', 'ifname', 'port', 'interface', 'baudrate', 'mac',
                    'max_master', 'frames', 'device_id', 'object_name'];

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
        bacnetProtocolChange();
    });
}

/*OPCUA Server*/
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

/*configs import and export*/
function openConfBox() {
    $('#confBox').show();
    $('#confLayer').show();
    document.getElementById("confBox").scrollTop = 0;
}

function closeConfBox() {
    $('#confBox').hide();
    $('#confLayer').hide();
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

/*rules common*/
function addData(table_name) {
    openBox(table_name);
    document.getElementById("page_type").value = "0"; /* 0 is add. other is edit */
    enableAlarm(table_name);
}

function findKey (data, value, compare = (a, b) => a === b) {
    return Object.keys(data).find(k => compare(data[k], value))
}

function get_table_data(table_name, option_list) {
    var data_type_value = [];
    var reg_type_value = [];
    var word_len_value = [];
    var cap_type_value = ['4-20mA', '0-10V'];
    var mode_value = ['Counting Mode', 'Status Mode'];
    var count_method_value = ['Rising Edge', 'Falling Edge'];
    var status_value = ['Open', 'Close'];
    var type_id_list = {'1':'M_SP_NA_1', '30':'M_SP_TB_1', '3':'M_DP_NA_1', '31':'M_DP_TB_1', '5':'M_ST_NA_1', '32':'M_ST_TB_1',
    '7':'M_BO_NA_1', '33':'M_BO_TB_1', '9':'M_ME_NA_1', '34':'M_ME_TD_1', '21':'M_ME_ND_1', '11':'M_ME_NB_1', '35':'M_ME_TE_1', '13':'M_ME_NC_1', 
    '36':'M_ME_TF_1', '15':'M_IT_NA_1', '37':'M_IT_TB_1', '38':'M_EP_TD_1'};

    if (table_name == 'modbus' || table_name == 'modbus_slave_point') {
        data_type_value = ['Bit', 'Unsigned 16Bits AB', 'Unsigned 16Bits BA', 'Signed 16Bits AB', 'Signed 16Bits BA',
        'Unsigned 32Bits ABCD', 'Unsigned 32Bits BADC', 'Unsigned 32Bits CDAB', 'Unsigned 32Bits DCBA',
        'Signed 32Bits ABCD', 'Signed 32Bits BADC', 'Signed 32Bits CDAB', 'Signed 32Bits DCBA',
        'Float ABCD', 'Float BADC', 'Float CDAB', 'Float DCBA'];
    } else if (table_name == 'fx') {
        data_type_value = ['Bit', 'Byte', 'Word', 'DWord', 'Real'];
        reg_type_value = ['X', 'Y', 'M', 'S', 'D'];
    } else if (table_name == 's7') {
        reg_type_value = ['I', 'Q', 'M', 'DB', 'V', 'C', 'T'];
        word_len_value = ['Bit', 'Byte', 'Word', 'DWord', 'Real', 'Counter', 'Timer'];
    } else if (table_name == 'mc' || table_name == 'iec104') {
        data_type_value = ['Bit', 'Int', 'Float'];
    } else if (table_name == 'opcuacli') {
        data_type_value = ['Bool', 'Byte', 'Int16', 'UInt16', 'Int32', 'UInt32', 'Float', 'String'];
    }

    var tr = $('#table_' + table_name + ' tr');
    var result = [];
    for (var i = 2; i < tr.length; i++) {
        var tds = $(tr[i]).find("td");
        if (tds.length > 0) {
            var tmp = [];
            var num = 0;
            tmp += '{';
            option_list.forEach(function (option) {
                var val = tds.filter('[name="'+ option +'"]').text();
                // console.log(val);

                if (option == 'enabled' || option == 'sms_reporting') {
                    var check = tds.find('input[name="' + ((table_name == 'baccli' && option == 'enabled') ? (option + '_baccli') : option) + '"]').is(':checked');
                    // console.log(check);
                    tmp += '"' + option + '":"' + ( check ? 1 : 0) + '",';
                } else if (option == 'data_type') {
                    tmp += '"' + option + '":"' + data_type_value.indexOf(val) + '",';
                } else if (option == 'reg_type') {
                    tmp += '"' + option + '":"' + reg_type_value.indexOf(val) + '",';
                } else if (option == 'word_len') {
                    tmp += '"' + option + '":"' + word_len_value.indexOf(val) + '",';
                } else if (option == 'cap_type') {
                    tmp += '"' + option + '":"' + cap_type_value.indexOf(val) + '",';
                } else if (option == 'mode') {
                    tmp += '"' + option + '":"' + mode_value.indexOf(val) + '",';
                } else if (option == 'count_method') {
                    tmp += '"' + option + '":"' + count_method_value.indexOf(val) + '",';
                } else if (option == 'init_status') {
                    tmp += '"' + option + '":"' + status_value.indexOf(val) + '",';
                } else if (option == 'cur_status') {
                    var cur_status = val;
                    if (cur_status == '0' || cur_status == '1')
                        cur_status = status_value.indexOf(val);
                    
                    tmp += '"' + option + '":"' + cur_status + '",';
                } else if (option == 'type_id') {
                    tmp += '"' + option + '":"' + findKey(type_id_list, val) + '",';
                } else  {
                    tmp += '"' + option + '":"' + val + '",';
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
    var mode = 0;
    var option_value = [];
    var io_type;
    var data_type_value = [];
    var reg_type_value = [];
    var word_len_value = [];
    var cap_type_value = ['4-20mA', '0-10V'];
    var mode_value = ['Counting Mode', 'Status Mode'];
    var count_method_value = ['Rising Edge', 'Falling Edge'];
    var status_value = ['Open', 'Close'];
    var type_id_list = {'1':'M_SP_NA_1', '30':'M_SP_TB_1', '3':'M_DP_NA_1', '31':'M_DP_TB_1', '5':'M_ST_NA_1', '32':'M_ST_TB_1',
    '7':'M_BO_NA_1', '33':'M_BO_TB_1', '9':'M_ME_NA_1', '34':'M_ME_TD_1', '21':'M_ME_ND_1', '11':'M_ME_NB_1', '35':'M_ME_TE_1', '13':'M_ME_NC_1', 
    '36':'M_ME_TF_1', '15':'M_IT_NA_1', '37':'M_IT_TB_1', '38':'M_EP_TD_1'};

    if (table_name == 'modbus' || table_name == 'modbus_slave_point') {
        data_type_value = ['Bit', 'Unsigned 16Bits AB', 'Unsigned 16Bits BA', 'Signed 16Bits AB', 'Signed 16Bits BA',
        'Unsigned 32Bits ABCD', 'Unsigned 32Bits BADC', 'Unsigned 32Bits CDAB', 'Unsigned 32Bits DCBA',
        'Signed 32Bits ABCD', 'Signed 32Bits BADC', 'Signed 32Bits CDAB', 'Signed 32Bits DCBA',
        'Float ABCD', 'Float BADC', 'Float CDAB', 'Float DCBA'];
    } else if (table_name == 'fx') {
        data_type_value = ['Bit', 'Byte', 'Word', 'DWord', 'Real'];
        reg_type_value = ['X', 'Y', 'M', 'S', 'D'];
    } else if (table_name == 's7') {
        reg_type_value = ['I', 'Q', 'M', 'DB', 'V', 'C', 'T'];
        word_len_value = ['Bit', 'Byte', 'Word', 'DWord', 'Real', 'Counter', 'Timer'];
    } else if (table_name == 'mc' || table_name == 'iec104') {
        data_type_value = ['Bit', 'Int', 'Float'];
    } else if (table_name == 'opcuacli') {
        data_type_value = ['Bool', 'Byte', 'Int16', 'UInt16', 'Int32', 'UInt32', 'Float', 'String'];
    }

    var page_type = document.getElementById("page_type").value;
    var tmp = $('#option_list_'+table_name).val();
    var option_list = tmp.split(",");

    if (table_name == 'adc' || table_name == 'di' || table_name == 'do') {
        io_type = table_name;
        table_name = 'io';
    }

    if (option_list.includes('mode')) {
        mode = Number(document.getElementById(table_name + '.'  + 'mode').value);
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
            option_value[option] = (mode == 1) ? '' : count_method_value[Number(document.getElementById(table_name + '.'  + option).value)];
        } else if (option == 'init_status') {
            option_value[option] = status_value[Number(document.getElementById(table_name + '.'  + option).value)];
        } else if (option == 'cur_status') {
            var cur_status = document.getElementById(table_name + '.'  + option).innerHTML;
            if (cur_status == '0' || cur_status == '1')
                option_value[option] = status_value[Number(cur_status)];
            else
                option_value[option] = cur_status;
        } else if (option == 'enabled' || option == 'sms_reporting') {
            option_value[option] = document.getElementById(table_name + '.'  + option).checked ? '1' : '0';
        } else if (option == 'index') {
            option_value[option] = document.getElementById(table_name + '.'  + option + '.' + io_type).value;
        } else if (option == 'type_id') {
            option_value[option] = type_id_list[document.getElementById(table_name + '.'  + option).value];
        } else {
            // console.log(option);
            option_value[option] = (mode == 1 && option == 'debounce_interval') ? '-' : document.getElementById(table_name + '.'  + option).value;
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
        deleteColumnByHeader("table_" + table_name, 'Current Value');
        var contents = '';
        contents += '<tr  class="tr cbi-section-table-descr">\n';
        option_list.forEach(function(option){
            if (option == 'operator' || option == 'operand' || option == 'ex' || option == 'accuracy' ||
                option == 'report_type' || option == 'alarm_up' || option == 'alarm_down' || option == 'phone_num' || 
                option == 'email' || option == 'contents' || option == 'retry_interval' || option == 'again_interval') {
                contents += '   <td style="display:none" name="'+option+'">'+ (option_value[option].length > 0 ? option_value[option] : "-") +'</td>\n';
            } else if (option == 'enabled' || option == 'sms_reporting') {
                contents += '   <td style="' + ((option == 'enabled') ? 'text-align:center' : 'display:none') + '"><input type="checkbox" name="' + option + 
                ((table_name == 'baccli' && option == 'enabled') ? '_baccli' : '') +'" ' + (option_value[option] == '1' ? 'checked' : ' ') + 
                ' onclick="updateData(\''+table_name+'\')"></td>\n';
            } else {
                contents += '   <td style="text-align:center" name="'+option+'">'+ (option_value[option] ? option_value[option] : "-") +'</td>\n';
            }
        })
        contents += '   <td><a href="javascript:void(0);" onclick="editData(this, \''+table_name+'\');" >Edit</a></td>\n' +
            '       <td><a href="javascript:void(0);" onclick="delData(this, \''+table_name+'\');" >Del</a></td>\n' +
            '   </tr>';
        table.innerHTML += contents;

        insertColumn("table_" + table_name, 'cur_value', 'Tag Name', 'Current Value');
    } else {
        var num = 0;
        option_list.forEach(function (option){
            if (option == 'enabled' || option == 'sms_reporting') {
                // Get all checkbox elements
                var trs = table.getElementsByTagName("tr");
                var checkboxes = trs[page_type].getElementsByTagName("input");
                // Traverse checkbox elements
                for (var i = 0; i < checkboxes.length; i++) {
                    // Determine if it is of checkbox type
                    var m_option = (table_name == 'baccli' && option == 'enabled') ? (option + '_baccli') : option;
                    // console.log(m_option);
                    if ((checkboxes[i].type === "checkbox" && checkboxes[i].name == m_option)) {
                        checkboxes[i].checked = (option_value[option] == '1') ? true : false;
                        num++;
                        break;  
                    }
                }
            } else {
                // console.log(table.rows[Number(page_type)].querySelector('td[name="'+ option +'"]').innerHTML);
                table.rows[Number(page_type)].querySelector('td[name="'+ option +'"]').innerHTML = (option_value[option].length > 0 ? option_value[option] : "-");
            }
        })
    }

    result = get_table_data(table_name, option_list);
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

function editData(object, table_name) {
    var row = $(object).parent().parent().parent().prevAll().length + 1;
    document.getElementById("page_type").value = row;
    var num = 0;
    var tmp = $('#option_list_'+table_name).val();
    var option_list = tmp.split(",");
    var tds = $(object).parent().parent().find("td");
    var io_type;

    if (table_name == 'adc' || table_name == 'di' || table_name == 'do') {
        io_type = table_name;
        table_name = 'io';
    }

    option_list.forEach(function(option) {
        var val = tds.filter('[name="'+ option +'"]').text();

        if (option == 'data_type' || option == 'reg_type' || option == 'word_len' || option == 'cap_type' ||
            option == 'cap_type' || option == 'mode' || option == 'count_method' || option == 'init_status' || option == 'type_id') {
            setSelectByText(table_name + '.'  + option, val);
        } else if (option == 'index') {
            document.getElementById(table_name + '.'  + option + '.' + io_type).value = val;
        } else if (option == 'enabled' || option == 'sms_reporting') {
            var check = tds.find('input[name="' + ((table_name == 'baccli' && option == 'enabled') ? (option + '_baccli') : option) + '"]').is(':checked');
            document.getElementById(table_name + '.'  + option).checked = check;
        } else if (option == 'cur_status') {
            document.getElementById(table_name + '.'  + option).innerHTML = val;
        } else {
            document.getElementById(table_name + '.'  + option).value = val;
        }
    })
    
    openBox(table_name);
    if (table_name == 'io')
        switchPage('btn' + io_type.toUpperCase());

    enableAlarm(table_name);
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
    enableAlarm(table_name);
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

/* DNP3 Server*/
function enableDnp3(state) {
    if (state) {
      $('#page_dnp3').show();
    } else {
      $('#page_dnp3').hide();
    }
    dnp3ProtocolChange();
}

function dnp3ProtocolChange() {
    var head = 'dnp3_server';
    var proto = document.getElementById('proto').value;

    if (proto == 'RTU') {
        $('#page_proto_rtu').show();
        $('#page_proto_ip').hide();
    } else {
        $('#page_proto_rtu').hide();
        $('#page_proto_ip').show();
    }
}

function loadDnp3Config() {
    $('#loading').show();
    var table_name = 'dnp3';
    $.get('ajax/dct/get_dctcfg.php?type=dnp3',function(data){
        jsonData = JSON.parse(data);
        var arr = jsonData.option;
        if (jsonData.hasOwnProperty("dnp3_server")) {
            var dnp3_server = JSON.parse(jsonData.dnp3_server);

            $('#enabled').val(dnp3_server.enabled);
            if (dnp3_server.enabled == '1') {
                $('#page_dnp3').show();
                $('#dnp3_server_enable').prop('checked', true);

                arr.forEach(function (info) {
                    if (info == null) {
                        return true;    // continue: return true; break: return false
                    }

                    $('#' + info).val(dnp3_server[info]);
                })
            } else {
                $('#page_dnp3').hide();
                $('#dnp3_server_disable').prop('checked', true);
            }
            dnp3ProtocolChange();
        }
        

        if (jsonData.hasOwnProperty("factor_list")) {
            var factor_list = jsonData.factor_list;
            var select = document.getElementById(table_name + '.source_object');
            if (factor_list != null) {
                factor_list.forEach(function(factor) {
                    var newOption = document.createElement('option');
                    newOption.value = factor;
                    newOption.text = factor;
                    select.appendChild(newOption);
                });
            }
            
        }
        
        if (jsonData.hasOwnProperty("dnp3")) {
            var tmpData = JSON.parse(jsonData.dnp3);
            var option_list = jsonData.option_list;

            addSectionTable(table_name, tmpData, option_list);

            loadRealtimeData();
        }
        $('#loading').hide();
    });
}

/* Modbus Slave*/
function enableModbusSlave(state) {
    if (state) {
      $('#page_modbus_slave').show();
    } else {
      $('#page_modbus_slave').hide();
    }
    modbusSlaveProtocolChange();
}

function modbusSlaveProtocolChange() {
    var proto = document.getElementById('proto').value;

    if (proto == 'RTU') {
        $('#page_proto_rtu').show();
        $('#page_proto_ip').hide();
    } else {
        $('#page_proto_rtu').hide();
        $('#page_proto_ip').show();
    }
}

function loadModbusSlaveConfig() {
    $('#loading').show();
    $.get('ajax/dct/get_dctcfg.php?type=modbus_slave',function(data){
        jsonData = JSON.parse(data);
        var arr = jsonData.option;
        if (jsonData.hasOwnProperty("modbus_slave")) {
            var modbus_slave = JSON.parse(jsonData.modbus_slave);

            $('#enabled').val(modbus_slave.enabled);
            if (modbus_slave.enabled == '1') {
                $('#page_modbus_slave').show();
                $('#modbus_slave_enable').prop('checked', true);

                arr.forEach(function (info) {
                    if (info == null) {
                        return true;    // continue: return true; break: return false
                    }

                    $('#' + info).val(modbus_slave[info]);
                })
            } else {
                $('#page_modbus_slave').hide();
                $('#modbus_slave_disable').prop('checked', true);
            }
            modbusSlaveProtocolChange();
        }

        var table_name = 'modbus_slave_point';
        if (jsonData.hasOwnProperty("factor_list")) {
            var factor_list = jsonData.factor_list;
            var select = document.getElementById(table_name + '.source_object');
            if (factor_list != null) {
                factor_list.forEach(function(factor) {
                    var newOption = document.createElement('option');
                    newOption.value = factor;
                    newOption.text = factor;
                    select.appendChild(newOption);
                });
            }
            
        }
        
        if (jsonData.hasOwnProperty(table_name)) {
            var tmpData = JSON.parse(jsonData.modbus_slave_point);
            var option_list = jsonData.option_list;

            addSectionTable(table_name, tmpData, option_list);

            loadRealtimeData();
        }
        $('#loading').hide();
    });
}

/*datadisplay*/
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

