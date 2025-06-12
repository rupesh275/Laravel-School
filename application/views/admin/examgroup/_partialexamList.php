<?php
foreach ($examList as $exam_key => $exam_value) {
?>
    <tr>
        <td>
            <?php echo $exam_value->exam; ?>
        </td>
        <td>
            <?php echo $exam_value->session; ?>
        </td>

        <td>
            <?php echo $exam_value->total_subjects; ?>
        </td>

        <td class="text text-center">
            <?php echo ($exam_value->is_active == 1) ? "<i class='fa fa-check-square-o'></i>" : "<i class='fa fa-exclamation-circle'></i>"; ?>
        </td>
        <td class="text text-center">
            <?php echo ($exam_value->is_publish == 1) ? "<i class='fa fa-check-square-o'></i>" : "<i class='fa fa-exclamation-circle'></i>"; ?>
        </td>
        <td>
            <?php echo $exam_value->description; ?>
        </td>
        <?php /*
        <td class="text text-center">
            
            $studentsCount = $this->examgroup_model->getcountstudents($exam_value->id)->num_rows();
            $result = $this->examgroup_model->getcountstudents($exam_value->id)->result_array();
            $ids = array_column($result, 'id');
            $studentsMark = $this->examgroup_model->getMarkEntry($ids)->num_rows();
            if ($studentsMark > 0) {
                $avg = ($studentsMark * 100) / $studentsCount;
                echo number_format($avg, 2) . ' %';
            } else {
                echo 0;
            }
              
        </td>
        */ ?>
        <td class="text-right">

            <?php 
            if ($userdata['designation'] == 1 || $userdata['role_id'] == 7) {
                
            
                $btn = 'U';
                $title = 'Unlocked';
                $btn_value = 1;
            if ($exam_value->lock_status == 1) {
                $btn = 'L';
                $title = 'Locked';
                $btn_value = 0;
            } 

            ?>
                <button type="button" data-toggle="tooltip" title="<?php echo $title; ?>" class="btn btn-default btn-xs exam_status  status_<?php echo $exam_value->id;?>" id="load" data-status="<?php echo $btn_value;?>" data-exam_id="<?php echo $exam_value->id; ?>"><?php echo $btn;?>
                </button>
                <?php }?>


            <?php
            if ($this->rbac->hasPrivilege('exam_assign_view_student', 'can_view')) {
            ?>
                <button type="button" data-toggle="tooltip" title="<?php echo $this->lang->line('assign / view'); ?>" class="btn btn-default btn-xs assignStudent" id="load" data-examid="<?php echo $exam_value->id; ?>"><i class="fa fa-tag"></i>
                </button>
            <?php
            }
            $disabled = "";
            if ($this->rbac->hasPrivilege('exam_subject', 'can_view')) {
                if ($exam_value->lock_status == 1) {  
                    $disabled = "disabled";
                }
            ?>
                <button class="btn btn-default btn-xs" id="subjectModalButton" <?php echo $disabled;?> data-toggle="tooltip" data-exam_id="<?php echo $exam_value->id; ?>" title="<?php echo $this->lang->line('exam') . " " . $this->lang->line('subjects') ?>"><i class="fa fa-book" aria-hidden="true"></i></button>
            <?php
            }
            if ($this->rbac->hasPrivilege('exam_marks', 'can_view')) {
            ?>
                <a class="btn btn-default btn-xs first_modal" data-mainexam="<?php echo $exam_value->exam; ?>" role="button" data-toggle="modal" data-exam_id="<?php echo $exam_value->id; ?>" title="<?php echo $this->lang->line('exam_marks'); ?>" data-loading-text="<i class='fa fa-spinner fa-spin'></i>"><i class="fa fa-newspaper-o" aria-hidden="true"></i></a>
                <!-- <button type="button" class="btn btn-default btn-xs firstmodel" data-toggle="modal" data-target="#subjectModal" data-recordid="<?php echo $exam_value->id; ?>" ><i class="fa fa-newspaper-o" aria-hidden="true"></i></button> -->
                <!-- <button type="button" class="btn btn-default btn-xs examMarksSubject" id="load" data-toggle="tooltip" data-recordid="<?php echo $exam_value->id; ?>" title="<?php echo $this->lang->line('exam_marks'); ?>" data-loading-text="<i class='fa fa-spinner fa-spin'></i>"><i class="fa fa-newspaper-o"></i></button> -->
            <?php
            }

            if ($this->rbac->hasPrivilege('exam_marks', 'can_view')) {
            ?>
                <button type="button" class="btn btn-default btn-xs examTeacherReamark" <?php echo $disabled;?> id="load" data-toggle="tooltip" data-recordid="<?php echo $exam_value->id; ?>" title="<?php echo $this->lang->line('teacher_remark'); ?>" data-loading-text="<i class='fa fa-spinner fa-spin'></i>"><i class="fa fa-comment"></i></button>
            <?php
            }

            if ($this->rbac->hasPrivilege('exam', 'can_edit')) {
            ?>
                <button class="btn btn-default btn-xs editexamModalButton" <?php echo $disabled;?> data-toggle="tooltip" data-exam_id="<?php echo $exam_value->id; ?>" title="<?php echo $this->lang->line('edit') ?>"><i class="fa fa-pencil" aria-hidden="true"></i></button>
            <?php
            }
            if ($this->rbac->hasPrivilege('exam', 'can_delete')) {
            ?>
                <span data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>">
                    <a href="#" class="btn btn-default btn-xs" <?php echo $disabled;?> data-id="<?php echo $exam_value->id; ?>" data-exam="<?php echo $exam_value->exam; ?>" id="deleteItem" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-remove"></i></a>
                </span>
            <?php
            }
            ?>
        </td>
    </tr>
<?php
}
?>