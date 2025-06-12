<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <!-- <link rel="stylesheet" href="localhost/leavingcertificate/css/style.css"> -->
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link href="https://fonts.cdnfonts.com/css/algeria" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Exo+2:ital,wght@0,800;1,900&family=Titillium+Web:wght@900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Charm&display=swap" rel="stylesheet">

    <style media="all">
        /* @import url('<?php //echo base_url('backend/dist/fonts/Algerian_Regular.ttf'); 
                        ?>'); */

        p {
            margin-top: 0;
            margin-bottom: 1rem;
            font-family: unset;
            font-weight: 400;
            font-size: 14px;
        }

        h4 {
            font-family: 'Exo 2', sans-serif;
            color: #c10b0b;
            /* font-family: 'Titillium Web', sans-serif; */
            font-size: 29px;
        }

        .FW {
            font-weight: 700 !important;
        }

        .pt-2 {
            padding-top: 5px;
        }

        .m-0 {
            margin: 0px;
        }

        table {
            width: 100%;
        }

        .manage2 {
            color: #000;
            font-size: 19px;
        }

        .manage {
            font-family: sans-serif;
            font-weight: 600;
            font-size: 17px;
            color: #f34d6a;
        }

        .chunk span {
            padding: 5px 9px;
            /* border: 2px solid; */
            color: #000;
            font-size: 20px;
            font-weight: 600;
            font-family: system-ui;
        }



        .addr {
            margin-top: 0;
            margin-bottom: 1rem;
            font-family: unset;
            font-weight: 500;
            font-size: 21px;
        }

        @media print {
            table {
                width: 100%;
            }
        }

        .d-flex {
            display: flex;
        }

        .mb-0 {
            margin-bottom: 0;
        }

        .aic {
            align-items: center;
        }
        .air {
            align-items: right;
        }
        .mr-2 {
            margin-right: 5px
        }

        .W100 {
            width: 100%;
        }

        .w80 {
            display: flex;
            justify-content: center;

        }

        @media print {
            @page {
                size: portrait
            }

            .bg {
                page-break-before: always;
                margin-left: 20px;
            }
        }



        .charm span {
            padding: 0 35px;
            border-bottom: 2px solid #000;
            color: #890d0d;
            width: 100%;
        }

        .charm p {
            line-height: 50px;
            width: 100%;
        }

        .charm p,
        .charm span {
            font-family: 'Charm', cursive;
            font-size: 28px;
            font-weight: 600;
        }



        .bonf {
            /* background: #050554; */
            color: #000;
            display: inline-block;
            /* justify-content: center; */
            padding: 3px 23px;
            font-size: 23px;
            /* font-family: fangsong; */
            font-weight: 700;
        }

        .Sign {
            display: block;
            width: 100%;
            /* margin: 0 20px; */
            border-bottom: 2px solid #000;
        }

        .bg {
            background-image: url(<?php echo base_url('uploads/school_content/logo/bg.png') ?>);
            background-repeat: no-repeat;
            background-position: center 334px;
            background-color: #dedea3;
            padding: 15px 30px 0 30px;
            background-size: 127%;
            margin-top:300px;
        }
    </style>
</head>

