<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.js"></script>
<style>
    .status{
     margin-left: 5px;   
    }
</style>
<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1>
            <i class="fa fa-map-o"></i> <?php echo $this->lang->line('examinations'); ?> <small><?php echo $this->lang->line('student_fee1'); ?></small>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box removeboxmius">
                    <div class="box-header ptbnull"></div>
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>
                    <div class="box-body">
                        <form role="form" action="<?php echo site_url('admin/verification') ?>" method="post">

                            <?php echo $this->customlib->getCSRF(); ?>

                            <div class="row">
                                <div class="col-sm-4 col-lg-4 col-md-4 ">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('exam') . " " . $this->lang->line('group'); ?></label><small class="req"> *</small>
                                        <select id="exam_group_id" name="exam_group_id" class="form-control select2">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($examgrouplist as $ex_group_key => $ex_group_value) {
                                            ?>
                                                <option value="<?php echo $ex_group_value->id ?>" <?php
                                                                                                    if (set_value('exam_group_id') == $ex_group_value->id) {
                                                                                                        echo "selected=selected";
                                                                                                    }
                                                                                                    ?>><?php echo $ex_group_value->name; ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('exam_group_id'); ?></span>
                                    </div>
                                </div><!--./col-md-3-->
                                <div class="col-sm-4 col-lg-4 col-md-4 ">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('exam') ?></label><small class="req"> *</small>
                                        <select id="exam_id" name="exam_id" class="form-control ">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('exam_id'); ?></span>
                                    </div>
                                </div><!--./col-md-3-->
                                <div class="col-sm-4 col-lg-4 col-md-4 ">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('session'); ?></label><small class="req"> *</small>
                                        <select id="session_id" name="session_id" class="form-control select2">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($sessionlist as $session) {
                                            ?>
                                                <option value="<?php echo $session['id'] ?>" <?php
                                                                                                if (set_value('session_id') == $session['id']) {
                                                                                                    echo "selected=selected";
                                                                                                }
                                                                                                ?>><?php echo $session['session'] ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('session_id'); ?></span>
                                    </div>
                                </div><!--./col-md-3-->
                                <div class="col-sm-4 col-lg-4 col-md-4 ">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('class'); ?></label><small class="req"> *</small>
                                        <select autofocus="" id="class_id" name="class_id" class="form-control select2">
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
                                </div>

                                <div class="col-sm-4 col-lg-4 col-md-4 ">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('section'); ?></label><small class="req"> *</small>
                                        <select id="section_id" name="section_id" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-lg-4 col-md-4 ">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('subject'); ?></label><small class="req"> *</small>
                                        <select id="main_sub" name="main_sub" class="form-control select2">
                                            <?php
                                            foreach ($subjects as $subject) {
                                            ?>
                                                <option value="<?php echo $subject['id']; ?>" <?php
                                                                                                if (set_value('main_sub') == $subject['id']) {
                                                                                                    echo "selected=selected";
                                                                                                }
                                                                                                ?>><?php echo $subject['name']; ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('main_sub'); ?></span>
                                    </div>
                                </div>
                                <?php //echo "<pre>"; print_r($userdata);?>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <button type="submit" name="search" value="search_filter" class="btn btn-primary pull-right btn-sm checkbox-toggle status"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                        <?php if ($userdata['user_type'] == "Super Admin" || $userdata['user_type'] == "Admin" || $userdata['user_type'] == "Webadmin"  ) {
                                           ?>
                                            <button type="button" name="unlock" value="0" data-status="0"  class="btn btn-primary pull-right btn-sm checkbox-toggle btnstatus status"> <?php echo "Unlock"; ?></button>
                                            <button type="button" name="lock" value="1" data-status="1"  class="btn btn-primary pull-right btn-sm checkbox-toggle btnstatus "> <?php echo "Lock"; ?></button>
                                           <?php
                                        }?>
                                       
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <?php
                    if (isset($studentList)) {
                    ?>
                        <div class="box-header ptbnull"></div>
                        <div class="box-header ptbnull">
                            <h3 class="box-title titlefix"><i class="fa fa-users"></i> <?php echo $this->lang->line('student'); ?> <?php echo $this->lang->line('list'); ?></h3>

                        </div>
                        <div class="box-body">
                            <div class="table-responsive no-padding">
                                <div class="download_label"><?php ?> <?php echo $this->lang->line('student'); ?> <?php echo $this->lang->line('list') . "<br>";
                                                                                                                    $this->customlib->get_postmessage();
                                                                                                                    ?></div>
                                <?php
                                if (empty($studentList)) {
                                ?>
                                <?php
                                } else {
                                    // ini_set('display_errors', 1);
                                    // ini_set('display_startup_errors', 1);
                                    // error_reporting(E_ALL);                                    
                                    $count = 1;
                                    $student_list_array = array();
                                    // echo"<pre>";
                                    // print_r($studentList);
                                    foreach ($studentList as $student_key => $student_value) {
                                        
                                        $result_status = 1;
                                        $no_subject_result = 0;
                                        $student_array = array();
                                        $student_array['exam_mark_by_user'] = $student_value->exam_mark_by_user;
                                        $student_array['exam_group_class_batch_exam_student_id'] = $student_value->exam_group_class_batch_exam_student_id;
                                        $student_array['admission_no'] = $student_value->admission_no;
                                        //$student_array['profile_roll_no'] = ($student_value->roll_no != 0) ? $student_value->roll_no : "-";
                                        $student_array['exam_roll_no'] = ($student_value->exam_roll_no != 0) ? $student_value->exam_roll_no : "-";
                                        $student_array['roll_no'] = ($student_value->session_roll_no != 0) ? $student_value->session_roll_no : "-";
                                        $student_array['student_id'] = $student_value->student_id;
                                        $student_array['name'] = $this->customlib->getFullName($student_value->firstname, $student_value->middlename, $student_value->lastname, $sch_setting->middlename, $sch_setting->lastname);
                                        $total_subject = count($subjectList);
                                        $result_total_subject = 0;
                                        // echo "<pre>";
                                        // print_r ($subjectList);
                                        // echo "</pre>";
                                        if (!empty($subjectList)) {
                                            $student_array['subject_added'] = true;
                                            $total_marks = 0;
                                            $get_marks = 0;
                                            $get_percentage = 0;
                                            $total_credit_hour = 0;
                                            $total_quality_point = 0;
                                            $subject_result_list = array();
                                            $subject_status = true;
                                            $result_ids = [];

                                            foreach ($subjectList as $subject_key => $subject_value) {
                                                
                                                $result = getSubjectMarks($student_value->subject_results, $subject_value->subject_id);
                                                $subject_result = array();
                                                $subject_result['result_status'] = false;
                                                if ($result) {
                                                    $result_ids = $result->exam_group_exam_results_id;
                                                    $result_total_subject++;
                                                    $subject_status = false;
                                                    $subject_result['result_status'] = true;
                                                    $no_subject_result = 1;
                                                    $subject_credit_hour = $subject_value->credit_hours;
                                                    $total_credit_hour = $total_credit_hour + $subject_value->credit_hours;
                                                    $subject_result['result_id'] = $result->exam_group_exam_results_id;
                                                    $subject_result['subject_credit_hour'] = $subject_credit_hour;
                                                    if($result->input_type == "Grade")
                                                    {
                                                        $percentage_grade = 0;
                                                        $point = findGradePoints_grade($exam_grades, $result->get_grade);
                                                        $subject_result['get_marks'] = 0;                                                        
                                                        $subject_result['get_grade'] = $result->get_grade;                                                        
                                                        $percentage_grade = 0;
                                                        $subject_result['get_exam_grade'] = $result->get_grade;    
                                                        $get_grade =  $result->get_grade;                                                                                                           
                                                    }
                                                    else
                                                    {
                                                        $total_marks = $total_marks + $subject_value->max_marks;
                                                        $percentage_grade = ($result->get_marks * 100) / $result->max_marks;
                                                        $point = findGradePoints($exam_grades, $percentage_grade);
                                                        $get_marks = $get_marks + $result->get_marks;
                                                        $subject_result['get_marks'] = $result->get_marks;
                                                        $percentage_grade = ($result->get_marks * 100) / $subject_value->max_marks;
                                                        $subject_result['get_exam_grade'] = get_ExamGrade($exam_grades, $percentage_grade);                                                        
                                                        if (($result->get_marks < $subject_value->min_marks) || $result->attendence == "absent") {
                                                            $result_status = 0;
                                                        }                                                        
                                                    }
                                                    $subject_result['point'] = $point;
                                                    $total_quality_point = $total_quality_point + ($point * $subject_credit_hour);
                                                    $subject_result['attendence'] = $result->attendence;
                                                    $subject_result['note'] = $result->note;
                                                    $subject_result['admin_lock_status'] = $result->admin_lock_status;
                                                    $subject_result['head_lock_status'] = $result->head_lock_status;

                                                    
                                                }
                                                $subject_result_list[] = $subject_result;
                                            }
                                            // echo "<pre>";
                                            // print_r($subject_result_list);die();




                                            $student_array['total_subject'] = $total_subject;
                                            $student_array['result_total_subject'] = $result_total_subject;
                                            $student_array['subjet_results'] = $subject_result_list;
                                            $student_array['get_marks'] = $get_marks;
                                            $student_array['total_marks'] = $total_marks;
                                            $student_array['grand_total'] = number_format($get_marks, 2, '.', '') . "/" . number_format($total_marks, 2, '.', '');
                                            if($get_marks > 0 && $total_marks > 0)
                                            {$total_percentage = ($get_marks * 100) / $total_marks;}
                                            else
                                            {$total_percentage = 0;}
                                            $student_array['percentage'] = number_format($total_percentage, 2, '.', '');
                                            if ($total_quality_point > 0 && $total_credit_hour > 0) {
                                                $exam_qulity_point = number_format($total_quality_point / $total_credit_hour, 2, '.', '');
                                            } else {
                                                $exam_qulity_point = number_format(0, 2, '.', '');
                                            }
                                            $student_array['quality_points'] = $total_quality_point . "/" . $total_credit_hour . "=" . $exam_qulity_point;
                                            $student_array['no_subject_result'] = $no_subject_result;
                                            $student_array['exam_qulity_point'] = $exam_qulity_point;

                                            $student_array['result_status'] = $result_status;
                                        } else {
                                            $student_array['subject_added'] = false;
                                        }
                                        $student_list_array[] = $student_array;
                                    }
                                    
                                    
                                    // if ($student_array['subject_added']) {
                                    //     if ($exam_details->exam_group_type != "gpa") {
                                    //         aasort($student_list_array);
                                    //     } else {
                                    //         aasort_gpa($student_list_array);
                                    //     }
                                    // }
                                }

                                ?>
                                <table class="table table-striped table-bordered table-hover " cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th><?php echo "Mark Verify"; ?></th>
                                            <th><?php echo $this->lang->line('roll_no'); ?></th>
                                            <th><?php echo $this->lang->line('student_name'); ?></th>
                                            <?php
                                            // print_r($subjectList);
                                            if (!empty($subjectList)) {
                                                foreach ($subjectList as $subject_key => $subject_value) {
                                            ?>
                                                    <th>
                                                        <?php
                                                        echo $subject_value->subject_name;
                                                        echo "<br/>";
                                                        echo "(" . $subject_value->min_marks . "/" . $subject_value->max_marks . " - " . $subject_value->subject_code . ")";
                                                        if ($exam_details->exam_group_type == "gpa") {
                                                        ?>
                                                            <br />
                                                            (<?php echo $this->lang->line('grade') . " " . $this->lang->line('point'); ?>) * (<?php echo $this->lang->line('credit') . " " . $this->lang->line('hours') ?>)
                                                        <?php
                                                        }
                                                        ?>
                                                    </th>
                                                <?php
                                                }

                                                if ($exam_details->exam_group_type == "school_grade_system" || $exam_details->exam_group_type == "basic_system" || $exam_details->exam_group_type == "coll_grade_system") {
                                                ?>

                                                    <th><?php echo $this->lang->line('grand') . " " . $this->lang->line('total'); ?></th>
                                                    <th><?php echo $this->lang->line('percent') ?> (%)</th>
                                                    <?php
                                                    if ($exam_details->exam_group_type != "gpa") {
                                                    ?>
                                                        <th><?php echo $this->lang->line('result') ?></th>
                                                    <?php
                                                    }
                                                    ?>

                                                <?php
                                                } elseif ($exam_details->exam_group_type == "gpa") {
                                                ?>
                                                    <th><?php echo $this->lang->line('result') ?></th>
                                            <?php
                                                }
                                            }
                                            ?>
                                            <!-- <th><?php echo $this->lang->line('action') ?></th> -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        
                                        if (!empty($student_list_array)) {
                                            $rank_count = 1;

                                            foreach ($student_list_array as $student_list_value) {
                                                $exam_resultArray = $this->examgroup_model->getexam_resultArray($exam_id, $main_sub, $student_list_value['exam_group_class_batch_exam_student_id']);

                                                // echo $exam_id.' || '.$main_sub.' || '.$student_list_value['exam_group_class_batch_exam_student_id'].'=====';
                                                 //echo "<pre>";
                                                 //print_r($exam_resultArray);die();
                                                // print_r($this->db->last_query());die;
                                                // echo 'dd';
                                                // $exam_result_id = array_column($exam_resultArray,'id');                                                
                                        ?>
                                                <tr>
                                                    <td>
                                                        <input type="hidden" name="exam_id" id="exam_id" value="<?php echo $exam_id; ?>">
                                                        <input type="hidden" name="main_sub" id="main_sub" value="<?php echo $main_sub; ?>">
                                                        <input type="hidden" name="student_id" id="student_id" value="<?php echo $student_list_value['exam_group_class_batch_exam_student_id']; ?>">


                                                        <?php $disabled = '';
                                                        $checked = '';
                                                        //  $res=$student_list_value['subjet_results'][0];
                                                         if (!empty($student_list_value['subjet_results'][0])) {
                                                            $res=$student_list_value['subjet_results'][0];
                                                         }else {
                                                            $res=array();
                                                         }
                                                        if (!empty($res['result_status']) && $res['head_lock_status'] == 1) {
                                                            $disabled = "disabled";
                                                            $checked = 'checked';
                                                            
                                                        } else {
                                                            $pos = strpos(strtoupper("PP" . $userdata['user_type']), "TEACHER");
                                                            if ($pos == false) {
                                                                $pos = -1;
                                                            } else {
                                                                $pos = 1;
                                                            }

                                                            if ((!empty($res['result_status']) && $res['admin_lock_status'] == 1) && ((strtoupper($userdata['user_type'])=="SUPER ADMIN") || (strtoupper($userdata['user_type'])=="ADMIN")  || (strtoupper($userdata['user_type'])=="WEBADMIN")  )  )  {
                                                                
                                                                $checked = 'checked';
                                                            }
                                                            if ((!empty($res['result_status']) && $res['admin_lock_status'] == 1) && (strtoupper($userdata['user_type'])=="TEACHER")) {
                                                                $checked = 'checked';
                                                                $disabled = "disabled";    
                                                            }
                                                        }  ?>
                                                        <input type="checkbox" data-resiult_status = "<?php echo $res['result_status'] ; ?>" name="student_check" <?php echo $disabled; ?> id="student_check_<?php echo $student_list_value['exam_group_class_batch_exam_student_id']; ?>" <?php echo $checked; ?> class="student_check" value="<?php echo $student_list_value['exam_group_class_batch_exam_student_id']; ?>">
                                                    </td>
                                                    <td>

                                                        <?php
                                                        if (!empty($res['result_status']) && $res['head_lock_status'] == 1) {
                                                            $markUpdate = "";
                                                        } else {
                                                            $class_teacher = $this->classteacher_model->checkclassteacher($class_id, $section_id, $userdata['id']);
                                                            if (($userdata['user_type'] == 'Super Admin' || $class_teacher > 0 || $userdata['user_type'] == 'Admin' || $userdata['user_type'] == 'Webadmin') && (!empty($res['result_status']) && $res['admin_lock_status'] != 1)) {
                                                                $markUpdate = "marksUpdate";
                                                            } else {
                                                                $markUpdate = "";
                                                            }
                                                        }
                                                        
                                                        ?>
                                                        <a href="javascript:void(0);" class="<?php echo $markUpdate; ?>" data-main_sub="<?php echo $main_sub; ?>" data-studentid="<?php echo $student_list_value['exam_group_class_batch_exam_student_id']; ?>" data-studentname="<?php echo $student_list_value['name']; ?>"><?php echo $student_list_value['roll_no'];//($exam_details->use_exam_roll_no) ? $student_list_value['exam_roll_no'] : $student_list_value['profile_roll_no']; ?></a>
                                                    </td>
                                                    <td>
                                                        <a href="<?php echo base_url(); ?>student/view/<?php echo $student_list_value['student_id']; ?>"><?php echo $student_list_value['name']; ?></a>
                                                    </td>
                                                    <?php
                                                    if ($student_list_value['subject_added']) {
                                                        $ids = array();
                                                        if (!empty($student_list_value)) {
                                                            foreach ($student_list_value['subjet_results'] as $result_key => $result_value) {
                                                                // echo "<pre>";
                                                                // print_r($result_value);
                                                                if (!empty($result_value['result_id']) && $result_value['result_id'] != "") {
                                                                    
                                                                    $ids[] = $result_value['result_id'];
                                                                }


                                                    ?>
                                                                <td>
                                                                    <?php
                                                                    if ($result_value['result_status']) {
                                                                        if ($exam_details->exam_group_type == "gpa") {
                                                                            echo $result_value['point'] . " X " . $result_value['subject_credit_hour'] . " = " . number_format($result_value['point'] * $result_value['subject_credit_hour'], 2, '.', '');
                                                                        } else {

                                                                            echo $result_value['get_marks'] . ($result_value['get_exam_grade'] == "-" ? "" : " (" . $result_value['get_exam_grade'] . ")");
                                                                        }


                                                                        if ($result_value['attendence'] == "absent") {
                                                                    ?>
                                                                            <p class="text">
                                                                                <?php echo $this->lang->line($result_value['attendence']); ?>
                                                                            </p>
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                        <p class="text"><?php echo $result_value['note']; ?></p>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </td>
                                                            <?php
                                                            }
                                                        }

                                                        if ($exam_details->exam_group_type != "gpa") {
                                                            ?>
                                                            <td>
                                                                <?php echo $student_list_value['grand_total']; ?>
                                                            </td>
                                                            <td>
                                                                <?php echo $student_list_value['percentage']; ?>
                                                            </td>
                                                        <?php
                                                        }

                                                        ?>
                                                        <input type="hidden" class="result_ids" name="result_ids_<?php echo $student_list_value['exam_group_class_batch_exam_student_id'] ?>" id="result_ids_<?php echo $student_list_value['exam_group_class_batch_exam_student_id'] ?>" value="<?php echo implode(',', $ids); ?>">
                                                        <td>
                                                            <?php
                                                            //print_r($student_list_value);
                                                            if ($student_list_value['total_subject'] > 0 && $student_list_value['result_total_subject'] >= 1)
                                                                if ($exam_details->exam_group_type == "gpa") {
                                                                    echo $student_list_value['quality_points'];
                                                                } else {
                                                                    if ($student_list_value['result_status']) {
                                                            ?>
                                                                    <label class="label label-success"><?php echo $this->lang->line('pass'); ?><label>
                                                                        <?php
                                                                    } else {
                                                                        ?>
                                                                            <label class="label label-danger"><?php echo $this->lang->line('fail'); ?><label>
                                                                            <?php
                                                                        }
                                                                    }
                                                                            ?>
                                                        </td>
                                                        <!-- <td><button class="btn btn-sm btn-primary marksUpdate" data-main_sub="<?php echo $main_sub; ?>" data-studentid="<?php echo $student_list_value['exam_group_class_batch_exam_student_id']; ?>" data-studentname="<?php echo $student_list_value['name']; ?>">Edit</button></td> -->
                                                    <?php
                                                    }
                                                    //echo "<>";
                                                    //print_r($ids);die();
                                                    ?>
                                                </tr>
                                        <?php
                                                $rank_count++;
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                </div>

            <?php
                    }
            ?>
            </div>
        </div>
    </section>
</div>

<!-- Modal  marks entry-->
<div id="StudentMark" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title subjectmodal_header_1"> </h4>
            </div>
            <div class="modal-body">
                <div class="examheight100 relative">
                    <div id="examfade"></div>
                    <div id="exammodal">
                        <img id="loader" src="<?php echo base_url() ?>/backend/images/loading_blue.gif" />
                    </div>
                    <div class="marksEntryFormOne">

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>



<?php

function getSubjectMarks($subject_results, $subject_id)
{
    if (!empty($subject_results)) {
        foreach ($subject_results as $subject_result_key => $subject_result_value) {
            if ($subject_id == $subject_result_value->subject_id) {
                return $subject_result_value;
            }
        }
    }
    return false;
}

function get_ExamGrade($exam_grades, $percentage)
{
    if (!empty($exam_grades)) {
        foreach ($exam_grades as $exam_grade_key => $exam_grade_value) {

            if ($exam_grade_value->mark_from >= $percentage && $exam_grade_value->mark_upto <= $percentage) {
                return $exam_grade_value->name;
            }
        }
    }

    return "-";
}

function findGradePoints($exam_grades, $percentage)
{

    if (!empty($exam_grades)) {
        foreach ($exam_grades as $exam_grade_key => $exam_grade_value) {

            if ($exam_grade_value->mark_from >= $percentage && $exam_grade_value->mark_upto <= $percentage) {
                return $exam_grade_value->point;
            }
        }
    }

    return 0;
}
function findGradePoints_grade($exam_grades, $grade)
{

    if (!empty($exam_grades)) {
        foreach ($exam_grades as $exam_grade_key => $exam_grade_value) {

            if ($exam_grade_value->name == $grade) {
                return $exam_grade_value->point;
            }
        }
    }
    return 0;
}
function aasort(&$arr)
{
    array_multisort(
        array_column($arr, 'result_status'),
        SORT_DESC,
        array_column($arr, 'percentage'),
        SORT_DESC,
        $arr
    );
}

function aasort_gpa(&$arr)
{
    array_multisort(
        array_column($arr, 'exam_qulity_point'),
        SORT_DESC,
        $arr
    );
}
?>

<script type="text/javascript">
    $(document).ready(function() {
        $('.select2').select2();
        $(".student_check").on("click", function() {
            var id = $(this).val();
            var exam_id = $('#exam_id').val();
            var main_sub = $('#main_sub').val();
            var student_id = id;
            var result_ids = $('#result_ids_' + student_id).val();
            if (id != '') {
                if ($('#student_check_' + id).is(":checked")) {
                    var check = 1;
                } else {
                    var check = 0;
                }
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('admin/verification/update_verified'); ?>",
                    data: {
                        id: id,
                        check: check,
                        exam_id: exam_id,
                        main_sub: main_sub,
                        result_ids: result_ids,
                    },
                    dataType: "json",
                    success: function(data) {
                        if (data.success) {
                            return true;
                        }
                        
                    }
                });
            }
        });
    });
    $(document).ready(function() {
        // $.extend($.fn.dataTable.defaults, {
        //     searching: true,
        //     ordering: true,
        //     paging: false,
        //     retrieve: true,
        //     destroy: true,
        //     info: false,

        // });
        var table = $('.datatable').DataTable({
            "aaSorting": [],
            order: [
                [1, 'asc']
            ],
            rowReorder: {
                selector: 'td:nth-child(2)'
            },
            //responsive: 'false',
            dom: "Bfrtip",
            buttons: [

                {
                    extend: 'copyHtml5',
                    text: '<i class="fa fa-files-o"></i>',
                    titleAttr: 'Copy',
                    title: $('.download_label').html(),
                    exportOptions: {
                        columns: ["thead th:not(.noExport)"]
                    }
                },

                {
                    extend: 'excelHtml5',
                    text: '<i class="fa fa-file-excel-o"></i>',
                    titleAttr: 'Excel',

                    title: $('.download_label').html(),
                    exportOptions: {
                        columns: ["thead th:not(.noExport)"]
                    }
                },

                {
                    extend: 'csvHtml5',
                    text: '<i class="fa fa-file-text-o"></i>',
                    titleAttr: 'CSV',
                    title: $('.download_label').html(),
                    exportOptions: {
                        columns: ["thead th:not(.noExport)"]
                    }
                },

                {
                    extend: 'pdfHtml5',
                    text: '<i class="fa fa-file-pdf-o"></i>',
                    titleAttr: 'PDF',
                    title: $('.download_label').html(),
                    exportOptions: {
                        columns: ["thead th:not(.noExport)"]
                    }
                },

                {
                    extend: 'print',
                    text: '<i class="fa fa-print"></i>',
                    titleAttr: 'Print',
                    title: $('.download_label').html(),
                    customize: function(win) {

                        $(win.document.body).find('th').addClass('display').css('text-align', 'center');
                        $(win.document.body).find('td').addClass('display').css('text-align', 'left');
                        $(win.document.body).find('table').addClass('display').css('font-size', '14px');
                        // $(win.document.body).find('table').addClass('display').css('text-align', 'center');
                        $(win.document.body).find('h1').css('text-align', 'center');
                    },
                    exportOptions: {
                        columns: ["thead th:not(.noExport)"]
                    }
                },

                {
                    extend: 'colvis',
                    text: '<i class="fa fa-columns"></i>',
                    titleAttr: 'Columns',
                    title: $('.download_label').html(),
                    postfixButtons: ['colvisRestore']
                },
            ]
        });
    });

    var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy']) ?>';
    var class_id = '<?php echo set_value('class_id') ?>';
    var section_id = '<?php echo set_value('section_id') ?>';
    var session_id = '<?php echo set_value('session_id') ?>';
    var exam_group_id = '<?php echo set_value('exam_group_id') ?>';
    var exam_id = '<?php echo set_value('exam_id') ?>';
    getSectionByClass(class_id, section_id);

    getExamByExamgroup(exam_group_id, exam_id);
    $(document).on('change', '#exam_group_id', function(e) {
        $('#exam_id').html("");
        var exam_group_id = $(this).val();
        getExamByExamgroup(exam_group_id, 0);
    });

    $(document).on('change', '#class_id', function(e) {
        $('#section_id').html("");
        var class_id = $(this).val();
        getSectionByClass(class_id, 0);
    });

    function getSectionByClass(class_id, section_id) {

        if (class_id !== "") {
            $('#section_id').html("");
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';

            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {
                    'class_id': class_id
                },
                dataType: "json",
                beforeSend: function() {
                    $('#section_id').addClass('dropdownloading');
                },
                success: function(data) {
                    $.each(data, function(i, obj) {
                        var sel = "";
                        if (section_id === obj.section_id) {
                            sel = "selected";
                        }
                        div_data += "<option value=" + obj.section_id + " " + sel + ">" + obj.section + "</option>";
                    });
                    $('#section_id').append(div_data);
                },
                complete: function() {
                    $('#section_id').removeClass('dropdownloading');
                }
            });
        }
    }


    function getExamByExamgroup(exam_group_id, exam_id) {

        if (exam_group_id !== "") {
            $('#exam_id').html("");
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';

            $.ajax({
                type: "POST",
                url: base_url + "admin/examgroup/getExamByExamgroup",
                data: {
                    'exam_group_id': exam_group_id
                },
                dataType: "json",
                beforeSend: function() {
                    $('#exam_id').addClass('dropdownloading');
                },
                success: function(data) {
                    $.each(data, function(i, obj) {
                        var sel = "";
                        if (exam_id === obj.id) {
                            sel = "selected";
                        }
                        div_data += "<option value=" + obj.id + " " + sel + ">" + obj.exam + "</option>";
                    });
                    $('#exam_id').append(div_data);
                },
                complete: function() {
                    $('#exam_id').removeClass('dropdownloading');
                }
            });
        }
    }

    $(document).on('click', '.marksUpdate', function(e) {
        var $this = $(this);
        var studentid = $(this).data('studentid');
        var studentname = $(this).data('studentname');
        var main_sub = $(this).data('main_sub');
        // alert(studentid);
        $(".subjectmodal_header_1").text(studentname);
        $('#StudentMark').modal('show');

        $.ajax({
            type: "POST",
            url: base_url + "admin/examgroup/getstudentmarksSubjects",
            data: {
                'studentid': studentid,
                'main_sub': main_sub,
            },
            dataType: "json",
            beforeSend: function() {

            },
            success: function(data) {
                //    console.log(data);
                $('.marksEntryFormOne').html(data.page);
                $('#StudentMark').modal('show');
                $('#examfade,#exammodal').css({
                    'display': 'none'
                });
                $('.marksEntryFormOne').find('.dropify').dropify();

            },
            error: function(xhr) { // if error occured  
                alert("Error occured.please try again");
                $this.button('reset');
            },
            complete: function() {
                $this.button('reset');
            }
        });
    });
</script>


<script type="text/javascript">
    // $.validator.addMethod("uniqueUserName", function(value, element, options) {
    //     // console.log(value);
    //     // console.log(element);
    //     // console.log(options);
    //     // console.log($(this).prop('id'));
    //     //     var max_mark = $('#max_mark').val();
    //         // var min_mark = '0.00';
    //         // //we need the validation error to appear on the correct element
    //         // if (parseFloat(value) <= parseFloat(max_mark)) {
                
    //         // } else {
                
    //         // }
    //         // return parseFloat(value) <= parseFloat(max_mark);
    //         // return parseFloat(value) < parseFloat(min_mark);
    //     },
    //     "Invalid Marks"
    // );
    $(document).ready(function() {
        
        $(document).on("keydown keyup change",'.marksssss',function() {
            var id = $(this).attr('id');
            var val = parseFloat($(this).val());
            var max_val = parseFloat($("#max_mark_"+id).val());
            if(max_val < val || val < 0){
                $(this).val('');
            } 
        });
        $(document).on('submit', 'form#assign_form1112', function(event) {
            event.preventDefault();
            // $('form#assign_form1112').validate({
            //     debug: true,
            //     errorClass: 'error text text-danger',
            //     validClass: 'success',
            //     errorElement: 'span',
            //     highlight: function(element, errorClass, validClass) {
            //         $(element).parent().addClass(errorClass);
            //     },
            //     unhighlight: function(element, errorClass, validClass) {
            //         $(element).parent().removeClass(errorClass);
            //     }
            // });
            
            // $('.marksssss').each(function() {
            //     $(this).rules("add", {
            //         required: true,
            //         uniqueUserName: true,
            //         messages: {
            //             required: "Required",
            //         }
            //     });
            // });

           

            // test if form is valid
            //  $('form#assign_form1112')
            // if ($('form#assign_form1112').validate().form()) {
            var $this = $('.allot-fees');
            $.ajax({
                type: "POST",
                dataType: 'Json',
                url: $("#assign_form1112").attr('action'),
                data: $("#assign_form1112").serialize(), // serializes the form's elements.
                beforeSend: function() {
                    $this.button('loading');

                },
                success: function(data) {
                    if (data.status == "0") {
                        var message = "";
                        $.each(data.error, function(index, value) {

                            message += value;
                        });
                        errorMsg(message);
                    } else {
                        successMsg(data.message);
                        $('#StudentMark').modal('hide');
                    }
                },
                complete: function() {
                    $this.button('reset');
                    // location.reload();
                }
            });
            // } else {
            //     console.log("does not validate");
            // }

        })


        // // initialize the validator      

    });

    $(document).on('click', '.attendance_chk', function() {
        if ($(this).prop("checked") == true) {
            console.log("Checkbox is checked.");

            $(this).closest('tr').find('.marksssss').val("0");
            $(this).closest('tr').find('.marksssss').prop("readonly", true);

        } else if ($(this).prop("checked") == false) {
            $(this).closest('tr').find('.marksssss').val("");
            $(this).closest('tr').find('.marksssss').prop("readonly", false);
        }
    });

    $(document).on('click', '.btnstatus', function(e) {
        var $this = $(this);
        var status = $(this).data('status');
        // var exam_id = $(this).data('exam_id');
        change_sts(status);
    });

    function change_sts(status){
        var exam_id = [];
        
        var result_id = [];
        $("input[class='result_ids']").each(function(a){
            result_id.push($(this).val());
        });
       
        $.ajax({
            type: "POST",
            url: base_url + "admin/verification/updatelock_status",
            data: {
                'status': status,
                'result_id': result_id,
                
            },
            dataType: "json",
            beforeSend: function() {

            },
            success: function(data) {
            
            },
            error: function(xhr) { // if error occured  
                alert("Error occured.please try again");
                $this.button('reset');
            },
            complete: function() {
                location.reload();
            }
        });
    }
    

</script>