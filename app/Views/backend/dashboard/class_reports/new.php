<form action="<?php echo base_url('Academic/ReportsController/create').'/'.$class_id; ?>" method="POST" id="class-report-form">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <!-- Date -->
                        <div class="form-group col-md-4 col-sm">
                            <label for="date" class="col-form-label text-md-right">تاريخ الصف</label>
                            <input type="date" class="form-control" name="class_date" required>
                        </div>

                        <!-- Time -->
                        <div class="form-group col-md-4 col-sm">
                            <label for="class-time" class="col-form-label text-md-right">وقت الصف</label>

                            <div class="">
                                <select id="class-time" class="form-control" name="class_time" required>
                                    <option value="">اختر وقت الصف</option>
                                    <?php foreach ($times as $time): ?>
                                        <option value="time_id_<?php echo $time['id']; ?>" <?php if($class_data['time'] == 'time_id_'.$time['id']) echo 'selected'; ?> >
                                            <?php echo $time['name']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                    <?php
                                        $start = "00:00";
                                        $end = "23:30";

                                        $tStart = strtotime($start);
                                        $tEnd = strtotime($end);
                                        $tNow = $tStart;
                                        while($tNow <= $tEnd)
                                        {
                                            echo '<option value="'.date("H:i",$tNow).'"';
                                            if($class_data['time'] == date("H:i",$tNow)) echo 'selected';
                                            echo '>'.date("H:i",$tNow).'</option>';
                                            $tNow = strtotime('+15 minutes',$tNow);
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <!-- Place -->
                        <div class="form-group col-md-4 col-sm">
                            <label for="class-place" class="col-form-label text-md-right">مكان الصف</label>
                            <div class="">
                                <select id="class-place" class="form-control" name="class_place" required>
                                    <option value="">اختر مكان الصف</option>
                                    <?php foreach ($places as $place): ?>
                                        <option value="<?php echo $place['id']; ?>" <?php if($class_data['place_id'] == $place['id']) echo 'selected'; ?>><?php echo $place['name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    


    <style>
        #did-class-hold-button:checked~#class-did-not-hold {
            display: block;
        }
        #did-class-hold-button~#class-did-not-hold {
            display: none;
        }
        #did-class-hold-button:checked~#class-was-hold {
            display: none;
        }

        #did-class-hold-button:checked~label {
            color: #ffffff;
            background-color: #f44336;
        }
    </style>

    <input type="checkbox" id="did-class-hold-button" name="did_class_hold_button" style="display:none" value="1">
    <label class="btn btn-outline-danger btn-just-icon" for="did-class-hold-button">
        <i class="material-icons">event_busy</i>
    </label>
    <span>الصف لم يعقد</span>

    <div id="class-did-not-hold">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <h2>سبب عدم إنعقاد الصف</h2>
                            <!-- <label for="class-not-hold-reason" class="col-form-label text-md-right">سبب عدم إنعقاد الصف</label> -->
                            <textarea name="class_not_hold_reason" class="form-control no-latin" rows="2" id="class-not-hold-reason"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div id="class-was-hold">
        <!-- Attendance -->
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <h2>الحضور</h2>
                            <table class="table table-hover" id="students-attendance-table">
                                <?php foreach ($students as $student): ?>
                                    <tr>
                                        <td style="width: 100%;">
                                            <?php if (!empty($student['data'])): ?>
                                                <?php echo $student['data']['first_name'].' '.mb_substr($student['data']['last_name'], 0, 2); ?>
                                            <?php else: ?>
                                                User not found
                                            <?php endif; ?> 
                                        </td>
                                        
                                        <td class="btn-group" data-toggle="buttons">
                                            <label class="btn btn-outline-success btn-just-icon active">
                                                <input type="radio" name="attendance_status_<?php echo $student['user_id']; ?>" value="present" class="d-none" checked><i class="material-icons">check</i>
                                            </label>
                                            &nbsp;&nbsp;&nbsp;
                                            <label class="btn btn-outline-info btn-just-icon">
                                                <input type="radio" name="attendance_status_<?php echo $student['user_id']; ?>" value="late" class="d-none"><i class="material-icons">watch_later</i>
                                            </label>
                                            &nbsp;&nbsp;&nbsp;
                                            <label class="btn btn-outline-danger btn-just-icon">
                                                <input type="radio" name="attendance_status_<?php echo $student['user_id']; ?>" value="absent" class="d-none"><i class="material-icons">clear</i>
                                            </label>
                                            <input type="hidden" name="students_id[]" id="<?php echo $student['user_id']; ?>" value="<?php echo $student['user_id']; ?>" />
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <!-- Sections & Lessons -->
                        <div class="form-group">
                            <h2>المقرر</h2>
                            <?php foreach ($sections as $section): ?>
                                <h3><u><?= $section['name']; ?></u></h3>
                                <!-- Lessons -->
                                <?php foreach($lessons[$section['id']] as $lesson): ?>
                                    <?php if (!$lesson['status']): ?>
                                        <input type="checkbox" name="lessons[]" value="<?php echo $lesson['id']; ?>"/> <?php echo $lesson['name']; ?><br/>
                                    <?php else: ?>
                                        <input type="checkbox" name="" value="-1" disabled checked/> <?php echo $lesson['name']; ?><br/>
                                    <?php endif; ?>
                                <?php endforeach; ?>

                                <!-- Section Notes -->
                                <div class="form-group">
                                    <label for="section-notes-<?= $section['id']; ?>" class="col-form-label text-md-right">ملاحظات</label>
                                    <textarea name="section_notes_<?= $section['id']; ?>" class="form-control no-latin" rows="2" id="section-notes-<?= $section['id']; ?>"></textarea>
                                    <input type="hidden" name="sections_id[]" id="section-<?= $section['id']; ?>" value="<?= $section['id']; ?>" />
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <hr>
                        <hr>
                        
                        <!-- Overall Notes -->
                        <div class="form-group">
                            <label for="notes" class="col-form-label text-md-right">ملاحظات</label>
                            <textarea name="notes" class="form-control no-latin" rows="2" id="notes"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <input type="submit" name="create_report" class="btn btn-primary" value="إرسال">
</form>
