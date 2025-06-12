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
                        <form id="form" action="" method="post" accept-charset="utf-8">
                            <div class="row">
                                <div class="col-sm-3 col-lg-3 col-md-3 ">
                                    <div class="form-group" style="height: 50px;">
                                        <label><?php echo $this->lang->line('session'); ?></label>
                                        <select autofocus="" id="session_id" name="session_id" class="form-control select2">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($sessionlist as $session) {
                                            ?>
                                                <option value="<?php echo $session['id'] ?>" <?php
                                                                                                if (set_value('session_id') == $session['id']) {
                                                                                                    echo "selected=selected";
                                                                                                }
                                                                                                ?><?php echo set_select('session_id', $session['id'], isset($update['session_id']) && $update['session_id'] == $session['id'] ? true : false); ?>><?php echo $session['session'] ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <span class="text-danger"><?php echo form_error('session_id'); ?></span>
                                    <div class="text-danger" id="error-session_id"></div>
                                </div>
                                <div class="col-sm-3 col-lg-3 col-md-3 ">
                                    <div class="form-group" style="height: 50px;">
                                        <label><?php echo $this->lang->line('class'); ?></label>
                                        <select autofocus="" id="class_id" name="class_id" class="form-control select2">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            // print_r($classlist);die;
                                            foreach ($classlist as $class) {
                                            ?>
                                                <option value="<?php echo $class['id'] ?>" <?php
                                                                                            if (set_value('class_id') == $class['id']) {
                                                                                                echo "selected=selected";
                                                                                            }
                                                                                            ?><?php echo set_select('class_id', $class['id'], isset($update['class_id']) && $update['class_id'] == $class['id'] ? true : false); ?>><?php echo $class['class'] ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                        <input type="hidden" name="id" value="<?php if (isset($update)) {
                                                                                    echo $update['id'];
                                                                                } ?>">
                                    </div>
                                    <span class="text-danger"><?php echo form_error('class_id'); ?></span>
                                    <div class="text-danger" id="error-class_id"></div>
                                </div>

                                <div class="col-sm-3 col-lg-3 col-md-3 ">
                                    <div class="form-group" style="height: 50px;">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('section'); ?></label>
                                        <select id="section_id" name="section_id" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        </select>
                                    </div>
                                    <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                                    <div class="text-danger" id="error-section_id"></div>
                                </div>

                                <div class="col-sm-3 col-lg-3 col-md-3 ">
                                    <div class="form-group" style="height: 50px;">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('student'); ?></label>
                                        <select id="student_id" name="student_id" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        </select>
                                    </div>
                                    <span class="text-danger"><?php echo form_error('student_id'); ?></span>
                                    <div class="text-danger" id="error-student_id"></div>
                                </div>

                                <div class="col-sm-4 col-lg-4 col-md-4 ">
                                    <div class="form-group" style="height: 50px;">
                                        <label for="exampleInputEmail1"><?php echo "Disabilty"; ?></label>
                                        <input type="text" name="disabilty" id="disabilty" class="form-control" value="<?php if (isset($update)) {
                                                                                                                            echo $update['disabilty'];
                                                                                                                        } ?>">
                                    </div>
                                    <span class="text-danger"><?php echo form_error('disabilty'); ?></span>
                                    <div class="text-danger" id="error-disabilty"></div>
                                </div>

                                <div class="col-sm-4 col-lg-4 col-md-4 ">
                                    <div class="form-group" style="height: 50px;">
                                        <label for="exampleInputEmail1"><?php echo "Disabilty Detail"; ?></label>
                                        <input type="text" name="disabilty_detail" id="disabilty_detail" class="form-control" value="<?php if (isset($update)) {
                                                                                                                                            echo $update['disabilty_detail'];
                                                                                                                                        } ?>">
                                    </div>
                                    <span class="text-danger"><?php echo form_error('disabilty_detail'); ?></span>
                                    <div class="text-danger" id="error-disabilty_detail"></div>
                                </div>

                                <div class="col-sm-4 col-lg-4 col-md-4 ">
                                    <div class="form-group" style="height: 50px;">
                                        <label for="exampleInputEmail1"><?php echo "Percentage"; ?></label>
                                        <input type="text" name="percentage" id="percentage" class="form-control" value="<?php if (isset($update)) {
                                                                                                                                echo $update['percentage'];
                                                                                                                            } ?>">
                                    </div>
                                    <span class="text-danger"><?php echo form_error('percentage'); ?></span>
                                    <div class="text-danger" id="error-percentage"></div>
                                </div>

                                <div class="col-sm-4 col-lg-4 col-md-4 ">
                                    <div class="form-group" style="height: 50px;">
                                        <label for="exampleInputEmail1"><?php echo "Certificate No"; ?></label>
                                        <input type="text" name="certificate_no" id="certificate_no" class="form-control" value="<?php if (isset($update)) {
                                                                                                                                        echo $update['certificate_no'];
                                                                                                                                    } ?>">
                                    </div>
                                    <span class="text-danger"><?php echo form_error('certificate_no'); ?></span>
                                    <div class="text-danger" id="error-certificate_no"></div>
                                </div>

                                <div class="col-sm-4 col-lg-4 col-md-4 ">
                                    <div class="form-group" style="height: 50px;">
                                        <label for="exampleInputEmail1"><?php echo "Lering Style"; ?></label>
                                        <input type="text" name="lering_style" id="lering_style" class="form-control" value="<?php if (isset($update)) {
                                                                                                                                    echo $update['lering_style'];
                                                                                                                                } ?>">
                                    </div>
                                    <span class="text-danger"><?php echo form_error('lering_style'); ?></span>
                                    <div class="text-danger" id="error-lering_style"></div>
                                </div>

                                <div class="col-sm-4 col-lg-4 col-md-4 ">
                                    <div class="form-group" style="height: 50px;">
                                        <label for="exampleInputEmail1"><?php echo "Supportive Services"; ?></label>
                                        <input type="text" name="supportive_services" id="supportive_services" class="form-control" value="<?php if (isset($update)) {
                                                                                                                                                echo $update['supportive_services'];
                                                                                                                                            } ?>">
                                    </div>
                                    <span class="text-danger"><?php echo form_error('supportive_services'); ?></span>
                                    <div class="text-danger" id="error-supportive_services"></div>
                                </div>



                                <div class="col-sm-4 col-lg-4 col-md-4 ">
                                    <div class="form-group" style="height: 50px;">
                                        <label for="exampleInputEmail1"><?php echo "UDID"; ?></label>
                                        <input type="text" name="udid" id="udid" class="form-control" value="<?php if (isset($update)) {
                                                                                                                    echo $update['udid'];
                                                                                                                } ?>">
                                    </div>
                                    <span class="text-danger"><?php echo form_error('udid'); ?></span>
                                    <div class="text-danger" id="error-udid"></div>
                                </div>


                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <input type="hidden" name="student_session_id" id="student_session_id" value="<?php if (isset($update)) {
                                                                                                                            echo $update['student_session_id'];
                                                                                                                        } ?>">

                                        <button type="submit" name="search" value="search_filter" class="btn btn-primary pull-right btn-sm checkbox-toggle" style="margin-left: 12px;"> <?php echo $this->lang->line('save'); ?></button>
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
        var session_id = $('#session_id').val();
        var class_id = $('#class_id').val();
        var section_id = $('#section_id').val();
        var student_session_id = $('#student_session_id').val();
        $('#student_id').html("");
        var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
        $.ajax({
            type: "POST",
            url: base_url + "admin/certificate/getclass_students",
            data: {
                'class_id': class_id,
                'section_id': section_id,
                'session_id': session_id,
            },
            dataType: "json",
            beforeSend: function() {
                $('#student_id').addClass('dropdownloading');
            },
            success: function(data) {
                var student_id = $('#student_id').val();
                console.log(student_session_id);
                $.each(data, function(i, obj) {
                    var sel = "";
                    if (student_session_id === obj.studentses_id) {
                        sel = "selected";
                    }
                    div_data += "<option value=" + obj.studentses_id + " " + sel + ">" + obj.firstname + " " + obj.lastname + " (" + obj.roll_no + ")" + "</option>";
                });
                $('#student_id').append(div_data);
                //$('#student_session_id').val(div_data);
            },
            complete: function() {
                $('#student_id').removeClass('dropdownloading');
            }
        });
    });

    $(document).on('change', '#student_id', function() {
        var $this = $(this);
        var student_session_id = $('#student_id').val();
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
                // $('#pob').val(data.place_of_birth);
                // $('#religion').val(data.religion);
                // $('#cast').val(data.cast);
                // if (nationality == "") {
                //     $('#nationality').val('Indian');
                // }
                // $('#student_id').append(div_data);
            },
            complete: function() {
                // $('#student_id').removeClass('dropdownloading');
            }
        });
    });

    $("#form").on("submit", function(e) {
        e.preventDefault();
        $.ajax({
            url: "<?php echo site_url('student/disability_valid') ?>",
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
                    window.location.href = '<?php echo site_url('student/disable') ?>';
                }
            }
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