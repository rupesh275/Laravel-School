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
            <i class="fa fa-calendar-check-o"></i> <?php echo  "Cheque Deposit"; ?> <small> </small>
        </h1>
    </section>
    <section class="content">
    <?php $this->load->view('admin/feemaster/chq_menu');?>
        <form action="<?php echo site_url("admin/feemaster/chq_bounce") ?>" method="post" accept-charset="utf-8">
            <div class="row">
                <div class="col-md-12">
                    <div class="box removeboxmius">
                        <div class="box-header ptbnull"></div>
                        <div class="box-body row">
                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label>Bounce Date</label>
                                    <input type="text" name="date" value="<?php echo date('d-m-Y'); ?>" id="" class="form-control date">
                                    <span class="text-danger" id="error_date"></span>
                                </div>
                                <input type="checkbox" name="bounce_charge" value="1"  id="" checked > Bounce Charge
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <span class="text-danger" id="error_date"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">

                    <div class="box " id="">
                        <div class="box-header with-border">
                            <div class="row">
                                <?php if ($this->session->flashdata('msg')) { ?>
                                    <?php echo $this->session->flashdata('msg');
                                    unset($_SESSION['msg']); ?>
                                <?php } ?>
                                <div class="box-tools ">
                                </div>
                                <div class="col-md-4 col-sm-4">
                                    <h3 class="box-title"><i class="fa fa-users"></i><?php echo "Cheque Bounce"; ?></h3>
                                </div>
                                <div class="col-md-8 col-sm-8">
                                    <div class="lateday">

                                    </div>

                                </div>
                            </div>
                            <br>
                            <div class="col-md-3 col-sm-3">
                                <div class="form-group">

                                </div>

                            </div>
                        </div>
                        <div class="box-body table-responsive">


                            <div class="mailbox-controls">
                                <div class="pull-right">
                                </div>
                            </div>
                            <div class="download_label"><?php echo "List"; ?></div>
                            <table class="table table-striped table-bordered table-hover  xyz">
                                <thead>
                                    <tr>
                                        <th><?php echo ""; ?></th>
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
                                        <!-- <th class="pull-right"><?php echo $this->lang->line('action'); ?></th> -->


                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
                                    $i = 1;

                                    foreach ($resultlist as $value) { ?>
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="cheque_id[]" id="" value="<?php echo $value['id']; ?>">
                                                <!-- <input type="hidden" name="student_session_id[]"  value="<?php echo $value['student_session_id']; ?>"> -->
                                                <input type="hidden" name="session_id" id="session_id" value="<?php echo $value['session_id']; ?>">
                                            </td>
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
                                                <?php /*
                                            if ($this->rbac->hasPrivilege('reminder', 'can_edit')) {
                                            ?>
                                                <a data-placement="left" href="<?php echo base_url(); ?>admin/feemaster/addCheque/<?php echo $value['id'] ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                    <i class="fa fa-pencil"></i>
                                                </a>
                                            <?php } ?>
                                            <?php
                                            if ($this->rbac->hasPrivilege('reminder', 'can_delete')) {
                                            ?>
                                                <a data-placement="left" href="<?php echo base_url(); ?>admin/feemaster/delCheque/<?php echo $value['id'] ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                    <i class="fa fa-remove"></i>
                                                </a>
                                            <?php } */ ?>
                                            </td>
                                        </tr>
                                    <?php $i++;
                                    } ?>

                                </tbody>
                            </table>

                        </div>
                        <div class="box-footer clearfix">
                            <?php
                            if (!empty($resultlist)) {
                            ?>

                                <button type="submit" class="btn btn-sm btn-primary pull-right"><?php echo "Apply"; ?></button>
                            <?php
                            }
                            ?>
                        </div>
        </form>

    </section>
</div>