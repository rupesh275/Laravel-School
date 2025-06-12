<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-user-plus"></i> <?php echo $this->lang->line('student_information'); ?> <small><?php echo $this->lang->line('student1'); ?></small>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box removeboxmius">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>
                    <div class="box-body">
                        <form action="<?php echo site_url('student/roll_list') ?>" method="post" class="class_search_formss">
                            <?php if ($this->session->flashdata('msg')) { ?> <div class="alert alert-success"> <?php echo $this->session->flashdata('msg') ?> </div> <?php } ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <!-- <div class="row"> -->
                                    <?php echo $this->customlib->getCSRF(); ?>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('class'); ?></label> <small class="req"> *</small>
                                            <select autofocus="" id="class_id" name="class_id" class="form-control">
                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                <?php
                                                $count = 0;
                                                foreach ($classlist as $class) {
                                                ?>
                                                    <option value="<?php echo $class['id'] ?>" <?php if (set_value('class_id') == $class['id']) {
                                                                                                    echo "selected=selected";
                                                                                                }
                                                                                                ?>><?php echo $class['class'] ?></option>
                                                <?php
                                                    $count++;
                                                }
                                                ?>
                                            </select>
                                            <span class="text-danger" id="error_class_id"></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('division'); ?></label>
                                            <select id="section_id" name="section_id" class="form-control">
                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            </select>
                                            <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                                        </div>
                                    </div>
                                    <!-- <div class="col-sm-12">
                                            <div class="form-group">
                                                <button type="submit" name="search" value="search_filter" class="btn btn-primary btn-sm pull-right checkbox-toggle"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                            </div>
                                        </div> -->
                                    <!-- </div> -->
                                </div>
                                <!--./col-md-6-->
                                <!-- <div class="col-md-6"> -->
                                <!-- <div class="row"> -->
                                <!-- <div class="col-sm-12">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('search_by_keyword'); ?></label>
                                                <input type="text" name="search_text" id="search_text" class="form-control" value="<?php echo set_value('search_text'); ?>" placeholder="<?php echo $this->lang->line('search_by_student_name'); ?>">
                                            </div>
                                        </div> -->
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <button type="submit" name="search" value="search_filter" class="btn btn-primary btn-sm pull-right checkbox-toggle"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                        <!-- <button type="submit" name="search" value="search_full" class="btn btn-primary pull-right btn-sm checkbox-toggle"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button> -->
                                    </div>
                                </div>
                        </form>
                    </div>
                </div>
                <!--./col-md-6-->
            </div>
            <!--./row-->
        </div>


        <div class="nav-tabs-custom border0 navnoshadow">
            <div class="box-header ptbnull"></div>
            <ul class="nav nav-tabs">
                <!-- <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true"><i class="fa fa-list"></i> <?php echo $this->lang->line('list'); ?> <?php echo $this->lang->line('view'); ?></a></li>
                <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false"><i class="fa fa-newspaper-o"></i> <?php echo $this->lang->line('details'); ?> <?php echo $this->lang->line('view'); ?></a></li> -->
            </ul>
            <style>
                .table,
                td {
                    font-size: 12px;
                }
            </style>
            <div class="tab-content">
                <div class="tab-pane active table-responsive no-padding" id="tab_1">
                    <?php if(isset($classess))
                     {
                        $title = "Student roll list for the class " . $classess['code']."-".$section['section']." [".$session['session']."]";
                     }
                     else
                     {
                        $title = "Student roll list";
                     }
                     ?>
                    <table class="table table-striped table-bordered table-hover student-listss" data-export-title="<?php echo $title; ?>">
                        <thead>
                            <tr>
                                <th><?php echo $this->lang->line('admission_no'); ?></th>
                                <th><?php echo $this->lang->line('class'); ?></th>
                                <th><?php echo $this->lang->line('roll_no'); ?></th>

                                <th><?php echo $this->lang->line('student_name'); ?></th>
                                <th><?php echo "House Color"; ?></th>
                                <th style="width: 59px"><?php echo $this->lang->line('date_of_birth'); ?></th>
                                <th><?php echo $this->lang->line('gender'); ?></th>
                                <?php if ($sch_setting->category) { ?>
                                    <th><?php echo $this->lang->line('category'); ?></th>
                                <?php } ?>
                                <th><?php echo $this->lang->line('religion'); ?></th>
                                <th><?php echo $this->lang->line('cast'); ?></th>
                                <?php if ($sch_setting->father_name) { ?>
                                    <th><?php echo $this->lang->line('father_name'); ?></th>
                                <?php } ?>
                                <th><?php echo $this->lang->line('mother_name'); ?></th>
                                <?php if ($sch_setting->mobile_no) { ?>
                                    <th><?php echo $this->lang->line('mobile_no'); ?></th>
                                <?php } ?>
                                <th><?php echo "Email ID"; ?></th>
                                <th><?php echo "Aadhar No"; ?></th>
                                
                                <th><?php echo "SARAL ID"; ?></th>
                                <th><?php echo "APAAR ID"; ?></th>
                                <th><?php echo "PEN NO."; ?></th>
                                <th style="text-align: center;"><?php echo $this->lang->line('address'); ?></th>
                                <!-- <th class="text-right noExport"><?php //echo $this->lang->line('action'); 
                                                                        ?></th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($studentList)) {
                                foreach ($studentList as  $student) {

                                    // echo "<pre>";
                                    // print_r ($student);
                                    // echo "</pre>";

                            ?>
                                    <tr>
                                        <td><?php echo $student['admission_no']; ?></td>
                                        <td><?php echo $student['code'] . " " . $student['section']; ?></td>
                                        <td><?php echo $student['roll_no']; ?></td>
                                        <td><?php echo $this->customlib->getFullName($student['firstname'], $student['middlename'], $student['lastname'], $sch_setting->middlename, $sch_setting->lastname); ?></td>
                                        <td><?php echo $student['house_name']; ?></td>
                                        <td><?php echo $this->customlib->dateformat($student['dob']);; ?></td>
                                        <td><?php echo $student['gender']; ?></td>
                                        <td><?php echo $student['category']; ?></td>
                                        <td><?php echo $student['religion']; ?></td>
                                        <td><?php echo $student['cast']; ?></td>
                                        <td><?php echo $student['father_name']; ?></td>
                                        <td><?php echo $student['mother_name']; ?></td>
                                        <td><?php echo $student['mobileno']; ?></td>
                                        <td><?php echo $student['email']; ?></td>
                                        <td><?php echo $student['adhar_no']; ?></td>
                                        <td><?php echo $student['dep_student_id']; ?></td>
                                        <td><?php echo $student['aapar_id']; ?></td>
                                        <td><?php echo $student['uid_no']; ?></td>

                                        <td style="text-align: left;"><?php echo !empty($student['current_address']) ? $student['current_address'] : $student['permanent_address']; ?></td>
                                        <!-- <td>
                                            <div class="dropdown">
                                                <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                    Action
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                                    <li><a href="<?php echo base_url('student/edit/' . $student['id']); ?>">Edit</a></li>
                                                    <li><a href="<?php echo base_url('studentfee/addfee/' . $student['student_session_id']); ?>">Add Fees</a></li>
                                                </ul>
                                            </div>
                                        </td> -->
                                    </tr>
                            <?php
                                }
                            } ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
