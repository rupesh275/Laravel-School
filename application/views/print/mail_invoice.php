<html>
<head>
    <title> </title>
</head>
<body style="font-family: Helvetica, Arial, sans-serif; color: rgb(103, 103, 103); width: 100%; margin: 0px; cursor: auto; background-color: #ededf2;" class="ui-sortable">
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
    <?php $currency_symbol = $this->customlib->getSchoolCurrencyFormat();
    $session = $this->db->where('id', $session)->get('sessions')->row_array();
    ?>
    <div id="invoiceholder" style="width: 100%; height: 100%;">
        <div id="headerimage"></div>
        <div id="invoice" class="effect2" style="position: relative; margin: 0 auto; width: 900px; background: #fff;">
            <div id="invoice-top" style="border-bottom: 1px solid #e82b45; min-height: 120px; padding: 30px 30px 0;">
                <div class="info" style="display: block; float: left;">
                    <img src="<?php echo base_url('uploads/school_content/admin_small_logo/' . $settinglist['admin_small_logo']); ?>" alt="" style="width:100px;" />
                </div>
                <!--End Info-->
                <div class="title text-right" style="text-align: center;">
                    <h3 style="font-size: 1.8em; font-weight: 300; margin: 0px;"><b><?php echo $settinglist['name']; ?></b></h3>
                    <p style="text-align: center; font-size: 15px; color: #666; "><?php echo $settinglist['address']; ?></p>
                    <p style="text-align: center; font-size: 15px; color: #666; "><b>Email Id</b> <?php echo $settinglist['email']; ?> <b>Website</b> <?php echo base_url(); ?></p>
                    <p style="text-align: center; font-size: 15px; color: #666; line-height: 21px;"><b>Phone No.</b> <?php echo $settinglist['phone']; ?></p>
                    <h3><b>Fees Receipt [<?php echo $billing_session['session']; ?>]</b> </h3>
                </div>
                <!--End Title-->
            </div>
            <!--End InvoiceTop-->
            <div id="invoice-mid" style="min-height: 125px; padding: 30px 30px 0;">
                <div class="info" style="display: block; float: left;">
                    <p style="font-size: 17px;"><b>Student Name : <?php echo $this->customlib->getFullName($student['firstname'], $student['middlename'], $student['lastname'], $sch_setting->middlename, $sch_setting->lastname); ?></b></p>
                    <p style="font-size: 17px; color: #666; line-height: 21px;">
                    <b>Class & Division : </b> <?php echo $student['class']; ?> <?php echo $student['section']; ?><br /></p>
                    <p style="font-size: 17px; color: #666; line-height: 21px;"><b>Roll No : </b> <?php echo $student['roll_no']; ?><br /></p>                    
                </div>
                <div id="project" style="margin-left: 52%; text-align: right;">
                    <h2 style="font-size: 17px;"><b>Receipt No:</b> <?php echo $invo_no; ?><br></h2>
                    <h2 style="font-size: 17px;"><b>Receipt Date: <?php echo $invDate; ?></b> </h2>
                    <p style="font-size: 17px; color: #666; line-height: 21px;"><b> Academic Year :</b> <?php echo  $receipt_session['session']; ?></p>
                </div>
            </div>
            <!--End Invoice Mid-->

            <div id="" style="padding: 0 30px 30px;">
                <div id="table">
                    <table style="width: 100%;">
                        <tr class="tabletitle" style="padding: 5px; background: #eee;">
                            <td class="Rate" style="padding: 5px 15px; border: 1px solid #eee;">
                                <h2 style="font-size: 17px; width: 50%; border: 1px solid #eee;">Sr. No.</h2>
                            </td>
                            <td class="subtotal" style="text-align: center; padding: 5px 15px; border: 1px solid #eee;">
                                <h2 style="font-size: 17px;"> Particulars</h2>
                            </td>

                            <td class="subtotal" style="text-align: right; padding: 5px 15px; border: 1px solid #eee;">
                                <h2 style="font-size: 17px;">Total</h2>
                            </td>


                        </tr>
                        <?php if (!empty($feeList)) { ?>
                            <?php
                                            $i = 1;
                                            $total = 0;
                                            $discount = $this->feediscount_model->getdiscountbypayment_id($invo_no,$student_session_id); 
                                            $total_disc=0;
                                            $total_fine=0;
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
                                                    $fine = !empty($record->amount_fine) ? $record->amount_fine : 0;
                                                    $total_disc+=$disc_amt;
                                                    $total_fine+=$fine;
                                                } ?>

                                <tr class="service" style="height: 100px; vertical-align: initial; border: 1px solid #eee;">
                                    <td class="tableitem" style="padding: 5px 15px; border: 1px solid #eee;">
                                        <p class="itemtext" style="font-size: 17px; color: #666; line-height: 21px;"><?php echo $i; ?></p>
                                    </td>
                                    <td class="tableitem" style="text-align: left; padding: 5px 15px; border: 1px solid #eee;">
                                        <p class="itemtext" style="font-size: 17px; color: #666; line-height: 21px;"><?php echo $value->type ."-".$value->dis_name; ?></p>
                                    </td>
                                    <td class="tableitem" style="text-align: right; padding: 5px 15px; border: 1px solid #eee;">
                                        <p class="itemtext" style="font-size: 17px; color: #666; line-height: 21px;"><?php echo number_format($amount + $disc_amt , 2);  ?></p>
                                    </td>
                                    <?php $total += $amount; ?>
                                </tr>
                            <?php $i++; } ?>

                            <tr class="tabletitle" style="padding: 5px; background: #eee;">

                            </tr>
                            <tr class="tabletitle" style="padding: 5px; background: #eee;">

                            </tr>

                            <tr class="tabletitle" style="padding: 5px; background: #eee;">

                            </tr>
                        


                        <?php if ($total_fine > 0) { ?>
                            <tr class="tabletitle" style="padding: 5px; background: #eee;">
                            <td colspan="2" style="text-align: right; padding: 5px 15px; border: 1px solid #eee;">
                                <h2 style="font-size:17px;"><?php echo "Fine : "; ?></h2>
                            </td>
                            <td class="payment" style="text-align: right; padding: 5px 15px; border: 1px solid #eee;">
                                <h2 style="font-size:17px;"><b> <?php echo $currency_symbol . ' ' . number_format($total_fine, 2); ?> </b></h2>
                            </td>
                        </tr>
                        <?php } ?>
                        <?php
                                        //echo $invo_no."--".$student_session_id;
                                        $discount = $this->feediscount_model->getdiscountbypayment_id($invo_no,$student_session_id); 
                                        
                                        
                                        $dis_name="";
                                        
                                        foreach($discount as $dis)
                                        {
                                            
                                             $feediscount = $this->feediscount_model->get($dis['fees_discount_id']);
                                             
                                            if($dis_name=='')
                                            {$dis_name="(" . $feediscount['name'] . ")";}
                                            else
                                            {$dis_name = $dis_name . " + (" . $feediscount['name'] . ")";}
                                        }
                        if($discount>0) {
                            if ($total_disc > 0) { ?>
                            <tr class="tabletitle" style="padding: 5px; background: #eee;">
                            <td colspan="2" style="text-align: right; padding: 5px 15px; border: 1px solid #eee;">
                                <h2 style="font-size:17px;"><?php echo $dis_name; ?></h2>
                            </td>
                            <td class="payment" style="text-align: right; padding: 5px 15px; border: 1px solid #eee;">
                                <h2 style="font-size:17px;"><b> <?php echo $currency_symbol . ' ' . number_format($total_disc, 2); ?> </b></h2>
                            </td>
                            </tr>
                        <?php 
                        }
                        }
                        ?>
                        
                            <tr class="tabletitle" style="padding: 5px; background: #eee;">
                            <td colspan="2" style="text-align: right; padding: 5px 15px; border: 1px solid #eee;">
                                <h2 style="font-size:17px;"><?php echo "Total : "; ?></h2>
                            </td>
                            <td class="payment" style="text-align: right; padding: 5px 15px; border: 1px solid #eee;">
                                <h2 style="font-size:17px;"><b> <?php echo $currency_symbol . ' ' . number_format($total + $total_fine, 2); ?> </b></h2>
                            </td>
                            </tr>
                            <tr class="tabletitle" style="padding: 5px; background: #eee;">
                            <td colspan="2" style="text-align: right; padding: 5px 15px; border: 1px solid #eee;">
                                <h2 style="font-size:17px;"><?php echo "Previous : "; ?></h2>
                            </td>
                            <?php $preamt = $total_paid - $total; ?>
                                            <?php
                                            if ($preamt > 0) {
                                                $prev_amt = $preamt;
                                            } else {
                                                $prev_amt = 0;
                                            }
                                            ?>
                            <td class="payment" style="text-align: right; padding: 5px 15px; border: 1px solid #eee;">
                                <h2 style="font-size:17px;"><b> <?php echo $currency_symbol . ' ' . number_format($prev_amt, 2); ?> </b></h2>
                            </td>
                            </tr>
                            <tr class="tabletitle" style="padding: 5px; background: #eee;">
                            <td colspan="2" style="text-align: right; padding: 5px 15px; border: 1px solid #eee;">
                                <h2 style="font-size:17px;"><?php echo "Balance : "; ?></h2>
                            </td>
                            <td class="payment" style="text-align: right; padding: 5px 15px; border: 1px solid #eee;">
                                <h2 style="font-size:17px;"><b> <?php echo $currency_symbol . ' ' . number_format($total_balance, 2); ?> </b></h2>
                            </td>
                            </tr>                            
                        <?php } ?>
                    </table>
                </div>
                <!--End Table-->

                <div id="legalcopy" style="margin-top: 20px;">
                    <p class="legal" style="width: 70%; font-size: 15px; color: #666; ">
                        <strong><?php echo ucwords(markSheetDigitTwo($total)); ?> Only</strong>
                    </p>
                    <?php if ($payment_mode == "Cheque") { ?>
                        <p class="legal" style="width: 70%; font-size: 15px; color: #666; ">
                            <strong><b>Mode of Payment : <?php echo $payment_mode; ?></b></strong>
                        </p>                    
                    <?php } ?>
                    <?php if (!empty($note)) {   ?>
                        <p class="legal" style="width: 70%; font-size: 15px; color: #666; ">
                            <strong><b>Note : <?php echo $note; ?></b></strong>
                        </p>                    
                    <?php } ?>

                </div>
                <div>
                    <p style="text-align: right;"><?php echo "For ".$settinglist['name'];?></p>
                    <p style="text-align: right;">Authorized Signature</p>
                </div>
            </div>
            <!--End InvoiceBot-->
        </div>
        <!--End Invoice-->
    </div>
    <!-- End Invoice Holder-->
</body>

</html>