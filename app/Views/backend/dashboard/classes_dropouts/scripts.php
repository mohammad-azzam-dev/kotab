<script type="text/javascript">
    /* # START: DataTables # */
    $(document).ready(function() {
        var reports_dataTable = $('#dropouts-dataTable').DataTable({
            "scrollX": true,
            "pageLength" : 10,
        });

        reports_dataTable.on( 'order.dt search.dt', function () {
            reports_dataTable.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            } );
        } ).draw();
    });
    /* # END: DataTables # */
</script>


