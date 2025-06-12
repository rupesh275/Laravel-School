<style type="text/css">
    .checkbox-inline+.checkbox-inline,
    .radio-inline+.radio-inline {
        margin-left: 8px;
    }

    #loader {
        border: 12px solid #58c7f0;
        border-radius: 50%;
        border-top: 12px solid #4a4444;
        width: 70px;
        height: 70px;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        100% {
            transform: rotate(360deg);
        }
    }

    .center {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        margin: auto;
        z-index: 999;
    }
</style>
<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
$language        = $this->customlib->getLanguage();
$language_name   = $language["short_code"];
?>
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12">
            <section class="content-header">
                <h1>
                    <i class="fa fa-money"></i> <?php echo $this->lang->line('fees_collection'); ?><small><?php echo $this->lang->line('student_fee'); ?></small>
                </h1>
            </section>

        </div>
    </div>
    <!-- /.control-sidebar -->
    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <div class="row">
                            <div class="col-md-4">
                                <h3 class="box-title"><?php echo $this->lang->line('student_fees'); ?></h3>
                            </div>
                            <div class="col-md-8">
                                <div class="btn-group pull-right">
                                    <a href="<?php echo base_url() ?>studentfee" type="button" class="btn btn-primary btn-xs">
                                        <i class="fa fa-arrow-left"></i> <?php echo $this->lang->line('back'); ?></a>
                                </div>
                            </div>

                        </div>
                    </div><!--./box-header-->
                    <div class="box-body" style="padding-top:0;">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="sfborder">
                                    <div class="col-md-2">
                                        <img width="115" height="115" class="round5" src="<?php
                                                                                            if (!empty($student['image'])) {
                                                                                                echo base_url() . $student['image'];
                                                                                            } else {
                                                                                                echo base_url() . "uploads/student_images/no_image.png";
                                                                                            }
                                                                                            ?>" alt="No Image">
                                    </div>

                                    <div class="col-md-10">
                                        <div class="row">
                                            <table class="table table-striped mb0 font13">
                                                <tbody>
                                                    <tr>
                                                        <th class="bozero"><?php echo $this->lang->line('name'); ?></th>
                                                        <td class="bozero"><?php echo $student['aadhar_name']; ?></td>

                                                        <th class="bozero"><?php echo $this->lang->line('class_section'); ?></th>
                                                        <td class="bozero"><?php echo $student['class'] . " (" . $student['section'] . ")" ?> </td>
                                                    </tr>
                                                    <tr>
                                                        <th><?php echo $this->lang->line('father_name'); ?></th>
                                                        <td><?php echo $student['father_name']; ?></td>
                                                        <th><?php echo $this->lang->line('admission_no'); ?></th>
                                                        <td><?php echo $student['admission_no']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th><?php echo $this->lang->line('mobile_no'); ?></th>
                                                        <td><?php echo $student['mobileno']; ?></td>
                                                        <th><?php echo $this->lang->line('roll_no'); ?></th>
                                                        <td> <?php echo $student['roll_no']; ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th><?php echo $this->lang->line('category'); ?></th>
                                                        <td>
                                                            <?php
                                                            foreach ($categorylist as $value) {
                                                                if ($student['category_id'] == $value['id']) {
                                                                    echo $value['category'];
                                                                }
                                                            }
                                                            ?>
                                                        </td>
                                                        <?php if ($sch_setting->rte) { ?>
                                                            <th><?php echo $this->lang->line('rte'); ?></th>
                                                            <td><b class="text-danger"> <?php echo $student['rte']; ?> </b>
                                                            </td>
                                                        <?php } ?>

                                                        <tr>
                                                        <th></th>
                                                        <td><a href="<?php echo site_url('studentfee/addfee/'.$student["student_session_id"]);?>" class="btn btn-sm btn-primary">Main Fees</a></td>
                                                        <td><a href="<?php echo site_url('studentfee/addotherfee/'.$student["student_session_id"]);?>" class="btn btn-sm btn-primary">Other Fees</a></td>
                                                        <td><a href="<?php echo site_url('studentfee/addBusfee/'.$student["student_session_id"]);?>" class="btn btn-sm btn-primary">Bus Fees</a></td>
                                                        </tr>
                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div style="background: #dadada; height: 1px; width: 100%; clear: both; margin-bottom: 10px;"></div>
                            </div>
                        </div>
                        <div class="row no-print">
                            <div class="col-md-12 mDMb10">
                                <a href="#" class="btn btn-sm btn-info printSelected"><i class="fa fa-print"></i> <?php echo $this->lang->line('print_selected'); ?> </a>

                                <button type="button" class="btn btn-sm btn-warning collectSelected" data-student_session_id="<?php echo $student['student_session_id']; ?>" id="load" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait') ?>"><i class="fa fa-money"></i> <?php echo $this->lang->line('collect') . " " . $this->lang->line('selected') ?></button>

                                <span class="pull-right"><?php echo $this->lang->line('date'); ?>: <?php echo date($this->customlib->getSchoolDateFormat()); ?></span>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <div class="download_label "><?php echo $this->lang->line('student_fees') . ": " . $student['firstname'] . " " . $student['lastname'] ?> </div>
                            <table class="table table-striped table-bordered table-hover example table-fixed-header">
                                <thead class="header">
                                    <tr>
                                        <th style="width: 10px"><input type="checkbox" id="select_all" /></th>
                                        <th align="left"><?php echo "Fees Group"; ?></th>
                                        <th align="left"></th>
                                        <th align="left"><?php echo "Fees Name"; ?></th>
                                        <th align="left"><?php echo $this->lang->line('fees_code'); ?></th>
                                        <th align="left" class="text text-left"><?php echo $this->lang->line('due_date'); ?></th>
                                        <th align="left" class="text text-left"><?php echo $this->lang->line('status'); ?></th>
                                        <th class="text text-right"><?php echo $this->lang->line('amount') ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                        <th class="text text-left"><?php echo $this->lang->line('payment_id'); ?></th>
                                        <th class="text text-left"><?php echo $this->lang->line('mode'); ?></th>
                                        <th class="text text-left"><?php echo $this->lang->line('date'); ?></th>
                                        <th class="text text-right"><?php echo $this->lang->line('discount'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                        <th class="text text-right"><?php echo $this->lang->line('fine'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                        <th class="text text-right"><?php echo $this->lang->line('paid'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                        <th class="text text-right"><?php echo $this->lang->line('balance'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                        <th class="text text-right"><?php echo $this->lang->line('action'); ?></th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php

                                    $total_amount           = 0;
                                    $total_main_fees        = 0;
                                    $total_other_fees       = 0;
                                    $total_deposite_amount  = 0;
                                    $total_fine_amount      = 0;
                                    $total_fees_fine_amount = 0;

                                    $total_discount_amount = 0;
                                    $total_balance_amount  = 0;
                                    $alot_fee_discount     = 0;

                                    $i = 0;
                                    foreach ($student_due_fee as $key => $val) {
                                        // echo "<pre>";
                                        // print_r($student_due_fee);
                                        foreach ($val as $key => $fee) {
                                            $old_group="";



                                            foreach ($fee->fees as $fee_key => $fee_value) {
                                        //         echo "<pre>";
                                        // print_r($fee);
                                                if ($userdata['user_type'] == "Super Admin" || $userdata['user_type'] == "Admin" || $userdata['user_type'] == "Accountant" || $userdata['user_type'] == "OfficeAdmin" || $userdata['user_type'] == "Office Clerk") {

                                                    if ($fee->fees_type == 'o') {

                                                        $this->db->where('id', $fee->student_session_id);
                                                        $studSession = $this->db->get('student_session')->row_array();


                                                        $fee_paid         = 0;
                                                        $fee_discount     = 0;
                                                        $fee_fine         = 0;
                                                        $fees_fine_amount = 0;
                                                        if (!empty($fee_value->amount_detail)) {
                                                            $fee_deposits = json_decode(($fee_value->amount_detail));

                                                            foreach ($fee_deposits as $fee_deposits_key => $fee_deposits_value) {
                                                                $fee_paid     = $fee_paid + $fee_deposits_value->amount;
                                                                $fee_discount = $fee_discount + $fee_deposits_value->amount_discount;
                                                                $fee_fine     = $fee_fine + $fee_deposits_value->amount_fine;
                                                            }
                                                        }

                                                        $feetype_balance       = $fee_value->amount - ($fee_paid + $fee_discount);

                                                        // if ($feetype_balance != 0 &&  $studSession['session_id'] != $current_session && !empty($fee_value->amount_detail)) {
                                                        if ($feetype_balance != 0 && !($studSession['session_id'] >= $current_session)) {


                                                            $fee_paid         = 0;
                                                            $fee_discount     = 0;
                                                            $fee_fine         = 0;
                                                            $fees_fine_amount = 0;
                                                            if (!empty($fee_value->amount_detail)) {
                                                                $fee_deposits = json_decode(($fee_value->amount_detail));

                                                                foreach ($fee_deposits as $fee_deposits_key => $fee_deposits_value) {
                                                                    $fee_paid     = $fee_paid + $fee_deposits_value->amount;
                                                                    $fee_discount = $fee_discount + $fee_deposits_value->amount_discount;
                                                                    $fee_fine     = $fee_fine + $fee_deposits_value->amount_fine;
                                                                }
                                                            }
                                                            if (($fee_value->due_date != "0000-00-00" && $fee_value->due_date != null) && (strtotime($fee_value->due_date) < strtotime(date('Y-m-d')))) {
                                                                $fees_fine_amount       = $fee_value->fine_amount;
                                                                $total_fees_fine_amount = $total_fees_fine_amount + $fee_value->fine_amount;
                                                            }

                                                    // if ($fee->fees_type == 'm') {
                                                    //     $total_main_fees          =  $total_main_fees + $fee_value->amount;
                                                    //     // echo $total_main_fees;
                                                    // }
                                                    if ($fee->fees_type == 'o') {
                                                        $total_other_fees          = $total_other_fees + $fee_value->amount;
                                                        // echo $total_other_fees;

                                                    }

                                                    $total_amount          = $total_amount + $fee_value->amount;
                                                    $total_discount_amount = $total_discount_amount + $fee_discount;
                                                    $total_deposite_amount = $total_deposite_amount + $fee_paid;
                                                    $total_fine_amount     = $total_fine_amount + $fee_fine;
                                                    $feetype_balance       = $fee_value->amount - ($fee_paid + $fee_discount);
                                                    $total_balance_amount  = $total_balance_amount + $feetype_balance;
                                    ?>


                                                            <?php
                                                            if ($feetype_balance > 0 && strtotime($fee_value->due_date) < strtotime(date('Y-m-d'))) {
                                                            ?>
                                                                <tr class="danger font12">
                                                                <?php
                                                            } else {
                                                                ?>
                                                                <tr class="dark-gray">
                                                                <?php
                                                            }
                                                            if($old_group!=$fee_value->name) {
                                                                ?>
                                                                <td>
                                                                    <input class="checkbox" type="checkbox" name="fee_checkbox" data-fee_master_id="<?php echo $fee_value->id ?>" data-fee_session_group_id="<?php echo $fee_value->fee_session_group_id ?>" data-fee_groups_feetype_id="<?php echo $fee_value->fee_groups_feetype_id ?>">
                                                                </td>
                                                                <td><?php echo $fee_value->name; $old_group=$fee_value->name; ?>
                                                                </td>
                                                                <?php } else { ?>
                                                                    <td>
                                                                    
                                                                </td>
                                                                <td>
                                                                </td>
                                                                <?php } ?>
                                                                <td align="left"><?php
                                                                                    echo $fee_value->name . " (" . $fee_value->type . ")";
                                                                                    ?></td>
                                                                <!-- <td align="left"><?php
                                                                                    //echo $fee->dis_name;
                                                                                    ?></td> -->
                                                                <td align="left"><?php echo $fee_value->code; ?></td>
                                                                <td align="left" class="text text-left">

                                                                    <?php
                                                                    if ($fee_value->due_date == "0000-00-00") {
                                                                    } else {

                                                                        echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($fee_value->due_date));
                                                                    }
                                                                    ?>
                                                                </td>
                                                                <td align="left" class="text text-left width85">
                                                                    <?php
                                                                    if ($feetype_balance == 0) {
                                                                    ?><span class="label label-success"><?php echo $this->lang->line('paid'); ?></span><?php
                                                                                                                                                } else if (!empty($fee_value->amount_detail)) {
                                                                                                                                                    ?><span class="label label-warning"><?php echo $this->lang->line('partial'); ?></span><?php
                                                                                                                                                                                                                            } else {
                                                                                                                                                                                                                                ?><span class="label label-danger"><?php echo $this->lang->line('unpaid'); ?></span><?php
                                                                                                                                                                                                                                                                                                                }
                                                                                                                                                                                                                                                                                                                    ?>

                                                                </td>
                                                                <td class="text text-right">
                                                                    <?php echo $fee_value->amount;
                                                                    if (($fee_value->due_date != "0000-00-00" && $fee_value->due_date != null) && (strtotime($fee_value->due_date) < strtotime(date('Y-m-d')))) {
                                                                    ?>
                                                                        <span class="text text-danger"><?php echo " + " . ($fee_value->fine_amount); ?></span>
                                                                    <?php
                                                                    }
                                                                    ?>


                                                                </td>

                                                                <td class="text text-left"></td>
                                                                <td class="text text-left"></td>
                                                                <td class="text text-left"></td>
                                                                <td class="text text-right"><?php
                                                                                            echo (number_format($fee_discount, 2, '.', ''));
                                                                                            ?></td>
                                                                <td class="text text-right"><?php
                                                                                            echo (number_format($fee_fine, 2, '.', ''));
                                                                                            ?></td>
                                                                <td class="text text-right"><?php
                                                                                            echo (number_format($fee_paid, 2, '.', ''));
                                                                                            ?></td>
                                                                <td class="text text-right"><?php
                                                                                            $display_none = "ss-none";
                                                                                            if ($feetype_balance > 0) {
                                                                                                $display_none = "";

                                                                                                echo (number_format($feetype_balance, 2, '.', ''));
                                                                                            }
                                                                                            ?>
                                                                </td>
                                                                <td width="100">
                                                                    <div class="btn-group">
                                                                        <div class="pull-right">
                                                                        <?php if ($this->rbac->hasPrivilege('deleteassignfees', 'can_delete')) { ?>
                                                                            <button type="button" data-student_session_id="<?php echo $fee->student_session_id; ?>" data-student_fees_master_id="<?php echo $fee->id; ?>" data-fee_groups_feetype_id="<?php echo $fee_value->fee_groups_feetype_id; ?>" data-group="<?php echo $fee_value->name; ?>" data-type="<?php echo $fee_value->code; ?>" class="btn btn-xs btn-default deleteassignfees <?php echo $display_none; ?>" title="<?php echo "Cancel Assign Group"; ?>" ><i class="fa fa-trash-o"></i></button>
                                                                            <?php }?>
                                                                            <!-- <button type="button" data-student_session_id="<?php echo $fee->student_session_id; ?>" data-student_fees_master_id="<?php echo $fee->id; ?>" data-fee_groups_feetype_id="<?php echo $fee_value->fee_groups_feetype_id; ?>" data-group="<?php echo $fee_value->name; ?>" data-type="<?php echo $fee_value->code; ?>" class="btn btn-xs btn-default myCollectFeeBtn <?php echo $display_none; ?>" title="<?php echo $this->lang->line('add_fees'); ?>" data-toggle="modal" data-target="#myFeesModal"><i class="fa fa-plus"></i></button> -->

                                                                            <button class="btn btn-xs btn-default printInv" data-fee_master_id="<?php echo $fee_value->id ?>" data-fee_session_group_id="<?php echo $fee_value->fee_session_group_id ?>" data-fee_groups_feetype_id="<?php echo $fee_value->fee_groups_feetype_id ?>" title="<?php echo $this->lang->line('print'); ?>"><i class="fa fa-print"></i> </button>
                                                                        </div>
                                                                    </div>
                                                                </td>


                                                                </tr>

                                                                <?php
                                                                if (!empty($fee_value->amount_detail)) {

                                                                    $fee_deposits = json_decode(($fee_value->amount_detail));

                                                                    foreach ($fee_deposits as $fee_deposits_key => $fee_deposits_value) {
                                                                        // echo "<pre>";
                                                                        // print_r($fee_deposits_value);
                                                                ?>
                                                                        <tr class="white-td">
                                                                            <td align="left"></td>
                                                                            <td align="left"></td>
                                                                            <td align="left"></td>
                                                                            <td align="left"></td>
                                                                            <td align="left"></td>
                                                                            <td class="text-right"><img src="<?php echo base_url(); ?>backend/images/table-arrow.png" alt="" /></td>
                                                                            <td class="text text-left">


                                                                                <a href="#" data-toggle="popover" class="detail_popover"> <?php echo $fee_deposits_value->inv_no; ?></a>
                                                                                <div class="fee_detail_popover" style="display: none">
                                                                                    <?php
                                                                                    if ($fee_deposits_value->description == "") {
                                                                                    ?>
                                                                                        <p class="text text-danger"><?php echo $this->lang->line('no_description'); ?></p>
                                                                                    <?php
                                                                                    } else {
                                                                                    ?>
                                                                                        <p class="text text-info"><?php echo $fee_deposits_value->description; ?></p>
                                                                                    <?php
                                                                                    }
                                                                                    ?>
                                                                                </div>


                                                                            </td>
                                                                            <td class="text text-left"><?php echo $this->lang->line(strtolower($fee_deposits_value->payment_mode)); ?></td>
                                                                            <td class="text text-left">

                                                                                <?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($fee_deposits_value->date)); ?>
                                                                            </td>
                                                                            <td class="text text-right"><?php echo (number_format($fee_deposits_value->amount_discount, 2, '.', '')); ?></td>
                                                                            <td class="text text-right"><?php echo (number_format($fee_deposits_value->amount_fine, 2, '.', '')); ?></td>
                                                                            <td class="text text-right"><?php echo (number_format($fee_deposits_value->amount, 2, '.', '')); ?></td>
                                                                            <td></td>
                                                                            <td class="text text-right">
                                                                                <div class="btn-group ">
                                                                                    <div class="pull-right">
                                                                                        <?php if ($this->rbac->hasPrivilege('collect_fees', 'can_delete')) { ?>
                                                                                            <button class="btn btn-default btn-xs" data-invoiceno="<?php echo $fee_value->student_fees_deposite_id . "/" . $fee_deposits_value->inv_no; ?>" data-main_invoice="<?php echo $fee_deposits_value->inv_no; ?>" data-sub_invoice="<?php echo $fee_deposits_value->inv_no ?>" data-toggle="modal" data-target="#confirm-delete" title="<?php echo $this->lang->line('revert'); ?>">
                                                                                                <i class="fa fa-undo"> </i>
                                                                                            </button>
                                                                                        <?php } ?>
                                                                                        <button class="btn btn-xs btn-default" id="editbtn" data-id="<?php echo  $fee_value->student_fees_deposite_id;   ?>" data-sub_invoice = "<?php echo $fee_deposits_value->inv_no; ?>" data-mode="<?php echo $value->payment_mode; ?>" data-note="<?php echo $value->note; ?>" data-toggle="modal" data-target="#edit_receipt"  title="<?php echo "Edit"; ?>">
                                                                                                    <i class="fa fa-edit"></i>
                                                                                        </button>                                                                                         
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                <?php
                                                                    }
                                                                }
                                                                ?>
                                                            <?php
                                                        } elseif ($studSession['session_id'] == $current_session) {

                                                            $fee_paid         = 0;
                                                            $fee_discount     = 0;
                                                            $fee_fine         = 0;
                                                            $fees_fine_amount = 0;
                                                            if (!empty($fee_value->amount_detail)) {
                                                                $fee_deposits = json_decode(($fee_value->amount_detail));

                                                                foreach ($fee_deposits as $fee_deposits_key => $fee_deposits_value) {
                                                                    $fee_paid     = $fee_paid + $fee_deposits_value->amount;
                                                                    $fee_discount = $fee_discount + $fee_deposits_value->amount_discount;
                                                                    $fee_fine     = $fee_fine + $fee_deposits_value->amount_fine;
                                                                }
                                                            }
                                                            if (($fee_value->due_date != "0000-00-00" && $fee_value->due_date != null) && (strtotime($fee_value->due_date) < strtotime(date('Y-m-d')))) {
                                                                $fees_fine_amount       = $fee_value->fine_amount;
                                                                $total_fees_fine_amount = $total_fees_fine_amount + $fee_value->fine_amount;
                                                            }

                                                            if ($fee->fees_type == 'm') {
                                                                $total_main_fees          =  $total_main_fees + $fee_value->amount;
                                                                // echo $total_main_fees;
                                                            }
                                                            // if ($fee->fees_type == 'o') {
                                                            //     $total_other_fees          = $total_other_fees + $fee_value->amount;
                                                            //     // echo $total_other_fees;

                                                            // }

                                                            $total_amount          = $total_amount + $fee_value->amount;
                                                            $total_discount_amount = $total_discount_amount + $fee_discount;
                                                            $total_deposite_amount = $total_deposite_amount + $fee_paid;
                                                            $total_fine_amount     = $total_fine_amount + $fee_fine;
                                                            $feetype_balance       = $fee_value->amount - ($fee_paid + $fee_discount);
                                                            $total_balance_amount  = $total_balance_amount + $feetype_balance;

                                                            // if ($feetype_balance == 0 && ) {
                                                            //     # code...
                                                            // }

                                                            
                                                            // echo "<pre>";
                                                            // print_r ($fee_value);
                                                            // echo "</pre>";
                                                            
                                                            ?>


                                                                <?php
                                                                if ($feetype_balance > 0 && strtotime($fee_value->due_date) < strtotime(date('Y-m-d'))) {
                                                                ?>
                                                                    <tr class="danger font12">
                                                                    <?php
                                                                } else {
                                                                    ?>
                                                                    <tr class="dark-gray">

                                                                    <?php
                                                                }
                                                                if($old_group!=$fee_value->name) {
                                                                    ?>
                                                                    <td>
                                                                        <input class="checkbox chkgrp" data-master_id = "<?php echo $fee_value->id; ?>" type="checkbox" name="fee_checkbox_selection" >
                                                                    </td>
                                                                    <td><?php echo $fee_value->name; $old_group=$fee_value->name; ?>
                                                                    </td>
                                                                    <?php } else { ?>
                                                                        <td>
                                                                        
                                                                    </td>
                                                                    <td>
                                                                    </td>
                                                                    <?php } ?>
                                                                    <td>
                                                                        <input class="checkbox grp_<?php echo $fee_value->id;  ?>" type="checkbox" name="fee_checkbox" data-fee_master_id="<?php echo $fee_value->id ?>" data-fee_session_group_id="<?php echo $fee_value->fee_session_group_id ?>" data-fee_groups_feetype_id="<?php echo $fee_value->fee_groups_feetype_id ?>">
                                                                    </td>                                                                    
                                                                    <td align="left"><?php
                                                                                        echo $fee_value->name . " (" . $fee_value->type . ")";
                                                                                        ?></td>
                                                                         
                                                                         <!-- <td align="left"><?php
                                                                                    //echo $fee->dis_name;
                                                                                    ?></td> -->
                                                                    <td align="left"><?php echo $fee_value->code; ?></td>
                                                                    <td align="left" class="text text-left">

                                                                        <?php
                                                                        if ($fee_value->due_date == "0000-00-00" || $fee_value->due_date == null) {
                                                                        } else {

                                                                            echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($fee_value->due_date));
                                                                        }
                                                                        ?>
                                                                    </td>
                                                                    <td align="left" class="text text-left width85">
                                                                        <?php
                                                                        if ($feetype_balance == 0) {
                                                                        ?><span class="label label-success"><?php echo $this->lang->line('paid'); ?></span><?php
                                                                                                                                                } else if (!empty($fee_value->amount_detail)) {
                                                                                                                                                    ?><span class="label label-warning"><?php echo $this->lang->line('partial'); ?></span><?php
                                                                                                                                                                                                                            } else {
                                                                                                                                                                                                                                ?><span class="label label-danger"><?php echo $this->lang->line('unpaid'); ?></span><?php
                                                                                                                                                                                                                                                                                                                }
                                                                                                                                                                                                                                                                                                                    ?>

                                                                    </td>
                                                                    <td class="text text-right">
                                                                        <?php echo $fee_value->amount;
                                                                        if (($fee_value->due_date != "0000-00-00" && $fee_value->due_date != null) && (strtotime($fee_value->due_date) < strtotime(date('Y-m-d')))) {
                                                                        ?>
                                                                            <span class="text text-danger"><?php echo " + " . ($fee_value->fine_amount); ?></span>
                                                                        <?php
                                                                        }
                                                                        ?>


                                                                    </td>

                                                                    <td class="text text-left"></td>
                                                                    <td class="text text-left"></td>
                                                                    <td class="text text-left"></td>
                                                                    <td class="text text-right"><?php
                                                                                                echo (number_format($fee_discount, 2, '.', ''));
                                                                                                ?></td>
                                                                    <td class="text text-right"><?php
                                                                                                echo (number_format($fee_fine, 2, '.', ''));
                                                                                                ?></td>
                                                                    <td class="text text-right"><?php
                                                                                                echo (number_format($fee_paid, 2, '.', ''));
                                                                                                ?></td>
                                                                    <td class="text text-right"><?php
                                                                                                $display_none = "ss-none";
                                                                                                if ($feetype_balance > 0) {
                                                                                                    $display_none = "";

                                                                                                    echo (number_format($feetype_balance, 2, '.', ''));
                                                                                                }
                                                                                                ?>
                                                                    </td>
                                                                    <td width="100">
                                                                        <div class="btn-group">
                                                                            <div class="pull-right">
                                                                            <?php if ($this->rbac->hasPrivilege('deleteassignfees', 'can_delete')) { 
                                                                                if (empty($fee_value->amount_detail)) {
                                                                                ?>
                                                                            <button type="button" data-student_session_id="<?php echo $fee->student_session_id; ?>" data-student_fees_master_id="<?php echo $fee->id; ?>" data-fee_groups_feetype_id="<?php echo $fee_value->fee_groups_feetype_id; ?>" data-group="<?php echo $fee_value->name; ?>" data-type="<?php echo $fee_value->code; ?>" class="btn btn-xs btn-default deleteassignfees <?php echo $display_none; ?>" title="<?php echo "Cancel Assign Group"; ?>" ><i class="fa fa-trash-o"></i></button>
                                                                            <?php } }?>    
                                                                            <!-- <button type="button" data-student_session_id="<?php echo $fee->student_session_id; ?>" data-student_fees_master_id="<?php echo $fee->id; ?>" data-fee_groups_feetype_id="<?php echo $fee_value->fee_groups_feetype_id; ?>" data-group="<?php echo $fee_value->name; ?>" data-type="<?php echo $fee_value->code; ?>" class="btn btn-xs btn-default myCollectFeeBtn <?php echo $display_none; ?>" title="<?php echo $this->lang->line('add_fees'); ?>" data-toggle="modal" data-target="#myFeesModal"><i class="fa fa-plus"></i></button> -->

                                                                                <button class="btn btn-xs btn-default printInv" data-fee_master_id="<?php echo $fee_value->id ?>" data-fee_session_group_id="<?php echo $fee_value->fee_session_group_id ?>" data-fee_groups_feetype_id="<?php echo $fee_value->fee_groups_feetype_id ?>" title="<?php echo $this->lang->line('print'); ?>"><i class="fa fa-print"></i> </button>
                                                                            </div>
                                                                        </div>
                                                                    </td>


                                                                    </tr>

                                                                    <?php
                                                                    if (!empty($fee_value->amount_detail)) {

                                                                        $fee_deposits = json_decode(($fee_value->amount_detail));

                                                                        foreach ($fee_deposits as $fee_deposits_key => $fee_deposits_value) {
                                                                            // echo "<pre>";
                                                                            // print_r($fee_deposits_value);
                                                                    ?>
                                                                            <tr class="white-td">
                                                                                <td align="left"></td>
                                                                                <td align="left"></td>
                                                                                <td align="left"></td>
                                                                                <td align="left"></td>
                                                                                <td align="left"></td>
                                                                                <td align="left"></td>
                                                                                <td align="left"></td>
                                                                                <td class="text-right"><img src="<?php echo base_url(); ?>backend/images/table-arrow.png" alt="" /></td>
                                                                                <td class="text text-left">


                                                                                    <a href="#" data-toggle="popover" class="detail_popover"> <?php echo $fee_deposits_value->inv_no; ?></a>
                                                                                    <div class="fee_detail_popover" style="display: none">
                                                                                        <?php
                                                                                        if ($fee_deposits_value->description == "") {
                                                                                        ?>
                                                                                            <p class="text text-danger"><?php echo $this->lang->line('no_description'); ?></p>
                                                                                        <?php
                                                                                        } else {
                                                                                        ?>
                                                                                            <p class="text text-info"><?php echo $fee_deposits_value->description; ?></p>
                                                                                        <?php
                                                                                        }
                                                                                        ?>
                                                                                    </div>


                                                                                </td>
                                                                                <td class="text text-left"><?php echo $this->lang->line(strtolower($fee_deposits_value->payment_mode)); ?></td>
                                                                                <td class="text text-left">

                                                                                    <?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($fee_deposits_value->date)); ?>
                                                                                </td>
                                                                                <td class="text text-right"><?php echo (number_format($fee_deposits_value->amount_discount, 2, '.', '')); ?></td>
                                                                                <td class="text text-right"><?php echo (number_format($fee_deposits_value->amount_fine, 2, '.', '')); ?></td>
                                                                                <td class="text text-right"><?php echo (number_format($fee_deposits_value->amount, 2, '.', '')); ?></td>
                                                                                <td></td>
                                                                                <td class="text text-right">
                                                                                    <div class="btn-group ">
                                                                                        <div class="pull-right">
                                                                                            
                                                                                            <?php if ($this->rbac->hasPrivilege('collect_fees', 'can_delete')) { ?>
                                                                                                <button class="btn btn-default btn-xs" data-invoiceno="<?php echo $fee_value->student_fees_deposite_id . "/" . $fee_deposits_value->inv_no; ?>" data-main_invoice="<?php echo $fee_deposits_value->inv_no; ?>" data-sub_invoice="<?php echo $fee_deposits_value->inv_no ?>" data-session_id="<?php echo $fee_deposits_value->session_id; ?>"  data-toggle="modal" data-target="#confirm-delete" title="<?php echo $this->lang->line('revert'); ?>">
                                                                                                    <i class="fa fa-undo"> </i>
                                                                                                </button>
                                                                                                <!--
                                                                                                <button class="btn btn-xs btn-default" id="editbtn1" data-session_id = "<?php echo $rec_session_id; ?>" data-id="<?php echo  $value->fee_receipt_id;   ?>" data-mode="<?php echo $value->payment_mode; ?>" data-session_id="<?php echo $rec_session_id; ?>"  data-note="<?php echo $value->note; ?>" data-toggle="modal" data-target="#edit_receipt"  title="<?php echo "Edit"; ?>">
                                                                                                    <i class="fa fa-edit"></i>
                                                                                                </button>                                                                                               
                                                                                                -->
                                                                                            <?php } ?>
                                                                                            <button class="btn btn-xs btn-default printDocDirect" data-student_session_id="<?php echo $student_session_id[$i]; ?>" data-percentage="<?php //echo $fee_value->
                                                                                                                                                                                                                                ?>" data-discount_amt="<?php echo $fee_deposits_value->amount_discount; ?>" data-payment_id="<?php echo  $fee_deposits_value->inv_no; ?>" data-full_amount="<?php echo $total_amount; ?>" data-fee_master_id="<?php echo $fee_value->id ?>" data-fee_groups_feetype_id="<?php echo $fee_value->fee_groups_feetype_id ?>" data-paidamt="<?php echo $fee_paid; ?>" data-main_invoice="<?php echo $fee_deposits_value->inv_no; ?>" data-sub_invoice="<?php echo $fee_deposits_value->inv_no ?>"   data-id="<?php echo $fee_deposits_value->inv_no ?>" data-sessionid="<?php if(isset($fee_deposits_value->session_id)) { echo  $fee_deposits_value->session_id; } else { echo "0"; }   ?>"  title="<?php echo $this->lang->line('print'); ?>"><i class="fa fa-print"></i> </button>    
                                                                                                                                                                                                                                                                                                    
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                    <?php
                                                                        }
                                                                    }
                                                                    ?>
                                            <?
                                                        }
                                                    }
                                                }
                                            }
                                        }

                                        $i++;
                                    }
                                            ?>
                                            <?php
                                            if (!empty($student_discount_fee)) {

                                                foreach ($student_discount_fee as $key => $value) {



                                                    foreach ($value as $discount_key => $discount_value) {
                                            ?>
                                                        <tr class="dark-light">
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td align="left"> <?php echo $this->lang->line('discount'); ?> </td>
                                                            <td align="left">
                                                                <?php echo $discount_value['code']; ?>
                                                            </td>
                                                            <td align="left"></td>
                                                            <td align="left" class="text text-left">
                                                                <?php
                                                                if ($discount_value['status'] == "applied") {
                                                                ?>
                                                                    <a href="#" data-toggle="popover" class="detail_popover">

                                                                        <?php echo $this->lang->line('discount_of') . " " . $currency_symbol . $discount_value['amount'] . " " . $this->lang->line($discount_value['status']) . " : " . $discount_value['payment_id']; ?>

                                                                    </a>
                                                                    <div class="fee_detail_popover" style="display: none">
                                                                        <?php
                                                                        if ($discount_value['student_fees_discount_description'] == "") {
                                                                        ?>
                                                                            <p class="text text-danger"><?php echo $this->lang->line('no_description'); ?></p>
                                                                        <?php
                                                                        } else {
                                                                        ?>
                                                                            <p class="text text-danger"><?php echo $discount_value['student_fees_discount_description'] ?></p>
                                                                        <?php
                                                                        }
                                                                        ?>

                                                                    </div>
                                                                <?php
                                                                } else {
                                                                    echo '<p class="text text-danger">' . $this->lang->line('discount_of') . " " . $currency_symbol . $discount_value['amount'] . " " . $this->lang->line($discount_value['status']);
                                                                }
                                                                ?>

                                                            </td>
                                                            <td></td>
                                                            <td class="text text-left"></td>
                                                            <td class="text text-left"></td>
                                                            <td class="text text-left"></td>
                                                            <td class="text text-right">
                                                                <?php
                                                                $alot_fee_discount = $alot_fee_discount;
                                                                ?>
                                                            </td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td>
                                                                <div class="btn-group ">
                                                                    <div class="pull-right">
                                                                        <?php
                                                                        if ($discount_value['status'] == "applied") {
                                                                        ?>

                                                                            <?php if ($this->rbac->hasPrivilege('collect_fees', 'can_delete')) { ?>
                                                                                <button class="btn btn-default btn-xs" data-discounttitle="<?php echo $discount_value['code']; ?>" data-discountid="<?php echo $discount_value['id']; ?>" data-toggle="modal" data-target="#confirm-discountdelete" title="<?php echo $this->lang->line('revert'); ?>">
                                                                                    <i class="fa fa-undo"> </i>
                                                                                </button>
                                                                        <?php
                                                                            }
                                                                        }
                                                                        ?>

                                                                        <button type="button" data-modal_title="<?php echo $this->lang->line('discount') . " : " . $discount_value['code']; ?>" data-student_fees_discount_id="<?php echo $discount_value['id']; ?>" class="btn btn-xs btn-default applydiscount" title="<?php echo $this->lang->line('apply_discount'); ?>"><i class="fa fa-check"></i>
                                                                        </button>

                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                            <?php
                                                    }
                                                }
                                            }
                                            ?>


                                            <tr class="box box-solid total-bg">
                                                <td align="left"></td>
                                                <td align="left"></td>
                                                <td align="left"></td>
                                                <td align="left"></td>
                                                <td align="left"></td>
                                                <td align="left"></td>
                                                <td align="left" class="text text-left"><?php echo $this->lang->line('grand_total'); ?></td>
                                                <td class="text text-right">
                                                    <?php
                                                    echo $currency_symbol . number_format($total_amount, 2, '.', '') . "<span class='text text-danger'>+" . number_format($total_fees_fine_amount, 2, '.', '') . "</span>";
                                                    ?>
                                                    <?php $total_bal_amt = $total_balance_amount - $alot_fee_discount; ?>
                                                    <input type="hidden" name="total_amt" id="total_amt" value="<?php echo $total_amount; ?>">
                                                    <input type="hidden" name="total_paid" id="total_paid" value="<?php echo $total_deposite_amount; ?>">
                                                    <input type="hidden" name="total_balance" id="total_balance" value="<?php echo $total_bal_amt; ?>">
                                                    <input type="hidden" name="total_main_fees" id="total_main_fees" value="<?php echo $total_main_fees; ?>">
                                                    <input type="hidden" name="total_other_fees" id="total_other_fees" value="<?php echo $total_other_fees; ?>">


                                                </td>
                                                <td class="text text-left"></td>
                                                <td class="text text-left"></td>
                                                <td class="text text-left"></td>

                                                <td class="text text-right"><?php
                                                                            echo ($currency_symbol . number_format($total_discount_amount + $alot_fee_discount, 2, '.', ''));

                                                                            ?></td>
                                                <td class="text text-right"><?php
                                                                            echo ($currency_symbol . number_format($total_fine_amount, 2, '.', ''));
                                                                            ?></td>
                                                <td class="text text-right"><?php
                                                                            echo ($currency_symbol . number_format($total_deposite_amount, 2, '.', ''));
                                                                            ?></td>
                                                <td class="text text-right"><?php
                                                                            echo ($currency_symbol . number_format($total_balance_amount - $alot_fee_discount, 2, '.', ''));
                                                                            ?></td>
                                                <td class="text text-right"></td>
                                            </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
            <!--/.col (left) -->
        </div>
    </section>
