<style type="text/css">
    @media print {

        .no-print,
        .no-print * {
            display: none !important;
        }
    }
</style>
<div class="content-wrapper" style="min-height: 946px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-calendar-check-o"></i> <?php echo  $this->lang->line('list'); ?> <small> </small>
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">

                <div class="box box-info" id="attendencelist">
                    <div class="box-header with-border">
                        <div class="row">
                            <?php if ($this->session->flashdata('msg')) { ?>
                                <?php echo $this->session->flashdata('msg');
                                unset($_SESSION['msg']); ?>
                            <?php } ?>
                            <div class="box-tools pull-right">
                                <small class="pull-right"><a href="<?php echo base_url('admin/feemaster/addCheque'); ?>" class="btn btn-primary btn-sm checkbox-toggle pull-right " data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Processing"><?php echo "Add"; ?></a></small>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <h3 class="box-title"><i class="fa fa-users"></i><?php echo $this->lang->line('list'); ?></h3>
                            </div>
                            <div class="col-md-8 col-sm-8">
                                <div class="lateday">

                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="box-body table-responsive">


                        <div class="mailbox-controls">
                            <div class="pull-right">
                            </div>
                        </div>
                        <div class="download_label"><?php echo "List"; ?></div>
                        <table class="table table-striped table-bordered table-hover example xyz">
                            <thead>
                                <tr>
                                    <th><?php echo "Sr.no"; ?></th>
                                    <th><?php echo "Class"; ?></th>
                                    <th><?php echo "Student"; ?></th>
                                    <th><?php echo "Cheque No"; ?></th>
                                    <th><?php echo "Collection Date"; ?></th>
                                    <th><?php echo "Cheque Date"; ?></th>
                                    <th><?php echo "Deposit Date"; ?></th>
                                    <th><?php echo "Pass Date"; ?></th>
                                    <th><?php echo "Cheque Amount"; ?></th>
                                    <th><?php echo "Created By"; ?></th>
                                    <th><?php echo "Cheque Status"; ?></th>
                                    <th class="pull-right"><?php echo $this->lang->line('action'); ?></th>


                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                $i = 1;
                                foreach ($resultlist as $value) { ?>
                                    <tr>
                                        <td><?php echo $i; ?></td>
                                        <td><?php echo $value['class'] . " " . $value['section']; ?></td>
                                        <td><?php echo $value['firstname'] . " " . $value['lastname']; ?></td>
                                        <td><?php echo $value['chq_no']; ?></td>
                                        <td><?php echo date('d-m-Y', strtotime($value['created_at'])); ?></td>
                                        <td><?php echo date('d-m-Y', strtotime($value['chq_date'])); ?></td>
                                        <td><?php echo !empty($value['deposit_date']) && $value['deposit_date'] != "1970-01-01" ? date('d-m-Y', strtotime($value['deposit_date'])) : ""; ?></td>
                                        <td><?php echo !empty($value['chq_pass_date']) && $value['chq_pass_date'] != "1970-01-01" ? date('d-m-Y', strtotime($value['chq_pass_date'])) : ""; ?></td>
                                        <td><?php echo $value['chq_amt']; ?></td>
                                        <td><?php echo $value['created_by']; ?></td>
                                        <td><?php echo $value['chq_status']; ?></td>
                                        <td>
                                            <?php
                                            if ($this->rbac->hasPrivilege('cheque_in_word', 'can_edit') && $value['chq_status']=="deposit") {
                                            ?>
                                                <a data-placement="left" href="<?php echo base_url(); ?>admin/feemaster/addCheque/<?php echo $value['id'] ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                    <i class="fa fa-pencil"></i>
                                                </a>
                                            <?php } ?>
                                            <?php
                                            if ($this->rbac->hasPrivilege('cheque_in_word', 'can_delete')) {
                                            ?>
                                                <a data-placement="left" href="<?php echo base_url(); ?>admin/feemaster/delCheque/<?php echo $value['id'] ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                    <i class="fa fa-remove"></i>
                                                </a>
                                            <?php }
                                            if($value['chq_status']=='collected')
                                            {
                                             ?>
                                                <a data-placement="left" target="_blank" href="<?php echo base_url(); ?>studentfee/print_cheque_receipt/<?php echo $value['id'] ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo "Print Cheque Acknoledgement"; ?>" >
                                                    <i class="fa fa-print"></i>
                                                </a>                                             
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php $i++;
                                } ?>

                            </tbody>
                        </table>

                    </div>
                </div>

    </section>
</div>


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





<script type="text/javascript">
    $(document).on('click', '.printInv', function() {
        var student_id = $(this).data('student_id');
        var student_info_id = $(this).data('student_info_id');
        $.ajax({
            url: '<?php echo site_url("admin/certificate/print_tc_certificate") ?>',
            type: 'post',
            data: {
                'student_id': student_id,
                'student_info_id': student_info_id
            },
            success: function(response) {
                Popup(response);
            }
        });
    });
</script>