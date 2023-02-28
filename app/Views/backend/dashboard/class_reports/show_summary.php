<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-body row">
                <!-- Instructor -->
                <div class="col">
                    <div class="input-group">
                        <div class="input-group-btn">
                            <span class="btn btn-outline-warning btn-round btn-just-icon btn-sm text-center"><i class="material-icons">person</i></span>
                        </div>
                        <span class="my-auto"><?= $instructor['first_name'] ." ". $instructor['last_name']; ?></span><br><br>
                    </div>
                </div>
                <!-- Date -->
                <div class="col">
                    <div class="input-group">
                        <div class="input-group-btn">
                            <span class="btn btn-outline-warning btn-round btn-just-icon btn-sm text-center"><i class="material-icons">event</i></span>
                        </div>
                        <?php $class_data['date_name'] = ''; ?>
                        <?php foreach ($dates as $date): ?>
                            <?php if ($date['id'] == $class_data['date_id'])
                            {
                                $class_data['date_name'] = $date['name'];
                            } ?>
                        <?php endforeach; ?>
                        <span class="my-auto"><?= $class_data['date_name']; ?></span><br><br>
                    </div>
                </div>
                <!-- Time -->
                <div class="col">
                    <div class="input-group">
                        <div class="input-group-btn">
                            <span class="btn btn-outline-warning btn-round btn-just-icon btn-sm text-center border"><i class="material-icons">schedule</i></span>
                        </div>
                        <?php
                            // Time
                            $class_data['time_name'] = '';
                            foreach ($times as $time)
                            {
                                if (('time_id_'.$time['id']) == $class_data['time'])
                                {
                                    $class_data['time_name'] = $time['name'];
                                    break;
                                }
                            }
                            if ($class_data['time_name'] == '')
                            {
                                $class_data['time_name'] = $class_data['time'];
                            }
                        ?>

                        <span class="my-auto"><?= $class_data['time_name']; ?></span><br><br>
                    </div>
                </div>
                <!-- Place -->
                <div class="col">
                    <div class="input-group">
                        <div class="input-group-btn">
                            <span class="btn btn-outline-warning btn-round btn-just-icon btn-sm text-center"><i class="material-icons">room</i></span>
                        </div>
                        <?php $class_data['place_name'] = ''; ?>
                        <?php foreach ($places as $place): ?>
                            <?php if ($place['id'] == $class_data['place_id'])
                            {
                                $class_data['place_name'] = $place['name'];
                            } ?>
                        <?php endforeach; ?>
                        <span class="my-auto"><?= $class_data['place_name']; ?></span><br><br>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Attendance -->