</div>


<div class="modal fade" id="myFeesModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title title text-center fees_title"></h4>
            </div>
            <div class="modal-body pb0">
                <div class="form-horizontal balanceformpopup">
                    <div class="box-body">

                        <input type="hidden" class="form-control" id="std_id" value="<?php echo $student["student_session_id"]; ?>" readonly="readonly" />
                        <input type="hidden" class="form-control" id="parent_app_key" value="<?php echo $student['parent_app_key'] ?>" readonly="readonly" />
                        <input type="hidden" class="form-control" id="guardian_phone" value="<?php echo $student['guardian_phone'] ?>" readonly="readonly" />
                        <input type="hidden" class="form-control" id="guardian_email" value="<?php echo $student['guardian_email'] ?>" readonly="readonly" />
                        <input type="hidden" class="form-control" id="student_fees_master_id" value="0" readonly="readonly" />
                        <input type="hidden" class="form-control" id="fee_groups_feetype_id" value="0" readonly="readonly" />
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-3 control-label"><?php echo $this->lang->line('date'); ?></label>
                            <div class="col-sm-9">
                                <input id="date" name="admission_date" placeholder="" type="text" class="form-control date_fee" value="<?php echo date($this->customlib->getSchoolDateFormat()); ?>" readonly="readonly" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-3 control-label"><?php echo $this->lang->line('amount'); ?><small class="req"> *</small></label>
                            <div class="col-sm-9">

                                <input type="text" autofocus="" class="form-control modal_amount" id="amount" value="0">

                                <span class="text-danger" id="amount_error"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-3 control-label"> <?php echo $this->lang->line('discount'); ?> <?php echo $this->lang->line('group'); ?></label>
                            <div class="col-sm-9">
                                <select class="form-control modal_discount_group" id="discount_group">
                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                </select>

                                <span class="text-danger" id="amount_error"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-3 control-label"><?php echo $this->lang->line('discount'); ?><small class="req"> *</small></label>
                            <div class="col-sm-9">
                                <div class="row">
                                    <div class="col-md-5 col-sm-5">
                                        <div class="">
                                            <input type="text" class="form-control" id="amount_discount" value="0">

                                            <span class="text-danger" id="amount_discount_error"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-2 ltextright">

                                        <label for="inputPassword3" class="control-label"><?php echo $this->lang->line('fine'); ?><small class="req">*</small></label>
                                    </div>
                                    <div class="col-md-5 col-sm-5">
                                        <div class="">
                                            <input type="text" class="form-control" id="amount_fine" value="0">

                                            <span class="text-danger" id="amount_fine_error"></span>
                                        </div>
                                    </div>
                                </div>
                            </div><!--./col-sm-9-->
                        </div>
                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-3 control-label"><?php echo $this->lang->line('payment'); ?> <?php echo $this->lang->line('mode'); ?></label>
                            <div class="col-sm-9">
                                <label class="radio-inline">
                                    <input type="radio" name="payment_mode_fee" value="Cash" checked="checked"><?php echo $this->lang->line('cash'); ?>
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="payment_mode_fee" value="Cheque"><?php echo $this->lang->line('cheque'); ?>
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="payment_mode_fee" value="DD"><?php echo $this->lang->line('dd'); ?>
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="payment_mode_fee" value="bank_transfer"><?php echo $this->lang->line('bank_transfer'); ?>
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="payment_mode_fee" value="upi"><?php echo $this->lang->line('upi'); ?>
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="payment_mode_fee" value="card"><?php echo $this->lang->line('card'); ?>
                                </label>
                                <span class="text-danger" id="payment_mode_error"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-3 control-label"><?php echo $this->lang->line('note'); ?></label>

                            <div class="col-sm-9">
                                <textarea class="form-control" rows="3" id="description" placeholder=""></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><?php echo $this->lang->line('cancel'); ?></button>
                <button type="button" class="btn cfees save_button" id="load" data-action="collect" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Processing"> <?php echo $currency_symbol; ?> <?php echo $this->lang->line('collect_fees'); ?> </button>
                <button type="button" class="btn cfees save_button" id="load" data-action="print" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Processing"> <?php echo $currency_symbol; ?> <?php echo $this->lang->line('collect') . " & " . $this->lang->line('print') ?></button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myDisApplyModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title title text-center discount_title"></h4>
            </div>
            <div class="modal-body pb0">
                <div class="form-horizontal">
                    <div class="box-body">
                        <input type="hidden" class="form-control" id="student_fees_discount_id" value="" />
                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-3 control-label"><?php echo $this->lang->line('payment_id'); ?> <small class="req">*</small></label>
                            <div class="col-sm-9">

                                <input type="text" class="form-control" id="discount_payment_id">

                                <span class="text-danger" id="discount_payment_id_error"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-3 control-label"><?php echo $this->lang->line('description'); ?></label>

                            <div class="col-sm-9">
                                <textarea class="form-control" rows="3" id="dis_description" placeholder=""></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><?php echo $this->lang->line('cancel'); ?></button>
                <button type="button" class="btn cfees dis_apply_button" id="load" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Processing"> <?php echo $this->lang->line('apply_discount'); ?></button>
            </div>
        </div>
    </div>
