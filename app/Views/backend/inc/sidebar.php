<!--
  Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

  Tip 2: you can also add an image using data-image tag
-->
<div class="logo row">
  <table>
    <tr>
      <th class="px-3"><img src="https://i.pinimg.com/originals/33/b8/69/33b869f90619e81763dbf1fccc896d8d.jpg" style="width:50px;"></th>
      <!--<th class="px-3"><img src="<?= ""//base_url('public/assets/common/img/logo.png'); ?>" style="width:50px;"></th>-->
      <th class="pl-2"><a href="<?= base_url(); ?>" class="simple-text logo-normal my-auto">مدرستي</a></th>
    </tr>
  </table>
</div>


<div class="sidebar-wrapper">
  <ul class="nav">
    <?php if (isRole($_SESSION['id'], 'admin')): ?>
      <!-- Dashboard -->
      <li class="nav-item active">
        <a class="nav-link" href="<?= base_url('dashboard') ?>">
          <i class="material-icons">dashboard</i>
          <p>لوحة التحكم</p>
        </a>
      </li>

      <!-- Users -->
      <li class="nav-item ">
        <a class="nav-link" href="<?= base_url('dashboard/users') ?>">
          <i class="material-icons">person</i>
          <p>المستخدمين</p>
        </a>
      </li>
    <?php endif; ?>

    <!-- Acadmeic -->
    <li class="nav-item ">
      <a class="nav-link collapse" data-toggle="collapse" href="#mapsExamples" aria-expanded="false">
        <i class="material-icons">place</i>
        <b class="caret"></b>
        <p>آكاديمي</p>
        
      </a>
      <div class="collapse" id="mapsExamples" style="">
        <ul class="nav">
          <?php if (isRole($_SESSION['id'], 'admin')): ?>
            <!-- All Classes -->
            <li class="nav-item ">
              <a class="nav-link" href="<?= base_url('dashboard/classes/all-classes') ?>">
                <span class="sidebar-normal">كل الصفوف</span>
              </a>
            </li>
          <?php endif; ?>

          <?php if (isRole($_SESSION['id'], 'student')): ?>
            <!-- My Classes -->
            <li class="nav-item ">
              <a class="nav-link" href="<?= base_url('dashboard/classes/my-classes') ?>">
                <span class="sidebar-normal">صفوفي</span>
              </a>
            </li>
          <?php endif; ?>

          <?php if (isRole($_SESSION['id'], 'student')): ?>
            <!-- Parent's Children Classes -->
            <li class="nav-item ">
              <a class="nav-link" href="<?php echo base_url('dashboard/classes/my-children-classes') ?>">
                <span class="sidebar-normal">صفوف أولادي</span>
              </a>
            </li>
          <?php endif; ?>

          <?php if (isRole($_SESSION['id'], 'instructor')): ?>
            <!-- Instructor Classes -->
            <li class="nav-item ">
              <a class="nav-link" href="<?= base_url('dashboard/classes/instructor-classes'); ?>">
                <span class="sidebar-normal">الصفوف التي أدرسها</span>
              </a>
            </li>
          <?php endif; ?>

          <?php if (isRole($_SESSION['id'], 'admin')): ?>
            <!-- Majors -->
            <li class="nav-item ">
              <a class="nav-link" href="<?= base_url('dashboard/majors') ?>">
                <span class="sidebar-normal">الإختصاصات</span>
              </a>
            </li>
            <!-- Lessons -->
            <li class="nav-item ">
              <a class="nav-link" href="<?= base_url('dashboard/courses') ?>">
                <span class="sidebar-normal">المواد</span>
              </a>
            </li>
            <!-- Classes Requests -->
            <li class="nav-item ">
              <a class="nav-link" href="<?= base_url('Academic/ClassesRequestsController/list_requests/pending') ?>">
                <span class="sidebar-normal">طلبات إزالة / إضافة طلاب</span>
              </a>
            </li>
            <!-- Dropouts -->
            <li class="nav-item ">
              <a class="nav-link" href="<?= base_url('Academic/ReportsController/get_dropouts_students') ?>">
                <span class="sidebar-normal">المتسربين</span>
              </a>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </li>
  </ul>
</div>