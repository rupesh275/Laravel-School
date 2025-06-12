<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SNG Central School</title>
    <link href="<?php echo base_url(); ?>uploads/school_content/admin_small_logo/<?php $this->setting_model->getAdminsmalllogo(); ?>" rel="shortcut icon" type="image/x-icon">
    <!-- <link href="https://fonts.cdnfonts.com/css/algeria" rel="stylesheet"> -->

    <!-- <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> -->
</head>
<style>
    @import url('<?php echo base_url('backend/dist/fonts/Algerian_Regular.ttf'); ?>');

    /* @font-face {
        font-family: "Algerian";
        src: url("https://fontsforyou.com/fonts/a/Algerian.ttf");
    } */

    @media print {

        .w-70 {
            width: 100%;
        }

        .w75 {
            width: 100%;
        }

        .p121 {
            padding-right: 0px !important;
        }

    }

    .annualp {
        margin-top: 5px;
        font-size: 17px;
        font-family: system-ui;
        font-style: italic;
        font-weight: 600;
    }

    .annual {
        font-size: 24px;
        font-family: algerian;
        font-style: italic;
        padding-bottom: 5px;
        color: #00008b;
        border-bottom: 3px solid;
        font-weight: bold;
        display: initial;
        padding-bottom: 0;
    }

    .p121 {
        padding-right: 121px;
    }

    .w75 {
        width: 75%;
    }

    .bt {
        border-top: 2px solid #0a4279;
    }

    .bb {
        border-bottom: 2px solid #0a4279;
    }

    .bl {
        border-left: 2px solid #0a4279;
    }

    .br {
        border-right: 2px solid #0a4279;
    }

    table {
        font-family: arial, sans-serif;
        border-collapse: collapse;
        width: 96%;
        font-size: 10px;
        /* justify-content: center; */
        /* display: flex; */
    }

    .plr--40 {
        padding: 0 15%;
    }

    .bnone h2 {
        font-size: 19px;
    }

    .bnone p {
        font-size: 20px;
    }

    .bnone table td {
        border: none;
        /* text-align: center; */
    }

    .bnone table th {
        border: none;
        /* font-family: initial;
        /* text-align: center; */
    }

    .w-50 {
        width: 99%;
        padding-top: 1%;
    }

    .bnone td,
    th {
        padding: 0 12px;
        border: none;
    }

    .bnone span {
        height: 20px;
        border-bottom: 1px solid #3c3838;
        width: 100%;
        display: block;
        font-size: 12px;
    }

    .border_g {
        border-width: 6px;
        padding-bottom: 17px;
        border: 5px solid #0a4279;
    }

    td,
    th {
        border: 2px solid #0a4279;
        text-align: left;
        padding: 2px 12px;
        /* font-size: 12px; */
    }

    .w-70 {
        width: 100%;
    }

    /* .wt {
        width: 18%;
    } */

    .detail th {
        font-size: 12px;
        padding-top: 20px;
    }

    .bg2 {
        background-color: #7ba3c6ba;
    }

    .bg {
        background-color: #739cc16e;
    }

    .mb-0 {
        margin: 0px;
    }

    .border-0 td {
        border: none;
    }

    .sclnm {
        font-size: 30px;
        font-family: Algerian;
        color: darkblue;
    }

    .smt {
        color: #9c1111;
        font-size: 17px;
        font-weight: 800;
    }

    .add {
        font-size: 17px;
        font-family: initial;
    }

    .heading {
        background-color: #7ba3c6ba;
        /* border: 1px solid;
        border-color: #746d6d;
        border-style: double;
        border-width: 3px; */
    }
</style>