</div>

<div class="delmodal modal fade" id="confirm-discountdelete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $this->lang->line('confirmation'); ?></h4>
            </div>
            <div class="modal-body">
                <p><?php echo $this->lang->line('are_you_sure_want_to_revert'); ?> <b class="discount_title"></b> <?php echo $this->lang->line('discount_this_action_is_irreversible'); ?></p>
                <p><?php echo $this->lang->line('do_you_want_to_proceed') ?></p>
                <p class="debug-url"></p>
                <input type="hidden" name="discount_id" id="discount_id" value="">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('cancel'); ?></button>
                <a class="btn btn-danger btn-discountdel"><?php echo $this->lang->line('revert'); ?></a>
            </div>
        </div>
    </div>
</div>
<div class="delmodal modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $this->lang->line('confirmation'); ?></h4>
            </div>
            <div class="modal-body">

                <p><?php echo $this->lang->line('are_you_sure_want_to_delete'); ?> <b class="invoice_no"></b> <?php echo $this->lang->line('invoice_this_action_is_irreversible') ?></p>
                <p><?php echo $this->lang->line('do_you_want_to_proceed') ?></p>
                <p class="debug-url"></p>
                <input type="hidden" name="main_invoice" id="main_invoice" value="">
                <input type="hidden" name="sub_invoice" id="sub_invoice" value="">
                <input type="hidden" name="del_session_id" id="del_session_id" value="">
                <div class="form-group">
                <label for="inputPassword3" class="col-sm-3 control-label"><?php echo $this->lang->line('reason'); ?></label>
                <div class="col-sm-8">
                    <textarea class="form-control" rows="3" id="reason" name = "reason" placeholder=""></textarea>
                </div>
                </div>                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('cancel'); ?></button>
                <a class="btn btn-danger btn-ok"><?php echo $this->lang->line('revert'); ?></a>
            </div>
        </div>
    </div>
