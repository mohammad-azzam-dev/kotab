<script type="text/javascript">
    // START: Modal: Confirm Delete Data
        var url;
        // Delete Data that needs only 1 variable/id
        $(document).on('click', '.delete', function() {
        $('.modal-title').text("تأكيد");
            url = $(this).attr('url');

            $('#confirm-form').attr('action', url);
        });
    // END: Modal: Confirm Delete Data

    
    // Bootstrap MultiSelect
    $('.selectpicker').selectpicker();

    
    /* # START: Prevent User From Typing A~Z in Notes # */
    $('.no-latin').on('input', function(e)
    {
        var kCd = e.keyCode || e.which;
        if (kCd == 0 || kCd == 229) { //for android chrome keycode fix
            var str = this.value;
            kCd = str.charCodeAt(str.length - 1);
        }
        
        var chr = String.fromCharCode(kCd);
        var cht_to_check = ['a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
        
        var string = this.value;
        if (cht_to_check.includes(chr))
        {
            cht_to_check.forEach(function(entry) {
                string = string.split(entry).join('');
            });
        }
        $(this).val(string);
    });
    /* # END: Prevent User From Typing A~Z in Notes # */
</script>


<!--Start of Tawk.to Script-->
<script type="text/javascript">
    var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
    (function(){
        var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
        s1.async=true;
        s1.src='https://embed.tawk.to/5efd061e9e5f69442291b126/default';
        s1.charset='UTF-8';
        s1.setAttribute('crossorigin','*');
        s0.parentNode.insertBefore(s1,s0);
    })();
</script>
<!--End of Tawk.to Script-->

<!-- Start: DataTables -->
<script type="text/javascript">
    // Jsudt Scroll X
    $('.dataTable.just-scrollX').dataTable( {
        scrollX: true,
        paging: false,
        searching: false,
        ordering: false,
    });
</script>
<!-- END: DataTables -->





<!-- DataTable CheckBox -->
<script>
    $("#dataTable-checkall").change(function() {
        $(".dataTable-check-item").prop("checked", $(this).prop("checked"));
    });
</script>


<!-- Notification -->
<script>
    function _notify(type, message = '', title = '', url = '', timer = 2000)
    {
        if (title == '')
        {
            if (type == 'success')
            {
                title = 'رائع!';
            }
            else if (type == 'danger')
            {
                title = 'تحذير!';
            }
        }

        $.notify({
            // options
            title: title,
            url: url,
            message: message
          },{
            // settings
            type: type,
            timer: timer,
          }); 
    }
</script>
