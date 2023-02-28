<script type="text/javascript">
    /* # START: DataTables # */
    $(document).ready(function() {
        var majors_dataTable = $('#majors-dataTable').DataTable({
            "scrollX": true,
            "pageLength" : 10,
            "ajax": {
                url : "<?php echo base_url("MajorsController/majorsDatatable") ?>",
                type : 'GET'
            },
        });

        majors_dataTable.on( 'order.dt search.dt', function () {
            majors_dataTable.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            } );
        } ).draw();
    });
    /* # END: DataTables # */


    /* # START: Modals # */
        // START: Modal: Create/Update Major
        // START: Modal Trigger
        $('#create-update-major-modal').on('shown.bs.modal', function (){});
        // END: Modal Trigger

        // START: Craete Major Button
            $(document).on('click', '#create-major', function() {
            $('#create-update-major-form')[0].reset(); // Reset Modal
            $('.modal-title').text("إضافة إختصاص");
            $('#major-action-button').val('تأكيد');
            $('#major-action').val('create');
            });
        // END: Create Major Button


        // START: Update Major Button
        $(document).on('click', '.update-major', function() {
            var id = $(this).attr('id');
            $('#major-form-result').html('');
            $.ajax({
                url: "<?php echo base_url('MajorsController/getMajor'); ?>/" + id,
                dataType: "json",
                success: function(data)
                {
                    $('#major-name').val(data.result.name);
                    $('#major-code').val(data.result.code);
                    $('#major-description').val(data.result.description);
                    /*$('#major-credit-price').val(data.result.credit_price);*/

                    $('#major-hidden-id').val(id);
                    $('.modal-title').text("تعديل مستخدم");
                    $('#major-action-button').val('تأكيد');
                    $('#major-action').val('update');

                    $('#create-update-major-modal').modal('show');
                }
            })
        });
        // END: Update Major Button

        // START: On Submit Major Modal
            $('#create-update-major-form').on('submit', function(e){
            e.preventDefault();
            var action_url = '';
            var _method = '';

            if ($('#major-action').val() == 'create')
            {
                action_url = "<?php echo base_url('MajorsController/create'); ?>";
                _method = "POST";
            }
            if ($('#major-action').val() == 'update')
            {
                action_url = "<?php echo base_url('MajorsController/update'); ?>/" + $('#major-hidden-id').val();
                _method = "POST";
            }

            $.ajax({
                url: action_url,
                method: _method,
                cache: false,
                data: $('#create-update-major-form').serialize(),
                dataType: "json", // We will receive data in Json format
                success: function(data)
                {
                    if(data.errors) // Validation error
                    {
                        _notify('danger', data.errors);
                    }
                    if (data.success)
                    {
                        _notify('success', data.success);
                        if ($('#major-action').val() !== 'update')
                        {
                            $('#create-update-major-form')[0].reset(); // Reset Modal
                        }
                        $('#majors-dataTable').DataTable().ajax.reload(); // Refrsh Data Table
                    }
                },
                error: function (xhr, textStatus, errorThrown)
                {
                    _notify('danger', errorThrown);
                }
            })
            });
        // END: On Submit Major Modal
    // END: Modal: Craete/Update Major
</script>