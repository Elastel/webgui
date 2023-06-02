<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row">
          <div class="col">
          <?php echo _("Data Display"); ?>
          </div>
        </div><!-- ./row -->
      </div><!-- ./card-header -->
      <div class="card-body">
          <form method="POST" action="datadisplay" role="form">
            <input type="hidden" name="table_data" value="" id="hidTD">
            <?php echo CSRFTokenFieldTag() ?>
            <div style="max-height:40rem; overflow-y:auto;">  
              <table class="table cbi-section-table" name="table_modbus" id="table_modbus">
              <label name="msg" id="msg" style="color:red;"></label>
            </div>
            </table>
          </form>
      </div><!-- card-body -->
    </div><!-- card -->
  </div><!-- col-lg-12 -->
</div>