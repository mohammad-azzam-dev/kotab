<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-body">
                <table class="table">
                    <?php if (isRole($_SESSION['id'], 'admin')): ?>
                        <tr>
                            <td>
                                <select id="users-list" type="text" class="form-control {{ $errors->has('users') ? ' is-invalid' : ''}}" name="users" value="{{ old('users') }}" required>
                                    <option value="">غير محدد</option>
                                    <?php if ($users): ?>
                                        <?php foreach ($users as $user): ?>
                                            <option class="<?= (isRole($user['id'], 'instructor') ? 'text-danger' : ''); ?>" value="<?php echo $user['id']; ?>"><?php echo $user['id_code'].' - '.$user['first_name'].' '.mb_substr($user['last_name'], 0, 1); ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>    
                            </td>
                            <td style="width: 70px;"><button type="button" class="btn btn-success btn-just-icon" name="add_student" id="add-student-admin"><i class="material-icons">add</i></button></td>
                        </tr>
                    <?php endif; ?> 
                </table>
                      
                <form action="<?php echo base_url('ClassesController/storeEnrollement').'/'.$class_id; ?>" method="POST" id="enroll-students-form">
                    <table class="table table-hover table-striped" id="enroll-students-table">
                        <thead>
                            <th>الإسم</th>
                            <th style="width:100px;">العمليات</th>
                        </thead>

                        <tbody>
                            <?php if (count($students) > 0): ?>
                                <?php foreach ($students as $student): ?>
                                    <tr id="student-<?php echo $student['user_id']; ?>">
                                        <td>
                                            <?php $user_index = '-1' ?>
                                            <?php foreach ($users as $key => $val): ?>
                                                <?php if ($val['id'] === $student['user_id']): ?>
                                                    <?php $user_index = $key; ?>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                            <?php if ($user_index != '-1'): ?>
                                                <p id="student-name-<?php echo $student['user_id']; ?>" class="<?= (isRole($student['user_id'], 'instructor') ? 'text-danger' : ''); ?>">
                                                    <?php echo $users[$user_index]['first_name'].' '.mb_substr($users[$user_index]['last_name'], 0, 2); ?>
                                                    <?php if ($student['remove_request'] != ''): ?>
                                                        <?= ' '.'('.$student['remove_request'].')'; ?>
                                                    <?php endif; ?>
                                                </p>
                                            <?php else: ?>
                                                <p>User not found</p>
                                            <?php endif; ?>
                                            <input type="hidden" id="student-hidden-id-<?php echo $student['user_id']; ?>" name="students_id[]" value="<?php echo $student['user_id']; ?>">
                                        </td>
                                        <?php if ((is_instructor_of($class_id) && $student['remove_request'] != 'pending') || isRole($_SESSION['id'], 'admin')): ?>
                                            <td><button type="button" name="remove_student" id="<?php echo $student['user_id']; ?>" class="btn btn-danger btn-remove-student btn-just-icon"  data-toggle="modal" data-target="#remove-student-modal"><i class="material-icons">remove</i></button></td>
                                        <?php else: ?>
                                            <td></td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    <div class="form-group">
                    </div>
                    <?php if (isRole($_SESSION['id'], 'admin')): ?>
                        <button type="submit" class="btn btn-primary"><i class="material-icons">done</i></button>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Add Students Requests -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <!-- Card Header -->
            <div class="card-header card-header-warning">
                <div class="row">
                    <div class="col">
                        <h4 class="card-title ">طلبات إضافات طلاب</h4>
                        <!--<p class="card-category"> Here is a subtitle for this table</p>-->
                    </div>
                    <div class="col">
                    </div>
                </div>
            </div>

            <!-- Card Body -->
            <div class="card-body">
                <!-- Add Student Request Form -->
                <p>لإضافة طلاب، يرجى التواصل معنا عبر الواتسأب</p>
                <?php /*<table class="table">
                    <form id="add-student-request-form">
                        <tr>
                            <td>
                                <input type="text" class="form-control" name="first_name" id="add-student-request-first-name" placeholder="الإسم الأول" required> 
                            </td>
                            <td>
                                <input type="text" class="form-control" name="middle_name" id="add-student-request-middle-name" placeholder="اسم الأب" required>
                            </td>
                            <td>
                                <input type="text" class="form-control" name="last_name" id="add-student-request-last-name" placeholder="الإسم الأخير" required> 
                            </td>
                            <td>
                                <input type="number" class="form-control" name="birth_year" id="add-student-request-birth-year" placeholder="سنة الولادة" required> 
                            </td>
                            <td style="width: 70px;">
                                <button type="submit" class="btn btn-success btn-just-icon" name="add-student-request"><i class="material-icons">add</i></button>
                            </td>
                        </tr>
                    </form>
                </table>

                <table class="table" id="add-student-request-table">
                    <thead class="text-primary">
                        <tr>
                            <th style="min-width:150px;">الإسم الأول</th>
                            <th style="min-width:150px;">إسم الأب</th>
                            <th style="min-width:150px;">الإسم الأخير</th>
                            <th style="min-width:150px;">سنة الولادة</th>
                            <th style="min-width:150px;">الحالة</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($add_students_requests as $request): ?>
                            <tr>
                                <td><?= $request['first_name']; ?></th>
                                <td><?= $request['middle_name']; ?></th>
                                <td><?= $request['last_name']; ?></th>
                                <td><?= $request['birth_year']; ?></th>
                                <td><?= $request['status']; ?></th>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table> */ ?>
            </div>
        </div>
    </div>
</div>