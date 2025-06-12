<style type="text/css">
    #div_avail {
        display: none;
    }
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><i class="fa fa-building-o"></i> <?php echo $this->lang->line('inventory'); ?></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo "FD Management"; ?></h3>
                        <div class="box-tools pull-right">
                            <small class="pull-right"></small>
                        </div>
                    </div>
                    <div class="box-body">
                        <?php if ($this->session->flashdata('msg')) { ?> <?php echo $this->session->flashdata('msg') ?> <?php } ?>
                        <div class="row">
                            <form role="form" action="<?php echo site_url('admin/issueitem/createFD') ?>" method="post" class="">
                                <?php echo $this->customlib->getCSRF(); ?>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>FD No </label><small class="req"> *</small>
                                        <input type="text" name="fdt_no" value="<?php echo set_value('fdt_no', !empty($update['fdt_no']) ? $update['fdt_no'] : "") ?>" id="fdt_no" class="form-control">
                                        <span class="text-danger"><?php echo form_error('fdt_no'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>FD Name</label><small class="req"> *</small>
                                        <input type="text" name="fdt_name" value="<?php echo set_value('fdt_name', !empty($update['fdt_name']) ? $update['fdt_name'] : "") ?>" id="fdt_name" class="form-control">
                                        <span class="text-danger"><?php echo form_error('fdt_name'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Bank</label><small class="req"> *</small>
                                        <input type="text" name="fdt_bank" value="<?php echo set_value('fdt_bank', !empty($update['fdt_bank']) ? $update['fdt_bank'] : ""); ?>" id="fdt_bank" class="form-control">
                                        <span class="text-danger"><?php echo form_error('fdt_bank'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Branch </label><small class="req"> *</small>
                                        <input type="text" name="fdt_branch" value="<?php echo set_value('fdt_branch', !empty($update['fdt_branch']) ? $update['fdt_branch'] : ""); ?>" id="fdt_branch" class="form-control">
                                        <span class="text-danger"><?php echo form_error('fdt_branch'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Date Of FD </label><small class="req"> *</small>
                                        <input type="text" name="fdt_dofd" value="<?php echo set_value('fdt_dofd', !empty($update['fdt_dofd']) ? date('d-m-Y',strtotime($update['fdt_dofd'])) : date('d-m-Y')); ?>" id="fdt_dofd" class="form-control date">
                                        <span class="text-danger"><?php echo form_error('fdt_dofd'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Reminder In Days </label><small class="req"> *</small>
                                        <input type="text" name="reminder_in_days" value="<?php echo set_value('reminder_in_days', !empty($update['reminder_in_days']) ? $update['reminder_in_days'] : ""); ?>" id="reminder_in_days" class="form-control">
                                        <span class="text-danger"><?php echo form_error('reminder_in_days'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Amount </label><small class="req"> *</small>
                                        <input type="text" name="fdt_amount" value="<?php echo set_value('fdt_amount', !empty($update['fdt_amount']) ? $update['fdt_amount'] : ""); ?>" id="fdt_amount" class="form-control">
                                        <span class="text-danger"><?php echo form_error('fdt_amount'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Interest Rate </label><small class="req"> *</small>
                                        <input type="text" name="fdt_intrate" value="<?php echo set_value('fdt_intrate', !empty($update['fdt_intrate']) ? $update['fdt_intrate'] : ""); ?>" id="fdt_intrate" class="form-control">
                                        <span class="text-danger"><?php echo form_error('fdt_intrate'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Maturity Amount </label><small class="req"> *</small>
                                        <input type="text" name="fdt_matamount" value="<?php echo set_value('fdt_matamount', !empty($update['fdt_matamount']) ? $update['fdt_matamount'] : ""); ?>" id="fdt_matamount" class="form-control">
                                        <span class="text-danger"><?php echo form_error('fdt_matamount'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Tenure In </label><small class="req"> *</small>
                                        <!-- <input type="text" name="fdt_nomonth" value="<?php //echo set_value('fdt_nomonth', !empty($update['fdt_nomonth']) ? $update['fdt_nomonth'] : ""); ?>" id="fdt_nomonth" class="form-control"> -->
                                        <select name="tenure_in" class="form-control" id="tenure_in">
                                        <option <?php if (!empty($update) && $update['tenure_in'] == 1) {
                                                    echo "selected";
                                                } ?> value="1">Yearly</option>
                                        <option <?php if (!empty($update) && $update['tenure_in'] == 2) {
                                                    echo "selected";
                                                } ?> value="2">Monthly</option>
                                        <option <?php if (!empty($update) && $update['tenure_in'] == 3) {
                                                    echo "selected";
                                                } ?> value="3">Weekly</option>
                                        <option <?php if (!empty($update) && $update['tenure_in'] == 4) {
                                                    echo "selected";
                                                } ?> value="4">Daily</option>
                                    </select>
                                        <span class="text-danger"><?php echo form_error('tenure_in'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Tenure </label><small class="req"> *</small>
                                        <input type="text" name="tenure" value="<?php echo set_value('tenure', !empty($update['tenure']) ? $update['tenure'] : ""); ?>" id="tenure" class="form-control">
                                        <span class="text-danger"><?php echo form_error('tenure'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Mature Date </label><small class="req"> *</small>
                                        <input type="text" name="fdt_wdate" value="<?php echo set_value('fdt_wdate', !empty($update['fdt_wdate']) ? date('d-m-Y',strtotime($update['fdt_wdate'])) : ""); ?>" id="fdt_wdate" class="form-control date">
                                        <span class="text-danger"><?php echo form_error('fdt_wdate'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Status </label><small class="req"> *</small>
                                        <select name="fdt_status" class="form-control" id="fdt_status">
                                        <option <?php if (!empty($update) && $update['fdt_status'] == 1) {
                                                    echo "selected";
                                                } ?> value="1">Active</option>
                                        <option <?php if (!empty($update) && $update['fdt_status'] == 2) {
                                                    echo "selected";
                                                } ?> value="2">Inactive</option>
                                    </select>
                                        <span class="text-danger"><?php echo form_error('fdt_status'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-12 text-center">
                                    <input type="hidden" name="id" value="<?php echo !empty($update['id']) ? $update['id'] : "" ?>">
                                    <button type="submit" class="btn btn-info" autocomplete="off">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
            </div><!--/.col (right) -->
        </div> <!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->


<script type="text/javascript">
    var base_url = '<?php echo base_url() ?>';

    function populateItem(item_id_post, item_category_id_post) {
        if (item_category_id_post != "") {
            $('#item_id').html("");
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: "GET",
                url: base_url + "admin/itemstock/getItemByCategory",
                data: {
                    'item_category_id': item_category_id_post
                },
                dataType: "json",
                success: function(data) {
                    $.each(data, function(i, obj) {
                        var select = "";
                        if (item_id_post == obj.id) {
                            var select = "selected=selected";
                        }
                        div_data += "<option value=" + obj.id + " " + select + ">" + obj.name + "</option>";
                    });
                    $('#item_id').append(div_data);
                }

            });
        }
    }


    $(document).on('change', '#item_category_id', function(e) {
        $('#item_id').html("");
        var item_category_id = $(this).val();
        populateItem(0, item_category_id);
    });
    $(document).on('change', '#item_id', function(e) {
        $('#div_avail').hide();
        var item_id = $(this).val();
        availableQuantity(item_id);

    });
    $(document).on('keyup', '#fdt_intrate,#fdt_amount', function(e) {
        var fdt_amount = parseFloat($('#fdt_amount').val()) || 0;
        var fdt_intrate = parseFloat($('#fdt_intrate').val()) || 0;

        if (fdt_amount != "") {
           var new_width = (fdt_intrate / 100) * fdt_amount;
           var totalamount = new_width + fdt_amount;
        //    console.log(totalamount);
            $('#fdt_matamount').val(totalamount);
        }else{
            $('#fdt_matamount').val('0');
        }
        // availableQuantity(item_id);

    });

    function availableQuantity(item_id) {
        if (item_id != "") {
            $('#item_available_quantity').html("");
            var div_data = '';
            $.ajax({
                type: "GET",
                url: base_url + "admin/item/getAvailQuantity",
                data: {
                    'item_id': item_id
                },
                dataType: "json",
                success: function(data) {

                    $('#item_available_quantity').html(data.available);
                    $('#div_avail').show();
                }

            });
        }
    }

    $("input[name=account_type]:radio").change(function() {
        var user = $('input[name=account_type]:checked').val();
        getIssueUser(user);



    });

    function getIssueUser(usertype) {
        $('#issue_to').html("");
        var div_data = "";
        $.ajax({
            type: "POST",
            url: base_url + "admin/issueitem/getUser",
            data: {
                'usertype': usertype
            },
            dataType: "json",
            success: function(data) {

                $.each(data.result, function(i, obj) {
                    if (data.usertype == "admin") {
                        name = obj.username;
                    } else {
                        name = obj.name + " " + obj.surname + " (" + obj.employee_id + ")";

                    }
                    div_data += "<option value=" + obj.id + ">" + name + "</option>";
                });
                $('#issue_to').append(div_data);
            }

        });
    }

    $("#issueitem").submit(function(e) {
        var data = $(this).serializeArray();
        var issue_to = $('#issue_to option:selected').text();
        data.push({
            name: 'issue_to_name',
            value: issue_to
        });

        var $this = $('.allot-fees');
        $this.button('loading');
        e.preventDefault();
        var postData = data;
        var formURL = $(this).attr("action");
        $.ajax({
            url: formURL,
            type: "POST",
            data: postData,
            dataType: 'Json',
            success: function(data, textStatus, jqXHR) {
                if (data.status == "fail") {
                    var message = "";
                    $.each(data.error, function(index, value) {

                        message += value;
                    });
                    errorMsg(message);
                } else {
                    $('#item_available_quantity').html("");
                    $('#div_avail').css('display', 'none');
                    document.getElementById("issueitem").reset();
                    successMsg(data.message);
                }

                $this.button('reset');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                $this.button('reset');
            }
        });

    });
</script>
<script type="text/javascript" src="<?php echo base_url(); ?>backend/dist/js/savemode.js"></script>