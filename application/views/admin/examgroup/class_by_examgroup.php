<div class="row">
    <div class="col-md-12">
        <table class="table table-striped table-bordered table-hover" id="classTable">
            <thead>
                <tr>
                    <th> </th>
                    <th> <?php echo $this->lang->line('class'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($classlist as  $classRow) {
                $result = $this->examgroup_model->getAssignClassByExamgroup($examgroup_id, $classRow['id']);

                ?>
                    <tr>
                        <td>
                            <input type="checkbox" name="class_id[]" <?php if (!empty($result) && $result['class_id'] == $classRow['id']) { echo "checked"; } ?> value="<?php echo $classRow['id']; ?>" id="<?php echo $classRow['class']; ?>">
                        </td>
                        <td>
                            <?php echo $classRow['class']; ?>
                        </td>
                    </tr>
                <?php
                } ?>
            </tbody>
        </table>
    </div>
</div>