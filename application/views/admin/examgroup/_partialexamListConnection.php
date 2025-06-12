<div class="error_connection">

</div>
<form method="POST" action="<?php echo site_url('admin/examgroup/ajaxConnect'); ?>" id="connectExamForm">

    <input type="hidden" name="examgroup_id" value="<?php echo $examgroup_id; ?>">

    <table class="table table-strippedn table-hover mb10">
        <thead>
            <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label><?php echo $this->lang->line('class'); ?><small class="req"> *</small></label>
                    <select autofocus="" id="class_id" name="class_id" class="form-control">
                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                        <?php
                        foreach ($classlist as $class) {
                        ?>
                            <option value="<?php echo $class['id'] ?>" <?php
                                                                        if (set_value('class_id') == $class['id']) {
                                                                            echo "selected=selected";
                                                                        }
                                                                        ?>><?php echo $class['class'] ?></option>
                        <?php
                        }
                        ?>
                    </select>
                    <span class="text-danger"><?php echo form_error('class_id'); ?></span>
                </div>
                <!--./form-group-->
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo $this->lang->line('section'); ?><small class="req"> *</small></label>
                        <select id="section_id" name="section_id" class="form-control">
                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                        </select>
                        <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                    </div>
                </div>
                <!--./form-group-->
            </div>
            </div>
            <tr class="active">

                <th width="20">
                    <input type="checkbox" class="select-all checkbox" id="ckbCheckAll" />
                </th>

                <th><?php echo $this->lang->line('exam'); ?></th>

                <!-- <th><?php echo $this->lang->line('weightage') ?></th> -->

            </tr>
        </thead>

        <tbody>
            <?php
            if (!empty($examList)) {
                foreach ($examList as $exam_key => $exam_value) {
            ?>
                    <tr>
                        <td>

                            <input type="checkbox" class="checkbox checkBoxExam" name="<?php echo "exam[]" ?>" <?php echo ($exam_value->exam_group_exam_connection_id > 0) ? "checked='checked'" : ""; ?> value="<?php echo $exam_value->id ?>" />

                        </td>
                        <td>
                            <?php echo $exam_value->exam; ?>
                        </td>

                        <!-- <td>
                            <input type="number" class="form-control" value="<?php echo $exam_value->exam_weightage; ?>" name="exam_<?php echo $exam_value->id ?>">
                        </td> -->

                    </tr>
                <?php
                }
            } else {
                ?>
                <?php echo $this->lang->line('no_exam_found'); ?>
            <?php
            }
            ?>
        </tbody>
        <thead>
            <tr class="active">

                <th width="20">
                    <!-- <input type="checkbox" class="checkbox" id="mark_result"/> -->
                </th>

                <th>Mark Result</th>

            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($markList)) {
                foreach ($markList as $exam_key => $exam_value) {
            ?>
                    <tr>
                        <td>

                            <input type="checkbox" class="checkbox " name="<?php echo "mark_result[]" ?>" value="<?php echo $exam_value->id ?>" />

                        </td>
                        <td>
                            <?php echo $exam_value->exam; ?>
                        </td>

                        <!-- <td>
                            <input type="number" class="form-control" value="<?php echo $exam_value->exam_weightage; ?>" name="exam_<?php echo $exam_value->id ?>">
                        </td> -->

                    </tr>
                <?php
                }
            } else {
                ?>
                <?php echo $this->lang->line('no_exam_found'); ?>
            <?php
            }
            ?>
        </tbody>
    </table>
    <div class="row">
        <!-- <button type="submit" class="btn btn-danger" id="load" name="reset" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Saving..."><?php echo $this->lang->line('reset') . " " . $this->lang->line('link') . " " . $this->lang->line('exam'); ?></button> -->
        <div class="pull-right">
            <?php
            if ($this->rbac->hasPrivilege('link_exam', 'can_edit')) {
            ?>
                <button type="submit" class="btn btn-primary" id="load" name="save" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Saving...">Process</button>
            <?php
            }
            ?>

        </div>
    </div>
</form>