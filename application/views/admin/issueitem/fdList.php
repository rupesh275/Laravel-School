<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-object-group"></i> <?php echo $this->lang->line('inventory'); ?>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <!-- Horizontal Form -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo "FD Management"; ?></h3>
                        <div class="box-tools pull-right">
                            <?php if ($this->rbac->hasPrivilege('fd_trn', 'can_add')) {
                            ?>
                                <a href="<?php echo site_url('admin/issueitem/createFD') ?>" type="button" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> <?php echo "Add FD"; ?></a>
                            <?php }
                            ?>

                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="mailbox-messages table-responsive">
                            <table class="table table-striped table-bordered table-hover example item-listss" data-export-title="<?php echo $this->lang->line('issue_item'); ?>">
                                <thead>
                                    <tr>
                                        <th><?php echo "FD Name"; ?></th>
                                        <th><?php echo "FD Bank"; ?></th>
                                        <th><?php echo "FD Branch"; ?></th>
                                        <th><?php echo "FD Amount"; ?></th>
                                        <th><?php echo "FD Interest Rate"; ?></th>
                                        <th><?php echo $this->lang->line('status'); ?></th>
                                        <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $count = 1;
                                    if (!empty($resultlist)) {
                                        foreach ($resultlist as  $row) {
                                    ?>
                                            <tr>
                                                <td class="mailbox-name"><?php echo $row['fdt_name'];?></td>
                                                <td class="mailbox-name"><?php echo $row['fdt_bank'];?></td>
                                                <td class="mailbox-name"><?php echo $row['fdt_branch'];?></td>
                                                <td class="mailbox-name"><?php echo $row['fdt_amount'];?></td>
                                                <td class="mailbox-name"><?php echo $row['fdt_intrate'];?></td>
                                                <td class="mailbox-name">
                                                <?php
                                                if ($row['fdt_status'] == 1) {
                                                    echo "Active";
                                                }else{
                                                 echo "Inactive";
                                                }
                                                ?></td>
                                                <td class="mailbox-date pull-right no-print">
                                                <?php
                                                    if ($this->rbac->hasPrivilege('fd_trn', 'can_edit')) {
                                                    ?>
                                                        <a data-placement="left" href="<?php echo base_url(); ?>admin/issueitem/createFD/<?php echo $row['id'] ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                            <i class="fa fa-pencil"></i>
                                                        </a>
                                                    <?php
                                                    }
                                                    if ($this->rbac->hasPrivilege('fd_trn', 'can_delete')) {
                                                    ?>
                                                        <a data-placement="left" href="<?php echo base_url(); ?>admin/issueitem/deleteFD/<?php echo $row['id'] ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
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
                            </table><!-- /.table -->
                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->
                </div>
            </div><!--/.col (right) -->
        </div>

    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<script>
    $(document).ready(function() {
        $('.detail_popover').popover({
            placement: 'right',
            trigger: 'hover',
            container: 'body',
            html: true,
            content: function() {
                return $(this).closest('td').find('.fee_detail_popover').html();
            }
        });
    });
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $('#confirm-delete').on('show.bs.modal', function(e) {
            $('#item_issue_id').val("");
            $('.debug-url').html('');
            $('#modal_item_quantity,#modal_item,#modal_item_cat').text("");
            var item_issue_id = $(e.relatedTarget).data('item');
            var item_category = $(e.relatedTarget).data('category');
            var quantity = $(e.relatedTarget).data('quantity');
            var item_name = $(e.relatedTarget).data('item_name');
            $('#item_issue_id').val(item_issue_id);
            $('#modal_item_cat').text(item_category);
            $('#modal_item').text(item_name);
            $('#modal_item_quantity').text(quantity);

        });
        $("#confirm-delete").modal({
            backdrop: false,
            show: false

        });

    });



    var base_url = '<?php echo base_url() ?>';

    $(document).on('change', '#item_category_id', function(e) {
        $('#item_id').html("");
        var item_category_id = $(this).val();
        populateItem(0, item_category_id);
    });


    $(document).on('click', '.btn-ok', function() {
        var $this = $('.btn-ok');
        $this.button('loading');
        var item_issue_id = $('#item_issue_id').val();
        $.ajax({
            url: "<?php echo site_url('admin/issueitem/returnItem') ?>",
            type: "POST",
            data: {
                'item_issue_id': item_issue_id
            },
            dataType: 'Json',
            success: function(data, textStatus, jqXHR) {
                if (data.status == "fail") {

                    errorMsg(data.message);
                } else {
                    successMsg(data.message);
                    //  $("span[data-item='" + item_issue_id + "']").removeClass("label-danger").addClass("label-success").text("Returned");

                    $("#confirm-delete").modal('hide');
                    location.reload();
                }

                $this.button('reset');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                $this.button('reset');
            }
        });

    });
</script>
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">

                <h4 class="modal-title" id="myModalLabel"><?php echo $this->lang->line('confirm_return'); ?></h4>
            </div>

            <div class="modal-body">
                <input type="hidden" id="item_issue_id" name="item_issue_id" value="">
                <p>Are you sure to return this item !</p>

                <ul class="list2">
                    <li><?php echo $this->lang->line('item'); ?><span id="modal_item"></span></li>
                    <li><?php echo $this->lang->line('item_category'); ?><span id="modal_item_cat"></span></li>
                    <li><?php echo $this->lang->line('quantity'); ?><span id="modal_item_quantity"></span></li>
                </ul>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('cancel'); ?></button>
                <a class="btn cfees btn-ok" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Please Wait.."><?php echo $this->lang->line('return'); ?></a>
            </div>
        </div>
    </div>
</div>

<script>
    (function($) {
        'use strict';
        $(document).ready(function() {
            // initDatatable('item-list','admin/issueitem/getitemlist',[],[],100);
        });
    }(jQuery))
</script>