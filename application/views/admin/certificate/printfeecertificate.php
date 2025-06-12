<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fees Certificate</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Exo+2:ital,wght@0,800;1,900&family=Titillium+Web:wght@900&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Charm&display=swap" rel="stylesheet">
</head>
<style>
    body {
      font-family: "Times New Roman", Times, serif;
    }

    p {
        margin-top: 0;
        margin-bottom: 1rem;
        font-family: unset;
        font-weight: 400;
        font-size: 14px;
    }

    @media print {
        @page {
            size: portrait
        }
        .break{
            page-break-after: always;
        }

    }

    .w-50 {
        width: 50%;
        padding: 0 7px;
    }

    td {
        border: 1px solid #9f9c9c;
        padding: 8px;
        font-size: 19px;
    }

    th {
        border: 1px solid #9f9c9c;
        text-align: left;
        padding: 8px;
        font-size: 22px;
    }

    .wt {
        width: 100px;
    }

    .bg {
        background-color: #e3dede;
    }

    .detail {
        font-size: 20px;
    }
</style>

<body>
    <?php 
    // echo "<pre>";
    // print_r ($students);
    // echo "</pre>";
    $school = $sch_setting[0];
     
    if (!empty($students)) {
        foreach ($students as  $student) {
            $feeData = $this->certificate_model->getfeesData($student->feestrn_id);
            $feetrn = $this->certificate_model->getfeesTrn($student->feestrn_id);
            if($student->gender=="Male")
            {$master="Master";$his="his";$son="S/o";}
            else
            {$master="Ms.";$his="her";$son="D/o";}
            ?>
          
    <div class="break" style="justify-content: center;display: flex;padding-top:200px">
        <div style="width:80%">
            <div class="" style="padding: 5px 0;">
                <p class="detail"><strong>C.B.S.E. Affiliation No: <?php echo $school['affilation_no']; ?></strong></p><br>
                <p class="detail"><strong> Certificate No: SNGCS/FCD/<?php echo $feetrn['certificate_no'];?>/<?php echo $school['session'];?></strong></p><br>
                
            </div>
            <div class="" style="text-align:right">
                <p class="detail"><strong> <?php echo date('l jS \of F Y',strtotime($feetrn['date']))?></strong></p>
            </div>

            <div style="text-align:center;padding-top: 41px;padding-bottom: 48px;font-size: 27px;">
                <b><u>TO WHOMSOVER IT MAY CONCERN</u></b>
            </div>

            <div>
                <?php 
                    $fees_details="";
                    $rw=0;
                    foreach ($feeData as  $fee) {
                        if($rw==0)
                        {$fees_details=$fee['fees_name'];}
                        else
                        {
                            if($rw==sizeof($feeData)-1)
                            {$fees_details.= ' and ' . $fee['fees_name'];}
                            else
                            {$fees_details.= ', ' . $fee['fees_name'];}
                        }
                        ++$rw;
                    }
                ?>
                <p style="font-size: 20px;line-height: 29px;">
                    This is to certify that <?php echo $master; ?> <b><?php echo $student->aadhar_name;?>.</b> <?php echo $son; ?> <?php echo $student->father_name ?> is a Bonifide
                    Student
                    of our school studying in
                    Std. <?php echo $student->class." ".$student->section;?>,Roll No:<?php echo $student->roll_no;?>, GR.No.<?php echo $student->admission_no; ?> during the Academic Year, <?php echo $school['session'];?>. Total amount of <?php echo $his; ?>
                    <?php echo $fees_details; ?> is as below:
                </p>
            </div>
            <div style="padding-top:50px">
                <table style="width:100%;">
                    <thead>
                        <tr>
                            <th>Details</th>
                            <th  style="text-align:right;">Fees</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if (!empty($feeData)) {
                            $total = 0;
                            foreach ($feeData as  $fee) {
                                $total += $fee['amount'];
                                ?>
                        <tr >
                            <td><?php echo $fee['fees_name'];?></td>
                            <td style="text-align:right;"><?php echo $fee['amount']; ?>/-</td>
                        </tr>
                        <?php
                            }
                        }
                        ?>
                        <tr>
                            <td><b>Total</b></td>
                            <td  style="text-align:right;"><b><?php echo $total?>/-</b></td>
                        </tr>
                    </tbody>


                </table>
            </div>
            <br>
            <p style="font-size: 21px;"><b>Rs. <?php echo ucwords(markSheetDigitTwo($total)); ?> Only</b></p>
            <div style="padding-top:100px;width:100%;">
                <p style="font-size: 21px;">Deepa Jayaroy</p>
                <p style="font-size: 21px;width:100%;"><b>Principal</b><span style="padding-left:400px;text-align:right;font-size: 21px;"><b>School Seal</b></span></p>
            </div>
        </div>
    </div>
    <?php
        }
    }?>
</body>

</html>