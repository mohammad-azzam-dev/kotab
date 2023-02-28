<script type="text/javascript">
    /* # START: DataTables # */
    $(document).ready(function() {
        var achievements_dataTable = $('#achievements-dataTable').DataTable({
            "scrollX": true,
            "pageLength" : 10,
        });

        achievements_dataTable.on( 'order.dt search.dt', function () {
            achievements_dataTable.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            } );
        } ).draw();

        document.getElementById('achievements-dataTable').getElementsByTagName("thead")[0].style.display = 'none';
    });
    /* # END: DataTables # */


    /* # START: Modals # */
        // START: Modal: Create/Update Achievement
            // START: Create Achievement Button
                $(document).on('click', '#create-achievement', function() {
                    var cur_sub_category_id = $(this).attr('cur_sub_category_id');

                    $('#create-update-achievement-form')[0].reset(); // Reset Modal
                    $('#create-update-achievement-form .form-input-group').empty(); // Delete Modal Form Input Fields

                    $('#create-update-achievement-form').attr('action', "<?= base_url('Academic/AchievementsController/create/'.$achievements_data['category']['id']); ?>" + "/" + cur_sub_category_id);
                    $('.modal-title').text("إضافة إنجاز");
                    $('#achievement-action-button').val('تأكيد');
                    $('#achievement-action').val('create');
                    $.ajax({
                        url: "<?php echo base_url('Academic/AchievementsCategoriesController/get_header_data'); ?>/" + cur_sub_category_id,
                        dataType: "json",
                        success: function(data)
                        {
                            data.result.forEach(function (col) {
                                var content = '';
                                content += '<!-- Achievement Header -->'
                                        +  '<div class="form-group row">'
                                        +  '<label for="achievement-header-' + col.id + '" class="col-md-4 col-form-label text-md-right">' + col.name + '</label>'
                                        +  '<div class="col-md-6">'
                                        +  '<input id="achievement-header-' + col.id + '" type="text" class="form-control" name="achievement_header_' + col.id + '" value="" required>'
                                        +  '</div>'
                                        +  '</div>';
                                $('#create-update-achievement-form .form-input-group').append(content);
                            })
                        }
                    })
                });
            // END: Create Achievement Button


            // START: Update Achievement Button
            $(document).on('click', '.update-achievement', function() {
                var cur_sub_category_id = $(this).attr('cur_sub_category_id');
                var row_code = $(this).attr('row_code');

                $('#create-update-achievement-form')[0].reset(); // Reset Modal
                $('#create-update-achievement-form .form-input-group').empty(); // Delete Modal Form Input Fields
                $('#create-update-achievement-form').attr('action', "<?= base_url('Academic/AchievementsController/update/'.$achievements_data['category']['id']); ?>" + "/" + cur_sub_category_id + "/" + row_code);
                $.ajax({
                    url: "<?php echo base_url('Academic/AchievementsCategoriesController/get_header_data'); ?>/" + cur_sub_category_id,
                        dataType: "json",
                        success: function(data)
                        {
                            data.result.forEach(function (col) {
                                var content = '';
                                content += '<!-- Achievement Header -->'
                                        +  '<div class="form-group row">'
                                        +  '<label for="achievement-header-' + col.id + '" class="col-md-4 col-form-label text-md-right">' + col.name + '</label>'
                                        +  '<div class="col-md-6">'
                                        +  '<input id="achievement-header-' + col.id + '" type="text" class="form-control" name="achievement_header_' + col.id + '" value="" required>'
                                        +  '</div>'
                                        +  '</div>';
                                $('#create-update-achievement-form .form-input-group').append(content);
                            })

                            // Get Achievements Data After Geting Header Data
                            $.ajax({
                                url: "<?php echo base_url('Academic/AchievementsController/edit'); ?>/" + row_code,
                                dataType: "json",
                                success: function(data_2)
                                {
                                    data.result.forEach(function (col) {
                                        data_2.result.forEach(function (achievement) {
                                            if (achievement.header_field_id == col.id)
                                            {
                                                $('#achievement-header-' + col.id).val(achievement.name);
                                            }
                                        })
                                    })

                                    $('#achievement-hidden-row-code').val(row_code);
                                    $('.modal-title').text("تعديل مهمة");
                                    $('#achievement-action-button').val('تأكيد');
                                    $('#achievement-action').val('update');

                                    $('#create-update-achievement-modal').modal('show');
                                }
                            })
                        }
                    })
            });
            // END: Update Achievement Button
        // END: Modal: Create/Update Achievement

        // START: Modal: Create/Update Achievements Sub Category
            // START: Create Achievement Category Button
            $(document).on('click', '#create-achievements-sub-category', function() {
                    $('#create-update-achievements-sub-category-form')[0].reset(); // Reset Modal

                    $('#create-update-achievements-sub-category-form').attr('action', "<?php echo base_url('Academic/AchievementsCategoriesController/create/'.$achievements_data['category']['id']); ?>")

                    $('.modal-title').text("إضافة فئة للإنجازات");
                    $('#achievement-category-action-button').val('تأكيد');
                    $('#achievement-category-action').val('create');
                });
            // END: Create Achievement Category Button


            // START: Update Achievement Category Button
            $(document).on('click', '.update-achievements-sub-category', function() {
                var id = $(this).attr('id');
                $.ajax({
                    url: "<?php echo base_url('Academic/AchievementsCategoriesController/edit'); ?>/" + id,
                    dataType: "json",
                    success: function(data)
                    {
                        $('#achievement-category-name').val(data.result.name);
                        $('#achievement-category-description').val(data.result.description);

                        $('#create-update-achievements-sub-category-form').attr('action', "<?php echo base_url('Academic/AchievementsCategoriesController/update'); ?>/" + id + "/<?= $achievements_data['category']['id'] ?>")

                        $('#achievement-category-hidden-id').val(id);
                        $('.modal-title').text("تعديل الفئة");
                        $('#achievement-category-action-button').val('تأكيد');
                        $('#achievement-category-action').val('update');

                        $('#create-update-achievements-sub-category-modal').modal('show');
                    }
                })
            });
            // END: Update Achievement Category Button
        // END: Modal: Create/Update Achievements Sub Category
    /* END: Modals */
</script>