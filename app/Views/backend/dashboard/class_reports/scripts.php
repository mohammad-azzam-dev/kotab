<script type="text/javascript">
    /* # START: DataTables # */
    $(document).ready(function() {
        var reports_dataTable = $('#reports-dataTable').DataTable({
            "scrollX": true,
            "pageLength" : 10,
            "ajax": {
                url : "<?php echo base_url("Academic/ReportsController/datatable/".(isset($class_id) ? $class_id : '')) ?>",
                type : 'GET'
            },
        });

        reports_dataTable.on( 'order.dt search.dt', function () {
            reports_dataTable.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            } );
        } ).draw();
    });
    /* # END: DataTables # */



    /* # START: Share Class Report # */
        const shareButton = document.querySelector('#share-class-report');

        shareButton.addEventListener('click', event => {
            if (navigator.share)
            {
                var text  = '';
                    text += '*<?= $class_data['name']; ?>*'; // * are for bold on What's App
                    text += '\n';
                    text += '*المكان:* <?= isset($report_data['place_name']) ? $report_data['place_name'] : ''; ?>';
                    text += '\n';
                    text += '*التاريخ:* <?= isset($report_data['class_date']) ? $report_data['class_date'] : ''; ?>';
                    text += '\n';
                    text += '*الوقت:* <?= isset($report_data['class_date']) ? $report_data['time_name'] : ''; ?>';
                    text += '\n';
                    text += '*العريف:* <?= isset($instructor['first_name']) ? ($instructor['first_name'] .' ' .$instructor['last_name']) : ''; ?>';
                    text += '\n';

                    text += '\n';
                    text += '*الحضور والغياب:*';
                    text += '\n';
                    <?php foreach ($students as $student): ?>
                        <?php if (!empty($student['data']) && isset($attendance_data)): ?>
                            <?php $student_name = $student['data']['first_name'].' '.mb_substr($student['data']['last_name'], 0, 2); ?>

                            <?php $has_attendamce_data = false; ?> // If The student is new to class, so it does not have any attendance data for old reports
                            <?php $student_atendance_text = '❔'; ?>
                            <?php if ($attendance_data[$student['user_id']]['present'] == 1): ?>
                                <?php $student_atendance_text = '✅'; ?>
                                <?php $has_attendamce_data = true; ?>
                            <?php elseif ($attendance_data[$student['user_id']]['late'] == 1): ?>
                                <?php $student_atendance_text = '⏱'; ?>
                                <?php $has_attendamce_data = true; ?>
                            <?php elseif ($attendance_data[$student['user_id']]['absent'] == 1): ?>
                                <?php $student_atendance_text = '❌'; ?>
                                <?php $has_attendamce_data = true; ?>
                            <?php endif; ?>  

                            <?php if ($has_attendamce_data): ?>
                                text += '<?= $student_atendance_text ." " .$student_name; ?>'; 
                                text += '\n'; 
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>

                    // Sections Notes
                    text += '\n';
                    text += '*المواد:*';
                    text += '\n';
                    <?php foreach ($sections as $section): ?>
                        <?php if (!empty($sections_notes[$section['id']])): ?>
                            <?php if ($sections_notes[$section['id']][0]['notes'] == ''): ?>
                                // Nothing to display
                            <?php else: ?>
                                text += '- <?= $section['name']; ?>';
                                text += '\n';
                                text += '<?= json_encode($sections_notes[$section['id']][0]['notes'], JSON_UNESCAPED_UNICODE); ?>';
                                text += '\n';
                            <?php endif; ?>
                        <?php else: ?>
                            // Nothing to display
                        <?php endif; ?>
                    <?php endforeach; ?>

                    // Report Notes
                    <?php if (isset($report_data['notes'])): ?>
                        <?php if ($report_data['notes'] == ''): ?>
                            // Nothing to display
                        <?php else: ?>
                            text += '\n';
                            text += '*ملاحظات*';
                            text += '\n';
                            text += '<?= json_encode($report_data['notes'], JSON_UNESCAPED_UNICODE); ?>';
                            text += '\n';
                        <?php endif; ?>
                    <?php endif; ?>

                //alert(text);

                navigator.share({
                    title: 'WebShare API Demo',
                    text: text,
                }).then(() => {
                    console.log('Thanks for sharing!');
                }).catch(console.error);
            }
            else
            {
                alert('Share option is not supported by your browser :(')
            }
        });
    /* # END: Share Class Report # */
</script>


