<!-- START: Create/Update Class Modal -->
<div id="create-update-class-modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Craete New Class</h4>
            </div>
            <div class="modal-body">
                <form method="post" id="create-update-class-form" class="form-horizontal">
                    <!-- Class Name -->
                    <div class="form-group row">
                        <label for="class-name" class="col-md-4 col-form-label text-md-right">الإسم</label>

                        <div class="col-md-6">
                            <input id="class-name" type="text" class="form-control" name="class_name" value="" autocomplete="class_name" autofocus>
                        </div>
                    </div>

                    <!-- Major -->
                    <div class="form-group row">
                        <label for="major" class="col-md-4 col-form-label text-md-right">الإختصاص</label>

                        <div class="col-md-6">
                            <select id="major" class="form-control" name="major">
                                <option value="">اختر إختصاص الصف</option>
                                <?php foreach ($majors as $major): ?>
                                    <option value="<?php echo $major['id']; ?>"><?php echo $major['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>


                    <!-- Course -->
                    <div class="form-group row">
                        <label for="course" class="col-md-4 col-form-label text-md-right">المادة</label>

                        <div class="col-md-6">
                            <select id="course" class="form-control" name="course">
                                <option value="">اختر مادة الصف</option>
                                <?php foreach ($courses as $course): ?>
                                    <option value="<?php echo $course['id']; ?>"><?php echo $course['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Instructor -->
                    <div class="form-group row">
                        <label for="instructor" class="col-md-4 col-form-label text-md-right">المعلم</label>

                        <div class="col-md-6">
                            <select id="instructor" class="form-control" name="instructor">
                                <option value="">اختر معلم الصف</option>
                                <?php foreach ($instructors as $instructor): ?>
                                    <?php if (isset($instructor)): ?>
                                        <option value="<?php echo $instructor['id']; ?>"><?php echo $instructor['first_name'].' '.mb_substr($instructor['last_name'], 0, 2); ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Place -->
                    <div class="form-group row">
                        <label for="place" class="col-md-4 col-form-label text-md-right">المكان</label>

                        <div class="col-md-6">
                            <select id="place" class="form-control" name="place">
                                <option value="">اختر مكان الصف</option>
                                <?php foreach ($places as $place): ?>
                                    <option value="<?php echo $place['id']; ?>"><?php echo $place['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Date -->
                    <div class="form-group row">
                        <label for="date" class="col-md-4 col-form-label text-md-right">اليوم</label>

                        <div class="col-md-6">
                            <select id="date" class="form-control" name="date">
                                <option value="">اختر تاريخ الصف</option>
                                <?php foreach ($dates as $date): ?>
                                    <option value="<?php echo $date['id']; ?>"><?php echo $date['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Time -->
                    <div class="form-group row">
                        <label for="time" class="col-md-4 col-form-label text-md-right">الوقت</label>

                        <div class="col-md-6">
                            <select id="time" class="form-control" name="time">
                                <option value="">اختر وقت الصف</option>
                                <?php foreach ($times as $time): ?>
                                    <option value="time_id_<?php echo $time['id']; ?>"><?php echo $time['name']; ?></option>
                                <?php endforeach; ?>
                                <?php
                                    $start = "00:00";
                                    $end = "23:30";

                                    $tStart = strtotime($start);
                                    $tEnd = strtotime($end);
                                    $tNow = $tStart;
                                    while($tNow <= $tEnd){
                                        echo '<option value="'.date("H:i",$tNow).'">'.date("H:i",$tNow).'</option>';
                                        $tNow = strtotime('+15 minutes',$tNow);
                                    }
                                ?>
                            </select>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="form-group row">
                        <label for="status" class="col-md-4 col-form-label text-md-right">حالة الصف</label>

                        <div class="col-md-6">
                            <select id="status" class="form-control" name="status">
                                <option value="draft">مسودة</option>
                                <option value="active">فعال</option>
                                <option value="inactive">غير نشطة</option>
                                <option value="canceled">ملغى</option>
                            </select>
                        </div>
                    </div>

                    <!-- Status Change Date -->
                    <div class="form-group row">
                        <label for="status-change-date" class="col-md-4 col-form-label text-md-right">تاريخ تغيير حالة الصف</label>

                        <div class="col-md-6">
                            <input type="date" id="status-change-date" class="form-control" name="status_change_date">
                        </div>
                    </div>

                    <br />
                    <div class="form-group text-center">
                        <input type="hidden" name="action" id="class-action" value="create" />
                        <input type="hidden" name="hidden_id" id="class-hidden-id" />
                        <input type="submit" name="create_update_class" id="class-action-button" class="btn btn-warning" value="create" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- END: Create/Update Class Modal -->