<!-- START: Create/Update Achievement Modal -->
<div id="create-update-achievement-modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Create New Achievement</h4>
            </div>
            <div class="modal-body">
                <form method="post" action="" id="create-update-achievement-form" class="form-horizontal">

                    <div class="form-input-group">
                    </div>

                    <br />
                    <div class="form-group text-center">
                        <input type="hidden" name="action" id="achievement-action" value="create" />
                        <input type="hidden" name="hidden_row_code" id="achievement-hidden-row-code" />
                        <input type="submit" name="create_update_achievement" id="achievement-action-button" class="btn btn-warning" value="create" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- END: Create/Update Achievement Modal -->

<!-- START: Create/Update Achievements Sub Category Modal -->
    <div id="create-update-achievements-sub-category-modal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Create New Achievement category</h4>
                </div>
                <div class="modal-body">
                    <form method="post" id="create-update-achievements-sub-category-form" class="form-horizontal">

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

                        <br />
                        <div class="form-group text-center">
                            <input type="hidden" name="action" id="achievement-category-action" value="create" />
                            <input type="hidden" name="hidden_id" id="achievement-category-hidden-id" />
                            <input type="submit" name="create_update_achievement_category" id="achievement-category-action-button" class="btn btn-warning" value="create" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<!-- END: Create/Update Achievements Sub Category Modal -->