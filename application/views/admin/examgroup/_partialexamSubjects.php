<div class="row pb10">
    <div class="col-lg-2 col-md-3 col-sm-12">
        <p class="examinfo"><span><?php echo $this->lang->line('exam'); ?></span><?php echo $examgroupDetail->exam; ?></p>
    </div>
    <div class="col-lg-10 col-md-9 col-sm-12">
        <p class="examinfo"><span><?php echo $this->lang->line('exam') . " " . $this->lang->line('group'); ?></span><?php echo $examgroupDetail->exam_group_name; ?></p>
    </div>
</div>
<div class="divider2"></div>
<?php //print_r($examgroupDetail); 
?>
<div class="row">
    <div class="col-md-12 pt5">
        <?php if ($examgroupDetail->mark_result == 1) {
        ?>
            <button type="button" name="add" class="btn btn-primary btn-sm add pull-right disabled" autocomplete="off"><span class="fa fa-plus"></span> <?php echo $this->lang->line('add') . " " . $this->lang->line('exam') . " " . $this->lang->line('subject'); ?></button>
        <?php
        } else {
        ?>

            <button type="button" name="add" class="btn btn-primary btn-sm add pull-right" autocomplete="off"><span class="fa fa-plus"></span> <?php echo $this->lang->line('add') . " " . $this->lang->line('exam') . " " . $this->lang->line('subject'); ?></button>
        <?php
        } ?>
    </div>
