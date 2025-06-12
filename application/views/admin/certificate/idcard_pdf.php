<!doctype html>
<html lang="en">

<head>
    <title>Title</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous"> -->
</head>
<style>
    @page {
        width: 99%;
        height: 100%;
        margin: 0;
    }

    /* .id-card-holder {
            width: 45%;
            height: 98%;
            max-height: fit-content;
        } */


    body {
        background-color: #d7d6d3;
        font-family: 'verdana';
    }

    .id-card-holder:last-child{
        page-break-after: avoid;
    }

    .id-card-holder {
        width: 97%;
        padding: 4px;
        border-radius: 5px;
        page-break-after: always;
    }



    .id-card {

        background-color: #fff;
        border-radius: 10px;
        text-align: center;
        box-shadow: 0 0 1.5px 0px #b9b9b9;
    }

    .id-card img {
        margin: 0 auto;
    }

    .header img {
        width: 100px;
        margin-top: 15px;
    }

    .photo img {
        width: 70px;
        height: 70px;
        margin-top: 5px;
        margin-bottom: 5px;
    }

    h2 {
        font-size:8px;
        margin: 5px 0;
        font-weight: 700;
        color: #c31212;
        text-transform: uppercase;
    }

    h3 {
        font-size: 12px;
        margin: 2.5px 0;
        font-weight: 300;
    }

    .qr-code img {
        width: 50px;
    }

    p,span {
        font-size: 9px;
        margin: 0;
        color: #000;
    }


    table {
        border-collapse: collapse;
    }

    td,
    th {

        text-align: left;
        /* padding: 3px 5px; */
        font-weight: 600;
        text-transform: uppercase;
    }

    .smt {
        color: #9c1111;
        font-size: 11px;
        font-weight: 800;
        margin: 0px;
    }

    .sclnm {
        font-size: 17px;
        font-family: Algerian;
        color: darkblue;
        font-weight: 600;
    }

    .add {
        font-size: 7px;
        font-weight: 700;
        color: #000;
    }

    .idtable th {
        padding: 3px 2px;
        font-size: 7px;
        color: #000;
        width: 40%;
    }

    .idtable td {
        padding: 3px 2px;
        font-size: 8px;
        color: #000;
        display: flex;
    }

    .idtable tr {
        vertical-align: top;
    }

    .idtable p {
        margin: 0;
        padding: 0 2px;
    }
</style>

<body>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->

    <?php
    $school = $sch_setting[0];
    $i = 0;

    ?>
    <?php
    // if ($id_card[0]->enable_vertical_card) {
    // } else {

    ?>
    <?php
    foreach ($students as $student) {
        $i++;
    ?>
        <div class="id-card-holder" id="<?php echo $i; ?>">
            <div class="id-card" style="border: 2px solid #0a4279;">
                <table>
                    <tbody>
                        <tr class="heading" style="border-bottom: 0;">
                            <td style="text-align: center;border-right: 2px solid #0a4279; border-bottom: 2px solid #0a4279;">
                                <img src="<?php echo base_url('uploads/school_content/admin_small_logo/' . $school['admin_small_logo']); ?>" alt="" style="width:40px;">
                            </td>
                            <td style="border-bottom: 2px solid #0a4279;">
                                <div style="text-align:center">
                                    <h5 class="mb-0 smt">
                                        <?php echo $school['name'] ?>
                                    </h5>
                                    <!-- <h3 class="mb-0 sclnm"> <?php echo $schoolschool_name ?></h3> -->
                                    <!-- <p class="mb-0 add"><?php echo $school['address'] ?></p> -->
                                    <p class="mb-0 add">SREE NARAYANA NAGAR, CHEMBUR,<br> MUMBAI - 400 089</p>
                                </div>
                                <!-- <div class="" style="display: flex;justify-content: space-between;">
                                            <div style="width:70%">
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

                <div class="photo">
                    <img src="<?php
                                if (!empty($student->image)) {
                                    echo base_url() . $student->image;
                                } else {

                                    if ($student->gender == 'Female') {
                                        echo base_url() . "uploads/student_images/default_female.jpg";
                                    } elseif ($student->gender == 'Male') {
                                        echo base_url() . "uploads/student_images/default_male.jpg";
                                    }
                                }
                                ?>" >
                </div>
                <h2><?php echo $this->customlib->getFullName($student->firstname, $student->middlename, $student->lastname, $sch_settingdata->middlename, $sch_settingdata->lastname); ?></h2>
                <div style="height:110px">
                    <table class="idtable" style="width: 100%;">
                        <!--
                            <tr>
                                <td>Reg No:</td>
                                <td>:<?php //echo $student->admission_no; 
                                        ?></td>
                            </tr>
                            <tr>
                                <td>Student Id</td>
                                <td>:123</td>
                            </tr>
                                                    -->
                        <!-- <tr>
                                <th style="width: 25%;">Student Name</th>
                                <td>:<?php //echo $this->customlib->getFullName($student->firstname, $student->middlename, $student->lastname, $sch_settingdata->middlename, $sch_settingdata->lastname); 
                                        ?></td>
                            </tr> -->
                        <tr>
                            <th>Class</th>
                            <td>
                                <p><span>:</span> <?php echo $student->code . ' - ' . $student->section; ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th>Father/Guardian</th>
                            <td>
                                <p><span>:</span> <?php if ($student->father_name != '') {
                                            echo $student->father_name;
                                        } else {
                                            echo $student->guardian_name;
                                        } ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th>Address</th>
                            <td style=" font-size: 14px;">
                                <p style="font-size:8px;"> <span>:</span> <?php echo !empty($student->current_address) ? $student->current_address : $student->permanent_address; ?></p>
                            </td>
                        </tr>
                        <!-- <tr>
                                <th>Blood Group</th>
                                <td>: <?php echo $student->blood_group; ?></td>
                            </tr> -->
                        <tr>
                            <th>Contact</th>
                            <td>
                                <p><span>:</span> <?php echo $student->mobileno; ?></p>
                            </td>
                        </tr>
                    </table>
                </div>

                <div style="height:40px">

                
                <hr style="border: 1px solid #0a4279;" style="margin:4px 0 4px 0 ;">
                <p style="margin: 0;"> <strong>Website: <?php echo $sch_settingdata->website;
                                                        ?> </strong></p>
                <p style="margin: 0;"><strong>Phone No.: <?php echo $sch_settingdata->phone ?> </strong></p>
                </div>
                <!-- <p>Email: <?php //echo $sch_settingdata->email
                                ?></p> -->
                <!-- <br style="margin:2px;"> -->
            </div>
        </div>
    <?php //}
    } ?>
</body>

</html>