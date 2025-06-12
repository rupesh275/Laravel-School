<?php $currency_symbol = $this->customlib->getSchoolCurrencyFormat(); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <section class="content-header">
        <h1>
            <i class="fa fa-money"></i> <?php echo $this->lang->line('fees_collection'); ?>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <div class="box-body box-primary" style="padding-top:0;">
                    <div class="box-header">
                        <div class="row">
                            <div class="col-md-4">
                                <h3 class="box-title"><?php echo "Student Info"; ?></h3>
                            </div>
                            <div class="col-md-8">
                                <div class="btn-group pull-right">
                                    <a href="<?php echo base_url() ?>studentfee" type="button" class="btn btn-primary btn-xs">
                                        <i class="fa fa-arrow-left"></i> <?php echo $this->lang->line('back'); ?></a>
                                </div>
                            </div>

                        </div>
                    </div><!--./box-header-->
                    <div class="box-body" style="padding-top:0;">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="sfborder">
                                    <div class="col-md-2">
                                        <img width="115" height="115" class="round5" src="<?php
                                                                                            if (!empty($student['image'])) {
                                                                                                echo base_url() . $student['image'];
                                                                                            } else {
                                                                                                echo base_url() . "uploads/student_images/no_image.png";
                                                                                            }
                                                                                            ?>" alt="No Image">
                                    </div>

                                    <div class="col-md-10">
                                        <div class="row">
                                            <table class="table table-striped mb0 font13">
                                                <tbody>
                                                    <tr>
                                                        <th class="bozero"><?php echo $this->lang->line('name'); ?></th>
                                                        <td class="bozero"><?php echo $this->customlib->getFullName($student['firstname'], $student['middlename'], $student['lastname'], $sch_setting->middlename, $sch_setting->lastname); ?></td>

                                                        <th class="bozero"><?php echo $this->lang->line('class') . " " . $this->lang->line('section'); ?></th>
                                                        <td class="bozero"><?php echo $student['class'] . " (" . $student['section'] . ")" ?> </td>
                                                    </tr>
                                                    <tr>
                                                        <th><?php echo $this->lang->line('father_name'); ?></th>
                                                        <td><?php echo $student['father_name']; ?></td>
                                                        <th><?php echo $this->lang->line('admission_no'); ?></th>
                                                        <td><?php echo $student['admission_no']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th><?php echo $this->lang->line('mobile_no'); ?></th>
                                                        <td><?php echo $student['mobileno']; ?></td>
                                                        <th><?php echo $this->lang->line('roll_no'); ?></th>
                                                        <td> <?php echo $student['roll_no']; ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th><?php echo "Email ID"; ?></th>
                                                        <td><?php echo $student['email']; ?></td>
                                                        <th><?php echo "Class Teacher"; ?></th>
                                                        <td> <?php if (!empty($class_teacher)) {
                                                                    echo $class_teacher['name'];
                                                                } ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th><?php echo $this->lang->line('category'); ?></th>
                                                        <td>
                                                            <?php
                                                            foreach ($categorylist as $value) {
                                                                if ($student['category_id'] == $value['id']) {
                                                                    echo $value['category'];
                                                                }
                                                            }
                                                            ?>
                                                        </td>
                                                        <?php // if ($sch_setting->rte) { 
                                                        ?>
                                                        <th><?php echo "Teacher Contact" ?></th>
                                                        <td><b class="text-danger"> <?php if (!empty($class_teacher)) {
                                                                                        echo $class_teacher['contact_no'];
                                                                                    }  ?> </b>
                                                        </td>
                                                        <?php // } 
                                                        ?>
                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <!-- general form elements -->
                        <div class="box box-primary">
                            <div class="box-header ptbnull">
                                <h3 class="box-title titlefix"><?php echo "Add Collect Fee"; ?></h3>
                                <div class="box-tools pull-right">
                                    <!-- <button id="btnAdd" class="btn btn-primary btn-sm checkbox-toggle pull-right" type="button"><i class="fa fa-plus"></i> <?php echo $this->lang->line('add'); ?></button> -->
                                </div><!-- /.box-tools -->
                            </div><!-- /.box-header -->
                            <form id="form">
                                <div class="box-body">
                                    <div class="mailbox-messages ">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Date</label> <small class="req"> *</small>
                                                    <input type="text" name="date" value="<?php 
                                                                                                echo date('d-m-Y');
                                                                                             ?>" class="form-control date" id="date">
                                                    <span class="text-danger"><?php echo form_error('date'); ?></span>
                                                </div>
                                            </div>

                                            <!-- </div> -->

                                        </div>

                                    </div><!-- /.mail-box-messages -->
                                </div><!-- /.box-body -->
                                <div class="box-body">
                                    <div class="mailbox-messages ">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form" id="TextBoxContainer" role="form">
                                                    <?php if (!empty($resultlistss)) {
                                                        foreach ($resultlist as $row) {
                                                    ?>
                                                            <div class="del-group">
                                                                <input type="hidden" name="id[]" value="<?php echo $row['id']; ?>" />
                                                                <input type="hidden" name="i[]" value="" />
                                                                <input type="hidden" name="row_id_" value="0" />
                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <label for="exampleInputEmail1">Fees Type</label>
                                                                        <select id="fees_type_id" name="fees_type_id[]" class="form-control">
                                                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                                            <?php
                                                                            if (!empty($feestypeList)) {
                                                                                foreach ($feestypeList as $feestype) {
                                                                            ?>
                                                                                    <option value="<?php echo $feestype['id'] ?>" <?php if ($feestype['id'] == $row['fees_type_id']) {
                                                                                                                                        echo 'selected';
                                                                                                                                    } ?>><?php echo $feestype['type'] ?></option>
                                                                            <?php
                                                                                }
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <label for="exampleInputEmail1">Amount</label>
                                                                        <input type="text" name="amt[]" value="<?php echo $row['amt']; ?>" class="form-control" id="amt">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="exampleInputEmail1">Description</label>
                                                                        <input type="text" name="description[]" value="<?php echo $row['description']; ?>" class="form-control" id="description">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2 text-center"><button id="btnRemove" style="margin-top: 22px" class="btn btn-sm btn-danger" data-id="<?php echo $row['id']; ?>" type="button"><i class="fa fa-trash"></i></button></div>
                                                            </div>
                                                    <?php
                                                        }
                                                    } ?>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="inputPassword3" class="col-sm-3 control-label"> <?php echo $this->lang->line('payment') . " " . $this->lang->line('mode'); ?></label>
                                                    <div class="col-sm-9">
                                                        <label class="radio-inline">
                                                            <input type="radio" name="payment_mode_fee" <?php if (!empty($resultlistsss) && $resultlist[0]['payment_mode'] == 'Cash') {
                                                                                                            echo 'checked';
                                                                                                        } ?> value="Cash" checked="checked"> <?php echo $this->lang->line('cash'); ?></label>
                                                        
                                                        <label class="radio-inline">
                                                            <input type="radio" name="payment_mode_fee" <?php if (!empty($resultlistsss) && $resultlist[0]['payment_mode'] == 'Cheque') {
                                                                                                            echo 'checked';
                                                                                                        } ?> value="Cheque"> <?php echo $this->lang->line('cheque'); ?></label>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="payment_mode_fee" <?php if (!empty($resultlistsss) && $resultlist[0]['payment_mode'] == 'DD') {
                                                                                                            echo 'checked';
                                                                                                        } ?> value="DD"><?php echo $this->lang->line('dd'); ?></label>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="payment_mode_fee" <?php if (!empty($resultlistsss) && $resultlist[0]['payment_mode'] == 'bank_transfer') {
                                                                                                            echo 'checked';
                                                                                                        } ?> value="bank_transfer"><?php echo $this->lang->line('bank_transfer'); ?>
                                                        </label>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="payment_mode_fee" <?php if (!empty($resultlistsss) && $resultlist[0]['payment_mode'] == 'upi') {
                                                                                                            echo 'checked';
                                                                                                        } ?> value="upi"><?php echo $this->lang->line('upi'); ?>
                                                        </label>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="payment_mode_fee" <?php if (!empty($resultlistsss) && $resultlist[0]['payment_mode'] == 'card') {
                                                                                                            echo 'checked';
                                                                                                        } ?> value="card"><?php echo $this->lang->line('card'); ?>
                                                        </label>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="payment_mode_fee" <?php if (!empty($resultlistsss) && $resultlist[0]['payment_mode'] == 'gateway') {
                                                                                                            echo 'checked';
                                                                                                        } ?> value="gateway"><?php echo "Gateway"; ?>
                                                        </label>      
                                                                                                           
                                                        <span class="text-danger" id="payment_mode_error"></span>
                                                    </div>
                                                    <span id="form_collection_payment_mode_fee_error" class="text text-danger"></span>
                                                    <div class="form-group" id="chq_details" style="align:right;margin-left:60px;">
                                                        <div class="col-sm-3">
                                                        <label for="inputEmail3" class="col-sm-3 control-label"><?php echo "Chq. No"; ?> </label>
                                                            <input  name="cheque_no" id="cheque_no" placeholder="" type="text" class="form-control " value=""  autocomplete="off">
                                                            <span id="form_cheque_no_error" class="text text-danger"></span>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <label for="inputEmail3" class="col-sm-3 control-label"><?php echo "Chq. Date"; ?> </label>
                                                            <input  name="cheque_date" id="cheque_date" placeholder="" type="date" class="form-control " value="<?php echo date('d/m/Y');?>"  autocomplete="off">
                                                            <span id="form_cheque_date_error" class="text text-danger"></span>
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <label for="inputEmail3" class="col-sm-3 control-label"><?php echo "Chq. Bank"; ?> </label>
                                                            <input  name="cheque_bank" id="cheque_bank" placeholder="" type="text" class="form-control " value=""  autocomplete="off">
                                                            <span id="form_cheque_bank_error" class="text text-danger"></span>
                                                        </div>
                                                    </div>   
                                                    <div class="form-group">
                                                        <label for="inputPassword3" class="col-sm-3 control-label"> <?php echo $this->lang->line('note') ?></label>
                                                        <div class="col-sm-9">
                                                            <textarea class="form-control" rows="3" name="fee_gupcollected_note" id="description" placeholder=""></textarea>
                                                            <span id="form_collection_fee_gupcollected_note_error" class="text text-danger"></span>
                                                        </div>
                                                    </div>                                                                                                      
                                                </div>
                                                 
                                                                                                                                              
                                            </div>
                                            
                                          
                                        </div>
                                        <div class="col-md-12">
                                                <input type="hidden" name="all_sub_id" id="all_sub_id" value="">
                                                <input type="hidden" name="student_session_id" value="<?php echo $student_session_id; ?>">
                                                <button type="button" id="pay_print" name="collect" value="collect"  style="margin-left: 12px" class="btn  btn-primary pull-right payment_collect" data-actions="print" data-loading-text="<i class='fa fa-spinner fa-spin '></i><?php echo $this->lang->line('processing') ?>"><i class="fa fa-money"></i> <?php echo $this->lang->line('pay'). " & " . $this->lang->line('print') ; ?></button>
                                                <!-- <button type="button" id="pay_btn" name="print"  value="print" class="btn btn-primary pull-right payment_collect" data-actions="collect" data-loading-text="<i class='fa fa-spinner fa-spin '></i><?php echo $this->lang->line('processing') ?>"><i class="fa fa-money"></i> <?php echo $this->lang->line('pay'); ?></button> -->
                                                </button>
                                            </div>                                        
                                    </div>
                                </div>
                            </form>

                        </div>
                        <!-- </form> -->
                    </div><!--/.col (left) -->
                    <!-- right column -->

                </div>

                <div class="row">
                    <!-- left column -->

                    <!-- right column -->
                    <div class="col-md-12">

                    </div><!--/.col (right) -->
                </div> <!-- /.row -->
            </div> <!-- /.row -->
        </div> <!-- /.row -->

    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<script type="text/javascript">
    $(document).ready(function() {
        $('#chq_details').hide();
        <?php if (!empty($resultlistss)) {
        ?>
            var lenght_div = $('#TextBoxContainer .app').length;
            GetDynamicTextBox(lenght_div, 0);
            <?php
        } else {
            ?>GetDynamicTextBox(0, 1);
        <?php
        }
        ?>

        $("#btnreset").click(function() {
            $("#form1")[0].reset();
        });

    });
</script>
<script>
    $(function() {
        $(document).on("click", "#btnAdd", function() {
            var lenght_div = $('#TextBoxContainer .app').length;
            var div = GetDynamicTextBox(lenght_div, 0);
            $("#TextBoxContainer").append(div);
        });
        $(document).on("click", "#btnGet", function() {
            var values = "";
            $("input[name=DynamicTextBox]").each(function() {
                values += $(this).val() + "\n";
            });
        });
        $("body").on("click", ".remove", function() {
            $(this).closest("div").remove();
        });
    });
    function collect_fee_group(print) {
        var form = $("#collect_fee_group");
        // var url = form.attr('action');
        var url = '<?php echo site_url('studentfee/addfeegrp'); ?>';
        var smt_btn = $(this).find("button[type=submit]");
        var total_paid = $('input#total_paid').val();
        var paid_amount = $('input#paid_amount').val();
        var tot_fine_amount = $('input#total_fine_amount').val();
        var total_balance = $('input#total_balance').val();
        var previous_paid = $('input#previous_paid').val();
        var paymode = $('input#radio_temp').val();
        if(paymode == 'Cheque')
        {
            var cheque_no = $('input#cheque_no').val();
            var cheque_bank = $('input#cheque_bank').val();
            if(cheque_no='' || $.isNumeric(cheque_no) == false )
            {
                alert('Please enter the chque no.');
                $('input#cheque_no').focus();
                $('#pay_print').prop('disabled', false);
                $("#pay_btn").prop('disabled', false);                 
                return false;
            }
        }        
        if (print == 1) {
            var btn = '#pay_print';
        } else {
            var btn = '#pay_btn';
        }
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'JSON',
            data: form.serialize() + "&print=" + print + "&total_paid=" + total_paid + "&total_balance=" + total_balance  + "&paid_amount=" + paid_amount + "&tot_fine_amount=" + tot_fine_amount + "&prev_paid=" + previous_paid, // serializes the form's elements.
            beforeSend: function() {
                smt_btn.button('loading');
                $(btn).button('loading');
            },
            success: function(response) {
                if (response.status === 1) {
                    $("#loader").show();

                    if (response.mode=='gateway')
                    {
                        //window.location.href = "<?php echo site_url('site/worldline/'); ?>" + response.hash_code;
                        window.location.href = "<?php echo site_url('onlineadmission/ccavenue/index/'); ?>" + response.hash_code;
                    }
                    else if(response.mode=='cheque')
                    {
                        Popup(response.print, true);
                    }
                    else
                    {
                        if (print == 0) {
                            location.reload(true);
                        } else if (print == 1) {
                            Popup(response.print);
                        }                        
                    }                    

                } else if (response.status === 0) {
                    $.each(response.error, function(index, value) {
                        var errorDiv = '#form_collection_' + index + '_error';
                        $(errorDiv).empty().append(value);
                    });
                    if(response.msg!='')
                    {
                        alert(response.msg);
                        $('#date').focus();
                    }                    
                }
            },
            error: function(xhr) { // if error occured

                alert("Error occured.please try again");

            },
            complete: function() {
                smt_btn.button('reset');
                // $("#loader").hide();
            }
        });
    }

    $(document).on("click", "#pay_btn", function() {
        var collect = collect_fee_group(0);
        console.log(collect);
        // alert('pay_btn');
    });

    $(document).on("click", "#pay_print", function() {
        var paid_amt = $("#paid_amount").val();
        var valid_amt = $("#valid_amount").val();
        // if(parseFloat(paid_amt)!=parseFloat(valid_amt))
        // {
        //     alert("Paid Amount Mismatch");
        //     return false;
        // }
        $(this).prop('disabled', true);
        $("#pay_btn").prop('disabled', true);
        // var collect = collect_fee_group(1);
        // console.log(collect);
        // alert('pay_print');
        //e.preventDefault(); // avoid to execute the actual submit of the form.
        $.ajax({
                type: "POST",
                dataType: 'Json',
                url: '<?php echo base_url('studentfee/general_receipt_valid'); ?>',
                data: $("#form").serialize(), // serializes the form's elements.
                success: function(data) {
                    if (data.status == "fail") {
                        var message = "";
                        $.each(data.error, function(index, value) {

                            message += value;
                        });
                        errorMsg(message);
                    } else {
                    
                        if (data.status === "success") {
                                $("#loader").show();
                                if (data.mode=='gateway')
                                {
                                    //window.location.href = "<?php echo site_url('site/worldline_payrequest/'); ?>" + data.hash_code;
                                    window.location.href = "<?php echo site_url('onlineadmission/ccavenue/index/'); ?>" + data.hash_code;
                                }
                                else if(data.mode=='cheque')
                                {
                                    Popup(data.print, true);
                                }
                                else
                                {
                                    Popup(data.print, true);
                                    // if (print == 0) {
                                    //     location.reload(true);
                                    // } else if (print == 1) {
                                       
                                    // }                        
                                }                    
                            } else if (data.status === "failed") {
                                // $.each(response.error, function(index, value) {
                                //     var errorDiv = '#form_collection_' + index + '_error';
                                //     $(errorDiv).empty().append(value);
                                // });
                                if(data.msg!='')
                                {
                                    alert(data.msg);
                                    $('#date').focus();
                                }                    
                            }

                        

                    }
                },
                complete: function() {

                }
            });        
    });

    function GetDynamicTextBox(value, type) {

        // alert(value);
        var row = "";
        row += '<div class="del-group">';
        row += '<input type="hidden" name="id[]" value=""/>';
        row += '<input type="hidden" name="i[]" value="' + value + '"/>';
        row += '<input type="hidden" name="row_id_' + value + '" value="0"/>';
        row += '<div class="col-md-3">';
        row += '<div class="form-group">';
        row += '<label for="exampleInputEmail1">Fees Type</label>';
        row += '<select  id="fees_type_id" name="fees_type_id[]" class="form-control" >';
        row += '<option value=""><?php echo $this->lang->line('select'); ?></option>';
        <?php
        if (!empty($feestypeList)) {
            foreach ($feestypeList as $feestype) {
        ?>
                row += '<option value="<?php echo $feestype['id'] ?>"><?php echo $feestype['type'] ?></option>';
        <?php
            }
        }
        ?>
        row += '</select>';
        row += '</div>';
        row += '</div>';
        row += '<div class="col-md-3">';
        row += '<div class="form-group">';
        row += '<label for="exampleInputEmail1">Amount</label>';
        row += '<input type="text" name="amt[]" class="form-control" id="amt">';
        row += '</div>';
        row += '</div>';
        row += '<div class="col-md-4">';
        row += '<div class="form-group">';
        row += '<label for="exampleInputEmail1">Description</label>';
        row += '<input type="text" name="description[]" class="form-control" id="description">';
        row += '</div>';
        row += '</div>';
        row += '<div class="col-md-2 text-center"><button id="btnRemove" style="margin-top: 22px" class="btn btn-sm btn-danger" type="button"><i class="fa fa-trash"></i></button></div>';
        row += '</div>';
        if (type == 1) {
            var value = $('#TextBoxContainer .app').length;
            $("#TextBoxContainer").append(row);
        } else {
            return row;
        }
    }
    $(document).ready(function() {

        $(document).on('submit', '#form', function(event) {
            event.preventDefault();
            $.ajax({
                type: "POST",
                dataType: 'Json',
                url: '<?php echo base_url('studentfee/collectfileValid'); ?>',
                data: $("#form").serialize(), // serializes the form's elements.
                success: function(data) {
                    if (data.status == "fail") {
                        var message = "";
                        $.each(data.error, function(index, value) {

                            message += value;
                        });
                        errorMsg(message);
                    } else {
                        successMsg(data.message);
                        Popup(data.print, true);
                        // window.setTimeout(
                        //     function() {
                        //         location.reload(true)
                        //     },
                        //     1000
                        // );
                    }
                },
                complete: function() {

                }
            });
            // } else {
            //     console.log("does not validate");
            // }

        });
        $(document).on('click', '#btnRemove', function() {
            $(this).parents('.del-group').remove();
            var id = $(this).data("id");
            if (id != '') {
                var del_id = $('#all_sub_id').val();
                var all_id = del_id + ',' + id;
                $('#all_sub_id').val(all_id);
            }
        });
        $(document).on('change','input[type=radio][name=payment_mode_fee]',function() {
            $('#radio_temp').val(this.value);
            if(this.value=="Cheque")
            {
                $('#chq_details').show();
            }
            else
            {$('#chq_details').hide();}
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
</script>