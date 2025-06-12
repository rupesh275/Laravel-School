<style type="text/css">

</style>

<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1><i class="fa fa-sitemap"></i> <?php echo $this->lang->line('human_resource'); ?>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                        <div class="box-tools pull-right">
                            <small class="pull-right"></small>
                        </div>
                    </div>
                    <?php
                    // echo "<pre>";
                    // print_r ($update);
                    // echo "</pre>";
                    ?>
                    <div class="box-body">
                        <?php if ($this->session->flashdata('msg')) { ?> <?php echo $this->session->flashdata('msg') ?> <?php } ?>
                        <div class="row">
                            <form role="form" action="<?php echo site_url('admin/payroll/salary_cheque_detail') ?>" method="post" class="">
                                <?php //echo $this->customlib->getCSRF(); 
                                ?>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('month'); ?><small class="req"> *</small></label>
                                        <select name="month" class="form-control" id="month">
                                            <option value=""><?php
                                                                echo $this->lang->line(
                                                                    'select'
                                                                )
                                                                ?></option>
                                            <?php foreach ($monthlist as $monthkey => $monthvalue) { ?>
                                                <option <?php echo set_select('month', $month, (!empty($update['month']) && $update['month'] == $monthvalue ? TRUE : FALSE)) ?> value="<?php echo $monthvalue ?>"><?php echo $monthvalue; ?></option>
                                            <?php } ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('month'); ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('year'); ?><small class="req"> *</small></label>
                                        <select name="year" class="form-control" id="year">
                                            <option value=""><?php
                                                                echo $this->lang->line(
                                                                    'select'
                                                                )
                                                                ?></option>
                                            <?php foreach ($yearlist as $yearkey => $yearvalue) { ?>
                                                <option <?php echo set_select('year', $year, (!empty($update['year']) && $update['year'] == $yearvalue["year"] ? TRUE : FALSE)) ?> value="<?php echo $yearvalue["year"]; ?>"><?php echo $yearvalue["year"]; ?></option>
                                            <?php } ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('year'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Letter Date</label><small class="req"> *</small>
                                        <input type="text" name="letter_date" id="letter_date" value="<?php echo set_value('letter_date', !empty($update['letter_date']) ? date('d-m-Y', strtotime($update['letter_date'])) : date('d-m-Y')) ?> " class="form-control date">
                                        <span class="text-danger"><?php echo form_error('letter_date'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Payment Date</label><small class="req"> *</small>
                                        <input type="text" name="payment_date" id="payment_date" value="<?php echo set_value('payment_date', !empty($update['payment_date']) ? date('d-m-Y', strtotime($update['payment_date'])) : date('d-m-Y')) ?> " class="form-control date">
                                        <span class="text-danger"><?php echo form_error('payment_date'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Type </label><small class="req"> *</small>
                                        <select name="type" id="type" class="form-control">
                                            <option value=""><?php echo $this->lang->line("select"); ?></option>
                                            <option value="SALARY" <?php echo set_select('type', $type, (!empty($update['type']) && $update['type'] == "SALARY" ? TRUE : FALSE)) ?>>SALARY</option>
                                            <option value="PF" <?php echo set_select('type', $type, (!empty($update['type']) && $update['type'] == "PF" ? TRUE : FALSE)) ?>>PF</option>
                                            <option value="PT" <?php echo set_select('type', $type, (!empty($update['type']) && $update['type'] == "PT" ? TRUE : FALSE)) ?>>PT</option>
                                            <option value="DailyWages" <?php echo set_select('type', $type, (!empty($update['type']) && $update['type'] == "DailyWages" ? TRUE : FALSE)) ?>>DailyWages</option>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('type'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Bank </label><small class="req"> *</small>
                                        <select name="chq_bank" class="form-control">
                                            <option value=""><?php echo $this->lang->line("select"); ?></option>
                                            <?php foreach ($banklist as  $bank) {
                                            ?>
                                                <option value="<?php echo $bank['id']; ?>" <?php echo set_select('chq_bank', $bank['id'], (!empty($update['chq_bank']) && $update['chq_bank'] == $bank['id'] ? TRUE : FALSE)) ?>><?php echo $bank['bank_head']; ?></option>
                                            <?php
                                            } ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('chq_bank'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Cheque No</label><small class="req"> *</small>
                                        <input type="text" name="chq_no" id="chq_no" value="<?php echo set_value('chq_no', !empty($update['chq_no']) ? $update['chq_no'] : ""); ?>" class="form-control">
                                        <span class="text-danger"><?php echo form_error('chq_no'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Cheque Date</label><small class="req"> *</small>
                                        <input type="text" name="chq_date" id="chq_date" value="<?php echo set_value('chq_date', !empty($update['chq_date']) ? date('d-m-Y', strtotime($update['chq_date'])) : date('d-m-Y')) ?> " class="form-control date">
                                        <span class="text-danger"><?php echo form_error('chq_date'); ?></span>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Amount</label><small class="req"> *</small>
                                        <input type="text" name="amount" value="<?php echo set_value('amount', !empty($update['amount']) ? $update['amount'] : ""); ?>" id="amount" class="form-control">
                                        <span class="text-danger"><?php echo form_error('amount'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Remarks</label><small class="req"> </small>
                                        <textarea name="remarks" id="remarks" class="form-control"><?php echo set_value('remarks', !empty($update['remarks']) ? $update['remarks'] : ""); ?></textarea>
                                        <span class="text-danger"><?php echo form_error('remarks'); ?></span>
                                    </div>
                                </div>

                                <div class="col-md-12 text-center">
                                    <input type="hidden" name="id" id="id" value="<?php echo !empty($update['id']) ? $update['id'] : "" ?>">
                                    <button type="submit" class="btn btn-info" autocomplete="off">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="box box-primary" id="sublist">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo "Salary Cheque List"; ?></h3>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label"><?php echo "Salary Cheque List"; ?></div>
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th><?php echo "Month-Year"; ?></th>
                                        <th><?php echo "Letter Date"; ?></th>
                                        <th><?php echo "Payment Date"; ?></th>
                                        <th><?php echo "Type"; ?></th>
                                        <th><?php echo "Cheque No"; ?></th>
                                        <th><?php echo "Amount"; ?></th>
                                        <th class="text-right no-print"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $count = 1;
                                    if (!empty($stafflist)) {
                                        foreach ($stafflist as $staffRow) {
                                    ?>
                                            <tr>
                                                <td class="mailbox-name"> <?php echo $staffRow['month']."-".$staffRow['year'] ?></td>
                                                <td class="mailbox-name"><?php echo date('d-m-Y', strtotime($staffRow['letter_date'])) ?></td>
                                                <td class="mailbox-name"><?php echo date('d-m-Y', strtotime($staffRow['payment_date'])) ?></td>
                                                <td class="mailbox-name"><?php echo $staffRow['type'] ?></td>
                                                <td class="mailbox-name"><?php echo $staffRow['chq_no'] ?></td>
                                                <td class="mailbox-name"><?php echo $staffRow['amount'] ?></td>
                                                <td class="mailbox-date pull-right no-print">
                                                    <a data-placement="left" href="javascript:void(0);" class="btn btn-default btn-xs print_detail" id="<?php echo $staffRow['id'] ?>" data-toggle="tooltip" title="<?php echo $this->lang->line('print'); ?>">
                                                        <i class="fa fa-print"></i>
                                                    </a>
                                                    <?php
                                                    if ($this->rbac->hasPrivilege('salary_cheque_detail', 'can_edit')) {
                                                    ?>
                                                        <a data-placement="left" href="<?php echo base_url(); ?>admin/payroll/salary_cheque_detail/<?php echo $staffRow['id'] ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                            <i class="fa fa-pencil"></i>
                                                        </a>
                                                    <?php
                                                    }
                                                    if ($this->rbac->hasPrivilege('salary_cheque_detail', 'can_delete')) {
                                                    ?>
                                                        <a data-placement="left" href="<?php echo base_url(); ?>admin/payroll/delete_cheque/<?php echo $staffRow['id'] ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                            <i class="fa fa-remove"></i>
                                                        </a>
                                                    <?php } ?>

                                                </td>
                                            </tr>
                                    <?php
                                        }
                                    }
                                    $count++;
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script type="text/javascript">
    $(document).ready(function(e) {

        $('.print_detail').on("click", function() {
            var id = $(this).attr('id');
            var base_url = '<?php echo base_url() ?>';

            $.ajax({
                type: "POST",
                url: base_url + "admin/payroll/print_cheque",
                data: {
                    'id': id
                },
                dataType: "json",
                success: function(data) {
                    Popup(data.response);
                }
            });
        });

        $('#type').on("change", function() {
            var type = $(this).val();
            var base_url = '<?php echo base_url() ?>';
            var id = $('#id').val();
            var month = $('#month').val();
            var year = $('#year').val();

            if (id == '' && month != '' && year != '') {
                $.ajax({
                    type: "POST",
                    url: base_url + "admin/payroll/get_amount",
                    data: {
                        'type': type,
                        'month': month,
                        'year': year,
                    },
                    dataType: "json",
                    beforeSend: function() {
                        $('#amount').addClass('dropdownloading');
                    },
                    success: function(data) {
                        $('#amount').val(data.amount);
                    },
                    complete: function() {
                        $('#amount').removeClass('dropdownloading');
                    }
                });
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
        frameDoc.document.write('<html>');
        frameDoc.document.write('<head>');
        frameDoc.document.write('<title></title>');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/bootstrap/css/bootstrap.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/font-awesome.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/ionicons.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/AdminLTE.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/skins/_all-skins.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/iCheck/flat/blue.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/morris/morris.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/jvectormap/jquery-jvectormap-1.2.2.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/datepicker/datepicker3.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/daterangepicker/daterangepicker-bs3.css">');
        frameDoc.document.write('</head>');
        frameDoc.document.write('<body>');
        frameDoc.document.write(data);
        frameDoc.document.write('</body>');
        frameDoc.document.write('</html>');
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
</script>