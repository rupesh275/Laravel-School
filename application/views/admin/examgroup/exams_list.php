<style>
    .success_box{
        border: 2px solid #2bd014;
        background-color: #2b80123b !important;
    }
</style>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.js"></script>
<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1>
            <i class="fa fa-mortar-board"></i> <?php echo $this->lang->line('academics'); ?>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></h3>
                    </div>
                    <input type="hidden" name="current_session" id="current_session" value="<?php echo $current_session; ?>">
                    <form role="form" action="<?php echo site_url('admin/examgroup/exams_list') ?>" method="post">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="class_id"><?php echo $this->lang->line('exam_group'); ?></label><small class="req"> *</small>
                                        <select class="form-control" name="exam_group" id="exam_group">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            if (!empty($examlist)) {
                                                foreach ($examlist as $examRow) {
                                            ?>
                                                    <option value="<?php echo $examRow['id'] ?>" <?php
                                                                                                    if (set_value('exam_group') == $examRow['id']) {
                                                                                                        echo "selected = selected";
                                                                                                    }
                                                                                                    ?>><?php echo $examRow['name'] ?></option>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('exam_group'); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div> -->

        <div class="box box-info" id="box_display">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <th>Exam Group</th>
                    <th>Exam</th>
                    <th>Action</th>
                </thead>
                <tbody id="exam_list_body">
                    <?php

                    // echo "<pre>";
                    // print_r ($examlist);
                    // echo "</pre>";

                    if (!empty($examlist)) {
                        foreach ($examlist as $key => $value) {
                    ?>
                            <tr>
                                <td><?php echo $value['name']; ?></td>
                                <td> </td>
                                <td> </td>
                            </tr>
                            <?php
                            $examListArr = $this->examgroup_model->getExamByExamGroupexamlist($value['id']);
                            foreach ($examListArr as $key1 => $exam_value) {
                            ?>
                                <tr>
                                    <td><?php //echo $value['name']; 
                                        ?></td>
                                    <td><?php echo $exam_value->exam; ?></td>
                                    <td><a class="btn btn-default btn-xs first_modal" data-mainexam="<?php echo $exam_value->exam; ?>" role="button" data-toggle="modal" data-exam_id="<?php echo $exam_value->id; ?>" title="<?php echo $this->lang->line('exam_marks'); ?>" data-loading-text="<i class='fa fa-spinner fa-spin'></i>"><i class="fa fa-newspaper-o" aria-hidden="true"></i></a></td>
                                </tr>
                    <?php
                            }
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </section>
</div>


<!-- Modal -->
<div id="subjectModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-sm">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title subjectmodal_header1"></h4>
            </div>
            <div class="modal-body">
                <!-- <form role="form" id="searchStudentForm" action="<?php echo site_url('admin/examgroup/subjectstudent') ?>" method="post" class="mb10"> -->
                <form role="form" id="searchStudentSubject" action="<?php echo site_url('admin/examgroup/searchsubjectstudent') ?>" method="post" class="mb10">

                    <input type="hidden" name="dataexam_id" id="dataexam_id" value="" class="exam_id">
                    <input type="hidden" name="main_sub" value="0" class="main_sub">
                    <input type="hidden" name="subject_id" value="0" class="subject_id">
                    <input type="hidden" name="teachersubject_id" value="0" class="teachersubject_id">
                    <input type="hidden" name="class_id" value="<?php echo $class_id; ?>" id="class_id">
                    <input type="hidden" name="section_id" value="<?php echo $section_id; ?>" id="section_id">
                    <input type="hidden" name="main_sub" value="<?php echo $subject_id; ?>" id="main_sub">
                    <input type="hidden" name="session_id" value="<?php echo $current_session; ?>" id="session_id">


                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('type'); ?><small class="req"> *</small></label>
                                <select id="type" name="type" class="form-control">
                                    <option value="">Select</option>
                                    <option value="1">By Single</option>
                                    <option value="2">By All</option>
                                </select>
                            </div>
                            <!--./form-group-->
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group">
                                <!-- <button type="submit" name="search" value="search_filter" class="btn btn-primary pull-right btn-sm checkbox-toggle"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button> -->
                                <button type="submit" name="search" class="btn btn-primary pull-right btn-sm checkbox-toggle "><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="examheight100 relative">
                    <div id="examfade"></div>
                    <div id="exammodal">
                        <img id="loader" src="<?php echo base_url() ?>/backend/images/loading_blue.gif" />
                    </div>
                    <div class="marksEntryForm">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal  marks entry-->
<div id="StudentModalSubject" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title subjectmodal_header_2"> </h4>
            </div>
            <div class="modal-body">

                <div class="examheight100 relative">
                    <div id="examfade1"></div>
                    <div id="exammodal1">
                        <img id="loader" src="<?php echo base_url() ?>/backend/images/loading_blue.gif" />
                    </div>
                    <div class="marksEntryFormtwo">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="subjectmarkModal" class="modal fade modalmark" role="dialog" style="overflow:auto;">
    <div class="modal-dialog modal-xl">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"> <?php echo $this->lang->line('exam') . " " . $this->lang->line('subjects'); ?></h4>
            </div>

            <div class="modal-body">

            </div>
        </div>
    </div>
</div>
<div id="subjectmarkEntryModal" class="modal fade" role="dialog" style="overflow:auto;">
    <div class="modal-dialog modal-xl">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"> <?php echo $this->lang->line('exam') . " " . $this->lang->line('subjects'); ?></h4>
            </div>

            <div class="modal-body">

            </div>
        </div>
    </div>
</div>


<!-- Modal  marks entry-->
<div id="StudentModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title subjectmodal_header_1"> </h4>
            </div>
            <div class="modal-body">

                <div class="examheight100 relative">
                    <div id="examfade"></div>
                    <div id="exammodal">
                        <img id="loader" src="<?php echo base_url() ?>/backend/images/loading_blue.gif" />
                    </div>
                    <div class="marksEntryFormOne">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('#subjectModal').modal({
            backdrop: 'static',
            keyboard: false,
            show: false
        })

    });

    $('#subjectModal').on('shown.bs.modal', function(e) {
        var main_sub = $(e.relatedTarget).data('main_sub');
        var subject_id = $(e.relatedTarget).data('subject_id');
        var subject_name = $(e.relatedTarget).data('subject_name');
        var teachersubject_id = $(e.relatedTarget).data('teachersubject_id');
        $('.subjectmodal_header').html("").html(subject_name);
        $('.marksEntryForm').html("");
        $('.main_sub').val("").val(main_sub);
        $('.subject_id').val("").val(subject_id);
        $('.teachersubject_id').val("").val(teachersubject_id);
        $(e.currentTarget).find('input[name="subject_name"]').val(subject_name);
        var current_session = $('#current_session').val();
        $('#session_id option[value="' + current_session + '"]').prop("selected", true);

    });
    $('#examModal').on('hidden.bs.modal', function() {
        reset_exm_form();
        $("span[id$='_error']").html("");
    });

    function reset_exm_form() {
        var current_session = $('#current_session').val();
        console.log(current_session);
        $('#formadd')[0].reset();
        $("#class_id").prop("selectedIndex", 0);
        $("#section_id,#batch_id,#type").find('option:not(:first)').remove();
        $("#formadd input[name=exam_id]").val(0);
        $('#session_id').val(current_session);

    }

    $('#subjectModal').on('hidden.bs.modal', function() {
        $('.subjectmodal_header').html("");
        $('.marksEntryForm').html("");
        $('.main_sub').val("");
        $('.subject_id').val("");
        $("#searchStudentForm").find('input:text,select,textarea').val('');
        $("#searchStudentSubject").find('input:text,select,textarea').val('');
        $('#section_id').find('option').not(':first').remove();
        $('#session_id > option[selected="selected"]').removeAttr('selected');


    });

    // $(document).on('change', '#exam_group', function(e) {

    //     var exam_group = $(this).val();
    //     if (exam_group != "") {
    //         $('#exam_list_body').html("");
    //         $.ajax({
    //             type: "post",
    //             url: baseurl + "admin/examgroup/getexamList",
    //             data: {
    //                 'exam_group': exam_group
    //             },
    //             dataType: "json",
    //             success: function(data) {
    //                 $('#box_display').show();
    //                 $('#exam_list_body').html(data.exam_page);
    //             }
    //         });

    //         getClass(exam_group);
    //     }

    // });

    function getClass(examgroupId) {
        var selector = $('#class_id');
        var div_data = "";
        if (examgroupId != 0) {
            $.ajax({
                type: "POST",
                url: base_url + "admin/examgroup/getclassByexamgroupId",
                data: {
                    'examgroup_id': examgroupId
                },
                dataType: "json",
                beforeSend: function() {
                    selector.addClass('dropdownloading');
                },
                success: function(data) {
                    console.log(data);
                    $.each(data, function(i, obj) {
                        var sel = "";
                        div_data += "<option value=" + obj.id + ">" + obj.class + "</option>";
                    });
                    selector.append(div_data);
                },
                error: function(xhr) { // if error occured
                    alert("Error occured.please try again");
                },
                complete: function() {
                    selector.removeClass('dropdownloading');
                }
            });

        }
    }

    $(document).on('change', '#section_id', function(e) {
        var $this = $(this);
        var class_id = $('#class_id').val();
        var section_id = $('#section_id').val();
        var main_sub = $('#main_sub').val();
        $('#main_sub').html("");
        var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
        $.ajax({
            type: "POST",
            url: base_url + "admin/subject/getclass_subjects",
            data: {
                'class_id': class_id,
                'section_id': section_id,
            },
            dataType: "json",
            beforeSend: function() {
                $('#main_sub').addClass('dropdownloading');
            },
            success: function(data) {
                var main_sub = $('#main_sub').val();
                $.each(data, function(i, obj) {
                    var sel = "";
                    if (main_sub === obj.id) {
                        sel = "selected";
                    }
                    div_data += "<option value=" + obj.id + " " + sel + ">" + obj.name + "</option>";
                });
                $('#main_sub').append(div_data);
                //$('#student_session_id').val(div_data);
            },
            complete: function() {
                $('#main_sub').removeClass('dropdownloading');
            }
        });
    });


    $(document).on("click", ".first_modal", function(e) {
        var title_exam = $(this).data('mainexam');
        $('.subjectmodal_header1').html("").html(title_exam);
        var ids = $(this).data('exam_id');
        $("#dataexam_id").val(ids);
        $('#subjectModal').modal('show');
    });


    $(document).on('click', '.model_mark', function(e) {
        var $this = $(this);
        var class_id = $(this).data('class_id');
        var section_id = $(this).data('section_id');
        var session_id = $(this).data('session_id');
        var exam_id = $(this).data('exam_id');
        var main_sub = $(this).data('main_sub');
        var subject_id = $(this).data('subject_id');
        var teachersubject_id = $(this).data('teachersubject_id');
        var subject_name = $(this).data('subject_name');

        $.ajax({
            type: "POST",
            url: base_url + "admin/examgroup/getstudents",
            data: {
                'class_id': class_id,
                'section_id': section_id,
                'session_id': session_id,
                'exam_id': exam_id,
                'main_sub': main_sub,
                'subject_id': subject_id,
                'teachersubject_id': teachersubject_id,
                'subject_name': session_id,
            },
            dataType: "json",
            beforeSend: function() {

            },
            success: function(data) {
                //    console.log(data);
                $('.marksEntryFormOne').html(data.page);
                $('#StudentModal').modal('show');
                $('#examfade,#exammodal').css({
                    'display': 'none'
                });
                $('.marksEntryFormOne').find('.dropify').dropify();

            },
            error: function(xhr) { // if error occured  
                alert("Error occured.please try again");
                $this.button('reset');
            },
            complete: function() {
                $this.button('reset');
            }
        });
    });


    $(document).on('change', '#class_id', function(e) {
        $('#section_id').html("");
        var class_id = $('#class_id').val();
        console.log(class_id);
        var selector = $('#section_id');

        getSectionByClass(class_id, section_id, selector);
    });



    function getSectionByClass(class_id, section_id, selector) {
        if (class_id != "") {
            selector.html("");
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
                    selector.addClass('dropdownloading');
                },
                success: function(data) {
                    $.each(data, function(i, obj) {
                        var sel = "";
                        if (section_id == obj.section_id) {
                            sel = "selected";
                        }
                        div_data += "<option value=" + obj.section_id + " " + sel + ">" + obj.section + "</option>";
                    });
                    selector.append(div_data);
                },
                complete: function() {
                    selector.removeClass('dropdownloading');
                }
            });
        }
    }

    $("form#searchStudentForm").on('submit', (function(e) {
        e.preventDefault(); // avoid to execute the actual submit of the form.
        var form = $(this);
        var $this = form.find("button[type=submit]:focus");
        var url = form.attr('action');
        $.ajax({
            url: url,
            type: "POST",
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function() {
                $this.button('loading');
                $('#examfade,#exammodal').css({
                    'display': 'block'
                });
            },
            success: function(res) {

                $('#examfade,#exammodal').css({
                    'display': 'none'
                });

                if (res.status == "0") {
                    $('.marksEntryForm').html('');
                    var message = "";
                    $.each(res.error, function(index, value) {

                        message += value;
                    });
                    errorMsg(message);
                } else {
                    $('.marksEntryForm').html(res.page);

                    $('.marksEntryForm').find('.dropify').dropify();

                }
            },
            error: function(xhr) { // if error occured
                alert("Error occured.please try again");
                $this.button('reset');
                $('#examfade,#exammodal').css({
                    'display': 'none'
                });
                // setTimeout(function() {
                //     history.go(0);
                // }, 3000);
            },
            complete: function() {
                $this.button('reset');
                $('#examfade,#exammodal').css({
                    'display': 'none'
                });
            }

        });
    }));

    $(document).on('click', '.attendance_chk', function() {
        if ($(this).prop("checked") == true) {
            console.log("Checkbox is checked.");

            $(this).closest('tr').find('.marksssss').val("0");
            $(this).closest('tr').find('.marksssss').prop("readonly", true);

        } else if ($(this).prop("checked") == false) {
            $(this).closest('tr').find('.marksssss').val("");
            $(this).closest('tr').find('.marksssss').prop("readonly", false);
        }
    });
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $(document).on('click', "#btnSubmit", function(event) {

            //stop submit the form, we will post it manually.
            event.preventDefault();
            var file_data = $('#my-file-selector').prop('files')[0];
            var form_data = new FormData();
            form_data.append('file', file_data);

            $.ajax({
                url: baseurl + "/admin/examgroup/uploadfile",
                type: 'POST',
                dataType: 'JSON',
                data: form_data,
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {

                    $('#examfade,#exammodal').css({
                        'display': 'block'
                    });
                },
                success: function(data) {
                    $('#fileUploadForm')[0].reset();
                    if (data.status == "0") {
                        var message = "";
                        $.each(data.error, function(index, value) {
                            message += value;
                        });
                        errorMsg(message);
                    } else {
                        var arr = [];

                        $.each(data.student_marks, function(index) {
                            var s = JSON.parse(data.student_marks[index]);
                            arr.push({
                                roll_no: s.roll_no,
                                attendence: s.attendence,
                                marks: s.marks,
                                note: s.note
                            });

                        });
                        //===============

                        $.each(arr, function(index, value) {
                            var row = $('.marksEntryFormOne').find('table tbody').find('tr#roll_no_' + value.roll_no);
                            row.find("td input.marksssss").val(value.marks);
                            row.find("td input.note").val(value.note);
                            if (value.attendence == 1) {
                                row.find("td input.attendance_chk").prop("checked", true);
                            } else {
                                row.find("td input.attendance_chk").prop("checked", false);
                            }
                        });

                        //=================
                    }
                },
                error: function(xhr) { // if error occured
                    alert("Error occured.please try again");
                    $('#examfade,#exammodal').css({
                        'display': 'none'
                    });
                },
                complete: function() {
                    $('#fileUploadForm')[0].reset();
                    $('#examfade,#exammodal').css({
                        'display': 'none'
                    });
                }

            });

        });

        $(document).on('click', '.exam_status', function() {
            var $this = $(this);
            var exam_id = $(this).data('exam_id');
            var statusTxt = $(this).text();
            if (statusTxt == 'L') {
                var status = 0;
            } else {
                var status = 1;
            }

            $.ajax({
                type: "POST",
                url: base_url + "admin/examgroup/update_examstatus",
                data: {
                    'exam_id': exam_id,
                    'status': status,
                },
                dataType: "json",
                beforeSend: function() {},
                success: function(data) {
                    console.log(data);
                    if (statusTxt == 'L') {
                        $(".status_" + data.exam_id).text('U');
                        // $(".status_" + data.exam_id).attr('data-status', 0);
                    } else {
                        $(".status_" + data.exam_id).text('L');
                        // $(".status_" + data.exam_id).attr('data-status', 1);
                    }
                },
                error: function(xhr) { // if error occured   
                    alert("Error occured.please try again");
                    $this.button('reset');
                },
                complete: function() {
                    // setTimeout(function() {
                    //     location.reload(true);
                    // }, 3000);
                }
            });
        });



        $(document).on('click', '.attendance_chk_two', function() {
            if ($(this).prop("checked") == true) {
                console.log("Checkbox is checked.");

                $(this).closest('tr').find('.markss').val("0");
                $(this).closest('tr').find('.markss').prop("readonly", true);
                $(this).closest('tr').find('.note').val("AB").trigger("change");

            } else if ($(this).prop("checked") == false) {
                $(this).closest('tr').find('.markss').val("");
                $(this).closest('tr').find('.markss').prop("readonly", false);
                $(this).closest('tr').find('.note').val("").trigger("change");
            }
        });
    });

    function onsubmit() {
        $.ajax({
            type: "POST",
            dataType: 'Json',
            url: "<?php echo base_url(); ?>admin/examgroup/entrysubjectmarks",
            data: $('#assign_form2222').serialize(), // serializes the form's elements.
            beforeSend: function() {
                // $this.button('loading');

            },
            success: function(data) {
                // $this.button('reset');
                if (data.status == "fail") {
                    var message = "";
                    $.each(data.error, function(index, value) {

                        message += value;
                    });
                    errorMsg(message);
                } else {
                    successMsg(data.message);
                    // $('#StudentModal').modal('hide');
                    // $('#subjectmarkEntryModal').modal('hide');
                }
            },
            complete: function() {
                // $this.button('reset');
            }
        });
    }


    $(document).on('click', '#save_marks', function() {
        // submit form
        var exam_studentid = $(this).data('exam_studentid');
        console.log("submit form");
        onsubmit();
        $('.examstudentId' + exam_studentid).addClass('success_box');
        // $('#assign_form2222').submit();

    });

    $(document).on('keyup', '.markss', function() {
        var max_mark = $(this).data('max_marks');
        var min_mark = $(this).data('min_marks');
        var regex = /^\d*\.?\d*$/;
        var value = $(this).val();
        if ((parseFloat(value) > parseFloat(max_mark)) || !regex.test(value)) {
            $(this).val("");
        } else if ((parseFloat(value) < parseFloat(min_mark)) || parseFloat(value) < 0) {
            $(this).val("");
        }
    });