</div>

<div class="norecord modal fade" id="confirm-norecord" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <p><?php echo $this->lang->line('no_record_found'); ?></p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('cancel'); ?></button>
            </div>
        </div>
    </div>
</div>

<div id="listCollectionModal" class="modal fade">
    <div class="modal-dialog">
        <form id="collect_fee_group">
            <!-- <form action="<?php echo site_url('studentfee/addfeegrp'); ?>" method="POST" id="collect_fee_group"> -->
            <div class="modal-content">
                <!-- //================ -->
                <input type="hidden" class="form-control" id="group_std_id" name="student_session_id" value="<?php echo $student["student_session_id"]; ?>" readonly="readonly" />
                <input type="hidden" class="form-control" id="group_parent_app_key" name="parent_app_key" value="<?php echo $student['parent_app_key'] ?>" readonly="readonly" />
                <input type="hidden" class="form-control" id="group_guardian_phone" name="guardian_phone" value="<?php echo $student['guardian_phone'] ?>" readonly="readonly" />
                <input type="hidden" class="form-control" id="group_guardian_email" name="guardian_email" value="<?php echo $student['guardian_email'] ?>" readonly="readonly" />
                <!-- //================ -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><?php echo $this->lang->line('collect') . " " . $this->lang->line('fees'); ?></h4>
                </div>
                <div class="modal-body">

                </div>
                <!--  <div class="modal-footer">
                    <button type="submit" class="btn btn-primary payment_collect" data-loading-text="<i class='fa fa-spinner fa-spin '></i><?php //echo $this->lang->line('processing')
                                                                                                                                            ?>"><i class="fa fa-money"></i> <?php //echo $this->lang->line('pay'); 
                                                                                                                                                                            ?></button>
                </div> -->
            </div>
        </form>
    </div>
