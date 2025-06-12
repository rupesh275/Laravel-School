<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <!-- <link rel="stylesheet" href="localhost/leavingcertificate/css/style.css"> -->
    <link href="<?php echo base_url('backend/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet" id="bootstrap-css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-print-css/css/bootstrap-print.min.css" media="print">
    <style>
        p {
            margin-top: 0;
            margin-bottom: 1rem;
            font-family: unset;
            font-weight: 400;
            font-size: 20px;
        }



        .sfborder .img-circle,
        .box-profile .img-circle,
        .table td .img-circle {
            height: 100px;
            flex-shrink: 0;
            min-width: 100px;
            min-height: 100px;
        }

        .profile-user-img {
            margin: 0px auto;
            width: 145px;
            padding: 3px;
            border: 3px solid #d2d6de;
            height: 147px;
        }

        .db {
            color: darkblue
        }

        .FW {
            font-weight: 700 !important;
            text-transform: uppercase;
        }

        .pt-2 {
            padding-top: 5px;
        }

        .bg {
            background: #fac669;
        }

        .m-0 {
            margin: 0px;
        }

        .table td,
        .table th {
            font-size: 14px;
            padding: 3px 0 !important;
            /* font-weight: 600; */
            border-top: none !important;
        }

        tr {
            border-top: none;
        }

        @media print {
            #printInvoice {
                display: none;
            }

            .bg {
                background: #fac669 !important;
            }

            .db {
                color: darkblue !important;
            }
        }
    </style>
</head>

