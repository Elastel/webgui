<!-- wireguard settings tab -->
<div class="tab-pane active" id="wgsettings">
  <div class="row">
    <div class="cbi-value">
      <label class="cbi-value-title"><?php echo _("Start Selection"); ?></label>
      <select id="type" name="type" class="cbi-input-select" onchange="typeChangeWg()">
        <option value="off">OFF</option>
        <option value="config">Config Enabled</option>
        <option value="wg">Wg File Enabled</option>
      </select>
    </div>

    <div class="cbi-value" id="page_role">
      <label class="cbi-value-title"><?php echo _("Role"); ?></label>
      <select id="role" name="role" class="cbi-input-select" onchange="roleChangeWg()">
        <option value="client">Client</option>
        <option value="server">Server</option>
      </select>
    </div>

    <div id="page_config">
      <div class="cbi-value">
        <label for="code" class="cbi-value-title"><?php echo _("Local public key"); ?></label>
        <input type="text" class="cbi-input-text" name="wg-server" id="wg-srvpubkey" value="<?php echo htmlspecialchars($wg_srvpubkey, ENT_QUOTES); ?>" />
        <div style="display: inline-block;">
          <button class="btn btn-outline-secondary rounded-right wg-keygen" type="button"><i class="fas fa-magic"></i></button>
          <span id="wg-server-pubkey-status" class="input-group-addon check-hidden ml-2 mt-1"><i class="fas fa-check"></i></span>
        </div>
      </div>

      <div class="cbi-value">
        <label for="code" class="cbi-value-title"><?php echo _("Local IP Address"); ?></label>
        <input type="text" class="cbi-input-text" name="wg_srvipaddress" value="<?php echo htmlspecialchars($wg_srvipaddress, ENT_QUOTES); ?>" />
        <label class="cbi-value-description"><?php echo _("eg:10.0.10.4/24"); ?></label>
      </div>

      <div class="cbi-value">
        <label for="code" class="cbi-value-title"><?php echo _("Local Port"); ?></label>
        <input type="text" class="cbi-input-text" name="wg_srvport" value="<?php echo htmlspecialchars($wg_srvport, ENT_QUOTES); ?>" />
      </div>
      
      <div class="cbi-value">
        <label for="code" class="cbi-value-title"><?php echo _("DNS"); ?></label>
        <input type="text" class="cbi-input-text" name="wg_srvdns" value="<?php echo htmlspecialchars($wg_srvdns, ENT_QUOTES); ?>" />
      </div>

      <div class="cbi-value">
        <label for="code" class="cbi-value-title"><?php echo _("Peer public key"); ?></label>
        <input type="text" class="cbi-input-text" name="wg-peer" id="wg-peerpubkey" value="<?php echo htmlspecialchars($wg_peerpubkey, ENT_QUOTES); ?>" />
      </div>

      <div id="page_client">
        <div class="cbi-value">
          <label for="code" class="cbi-value-title"><?php echo _("Endpoint address"); ?></label>
          <input type="text" class="cbi-input-text" name="wg_pendpoint" value="<?php echo htmlspecialchars($wg_pendpoint, ENT_QUOTES); ?>" />
          <label class="cbi-value-description"><?php echo _("eg:10.0.10.8:51820"); ?></label>
        </div>
      </div>

      <div class="cbi-value">
        <label for="code" class="cbi-value-title"><?php echo _("Allowed IPs"); ?></label>
        <input type="text" class="cbi-input-text" name="wg_pallowedips" value="<?php echo htmlspecialchars($wg_pallowedips, ENT_QUOTES); ?>" />
        <label class="cbi-value-description"><?php echo _("eg:10.0.10.1/24"); ?></label>
      </div>

      <div class="cbi-value">
        <label for="code" class="cbi-value-title"><?php echo _("Persistent keepalive"); ?></label>
        <input type="text" class="cbi-input-text" name="wg_pkeepalive" value="<?php echo htmlspecialchars($wg_pkeepalive, ENT_QUOTES); ?>" />
      </div>
    </div>

    <div id="page_wg">
      <div class="cbi-value">
        <label class="cbi-value-title"><?php echo _("Configuration File(.conf)"); ?></label>
        <label for="wg" class="cbi-file-lable">
            <input type="button" class="cbi-file-btn" id="wg_btn" value="<?php echo _("Choose file"); ?>">
            <span id="wg_text"><?php echo _("No file chosen"); ?></span>
            <input type="file" class="cbi-file" name="wgFile" id="wgFile" onchange="wgFileChange()">
        </label>
      </div>
    </div>
  </div><!-- /.row -->
</div><!-- /.tab-pane | settings tab -->

<script type="text/javascript">
  function typeChangeWg() {
    var type = document.getElementById("type").value;

    if (type == "config") {
      $('#page_config').show();
      $('#page_wg').hide();
      $('#page_role').show();
      roleChangeWg();
    } else if (type == "wg") {
      $('#page_config').hide();
      $('#page_wg').show();
      $('#page_role').hide();
    } else {
      $('#page_config').hide();
      $('#page_wg').hide();
      $('#page_role').hide();
    }
  }

  function roleChangeWg() {
    var role = document.getElementById("role").value;

    if (role == "client") {
      $('#page_client').show();
      $('#page_server').hide();
    } else {
      $('#page_client').hide();
      $('#page_server').show();
    }
  }

  function wgFileChange() {
    $('#wg_text').html($('#wgFile')[0].files[0].name);
  }

</script>

