<style type="text/css">
    @media print {

        .no-print,
        .no-print * {
            display: none !important;
        }
    }
</style>
<div class="content-wrapper" style="min-height: 946px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-calendar-check-o"></i> <?php echo $this->lang->line('attendance'); ?> <small> <?php echo $this->lang->line('by_date1'); ?></small>
        </h1>
    </section>
    <section class="content">
        <?php $this->load->view('reports/_examinations'); ?>
        <div class="row">
            <div class="col-md-12">
                <div class="box removeboxmius">
                    <div class="box-header ptbnull"></div>


                    <div class="box-header ptbnull"></div>
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><i class="fa fa-money"></i> <?php echo form_error('student'); ?> <?php echo $this->lang->line('teacher') . "-" . $this->lang->line('report'); ?></h3>
                    </div>
                    <div class="box-body table-responsive">
                        <div class="download_label">Teacher Report</div>


                        <?php
                        $i = 0;
                        if (!empty($teacherlist)) {
                            foreach ($teacherlist as $teacher) {
                                // echo "<pre>";
                                // print_r($teacherlist);

                        ?>
                                <div class="panel-group" id="accordion">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse_<?php echo $i; ?>"><?php echo str_replace("'", "\\'", $teacher['name']);  ?></a>
                                                <?php
                                                $TeacherClassSubjects = $this->teachersubject_model->getTeacherClassSubjects($teacher['id']);
                                                ?>
                                            </h4>
                                        </div>
                                        <div id="collapse_<?php echo $i; ?>" class="panel-collapse collapse">
                                            <div class="row">
                                                <table class="table table-bordered">
                                                    <th>
                                                        <tr>
                                                            <td style="padding-left: 17px;">
                                                                <h5><b>Class</b></h5>
                                                                <!-- <?php print_r($TeacherClassSubjects); ?> -->
                                                            </td>
                                                            <td style="padding-left: 17px;">
                                                                <h5><b>Subjects</b></h5>
                                                            </td>
                                                          
                                                        </tr>

                                                    </th>
                                                    <?php
                                                    if (!empty($TeacherClassSubjects)) {
                                                        foreach ($TeacherClassSubjects as $value) {
                                                            $exams = $this->examgroup_model->getexam($value->class_id, $value->section_id);
                                                            $marksentry = $this->examgroup_model->getsubjectbyvalue($value->subject_id)->num_rows();
                                                            // print_r($value);die;
                                                    ?>
                                                            <tr>
                                                                <td style="padding-left: 17px;"><?php echo $value->class . "(" . $value->section . ")"; ?></td>
                                                                <td style="padding-left: 17px;"><b><?php echo $value->name ?></b>
                                                                    <div class="table-responsive">
                                                                        <table class="table table-curved">
                                                                          
                                                                                <tr>
                                                                                    <th scope="col">
                                                                                       <b> Exam </b>
                                                                                    </th>
                                                                                    <th scope="col">
                                                                                    <b> Percentage </b>
                                                                                    </th>
                                                                                </tr>

                                                                            
                                                                            <?php if (!empty($exams)) {
                                                                                foreach ($exams as $exam) {
                                                                                    $studentsCount = $this->examgroup_model->getcountstudents($exam['exam_group_class_batch_exam_id'])->num_rows();
                                                                                    // print_r($exam);die;
                                                                            ?>

                                                                                    <tr>
                                                                                        <td>
                                                                                            <?php echo $exam['exam']; ?>
                                                                                            <input type="hidden" name="exam_id" value="<?php echo $exam['exam_group_class_batch_exam_id']; ?>">
                                                                                        </td>
                                                                                        <td>
                                                                                            <?php

                                                                                            $result = $this->examgroup_model->getcountstudents($exam['exam_group_class_batch_exam_id'])->result_array();
                                                                                            $ids = array_column($result, 'id');
                                                                                            $studentsMark = $this->examgroup_model->getMarkEntry($ids,$exam['exam_group_class_batch_exam_id'])->num_rows();
                                                                                            if ($studentsMark > 0) {
                                                                                                $avg = ($studentsMark * 100) / $studentsCount;
                                                                                                echo number_format($avg, 2) . ' %';
                                                                                            } else {
                                                                                                echo 0;
                                                                                            }
                                                                                            ?>
                                                                                        </td>
                                                                                    </tr>


                                                                            <?php
                                                                                }
                                                                            } ?>
                                                                        </table>
                                                                    </div>

                                                                </td>
                                                                <td style="padding-left: 17px;"> -

                                                                </td>
                                                            </tr>


                                                    <?php
                                                        }
                                                    } ?>

                                                </table>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                        <?php
                                $i = $i + 1;
                            }
                        }
                        ?>

                    </div>
                </div>

            </div>
        </div>
    </section>
</div>