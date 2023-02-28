<?php if (!isRole($_SESSION['id'], 'admin'))
{
    header("Location: ".base_url('/dashboard'));
    exit();
} ?>

<div class="row">
  <div class="col-md-12">
    <div class="card">
      <!-- Card Header -->
      <div class="card-header card-header-success">
        <div class="row">
          <div class="col">
            <h4 class="card-title ">المواد</h4>
            <p class="card-category"> Here is a subtitle for this table</p>
          </div>
          <div class="col">
            <button type="button" id="create-course" class="btn btn-white btn-round btn-just-icon float-left" data-toggle="modal" data-target="#create-update-course-modal"><i class="material-icons">add</i></button>
          </div>
        </div>
      </div>

      <!-- Card Body -->
      <div class="card-body">
        <table class="table" id="courses-dataTable">
          <thead class=" text-primary">
            <tr>
                <th width="10px">#</th>
                <th style="min-width:150px;">الإسم</th>
                <th style="min-width:150px;">الرقم</th>
                <th style="min-width:150px;">الوصف</th>
                <th style="min-width:150px;">الإختصاصات</th>
                <!--<th style="min-width:100px;">عدد النقاط</th>-->
                <th style="min-width:150px;">العمليات</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div>