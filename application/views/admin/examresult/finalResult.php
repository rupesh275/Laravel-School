<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SNG Central School</title>
    <link href="<?php echo base_url(); ?>uploads/school_content/admin_small_logo/<?php $this->setting_model->getAdminsmalllogo(); ?>" rel="shortcut icon" type="image/x-icon">
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/bootstrap/css/bootstrap.min.css">

    <!-- include jquery js -->
    <script src="<?php echo base_url(); ?>backend/custom/jquery.min.js"></script>
    <!-- Then include bootstrap js -->
    <script src="<?php echo base_url(); ?>backend/bootstrap/js/bootstrap.min.js"></script>

</head>
<style>
    table {
        font-family: arial, sans-serif;
        border-collapse: collapse;
        width: 96%;
        font-size: 9px;
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
        font-size: 14px;
    }

    .bnone table td {
        border: none;
        /* text-align: center; */
    }

    .bnone table th {
        border: none;
        /* text-align: center; */
    }

    .w-50 {
        width: 50%;
        padding: 0 5%;
    }

    .bnone span {
        height: 28px;
        border-bottom: 1px solid #3c3838;
        width: 100%;
        display: block;
    }

    td,
    th {
        border: 1px solid #9f9c9c;
        text-align: left;
        padding: 3px;
    }

    .wt {
        width: 60%;
    }

    .detail th {
        font-size: 13px;
        padding-top: 20px;
    }

    .bg {
        background-color: #e3dede;
    }
</style>

<!-- <body onload="window.print()"> -->

<body>
    <div class="container ">
        <div class="row">
            <div class="col-md-6" style="">


                <?php
                $i = 0;
                $k = 0;

                foreach ($students_array as $rowone) {

                    $examstudentArray = $this->examgroup_model->get_id('exam_group_class_batch_exam_students', 'id', $rowone)->row_array();
                    $student_id = $examstudentArray['student_id'];
                    $exam_id = $post_exam_id;
                    $studentRow = $this->examgroup_model->get_id('students', 'id', $student_id)->row_array();
                    // print_r($rowone);
                    $classRow = $this->examgroup_model->get_id('student_session', 'student_id', $student_id)->row_array();


                    $subjects = $this->subject_model->getsubjectbyidgroup($exam_id)->result_array();
                    $subjects2 = $this->subject_model->getsubjectbyid2($exam_id)->result_array();
                    // echo "<pre>";
                    //  print_r($subjects);
                    $subjects_idsArray = array_column($subjects2, 'subject_id');
                ?>
                    <h5 class="text-center"><?php echo $studentRow['firstname'] . " " . $studentRow['middlename'] . " " . $studentRow['lastname']; ?></h5>
                    <?php
                    $countsub =  count($subjects);
                    $divsub = count($subjects) / 2;
                    $roundsub = round($divsub);
                    $j = 0;

                    if (!empty($subjects)) {
                        foreach ($subjects as  $subjectRow) {
                            $j++;
                            if ($j == 1) { ?>
                                <table>
                                    <tbody>
                                    <?php }
                                $subject_name = $this->subject_model->get($subjectRow['main_sub']);

                                    ?>

                                    <tr>
                                        <th colspan='4' style="text-align:center"> <?php echo $subject_name['name']; ?> </th>
                                    </tr>

                                    <tr class="bg">
                                        <th class="wt"></th>
                                        <th>Evaluation 1</th>
                                        <th>Evaluation 2</th>
                                        <th>Evaluation 3</th>
                                    </tr>
                                    <?php
                                    $Subsubjects = $this->subject_model->getsubjectbyexamid($exam_id, $subject_name['id'])->result_array();
                                    $data_one = [];
                                    $i = 0;
                                    if (!empty($Subsubjects)) {
                                        foreach ($Subsubjects as  $SubsubjectRow) {
                                            $subSubject_name = $this->subject_model->get_two($SubsubjectRow['subject_id']);
                                            $data = [];
                                            $data['id'] = $subSubject_name['id'];
                                            $data['name'] = $subSubject_name['name'];
                                            $data['parent_id'] = $subSubject_name['parent_id'];
                                            $data_one[$subject_name['id']][] = $data;

                                    ?>
                                            <?php $i++;
                                        }
                                    }
                                    foreach ($data_one as $subRow) {
                                        $parent = [];
                                        $parentCollect = [];
                                        foreach ($subRow as $subRowOne) {
                                            // echo "<pre>";
                                            // print_r($subRowOne);

                                            if ($subRowOne['parent_id'] == $subject_name['id']) {
                                                $marks = $this->examgroup_model->getmarks($rowone, $subRowOne['id']);
                                            ?>

                                                <tr>
                                                    <th class="wt"> <?php echo $subRowOne['name']; ?></th>
                                                    <td><?php echo (!empty($marks['get_marks'])) ? $marks['get_marks'] : ""; ?></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <?php
                                            } else {
                                                if (!in_array($subRowOne['parent_id'], $parent)) {
                                                    $get_three_row = $this->subject_model->get_three($subRowOne['parent_id']);

                                                ?>
                                                    <tr>
                                                        <th class="wt bg" colspan="4"> <?php echo $get_three_row['name']; ?></th>

                                                    </tr>

                                                <?php
                                                }
                                                $marks = $this->examgroup_model->getmarks($rowone, $subRowOne['id']);
                                                ?>
                                                <tr>
                                                    <th class="wt"><?php echo $subRowOne['name']; ?></th>
                                                    <td><?php echo (!empty($marks['get_marks'])) ? $marks['get_marks'] : ""; ?></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                    <?php
                                            }

                                            $parentCollect[] = $subRowOne['parent_id'];
                                            $parent = $parentCollect;
                                        }
                                    }
                                    ?>
                                    <?php $i++;
                                    if ($j == $countsub) { ?>
                                    </tbody>
                                </table>
            </div>
            <div class="col-md-6 mt-5">
            <?php } ?>

            <?php
                            if ($j == $roundsub) {
                                $j = 0; ?>
                </tbody>
                </table>
            </div>
            <div class="col-md-6 mt-3">
            <?php } ?>

<?php }
                    }
                }
