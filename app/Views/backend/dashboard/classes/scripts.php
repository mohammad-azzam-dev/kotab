
<script type="text/javascript">
    /* # START: DataTables # */    
        $(document).ready(function() {
            // Classes DataTable
            var classes_dataTable = $('#classes-dataTable').DataTable({
                "scrollX": true,
                "pageLength" : 10,
                "ajax": {
                    <?php if (!isset($dataTable_parm)) $dataTable_parm = ""; ?>
                    url : "<?php echo base_url("ClassesController/classesDatatable".'/'.$dataTable_parm) ?>",
                    type : 'GET'
                },
            });

            classes_dataTable.on( 'order.dt search.dt', function () {
                classes_dataTable.column(1, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = i+1;
                } );
            } ).draw();

        });
    /* # END: DataTables # */


    /* # START: Modals # */
        // START: Modal: Create/Update Class
            // START: Create Class Button
                $(document).on('click', '#create-class', function() {
                    $('#create-update-class-form')[0].reset(); // Reset Modal
                    $('.modal-title').text("إضافة مستخدم");
                    $('#class-action-button').val('تأكيد');
                    $('#class-action').val('create');
                });
            // END: Create Class Button


            // START: Update Class Button
            $(document).on('click', '.update-class', function() {
                var id = $(this).attr('id');
                $.ajax({
                    url: "<?php echo base_url('ClassesController/getClass'); ?>/" + id,
                    dataType: "json",
                    success: function(data)
                    {
                        $('#class-name').val(data.result.name);
                        document.getElementById('major').value = data.result.major_id;
                        document.getElementById('course').value = data.result.course_id;
                        $('#instructor').val(data.result.instructor_id);
                        $('#place').val(data.result.place_id);
                        document.getElementById('date').value = data.result.date_id;
                        document.getElementById('time').value = data.result.time;
                        document.getElementById('status').value = data.result.status;
                        
                        $('#class-hidden-id').val(id);
                        $('.modal-title').text("تعديل صف");
                        $('#class-action-button').val('تأكيد');
                        $('#class-action').val('update');

                        $('#create-update-class-modal').modal('show');
                    }
                })
            });
            // END: Update Class Button

            // START: On Submit Class Modal
                $('#create-update-class-form').on('submit', function(e){
                    e.preventDefault();
                    var action_url = '';
                    var _method = '';

                    if ($('#class-action').val() == 'create')
                    {
                        action_url = "<?php echo base_url('ClassesController/create'); ?>";
                        _method = "POST";
                    }
                    if ($('#class-action').val() == 'update')
                    {
                        action_url = "<?php echo base_url('ClassesController/update'); ?>/" + $('#class-hidden-id').val();
                        _method = "POST";
                    }

                    $.ajax({
                        url: action_url,
                        method: _method,
                        cache: false,
                        data: $('#create-update-class-form').serialize(),
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
                                if ($('#class-action').val() !== 'update')
                                {
                                    $('#create-update-class-form')[0].reset(); // Reset Modal
                                }
                                $('#classes-dataTable').DataTable().ajax.reload(); // Refrsh Data Table
                            }
                        },
                        error: function (xhr, textStatus, errorThrown)
                        {
                            _notify('danger', errorThrown);
                        }
                    })
                });
            // END: On Submit Class Modal
        // END: Modal: Create/Update Class

    /* # START: Students Enrollement (Admin) # */
        var user_id = -1;
        var i = 0;
        $(document).on('click', '#add-student-admin', function() {
            i++;
            e = document.getElementById('users-list');
            user_id = e.value;
            user_name = e.options[e.selectedIndex].text;
            if (user_id != '')
                $('#enroll-students-table').append('<tr id="student-' + i + '"><td><p style="font-weight: 700;">' + user_name + '</p><input type="hidden" name="students_id[]" value="' + user_id + '"></td><td><a href="#" name="remove_student" id="' + i + '" class="btn btn-danger btn-remove-admin btn-just-icon"><i class="material-icons">remove</i></button></td></tr>');
        });
        /*$(document).on('click', '.btn-remove-admin', function() {
            var button_id = $(this).attr("id");
            $('#student-' + button_id + '').remove();
        });*/
    /* # END: Students Enrollement (Admin) # */

    /* # START: Remove Student Request # */
        $(document).on('click', '.btn-remove-student', function() {
            var student_id = $(this).attr('id');
            $('#student-hidden-id').val(student_id);
        });

        $('#remove-student-form').on('submit', function(e){
            e.preventDefault();
            var student_id = $('#student-hidden-id').val();
            <?php if (!isset($class_id)) // This If Statement Was Added Because This Script File Is Used In Another Function (Controller View) Were $class_id Is Not Defined
                {
                    $class_id = '';
                }
            ?>
            var action_url = '<?php echo base_url('Academic/ClassesRequestsController/remove_student_request/'.$class_id); ?>' + '/' + student_id;
            var _method = 'POST';

            $.ajax({
                url: action_url,
                method: _method,
                cache: false,
                data: $('#remove-student-form').serialize(),
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
                        // Reset Modal
                        $('#remove-student-form')[0].reset();
                        // Change Student Text
                        if (<?= isRole($_SESSION['id'], 'admin') ?>)
                        {
                            $('#student-name-' + student_id).html($('#student-name-' + student_id).text() + ' (تم حذف الطالب بنجاح)');
                            $('#student-hidden-id-' + student_id).attr('name', '');
                        }
                        else if (<?= is_instructor_of($class_id) ?>)
                        {
                            $('#student-name-' + student_id).html($('#student-name-' + student_id).text() + ' (طلب الحذف قيد المراجعة)');
                        }
                        // Delete Remove Button
                        $('button#' + student_id).remove();
                        // Close Modal
                        $('#remove-student-modal').modal('toggle');
                    }
                },
                error: function (xhr, textStatus, errorThrown)
                {
                    _notify('danger', errorThrown);
                }
            })
        });
    /* # END: Remove Student Request # */

    /* # START: Add Student Request # */
        $('#add-student-request-form').on('submit', function(e){
            e.preventDefault();
            <?php if (!isset($class_id)) // This If Statement Were Added Because This Script File Is Used In Another Function (Controller) Were $class_id Is Not Defined
                {
                    $class_id = '';
                }
            ?>
            var action_url = '<?php echo base_url('Academic/ClassesRequestsController/add_student_request/'.$class_id); ?>';
            var _method = 'POST';

            $.ajax({
                url: action_url,
                method: _method,
                cache: false,
                data: $('#add-student-request-form').serialize(),
                dataType: "json", // We will receive data in Json format
                success: function(data)
                {
                    if(data.errors) // Validation error
                    {
                        _notify('danger', data.errors);
                    }
                    if (data.success)
                    {
                        append_data = '<tr>'
                                        + '<td>'
                                            + $('#add-student-request-first-name').val()
                                        + '</td>'
                                        + '<td>'
                                            + $('#add-student-request-middle-name').val()
                                        + '</td>'
                                        + '<td>'
                                            + $('#add-student-request-last-name').val()
                                        + '</td>'
                                        + '<td>'
                                            + $('#add-student-request-birth-year').val()
                                        + '</td>'
                                        + '<td>'
                                            + 'pending'
                                        + '</td>'
                                    + '</tr>';


                        $('#add-student-request-table tbody').append(append_data);
                        $('#add-student-request-form')[0].reset();
                    }
                },
                error: function (xhr, textStatus, errorThrown)
                {
                    _notify('danger', errorThrown);
                }
            })
        });
    /* # END: Add Student Request # */
