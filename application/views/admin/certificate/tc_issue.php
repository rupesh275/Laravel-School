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
            font-size: 19px;
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
        .colan{
            padding-right:10px;
        }
    </style>
</head>

<body>
    <?php
    // echo "<pre>";
    // print_r($students);

    // $studentinfo = $this->student_model->get($student->id);
    // echo "<pre>";
    // print_r($studentinfo);

    ?>
    <div class="invoice" id="invoice">
        <?php 
        if ($settinglist['print_letter'] == 1) {
        ?>
        <div class="bg" style="width:100%;justify-content:center; display: flex;align-items:center">
            <div style="width:100%;display:flex;align-items:center">
                <div style="width:15%;padding:10px">
                    <img src="<?php echo base_url('uploads/school_content/admin_small_logo/' . $settinglist['admin_small_logo']) ?>" alt="" style="width:148px">
                </div>
                <div class="p-0 text-center" style="width:85%;padding:10px">
                    <h2 class="db" style="text-transform: uppercase; font-family: inherit; font-weight: bolder; color: #262672; font-size: 36px; margin-bottom: 1px; font-style: inherit;"><?php echo $settinglist['name']; ?> </h2>
                    <p class="m-0"><i>(Managed By Sree Narayanya Mandirs Samiti) </i></p>
                    <p class="m-0"><b><?php echo $sch_setting->address; ?></b></p>
                    <p class="m-0"><b>Email:<?php echo $sch_setting->email; ?> Website:WWW.sngcentralschool.org</b></p>
                    <p class="m-0"><b>Tel no: <?php echo $sch_setting->phone; ?></b></p>
                </div>
            </div>
        </div>
        <?php }else{?>
        <div class="" style="height: 168px;"></div>
        <?php }?>
        <?php
            $subjs=  str_replace(",",", ",$student_info['subject_studied']);

        ?>
        <div class="container">
            <div class="row" style="margin:0 17px">
                <!-- <div class="" style="width:100%; justify-content:space-between;display: flex;">
                    <div class="p-0" style="width:33.33%">
                        <p class="pt-2 mb-0">CBSC Affiliation No.: <?php echo $sch_setting->affilation_no; ?></p>
                    </div>
                    <div class="text-right" style="width:33.33%">
                        <p class="pt-2 mb-0">School UDISE No.: <?php echo $sch_setting->school_UDISE; ?></p>
                    </div>
                </div> -->
                <div class="text-center" style="width:100%;">
                    <br>
                    <br>
                    <br>
                    <br>
                    <h4 style="font-weight: 700;font-size:30px;text-transform: uppercase;"><u>Leaving certificate</u></h4>
                    <p><b>(C.B.S.E. Affiliation No.: <?php echo $sch_setting->affilation_no; ?>)</b></p>
                    <p><b>(See Rule 17 and 32 in chapter II , Section)</b></p>
                </div>
                <!-- <div class="" style="width:100%;display:flex">
                    <div style="width:50.33%">
                        <p><b>Admission No.: <?php echo $student['admission_no']; ?></b></p>
                        <p><b>Student ID:</b><?php echo $student['id']; ?></p>

                    </div>
                    <div style="width:50.33%;text-align:right">
                        <p><b>Transfer Certifcate No. <?php if (!empty($student_info['tc_certificate_no'])) {
                                                            echo $student_info['tc_certificate_no'];
                                                        }; ?></b></p>
                        <p><b>AADHAR CARD NO. <?php echo $student['adhar_no']; ?></b></p>
                    </div>
                </div> -->

                <div class="col-md-12">
                    <table class="table border-0">
                        <tr>
                            <td class="FW">Admission No.: <?php if (!empty($student['admission_no'])) {echo $student['admission_no'];} ?></td>
                            <td class="FW"><div style="display:flex"><span class="colan"> </span><span>Transfer Certifcate No. <?php if (!empty($student_info['tc_certificate_no'])) {
                                                                        echo $student_info['tc_certificate_no'];
                                                                    }; ?></span></span></td>

                        </tr>
                        <tr>
                            <td class="FW"><div style="display:flex">Student ID <span class="colan">:</span><?php if (!empty($student['dep_student_id'])) {echo $student['dep_student_id'];} ?></span></div></td>

                            <td class="FW"><div style="display:flex"><span class="colan"> </span> <span> Aadhar Card NO: <?php if (!empty($student['adhar_no'])) {echo $student['adhar_no'];} ?></span></div></td>
                        </tr>
                        <tr>
                            <?php //$full_name = $this->customlib->getFullName($student->firstname, $student->middlename, $student->lastname)
                            ?>
                            <td style="width: 60%;">1) Name of Pupil </td>
                            <td class="FW"><div style="display:flex"><span class="colan">:</span> <span><?php echo $student['aadhar_name']; ?></span></div></td>
                        </tr>
                        <tr>
                            <td>2) Father’s Name</td>
                            <td class="FW"><div style="display:flex"><span class="colan">:</span> <span><?php echo $student['father_name']; ?></span></div></td>
                        </tr>
                        <tr>
                            <td>3) Mother’s Name</td>
                            <td class="FW"><div style="display:flex"><span class="colan">:</span> <span><?php echo $student['mother_name']; ?></span></div></td>
                        </tr>
                        <tr>
                            <td>4) Gender</td>
                            <td class="FW"><div style="display:flex"><span class="colan">:</span> <span><?php echo $student['gender']; ?> </span></div></td>
                        </tr>
                        <tr>
                            <td>5) Mother Tongue</td>
                            <td class="FW"> <div style="display:flex"><span class="colan">:</span> <span><?php echo $student_info['mother_tongue']; ?></span></div></td>
                        </tr>
                        <tr>
                            <td>6) Nationality</td>
                            <td class="FW"><div style="display:flex"><span class="colan">:</span> <span><?php echo $student_info['nationality']; ?></span></div></td>
                        </tr>
                        <tr>
                            <td>7) Religion</td>
                            <td class="FW"><div style="display:flex"><span class="colan">:</span> <span><?php echo $student['religion']; ?></span></div></td>
                        </tr>
                        <tr>
                            <td>8) Caste and sub caste(if applicable)</td>
                            <td class="FW"> <div style="display:flex"><span class="colan">:</span> <span><?php if (!empty($student) && $student['cast'] != "") {
                                                    echo $student['cast']; echo $student['sub_caste'] !="" ? ",".$student['sub_caste']:"";
                                                } else {
                                                    echo "--------";
                                                } ?></span></div></td>
                        </tr>
                        <tr>
                            <td>9) Place of birth</td>
                            <td class="FW"><div style="display:flex"><span class="colan">:</span> <span><?php echo $student_info['pob']; ?></span></div></td>
                        </tr>
                        <tr>
                            <td>10) Date of birth(figures)</td>
                            <td class="FW"><div style="display:flex"><span class="colan">:</span> <span><?php if (!empty($student['dob'])) {
                                                    echo date('d-m-Y', strtotime($student['dob']));
                                                } ?></span></div></td>
                        </tr>
                        <?php 
                        if (!empty($student['dob'])) {
                            $day = date('d',strtotime($student['dob']));
                            $month = date('F',strtotime($student['dob']));
                            $year = date('Y',strtotime($student['dob']));
                            $dayWord = ucwords(markSheetDigitTwo($day));
                            $yearWord = ucwords(markSheetDigitTwo($year));
                            $dobword = $dayWord." ".$month." ".$yearWord;
                        }
                        else {
                            $dobword ="";
                        }
                        ?>
                        <tr>
                            <td>11) Date of birth (Words)</td>
                            <td class="FW"><div style="display:flex"><span class="colan">:</span><span> <?php echo $dobword; ?></span></div> </td>
                        </tr>
                        <tr>
                            <td>12) Date of first admission in the school with class</td>
                            <td class="FW"><div style="display:flex"><span class="colan">:</span> <span><?php echo $student_info['first_adm_class']; ?></span></div></td>
                        </tr>
                        <tr>
                            <td>13) Class in which the pupil last studied </td>
                            <td class="FW"><div style="display:flex"><span class="colan">:</span> <span><?php if (!empty($student_info['last_class'])) {
                                                    echo $student_info['last_class'];
                                                } ?></span></div></td>
                        </tr>
                        <tr>
                            <td>14) Previous school attended & board</td>
                            <td class="FW"><div style="display:flex"><span class="colan">:</span> <span><?php echo $student_info['prev_school_board']; ?></span></div></td>
                        </tr>
                        <tr>
                            <td>15) Whether failed, if so once/twice in the same class</td>
                            <td class="FW"><div style="display:flex"><span class="colan">:</span> <span><?php echo $student_info['repeated_class']; ?></span></div></td>
                        </tr>
                        <tr>
                            <td>16) Subject studied </td>
                            <td class="FW" style="font-size:17px;"><div style="display:flex"><span class="colan">:</span> <span><?php echo $subjs; ///$student_info['subject_studied']; ?></span></div></td>
                        </tr>
                        <tr>
                            <td>17) Passed and promoted to class (in figure and words)</td>
                            <td class="FW"><div style="display:flex"><span class="colan">:</span> <span><?php echo $student_info['promoted_class']." (".$student_info['class_text'].")"; ?></span></div></td>
                        </tr>
                        <tr>
                            <td>18) Month upto which the (pupil has paid) school dues paid </td>
                            <td class="FW"><div style="display:flex"><span class="colan">:</span> <span><?php echo $student_info['school_dues']; ?></span></div></td>
                        </tr>
                        <tr>
                            <td>19) Any fee concession availabled of</td>
                            <td class="FW"><div style="display:flex"><span class="colan">:</span> <span><?php echo $student_info['fee_concession']; ?></span></div></td>
                        </tr>
                        <tr>
                            <td>20) Total No. of working days in the academic session </td>
                            <td class="FW"><div style="display:flex"><span class="colan">:</span> <span><?php echo $student_info['working_academic']; ?></span></div></td>
                        </tr>
                        <tr>
                            <td>21) Total no. of working days pupil present in the school </td>
                            <td class="FW"><div style="display:flex"><span class="colan">:</span> <span><?php echo $student_info['working_present']; ?></span></div></td>
                        </tr>
                        <tr>
                            <td>22) Whether NCC Cadet/boy Scout/Girl Guide</td>
                            <td class="FW"><div style="display:flex"><span class="colan">:</span> <span><?php echo $student_info['special_category']; ?></span></div></td>
                        </tr>
                        <tr>
                            <td>23) Game played or extra-currical activities the pupil took part </td>
                            <td class="FW"><div style="display:flex"><span class="colan">:</span> <span><?php echo $student_info['curricular_activities']; ?></span></div></td>
                        </tr>
                        <tr>
                            <td>24) General conduct</td>
                            <td class="FW"><div style="display:flex"><span class="colan">:</span> <span><?php echo $student_info['general_conduct']; ?></span></div></td>
                        </tr>
                        <tr>
                            <td>25) Date of Application for certificate</td>
                            <td class="FW"><div style="display:flex"><span class="colan">:</span> <span><?php echo date('d-m-Y', strtotime($student_info['doa'])); ?></span></div></td>
                        </tr>
                        <tr>
                            <td>26) Day of issue of certificate </td>
                            <td class="FW"><div style="display:flex"><span class="colan">:</span> <span><?php echo date('d-m-Y', strtotime($student_info['doic'])); ?></span></div></td>
                        </tr>
                        <tr>
                            <td>27) Reason for leaving school </td>
                            <td class="FW"><div style="display:flex"><span class="colan">:</span> <span><?php echo $student_info['reason_leave']; ?></span></div></td>
                        </tr>
                        <tr>
                            <td>28) Any other Remark </td>
                            <td class="FW"><div style="display:flex"><span class="colan">:</span> <span><?php echo $student_info['student_remarks'];?></span></div></td>
                        </tr>

                    </table>
                    <br>
                    <br>
                    <br>
                </div>
                <div style="width:100%;display:flex">
                    <div class="" style="width:23%;text-align:left">
                        <p><b>Class Teacher</b></p>
                    </div>
                    <div class="" style="width:23%;text-align:center">
                        <p><b>Checked By</b></p>
                    </div>
                    <div class="" style="width:23%;text-align:center">
                        <p><b>Principal</b></p>
                    </div>
                    <div class="" style="width:23%;text-align:end">
                        <p><b>School Seal</b></p>
                    </div>
                </div>
                <div class="col-md-12">
                    <p>(<b>Transfer certification should be issued only under the signature of regular principal / in charge principal . in the absence of the principal the transfer certificate issued by the school should be countersigned by the chairman, school management committee.</b>)</p>
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