<body>
    <?php
    $school = $sch_setting[0];
    $i = 0;
    // if (!empty($students)) {
        foreach ($students as $student) {
            // $this->db->where('student_session_id',$student->student_session_id);
            // $this->db->order_by('id desc');
            // $bonfide = $this->db->get('bonafide_trn')->row_array();
            
            $i++;
    ?>
            <div class="bg">

                <div class="" style="margin-left:25px;margin-right:20px">
                    <div class="d-flex aic">
                        <p class="manage2 d-flex mr-2 mb-0"><strong></strong></p>
                        <div class="chunk d-flex">
                            <b style="font-size: 22px;">SNGCS/BNFD-<?php echo $bonfide['srno'];?>/<?php echo $school['session']; ?></b>
                        </div>
                    </div>
                    <div class="" style="text-align: right;">
                            <b style="font-size: 22px;">C.B.S.E. Affiliation No. : <?php echo $school['affilation_no']; ?></b>
                        </div>
                                        <div style="text-align:center;width:100%">
                        <div class="bonf">BONAFIDE CERTIFICATE</div>
                    </div>
                    <div class="d-flex w-100" style="justify-content: space-between;">
                        <div style="width:30%">

                            <div class="d-flex aic">
                                <p class="manage2 d-flex mr-2 mb-0"><strong>G.R. No. </strong></p>
                                <div class="chunk d-flex">
                                    <b style="font-size: 22px;"><?php echo $student->admission_no; ?></b>
                                </div>
                            </div>


                        </div>
                        <div style="width: 30%;margin-top: 15px;margin-left: 40px;text-align:end"><img style="width: 150px;height:150px;" src="<?php
                                                                                                                                                if (!empty($student->image)) {
                                                                                                                                                    echo base_url() . $student->image;
                                                                                                                                                } else {

                                                                                                                                                    if ($student->gender == 'Female') {
                                                                                                                                                        echo base_url() . "uploads/student_images/default_female.jpg";
                                                                                                                                                    } elseif ($student->gender == 'Male') {
                                                                                                                                                        echo base_url() . "uploads/student_images/default_male.jpg";
                                                                                                                                                    }
                                                                                                                                                }
                                                                                                                                                ?>" alt="Student Image"></div>
                    </div>

                    <div class="w-100 charm" style="margin-top: 55px;">
                    <?php if($student->gender=="Male") {$son="Son"; $ms="Mst";$he="He";$his="his";$bhis="His";} else {$son="Daughter";$ms="Ms";$he="She";$his="her";$bhis="Her";} 
                    if($student->is_active=="yes") {$iswas="is";} else {$iswas="was";}
                    ?>
                        <p>This is to certify that <?php echo $ms; ?><span style="width:300px;text-transform:uppercase;padding:0 30px"> <?php echo $student->aadhar_name; ?></span>,
                        <?php echo $son; ?> of <?php if($student->father_name!='') { echo 'Mr.'.$student->father_name.' & '; } ?>  <?php if($student->mother_name!='') { echo 'Mrs.'.$student->mother_name; } ?> bearing 
                            Roll No: <span><?php echo $student->roll_no;?></span> <?php echo $iswas; ?> a student of Std <span> <?php echo $student->code; ?></span>
                            Div <span> <?php echo $student->section; ?></span>,
                            for the Academic Year <span><?php echo $school['session']; ?></span>.
                        <p>
                        <p style="padding-top:12px"> <?php echo $he; ?> is a bonafide student of Sree Narayana Guru Central School.</p>
                        <p style="padding-top:15px"> According to the General Register of the School <?php echo $his; ?> date of birth is <span style="padding:0 10px"> <?php echo date('d-m-Y', strtotime($student->dob)); ?></span>. <br><?php echo $his; ?> conduct and character is good.
                    </div>
                    <br>
                    <div class="" style="text-align:left;width:35.33%">
                        <b class="addr" style="font-size:20px;height: 137px;display: block;">Address: <?php echo $student->current_address; ?></b>
                    </div>
                    <br>
                    <div class="w-100 d-flex" style="justify-content:space-between;padding-bottom:10px;padding-top:100px;">
                        <div class="charm" style="text-align:left;width:30%">

                            <b class="addr">Date <span><?php echo date('d-m-Y',strtotime($bonfide['bt_date'])); ?></span></b>
                        </div>
                        <div class="charm" style="text-align:center;width:33.33%">

                            <b class="addr">School Seal</b>
                        </div>
                        <div class="charm" style="text-align:center;width:33.33%">
                            <!-- <b class="addr">Signature of Principal</b> -->
                        </div>
                    </div>

                </div>
                <br>
                <br>
            </div>
     <?php
        //}
    } ?> 
</body>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
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