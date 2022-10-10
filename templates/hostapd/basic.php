<div class="tab-pane active" id="basic">
  <h4 class="mt-3"><?php echo _("Basic settings") ;?></h4>
  <div class="row">
    <div class="col-md-6 mb-2">
      <div class="custom-control custom-switch">
        <?php $checked = $arrConfig['disable_wifi_ap_bool'] == 1 ? 'checked="checked"' : '' ?>
        <?php $disabled = $arrConfig['enable_wifi_client_bool'] == 1 ? 'disabled="disabled"' : '' ?>
        <input class="custom-control-input" id="disablewifiap" name="disable_wifi_ap" type="checkbox" value="1" <?php echo $checked ?> <?php echo $disabled ?> />
        <label class="custom-control-label" for="disablewifiap"><?php echo _("Disable <code>Disable WIFI</code>"); ?></label>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-6 mb-2">
      <div class="custom-control custom-switch">
        <?php $checked = $arrConfig['enable_wifi_client_bool'] == 1 ? 'checked="checked"' : '' ?>
        <input class="custom-control-input" id="enablewificlient" name="enable_wifi_client" type="checkbox" value="1" <?php echo $checked ?> />
        <label class="custom-control-label" for="enablewificlient"><?php echo _("Enable WIFI Client <code>Enable WIFI STA mode, WIFI AP mode will be disabled.</code>"); ?></label>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="form-group col-md-6">
      <label for="cbxinterface"><?php echo _("Interface") ;?></label>
      <?php
        SelectorOptions('interface', $interfaces, $arrConfig['interface'], 'cbxinterface');
      ?>
    </div>
  </div>
  <div class="row">
    <div class="form-group col-md-6">
      <label for="txtssid"><?php echo _("SSID"); ?></label>
      <input type="text" id="txtssid" class="form-control" name="ssid" value="<?php echo htmlspecialchars($arrConfig['ssid'], ENT_QUOTES); ?>" />
    </div>
  </div>
  <div class="row">
    <div class="form-group col-md-6">
      <label for="cbxhwmode"><?php echo _("Wireless Mode") ;?></label>
      <?php
      $countries_5Ghz_max48ch = RASPI_5GHZ_ISO_ALPHA2;
      $selectedHwMode = $arrConfig['hw_mode'];
      if (isset($arrConfig['ieee80211n'])) {
          if (strval($arrConfig['ieee80211n']) === '1') {
              $selectedHwMode = 'n';
          }
      }
      if (isset($arrConfig['ieee80211ac'])) {
          if (strval($arrConfig['ieee80211ac']) === '1') {
              $selectedHwMode = 'ac';
          }
      }
      if (isset($arrConfig['ieee80211w'])) {
          if (strval($arrConfig['ieee80211w']) === '2') {
              $selectedHwMode = 'w';
          }
      }

      if (!in_array($arrConfig['country_code'], $countries_5Ghz_max48ch)) {
          $hwModeDisabled = 'ac';
          if ($selectedHwMode === $hwModeDisabled) {
              unset($selectedHwMode);
          }
      }
      SelectorOptions('hw_mode', $arr80211Standard, $selectedHwMode, 'cbxhwmode', 'loadChannelSelect', $hwModeDisabled); ?>
    </div>
  </div>
  <div class="row">
    <div class="form-group col-md-6">
      <label for="cbxchannel"><?php echo _("Channel"); ?></label>
      <?php
      $selectablechannels = Array();
      SelectorOptions('channel', $selectablechannels, intval($arrConfig['channel']), 'cbxchannel'); ?>
    </div>
  </div>
</div><!-- /.tab-pane | basic tab -->