<div class="row">
    <div class="col">
        <div class="card">
            <!-- Card Header -->
            <div class="card-header card-header-warning">
                <div class="row">
                <div class="col">
                    <h4 class="card-title ">الحضور والغياب</h4>
                    <!-- <p class="card-category"> Here is a subtitle for this table</p> -->
                </div>
                <div class="col">
                    <button type="button" onClick="copy_attendance();" class="btn btn-white btn-round btn-just-icon float-left"><i class="material-icons">content_copy</i></button>
                    <?php if (isRole($_SESSION['id'], 'admin')): ?>
                        <button type="button" onClick="export_attendance_to_excel();" class="btn btn-white btn-round btn-just-icon float-left"><i class="material-icons">cloud_download</i></button>
                    <?php endif; ?>
                </div>
                </div>
            </div>

            <!-- Card Body -->
            <div class="card-body">
                <table class="table table-hover table-striped" id="students-attendance-table">
                    <thead>
                        <tr>
                            <th style="width: 100%">الإسم</th>
                            <th><span class="btn btn-success btn-round btn-just-icon text-center"><i class="material-icons">check</i></span></th>
                            <th><span class="btn btn-info btn-round btn-just-icon text-center"><i class="material-icons">schedule</i></span></th>
                            <th><span class="btn btn-danger btn-round btn-just-icon text-center"><i class="material-icons">clear</i></span></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $student): ?>
                            <tr>
                                <td>
                                    <?php //$user_index = '-1' ?>
                                    <?php //foreach ($users as $key => $val): ?>
                                        <?php //if ($val['id'] === $student['user_id']): ?>
                                            <?php //$user_index = $key ?>
                                        <?php //endif; ?>
                                    <?php //endforeach; ?>

                                    <?php if (!empty($student['data'])): ?>
                                        <?php if ($attendance_data[$student['user_id']]['is_dropout']): ?>
                                            <span style="color:red;font-weight:700"><i class="material-icons" style="vertical-align: bottom;">fiber_manual_record</i><?php echo $student['data']['first_name'].' '.mb_substr($student['data']['last_name'], 0, 2); ?></span>
                                        <?php else: ?>
                                            <?php echo $student['data']['first_name'].' '.mb_substr($student['data']['last_name'], 0, 2); ?>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        User not found
                                    <?php endif; ?>    
                                </td>
                                
                                <td class="text-center">
                                    <span><?php echo $attendance_data[$student['user_id']]['present']; ?></span>
                                </td>
                                <td class="text-center">
                                    <span><?php echo $attendance_data[$student['user_id']]['late']; ?></span>
                                </td>
                                <td class="text-center">
                                    <span><?php echo $attendance_data[$student['user_id']]['absent']; ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Sections & Lessons -->
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-body">
                <!-- Sections -->
                <h2 for="notes">المقرر</h2>
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <?php foreach ($sections as $section): ?>
                        <li class="nav-item">
                            <a class="nav-link" id="section-<?=$section['id']; ?>-tab" data-toggle="pill" href="#section-<?=$section['id']; ?>" role="tab" aria-controls="section-<?=$section['id']; ?>" aria-selected="false"><?=$section['name']; ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <?php foreach ($sections as $section): ?>
                        <div class="tab-pane fade" id="section-<?=$section['id']; ?>" role="tabpanel" aria-labelledby="section-<?=$section['id']; ?>-tab">
                            <h3><u><?= $section['name']; ?></u></h3>
                            <!-- Lessons -->
                            <?php if (isset($lessons[$section['id']])): ?>
                                <?php foreach($lessons[$section['id']] as $lesson): ?>
                                    <div class="input-group">
                                        <div class="input-group-btn">
                                            <?php if (!$lesson['status']): ?>
                                                <span class="btn btn-danger btn-round btn-just-icon btn-sm text-center"><i class="material-icons">clear</i></span>
                                            <?php else: ?>
                                                <span class="btn btn-success btn-round btn-just-icon btn-sm text-center"><i class="material-icons">check</i></span>
                                            <?php endif; ?>
                                        </div>
                                        <!-- /btn-group -->
                                        <span class="border-0 my-auto"><?php echo $lesson['name']; ?></span><br><br>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            <?php /*if (isset($completed_lessons[$section['id']])): ?>
                                <?php foreach($completed_lessons[$section['id']] as $completed_lesson): ?>
                                    <div class="input-group">
                                        <div class="input-group-btn">
                                            <span class="btn btn-success btn-round btn-just-icon btn-sm text-center"><i class="material-icons">check</i></span>
                                        </div>
                                        <!-- /btn-group -->
                                        <span class="border-0 my-auto"><?php echo $completed_lesson['name']; ?></span><br><br>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; */?>

                            <!-- Section Notes -->
                            <div>
                                <h4>ملاحظات</h4>
                                <?php if (!empty($sections_notes[$section['id']])): ?>
                                    <?php foreach ($sections_notes[$section['id']] as $section): ?>
                                        <ul>
                                            <li><?= $section['notes']; ?></li>
                                        </ul>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p>لا يوجد ملاحظات<p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Reports Notes -->
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-body">
                <h3>ملاحظات أخرى</h3>
                <?php foreach ($reports_data as $report): ?>
                    <?php if ($report['notes'] != ''): ?>
                        <p><?= $report['notes']; ?><p>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>