</div>
<div class="editmodal modal fade" id="edit_receipt" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $this->lang->line('confirmation'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                        <label for="inputPassword3" class="col-sm-3 control-label"> <?php echo $this->lang->line('payment') . " " . $this->lang->line('mode'); ?></label>
                        <div class="col-sm-9">
                            <label class="radio-inline">
                                <input type="radio" class="fee_mode" id="payment_mode_fee" name="payment_mode_fee" value="Cash" checked="checked"> <?php echo $this->lang->line('cash'); ?></label>
                            <label class="radio-inline">
                                <input type="radio" class="fee_mode" id="payment_mode_fee" name="payment_mode_fee" value="Cheque"> <?php echo $this->lang->line('cheque'); ?></label>
                            <label class="radio-inline">
                                <input type="radio" class="fee_mode" id="payment_mode_fee" name="payment_mode_fee" value="DD"><?php echo $this->lang->line('dd'); ?></label>
                            <label class="radio-inline">
                                <input type="radio" class="fee_mode" id="payment_mode_fee" name="payment_mode_fee" value="bank_transfer"><?php echo $this->lang->line('bank_transfer'); ?>
                            </label>
                            <label class="radio-inline">
                                <input type="radio" class="fee_mode" id="payment_mode_fee" name="payment_mode_fee" value="upi"><?php echo $this->lang->line('upi'); ?>
                            </label>
                            <label class="radio-inline">
                                <input type="radio" class="fee_mode" id="payment_mode_fee" name="payment_mode_fee" value="card"><?php echo $this->lang->line('card'); ?>
                            </label>
                            <label class="radio-inline">
                                <input type="radio" class="fee_mode" id="payment_mode_fee" name="payment_mode_fee" value="gateway"><?php echo "Gateway"; ?>
                            </label>                            
                            <span class="text-danger" id="payment_mode_error"></span>
                        </div>
                        <span id="form_collection_payment_mode_fee_error" class="text text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-3 control-label"> <?php echo $this->lang->line('note') ?></label>
                        <input type="hidden" name="edit_inv_id" id="edit_inv_id" value="">
                        <input type="hidden" name="pay_mode" id="pay_mode" value="">
                        <input type="hidden" name="session_id" id="session_id" value="">
                        <div class="col-sm-9">
                            <textarea class="form-control" rows="3" name="fee_gupcollected_note" id="description" placeholder=""></textarea>
                            <span id="form_collection_fee_gupcollected_note_error" class="text text-danger"></span>
                        </div>
                    </div>

            </div>            
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('cancel'); ?></button>
                <a class="btn btn-danger btn-ok"><?php echo "Update"; ?></a>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $(document).on('click', '.printDocDirect', function() {
            var id = $(this).data('id');
            var recsessionid = $(this).data('sessionid');
            $.ajax({
                url: '<?php echo site_url("studentfee/printFeesByName_previous_id") ?>',
                type: 'post',
                data: {
                    'id': id,
                    'recsessionid': recsessionid,                    
                },
                success: function(response) {
                    Popup(response);
                }
            });
        });         
        $(document).on('click', '.printDoc', function() {
            var fee_master_id = $(this).data('fee_master_id');
            var fee_groups_feetype_id = $(this).data('fee_groups_feetype_id');
            var main_invoice = $(this).data('main_invoice');
            var sub_invoice = $(this).data('sub_invoice');
            var paidamt = $(this).data('paidamt');
            var student_session_id = $(this).data('student_session_id');
            var total_amt = $('input#total_amt').val();
            var total_paid = $('input#total_paid').val();
            var total_balance = $('input#total_balance').val();
            var discount_amt = $(this).data('discount_amt');
            var payment_id = $(this).data('payment_id');
            $.ajax({
                url: '<?php echo site_url("studentfee/printFeesByName") ?>',
                type: 'post',
                data: {
                    'student_session_id': student_session_id,
                    'main_invoice': main_invoice,
                    'sub_invoice': sub_invoice,
                    'fee_master_id': fee_master_id,
                    'fee_groups_feetype_id': fee_groups_feetype_id,
                    'paidamt': paidamt,
                    'total_amt': total_amt,
                    'total_paid': total_paid,
                    'total_balance': total_balance,
                    'discount_amt': discount_amt,
                    'payment_id': payment_id,
                },
                success: function(response) {
                    Popup(response);
                }
            });
        });
        $(document).on('click', '.printInv', function() {
            var fee_master_id = $(this).data('fee_master_id');
            var fee_session_group_id = $(this).data('fee_session_group_id');
            var fee_groups_feetype_id = $(this).data('fee_groups_feetype_id');
            $.ajax({
                url: '<?php echo site_url("studentfee/printFeesByGroup") ?>',
                type: 'post',
                data: {
                    'fee_groups_feetype_id': fee_groups_feetype_id,
                    'fee_master_id': fee_master_id,
                    'fee_session_group_id': fee_session_group_id
                },
                success: function(response) {
                    Popup(response);
                }
            });
        });
    });
