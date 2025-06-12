<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();

$cashtotal = 0;
$banktotal = 0;

$netcashtotal=0;


$netbanktotal=0;
$netcashdisc=0;
$netbankdisc=0;
$netcanceltotal=0;

$gateway_total = 0;
$net_gateway_total = 0;
$net_gateway_total_disc = 0;
?>
<div class="content-wrapper">
    <section class="content-header"></section>
    <!-- Main content -->
    <section class="content">
        <?php $this->load->view('reports/_finance'); ?>
        <div class="row">
            <div class="col-md-12">
                <div class="box removeboxmius">
                    <div class="box-header ptbnull"></div>
                    <div class="box-header ">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>
                    <form role="form" action="<?php echo site_url('studentfee/collection_report_old') ?>" method="post" class="">
                        <div class="box-body row" style="display:show">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="col-sm-3 col-lg-3 col-md-3">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('search') . " " . $this->lang->line('duration'); ?><small class="req"> *</small></label>
                                    <select class="form-control" name="search_type" onchange="showdate(this.value)">

                                        <?php foreach ($searchlist as $key => $search) {
                                        ?>
                                            <option value="<?php echo $key ?>" <?php
                                                                                if ((isset($search_type)) && ($search_type == $key)) {
                                                                                    echo "selected";
                                                                                }
                                                                                ?>><?php echo $search ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('search_type'); ?></span>
                                </div>
                            </div>
                            <?php /*?>
                            <div class="col-sm-3 col-lg-3 col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('fees_type'); ?></label>

                                    <select id="feetype_id" name="feetype_id" class="form-control">
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php
                                        foreach ($feetypeList as $feetype) {
                                        ?>
                                            <option value="<?php echo $feetype['id'] ?>" <?php
                                                                                            if (set_value('feetype_id') == $feetype['id']) {
                                                                                                echo "selected =selected";
                                                                                            }
                                                                                            ?>><?php echo $feetype['type'] ?></option>

                                        <?php
                                            $count++;
                                        }
                                        ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('feetype_id'); ?></span>
                                </div>
                            </div>
                            <?php */ ?>
                            <div class="col-sm-3 col-lg-3 col-md-3">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('collect') . " " . $this->lang->line('by'); ?></label>
                                    <select class="form-control" name="collect_by">
                                        <option value=""><?php echo $this->lang->line('select') ?></option>
                                        <?php
                                        foreach ($collect_by as $key => $value) {
                                        ?>
                                            <option value="<?php echo $value ?>" <?php
                                                                                    if ((isset($received_by)) && ($received_by == $value)) {
                                                                                        echo "selected";
                                                                                    }
                                                                                    ?>><?php echo $value ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('collect_by'); ?></span>
                                </div>
                            </div>
                            <div class="col-sm-3 col-lg-3 col-md-3">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('session'); ?></label>
                                    <select class="form-control" name="session_id">
                                        <option value=""><?php echo $this->lang->line('select') ?></option>
                                        <?php
                                        foreach ($sessionlist as $key => $value) {
                                        ?>
                                            <option value="<?php echo $value['id'] ?>" <?php
                                                                                    if ((isset($session_id)) && ($session_id == $value['id'])) {
                                                                                        echo "selected";
                                                                                    }
                                                                                    ?>><?php echo $value['session'] ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('session_id'); ?></span>
                                </div>
                            </div>
                            <div class="col-sm-3 col-lg-3 col-md-3">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('payment') . " " . $this->lang->line('mode'); ?></label>
                                    <select class="form-control" name="payment_mode">
                                        <option value=""><?php echo $this->lang->line('select') ?></option>
                                        <option value="Cash" <?php
                                                                if ((isset($payment_mode)) && ($payment_mode == "Cash")) {
                                                                    echo "selected";
                                                                }
                                                                ?>><?php echo $this->lang->line('cash') ?></option>
                                        <option value="gateway" <?php
                                                                if ((isset($payment_mode)) && ($payment_mode == "gateway")) {
                                                                    echo "selected";
                                                                }
                                                                ?>><?php echo "Gateway"; ?></option>                                                                

                                        <option value="bank_transfer" <?php
                                                                if ((isset($payment_mode)) && ($payment_mode == "bank_transfer")) {
                                                                    echo "selected";
                                                                }
                                                                ?>><?php echo $this->lang->line('bank') ?></option>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('payment_mode'); ?></span>
                                </div>
                            </div>
                            <div id='date_result'>
                            </div>
                            <?php /*?>
                            <div class="col-sm-3 col-lg-3 col-md-3">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('group') . " " . $this->lang->line('by'); ?></label>
                                    <select class="form-control" name="group">
                                        <?php foreach ($group_by as $key => $value) {
                                        ?>
                                            <option value="<?php echo $key ?>" <?php
                                                                                if ((isset($group_byid)) && ($group_byid == $key)) {
                                                                                    echo "selected";
                                                                                }
                                                                                ?>><?php echo $value ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('group'); ?></span>
                                </div>
                            </div>
                            <?php */ ?>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button type="submit" name="search" value="search_filter" class="btn btn-primary btn-sm checkbox-toggle pull-right"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <?php
                    if (empty($results)) {
                    ?>
                        <div class="alert alert-info">
                            <?php echo $this->lang->line('no_record_found'); ?>
                        </div>
                    <?php
                    } else { ?>
                        <div class="">
                            <div class="box-header ptbnull"></div>
                            <div class="box-header ptbnull">
                                <h3 class="box-title titlefix"><i class="fa fa-money"></i> <?php ?> <?php echo $this->lang->line('fees') . " " . $this->lang->line('collection') . " " . $this->lang->line('report'); ?> From <?php echo date('d/m/Y', strtotime($start_date)) . " To " . date('d/m/y', strtotime($end_date)); ?></h3>
                            </div>
                            <div class="box-body table-responsive" id="transfee">
                                <div id="printhead">
                                    <center>
                                        <h5><?php ?> <?php echo $this->lang->line('fees') . " " . $this->lang->line('collection') . " " . $this->lang->line('report') . "<br>";
                                                        $this->customlib->get_postmessage();
                                                        ?></h5>
                                    </center>
                                </div>
                                <div class="download_label"><?php ?> <?php echo $this->lang->line('fees') . " " . $this->lang->line('collection') . " " . $this->lang->line('report') . "<br>";
                                                                        $this->customlib->get_postmessage();
                                                                        ?></div>

                                <a class="btn btn-default btn-xs pull-right" id="print" onclick="printDiv()"><i class="fa fa-print"></i></a>
                                <a class="btn btn-default btn-xs pull-right" id="btnExport" onclick="exportToExcel();"> <i class="fa fa-file-excel-o"></i> </a>

                                <table class="table table-striped table-bordered table-hover " id="headerTable">
                                    <thead class="header">
                                        <tr>
                                            <th><?php echo "Receipt Id" . $this->lang->line('receipt_id'); ?></th>
                                            <th><?php echo $this->lang->line('date'); ?></th>
                                            <!-- <th><?php //echo $this->lang->line('admission_no'); ?></th> -->
                                            <th><?php echo $this->lang->line('name'); ?></th>
                                            <th><?php echo $this->lang->line('class'); ?></th>
                                            <th><?php echo $this->lang->line('fee_type'); ?></th>
                                            <th><?php echo $this->lang->line('collect_by'); ?></th>
                                            <th><?php echo $this->lang->line('mode'); ?></th>
                                            <th><?php echo $this->lang->line('status'); ?></th>
                                            <th style="mso-number-format:'\@'" class="text text-right"><?php echo "Gross"; ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                            <th style="mso-number-format:'\@'" class="text text-right"><?php echo $this->lang->line('discount'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                            <th style="mso-number-format:'\@'" class="text text-right"><?php echo $this->lang->line('fine'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                            <th style="mso-number-format:'\@'" class="text text-right"><?php echo "Net " . $this->lang->line('total'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                            <th class="text-right"><?php echo $this->lang->line('action'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $count = 1;
                                        $grdamountTotal = 0;
                                        $grddiscountTotal = 0;
                                        $grdfineTotal = 0;
                                        $grdTotalTotal = 0;
                                        $canceltotal = 0;
                                        foreach ($results as $key => $value) {
                                            if ($value->fee_receipt_status == "Cancelled") {
                                                $color = "background-color:#ff0000b0;color: white;";
                                            }else {
                                                $color = "";
                                            }
                                        ?>
                                            <tr style = "<?php echo $color;?>">
                                                <td style="mso-number-format:'\@'" class="text-left-md payment_id"><?php echo $value->fee_receipt_id; ?></td>
                                                
                                                <td class="text-left-md"><?php echo $value->receipt_date;; ?></td>
                                               
                                                <!-- <td class="text-left-md"><?php //echo $value->admission_no; ?></td> -->
                                                
                                                <td class="text-left-md"><?php echo $value->firstname . " " . $value->lastname; ?></td>
                                                
                                                <td class="text-left-md"><?php echo $value->class . " " . $value->section ."-".$value->roll_no; ?></td>
                                                
                                                <td class="text-left-md"><?php echo "[" . $value->session . "]"; ?></td>
                                                
                                                <td class="text-left-md"><?php echo $value->created_by; ?></td>
                                                
                                                <td class="text-left-md"><?php echo $value->payment_mode; echo "<br>Note: ".$value->note;

                                                                            ?></td>
                                                
                                                <td class="text-left"><?php echo $value->fee_receipt_status;if ($value->fee_receipt_status == "Cancelled") { echo "<br>".$value->cancel_reason; } ?></td>
                                                <?php if ($value->fee_receipt_status == "Cancelled") {
                                                      $canceltotal += $value->gross_amount;
                                                      $netcanceltotal += $value->net_amt;
                                                }
                                                else
                                                {
                                                    if ($value->payment_mode == 'Cash') {
                                                        $cashtotal += $value->gross_amount;
                                                        $netcashtotal += $value->net_amt;
                                                        $netcashdisc +=$value->discount;
                                                    } elseif($value->payment_mode == 'gateway')
                                                    {
                                                        $gateway_total += $value->gross_amount;
                                                        $net_gateway_total += $value->net_amt;
                                                        $net_gateway_total_disc +=$value->discount;                                                        
                                                    } else {
                                                        $banktotal += $value->gross_amount;
                                                        $netbanktotal += $value->net_amt;
                                                        $netbankdisc +=$value->discount;
                                                    }                                                    
                                                    $grdamountTotal += $value->net_amt;
                                                    $grddiscountTotal += $value->discount;
                                                    $grdfineTotal += $value->fine;
                                                    $grdTotalTotal += $value->gross_amount;
                                                }
                                                ?>
                                                
                                                <td class="text-right"><?php echo $value->gross_amount; ?></td>
                                                <td class="text-right"><?php echo $value->discount; ?></td>
                                                <td class="text-right"><?php echo $value->fine; ?></td>
                                                <td class="text-right"><?php echo $value->net_amt; ?></td>
                                                <td class="text-right">
                                                    <?php if ($this->rbac->hasPrivilege('collect_fees', 'can_delete') && $value->fee_receipt_status == "Active") { ?>
                                                    <button class="btn btn-default btn-xs " id="revert" data-invoiceno="<?php echo $value->fee_receipt_id; ?>" data-main_invoice="<?php echo $value->fee_receipt_id; ?>" data-sub_invoice="<?php echo $value->fee_receipt_id; ?>" data-session_id="<?php echo $rec_session_id; ?>" data-toggle="modal" data-target="#confirm-delete" title="<?php echo $this->lang->line('revert'); ?>">
                                                        <i class="fa fa-undo"> </i>
                                                    </button>
                                                    
                                                    <button class="btn btn-xs btn-default" id="editbtn" data-session_id = "<?php echo $rec_session_id; ?>" data-id="<?php echo  $value->fee_receipt_id;   ?>" data-mode="<?php echo $value->payment_mode; ?>" data-session_id="<?php echo $rec_session_id; ?>"  data-note="<?php echo $value->note; ?>" data-toggle="modal" data-target="#edit_receipt"  title="<?php echo "Edit"; ?>">
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                <?php } if($value->fee_receipt_status != "Cancelled") { ?>
                                                <button class="btn btn-xs btn-changeoc octype_<?php echo $value->fee_receipt_id; ?>" id="change_octype" data-sessionid="<?php echo  $rec_session_id;   ?>"  data-id="<?php echo  $value->fee_receipt_id;   ?>" title="<?php echo "To Old Receipt"; ?>">
                                                        <i class="fa fa-superpowers" data-sessionid="<?php echo  $rec_session_id;   ?>"></i>
                                                </button> 
                                                <button class="btn btn-xs btn-default <?php if($value->receipt_type == 'general') { echo "printDocGeneral"; } else { echo "printDoc";} ?> " id="printbtn" data-sessionid="<?php echo  $rec_session_id;   ?>"  data-id="<?php echo  $value->fee_receipt_id;   ?>" title="<?php echo $this->lang->line('print'); ?>">
                                                        <i class="fa fa-print"></i>
                                                </button>                                                 
                                                <?php } ?>
                                                
                                            </td>

                                            </tr>
                                            <?php
                                            //$count++;
                                            //if ($subtotal) {
                                            ?>
                                            <!-- <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td style="font-weight:bold"><?php //echo $this->lang->line('sub') . " " . $this->lang->line('total') 
                                                                                    ?></td>
                                                    <td class="text text-right" style="font-weight:bold"><?php //echo array_sum($amountLabel); 
                                                                                                            ?></td>
                                                    <td class="text text-right" style="font-weight:bold"><?php //echo array_sum($discountLabel); 
                                                                                                            ?></td>
                                                    <td class="text text-right" style="font-weight:bold"><?php //echo array_sum($fineLabel); 
                                                                                                            ?></td>
                                                    <td class="text text-right " style="font-weight:bold"><?php //echo array_sum($TotalLabel); 
                                                                                                            ?></td>
                                                </tr> -->
                                        <?php
                                            //}


                                        }
                                        ?>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td style="font-weight:bold"><?php echo $this->lang->line('grand') . " " . $this->lang->line('total'); ?></td>
                                            <td class="text text-right" style="font-weight:bold"><?php //echo array_sum($grdamountLabel); 
                                                                                                    ?></td>
                                            
                                            <td class="text text-right " style="font-weight:bold"><?php echo $grdTotalTotal; ?></td>
                                            <td class="text text-right" style="font-weight:bold"><?php echo $grddiscountTotal; ?></td>
                                            <td class="text text-right " style="font-weight:bold"><?php echo $grdfineTotal; ?></td>
                                            <td class="text text-right" style="font-weight:bold"><?php echo $grdamountTotal; ?></td>

                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td>Cash Total</td>
                                            <td style="font-weight:bold"></td>
                                            <td class="text text-right" style="font-weight:bold"><?php //echo array_sum($grdamountLabel); 
                                                                                                    ?></td>
                                            <td class="text text-right " style="font-weight:bold"><?php echo $cashtotal; ?></td>
                                            <td class="text text-right" style="font-weight:bold"><?php echo $netcashdisc; ?></td>
                                            <td class="text text-right " style="font-weight:bold"></td>
                                            <td class="text text-right" style="font-weight:bold"><?php echo $netcashtotal; ?></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td>Gateway Total</td>
                                            <td style="font-weight:bold"></td>
                                            <td class="text text-right" style="font-weight:bold"><?php //echo array_sum($grdamountLabel); 
                                                                                                    ?></td>
                                            <td class="text text-right" style="font-weight:bold"><?php echo $gateway_total; ?></td>
                                            <td class="text text-right" style="font-weight:bold"><?php echo $net_gateway_total_disc; ?></td>
                                            <td class="text text-right " style="font-weight:bold"></td>
                                            <td class="text text-right " style="font-weight:bold"><?php echo $net_gateway_total; ?></td>
                                            <td></td>
                                        </tr>                                        
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td>Bank Total</td>
                                            <td style="font-weight:bold"></td>
                                            <td class="text text-right" style="font-weight:bold"><?php //echo array_sum($grdamountLabel); 
                                                                                                    ?></td>
                                            <td class="text text-right" style="font-weight:bold"><?php echo $banktotal; ?></td>
                                            <td class="text text-right" style="font-weight:bold"><?php echo $netbankdisc; ?></td>
                                            <td class="text text-right " style="font-weight:bold"></td>
                                            <td class="text text-right " style="font-weight:bold"><?php echo $netbanktotal; ?></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td>Cancelled Total</td>
                                            <td style="font-weight:bold"></td>
                                            <td class="text text-right" style="font-weight:bold"><?php //echo array_sum($grdamountLabel); 
                                                                                                    ?></td>
                                            <td class="text text-right" style="font-weight:bold"><?php echo $canceltotal; ?></td>
                                            <td class="text text-right" style="font-weight:bold"></td>
                                            <td class="text text-right " style="font-weight:bold"></td>
                                            <td class="text text-right " style="font-weight:bold"><?php echo $netcanceltotal; ?></td>
                                            <td></td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>
</div>
</section>
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
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-3 control-label"><?php echo $this->lang->line('reason'); ?></label>
                <div class="col-sm-8">
                    <textarea class="form-control" rows="3" id="reason" name = "reason" placeholder=""></textarea>
                </div>
            </div>             
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('cancel'); ?></button>
                <a class="btn btn-danger btn-ok"><?php echo $this->lang->line('revert'); ?></a>
            </div>
        </div>
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
                    <label for="inputPassword3" class="col-sm-3 control-label"> </label>
                    <label for="inputPassword3" class="col-sm-3 control-label"> </label>
                    <label for="inputPassword3" class="col-sm-3 control-label"> </label>
                    
                            <label class="radio-inline">
                                <input type="checkbox"  id="oc_type" name="oc_type" >Is Old Receipt
                            </label>
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
<iframe id="txtArea1" style="display:none"></iframe>

<script type="text/javascript">
    $(document).ready(function() {
        $(document).on('click', '.printDoc', function() {
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
        $(document).on('click', '.printDocGeneral', function() {
            var id = $(this).data('id');
            var recsessionid = $(this).data('sessionid');
            $.ajax({
                url: '<?php echo site_url("studentfee/printFeesGeneral") ?>',
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

<script>
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
    <?php
    if ($search_type == 'period') {
    ?>

        $(document).ready(function() {
            showdate('period');
        });

    <?php
    }
    ?>


    document.getElementById("print").style.display = "block";
    document.getElementById("btnExport").style.display = "block";
    document.getElementById("printhead").style.display = "none";

    function printDiv() {
        document.getElementById("print").style.display = "none";
        document.getElementById("btnExport").style.display = "none";
        document.getElementById("printhead").style.display = "block";
        var divElements = document.getElementById('transfee').innerHTML;
        var oldPage = document.body.innerHTML;
        document.body.innerHTML =
            "<html><head><title>Fee Collection Report</title></head><body><h3 style='text-align:center;'>Sree Narayana Guru Central School</h3>" +
            divElements + "</body>";
        window.print();
        document.body.innerHTML = oldPage;
        document.getElementById("printhead").style.display = "none";
        location.reload(true);
    }

    function fnExcelReport() {
        exportToExcel();


    }




    function exportToExcel() {
        var htmls = "";
        var uri = 'data:application/vnd.ms-excel;base64,';
        var template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>';
        var base64 = function(s) {
            return window.btoa(unescape(encodeURIComponent(s)))
        };

        var format = function(s, c) {
            return s.replace(/{(\w+)}/g, function(m, p) {
                return c[p];
            })
        };
        var tab_text = "<tr >";
        var textRange;
        var j = 0;
        var val = "";
        tab = document.getElementById('headerTable'); // id of table

        for (j = 0; j < tab.rows.length; j++) {

            tab_text = tab_text + tab.rows[j].innerHTML + "</tr>";
            //tab_text=tab_text+"</tr>";
        }

        var ctx = {
            worksheet: 'Worksheet',
            table: tab_text
        }


        var link = document.createElement("a");
        link.download = "studentfee_collection_report.xls";
        link.href = uri + base64(format(template, ctx));
        link.click();
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
            $('.invoice_no', this).text($(e.relatedTarget).data('invoiceno'));
            $('#main_invoice', this).val($(e.relatedTarget).data('main_invoice'));
            $('#sub_invoice', this).val($(e.relatedTarget).data('sub_invoice'));
            $('#del_session_id', this).val($(e.relatedTarget).data('session_id'));
        });
        $('#edit_receipt').on('show.bs.modal', function(e) {
            var mode= $(e.relatedTarget).data('mode');
            var note = $(e.relatedTarget).data('note');
            var invid = $(e.relatedTarget).data('id');
            var session_id = $(e.relatedTarget).data('session_id');

            $('#description').val(note);
            $('#edit_inv_id').val(invid);
            $('#pay_mode').val(mode);
            $('#session_id').val(session_id);
            $('.fee_mode').each(function(index,item){
                if($(item).val() == mode)
                {$(item).prop("checked", true);}
                else
                {$(item).prop("checked", false);}
            });
        });

        $('#confirm-discountdelete').on('show.bs.modal', function(e) {
            $('.discount_title', this).text("");
            $('#discount_id', this).val("");
            $('.discount_title', this).text($(e.relatedTarget).data('discounttitle'));
            $('#discount_id', this).val($(e.relatedTarget).data('discountid'));
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
        $(document).on('click', '#change_octype', function() {
            var session_id = $(this).data('sessionid');
            var sub_invoice = $(this).data('id');
            var oc_type = 'C';
            $.ajax({
                type: "post",
                url: '<?php echo site_url("studentfee/changeoctype") ?>',
                dataType: 'JSON',
                data: {
                    'sub_invoice': sub_invoice,
                    'oc_type': oc_type,
                    'session_id': session_id,
                },
                success: function(data) {
                    // alert('alert');
                    // $modalDiv.modal('hide').removeClass('modalloading');
                    $('.octype_'+ data.id).hide();
                    location.reload(true);
                    //$(this).hide();
                }
            });
        });

        $('#edit_receipt').on('click', '.btn-ok', function(e) {
            var $modalDiv = $(e.delegateTarget);
            var sub_invoice = $('#edit_inv_id').val();
            var note = $('#description').val();
            var payment_mode = $('#payment_mode_fee').val();
            var session_id = $('#session_id').val();
            $('.fee_mode').each(function(index,item){
                if($(item).is(":checked"))
                {
                    payment_mode=$(item).val();
                }
            });    
                   
            $modalDiv.addClass('modalloading');
            $.ajax({
                type: "post",
                url: '<?php echo site_url("studentfee/updateReceipt") ?>',
                dataType: 'JSON',
                data: {
                    'sub_invoice': sub_invoice,
                    'payment_mode': payment_mode,
                    'note': note,
                    'session_id': session_id,
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


        // $(document).on('click', '.btn-ok', function(e) {
        //     var $modalDiv = $(e.delegateTarget);
        //     var main_invoice = $('#main_invoice').val();
        //     var sub_invoice = $('#sub_invoice').val();

        //     $modalDiv.addClass('modalloading');
        //     $.ajax({
        //         type: "post",
        //         url: '<?php echo site_url("studentfee/deleteFee") ?>',
        //         dataType: 'JSON',
        //         data: {
        //             'main_invoice': main_invoice,
        //             'sub_invoice': sub_invoice
        //         },
        //         success: function(data) {
        //             $modalDiv.modal('hide').removeClass('modalloading');
        //             location.reload(true);
        //         }
        //     });

        // });
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
</script>