</div>
<!--./box box-primary -->
<?php
//  }
?>
</div>
</div>
<div class="modal" id="mySiblingModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title modal_sibling_title"></h4>
            </div>
            <div class="modal-body modal_sibling_body">
                <div class="form-horizontal">
                    <input type="hidden" name="current_student_id" class="current_student_id" value="0">
                    <div class="sibling_content">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <div class="sibling_msg">
                                </div>
                                <label for="inputEmail3" class="col-sm-2 control-label"><?php echo $this->lang->line('class'); ?></label>
                                <div class="col-sm-10">
                                    <select id="sibiling_class_id" name="sibiling_class_id" class="form-control">
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php
                                        foreach ($classlist as $class) {
                                        ?>
                                            <option value="<?php echo $class['id'] ?>" <?php
                                                                                        if (set_value('sibiling_class_id') == $class['id']) {
                                                                                            echo "selected=selected";
                                                                                        }
                                                                                        ?>><?php echo $class['class'] ?></option>
                                        <?php
                                            $count++;
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputPassword3" class="col-sm-2 control-label"><?php echo $this->lang->line('section'); ?></label>
                                <div class="col-sm-10">
                                    <select id="sibiling_section_id" name="sibiling_section_id" class="form-control">
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    </select>

                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputPassword3" class="col-sm-2 control-label"><?php echo $this->lang->line('student'); ?>
                                </label>

                                <div class="col-sm-10">
                                    <select id="sibiling_student_id" name="sibiling_student_id" class="form-control">
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    </select>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="clear: both;">
                <button type="button" class="btn btn-primary btn-sm add_sibling" id="load" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Processing"><i class="fa fa-user"></i> <?php echo $this->lang->line('add'); ?></button>
            </div>
        </div>
    </div>
</div>
</section>
</div>


<div class="modal fade" id="studentinfoModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title title text-center "> Student Detail</h4>
            </div>
            <form id="form">
                <div class="modal-body pb0">
                    <div class="form-horizontal balanceformpopup">
                        <div class="box box-primary">
                            <div class="box-body box-profile">
                                <?php //if ($sch_setting->student_photo) {
                                ?>
                                <img class="profile-user-img img-responsive img-circle" src="<?php
                                                                                                //if (!empty($student["image"])) {
                                                                                                //  echo base_url() . $student["image"];
                                                                                                //} else {

                                                                                                //   if ($student['gender'] == 'Female') {
                                                                                                echo base_url() . "uploads/student_images/default_female.jpg";
                                                                                                // } else {
                                                                                                // echo base_url() . "uploads/student_images/default_male.jpg";
                                                                                                // }
                                                                                                //}
                                                                                                ?>" alt="User profile picture">
                                <?php //} 
                                ?>
                                <h3 class="profile-username text-center"></h3>

                                <ul class="list-group list-group-unbordered">

                                    <li class="list-group-item listnoback">
                                        <b><?php echo $this->lang->line('admission_no'); ?></b> <a class="pull-right text-aqua admission_no" id="admission_no"></a>
                                    </li>
                                    <?php
                                    //if ($sch_setting->roll_no) {
                                    ?>
                                    <li class="list-group-item listnoback">
                                        <b><?php echo $this->lang->line('roll_no'); ?></b> <a class="pull-right text-aqua roll_no"><?php //echo $student['roll_no']; 
                                                                                                                                    ?></a>
                                    </li>
                                    <?php
                                    //} 
                                    ?>
                                    <li class="list-group-item listnoback">
                                        <b><?php echo $this->lang->line('class'); ?></b> <a class="pull-right text-aqua class"><?php //echo $student['class'] . " (" . $session . ")"; 
                                                                                                                                ?></a>
                                    </li>
                                    <li class="list-group-item listnoback">
                                        <b><?php echo $this->lang->line('section'); ?></b> <a class="pull-right text-aqua section"><?php //echo $student['section']; 
                                                                                                                                    ?></a>
                                    </li>
                                    <?php //if ($sch_setting->rte) { 
                                    ?>
                                    <li class="list-group-item listnoback">
                                        <b><?php echo $this->lang->line('rte'); ?></b> <a class="pull-right text-aqua"><?php //echo $student['rte']; 
                                                                                                                        ?></a>
                                    </li>
                                    <?php //} 
                                    ?>
                                    <li class="list-group-item listnoback">
                                        <b><?php echo $this->lang->line('gender'); ?></b> <a class="pull-right text-aqua gender"><?php //echo $this->lang->line(strtolower($student['gender'])); 
                                                                                                                                    ?></a>
                                    </li>
                                    <li class="list-group-item listnoback">
                                        <b><?php echo $this->lang->line('status'); ?></b>
                                        <select name="status" id="status" name="status" class="form-control">
                                            <option value="">Select</option>
                                            <?php
                                            if (!empty($status_result)) {
                                                foreach ($status_result as  $status) {
                                            ?>
                                                    <option value="<?php echo $status['id']; ?>"><?php echo $status['student_status']; ?></option>
                                            <?php
                                                }
                                            } ?>
                                        </select>
                                    </li>
                                    <li class="list-group-item listnoback">
                                        <b><?php echo "Remark"; ?></b>
                                        <textarea name="remark" id="remark" class="form-control" cols="0" rows="3"></textarea>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="student_id" id="student_id" value="">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><?php echo $this->lang->line('cancel'); ?></button>
                    <button type="submit" class="btn cfees save_button" id="load" data-action="collect" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Processing"> <?php echo $this->lang->line('save'); ?> </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    function getSectionByClass(class_id, section_id) {
        if (class_id != "" && section_id != "") {
            $('#section_id').html("");
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {
                    'class_id': class_id
                },
                dataType: "json",
                success: function(data) {
                    $.each(data, function(i, obj) {
                        var sel = "";
                        if (section_id == obj.section_id) {
                            sel = "selected";
                        }
                        div_data += "<option value=" + obj.section_id + " " + sel + ">" + obj.section + "</option>";
                    });
                    $('#section_id').append(div_data);
                }
            });
        }
    }
    $(document).ready(function() {
        var class_id = $('#class_id').val();
        var section_id = '<?php echo set_value('section_id') ?>';
        getSectionByClass(class_id, section_id);
        $(document).on('change', '#class_id', function(e) {
            $('#section_id').html("");
            var class_id = $(this).val();
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {
                    'class_id': class_id
                },
                dataType: "json",
                success: function(data) {
                    $.each(data, function(i, obj) {
                        div_data += "<option value=" + obj.section_id + ">" + obj.section + "</option>";
                    });
                    $('#section_id').append(div_data);
                }
            });
        });
    });
