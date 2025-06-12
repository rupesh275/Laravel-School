<style type="text/css">
    @media print {

        .no-print,
        .no-print * {
            display: none !important;
        }
    }
</style>

<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1>
            <i class="fa fa-mortar-board"></i> <?php echo $this->lang->line('academics'); ?>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <?php
            if ($this->rbac->hasPrivilege('media_master', 'can_add')) {
            ?>
                <div class="col-md-4">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo "Media Master"; ?></h3>
                        </div>
                        <form action="<?php echo site_url("admin/lessonplan/media_master") ?>" id="employeeform" name="employeeform" method="post" accept-charset="utf-8">
                            <div class="box-body">
                                <?php if ($this->session->flashdata('msg')) { ?>
                                    <?php echo $this->session->flashdata('msg');
                                    unset($_SESSION['msg']); ?>
                                <?php } ?>
                                <?php echo $this->customlib->getCSRF(); ?>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo "Media Name"; ?></label> <small class="req"> *</small>
                                    <input autofocus="" id="media_name" name="media_name" placeholder="" type="text" class="form-control" value="<?php echo set_value('media_name', isset($update['media_name']) ? $update['media_name'] : ""); ?>" />
                                    <span class="text-danger"><?php echo form_error('media_name'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo "Input"; ?></label>
                                    <select name="input_type" id="input_type" class="form-control">
                                        <option value="1" <?php echo set_select('input_type', 1, isset($update) && $update['input_type'] == 1 ? true : false); ?>>File-input</option>
                                        <option value="2" <?php echo set_select('input_type', 2, isset($update) && $update['input_type'] == 2  ? true : false); ?>>Embed</option>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('input_type'); ?></span>
                                </div>
                                <div class="form-group embededcol" style="display: none;">
                                    <label for="exampleInputEmail1"><?php echo "I-Frame"; ?></label>
                                    <textarea name="type_detail" id="type_detail" class="form-control" cols="30" rows="10"><?php echo set_value('type_detail', isset($update['type_detail']) ? $update['type_detail'] : ""); ?></textarea>
                                    <span class="text-danger"><?php echo form_error('type_detail'); ?></span>
                                </div>

                            </div>
                            <div class="box-footer">
                                <input type="hidden" name="id" value="<?php if (isset($update)) {
                                                                            echo $update['id'];
                                                                        } ?>">
                                <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php } ?>
            <div class="col-md-<?php
                                if ($this->rbac->hasPrivilege('media_master', 'can_add') || $this->rbac->hasPrivilege('media_master', 'can_edit')) {
                                    echo "8";
                                } else {
                                    echo "12";
                                }
                                ?>">
                <div class="box box-primary" id="sublist">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo "Media List"; ?></h3>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label"><?php echo "Media List"; ?></div>
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th><?php echo "Media Name"; ?></th>
                                        <th><?php echo "Input "; ?></th>
                                        <th class="text-right no-print"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $count = 1;
                                    foreach ($medialist as $mediaRow) {
                                    ?>
                                        <tr>
                                            <td class="mailbox-name"> <?php echo $mediaRow['media_name'] ?></td>
                                            <td class="mailbox-name"><?php echo $mediaRow['input_type'] == 1 ? 'File-input' : 'Embed' ?></td>
                                            <td class="mailbox-date pull-right no-print">
                                                <?php
                                                if ($this->rbac->hasPrivilege('media_master', 'can_edit')) {
                                                ?>
                                                    <a data-placement="left" href="<?php echo base_url(); ?>admin/lessonplan/media_master/<?php echo $mediaRow['id'] ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                <?php
                                                }
                                                if ($this->rbac->hasPrivilege('media_master', 'can_delete')) {
                                                ?>
                                                    <a data-placement="left" href="<?php echo base_url(); ?>admin/lessonplan/delete_media_master/<?php echo $mediaRow['id'] ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                        <i class="fa fa-remove"></i>
                                                    </a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                    <?php
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
    $(document).ready(function() {
        $("#btnreset").click(function() {
            $("#form1")[0].reset();
        });
    });
</script>

<script type="text/javascript">
    var base_url = '<?php echo base_url() ?>';

    function printDiv(elem) {
        Popup(jQuery(elem).html());
    }

    function Popup(data) {
        var mywindow = window.open('', 'my div', 'height=400,width=600');
        mywindow.document.write('<html><head><title></title>');
        mywindow.document.write('<link rel="stylesheet" href="' + base_url + 'backend/bootstrap/css/bootstrap.min.css">');
        mywindow.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/font-awesome.min.css">');
        mywindow.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/ionicons.min.css">');
        mywindow.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/AdminLTE.min.css">');
        mywindow.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/skins/_all-skins.min.css">');
        mywindow.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/iCheck/flat/blue.css">');
        mywindow.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/morris/morris.css">');
        mywindow.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/jvectormap/jquery-jvectormap-1.2.2.css">');
        mywindow.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/datepicker/datepicker3.css">');
        mywindow.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/daterangepicker/daterangepicker-bs3.css">');
        mywindow.document.write('<style type="text/css">.test { color:red; } </style></head><body>');
        mywindow.document.write(data);
        mywindow.document.write('</body></html>');
        mywindow.document.close();
        mywindow.print();
    }
</script>
<script>
    $(document).on('change', '#input_type', function(e) {
        var id = $(this).val();
        if (id == 2) {
            $('.embededcol').show();
        } else {
            $('.embededcol').hide();
        }
    });

    $(document).ready(function() {
        var id = $('#input_type').val();
        if (id == 2) {
            $('.embededcol').show();
        } else {
            $('.embededcol').hide();
        }
    });
</script>