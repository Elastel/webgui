<?php

require_once 'includes/status_messages.php';
require_once 'config.php';

function save_upload_file($file) {
    define('KB', 1024);
      $tmp_destdir = '/tmp/';
      $auth_flag = 0;
  
      try {
          // If undefined or multiple files, treat as invalid
          if (!isset($file['error']) || is_array($file['error'])) {
              throw new RuntimeException('Invalid parameters');
          }
  
          $upload = \RaspAP\Uploader\Upload::factory('upload', $tmp_destdir);
          $upload->set_max_file_size(2048*KB);
          $upload->set_allowed_mime_types(array('text/plain', 'application/octet-stream', 'application/gzip'));
          $upload->file($file);
          $validation = new validation;
          $upload->callbacks($validation, array('check_name_length'));
          $results = $upload->upload();
  
          if (!empty($results['errors'])) {
              throw new RuntimeException($results['errors'][0]);
          }
  
          // Valid upload, get file contents
          $file_path = $results['full_path'];
          $new_file_path = '/tmp/backup.tar.gz';
          system("sudo mv $file_path $new_file_path");
          
          if (file_exists($new_file_path)) {
              return true;
          } else {
              return false;
          }
      } catch (RuntimeException $e) {
          return false;
      }
  }

function DisplayBackupRestore()
{
    if (isset($_POST['saveBackupList'])) {
        $newBackupList = $_POST['backup_list'];
        if (strlen($newBackupList) > 0) {
            $file = '/tmp/backup.list';
            $unixText = str_replace(["\r\n", "\r"], "\n", $newBackupList);
            if (file_put_contents($file, $unixText)) {
                exec("sudo mv $file /etc/backup.list");
            }
        }
    }

    if ( isset($_POST['upload']) ) {
        if (strlen($_FILES['upload_file']['name']) > 0) {
            if (is_uploaded_file($_FILES['upload_file']['tmp_name'])) {
                $file = "/tmp/backup.tar.gz";

                if (file_exists($file)) {
                    unlink($file);
                }

                $ret = save_upload_file($_FILES['upload_file']);
                $upload_backup_list = '';
                if ($ret) {
                    exec("tar tzf /tmp/backup.tar.gz", $tmp);
                    $upload_backup_list = implode("\n", $tmp);
                }
            } else {
                $status->addMessage('fail to upload file', 'danger');
            }
        }
    }

    $backupList = file_get_contents("/etc/backup.list");

    echo renderTemplate("backup_restore", compact('backupList', 'upload_backup_list'));
}

