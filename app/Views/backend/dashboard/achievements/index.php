<?php if (!isRole($_SESSION['id'], 'admin'))
{
    header("Location: ".base_url('/dashboard'));
    exit();
} ?>

<div class="row">
  <div class="col-md-12">
    <div class="card">
      <!-- Card Header -->
      <div class="card-header card-header-warning">
        <div class="row">
          <div class="col">
            <h4 class="card-title "><?= $achievements_data['category']['name']; ?></h4>
            <p class="card-category"><?= $achievements_data['category']['description']; ?></p>
          </div>
          <div class="col text-left">
            <button type="button" id="add-achievements-sub-category" class="btn btn-white btn-round btn-just-icon" data-toggle="modal" data-target="#create-update-achievements-sub-category-modal"><i class="material-icons">add</i></button>
            <!--<button type="button" id="create-achievement" class="btn btn-white btn-round btn-just-icon" data-toggle="modal" data-target="#create-update-achievement-modal"><i class="material-icons">add</i></button>-->
          </div>
        </div>
      </div>

        <!-- Card Body -->
        <div class="card-body">
          <?php foreach ($achievements_data['sub_categories'] as $sub_category): ?>
            <div class="row">
              <div class="col-md-12">
                <div class="card">
                  <!-- Card Header -->
                  <div class="card-header card-header-success">
                    <div class="row">
                      <div class="col">
                        <h4 class="card-title "><?= $sub_category['name']; ?></h4>
                        <p class="card-category"><?= $sub_category['description']; ?></p>
                      </div>
                      <div class="col text-left">
                        <button type="button" name="delete" url="<?= base_url('Academic/AchievementsCategoriesController/delete/'.$sub_category['id']) ?>" id="<?= $sub_category['id'] ?>" class="delete btn btn-white btn-round btn-just-icon" data-toggle="modal" data-target="#confirmModal"><i class="material-icons">delete</i></button>
                        <button type="button" name="update" id="<?= $sub_category['id'] ?>" class="update-achievements-sub-category btn btn-white btn-round btn-just-icon" data-toggle="modal" data-target="#create-update-achievements-sub-category-modal"><i class="material-icons">edit</i></button>
                        <button type="button" id="create-achievement" class="btn btn-white btn-round btn-just-icon" cur_sub_category_id="<?= $sub_category['id'] ?>" data-toggle="modal" data-target="#create-update-achievement-modal"><i class="material-icons">add</i></button>
                      </div>
                    </div>
                  </div>

                    <!-- Card Body -->
                    <div class="card-body">
                        <table class="table" id="achievements-dataTable" style="width:100%;">
                            <thead class="text-primary">
                                <tr class="none">
                                    <?php
                                        // Check If Headers Exist or Not
                                        if (empty($achievements_data[$sub_category['id']]['headers']))
                                        {
                                          echo 'Create Headers First';
                                        }
                                        else
                                        {
                                          $headers_ids = array();
                                          echo '<th>#</th>';
                                          foreach ($achievements_data[$sub_category['id']]['headers'] as $header)
                                          {
                                              echo '<th>'.$header['name'].'</th>';
                                              $headers_ids[] = $header['id'];
                                          }
                                          echo '<th>العمليات</th>';
                                        }
                                    ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    if (!isset($achievements_data[$sub_category['id']]['achievements']))
                                    {
                                      // Empty Table
                                    }
                                    else
                                    {
                                      foreach ($achievements_data[$sub_category['id']]['achievements'] as $key => $row) // $key is "row_code"
                                      {
                                          echo '<tr>';
                                            echo '<td></td>';
                                            for ($i =0; $i < count($headers_ids); $i++)
                                            {
                                                foreach ($row as $achievement)
                                                {
                                                    if ($achievement['header_field_id'] == $headers_ids[$i])
                                                    {
                                                        echo '<td>';
                                                            echo $achievement['name'];
                                                        echo '</td>';
                                                    }
                                                }
                                            }
                                            echo '<td>
                                                    <button type="button" name="update" cur_sub_category_id="'.$sub_category['id'].'" row_code="'.$key.'" class="update-achievement btn btn-primary btn-sm btn-just-icon" data-toggle="modal" data-target="#create-update-achievement-modal"><i class="material-icons">edit</i></button>
                                                    &nbsp;&nbsp;&nbsp;
                                                    <button type="button" name="delete" url="'.base_url("Academic/AchievementsController/delete").'/'.$achievement["row_code"].'/'.$achievements_data['category']['id'].'" id="'.$achievement["id"].'" class="delete btn btn-danger btn-sm btn-just-icon" data-toggle="modal" data-target="#confirmModal"><i class="material-icons">delete</i></button>
                                                  </td>';
                                          echo '</tr>';
                                      }
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
    </div>
  </div>
</div>

