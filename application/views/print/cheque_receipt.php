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
<?php

// $invDate = '';
// $invNo = '';
// $payment_mode = '';
// $amount = '';
// if (isJSON($feeList->amount_detail)) {
//     $fee    = json_decode($feeList->amount_detail);
//     $receipt = $this->db->where('id', $sub_invoice_id)->from('fee_receipt_no_' . $session)->get()->row_array();
//     $record = $fee->{$receipt['id']};
//     // print_r($record);
//     $invDate = !empty($record->date) ? date('d-m-Y', strtotime($record->date)) : '';
//     $invNo = !empty($record->inv_no) ? $record->inv_no : '';
//     $payment_mode = !empty($record->payment_mode) ? $record->payment_mode : '';
//     $amount = !empty($record->amount) ? $record->amount : '';
// }
?>

<body>
    <?php
    $invDate = '';
    if (!empty($feeList)) {
        foreach ($feeList as  $value) {
            if (isJSON($value->amount_detail)) {
                $fee    = json_decode($value->amount_detail);
                // print_r($fee);
                // $receipt = $this->db->where('id', $sub_invoice_id)->from('fee_receipt_no_' . $session)->get()->row_array();
                $record = $fee->{$sub_invoice_id};
                $invDate = !empty($record->date) ? date('d-m-Y', strtotime($record->date)) : '';
            }
        }
    } ?>

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body p-0">
                        <div class="row pt-2">
                            <div class="w-25 text-left mb-3">
                                <img src="<?php echo base_url('uploads/school_content/admin_small_logo/' . $settinglist['admin_small_logo']); ?>" alt="" style="padding: 20px 0 0 29px; width: 138px;">
                            </div>
                            <?php
                            $session_copy = $session;
                            $session = $this->db->where('id', $session)->get('sessions')->row_array();
                            ?>

                            <div class=" text-center mt-5">
                                <p class="name"> <b><?php echo $settinglist['name']; ?></b></p>
                                <p class="headingfnt"><?php echo $settinglist['address']; ?> </p>
                                <!-- <p class="headingfnt"><b>Phone No.</b> <?php echo $settinglist['phone']; ?></p> -->
                                <p class="headingfnt"><b>Email Id</b> <?php echo $settinglist['email']; ?> <b>Website</b> <?php echo base_url(); ?></p>
                                <!-- <p class="headingfnt"><b>Website</b> <?php echo base_url(); ?></p> -->
                                <p class="headingfnt"><b>Phone No.</b> <?php echo $settinglist['phone']; ?>
                                <h3><b>Cheque Acknowledgement Letter</b> </h3>
                                <h5><b>AY <?php echo $session['session']; ?></b> </h5> 
                            </div>
                        </div>

                        <hr>

                        <div class="row " style="margin-left:30px;">
                            <div >
                            <p class="font-weight-bold ">
                                
                                Date : <?php if(isset($chq['created_at'])) { echo date('d-m-Y',strtotime($chq['created_at'])); } else { echo $created_date; } ?>,
                            </p>                                
                            <p class="font-weight-bold ">
                                Dear parent,
                            </p>
                            <p class="font-weight-bold;  ">
                                We thankfully acknowledge the receipt of your cheque with no : <?php echo $chq['chq_no']; ?>, dated : <?php echo date('d-m-Y',strtotime($chq['chq_date'])); ?> for Rs.<?php echo $chq['chq_amt']; ?> as the fees payment of your child <?php echo $this->customlib->getFullName($student['firstname'], $student['middlename'], $student['lastname'], $sch_setting->middlename, $sch_setting->lastname); ?>
                                on class : <?php echo $student['class']."-"; ?> <?php echo $student['section']; ?> with roll no <?php echo $student['roll_no']; ?> of the year <?php echo  $student_session['session']; ?>. Please bring this copy at the time of collecting original receipt.
                            </p>
                            </div>
                        </div>
                        <div class="p-3">
                            <div class="row">
                                <div class="col-4">
                                </div>
                                <div class="col-4">
                                </div>
                                <div class="col-7 pt-4 mt-5">                                    
                                </div>
                                <div class="col-5" style="font-size: 19px;margin: bottom 15px;" >
                                 <b> For  <?php echo $settinglist['name'];?></b>
                                    <div class=" p-1 ml-3 dtitle mt-5">
                                        Authorized Signature
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>    
    </div>
    <div class="d-flex" style="justify-content:space-between;margin:0 20px;align-items: center;">
        <div class="dtitle" style="font-size: 15px;padding-left:0;margin-left:80px;">
           <b> Created by </b>: <?php echo $chq['created_by'] ?><br>
            Fees once paid will not be Adjusted or Refunded.<br>
        </div>
        <div>
            <?php if(isset($source)) { ?>
            <p  style="font-size: 15px;margin-right:80px;"><b>Time  :</b><?php echo $created_at; ?>
            <?php }else { ?>
            <p  style="font-size: 15px;margin-right:80px;"><b>Time  :</b><?php echo date('d-m-Y h:m:s', strtotime($chq['created_at'])); ?>
            <?php } ?>
            </p>
        </div>
    </div>
</body>
</html>