?>


            </div>
            <div class="w-50">
            <div class="bnone">
                <table class="">

                    <tr>
                        <td></td>
                        <td>
                            <div class="" style="text-align:center;width:100%;">
                                <h2>ACHIVEMENT RECORD</h2>
                                <!-- <h4 style="font-size:14px;">Academic year: <b>2014 to 2015</b></h4> -->
                                <p></p>
                            </div>
                        </td>

                    </tr>
                </table>
                <div>
                    <table class="bnone detail">
                        <tr>
                            <th style='width:30%'>Name</th>
                            <td><span></span></td>
                        </tr>
                        <tr>
                            <th style='width:30%'>Class</th>
                            <td><span></span></td>
                        </tr>
                        <tr>
                            <th style='width:30%'>Section</th>
                            <td><span></span></td>
                        </tr>
                        <tr>
                            <th style='width:30%'>Date Of Birth</th>
                            <td><span></span></td>
                        </tr>
                        <tr>
                            <th style='width:30%'>Admn No.</th>
                            <td><span></span></td>
                        </tr>
                        <tr>
                            <th style='width:30%'>Residential Address</th>
                            <td><span></span></td>
                        </tr>
                        <tr>
                            <th style='width:30%'></th>
                            <td><span></span></td>
                        </tr>
                        <tr>
                            <th style='width:30%'></th>
                            <td><span></span></td>
                        </tr>
                        <tr>
                            <th style='width:30%'>Telephone No.</th>
                            <td><span></span></td>
                        </tr>
                        <tr>
                            <th style='width:30%'>Specimen Signature Of Parent/Guardian</th>
                            <td><span></span></td>
                        </tr>
                    </table>


                </div>
            </div>
        </div>
        </div>
</body>

</html>