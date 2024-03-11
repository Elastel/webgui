<?php

require_once 'includes/status_messages.php';
require_once 'config.php';

/**
 * Displays info about the RaspAP project
 */
function DisplayIO()
{
    $status = new StatusMessages();
    $model = getModel();

    if (!RASPI_MONITOR_ENABLED) {
        if (isset($_POST['saveIOsettings']) || isset($_POST['applyIOsettings'])) {
            saveIOConfig($status, $model);
            
            if (isset($_POST['applyIOsettings'])) {
                sleep(2);
                exec('sudo /etc/init.d/dct restart > /dev/null');
            }
        }
    }

    if ( isset($_POST['upload']) ) {
        if (strlen($_FILES['upload_file']['name']) > 0) {
            if (is_uploaded_file($_FILES['upload_file']['tmp_name'])) {
                save_import_file($_POST['page_im_ex_name'], $status, $_FILES['upload_file']);
            } else {
                $status->addMessage('fail to upload file', 'danger');
            }
        }
    }

    // 判断串口是否有有外接io设备的配置
    $adc_index_count = 0;
    $di_index_count = 0;
    $do_index_count = 0;
    $com_count = 4;

    switch ($model) {
        case "EG500":
            $adc_index_count += 3;
            $di_index_count += 6;
            $do_index_count += 6;
            $com_count = 2;
            break;
        case "EG410":
            $di_index_count += 2;
            $do_index_count += 2;
            $com_count = 2;
            break;
    }

    echo renderTemplate("io", compact('status', "model", 'adc_index_count', 'di_index_count', 'do_index_count'));
}

function saveADC($status)
{
    $data = $_POST['tableDataADC'];
    file_put_contents(ELASTEL_DCT_CONFIG_JSON, '');
    file_put_contents(ELASTEL_DCT_CONFIG_JSON, $data);
    exec('sudo /usr/sbin/set_config ' . ELASTEL_DCT_CONFIG_JSON . ' dct adc');
}

function saveDI($status)
{
    $data = $_POST['tableDataDI'];
    file_put_contents(ELASTEL_DCT_CONFIG_JSON, '');
    file_put_contents(ELASTEL_DCT_CONFIG_JSON, $data);
    exec('sudo /usr/sbin/set_config ' . ELASTEL_DCT_CONFIG_JSON . ' dct di');
}

function saveDO($status)
{
    $data = $_POST['tableDataDO'];
    file_put_contents(ELASTEL_DCT_CONFIG_JSON, '');
    file_put_contents(ELASTEL_DCT_CONFIG_JSON, $data);
    exec('sudo /usr/sbin/set_config ' . ELASTEL_DCT_CONFIG_JSON . ' dct do');
}

function saveIOConfig($status, $model)
{
    if ($model == "EG500") {
        saveADC($status);
    }

    saveDI($status);
    saveDO($status);
    
    exec('sudo /usr/local/bin/uci commit dct');

    $status->addMessage('dct configuration updated ', 'success');
    return true;
}