</script>

<!-- Export Data -->
<script charset="utf-8" type="text/javascript">
    $('#export-classes-reports-to-excel').click(function() {
        var id = $('.dataTable-check-item:checked').map(function() {
            var txt = $(this).val();
            var class_id = txt.match(/\d/g);
            class_id = class_id.join("");
            return class_id;
        }).get().join(',');
        var export_from_date = $('#export-from-date').val();
        var export_to_date = $('#export-to-date').val();
        console.log(export_to_date);
        $.post('<?php echo base_url('Academic/ReportsController/export_to_excel'); ?>', {id: id, from_date: export_from_date, to_date: export_to_date}, function(response) {
            response = JSON.parse(response);

            var downloadLink;
            var uri = 'data:application/vnd.ms-excel;base64,'
            , tmplWorkbookXML = '<?= "<?xml version=\"1.0\" encoding=\"utf-8\"?><?mso-application progid=\"Excel.Sheet\"?>"; ?>'
            + '	<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet" xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel"  xmlns:html="http://www.w3.org/TR/REC-html40">'
            + '		<DocumentProperties xmlns="urn:schemas-microsoft-com:office:office">'
            + '			<Author>Qompare</Author>'
            + '			<Created>{created}</Created>'
            + '		</DocumentProperties>'
            + '		<Styles>'
            + '			<Style ss:ID="Default" ss:Name="Normal">'
            + '				<NumberFormat ss:Format=""/>'
            + '			</Style>'
            + '			<Style ss:ID="Header">'
            + '				<Alignment ss:Vertical="Bottom"/>'
            + '				<Borders>'
            + '					<Border ss:Color="#000000" ss:Weight="2" ss:LineStyle="Continuous" ss:Position="Right"/>'
            + '					<Border ss:Color="#000000" ss:Weight="2" ss:LineStyle="Continuous" ss:Position="Left"/>'
            + '					<Border ss:Color="#000000" ss:Weight="2" ss:LineStyle="Continuous" ss:Position="Top"/>'
            + '					<Border ss:Color="#000000" ss:Weight="2" ss:LineStyle="Continuous" ss:Position="Bottom"/>'
            + '				</Borders>'
            + '				<Font ss:FontName="Calibri" ss:Size="12" ss:Color="#000000"/>'
            + '				<Interior ss:Color="#cccccc" ss:Pattern="Solid" />'
            + '				<NumberFormat/>'
            + '				<Protection/>'
            + '			</Style>'
            + '			<Style ss:ID="Changed">'
            + '				<Borders/>'
            + '				<Font ss:Color="#ff0000"></Font>'
            + '				<Interior ss:Color="#99CC00" ss:Pattern="Solid"></Interior>'
            + '				<NumberFormat/>'
            + '				<Protection/>'
            + '			</Style>'
            + '			<Style ss:ID="Missed">'
            + '				<Borders/>'
            + '				<Font ss:Color="#ff0000"></Font>'
            + '				<Interior ss:Color="#ff0000" ss:Pattern="Solid"></Interior>'
            + '				<NumberFormat/>'
            + '				<Protection/>'
            + '			</Style>'
            + '			<Style ss:ID="Decimals">'
            + '				<NumberFormat ss:Format="Fixed"/>'
            + '			</Style>' 
            +           response["styles"]
            + '	    </Styles>' 
            + '	{worksheets}'
            + '</Workbook>'
            , tmplWorksheetXML = '<Worksheet ss:Name="{nameWS}" ss:RightToLeft="1">'
            + '	<ss:Table ss:DefaultColumnWidth="75" ss:DefaultRowHeight="20">'
            + '		{rows}'
            + '	</ss:Table>'
            + '</Worksheet>'
            , tmplCellXML = '			<ss:Cell{attributeStyleID}{attributeFormula}>'
            + '				<ss:Data ss:Type="{nameType}">{data}</ss:Data>'
            + '			</ss:Cell>'
            , base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) }
            , format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) }


            
            /*tab_text= tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
            tab_text= tab_text.replace(/<img[^>]*>/gi,""); // remove if u want images in your table
            tab_text= tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params*/

            var dummy = document.createElement("div");
            document.body.appendChild(dummy);
            dummy.innerHTML = response['tables'];


            //var tables = ['tbl1'];
            var tables = document.getElementsByClassName('selected-classes-reports');

            console.log(tables);
            var appname = 'Excel';

            var wsnames = response['wsnames'];

            var ctx = "";
            var workbookXML = "";
            var worksheetsXML = "";
            var rowsXML = "";

            for (var i = 0; i < tables.length; i++) {
                //if (!tables[i].nodeType) tables[i] = document.getElementById(tables[i]);
                //if (!tables[i].nodeType) tables[i] = document.getElementsByClassName('selected-classes-reports')[i];
                for (var j = 0; j < tables[i].rows.length; j++) {
                rowsXML += '		<ss:Row>'
                for (var k = 0; k < tables[i].rows[j].cells.length; k++) {
                    var dataType = tables[i].rows[j].cells[k].getAttribute("data-type");
                    var dataStyle = tables[i].rows[j].cells[k].getAttribute("data-style");
                    var mergeAcross = tables[i].rows[j].cells[k].getAttribute("mergeAcross");
                    //var dataValue = tables[i].rows[j].cells[k].getAttribute("data-value");
                    //dataValue = (dataValue)?dataValue:tables[i].rows[j].cells[k].innerHTML; // if "tables[i].rows[j].cells[k].innerHTML" is empty, we get an error when opening the excel file
                    var dataValue = (tables[i].rows[j].cells[k].innerHTML == '')?'\xa0':tables[i].rows[j].cells[k].innerHTML; // We add a "\xa0" if it is empty cell, because we get error when opening the excel file if it is empty cell or we add just normal space
                    /*if(!isNaN(dataValue)){
                            dataType = 'Number';
                            dataValue = parseFloat(dataValue);
                    }*/ // If we use it, the int numbers become invisible, I don't know why
                    var dataFormula = tables[i].rows[j].cells[k].getAttribute("data-formula");
                    dataFormula = (dataFormula)?dataFormula:(appname=='Calc' && dataType=='DateTime')?dataValue:null;
                    ctx = {  attributeStyleID: ((dataStyle)?' ss:StyleID="'+dataStyle+'"':'') + ((mergeAcross)?' ss:MergeAcross="'+mergeAcross+'"':'')
                        
                        , nameType: (dataType=='Number' || dataType=='DateTime' || dataType=='Boolean' || dataType=='Error')?dataType:'String'
                        , data: (dataFormula)?'':dataValue
                        , attributeFormula: (dataFormula)?' ss:Formula="'+dataFormula+'"':''
                        };
                    rowsXML += format(tmplCellXML, ctx);
                }
                rowsXML += '		</ss:Row>'
                }

                if (wsnames[i].includes('/')) // The Excel Sheet Name cannot contains "Forward Slash" Character
                {
                    wsnames[i] = wsnames[i].split("/").join("-");
                }
                if (wsnames[i].includes('\\')) // The Excel Sheet Name cannot contains "Back Slash" Character
                {
                    wsnames[i] = wsnames[i].split("\\").join("-");
                }

                ctx = {rows: rowsXML, nameWS: wsnames[i] || 'Sheet' + i};
                worksheetsXML += format(tmplWorksheetXML, ctx);
                rowsXML = "";
            }


            ctx = {created: (new Date()).getTime(), worksheets: worksheetsXML};
            workbookXML = format(tmplWorkbookXML, ctx);
            var link = document.createElement("A");
            link.href = uri + base64(workbookXML);
            link.download = 'Workbook.xls';
            link.target = '_blank';
            document.body.appendChild(link);
            link.click();

            // Remove Added Elements
            document.body.removeChild(link);
            document.body.removeChild(dummy);

            
            _notify('success', 'لقد تم تصدير البيانات بنجاح');
        });
    });
</script>