<!-- Class Not Hold Count & Reasons -->
<div class="row">
    <div class="col">
        <div class="card">
            <!-- Card Header -->
            <div class="card-header card-header-warning">
                <div class="row">
                    <div class="col">
                        <h4 class="card-title ">مرات عدم إنعقاد الصف والأسباب</h4>
                        <!-- <p class="card-category"> Here is a subtitle for this table</p> -->
                    </div>
                    <div class="col">
                    </div>
                </div>
            </div>

            <!-- Card Body -->
            <div class="card-body">
                <?php $did_not_hold_count = 0; ?>
                <?php foreach ($reports_data as $report): ?>
                    <?php if ($report['did_not_hold']): ?>
                        <?php $did_not_hold_count++; ?>
                        <!-- Create Table If There Is Data -->
                        <?php if ($did_not_hold_count == 1): ?>
                            <table class="table">
                            <thead class=" text-primary">
                                <tr>
                                    <th width="10px">#</th>
                                    <th style="min-width:100px;">سبب عدم إنعقاد الحلقة</th>
                                </tr>
                            </thead>
                            <tbody>
                        <?php endif; ?>
                            <tr>
                                <td><?= $did_not_hold_count; ?></td>
                                <td><?= $report['not_hold_reason']; ?></td>
                            </tr>
                    <?php endif; ?>
                <?php endforeach; ?>


                <!-- Close Table -->
                <?php if ($did_not_hold_count > 0): ?>
                        </tbody>
                    </table>
                <?php endif; ?>

                <!-- Class Was Hold Every Time -->
                <?php if ($did_not_hold_count == 0): ?>
                    <p>لا يوجد سجلات حول إلغاء الصف</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>


<!-- Reports List -->
<div class="row">
  <div class="col-md-12">
    <div class="card">
      <!-- Card Header -->
      <div class="card-header card-header-success">
        <div class="row">
          <div class="col">
            <h4 class="card-title ">تقارير الصف</h4>
            <p class="card-category"> Here is a subtitle for this table</p>
          </div>
          <div class="col">
            <!--<button type="button" id="create-report" class="btn btn-white btn-round btn-just-icon float-left" data-toggle="modal" data-target="#create-update-report-modal"><i class="material-icons">add</i></button>-->
          </div>
        </div>
      </div>

      <!-- Card Body -->
      <div class="card-body">
        <table class="table" id="reports-dataTable">
          <thead class=" text-primary">
            <tr>
                <th width="10px">#</th>
                <th style="min-width:100px;">تاريخ التقرير</th>
                <th style="min-width:100px;">تاريخ الصف</th>
                <th style="min-width:100px;">وقت الصف</th>
                <th style="min-width:100px;">مكان الصف</th>
                <th style="min-width:150px;">العمليات</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div>



