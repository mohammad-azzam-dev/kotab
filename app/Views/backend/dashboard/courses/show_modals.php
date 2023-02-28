<!-- START: Create/Update Lesson Modal -->
<div id="create-update-lesson-modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Create New Lesson</h4>
            </div>
            <div class="modal-body">
                <form method="post" id="create-update-lesson-form" class="form-horizontal">

                    <!-- Lesson Name -->
                    <div class="form-group row">
                        <label for="lesson-name" class="col-md-4 col-form-label text-md-right">الإسم</label>

                        <div class="col-md-6">
                            <input id="lesson-name" type="text" class="form-control" name="lesson_name" value="" required autofocus>
                        </div>
                    </div>

                    <!-- Lesson Description -->
                    <div class="form-group row">
                        <label for="lesson-description" class="col-md-4 col-form-label text-md-right">الوصف</label>

                        <div class="col-md-6">
                            <input id="lesson-description" type="text" class="form-control" name="lesson_description" value="" required>
                        </div>
                    </div>

                    <br />
                    <div class="form-group text-center">
                        <input type="hidden" name="action" id="lesson-action" value="create" />
                        <input type="hidden" name="lesson_hidden_id" id="lesson-hidden-id" />
                        <input type="hidden" name="section_hidden_id" id="lesson-section-hidden-id" />
                        <input type="submit" name="create_update_lesson" id="lesson-submit-button" class="btn btn-warning" value="" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- END: Create/Update Lesson Modal -->

<!-- START: Create/Update Section Modal -->
<div id="create-update-section-modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Create New Lesson</h4>
            </div>
            <div class="modal-body">
                <form method="post" id="create-update-section-form" class="form-horizontal">

                    <!-- Section Name -->
                    <div class="form-group row">
                        <label for="section-name" class="col-md-4 col-form-label text-md-right">الإسم</label>

                        <div class="col-md-6">
                            <input id="section-name" type="text" class="form-control" name="section_name" value="" require autofocus>
                        </div>
                    </div>

                    <!-- section Description -->
                    <div class="form-group row">
                        <label for="section-description" class="col-md-4 col-form-label text-md-right">الوصف</label>

                        <div class="col-md-6">
                            <input id="section-description" type="text" class="form-control" name="section_description" value="" required>
                        </div>
                    </div>

                    <br />
                    <div class="form-group text-center">
                        <input type="hidden" name="action" id="section-action" value="create" /> <!-- Create or Update Data -->
                        <input type="hidden" name="hidden_id" id="section-hidden-id" />
                        <input type="submit" name="create_update_section" id="section-submit-button" class="btn btn-warning" value="" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- END: Create/Update Section Modal -->

<!-- START: Order Sections Modal -->
<div id="order-sections-modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">ترتيب أقسام المادة</h4>
            </div>
            <div class="modal-body">
                <ul class="list-group" id="order-sections-list">
                    
                </ul>

                <br />
                <div class="form-group text-center">
                    <input type="button" name="order_sections" id="order-sections-submit-button" class="btn btn-warning" value="تأكيد" />
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END: Order Sections Modal -->