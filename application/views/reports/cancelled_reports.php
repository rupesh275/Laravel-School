<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
$cashtotal = 0;
$banktotal = 0;
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
                    <form role="form" action="<?php echo site_url('report/cancelled_reports') ?>" method="post" class="">
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
                            <!-- <div class="col-sm-3 col-lg-3 col-md-3">
                                <div class="form-group">
                                    <label><?php //echo $this->lang->line('collect') . " " . $this->lang->line('by'); ?></label>
                                    <select class="form-control" name="collect_by">
                                        <option value=""><?php //echo $this->lang->line('select') ?></option>
                                        <?php
                                        //foreach ($collect_by as $key => $value) {
                                        ?>
                                            <option value="<?php //echo $value ?>" <?php
                                                                                    
                                                                                    ?>><?php //echo $value ?></option>
                                        <?php //} ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('collect_by'); ?></span>
                                </div>
                            </div> -->
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
                                <h3 class="box-title titlefix"><i class="fa fa-money"></i> <?php ?> <?php echo  "Cancelled Receipt " . $this->lang->line('report'); ?> From <?php echo date('d/m/Y', strtotime($start_date)) . " To " . date('d/m/y', strtotime($end_date)); ?></h3>
                            </div>
                            <div class="box-body table-responsive" id="transfee">
                                <div id="printhead">
                                    <center>
                                        <h5><?php ?> <?php echo  "Cancelled Receipt " . $this->lang->line('report') . "<br>";
                                                        $this->customlib->get_postmessage();
                                                        ?></h5>
                                    </center>
                                </div>
                                <div class="download_label"><?php ?> <?php echo  "Cancelled Receipt " . $this->lang->line('report') . "<br>";
                                                                        $this->customlib->get_postmessage();
                                                                        ?></div>

                                <a class="btn btn-default btn-xs pull-right" id="print" onclick="printDiv()"><i class="fa fa-print"></i></a>
                                <a class="btn btn-default btn-xs pull-right" id="btnExport" onclick="exportToExcel();"> <i class="fa fa-file-excel-o"></i> </a>

                                <table class="table table-striped table-bordered table-hover " id="headerTable">
                                    <thead class="header">
                                        <tr>
                                            <th><?php echo "Receipt Id" . $this->lang->line('receipt_id'); ?></th>
                                            <th><?php echo $this->lang->line('date'); ?></th>
                                            <th><?php echo $this->lang->line('admission_no'); ?></th>
                                            <th><?php echo $this->lang->line('name'); ?></th>
                                            <th><?php echo $this->lang->line('class'); ?></th>
                                            <th><?php echo $this->lang->line('fee_type'); ?></th>
                                            <!-- <th><?php //echo $this->lang->line('collect_by'); ?></th> -->
                                            <!-- <th><?php //echo $this->lang->line('mode'); ?></th> -->
                                            <th style="mso-number-format:'\@'" class="text text-right"><?php echo $this->lang->line('amount'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                            <th class="text text-right"><?php echo $this->lang->line('mode'); ?></th>
                                            <!-- <th style="mso-number-format:'\@'" class="text text-right"><?php echo $this->lang->line('discount'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                            <th style="mso-number-format:'\@'" class="text text-right"><?php echo $this->lang->line('fine'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                            <th style="mso-number-format:'\@'" class="text text-right"><?php echo "Gross " . $this->lang->line('total'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th> -->
                                            <th class="text text-right"><?php echo "Cancelled Date"; ?></th>
                                            <th class="text text-right"><?php echo "Cancelled Reason"; ?></th>
                                            <th class="text text-right"><?php echo "Cancelled By"; ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php

                                        $count = 1;
                                        $grdamountTotal = 0;
                                        $grddiscountTotal = 0;
                                        $grdfineTotal = 0;
                                        $grdTotalTotal = 0;

                                        
                                        foreach ($results as $key => $value) {
                                            
                                            // echo "<pre>";
                                            // print_r ($value);
                                            // echo "</pre>";
                                            

                                        ?>
                                            <tr>
                                                <td style="mso-number-format:'\@'" class="text-left-md payment_id"><?php echo $value->fee_receipt_id; ?></td>
                                                
                                                <td class="text-left-md"><?php echo $value->receipt_date;; ?></td>
                                               
                                                <td class="text-left-md"><?php echo $value->admission_no; ?></td>
                                                
                                                <td class="text-left-md"><?php echo $value->firstname . " " . $value->lastname; ?></td>
                                                
                                                <td class="text-left-md"><?php echo $value->class . " " . $value->section; ?></td>
                                                
                                                <td class="text-left-md"><?php echo "[" . $value->session . "]"; ?></td>
                                                
                                                <!-- <td class="text-left-md"><?php //echo $value->created_by; ?></td> -->
                                                
                                                <!-- <td class="text-left-md"><?php echo $value->payment_mode;
                                                                            if ($value->payment_mode == 'Cash') {
                                                                                $cashtotal += $value->net_amt;
                                                                            } else {
                                                                                $banktotal += $value->net_amt;
                                                                            }
                                                                            ?></td> -->
                                                
                                                <td class="text-right"><?php echo $value->net_amt; ?></td>
                                                <td class="text-right"><?php echo $value->payment_mode; ?></td>
                                                
                                                <!-- <td class="text-right"><?php //echo $value->discount; ?></td> -->
                                                <td class="text-right"><?php echo !empty($value->cancelled_at) ? date('d-m-Y',strtotime($value->cancelled_at)) :""; ?></td>
                                                
                                                <td class="text-right"><?php echo $value->cancel_reason; ?></td>
                                                
                                                <td class="text-right"><?php echo $value->canceled_by; ?></td>

                                            </tr>
                                        
                                        <?php
                                            $grdamountTotal += $value->net_amt;
                                            $grddiscountTotal += $value->discount;
                                            $grdfineTotal += $value->fine;
                                            $grdTotalTotal += $value->gross_amount;
                                        }
                                        ?>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <!-- <td></td> -->
                                            <!-- <td></td> -->
                                            <td style="font-weight:bold"><?php echo $this->lang->line('grand') . " " . $this->lang->line('total'); ?></td>
                                            <td class="text text-right" style="font-weight:bold"><?php //echo array_sum($grdamountLabel); 
                                                                                                    ?></td>
                                            <td class="text text-right" style="font-weight:bold"><?php echo $grdamountTotal; ?></td>
                                            <td class="text text-right" style="font-weight:bold"><?php //echo $grddiscountTotal; ?></td>
                                            <td class="text text-right " style="font-weight:bold"><?php //echo $grdfineTotal; ?></td>
                                            <td class="text text-right " style="font-weight:bold"><?php //echo $grdTotalTotal; ?></td>
                                            <td class="text text-right " style="font-weight:bold"><?php //echo $grdTotalTotal; ?></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <!-- <td></td> -->
                                            <!-- <td></td> -->
                                            <td>Cash Total</td>
                                            <td style="font-weight:bold"></td>
                                            <td class="text text-right" style="font-weight:bold"><?php //echo array_sum($grdamountLabel); 
                                                                                                    ?></td>
                                            <td class="text text-right" style="font-weight:bold"><?php echo $cashtotal; ?></td>
                                            <td class="text text-right" style="font-weight:bold"></td>
                                            <td class="text text-right " style="font-weight:bold"></td>
                                            <td class="text text-right " style="font-weight:bold"></td>
                                            <td class="text text-right " style="font-weight:bold"></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <!-- <td></td> -->
                                            <!-- <td></td> -->
                                            <td>Bank Total</td>
                                            <td style="font-weight:bold"></td>
                                            <td class="text text-right" style="font-weight:bold"><?php //echo array_sum($grdamountLabel); 
                                                                                                    ?></td>
                                            <td class="text text-right" style="font-weight:bold"><?php echo $banktotal; ?></td>
                                            <td class="text text-right" style="font-weight:bold"></td>
                                            <td class="text text-right " style="font-weight:bold"></td>
                                            <td class="text text-right " style="font-weight:bold"></td>
                                            <td class="text text-right " style="font-weight:bold"></td>
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
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('cancel'); ?></button>
                <a class="btn btn-danger btn-ok"><?php echo $this->lang->line('revert'); ?></a>
            </div>
        </div>
    </div>
</div>
<iframe id="txtArea1" style="display:none"></iframe>

<script type="text/javascript">
    $(document).ready(function() {
        $(document).on('click', '.printDoc', function() {
            var id = $(this).data('id');
            $.ajax({
                url: '<?php echo site_url("studentfee/printFeesByName_previous_id") ?>',
                type: 'post',
                data: {
                    'id': id,
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
        link.download = "cancelled_receipt_report.xls";
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

            $modalDiv.addClass('modalloading');
            $.ajax({
                type: "post",
                url: '<?php echo site_url("studentfee/deleteFee_session") ?>',
                dataType: 'JSON',
                data: {
                    'main_invoice': main_invoice,
                    'sub_invoice': sub_invoice,
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