<script>
    // Copy Attendance Data
    function copy_attendance()
    {
        var students = <?php echo json_encode($students); ?>;
        var attendance_data = <?php echo json_encode($attendance_data); ?>;

        var dummy = document.createElement("textarea");
        var dataToCopy = '';
        students.forEach(function(student) {
            dataToCopy += student['data']['first_name'] + " " + student['data']['last_name'];
            dataToCopy += "\n";
            dataToCopy += "نسبة الحضور: " + (attendance_data[student['user_id']]['present'] + attendance_data[student['user_id']]['late'] + attendance_data[student['user_id']]['absent']) + "/" + (attendance_data[student['user_id']]['present'] + attendance_data[student['user_id']]['late']);
            dataToCopy += "\n\n";
        });

        document.body.appendChild(dummy);
        dummy.value = dataToCopy;
        /* Select the text field */
        dummy.select();
        dummy.setSelectionRange(0, 99999); /*For mobile devices*/

        /* Copy the text inside the text field */
        document.execCommand("copy");

        // Remove Element
        document.body.removeChild(dummy);

        /* Alert the copied text */
        alert("Copied the text:\n" + dummy.value);
    }

    // Export Attendance Data To Excel
    function export_attendance_to_excel(filename = '')
    {
        var downloadLink;
        var dataType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;utf-8;base64';

        // Specify file name
        filename = filename?filename+'.xls':'excel_data.xls';

        // Create download link element
        downloadLink = document.createElement("a");
        document.body.appendChild(downloadLink);



        var dummy = document.createElement("div");
        var dataToExport = '';
        dataToExport += "<table id='attendance-dataTable-to-excel'>"
        dataToExport +=   "<tr>";
        dataToExport +=     "<th>الرمز</th>";
        dataToExport +=     "<th>الإسم</th>";
        dataToExport +=     "<th>نسبة الحضور</th>";
        dataToExport +=     "<th>عدد مرات الحضور</th>";
        dataToExport +=     "<th>عدد مرات التأخر</th>";
        dataToExport +=     "<th>عدد مرات الغياب</th>";
        dataToExport +=   "</tr>";

        var students = <?php echo json_encode($students); ?>;
        var attendance_data = <?php echo json_encode($attendance_data); ?>;
        students.forEach(function(student) {
            dataToExport += "<tr>";
            dataToExport +=   "<th>" + student['data']['id_code'] + "</th>";
            dataToExport +=   "<th>" + student['data']['first_name'] + " " + student['data']['last_name'] + "</th>";
            dataToExport +=   "<th>" + (attendance_data[student['user_id']]['present'] + attendance_data[student['user_id']]['late'] + attendance_data[student['user_id']]['absent']) + "/" + (attendance_data[student['user_id']]['present'] + attendance_data[student['user_id']]['late']) + "</th>";
            dataToExport +=   "<th>" + attendance_data[student['user_id']]['present'] + "</th>";
            dataToExport +=   "<th>" + attendance_data[student['user_id']]['late'] + "</th>";
            dataToExport +=   "<th>" + attendance_data[student['user_id']]['absent'] + "</th>";
            dataToExport += "</tr>";
        });
        dataToExport += "</table>";

        document.body.appendChild(dummy);
        dummy.innerHTML = dataToExport;


        var tableSelect = document.getElementById('attendance-dataTable-to-excel');
        var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');

        if(navigator.msSaveOrOpenBlob){
            var blob = new Blob(['\ufeff', tableHTML], {
                type: dataType
            });
            navigator.msSaveOrOpenBlob( blob, filename);
        }else{
            // Create a link to the file
            downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
        
            // Setting the file name
            downloadLink.download = filename;
            
            //triggering the function
            downloadLink.click();
        }


        // Copy to Excel
        var dummy2 = document.createElement("textarea");

        var dataToCopy = '';
        dataToCopy += "الرمز"
                    + "\t"
                    + "الإسم"
                    + "\t"
                    + "نسبة الحضور"
                    + "\t"
                    + "عدد مرات الحضور"
                    + "\t"
                    + "عدد مرات التأخر"
                    + "\t"
                    + "عدد مرات الغياب";
        dataToCopy += "\n";
        students.forEach(function(student) {
            dataToCopy += student['data']['id_code']
                        + "\t"
                        + student['data']['first_name'] + " " + student['data']['last_name']
                        + "\t"
                        + (attendance_data[student['user_id']]['present'] + attendance_data[student['user_id']]['late'] + attendance_data[student['user_id']]['absent']) + "/" + (attendance_data[student['user_id']]['present'] + attendance_data[student['user_id']]['late'])
                        + "\t"
                        + attendance_data[student['user_id']]['present']
                        + "\t"
                        + attendance_data[student['user_id']]['late']
                        + "\t"
                        + attendance_data[student['user_id']]['absent']
            dataToCopy += "\n";
        });

        document.body.appendChild(dummy2);
        dummy2.value = dataToCopy;
        /* Select the text field */
        dummy2.select();
        dummy2.setSelectionRange(0, 99999); /*For mobile devices*/

        /* Copy the text inside the text field */
        document.execCommand("copy");

        // Remove Element
        document.body.removeChild(dummy2);

        /* Alert the copied text */
        alert("Copied the text:\n" + dummy2.value);
    }
</script>