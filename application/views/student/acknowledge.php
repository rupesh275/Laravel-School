<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $this->customlib->getAppName(); ?></title>
    <link href="<?php echo base_url(); ?>uploads/school_content/admin_small_logo/<?php $this->setting_model->getAdminsmalllogo(); ?>" rel="shortcut icon" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Charm&family=Darker+Grotesque:wght@500&family=Kodchasan:wght@200&family=Montserrat+Alternates:wght@600&family=Montserrat:wght@100;200;400;600&family=Raleway:wght@500&family=Roboto:ital,wght@1,300&display=swap" rel="stylesheet">
</head>
<style>
    .dflex {
        display: flex;
    }

    body {
        font-family: 'Raleway', sans-serif;
        color: #000;
        font-weight: 500;
    }

    table {
        border-collapse: collapse;
        width: 80%;
        font-family: 'Raleway', sans-serif;

    }

    td,
    th {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 4px;
        font-size: 13px;
        font-family: 'Roboto', sans-serif;
    }

    tr:nth-child(even) {
        background-color: #dddddd;
    }

    p {
        margin-bottom: 3px;
        font-size: 14px;
    }

    .name {
        width: 103px;
        border-bottom: 1px solid;
        font-weight: 600;
    }

    .To {
        font-size: 14px;
    }

    .para {
        width: 158px;
    }

    .mb {
        margin-bottom: 1px;
        margin-top: 5px;
    }

    .bg {
        background: #fac669 !important;
    }

    .db {
        color: darkblue !important;
    }

    .text-center {
        text-align: center;
    }

    .p-0 {
        padding: 0;
    }

    .m-0 {
        margin: 0px;
    }

    .Head p {
        font-size: 12px;
    }
</style>

<body>
    <div class="" style="margin:20px 20px 0 20px">
        <div class="bg" style="width:100%;justify-content:center; display: flex;align-items:center">
            <div style="width:100%;display:flex;align-items:center">
                <div style="width:7%;padding:10px">
                    <img src="<?php echo base_url('uploads/school_content/admin_small_logo/' . $sch_setting->admin_small_logo) ?>" alt="" style="width:95px">
                </div>
                <div class="Head p-0 text-center" style="width:85%;padding:10px">
                    <h2 class="db" style="text-transform: uppercase; font-family: inherit; font-weight: 800; color: #262672; font-size: 21px; margin-bottom: 1px; font-style: inherit;"><?php echo $sch_setting->name; ?> </h2>
                    <p class="m-0"><i>(Managed By Sree Narayanya Mandirs Samiti) </i></p>
                    <p class="m-0"><b><?php echo $sch_setting->address; ?></b></p>
                    <p class="m-0"><b>Email:<?php echo $sch_setting->email; ?> Website:WWW.sngcentralschool.org</b></p>
                    <p class="m-0"><b>Tel no: <?php echo $sch_setting->phone; ?></b></p>
                </div>
            </div>
        </div>
        <div class="" style="text-align:center;width:100%;">
            <p style="font-size: 18px;font-weight: 700;font-family: math;">ACKNOLWEDGEMENT FOR DOCUMENT RECEIVED</p>
            <!-- <h4 style="font-size:14px;">Academic year: <b>2014 to 2015</b></h4> -->
            <p></p>
        </div>
        <div style="width:100%">
            <div style="justify-content:space-between;display:flex;width:100%">
                <div class="To" style="width:40%">
                    <p>To</p>
                    <p><b>Name of the Parent/Guardian</b></p>
                    <p style="display: initial;"><?php echo $student['father_name']; ?></p>
                    <p><b>Address</b></p>
                    <p style="display: initial;    line-height: 24px;"><?php echo $student['current_address']; ?></p>
                </div>
                <div style="width:40%">
                    <p class="dflex mb"><span class="para">Date</span> : <?php echo date('d-m-Y'); ?></p>
                    <p class="dflex mb"><span class="para">Name of the student</span>: <b> <?php echo $student['firstname'] . " " . $student['lastname']; ?></b></p>
                    <p class="dflex mb"><span class="para">Class Enrolled</span>: <?php echo $student['class']; ?> </p>
                    <p class="dflex mb"><span class="para">Section</span>: <?php echo $student['section']; ?></p>
                    <p class="dflex mb"><span class="para">D.O.A</span>: <?php echo date('d-m-Y', strtotime($student['admission_date'])); ?></p>
                </div>
            </div>
            <div>
                <br>
                <br>
                <div style="text-align:center">
                    <p><b>SUBJECT</b> : Acknowledgement for Receipt of Documents</p>
                </div>
                <!-- <p>Dear Sir/Madam,</p> -->
                <!-- <span class="name" style="padding:0 12px;text-align:center"><?php echo $student['father_name']; ?></span> -->
                <p>Dear Parent, </p>
                <p> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; We kindly acknowledge the receipt of the documents you have provided to SNGCS for admission purposes.</p>
                <p><b> Thank you for your cooperation</b></p>
                <div class="dflex">
                    <p><b>Yours sincerely</b></p>
                </div>
                <br>
                <p>[Your Name]</p>
                <table>
                    <tbody>
                        <tr>
                            <th style="width: 50%;">Documents</th>
                            <th style="width: 50%;text-align:center">Status</th>
                        </tr>
                        <?php
                        foreach ($document as $key => $documentRow) {
                        ?>
                            <tr>
                                <td style="text-align:left;"><?php echo $documentRow['item_name']; ?></td>
                                <td style="text-align:center"><?php echo $documentRow['status']; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <br>
                <p style="font-size: 13px;">
                    Note :We would humbly request you to submit the remaining documents at your earliest convenience to ensure a confirmed admission.( Only if not submitted )
                </p>

            </div>
           
           

            <div class="dflex">
                <p style="font-size: 15px;font-weight: 600;">Yours Faithfully</p>
            </div>
        </div>
    </div>
</body>

</html>