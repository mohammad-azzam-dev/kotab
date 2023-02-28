<!-- START: Remove Student Request Modal -->
<div id="remove-student-modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">سبب إزالة التلميذ</h4>
            </div>
            <div class="modal-body">
                <form method="post" id="remove-student-form" class="form-horizontal">
                    <!-- Reason -->
                    <div class="form-group row">
                        <label for="reason" class="col-md-4 col-form-label text-md-right">اذكر السبب</label>

                        <div class="col-md-6">
                            <input id="reason" type="text" class="form-control" name="reason" value="" required autocomplete="reason">
                        </div>
                    </div>

                    <br />
                    <div class="form-group text-center">
                        <input type="hidden" name="hidden_id" id="student-hidden-id" />
                        <input type="submit" name="remove_student_request_button" id="remove-student-request-button" class="btn btn-warning" value="تأكيد" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- END: Remove Student Request Modal -->