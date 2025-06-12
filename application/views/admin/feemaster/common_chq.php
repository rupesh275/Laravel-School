<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1>
            <i class="fa fa-map-o"></i> <?php echo $this->lang->line('examinations'); ?> <small><?php echo $this->lang->line('student_fee1'); ?></small>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">

    <?php $this->load->view('admin/feemaster/chq_menu');?>
        <div class="row">
            <div class="col-md-12">
                <div class="box removeboxmius">
                    <div class="box-header ptbnull"></div>
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>
                    <div class="box-body">
                        <form id="form" action="<?php echo base_url('admin/feemaster/common_chq') ?>" method="post" accept-charset="utf-8">

                            <?php echo $this->customlib->getCSRF(); ?>

                            <div class="row">

                                <div class="col-sm-4 col-lg-4 col-md-4 ">
                                    <div class="form-group" style="height: 50px;">
                                        <label for="exampleInputEmail1"><?php echo "Collection Date"; ?></label>
                                        <input type="text" name="created_at" id="created_at" class="form-control date" value="<?php echo set_value('created_at', !empty($update) ? date('d-m-Y', strtotime($update['created_at'])) : date('d-m-Y')); ?>">
                                    </div>
                                    <span class="text-danger"><?php echo form_error('created_at'); ?></span>
                                    <div class="text-danger" id="error-created_at"></div>
                                </div>

                                <!-- <div class="col-sm-4 col-lg-4 col-md-4 ">
                                    <div class="form-group" style="height: 50px;">
                                        <label for="exampleInputEmail1"><?php //echo "Type"; ?></label>
                                        <select autofocus="" id="chq_type" name="chq_type" class="form-control">
                                            <option value=""><?php //echo $this->lang->line('select'); ?></option>
                                            <option <?php //if (!empty($update) && $update['chq_type'] == 1) {
                                                        //echo "selected";
                                                    //} ?> value="1">Student</option>
                                            <option <?php //if (!empty($update) && $update['chq_type'] == 2) {
                                                        //echo "selected";
                                                    //} ?> value="2">Others</option>
                                        </select>
                                    </div>
                                    <span class="text-danger"><?php //echo form_error('chq_type'); ?></span>
                                    <div class="text-danger" id="error-chq_type"></div>
                                </div> -->

                                <div class="col-sm-4 col-lg-4 col-md-4 ">
                                    <div class="form-group" style="height: 50px;">
                                        <label for="exampleInputEmail1"><?php echo "Cheque No"; ?></label>
                                        <input type="text" name="chq_no" id="chq_no" class="form-control" value="<?php echo set_value('chq_no', !empty($update) ? $update['chq_no'] : ""); ?>">
                                    </div>
                                    <span class="text-danger"><?php echo form_error('chq_no'); ?></span>
                                    <div class="text-danger" id="error-chq_no"></div>
                                </div>

                                <div class="col-sm-4 col-lg-4 col-md-4 ">
                                    <div class="form-group" style="height: 50px;">
                                        <label for="exampleInputEmail1"><?php echo "Cheque Date"; ?></label>
                                        <input type="text" name="chq_date" id="chq_date" class="form-control date" value="<?php echo set_value('chq_date', !empty($update) ? date('d-m-Y', strtotime($update['chq_date'])) : date('d-m-Y')); ?>">
                                    </div>
                                    <span class="text-danger"><?php echo form_error('chq_date'); ?></span>
                                    <div class="text-danger" id="error-chq_date"></div>
                                </div>

                                <div class="col-sm-4 col-lg-4 col-md-4 ">
                                    <div class="form-group" style="height: 50px;">
                                        <label for="exampleInputEmail1"><?php echo "Bank"; ?></label>
                                        <input type="text" name="chq_bank" id="chq_bank" class="form-control" value="<?php echo set_value('chq_bank', !empty($update) ? $update['chq_bank'] : ""); ?>">
                                    </div>
                                    <span class="text-danger"><?php echo form_error('chq_bank'); ?></span>
                                    <div class="text-danger" id="error-chq_bank"></div>
                                </div>

                                <div class="col-sm-4 col-lg-4 col-md-4 ">
                                    <div class="form-group" style="height: 50px;">
                                        <label for="exampleInputEmail1"><?php echo "Branch"; ?></label>
                                        <input type="text" name="chq_branch" id="chq_branch" class="form-control" value="<?php echo set_value('chq_branch', !empty($update) ? $update['chq_branch'] : ""); ?>">
                                    </div>
                                    <span class="text-danger"><?php echo form_error('chq_branch'); ?></span>
                                    <div class="text-danger" id="error-chq_branch"></div>
                                </div>



                                <div class="col-sm-4 col-lg-4 col-md-4 ">
                                    <div class="form-group" style="height: 50px;">
                                        <label for="exampleInputEmail1"><?php echo "Amount"; ?></label>
                                        <input type="text" name="chq_amt" id="chq_amt" class="form-control" value="<?php echo set_value('chq_amt', !empty($update) ? $update['chq_amt'] : ""); ?>">
                                    </div>
                                    <span class="text-danger"><?php echo form_error('chq_amt'); ?></span>
                                    <div class="text-danger" id="error-chq_amt"></div>
                                </div>
                                <!-- <div class="col-sm-4 col-lg-4 col-md-4 ">
                                    <div class="form-group" style="height: 50px;">
                                        <label for="exampleInputEmail1"><?php echo "Cheque Status"; ?></label>
                                        <select autofocus="" id="chq_status" name="chq_status" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <option <?php if (!empty($update) && $update['chq_status'] == 1) {
                                                        echo "selected";
                                                    } ?> value="1">Collected</option>
                                            <option <?php if (!empty($update) && $update['chq_status'] == 2) {
                                                        echo "selected";
                                                    } ?> value="2">Deposit</option>
                                            <option <?php if (!empty($update) && $update['chq_status'] == 3) {
                                                        echo "selected";
                                                    } ?> value="3">Passed</option>
                                            <option <?php if (!empty($update) && $update['chq_status'] == 4) {
                                                        echo "selected";
                                                    } ?> value="4">Bounced</option>
                                        </select>
                                    </div>
                                    <span class="text-danger"><?php echo form_error('chq_status'); ?></span>
                                    <div class="text-danger" id="error-chq_status"></div>
                                </div> -->


                                <div class="col-sm-4 col-lg-4 col-md-4 ">
                                    <div class="form-group" style="height: 70px;">
                                        <label for="exampleInputEmail1"><?php echo "Contact No"; ?></label>
                                        <input type="text" name="contact_no" id="contact_no" class="form-control" value="<?php echo set_value('contact_no', !empty($update) ? $update['contact_no'] : ""); ?>">
                                    </div>
                                    <span class="text-danger"><?php echo form_error('contact_no'); ?></span>
                                    <div class="text-danger" id="error-contact_no"></div>
                                </div>

                                <div class="col-sm-4 col-lg-4 col-md-4 ">
                                    <div class="form-group" style="height: 50px;">
                                        <label for="exampleInputEmail1"><?php echo "Remarks"; ?></label>
                                        <input type="text" name="remarks" id="remarks" class="form-control" value="<?php echo set_value('remarks', !empty($update) ? $update['remarks'] : ""); ?>">
                                    </div>
                                    <span class="text-danger"><?php echo form_error('remarks'); ?></span>
                                    <div class="text-danger" id="error-remarks"></div>
                                </div>




                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <input type="hidden" name="id" value="<?php if (isset($update)) {
                                                                                    echo $update['id'];
                                                                                } ?>">
                                        <input type="hidden" id="studentSession_id" value="<?php if (isset($update)) {
                                                                                                echo $update['student_session_id'];
                                                                                            } ?>">
                                        <button type="submit" class="btn btn-primary pull-right btn-sm checkbox-toggle" style="margin-left: 12px;"> <?php echo $this->lang->line('save'); ?></button>
                                    </div>
                                </div>
                            </div>
                        </form>


                    </div>

                </div>


            </div>
        </div>


    </section>
