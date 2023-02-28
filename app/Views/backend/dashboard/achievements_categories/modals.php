<!-- START: Create/Update Achievement Category Modal -->
<div id="create-update-achievement-category-modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Create New Achievement category</h4>
            </div>
            <div class="modal-body">
                <span id="achievement-category-form-result"></span>
                <form method="post" id="create-update-achievement-category-form" class="form-horizontal">

                    <!-- Achievement Category Name -->
                    <div class="form-group row">
                        <label for="achievement-category-name" class="col-md-4 col-form-label text-md-right">الإسم</label>

                        <div class="col-md-6">
                            <input id="achievement-category-name" type="text" class="form-control" name="achievement_category_name" value="" required autocomplete="achievement_category_name" autofocus>
                        </div>
                    </div>

                    <!-- Achievement Category Description -->
                    <div class="form-group row">
                        <label for="achievement-category-description" class="col-md-4 col-form-label text-md-right">الوصف</label>

                        <div class="col-md-6">
                            <input id="achievement-category-description" type="text" class="form-control" name="achievement_category_description" value="" autocomplete="achievement_category_description">
                        </div>
                    </div>

                    <!-- Achievement Category Type -->
                    <!--<div class="form-group row">
                        <label for="achievement-category-type" class="col-md-4 col-form-label text-md-right">النوع</label>
                        &nbsp;&nbsp;&nbsp;
                        <span class="btn-group" data-toggle="buttons">
                            <label class="btn btn-outline-success active">
                                <input type="radio" name="achievement_category_type" value="role" class="d-none" checked><i class="fas fa-pencil-ruler"></i>
                            </label>
                            &nbsp;&nbsp;&nbsp;
                            <label class="btn btn-outline-primary">
                                <input type="radio" name="achievement_category_type" value="user" class="d-none"><i class="fas fa-user"></i>
                            </label>
                        </span>
                    </div>-->

                    <!-- Achievement Category Assigned Ids -->
                    <!--<div class="form-group row">
                        <label for="achievement-category-assigned-ids" class="col-md-4 col-form-label text-md-right">الجهات</label>

                        <div class="col-md-6">
                            <input id="achievement-category-assigned-ids" type="text" class="form-control" name="achievement_category_assigned_ids" value="" required autocomplete="achievement_category_description">
                        </div>
                    </div>-->

                    <!-- Achievement Category Icon -->
                    <!--<div class="form-group row">
                        <label for="achievement-category-icon" class="col-md-4 col-form-label text-md-right">الرمز</label>

                        <div class="col-md-6">
                            <select class="form-control" name="achievement_category_icon" id="achievement-category-icon">
                                <option value="warning">أصفر</option>
                                <option value="info">أزرق</option>
                                <option value="primary">بنفسجي</option>
                                <option value="secondary">رمادي</option>
                                <option value="success">أخضر</option>
                                <option value="danger">أحمر</option>
                            </select>
                        </div>
                    </div>-->

                    <!-- Achievement Category Color -->
                    <div class="form-group row">
                        <label for="achievement-category-color" class="col-md-4 col-form-label text-md-right">اللون</label>

                        <div class="col-md-6">
                            <select class="form-control" name="achievement_category_color" id="achievement-category-color">
                                <option value="warning">أصفر</option>
                                <option value="info">أزرق</option>
                                <option value="primary">بنفسجي</option>
                                <option value="secondary">رمادي</option>
                                <option value="success">أخضر</option>
                                <option value="danger">أحمر</option>
                            </select>
                        </div>
                    </div>

                    <br />
                    <div class="form-group text-center">
                        <input type="hidden" name="action" id="achievement-category-action" value="create" />
                        <input type="hidden" name="hidden_id" id="achievement-category-hidden-id" />
                        <input type="submit" name="create-updtae-achievement-category" id="achievement-category-action-button" class="btn btn-warning" value="create" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- END: Create/Update Achievement Category Modal -->