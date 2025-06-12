<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $this->lang->line('fees_receipt'); ?></title>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script async src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<style>
    body {
        background: white;
        margin-top: 20px;
    }

    .table thead th {
        border: 2px solid #000000 !important;
    }

    p {
        margin: 0px;
    }

    .table td,
    .table th {
        border: 2px solid #000000;
    }

    .headingfnt {
        font-size: 18px;
    }

    .name {
        font-size: 28px;
    }

    .dtitle {
        font-size: 20px;
        font-weight: 12px;
    }

    .content {
        font-size: 22px;
        font-weight: 12px;
    }
</style>
<?php $currency_symbol = $this->customlib->getSchoolCurrencyFormat(); ?>

<body>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body p-0">
                        <div class="" style="display: flex; justify-content: space-between;  margin: 1px 10px; font-size: 11px;">
                            <div>
                                <b class="text-uppercase">Email Id </b>:<?php echo $settinglist['email']; ?>
                            </div>
                            <div>
                                <b class="text-uppercase">Website </b>:<?php echo $settinglist['website']; ?>
                            </div>

                        </div>
                        <div class="row pt-2" style="display: flex;align-items: center;">
                            <div class="text-left mb-3" style="width:17%">
                                <img src="<?php echo base_url('uploads/school_content/admin_small_logo/' . $settinglist['admin_small_logo']); ?>" alt="" style="padding: 0 0 0 29px; width: 138px;">
                            </div>
                            <?php
                            $session = $this->db->where('id', $session)->get('sessions')->row_array();
                            ?>

                            <div class="text-center" style="width:70%">
                                <p class="name"><b><?php echo $settinglist['name']; ?></b></p>
                                <p class="headingfnt"><?php echo $settinglist['address']; ?> </p>
                                <p class="headingfnt"><b>Phone No.</b> <?php echo $settinglist['phone']; ?>
                                <h3><b>Fees Receipt [<?php echo $billing_session['session']; ?>]</b> </h3>
                            </div>
                        </div>
                        <hr>
                        <div style="margin-left:30px;display:flex;justify-content: space-between; margin: 0;">
                            <div class="w-30 text-left">
                                <div class="dflex dtitle">
                                    <p class="text-uppercase"><b> Receipt No</b></p>: <b><?php echo $invo_no; ?></b>
                                </div>
                                <div class="dflex dtitle">
                                    <p class="text-uppercase"><b> Receipt Date</b></p>: <b><?php echo $receipt_date; ?></b>
                                </div>

                                <!--<p class="mb-1 dtitle"><b>Father's Name :</b> <?php echo $student['father_name']; ?></p> -->
                                <!-- <p class="mb-1 dtitle"><b> Receipt No:</b> <?php echo $invo_no; ?></p> -->
                            </div>
                            <div class="w-30 text-center">
                                <p class="text-uppercase mb-1 dtitle"><b> Class & Division:</b> <?php echo $student['class']; ?> <?php echo $student['section']; ?></p>
                                <!--<p class="mb-1 dtitle"><b>Roll No : </b> <?php echo $student['roll_no']; ?></p>
                                <p class="mb-1 dtitle"><b>Father's Name :</b> <?php echo $student['father_name']; ?></p>
                                 <p class="mb-1 dtitle"><b> Receipt No:</b> <?php echo $invo_no; ?></p> -->
                            </div>
                            <div class="w-40 text-right" style="margin-right: 19px;">
                                <div class="text-uppercase dtitle" style="justify-content:right;display:flex"><b>Roll No : </b>
                                    <p style="width: 91px;"> <?php echo $student['roll_no']; ?> </p>
                                </div>
                                <div class="text-uppercase dtitle" style="display:flex"><b> Academic Year :</b>
                                    <p style="width: 91px;"><?php echo $receipt_session['session']; ?> </p>
                                </div>
                            </div>

                        </div>
                        <div class="dflex dtitle">
                            <p class="text-uppercase"><b>Student Name</b></p>: <?php echo $this->customlib->getFullName($student['firstname'], $student['middlename'], $student['lastname'], $sch_setting->middlename, $sch_setting->lastname); ?>
                        </div>
                        <div class="row p-2">
                            <div class="col-md-12">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="border-0  small font-weight-bold text-center dtitle" style="width:10%">Sr.No</th>
                                            <th class="border-0  small font-weight-bold text-center dtitle" style="width:70% ">Particulars</th>
                                            <th class="border-0  small font-weight-bold text-right dtitle" style="width:20%">Total</th>
                                        </tr>
                                    </thead>
                                    <?php if (!empty($fees_receipt_array)) { ?>
                                        <tbody>
                                            <?php
                                            $i = 1;
                                            $total = 0;
                                            foreach ($fees_receipt_array as  $value) {
                                                $invDate = '';
                                                $payment_mode = '';
                                                $amount = '';
                                            ?>
                                                <tr style="line-height:30px;">
                                                    <td class="text-center content"><?php echo $i; ?></td>
                                                    <td class="text-left content"><?php echo $value['type']; ?> <br>
                                                    </td>
                                                    <td class="text-right content"><?php echo number_format($value['amt'], 2); ?><br>

                                                    </td>
                                                    <?php $total += $value['amt']; ?>
                                                </tr>
                                            <?php $i++;
                                            } ?>

                                        </tbody>
                                        <tfoot>
                                            <td></td>
                                            <td class="text-right content"><b> Total: </b></td>
                                            <td class="text-right content"><b> <?php echo $currency_symbol . ' ';
                                                                                echo number_format($total, 2); ?> </b></td>
                                        </tfoot>
                                    <?php }  ?>
                                </table>
                            </div>
                        </div>
                        <div class="p-3">
                            <p class="dtitle"><?php echo ucwords(markSheetDigitTwo($total)); ?> Only</p>
                            <div class="row">
                                <div class="col-4">
                                    <?php 
                                    if ($value['payment_mode'] == "Cheque") {
                                    ?>
                                        <p class="dtitle"><b>Mode of Payment : <?php echo $value['payment_mode'];
                                                                                ?></b></p>
                                    <?php  } ?>
                                    <?php if (!empty($value['remarks'])) {
                                    ?>
                                        <p class="dtitle"><b>Note : <?php echo $value['remarks']; ?></b></p>
                                    <?php } ?>
                                </div>
                                <div class="col-4">
                                    <!-- <p class="dtitle"><b>Bank:</b></p> -->

                                </div>
                                <div class="col-4">
                                    <!-- <p class="dtitle"><b>Instrument No.</b></p> -->
                                </div>
                                <div class="col-7 pt-4 mt-5">
                                    <p class="dtitle" style="border-bottom: 2px dotted #000;"><b> Narration:</b></p>

                                </div>
                                <div class="col-5" style="font-size: 19px;margin: bottom 15px;">
                                    <b> For <?php echo $settinglist['name']; ?></b>
                                    <div class=" p-1 ml-3 dtitle mt-5">
                                        Authorized Signature
                                    </div>
                                </div>
                                <!-- <div class="col-3">
                                    <div class=" p-4 border mt-5 mr-2 dtitle">
                                        Authorized Signature
                                    </div>
                                </div> -->


                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div> <br>
    <p class="dtitle"> Fees once paid will not be Adjusted or Refunded.<br>
        Cheque subject to realisation.<br></p>
    <!-- Electronically generated receipt, no signature required.</p> -->
</body>

</html>