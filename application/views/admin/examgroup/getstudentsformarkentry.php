<div>
    <form target="_blank" action="<?php echo base_url('admin/examgroup/exportMarks') ?>" method="post">
        <input type="hidden" name="main_sub" value="<?php echo $main_sub; ?>">
        <input type="hidden" name="exam_id" value="<?php echo $exam_id; ?>">
        <input type="hidden" name="class_id" value="<?php echo $class_id; ?>">
        <input type="hidden" name="section_id" value="<?php echo $section_id; ?>">
        <input type="hidden" name="session_id" value="<?php echo $session_id; ?>">
        <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-download"></i></button>
    </form>
</div>
<form method="post" action="<?php echo site_url('admin/examgroup/entrysubjectmarks') ?>" id="assign_form2222">
    <div class="row">
        <input type="hidden" name="main_sub" value="<?php echo $main_sub; ?>">
        <input type="hidden" name="exam_id" value="<?php echo $exam_id; ?>">

        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-striped text-nowrap">
                    <thead>
                        <tr>
                            <th><?php echo "GR.No"; ?></th>
                            <th><?php echo "R.No"; ?></th>
                            <th><?php echo $this->lang->line('student_name'); ?></th>
                            <th><?php echo $this->lang->line('gender'); ?></th>
                            <th> <?php echo $this->lang->line('attendence'); ?></th>
                            <?php if (!empty($subjectList)) {
                                foreach ($subjectList as $key => $subRow) {
                            ?>
                                    <th class="text-center"><?php echo $subRow->subject_name; ?>
                                        <input type="hidden" name="exam_group_class_batch_exam_subject_id[]" value="<?php echo $subRow->id; ?>">
                                        <input type="hidden" name="subject_id[]" value="<?php echo $subRow->subject_id; ?>">
                                    </th>
                            <?php }
                            } ?>
                            <th><?php echo $this->lang->line('action'); 
                                        ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $disable = "";
                        $disableBtn = 0;
                        $enablecnt = 1;
                        if (empty($resultlist)) {
                        ?>
                            <tr>
                                <td colspan="7" class="text-danger text-center"><?php echo $this->lang->line('no_record_found'); ?></td>
                            </tr>
                            <?php
                        } else {
                            $disableBtn = 0;
                            $enablecnt = 0;
                            foreach ($resultlist as $student) {

                                // echo "<pre>";
                                // print_r($student);

                                $roll_no = $student->session_roll_no;
                            ?>
                                <tr class="std_adm_<?php echo $student->admission_no; ?> examstudentId<?php echo $student->exam_group_class_batch_exam_student_id?>" id="roll_no_<?php echo $roll_no; ?>">
                                    <!-- <input type="hidden" name="prev_id[<?php //echo $student->exam_group_class_batch_exam_students_id 
                                                                            ?>]" value="<?php //echo $student->exam_group_exam_result_id 
                                                                                                                                                        ?>"> -->
                                    <input type="hidden" name="exam_group_student_id[]" value="<?php echo $student->exam_group_class_batch_exam_student_id; ?>">

                                    <td><?php echo $student->admission_no; ?></td>
                                    <td class="text-center"><?php echo ($roll_no != 0) ? $roll_no : '-'; ?></td>
                                    <td><?php echo  $student->firstname; ?>
                                        <p><?php echo $student->middlename; ?> <br>
                                            <?php echo $student->lastname; ?></p>
                                    </td>
                                    <td><?php echo $student->gender; ?></td>
                                    <td class="text-center">
                                        <div>

                                            <?php
                                            //foreach ($attendence_exam as $attendence_key => $attendence_value) {
                                            //$chk = ($student->exam_group_exam_result_attendance == $attendence_value) ? "checked='checked'" : "";
                                            ?>
                                            <label class="checkbox-inline">
                                                <input type="checkbox" class="attendance_chk_two" name="exam_group_student_attendance_<?php echo $student->exam_group_class_batch_exam_student_id; ?>" value="<?php echo 'absent'; ?>" <?php //echo $chk; 
                                                                                                                                                                                                                                        ?>><?php echo "Absent"; ?></label>
                                            <?php
                                            //}
                                            ?>

                                        </div>
                                    </td>
                                    <?php
                                    if (!empty($subjectList)) {
                                        foreach ($subjectList as $key => $subRow) {
                                            $marksRow = $this->examgroupstudent_model->getStudentMarksBySubject($student->exam_group_class_batch_exam_student_id, $subRow->id, $subRow->subject_id);
                                            if (!empty($marksRow) && $marksRow->head_lock_status == 1) {
                                                $disable = 'disabled';
                                                $disableBtn = 1;
                                            } elseif (!empty($marksRow) && $marksRow->admin_lock_status == 1) {
                                                if (!($userdata->designation == 1 || $userdata->user_type == "Super Admin")) {
                                                    $disable = 'disabled';
                                                    $disableBtn = 1;
                                                } else {
                                                    $disable = '';
                                                }
                                            } else {
                                                $disable = '';
                                            }
                                    ?>
                                            <td class="success_boxs">
                                                <?php
                                                if ($subRow->input_type == "Grade") {
                                                ?>

                                                    <input type="text" <?php echo $disable == 'disabled' ? 'readOnly' : ''; 
                                                                        ?> <?php if (!empty($marksRow) && $marksRow->attendence == "absent") {
                                                                                echo "readonly";
                                                                            } ?> class="gradesss form-control" name="get_grade[]" value="<?php echo $marksRow->get_grade; ?>">
                                                                            
                                                    <select name="exam_group_student_note[]" class="form-control note" id="exam_group_student_note" style="width: 100px;">
                                                        <option <?php if (!empty($marksRow) && $marksRow->note == "NA") {
                                                                    echo "selected";
                                                                } ?> value="NA">NA</option>
                                                        <option <?php if (!empty($marksRow) && $marksRow->note == "AB") {
                                                                    echo "selected";
                                                                } ?> value="AB">AB</option>
                                                        <option <?php if (!empty($marksRow) && $marksRow->note == "LA") {
                                                                    echo "selected";
                                                                } ?> value="LA">LA</option>
                                                        <option <?php if (!empty($marksRow) && $marksRow->note == "LEFT") {
                                                                    echo "selected";
                                                                } ?> value="LEFT">LEFT</option>
                                                        <option <?php if (!empty($marksRow) && $marksRow->note == "OS") {
                                                                    echo "selected";
                                                                } ?> value="OS">OS</option>
                                                        <option <?php if (!empty($marksRow) && $marksRow->note == "SICK") {
                                                                    echo "selected";
                                                                } ?> value="SICK">SICK</option>
                                                        <option <?php if (!empty($marksRow) && $marksRow->note == "NADD") {
                                                                    echo "selected";
                                                                } ?> value="NADD">NADD</option>
                                                    </select>
                                                <?php
                                                } else {
                                                ?>
                                                    <input type="text" <?php echo $disable == 'disabled' ? 'readOnly' : ''; ?> <?php if (!empty($marksRow) && $marksRow->attendence == "absent") {
                                                                                                                                    echo "readonly";
                                                                                                                                }
                                                                                                                                ?> class="markss form-control" data-max_marks="<?php echo $subRow->max_marks; ?>" data-min_marks="<?php echo $subRow->min_marks; ?>" name="exam_group_student_mark[]" value="<?php if (!empty($marksRow)) {
                                                                                                                                                                                                                echo $marksRow->get_marks;
                                                                                                                                                                                                            } ?>" step="any">
                                                    <br>
                                                    <select name="exam_group_student_note[]" class="form-control note" id="exam_group_student_note" style="width: 100px;">
                                                        <option value="">Select</option>
                                                        <option <?php if (!empty($marksRow) && $marksRow->note == "NA") {
                                                                    echo "selected";
                                                                } ?> value="NA">NA</option>
                                                        <option <?php if (!empty($marksRow) && $marksRow->note == "AB") {
                                                                    echo "selected";
                                                                } ?> value="AB">AB</option>
                                                        <option <?php if (!empty($marksRow) && $marksRow->note == "LA") {
                                                                    echo "selected";
                                                                } ?> value="LA">LA</option>
                                                        <option <?php if (!empty($marksRow) && $marksRow->note == "LEFT") {
                                                                    echo "selected";
                                                                } ?> value="LEFT">LEFT</option>
                                                        <option <?php if (!empty($marksRow) && $marksRow->note == "OS") {
                                                                    echo "selected";
                                                                } ?> value="OS">OS</option>
                                                        <option <?php if (!empty($marksRow) && $marksRow->note == "SICK") {
                                                                    echo "selected";
                                                                } ?> value="SICK">SICK</option>
                                                        <option <?php if (!empty($marksRow) && $marksRow->note == "NADD") {
                                                                    echo "selected";
                                                                } ?> value="NADD">NADD</option>
                                                    </select>
                                                <?php
                                                }

                                                ?>

                                            </td>

                                    <?php }
                                    } ?>
                                    <td>
                                        <button type="button" id="save_marks" data-exam_studentid="<?php echo $student->exam_group_class_batch_exam_student_id; ?>" class="btn btn-primary btn-sm"><?php echo $this->lang->line('save'); ?></button>
                                    </td>
                                </tr>
                        <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>

            </div>

            <?php if ($this->rbac->hasPrivilege('exam_marks', 'can_edit')) {
                $rowArray = $this->exam_model->checkexam_type($exam_id);
                if ($rowArray['mark_result'] = 1) {
            ?>
                    <!-- <button type="submit" class="allot-fees btn btn-primary btn-sm pull-right " id="load" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Please Wait.."><?php echo $this->lang->line('save'); ?> -->
                    <?php
                } else {
                    ?>
                        <!-- <button type="submit" class="allot-fees btn btn-primary btn-sm pull-right " <?php echo $enablecnt == 0 ? 'disabled' : ''; ?> id="load" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Please Wait.."><?php echo $this->lang->line('save'); ?> -->
                        <?php
                    }
                        ?>
                        </button>
                    <?php } ?>

                    <br />
                    <br />

        </div>
    </div>
</form>