<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SNG Central School</title>
    <link href="<?php echo base_url(); ?>uploads/school_content/admin_small_logo/<?php $this->setting_model->getAdminsmalllogo(); ?>" rel="shortcut icon" type="image/x-icon">
    <!-- <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> -->
</head>
<style>
    @import url('<?php echo base_url('backend/dist/fonts/Algerian_Regular.ttf'); ?>');

    @media print {

        /* .achive {
            padding: 100px 20px;
        }  */

    }

    table {
        font-family: arial, sans-serif;
        border-collapse: collapse;
        width: 96%;
        font-size: 10px;
        /* justify-content: center; */
        /* display: flex; */
    }

    .achive {
        padding: 19px 20px 91px;
        border: 1px solid;
        border-color: #0a4279;
        /* border-style: double; */
        border-width: 5px;
    }

    /* .bnone span {
        padding-top: 20px;
        font-size: 13px;
        height: 17px;
    } */

    .plr--40 {
        padding: 0 15%;
    }

    .bnone h2 {
        margin: 0px;
        font-size: 24px;
        font-family: algerian;
        font-style: italic;
        padding-bottom: 5px;
        color: #00008b;
        border-bottom: 3px solid;
        font-weight: bold;
        display: initial;
    }

    .bnone p {
        font-size: 14px;
    }

    .bnone table td {
        border: none;
        /* text-align: center; */
    }

    .bnone table th {
        border: none;
        font-size: 15px;
        font-family: initial;
    }

    .w-50 {
        width: 50%;
        padding: 0 1%;
    }

    .bnone span {
        height: 19px;
        border-bottom: 1px solid #7ba3c6ba;
        width: 100%;
        display: block;
        padding-top: 7px;
        font-size: 14px;
        /* font-weight: 700; */
    }

    td,
    th {
        border: 2px solid #0a4279;
        text-align: left;
        padding: 3px 5px;
    }

    .wt {
        width: 53%;
    }

    .textct {
        text-align: center;
    }

    .detail th {
        font-size: 13px;
        padding-top: 19px;
    }

    .bg {
        background-color: #739cc16e;
    }

    body {
        zoom: 104%;
        /*or whatever percentage you need, play around with this number*/
    }

    .mb-0 {
        margin: 0px;
    }

    .sclnm {
        font-size: 19px;
        font-family: Algerian;
        color: darkblue;
    }

    .smt {
        color: #9c1111;
        font-size: 12px;
        font-weight: 800;
    }

    .add {
        font-size: 15px;
        font-family: initial;
    }

    .heading {
        background-color: #7ba3c6ba;
    }
</style>

