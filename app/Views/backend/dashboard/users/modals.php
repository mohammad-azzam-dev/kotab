<!-- START: Create/Update User Modal -->
<div id="create-update-user-modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Create New User</h4>
            </div>
            <div class="modal-body">
                <form method="post" id="create-update-user-form" class="form-horizontal">
                    <div class="form-group row">
                        <label for="first-name" class="col-md-4 col-form-label text-md-right">الإسم الأول</label>

                        <div class="col-md-6">
                            <input id="first-name" type="text" class="form-control" name="first_name" value="" required autocomplete="first_name" autofocus>
                            <!--@error('first_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror-->
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="last-name" class="col-md-4 col-form-label text-md-right">الإسم الأخير</label>

                        <div class="col-md-6">
                            <input id="last-name" type="text" class="form-control" name="last_name" value="" required autocomplete="last_name">
                            <!--@error('last_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror-->
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="email" class="col-md-4 col-form-label text-md-right">الريد الإلكتروني</label>

                        <div class="col-md-6">
                            <input id="email" type="text" class="form-control" name="email" value="" required autocomplete="email">
                            <!--@error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror-->
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="password" class="col-md-4 col-form-label text-md-right">كلمة المرور</label>

                        <div class="col-md-6">
                            <input id="password" type="password" class="form-control" name="password">
                            <!--@if ($errors->has('password'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif-->
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password-confirm" class="col-md-4 col-form-label text-md-right">تأكيد كلمة المرور</label>

                        <div class="col-md-6">
                            <input id="password-confirm" type="password" class="form-control" name="confirm_password">
                        </div>
                    </div>

                    <!-- Roles -->
                    <div class="form-group row">
                        <label for="roles" class="col-md-4 col-form-label text-md-right">الأدوار</label>

                        <div class="col-md-6">
                            <select id="roles" class="form-control selectpicker" name="roles[]"  multiple data-live-search="true" title="اختر دورا" required>
                                <?php foreach ($roles as $role): ?>
                                    <option value="<?php echo $role['id']; ?>"><?php echo $role['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <br />
                    <div class="form-group text-center">
                        <input type="hidden" name="action" id="user-action" value="create" />
                        <input type="hidden" name="hidden_id" id="user-hidden-id" />
                        <input type="submit" name="create_update_user" id="user-action-button" class="btn btn-warning" value="create" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- END: Create/Update User Modal -->

<!-- START: Assign Parent Modal -->
<div id="assign-parent-modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">تحديد الأهل</h4>
            </div>
            <div class="modal-body">
                <form method="post" id="assign-parent-form" class="form-horizontal">
                    <!-- Parents List -->
                    <div class="form-group row">
                        <label for="roles" class="col-md-4 col-form-label text-md-right">الأهل</label>

                        <div class="col-md-6">
                            <select id="parents" class="form-control selectpicker" name="parents[]"  multiple data-live-search="true" title="اختر أهل الطالب" required>
                                <?php foreach ($parents as $parent): ?>
                                    <option class="<?= (isRole($parent['id'], 'instructor') ? 'text-danger' : ''); ?>" value="<?php echo $parent['id']; ?>"><?php echo $parent['first_name'].' '.$parent['last_name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <br />
                    <div class="form-group text-center">
                        <input type="hidden" name="hidden_id" id="user-hidden-id" />
                        <input type="submit" name="assign_parent" id="assign-parent-action-button" class="btn btn-warning" value="create" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- END: Assign Parent Modal -->