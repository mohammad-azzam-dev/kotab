<script>
/* START: Accept "Remove Student Request" */
    $(".accept-remove-student-request").click(function() {
        request_id = $(this).attr('request_id');

        var action_url = '<?php echo base_url('Academic/ClassesRequestsController/accept_remove_student_request'); ?>' + '/' + request_id;
        var _method = 'POST';

        $.ajax({
            url: action_url,
            method: _method,
            cache: false,
            dataType: "json", // We will receive data in Json format
            success: function(data)
            {
                if(data.errors) // Validation error
                {
                    alert("Error");
                }
                if (data.success)
                {
                    alert(data.success);
                    
                    // Change Student Text
                    $('#request-status-' + request_id).html('accepted');
                    // Delete Button
                    $('.button-' + request_id).remove();
                }
            },
            error: function (xhr, textStatus, errorThrown)
            {
                alert("Error: " + errorThrown);
            }
        })
    });
/* END: Accept "Remove Student Request" */

/* START: Reject "Remove Student Request" */
$(".reject-remove-student-request").click(function() {
        request_id = $(this).attr('request_id');

        var action_url = '<?php echo base_url('Academic/ClassesRequestsController/reject_remove_student_request'); ?>' + '/' + request_id;
        var _method = 'POST';

        $.ajax({
            url: action_url,
            method: _method,
            cache: false,
            dataType: "json", // We will receive data in Json format
            success: function(data)
            {
                if(data.errors) // Validation error
                {
                    _notify('danger', 'حدث خطأ ما');
                }
                if (data.success)
                {
                    _notify('success', data.success);
                    
                    // Change Student Text
                    $('#request-status-' + request_id).html('rejected');
                    // Delete Button
                    $('.button-' + request_id).remove();
                }
            },
            error: function (xhr, textStatus, errorThrown)
            {
                _notify('danger', errorThrown);
            }
        })
    });
/* END: Reject "Remove Student Request" */


/* START: Accept "Add Student Request" */
$(".accept-add-student-request").click(function() {
        request_id = $(this).attr('request_id');

        var action_url = '<?php echo base_url('Academic/ClassesRequestsController/accept_add_student_request'); ?>' + '/' + request_id;
        var _method = 'POST';

        $.ajax({
            url: action_url,
            method: _method,
            cache: false,
            dataType: "json", // We will receive data in Json format
            success: function(data)
            {
                if(data.errors) // Validation error
                {
                    _notify('danger', 'حدث خطأ ما');
                }
                if (data.success)
                {
                    _notify('success', data.success);
                    
                    // Change Student Text
                    $('#request-status-' + request_id).html('accepted');
                    // Delete Button
                    $('.button-' + request_id).remove();
                }
            },
            error: function (xhr, textStatus, errorThrown)
            {
                _notify('danger', errorThrown);
            }
        })
    });
/* END: Accept "Add Student Request" */

/* START: Reject "Add Student Request" */
$(".reject-add-student-request").click(function() {
        request_id = $(this).attr('request_id');

        var action_url = '<?php echo base_url('Academic/ClassesRequestsController/reject_add_student_request'); ?>' + '/' + request_id;
        var _method = 'POST';

        $.ajax({
            url: action_url,
            method: _method,
            cache: false,
            dataType: "json", // We will receive data in Json format
            success: function(data)
            {
                if(data.errors) // Validation error
                {
                    _notify('danger', 'حدث خطأ ما');
                }
                if (data.success)
                {
                    _notify('success', data.success);
                    
                    // Change Student Text
                    $('#request-status-' + request_id).html('rejected');
                    // Delete Button
                    $('.button-' + request_id).remove();
                }
            },
            error: function (xhr, textStatus, errorThrown)
            {
                _notify('danger', errorThrown);
            }
        })
    });
/* END: Reject "Add Student Request" */
</script>

<!-- Toggle Collapse Button Icon -->
<script>
    $(".collapse-button").click(function() {
        if (this.innerHTML === "expand_more") {
            this.innerHTML = "expand_less";
        } else {
            this.innerHTML = "expand_more";
        }
    });
</script>