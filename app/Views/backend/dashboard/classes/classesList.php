<!-- Export Date Filters -->

<?php if (isRole($_SESSION['id'], 'admin')): ?>
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <!-- Card Body -->
        <div class="card-body">
          <div class="row">
            <div class="col">
              <div class="form-group">
                <label>من</label>
                <input type="date" class="form-control" id="export-from-date">
              </div>
            </div>
            <div class="col">
              <div class="form-group">
                <label>إلى</label>
                <input type="date" class="form-control" id="export-to-date">
              </div>
            </div>
            <div class="col text-center">
              <br>
              <button type="button" id="export-classes-reports-to-excel" class="btn btn-warning btn-round btn-just-icon"><i class="material-icons">cloud_download</i></button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>


<!-- Classes List -->
<div class="row">
  <div class="col-md-12">
    <div class="card">
      <!-- Card Header -->
      <div class="card-header card-header-success">
        <div class="row">
          <div class="col">
            <h4 class="card-title ">الصفوف</h4>
            <p class="card-category"> Here is a subtitle for this table</p>
          </div>
          <div class="col">
                <?php if (isRole($_SESSION['id'], 'admin')): ?>
                    <button type="button" id="create-class" class="btn btn-white btn-round btn-just-icon float-left" data-toggle="modal" data-target="#create-update-class-modal"><i class="material-icons">add</i></button>
                <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- Card Body -->
      <div class="card-body">
        <table class="table" id="classes-dataTable">
          <thead class=" text-primary">
            <tr>
                <th width="10px"><input type="checkbox" id="dataTable-checkall"></th>
                <th width="10px">#</th>
                <th style="min-width:150px;">الإسم</th>
                <th style="min-width:150px;">الإختصاص</th>
                <th style="min-width:150px;">المادة</th>
                <th style="min-width:150px;">المعلم</th>
                <th style="min-width:150px;">المكان</th>
                <th style="min-width:150px;">اليوم</th>
                <th style="min-width:150px;">الوقت</th>
                <th style="min-width:150px;">عدد المحاضر</th>
                <th style="min-width:250px;">العمليات</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div>