<body>
    <?php
    // echo "<pre>";
    // print_r($staffs);

    // $staffinfo = $this->staff_model->get($staff->id);
    // echo "<pre>";
    // print_r($staffinfo);
    $settinglist = $settinglist[0];
    ?>
    <div class="invoice" id="invoice">
        <?php
        // if ($settinglist['print_letter'] == 1) {
        ?>
        <div class="bg" style="width:100%;justify-content:center; display: flex;align-items:center">
            <div style="width:100%;display:flex;align-items:center">
                <div style="width:15%;padding:10px">
                    <img src="<?php echo base_url('uploads/school_content/admin_small_logo/' . $settinglist['admin_small_logo']) ?>" alt="" style="width:148px">
                </div>
                <div class="p-0 text-center" style="width:85%;padding:10px">
                    <h2 class="db" style="text-transform: uppercase; font-family: inherit; font-weight: bolder; color: #262672; font-size: 36px; margin-bottom: 1px; font-style: inherit;"><?php echo $settinglist['name']; ?> </h2>
                    <!-- <p class="m-0"><i>(Managed By Sree Narayanya Mandirs Samiti) </i></p>
                    <p class="m-0"><b><?php //echo $sch_setting->address; 
                                        ?></b></p>
                    <p class="m-0"><b>Email:<?php //echo $sch_setting->email; 
                                            ?> Website:WWW.sngcentralschool.org</b></p>
                    <p class="m-0"><b>Tel no: <?php //echo $sch_setting->phone; 
                                                ?></b></p> -->
                </div>
            </div>
        </div>
        <div class="" style="height: 28px;"></div>
        <?php

        $image = $staff['image'];
        if (!empty($image)) {

            $file = $staff['image'];
        } else {
            if ($staff['gender'] == 'Male') {
                $file = "default_male.jpg";
            } else {
                $file = "default_female.jpg";
            }
        }
        ?>
        <div class="container">
            <div class="" style="display:flex">


                <div style="width:55%">
                    <table class="table border-0" style="width:100%">
                        <tr>
                            <td colspan="2">
                                <h2>Basic Information</h2>
                            </td>
                        </tr>
                        <tr>
                            <td style="width:50%;">1) Staff ID </td>
                            <td class="FW" style="width:50%;">: <?php echo $staff['employee_id'];
                                                                ?></td>

                        </tr>
                        <tr>
                            <td style="width:50%;">2) Role</td>
                            <td class="FW" style="width:50%;">: <?php echo $staff['role'];
                                                                ?></td>
                        </tr>
                        <tr>
                            <td>3) Designation</td>
                            <td class="FW">: <?php echo $staff['designation'];
                                                ?></td>


                        </tr>
                        <tr>
                            <td>4) Department</td>
                            <td class="FW">: <?php echo $staff['department'];
                                                ?> </td>
                        </tr>

                        <tr>
                            <td>5) Date of Birth</td>
                            <td class="FW">: <?php if (!empty($staff['dob'])) {
                                                    echo date('d-m-Y', strtotime($staff['dob']));
                                                }
                                                ?>
                            </td>


                        </tr>
                        <tr>
                            <td>6) Subcaste</td>
                            <td class="FW">: <?php echo $staff_profile['subcaste']; ?> </td>


                        </tr>
                        <tr>
                            <td>7) Seniority ID </td>
                            <td class="FW">: <?php echo $staff['seniority_id'];
                                                ?></td>


                        </tr>
                        <tr>
                            <td>8) Grade</td>
                            <td class="FW">: <?php echo $staff_profile['grade'];
                                                ?></td>


                        </tr>
                        <tr>
                            <td>9) Date of Confirmation</td>
                            <td class="FW">: <?php 
                                                if (!empty($staff_profile['date_of_confirmation']) && $staff_profile['date_of_confirmation'] != "0000-00-00" && $staff_profile['date_of_confirmation'] != "1970-01-01") {
                                                    echo date('d-m-Y', strtotime($staff_profile['date_of_confirmation']));
                                                }?></td>


                        </tr>
                        <tr>
                            <td>10) Work Experience</td>
                            <td class="FW">: <?php echo $staff['work_exp'];
                                                ?></td>


                        </tr>
                        <tr>
                            <td>11) Aadhar No </td>
                            <td class="FW">: <?php echo $staff['aadhar_no'];
                                                ?></td>


                        </tr>
                        <tr>
                            <td>12) Father Name</td>
                            <td class="FW"> : <?php echo $staff['father_name'];
                                                ?></td>


                        </tr>

                        <tr>
                            <td>13) Mother Name</td>
                            <td class="FW">: <?php echo $staff['mother_name'];
                                                ?></td>
                        </tr>
                        <tr>
                            <td>14) Marital Status</td>
                            <td class="FW">: <?php echo $staff['marital_status'];
                                                ?></td>


                        </tr>
                        <div>


                    </table>
                </div>
                <div style="width:45%">

                    <div class="image" style="justify-content: right;  display: flex;">
                        <div class="">
                            <img class="profile-user-img img-responsive img-circle" src="<?php echo base_url() . "uploads/staff_images/" . $file ?>" alt="User profile picture">
                            <h3 class="profile-username text-center"><b><?php echo $staff['name'] . " " . $staff['middle_name']  . " " . $staff['surname']; ?></b></h3>
                        </div>
                    </div>
                    <table class="table border-0" style="width:100%">

                        <tr>
                            <td>15) Spouse Name</td>
                            <td class="FW"> : <?php echo $staff_profile['spouse_name'];
                                                ?></td>
                        </tr>
                        <tr>
                            <td>16) Caste</td>
                            <td class="FW">: <?php echo $staff_profile['cast'];
                                                ?> </td>
                        </tr>
                        <tr>
                            <td>17) Religion</td>
                            <td class="FW">: <?php echo $staff_profile['religion'];
                                                ?></td>
                        </tr>
                        <tr>
                            <td>18) Gender</td>
                            <td class="FW">: <?php echo $staff['gender'];
                                                ?></td>
                        </tr>
                        <tr>
                            <td>19) Date Of Joining </td>
                            <td class="FW">: <?php 
                                                if (!empty($staff_profile['date_of_joining']) && $staff['date_of_joining'] != "0000-00-00" && $staff['date_of_joining'] != "1970-01-01") {
                                                    echo date('d-m-Y', strtotime($staff_profile['date_of_joining']));
                                                }
                                                ?></td>
                        </tr>
                        <tr>
                            <td>20) Qualification </td>
                            <td class="FW">: <?php echo $staff['qualification'];
                                                ?></td>
                        </tr>
                        <tr>
                            <td>21) Blood Group </td>
                            <td class="FW">: <?php echo $staff['blood_group'];
                                                ?></td>
                        </tr>
                        <tr>
                            <td>22) Pan No</td>
                            <td class="FW">: <?php echo $staff['pan_no'];
                                                ?></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="col-md-12">

                <table class="table border-0" style="width:100%">
                    <tr>
                        <td colspan="4">
                            <h2>Contact Information</h2>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:20%;">23) Email </td>
                        <td class="FW">: <?php echo $staff['email'];
                                            ?></td>
                        <td style="width:20%;">24) Phone </td>
                        <td class="FW">: <?php echo $staff['contact_no'];
                                            ?></td>
                    </tr>
                </table>
                <table class="table border-0" style="width:100%">
                    <tr>
                        <td colspan="4">
                            <h2>Address</h2>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:20%;">25) Address </td>
                        <td class="FW">: <?php $address = !empty($staff['local_address']) ? $staff['local_address'] : $staff['permanent_address'];
                                            echo $address;
                                            ?></td>
                    </tr>
                </table>
                <table class="table border-0" style="width:100%">
                    <tr>
                        <td colspan="4">
                            <h2>Passport Details</h2>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:20%;">26) Passport No </td>
                        <td class="FW">: <?php echo $staff_profile['passport_no'];
                                            ?></td>
                        <td style="width:20%;">27) Place Of Issue </td>
                        <td class="FW">: <?php echo $staff_profile['place_of_issue'];
                                            ?></td>
                    </tr>
                    <tr>
                        <td style="width:20%;">28) Date Of Issue </td>
                        <td class="FW">: <?php if (!empty($staff_profile['date_of_issue']) && $staff_profile['date_of_issue'] != "0000-00-00" && $staff_profile['date_of_issue'] != "1970-01-01") {
                                                echo date('d-m-Y', strtotime($staff_profile['date_of_issue']));
                                            }
                                            ?></td>
                        <td style="width:20%;">29) Date Of Expiry </td>
                        <td class="FW">: <?php if (!empty($staff_profile['date_of_expiry']) && $staff_profile['date_of_expiry'] != "0000-00-00" && $staff_profile['date_of_expiry'] != "1970-01-01") {
                                                echo date('d-m-Y', strtotime($staff_profile['date_of_expiry']));
                                            }
                                            ?></td>
                    </tr>
                </table>
                <table class="table border-0" style="width:100%">
                    <tr>
                        <td colspan="4">
                            <h2>Payroll</h2>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:15%;">30) EPF No </td>
                        <td class="FW" style="width: 11%;">: <?php echo $staff_profile['epf_no'];
                                                                ?></td>
                        <td style="width:15%;">31) Contract Type </td>
                        <td class="FW" style="width: 11%;">: <?php echo $staff_profile['contract_type'];
                                                                ?></td>

                        <td style="width:20%;">32) Basic Salary </td>
                        <td class="FW" style="width: 11%;">: <?php echo $staff_profile['basic_salary'];
                                                                ?></td>
                    </tr>
                    <tr>
                        <td style="width:20%;">33) Work Shift </td>
                        <td class="FW" style="width: 11%;">: <?php echo $staff_profile['shift'];
                                                                ?></td>

                        <td style="width:20%;">34) Location </td>
                        <td class="FW" style="width: 11%;">: <?php echo $staff_profile['location'];
                                                                ?></td>
                        <td style="width:20%;">35) Scale Of Pay </td>
                        <td class="FW" style="width: 11%;">: <?php echo $staff_profile['scale_of_pay'];
                                                                ?></td>
                    </tr>
                    <tr>
                        <td style="width:20%;">36) Basic Pay </td>
                        <td class="FW" style="width: 11%;">: <?php echo $staff_profile['basic_pay'];
                                                                ?></td>
                        <td style="width:20%;">37) GP </td>
                        <td class="FW" style="width: 11%;">: <?php echo $staff_profile['gp'];
                                                                ?></td>

                        <td style="width:20%;">38) PF </td>
                        <td class="FW" style="width: 11%;">: <?php echo $staff_profile['pf'];
                                                                ?></td>
                    </tr>
                    <tr>
                        <td style="width:20%;">39) DA </td>
                        <td class="FW" style="width: 11%;">: <?php echo $staff_profile['da'];
                                                                ?></td>

                        <td style="width:20%;">40) HRA </td>
                        <td class="FW" style="width: 11%;">: <?php echo $staff_profile['hra'];
                                                                ?></td>
                        <td style="width:20%;">41) Increment Amount </td>
                        <td class="FW" style="width: 11%;">: <?php echo $staff_profile['increment_amount'];
                                                                ?></td>
                    </tr>
                    <tr>
                        <td style="width:20%;">42) TA </td>
                        <td class="FW" style="width: 11%;">: <?php echo $staff_profile['ta'];
                                                                ?></td>
                        <td style="width:20%;">43) Other Allowance </td>
                        <td class="FW" style="width: 11%;">: <?php echo $staff_profile['other_allowance'];
                                                                ?></td>
                        <td style="width:20%;">44) Increment Month </td>
                        <td class="FW" style="width: 11%;">: <?php echo $staff_profile['increment_month'];
                                                                ?></td>

                    </tr>
                    <tr>
                        <td style="width:20%;">45) Profession Tax </td>
                        <td class="FW" style="width: 11%;">: <?php echo $staff_profile['profession_tax'];
                                                                ?></td>
                        <td style="width:20%;">46) Income Tax </td>
                        <td class="FW" style="width: 11%;">: <?php echo $staff_profile['income_tax'];
                                                                ?></td>
                        <td style="width:20%;">47) UAN NO </td>
                        <td class="FW" style="width: 11%;">: <?php echo $staff_profile['uan_no'];
                                                                ?></td>
                    </tr>
                    <tr>
                        <td style="width:20%;">48) DCPS No </td>
                        <td class="FW" style="width: 11%;">: <?php echo $staff_profile['dcps_no'];
                                                                ?></td>
                        <td style="width:20%;">49) Gratuity No </td>
                        <td class="FW" style="width: 11%;">: <?php echo $staff_profile['gratuity_no'];
                                                                ?></td>
                        <td style="width:20%;">50) LWF Applicable </td>
                        <td class="FW" style="width: 11%;">: <?php
                                                                if (!empty($staff_profile['lwf_applicable'] && $staff_profile['lwf_applicable'] == 1)) {
                                                                    echo "Yes";
                                                                } elseif ($staff_profile['lwf_applicable'] == 2) {
                                                                    echo "No";
                                                                }
                                                                ?></td>
                    </tr>
                    <tr>
                        <td style="width:20%;">51) LWF Grade </td>
                        <td class="FW" style="width: 11%;">: <?php echo $staff_profile['lwf_grade'];
                                                                ?></td>

                        <td style="width:20%;">52) PF Exempted </td>
                        <td class="FW" style="width: 11%;">: <?php
                                                                if (!empty($staff_profile['pf_exempted'] && $staff_profile['pf_exempted'] == 1)) {
                                                                    echo "Yes";
                                                                } elseif ($staff_profile['pf_exempted'] == 2) {
                                                                    echo "No";
                                                                }
                                                                ?></td>

                        <td style="width:20%;">53) PT Exempted </td>
                        <td class="FW" style="width: 11%;">: <?php
                                                                if (!empty($staff_profile['pt_exempted'] && $staff_profile['pt_exempted'] == 1)) {
                                                                    echo "Yes";
                                                                } elseif ($staff_profile['pt_exempted'] == 2) {
                                                                    echo "No";
                                                                }
                                                                ?></td>
                    </tr>
                </table>
                <table class="table border-0" style="width:100%">
                    <tr>
                        <td colspan="4">
                            <h2>Bank Account Details</h2>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:20%;">54) Account Title </td>
                        <td class="FW">: <?php echo $staff['account_title'];
                                            ?></td>
                        <td style="width:20%;">55) Bank Account Number </td>
                        <td class="FW">: <?php echo $staff['bank_account_no'];
                                            ?></td>
                    </tr>
                    <tr>
                        <td style="width:20%;">56) Bank Name </td>
                        <td class="FW">: <?php echo $staff['bank_name'];
                                            ?></td>
                        <td style="width:20%;">57) IFSC Code </td>
                        <td class="FW">: <?php echo $staff['ifsc_code'];
                                            ?></td>
                    </tr>
                    <tr>
                        <td style="width:20%;">58) Bank Branch Name </td>
                        <td class="FW">: <?php echo $staff['bank_branch'];
                                            ?></td>
                    </tr>
                </table>
                <table class="table border-0" style="width:100%">
                    <tr>
                        <td colspan="4">
                            <h2>ESI Details</h2>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:20%;">59) ESI No </td>
                        <td class="FW">: <?php echo $staff_profile['esi_no'];
                                            ?></td>
                        <td style="width:20%;">60) ESI Dispensary </td>
                        <td class="FW">: <?php echo $staff_profile['esi_dispensary'];
                                            ?></td>

                        <td style="width:20%;">61) ESI Exempted </td>
                        <td class="FW">: <?php
                                            if (!empty($staff_profile['esi_exempted'] && $staff_profile['esi_exempted'] == 1)) {
                                                echo "Yes";
                                            } elseif ($staff_profile['esi_exempted'] == 2) {
                                                echo "No";
                                            }
                                            ?></td>
                    </tr>
                </table>
                <table class="table border-0" style="width:100%">
                    <tr>
                        <td colspan="4">
                            <h2>Left Details</h2>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:20%;">62) Date of Retirement </td>
                        <td class="FW">: <?php if (!empty($staff_profile['date_of_retirement']) && $staff_profile['date_of_retirement'] != "0000-00-00" && $staff_profile['date_of_retirement'] != "1970-01-01") {
                                                    echo date('d-m-Y', strtotime($staff_profile['date_of_retirement']));
                                                }
                                            ?></td>
                        <td style="width:20%;">63) LeftOn </td>
                        <td class="FW">: <?php if (!empty($staff_profile['lefton']) && $staff_profile['lefton'] != "0000-00-00" && $staff_profile['lefton'] != "1970-01-01") {
                                                    echo date('d-m-Y', strtotime($staff_profile['lefton']));
                                                }
                                            ?></td>

                        <td style="width:20%;">64) Left Reason </td>
                        <td class="FW">: <?php echo $staff_profile['left_reason']; ?></td>
                    </tr>
                </table>
                <table class="table border-0" style="width:100%">
                    <tr>
                        <td colspan="4">
                            <h2>Social Media Links</h2>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:15%;">65) Facebook URL </td>
                        <td class="FW">: <?php echo $staff_profile['facebook'];
                                            ?></td>
                        <td style="width:15%;">66) Twitter URL </td>
                        <td class="FW">: <?php echo $staff_profile['twitter'];
                                            ?></td>
                    </tr>
                    <tr>
                        <td style="width:15%;">67) Linkedin URL </td>
                        <td class="FW">: <?php echo $staff_profile['linkedin']; ?></td>

                        <td style="width:15%;">68) Instagram URL </td>
                        <td class="FW">: <?php echo $staff_profile['instagram'];
                                            ?></td>
                    </tr>
                </table>
            </div>

        </div>
    </div>

    </div>

</body>
<script src="<?php echo base_url('backend/custom/jquery.min.js') ?>"></script>
<script src="<?php echo base_url('backend/bootstrap/js/bootstrap.min.js') ?>"></script>

<script>
    // $('#printInvoice').click(function() {
    //     Popup($('.invoice')[0].outerHTML);

    //     function Popup(data) {
    //         window.print();
    //         return true;
    //     }
    // });
</script>

</html>