<?php $currency_symbol = $this->customlib->getSchoolCurrencyFormat(); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <section class="content-header">
        <h1>
            <i class="fa fa-money"></i> <?php echo $this->lang->line('fees_collection'); ?></h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <?php
            if ($this->rbac->hasPrivilege('fees_discount', 'can_add') || $this->rbac->hasPrivilege('fees_discount', 'can_edit')) {
                ?>
                <div class="col-md-4">
                    <!-- Horizontal Form -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $this->lang->line('edit_fees_discount'); ?></h3>
                        </div><!-- /.box-header -->
                        <form id="form1" action="<?php echo site_url('admin/feediscount/edit/' . $feediscount['id']) ?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8">
                            <div class="box-body">
                                <?php if ($this->session->flashdata('msg')) { ?>
                                    <?php echo $this->session->flashdata('msg') ?>
                                <?php } ?>

                                <?php echo $this->customlib->getCSRF(); ?>
                                <input id="id" name="id" placeholder="" type="hidden" class="form-control"  value="<?php echo set_value('id', $feediscount['id']); ?>" />
                                <input id="current_session" name="current_session" placeholder="" type="hidden" value="<?php echo $session_id; ?>" />

                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('name'); ?></label><small class="req">*</small>
                                    <input autofocus="" id="name" name="name" placeholder="" type="text" class="form-control"  value="<?php echo set_value('name', $feediscount['name']); ?>" />
                                    <span class="text-danger"><?php echo form_error('name'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('discount_code'); ?></label>
                                    <input id="code" name="code" type="text" class="form-control"  value="<?php echo set_value('code', $feediscount['code']); ?>" />
                                    <span class="text-danger"><?php echo form_error('code'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('amount'); ?></label>
                                    <input id="amount" name="amount" placeholder="" type="text" class="form-control"  value="<?php echo set_value('amount', $feediscount['amount']); ?>" />
                                    <span class="text-danger"><?php echo form_error('amount'); ?></span>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo "Percent"; ?></label>
                                    <input id="feepercent" min="1" max="100" name="feepercent" type="number" class="form-control" value="<?php echo set_value('feepercent'); ?><?php echo set_value('feepercent', $feediscount['feepercent']); ?>" />
                                    <span class="text-danger"><?php echo form_error('feepercent'); ?></span>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo "Apply To"; ?></label>
                                    <select name="fees_type" id="fees_type" class="form-control">
                                        <option value="m"<?php echo set_select('fees_type','m', (!empty($feediscount['fees_type'])) && $feediscount['fees_type'] == 'm' ? true : false); ?>>Main fees</option>
                                        <option value="o"<?php echo set_select('fees_type','o', (!empty($feediscount['fees_type'])) && $feediscount['fees_type'] == 'o' ? true : false); ?>>Other fees</option>
                                        <option value="b"<?php echo set_select('fees_type','b', (!empty($feediscount['fees_type'])) && $feediscount['fees_type'] == 'b' ? true : false); ?>>Both</option>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('feepercent'); ?></span>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('description'); ?></label>
                                    <textarea class="form-control" id="description" name="description" rows="3"><?php echo set_value('description'); ?><?php echo set_value('description', $feediscount['description']); ?></textarea>
                                    <span class="text-danger"></span>
                                </div>

                                <div class="form-group">
                                    <input type="checkbox" name="date_enabled" id="date_enabled" value="1" <?php if($feediscount['date_enabled']==1){ echo "checked";}?>>
                                    <label for="exampleInputEmail1"><?php echo "Date Enabled"; ?></label>
                                    <span class="text-danger"></span>
                                </div>
                                <?php 

                                if ($feediscount['date_enabled'] != 1) {
                                    $condition = 'style="display:none;"';
                                }else {
                                    $condition="";
                                }
                                ?>
                                <div class="form-group date_upt" <?php echo $condition?>>
                                    <label for="exampleInputEmail1"><?php echo "Date Upto"; ?></label>
                                    <input id="date_upto" name="date_upto" type="text" class="form-control date" value="<?php echo set_value('date_upto',date('d-m-Y',strtotime($feediscount['date_upto']))); ?>" />
                                    <span class="text-danger"></span>
                                </div>


                            </div><!-- /.box-body -->

                            <div class="box-footer">
                                <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                            </div>
                        </form>
                    </div>

                </div><!--/.col (right) -->
                <!-- left column -->
            <?php } ?>
            <div class="col-md-<?php
            if ($this->rbac->hasPrivilege('fees_discount', 'can_add') || $this->rbac->hasPrivilege('fees_discount', 'can_edit')) {
                echo "8";
            } else {
                echo "12";
            }
            ?>">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('fees_discount_list'); ?></h3>
                        <div class="box-tools pull-right">
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="mailbox-messages table-responsive">
                            <div class="download_label"><?php echo $this->lang->line('fees_discount_list'); ?></div>
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>

                                        <th><?php echo $this->lang->line('name'); ?>
                                        <th><?php echo $this->lang->line('discount_code'); ?>

                                        <th><?php echo $this->lang->line('amount'); ?>
                                        </th>
                                        <th><?php echo "Percent"; ?>
                                        </th>
                                        <th><?php echo "Fess Type"; ?>
                                        </th>

                                        <th class="text text-right"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (empty($feediscountList)) {
                                        ?>
                                        <tr>
                                            <td colspan="2" class="text-danger text-center"><?php echo $this->lang->line('no_record_found'); ?></td>

                                        </tr>
                                        <?php
                                    } else {
                                        foreach ($feediscountList as $feediscount) {
                                            ?>
                                            <tr>
                                                <td class="mailbox-name">
                                                    <a href="#" data-toggle="popover" class="detail_popover"><?php echo $feediscount['name'] ?></a>

                                                    <div class="fee_detail_popover" style="display: none">
                                                        <?php
                                                        if ($feediscount['description'] == "") {
                                                            ?>
                                                            <p class="text text-danger"><?php echo $this->lang->line('no_description'); ?></p>
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <p class="text text-info"><?php echo $feediscount['description']; ?></p>
                                                            <?php
                                                        }
                                                        ?>
                                                    </div>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo $feediscount['code'] ?>

                                                </td>

                                                <td class="mailbox-name">
                                                    <?php echo $feediscount['amount'] ?>
                                                </td>

                                                <td class="mailbox-name">
                                                <?php echo $feediscount['feepercent'] ?>
                                                </td>

                                                <td class="mailbox-name">
                                                <?php  if($feediscount['fees_type']== 'm'){ echo "Main Fees";}
                                                elseif ($feediscount['fees_type'] == 'o') {
                                                    echo "Other Fees";
                                                } elseif($feediscount['fees_type'] == 'b') {
                                                    echo "Both";
                                                }?>
                                                </td>


                                                <td class="mailbox-date pull-right white-space-nowrap">
                                                    <?php
                                                    if ($this->rbac->hasPrivilege('fees_discount_assign', 'can_view')) {
                                                        ?>
                                                        <a data-placement="left" href="<?php echo base_url(); ?>admin/feediscount/assign/<?php echo $feediscount['id'] ?>" 
                                                           class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('assign / view'); ?>">
                                                            <i class="fa fa-tag"></i>
                                                        </a>
                                                        <?php
                                                    }
                                                    if ($this->rbac->hasPrivilege('fees_discount', 'can_edit')) {
                                                        ?>

                                                        <a data-placement="left" href="<?php echo base_url(); ?>admin/feediscount/edit/<?php echo $feediscount['id'] ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                            <i class="fa fa-pencil"></i>
                                                        </a>
                                                        <?php
                                                    }
                                                    if ($this->rbac->hasPrivilege('fees_type', 'can_delete')) {
                                                        ?>
                                                        <a data-placement="left" href="<?php echo base_url(); ?>admin/feediscount/delete/<?php echo $feediscount['id'] ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                            <i class="fa fa-remove"></i>
                                                        </a>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>

                                </tbody>
                            </table><!-- /.table -->


                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->

                </div>
            </div><!--/.col (left) -->
            <!-- right column -->

        </div>
        <div class="row">
            <!-- left column -->

            <!-- right column -->
            <div class="col-md-12">

            </div><!--/.col (right) -->
        </div>   <!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<script type="text/javascript">
    $(document).ready(function () {


        $("#btnreset").click(function () {
            $("#form1")[0].reset();
        });

    });
</script>
<script>
    $(document).ready(function () {
        $('.detail_popover').popover({
            placement: 'right',
            trigger: 'hover',
            container: 'body',
            html: true,
            content: function () {
                return $(this).closest('td').find('.fee_detail_popover').html();
            }
        });
        $('#date_enabled').click(function() {
            $('.date_upt').toggle("slide");
        });
    });
</script>