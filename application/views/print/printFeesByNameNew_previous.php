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

    .table td,
    .table th {
        border: 1px solid #0000009c !important;
    }

    .card {
        border: 2px solid #000000;
    }

    p {
        margin: 0px;
    }

    hr {
        margin-top: 5px;
        margin-bottom: 5px;
        border: 0;
        border-top: 1px solid rgb(0 0 0 / 68%);
    }

    .table td,
    .table th {
        border: 2px solid #000000;
    }

    .headingfnt {
        font-size: 15px;
    }

    .dflex p {
        width: 151px;
    }

    .dflex {
        display: flex;
    }

    .name {
        font-size: 28px;
    }

    .dtitle {
        font-size: 18px;
        padding-left: 13px;
    }

    .content {
        font-size: 16px;
        font-weight: 12px;
    }

    .table thead th {
        border: 1px solid #00000099 !important;
    }

    .table td,
    .table th {
        padding: 2px 8px;
        font-weight: 500;
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
                        <div class="" style="display: flex; justify-content: space-between;  margin: 1px 10px;">
                            <div>
                                <b class="text-uppercase" style="font-size:15px">Website </b>:<?php echo $settinglist['website']; ?><br>
                                <b class="text-uppercase" style="font-size:15px">Email Id </b>:<?php echo $settinglist['email']; ?>
                            </div>
                            <div>
                                <b class="text-uppercase" style="font-size:15px">C.B.S.E. Affliation No </b>:<?php echo $settinglist['affilation_no']; ?><br>
                                <b class="text-uppercase" style="font-size:15px">UDISE Code </b>:<?php echo $settinglist['school_UDISE']; ?>
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
                            <p class="text-uppercase"><b>Student Name</b></p>: <?php echo $student['aadhar_name'] ;//$this->customlib->getFullName($student['firstname'], $student['middlename'], $student['lastname'], $sch_setting->middlename, $sch_setting->lastname); ?>
                        </div>
                        <div style="display: flex;justify-content: space-between;">
                            <div class="col-md-12">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="border-0  small text-center dtitle" style="width:10%">Sr.No</th>
                                            <th class="border-0  small text-center dtitle" style="width:70% ">Particulars</th>
                                            <th class="border-0  small text-right dtitle" style="width:20%">Total</th>
                                        </tr>
                                    </thead>
                                    <?php 
                                    $total = 0;
                                     $payment_mode = '';
                                     //echo "<pre>";print_r($feeList);die();
                                    if (!empty($feeList)) { ?>
                                        <tbody>
                                            <?php
                                            $i = 1;
                                            $total = 0;

                                            $discount = $this->feediscount_model->getdiscountbypayment_session_id($invo_no, $student_session_id, $receipt_session_id);


                                            $total_disc = 0;
                                            $total_fine = 0;
                                            foreach ($feeList as  $value) {
                                                //echo "<pre>";print_r($value);
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
                                                    $fine = !empty($record->amount_fine) ? $record->amount_fine : 0;
                                                    $total_disc += $disc_amt;
                                                    $total_fine += $fine;
                                                } if($amount + $disc_amt != 0) { ?>
                                                <tr style="line-height:30px;">
                                                    <td class="text-center content"><?php echo $i; ?></td>
                                                    <td class="text-left content"><?php echo $value->type . "-" . $value->dis_name; ?> <br>
                                                    </td>
                                                    <td class="text-right content"><?php echo number_format($amount + $disc_amt, 2); ?><br>
                                                        <?php //if ($discount_amt > 0) {
                                                        //echo number_format($discount_amt, 2);
                                                        //} 
                                                        ?>
                                                    </td>
                                                    <?php $total += $amount; } ?>
                                                </tr>
                                            <?php $i++;
                                            } if($receipt->fine>0) {
                                                ?> 
                                                <tr style="line-height:30px;">
                                                    <td class="text-center content"><?php echo $i; ?></td>
                                                    <td class="text-left content"><?php echo "Fine"; ?> <br>
                                                    </td>
                                                    <td class="text-right content"><?php echo number_format($receipt->fine, 2); ?><br>

                                                        <?php //if ($discount_amt > 0) {
                                                        //echo number_format($discount_amt, 2);
                                                        //} 
                                                        ?>
                                                    </td>
                                                    <?php $total += $receipt->fine; ?>
                                                </tr>  
                                            <?php } ?>                                          
                                        </tbody>
                                        <?php  if ($total_balance > 0) { ?>
                                            <tfoot>
                                                <td></td>
                                                <td class="text-right content"><b> Balance.: </b></td>
                                                <td class="text-right content"><b> <?php echo $currency_symbol . ' ';
                                                                                    echo number_format($total_balance, 2); ?> </b></td>
                                            </tfoot>
                                        <?php }  ?>
                                        <?php if ($total_fine > 0) { ?>
                                            <tfoot>
                                                <td></td>
                                                <td class="text-right content"><b> Fine: </b></td>
                                                <td class="text-right content"><b> <?php echo $currency_symbol . ' ';
                                                                                    echo number_format($total_fine, 2); ?> </b></td>
                                            </tfoot>
                                        <?php } ?>
                                        <?php
                                        $discount = $this->feediscount_model->getdiscountbypayment_session_id($invo_no, $receipt->student_session_id, $receipt_session_id);
                                        $dis_name = "";
                                        foreach ($discount as $dis) {
                                            $feediscount = $this->feediscount_model->get($dis['fees_discount_id']);
                                            if ($dis_name == '') {
                                                $dis_name = "(" . $feediscount['name'] . ")";
                                            } else {
                                                $dis_name = $dis_name . " + (" . $feediscount['name'] . ")";
                                            }
                                        }
                                        
                                        if (!empty($discount)) {
                                            if ($total_disc > 0) {
                                        ?>
                                                <tfoot>
                                                    <td></td>
                                                    <td class="text-right content"><b><?php echo $dis_name; ?></b></td>
                                                    <td class="text-right content"><b><?php if ($total_disc > 0) {
                                                                                            echo number_format($total_disc, 2);
                                                                                        } ?></b></td>
                                                </tfoot>
                                        <?php }
                                        }
                                        ?>
                                        <tfoot>
                                            <td></td>
                                            <td class="text-right content"><b> Total: </b></td>
                                            <td class="text-right content"><b> <?php echo $currency_symbol . ' ';
                                                                                echo number_format($total + $total_fine, 2); ?> </b></td>
                                        </tfoot>
                                        <?php 
                                        if ($receipt->prev_balance > 0) {
                                            $preamt = $receipt->prev_balance;
                                        } else {
                                            $preamt = $total_paid - $total;
                                        } ?>
                                        <?php if ($total_prev_balance > 0) { ?>
                                            <tfoot>
                                                <td></td>
                                                <td class="text-right content"><b> Previous: </b></td>

                                                <td class="text-right content"><b> <?php echo $currency_symbol . ' ';
                                                                                    echo number_format($total_prev_balance, 2); ?> </b></td>
                                            </tfoot>
                                        <?php }   ?>
                                    <?php } ?>
                                </table>
                            </div>
                        </div>

                        <p class="dtitle"><?php echo ucwords(markSheetDigitTwo($total)); ?> Only</p>
                        <div class="d-flex" style="padding-bottom: 16px;">
                            <div class="col-6">
                                <p style="font-size: 17px;"><b>Mode of Payment : <?php echo $payment_mode; ?></b></p>
                                <?php if (!empty($cheque)) {
                                    if($receipt->note=='') {
                                ?>
                                    <p style="font-size: 17px;"><b>Chq.No:<?php echo $cheque['chq_no'] . ",Chq.Date : " . date('d-m-y', strtotime($cheque['chq_date'])) . " , Bank : " . $cheque['chq_bank']; ?></b></p>
                                <?php } } ?>
                                <?php if ($receipt->note != '') {
                                ?>
                                    <p style="font-size: 17px;"><b>Note : <?php echo $receipt->note; ?></b></p>
                                <?php } ?>
                                <?php if ($payment_mode == 'gateway') {
                                ?>
                                    <p style="font-size: 15px;"><b>Note : Kindly note : If Gateway is aborted from your end after payment, a fine of Rs.500/- will be applicable and the Receipt will be considered as invalid</b></p>

                                <?php } ?>                                 
                            </div>
                            <div class="col-6" style="text-align:right">
                                <div style="font-size: 17px;margin-left:60px;">
                                    <b> For <?php echo $settinglist['name']; ?></b>
                                    <div class="ml-3 dtitle" style="padding-top: 35px;">
                                        Authorized Signature
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div> <br>
        <div class="d-flex" style="justify-content:space-between">
            <div class="dtitle" style="font-size: 15px;padding-left:0">
                <b>Created by</b> : <?php echo $receipt->created_by; ?><br>
                Fees once paid will not be Adjusted or Refunded.<br>
            </div>
            <div>
                <p class="dtitle" style="font-size: 15px;"><b>Time: </b><?php echo date('d-m-Y h:m:s', strtotime($receipt->created_at)); ?></p>
            </div>
        </div>
    </div>
</body>

</html>