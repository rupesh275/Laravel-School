<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $this->lang->line('fees_receipt'); ?></title>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<style>
    body {
        background: white;
        margin-top: 120px;
        margin-bottom: 120px;
    }

    .table thead th {
        border: 1px solid #dee2e6 !important;
    }

    p {
        margin: 0px;
    }

    .table td,
    .table th {
        border: 1px solid #dee2e6;
    }
</style>
<?php $currency_symbol = $this->customlib->getSchoolCurrencyFormat();?>
<?php
    $invDate = '';
    $invNo = '';
    $payment_mode = '';
    $amount = '';
    // if (isJSON($feeList->amount_detail)) {
    //     $fee    = json_decode($feeList->amount_detail);
    //     // print_r($fee);
    //     $record = $fee->{$sub_invoice_id};
    //     $invDate = !empty($record->date) ? date('d-m-Y',strtotime($record->date)) : '';
    //     $invNo = !empty($record->inv_no) ? $record->inv_no : '';
    //     $payment_mode = !empty($record->payment_mode) ? $record->payment_mode : '';
    //     $amount = !empty($record->amount) ? $record->amount : '';
    // }
?>
<body>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body p-0">
                        <div class="row pt-2">
                            <div class="w-25 text-left mb-3">
                                <img src="<?php echo base_url('uploads/school_content/admin_small_logo/' . $settinglist['admin_small_logo']); ?>" alt="" style="padding: 20px 0 0 29px; width: 138px;">
                            </div>

                            <div class="w-50 text-center mt-5">
                                <h2 class="name"> <?php echo $settinglist['name']; ?></h2>
                                <p><?php echo $settinglist['address']; ?> </p>
                                <p><b>Phone No.</b> <?php echo $settinglist['phone']; ?></p>
                                <p><b>Email Id</b> <?php echo $settinglist['email']; ?></p>
                                <p><b>Website</b> <?php echo base_url(); ?></p>
                            </div>
                        </div>

                        <hr>
                        <div class="row pb-5 pl-3 pr-3">
                            <div class="w-50 pl-5 text-left">
                                <p class="font-weight-bold mb-4"><b> Student Name: </b><?php  echo $this->customlib->getFullName($feeList->firstname,$feeList->middlename,$feeList->lastname,$sch_setting->middlename,$sch_setting->lastname); ?></p>
                                <p class="mb-1"><b> INVOICE TO:</b> <?php echo $invNo; ?></p>
                                <p class="mb-1"><b> Class:</b> <?php echo $student['class']; ?></p>

                            </div>
                            
                            <div class="w-50 pr-5 text-right">
                                <p class="font-weight-bold mb-4"><b> Reciept Date:</b><?php echo $invDate; ?></p>
                                <p class="mb-1"><b>Admission No. </b> <?php echo $student['admission_no']; ?></p>
                                <p class="mb-1"><b>Section </b> <?php echo $student['section']; ?></p>

                            </div>
                        </div>

                        <div class="row p-2">
                            <div class="col-md-12">
                            <?php
                            if (!empty($feeList)) {
                                ?>

                                <table class="table table-striped table-responsive" style="font-size: 14px;">
                                    <thead>
                                    <th style="width: 200px;"><?php echo $this->lang->line('fees_group'); ?></th>
                                    <th><?php echo $this->lang->line('fees_code'); ?></th>
                                    <th  class=""><?php echo $this->lang->line('due_date'); ?></th>
                                    <th class=""><?php echo $this->lang->line('status'); ?></th>
                                    <th  class="text text-right"><?php echo $this->lang->line('amount'); ?></th>
                                    <th  class="text text-center"><?php echo $this->lang->line('payment_id'); ?></th>
                                    <th  class="text text-center"><?php echo $this->lang->line('mode'); ?></th>
                                    <th  class=""><?php echo $this->lang->line('date'); ?></th>
                                    <th  class="text text-right"><?php echo $this->lang->line('paid'); ?></th>
                                    <th  class="text text-right"><?php echo $this->lang->line('fine'); ?></th>
                                    <th class="text text-right" ><?php echo $this->lang->line('discount'); ?></th>
                                    <th  class="text text-right"><?php echo $this->lang->line('balance'); ?></th>
                                    <!-- <th></th> -->
                                    </thead>
                                    <tbody>
                                        <?php
                                        $amount = 0;
                                        $discount = 0;
                                        $fine = 0;
                                        $total = 0;
                                        $grd_total = 0;

                                        if (empty($feeList)) {
                                            ?>
                                            <tr>
                                                <td colspan="11" class="text-danger text-center">
                                                    <?php echo $this->lang->line('no_transaction_found'); ?>
                                                </td>
                                            </tr>
                                            <?php
                                        } else {
                                            $fee_discount = 0;
                                            $fee_paid = 0;

                                            $fee_fine = 0;
                                            $alot_fee_discount = 0;
                                            if ($feeList->is_system) {
                                                $feeList->amount = $feeList->student_fees_master_amount;
                                            }
                                            if (!empty($feeList->amount_detail)) {
                                                $fee_deposits = json_decode(($feeList->amount_detail));

                                                foreach ($fee_deposits as $fee_deposits_key => $fee_deposits_value) {
                                                    $fee_paid = $fee_paid + $fee_deposits_value->amount;
                                                    $fee_discount = $fee_discount + $fee_deposits_value->amount_discount;
                                                    $fee_fine = $fee_fine + $fee_deposits_value->amount_fine;
                                                }
                                            }
                                            $feetype_balance = $feeList->amount - ($fee_paid + $fee_discount);
                                            ?>
                                            <tr  class="dark-gray" >

                                                <td><?php
                                                    echo $feeList->name;
                                                    ?></td>
                                                <td><?php echo $feeList->code; ?></td>
                                                <td class="">

                                                    <?php
                                                    if ($feeList->due_date == "0000-00-00") {
                                                        
                                                    } else {

                                                        echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($feeList->due_date));
                                                    }
                                                    ?>
                                                </td>
                                                <td class="">
                                                    <?php
                                                    if ($feetype_balance == 0) {
                                                        echo $this->lang->line('paid');
                                                    } else if (!empty($feeList->amount_detail)) {
                                                        ?><?php echo $this->lang->line('partial'); ?><?php
                                                    } else {
                                                        echo $this->lang->line('unpaid');
                                                    }
                                                    ?>

                                                </td>
                                                <td class="text text-right"><?php echo $currency_symbol . $feeList->amount; ?></td>

                                                <td colspan="3"></td>
                                                <td class="text text-right"><?php
                                                    echo ($currency_symbol . number_format($fee_paid, 2, '.', ''));
                                                    ?></td>
                                                <td class="text text-right"><?php
                                                    echo ($currency_symbol . number_format($fee_fine, 2, '.', ''));
                                                    ?></td>
                                                <td class="text text-right"><?php
                                                    echo ($currency_symbol . number_format($fee_discount, 2, '.', ''));
                                                    ?></td>
                                                <td class="text text-right"><?php
                                                    $display_none = "ss-none";
                                                    if ($feetype_balance > 0) {
                                                        $display_none = "";


                                                        echo ($currency_symbol . number_format($feetype_balance, 2, '.', ''));
                                                    }
                                                    ?>

                                                </td>



                                            </tr>

                                            <?php
                                            $fee_deposits = json_decode(($feeList->amount_detail));
                                            if (is_object($fee_deposits)) {


                                                foreach ($fee_deposits as $fee_deposits_key => $fee_deposits_value) {
                                                    ?>
                                                    <tr class="white-td">
                                                        <td colspan="5" class="text-right"><img src="<?php echo base_url(); ?>backend/images/table-arrow.png" alt="" /></td>
                                                        <td class="text text-center">


                                                            <?php echo $feeList->student_fees_deposite_id . "/" . $fee_deposits_value->inv_no; ?>



                                                        </td>
                                                        <td class="text text-center"><?php echo $fee_deposits_value->payment_mode; ?></td>
                                                        <td class="text text-center">

                                                            <?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($fee_deposits_value->date)); ?>
                                                        </td>
                                                        <td class="text text-right"><?php echo ($currency_symbol . number_format($fee_deposits_value->amount, 2, '.', '')); ?></td>
                                                        <td class="text text-right"><?php echo ($currency_symbol . number_format($fee_deposits_value->amount_fine, 2, '.', '')); ?></td>
                                                        <td class="text text-right"><?php echo ($currency_symbol . number_format($fee_deposits_value->amount_discount, 2, '.', '')); ?></td>

                                                    </tr>
                                                    <?php
                                                }
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                <?php
                            }
                            ?>
                            </div>

                        </div>

                        <div class="p-3">
                            <p><?php echo ucwords(markSheetDigitTwo($amount)); ?> Only</p>
                            <div class="row">

                                <div class="col-4">
                                    <p><b>Mode of Payment : <?php echo $payment_mode; ?></b></p>
                                </div>
                                <div class="col-4">
                                    <p><b>Bank:</b></p>

                                </div>
                                <div class="col-4">
                                    <p><b>Instrument No.</b></p>
                                </div>
                                <div class="col-9 pt-4 mt-5">
                                    <p style="border-bottom: 2px dotted #000;"><b> Narration:</b></p>
                                </div>
                                <div class="col-3">
                                    <div class=" p-4 border mt-5 mr-2">
                                        Receiver's Signature
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>