<?php if (!isRole($_SESSION['id'], 'admin'))
{
    header("Location: ".base_url('/dashboard'));
    exit();
} ?>

<?php if (!isRole($_SESSION['id'], 'admin'))
{
    header("Location: ".base_url('/dashboard'));
    exit();
} ?>
<?php /*
<div class="row">
  <div class="col-md-12">
    <div class="card">
      <!-- Card Header -->
      <div class="card-header card-header-success">
        <div class="row">
          <div class="col">
            <h4 class="card-title ">الدروس</h4>
            <p class="card-category"> Here is a subtitle for this table</p>
          </div>
          <div class="col">
            <button type="button" id="create-lesson" class="btn btn-white btn-round btn-just-icon float-left" data-toggle="modal" data-target="#create-update-lesson-modal"><i class="material-icons">add</i></button>
          </div>
        </div>
      </div>

      <!-- Card Body -->
      <div class="card-body">
        <table class="table" id="lessons-dataTable">
          <thead class="text-primary">
            <tr>
                <th width="10px">#</th>
                <th style="min-width:150px;">الإسم</th>
                <th style="min-width:150px;">الوصف</th>
                <th style="min-width:150px;">العمليات</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div> */ ?>


<div class="row">
  <div class="col-md-12">
    <div class="card">
      <!-- Card Header -->
      <div class="card-header card-header-success">
        <div class="row">
          <div class="col">
            <h4 class="card-title "><?= $page_title; ?></h4>
            <p class="card-category"> Here is a subtitle for this table</p>
          </div>
          <div class="col text-left">
            <!-- Create Section Button -->
            <button type="button" id="create-section" class="btn btn-white btn-round btn-just-icon" data-toggle="modal" data-target="#create-update-section-modal"><i class="material-icons">add</i></button>
            <!-- Order Sections Button -->
            <button type="button" course_id="<?= $course_id; ?>" class="order-sections btn btn-white btn-round btn-just-icon" data-toggle="modal" data-target="#order-sections-modal"><i class="material-icons">sort</i></button>
          </div>
        </div>
      </div>

      <!-- Card Body -->
      <div class="card-body">
      </div>
    </div>
  </div>
</div>

<?php foreach ($sections as $section): ?>
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <!-- Card Header -->
        <div class="card-header card-header-warning">
          <div class="row">
            <div class="col">
              <h4 class="card-title "><?= $section['name']; ?></h4>
              <p class="card-category"><?= $section['description']; ?></p>
            </div>
            <div class="col text-left">
              <!-- Create Lesson Button -->
              <button type="button" id="create-lesson" section-id="<?= $section['id']; ?>" class="btn btn-white btn-round btn-just-icon" data-toggle="modal" data-target="#create-update-lesson-modal"><i class="material-icons">add</i></button>
              <!-- Update Section Button -->
              <button type="button" id="<?= $section['id']; ?>" class="update-section btn btn-white btn-round btn-just-icon" data-toggle="modal" data-target="#create-update-section-modal"><i class="material-icons">edit</i></button>
            </div>
          </div>
        </div>

        <!-- Card Body -->
        <div class="card-body">
          <table class="table" id="lessons-dataTable">
            <thead class="text-primary">
              <tr>
                  <th width="10px">#</th>
                  <th style="min-width:150px;">الإسم</th>
                  <th style="min-width:150px;">الوصف</th>
                  <th style="min-width:150px;">العمليات</th>
              </tr>
            </thead>
            <tbody>
              <?php $count = 1; ?>
              <?php foreach ($lessons[$section['id']] as $lesson): ?>
                <tr>
                  <td><?= $count; ?></td>
                  <td><?= $lesson['name']; ?></td>
                  <td><?= $lesson['description']; ?></td>
                  <td><?= $lesson['name']; ?></td>
                </tr>
                <?php $count++; ?>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
<?php endforeach; ?>