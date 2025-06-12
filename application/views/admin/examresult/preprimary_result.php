<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SNG Central School</title>
    <link href="https://tezitservices.com/school/uploads/school_content/admin_small_logo/1.png" rel="shortcut icon" type="image/x-icon">
    <!-- <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> -->
</head>
<style>
    @import url('https://tezitservices.com/school/backend/dist/fonts/Algerian_Regular.ttf');

    @media print {

        /* .achive {
            padding: 100px 20px;
        }  */

        .heading {
            background-color: #b3d694;
        }

        td,
        th {
            padding: 3px 5px;
        }
    }




    .d-flex {
        display: flex;
    }

    body {
        background: #e6edbe;
    }

    table {
        font-family: arial, sans-serif;
        border-collapse: collapse;
        width: 96%;
        font-size: 11px;
        /* justify-content: center; */
        /* display: flex; */
    }

    .achive {
        padding: 33px 25px 50px;
        border: none;
        /* border: 1px solid; */
        /* border-color: #0a4279; */
        /* border-style: double; */
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
        border-width: 5px;
        margin: 42px 95px 0 95px;
    }

    /* .bnone span {
        padding-top: 20px;
        font-size: 13px;
        height: 17px;
    } */
    h3 {
        font-family: cursive;
        color: #0a4279;
        margin-top: 50px;
        background: #9ac86f;
        display: inline;
        border-radius: 6px;
        padding: 2px 4px;
        font-size: 14px;
    }

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
        padding-top: 5px;
    }

    .bnone table th {
        border: none;
        /* font-family: initial; */
        /* text-align: center; */
        padding: 0px;
    }

    .w-50 {
        width: 50%;
        padding: 0 1%;
    }

    .bnone p {
        border-bottom: 1px solid #9ac86f;
    }



    .bnone span {
        height: 16px;
        width: 100%;
        display: block;
        /* padding-top: 7px; */
        font-size: 13px;
        /* font-weight: 700; */
        /* font-family: emoji; */
        font-weight: 700;
        font-family: sans-serif;
        border-bottom: 1px solid #000;
        margin-left: 5px;
        padding-left: 5px
    }

    td,
    th {
        border: 1px solid #050709;
        text-align: left;
        padding: 2px 5px;
        font-size: 12px;
    }

    .wt {
        width: 40%;
    }

    .textct {
        text-align: center;
    }

    .detail th {
        font-size: 14px;
        padding-top: 19px;
    }

    .bg {
        background-color: #b3d694;
    }

    body {
        zoom: 104%;
        /*or whatever percentage you need, play around with this number*/
    }

    .mb-0 {
        margin: 0px;
    }

    .sclnm {
        font-size: 21px;
        font-family: Algerian;
        color: darkblue;
    }

    .smt {
        color: #9c1111;
        font-size: 16px;
        font-weight: 800;
    }

    .add {
        font-size: 15px;
        font-family: initial;
    }

    .Address p {
        /* font-size: 14px; */
        font-weight: 700;
        margin: 0;
        border: none;
        /* font-family: system-ui; */
        font-family: arial, sans-serif;
        width: 23%;
        padding: 15px 5px 0;
    }

    .Address span {
        border-bottom: 1px solid #000;
        padding: 14px 5px 0 0;
        margin-right: 32px;
        /* margin-right: 9px; */
        width: 92%;
        line-height: 26px;
        display: contents;

    }

    .heading {
        background-color: #9ac86f;
    }

    .mtb-0 {
        margin: 0;
        font-family: arial, sans-serif;
        font-size: 12px;
    }

    .evl {
        font-size: 9px;
    }

    .W50BG {
        /* background: url('image/nursery.png'); */
        background: url('<?php echo base_url('uploads/gallery/nursery.png') ?>');
        background-size: 76%;
        background-repeat: no-repeat;
        background-position: center;
    }

    .FBG {
        background: url('<?php echo base_url('uploads/gallery/nursery2.png') ?>');
        background-size: 100%;
        background-repeat: no-repeat;
        background-position: bottom center;
    }

    .W50BG2 {
        /* background: url('image/nursery2.png'); */
        background: #fbedd0de;
        background-size: 76%;
        background-repeat: no-repeat;
        background-position: bottom center;
    }

    @media print {
        .PageBrack {
            page-break-before: always;
            padding-top: 68px;
        }

        /* page-break-after works, as well */
    }

    h3 {
        color: #0a4279;
        margin-top: 50px;
    }

    .frontpage {
        height: 120px;
    }

    .frontpage th {
        font-size: 10px;
        font-weight: 700;
    }

    .text-right {
        text-align: right;
    }

   

    @media screen and (max-width:1366px) {


        .achive {
            margin: 34px 37px 0 63px;
        }

        td,
        th {
            font-size: 10px;
        }

        .sclnm {
            font-size: 15px;
        }

        .add {
            width: 73%;
            font-size: 10px;
        }
    }
</style>

