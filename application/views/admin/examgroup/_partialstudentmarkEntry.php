<div class="divider"></div>
<div class="row">
    <div class="col-md-12 pull-right">
        
    </div>
</div>
<br>
<?php if ($this->rbac->hasPrivilege('marks_import', 'can_view')) {
?>

    <div class="row">
        <div class="col-md-9">
            <form method="POST" enctype="multipart/form-data" id="fileUploadForm">

                <div class="input-group mb10">
                    <input id="my-file-selector" data-height="34" class="dropify" type="file">
                    <div class="input-group-btn">
                        <input type="submit" class="btn btn-primary" value="<?php echo $this->lang->line('submit') ?>" id="btnSubmit" />
                    </div>
                </div>
        </div>
        </form>

        <form target="_blank" action="<?php echo site_url('admin/examgroup/export_single_subject_marks') ?>" method="post">

            <input type="hidden" name="class_id" value="<?php echo $class_id ?>">
            <input type="hidden" name="section_id" value="<?php echo $section_id; ?>">
            <input type="hidden" name="session_id" value="<?php echo $session_id; ?>">
            <input type="hidden" name="exam_id" value="<?php echo $resultlist[0]['exam_group_class_batch_exams_id']; ?>">
            <input type="hidden" name="subject_id" value="<?php echo $resultlist[0]['subject_id']; ?>">
            <input type="hidden" name="main_sub" value="<?php echo $resultlist[0]['main_sub']; ?>">
            <input type="hidden" name="exam_group_class_batch_exam_subject_id" value="<?php echo $exam_group_class_batch_exam_subject_id; ?>">
            <div class="col-md-3 d-flex justify-content-between">
            <button type="submit" href="javascript:void(0);" style="margin-left: 10px;" class="btn btn-primary w-md d-print-none pull-right" id="btnExport">
                <i class="fa fa-excel"></i> Export
            </button>
                <a class="btn btn-primary pull-right" href="<?php echo site_url('admin/examgroup/exportformat') ?>" target="_blank"><i class="fa fa-download"></i> <?php echo $this->lang->line('export') . " " . $this->lang->line('sample'); ?></a>
            </div>
        </form>
    </div>
<?php
}
?>


