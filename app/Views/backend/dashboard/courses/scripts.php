<script type="text/javascript">
    /* # START: DataTables # */
    $(document).ready(function() {
        var courses_dataTable = $('#courses-dataTable').DataTable({
            "scrollX": true,
            "pageLength" : 10,
            "ajax": {
                url : "<?php echo base_url("CoursesController/coursesDatatable") ?>",
                type : 'GET'
            },
        });

        courses_dataTable.on( 'order.dt search.dt', function () {
            courses_dataTable.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            } );
        } ).draw();
    });
    /* # END: DataTables # */


    /* # START: Modals # */
        // START: Modal: Create/Update Course
        // START: Modal Trigger
        $('#create-update-course-modal').on('shown.bs.modal', function (){});
        // END: Modal Trigger

        // START: Craete Course Button
            $(document).on('click', '#create-course', function() {
                $('#create-update-course-form')[0].reset(); // Reset Modal
                $('.modal-title').text("إضافة مستخدم");
                $('#course-action-button').val('تأكيد');
                $('#course-action').val('create');
            });
        // END: Craete Course Button


        // START: Update Course Button
        $(document).on('click', '.update-course', function() {
            var id = $(this).attr('id');
            $.ajax({
                url: "<?php echo base_url('CoursesController/getCourse'); ?>/" + id,
                dataType: "json",
                success: function(data)
                {
                    $('#course-name').val(data.result.name);
                    $('#course-code-number').val(data.result.code_number);
                    document.getElementById('course-majors').value = data.result.majors_id;
                    $('#course-description').val(data.result.description);
                    $('#course-number-of-credits').val(data.result.number_of_credits);

                    $('#course-hidden-id').val(id);
                    $('.modal-title').text("تعديل مستخدم");
                    $('#course-action-button').val('تأكيد');
                    $('#course-action').val('update');

                    $('#create-update-course-modal').modal('show');
                }
            })
        });
        // END: Update Course Button

        // START: On Submit Course Modal
            $('#create-update-course-form').on('submit', function(e){
            e.preventDefault();
            var action_url = '';
            var _method = '';

            if ($('#course-action').val() == 'create')
            {
                action_url = "<?php echo base_url('CoursesController/create'); ?>";
                _method = "POST";
            }
            if ($('#course-action').val() == 'update')
            {
                action_url = "<?php echo base_url('CoursesController/update'); ?>/" + $('#course-hidden-id').val();
                _method = "POST";
            }

            $.ajax({
                url: action_url,
                method: _method,
                cache: false,
                data: $('#create-update-course-form').serialize(),
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
                        if ($('#course-action').val() !== 'update')
                        {
                        $('#create-update-course-form')[0].reset(); // Reset Modal
                        }
                        $('#courses-dataTable').DataTable().ajax.reload(); // Refrsh Data Table
                    }
                },
                error: function (xhr, textStatus, errorThrown)
                {
                    _notify('danger', errorThrown);
                }
            })
            });
        // END: On Submit Course Modal
    // END: Modal: Create/Update Course
</script>