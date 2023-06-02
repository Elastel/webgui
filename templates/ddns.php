<?php ob_start() ?>
  <?php if (!RASPI_MONITOR_ENABLED) : ?>
      <div class="cbi-page-actions">
        <input type="submit" class="btn btn-outline btn-primary" value="<?php echo _("Save settings"); ?>" name="saveddnssettings" />
        <input type="submit" class="btn btn-success" value="<?php echo _("Apply settings"); $msg=_("Restarting DDNS"); ?>" data-toggle="modal" data-target="#hostapdModal" name="applyddnssettings" />
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
          <div class="col-md-12 mb-3 mt-1"><?php echo _("Executing DDNS start") ?>...</div>
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
          <?php echo _("DDNS"); ?>
          </div>
        </div><!-- ./row -->
      </div><!-- ./card-header -->
      <div class="card-body">
          <?php $status->showMessages(); ?>
          <form method="POST" action="ddns" role="form">
          <?php echo CSRFTokenFieldTag() ?>
            <div class="cbi-section cbi-tblsection">
              <div class="cbi-value">
                <label class="cbi-value-title"><?php echo _("DDNS"); ?></label>
                <input class="cbi-input-radio" id="ddns_enable" name="enabled" value="1" type="radio" checked onchange="enableDDNS(true)">
                <label ><?php echo _("Enable"); ?></label>

                <input class="cbi-input-radio" id="ddns_disable" name="enabled" value="0" type="radio" onchange="enableDDNS(false)">
                <label ><?php echo _("Disable"); ?></label>
              </div>
          
              <div id="page_ddns" name="page_ddns">
                <div class="cbi-value">
                  <label class="cbi-value-title"><?php echo _("Interface"); ?></label>
                  <select id="interface" name="interface" class="cbi-input-select">
                    <option value="eth0" selected="">eth0</option>
                    <option value="wwan0">wwan0</option>
                  </select>
                </div>

                <div class="cbi-value">
                  <label class="cbi-value-title"><?php echo _("Server Type"); ?></label>
                  <select id="server_type" name="server_type" class="cbi-input-select">
                    <option value="0" selected="">noip.com</option>
                  </select>
                </div> 

                <div class="cbi-value">
                  <label class="cbi-value-title"><?php echo _("Username"); ?></label>
                  <input type="text" class="cbi-input-text" name="username" id="username"/>
                </div> 

                <div class="cbi-value">
                  <label class="cbi-value-title"><?php echo _("Password"); ?></label>
                  <input type="password" class="cbi-input-text" name="password" id="password"/>
                </div>

                <div class="cbi-value">
                  <label class="cbi-value-title"><?php echo _("Update Interval"); ?></label>
                  <input type="text" class="cbi-input-text" name="interval" id="interval"/>
                  <label class="cbi-value-description"><?php echo _("Minutes, minimum is 5"); ?></label>
                </div>

                <div class="cbi-value">
                <label class="cbi-value-title"><?php echo _("Hostname"); ?></label>
                <input type="text" class="cbi-input-text" name="hostname" id="hostname"/>
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
  function enableDDNS(state) {
      if (state) {
        $('#page_ddns').show();
      } else {
        $('#page_ddns').hide();
      }
  }
</script>

