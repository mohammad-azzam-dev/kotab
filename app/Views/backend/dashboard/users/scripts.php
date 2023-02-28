<script type="text/javascript">
    /* # START: DataTables # */
    $(document).ready(function() {
        var users_dataTable = $('#users-dataTable').DataTable({
            "scrollX": true,
            "pageLength" : 10,
            "ajax": {
                url : "<?php echo base_url("dashboard/users/dataTable") ?>",
                type : 'GET'
            },
        });

        users_dataTable.on( 'order.dt search.dt', function () {
            users_dataTable.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            } );
        } ).draw();
    });
    /* # END: DataTables # */


    /* # START: Modals # */
        // START: Modal: Create/Update User
            // START: Modal Trigger
                $('#create-update-user-modal').on('shown.bs.modal', function (){});
            // END: Modal Trigger

            // START: Create User Button
                $(document).on('click', '#create-user', function() {
                    $('#create-update-user-form')[0].reset(); // Reset Modal
                    $('.selectpicker#roles').selectpicker('deselectAll');
                    $('.selectpicker#roles').selectpicker('refresh');
                    $('#user-form-result').html('');
                    $('.modal-title').text("إضافة مستخدم");
                    $('#user-action-button').val('تأكيد');
                    $('#user-action').val('create');
                });
            // END: Create User Button


            // START: Update User Button
                $(document).on('click', '.update-user', function() {
                    var id = $(this).attr('id');
                    $.ajax({
                        url: "<?php echo base_url('dashboard/users/edit'); ?>/" + id,
                        dataType: "json",
                        success: function(data)
                        {
                            $('#first-name').val(data.result.first_name);
                            $('#last-name').val(data.result.last_name.charAt(0));
                            $('#email').val(data.result.email);

                            $('.selectpicker#roles').selectpicker('deselectAll');
                            var roles_id = new Array();
                            data.result.roles.forEach(function(role)
                            {
                                roles_id.push(role['role_id']);
                            });
                            $('.selectpicker#roles').selectpicker('val', roles_id);

                            $('#user-hidden-id').val(id);
                            $('.modal-title').text("تعديل مستخدم");
                            $('#user-action-button').val('تأكيد');
                            $('#user-action').val('update');

                            $('#create-update-user-modal').modal('show');
                        }
                    })
                });
            // END: Update User Button

            // START: On Submit User Modal
                $('#create-update-user-form').on('submit', function(e){
                e.preventDefault();
                var action_url = '';
                var _method = '';

                if ($('#user-action').val() == 'create')
                {
                    action_url = "<?php echo base_url('dashboard/users/create'); ?>";
                    _method = "POST";
                }
                if ($('#user-action').val() == 'update')
                {
                    action_url = "<?php echo base_url('dashboard/users/update'); ?>/" + $('#user-hidden-id').val();
                    _method = "POST";
                }

                $.ajax({
                    url: action_url,
                    method: _method,
                    cache: false,
                    data: $('#create-update-user-form').serialize(),
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
                            if ($('#user-action').val() !== 'update')
                            {
                            $('#create-update-user-form')[0].reset(); // Reset Modal
                            }
                            $('#users-dataTable').DataTable().ajax.reload(); // Refrsh Data Table
                        }
                    },
                    error: function (xhr, textStatus, errorThrown)
                    {
                        _notify('danger', errorThrown);
                    }
                })
                });
            // END: On Submit User Modal
        // END: Modal: Craete/Update User


        
        // START: Modal: Assign Parent
            // START: Assign Parent Button
                $(document).on('click', '.assign-parent', function() {
                    var id = $(this).attr('id');
                    $.ajax({
                        url: "<?php echo base_url('dashboard/users/getUserParents'); ?>/" + id,
                        dataType: "json",
                        success: function(data)
                        {
                            $('.selectpicker').selectpicker('deselectAll');
                            var parents_id = data.parents_id;
                            $('.selectpicker').selectpicker('val', parents_id);

                            $('#user-hidden-id').val(id);
                            $('.modal-title').text("تعديل مستخدم");
                            $('#assign-parent-action-button').val('تأكيد');

                            $('#assign-parent-modal').modal('show');
                        }
                    })
                });
            // END: Assign Parent Button

            // START: On Submit Assign Parent Modal
                $('#assign-parent-form').on('submit', function(e){
                    e.preventDefault();
                    var action_url = "<?php echo base_url('dashboard/users/assign-parent'); ?>/" + $('#user-hidden-id').val();
                    var _method = "POST";

                    $.ajax({
                        url: action_url,
                        method: _method,
                        cache: false,
                        data: $('#assign-parent-form').serialize(),
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
                            }
                        },
                        error: function (xhr, textStatus, errorThrown)
                        {
                            _notify('danger', errorThrown);
                        }
                    })
                });
            // END: On Submit Assign Parent Modal
        // END: Modal: Assign Parent
    /* # END: Modals # */
</script>