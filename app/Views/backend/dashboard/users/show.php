<?php print_r($user); ?>



<?php
    echo '<br><br>------------------<br><br>';
    foreach ($role_achievements as $achievements)
    {
        
        echo '<table>';
        echo '<tr>';
        echo '</tr>';




        echo '</table>';
    }
?>



<?php foreach ($role_achievements as $category_data): ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <!-- Card Header -->
                <div class="card-header card-header-success">
                    <div class="row">
                    <div class="col">
                        <h4 class="card-title ">اسم الفئة</h4>
                        <p class="card-category"> Here is a subtitle for this table</p>
                    </div>
                    <div class="col">
                        <button type="button" id="create-user" class="btn btn-white btn-round btn-just-icon float-left" data-toggle="modal" data-target="#create-update-user-modal"><i class="material-icons">add</i></button>
                    </div>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="card-body">
                    <table class="table" id="users-dataTable">
                        <thead class=" text-primary">
                            <tr>
                                <?php
                                    $headers_ids = array();
                                    foreach ($category_data['headers'] as $header)
                                    {
                                        echo '<th>'.$header['name'].'</th>';
                                        $headers_ids[] = $header['id'];
                                    }
                                ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                foreach ($category_data as $key => $row)
                                {
                                    echo '<tr>';
                                        if ($key == "headers")
                                        {
                                            $headids = $row;
                                        }
                                        else
                                        {
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
                                        }
                                    echo '</tr>';
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>





















<?php /*
<form action="<?= base_url('dashboard/users/store-completed-achievements/'.$user['id']); ?>" method="POST">
    <!-- Role Achievements -->
    <div class="form-group">
        <?php foreach($role_achievements as $achievement): ?>
            <?php if (!$achievement['status']): ?>
                <input type="checkbox" name="role_achievements[]" value="<?php echo $achievement['id']; ?>"/> <?php echo $achievement['name']; ?><br/>
            <?php else: ?>
                <input type="checkbox" name="" value="-1" disabled checked/> <?php echo $achievement['name']; ?><br/>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <input type='submit' name="achievements">

</form>*/?>