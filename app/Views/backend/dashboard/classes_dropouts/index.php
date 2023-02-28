<div class="row">
<div class="col-md-12">
    <div class="card">
    <!-- Card Body -->
    <div class="card-body">
        <form>
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label>من</label>
                        <input type="date" class="form-control" name="from_date" value="<?= (isset($_GET['from_date']) ? $_GET['from_date'] : 0); ?>">
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label>إلى</label>
                        <input type="date" class="form-control" name="to_date" value="<?= (isset($_GET['to_date']) ? $_GET['to_date'] : 0); ?>">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label>النوع</label>
                        <select name="absence_count_type" class="form-control">
                            <option value="number" <?= ((isset($_GET['absence_count_type']) && $_GET['absence_count_type'] == 'number') ? 'selected' : ''); ?>>Number</option>
                            <option value="percentage" <?= ((isset($_GET['absence_count_type']) && $_GET['absence_count_type'] == 'percentage') ? 'selected' : ''); ?>>Precentage (%)</option>
                        </select>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label>أدنى عدد لمرات الغياب / لنسبة الغياب (%)</label>
                        <input type="number" name="absence_value" class="form-control" value="<?= (isset($_GET['absence_value']) ? $_GET['absence_value'] : 0); ?>">
                    </div>
                </div>
            </div>
            <!--<div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>النوع</label>
                        <select name="absence_type" class="form-control">
                            <option value="consecutive">Consecutive</option>
                            <option value="non-consecutive">Non Consecutive</option>
                        </select>
                    </div>
                </div>
            </div>-->

            <button class="btn btn-warning btn-round btn-just-icon float-left"><i class="material-icons">send</i></button>
        </form>
    </div>
    </div>
</div>
</div>

<?php if ((isset($_GET['from_date'])          && $_GET['from_date']          != '') &&
          (isset($_GET['to_date'])            && $_GET['to_date']            != '') &&
          (isset($_GET['absence_count_type']) && $_GET['absence_count_type'] != '') &&
          (isset($_GET['absence_value'])      && $_GET['absence_value']      != '')): ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
            <!-- Card Header -->
            <div class="card-header card-header-success">
                <div class="row">
                <div class="col">
                    <h4 class="card-title "><?= $page_title; ?></h4>
                    <p class="card-category"> Here is a subtitle for this table</p>
                </div>
                <div class="col">
                        
                </div>
                </div>
            </div>

            <!-- Card Body -->
            <div class="card-body">
                <table class="table" id="dropouts-dataTable">
                    <thead class=" text-primary">
                        <tr>
                            <th width="10px">#</th>
                            <th style="min-width:150px;">إسم الطالب</th>
                            <th style="min-width:150px;">إسم الصف</th>
                            <th style="min-width:150px;">إسم المعلم</th>
                            <th style="min-width:150px;">نسبة الغياب</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($classes)): ?>
                            <?php foreach ($classes as $class): ?>
                                <?php foreach ($class['dropouts'] as $dropout): ?>
                                    <tr>
                                        <td></td>
                                        <td><?= (isset($dropout['student_data']['data']['first_name']) ? ($dropout['student_data']['data']['first_name'].' '.$dropout['student_data']['data']['last_name']) : ''); ?></td>
                                        <td><?= $class['class_data']['name']; ?></td>
                                        <td><?= (isset($class['instructor']['first_name']) ? ($class['instructor']['first_name'].' '.$class['instructor']['last_name']) : ''); ?></td>
                                        <td><?= ($dropout['attendance_data']['present'] + $dropout['attendance_data']['late']).'/'.($dropout['attendance_data']['present'] + $dropout['attendance_data']['late'] + $dropout['attendance_data']['absent']);?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            </div>
        </div>
    </div>
<?php endif; ?>