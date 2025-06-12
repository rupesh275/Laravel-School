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
                        <form role="form" action="<?php echo site_url('admin/principal_verify') ?>" method="post">

                            <?php echo $this->customlib->getCSRF(); ?>

                            <div class="row">

                                <div class="col-sm-4 col-lg-4 col-md-4 ">
                                    <div class="form-group" style="height: 74px;">
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
                                    </div>
                                    <span class="text-danger"><?php echo form_error('class_id'); ?></span>
                                </div>

                                <div class="col-sm-4 col-lg-4 col-md-4 ">
                                    <div class="form-group" style="height: 74px;">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('section'); ?></label><small class="req"> *</small>
                                        <select id="section_id" name="section_id" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        </select>
                                    </div>
                                    <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                                </div>


                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <button type="submit" name="search" value="search_filter" class="btn btn-primary pull-right btn-sm checkbox-toggle" style="margin-left: 12px;"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                        <!-- <button type="submit" name="search" value="search_filter" class="btn btn-primary pull-right btn-sm checkbox-toggle" style="margin-left: 12px;"><i class="fa fa-check"></i> <?php echo $this->lang->line('approve'); ?></button>
                                        <button type="button" name="search" value="search_filter" class="btn btn-primary pull-right btn-sm checkbox-toggle unapprove"><i class="fa fa-ban"></i> <?php echo "Unapprove"; ?></button> -->
                                    </div>
                                </div>
                            </div>
                        </form>


                    </div>

                </div>


            </div>
        </div>

        <div class="box removeboxmius">
            <?php
            $i = 0;
            if (!empty($class_section_list)) {
            ?>
                <div class="box-header ptbnull">
                    <h3 class="box-title titlefix"><i class="fa fa-money"></i> <?php echo $this->lang->line('class'); ?> <?php echo $this->lang->line('wise') . "-" . $this->lang->line('report'); ?></h3>
                </div>
                <?php
                foreach ($class_section_list as $class) {
                    $count = $this->examgroupstudent_model->getClassExamCount($class['class_id'], $class['section_id']);
                    if ($count > 0) {
                        $exams = $this->examgroupstudent_model->getClassExams($class['class_id'], $class['section_id']);
                        $exam_array = array_column($exams, 'exam_id');
                        $no_students = $this->student_model->getStudentCount($class['class_id'], $class['section_id']);
                        $class_status = $this->examgroupstudent_model->getClassExamStatusFull($class['class_id'], $class['section_id'], $exam_array);

                ?>

                        <div class="panel-group" id="accordion">
                            <div class="box-body table-responsive">
                                <div class="panel panel-default">
                                    <div class="panel-heading" style="background-color: #ffffff;">
                                        <h4 class="panel-title">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse_<?php echo $i; ?>"><?php echo $class['class'] . " (" . $class['section'] . ")"; ?></a>

                                                </div>
                                                <div class="col-md-3">
                                                    <p>Total exam : <?php echo $count; ?></p>
                                                </div>
                                                <div class="col-md-3">
                                                    <p>Total Student : <?php echo $no_students; ?></p>
                                                </div>
                                                <div class="col-md-3">
                                                    <p>Status : <?php echo $class_status . "%"; ?></p>
                                                </div>
                                            </div>

                                        </h4>
                                    </div>
                                    <div id="collapse_<?php echo $i; ?>" class="panel-collapse collapse">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <table class="table table-bordered table-responsive">
                                                    <thead>
                                                        <tr>
                                                            <th style="padding-left: 17px;">Exam Name</th>
                                                            <th style="padding-left: 17px;">Status</th>
                                                            <th style="padding-left: 17px;">Verification</th>
                                                            <th style="padding-left: 17px;">Pending List</th>
                                                            <th style="padding-left: 17px;">Admin </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        if (!empty($exams)) {
                                                            foreach ($exams as $exam) {
                                                                $examlist = $this->examgroup_model->getExamByID($exam['exam_id']);
                                                                $status = $this->examgroupstudent_model->getClassExamStatus($class['class_id'], $class['section_id'], $exam['exam_id']);
                                                                $vstatus = $this->examgroupstudent_model->getClassExamVerificationStatus($class['class_id'], $class['section_id'], $exam['exam_id']);

                                                                $exam_Resultstatus = $this->examgroup_model->examResultsstatusbyexam_id($exam['exam_id'],$class_id,$section_id);
                                                                // echo "<pre>";
                                                                // print_r($exam_Resultstatus);
                                                        ?>
                                                                <tr>
                                                                    <th style="padding-left: 17px;"><?php echo $examlist->exam; ?></th>
                                                                    <th style="padding-left: 17px;"><?php echo $status . "%"; ?></th>
                                                                    <th style="padding-left: 17px;"><?php echo $vstatus . "%"; ?></th>
                                                                    <th style="padding-left: 17px;">
                                                                        <!-- <a href="javascript:void(0);" class="btn btn-primary btn-sm editsub" data-class_id="<?php echo $class['class_id']; ?>" data-section_id="<?php echo $class['section_id']; ?>" data-exam_id="<?php echo $exam['exam_id']; ?>">View</a> -->
                                                                        <?php
                                                                        $btn = 'Lock'; // inactive => 0
                                                                        $btn_value = 1;
                                                                        if (!empty($exam_Resultstatus) && $exam_Resultstatus['head_lock_status'] == 1) {
                                                                            $btn = "Unlock"; // active => 1
                                                                            $btn_value = 0;
                                                                        } ?>
                                                                        <!-- <input type="hidden" name="button" id="btn_id" value=""> -->
                                                                        <button class="btn btn-primary btn-sm btn_lock status_<?php echo $exam['exam_id']; ?>" data-status="<?php echo $btn_value; ?>" data-class_id="<?php echo $class_id;?>" data-section_id="<?php echo $section_id;?>" id="exam_btn_<?php echo $exam['exam_id']; ?>" data-exam_id="<?php echo $exam['exam_id']; ?>"><?php echo $btn; ?></button>
                                                                    </th>
                                                                    <th style="padding-left: 17px;">
                                                                        <!-- <a href="javascript:void(0);" class="btn btn-primary btn-sm editsub" data-class_id="<?php echo $class['class_id']; ?>" data-section_id="<?php echo $class['section_id']; ?>" data-exam_id="<?php echo $exam['exam_id']; ?>">View</a> -->
                                                                        <?php
                                                                        $btn = 'Lock'; // inactive => 0
                                                                        $btn_value = 1;
                                                                        if (!empty($exam_Resultstatus) && $exam_Resultstatus['admin_lock_status'] == 1) {
                                                                            $btn = "Unlock"; // active => 1
                                                                            $btn_value = 0;
                                                                        } ?>
                                                                        <!-- <input type="hidden" name="button" id="btn_id" value=""> -->
                                                                        <button class="btn btn-primary btn-sm admin_lock adminstatus_<?php echo $exam['exam_id']; ?>" data-status="<?php echo $btn_value; ?>" data-class_id="<?php echo $class_id;?>" data-section_id="<?php echo $section_id;?>" id="exam_btn_<?php echo $exam['exam_id']; ?>" data-exam_id="<?php echo $exam['exam_id']; ?>"><?php echo $btn; ?></button>
                                                                    </th>

                                                                </tr>
                                                        <?php }
                                                        } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
            <?php
                    }
                    $i = $i + 1;
                }
            }
            ?>


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

    getExamByExamgroup(exam_group_id, exam_id);
    $(document).on('change', '#exam_group_id', function(e) {
        $('#exam_id').html("");
        var exam_group_id = $(this).val();
        getExamByExamgroup(exam_group_id, 0);
    });

    $(document).on('change', '#class_id', function(e) {
        $('#section_id').html("");
        var class_id = $(this).val();
        getSectionByClass(class_id, 0);
    });

    function getSectionByClass(class_id, section_id) {

        if (class_id !== "") {
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


    function getExamByExamgroup(exam_group_id, exam_id) {

        if (exam_group_id !== "") {
            $('#exam_id').html("");
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';

            $.ajax({
                type: "POST",
                url: base_url + "admin/examgroup/getExamByExamgroup",
                data: {
                    'exam_group_id': exam_group_id
                },
                dataType: "json",
                beforeSend: function() {
                    $('#exam_id').addClass('dropdownloading');
                },
                success: function(data) {
                    $.each(data, function(i, obj) {
                        var sel = "";
                        if (exam_id === obj.id) {
                            sel = "selected";
                        }
                        div_data += "<option value=" + obj.id + " " + sel + ">" + obj.exam + "</option>";
                    });
                    $('#exam_id').append(div_data);
                },
                complete: function() {
                    $('#exam_id').removeClass('dropdownloading');
                }
            });
        }
    }


    $(document).on('click', '.btn_lock', function() {
        var $this = $(this);
        var class_id = $(this).data('class_id');
        var section_id = $(this).data('section_id');
        var exam_id = $(this).data('exam_id');
        var statusTxt = $(this).text();
        if(statusTxt == 'Lock'){
            var status = 1;
        } else{
            var status = 0;
        }
        $.ajax({
            type: "POST",
            url: base_url + "admin/principal_verify/update_exam_result_status",
            data: {
                'exam_id': exam_id,
                'class_id': class_id,
                'section_id': section_id,
                'status': status,
            },
            dataType: "json",
            beforeSend: function() {
                $('#exam_btn_'+ exam_id).addClass('dropdownloading');
            },
            success: function(data) {
                console.log(data);
                if (statusTxt == 'Lock') {
                    $(".status_" + data.exam_id).text('Unlock');
                    $(".status_" + data.exam_id).attr('data-status',0);
                } else {
                    $(".status_" + data.exam_id).text('Lock');
                    $(".status_" + data.exam_id).attr('data-status',1);
                }
            },
            error: function(xhr) { // if error occured   
                alert("Error occured.please try again");
                $this.button('reset');
            },
            complete: function() {
            }
        });
    });


    $(document).on('click', '.admin_lock', function() {
        var $this = $(this);
        var class_id = $(this).data('class_id');
        var section_id = $(this).data('section_id');
        var exam_id = $(this).data('exam_id');
        var statusTxt = $(this).text();
        if(statusTxt == 'Lock'){
            var status = 1;
        } else{
            var status = 0;
        }
        $.ajax({
            type: "POST",
            url: base_url + "admin/principal_verify/update_exam_result_admin_status",
            data: {
                'exam_id': exam_id,
                'class_id': class_id,
                'section_id': section_id,
                'status': status,
            },
            dataType: "json",
            beforeSend: function() {
                $('#exam_btn_'+ exam_id).addClass('dropdownloading');
            },
            success: function(data) {
                console.log(data);
                if (statusTxt == 'Lock') {
                    $(".adminstatus_" + data.exam_id).text('Unlock');
                    $(".adminstatus_" + data.exam_id).attr('data-status',0);
                } else {
                    $(".adminstatus_" + data.exam_id).text('Lock');
                    $(".adminstatus_" + data.exam_id).attr('data-status',1);
                }
            },
            error: function(xhr) { // if error occured   
                alert("Error occured.please try again");
                $this.button('reset');
            },
            complete: function() {
            }
        });
    });
</script>