</div>


<script type="text/javascript">
    $(document).on('ready', function() {
        $('.select2').select2();
    });
    var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy']) ?>';
    var class_id = '<?php echo set_value('class_id') ?>';
    var section_id = '<?php echo set_value('section_id') ?>';
    var session_id = '<?php echo set_value('session_id') ?>';
    var exam_group_id = '<?php echo set_value('exam_group_id') ?>';
    var exam_id = '<?php echo set_value('exam_id') ?>';
    getSectionByClass(class_id, section_id);


    $(document).on('change', '#class_id', function(e) {
        $('#section_id').html("");
        var class_id = $(this).val();
        getSectionByClass(class_id, 0);
    });
    $(document).on('change', '#session_id', function(e) {
        $('#section_id').html("");
        $('#student_id').html("");
        // var class_id = $(this).val();
        // getSectionByClass(class_id, 0);
    });
    <?php
    if (isset($update)) { ?>
        getSectionByClass(<?php echo $update['class_id']; ?>, 0);
        setTimeout(function() {
            $("#section_id").val(<?php echo $update['section_id']; ?>).trigger('change');
        }, 600);

        setTimeout(function() {
            $("#student_id").val(<?php echo $update['student_session_id']; ?>).trigger('change');
        }, 2000);

    <?php } ?>

    function getSectionByClass(class_id, section_id) {

        if (class_id !== "") {
            $('#section_id').html("");
            $('#student_id').html("");
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';

            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {
                    'class_id': class_id
                },
                dataType: "json",
                beforeSend: function() {
                    $('#section_id').addClass('dropdownloading');
                },
                success: function(data) {
                    $.each(data, function(i, obj) {
                        var sel = "";
                        if (section_id == obj.section_id) {
                            sel = "selected";
                        }
                        div_data += "<option value=" + obj.section_id + " " + sel + ">" + obj.section + "</option>";
                    });
                    $('#section_id').append(div_data);
                },
                complete: function() {
                    $('#section_id').removeClass('dropdownloading');
                }
            });
        }
    }

    $(document).on('change', '#section_id', function() {
        var $this = $(this);
        // var session_id = $('#session_id').val();
        var class_id = $('#class_id').val();
        var section_id = $('#section_id').val();
        var student_session_id = $('#student_session_id').val();
        $('#student_session_id').html("");
        var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
        $.ajax({
            type: "POST",
            url: base_url + "admin/feemaster/getclass_students",
            data: {
                'class_id': class_id,
                'section_id': section_id,
                // 'session_id': session_id,
            },
            dataType: "json",
            beforeSend: function() {
                $('#student_session_id').addClass('dropdownloading');
            },
            success: function(data) {
                var student_session_id = $('#studentSession_id').val();
                // console.log(student_session_id);
                $.each(data, function(i, obj) {
                    var sel = "";
                    if (student_session_id === obj.studentses_id) {
                        sel = "selected";
                    }
                    div_data += "<option value=" + obj.studentses_id + " " + sel + ">" + obj.firstname + " " + obj.lastname + " (" + obj.roll_no + ")" + "</option>";
                });
                $('#student_session_id').append(div_data);
                //$('#student_session_id').val(div_data);
            },
            complete: function() {
                $('#student_session_id').removeClass('dropdownloading');
            }
        });
    });

    $(document).on('change', '#student_session_id', function() {
        var $this = $(this);
        var student_session_id = $('#student_session_id').val();
        var nationality = $('#nationality').val();
        $.ajax({
            type: "POST",
            url: base_url + "admin/certificate/getstudents_detail",
            data: {
                'student_session_id': student_session_id,
            },
            dataType: "json",
            beforeSend: function() {
                // $('#student_id').addClass('dropdownloading');
            },
            success: function(data) {
                $('#contact_no').val(data.mobileno);
                // $('#student_id').append(div_data);
            },
            complete: function() {
                // $('#student_id').removeClass('dropdownloading');
            }
        });
    });

    // $("#form").on("submit", function(e) {
    //     e.preventDefault();
    //     $.ajax({
    //         url: "<?php echo site_url('admin/feemaster/tc_register_valid') ?>",
    //         type: 'POST',
    //         data: new FormData(this),
    //         contentType: false,
    //         cache: false,
    //         processData: false,
    //         dataType: 'json',
    //         beforeSend: function(data) {
    //             // $("#spinner").show();
    //             // $("#submit").attr("disabled",true);
    //         },
    //         success: function(data) {
    //             // $("#spinner").hide(); 
    //             // $("#submit").attr("disabled",false);
    //             if (data.error) {
    //                 $.each(data, function(key, value) {
    //                     if (value) {
    //                         $('#error-' + key).html(value);
    //                         $('#input-' + key).addClass("border-danger");
    //                     } else {
    //                         $('#error-' + key).html(" ");
    //                         $('#input-' + key).removeClass("border-danger");
    //                     }
    //                 });
    //             }
    //             if (data.success) {
    //                 $('#form .form-control').removeClass("error");
    //                 $('#form .error').html(" ");
    //                 Popup(data.response);
    //             }
    //         }
    //     });
    // });
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
            if (winload == false) {
                window.location.href = '<?php echo site_url('admin/certificate/tc_list') ?>';
            }
        }, 500);

        return true;
    }
</script>