</script>
<script>
    $(document).ready(function() {
        emptyDatatable('student-list', 'data');
    });
</script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#mySiblingModal').modal({
            backdrop: 'static',
            keyboard: false,
            show: false
        });

        table = $('.student-listss').DataTable({
            // "scrollX": true,
            dom: 'Bfrtip',
            buttons: [{
                    extend: 'copy',
                    text: '<i class="fa fa-files-o"></i>',
                    titleAttr: 'Copy',
                    className: "btn-copy",
                    title: $('.student-list').data("exportTitle"),
                    exportOptions: {
                        columns: ["thead th:not(.noExport)"]
                    }
                },
                {
                    extend: 'excel',
                    text: '<i class="fa fa-file-excel-o"></i>',
                    titleAttr: 'Excel',
                    className: "btn-excel",
                    title: $('.student-list').data("exportTitle"),
                    exportOptions: {
                        columns: ["thead th:not(.noExport)"]
                    }
                },
                {
                    extend: 'csv',
                    text: '<i class="fa fa-file-text-o"></i>',
                    titleAttr: 'CSV',
                    className: "btn-csv",
                    title: $('.student-list').data("exportTitle"),
                    exportOptions: {
                        columns: ["thead th:not(.noExport)"]
                    }
                },
                {
                    extend: 'pdf',
                    text: '<i class="fa fa-file-pdf-o"></i>',
                    titleAttr: 'PDF',
                    className: "btn-pdf",
                    title: $('.student-list').data("exportTitle"),
                    exportOptions: {
                        columns: ["thead th:not(.noExport)"]
                    },

                },
                {
                    extend: 'print',
                    text: '<i class="fa fa-print"></i>',
                    titleAttr: 'Print',
                    className: "btn-print",
                    title: $('.student-list').data("exportTitle"),
                    customize: function(win) {

                        $(win.document.body).find('th').addClass('display').css('text-align', 'center');
                        // $(win.document.body).find('table').addClass('display').css('font-size', '14px');
                        $(win.document.body).find('h1').css('text-align', 'center');
                        $(win.document.body).find('td').css('text-align', 'left');
                    },
                    exportOptions: {
                        columns: ["thead th:not(.noExport)"]

                    }

                }
            ],
            "order": [
                [2, 'asc']
            ],
        });

        $("form.class_search_form button[type=submit]").click(function() {
            $("button[type=submit]", $(this).parents("form")).removeAttr("clicked");
            $(this).attr("clicked", "true");
        });

        $(document).on('submit', '.class_search_form', function(e) {
            e.preventDefault(); // avoid to execute the actual submit of the form.
            var $this = $("button[type=submit][clicked=true]");
            var form = $(this);
            var url = form.attr('action');
            var form_data = form.serializeArray();
            form_data.push({
                name: 'search_type',
                value: $this.attr('value')
            });
            $.ajax({
                url: url,
                type: "POST",
                dataType: 'JSON',
                data: form_data, // serializes the form's elements.
                beforeSend: function() {
                    $('[id^=error]').html("");
                    $this.button('loading');
                    resetFields($this.attr('value'));
                },
                success: function(response) { // your success handler

                    if (!response.status) {
                        $.each(response.error, function(key, value) {
                            $('#error_' + key).html(value);
                        });
                    } else {



                        if ($.fn.DataTable.isDataTable('.student-list')) { // if exist datatable it will destrory first
                            $('.student-list').DataTable().destroy();
                        }
                        table = $('.student-list').DataTable({
                            // "scrollX": true,
                            dom: 'Bfrtip',
                            buttons: [{
                                    extend: 'copy',
                                    text: '<i class="fa fa-files-o"></i>',
                                    titleAttr: 'Copy',
                                    className: "btn-copy",
                                    title: $('.student-list').data("exportTitle"),
                                    exportOptions: {
                                        columns: ["thead th:not(.noExport)"]
                                    }
                                },
                                {
                                    extend: 'excel',
                                    text: '<i class="fa fa-file-excel-o"></i>',
                                    titleAttr: 'Excel',
                                    className: "btn-excel",
                                    title: $('.student-list').data("exportTitle"),
                                    exportOptions: {
                                        columns: ["thead th:not(.noExport)"]
                                    }
                                },
                                {
                                    extend: 'csv',
                                    text: '<i class="fa fa-file-text-o"></i>',
                                    titleAttr: 'CSV',
                                    className: "btn-csv",
                                    title: $('.student-list').data("exportTitle"),
                                    exportOptions: {
                                        columns: ["thead th:not(.noExport)"]
                                    }
                                },
                                {
                                    extend: 'pdf',
                                    text: '<i class="fa fa-file-pdf-o"></i>',
                                    titleAttr: 'PDF',
                                    className: "btn-pdf",
                                    title: $('.student-list').data("exportTitle"),
                                    exportOptions: {
                                        columns: ["thead th:not(.noExport)"]
                                    },

                                },
                                {
                                    extend: 'print',
                                    text: '<i class="fa fa-print"></i>',
                                    titleAttr: 'Print',
                                    className: "btn-print",
                                    title: $('.student-list').data("exportTitle"),
                                    customize: function(win) {

                                        $(win.document.body).find('th').addClass('display').css('text-align', 'center');
                                        $(win.document.body).find('table').addClass('display').css('font-size', '14px');
                                        $(win.document.body).find('h1').css('text-align', 'center');
                                    },
                                    exportOptions: {
                                        columns: ["thead th:not(.noExport)"]

                                    }

                                }
                            ],


                            "language": {
                                processing: '<i class="fa fa-spinner fa-spin fa-1x fa-fw"></i><span class="sr-only">Loading...</span> '
                            },
                            "pageLength": 100,
                            "processing": true,
                            "serverSide": true,
                            "order": [
                                [1, 'asc']
                            ],
                            "ajax": {
                                "url": baseurl + "student/dtstudentlistsCont",
                                "dataSrc": 'data',
                                "type": "POST",
                                'data': response.params,

                            },
                            "drawCallback": function(settings) {

                                $('.detail_view_tab').html("").html(settings.json.student_detail_view);
                            }

                        });



                        //=======================
                    }
                },
                error: function() { // your error handler
                    $this.button('reset');
                },
                complete: function() {
                    $this.button('reset');
                }
            });

        });

    });

    function resetFields(search_type) {

        if (search_type == "search_full") {
            $('#class_id').prop('selectedIndex', 0);
            $('#section_id').find('option').not(':first').remove();
        } else if (search_type == "search_filter") {

            $('#search_text').val("");
        }
    }

    $(document).on('click', '.add_sibling', function() {
        var student_id = $('#sibiling_student_id').val();
        var current_student_id = $('.current_student_id').val();
        if (student_id.length == '') {
            $('.sibling_msg').html("<div class='alert alert-danger text-center'> <?php echo $this->lang->line('no_student_selected'); ?> </div>");

        } else {
            var $this = $(this);

            $.ajax({
                type: "post",
                url: baseurl + "student/addsibling",
                data: {
                    'student_id': student_id,
                    'current_student_id': current_student_id
                },
                dataType: "json",
                beforeSend: function() {
                    $this.button('loading');
                },
                success: function(data) {
                    console.log(data);
                    $('#sibling_name').text("Sibling: " + data.full_name);
                    $('#sibling_name_next').val(data.firstname + " " + data.lastname);
                    $('#sibling_id').val(student_id);
                    $('#father_name').val(data.father_name);
                    $('#father_phone').val(data.father_phone);
                    $('#father_occupation').val(data.father_occupation);
                    $('#mother_name').val(data.mother_name);
                    $('#mother_phone').val(data.mother_phone);
                    $('#mother_occupation').val(data.mother_occupation);
                    $('#guardian_name').val(data.guardian_name);
                    $('#guardian_relation').val(data.guardian_relation);
                    $('#guardian_address').val(data.guardian_address);
                    $('#guardian_phone').val(data.guardian_phone);
                    $('#state').val(data.state);
                    $('#city').val(data.city);
                    $('#pincode').val(data.pincode);
                    $('#current_address').val(data.current_address);
                    $('#permanent_address').val(data.permanent_address);
                    $('#guardian_occupation').val(data.guardian_occupation);
                    $("input[name=guardian_is][value='" + data.guardian_is + "']").prop("checked", true);
                    $('#mySiblingModal').modal('hide');
                },
                complete: function() {
                    $this.button('reset');
                }
            });

        }

    });

    $(document).on("click", ".view_modal", function() {
        var $this = $(this);
        var student_id = $(this).data('student_id');
        $('#status').val('').change();
        $('#remark').val('');
        $.ajax({
            type: "post",
            url: baseurl + "student/getstudentdata",
            data: {
                'student_id': student_id,
            },
            dataType: "json",
            beforeSend: function() {
                // $this.button('loading');
            },
            success: function(data) {
                // console.log(data.datas);
                $('.profile-username').text(data.full_name);
                $('.admission_no').text(data.admission_no);
                $('.roll_no').text(student_id);
                $('.class').text(data.class);
                $('.section').text(data.section);
                $('.rte').text(data.rte);
                $('.gender').text(data.gender);
                $('#student_id').val(data.id);

                if (data.datas != null) {
                    $('#status').val(data.datas.status).change();
                    $('#remark').val(data.datas.remark);
                }

            },
            complete: function() {
                // $this.button('reset');
            }
        });
        $('#studentinfoModal').modal('show');

    });


    $("#form").on("submit", function(e) {
        e.preventDefault();
        $.ajax({
            url: baseurl + "student/save_student_status",
            type: 'POST',
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            dataType: 'json',
            beforeSend: function(data) {
                // $this.button('loading');
            },
            success: function(data) {
                $("#spinner").hide();
                $("#submit").attr("disabled", false);
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
                if (data.status == "success") {
                    $('#form .form-control').removeClass("error");
                    $('#form .error').html(" ");
                    $('#studentinfoModal').modal('hide');
                    successMsg(data.message);
                }
            }
        });
    });
</script>