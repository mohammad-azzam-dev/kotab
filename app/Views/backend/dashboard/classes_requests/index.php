<!-- Remove Students Requests -->
<div class="row">
  <div class="col-md-12">
    <div class="card">
      <!-- Card Header -->
      <div class="card-header card-header-success">
        <div class="row">
          <div class="col">
            <h4 class="card-title ">طلبات إزالة الطلاب</h4>
            <p class="card-category"> Here is a subtitle for this table</p>
          </div>
          <div class="col">
            <button type="button" class="btn btn-white btn-round btn-just-icon float-left" data-toggle="collapse" data-target="#remove-requests-section"><i class="material-icons collapse-button">expand_more</i></button>
          </div>
        </div>
      </div>

      <!-- Card Body -->
      <div class="card-body collapse" id="remove-requests-section">
        <?php foreach ($remove_students_requests as $class_id => $class_request): ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                    <!-- Card Header -->
                    <div class="card-header card-header-warning">
                        <div class="row">
                        <div class="col">
                            <?php $class_data = getClass($class_id, 'array'); ?>
                            <h4 class="card-title "><?= (!empty($class_data))?$class_data['name']:"Class May Be Deleted"; ?></h4>
                            <!--<p class="card-category"> Here is a subtitle for this table</p>-->
                        </div>
                        <div class="col">
                            <button type="button" id="get-class-data" class_id="<?= $class_id; ?>" class="btn btn-white btn-round btn-just-icon float-left" data-toggle="modal" data-target="#get-class-data"><i class="material-icons">list</i></button>
                            <button type="button" class="btn btn-white btn-round btn-just-icon float-left" data-toggle="collapse" data-target="#remove-requests-<?= $class_id; ?>"><i class="material-icons collapse-button">expand_more</i></button>
                        </div>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="card-body collapse" id="remove-requests-<?= $class_id; ?>">
                      <table class="table dataTable just-scrollX" id="remove-students-requests-dataTable">
                        <thead class=" text-primary">
                            <tr>
                                <th width="10px">#</th>
                                <th style="min-width:150px;">رقم تعريف الطالب</th>
                                <th style="min-width:150px;">اسم الطالب</th>
                                <th style="min-width:150px;">السبب</th>
                                <th style="min-width:150px;">الحالة</th>
                                <th style="min-width:250px;">العمليات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $count = 1; ?>
                            <?php foreach ($class_request as $request): ?>
                                <?php $user_data = get_user_data($request['student_id']); ?>
                                <tr>
                                    <td><?= $count ?></td>
                                    <td><?= $user_data['id_code'] ?></td>
                                    <td><?= $user_data['first_name'].' '.$user_data['last_name'] ?></td>
                                    <td><?= $request['reason']; ?></td>
                                    <td id="request-status-<?= $request['id']; ?>"><?= $request['status']; ?></td>
                                    <td>
                                        <?php if ($request['status'] == 'pending'): ?>
                                            <!-- Accept Request -->
                                            <button type="button" id="" request_id="<?= $request['id']; ?>" class="button-<?= $request['id']; ?> accept-remove-student-request btn btn-info btn-round btn-just-icon"><i class="material-icons">check</i></button>
                                            <!-- Reject Request -->
                                            <button type="button" id="<?= $request['id']; ?>" request_id="<?= $request['id']; ?>" class="button-<?= $request['id']; ?> reject-remove-student-request btn btn-danger btn-round btn-just-icon"><i class="material-icons">clear</i></button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php $count++; endforeach; ?>
                        </tbody>
                      </table>
                    </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>


<!-- Add Students Requests -->
<div class="row">
  <div class="col-md-12">
    <div class="card">
      <!-- Card Header -->
      <div class="card-header card-header-success">
        <div class="row">
          <div class="col">
            <h4 class="card-title ">طلبات إضافة الطلاب</h4>
            <p class="card-category"> Here is a subtitle for this table</p>
          </div>
          <div class="col">
            <button type="button" class="btn btn-white btn-round btn-just-icon float-left" data-toggle="collapse" data-target="#addition-requests-section"><i class="material-icons collapse-button">expand_more</i></button>
          </div>
        </div>
      </div>

      <!-- Card Body -->
      <div class="card-body collapse" id="addition-requests-section">
        <?php foreach ($add_students_requests as $class_id => $class_request): ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                    <!-- Card Header -->
                    <div class="card-header card-header-warning">
                        <div class="row">
                        <div class="col">
                            <?php $class_data = getClass($class_id, 'array'); ?>
                            <h4 class="card-title "><?= (!empty($class_data))?$class_data['name']:"Class May Be Deleted"; ?></h4>
                            <!--<p class="card-category"> Here is a subtitle for this table</p>-->
                        </div>
                        <div class="col">
                            <button type="button" id="get-class-data" class_id="<?= $class_id; ?>" class="btn btn-white btn-round btn-just-icon float-left" data-toggle="modal" data-target="#get-class-data"><i class="material-icons">list</i></button>
                            <button type="button" class="btn btn-white btn-round btn-just-icon float-left" data-toggle="collapse" data-target="#addition-requests-<?= $class_id; ?>"><i class="material-icons collapse-button">expand_more</i></button>
                        </div>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="card-body collapse" id="addition-requests-<?= $class_id; ?>">
                      <table class="table dataTable just-scrollX" id="add-students-requests-dataTable">
                        <thead class=" text-primary">
                            <tr>
                                <th width="10px">#</th>
                                <th style="min-width:150px;">الإسم الأول</th>
                                <th style="min-width:150px;">اسم الأب</th>
                                <th style="min-width:150px;">الإسم الثاني</th>
                                <th style="min-width:150px;">سنة الولادة</th>
                                <th style="min-width:150px;">الحالة</th>
                                <th style="min-width:250px;">العمليات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $count = 1; ?>
                            <?php foreach ($class_request as $request): ?>
                                <tr>
                                    <td><?= $count ?></td>
                                    <td><?= $request['first_name'] ?></td>
                                    <td><?= $request['middle_name'] ?></td>
                                    <td><?= $request['last_name']; ?></td>
                                    <td><?= $request['birth_year']; ?></td>
                                    <td id="request-status-<?= $request['id']; ?>"><?= $request['status']; ?></td>
                                    <td>
                                        <?php if ($request['status'] == 'pending'): ?>
                                            <!-- Accept Request -->
                                            <button type="button" id="" request_id="<?= $request['id']; ?>" class="button-<?= $request['id']; ?> accept-add-student-request btn btn-info btn-round btn-just-icon"><i class="material-icons">check</i></button>
                                            <!-- Reject Request -->
                                            <button type="button" id="<?= $request['id']; ?>" request_id="<?= $request['id']; ?>" class="button-<?= $request['id']; ?> reject-add-student-request btn btn-danger btn-round btn-just-icon"><i class="material-icons">clear</i></button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php $count++; endforeach; ?>
                        </tbody>
                      </table>
                    </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>