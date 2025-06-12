<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<style type="text/css">
    .collect_grp_fees {
        font-size: 15px;
        font-weight: 600;
        padding-bottom: 15px;
    }

    .fees-list {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .fees-list>.item {
        border-radius: 3px;
        -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
        box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
        padding: 10px 0;
        background: #fff;
    }

    .fees-list>.item:before,
    .fees-list>.item:after {
        content: " ";
        display: table;
    }

    .fees-list>.item:after {
        clear: both;
    }

    .fees-list .product-img {
        float: left;
    }

    .fees-list .product-img img {
        width: 50px;
        height: 50px;
    }

    .fees-list .product-info {
        margin-left: 0px;
    }

    .fees-list .product-title {
        font-weight: 600;
        font-size: 15px;
        display: inline;
    }

    .fees-list .product-title span {

        font-size: 15px;
        display: inline;
        font-weight: 100 !important;
    }

    .fees-list .product-description {
        display: block;
        color: #999;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }

    .fees-list-in-box>.item {
        -webkit-box-shadow: none;
        box-shadow: none;
        border-radius: 0;
        border-bottom: 1px solid #f4f4f4;
    }

    .fees-list-in-box>.item:last-of-type {
        border-bottom-width: 0;
    }

    .fees-footer {
        border-top-color: #f4f4f4;
    }

    .fees-footer {
        padding: 15px 0px 0px 0px;
        text-align: right;
        border-top: 1px solid #e5e5e5;
    }
</style>
<div class="box-body">
    <div class="row">
        <div class="col-lg-12">
            <span id="form_collection_valid_amount_error" class="text text-danger"></span>
            <div class="form-group">
                    <label for="inputEmail3" class="col-sm-3 control-label"><?php echo "Paying Amount"; ?> </label>
                    <div class="col-sm-9">
                        <input  name="paid_amount" id="paid_amount" placeholder="" type="text" class="form-control " value=""  autocomplete="off">
                        <span id="form_paid_amount_error" class="text text-danger"></span>
                    </div>
                </div>                

                <div class="form-group">
                    
                    <div class="col-sm-9">
                        <?php 
                            if(isset($lafd)) {if($lafd) {
                                if($lafd->custom_amount > 0 ) //for custom discount amount
                                {$amt = $lafd->custom_amount;}
                                elseif($lafd->amount > 0 ) //for discount with amount
                                {$amt = $lafd->amount;}
                                else
                                {$amt=0;}//for discount with out amount                                
                                ?>
                            <input type = "hidden" id="lafd_disc" name = "lafd_disc"  value="" data-lafdenabled = "1" data-discid = "<?php echo $lafd->id; ?>" data-code="LAFD" data-fees_type="m" data-total_main_fees="<?php echo $total_main_fees;?>" data-total_other_fees="<?php echo $total_other_fees;?>" data-per_cent="<?php echo $lafd->feepercent;?>" data-dis_amt="<?php echo $amt;?>" data-applied="0" data-lastdate="<?php echo date('m-d-Y',strtotime($lafd->date_upto)) ?>" >
                        <?php } }    
                        ?>
                        <?php 
                            if(isset($sibling_disc) && $full_pay == 1) {if($sibling_disc) { ?>
                            <input type = "hidden" id="sib_disc" name = "sib_disc"  value="" data-sibenabled = "1" data-discid = "<?php echo $sibling_disc->id; ?>" data-code="LAFD" data-fees_type="m" data-total_main_fees="<?php echo $total_main_fees;?>" data-total_other_fees="<?php echo $total_other_fees;?>" data-per_cent="<?php echo $sibling_disc->feepercent;?>" data-dis_amt="<?php echo $sibling_disc->amount;?>" data-applied="0" data-lastdate="<?php echo date('m-d-Y',strtotime($sibling_disc->date_upto)) ?>" >
                        <?php } }   
                        ?>
                        <input type = "hidden" id="ot_disc" name = "ot_disc"  value="" data-otenabled = "<?php echo $ot_enabled; ?>" data-discid = "<?php echo $ot_disc[0]->id; ?>" data-code="EFD" data-fees_type="m" data-total_main_fees="<?php echo $total_main_fees;?>" data-total_other_fees="<?php echo $total_other_fees;?>" data-per_cent="<?php echo $ot_disc[0]->feepercent;?>" data-dis_amt="<?php echo $ot_disc[0]->amount;?>" data-applied="0" data-lastdate="<?php echo date('m-d-Y',strtotime($ot_disc[0]->date_upto)) ?>" >
                        <input type = "hidden" id="ot_discid" name = "ot_discid"  value="<?php echo $ot_disc[0]->id; ?>">
                    </div>
                </div>
                <?php
                $row_counter = 1;
                $total_amount = 0;
                $total_fine_amount = 0;
                foreach ($feearray as $fee_key => $fee_value) {
                    $amount_prev_paid = 0;
                    $fees_fine_amount = 0;
                    $fine_amount_paid = 0;
                    $amount_to_be_pay = $fee_value->amount;
                    
                    if ($fee_value->is_system) {
                        $amount_to_be_pay = $fee_value->student_fees_master_amount;
                    }
                    if (is_string(($fee_value->amount_detail)) && is_array(json_decode(($fee_value->amount_detail), true))) {

                    $amount_data = json_decode($fee_value->amount_detail);
                    foreach ($amount_data as $amount_data_key => $amount_data_value) {
                        $fine_amount_paid += $amount_data_value->amount_fine;
                        $amount_prev_paid = $amount_prev_paid + ($amount_data_value->amount + $amount_data_value->amount_discount);
                    }
                }
                    if (($fee_value->due_date != "0000-00-00" && $fee_value->due_date != NULL) && (strtotime($fee_value->due_date) < strtotime(date('Y-m-d'))) && $amount_to_be_pay > 0) {
                        $fees_fine_amount = $fee_value->fine_amount - $fine_amount_paid;
                        $total_amount = $total_amount + $fees_fine_amount;
                        $fine_amount_status = true;
                        $total_fine_amount+=$fees_fine_amount;
                    }
                ?>
                <!--<div class="form-group">
                     <label for="inputEmail3" class="col-sm-3 control-label"><?php echo $this->lang->line('fine'); ?> </label> 
                    
                    <div class="col-sm-9"> -->
                        <input type = "hidden"  name="fee_groups_feetype_fine_amount_<?php echo $row_counter; ?>" placeholder="" type="text" class="form-control " value="<?php echo $fees_fine_amount; ?>"  autocomplete="off">
                    <!--    <span id="form_collection_amount_fine_error" class="text text-danger"></span>
                    </div>
                </div> -->
                <?php $row_counter++; }?>
                <input style ="visibility: hidden;" name="total_fine_amount" id="total_fine_amount" placeholder="" type="text" class="form-control " value="<?php echo $total_fine_amount; ?>"  autocomplete="off">
                <input type="hidden" name="payment_mode_fee" id="payment_mode_fee" value="Online" > 
                <input type="hidden"  id="description"  name="fee_gupcollected_note" value="" >
                <input type="hidden" id="date" name="collected_date" value="<?php echo date('d-m-Y');?>">
            </div>
            <ul class="fees-list fees-list-in-box">
                <?php
                $row_counter = 1;
                $total_amount = 0;
                foreach ($feearray as $fee_key => $fee_value) {
                    $amount_prev_paid = 0;
                    $fees_fine_amount = 0;
                    $fine_amount_paid = 0;
                    $fine_amount_status = false;
                    $amount_to_be_pay = $fee_value->amount;

                    if ($fee_value->is_system) {
                        $amount_to_be_pay = $fee_value->student_fees_master_amount;
                    }

                    if (is_string(($fee_value->amount_detail)) && is_array(json_decode(($fee_value->amount_detail), true))) {
                        $amount_data = json_decode($fee_value->amount_detail);
                        foreach ($amount_data as $amount_data_key => $amount_data_value) {
                            $fine_amount_paid += $amount_data_value->amount_fine;
                            $amount_prev_paid = $amount_prev_paid + ($amount_data_value->amount + $amount_data_value->amount_discount);
                        }
                        if ($fee_value->is_system) {
                            $amount_to_be_pay = $fee_value->student_fees_master_amount - $amount_prev_paid;
                        } else {
                            $amount_to_be_pay = $fee_value->amount - $amount_prev_paid;
                        }
                    }
                    if (($fee_value->due_date != "0000-00-00" && $fee_value->due_date != NULL) && (strtotime($fee_value->due_date) < strtotime(date('Y-m-d'))) && $amount_to_be_pay > 0) {
                        $fees_fine_amount = $fee_value->fine_amount - $fine_amount_paid;
                        $total_amount = $total_amount + $fees_fine_amount;
                        $fine_amount_status = true;
                    }
                    $total_amount = $total_amount + $amount_to_be_pay;
                    if ($amount_to_be_pay > 0) {
                ?>
                        <li class="item">
                            <input data-no="<?php echo $row_counter; ?>" class = "item_count" name="row_counter[]" type="hidden" value="<?php echo $row_counter; ?>">
                            <input name="amount_discount_<?php echo $row_counter; ?>" id="amount_discount_<?php echo $row_counter; ?>" type="hidden" value="0">
                            <input name="disc_code_<?php echo $row_counter; ?>" id="disc_code_<?php echo $row_counter; ?>" type="hidden" value="">
                            <input name="session_id_<?php echo $row_counter; ?>" id="session_id_<?php echo $row_counter; ?>" type="hidden" value="<?php echo $fee_value->session_id; ?>">
                            <input name="student_fees_master_id_<?php echo $row_counter; ?>" type="hidden" value="<?php echo $fee_value->id; ?>">
                            <input name="fee_groups_feetype_id_<?php echo $row_counter; ?>" type="hidden" value="<?php echo $fee_value->fee_groups_feetype_id; ?>">
                            <input name="fee_groups_feetype_fine_amount_<?php echo $row_counter; ?>"  id="fee_groups_feetype_fine_amount_<?php echo $row_counter; ?>" type="hidden" value="<?php echo $fees_fine_amount; ?>">
                            <input name="org_fine_amount_<?php echo $row_counter; ?>"  id="org_fine_amount_<?php echo $row_counter; ?>" type="hidden" value="<?php echo $fees_fine_amount; ?>">
                            <input name="fee_amount_<?php echo $row_counter; ?>" id="fee_amount_<?php echo $row_counter; ?>" type="hidden" value="<?php echo $amount_to_be_pay; ?>">
                            <input name="org_fee_amount_<?php echo $row_counter; ?>" id="org_fee_amount_<?php echo $row_counter; ?>" type="hidden" value="<?php echo $amount_to_be_pay; ?>">
                            <input id="fee_type_<?php echo $row_counter; ?>"  type="hidden" value="<?php echo $fee_value->code; ?>">
                            <div class="product-info">
                                <a href="#" onclick="return false;" class="product-title"><?php echo $fee_value->name; ?>
                                    <span class="pull-right" id="dis_fee_amount_<?php echo $row_counter; ?>"><?php echo  $currency_symbol . number_format((float) $amount_to_be_pay, 2, '.', ''); ?></span></a>
                                <span class="product-description">
                                    <?php echo $fee_value->code; ?>
                                </span>
                                <?php
                                if ($fine_amount_status) {
                                ?>
                                    <a href="#" onclick="return false;" class="product-title text text-danger"><?php echo $this->lang->line('fine'); ?>
                                        <span class="pull-right"  id="dis_fine_amount_<?php echo $row_counter; ?>">
                                            <?php echo  $currency_symbol . number_format((float) $fees_fine_amount, 2, '.', ''); ?>
                                        </span>
                                    </a>

                                <?php
                                }
                                ?>
                                <br>

                            </div>
                        </li>

                <?php
                    }
                    $row_counter++;
                }
                ?>

                <li class="item">
                    <div class="product-info">
                    <a href="#" onclick="return false;" class="product-title text "><?php echo $this->lang->line('discount'); ?>
                                        <span class="pull-right " id="discount_text">
                                            <?php //echo  $currency_symbol . number_format((float) $fees_fine_amount, 2, '.', ''); ?>
                                        </span>
                                    </a>                        
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
<?php if ($total_amount > 0) { ?>
    <div class="row collect_grp_fees">
        <div class="col-md-8">
            <span class="pull-right">
                <?php echo $this->lang->line('total') . " " . $this->lang->line('pay'); ?>
            </span>
        </div>
        <div class="col-md-4">
            <input type="hidden" name="valid_amount" id="valid_amount" class="" value="<?php echo  number_format((float) $total_amount, 2, '.', ''); ?>">
            <input type="hidden" name="amount" class="total_amt_" value="<?php echo  number_format((float) $total_amount, 2, '.', ''); ?>">
            <input type="hidden" name="total_disc" id="total_disc"  value="">
            <span class="pull-right " id="total_amt_">
                <?php echo $currency_symbol . number_format((float) $total_amount, 2, '.', ''); ?>
            </span>
        </div>
    </div>
    <div class="row fees-footer">
        <div class="col-md-12">
            <button type="button" id="pay_print" name="collect" value="collect"  style="margin-left: 12px" class="btn  btn-primary pull-right payment_collect" data-actions="print" data-loading-text="<i class='fa fa-spinner fa-spin '></i><?php echo $this->lang->line('processing') ?>"><i class="fa fa-money"></i> <?php echo $this->lang->line('pay') ; ?></button> 
            <!--<button type="button" id="pay_btn" name="print"  value="print" class="btn btn-primary pull-right payment_collect" data-actions="collect" data-loading-text="<i class='fa fa-spinner fa-spin '></i><?php echo $this->lang->line('processing') ?>"><i class="fa fa-money"></i> <?php echo $this->lang->line('pay'); ?></button> -->
        </div>
    </div>
<?php } else { ?>
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-info">
                <?php echo $this->lang->line('no_fees_found'); ?>
            </div>
        </div>
    <?php
}
    ?>