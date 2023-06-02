<?php ob_start() ?>
  <?php if (!RASPI_MONITOR_ENABLED) : ?>
      <div class="cbi-page-actions">
        <input type="submit" class="btn btn-outline btn-primary" value="<?php echo _("Save settings"); ?>" name="savebasicsettings" />
        <input type="submit" class="btn btn-success" value="<?php echo _("Apply settings"); $msg=_("Restarting dct"); ?>" data-toggle="modal" data-target="#hostapdModal" name="applybasicsettings" />
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
          <?php echo _("Basic Setting"); ?>
          </div>
        </div><!-- ./row -->
      </div><!-- ./card-header -->
      <div class="card-body">
          <?php $status->showMessages(); ?>
          <form method="POST" action="basic_conf" role="form">
          <?php echo CSRFTokenFieldTag() ?>
            <div class="cbi-section cbi-tblsection">
              <div class="cbi-value">
                <label class="cbi-value-title"><?php echo _("Data Collect"); ?></label>
                <input class="cbi-input-radio" id="basic_enable" name="enabled" value="1" type="radio" checked onchange="enableBasic(true)">
                <label ><?php echo _("Enable"); ?></label>

                <input class="cbi-input-radio" id="basic_disable" name="enabled" value="0" type="radio" onchange="enableBasic(false)">
                <label ><?php echo _("Disable"); ?></label>
              </div>
          
              <div id="page_basic" name="page_basic">
                <div class="cbi-value">
                  <label class="cbi-value-title"><?php echo _("Collect Period"); ?></label>
                  <input type="text" class="cbi-input-text" name="collect_period" id="collect_period" value="5" />
                  <label class="cbi-value-description"><?php echo _("Seconds"); ?></label>
                </div> 

                <div class="cbi-value">
                  <label class="cbi-value-title"><?php echo _("Report Period"); ?></label>
                  <input type="text" class="cbi-input-text" name="report_period" id="report_period" value="10" />
                  <label class="cbi-value-description"><?php echo _("Seconds"); ?></label>
                </div>

                <div class="cbi-value">
                  <label class="cbi-value-title"><?php echo _("Enable Cache"); ?></label>
                  <input type="checkbox" class="cbi-input-checkbox" onchange="enableCache(this)" name="cache_enabled" id="cache_enabled" value="1"/>
                  <label class="cbi-value-description"><?php echo _("Cache History Data"); ?></label>
                </div>

                <div class="cbi-value" id="page_cache_days" name="page_cache_days">
                  <label class="cbi-value-title"><?php echo _("Cache Days"); ?></label>
                  <input type="text" class="cbi-input-text" name="cache_day" id="cache_day" />
                  <label class="cbi-value-description"><?php echo _("Days"); ?></label>
                </div>

                <div class="cbi-value">
                  <label class="cbi-value-title"><?php echo _("Send Minute Data"); ?></label>
                  <input type="checkbox" class="cbi-input-checkbox" onchange="enableMinuteData(this)" name="minute_enabled" id="minute_enabled" value="1" />
                </div>

                <div class="cbi-value" id="page_minute_data" name="page_minute_data">
                  <label class="cbi-value-title"><?php echo _("Minute Data Period"); ?></label>
                  <input type="text" class="cbi-input-text" name="minute_period" id="minute_period" />
                  <label class="cbi-value-description"><?php echo _("Minutes"); ?></label>
                </div>

                <div class="cbi-value">
                  <label class="cbi-value-title"><?php echo _("Send Hour Data"); ?></label>
                  <input type="checkbox" class="cbi-input-checkbox" name="hour_enabled" id="hour_enabled" value="1" />
                </div>

                <div class="cbi-value">
                  <label class="cbi-value-title"><?php echo _("Send Day Data"); ?></label>
                  <input type="checkbox" class="cbi-input-checkbox" name="day_enabled" id="day_enabled" value="1" />
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
</script>