<form method="post" action="<?php echo site_url('admin/examgroup/entrymarks') ?>" id="assign_form1111">
    <input type="hidden" id="max_mark" value="<?php echo $subject_detail->max_marks; ?>">
    <?php
    if (isset($resultlist) && !empty($resultlist)) {
        // echo "<pre>";
        // print_r($resultlist);
    ?>
        <div class="row">

            <div class="col-md-12">


                <input type="hidden" name="subject_id" value="<?php echo $resultlist[0]['subject_id']; ?>">
                <input type="hidden" name="main_sub" value="<?php echo $resultlist[0]['main_sub']; ?>">
                <input type="hidden" name="exam_group_class_batch_exam_subject_id" value="<?php echo $exam_group_class_batch_exam_subject_id; ?>">
                <div class="table-responsive">
                    <?php if ($resultlist[0]['input_type'] == "Grade") {
                        $markhead = "Grade";
                    }else {
                        $markhead = $this->lang->line('marks');
                    }?>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th data-b-a-s='thin' data-b-a-s='bold'><?php echo $this->lang->line('admission_no'); ?></th>
                                <th data-b-a-s='thin' data-b-a-s='bold'><?php echo $this->lang->line('roll_no'); ?></th>
                                <th data-b-a-s='thin' data-b-a-s='bold'><?php echo $this->lang->line('student_name'); ?></th>
                                <th data-b-a-s='thin' data-b-a-s='bold'><?php echo $this->lang->line('father_name'); ?></th>
                                <th data-b-a-s='thin' data-b-a-s='bold'><?php echo $this->lang->line('category'); ?></th>
                                <th data-b-a-s='thin' data-b-a-s='bold'><?php echo $this->lang->line('gender'); ?></th>
                                <th data-b-a-s='thin' data-b-a-s='bold'><?php echo $this->lang->line('attendence'); ?></th>
                                <th data-b-a-s='thin' data-b-a-s='bold'><?php echo $markhead?></th>
                                <th data-b-a-s='thin' data-b-a-s='bold'><?php echo $this->lang->line('note') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $disable = "";
                            if (empty($resultlist)) {
                            ?>
                                <tr>
                                    <td colspan="7" class="text-danger text-center"><?php echo $this->lang->line('no_record_found'); ?></td>
                                </tr>
                                <?php
                            } else {
                                $disableBtn = 0;
                                $enablecnt=0;
                                foreach ($resultlist as $student) {

                                    // echo "<pre>";
                                    // print_r($student);

                                    $roll_no = ($student['use_exam_roll_no']) ? $student['exam_roll_no'] : $student['roll_no'];
                                ?>
                                    <tr class="std_adm_<?php echo $student['admission_no']; ?>" id="roll_no_<?php echo $roll_no; ?>">
                                        <input type="hidden" name="exam_id_<?php echo $student['exam_group_class_batch_exam_students_id'] ?>" value="<?php echo $student['exam_group_class_batch_exams_id'] ?>">
                                        <input type="hidden" name="prev_id[<?php echo $student['exam_group_class_batch_exam_students_id'] ?>]" value="<?php echo $student['exam_group_exam_result_id'] ?>">
                                        <input type="hidden" name="exam_group_student_id[]" value="<?php echo $student['exam_group_class_batch_exam_students_id'] ?>">

                                        <td><?php echo $student['admission_no']; ?></td>
                                        <td><?php echo ($roll_no != 0) ? $roll_no : '-'; ?></td>
                                        <td><?php echo $this->customlib->getFullName($student['firstname'], $student['middlename'], $student['lastname'], $sch_setting->middlename, $sch_setting->lastname); ?></td>
                                        <td><?php echo $student['father_name']; ?></td>
                                        <td><?php echo $student['category']; ?></td>
                                        <td><?php echo $student['gender']; ?></td>
                                        <td>
                                            <div>

                                                <?php
                                                foreach ($attendence_exam as $attendence_key => $attendence_value) {
                                                    $chk = ($student['exam_group_exam_result_attendance'] == $attendence_value) ? "checked='checked'" : "";
                                                ?>
                                                    <label class="checkbox-inline"><input type="checkbox" class="attendance_chk" name="exam_group_student_attendance_<?php echo $student['exam_group_class_batch_exam_students_id']; ?>" value="<?php echo $attendence_value; ?>" <?php echo $chk; ?>><?php echo $this->lang->line($attendence_value); ?></label>
                                                <?php
                                                }
                                                ?>

                                            </div>
                                        </td>
                                        <td>
                                            <?php
                                            if ($student['head_lock_status'] == 1) {
                                                $disable = 'disabled';
                                                $disableBtn = 1;
                                            } elseif ($student['admin_lock_status'] == 1) {
                                                if (!($userdata['designation'] == 1 || $userdata['user_type'] == "Super Admin")) {
                                                    $disable = 'disabled';
                                                    $disableBtn = 1;
                                                } else {
                                                    $disable = '';
                                                }
                                            } else {
                                                $disable = '';
                                            }
                                            if($disable==''){
                                                ++$enablecnt;
                                            }
                                            ?>

                                            <?php 
                                            if ($student['input_type'] == "Grade") {
                                               ?>
                                               <input type="text" <?php echo $disable=='disabled' ? 'readOnly' : ''; ?> <?php if(!empty($student) && $student['exam_group_exam_result_attendance'] == "absent") { echo "readonly";}?> class="gradesss form-control" name="get_grade_<?php echo $student['exam_group_class_batch_exam_students_id']; ?>" value="<?php echo $student['exam_group_exam_result_get_grade']; ?>">
                                               <?php
                                            } else {
                                               ?>
                                            <input type="number" <?php echo $disable=='disabled' ? 'readOnly' : ''; ?> <?php if(!empty($student) && $student['exam_group_exam_result_attendance'] == "absent") { echo "readonly";}?> class="marksssss form-control" name="exam_group_student_mark_<?php echo $student['exam_group_class_batch_exam_students_id']; ?>" value="<?php echo $student['exam_group_exam_result_get_marks']; ?>" step="any">
                                               <?php
                                            }
                                            
                                            ?>
                                        </td>

                                        <td>
                                        <select name="exam_group_student_note_<?php echo $student['exam_group_class_batch_exam_students_id']; ?>" class="form-control note" id="exam_group_student_note" style="width: 100px;">
                                        <option value="">Select</option>
                                                       <option <?php if ($student['exam_group_exam_result_note'] == "NA") {
                                                                    echo "selected";
                                                                } ?> value="NA">NA</option>
                                                        <option <?php if ($student['exam_group_exam_result_note'] == "AB") {
                                                                    echo "selected";
                                                                } ?> value="AB">AB</option>
                                                        <option <?php if ($student['exam_group_exam_result_note'] == "LA") {
                                                                    echo "selected";
                                                                } ?> value="LA">LA</option>
                                                        <option <?php if ($student['exam_group_exam_result_note'] == "LEFT") {
                                                                    echo "selected";
                                                                } ?> value="LEFT">LEFT</option>
                                                        <option <?php if ($student['exam_group_exam_result_note'] == "OS") {
                                                                    echo "selected";
                                                                } ?> value="OS">OS</option>
                                                        <option <?php if ($student['exam_group_exam_result_note'] == "SICK") {
                                                                    echo "selected";
                                                                } ?> value="SICK">SICK</option>
                                                        <option <?php if ($student['exam_group_exam_result_note'] == "NADD") {
                                                                    echo "selected";
                                                                } ?> value="NADD">NADD</option>
                                                    </select> 
                                            <!-- <input type="text" class="form-control note" name="exam_group_student_note_<?php echo $student['exam_group_class_batch_exam_students_id']; ?>" value="<?php echo $student['exam_group_exam_result_note']; ?>"> -->
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
                    $rowArray = $this->exam_model->checkexam_type($student['exam_group_class_batch_exams_id']);
                    if ($rowArray['mark_result'] == 1) {
                ?>
                        <button type="submit" class="allot-fees btn btn-primary btn-sm pull-right disabled" id="load" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Please Wait.."><?php echo $this->lang->line('save'); ?>
                        <?php
                    } else {
                        ?>
                            <button type="submit" class="allot-fees btn btn-primary btn-sm pull-right " <?php echo $enablecnt==0 ? 'disabled' : ''; ?> id="load" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Please Wait.."><?php echo $this->lang->line('save'); ?>
                            <?php
                        }
                            ?>
                            </button>
                        <?php } ?>

                        <br />
                        <br />

            </div>
        </div>
    <?php
    } else {
    ?>

        <div class="alert alert-info">
            <?php echo $this->lang->line('no_record_found'); ?>
        </div>
    <?php
    }
    ?>
</form>