<body>
    <?php foreach ($students_array as $student) {
        $student_session = $this->student_model->getByStudentSessionOnly($student['student_session_id']);
        $total_att_st = $this->attendencetype_model->getPresentAttendance($student['student_session_id']);
        $total_work_days_st = $this->attendencetype_model->getFullAttendance($student['student_session_id']);
        $total_work_days = 0;
        $total_att=0;
        if(!empty($total_att_st))
        {$total_att=$total_att_st['att'];}
        if(!empty($total_work_days_st))
        {$total_work_days=$total_work_days_st['att'];} 
        if ($total_work_days > 0) {
            
        } else {
            $total_work_days = $working_days['working_days'];
        }                 

        //echo "<pre>";
        //print_r($student_session);die();
    ?>

        <div class="height">

            <div>
                <div class="w-50" style="margin:12px 5px;">
                    <div class="border_g">


                        <div style="justify-content:center;display:flex;">

                            <table class="w-70 border-0">
                                <tr class="heading">
                                    <td style="text-align: center;width: 8%;">
                                        <img src="<?php echo base_url('upload/banner/logo.png') ?>" alt="" style="width: 105px;">
                                    </td>
                                    <td class="w75">
                                        <div class="p121">
                                            <div style="text-align:center">
                                                <h5 class="mb-0 smt">
                                                    SREE NARAYANA MANDIRA SAMITI’S
                                                </h5>
                                                <h3 class="mb-0 sclnm"> SREE NARAYANA GURU CENTRAL SCHOOL (C.B.S.E.)</h3>
                                                <p class="mb-0 add">Sree Narayana Nagar, P.L. Lokhande Marg, Chembur, Mumbai – 400089</p>
                                            </div>
                                            <div class="" style="display: flex;justify-content: space-between;">
                                                <div style="padding-left: 11px;">
                                                    <p class="mb-0 add">Email: sngcentralschool@gmail.com</p>
                                                    <p class="mb-0 add">UDISE NO.: 27230300512</p>
                                                </div>
                                                <div style="padding-right: 23px;">
                                                    <p class="mb-0 add">Website: sngcentralschool.org</p>
                                                    <p class="mb-0 add">Affiliation No. :<?php echo $sch_setting->affilation_no; ?></p>
                                                </div>
                                            </div>

                                        </div>
                                    </td>
                                    <!-- <td></td> -->
                                </tr>
                            </table>
                        </div>
                        <table class="bnone">
                            <tr>
                                <td style="border:none">
                                    <div class="" style="text-align:center;width:100%">
                                        <p class="annual" ><b>Annual Progress Report Card</b></p>
                                        <p class="annualp" style="font-size: 15px;margin-bottom: 0;">AY : <?php echo $ay_year; ?></p>
                                    </div>
                                </td>
                            </tr>
                        </table>
                        <?php //echo "<pre>";print_r($student);die; 
                        ?>

                        <table class="bnone detail" style="width: 100%;">
                            <tr>
                                <td style="font-size: 11px;font-weight: 600;text-transform:uppercase;width: 8%;"> 
                                    <b style="font-size: 11px;"> Name</b> </td>
                                <td style="font-size: 16px;font-weight: 600;">: <?php echo $student['aadhar_name']; ?></td>
                                <td style="font-size: 14px;font-weight: 600;text-transform:uppercase;width: 28%;"><b style="font-size: 11px;">Date Of Birth </b></td>
                                <td style="font-size: 11px;font-weight: 600;">: <?php echo date('d-m-Y', strtotime($student['dob'])); ?></td>
                                <td style="font-size: 14px;font-weight: 600;text-align:left;text-transform:uppercase;    width: 10%;"><b style="font-size: 11px;">Apaar Id</b></td>
                                <td style="font-size: 11px;font-weight: 600;    width: 17%;">: <?php echo $student['aapar_id']; ?></td>
                            </tr>
                            <tr>
                                <td style="font-size: 11px;font-weight: 600;text-transform:uppercase"><b style="font-size: 11px;">Class </b> </td>
                                <td style="font-size: 11px;font-weight: 600;">: <?php echo $student['code'] . '-' . $student['section']; ?></td>
                                <td  style="font-size: 14px;font-weight: 600;text-transform:uppercase"><b style="font-size: 11px;"> Mother's / Father's / Guardian's Name:</b></td>
                                <td style="font-size: 11px;font-weight: 600;">: <?php echo ($student['father_name']); ?> </td>
                                <td  style="font-size: 14px;font-weight: 600; text-align:left;text-transform:uppercase"><b style="font-size: 11px;">Pen No. </b>  </td>
                                <td style="font-size: 11px;font-weight: 600;">: <?php echo $student['uid_no']; ?></td>
                            </tr>
                            
                            <tr>
                                <td style="font-size: 14px;font-weight: 600;text-transform:uppercase"><b style="font-size: 11px;">Roll No.  </b> </td>
                                <td style="font-size: 11px;font-weight: 600;">: <?php echo $student['roll_no'];  ?></td>
                                <td style="font-size: 14px;font-weight: 600; text-align:left;text-transform:uppercase"><b style="font-size: 11px;">GR No.</b> </td>
                                <td style="font-size: 11px;font-weight: 600;">: <?php echo $student['admission_no']; ?></td>
                                <td style="font-size: 14px;font-weight: 600; text-align:left;text-transform:uppercase"><b style="font-size: 11px;">SARAL ID</b> </td>
                                <td style="font-size: 11px;font-weight: 600;">: <?php echo $student['dep_student_id']; ?></td>
                            </tr>
                            <!-- <tr>
                                <td colspan="2" style="font-size: 12px;font-weight: 600;text-transform:uppercase"><b style="font-size: 12px;">Aadhar No:</b> <?php echo $student['adhar_no']; ?></td>
                                <td style="font-size: 12px;font-weight: 600; text-align:left;text-transform:uppercase"><b style="font-size: 12px;">GR No.</b> <?php echo $student['admission_no']; ?></td>
                                <td colspan="3" style="font-size: 12px;font-weight: 600; text-align:right;text-transform:uppercase"></td>
                            </tr> -->
                            
                        </table>
                        <table class="table" style="margin-top:9px;border-bottom: 2px solid #0a4279;width: 100%;">
                            <tbody>
                                <tr class="bg2">
                                    <th class="bt" style="border-left: none;"></th>
                                    <th colspan='5' class="br bt" style="text-align:center;font-size: 11px;text-transform:uppercase;font-weight: 800;"> Term I (50 Marks)</th>
                                    <th colspan='5' class="bt br" style="text-align:center;font-size: 11px;text-transform:uppercase;font-weight: 800;"> Term II (50 Marks)</th>
                                    <th colspan='2' class="bt" style="text-align:center;font-size: 11px;width: 194px;border-right: none;text-transform:uppercase;font-weight: 800;"> Term I + Term II (100 Marks)</th>
                                </tr>
                                <tr class="bg">
                                    <th class="wt bb" style="font-weight: 800;border-left: none;text-transform:uppercase;font-size: 10px;">Subjects</th>
                                    <th class="bb" style="text-align:center;font-weight: 800;text-transform:uppercase;font-size:10px">FA-1 (10)</th>
                                    <th class="bb" style="text-align:center;font-weight: 800;text-transform:uppercase;font-size:10px">FA-2 (10)</th>
                                    <th class="bb" style="text-align:center;font-weight: 800;text-transform:uppercase;font-size:10px">SA-1 (30)</th>
                                    <th class="bb" style="text-align:center;font-weight: 800;text-transform:uppercase;font-size:10px">Total (50)</th>
                                    <th class="bb br" style="text-align:center;font-weight: 800;text-transform:uppercase;font-size:10px">Grade</th>
                                    <th class="bb" style="text-align:center;font-weight: 800;text-transform:uppercase;font-size:10px">FA-3 (10)</th>
                                    <th class="bb" style="text-align:center;font-weight: 800;text-transform:uppercase;font-size:10px">FA-4 (10)</th>
                                    <th class="bb" style="text-align:center;font-weight: 800;text-transform:uppercase;font-size:10px">SA-2 (30)</th>
                                    <th class="bb" style="text-align:center;font-weight: 800;text-transform:uppercase;font-size:10px">Total (50)</th>
                                    <th class="bb br" style="text-align:center;font-weight: 800;text-transform:uppercase;font-size:10px">Grade</th>
                                    <th class="bb" style="text-align:center;font-weight: 800;text-transform:uppercase;font-size:10px">Grand Total (100)</th>
                                    <th class="bb" style="text-align:center;font-weight: 800;border-right: none;text-transform:uppercase;font-size:10px">Grade</th>
                                </tr>
                                <tr>
                                </tr>
                                <?php
                                $grace=0;
                                foreach ($student['main_subject'] as $subjects) {
                                ?>
                                    <tr>
                                        <th class="wt bg" style="border-left: none;text-transform:uppercase;font-size: 11px;"><?php echo $subjects['name']; ?></th>
                                        <td style="font-weight: 700;font-size: 12px;text-align:center" class="wt"><?php echo $subjects['marks']['fa1_final'];if($subjects['marks']['fa1_grace']>0) { ?> <sup style="font-size: 8px; color: red;">★</sup> <?php } ?> </td>
                                        <td style="font-weight: 700;font-size: 12px;text-align:center" class="wt"><?php echo $subjects['marks']['fa2_final'];if($subjects['marks']['fa2_grace']>0) { ?> <sup style="font-size: 8px; color: red;">★</sup> <?php } ?>  </td>
                                        <td style="font-weight: 700;font-size: 12px;text-align:center" class="wt"><?php echo $subjects['marks']['sa1_final'];if($subjects['marks']['sa1_grace']>0) { ?> <sup style="font-size: 8px; color: red;">★</sup> <?php } ?>  </td>
                                        <td style="font-weight: 700;font-size: 12px;text-align:center" class="wt"><?php echo $subjects['marks']['t1'] ?></td>
                                        <td style="font-weight: 700;font-size: 12px;text-align:center" class="wt br"><?php echo $subjects['marks']['t1grade'] ?></td>
                                        <td style="font-weight: 700;font-size: 12px;text-align:center" class="wt"><?php echo $subjects['marks']['fa3_final'];if($subjects['marks']['fa3_grace']>0) { ?> <sup style="font-size: 8px; color: red;">★</sup> <?php } ?>  </td>
                                        <td style="font-weight: 700;font-size: 12px;text-align:center" class="wt"><?php echo $subjects['marks']['fa4_final'];if($subjects['marks']['fa4_grace']>0) { ?> <sup style="font-size: 8px; color: red;">★</sup> <?php } ?>  </td>
                                        <td style="font-weight: 700;font-size: 12px;text-align:center" class="wt"><?php echo $subjects['marks']['sa2_final'];if($subjects['marks']['sa2_grace']>0) { ?> <sup style="font-size: 8px; color: red;">★</sup> <?php } ?>  </td>
                                        <td style="font-weight: 700;font-size: 12px;text-align:center" class="wt"><?php echo $subjects['marks']['t2'] ?></td>
                                        <td style="font-weight: 700;font-size: 12px;text-align:center" class="wt br"><?php echo $subjects['marks']['t2grade'] ?></td>
                                        <td class="wt" style="font-weight: 700;font-size: 12px;text-align:center"><?php echo $subjects['marks']['total'];if($subjects['marks']['total_grace']>0) { $grace+=$subjects['marks']['total_grace']; ?> <sup style="font-size: 8px; color: red;">★</sup> <?php } ?> </td>
                                        <td class="wt" style="font-weight: 700;font-size: 12px;text-align:center;border-right: none;"><?php echo $subjects['marks']['grade'] ?></td>
                                    </tr>

                                <?php

                             } ?>
                            </tbody>
                        </table>
                        <p style="margin: 2px 0 0 5px; font-size: 10px; font-weight: 600; font-family: sans-serif;"><b style="font-size: 16px; color: red;">*</b>  Indicates Grace Marks</p>
                        <div style="padding-top: 10px; display: flex;padding-bottom: 10px;justify-content: space-between;width: 100%;">
                            <table style="width: 39%; border-bottom: 2px solid #0a4279; margin-left: 9%;">
                                <tr class="bg2">
                                    <th colspan="3" style="text-align:center;font-size:13px;text-transform:uppercase;color: #000;font-weight: 800;" class="bt bl br"> GRADED SUBJECTS</th>
                                </tr>
                                <tr class="bg">
                                    <th class="bl bb" style="font-weight: 800;width:48%;font-size: 12px;">SUBJECTS</th>
                                    <th class="bb" style="text-align:center;font-weight: 800;font-size: 12px;">TERM I</th>
                                    <th class="bb br" style="text-align:center;font-weight: 800;font-size: 12px;">TERM II</th>
                                </tr>
                                <?php
                                foreach ($student['grade_subject'] as $subjects) {
                                ?>
                                    <tr>
                                        <?php if ($subjects->name == "Art Integrated Project") { ?>
                                            <th class="bg bl" style="text-transform: uppercase;font-size: 11px;" ><?php echo $subjects->name; ?></th>
                                            <td colspan="2" style="text-align:center;text-transform: Capitalize;font-size: 12px;    font-weight: 600;" class="br"><?php echo $subjects->marks['t2']; ?></td>
                                            <!-- <td class="br" style="text-align:center"><?php echo $subjects->marks['t2']; ?></td> -->
                                        <?php } else { ?>
                                            <th class="bg bl" style="text-transform: uppercase;font-size: 11px;"><?php echo $subjects->name; ?></th>
                                            <td style="text-align:center;text-transform: Capitalize;font-size: 12px;    font-weight: 600;"> <?php echo $subjects->marks['t1']; ?> <span style="font-size: 20px;line-height: 1; vertical-align: middle;color: red;"><?php if($subjects->marks['t1_grace_mark'] > 0){ $grace+=$subjects->marks['t1_grace_mark']; ?>*<?php } ?> </span></td>
                                            <td class="br" style="text-align:center;text-transform: Capitalize;font-size: 12px;    font-weight: 600;"><?php echo $subjects->marks['t2']; ?> <span style="font-size: 20px;line-height: 1; vertical-align: middle;color: red;"><?php if($subjects->marks['t2_grace_mark'] > 0){ $grace+=$subjects->marks['t2_grace_mark']; ?>*<?php } ?></span></td>
                                        <?php } ?>
                                    </tr>
                                <?php } ?>
                                <tr>
                                <th class="bg bl" style="text-transform: uppercase;font-size: 11px;"><?php echo "ART INTEGRATED PROJECT"; ?></th>
                                <td colspan="2" style="text-align:center;text-transform: Capitalize;font-size: 12px;    font-weight: 600;" style="text-align:center"><?php echo $student['aip_grade']; ?></td>
                                <!-- <td class="br" style="text-align:center"></td> -->
                                </tr>                                
                            </table>
                            <div style="width: 31%;margin-right: 9%;">
                                <table style="margin-bottom:0px;width: 100%;border-bottom: 2px solid #0a4279;">
                                    <tr>
                                        <th class="bg bl bt" style="width: 69%;height: 21px;font-size: 12px;">Attendance</th>
                                        <td class=" bt" style="text-align:center;font-size: 12px;"><?php ///if($student_session['total_att'] > 0)  { echo $student_session['student_att'] . '/' . $student_session['total_att']; } else { echo $student_session['student_att'] . '/' . $working_days['working_days'];
                                        echo $total_att . ' / ' . $total_work_days;
                                         ?></td>
                                    </tr>
                                    <tr>
                                        <th class="bg bl" style="width:69%;height: 21px;font-size: 12px;font-weight: 800;">Percentage</th>
                                        <td class="" style="text-align:center;font-size: 12px;color: #0a4279;font-weight: 800;"><?php echo $student_session['percentage'];  ?></td>
                                        <!-- <td></td> -->
                                    </tr>
                                    <tr>
                                        <th class="bg bl" style="width: 69%;height: 21px;font-size: 12px;font-weight: 800;">Grade</th>
                                        <td class="" style="text-align:center;font-size: 12px;color: #0a4279;font-weight: 800;"><?php echo $student_session['grade'];  ?></td>
                                    </tr>
                                    <tr>
                                        <th class="bg bl" style="width: 69%;height: 21px;font-size: 12px;">Class Teacher's Remarks</th>
                                        <td class="" style="text-align:center;font-size: 12px;"><?php echo $student_session['remark'];  ?></td>
                                    </tr>
                                    <tr>
                                        <th class="bg bl" style="width: 69%;height: 21px;line-height: 20px;font-size: 12px;">
                                            <?php if ($student_session['grade'] == "E1" || $student_session['grade'] == "E2" || $grace > 0 ) { ?>
                                                Condoned and Promoted to class
                                            <?php } else { ?>
                                                Congratulations !!! Passed & Promoted to class
                                            <?php }
                                                $grace=0;
                                            ?>
                                        </th>
                                        <td class="" style="text-align:center;font-size: 12px;"><?php echo $nextclass['code']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="bg bl" style="width: 69%;height: 21px;font-size: 12px;">New session begins on </th>
                                        <?php if($marksheet_partial == 1){?>
                                            <td class="" style="text-align:center"></span></td>
                                            <?php } else { ?>
                                            <td class="" style="text-align:center;font-size: 12px;"><?php echo date('d-m-Y', strtotime($new_session['start_date'])); ?></span></td>
                                        <?php } ?>
                                    </tr>
                                </table>
                            </div>

                        </div>
                        <!-- <br> -->
                        <table class="bnone detail">
                            <tr>
                                <td style="width:142px;padding-right: 0;"><b style="font-size: 12px;width: 22%;">Class Teacher's Name:</b> </td>
                                <td style="font-size: 12px;width: 20%;"><?php echo $class_teacher; ?> </td>
                                <td style="width:20%"><b style="font-size: 12px;">Class Teacher's Signature:</b></td>
                                <td> </td>
                                <td style="text-align:right;width:6%;"><b style="font-size: 12px;"> Date :</b></td>
                                <td style="font-size: 12px;width:12%;"><?php echo date('d-m-Y', strtotime($section_result_date['result_date'])); ?> </td>
                                <td style="text-align:right;    width: 11%;"><b style="font-size: 12px;">School Seal :</b></td>
                                <td> </td>
                            </tr>
                        </table>
                        <br>
                        <table class="bnone detail">
                            <tr>
                                <td style="width:142px;padding-right: 0;"><b style="font-size: 12px;">Principal's Name:</b></td>
                                <td style="font-size: 12px;width: 20%;"> <?php echo $principal[0]['name'] . " " . $principal[0]['middle_name'] . " " . $principal[0]['surname']; ?></td>
                                <td style="width:25%"><b style="font-size: 12px;">Principal's Signature:</b></td>
                                <td> </td>

                            </tr>
                        </table>
                    </div>
                </div>
                <div class="w-50" style="margin:12px 5px;">
                    <div class="border_g" style="margin-top:22px">
                        <div style="font-size: 19px;font-family: initial;text-align:center;margin-top:141px;" class="bnone">
                            <p class="annual" style="font-size:17px"><b>Grading Scale</b></p>

                            <p><b>Scholastic Areas (on a 9-Points Grading Scale)</b></p>
                        </div>
                        <div class="" style="justify-content:center;display:flex;width:100%;margin-bottom: 50px;">



                            <table style="width: 32%; border-bottom: 2px solid #0a4279;">
                                <tbody>
                                    <tr class="bg2">
                                        <th style="text-align:center" class="bt bl br"> Grade Name</th>
                                        <th style="text-align:center" class="bt bl br"> Percent From / Upto</th>
                                        <th style="text-align:center" class="bt bl br"> Grade Remark</th>
                                    </tr>

                                    <tr>
                                        <th style="text-align:center" class="bg bl">A1</th>
                                        <td style="text-align:center"> 91.00 To 100.00</td>
                                        <td class="br" style="text-align:center">Exceptional</td>

                                    </tr>
                                    <tr>
                                        <th style="text-align:center" class="bg bl">A2</th>
                                        <td style="text-align:center"> 81.00 To 90.00</td>
                                        <td class="br" style="text-align:center">Excellent</td>

                                    </tr>
                                    <tr>
                                        <th style="text-align:center" class="bg bl">B1</th>
                                        <td style="text-align:center"> 71.00 To 80.00</td>
                                        <td class="br" style="text-align:center">Very Good</td>

                                    </tr>
                                    <tr>
                                        <th style="text-align:center" class="bg bl">B2</th>
                                        <td style="text-align:center"> 61.00 To 70.00</td>
                                        <td class="br" style="text-align:center">Good</td>

                                    </tr>
                                    <tr>
                                        <th style="text-align:center" class="bg bl">C1</th>
                                        <td style="text-align:center"> 51.00 To 60.00</td>
                                        <td class="br" style="text-align:center">Fair</td>

                                    </tr>
                                    <tr>
                                        <th style="text-align:center" class="bg bl">C2</th>
                                        <td style="text-align:center"> 41.00 To 50.00</td>
                                        <td class="br" style="text-align:center">Average</td>

                                    </tr>
                                    <tr>
                                        <th style="text-align:center" class="bg bl">D</th>
                                        <td style="text-align:center" class="br">33.00 To 40.00</td>
                                        <td class="br" style="text-align:center">Below Average </td>
                                    </tr>
                                    <tr>
                                        <th style="text-align:center" class="bg bl">E1</th>
                                        <td style="text-align:center" class="br"> 21.00 To 32.00</td>
                                        <td class="br" style="text-align:center">Needs Improvement </td>
                                    </tr>
                                    <tr>
                                        <th style="text-align:center" class="bg bl">E2</th>
                                        <td style="text-align:center" class="br">0.00 To 20.00</td>
                                        <td class="br" style="text-align:center">Unsatisfactory </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div style="font-size: 19px;font-family: initial;text-align:center;margin-top: 50px;" class="bnone ">
                            <p><b>Co-Scholastic Areas (on a 5-Points Grading Scale)</b></p>
                        </div>

                        <div class="" style="justify-content:center;display:flex;width:100%;margin-bottom:100px;">
                            <table style="width: 32%; border-bottom: 2px solid #0a4279;">
                                <tbody>
                                    <tr class="bg2">
                                        <th style="text-align:center" class="bt bl br"> Grade Name</th>
                                        <th style="text-align:center" class="bt bl br"> Percent From / Upto</th>
                                        <th style="text-align:center" class="bt bl br"> Grade Remark</th>
                                    </tr>
                                    <tr>
                                        <th style="text-align:center" class="bg bl">A</th>
                                        <td style="text-align:center" class="br">90.00 To 100.00</td>
                                        <td class="br" style="text-align:center">Outstanding </td>
                                    </tr>
                                    <tr>
                                        <th style="text-align:center" class="bg bl">B</th>
                                        <td style="text-align:center" class="br">75.00 To 89.00</td>
                                        <td class="br" style="text-align:center">Excellent </td>
                                    </tr>
                                    <tr>
                                        <th style="text-align:center" class="bg bl">C</th>
                                        <td style="text-align:center" class="br">56.00 To 74.00</td>
                                        <td class="br" style="text-align:center">Very Good </td>
                                    </tr>
                                    <tr>
                                        <th style="text-align:center" class="bg bl">D</th>
                                        <td style="text-align:center" class="br">35.00 To 55.00</td>
                                        <td class="br" style="text-align:center">Good </td>
                                    </tr>
                                    <tr>
                                        <th style="text-align:center" class="bg bl">E</th>
                                        <td style="text-align:center" class="br">0.00 To 34.00</td>
                                        <td class="br" style="text-align:center">Scope for improvement </td>
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