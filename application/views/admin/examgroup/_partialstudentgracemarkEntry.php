<div class="divider"></div>
<?php if ($this->rbac->hasPrivilege('marks_import', 'can_view')) {
?>
    <!-- <div class="row">
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

        <div class="col-md-3">
            <a class="btn btn-primary pull-right" href="<?php echo site_url('admin/examgroup/exportformat') ?>" target="_blank"><i class="fa fa-download"></i> <?php echo $this->lang->line('export') . " " . $this->lang->line('sample'); ?></a>
        </div>
    </div> -->
<?php
}
?>


<form method="post" action="<?php echo site_url('admin/examgroup/entrymarksgrace') ?>" id="assign_form1111">
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

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th><?php echo $this->lang->line('admission_no'); ?></th>
                                <th><?php echo $this->lang->line('roll_no'); ?></th>
                                <th><?php echo $this->lang->line('student_name'); ?></th>
                                <th><?php echo $this->lang->line('marks') ?></th>
                                <th><?php echo "Grace " . $this->lang->line('marks') ?></th>
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
                                $enablecnt = 0;
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
                                        <td>

                                            <input type="number" readOnly class=" form-control" name="exam_group_student_mark_<?php echo $student['exam_group_class_batch_exam_students_id']; ?>" value="<?php echo $student['exam_group_exam_result_get_marks']; ?>" step="any">
                                        </td>

                                        <td> <input type="number" class="form-control grace" name="exam_group_student_grace_<?php echo $student['exam_group_class_batch_exam_students_id']; ?>" value="<?php echo $student['exam_group_exam_result_grace_mark']; ?>" step="any"></td>

                                    </tr>
                            <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>

                </div>

                <?php if ($this->rbac->hasPrivilege('grace_marks', 'can_edit')) {
                    $permitted = 0;
                    if (($userdata['role_id'] == 1  || $userdata['role_id'] == 7 || $userdata['role_id'] == 8)) {   // roleid->1=>Super Admin || roleid->7=> Admin  
                        $permitted = 1;
                    } elseif ((($userdata['role_id'] == '2' && $class_teacher > 0))) {
                        $permitted = 1;
                    }
                ?>
                    <?php
                    if ($permitted == 0) {
                    } else {


                    ?>
                        <button type="submit" class="allot-fees btn btn-primary btn-sm pull-right "  id="load" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Please Wait.."><?php echo $this->lang->line('save'); ?>
                            
                        </button>
                <?php }
                } ?>

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