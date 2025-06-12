<div class="examheight100 relative">
    <div id="examfade"></div>
    <div id="exammodal">
        <img id="loader" src="<?php echo base_url() ?>/backend/images/loading_blue.gif" />
    </div>
    <div class="marksEntryupdate">
        <form method="post" action="<?php echo site_url('admin/examgroup/entrymarksupdate') ?>" id="assign_form1112">
            <input type="hidden" id="max_mark" value="<?php //echo $subject_detail->max_marks; 
                                                        ?>">
            <?php
            if (isset($teacherlist) && !empty($teacherlist)) {
                // echo "<pre>";
                // print_r($student_AllSubjects);
            ?>
                <div class="row">

                    <div class="col-md-12">


                        <div class="table-responsive">

                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('teacher'); ?></th>
                                        <th><?php echo $this->lang->line('subject'); ?></th>
                                        <th><?php echo "Status %"; ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (empty($teacherlist)) {
                                    ?>
                                        <tr>
                                            <td colspan="7" class="text-danger text-center"><?php echo $this->lang->line('no_record_found'); ?></td>
                                        </tr>
                                        <?php
                                    } else {
                                        $i = 1;
                                        foreach ($teacherlist as $teachersub) {

                                            // echo "<pre>";
                                            // print_r($subjects);
                                            $no_of_subsubjects = $this->examgroupstudent_model->getClassExamMainSubjectExist($exam_id, $teachersub['subject_id']);
                                            if ($no_of_subsubjects > 0) {
                                                $status  = $this->examgroupstudent_model->getClassExamMainSubjectStatus($class_id, $section_id, $exam_id, $teachersub['subject_id']);
                                                if ($status < 100) {
                                                    $teacher = $this->staff_model->get($teachersub['teacher_id']);
                                                    $subject = $this->subject_model->getSubjectByID($teachersub['subject_id']);

                                                    // $getmarks = $this->examresult_model->getsubjectMarks($subjects->exam_group_class_batch_exams_id, $subjects->id, $subjects->subject_id, $subjects->main_sub, $exam_studentid);
                                                    // print_r($getmarks);
                                        ?>
                                                    <tr class="std_adm_<?php echo $i; ?>" id="">
                                                        <td><?php echo $teacher['name']; ?></td>
                                                        <td><?php echo $subject['name']; ?></td>
                                                        <td><?php echo $status; ?></td>

                                                    </tr>
                                    <?php
                                                }
                                            }
                                            $i++;
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>

                        </div>


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