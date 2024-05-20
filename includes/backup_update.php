<?php

require_once 'includes/status_messages.php';
require_once 'config.php';

function DisplayBackupUpdate()
{
    echo renderTemplate("backup_update", compact(''));
}

