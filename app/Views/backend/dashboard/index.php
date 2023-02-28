<div class="row">
    <!-- col -->
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats">
            <div class="card-header card-header-warning card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">people_alt</i>
                </div>
                <p class="card-category">الطلاب</p>
                <h3 class="card-title">
                    <?= $cur_students_count; ?>
                    <!--<small>GB</small>-->
                </h3>
            </div>
            <div class="card-footer">
                <div class="stats">
                    <!--<i class="material-icons text-danger">warning</i>
                    <a href="javascript:;">Get More Space...</a>-->
                </div>
            </div>
        </div>
    </div>
</div>

<h3>الصفوف</h3>
<div class="row">
    <!-- col -->
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats">
            <div class="card-header card-header-success card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">done</i>
                </div>
                <p class="card-category">الصفوف النشطة</p>
                <h3 class="card-title"><?= $active_classes_count; ?></h3>
            </div>
            <div class="card-footer">
                <div class="stats">
                    <!--<i class="material-icons">date_range</i> Last 24 Hours-->
                </div>
            </div>
        </div>
    </div>

    <!-- col -->
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats">
            <div class="card-header card-header-warning card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">schedule</i>
                </div>
                <p class="card-category">الصفوف غير النشطة</p>
                <h3 class="card-title"><?= $inactive_classes_count; ?></h3>
            </div>
            <div class="card-footer">
                <div class="stats">
                    <!--<i class="material-icons">date_range</i> Last 24 Hours-->
                </div>
            </div>
        </div>
    </div>

    <!-- col -->
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats">
            <div class="card-header card-header-danger card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">clear</i>
                </div>
                <p class="card-category">الصفوف الملغاة</p>
                <h3 class="card-title"><?= $canceled_classes_count; ?></h3>
            </div>
            <div class="card-footer">
                <div class="stats">
                    <!--<i class="material-icons">date_range</i> Last 24 Hours-->
                </div>
            </div>
        </div>
    </div>

    <!-- col -->
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats">
            <div class="card-header card-header-secondary card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">edit</i>
                </div>
                <p class="card-category">الصفوف المسودة</p>
                <h3 class="card-title"><?= $draft_classes_count; ?></h3>
            </div>
            <div class="card-footer">
                <div class="stats">
                    <!--<i class="material-icons">date_range</i> Last 24 Hours-->
                </div>
            </div>
        </div>
    </div>
</div>