<body id="body">


    <?php
    $height = 0;
    $weight = 0;

    foreach ($students_array as $student) {

        $student_session = $this->student_model->getByStudentSessionOnly($student['student_session_id']);

    ?>

        <div class="" style="display:flex;padding:15px 0;">
            <div class="w-50">
                <?php $rw = 0;
                for ($rw = 0; $rw < 2; ++$rw) { ?>

                    <table class="table" style="margin-top:9px">
                        <tbody>
                            <tr>
                                <th colspan='4' style="text-align: center; background-color: #7ba3c6ba; font-size: 14px;"> <?php echo $main_subjects[$rw]->name; ?> </th>
                            </tr>
                            <tr class="bg">
                                <th class="wt"></th>
                                <th style="text-align:center">Evaluation 1</th>
                                <th style="text-align:center">Evaluation 2</th>
                                <th style="text-align:center">Evaluation 3</th>
                            </tr>
                            <?php
                            $subGroups = $main_subjects[$rw]->subGroups;
                            for ($rwg = 0; $rwg < sizeof($subGroups); ++$rwg) {
                                if ($subGroups[$rwg]->id != $main_subjects[$rw]->id) { ?>
                                    <tr class="bg">
                                        <th colspan="4" class="wt"><?php echo $subGroups[$rwg]->name; ?></th>
                                    </tr>
                                <?php }
                                $subSubjects = $subGroups[$rwg]->subSubjects;
                                for ($rws = 0; $rws < sizeof($subSubjects); ++$rws) {
                                    $ev1_result = $this->examresult_model->getFinalMark($student['student_session_id'], $examList[0]->exam_group_class_batch_exam_id, $subSubjects[$rws]->id);
                                    $ev2_result = $this->examresult_model->getFinalMark($student['student_session_id'], $examList[1]->exam_group_class_batch_exam_id, $subSubjects[$rws]->id);
                                    $ev3_result = $this->examresult_model->getFinalMark($student['student_session_id'], $examList[2]->exam_group_class_batch_exam_id, $subSubjects[$rws]->id);

                                ?>
                                    <tr>
                                        <td class="wt">> <?php echo $subSubjects[$rws]->name; ?></td>
                                        <td class="textct"><?php if (!empty($ev1_result)) {
                                                                echo $ev1_result['final_mark'];
                                                            } ?></td>
                                        <td class="textct"><?php if (!empty($ev2_result)) {
                                                                echo $ev2_result['final_mark'];
                                                            } ?></td>
                                        <td class="textct"><?php if (!empty($ev3_result)) {
                                                                echo $ev3_result['final_mark'];
                                                            } ?></td>
                                    </tr>
                            <?php }
                            }
                            ?>
                        </tbody>
                    </table>

                <?php } ?>
            </div>
            <div class="w-50">
                <?php $rw = 0;
                for ($rw = 2; $rw < 7; ++$rw) { ?>
                    <table class="table" style="margin-top:9px">
                        <tbody>
                            <tr>
                                <th colspan='4' style="text-align: center; background-color: #7ba3c6ba; font-size: 14px;"> <?php echo $main_subjects[$rw]->name; ?> </th>
                            </tr>
                            <tr class="bg">
                                <th class="wt"></th>
                                <th style="text-align:center">Evaluation 1</th>
                                <th style="text-align:center">Evaluation 2</th>
                                <th style="text-align:center">Evaluation 3</th>
                            </tr>
                            <?php
                            $subGroups = $main_subjects[$rw]->subGroups;
                            for ($rwg = 0; $rwg < sizeof($subGroups); ++$rwg) {
                                if ($subGroups[$rwg]->id != $main_subjects[$rw]->id) { ?>
                                    <tr class="bg">
                                        <th colspan="4" class="wt"><?php echo $subGroups[$rwg]->name; ?></th>
                                    </tr>
                                <?php }
                                $subSubjects = $subGroups[$rwg]->subSubjects;
                                for ($rws = 0; $rws < sizeof($subSubjects); ++$rws) {
                                    $ev1_result = $this->examresult_model->getFinalMark($student['student_session_id'], $examList[0]->exam_group_class_batch_exam_id, $subSubjects[$rws]->id);
                                    $ev2_result = $this->examresult_model->getFinalMark($student['student_session_id'], $examList[1]->exam_group_class_batch_exam_id, $subSubjects[$rws]->id);
                                    $ev3_result = $this->examresult_model->getFinalMark($student['student_session_id'], $examList[2]->exam_group_class_batch_exam_id, $subSubjects[$rws]->id);
                                ?>
                                    <tr>
                                        <td class="wt">> <?php echo $subSubjects[$rws]->name; ?></td>
                                        <td class="textct"><?php if (!empty($ev1_result)) {
                                                                echo $ev1_result['final_mark'];
                                                            } ?></td>
                                        <td class="textct"><?php if (!empty($ev2_result)) {
                                                                echo $ev2_result['final_mark'];
                                                            } ?></td>
                                        <td class="textct"><?php if (!empty($ev3_result)) {
                                                                echo $ev3_result['final_mark'];
                                                            } ?></td>

                                    </tr>
                            <?php }
                            }
                            ?>
                        </tbody>
                    </table>
                <?php } ?>
            </div>
        </div>

        <div class="" style="display:flex;align-items: center;padding:45px 0">
            <div class="w-50">
                <?php
                $cocurriculam = array("Games", "Art-Craft", "Music");
                $subg = 0;
                $rw = 0;
                for ($rw = 7; $rw < sizeof($main_subjects); ++$rw) {
                    if (in_array($main_subjects[$rw]->name, $cocurriculam)) {
                        if ($subg == 0) { ?>
                            <table class="table" style="margin-top:10px">
                                <tbody>
                                    <tr>
                                        <th colspan='4' style="text-align: center; background-color: #7ba3c6ba; font-size: 14px;"> <?php echo "CO-CURRICULAR ACTIVITIES"; ?> </th>
                                    </tr>
                                    <tr class="bg">
                                        <th class="wt"></th>
                                        <th style="text-align:center">Evaluation 1</th>
                                        <th style="text-align:center">Evaluation 2</th>
                                        <th style="text-align:center">Evaluation 3</th>
                                    </tr>
                                <?php $subg = 1;
                            }
                            $subGroups = $main_subjects[$rw]->subGroups;
                            for ($rwg = 0; $rwg < sizeof($subGroups); ++$rwg) { ?>
                                    <tr class="bg">
                                        <th colspan="4" class="wt"><?php echo $subGroups[$rwg]->name; ?></th>
                                    </tr>
                                    <?php
                                    $subSubjects = $subGroups[$rwg]->subSubjects;
                                    for ($rws = 0; $rws < sizeof($subSubjects); ++$rws) {
                                        $ev1_result = $this->examresult_model->getFinalMark($student['student_session_id'], $examList[0]->exam_group_class_batch_exam_id, $subSubjects[$rws]->id);
                                        $ev2_result = $this->examresult_model->getFinalMark($student['student_session_id'], $examList[1]->exam_group_class_batch_exam_id, $subSubjects[$rws]->id);
                                        $ev3_result = $this->examresult_model->getFinalMark($student['student_session_id'], $examList[2]->exam_group_class_batch_exam_id, $subSubjects[$rws]->id);
                                    ?>
                                        <tr>
                                            <td class="wt">> <?php echo $subSubjects[$rws]->name; ?></td>
                                            <td class="textct"><?php if (!empty($ev1_result)) {
                                                                    echo $ev1_result['final_mark'];
                                                                } ?></td>
                                            <td class="textct"><?php if (!empty($ev2_result)) {
                                                                    echo $ev2_result['final_mark'];
                                                                } ?></td>
                                            <td class="textct"><?php if (!empty($ev3_result)) {
                                                                    echo $ev3_result['final_mark'];
                                                                } ?></td>
                                        </tr>
                                <?php }
                                }
                                ?>
                                <?php
                            } else {
                                if ($subg == 1) { ?>
                                </tbody>
                            </table>
                        <?php $subg = 0;
                                }
                        ?>
                        <table class="table" style="margin-top:5px">
                            <tbody>

                                <?php if ($main_subjects[$rw]->name != "Art Integrated Project") { ?>
                                    <tr>
                                        <th colspan='4' style="text-align: center; background-color: #7ba3c6ba; font-size: 14px;"> <?php echo $main_subjects[$rw]->name; ?> </th>
                                    </tr>
                                    <tr class="bg">
                                        <th class="wt"></th>
                                        <th style="text-align:center">Evaluation 1</th>
                                        <th style="text-align:center">Evaluation 2</th>
                                        <th style="text-align:center">Evaluation 3</th>
                                    </tr>
                                    <?php
                                    $subGroups = $main_subjects[$rw]->subGroups;
                                    for ($rwg = 0; $rwg < sizeof($subGroups); ++$rwg) {
                                        if ($subGroups[$rwg]->id != $main_subjects[$rw]->id) { ?>
                                            <tr class="bg">
                                                <th colspan="4" class="wt"><?php echo $subGroups[$rwg]->name; ?></th>
                                            </tr>
                                        <?php }
                                        $subSubjects = $subGroups[$rwg]->subSubjects;
                                        for ($rws = 0; $rws < sizeof($subSubjects); ++$rws) {
                                            $ev1_result = $this->examresult_model->getFinalMark($student['student_session_id'], $examList[0]->exam_group_class_batch_exam_id, $subSubjects[$rws]->id);
                                            $ev2_result = $this->examresult_model->getFinalMark($student['student_session_id'], $examList[1]->exam_group_class_batch_exam_id, $subSubjects[$rws]->id);
                                            $ev3_result = $this->examresult_model->getFinalMark($student['student_session_id'], $examList[2]->exam_group_class_batch_exam_id, $subSubjects[$rws]->id);

                                        ?>
                                            <tr>
                                                <td class="wt">> <?php echo $subSubjects[$rws]->name; ?></td>
                                                <td class="textct"><?php if (!empty($ev1_result)) {
                                                                        echo $ev1_result['final_mark'];
                                                                    } ?></td>
                                                <td class="textct"><?php if (!empty($ev2_result)) {
                                                                        echo $ev2_result['final_mark'];
                                                                    } ?></td>
                                                <td class="textct"><?php if (!empty($ev3_result)) {
                                                                        echo $ev3_result['final_mark'];
                                                                    } ?></td>
                                            </tr>
                                    <?php }
                                    }
                                } else {
                                    $subGroups = $main_subjects[$rw]->subGroups;
                                    $subSubjects = $subGroups[0]->subSubjects;

                                    $ev1_result = $this->examresult_model->getFinalMark($student['student_session_id'], $examList[2]->exam_group_class_batch_exam_id, $subSubjects[0]->id);

                                    ?>
                                    <tr>
                                        <th class="bg" colspan="2"></th>
                                    </tr>
                                    <tr>
                                        <th style="text-align:left;width: 53%;">><?php echo $main_subjects[$rw]->name; ?></th>
                                        <td class="wt textct"><?php if (!empty($ev1_result)) {
                                                                    echo $ev1_result['final_mark'];
                                                                } ?></td>
                                    <?php } ?>
                            </tbody>
                        </table>

                <?php }
                        } ?>
                   
                <div style="display:flex;justify-content:center;margin-top: 5px;">
                    <table class="table" style="width: 70%;">
                        <tr>
                            <td style="text-align:left;width:60%;">Overall Grade</td>
                            <td style="text-align:center"><?php echo $student_session['grade']; ?></td>
                        </tr>
                        <tr>
                            <td style="text-align:left;width:60%;">Class Teacher's Remarks</td>
                            <td style="text-align:center"><?php echo $student_session['remark']; ?></td>
                        </tr>                        
                    </table>
                </div>

                <div style="display:flex;justify-content:center;    margin-top: 5px;">
                    <table class="table" style="width: 70%;">
                        <tr>
                            <th class="bg" style="width:32%;text-align:center">Grade Name</th>
                            <th class="bg" style="width:30%;text-align:center"> Percent From / Upto</th>
                            <th class="bg" style="width:30%;text-align:center"> Remarks</th>
                        </tr>
                        <tr>
                            <td style="text-align:center">A+</td>
                            <td style="text-align:center">90.00 To 100.00</td>
                            <td style="text-align:center">Outstanding</td>
                        </tr>
                        <tr>
                            <td style="text-align:center">A</td>
                            <td style="text-align:center">75.00 To 89.00</td>
                            <td style="text-align:center">Excellent</td>
                        </tr>
                        <tr>
                            <td style="text-align:center">B</td>
                            <td style="text-align:center">56.00 To 74.00</td>
                            <td style="text-align:center">Very Good</td>
                        </tr>
                        <tr>
                            <td style="text-align:center">C</td>
                            <td style="text-align:center">35.00 To 55.00</td>
                            <td style="text-align:center">Good</td>
                        </tr>
                        <tr>
                            <td style="text-align:center">D</td>
                            <td style="text-align:center">0.00 To 35.00</td>
                            <td style="text-align:center">Scope for improvement</td>
                        </tr>
                    </table>

                </div>

            </div>

            <div class="w-50">

                <table style="width: 100%;">
                    <tr class="heading" style="border: 5px solid #0a4279; border-bottom: 0;">
                        <td style="text-align: center;">
                            <img src="<?php echo base_url('upload/banner/logo.png') ?>" alt="" style="width: 90px;">
                        </td>
                        <td>
                            <div style="text-align:center">
                                <h5 class="mb-0 smt">
                                    SREE NARAYANA GURU MANDIRA SAMITI’S
                                </h5>
                                <h3 class="mb-0 sclnm"> SREE NARAYANA GURU CENTRAL SCHOOL (C.B.S.E.)</h3>
                                <p class="mb-0 add">Sree Narayana Guru Nagar, P.L. Lokhande Marg, Chembur, Mumbai – 400089</p>
                            </div>
                            <div class="" style="display: flex;justify-content: space-between;">
                                <div style="width:70%">
                                    <p class="mb-0 add">Email: sngcentralschool@gmail.com</p>
                                    <p class="mb-0 add">UDISE NO.: 27230300512</p>
                                </div>
                                <div style="width:40%;">
                                    <p class="mb-0 add">Website: sngcentralschool.org</p>
                                    <p class="mb-0 add">Phone No.: 25263113</p>
                                </div>
                            </div>
                        </td>

                    </tr>
                </table>
                <div class="bnone achive">



                    <table class="">

                        <tr>

                            <td>
                                <div class="" style="text-align:center;width:100%;">
                                    <h2>ACHIEVEMENT RECORD</h2>
                                    <h4 style="font-size: 13px;font-family: sans-serif;color: #00008b;font-weight: 700;">Academic year: <b><?php echo $ay_year; ?></b></h4>
                                    <p></p>
                                </div>
                            </td>
                        </tr>
                    </table>
                    <div>
                        <table class="bnone detail">
                            <tr>
                                <th style='width:30%'>Name</th>
                                <td colspan="3"><span style="font-weight: 700;"><?php echo $student['aadhar_name']; ?></span></td>
                            </tr>

                            <tr>
                                <th style='width:30%'>Class & Division</th>
                                <td style='width:20%'><span><?php echo $student['code'] . " - " . $student['section']; ?></span></td>
                                <th style='width:10%;text-align:right'>Roll No.</th>
                                <td><span style="padding-top: 17px;"><?php echo $student['roll_no']; ?></span></td>
                            </tr>
                            <tr>
                                <th style='width:30%'>Mother's/Father's/Guardian's Name:</th>
                                <td colspan="3"><span><?php echo $student['father_name']; ?></span></td>
                            </tr>
                            <tr>
                                <th style='width:30%'>Date Of Birth</th>
                                <td colspan="3"><span><?php echo date('d-m-Y', strtotime($student['dob'])); ?></span></td>
                            </tr>
                            <tr>
                                <th style='width:30%'>GR No.</th>
                                <td colspan="3"><span><?php echo $student['admission_no']; ?></span></td>
                            </tr>
                            <tr>
                                <th style='width:30%'>Height.</th>
                                <td style='width:20%'><span><?php echo $student_session['height'] . " cm"; ?></span></td>
                                <th style='width:15%;text-align:right'>Weight.</th>
                                <td style='width:35%'> <span><?php echo $student_session['weight'] . " kg"; ?></span></td>
                            </tr>
                            <tr>

                            </tr>
                            <tr>
                                <th style='width:30%'>Attendance</th>
                                <?php if ($student_session['total_att'] > 0) { ?>
                                    <td colspan="3"><span><?php echo $student_session['student_att'] . '/' . $student_session['total_att']; ?></span></td>
                                <?php } else { ?>
                                    <td colspan="3"><span><?php echo $student_session['student_att'] . '/' . $working_days['working_days']; ?></span></td>
                                <?php } ?>
                            </tr>
                            <tr>
                                <th style='width:30%'>Telephone No.</th>
                                <td colspan="3"><span><?php echo $student['mobileno']; ?></span></td>
                            </tr>
                            
                            <tr>
                                <th style='width:30%'>Specimen Signature Of Parent/Guardian</th>
                                <td colspan="3"><span></span></td>
                            </tr>
                            <!-- <tr>
                                <th style="width:30%"> General Remarks</th>
                                <td colspan="3" style=""><span><?php echo $student_session['remark']; ?></span></td>
                            </tr> -->
                            <tr>

                                <th colspan="2">
                                    <?php if ($student_session['grade'] == "D") { ?>
                                        <br> Condoned and Promoted to class
                                    <?php } else { ?>
                                        <br> Congratulations !!! Passed & Promoted to class
                                    <?php } ?>
                                </th>
                                <?php if($marksheet_partial != 1){?>
                                <td colspan="2"><span><?php echo $nextclass; ?></span></td>
                                <?php }else{?>
                                <td colspan="2"><span></span></td>
                                <?php }?>
                            </tr>
                            <tr>
                                <th colspan="2">New session begins on </th>
                                <?php if($marksheet_partial == 1){?>
                                    <td colspan="2"><span></span></td>
                                <?php }else{?>
                                <td colspan="2"><span><?php echo date('d-m-Y', strtotime($new_session['start_date'])); ?></span></td>
                                <?php }?>
                            </tr>
                            <tr>
                                <th>Name of the Class Teacher</th>
                                <td style="width:30%;"><span style="font-size: 14px;"><?php echo $class_teacher; ?></span></td>
                                <th>Signature</th>
                                <td style="width:30%"><span></span></td>
                            </tr>
                            <tr>
                                <th></th>
                                <td></td>
                                <th></th>
                                <td></td>
                            </tr>
                            <tr>
                                <th>Name of the Principal</th>
                                <td style="width:30%;"><span style="font-size: 14px;"><?php echo $principal[0]['name'] . " " . $principal[0]['middle_name'] . " " . $principal[0]['surname']; ?></span></td>
                                <th>Signature</th>
                                <td style="width:30%"><span style="font-size: 10px;"></span></td>
                            </tr>
                            <tr>
                                <th></th>
                                <td></td>
                                <th></th>
                                <td></td>
                            </tr>
                            <tr>
                            <tr>
                                <th>Date</th>
                                <td style="width:30%"><span style="font-size: 14px;"><?php echo date('d-m-Y', strtotime($result_date['result_date'])); ?></td>
                                <th>School Seal</th>
                                <td></td>

                                <!-- <td><b style="border-bottom: 1px solid;width:125px;height: 7px;display:block"></b></td> -->
                            </tr>
                            <!-- <td><b style="border-bottom: 1px solid;width:125px;height: 7px;display:block"></b></td> -->
                           
                        </table>
                        <br>
                    </div>
                </div>
            </div>

        </div>




    <?php } ?>

    </div>

</body>

</html>

<script>
    function printDiv(divName, formate) {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;
        var css = '@page { size: ' + formate + '; }',
            head = document.head || document.getElementById('head')[0],
            style = document.createElement('style');

        style.type = 'text/css';
        style.media = 'print';
        if (style.styleSheet) {
            style.styleSheet.cssText = css;
        } else {
            style.appendChild(document.createTextNode(css));
        }

        head.appendChild(style);

        document.body.innerHTML = printContents;

        window.print();

        document.body.innerHTML = originalContents;
        // eval(document.getElementById("runscriptsd").innerHTML);
    }
    // printDiv('body','landscape')
</script>