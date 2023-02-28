<?php if (!isRole($_SESSION['id'], 'admin'))
{
    header("Location: ".base_url('/dashboard'));
    exit();
} ?>


<?php echo (session()->getFlashdata('success')) ? session()->getFlashdata('success') : 'nonono'; ?>


<div class="row">
  <div class="col-md-12">
    <div class="card">
      <!-- Card Header -->
      <div class="card-header card-header-success" style="width:100%">
        <div class="row">
          <div class="col">
            <h4 class="card-title ">فئات المهام</h4>
            <p class="card-category"> Here is a subtitle for this table</p>
          </div>
          <div class="col">
            <button type="button" id="create-achievement-category" class="btn btn-white btn-round btn-just-icon float-left" data-toggle="modal" data-target="#create-update-achievement-category-modal"><i class="material-icons">add</i></button>
          </div>
        </div>
      </div>

      <!-- Card Body -->
      <div class="card-body">
        <?php $count = 0; ?>
        <?php $col_per_row = 3 ?>
        <?php foreach($achievementsCategories as $category): ?>
          <?php if ($count % $col_per_row == 0): ?>
            <div class="row">
          <?php endif; ?>
              <!-- col -->
              <div class="col-<?= 12/$col_per_row ?>">
                  <div class="card card-stats">
                      <div class="card-header card-header-<?= $category['color']; ?> card-header-icon" style="cursor: pointer;" onclick="location.href='<?= base_url('Academic/AchievementsController/achievements_list/'.$category['id']) ?>';">
                          <div class="card-icon">
                              <i class="<?= $category['icon']; ?> fa-5x"></i>
                          </div>
                          <p class="card-category"><?= $category['type']; ?></p>
                          <h3 class="card-title">
                              <?= $category['name']; ?>
                              <!--<small>GB</small>-->
                          </h3>
                      </div>
                      <div class="card-footer">
                          <div class="stats">
                              <!--<i class="material-icons text-danger">warning</i>-->
                              <button type="button" name="update" id="<?= $category['id']; ?>" class="update-achievement-category btn btn-primary btn-sm btn-just-icon" data-toggle="modal" data-target="#create-update-achievement-category-modal"><i class="material-icons">edit</i></button>
                              <button type="button" name="delete" url="<?= base_url('Academic/AchievementsCategoriesController/delete/'.$category['id'])?>" id="<?= $category['id'] ?>" class="delete btn btn-danger btn-sm btn-just-icon" data-toggle="modal" data-target="#confirmModal"><i class="material-icons">delete</i></button>
                          </div>
                      </div>
                  </div>
              </div>
          <?php $count++; ?>
          <?php if ($count > 0 && $count % $col_per_row == 0): // Add </div> when row is ended (contains $col_per_row col) // It should be after $count++ so we know that the last col were added ?> 
            </div> <!-- End of row -->
          <?php endif; ?>
        <?php endforeach; ?>
          <?php if ($count > 0 && $count % $col_per_row != 0): // Add </div> when row is ended and does not contain $col_per_row colums ?> 
            </div> <!-- End of row -->
          <?php endif; ?>
      </div>
    </div>
  </div>
</div>

