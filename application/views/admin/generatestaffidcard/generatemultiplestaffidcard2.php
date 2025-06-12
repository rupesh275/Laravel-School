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
        body {
            background-color: #d7d6d3;
            font-family: 'verdana';
        }

        .id-card-holder {
            width: 47%;
            height: webkit-fill-available;
            /* width: 296px; */
            margin: 4px;
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

            /* background-color: #fff; */
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
            width: 190px;
            height: 190px;
            margin-top: 25px;
            margin-bottom: 25px;
            border: 5px solid #c31212;
            border-radius: 50%;
        }

        h2 {
            font-size: 19px;
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
            font-size: 11px;
            margin: 2px;
            color: #fff;
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
            /* padding: 3px 0; */

            font-weight: 600;
            text-transform: uppercase;
        }

        .smt {
            color: #fff;
            font-size: 15px;
            font-weight: 800;
        }

        .sclnm {
            font-size: 17px;
            font-family: Algerian;
            color: darkblue;
            font-weight: 600;
        }

        .add {
            font-size: 9px;
            line-height: 12px;
            font-weight: 700;
            color: #fff;
        }

        .idtable th {
            padding: 6px 0 6px 15px;
            font-size: 14px;
            color: #000;
            width:50%;
        }

        .desg {
            text-transform: uppercase;
            font-size: 15px;
            font-weight: 700;
        }

        .idtable td {
            padding: 6px 0;
            font-size: 15px;
            line-height: 20px;
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

        .font {
            font-size: 24px;
        }

        .pagebreak {
            page-break-before: always;
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
    foreach ($staffs as $staff_value) {
        $i++;
        // echo "<pre>";
        // print_r($sch_settingdata)
    ?>
        <div class="pagebreak id-card-holder" style=" background: url(<?php echo base_url(); ?>upload/staffidcard1.png); background-size: cover;background-repeat: no-repeat; background-position: top center;">
            <div class="id-card">
                <div style="display:flex;justify-content:center;">
                    <table>
                        <tbody>
                            <tr class="heading" style="border-bottom: 0;">
                                <td style="text-align: center;">
                                    <img src="<?php echo base_url('uploads/school_content/admin_small_logo/' . $school['admin_small_logo']); ?>" alt="" style="width: 75px; height: 30%;padding:7px 5px 0 0;">
                                </td>
                                <td style="padding: 4% 0">
                                    <div style="text-align:center">
                                        <h5 class="mb-0 smt"><?php echo $school['name']; ?></h5>
                                        <!-- <h3 class="mb-0 sclnm"> <?php //echo $schoolschool_name 
                                                                        ?></h3> -->
                                        <!-- <p class="mb-0 add"><?php //echo $school['address'] 
                                                                    ?></p> -->
                                        <p class="mb-0 add">SREE NARAYANA NAGAR, CHEMBUR, MUMBAI - 400 089</p>
                                        <p class="mb-0 add">Phone No.: <?php echo $sch_settingdata->phone; ?> </p>
                                        <p class="mb-0 add">Email Id:<?php echo $sch_settingdata->email; ?></p>
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
                </div>
                <div class="photo">
                    <img src="<?php
                                if (!empty($staff_value->image)) {
                                    echo base_url() . "uploads/staff_images/" . $staff_value->image;
                                } else {
                                    if ($staff_value->gender == 'Female') {
                                        echo base_url() . "uploads/staff_images/default_female.jpg";
                                    } elseif ($staff_value->gender == 'Male') {
                                        echo base_url() . "uploads/staff_images/default_male.jpg";
                                    }
                                }
                                ?>">
                </div>
                <h2><?php echo $staff_value->name . " " . $staff_value->surname; ?></h2>
                <div class="desg">[<?php echo $staff_value->designation; ?>]</div>
                <div style="display:flex;justify-content:center;">
                    <div style="height:310px;width:100%;align-items:center;display: flex;">
                        <table class="idtable" style="width:100%;">
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
                                <th><?php echo $this->lang->line('staff_id'); ?></th>
                                <td>: <?php echo $staff_value->biometric_id; ?></td>
                            </tr>
                            <tr>
                                <th><?php echo $this->lang->line('department'); ?></th>
                                <td>: <?php echo $staff_value->department; ?></td>
                            </tr>

                            <tr>
                                <th><?php echo $this->lang->line('date_of_birth'); ?></th>
                                <td>: <?php if (!empty($staff_value->dob) && $staff_value->dob != '0000-00-00') {
                                            echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateYYYYMMDDtoStrtotime($staff_value->dob));
                                        } ?></td>
                            </tr>
                            <tr>
                                <th><?php echo $this->lang->line('date_of_joining'); ?></th>
                                <td>: <?php if (!empty($staff_value->date_of_joining) && $staff_value->date_of_joining != '0000-00-00') {
                                            echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateYYYYMMDDtoStrtotime($staff_value->date_of_joining));
                                        } ?></td>
                            </tr>
                            <tr>
                                <th><?php echo $this->lang->line('blood_group'); ?></th>
                                <td>: <?php echo $staff_value->blood_group; ?></td>
                            </tr>

                            <!-- <tr>
                                <th><?php //echo $this->lang->line('address'); 
                                    ?></th>
                                <td style=" font-size: 14px;">: <?php //echo $staff_value->local_address; 
                                                                ?></td>
                            </tr>
                           -->
                            <tr>
                                <th>Contact No.</th>
                                <td>: <?php echo $staff_value->contact_no; ?></td>
                            </tr>
                            <tr>
                                <th><span style="display:block;">Emergency Contact No.</span></th>
                                <td>: <?php echo $staff_value->emergency_contact_no; ?></td>
                            </tr>

                        </table>
                    </div>
                </div>
                <div style="background: #c31212;padding: 5px 0;border-radius: 19px 19px 0 0;">
                    <p> <strong>Website: <?php echo  $sch_settingdata->website; ?> </strong></p>

                    <!-- <p>Email: <?php //echo $sch_settingdata->email 
                                    ?></p> -->
                </div>
            </div>
        </div>
    <?php } ?>
</body>

</html>