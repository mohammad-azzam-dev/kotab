<!-- START: Create/Update Major Modal -->
<div id="create-update-major-modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Create New Major</h4>
            </div>
            <div class="modal-body">
                <form method="post" id="create-update-major-form" class="form-horizontal">
                    <!-- Major Name -->
                    <div class="form-group row">
                        <label for="major-name" class="col-md-4 col-form-label text-md-right">الإسم</label>

                        <div class="col-md-6">
                            <input id="major-name" type="text" class="form-control" name="major_name" value="" required autocomplete="major_name" autofocus>
                        </div>
                    </div>
                    
                    <!-- Code -->
                    <div class="form-group row">
                        <label for="major-code" class="col-md-4 col-form-label text-md-right">الرمز</label>

                        <div class="col-md-6">
                            <input id="major-code" type="text" class="form-control" name="major_code" value="" required autocomplete="major_code">
                        </div>
                    </div>
                    
                    <!-- Description -->
                    <div class="form-group row">
                        <label for="major-description" class="col-md-4 col-form-label text-md-right">الوصف</label>

                        <div class="col-md-6">
                            <input id="major-description" type="text" class="form-control" name="major_description" value="" required autocomplete="major_description">
                        </div>
                    </div>
                    
                    <!-- Credit Price -->
                    <!--<div class="form-group row">
                        <label for="major-credit-price" class="col-md-4 col-form-label text-md-right">الإسم الأول</label>

                        <div class="col-md-6">
                            <input id="major-credit-price" type="text" class="form-control" name="major_credit_price" value="" required autocomplete="major_credit_price">
                        </div>
                    </div>-->
                    
                    <br />
                    <div class="form-group text-center">
                        <input type="hidden" name="action" id="major-action" value="create" />
                        <input type="hidden" name="hidden_id" id="major-hidden-id" />
                        <input type="submit" name="create_update_major" id="major-action-button" class="btn btn-warning" value="create" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- END: Create/Update Major Modal -->