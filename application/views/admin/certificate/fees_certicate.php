<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1>
            <i class="fa fa-map-o"></i> <?php echo $this->lang->line('examinations'); ?> <small><?php echo $this->lang->line('student_fee1'); ?></small>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box removeboxmius">
                    <div class="box-header ptbnull"></div>
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>
                    <div class="box-body">
                        <form id="form" method="post" accept-charset="utf-8">

                            <?php echo $this->customlib->getCSRF(); ?>
                            <?php if ($this->session->flashdata('msg')) { ?>
                                <?php echo $this->session->flashdata('msg') ?>
                            <?php } ?>

                            <div class="row">

                                <div class="col-sm-4 col-lg-4 col-md-4 ">
                                    <div class="form-group" style="height: 50px;">
                                        <label><?php echo $this->lang->line('class'); ?></label>
                                        <?php 
                                        if (!empty($update)) { ?>
                                            <input type="hidden"  name="class_id" value="<?php echo $update['class_id']; ?>">
                                            <input type="text" readonly id="class_id" name="class_ids" class="form-control" value="<?php echo $update['class']; ?>">
                                        <?php } else {
                                        ?>
                                            <select autofocus="" id="class_id" name="class_id" class="form-control select2">
                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                <?php
                                                // print_r($classlist);die;
                                                $count = 1;
                                                foreach ($classlist as $class) {
                                                ?>
                                                    <option value="<?php echo $class['id'] ?>" <?php
                                                                                                if (set_value('class_id') == $class['id']) {
                                                                                                    echo "selected=selected";
                                                                                                }
                                                                                                ?>><?php echo $class['class'] ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        <?php
                                        }
                                        ?>
                                        <input type="hidden" name="id" value="<?php if (isset($update)) {
                                                                                    echo $update['id'];
                                                                                } ?>">
                                    </div>
                                    <span class="text-danger"><?php echo form_error('class_id'); ?></span>
                                    <div class="text-danger" id="error-class_id"></div>
                                </div>

                                <div class="col-sm-4 col-lg-4 col-md-4 ">
                                    <div class="form-group" style="height: 50px;">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('section'); ?></label>
                                        <?php if (!empty($update)) {
                                        ?>
                                            <input type="text" readonly id="section" name="section_id" class="form-control" value="<?php echo $update['section']; ?>">
                                        <?php
                                        } else {
                                        ?>
                                            <select id="section_id" name="section_id" class="form-control">
                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            </select>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                    <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                                    <div class="text-danger" id="error-section_id"></div>
                                </div>

                                <div class="col-sm-4 col-lg-4 col-md-4 ">
                                    <div class="form-group" style="height: 50px;">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('student'); ?></label>
                                        <?php if (!empty($update)) {
                                        ?>
                                            <input type="hidden"  id="student_session" name="feetrn_id" class="form-control" value="<?php echo $update['id']; ?>">
                                            <input type="hidden"  id="student_session" name="student_session_id" class="form-control" value="<?php echo $update['student_session_id']; ?>">
                                            <input type="text" readonly id="student_session" name="student_session" class="form-control" value="<?php echo $update['firstname'] . " " . $update['lastname']; ?>">
                                        <?php
                                        } else {
                                        ?>
                                            <select id="student_session_id" name="student_session_id" class="form-control">
                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            </select>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                    <span class="text-danger"><?php echo form_error('student_session_id'); ?></span>
                                    <div class="text-danger" id="error-student_session_id"></div>
                                </div>
                                <br>
                                <br>
                                <div class="col-md-12">
                                    <div class="box box-info" id="box_display">
                                        <div class="box-header with-border">
                                            <h3 class="box-title"><i class="fa fa-users"> </i> <?php echo $this->lang->line('fees'); ?></h3>

                                            <div class="box-tools pull-right">
                                                <button id="btnAdd" class="btn btn-primary btn-sm checkbox-toggle pull-right" type="button"><i class="fa fa-plus"></i> <?php echo $this->lang->line('add'); ?></button>
                                            </div>
                                        </div>
                                        <!-- <form action="<?php echo base_url() ?>admin/teacher/assignteacher" method="POST" id="formSubjectTeacher"> -->
                                        <br />
                                        <input type="hidden" value="<?php echo $current_session_id; ?>" id="current_session_id" name="current_session_id">
                                        <div class="form-horizontal" id="TextBoxContainer" role="form">
                                            <?php
                                            if (!empty($update)) {
                                                $feesArray = $this->certificate_model->getfeesData($update['id']);
                                                foreach ($feesArray as $key => $feeRow) {
                                                    ?>
                                                <div class="form-group app">
                                                    <div class="col-md-12">
                                                        <div class="form-group row">
                                                            <input type="hidden" name="feesub_id[]" value="<?php echo $feeRow['id']; ?>">
                                                            <label for="inputValue" class="col-md-1 control-label">Fees</label>
                                                            <div class="col-md-4">
                                                                <input type="text" id="fees_name[]" name="fees_name[]" value="<?php echo $feeRow['fees_name']; ?>" class="form-control">
                                                            </div>
                                                            <label for="inputKey" class="col-md-1 control-label">Amount</label>
                                                            <div class="col-md-4">
                                                                <input type="text" id="amount[]" name="amount[]" value="<?php echo $feeRow['amount']; ?>" class="form-control">
                                                            </div>
                                                            <div class="col-md-2"><button id="btnRemove" style="" class="btn btn-sm btn-danger" type="button"><i class="fa fa-trash"></i></button></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php
                                            } }
                                            ?>
                                        </div>
                                        <div class="box-footer">
                                            <button type="submit" class="btn btn-primary btn-sm btn pull-right save_button" style="display: none;"><?php echo $this->lang->line('save'); ?></button>
                                        </div>
                        </form>
                    </div>
                </div>


                <div class="col-sm-12">
                    <div class="form-group">
                        <button type="submit" name="search" value="search_filter" class="btn btn-primary pull-right btn-sm checkbox-toggle" style="margin-left: 12px;"> <?php echo $this->lang->line('save'); ?></button>
                        <!-- <button type="submit" name="search" value="search_filter" class="btn btn-primary pull-right btn-sm checkbox-toggle" style="margin-left: 12px;"><i class="fa fa-check"></i> <?php echo $this->lang->line('approve'); ?></button>
                                        <button type="button" name="search" value="search_filter" class="btn btn-primary pull-right btn-sm checkbox-toggle unapprove"><i class="fa fa-ban"></i> <?php echo "Unapprove"; ?></button> -->
                    </div>
                </div>
            </div>

            <!-- </form> -->

            <br><br>
            <div class="col-md-12">
                <div class="box box-primary" id="sublist">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo "Fees Certificate List"; ?></h3>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label"><?php echo "Fees Certificate List"; ?></div>
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th><?php echo "Sr No"; ?></th>
                                        <th><?php echo "Class"; ?></th>
                                        <th><?php echo $this->lang->line('roll_no'); ?></th>
                                        <th><?php echo "Admission No"; ?></th>
                                        <th><?php echo "Name"; ?></th>
                                        <th><?php echo "Gender"; ?></th>
                                        <th class="text-right no-print"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $count = 1;
                                    if (!empty($resultlist)) {
                                        foreach ($resultlist as $row) {
                                            $feeTrn = $this->certificate_model->getfeesTrn($row['id']);
                                    ?>
                                            <tr>
                                                <td class="mailbox-name"><?php echo $feeTrn['certificate_no']; ?></td>
                                                <td class="mailbox-name"><?php echo $row['code']."-".$row['section']; ?></td>
                                                <td class="mailbox-name"><?php echo $row['roll_no'] ?></td>
                                                <td class="mailbox-name"><?php echo $row['admission_no']; ?></td>
                                                <td class="mailbox-name"> <?php echo $row['firstname'] . " " . $row['lastname'] ?></td>
                                                <td class="mailbox-name"><?php echo $row['gender'] ?></td>
                                                <td class="mailbox-date pull-right no-print">
                                                    <button class="btn btn-default btn-xs printInv" data-student_info_id="<?php //echo $value['id'];
                                                                                                                                ?>" data-certificate_id="<?php echo $row['id']; ?>" data-toggle="tooltip" title="<?php echo $this->lang->line('print'); ?>"><i class="fa fa-print"></i></button>
                                                    <?php 
                                                    $lastrowTrn = $this->certificate_model->getfees_trnRow();
                                                    if ($lastrowTrn['id'] == $row['id']) {
                                                        ?>
                                                    <a class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>" href="<?php echo base_url(); ?>admin/certificate/fees_certicate/<?php echo $row['id'] ?>"><i class="fa fa-pencil"></i></a>
                                                        <?php
                                                    }?>
                                                    <a class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm'); ?>');" href="<?php echo base_url(); ?>admin/certificate/delete_fees_certificate/<?php echo $row['id'] ?>"><i class="fa fa-remove"></i></a>
                                                </td>
                                            </tr>
                                    <?php
                                        }
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
    var student_session_id = '<?php echo set_value('student_session_id') ?>';
    var exam_id = '<?php echo set_value('exam_id') ?>';
    getSectionByClass(class_id, section_id);


    $(document).on('change', '#class_id', function(e) {
        $('#section_id').html("");
        var class_id = $(this).val();
        getSectionByClass(class_id, 0);
    });
    <?php
    if (isset($update)) { ?>
        getSectionByClass(<?php echo $update['class_id']; ?>, 0);
        setTimeout(function() {
            $("#section_id").val(<?php echo $update['section_id']; ?>).trigger('change');
        }, 400);

        setTimeout(function() {
            $("#student_session_id").val(<?php echo $update['student_session_id']; ?>).trigger('change');
        }, 900);

    <?php } ?>

    function getSectionByClass(class_id, section_id) {

        if (class_id !== "") {
            $('#section_id').html("");
            $('#student_session_id').html("");
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
                        // if ($update['section_id'] == obj.section_id) {
                        //     sel = "selected";
                        // }
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
        var class_id = $('#class_id').val();
        var section_id = $('#section_id').val();
        var current_session_id = $('#current_session_id').val();
        $('#student_session_id').html("");
        var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
        $.ajax({
            type: "POST",
            url: base_url + "admin/certificate/getclass_students",
            data: {
                'class_id': class_id,
                'section_id': section_id,
                'session_id': current_session_id,
            },
            dataType: "json",
            beforeSend: function() {
                $('#student_session_id').addClass('dropdownloading');
            },
            success: function(data) {
                $.each(data, function(i, obj) {
                    var sel = "";
                    // if (!empty($update) && $update['student_session_id'] === obj.studentses_id) {
                    //     sel = "selected";
                    // }
                    div_data += "<option value=" + obj.studentses_id + " " + sel + ">" + obj.firstname + " " + obj.lastname + " (" + obj.roll_no + ")" + "</option>";
                });
                $('#student_session_id').append(div_data);
            },
            complete: function() {
                $('#student_session_id').removeClass('dropdownloading');
            }
        });
    });



    $(function() {
        $(document).on("click", "#btnAdd", function() {
            var lenght_div = $('#TextBoxContainer .app').length;
            var div = GetDynamicTextBox(lenght_div);
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

    function GetDynamicTextBox(value) {
        var row = "";
        row += '<div class="form-group app">';
        row += '<input type="hidden" name="i[]" value="' + value + '"/>';
        row += '<input type="hidden" name="row_id_' + value + '" value="0"/>';
        row += '<div class="col-md-12">';
        row += '<div class="form-group row">';
        row += '<label for="inputValue" class="col-md-1 control-label">Fees</label>';
        row += '<div class="col-md-4">';
        row += '<input type="text" id="fees_name[]" name="fees_name[]" class="form-control" >';

        row += '</div>';
        row += '<label for="inputKey" class="col-md-1 control-label">Amount</label>';
        row += '<div class="col-md-4">';
        row += '<input type="text"  id="amount[]" name="amount[]"  class="form-control" >';

        row += '</div>';
        row += '<div class="col-md-2"><button id="btnRemove" style="" class="btn btn-sm btn-danger" type="button"><i class="fa fa-trash"></i></button></div>';
        row += '</div>';
        row += '</div>';
        row += '</div>';
        return row;
    }

    $(document).on('click', '#btnRemove', function() {
        $(this).parents('.form-group').remove();
    });

    $("#form").on("submit", function(e) {
        e.preventDefault();
        $.ajax({
            url: "<?php echo base_url('admin/certificate/feeCertificateValid') ?>",
            type: 'POST',
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            dataType: 'json',
            beforeSend: function(data) {
                // $("#spinner").show();
                // $("#submit").attr("disabled",true);
            },
            success: function(data) {
                // $("#spinner").hide(); 
                // $("#submit").attr("disabled",false);
                if (data.error) {
                    $.each(data, function(key, value) {
                        if (value) {
                            $('#error-' + key).html(value);
                            $('#input-' + key).addClass("border-danger");
                        } else {
                            $('#error-' + key).html(" ");
                            $('#input-' + key).removeClass("border-danger");
                        }
                    });
                }
                if (data.success) {
                    $('#form .form-control').removeClass("error");
                    $('#form .error').html(" ");
                    Popup(data.response);
                }
            }
        });
    });
</script>


<script>
    $(document).on('click', '.printInv', function() {
        var certificate_id = $(this).data('certificate_id');
        
        $.ajax({
            url: '<?php echo site_url("admin/certificate/print_fee_certificate") ?>',
            type: 'post',
            data: {
                'certificate_id': certificate_id,
            },
            success: function(response) {
                Popup(response);
            }
        });
    });
    var base_url = '<?php echo base_url() ?>';

    function Popup(data, winload = false) {
        var frame1 = $('<iframe />').attr("id", "printDiv");
        frame1[0].name = "frame1";
        // frame1.css({
        //     "position": "absolute",
        //     "top": "-1000000px"
        // });
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
                window.location.href = '<?php echo site_url('admin/certificate/fees_certicate') ?>';
            }
        }, 500);

        return true;
    }
</script>