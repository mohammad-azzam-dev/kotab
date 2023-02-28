<button type="button" id="share-class-report" class="btn btn-warning btn-round btn-just-icon"><i class="material-icons">share</i></button>
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-body row">
                <!-- Date -->
                <div class="col">
                    <div class="input-group">
                        <div class="input-group-btn">
                            <span class="btn btn-outline-warning btn-round btn-just-icon btn-sm text-center"><i class="material-icons">event</i></span>
                        </div>
                        <span class="my-auto"><?= $report_data['class_date']; ?></span><br><br>
                    </div>
                </div>
                <!-- Time -->
                <div class="col">
                    <div class="input-group">
                        <div class="input-group-btn">
                            <span class="btn btn-outline-warning btn-round btn-just-icon btn-sm text-center border"><i class="material-icons">schedule</i></span>
                        </div>

                        <span class="my-auto"><?= $report_data['time_name']; ?></span><br><br>
                    </div>
                </div>
                <!-- Place -->
                <div class="col">
                    <div class="input-group">
                        <div class="input-group-btn">
                            <span class="btn btn-outline-warning btn-round btn-just-icon btn-sm text-center"><i class="material-icons">room</i></span>
                        </div>
                        <span class="my-auto"><?= $report_data['place_name']; ?></span><br><br>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if ($report_data['did_not_hold']): ?> <!-- Class Did Not Hold -->
    <!-- Not Hold Reason -->
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h3>سبب عدم إنعقاد الصف</h3>
                    <p><?= $report_data['not_hold_reason']; ?><p>
                </div>
            </div>
        </div>
    </div>

<?php else: ?> <!-- Class Was Hold -->
    <!-- Attendance -->
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <!-- Attendance -->
                    <h3 for="notes">الحضور</h3>
                    <table class="table table-hover" id="students-attendance-table">
                        <?php foreach ($students as $student): ?>
                            <tr>
                                <td style="width: 100%;">
                                    <?php /*$isFound = FALSE ?>
                                    <?php foreach ($users as $user): ?>
                                        <?php if ($user['id'] == $student['user_id']): ?>
                                            <?php $isFound = TRUE; ?>
                                            <?php break; ?>
                                        <?php endif; ?>
                                    <?php endforeach; */?>

                                    <?php if (!empty($student['data'])): ?>
                                        <?php echo $student['data']['first_name'].' '.mb_substr($student['data']['last_name'], 0, 2); ?>
                                    <?php else: ?>
                                        User not found
                                    <?php endif; ?>   
                                </td>
                                
                                <td class="btn-group" data-toggle="buttons">
                                    <label class="btn btn-outline-success btn-just-icon <?php if($attendance_data[$student['user_id']]['present'] == 1) echo 'active'; ?>">
                                        <i class="material-icons">check</i>
                                    </label>
                                    &nbsp;&nbsp;&nbsp;
                                    <label class="btn btn-outline-info btn-just-icon <?php if($attendance_data[$student['user_id']]['late'] == 1) echo 'active'; ?>">
                                        <i class="material-icons">watch_later</i>
                                    </label>
                                    &nbsp;&nbsp;&nbsp;
                                    <label class="btn btn-outline-danger btn-just-icon <?php if($attendance_data[$student['user_id']]['absent'] == 1) echo 'active'; ?>">
                                        <i class="material-icons">clear</i>
                                    </label>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Sections & Lessons -->
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <!-- Completed Lessons -->
                    <h2 for="notes">المقرر</h2>
                    <?php foreach ($sections as $section): ?>
                        <h3><u><?= $section['name']; ?></u></h3>
                        <!-- Completed Lessons -->
                        <?php if (isset($completed_lessons[$section['id']])): ?>
                            <?php foreach($completed_lessons[$section['id']] as $completed_lesson): ?>
                                <div class="input-group">
                                    <div class="input-group-btn">
                                        <span class="btn btn-success btn-round btn-just-icon btn-sm text-center"><i class="material-icons">check</i></span>
                                    </div>
                                    <!-- /btn-group -->
                                    <span class="border-0 my-auto"><?php echo $completed_lesson['name']; ?></span><br><br>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <!-- Section Notes -->
                        <div>
                            <h4>ملاحظات</h4>
                            <?php if (!empty($sections_notes[$section['id']])): ?>
                                <?php if ($sections_notes[$section['id']][0]['notes'] == ''): ?>
                                    <p>لا يوجد ملاحظات<p>
                                <?php else: ?>
                                    <p><?= $sections_notes[$section['id']][0]['notes']; ?><p>
                                <?php endif; ?>
                            <?php else: ?>
                                <p>لا يوجد ملاحظات<p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Notes -->
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <!-- Notes -->
                    <h3>ملاحظات</h3>
                    <?php if ($report_data['notes'] == ''): ?>
                        <p>لا يوجد ملاحظات<p>
                    <?php else: ?>
                        <p><?= $report_data['notes']; ?><p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>