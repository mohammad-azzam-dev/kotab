<script type="text/javascript">
    /* # START: Modals # */
        // START: Modal: Create/Update Achievement Category

        // START: Create Achievement Category Button
            $(document).on('click', '#create-achievement-category', function() {
                $('#create-update-achievement-category-form')[0].reset(); // Reset Modal
                
                $('#create-update-achievement-category-form').attr('action', "<?php echo base_url('Academic/AchievementsCategoriesController/create'); ?>")

                $('#achievement-category-form-result').html('');
                $('.modal-title').text("إضافة فئة للإنجازات");
                $('#achievement-category-action-button').val('تأكيد');
                $('#achievement-category-action').val('create');
            });
        // END: Create Achievement Category Button


        // START: Update Achievement Category Button
        $(document).on('click', '.update-achievement-category', function() {
            var id = $(this).attr('id');
            $('#achievement-category-form-result').html('');
            $.ajax({
                url: "<?php echo base_url('Academic/AchievementsCategoriesController/edit'); ?>/" + id,
                dataType: "json",
                success: function(data)
                {
                    $('#achievement-category-name').val(data.result.name);
                    $('#achievement-category-description').val(data.result.description);
                    $('#achievement-category-color').val(data.result.color);

                    $('#create-update-achievement-category-form').attr('action', "<?php echo base_url('Academic/AchievementsCategoriesController/update'); ?>/" + id)

                    $('#achievement-category-hidden-id').val(id);
                    $('.modal-title').text("تعديل الفئة");
                    $('#achievement-category-action-button').val('تأكيد');
                    $('#achievement-category-action').val('update');

                    $('#create-update-achievement-category-modal').modal('show');
                }
            })
        });
        // END: Update Achievement Category Button
    // END: Modal: Create/Update Achievement Category
</script>