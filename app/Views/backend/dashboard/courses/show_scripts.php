<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript">
    /* # START: DataTables # */
        /*$(document).ready(function() {
            var lessons_dataTable = $('#lessons-dataTable').DataTable({
                "scrollX": true,
                "pageLength" : 10,
                "ajax": {
                    url : "<?php //echo base_url("Academic/LessonsController/lessonsDatatable/".$course_id) ?>",
                    type : 'GET'
                },
            });

            lessons_dataTable.on( 'order.dt search.dt', function () {
                lessons_dataTable.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = i+1;
                } );
            } ).draw();
        });*/
    /* # END: DataTables # */


    /* # START: Modals # */
        // START: Modal: Create/Update Lesson Lesson

            // START: Create Lesson Button
                $(document).on('click', '#create-lesson', function() {
                    var section_id = $(this).attr('section-id');
                    $('#create-update-lesson-form')[0].reset(); // Reset Modal
                    $('#lesson-section-hidden-id').val(section_id);
                    $('.modal-title').text("إضافة درس");
                    $('#lesson-submit-button').val('تأكيد');
                    $('#lesson-action').val('create');
                });
            // END: Create Lesson Button


            // START: Update Lesson Button
            $(document).on('click', '.update-lesson', function() {
                var id = $(this).attr('id');
                var section_id = $(this).attr('section-id');
                $.ajax({
                    url: "<?php echo base_url('Academic/LessonsController/getLesson'); ?>/" + id,
                    dataType: "json",
                    success: function(data)
                    {
                        $('#lesson-name').val(data.result.name);
                        $('#lesson-description').val(data.result.description);

                        $('#lesson-hidden-id').val(id);
                        $('#lesson-section-hidden-id').val(section_id);
                        $('.modal-title').text("تعديل درس");
                        $('#lesson-submit-button').val('تأكيد');
                        $('#lesson-action').val('update');

                        $('#create-update-lesson-modal').modal('show');
                    }
                })
            });
            // END: Update Lesson Button

            // START: On Submit Form
                $('#create-update-lesson-form').on('submit', function(e){
                    e.preventDefault();
                    var action_url = '';
                    var _method = '';

                    if ($('#lesson-action').val() == 'create')
                    {
                        action_url = "<?php echo base_url('Academic/LessonsController/create'); ?>" + "/" + $('#lesson-section-hidden-id').val();
                        _method = "POST";
                    }
                    if ($('#lesson-action').val() == 'update')
                    {
                        action_url = "<?php echo base_url('Academic/LessonsController/update'); ?>/" + $('#lesson-hidden-id').val() + "/" + $('#lesson-section-hidden-id').val();
                        _method = "POST";
                    }

                    $.ajax({
                        url: action_url,
                        method: _method,
                        cache: false,
                        data: $('#create-update-lesson-form').serialize(),
                        dataType: "json", // We will receive data in Json format
                        success: function(data)
                        {
                            var html = '';
                            if(data.errors) // Validation error
                            {
                                _notify('danger', data.errors);
                            }
                            if (data.success)
                            {
                                _notify('success', data.success);
                                if ($('#lesson-action').val() !== 'update')
                                {
                                    $('#create-update-lesson-form')[0].reset(); // Reset Modal
                                }
                                location.reload(); // Reload Page
                            }
                        },
                        error: function (xhr, textStatus, errorThrown)
                        {
                            _notify('danger', errorThrown);
                        }
                    })
                });
            // END: On Submit Form
        // END: Modal: Create/Update Lesson

        
        // START: Modal: Create/Update Section

            // START: Create Button
                $(document).on('click', '#create-section', function() {
                    $('#create-update-section-form')[0].reset(); // Reset Form
                    $('.modal-title').text("إضافة قسم");
                    $('#section-submit-button').val('تأكيد');
                    $('#section-action').val('create');
                });
            // END: Create Lesson Button


            // START: Get Data Button (To Update It)
                $(document).on('click', '.update-section', function() {
                    var id = $(this).attr('id');
                    
                    $.ajax({
                        url: "<?php echo base_url('Academic/SectionsController/getData'); ?>/" + id,
                        dataType: "json",
                        success: function(data)
                        {
                            $('#section-name').val(data.result.name);
                            $('#section-description').val(data.result.description);

                            $('#section-hidden-id').val(id);
                            $('.modal-title').text("تعديل قسم");
                            $('#section-submit-button').val('تأكيد');
                            $('#section-action').val('update');

                            $('#create-update-section-modal').modal('show');
                        }
                    })
                });
            // END: Update Lesson Button

            // START: On Submit Form
                $('#create-update-section-form').on('submit', function(e){
                    e.preventDefault();
                    var action_url = '';
                    var _method = '';

                    if ($('#section-action').val() == 'create')
                    {
                        action_url = "<?php echo base_url('Academic/SectionsController/create/'.$course_id); ?>";
                        _method = "POST";
                    }
                    if ($('#section-action').val() == 'update')
                    {
                        action_url = "<?php echo base_url('Academic/SectionsController/update'); ?>/" + $('#section-hidden-id').val() + "/<?php echo $course_id; ?>";
                        _method = "POST";
                    }

                    $.ajax({
                        url: action_url,
                        method: _method,
                        cache: false,
                        data: $('#create-update-section-form').serialize(),
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
                                if ($('#section-action').val() !== 'update')
                                {
                                    $('#create-update-section-form')[0].reset(); // Reset Modal
                                }
                                location.reload(); // Reload Page
                            }
                        },
                        error: function (xhr, textStatus, errorThrown)
                        {
                            _notify('danger', errorThrown);
                        }
                    })
                });
            // END: On Submit Form
        // END: Modal: Create/Update Section

        // START: Order Sections
            // START: Get Course's Sections
                $(document).on('click', '.order-sections', function() {
                    var course_id = $(this).attr('course_id');
                    $('#order-sections-list').html('');
                    $.ajax({
                        url: "<?php echo base_url('CoursesController/get_course_sections/'); ?>/" + course_id + "/json",
                        dataType: "json",
                        success: function(data)
                        {
                            var sections_list = '';
                            data.result.forEach(function(section) {
                                sections_list += "<li class='list-group-item' section-id='" + section.id + "'><span class='ui-icon ui-icon-arrowthick-2-n-s'></span>" + section.name + "</li>";
                            });

                            $('#order-sections-list').append(sections_list);
                            /*$('#first-name').val(data.result.first_name);
                            $('#last-name').val(data.result.last_name.charAt(0));
                            $('#email').val(data.result.email);

                            $('.selectpicker').selectpicker('deselectAll');
                            var roles_id = new Array();
                            data.result.roles.forEach(function(role)
                            {
                                roles_id.push(role['role_id']);
                            });
                            $('.selectpicker').selectpicker('val', roles_id);

                            $('#user-hidden-id').val(id);
                            $('.modal-title').text("تعديل مستخدم");
                            $('#user-action-button').val('تأكيد');
                            $('#user-action').val('update');

                            $('#create-update-user-modal').modal('show');*/
                        }
                    })
                });
            // END: Get Course's Sections

            // Start: Define The Sortable List
                $('#order-sections-list').sortable({
                    placeholder: 'ui-state-highlight',
                    cursor: "move",
                });
            // END: Define The Sortable List

            // START: Submit Sorted List
                $(document).on("click", "#order-sections-submit-button", function () {
                    var section_id_array = new Array();
                    $('#order-sections-list li').each(function() {
                        section_id_array.push($(this).attr("section-id"));
                    });
                    $.ajax({
                        url: "<?= base_url('CoursesController/sort_sections'); ?>",
                        method: "POST",
                        data: { section_id_array: section_id_array},
                        success: function (data)
                        {
                            alert ('Data Sorted Successfully :)');
                            location.reload();
                        }
                    })
                });
            // END: Submit Sorted List
        // END: Order Sections
    /* # END: Modals # */
</script>