<div class="tab-pane" id="configuration">
    <div class="cbi-section">
        <h3>Configuration</h3>
        <div class="cbi-section-descr">The following list allows you to customize the files that need to be backed up. After setting, you need to execute the SAVE button.</div>
        <div class="cbi-value" id="cbi-json-config-editlist">
            <div id="editlist" style="width:100%">
                <textarea id="backup_list" name="backup_list" class="cbi-input-textarea" style="width:100%" rows="15"><?php echo $backupList ?></textarea>
            </div>
        </div>
        <div class="cbi-page-actions">
            <!-- <button class="cbi-button cbi-button-positive important" onclick="saveBackupList()"><?php echo _("Save"); ?></button> -->
            <input type="submit" class="btn btn-success" value="<?php echo _("Save"); ?>" name="<?php echo htmlspecialchars('saveBackupList', ENT_QUOTES); ?>" />
        </div>
    </div>
</div>