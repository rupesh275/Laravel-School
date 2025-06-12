<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.js"></script>
<style>
    .status {
        margin-left: 5px;
    }
</style>
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
                        <form role="form" action="<?php echo site_url('admin/examgroup/worksheet_mark_entry') ?>" method="post">

                            <?php echo $this->customlib->getCSRF(); ?>

                            <div class="row">
                                <div class="col-sm-4 col-lg-4 col-md-4 ">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('class'); ?></label><small class="req"> *</small>
                                        <select autofocus="" id="class_id" name="class_id" class="form-control select2">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
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
                                        <span class="text-danger"><?php echo form_error('class_id'); ?></span>
                                    </div>
                                </div>

                                <div class="col-sm-4 col-lg-4 col-md-4 ">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('section'); ?></label><small class="req"> *</small>
                                        <select id="section_id" name="section_id" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-lg-4 col-md-4 ">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('student'); ?></label><small class="req"> *</small>
                                        <select id="student_session_id" name="student_session_id" class="form-control select2">

                                        </select>
                                        <span class="text-danger"><?php echo form_error('student_session_id'); ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <input type="hidden" value="<?php echo $current_session; ?>" id="current_session_id" name="current_session_id">
                                        <button type="submit" name="search" value="search_filter" class="btn btn-primary pull-right btn-sm checkbox-toggle"> <?php echo $this->lang->line('search'); ?></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>


                    <div class="box-header ptbnull"></div>
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><i class="fa fa-users"></i> <?php echo $this->lang->line('exam'); ?> <?php echo $this->lang->line('list'); ?></h3>
                    </div>
                    <div>
                        <form action="<?php echo base_url('admin/examgroup/mark_entry_valid'); ?>" id="form" method="post">
                            <div class="box-body">
                                <div class="table-responsive no-padding">
                                    <table class="table table-striped table-bordered table-hover " cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th><?php echo "Exam"; ?></th>
                                                <th>Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbody">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="box-footer">
                                <button type="submit" class="btn btn-primary pull-right btn-sm checkbox-toggle">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>


