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
    <?php /*
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
    } */?>

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
                            $session = $this->db->where('id', $session)->get('sessions')->row_array();
                            ?>

                            <div class=" text-center mt-5">
                                <p class="name"> <b><?php echo $settinglist['name']; ?></b></p>
                                <p class="headingfnt"><?php echo $settinglist['address']; ?> </p>
                                <!-- <p class="headingfnt"><b>Phone No.</b> <?php echo $settinglist['phone']; ?></p> -->
                                <p class="headingfnt"><b>Email Id</b> <?php echo $settinglist['email']; ?> <b>Website</b> <?php echo base_url(); ?></p>
                                <!-- <p class="headingfnt"><b>Website</b> <?php echo base_url(); ?></p> -->
                                <p class="headingfnt"><b>Phone No.</b> <?php echo $settinglist['phone']; ?>
                                <h3><b>Fees Receipt</b> </h3>
                                <!-- <h4><b>AY <?php //echo $session['session']; ?></b> </h4> -->
                            </div>
                        </div>

                        <hr>

                        <div class="row pb-5 pl-3 pr-3">
                            <div class="w-50 pl-5 text-left">
                                <p class="font-weight-bold  dtitle"><b> Student Name: </b><?php echo $this->customlib->getFullName($student['firstname'], $student['middlename'], $student['lastname'], $sch_setting->middlename, $sch_setting->lastname); ?></p>
                                <p class="mb-1 dtitle"><b> Class & Division:</b> <?php echo $student['class']; ?> <?php echo $student['section']; ?></p>
                                <p class="mb-1 dtitle"><b>Roll No : </b> <?php echo $student['roll_no']; ?></p>
                                <p class="mb-1 dtitle"><b>Father's Name :</b> <?php echo $student['father_name']; ?></p>
                                <!-- <p class="mb-1 dtitle"><b> Receipt No:</b> <?php echo $invo_no; ?></p> -->

                            </div>
                            <div class="w-50 pr-5 text-right">
                                <p class="font-weight-bold  dtitle"><b> Reciept No:</b> <?php echo $penaltylist['receipt_id']; ?></p>
                                <p class="font-weight-bold  dtitle"><b> Reciept Date:</b> <?php echo date('d-m-Y',strtotime($penaltylist['fee_date'])); ?></p>
                                <!-- <p class="mb-1 dtitle"><b>Roll No. </b> <?php //echo $student['roll_no']; ?></p> -->
                                <p class="mb-1 dtitle"><b> Academic Year :</b> <?php echo $session['session']; ?></p>

                            </div>
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
                                    <?php if (!empty($penaltylist)) { ?>
                                        <tbody>
                                            <?php /*
                                            $i = 1;
                                            $total = 0;
                                            $discount = $this->feediscount_model->getdiscountbypayment_id($invo_no,$student_session_id); 
                                            $total_disc=0;
                                            foreach ($feeList as  $value) {
                                                $invDate = '';
                                                $payment_mode = '';
                                                $amount = '';
                                                if (isJSON($value->amount_detail)) {
                                                    $fee    = json_decode($value->amount_detail);
                                                    // $receipt = $this->db->where('id', $sub_invoice_id)->from('fee_receipt_no_' . $session)->get()->row_array();
                                                    $record = $fee->{$sub_invoice_id};
                                                    $invDate = !empty($record->date) ? date('d-m-Y', strtotime($record->date)) : '';
                                                    $invNo = !empty($record->inv_no) ? $record->inv_no : '';
                                                    $payment_mode = !empty($record->payment_mode) ? $record->payment_mode : '';
                                                    $note = !empty($record->note) ? $record->note : '';
                                                    $amount = !empty($record->amount) ? $record->amount : 0;
                                                    $disc_amt = !empty($record->amount_discount) ? $record->amount_discount : 0;
                                                    $total_disc+=$disc_amt;
                                                    // print_r($record);
                                                } */?>
                                                <tr style="line-height:30px;">
                                                    <td class="text-center content"><?php echo "1"; ?></td>
                                                    <td class="text-left content"><?php echo $penaltylist['subject']; ?> <br>
                                                    </td>
                                                    <td class="text-right content"><?php  echo number_format($penaltylist['fine'] , 2); ?><br>

                                                         <?php //if ($discount_amt > 0) {
                                                                //echo number_format($discount_amt, 2);
                                                                //} 
                                                                ?> 
                                                    </td>
                                                </tr>
                                            <?php 
                                            //} ?>

                                        </tbody>
                                        <?php /*?>
                                        <tfoot>
                                            <td></td>
                                            <td class="text-right content"><b> Balance: </b></td>
                                            <td class="text-right content"><b> <?php echo $currency_symbol . ' ';
                                                                                echo number_format($total_balance, 2); ?> </b></td>
                                        </tfoot>
                                        <?php if ($record->amount_fine > 0) { ?>
                                            <tfoot>
                                                <td></td>
                                                <td class="text-right content"><b> Fine: </b></td>
                                                <td class="text-right content"><b> <?php echo $currency_symbol . ' ';
                                                                                    echo number_format($record->amount_fine, 2); ?> </b></td>
                                            </tfoot>
                                        <?php } ?>
                                        <?php
                                        $discount = $this->feediscount_model->getdiscountbypayment_id($invo_no,$student_session_id); 
                                        if (!empty($discount)) {
                                            $feediscount = $this->feediscount_model->get($discount['fees_discount_id']);
                                            if ($total_disc > 0) {
                                        ?>
                                                <tfoot>
                                                    <td></td>
                                                    <td class="text-right content"> <b><?php echo "(" . $feediscount['name'] . ")"; ?></b></td>
                                                    <td class="text-right content"><b> <?php if ($total_disc > 0) {
                                                                                            echo number_format($total_disc, 2);
                                                                                        } ?></b></td>
                                                </tfoot>
                                        <?php }
                                        } */?>
                                        <tfoot>
                                            <td></td>
                                            <td class="text-right content"><b> Total: </b></td>
                                            <td class="text-right content"><b> <?php echo $currency_symbol . ' ';
                                                                                echo number_format($penaltylist['fine'], 2); ?> </b></td>
                                        </tfoot>

                                    <?php } ?>
                                </table>
                            </div>
                        </div>
                        <div class="p-3">
                            <p class="dtitle"><?php echo ucwords(markSheetDigitTwo($penaltylist['fine'])); ?> Only</p>
                            <div class="row">
                                <div class="col-4">
                                    <?php //if ($payment_mode == "Cheque") {
                                        ?>
                                        <!-- <p class="dtitle"><b>Mode of Payment : <?php // echo $payment_mode; ?></b></p> -->
                                    <?php //}?>
                                    <?php //if (!empty($note)) {  
                                    ?>
                                    <!-- <p class="dtitle"><b>Note : <?php //echo $note; ?></b></p> -->
                                    <?php //} ?>
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
                                <div class="col-5" style="font-size: 19px;margin: bottom 15px;" >
                                 <b> For  <?php echo $settinglist['name'];?></b>
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