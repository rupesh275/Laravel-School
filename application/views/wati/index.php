<link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<script src="<?php echo base_url(); ?>backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">

        <h1><i class="fa fa-gears"></i> <?php echo $this->lang->line('system_settings'); ?><small class="pull-right">
                <a type="button" onclick="test_mail()" class="btn btn-primary btn-sm"><?php echo $this->lang->line('test_email'); ?> </a>
            </small>
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-envelope"></i> <?php echo "Whatsapp Setting"; ?></h3>
                    </div>
                    <form id="form1" action="<?php echo base_url() ?>wati/setting" name="employeeform" class="form-horizontal form-label-left" method="post" accept-charset="utf-8">

                        <div class="box-body">
                            <div class="row">
                                <?php if ($this->session->flashdata('msg')) { ?>
                                    <?php echo $this->session->flashdata('msg');
                                    unset($_SESSION['msg']);
                                    ?>
                                    
                                <?php } ?>
                                <?php echo $this->customlib->getCSRF(); ?>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="exampleInputEmail1">
                                        <?php echo $this->lang->line('mobile_no'); ?>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input id="mobile_no" name="mobile_no" placeholder="" type="text" class="form-control col-md-7 col-xs-12" value="<?php echo set_value('mobile_no',$resullist['mobile_no']); ?>" />
                                        <span class="text-danger"><?php echo form_error('mobile_no'); ?></span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="exampleInputEmail1">
                                        <?php echo $this->lang->line('url'); ?>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input id="url" name="url" placeholder="" type="text" class="form-control col-md-7 col-xs-12" value="<?php echo set_value('url',$resullist['url']); ?>" />
                                        <span class="text-danger"><?php echo form_error('url'); ?></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="exampleInputEmail1">
                                        <?php echo "Authentication Key"; ?>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input id="auth_key" name="auth_key" placeholder="" type="text" class="form-control col-md-7 col-xs-12" value="<?php echo set_value('auth_key',$resullist['auth_key']); ?>" />
                                        <span class="text-danger"><?php echo form_error('auth_key'); ?></span>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="exampleInputEmail1">
                                        <?php echo "Status"; ?>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select id="status" name="status" class="form-control col-md-7 col-xs-12">
                                            <option <?php if ($resullist['status'] == 1) { echo "selected";} ?> value="1">Active</option>
                                            <option <?php if ($resullist['status'] == 2) { echo "selected";} ?> value="2">Inactive</option>
                                        </select>

                                        <span class="text-danger"><?php echo form_error('status'); ?></span>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        <div class="box-footer">
                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                    <?php
                                    if ($this->rbac->hasPrivilege('email_setting', 'can_edit')) {
                                    ?>

                                        <button type="submit" class="btn btn-info btnleftinfo"><?php echo $this->lang->line('save'); ?></button>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <div id="myModal" class="modal fade in" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog2">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title">Test Mail --r</h4>
                </div>
                <div class="modal-body pt0 pb0">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 paddlr">
                            <div class="">
                                <form id="sendform" action="<?php echo base_url() ?>emailconfig/test_mail" name="employeeform" class="form-horizontal form-label-left" method="post" accept-charset="utf-8">
                                    <div class="">


                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="pwd"><?php echo $this->lang->line('email'); ?> </label><small class="req"> *</small>
                                                <input type="text" id="title" autocomplete="off" class="form-control" value="" name="email">
                                                <span id="name_add_error" class="text-danger"></span>
                                            </div>

                                        </div>
                                    </div>

                            </div><!--./row-->
                            <div class="box-footer">
                                <div class="pull-right paddA10">

                                    <button type="submit" class="btn btn-info pull-right">Send --r</button>
                                </div>
                            </div>
                            </form>
                        </div>
                    </div><!--./col-md-12-->

                </div><!--./row-->

            </div>
        </div>
    </div>
</div>
</div>

</div>


<script type="text/javascript">
    $(document).ready(function() {


        $(document).on('change', '#email_type', function() {
            var selected = $(this).val();
            is_disabled(selected);
        });

    });

    function is_disabled(selected) {
        if (selected != "smtp") {
            $('.is_disabled').slideUp();
        } else {
            $('.is_disabled').slideDown();
        }
    }

    function test_mail() {
        $('#myModal').modal('show');
    }

    $(document).ready(function(e) {
        $("#sendform").on('submit', (function(e) {
            e.preventDefault();
            $.ajax({
                url: '<?php echo base_url() ?>emailconfig/test_mail',
                type: "POST",
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    if (data.status == "fail") {
                        var message = "";
                        $.each(data.error, function(index, value) {
                            message += value;
                        });
                        errorMsg(message);
                    } else {
                        successMsg(data.message);
                        window.location.reload(true);
                    }
                },
                error: function() {}
            });
        }));
    });
</script>