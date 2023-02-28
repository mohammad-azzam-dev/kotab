<!-- START: Create/Update Course Modal -->
<div id="create-update-course-modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Craete New Course</h4>
            </div>
            <div class="modal-body">
                <form method="post" id="create-update-course-form" class="form-horizontal">

                    <!-- Course Name -->
                    <div class="form-group row">
                        <label for="course-name" class="col-md-4 col-form-label text-md-right">الإسم</label>

                        <div class="col-md-6">
                            <input id="course-name" type="text" class="form-control" name="course_name" value="" required autocomplete="course_name" autofocus>
                        </div>
                    </div>

                    <!-- Course Code Number -->
                    <div class="form-group row">
                        <label for="course-code-number" class="col-md-4 col-form-label text-md-right">رمز المادة</label>

                        <div class="col-md-6">
                            <input id="course-code-number" type="text" class="form-control" name="course_code_number" value="" required autocomplete="course_code_number">
                        </div>
                    </div>

                    <!-- Course Majors -->
                    <div class="form-group row">
                        <label for="course-majors" class="col-md-4 col-form-label text-md-right">الإختصاص</label>

                        <div class="col-md-6">
                            <select id="course-majors" class="form-control" name="course_majors_id">
                                <option value="">اختر إختصاص الصف</option>
                                <?php foreach ($majors as $major): ?>
                                    <option value="<?php echo $major['id']; ?>"><?php echo $major['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Course Description -->
                    <div class="form-group row">
                        <label for="course-description" class="col-md-4 col-form-label text-md-right">الوصف</label>

                        <div class="col-md-6">
                            <input id="course-description" type="text" class="form-control" name="course_description" value="" required autocomplete="course_description">
                        </div>
                    </div>

                    <!-- Course Number Of Credits -->
                    <!--<div class="form-group row">
                        <label for="course-number-of-credits" class="col-md-4 col-form-label text-md-right">عدد الـ Credits</label>

                        <div class="col-md-6">
                            <input id="course-number-of-credits" type="text" class="form-control" name="course_number_of_credits" value="" required autocomplete="course_number_of_credits">
                        </div>
                    </div>-->

                    <br />
                    <div class="form-group text-center">
                        <input type="hidden" name="action" id="course-action" value="create" />
                        <input type="hidden" name="hidden_id" id="course-hidden-id" />
                        <input type="submit" name="create_update_course" id="course-action-button" class="btn btn-warning" value="create" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- END: Create/Update Course Modal -->