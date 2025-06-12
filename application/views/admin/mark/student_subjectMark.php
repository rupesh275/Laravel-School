<div class="examheight100 relative">
    <div id="examfade"></div>
    <div id="exammodal">
        <img id="loader" src="<?php echo base_url() ?>/backend/images/loading_blue.gif" />
    </div>
    <div class="marksEntryupdate">
        <form method="post" action="<?php echo site_url('admin/examgroup/entrymarksupdate') ?>" id="assign_form1112">
            
            <?php
            if (isset($subjectList) && !empty($subjectList)) {
                // echo "<pre>";
                // print_r($student_AllSubjects);
            ?>
                <div class="row">

                    <div class="col-md-12">



                        <div class="table-responsive">

                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('subject'); ?></th>
                                        <th><?php echo $this->lang->line('mark'); ?></th>
                                        <th><?php echo $this->lang->line('attendence'); ?></th>
                                        <th><?php echo $this->lang->line('note'); ?></th>
                                        <!--<th><?php echo $this->lang->line('category'); ?></th>
                                        <th><?php echo $this->lang->line('gender'); ?></th>
                                        <th><?php echo $this->lang->line('attendence'); ?></th>
                                        <th><?php echo $this->lang->line('marks') ?></th>
                                        <th><?php echo $this->lang->line('note') ?></th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (empty($subjectList)) {
                                    ?>
                                        <tr>
                                            <td colspan="7" class="text-danger text-center"><?php echo $this->lang->line('no_record_found'); ?></td>
                                        </tr>
                                        <?php
                                    } else {
                                        $i = 1;
                                        foreach ($subjectList as $subjects) {

                                            $getmarks = $this->examresult_model->getsubjectMarks($subjects->exam_group_class_batch_exams_id, $subjects->id, $subjects->subject_id, $subjects->main_sub, $exam_studentid);
                                            // echo "<pre>";
                                            // print_r($getmarks);
                                            $subject_detail = $this->batchsubject_model->getExamSubject($subjects->id);
                                            // print_r($subject_detail);
                                        ?>
                                            <tr class="std_adm_<?php echo $i; ?>" id="">
                                            <input type="hidden" id="max_mark_<?php echo $subjects->id; ?>" value="<?php if(!empty($subject_detail->max_marks)){ echo $subject_detail->max_marks; }; 
                                                        ?>">
                                                <input type="hidden" name="subject_id[]" value="<?php echo $subjects->subject_id;
                                                                                                ?>">
                                                <input type="hidden" name="main_sub" value="<?php echo $subjects->main_sub; ?>">
                                                <input type="hidden" name="exam_group_class_batch_exam_subject_id[]" value="<?php echo $subjects->id; //!empty($getmarks['exam_group_class_batch_exam_subject_id']) ? $getmarks['exam_group_class_batch_exam_subject_id']: ''; 
                                                                                                                            ?>">
                                                <input type="hidden" name="exam_id" value="<?php echo $subjects->exam_group_class_batch_exams_id; ?>">
                                                <input type="hidden" name="exam_group_student_id" value="<?php echo $exam_studentid; ?>">

                                                <td><?php echo $subjects->subject_name; ?></td>
                                                <?php if ($subjects->input_type == "Grade") {
                                                    ?>
                                                    <td><input type="text" name="get_grade_<?php echo $subjects->id; ?>" <?php if(!empty($getmarks) && $getmarks['attendence'] == "absent"){ echo "readonly";}?> class="form-control marksssss" id="<?php echo $subjects->id; ?>" value="<?php echo !empty($getmarks['get_grade']) ? $getmarks['get_grade'] : ''; ?>"></td>
                                                    <?php
                                                } else {
                                                   ?>
                                                <td><input type="text" name="get_marks_<?php echo $subjects->id; ?>" <?php if(!empty($getmarks) && $getmarks['attendence'] == "absent"){ echo "readonly";}?> class="form-control marksssss" id="<?php echo $subjects->id; ?>" value="<?php echo !empty($getmarks['get_marks']) ? $getmarks['get_marks'] : '0'; ?>"></td>
                                                   <?php
                                                }
                                                ?>
                                                <td class="text-center">
                                                    <?php foreach ($attendence_exam as $attendence_key => $attendence_value) {
                                                        if(!empty($getmarks)){
                                                            $chk = ($getmarks['attendence'] == $attendence_value) ? "checked='checked'" : ""; 
                                                            $read = "readonly";
                                                         }else{
                                                            $chk = "";
                                                            $read = "";
                                                         }
                                                         
                                                        ?>
                                                        <label class="checkbox-inline"><input type="checkbox" class="attendance_chk" name="attendance_<?php echo $subjects->id; ?>" value="<?php echo $attendence_value; ?>" <?php echo $read;?> <?php echo $chk; ?>><?php echo $this->lang->line($attendence_value); ?></label>
                                                    <?php
                                                    }
                                                    ?>
                                                </td>
                                                <td><input type="text" name="notes_<?php echo $subjects->id; ?>" class="form-control" id="notes_<?php echo $subjects->id; ?>" value="<?php echo !empty($getmarks) ? $getmarks['note'] : ""; ?>"></td>
                                            </tr>
                                    <?php
                                            $i++;
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>

                        </div>

                        <?php if ($this->rbac->hasPrivilege('exam_marks', 'can_edit')) { ?>
                            <button type="submit" class="allot-fees btn btn-primary btn-sm pull-right" id="load" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Please Wait.."><?php echo $this->lang->line('save'); ?>
                            </button>
                        <?php } ?>

                        <br />
                        <br />

                    </div>
                </div>
            <?php
            } else {
            ?>

                <!-- <div class="alert alert-info">
                                    <?php echo $this->lang->line('no_record_found'); ?>
                                </div> -->
            <?php
            }
            ?>
        </form>
    </div>
</div>