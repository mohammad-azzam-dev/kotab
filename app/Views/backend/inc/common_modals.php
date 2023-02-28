<!-- START: Delete Confirm Modal -->
<div id="confirmModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h2 class="right modal-title">تأكيد</h2>
            </div>
            <div class="modal-body">
                <h4 class="text-center" style="margin:0;">هل أنت متأكد من أنك تريد اتمام هذه العملية؟</h4>
            </div>
            <div class="modal-footer">
                <form method="POST" id="confirm-form" class="form-horizontal m-0" action="url">
                    <div class="form-group text-center">
                        <input type="submit" name="confirm_form" id="confirm-form" class="btn btn-danger" value="تأكيد" />
                    </div>
                </form>

                <div class="form-group text-center">
                    <button type="button" class="btn btn-default" data-dismiss="modal">إلغاء</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END: Delete Confirm Modal -->