<script type="text/javascript">
    $(document).ready(function() {
        // $.extend($.fn.dataTable.defaults, {
        //     searching: true,
        //     ordering: true,
        //     paging: false,
        //     retrieve: true,
        //     destroy: true,
        //     info: false,

        // });
        var table = $('.datatable').DataTable({
            "aaSorting": [],
            order: [
                [0, 'asc']
            ],
            rowReorder: {
                selector: 'td:nth-child(2)'
            },
            //responsive: 'false',
            dom: "Bfrtip",
            buttons: [

                {
                    extend: 'copyHtml5',
                    text: '<i class="fa fa-files-o"></i>',
                    titleAttr: 'Copy',
                    title: $('.download_label').html(),
                    exportOptions: {
                        columns: ["thead th:not(.noExport)"]
                    }
                },

                {
                    extend: 'excelHtml5',
                    text: '<i class="fa fa-file-excel-o"></i>',
                    titleAttr: 'Excel',

                    title: $('.download_label').html(),
                    exportOptions: {
                        columns: ["thead th:not(.noExport)"]
                    }
                },

                {
                    extend: 'csvHtml5',
                    text: '<i class="fa fa-file-text-o"></i>',
                    titleAttr: 'CSV',
                    title: $('.download_label').html(),
                    exportOptions: {
                        columns: ["thead th:not(.noExport)"]
                    }
                },

                {
                    extend: 'pdfHtml5',
                    text: '<i class="fa fa-file-pdf-o"></i>',
                    titleAttr: 'PDF',
                    title: $('.download_label').html(),
                    exportOptions: {
                        columns: ["thead th:not(.noExport)"]
                    }
                },

                {
                    extend: 'print',
                    text: '<i class="fa fa-print"></i>',
                    titleAttr: 'Print',
                    title: $('.download_label').html(),
                    customize: function(win) {

                        $(win.document.body).find('th').addClass('display').css('text-align', 'center');
                        $(win.document.body).find('td').addClass('display').css('text-align', 'left');
                        $(win.document.body).find('table').addClass('display').css('font-size', '14px');
                        // $(win.document.body).find('table').addClass('display').css('text-align', 'center');
                        $(win.document.body).find('h1').css('text-align', 'center');
                    },
                    exportOptions: {
                        columns: ["thead th:not(.noExport)"]
                    }
                },

                {
                    extend: 'colvis',
                    text: '<i class="fa fa-columns"></i>',
                    titleAttr: 'Columns',
                    title: $('.download_label').html(),
                    postfixButtons: ['colvisRestore']
                },
            ]
        });
    });

    var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy']) ?>';
    var class_id = '<?php echo set_value('class_id') ?>';
    var section_id = '<?php echo set_value('section_id') ?>';
    var session_id = '<?php echo set_value('session_id') ?>';
    var student_session_id = '<?php echo set_value('student_session_id') ?>';
    var exam_id = '<?php echo set_value('exam_id') ?>';
    getSectionByClass(class_id, section_id);
    getStudentsBy(section_id, student_session_id);
    // getExamByExamgroup(exam_group_id, exam_id);
    // $(document).on('change', '#exam_group_id', function(e) {
    //     $('#exam_id').html("");
    //     var exam_group_id = $(this).val();
    //     getExamByExamgroup(exam_group_id, 0);
    // });

    $(document).on('change', '#class_id', function(e) {
        $('#section_id').html("");
        var class_id = $(this).val();
        getSectionByClass(class_id, 0);
    });

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
                        if (section_id === obj.section_id) {
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

    function getStudentsBy(section_id, student_id) {
        var class_id = $('#class_id').val();
        // var section_id = $('#section_id').val();
        var current_session_id = $('#current_session_id').val();
        $('#student_session_id').html("");
        var base_url = '<?php echo base_url() ?>';
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
                    if (student_id === obj.studentses_id) {
                        sel = "selected";
                    }
                    div_data += "<option value=" + obj.studentses_id + " " + sel + ">" + obj.firstname + " " + obj.lastname + " (" + obj.roll_no + ")" + "</option>";
                });
                $('#student_session_id').append(div_data);
            },
            complete: function() {
                $('#student_session_id').removeClass('dropdownloading');
            }
        });
    }

    $(document).on('change', '#section_id', function() {
        var $section_id = $(this).val();
        getStudentsBy($section_id, 0);
    });

    $(document).on('change', '#student_session_id', function() {
        var class_id = $('#class_id').val();
        var section_id = $('#section_id').val();
        var student_session_id = $(this).val();
        $('#tbody').html(" ");
        var base_url = '<?php echo base_url() ?>';
        if (student_session_id != "") {

            $.ajax({
                type: "POST",
                url: base_url + "admin/examgroup/getExamlistByStudent",
                data: {
                    'class_id': class_id,
                    'section_id': section_id,
                    'student_session_id': student_session_id
                },
                dataType: "json",
                beforeSend: function() {
                    $('#student_session_id').addClass('dropdownloading');
                },
                success: function(data) {
                    if (data.success) {
                        $('#tbody').append(data.html);
                    }
                },
                complete: function() {
                    $('#student_session_id').removeClass('dropdownloading');
                }
            });

        }
    });

    $(document).on('submit', '#form', function(e) {

        e.preventDefault();
        var form = $(this);
        var subsubmit_button = $(this).find(':submit');
        var formdata = form.serializeArray();
        $.ajax({
            type: "POST",
            url: form.attr('action'),
            data: formdata, // serializes the form's elements.
            dataType: "JSON", // serializes the form's elements.
            beforeSend: function() {
                subsubmit_button.button('loading');
            },
            success: function(response) {
                if (response.status == 0) {
                    var message = "";
                    $.each(response.error, function(index, value) {

                        message += value;
                    });
                    errorMsg(message);

                } else {
                    successMsg(response.message);
                }
            },
            error: function(xhr) { // if error occured
                alert("<?php echo $this->lang->line('error_occured') . ", " . $this->lang->line('please_try_again') ?>");
                subsubmit_button.button('reset');
            },
            complete: function() {
                subsubmit_button.button('reset');
            }
        });
    });
</script>