</script>
<script type="text/javascript">
    $(document).on('click', '.save_button', function(e) {
        var $this = $(this);
        var action = $this.data('action');
        $this.button('loading');
        var form = $(this).attr('frm');
        var feetype = $('#feetype_').val();
        var date = $('#date').val();
        var student_session_id = $('#std_id').val();
        var amount = $('#amount').val();
        var amount_discount = $('#amount_discount').val();
        var amount_fine = $('#amount_fine').val();
        var description = $('#description').val();
        var parent_app_key = $('#parent_app_key').val();
        var guardian_phone = $('#guardian_phone').val();
        var guardian_email = $('#guardian_email').val();
        var student_fees_master_id = $('#student_fees_master_id').val();
        var fee_groups_feetype_id = $('#fee_groups_feetype_id').val();
        var payment_mode = $('input[name="payment_mode_fee"]:checked').val();
        var student_fees_discount_id = $('#discount_group').val();
        var total_amt = $('input#total_amt').val();
        var total_paid = $('input#total_paid').val();
        var total_balance = $('input#total_balance').val();
        var discount_amt = $(this).data('discount_amt');
        $.ajax({
            url: '<?php echo site_url("studentfee/addstudentfee") ?>',
            type: 'post',
            data: {
                action: action,
                student_session_id: student_session_id,
                date: date,
                type: feetype,
                amount: amount,
                amount_discount: amount_discount,
                amount_fine: amount_fine,
                description: description,
                student_fees_master_id: student_fees_master_id,
                fee_groups_feetype_id: fee_groups_feetype_id,
                payment_mode: payment_mode,
                guardian_phone: guardian_phone,
                guardian_email: guardian_email,
                student_fees_discount_id: student_fees_discount_id,
                parent_app_key: parent_app_key,
                total_paid: total_paid,
                total_balance: total_balance,
                discount_amt: discount_amt,
            },
            dataType: 'json',
            success: function(response) {
                $this.button('reset');
                if (response.status === "success") {
                    if (action === "collect") {
                        location.reload(true);
                    } else if (action === "print") {
                        Popup(response.print, true);
                    }
                } else if (response.status === "fail") {
                    $.each(response.error, function(index, value) {
                        var errorDiv = '#' + index + '_error';
                        $(errorDiv).empty().append(value);
                    });
                }
            }
        });
    });
</script>
<script>
    var base_url = '<?php echo base_url() ?>';

    function Popup(data, winload = false) {
        var frame1 = $('<iframe />').attr("id", "printDiv");
        frame1[0].name = "frame1";
        frame1.css({
            "position": "absolute",
            "top": "-1000000px"
        });
        $("body").append(frame1);
        var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
        frameDoc.document.open();
        //Create a new HTML document.
        // frameDoc.document.write('<html>');
        // frameDoc.document.write('<head>');
        // frameDoc.document.write('<title></title>');
        // frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/jvectormap/jquery-jvectormap-1.2.2.css">');
        // frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/bootstrap/css/bootstrap.min.css">');
        // frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/font-awesome.min.css">');
        // frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/ionicons.min.css">');
        // frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/AdminLTE.min.css">');
        // frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/skins/_all-skins.min.css">');
        // frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/iCheck/flat/blue.css">');
        // frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/morris/morris.css">');
        // frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/datepicker/datepicker3.css">');
        // frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/daterangepicker/daterangepicker-bs3.css">');
        // frameDoc.document.write('</head>');
        // frameDoc.document.write('<body>');
        frameDoc.document.write(data);
        //frameDoc.document.write('</body>');
        //frameDoc.document.write('</html>');
        frameDoc.document.close();
        setTimeout(function() {
            document.getElementById('printDiv').contentWindow.focus();
            document.getElementById('printDiv').contentWindow.print();
            $("#printDiv", top.document).remove();
            // frame1.remove();
            if (winload) {
                window.location.reload(true);
            }
        }, 500);

        return true;
    }
    $(document).ready(function() {
        $('.delmodal').modal({
            backdrop: 'static',
            keyboard: false,
            show: false
        });
        $('#listCollectionModal').modal({
            backdrop: 'static',
            keyboard: false,
            show: false
        });

        $('#confirm-delete').on('show.bs.modal', function(e) {
            $('.invoice_no', this).text("");
            $('#main_invoice', this).val("");
            $('#sub_invoice', this).val("");
            $('#del_session_id', this).val("");
            $('.invoice_no', this).text($(e.relatedTarget).data('sub_invoice'));
            $('#main_invoice', this).val($(e.relatedTarget).data('main_invoice'));
            $('#sub_invoice', this).val($(e.relatedTarget).data('sub_invoice'));
            $('#del_session_id', this).val($(e.relatedTarget).data('session_id'));
        });

        $('#confirm-discountdelete').on('show.bs.modal', function(e) {
            $('.discount_title', this).text("");
            $('#discount_id', this).val("");
            $('.discount_title', this).text($(e.relatedTarget).data('discounttitle'));
            $('#discount_id', this).val($(e.relatedTarget).data('discountid'));
        });
        $('#edit_receipt').on('show.bs.modal', function(e) {
            //alert($(e.relatedTarget).data('mode'));
            //$('.payment_mode_fee').val('upi');
            var mode= $(e.relatedTarget).data('mode');
            var note = $(e.relatedTarget).data('note');
            var invid = $(e.relatedTarget).data('id');
            $('#description').val(note);
            $('#edit_inv_id').val(invid);
            $('#pay_mode').val(mode);
            $('.fee_mode').each(function(index,item){
                if($(item).val() == mode)
                {$(item).prop("checked", true);}
                else
                {$(item).prop("checked", false);}
            });
        });    
        $('#confirm-delete').on('click', '.btn-ok', function(e) {
            var $modalDiv = $(e.delegateTarget);
            var main_invoice = $('#main_invoice').val();
            var sub_invoice = $('#sub_invoice').val();
            var del_session_id = $('#del_session_id').val();
            var reason = $('#reason').val();
            $modalDiv.addClass('modalloading');
            $.ajax({
                type: "post",
                url: '<?php echo site_url("studentfee/deleteFee_session") ?>',
                dataType: 'JSON',
                data: {
                    'main_invoice': main_invoice,
                    'sub_invoice': sub_invoice,
                    'reason': reason,
                    'session_id': del_session_id
                },
                success: function(data) {
                    $modalDiv.modal('hide').removeClass('modalloading');
                    location.reload(true);
                }
            });
        });

        $('#confirm-discountdelete').on('click', '.btn-discountdel', function(e) {
            var $modalDiv = $(e.delegateTarget);
            var discount_id = $('#discount_id').val();

            $modalDiv.addClass('modalloading');
            $.ajax({
                type: "post",
                url: '<?php echo site_url("studentfee/deleteStudentDiscount") ?>',
                dataType: 'JSON',
                data: {
                    'discount_id': discount_id
                },
                success: function(data) {
                    $modalDiv.modal('hide').removeClass('modalloading');
                    location.reload(true);
                }
            });
        });
        $('.detail_popover').popover({
            placement: 'right',
            title: '',
            trigger: 'hover',
            container: 'body',
            html: true,
            content: function() {
                return $(this).closest('td').find('.fee_detail_popover').html();
            }
        });
    });
    var fee_amount = 0;
