<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation of Admission Letter</title>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <!-- <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin> -->
    <!-- <link href="https://fonts.googleapis.com/css2?family=Bitter:ital,wght@0,600;1,600&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet"> -->
    <link href="https://fonts.googleapis.com/css2?family=Libre+Bodoni:wght@700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Aleo:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
   <style>
    @media print {
        body{
            margin: 0;
            padding: 0;
        }
        @page{
            margin: 0;
        }
    }
        .dflex {
            display: flex;
        }

        body {
            /* font-family: 'Raleway', sans-serif; */
            color: #000;
            /* font-weight: 500; */
        }

       
        .bg {
            background: #fac669 !important;
        }

        /* .db {
            color: darkblue !important;
        } */

        .p-0 {
            padding: 0;
        }

        .m-0 {
            margin: 0px;
        }

        .Head p {
            font-size: 10px;
            line-height: 13px;
        }
        .db{
            text-transform: uppercase; 
            color: #262672; 
            font-size:19px;
            margin-bottom: 1px;
            font-weight: 700;
            margin: 0 ;
            font-family: "Aleo", serif;
            font-optical-sizing: auto;
            font-style: normal;
        }
        .SNMS{
            font-family: "Shafarik", serif;
            font-weight: 400;
            font-style: italic;
        }
        .add{
            font-family: "Roboto", serif;
            font-optical-sizing: auto;
            font-size:12px ;
        }
        .letter{
            font-family: "Roboto", serif;
            font-optical-sizing: auto;
        }
        .bg{
            padding: 0 20px ;
        }
        .letter p{
            font-size: 12px;
        }
        .details li{
            font-size: 12px;
        }
    </style>
    <script>
        window.onload = function () {
            window.print();
        };
        // function printPage() {
        //     var printButton = document.getElementById('printButton');
        //     printButton.style.display = 'none'; // Hide the button

        //     window.print(); // Trigger the print dialog

        //     // After the print dialog is closed, show the button again (optional)
        //     window.onafterprint = function() {
        //         printButton.style.display = 'inline-block';
        //     };
            
        // }
    </script>
</head>
<body>
    <?php
    $studentName = $student['firstname'] . " " . $student['middlename'] . " " . $student['lastname'];
    ?>
    <!-- <button id="printButton"  onclick="printPage()">Print</button> -->
    <div class="">
        <div class="bg" style="width:100%;justify-content:center; display: flex;align-items:center">
            <div style="width:100%;display:flex;align-items:center">
                <div style="width:15%;padding:10px 0;margin-right:-20px">
                    <br>
                    <img src="<?php echo base_url('uploads/school_content/admin_small_logo/' . $sch_setting->admin_small_logo) ?>" alt="" style="width:75px">
                    <p style="font-size:10px;text-transform:uppercase;margin:2px 0"><b>AFF. no: <?php echo $sch_setting->affilation_no; ?></b></p>
                </div>
                <div class="Head p-0" style="text-align:center;width:85%;padding:10px">
                    <p class="m-0 SNMS"><i>Sree Narayanya Mandirs Samiti's </i></p>
                    <h2 class="db"><?php echo $sch_setting->name; ?> </h2>
                    <p class="add m-0"><b><?php echo $sch_setting->address; ?></b></p>
                    <p class="add m-0"><b>Email:<?php echo $sch_setting->email; ?> Website:<?php echo $sch_setting->website; ?></b></p>
                    <p class="add m-0"><b>Contact no: <?php echo $sch_setting->phone; ?> UDISE No :<?php echo $sch_setting->school_UDISE; ?></b></p>
                </div>
            </div>
        </div>
    <div class="letter" style="padding:20px;">
    <p style="font-size: 18px;font-weight: 700; text-align: center">Confirmation of Admission Letter</p>
        <br>
        <div class="header">
            <!-- <p><b><?php //echo $sch_setting->name; ?></b></p> -->
        </div>
        <p><strong>Date : <?php echo date('d-m-Y'); ?></strong></p>
        <p>Dear <strong><?php echo $student['father_name']; ?></strong>,</p>

        <p>This is to inform you that your ward, <strong><?php echo $studentName; ?></strong> admission in <strong><?php echo $student['class']; //. " " . $student['section']; ?></strong> at <strong><?php echo $sch_setting->name; ?></strong> has been confirmed.</p>

        <div class="details">
            <ul>
                <li><strong>Name of the student :</strong> <?php echo $studentName; ?></li>
                <li><strong>Class :</strong> <?php echo $student['class']; //. " " . $student['section']; ?></li>
                <li><strong>Academic Year  :</strong> <?php echo $current_session_name; ?></li>
                <li><strong>Application No :</strong> <?php echo $student['application_no']; ?></li>
                <li><strong>Registration date :</strong> <?php echo $student['admission_date'] != "" ? date('d-m-Y', strtotime($student['admission_date'])) : ""; ?></li>
            </ul>
        </div>

        <p>Regards,</p>
        <p>Signature</p>
        <p>Name of the Admin : <?php echo $userdata['name']; ?></p>
       <p> <b>Administrative Office</b></p><br>
       <p> <b><?php echo $sch_setting->name; ?></b></p>
    </div>
    </div>

</body>

</html>