</div>
<form action="<?php echo site_url('admin/examgroup/addexamsubject') ?>" method="POST" class="ssaddSubject ptt10 autoscroll">
    <input type="hidden" name="exam_group_class_batch_exam_id" value="<?php echo $exam_id; ?>">
    <div class="">
        <table class="table table-bordered" id="item_table">
            <thead>
                <tr>
                    <th class="tddm150">Main Subject</th>
                    <th class="tddm150"><?php echo $this->lang->line('subject'); ?></th>
                    <th class="tddm150"><?php echo "Input Type"; ?></th>
                    <?php /*
                <th class=""><?php echo $this->lang->line('date'); ?></th>
                <th class=""><?php echo $this->lang->line('time');?></th>
                <th class=""><?php echo $this->lang->line('duration')?></th>
                <th class=""><?php echo $this->lang->line('credit')." ".$this->lang->line('hours') ?></th>
                <th class=""><?php echo $this->lang->line('room')." ".$this->lang->line('no')?></th>
                */ ?>
                    <th class=""><?php echo $this->lang->line('marks') . " (" . $this->lang->line('max') . ".)"; ?></th>
                    <th class=""><?php echo $this->lang->line('marks') . " (" . $this->lang->line('min') . ".)"; ?></th>
                    <th class=""><?php echo "Convert To"; ?></th>
                    <?php
                    if ($examgroupDetail->exam_group_type == "coll_grade_system") {
                    ?>
                        <th class="text-center"><?php echo $this->lang->line('action'); ?></th>
                    <?php
                    }
                    ?>

                </tr>
            </thead>
            <?php
            if (!empty($exam_subjects)) {

                $count = 1;
                $main_id = 0;
                foreach ($exam_subjects as $exam_subject_key => $exam_subject_value) {
            ?>
                    <tr>
                        <td width="300">
                            <select name="main_sub[]" id="parent_sub_<?php echo $count; ?>" data-id="<?php echo $count; ?>" class="form-control parent_sub ">
                                <?php
                                $this->load->model('subject_model');

                                if (!empty($main_subjects)) {
                                    foreach ($main_subjects as $subject_keys => $subject_value) {
                                        $query = $this->subject_model->get_batch($subject_value['id']);
                                        // print_r($query);
                                        if ($exam_subject_value->main_sub == $subject_value['id']) {
                                            $main_id = $subject_value['id'];
                                        }
                                ?>
                                        <option value="<?php echo $subject_value['id'] ?>" <?php echo set_select('main_sub' . $count, $subject_value['id'], ($exam_subject_value->main_sub == $subject_value['id']) ? true : false); ?> data-parent="" data-subparent="" data-chiled="">
                                            <?php
                                            $sub_code = ($subject_value['code'] != "") ? " (" . $subject_value['code'] . ")" : "";
                                            echo $subject_value['name'] . $sub_code; ?>

                                        </option>
                                <?php

                                    }
                                }
                                ?>
                            </select>
                        </td>
                        <td width="300">
                            <select class="form-control item_unit  tddm200" id="subject_<?php echo $count; ?>" name="subject[]">
                                <option value="">Select Sub Subject</option>

                                <?php
                                $subParentId = '';
                                $subParentArr = $this->subject_model->get_batch($main_id);
                                if (!empty($subParentArr)) {
                                    foreach ($subParentArr as $subRow) {
                                        $childArr = $this->subject_model->get_batch($subRow['id']);
                                        if ($exam_subject_value->subject_id == $subRow['id']) {
                                            $subParentId = $subRow['id'];
                                        }
                                ?>
                                        <option value="<?php echo $subRow['id']; ?>" data-subparent="" <?php echo set_select('subject' . $count, $subRow['id'], ($exam_subject_value->subject_id == $subRow['id']) ? true : false); ?>>
                                            <?php $sub_code = ($subRow['code'] != "") ? " (" . $subRow['code'] . ")" : "";
                                            echo $subRow['name'] . $sub_code; ?>
                                        </option>
                                        <?php
                                        if (!empty($childArr)) {
                                            foreach ($childArr as $childRow) { ?>
                                                <option value="<?php echo $childRow['id']; ?>" data-subparent="<?php echo $subRow['id']; ?>" <?php echo set_select('subject' . $count, $childRow['id'], ($exam_subject_value->subject_id == $childRow['id']) ? true : false); ?>>
                                                    <?php $sub_code_ch = ($childRow['code'] != "") ? " (" . $childRow['code'] . ")" : "";
                                                    echo $childRow['name'] . $sub_code_ch; ?>
                                                </option>
                                            <?php } ?>
                                <?php }
                                    }
                                } ?>
                            </select>
                            <input type="hidden" name="subparent[]" id="subparent_<?php echo $count; ?>" value="<?php echo $subParentId; ?>">

                        </td>
                        <td>
                        <select class="form-control item_unit  tddm200" id="input_type_<?php echo $count; ?>" name="input_type[]">
                                <option value="">Select Type</option>
                                <option value="Grade" <?php echo set_select('input_type' . $count, "Grade", ($exam_subject_value->input_type =="Grade") ? true : false); ?>>Grade</option>
                                <option value="Marks" <?php echo set_select('input_type' . $count, "Marks", ($exam_subject_value->input_type =="Marks") ? true : false); ?>>Marks</option>
                        </select>
                        </td>
                        <?php /* ?>
                    <td>
                       
                        <div class="input-group datepicker_init">
                            <input class="form-control tddm200" name="date_from_<?php echo $count; ?>" type="text" value="<?php echo $this->customlib->dateformat($exam_subject_value->date_from); ?>">
                            <span class="input-group-addon" id="basic-addon2">
                                <i class="fa fa-calendar">
                                </i>
                            </span>
                            </input>
                        </div>
                    </td>
                    <td >
                        <div class="input-group datepicker_init_time">
            <input type="text" name="time_from<?php echo $count; ?>" class="form-control tddm200" value="<?php echo $exam_subject_value->time_from; ?>">
                            <span class="input-group-addon" id="basic-addon2">
                                <i class="fa fa-calendar"></i>
                            </span>
                        </div>
                    </td>

<td>
<input type="text" name="duration<?php echo $count; ?>" class="form-control duration tddm200" value="<?php echo $exam_subject_value->duration; ?>" autocomplete="off">
</td>

                    <td>
                        <input class="form-control credit_hours tddm150" name="credit_hours<?php echo $count; ?>" type="text" value="<?php echo $exam_subject_value->credit_hours; ?>"/>
                    </td>
                         <td>
                        <input class="form-control room_no" name="room_no_<?php echo $count; ?>" type="text" value="<?php echo $exam_subject_value->room_no ?>"/>
                    </td>
                    <?php */ ?>
                        <td>
                            <input class="form-control max_marks tddm150" name="max_marks[]" type="number" value="<?php echo $exam_subject_value->max_marks; ?>" />
                        </td>
                        <td>
                            <input name="rows[]" type="hidden" value="<?php echo $count; ?>">

                            <input name="prev_row[<?php echo $count; ?>]" type="hidden" value="<?php echo $exam_subject_value->id; ?>">
                            <input class="form-control min_marks tddm150" name="min_marks[]" type="number" value="<?php echo $exam_subject_value->min_marks; ?>" />

                        </td>
                        <td>
                            <input class="form-control max_marks tddm150" name="convertTo[]" type="number" value="<?php echo $exam_subject_value->convertTo; ?>" />
                        </td>

                        <td class="text-center" style="vertical-align: middle; cursor: pointer;">
                            <?php if ($examgroupDetail->mark_result == 1) {
                            ?>
                                <span class="text text-danger  fa fa-times"></span>
                            <?php
                            } else {
                            ?>

                                <span class="text text-danger remove fa fa-times"></span>
                            <?php
                            }
                            ?>
                        </td>


                    </tr>

            <?php
                    $count++;
                }
            }
            ?>
        </table>
    </div>
    <div class="modal-footer">
        <div class="row">

            <?php
            if ($this->rbac->hasPrivilege('exam_subject', 'can_edit')) {
            ?>
                <button type="submit" class="btn btn-primary pull-right" id="load" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Saving..."><?php echo $this->lang->line('save') ?></button>
            <?php
            }
            ?>

        </div>
    </div>

</form>