</script>

<script type="text/javascript">
    $.validator.addMethod("uniqueUserName", function(value, element, options) {
            var max_mark = $('#max_mark').val();
            //we need the validation error to appear on the correct element
            return parseFloat(value) <= parseFloat(max_mark);
        },
        "Invalid Marks"
    );
    $(document).ready(function() {


        $(document).on('submit', 'form#assign_form1111', function(event) {
            event.preventDefault();
            $('form#assign_form1111').validate({
                debug: true,
                errorClass: 'error text text-danger',
                validClass: 'success',
                errorElement: 'span',
                highlight: function(element, errorClass, validClass) {
                    $(element).parent().addClass(errorClass);
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).parent().removeClass(errorClass);
                }
            });

            $('.marksssss').each(function() {
                $(this).rules("add", {
                    required: true,
                    uniqueUserName: true,
                    messages: {
                        required: "Required",
                    }
                });
            });



            // test if form is valid
            if ($('form#assign_form1111').validate().form()) {
                var $this = $('.allot-fees');
                $.ajax({
                    type: "POST",
                    dataType: 'Json',
                    url: $("#assign_form1111").attr('action'),
                    data: $("#assign_form1111").serialize(), // serializes the form's elements.
                    beforeSend: function() {
                        $this.button('loading');

                    },
                    success: function(data) {
                        $this.button('reset');
                        if (data.status == "fail") {
                            var message = "";
                            $.each(data.error, function(index, value) {

                                message += value;
                            });
                            errorMsg(message);
                        } else {
                            successMsg(data.message);
                            $('#StudentModal').modal('hide');
                            $('#subjectmarkEntryModal').modal('hide');
                        }
                    },
                    complete: function() {
                        $this.button('reset');
                    }
                });
            } else {
                console.log("does not validate");
            }

        })


        // // initialize the validator      

    });


    $("form#searchStudentSubject").on('submit', (function(e) {
        e.preventDefault(); // avoid to execute the actual submit of the form.
        var form = $(this);
        var type = $('#type').val();
        if (type != "") {
            if (type == 1) {
                var url = form.attr('action');
            } else if (type = 2) {
                var url = "<?php echo base_url('admin/examgroup/getSubjectByClass') ?>";
            }
        } else {
            errorMsg("Please Select Type");
            return false;
        }
        var $this = form.find("button[type=submit]:focus");
        var recordid = $this.data('recordid');
        var class_id = $('#class_id').val();
        var section_id = $('#section_id').val();
        var main_sub = $('#main_sub').val();
        var session_id = $('#session_id').val();

        $.ajax({
            url: url,
            type: "POST",
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function() {
                $this.button('loading');
                $('#examfade,#exammodal').css({
                    'display': 'block'
                });
            },
            success: function(data) {
                $('#examfade,#exammodal').css({
                    'display': 'none'
                });
                if (type == 1) {
                    if (data.status == "0") {
                        $('.marksEntryForm').html('');
                        var message = "";
                        $.each(data.error, function(index, value) {

                            message += value;
                        });
                        errorMsg(message);
                    } else {
                        $('#subjectModal').modal('hide');
                        $('#subjectmarkModal .modal-body').html(data.subject_page);
                        $('#subjectmarkModal').modal('show');
                    }
                } else {
                    $('#subjectModal').modal('hide');
                    $('#subjectmarkEntryModal .modal-body').html(data.subject_page);
                    $('#subjectmarkEntryModal').modal('show');
                }



            },
            error: function(xhr) { // if error occured
                alert("Error occured.please try again");
                $this.button('reset');
                $('#examfade,#exammodal').css({
                    'display': 'none'
                });
                // setTimeout(function() {
                //     history.go(0);
                // }, 3000);
            },
            complete: function() {
                $this.button('reset');

            }

        });
    }));
</script>