</script>
<script type="text/javascript">
    $("#myFeesModal").on('shown.bs.modal', function(e) {
        e.stopPropagation();
        var discount_group_dropdown = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
        var total_main_fees = $('input#total_main_fees').val();
        var total_other_fees = $('input#total_other_fees').val();
        var data = $(e.relatedTarget).data();
        var modal = $(this);
        var type = data.type;
        var amount = data.amount;
        var group = data.group;
        var fee_groups_feetype_id = data.fee_groups_feetype_id;
        var student_fees_master_id = data.student_fees_master_id;
        var student_session_id = data.student_session_id;



        $('.fees_title').html("");
        $('.fees_title').html("<b>" + group + ":</b> " + type);
        $('#fee_groups_feetype_id').val(fee_groups_feetype_id);
        $('#student_fees_master_id').val(student_fees_master_id);

        $.ajax({
            type: "post",
            url: '<?php echo site_url("studentfee/geBalanceFee") ?>',
            dataType: 'JSON',
            data: {
                'fee_groups_feetype_id': fee_groups_feetype_id,
                'student_fees_master_id': student_fees_master_id,
                'student_session_id': student_session_id
            },
            beforeSend: function() {
                $('#discount_group').html("");
                $("span[id$='_error']").html("");
                $('#amount').val("");
                $('#amount_discount').val("0");
                $('#amount_fine').val("0");
                modal.addClass('modal_loading');
            },
            success: function(data) {

                if (data.status === "success") {
                    fee_amount = data.balance;

                    $('#amount').val(data.balance);
                    $('#amount_fine').val(data.remain_amount_fine);

                    $.each(data.discount_not_applied, function(i, obj) {
                        discount_group_dropdown += "<option value=" + obj.student_fees_discount_id + " data-fees_type=" + obj.fees_type + " data-total_main_fees=" + total_main_fees + " data-total_other_fees=" + total_other_fees + " data-disamount=" + obj.amount + " data-code=" + obj.code + " data-bal_amt=" + data.balance + " data-percent=" + obj.feepercent + ">" + obj.code + "</option>";
                    });
                    $('#discount_group').append(discount_group_dropdown);

                }
            },
            error: function(xhr) { // if error occured
                alert("Error occured.please try again");

            },
            complete: function() {
                modal.removeClass('modal_loading');
            }
        });
    });
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $.extend($.fn.dataTable.defaults, {
            searching: false,
            ordering: false,
            paging: false,
            bSort: false,
            info: false
        });
    });
    $(document).ready(function() {
        $('.table-fixed-header').fixedHeader();
    });

    (function($) {

        $.fn.fixedHeader = function(options) {
            var config = {
                topOffset: 50
                //bgColor: 'white'
            };
            if (options) {
                $.extend(config, options);
            }

            return this.each(function() {
                var o = $(this);

                var $win = $(window);
                var $head = $('thead.header', o);
                var isFixed = 0;
                var headTop = $head.length && $head.offset().top - config.topOffset;

                function processScroll() {
                    if (!o.is(':visible')) {
                        return;
                    }
                    if ($('thead.header-copy').size()) {
                        $('thead.header-copy').width($('thead.header').width());
                    }
                    var i;
                    var scrollTop = $win.scrollTop();
                    var t = $head.length && $head.offset().top - config.topOffset;
                    if (!isFixed && headTop !== t) {
                        headTop = t;
                    }
                    if (scrollTop >= headTop && !isFixed) {
                        isFixed = 1;
                    } else if (scrollTop <= headTop && isFixed) {
                        isFixed = 0;
                    }
                    isFixed ? $('thead.header-copy', o).offset({
                        left: $head.offset().left
                    }).removeClass('hide') : $('thead.header-copy', o).addClass('hide');
                }
                $win.on('scroll', processScroll);

                // hack sad times - holdover until rewrite for 2.1
                $head.on('click', function() {
                    if (!isFixed) {
                        setTimeout(function() {
                            $win.scrollTop($win.scrollTop() - 47);
                        }, 10);
                    }
                });

                $head.clone().removeClass('header').addClass('header-copy header-fixed').appendTo(o);
                var header_width = $head.width();
                o.find('thead.header-copy').width(header_width);
                o.find('thead.header > tr:first > th').each(function(i, h) {
                    var w = $(h).width();
                    o.find('thead.header-copy> tr > th:eq(' + i + ')').width(w);
                });
                $head.css({
                    margin: '0 auto',
                    width: o.width(),
                    'background-color': config.bgColor
                });
                processScroll();
            });
        };

    })(jQuery);


    $(".applydiscount").click(function() {
        $("span[id$='_error']").html("");
        $('.discount_title').html("");
        $('#student_fees_discount_id').val("");
        var student_fees_discount_id = $(this).data("student_fees_discount_id");
        var modal_title = $(this).data("modal_title");


        $('.discount_title').html("<b>" + modal_title + "</b>");

        $('#student_fees_discount_id').val(student_fees_discount_id);
        $('#myDisApplyModal').modal({
            backdrop: 'static',
            keyboard: false,
            show: true
        });
    });

    $(document).on('click', '.dis_apply_button', function(e) {
        var $this = $(this);
        $this.button('loading');

        var discount_payment_id = $('#discount_payment_id').val();
        var student_fees_discount_id = $('#student_fees_discount_id').val();
        var dis_description = $('#dis_description').val();

        $.ajax({
            url: '<?php echo site_url("admin/feediscount/applydiscount") ?>',
            type: 'post',
            data: {
                discount_payment_id: discount_payment_id,
                student_fees_discount_id: student_fees_discount_id,
                dis_description: dis_description
            },
            dataType: 'json',
            success: function(response) {
                $this.button('reset');
                if (response.status === "success") {
                    location.reload(true);
                } else if (response.status === "fail") {
                    $.each(response.error, function(index, value) {
                        var errorDiv = '#' + index + '_error';
                        $(errorDiv).empty().append(value);
                    });
                }
            }
        });
    });
</script>

