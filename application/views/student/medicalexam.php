<!doctype html>
<html lang="en">

<head>
    <title><?php echo $this->customlib->getAppName(); ?></title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Charm&family=Montserrat:wght@200;400;600&display=swap" rel="stylesheet">

</head>
<style>
    body {
        /* background-color: #d7d6d3; */
        /* font-family: ; */
    }

    .card-holder {
        width: 100%;
        padding: 4px;
        margin: 0 auto;
        /* background-color: #1f1f1f; */
        border-radius: 5px;
        position: relative;
    }



    .id-card {

        background-color: #fff;
        border-radius: 10px;
        text-align: center;
        /* box-shadow: 0 0 1.5px 0px #b9b9b9; */
    }

    .id-card img {
        margin: 0 20px 0 0;
    }

    .header img {
        width: 100px;
        margin-top: 15px;
    }

    .photo {
        justify-content: end;
        display: flex;

    }

    .photo img {
        width: 155px;
        margin-top: 15px;
    }

    h2 {
        font-size: 31px;
        margin: 5px 0;
        border-bottom: 3px solid #940a06;
        padding: 10px 20px;
    }



    .qr-code img {
        width: 50px;
    }

    p {
        font-size: 9px;
        margin: 2px;
    }

    .id-card-hook {
        background-color: #000;
        width: 70px;
        margin: 0 auto;
        height: 15px;
        border-radius: 5px 5px 0 0;
    }

    .id-card-hook:after {
        content: '';
        background-color: #d7d6d3;
        width: 47px;
        height: 6px;
        display: block;
        margin: 0px auto;
        position: relative;
        top: 6px;
        border-radius: 4px;
    }

    .id-card-tag-strip {
        width: 45px;
        height: 40px;
        background-color: #0950ef;
        margin: 0 auto;
        border-radius: 5px;
        position: relative;
        top: 9px;
        z-index: 1;
        border: 1px solid #0041ad;
    }

    .id-card-tag-strip:after {
        content: '';
        display: block;
        width: 100%;
        height: 1px;
        background-color: #c1c1c1;
        position: relative;
        top: 10px;
    }

    .id-card-tag {
        width: 0;
        height: 0;
        border-left: 100px solid transparent;
        border-right: 100px solid transparent;
        border-top: 100px solid #0958db;
        margin: -10px auto -30px auto;
    }

    .id-card-tag:after {
        content: '';
        display: block;
        width: 0;
        height: 0;
        border-left: 50px solid transparent;
        border-right: 50px solid transparent;
        border-top: 100px solid #d7d6d3;
        margin: -10px auto -30px auto;
        position: relative;
        top: -130px;
        left: -50px;
    }

    td,
    th {
        text-align: left;
        padding: 4px 5px;
        font-size: 17px;
        font-family: 'Montserrat', sans-serif;
        font-weight: 500;
    }

    .smt {
        color: #9c1111;
        font-size: 8px;
        font-weight: 800;
    }

    .sclnm {
        font-size: 8px;
        font-family: Algerian;
        color: darkblue;
    }

    .add {
        font-size: 10px;
        font-family: initial;
        line-height: 9px;
    }

    h4 {
        font-size: 19px;
        font-weight: bold;
        display: flex;
        text-transform: uppercase;
        color: #7b0d0d;
        font-family: inherit;
    }

    .idtable td {
        padding: 0 20px;
    }

    .text-uppercase {
        text-transform: uppercase;
    }

    .manage {
        font-family: sans-serif;
        font-weight: 600;
        font-size: 17px;
        color: #f34d6a;
    }

    .addr {
        margin-top: 0;
        margin-bottom: 1rem;
        font-family: unset;
        font-weight: 500;
        font-size: 21px;
    }

    .Heading {
        font-family: 'Exo 2', sans-serif;
        color: #c10b0b;
        font-size: 29px;
        font-weight: 700;
    }

    .w80 {
        margin-bottom: 25px;
        height: 184px;
        border-bottom: 3px solid #910b0b;
        height: 218px;
    }
</style>

