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
           
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('fees_discount_list')." Approval"; ?></h3>
                        <div class="box-tools pull-right">
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="mailbox-messages table-responsive">
                            <div class="download_label"><?php echo $this->lang->line('fees_discount_list')." Approval"; ?></div>
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>

                                        <th><?php echo $this->lang->line('name'); ?>
                                        <th><?php echo $this->lang->line('discount_code'); ?>

                                        <th><?php echo $this->lang->line('amount'); ?>
                                        </th>

                                        <th class="text text-right"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($feediscountList as $feediscount) {
                                        ?>
                                        <tr>
                                            <td class="mailbox-name">
                                                <a href="#" data-toggle="popover" class="detail_popover"><?php echo $feediscount['name']; ?></a>
                                            </td>
                                            <td class="mailbox-name">
                                                <?php echo $feediscount['code']; ?>

                                            </td>

                                            <td class="mailbox-name">
                                                <?php echo $feediscount['amount']; ?>
                                            </td>


                                            <td class="mailbox-date pull-right white-space-nowrap">
                                                <?php
                                                if ($this->rbac->hasPrivilege('fees_discount_assign', 'can_view')) {
                                                    ?>
                                                    <a data-placement="left" href="<?php echo base_url(); ?>admin/feediscount/approve/<?php echo $feediscount['id']; ?>" 
                                                       class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $feediscount['name']; ?>">
                                                        <i class="fa fa-tag"></i>
                                                    </a>
                                                    <?php
                                                }
                                               ?>
                                            </td>
                                        </tr>
                                        <?php
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
    });
</script>