<script type="text/javascript">
    $(document).ready(function() {

        $(document).on('click', '.chkgrp', function() {
           var clname = ".grp_" + $(this).data('master_id');
            //alert(clname);
            //$('grp_1448').prop('checked');
            if($(this).prop("checked"))
            {$(clname).prop("checked", true);}
            else
            {$(clname).prop("checked", false);}
            

        });
        $(document).on('click', '.printSelected', function() {
            var array_to_print = [];
            $.each($("input[name='fee_checkbox']:checked"), function() {
                var fee_session_group_id = $(this).data('fee_session_group_id');
                var fee_master_id = $(this).data('fee_master_id');
                var fee_groups_feetype_id = $(this).data('fee_groups_feetype_id');
                item = {};
                item["fee_session_group_id"] = fee_session_group_id;
                item["fee_master_id"] = fee_master_id;
                item["fee_groups_feetype_id"] = fee_groups_feetype_id;

                array_to_print.push(item);
            });
            if (array_to_print.length === 0) {
                alert("<?php echo $this->lang->line('no_record_selected'); ?>");
            } else {
                $.ajax({
                    url: '<?php echo site_url("studentfee/printFeesByGroupArray") ?>',
                    type: 'post',
                    data: {
                        'data': JSON.stringify(array_to_print)
                    },
                    success: function(response) {
                        Popup(response);
                    }
                });
            }
        });


        $(document).on('click', '.collectSelected', function() {
            var $this = $(this);
            var student_session_id = $(this).data('student_session_id');
            var total_main_fees = $('input#total_main_fees').val();
            var total_other_fees = $('input#total_other_fees').val();
            var array_to_collect_fees = [];
            $.each($("input[name='fee_checkbox']:checked"), function() {
                var fee_session_group_id = $(this).data('fee_session_group_id');
                var fee_master_id = $(this).data('fee_master_id');
                var fee_groups_feetype_id = $(this).data('fee_groups_feetype_id');
                item = {};
                item["fee_session_group_id"] = fee_session_group_id;
                item["fee_master_id"] = fee_master_id;
                item["fee_groups_feetype_id"] = fee_groups_feetype_id;

                array_to_collect_fees.push(item);
            });

            $.ajax({
                type: 'POST',
                url: base_url + "studentfee/getcollectfee",
                data: {
                    'data': JSON.stringify(array_to_collect_fees),
                    student_session_id: student_session_id,
                    total_main_fees: total_main_fees,
                    total_other_fees: total_other_fees,
                },
                dataType: "JSON",
                beforeSend: function() {
                    $this.button('loading');
                },
                success: function(data) {

                    $("#listCollectionModal .modal-body").html(data.view);

                    $("#listCollectionModal").modal('show');
                    $("#paid_amount").val($("#valid_amount").val());
                    $this.button('reset');
                },
                error: function(xhr) { // if error occured
                    alert("Error occured.please try again");

                },
                complete: function() {
                    $this.button('reset');
                }
            });
        });
    });
    $(function() {
        $(document).on('change', "#discount_group", function() {
            var amount = $('option:selected', this).data('disamount');
            var percent = $('option:selected', this).data('percent');
            var total_main_fees = $('option:selected', this).data('total_main_fees');
            var total_other_fees = $('option:selected', this).data('total_other_fees');
            var fees_type = $('option:selected', this).data('fees_type');
            var code = $('option:selected', this).data('code');
            var bal_amt = $('option:selected', this).data('bal_amt');

            if (percent > 0) {
                if (fees_type == "m") {
                    amount = Math.round(parseFloat(total_main_fees) * (percent / 100), 2);
                } else if (code == "ADFD") {
                    amount = Math.round(parseFloat(amount), 2);

                } else {
                    amount = Math.round(parseFloat(total_other_fees) * (percent / 100), 2);
                }
            }
            var balance_amount = (parseFloat(fee_amount) - parseFloat(amount)).toFixed(2);
            if (typeof amount !== typeof undefined && amount !== false) {
                $('div#myFeesModal').find('input#amount_discount').prop('readonly', true).val(amount);
                $('div#myFeesModal').find('input#amount').val(balance_amount);
            } else {
                $('div#myFeesModal').find('input#amount').val(fee_amount);
                $('div#myFeesModal').find('input#amount_discount').prop('readonly', false).val(0);
            }
        });
    });

    function process_fine()
    {
                var am=0;
                var balance_amount = 0;
                balance_amount = $('#total_fine_amount').val();
                $('.item_count').each(function(index,element) {
                    itm_cnt =  $(element).data('no');
                    fine_amount = $("#fee_groups_feetype_fine_amount_" + itm_cnt).val();
                    org_fine_amount = $("#org_fine_amount_" + itm_cnt).val();
                    fee_amount = $("#fee_amount_" + itm_cnt).val();
                    fine_amount = parseFloat(org_fine_amount);
                    console.log(balance_amount);
                    if(balance_amount>0)
                    {
                        if (parseFloat(balance_amount) >= parseFloat(org_fine_amount))
                        {
                            $("#fee_groups_feetype_fine_amount_" + itm_cnt).val(org_fine_amount);
                            $('#dis_fine_amount_' + itm_cnt).html("Rs." + parseFloat(org_fine_amount).toFixed(2));
                            balance_amount = parseFloat(balance_amount) - parseFloat(org_fine_amount); 
                        }
                        else
                        {
                            $("#fee_groups_feetype_fine_amount_" + itm_cnt).val(balance_amount);
                            console.log(balance_amount);
                            $('#dis_fine_amount_' + itm_cnt).html("Rs." + parseFloat(balance_amount).toFixed(2));                            
                            balance_amount=0;
                        }
                    }
                    else
                    {
                        $("#fee_groups_feetype_fine_amount_" + itm_cnt).val(0);
                        $('#dis_fine_amount_' + itm_cnt).html("");                            
                    }             
                });     
                calc_total();
    }
    $(function() {
        $(document).on('change', "#total_fine_amount", function() {
            process_fine();
        });
    });
    function calc_total()
    {
                var am=0;
                var balance_amount = 0;
                var tot_fine_amount = 0;
                var calc_paid_amount = 0;
                balance_amount = $('#paid_amount').val();
                $('.item_count').each(function(index,element) {
                    itm_cnt =  $(element).data('no');
                    org_fee_amount = $("#org_fee_amount_" + itm_cnt).val();
                    disc = $('#amount_discount_' + itm_cnt).val();
                    fine_amount = $("#fee_groups_feetype_fine_amount_" + itm_cnt).val();
                    org_fee_amount = parseFloat(org_fee_amount) - parseFloat(disc) + parseFloat(fine_amount);
                    
                    tot_fine_amount = parseFloat(tot_fine_amount) + parseFloat(fine_amount);
                    //console.log( itm_cnt + '-' +  tot_fine_amount);
                    if(balance_amount>0)
                    {
                        if (parseFloat(balance_amount) >= parseFloat(org_fee_amount))
                        {
                            balance_amount = parseFloat(balance_amount) - parseFloat(org_fee_amount);
                            $('#fee_amount_' + itm_cnt).val(parseFloat(org_fee_amount) + parseFloat(disc) - parseFloat(fine_amount));
                            $('#dis_fee_amount_' + itm_cnt).html("Rs." + (parseFloat(org_fee_amount) + parseFloat(disc) - parseFloat(fine_amount)).toFixed(2));
                        }
                        else
                        {
                            $('#fee_amount_' + itm_cnt).val(balance_amount);
                            $('#dis_fee_amount_' + itm_cnt).html("Rs." + parseFloat(balance_amount).toFixed(2));
                            balance_amount=0;
                        }
                    }
                    else
                    {
                        $('#fee_amount_' + itm_cnt).val(0);
                        $('#dis_fee_amount_' + itm_cnt).html("0.00");
                    }             
                    calc_paid_amount = parseFloat(calc_paid_amount) + parseFloat($('#fee_amount_' + itm_cnt).val()) - parseFloat(disc);
                });     
                balance_amount= parseFloat(calc_paid_amount) + parseFloat(tot_fine_amount);
                $('#total_amt_').text("Rs." + balance_amount.toFixed(2));
                $('#valid_amount').val(balance_amount);
                if(parseFloat($('#valid_amount').val())!= parseFloat($('#paid_amount').val()))
                {
                    $("#pay_print").prop('disabled', true);
                    $("#pay_btn").prop('disabled', true);                    
                }
                else
                {
                    $("#pay_print").removeAttr('disabled');
                    $("#pay_btn").removeAttr('disabled');                    
                }
    }
    function clear_discount()
    {
            var itm_cnt=0;
                $('.item_count').each(function(index,element) {
                    itm_cnt =  $(element).data('no');
                    $('#amount_discount_' + itm_cnt).val(0);                
                });     
    }
        $(function() {
            $(document).on('change', "#paid_amount", function() {
                calc_total();
            });
        });

        $(function() {
        $(document).on('change', "#discount_amt", function() {
            var amount = parseFloat($('option:selected', this).data('dis_amt'));
            var fee_amount = parseFloat($('.total_amt_').val());
            var percent = $('option:selected', this).data('per_cent');
            var total_main_fees = $('option:selected', this).data('total_main_fees');
            var total_other_fees = $('option:selected', this).data('total_other_fees');
            var fees_type = $('option:selected', this).data('fees_type');
            var code = $('option:selected', this).data('code');
            var am=0;
            var balance_amount = 0;
            disc_amt=0;
            disc_collected = 0;
            // $('.item_count').each(function(index,element) {
            //     itm_cnt =  $(element).data('no');
            //     org_fee_amount = $("#org_fee_amount_" + itm_cnt).val();
            //     am=0;
            //     $('#fee_amount_' + itm_cnt).val(org_fee_amount);
            //     $('#amount_discount_' + itm_cnt).val(0);                
            // });
            clear_discount();
            $('.item_count').each(function(index,element) {
                itm_cnt =  $(element).data('no');
                fee_code = $("#fee_type_" + itm_cnt).val();
                fee_amount = $("#org_fee_amount_" + itm_cnt).val();
                balance_amount = parseFloat(balance_amount) + parseFloat(fee_amount);
                org_fee_amount = $("#org_fee_amount_" + itm_cnt).val();
                if(disc_amt==0)
                {
                    if (fees_type == "m"  || fees_type == "o"  || fees_type == "O") {
                        if(amount>0)
                        {disc_amt = Math.round(parseFloat(amount), 2);}
                        else
                        {if(percent>0) {disc_amt = Math.round(parseFloat(total_main_fees) * (percent / 100), 2);}}
                        
                    } else if (fees_type == "b" && code == "ADFD" && fee_code == "adm-fees" ) {
                        disc_amt = Math.round(parseFloat(amount), 2);
                    } 
                    if(disc_amt > fee_amount)
                    {
                        disc_amt=0;
                    }
                    if ( parseFloat(disc_amt) > 0 && parseFloat(disc_amt) <= parseFloat(fee_amount) ) {
                        //var balance_amount = parseFloat(fee_amount) - parseFloat(disc_amt);
                        //$('#fee_amount_' + itm_cnt).val(balance_amount.toFixed(2));
                        $('#amount_discount_' + itm_cnt).val(disc_amt.toFixed(2));
                        $('#discount_text').text("Rs." + disc_amt.toFixed(2));
                    }
                }                        
                                    
            });    
            calc_total();
        });
    });


    // $("#collect_fee_group").submit(function(e) {
    //     var form = $("#collect_fee_group");
    //     var url = form.attr('action');
    //     var smt_btn = $(this).find("button[type=submit]");
    //     var submitButton = $(this).data('actions');
    //     var submit = $("button[type=submit]",this).attr('name'); 
    //     // alert(submit); // name of submit button
    //     $.ajax({
    //         type: "POST",
    //         url: url,
    //         dataType: 'JSON',
    //         data: form.serialize(), // serializes the form's elements.
    //         beforeSend: function() {
    //             smt_btn.button('loading');
    //         },
    //         success: function(response) {

    //             if (response.status === 1) {

    //                 location.reload(true);
    //             } else if (response.status === 0) {
    //                 $.each(response.error, function(index, value) {
    //                     var errorDiv = '#form_collection_' + index + '_error';
    //                     $(errorDiv).empty().append(value);
    //                 });
    //             }
    //         },
    //         error: function(xhr) { // if error occured

    //             alert("Error occured.please try again");

    //         },
    //         complete: function() {
    //             smt_btn.button('reset');
    //         }
    //     });

    //     e.preventDefault(); // avoid to execute the actual submit of the form.
    // });

    function collect_fee_group(print) {
        var form = $("#collect_fee_group");
        // var url = form.attr('action');
        var url = '<?php echo site_url('studentfee/addfeegrp'); ?>';
        var smt_btn = $(this).find("button[type=submit]");
        var total_paid = $('input#total_paid').val();
        var paid_amount = $('input#paid_amount').val();
        var tot_fine_amount = $('input#total_fine_amount').val();
        var total_balance = $('input#total_balance').val();
        var paymode = $('input#radio_temp').val();
        var source = 'counter-others';
        if(paymode == 'Cheque')
        {
            var cheque_no = $('input#cheque_no').val();
            var cheque_bank = $('input#cheque_bank').val();
            if(cheque_no='' || $.isNumeric(cheque_no) == false )
            {
                alert('Please enter the chque no.');
                $('input#cheque_no').focus();
                $('#pay_print').prop('disabled', false);
                $("#pay_btn").prop('disabled', false);                 
                return false;
            }
        }        
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'JSON',
            data: form.serialize() + "&print=" + print + "&total_paid=" + total_paid + "&total_balance=" + total_balance  + "&paid_amount=" + paid_amount + "&tot_fine_amount=" + tot_fine_amount + "&source=" + source, // serializes the form's elements.
            beforeSend: function() {
                smt_btn.button('loading');
            },
            success: function(response) {
                // return response;
                if (response.status === 1) {
                    if (response.mode=='gateway')
                    {
                        //window.location.href = "<?php echo site_url('site/worldline_payrequest/'); ?>" + response.hash_code;
                       window.location.href = "<?php echo site_url('onlineadmission/ccavenue/index/'); ?>" + response.hash_code;
                    }
                    else if(response.mode=='cheque')
                    {
                        Popup(response.print, true);
                    }
                    else
                    {
                        if (print == 0) {
                            location.reload(true);
                        } else if (print == 1) {
                            Popup(response.print, true);
                        }                        
                    }              
                } else if (response.status === 0) {
                    $.each(response.error, function(index, value) {
                        var errorDiv = '#form_collection_' + index + '_error';
                        $(errorDiv).empty().append(value);
                    });
                }
            },
            error: function(xhr) { // if error occured

                alert("Error occured.please try again");

            },
            complete: function() {
                smt_btn.button('reset');
            }
        });
    }

    $(document).on("click", "#pay_btn", function() {
        var collect = collect_fee_group(0);
        console.log(collect);
        // alert('pay_btn');
    });

    $(document).on("click", "#pay_print", function() {
        var paid_amt = $("#paid_amount").val();
        var valid_amt = $("#valid_amount").val();
        if(parseFloat(paid_amt)!=parseFloat(valid_amt))
        {
            alert("Paid Amount Mismatch");
            return false;
        }
        $(this).prop('disabled', true);
        $("#pay_btn").prop('disabled', true);
        var collect = collect_fee_group(1);
        console.log(collect);
        // alert('pay_print');

        e.preventDefault(); // avoid to execute the actual submit of the form.
    });

    $("#select_all").change(function() { //"select all" change
        $('input:checkbox').not(this).prop('checked', this.checked);
    });
    
    $(document).on("click", ".deleteassignfees", function() {
        var $this = $(this);
        var student_session_id = $(this).data('student_session_id');
        var student_fees_master_id = $(this).data('student_fees_master_id');
        if (confirm("Are you sure?")) {
            $.ajax({
                type: 'POST',
                url: base_url + "studentfee/deleteassignfee",
                data: {
                    student_session_id: student_session_id,
                    student_fees_master_id: student_fees_master_id,
                },
                dataType: "JSON",
                success: function(data) {

                    location.reload(true); 
                },
                
            });
    }
    return false;

        
    });
</script>