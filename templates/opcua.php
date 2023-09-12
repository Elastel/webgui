<?php ob_start() ?>
  <?php if (!RASPI_MONITOR_ENABLED) : ?>
      <div class="cbi-page-actions">
        <input type="submit" class="btn btn-outline btn-primary" value="<?php echo _("Save settings"); ?>" name="savesettings" />
        <input type="submit" class="btn btn-success" value="<?php echo _("Apply settings"); $msg=_("Restarting OPC UA Server"); ?>" data-toggle="modal" data-target="#hostapdModal" name="applysettings" />
      </div>
  <?php endif ?>
  <!-- Modal -->
  <div class="modal fade" id="hostapdModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <div class="modal-title" id="ModalLabel"><i class="fas fa-sync-alt mr-2"></i><?php echo $msg ?></div>
        </div>
        <div class="modal-body">
          <div class="col-md-12 mb-3 mt-1"><?php echo _("Executing dct start") ?>...</div>
          <div class="progress" style="height: 20px;">
            <div class="progress-bar bg-info" role="progressbar" id="progressBar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="9"></div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline btn-primary" data-dismiss="modal"><?php echo _("Close"); ?></button>
        </div>
      </div>
    </div>
  </div>
<?php $buttons = ob_get_clean(); ob_end_clean() ?>

<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row">
          <div class="col">
          <?php echo _("OPC UA Server"); ?>
          </div>
        </div><!-- ./row -->
      </div><!-- ./card-header -->
      <div class="card-body">
          <?php $status->showMessages(); ?>
          <form role="form" action="opcua" enctype="multipart/form-data" method="POST">
          <?php echo CSRFTokenFieldTag() ?>
            <div class="cbi-section cbi-tblsection">
              <div class="cbi-value">
                <label class="cbi-value-title"><?php echo _("OPC UA Server"); ?></label>
                <input class="cbi-input-radio" id="opcua_enable" name="enabled" value="1" type="radio" checked onchange="enableOpcua(true)">
                <label ><?php echo _("Enable"); ?></label>

                <input class="cbi-input-radio" id="opcua_disable" name="enabled" value="0" type="radio" onchange="enableOpcua(false)">
                <label ><?php echo _("Disable"); ?></label>
              </div>

              <div id="page_opcua" name="page_opcua">
                <div class="cbi-value">
                  <label class="cbi-value-title"><?php echo _("Port"); ?></label>
                  <input type="text" class="cbi-input-text" name="port" id="port"/>
                  <label class="cbi-value-description"><?php echo _("1~65535"); ?></label>
                </div> 

                <div class="cbi-value">
                  <label class="cbi-value-title" for="anonymous"><?php echo _("Anonymous"); ?></label>
                  <input id="anonymous" type="checkbox" name="anonymous" onchange="anonymousCheck(this)" value="1">
                </div>

                <div id="page_anonymous" name="page_anonymous">
                  <div class="cbi-value">
                    <label class="cbi-value-title"><?php echo _("Username"); ?></label>
                    <input type="text" class="cbi-input-text" name="username" id="username"/>
                  </div>

                  <div class="cbi-value">
                    <label class="cbi-value-title"><?php echo _("Password"); ?></label>
                    <input type="text" class="cbi-input-text" name="password" id="password"/>
                  </div>
                </div>

                <div class="cbi-value">
                  <label class="cbi-value-title"><?php echo _("Maximum Historical Value"); ?></label>
                  <input type="text" class="cbi-input-text" name="max_value" id="max_value"/>
                  <label class="cbi-value-description"><?php echo _("Minimum is 1"); ?></label>
                </div>

                <div class="cbi-value">
                  <label class="cbi-value-title" for="enable_database"><?php echo _("Enable Database"); ?></label>
                  <input id="enable_database" type="checkbox" name="enable_database" value="1">
                </div>

                <div class="cbi-value">
                  <label class="cbi-value-title"><?php echo _("Security Policy"); ?></label>
                  <select id="security_policy" name="security_policy" class="cbi-input-select" onchange="securityChange(this)">
                    <option value="0" selected=""><?php echo _("None"); ?></option>
                    <option value="1">basic128</option>
                    <option value="2">basic256</option>
                    <option value="3">basic256sha256</option>
                  </select>
                </div>
                <div id="page_security" name="page_security">
                  <div class="cbi-value">
                    <label class="cbi-value-title"><?php echo _("Certificate"); ?></label>
                    <label for="certificate" class="cbi-file-lable">
                        <input type="button" class="cbi-file-btn" id="cert_btn" value="<?php echo _("Choose file"); ?>">
                        <span id="cert_text"><?php echo _("No file chosen"); ?></span>
                        <input type="file" class="cbi-file" name="certificate" id="certificate" onchange="certChange()">
                    </label>
                  </div>

                  <div class="cbi-value">
                    <label class="cbi-value-title"><?php echo _("Private Key"); ?></label>
                    <label for="key" class="cbi-file-lable">
                        <input type="button" class="cbi-file-btn" id="key_btn" value="<?php echo _("Choose file"); ?>">
                        <span id="key_text"><?php echo _("No file chosen"); ?></span>
                        <input type="file" class="cbi-file" name="private_key" id="private_key" onchange="keyChange()">
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <?php echo $buttons ?>
          </form>
      </div><!-- card-body -->
    </div><!-- card -->
  </div><!-- col-lg-12 -->
</div>
<script type="text/javascript">
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
</script>

