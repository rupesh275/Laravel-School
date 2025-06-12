<!doctype html>
<html lang="en">

<head>
    <title>Title</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<style>
    @page {
        width: 100%;
        height: 100%;
    }

    @media print {
        /* .id-card-holder {
            width: 45%;
            height: 98%;
            max-height: fit-content;
        } */


        body {
            background-color: #d7d6d3;
            font-family: 'verdana';
        }

        .id-card-holder {
            width: 53%;
             height: 100%;
            /* width: 296px; */
            padding: 4px;
            /* margin: 0 auto; */
            /* background-color: #1f1f1f; */
            border-radius: 5px;
            position: relative;
        }

        .id-card-holder:after {
            content: '';
            width: 7px;
            display: block;
            /* background-color: #0a0a0a; */
            height: 100px;
            position: absolute;
            top: 105px;
            border-radius: 0 5px 5px 0;
        }

        .id-card-holder:before {
            content: '';
            width: 7px;
            display: block;
            /* background-color: #0a0a0a; */
            height: 100px;
            position: absolute;
            top: 105px;
            left: 222px;
            border-radius: 5px 0 0 5px;
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
            width: 180px;
            height: 220px;
            margin-top: 25px;
            margin-bottom: 25px;
        }

        h2 {
            font-size: 22px;
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

        p {
            font-size: 15px;
            margin: 2px;
            color:#000;
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
            padding: 3px 5px;
           
            font-weight: 600;
            text-transform: uppercase;
        }

        .smt {
            color: #9c1111;
            font-size: 21px;
            font-weight: 800;
        }

        .sclnm {
            font-size: 17px;
            font-family: Algerian;
            color: darkblue;
            font-weight: 600;
        }

        .add {
            font-size: 13px;
            line-height: 19px;
            font-weight: 700;
            color:#000;
        }

        .idtable th { 
            padding: 8px 20px;
            font-size: 16px;
            color:#000;
            width:40%;
        }
        .idtable td {
            padding: 8px 1px;
            font-size: 16px;
            line-height: 20px;
            color:#000;
            display: flex;
        }
        .idtable tr{
            vertical-align:top;
        }
        .idtable p{
            margin: 0;
            padding:0 2px;
        }
    }
</style>

<body>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

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
            <div class="id-card-holder">
                <div class="id-card" style="border: 2px solid #0a4279;">
                    <table>
                        <tbody>
                            <tr class="heading" style="border-bottom: 0;">
                                <td style="text-align: center;border-right: 2px solid #0a4279; border-bottom: 2px solid #0a4279;">
                                    <img src="<?php echo base_url('uploads/school_content/admin_small_logo/' . $school['admin_small_logo']); ?>" alt="" style="width: 97px; height: 30%;">
                                </td>
                                <td style="border-bottom: 2px solid #0a4279;     padding: 4% 0">
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
                                    ?>">
                    </div>
                    <h2><?php echo $this->customlib->getFullName($student->firstname, $student->middlename, $student->lastname, $sch_settingdata->middlename, $sch_settingdata->lastname); ?></h2>

                    <table class="idtable" style="width: 100%;height: 246px;">
                        <!--
                        <tr>
                            <td>Reg No:</td>
                            <td>:<?php //echo $student->admission_no; ?></td>
                        </tr>
                        <tr>
                            <td>Student Id</td>
                            <td>:123</td>
                        </tr>
                                                -->
                        <!-- <tr>
                            <th style="width: 25%;">Student Name</th>
                            <td>:<?php //echo $this->customlib->getFullName($student->firstname, $student->middlename, $student->lastname, $sch_settingdata->middlename, $sch_settingdata->lastname); ?></td>
                        </tr> -->
                        <tr>
                            <th>Class</th>
                            <td>: <?php echo $student->code . ' - ' . $student->section; ?></td>
                        </tr>
                        <tr>
                            <th>Father/Guardian</th>
                            <td>: <p><?php if($student->father_name!='') { echo $student->father_name; } else { echo $student->guardian_name;  } ?></p></td>
                        </tr>
                        <tr>
                            <th>Address</th>
                            <td style=" font-size: 14px;">: <p><?php echo !empty($student->current_address) ? $student->current_address : $student->permanent_address; ?></p></td>
                        </tr>
                        <!-- <tr>
                            <th>Blood Group</th>
                            <td>: <?php echo $student->blood_group; ?></td>
                        </tr> -->
                        <tr>
                            <th>Contact</th>
                            <td>: <?php echo $student->mobileno; ?></td>
                        </tr>
                    </table>
                    <hr style="border: 2px solid #0a4279;">

                    <p> <strong>Website: <?php echo $sch_settingdata->website;
                                            ?> </strong></p>
                    <p><strong>Phone No.: <?php echo $sch_settingdata->phone ?> </strong></p>
                    <!-- <p>Email: <?php //echo $sch_settingdata->email
                                    ?></p> -->
                    <br>
                </div>
            </div>
    <?php //}
    } ?>
</body>

</html>