<body id="body">
    <?php
    $height = 0;
    $weight = 0;
    foreach ($students_array as $student) {
        $student_session = $this->student_model->getByStudentSessionOnly($student['student_session_id']);
        $remarks = $this->examgroupstudent_model->getRemarksofstudent($student['student_session_id']);
    ?>
        <div class="PageBrack" style="display:flex;padding-bottom:10px;">
            <div class="w-50 W50BG">
                <?php $rw = 0;
                for ($rw = 0; $rw < 2; ++$rw) {
                    $total_mark1 = 0;
                    $total_mark2 = 0;
                    $total_mark3 = 0;
                    $total_mark4 = 0;
                    $total_mark5 = 0;
                    $total_mark6 = 0;
                    $max_mark1 = 0;
                    $max_mark2 = 0;
                    $max_mark3 = 0;
                    $max_mark4 = 0;
                    $max_mark5 = 0;
                    $max_mark6 = 0;
                    $mark1_status = 0;
                    $mark2_status = 0;
                    $mark3_status = 0;
                    $mark4_status = 0;
                    $mark5_status = 0;
                    $mark6_status = 0;
                ?>
                    <table class="table" style="margin-top:9px">
                        <tbody>
                            <?php if ($rw == 0) { ?>
                                <tr>
                                    <th colspan='7' style="text-align:center;background-color: #9ac86f;font-size: 14px;"> A.Language Development</th>
                                </tr>
                            <?php } ?>
                            <tr class="bg">
                                <th class="wt"><?php echo $main_subjects[$rw]->name; ?></th>
                                <th class="evl" style="text-align:center">Evaluation 1</th>
                                <th class="evl" style="text-align:center">Evaluation 2</th>
                                <th class="evl" style="text-align:center">Evaluation 3</th>
                                <th class="evl" style="text-align:center">Evaluation 4</th>
                                <th class="evl" style="text-align:center">Evaluation 5</th>
                                <th class="evl" style="text-align:center">Evaluation 6</th>
                            </tr>
                            <?php
                            $subGroups = $main_subjects[$rw]->subGroups;
                            for ($rwg = 0; $rwg < sizeof($subGroups); ++$rwg) {

                                if ($subGroups[$rwg]->id != $main_subjects[$rw]->id) { ?>
                                    <tr class="bg">
                                        <th colspan="7" class="wt"><?php echo $subGroups[$rwg]->name; ?></th>
                                    </tr>
                                <?php }

                                $subSubjects = $subGroups[$rwg]->subSubjects;
                                for ($rws = 0; $rws < sizeof($subSubjects); ++$rws) {
                                    $ev1_result = $this->examresult_model->getFinalMark($student['student_session_id'], $examList[0]->exam_group_class_batch_exam_id, $subSubjects[$rws]->id);
                                    $ev2_result = $this->examresult_model->getFinalMark($student['student_session_id'], $examList[1]->exam_group_class_batch_exam_id, $subSubjects[$rws]->id);
                                    $ev3_result = $this->examresult_model->getFinalMark($student['student_session_id'], $examList[2]->exam_group_class_batch_exam_id, $subSubjects[$rws]->id);
                                    $ev4_result = $this->examresult_model->getFinalMark($student['student_session_id'], $examList[3]->exam_group_class_batch_exam_id, $subSubjects[$rws]->id);
                                    $ev5_result = $this->examresult_model->getFinalMark($student['student_session_id'], $examList[4]->exam_group_class_batch_exam_id, $subSubjects[$rws]->id);
                                    $ev6_result = $this->examresult_model->getFinalMark($student['student_session_id'], $examList[5]->exam_group_class_batch_exam_id, $subSubjects[$rws]->id);

                                    $ev1_subject = $this->batchsubject_model->getExamSubjectsOnly($examList[0]->exam_group_class_batch_exam_id, $subSubjects[$rws]->id);
                                    $ev2_subject = $this->batchsubject_model->getExamSubjectsOnly($examList[1]->exam_group_class_batch_exam_id, $subSubjects[$rws]->id);
                                    $ev3_subject = $this->batchsubject_model->getExamSubjectsOnly($examList[2]->exam_group_class_batch_exam_id, $subSubjects[$rws]->id);
                                    $ev4_subject = $this->batchsubject_model->getExamSubjectsOnly($examList[3]->exam_group_class_batch_exam_id, $subSubjects[$rws]->id);
                                    $ev5_subject = $this->batchsubject_model->getExamSubjectsOnly($examList[4]->exam_group_class_batch_exam_id, $subSubjects[$rws]->id);
                                    $ev6_subject = $this->batchsubject_model->getExamSubjectsOnly($examList[5]->exam_group_class_batch_exam_id, $subSubjects[$rws]->id);

                                    if (!empty($ev1_result)) {
                                        $total_mark1 += $ev1_result['get_marks'];
                                    }
                                    if (!empty($ev2_result)) {
                                        $total_mark2 += $ev2_result['get_marks'];
                                    }
                                    if (!empty($ev3_result)) {
                                        $total_mark3 += $ev3_result['get_marks'];
                                    }
                                    if (!empty($ev4_result)) {
                                        $total_mark4 += $ev4_result['get_marks'];
                                    }
                                    if (!empty($ev5_result)) {
                                        $total_mark5 += $ev5_result['get_marks'];
                                    }
                                    if (!empty($ev6_result)) {
                                        $total_mark6 += $ev6_result['get_marks'];
                                    }


                                    if (!empty($ev1_result['get_marks'])) {
                                        if ($ev1_result['get_marks'] > 0) {
                                            $mark1_status = 1;
                                            $max_mark1 += $ev1_subject['max_marks'];
                                        } elseif ($ev1_result['final_mark'] == "-" && $mark1_status == 0) {
                                            $mark1_status = 2;
                                        } elseif ($ev1_result['final_mark'] == "Ab" && $mark1_status == 0) {
                                            $mark1_status = 3;
                                        }
                                    } elseif ($ev1_result['final_mark'] == "-" && $mark1_status == 0) {
                                        $mark1_status = 2;
                                    } elseif ($ev1_result['final_mark'] == "Ab" && $mark1_status == 0) {
                                        $mark1_status = 3;
                                    }

                                    if (!empty($ev2_result['get_marks'])) {
                                        if ($ev2_result['get_marks'] > 0) {
                                            $mark2_status = 1;
                                            $max_mark2 += $ev2_subject['max_marks'];
                                        } elseif ($ev2_result['final_mark'] == "-" && $mark2_status == 0) {
                                            $mark2_status = 2;
                                        } elseif ($ev2_result['final_mark'] == "Ab" && $mark2_status == 0) {
                                            $mark2_status = 3;
                                        }
                                    } elseif ($ev2_result['final_mark'] == "-" && $mark2_status == 0) {
                                        $mark2_status = 2;
                                    } elseif ($ev2_result['final_mark'] == "Ab" && $mark2_status == 0) {
                                        $mark2_status = 3;
                                    }

                                    if (!empty($ev3_result['get_marks'])) {
                                        if ($ev3_result['get_marks'] > 0) {
                                            $mark3_status = 1;
                                            $max_mark3 += $ev3_subject['max_marks'];
                                        } elseif ($ev3_result['final_mark'] == "-" && $mark3_status == 0) {
                                            $mark3_status = 2;
                                        } elseif ($ev3_result['final_mark'] == "Ab" && $mark3_status == 0) {
                                            $mark3_status = 3;
                                        }
                                    } elseif ($ev3_result['final_mark'] == "-" && $mark3_status == 0) {
                                        $mark3_status = 2;
                                    } elseif ($ev3_result['final_mark'] == "Ab" && $mark3_status == 0) {
                                        $mark3_status = 3;
                                    }

                                    if (!empty($ev4_result['get_marks'])) {
                                        if ($ev4_result['get_marks'] > 0) {
                                            $mark4_status = 1;
                                            $max_mark4 += $ev4_subject['max_marks'];
                                        } elseif ($ev4_result['final_mark'] == "-" && $mark4_status == 0) {
                                            $mark4_status = 2;
                                        } elseif ($ev4_result['final_mark'] == "Ab" && $mark4_status == 0) {
                                            $mark4_status = 3;
                                        }
                                    } elseif ($ev4_result['final_mark'] == "-" && $mark4_status == 0) {
                                        $mark4_status = 2;
                                    } elseif ($ev4_result['final_mark'] == "Ab" && $mark4_status == 0) {
                                        $mark4_status = 3;
                                    }

                                    if (!empty($ev5_result['get_marks'])) {
                                        if ($ev5_result['get_marks'] > 0) {
                                            $mark5_status = 1;
                                            $max_mark5 += $ev5_subject['max_marks'];
                                        } elseif ($ev5_result['final_mark'] == "-" && $mark5_status == 0) {
                                            $mark5_status = 2;
                                        } elseif ($ev5_result['final_mark'] == "Ab" && $mark5_status == 0) {
                                            $mark5_status = 3;
                                        }
                                    } elseif ($ev5_result['final_mark'] == "-" && $mark5_status == 0) {
                                        $mark5_status = 2;
                                    } elseif ($ev5_result['final_mark'] == "Ab" && $mark5_status == 0) {
                                        $mark5_status = 3;
                                    }

                                    if (!empty($ev6_result['get_marks'])) {
                                        if ($ev6_result['get_marks'] > 0) {
                                            $mark6_status = 1;
                                            $max_mark6 += $ev6_subject['max_marks'];
                                        } elseif ($ev6_result['final_mark'] == "-" && $mark6_status == 0) {
                                            $mark6_status = 2;
                                        } elseif ($ev6_result['final_mark'] == "Ab" && $mark6_status == 0) {
                                            $mark6_status = 3;
                                        }
                                    } elseif ($ev6_result['final_mark'] == "-" && $mark6_status == 0) {
                                        $mark6_status = 2;
                                    } elseif ($ev6_result['final_mark'] == "Ab" && $mark6_status == 0) {
                                        $mark6_status = 3;
                                    }

                                ?>
                                    <tr>
                                        <td class="wt">> <?php echo $subSubjects[$rws]->name; ?></td>
                                        <td class="textct"><?php if (!empty($ev1_result)) {
                                                                echo $ev1_result['final_mark'];
                                                            } else {
                                                                echo "-";
                                                            }  ?></td>
                                        <td class="textct"><?php if (!empty($ev2_result)) {
                                                                echo $ev2_result['final_mark'];
                                                            } else {
                                                                echo "-";
                                                            }  ?></td>
                                        <td class="textct"><?php if (!empty($ev3_result)) {
                                                                echo $ev3_result['final_mark'];
                                                            } else {
                                                                echo "-";
                                                            }  ?></td>
                                        <td class="textct"><?php if (!empty($ev4_result)) {
                                                                echo $ev4_result['final_mark'];
                                                            } else {
                                                                echo "-";
                                                            }  ?></td>
                                        <td class="textct"><?php if (!empty($ev5_result)) {
                                                                echo $ev5_result['final_mark'];
                                                            } else {
                                                                echo "-";
                                                            }  ?></td>
                                        <td class="textct"><?php if (!empty($ev6_result)) {
                                                                echo $ev6_result['final_mark'];
                                                            } else {
                                                                echo "-";
                                                            }  ?></td>
                                    </tr>
                            <?php }
                            }
                            $ev1_grade = $this->grade_model->get_Grade_New($exam_grades, $mark1_status, $total_mark1 == 0 ? 0 : round((($total_mark1 * 100) / $max_mark1), 2));
                            $ev2_grade = $this->grade_model->get_Grade_New($exam_grades, $mark2_status, $total_mark2 == 0 ? 0 : round((($total_mark2 * 100) / $max_mark2), 2));
                            $ev3_grade = $this->grade_model->get_Grade_New($exam_grades, $mark3_status, $total_mark3 == 0 ? 0 : round((($total_mark3 * 100) / $max_mark3), 2));
                            $ev4_grade = $this->grade_model->get_Grade_New($exam_grades, $mark4_status, $total_mark4 == 0 ? 0 : round((($total_mark4 * 100) / $max_mark4), 2));
                            $ev5_grade = $this->grade_model->get_Grade_New($exam_grades, $mark5_status, $total_mark5 == 0 ? 0 : round((($total_mark5 * 100) / $max_mark5), 2));
                            $ev6_grade = $this->grade_model->get_Grade_New($exam_grades, $mark6_status, $total_mark6 == 0 ? 0 : round((($total_mark6 * 100) / $max_mark6), 2));
                            ?>
                            <tr>
                                <td class="wt"><b>Subject Grade </b></td>
                                <td class="textct"><b><?php echo $ev1_grade ?></b></td>
                                <td class="textct"><b><?php echo $ev2_grade ?></b></td>
                                <td class="textct"><b><?php echo $ev3_grade ?></b></td>
                                <td class="textct"><b><?php echo $ev4_grade ?></b></td>
                                <td class="textct"><b><?php echo $ev5_grade ?></b></td>
                                <td class="textct"><b><?php echo $ev6_grade ?></b></td>
                            </tr>
                        </tbody>
                    </table>
                <?php } ?>

                <?php $rw = 0;
                for ($rw = 4; $rw <= 4; ++$rw) {
                    $total_mark1 = 0;
                    $total_mark2 = 0;
                    $total_mark3 = 0;
                    $total_mark4 = 0;
                    $total_mark5 = 0;
                    $total_mark6 = 0;
                    $max_mark1 = 0;
                    $max_mark2 = 0;
                    $max_mark3 = 0;
                    $max_mark4 = 0;
                    $max_mark5 = 0;
                    $max_mark6 = 0;
                    $mark1_status = 0;
                    $mark2_status = 0;
                    $mark3_status = 0;
                    $mark4_status = 0;
                    $mark5_status = 0;
                    $mark6_status = 0;
                ?>
                    <table class="table" style="margin-top:9px">
                        <tbody>
                            <?php if ($rw == 4) { ?>
                                <tr>
                                    <th colspan='7' style="text-align:center;background-color: #9ac86f;font-size: 14px;"> B.Cognitive Development</th>
                                </tr>
                            <?php } ?>
                            <tr class="bg">
                                <th class="wt"><?php echo $main_subjects[$rw]->name; ?></th>
                                <th class="evl" style="text-align:center">Evaluation 1</th>
                                <th class="evl" style="text-align:center">Evaluation 2</th>
                                <th class="evl" style="text-align:center">Evaluation 3</th>
                                <th class="evl" style="text-align:center">Evaluation 4</th>
                                <th class="evl" style="text-align:center">Evaluation 5</th>
                                <th class="evl" style="text-align:center">Evaluation 6</th>
                            </tr>
                            <?php
                            $subGroups = $main_subjects[$rw]->subGroups;
                            for ($rwg = 0; $rwg < sizeof($subGroups); ++$rwg) {
                                if ($subGroups[$rwg]->id != $main_subjects[$rw]->id) { ?>
                                    <tr class="bg">
                                        <th colspan="7" class="wt"><?php echo $subGroups[$rwg]->name; ?></th>
                                    </tr>
                                <?php }
                                $subSubjects = $subGroups[$rwg]->subSubjects;
                                for ($rws = 0; $rws < sizeof($subSubjects); ++$rws) {
                                    $ev1_result = $this->examresult_model->getFinalMark($student['student_session_id'], $examList[0]->exam_group_class_batch_exam_id, $subSubjects[$rws]->id);
                                    $ev2_result = $this->examresult_model->getFinalMark($student['student_session_id'], $examList[1]->exam_group_class_batch_exam_id, $subSubjects[$rws]->id);
                                    $ev3_result = $this->examresult_model->getFinalMark($student['student_session_id'], $examList[2]->exam_group_class_batch_exam_id, $subSubjects[$rws]->id);
                                    $ev4_result = $this->examresult_model->getFinalMark($student['student_session_id'], $examList[3]->exam_group_class_batch_exam_id, $subSubjects[$rws]->id);
                                    $ev5_result = $this->examresult_model->getFinalMark($student['student_session_id'], $examList[4]->exam_group_class_batch_exam_id, $subSubjects[$rws]->id);
                                    $ev6_result = $this->examresult_model->getFinalMark($student['student_session_id'], $examList[5]->exam_group_class_batch_exam_id, $subSubjects[$rws]->id);

                                    $ev1_subject = $this->batchsubject_model->getExamSubjectsOnly($examList[0]->exam_group_class_batch_exam_id, $subSubjects[$rws]->id);
                                    $ev2_subject = $this->batchsubject_model->getExamSubjectsOnly($examList[1]->exam_group_class_batch_exam_id, $subSubjects[$rws]->id);
                                    $ev3_subject = $this->batchsubject_model->getExamSubjectsOnly($examList[2]->exam_group_class_batch_exam_id, $subSubjects[$rws]->id);
                                    $ev4_subject = $this->batchsubject_model->getExamSubjectsOnly($examList[3]->exam_group_class_batch_exam_id, $subSubjects[$rws]->id);
                                    $ev5_subject = $this->batchsubject_model->getExamSubjectsOnly($examList[4]->exam_group_class_batch_exam_id, $subSubjects[$rws]->id);
                                    $ev6_subject = $this->batchsubject_model->getExamSubjectsOnly($examList[5]->exam_group_class_batch_exam_id, $subSubjects[$rws]->id);

                                    if (!empty($ev1_result)) {
                                        $total_mark1 += $ev1_result['get_marks'];
                                    }
                                    if (!empty($ev2_result)) {
                                        $total_mark2 += $ev2_result['get_marks'];
                                    }
                                    if (!empty($ev3_result)) {
                                        $total_mark3 += $ev3_result['get_marks'];
                                    }
                                    if (!empty($ev4_result)) {
                                        $total_mark4 += $ev4_result['get_marks'];
                                    }
                                    if (!empty($ev5_result)) {
                                        $total_mark5 += $ev5_result['get_marks'];
                                    }
                                    if (!empty($ev6_result)) {
                                        $total_mark6 += $ev6_result['get_marks'];
                                    }

                                    if (!empty($ev1_result['get_marks'])) {
                                        if ($ev1_result['get_marks'] > 0) {
                                            $mark1_status = 1;
                                            $max_mark1 += $ev1_subject['max_marks'];
                                        } elseif ($ev1_result['final_mark'] == "-" && $mark1_status == 0) {
                                            $mark1_status = 2;
                                        } elseif ($ev1_result['final_mark'] == "Ab" && $mark1_status == 0) {
                                            $mark1_status = 3;
                                        }
                                    } elseif ($ev1_result['final_mark'] == "-" && $mark1_status == 0) {
                                        $mark1_status = 2;
                                    } elseif ($ev1_result['final_mark'] == "Ab" && $mark1_status == 0) {
                                        $mark1_status = 3;
                                    }

                                    if (!empty($ev2_result['get_marks'])) {
                                        if ($ev2_result['get_marks'] > 0) {
                                            $mark2_status = 1;
                                            $max_mark2 += $ev2_subject['max_marks'];
                                        } elseif ($ev2_result['final_mark'] == "-" && $mark2_status == 0) {
                                            $mark2_status = 2;
                                        } elseif ($ev2_result['final_mark'] == "Ab" && $mark2_status == 0) {
                                            $mark2_status = 3;
                                        }
                                    } elseif ($ev2_result['final_mark'] == "-" && $mark2_status == 0) {
                                        $mark2_status = 2;
                                    } elseif ($ev2_result['final_mark'] == "Ab" && $mark2_status == 0) {
                                        $mark2_status = 3;
                                    }

                                    if (!empty($ev3_result['get_marks'])) {
                                        if ($ev3_result['get_marks'] > 0) {
                                            $mark3_status = 1;
                                            $max_mark3 += $ev3_subject['max_marks'];
                                        } elseif ($ev3_result['final_mark'] == "-" && $mark3_status == 0) {
                                            $mark3_status = 2;
                                        } elseif ($ev3_result['final_mark'] == "Ab" && $mark3_status == 0) {
                                            $mark3_status = 3;
                                        }
                                    } elseif ($ev3_result['final_mark'] == "-" && $mark3_status == 0) {
                                        $mark3_status = 2;
                                    } elseif ($ev3_result['final_mark'] == "Ab" && $mark3_status == 0) {
                                        $mark3_status = 3;
                                    }

                                    if (!empty($ev4_result['get_marks'])) {
                                        if ($ev4_result['get_marks'] > 0) {
                                            $mark4_status = 1;
                                            $max_mark4 += $ev4_subject['max_marks'];
                                        } elseif ($ev4_result['final_mark'] == "-" && $mark4_status == 0) {
                                            $mark4_status = 2;
                                        } elseif ($ev4_result['final_mark'] == "Ab" && $mark4_status == 0) {
                                            $mark4_status = 3;
                                        }
                                    } elseif ($ev4_result['final_mark'] == "-" && $mark4_status == 0) {
                                        $mark4_status = 2;
                                    } elseif ($ev4_result['final_mark'] == "Ab" && $mark4_status == 0) {
                                        $mark4_status = 3;
                                    }

                                    if (!empty($ev5_result['get_marks'])) {
                                        if ($ev5_result['get_marks'] > 0) {
                                            $mark5_status = 1;
                                            $max_mark5 += $ev5_subject['max_marks'];
                                        } elseif ($ev5_result['final_mark'] == "-" && $mark5_status == 0) {
                                            $mark5_status = 2;
                                        } elseif ($ev5_result['final_mark'] == "Ab" && $mark5_status == 0) {
                                            $mark5_status = 3;
                                        }
                                    } elseif ($ev5_result['final_mark'] == "-" && $mark5_status == 0) {
                                        $mark5_status = 2;
                                    } elseif ($ev5_result['final_mark'] == "Ab" && $mark5_status == 0) {
                                        $mark5_status = 3;
                                    }

                                    if (!empty($ev6_result['get_marks'])) {
                                        if ($ev6_result['get_marks'] > 0) {
                                            $mark6_status = 1;
                                            $max_mark6 += $ev6_subject['max_marks'];
                                        } elseif ($ev6_result['final_mark'] == "-" && $mark6_status == 0) {
                                            $mark6_status = 2;
                                        } elseif ($ev6_result['final_mark'] == "Ab" && $mark6_status == 0) {
                                            $mark6_status = 3;
                                        }
                                    } elseif ($ev6_result['final_mark'] == "-" && $mark6_status == 0) {
                                        $mark6_status = 2;
                                    } elseif ($ev6_result['final_mark'] == "Ab" && $mark6_status == 0) {
                                        $mark6_status = 3;
                                    }


                                ?>
                                    <tr>
                                        <td class="wt">> <?php echo $subSubjects[$rws]->name; ?></td>
                                        <td class="textct"><?php if (!empty($ev1_result)) {
                                                                echo $ev1_result['final_mark'];
                                                            } else {
                                                                echo "-";
                                                            }  ?></td>
                                        <td class="textct"><?php if (!empty($ev2_result)) {
                                                                echo $ev2_result['final_mark'];
                                                            } else {
                                                                echo "-";
                                                            }  ?></td>
                                        <td class="textct"><?php if (!empty($ev3_result)) {
                                                                echo $ev3_result['final_mark'];
                                                            } else {
                                                                echo "-";
                                                            }  ?></td>
                                        <td class="textct"><?php if (!empty($ev4_result)) {
                                                                echo $ev4_result['final_mark'];
                                                            } else {
                                                                echo "-";
                                                            }  ?></td>
                                        <td class="textct"><?php if (!empty($ev5_result)) {
                                                                echo $ev5_result['final_mark'];
                                                            } else {
                                                                echo "-";
                                                            }  ?></td>
                                        <td class="textct"><?php if (!empty($ev6_result)) {
                                                                echo $ev6_result['final_mark'];
                                                            } else {
                                                                echo "-";
                                                            }  ?></td>
                                    </tr>



                            <?php }
                            }
                            $ev1_grade = $this->grade_model->get_Grade_New($exam_grades, $mark1_status, $total_mark1 == 0 ? 0 : round((($total_mark1 * 100) / $max_mark1), 2));
                            $ev2_grade = $this->grade_model->get_Grade_New($exam_grades, $mark2_status, $total_mark2 == 0 ? 0 : round((($total_mark2 * 100) / $max_mark2), 2));
                            $ev3_grade = $this->grade_model->get_Grade_New($exam_grades, $mark3_status, $total_mark3 == 0 ? 0 : round((($total_mark3 * 100) / $max_mark3), 2));
                            $ev4_grade = $this->grade_model->get_Grade_New($exam_grades, $mark4_status, $total_mark4 == 0 ? 0 : round((($total_mark4 * 100) / $max_mark4), 2));
                            $ev5_grade = $this->grade_model->get_Grade_New($exam_grades, $mark5_status, $total_mark5 == 0 ? 0 : round((($total_mark5 * 100) / $max_mark5), 2));
                            $ev6_grade = $this->grade_model->get_Grade_New($exam_grades, $mark6_status, $total_mark6 == 0 ? 0 : round((($total_mark6 * 100) / $max_mark6), 2));


                            ?>
                            <tr>
                                <td class="wt"><b>Subject Grade </b></td>
                                <td class="textct"><b><?php echo $ev1_grade ?></b></td>
                                <td class="textct"><b><?php echo $ev2_grade ?></b></td>
                                <td class="textct"><b><?php echo $ev3_grade ?></b></td>
                                <td class="textct"><b><?php echo $ev4_grade ?></b></td>
                                <td class="textct"><b><?php echo $ev5_grade ?></b></td>
                                <td class="textct"><b><?php echo $ev6_grade ?></b></td>
                            </tr>
                        </tbody>
                    </table>
                <?php } ?>
            </div>
            <div class="w-50 W50BG">

                <?php $rw = 0;
                for ($rw = 2; $rw <= 2; ++$rw) {
                    $total_mark1 = 0;
                    $total_mark2 = 0;
                    $total_mark3 = 0;
                    $total_mark4 = 0;
                    $total_mark5 = 0;
                    $total_mark6 = 0;
                    $max_mark1 = 0;
                    $max_mark2 = 0;
                    $max_mark3 = 0;
                    $max_mark4 = 0;
                    $max_mark5 = 0;
                    $max_mark6 = 0;
                    $mark1_status = 0;
                    $mark2_status = 0;
                    $mark3_status = 0;
                    $mark4_status = 0;
                    $mark5_status = 0;
                    $mark6_status = 0;
                ?>
                    <table class="table" style="margin-top:9px">
                        <tbody>

                            <tr class="bg">
                                <th class="wt"><?php echo $main_subjects[$rw]->name; ?></th>
                                <th class="evl" style="text-align:center">Evaluation 1</th>
                                <th class="evl" style="text-align:center">Evaluation 2</th>
                                <th class="evl" style="text-align:center">Evaluation 3</th>
                                <th class="evl" style="text-align:center">Evaluation 4</th>
                                <th class="evl" style="text-align:center">Evaluation 5</th>
                                <th class="evl" style="text-align:center">Evaluation 6</th>
                            </tr>
                            <?php
                            $subGroups = $main_subjects[$rw]->subGroups;
                            for ($rwg = 0; $rwg < sizeof($subGroups); ++$rwg) {
                                /*
                                if ($subGroups[$rwg]->id != $main_subjects[$rw]->id) { ?>
                                    <tr class="bg">
                                        <th colspan="7" class="wt"><?php echo $subGroups[$rwg]->name; ?></th>
                                    </tr>
                                <?php } */
                                $subSubjects = $subGroups[$rwg]->subSubjects;
                                for ($rws = 0; $rws < sizeof($subSubjects); ++$rws) {
                                    $ev1_result = $this->examresult_model->getFinalMark($student['student_session_id'], $examList[0]->exam_group_class_batch_exam_id, $subSubjects[$rws]->id);
                                    $ev2_result = $this->examresult_model->getFinalMark($student['student_session_id'], $examList[1]->exam_group_class_batch_exam_id, $subSubjects[$rws]->id);
                                    $ev3_result = $this->examresult_model->getFinalMark($student['student_session_id'], $examList[2]->exam_group_class_batch_exam_id, $subSubjects[$rws]->id);
                                    $ev4_result = $this->examresult_model->getFinalMark($student['student_session_id'], $examList[3]->exam_group_class_batch_exam_id, $subSubjects[$rws]->id);
                                    $ev5_result = $this->examresult_model->getFinalMark($student['student_session_id'], $examList[4]->exam_group_class_batch_exam_id, $subSubjects[$rws]->id);
                                    $ev6_result = $this->examresult_model->getFinalMark($student['student_session_id'], $examList[5]->exam_group_class_batch_exam_id, $subSubjects[$rws]->id);

                                    $ev1_subject = $this->batchsubject_model->getExamSubjectsOnly($examList[0]->exam_group_class_batch_exam_id, $subSubjects[$rws]->id);
                                    $ev2_subject = $this->batchsubject_model->getExamSubjectsOnly($examList[1]->exam_group_class_batch_exam_id, $subSubjects[$rws]->id);
                                    $ev3_subject = $this->batchsubject_model->getExamSubjectsOnly($examList[2]->exam_group_class_batch_exam_id, $subSubjects[$rws]->id);
                                    $ev4_subject = $this->batchsubject_model->getExamSubjectsOnly($examList[3]->exam_group_class_batch_exam_id, $subSubjects[$rws]->id);
                                    $ev5_subject = $this->batchsubject_model->getExamSubjectsOnly($examList[4]->exam_group_class_batch_exam_id, $subSubjects[$rws]->id);
                                    $ev6_subject = $this->batchsubject_model->getExamSubjectsOnly($examList[5]->exam_group_class_batch_exam_id, $subSubjects[$rws]->id);

                                    if (!empty($ev1_result)) {
                                        $total_mark1 += $ev1_result['get_marks'];
                                    }
                                    if (!empty($ev2_result)) {
                                        $total_mark2 += $ev2_result['get_marks'];
                                    }
                                    if (!empty($ev3_result)) {
                                        $total_mark3 += $ev3_result['get_marks'];
                                    }
                                    if (!empty($ev4_result)) {
                                        $total_mark4 += $ev4_result['get_marks'];
                                    }
                                    if (!empty($ev5_result)) {
                                        $total_mark5 += $ev5_result['get_marks'];
                                    }
                                    if (!empty($ev6_result)) {
                                        $total_mark6 += $ev6_result['get_marks'];
                                    }

                                    if (!empty($ev1_result['get_marks'])) {
                                        if ($ev1_result['get_marks'] > 0) {
                                            $mark1_status = 1;
                                            $max_mark1 += $ev1_subject['max_marks'];
                                        } elseif ($ev1_result['final_mark'] == "-" && $mark1_status == 0) {
                                            $mark1_status = 2;
                                        } elseif ($ev1_result['final_mark'] == "Ab" && $mark1_status == 0) {
                                            $mark1_status = 3;
                                        }
                                    } elseif ($ev1_result['final_mark'] == "-" && $mark1_status == 0) {
                                        $mark1_status = 2;
                                    } elseif ($ev1_result['final_mark'] == "Ab" && $mark1_status == 0) {
                                        $mark1_status = 3;
                                    }

                                    if (!empty($ev2_result['get_marks'])) {
                                        if ($ev2_result['get_marks'] > 0) {
                                            $mark2_status = 1;
                                            $max_mark2 += $ev2_subject['max_marks'];
                                        } elseif ($ev2_result['final_mark'] == "-" && $mark2_status == 0) {
                                            $mark2_status = 2;
                                        } elseif ($ev2_result['final_mark'] == "Ab" && $mark2_status == 0) {
                                            $mark2_status = 3;
                                        }
                                    } elseif ($ev2_result['final_mark'] == "-" && $mark2_status == 0) {
                                        $mark2_status = 2;
                                    } elseif ($ev2_result['final_mark'] == "Ab" && $mark2_status == 0) {
                                        $mark2_status = 3;
                                    }

                                    if (!empty($ev3_result['get_marks'])) {
                                        if ($ev3_result['get_marks'] > 0) {
                                            $mark3_status = 1;
                                            $max_mark3 += $ev3_subject['max_marks'];
                                        } elseif ($ev3_result['final_mark'] == "-" && $mark3_status == 0) {
                                            $mark3_status = 2;
                                        } elseif ($ev3_result['final_mark'] == "Ab" && $mark3_status == 0) {
                                            $mark3_status = 3;
                                        }
                                    } elseif ($ev3_result['final_mark'] == "-" && $mark3_status == 0) {
                                        $mark3_status = 2;
                                    } elseif ($ev3_result['final_mark'] == "Ab" && $mark3_status == 0) {
                                        $mark3_status = 3;
                                    }

                                    if (!empty($ev4_result['get_marks'])) {
                                        if ($ev4_result['get_marks'] > 0) {
                                            $mark4_status = 1;
                                            $max_mark4 += $ev4_subject['max_marks'];
                                        } elseif ($ev4_result['final_mark'] == "-" && $mark4_status == 0) {
                                            $mark4_status = 2;
                                        } elseif ($ev4_result['final_mark'] == "Ab" && $mark4_status == 0) {
                                            $mark4_status = 3;
                                        }
                                    } elseif ($ev4_result['final_mark'] == "-" && $mark4_status == 0) {
                                        $mark4_status = 2;
                                    } elseif ($ev4_result['final_mark'] == "Ab" && $mark4_status == 0) {
                                        $mark4_status = 3;
                                    }

                                    if (!empty($ev5_result['get_marks'])) {
                                        if ($ev5_result['get_marks'] > 0) {
                                            $mark5_status = 1;
                                            $max_mark5 += $ev5_subject['max_marks'];
                                        } elseif ($ev5_result['final_mark'] == "-" && $mark5_status == 0) {
                                            $mark5_status = 2;
                                        } elseif ($ev5_result['final_mark'] == "Ab" && $mark5_status == 0) {
                                            $mark5_status = 3;
                                        }
                                    } elseif ($ev5_result['final_mark'] == "-" && $mark5_status == 0) {
                                        $mark5_status = 2;
                                    } elseif ($ev5_result['final_mark'] == "Ab" && $mark5_status == 0) {
                                        $mark5_status = 3;
                                    }

                                    if (!empty($ev6_result['get_marks'])) {
                                        if ($ev6_result['get_marks'] > 0) {
                                            $mark6_status = 1;
                                            $max_mark6 += $ev6_subject['max_marks'];
                                        } elseif ($ev6_result['final_mark'] == "-" && $mark6_status == 0) {
                                            $mark6_status = 2;
                                        } elseif ($ev6_result['final_mark'] == "Ab" && $mark6_status == 0) {
                                            $mark6_status = 3;
                                        }
                                    } elseif ($ev6_result['final_mark'] == "-" && $mark6_status == 0) {
                                        $mark6_status = 2;
                                    } elseif ($ev6_result['final_mark'] == "Ab" && $mark6_status == 0) {
                                        $mark6_status = 3;
                                    }


                                    if ($rws == 0) { ?>
                                        <tr class="bg">
                                            <th colspan="7" class="wt"><?php echo "Basic Concepts"; ?></th>
                                        </tr>
                                    <?php } elseif ($rws == 2) { ?>
                                        <tr class="bg">
                                            <th colspan="7" class="wt"><?php echo "Number Concepts"; ?></th>
                                        </tr>
                                    <?php } elseif ($rws == 5) { ?>
                                        <tr class="bg">
                                            <th colspan="7" class="wt"><?php echo "Thinking Skills"; ?></th>
                                        </tr>
                                    <?php }   ?>
                                    <tr>
                                        <td class="wt">> <?php echo $subSubjects[$rws]->name; ?></td>
                                        <td class="textct"><?php if (!empty($ev1_result)) {
                                                                echo $ev1_result['final_mark'];
                                                            } else {
                                                                echo "-";
                                                            }  ?></td>
                                        <td class="textct"><?php if (!empty($ev2_result)) {
                                                                echo $ev2_result['final_mark'];
                                                            } else {
                                                                echo "-";
                                                            }  ?></td>
                                        <td class="textct"><?php if (!empty($ev3_result)) {
                                                                echo $ev3_result['final_mark'];
                                                            } else {
                                                                echo "-";
                                                            }  ?></td>
                                        <td class="textct"><?php if (!empty($ev4_result)) {
                                                                echo $ev4_result['final_mark'];
                                                            } else {
                                                                echo "-";
                                                            }  ?></td>
                                        <td class="textct"><?php if (!empty($ev5_result)) {
                                                                echo $ev5_result['final_mark'];
                                                            } else {
                                                                echo "-";
                                                            }  ?></td>
                                        <td class="textct"><?php if (!empty($ev6_result)) {
                                                                echo $ev6_result['final_mark'];
                                                            } else {
                                                                echo "-";
                                                            }  ?></td>
                                    </tr>

                            <?php }
                            }
                            $ev1_grade = $this->grade_model->get_Grade_New($exam_grades, $mark1_status, $total_mark1 == 0 ? 0 : round((($total_mark1 * 100) / $max_mark1), 2));
                            $ev2_grade = $this->grade_model->get_Grade_New($exam_grades, $mark2_status, $total_mark2 == 0 ? 0 : round((($total_mark2 * 100) / $max_mark2), 2));
                            $ev3_grade = $this->grade_model->get_Grade_New($exam_grades, $mark3_status, $total_mark3 == 0 ? 0 : round((($total_mark3 * 100) / $max_mark3), 2));
                            $ev4_grade = $this->grade_model->get_Grade_New($exam_grades, $mark4_status, $total_mark4 == 0 ? 0 : round((($total_mark4 * 100) / $max_mark4), 2));
                            $ev5_grade = $this->grade_model->get_Grade_New($exam_grades, $mark5_status, $total_mark5 == 0 ? 0 : round((($total_mark5 * 100) / $max_mark5), 2));
                            $ev6_grade = $this->grade_model->get_Grade_New($exam_grades, $mark6_status, $total_mark6 == 0 ? 0 : round((($total_mark6 * 100) / $max_mark6), 2));


                            ?>
                            <tr>
                                <td class="wt"><b>Subject Grade </b></td>
                                <td class="textct"><b><?php echo $ev1_grade ?></b></td>
                                <td class="textct"><b><?php echo $ev2_grade ?></b></td>
                                <td class="textct"><b><?php echo $ev3_grade ?></b></td>
                                <td class="textct"><b><?php echo $ev4_grade ?></b></td>
                                <td class="textct"><b><?php echo $ev5_grade ?></b></td>
                                <td class="textct"><b><?php echo $ev6_grade ?></b></td>
                            </tr>
                        </tbody>
                    </table>
                <?php } ?>

                <?php //arts craft
                $rw = 0;
                for ($rw = 5; $rw <= 6; ++$rw) { ?>
                    <table class="table" style="margin-top:9px">
                        <tbody>
                            <?php if ($rw == 5) { ?>
                                <tr>
                                    <th colspan='7' style="text-align:center;background-color:#9ac86f;font-size: 14px;"> C. Creative and Expressive Arts </th>
                                </tr>
                            <?php } ?>
                            <tr class="bg">
                                <th class="wt"><?php echo $main_subjects[$rw]->name; ?></th>
                                <th class="evl" style="text-align:center">Evaluation 1</th>
                                <th class="evl" style="text-align:center">Evaluation 2</th>
                                <th class="evl" style="text-align:center">Evaluation 3</th>
                                <th class="evl" style="text-align:center">Evaluation 4</th>
                                <th class="evl" style="text-align:center">Evaluation 5</th>
                                <th class="evl" style="text-align:center">Evaluation 6</th>
                            </tr>
                            <?php
                            $subGroups = $main_subjects[$rw]->subGroups;
                            for ($rwg = 0; $rwg < sizeof($subGroups); ++$rwg) {
                                if ($subGroups[$rwg]->id != $main_subjects[$rw]->id) { ?>
                                    <tr class="bg">
                                        <th colspan="7" class="wt"><?php echo $subGroups[$rwg]->name; ?></th>
                                    </tr>
                                <?php }
                                $subSubjects = $subGroups[$rwg]->subSubjects;
                                for ($rws = 0; $rws < sizeof($subSubjects); ++$rws) {
                                    $ev1_result = $this->examresult_model->getFinalMark($student['student_session_id'], $examList[0]->exam_group_class_batch_exam_id, $subSubjects[$rws]->id);
                                    $ev2_result = $this->examresult_model->getFinalMark($student['student_session_id'], $examList[1]->exam_group_class_batch_exam_id, $subSubjects[$rws]->id);
                                    $ev3_result = $this->examresult_model->getFinalMark($student['student_session_id'], $examList[2]->exam_group_class_batch_exam_id, $subSubjects[$rws]->id);
                                    $ev4_result = $this->examresult_model->getFinalMark($student['student_session_id'], $examList[3]->exam_group_class_batch_exam_id, $subSubjects[$rws]->id);
                                    $ev5_result = $this->examresult_model->getFinalMark($student['student_session_id'], $examList[4]->exam_group_class_batch_exam_id, $subSubjects[$rws]->id);
                                    $ev6_result = $this->examresult_model->getFinalMark($student['student_session_id'], $examList[5]->exam_group_class_batch_exam_id, $subSubjects[$rws]->id);

                                ?>
                                    <tr>
                                        <td class="wt">> <?php echo $subSubjects[$rws]->name; ?></td>
                                        <td class="textct"><?php if (!empty($ev1_result)) {
                                                                echo $ev1_result['final_mark'];
                                                            } else {
                                                                echo "-";
                                                            }  ?></td>
                                        <td class="textct"><?php if (!empty($ev2_result)) {
                                                                echo $ev2_result['final_mark'];
                                                            } else {
                                                                echo "-";
                                                            }  ?></td>
                                        <td class="textct"><?php if (!empty($ev3_result)) {
                                                                echo $ev3_result['final_mark'];
                                                            } else {
                                                                echo "-";
                                                            }  ?></td>
                                        <td class="textct"><?php if (!empty($ev4_result)) {
                                                                echo $ev4_result['final_mark'];
                                                            } else {
                                                                echo "-";
                                                            }  ?></td>
                                        <td class="textct"><?php if (!empty($ev5_result)) {
                                                                echo $ev5_result['final_mark'];
                                                            } else {
                                                                echo "-";
                                                            }  ?></td>
                                        <td class="textct"><?php if (!empty($ev6_result)) {
                                                                echo $ev6_result['final_mark'];
                                                            } else {
                                                                echo "-";
                                                            }  ?></td>
                                    </tr>

                                    <!-- <tr>
                        <td class="wt"><b>Subject Grade </b></td>
                        <td class="textct"></td>
                        <td class="textct"></td>
                        <td class="textct"></td>
                        <td class="textct"></td>
                        <td class="textct"></td>
                        <td class="textct"></td>
                    </tr> -->
                        </tbody>
                <?php }
                            } ?>
                    </table>
                <?php } ?>

                <?php //personality
                $rw = 0;
                for ($rw = 3; $rw <= 3; ++$rw) { ?>
                    <table class="table" style="margin-top:9px">
                        <tbody>
                            <?php if ($rw == 3) { ?>
                                <tr>
                                    <th colspan='7' style="text-align:center;background-color:#9ac86f;font-size: 14px;"> D.Personality Development </th>
                                </tr>
                            <?php } ?>
                            <tr class="bg">
                                <th class="wt"><?php echo $main_subjects[$rw]->name; ?></th>
                                <th class="evl" style="text-align:center">Evaluation 1</th>
                                <th class="evl" style="text-align:center">Evaluation 2</th>
                                <th class="evl" style="text-align:center">Evaluation 3</th>
                                <th class="evl" style="text-align:center">Evaluation 4</th>
                                <th class="evl" style="text-align:center">Evaluation 5</th>
                                <th class="evl" style="text-align:center">Evaluation 6</th>
                            </tr>
                            <?php
                            $subGroups = $main_subjects[$rw]->subGroups;
                            for ($rwg = 0; $rwg < sizeof($subGroups); ++$rwg) {
                                if ($subGroups[$rwg]->id != $main_subjects[$rw]->id) { ?>
                                    <tr class="bg">
                                        <th colspan="7" class="wt"><?php echo $subGroups[$rwg]->name; ?></th>
                                    </tr>
                                <?php }
                                $subSubjects = $subGroups[$rwg]->subSubjects;
                                for ($rws = 0; $rws < sizeof($subSubjects); ++$rws) {
                                    $ev1_result = $this->examresult_model->getFinalMark($student['student_session_id'], $examList[0]->exam_group_class_batch_exam_id, $subSubjects[$rws]->id);
                                    $ev2_result = $this->examresult_model->getFinalMark($student['student_session_id'], $examList[1]->exam_group_class_batch_exam_id, $subSubjects[$rws]->id);
                                    $ev3_result = $this->examresult_model->getFinalMark($student['student_session_id'], $examList[2]->exam_group_class_batch_exam_id, $subSubjects[$rws]->id);
                                    $ev4_result = $this->examresult_model->getFinalMark($student['student_session_id'], $examList[3]->exam_group_class_batch_exam_id, $subSubjects[$rws]->id);
                                    $ev5_result = $this->examresult_model->getFinalMark($student['student_session_id'], $examList[4]->exam_group_class_batch_exam_id, $subSubjects[$rws]->id);
                                    $ev6_result = $this->examresult_model->getFinalMark($student['student_session_id'], $examList[5]->exam_group_class_batch_exam_id, $subSubjects[$rws]->id);

                                ?>
                                    <tr>
                                        <td class="wt">> <?php echo $subSubjects[$rws]->name; ?></td>
                                        <td class="textct"><?php if (!empty($ev1_result)) {
                                                                echo $ev1_result['final_mark'];
                                                            } else {
                                                                echo "-";
                                                            }  ?></td>
                                        <td class="textct"><?php if (!empty($ev2_result)) {
                                                                echo $ev2_result['final_mark'];
                                                            } else {
                                                                echo "-";
                                                            }  ?></td>
                                        <td class="textct"><?php if (!empty($ev3_result)) {
                                                                echo $ev3_result['final_mark'];
                                                            } else {
                                                                echo "-";
                                                            }  ?></td>
                                        <td class="textct"><?php if (!empty($ev4_result)) {
                                                                echo $ev4_result['final_mark'];
                                                            } else {
                                                                echo "-";
                                                            }  ?></td>
                                        <td class="textct"><?php if (!empty($ev5_result)) {
                                                                echo $ev5_result['final_mark'];
                                                            } else {
                                                                echo "-";
                                                            }  ?></td>
                                        <td class="textct"><?php if (!empty($ev6_result)) {
                                                                echo $ev6_result['final_mark'];
                                                            } else {
                                                                echo "-";
                                                            }  ?></td>
                                    </tr>

                                    <!-- <tr>
                        <td class="wt"><b>Subject Grade </b></td>
                        <td class="textct"></td>
                        <td class="textct"></td>
                        <td class="textct"></td>
                        <td class="textct"></td>
                        <td class="textct"></td>
                        <td class="textct"></td>
                    </tr> -->
                        </tbody>
                <?php }
                            } ?>
                    </table>
                <?php } ?>

                <?php //gross motor skills
                $rw = 0;
                for ($rw = 7; $rw <= 7; ++$rw) { ?>
                    <table class="table" style="margin-top:9px">
                        <tbody>
                            <?php if ($rw == 7) { ?>
                                <tr>
                                    <th colspan='7' style="text-align:center;background-color:#9ac86f;font-size: 14px;"> E. Gross Motor Skill </th>
                                </tr>
                            <?php } ?>
                            <tr class="bg">
                                <th class="wt"><?php echo $main_subjects[$rw]->name; ?></th>
                                <th class="evl" style="text-align:center">Evaluation 1</th>
                                <th class="evl" style="text-align:center">Evaluation 2</th>
                                <th class="evl" style="text-align:center">Evaluation 3</th>
                                <th class="evl" style="text-align:center">Evaluation 4</th>
                                <th class="evl" style="text-align:center">Evaluation 5</th>
                                <th class="evl" style="text-align:center">Evaluation 6</th>
                            </tr>
                            <?php
                            $subGroups = $main_subjects[$rw]->subGroups;
                            for ($rwg = 0; $rwg < sizeof($subGroups); ++$rwg) {
                                if ($subGroups[$rwg]->id != $main_subjects[$rw]->id) { ?>
                                    <tr class="bg">
                                        <th colspan="7" class="wt"><?php echo $subGroups[$rwg]->name; ?></th>
                                    </tr>
                                <?php }
                                $subSubjects = $subGroups[$rwg]->subSubjects;
                                for ($rws = 0; $rws < sizeof($subSubjects); ++$rws) {
                                    $ev1_result = $this->examresult_model->getFinalMark($student['student_session_id'], $examList[0]->exam_group_class_batch_exam_id, $subSubjects[$rws]->id);
                                    $ev2_result = $this->examresult_model->getFinalMark($student['student_session_id'], $examList[1]->exam_group_class_batch_exam_id, $subSubjects[$rws]->id);
                                    $ev3_result = $this->examresult_model->getFinalMark($student['student_session_id'], $examList[2]->exam_group_class_batch_exam_id, $subSubjects[$rws]->id);
                                    $ev4_result = $this->examresult_model->getFinalMark($student['student_session_id'], $examList[3]->exam_group_class_batch_exam_id, $subSubjects[$rws]->id);
                                    $ev5_result = $this->examresult_model->getFinalMark($student['student_session_id'], $examList[4]->exam_group_class_batch_exam_id, $subSubjects[$rws]->id);
                                    $ev6_result = $this->examresult_model->getFinalMark($student['student_session_id'], $examList[5]->exam_group_class_batch_exam_id, $subSubjects[$rws]->id);

                                ?>
                                    <tr>
                                        <td class="wt">> <?php echo $subSubjects[$rws]->name; ?></td>
                                        <td class="textct"><?php if (!empty($ev1_result)) {
                                                                echo $ev1_result['final_mark'];
                                                            } else {
                                                                echo "-";
                                                            }  ?></td>
                                        <td class="textct"><?php if (!empty($ev2_result)) {
                                                                echo $ev2_result['final_mark'];
                                                            } else {
                                                                echo "-";
                                                            }  ?></td>
                                        <td class="textct"><?php if (!empty($ev3_result)) {
                                                                echo $ev3_result['final_mark'];
                                                            } else {
                                                                echo "-";
                                                            }  ?></td>
                                        <td class="textct"><?php if (!empty($ev4_result)) {
                                                                echo $ev4_result['final_mark'];
                                                            } else {
                                                                echo "-";
                                                            }  ?></td>
                                        <td class="textct"><?php if (!empty($ev5_result)) {
                                                                echo $ev5_result['final_mark'];
                                                            } else {
                                                                echo "-";
                                                            }  ?></td>
                                        <td class="textct"><?php if (!empty($ev6_result)) {
                                                                echo $ev6_result['final_mark'];
                                                            } else {
                                                                echo "-";
                                                            }  ?></td>
                                    </tr>

                                    <!-- <tr>
                        <td class="wt"><b>Subject Grade </b></td>
                        <td class="textct"></td>
                        <td class="textct"></td>
                        <td class="textct"></td>
                        <td class="textct"></td>
                        <td class="textct"></td>
                        <td class="textct"></td>
                    </tr> -->
                        </tbody>
                <?php }
                            } ?>
                    </table>
                <?php } ?>

                <?php //fine motor skills
                $rw = 0;
                for ($rw = 8; $rw <= 8; ++$rw) { ?>
                    <table class="table" style="margin-top:9px">
                        <tbody>
                            <?php if ($rw == 8) { ?>
                                <tr>
                                    <th colspan='7' style="text-align:center;background-color:#9ac86f;font-size: 14px;"> F. Fine Motor Skill </th>
                                </tr>
                            <?php } ?>
                            <tr class="bg">
                                <th class="wt"><?php echo $main_subjects[$rw]->name; ?></th>
                                <th class="evl" style="text-align:center">Evaluation 1</th>
                                <th class="evl" style="text-align:center">Evaluation 2</th>
                                <th class="evl" style="text-align:center">Evaluation 3</th>
                                <th class="evl" style="text-align:center">Evaluation 4</th>
                                <th class="evl" style="text-align:center">Evaluation 5</th>
                                <th class="evl" style="text-align:center">Evaluation 6</th>
                            </tr>
                            <?php
                            $subGroups = $main_subjects[$rw]->subGroups;
                            for ($rwg = 0; $rwg < sizeof($subGroups); ++$rwg) {
                                if ($subGroups[$rwg]->id != $main_subjects[$rw]->id) { ?>
                                    <tr class="bg">
                                        <th colspan="7" class="wt"><?php echo $subGroups[$rwg]->name; ?></th>
                                    </tr>
                                <?php }
                                $subSubjects = $subGroups[$rwg]->subSubjects;
                                for ($rws = 0; $rws < sizeof($subSubjects); ++$rws) {
                                    $ev1_result = $this->examresult_model->getFinalMark($student['student_session_id'], $examList[0]->exam_group_class_batch_exam_id, $subSubjects[$rws]->id);
                                    $ev2_result = $this->examresult_model->getFinalMark($student['student_session_id'], $examList[1]->exam_group_class_batch_exam_id, $subSubjects[$rws]->id);
                                    $ev3_result = $this->examresult_model->getFinalMark($student['student_session_id'], $examList[2]->exam_group_class_batch_exam_id, $subSubjects[$rws]->id);
                                    $ev4_result = $this->examresult_model->getFinalMark($student['student_session_id'], $examList[3]->exam_group_class_batch_exam_id, $subSubjects[$rws]->id);
                                    $ev5_result = $this->examresult_model->getFinalMark($student['student_session_id'], $examList[4]->exam_group_class_batch_exam_id, $subSubjects[$rws]->id);
                                    $ev6_result = $this->examresult_model->getFinalMark($student['student_session_id'], $examList[5]->exam_group_class_batch_exam_id, $subSubjects[$rws]->id);

                                ?>
                                    <tr>
                                        <td class="wt">> <?php echo $subSubjects[$rws]->name; ?></td>
                                        <td class="textct"><?php if (!empty($ev1_result)) {
                                                                echo $ev1_result['final_mark'];
                                                            } else {
                                                                echo "-";
                                                            }  ?></td>
                                        <td class="textct"><?php if (!empty($ev2_result)) {
                                                                echo $ev2_result['final_mark'];
                                                            } else {
                                                                echo "-";
                                                            }  ?></td>
                                        <td class="textct"><?php if (!empty($ev3_result)) {
                                                                echo $ev3_result['final_mark'];
                                                            } else {
                                                                echo "-";
                                                            }  ?></td>
                                        <td class="textct"><?php if (!empty($ev4_result)) {
                                                                echo $ev4_result['final_mark'];
                                                            } else {
                                                                echo "-";
                                                            }  ?></td>
                                        <td class="textct"><?php if (!empty($ev5_result)) {
                                                                echo $ev5_result['final_mark'];
                                                            } else {
                                                                echo "-";
                                                            }  ?></td>
                                        <td class="textct"><?php if (!empty($ev6_result)) {
                                                                echo $ev6_result['final_mark'];
                                                            } else {
                                                                echo "-";
                                                            }  ?></td>
                                    </tr>

                                    <!-- <tr>
                        <td class="wt"><b>Subject Grade </b></td>
                        <td class="textct"></td>
                        <td class="textct"></td>
                        <td class="textct"></td>
                        <td class="textct"></td>
                        <td class="textct"></td>
                        <td class="textct"></td>
                    </tr> -->
                        </tbody>
                <?php }
                            } ?>
                    </table>
                <?php } ?>


                <table class="table" style="margin-top:9px">
                    <tbody>

                        <tr class="bg">
                            <th colspan="4" class="wt" style="text-align:center;background-color:#9ac86f;font-size: 14px;">Guidelines for assessment of Graded Subjects (C,D,E,F)</th>
                        </tr>
                        <tr>
                            <td class="textct">A-Excellent</td>
                            <td class="textct">B-Good</td>
                            <td class="textct">C-Satisfactory</td>
                            <td class="textct">D-Needs Improvement</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <?php
        // $result = $this->
        ?>

        <div class="PageBrack" style="display:flex;align-items: center;">
            <div class="w-50">
                <table class="table" style="margin-top:5px">
                    <tbody>
                        <tr>
                            <th colspan='4' style="text-align:center;background-color: #9ac86f;font-size: 14px;"> Evaluation 1 General Remarks </th>
                        </tr>

                        <tr>
                            <td colspan='4' style="text-align:center;height: 22px;"><?php if (isset($remarks[0]['long_remarks'])) {
                                                                                        echo $remarks[0]['long_remarks'];
                                                                                    } ?></td>
                        </tr>

                        <tr>
                            <th colspan='4' style="text-align:center;background-color: #9ac86f;font-size: 14px;"> Evaluation 2 General Remarks </th>
                        </tr>
                        <tr>
                            <td colspan='4' style="text-align:center;height: 22px;"><?php if (isset($remarks[1]['long_remarks'])) {
                                                                                        echo $remarks[1]['long_remarks'];
                                                                                    } ?></td>
                        </tr>

                        <tr>
                            <th colspan='4' style="text-align:center;background-color: #9ac86f;font-size: 14px;"> Evaluation 3 General Remarks </th>
                        </tr>
                        <tr>
                            <td colspan='4' style="text-align:center;height: 22px;"><?php if (isset($remarks[2]['long_remarks'])) {
                                                                                        echo $remarks[2]['long_remarks'];
                                                                                    } ?></td>
                        </tr>

                        <tr>
                            <th colspan='4' style="text-align:center;background-color: #9ac86f;font-size: 14px;"> Evaluation 4 General Remarks </th>
                        </tr>

                        <tr>
                            <td colspan='4' style="text-align:center;height: 22px;"><?php if (isset($remarks[3]['long_remarks'])) {
                                                                                        echo $remarks[3]['long_remarks'];
                                                                                    } ?></td>
                        </tr>
                        <tr>
                            <th colspan='4' style="text-align:center;background-color: #9ac86f;font-size: 14px;"> Evaluation 5 General Remarks </th>
                        </tr>
                        <tr>
                            <td colspan='4' style="text-align:center;height: 22px;"><?php if (isset($remarks[4]['long_remarks'])) {
                                                                                        echo $remarks[4]['long_remarks'];
                                                                                    } ?></td>
                        </tr>

                        <tr>
                            <th colspan='4' style="text-align:center;background-color: #9ac86f;font-size: 14px;"> Evaluation 6 General Remarks </th>
                        </tr>
                        <tr>
                            <td colspan='4' style="text-align:center;"><?php if (isset($remarks[5]['long_remarks'])) {
                                                                            echo $remarks[5]['long_remarks'];
                                                                        } ?></td>
                        </tr>

                    </tbody>
                </table>
                <?php if($marksheet_partial != 1){?>
                <table class="table" style="margin-top:15px">
                    <tr>
                        <td style="width:60%">CONGRATULATION !!! PASSED & PROMOTED TO CLASS : </td>
                        <?php if($marksheet_partial == 1){?>
                            <td><span style="font-weight: 800;font-size: 9px;">  </span></td>
                        <?php }else{?>
                        <td><span style="font-weight: 800;font-size: 9px;"> <?php echo $nextclass; ?> </span></td>
                        <?php }?>
                    </tr>
                    <tr>
                        <td>NEW SESSION BEGINS ON : </td>
                        <?php if($marksheet_partial == 1){?>
                            <td><span style="font-weight: 800;font-size: 9px;">  </span></td>
                        <?php }else{?>
                        <td><span style="font-weight: 800;font-size: 9px;"> <?php echo date('d-m-Y', strtotime($new_session['start_date'])); ?> </span></td>
                        <?php }?>
                    </tr>
                </table>
                <?php }?>

                <table class="table" style="margin-top:15px">
                    <tbody>
                        <tr>
                            <th colspan='2' style="text-align:center;background-color: #9ac86f;font-size: 14px;"> Subject Grades </th>
                        </tr>
                        <tr>
                            <td>A1-Outstanding</td>
                            <td>C1-Average</td>
                        </tr>
                        <tr>
                            <td>A2-Excellent</td>
                            <td>C2-Fair</td>
                        </tr>
                        <tr>
                            <td>B1-Very Good</td>
                            <td>D-Needs Improvement</td>
                        </tr>
                        <tr>
                            <td>B2-Good</td>
                            <td>E-Need To Work Hard</td>
                        </tr>
                    </tbody>
                </table>
                
                <div style="text-align:center">
                    <img src="<?php echo base_url('uploads/gallery/allthebest.png') ?>" alt="" style="    width: 44%;">
                </div>
            </div>
            <div class="w-50 FBG" style="padding-top:2%;">
                <table style="width: 100%;">
                    <tbody>
                        <!-- <tr class="heading" style="border: 5px solid #0a4279; border-bottom: 0;"> -->
                        <tr class="heading" style="">
                            <td style="text-align: center;border: none;">
                                <img src="https://tezitservices.com/school/upload/banner/logo.png" alt="" style="width: 140px;">
                            </td>
                            <td style="border: none; padding-bottom: 17px;">
                                <div style="text-align: center;padding-top: 8px;">
                                    <h5 class="mb-0 smt">
                                        SREE NARAYANA MANDIRA SAMITI'S
                                    </h5>
                                    <h3 class="mb-0 sclnm"> SREE NARAYANA GURU CENTRAL SCHOOL (C.B.S.E.)</h3>
                                    <div style="justify-content: center;display: flex;">
                                        <p class="mb-0 add" style="width:73%">Sree Narayana Guru Nagar, P.L. Lokhande Marg, Chembur, Mumbai-400089<br>
                                            Email: sngcentralschool@gmail.com <br>Phone No.: 25263113
                                        </p>
                                    </div>
                                </div>
                                <!-- <div class="" style="display: flex;justify-content: space-between;">
                                <div style="width:65%;padding-left: 11px;">
                                    <p class="mb-0 add">Email: sngcentralschool@gmail.com</p>
                                    <p class="mb-0 add">UDISE NO.: 27230300512</p>
                                </div>
                                <div style="width:40%;">
                                    <p class="mb-0 add">Website: sngcentralschool.org</p>
                                    <p class="mb-0 add">Phone No.: 25263113</p>
                                </div>
                            </div> -->
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="bnone achive W50BG2">
                    <table style="width: 100%;">
                        <tr>
                            <td>
                                <div class="" style="text-align:center;width:100%;padding-left: 20px;">
                                    <h2>REPORT CARD</h2>

                                    <h4 style="font-size: 13px;font-family: sans-serif;color: #00008b;font-weight: 700;">Academic year: <b><?php echo $ay_year; ?></b></h4>
                                </div>
                            </td>
                            <!-- <td> -->
                            <!-- <img  style="height: 100px;width: 100px;border:2px solid #9ac86f" src="https://sngcentralschool.org/web/uploads/gallery/allthebest.png" alt=""> -->
                            <!-- </td> -->
                        </tr>
                    </table>
                    <div style="margin-top:15px">
                        <h3>STUDENT PROFILE</h3>
                        <table class="frontpage" style="width:100%;height: 114px;">
                            <tbody>
                                <tr>
                                    <th style="width:25%;">Name Of The Student</th>
                                    <td colspan="3">
                                        <div style="display:flex"><b style="font-size: 12px;"> :</b><span><?php echo $student['aadhar_name']; ?></span></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Grade</th>
                                    <td>
                                        <div style="display:flex"><b style="font-size: 12px;"> :</b><span><?php echo strtoupper($student['code']); ?></span></div>
                                    </td>
                                    <th style="width:10%;text-align:right">Section</th>
                                    <td>
                                        <div style="display:flex"><b style="font-size: 12px;"> :</b><span><?php echo $student['section']; ?></span></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Roll No</th>
                                    <td>
                                        <div style="display:flex"><b style="font-size: 12px;"> :</b><span><?php echo $student['roll_no']; ?></span></div>
                                    </td>
                                    <th style="text-align:right">Gr. No</th>
                                    <td>
                                        <div style="display:flex"><b style="font-size: 12px;"> :</b><span><?php echo $student['admission_no']; ?></span></div>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                    <!-- <div class="d-flex Address">
                        <p>Residential Address</p><div style="display:flex"><b style="font-size: 12px;"> :</b><span></span>
                    </div> -->
                    <div>
                        <table class="frontpage" style="width:100%;height: 138px;">
                            <tbody>
                                <tr>
                                    <th>Date Of Birth</th>
                                    <td colspan="3">
                                        <div style="display:flex"><b style="font-size: 12px;"> :</b><span><?php echo date('d-m-Y', strtotime($student['dob'])); ?></span></div>
                                        <?php // if (!empty($student['current_address'])) { 
                                        ?>
                                        <!-- <span style="width:100%;border-bottom: none;line-height: 22px;text-decoration: underline;text-underline-offset: 5px;"><?php echo $student['current_address']; ?></span> -->
                                        <?php //} else { 
                                        ?>
                                        <!-- <div style="display:flex"><b style="font-size: 12px;"> :</b><span></span></div> -->

                                        <?php //} 
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th style="width:25%;">Aadhaar Card No:</th>
                                    <td style="width:25%;">
                                        <div style="display:flex"><b style="font-size: 12px;"> :</b><span><?php echo $student['adhar_no']; ?></span></div>
                                    </td>
                                    <th style="width:20%;text-align:right">Mobile No.</th>
                                    <td>
                                        <div style="display:flex"><b style="font-size: 12px;"> :</b><span><?php echo $student['mobileno']; ?></span></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Fathers Name</th>
                                    <td colspan="3">
                                        <div style="display:flex"><b style="font-size: 12px;"> :</b><span><?php echo $student['father_name']; ?></span></div>
                                    </td>

                                </tr>
                                <tr>
                                    <th style="width:20%;">Mothers Name</th>
                                    <td colspan="3">
                                        <div style="display:flex"><b style="font-size: 12px;"> :</b><span><?php echo $student['mother_name']; ?></span></div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div style="margin-top:15px">
                        <h3>Health Status</h3>
                        <table class="frontpage" style="width:100%;height:84px;">
                            <tbody>
                                <tr>
                                    <th style="width:25%;">Height</th>
                                    <td>
                                        <div style="display:flex"><b style="font-size: 12px;"> :</b><span><?php echo $student_session['height'] . " cm"; ?></span></div>
                                    </td>
                                    <th style="width:20%;    text-align: right;">Weight</th>
                                    <td>
                                        <div style="display:flex"><b style="font-size: 12px;"> :</b><span><?php echo $student_session['weight'] . " kg"; ?></span></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Blood Group</th>
                                    <td>
                                        <div style="display:flex"><b style="font-size: 12px;"> :</b><span><?php echo $student['blood_group']; ?></span></div>
                                    </td>
                                    <th></th>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div style="margin-top:15px">
                        <h3>Attendance</h3>
                        <div class="frontpage">



                            <table style="width:100%;height:42px">
                                <tbody>
                                    <tr>
                                        <th style="width:30%;padding:0px">Total Attendance of The Student</th>
                                        <td style="width:36%;">
                                            <div style="display:flex"><b style="font-size: 12px;"> :</b><span><?php echo $student_session['student_att']; ?></span></div>
                                        </td>
                                        <th style="width: 22%;text-align: center;padding-left: 7px;">Total Working Days</th>
                                        <td>
                                            <div style="display:flex"><b style="font-size: 12px;"> :</b><span><?php if ($student_session['total_att'] > 0) {
                                                                                                                    echo $student_session['total_att'];
                                                                                                                } else {
                                                                                                                    echo $working_days['working_days'];
                                                                                                                } ?></span></div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <table style="width:100%;height:67px">
                                <tbody>
                                    <!-- <tr>
                                        <th>Name of the Class Teacher </th>
                                        <td><div style="display:flex"><b style="font-size: 12px;"> :</b><span>133</span></div></td>
                                        <th>Signature</th>
                                        <td style="width: 36%;"><span></span></td>
                                    </tr> -->
                                    <tr>
                                        <th style="width:30%;padding:0px;">Name of the Class Teacher</th>
                                        <td>
                                            <div style="display:flex"><b style="font-size: 12px;"> :</b><span style="font-size: 10px;"><?php echo $class_teacher; ?></span></div>
                                        </td>
                                        <th style="width:14%;text-align:center">Signature</th>
                                        <td style="width:20%;">
                                            <div style="display:flex"><b style="font-size: 12px;"> :</b><span></span></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th style="padding:0px;">Name of the Principal</th>
                                        <td>
                                            <div style="display:flex"><b style="font-size: 12px;"> :</b><span style="font-size: 10px;"><?php echo $principal[0]['name'] . " " . $principal[0]['middle_name'] . " " . $principal[0]['surname']; ?></span></div>
                                        </td>
                                        <th style="text-align:center">Signature</th>
                                        <td>
                                            <div style="display:flex"><b style="font-size: 12px;"> :</b><span></span></div>
                                        </td>
                                    </tr>
                                    <!-- <tr>
                                        <th style="width:30%;padding:0px">Signature Of The Principal</th>
                                        <td>
                                            <div style="display:flex"><b style="font-size: 12px;"> :</b><span></span></div>
                                        </td>
                                        <th></th>
                                        <td></td>
                                    </tr> -->

                                </tbody>
                            </table>
                            <table style="width:100%;height:42px">
                                <tbody>
                                    <tr>
                                        <th style="padding:0px;width:30%;">Signature Of Parents/Guardian</th>
                                        <td>
                                            <div style="display:flex"><b style="font-size: 12px;"> :</b><span></span></div>
                                        </td>
                                        <th style="width:14%;"></th>
                                        <td style="width:20%;"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
</body>

</html>
<script>
    // printDiv('body','landscape')
</script>