<body>
    <?php 
    // echo "<pre>";
    // print_r ($sch_setting);
    // echo "</pre>";
    ?>
    <div class="card-holder">
        <div class="w80">
            <table style="width:100%">
                <td style="text-align:center">
                    <img src="<?php echo base_url('uploads/school_content/admin_small_logo/'.$sch_setting->admin_logo);?>" style="width:150px;" alt="">
                </td>
                <td style="text-align:center">
                    <div class="Heading text-uppercase"><?php echo $sch_setting->name; ?></div>
                    <p class="manage mb-0"> Managed by : SREE NARAYANA MANDIR SAMITI (Regd)</p>
                    <p class="addr"><?php echo $sch_setting->address; ?></p>
                </td>
                <td>
                </td>
            </table>

        </div>
        <div style="display:flex;justify-content:center">
            <h2>Health Card</h2>
        </div>
        <div class="id-card">
            <!-- <table>
                <tbody>
                    <tr class="heading" style="border-bottom: 0;">
                        <td style="text-align: center;border: 2px solid #0a4279;">
                            <img src="https://tezitservices.com/school/upload/banner/logo.png" alt="" style="width: 60px;">
                        </td>
                        <td style="border: 2px solid #0a4279;">
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
                </tbody>
            </table> -->
            <div style="padding:0 50px">
                <div style="display:flex;justify-content:space-between;padding-top:40px">
                    <div style="width: 50%;">
                        <h4>Personal Particulars:</h4>
                        <table style="width: 100%;">
                            <tr>
                                <td style="width: 50%;">
                                    Name Of The Student:
                                </td>
                                <td>
                                    : <?php echo $student['firstname'] . " " . $student['middlename'] . " " . $student['lastname']; ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Class</td>
                                <td>: <?php echo $student['class'] . " " . $student['section']; ?></td>
                                </td>
                            </tr>
                            <tr>
                                <td>Roll No</td>
                                <td>: <?php echo $student['roll_no']; ?></td>
                            </tr>
                            <tr>
                                <td>Sex</td>
                                <td>: <?php echo $student['gender']; ?></td>
                            </tr>
                            <tr>
                                <?php
                                $date = $student['dob'];
                                $age = date('Y') - date('Y', strtotime($date));
                                ?>
                                <td>Age</td>
                                <td>: <?php echo $age; ?> Years</td>
                            </tr>
                            <tr>
                                <td>Blood Group</td>
                                <td>: <?php echo $student['blood_group']; ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="photo" style="display:flex;justify-content:end;">
                        <img src="<?php
                                    if (!empty($student["image"])) {
                                        echo base_url() . $student["image"];
                                    } else {

                                        if ($student['gender'] == 'Female') {
                                            echo base_url() . "uploads/student_images/default_female.jpg";
                                        } elseif ($student['gender'] == 'Male') {
                                            echo base_url() . "uploads/student_images/default_male.jpg";
                                        }
                                    }
                                    ?>" style="height: 185px;width: 185px;">
                    </div>
                </div>
            <br>
            <br>

                <div style="display:flex;padding-top:20px;align-items: center;    flex-wrap: wrap;">
                    <div class="" style="width: 50%;padding-bottom: 32px;">
                        <h4>Anthopometry:</h4>
                        <table style="width: 100%;">
                            <tr>
                                <td style="width: 50%;">Height(Cm)</td>
                                <td>: <?php echo @$rowlist['height']; ?></td>
                            </tr>
                            <tr>
                                <td>Weight(Kg):</td>
                                <td>: <?php echo @$rowlist['weight']; ?></td>
                            </tr>


                        </table>
                    </div>
                    <?php

                    if (!empty($resultlist)) {
                        $i = 1;
                        foreach ($resultlist as  $rowValue) {
                    ?>
                            <div class="" style="width: 50%;padding-bottom: 32px;">
                                <h4><?php echo $rowValue['name']; ?> </h4>
                                <table style="width: 100%;">

                                    <tr>
                                        <td><?php echo $rowValue['content']; ?></td>
                                    </tr>

                                </table>
                            </div>
                    <?php }
                    } ?>
                </div>
            </div>
        </div>
        <div style="text-align:center;">
            <!-- <p><b>Your LifeLine To Safe Health</b></p> -->
        </div>
    </div>
</body>
<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</html>