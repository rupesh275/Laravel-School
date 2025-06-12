<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.js"></script>
<script src="https://cdn.jsdelivr.net/gh/linways/table-to-excel@v1.0.4/dist/tableToExcel.js"></script>
<style>
    .success_box{
        border: 2px solid #2bd014;
        background-color: #2b80123b !important;
    }
</style>
<?php $currency_symbol = $this->customlib->getSchoolCurrencyFormat(); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">


    <!-- Main content -->
    <section class="content">
        <div class="row">

            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"> <?php echo $this->lang->line('exam') . " " . $this->lang->line('list'); ?></h3>
                        <div class="impbtntitle">
                            <?php

                            if ($this->rbac->hasPrivilege('exam', 'can_add')) {
                            ?>
                                <a tabindex="-1" class="btn btn-primary btn-sm" href="#" id="examModalButton"> <?php echo $this->lang->line('new') . " " . $this->lang->line('exam') ?></a>
                            <?php
                            }
                            if ($this->rbac->hasPrivilege('link_exam', 'can_view')) {
                            ?>
                                <a tabindex="-1" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#examconnectModal" href="#" id="examconnectModalButton" data-examGroup_id="<?php echo $examgroup->id; ?>"> <?php echo $this->lang->line('link') . " " . $this->lang->line('exams'); ?></a>
                            <?php
                            }
                            ?>
                            <a tabindex="-1" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#examjoinModal" href="#" id="examjoinModalButton" data-examGroup_id="<?php echo $examgroup->id; ?>"> Join <?php echo $this->lang->line('join') . " " . $this->lang->line('exams'); ?></a>

                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <input type="hidden" name="current_session" id="current_session" value="<?php echo $current_session; ?>">

                        <div class="row pb10">
                            <div class="col-lg-2 col-md-3 col-sm-12 col-xs-6">
                                <p class="examinfo"><span> <?php echo $this->lang->line('exam') . " " . $this->lang->line('group'); ?></span> <?php echo $examgroup->name; ?></p>
                            </div>
                            <!--./col-lg-4-->
                            <div class="col-lg-2 col-md-3 col-sm-12 col-xs-6">
                                <p class="examinfo"><span> <?php echo $this->lang->line('exam') . " " . $this->lang->line('type') ?></span> <?php echo $examType[$examgroup->exam_type]; ?></p>
                            </div>
                            <!--./col-lg-4-->
                            <div class="col-lg-8 col-md-6 col-sm-12 col-xs-12">
                                <p class="examinfo"><span> <?php echo $this->lang->line('description'); ?> </span> <?php echo $examgroup->description; ?></p>
                            </div>
                            <!--./col-lg-4-->

                        </div>
                        <!--./row-->
                        <div class="divider2"></div>
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" id="examgroup_id" name="examgroup_id" value="<?php echo $examgroup->id; ?>">
                            </div>
                        </div>

                        <div class="table-responsive mailbox-messages" id="exam_tbl">
                            <div class="download_label"><?php echo $this->lang->line('expense_list'); ?></div>
                            <table class="table table-hover table-striped table-bordered loading1" id="exam_table">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('name'); ?></th>
                                        <th><?php echo $this->lang->line('session') ?></th>
                                        <th><?php echo $this->lang->line('subjects') . " " . $this->lang->line('included'); ?></th>
                                        <th class="text text-center"><?php echo $this->lang->line('status'); ?></th>
                                        <th class="text text-center"><?php echo $this->lang->line('publish') . " " . $this->lang->line('result'); ?></th>
                                        <th class=""><?php echo $this->lang->line('description') ?></th>
                                        <!-- <th class="text text-center">Marks Entered(%)</th> -->
                                        <th class="text-right"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table><!-- /.table -->
                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->
                </div>
            </div>
            <!--/.col (left) -->
        </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<?php
$examcategory         = $this->examgroup_model->getexamcategoryArray($examgroup->exam_type_id);
?>
<div class="modal fade" id="examModal">
    <div class="modal-dialog modal-lg">
        <form id="formadd" action="<?php echo site_url('admin/examgroup/ajaxaddexam'); ?>">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><?php echo $this->lang->line('exam'); ?></h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="exam_id" value="0">
                    <div class="row">
                        <div class="form-group col-xs-12 col-sm-9 col-md-9 col-lg-9">
                            <label for="exam"><?php echo $this->lang->line('exam') ?><small class="req"> *</small></label>
                            <input type="text" class="form-control" id="exam" name="exam">
                            <span class="text text-danger" id="exam_error"></span>
                        </div>
                        <div class="form-group col-xs-12 col-sm-3 col-md-3 col-lg-3">
                            <label for="exam"><?php echo $this->lang->line('session') ?></label>
                            <select id="session_id" name="session_id" class="form-control">
                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                <?php
                                foreach ($sessionlist as $session) {
                                ?>
                                    <option value="<?php echo $session['id']; ?>" <?php echo set_select('session_id', 'session_id', (($current_session == $session['id']) ? true : false)); ?>><?php echo $session['session']; ?></option>
                                <?php
                                }
                                ?>
                            </select>
                            <span class="text text-danger" id="session_id_error"></span>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
                            <div class="checkbox-inline">
                                <label>
                                    <input type="checkbox" value="1" name="is_active"> <?php echo $this->lang->line('publish'); ?>
                                </label>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
                            <div class="checkbox-inline">
                                <label>
                                    <input type="checkbox" value="1" name="is_publish" autocomplete="off"> <?php echo $this->lang->line('publish') . " " . $this->lang->line('result'); ?>
                                </label>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                            <label class="radio-inline"><input type="radio" value="1" name="use_exam_roll_no" checked="checked"><?php echo $this->lang->line('admit_card_roll_no') ?> </label>

                            <label class="radio-inline"><input type="radio" value="0" name="use_exam_roll_no"><?php echo $this->lang->line('profile_roll_no') ?> </label>

                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                            <div class="checkbox-inline">
                                <label>
                                    <input type="checkbox" value="1" name="mark_result">Mark Result
                                </label>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <label for="description"><?php echo $this->lang->line('description') ?></label>
                            <textarea class="form-control" name="description" id="description"></textarea>
                        </div>
                        <div class="clearfix"></div>
                        <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <label for="examlist">Select Subject From Exam</label>
                            <select name="examlist" id="examlist" class="form-control">
                                <option value="">Select</option>
                                <?php
                                if (!empty($examlist)) {
                                    foreach ($examlist as  $value) {
                                ?>
                                        <option value="<?php echo $value['id']; ?>"><?php echo $value['exam']; ?></option>
                                <?php
                                    }
                                } ?>
                            </select>
                            <span class="text text-danger" id="examcategory"></span>
                        </div>
                        <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <label for="examresulttype_id">Exam Result Type</label>
                            <select name="examresulttype_id" id="examresulttype_id" class="form-control">
                                <option value="">Select</option>
                                <option value="Mark">Mark</option>
                                <option value="Grade">Grade</option>
                                <option value="SubjectWise">SubjectWise</option>
                            </select>
                            <span class="text text-danger" id="examresulttype_id"></span>
                        </div>

                        <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <label for="examcategory_id">Exam Category</label>
                            <select name="examcategory_id" id="examcategory_id" class="form-control">

                                <?php
                                if (!empty($examcategory)) {
                                    foreach ($examcategory as  $value) {
                                ?>
                                        <option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
                                <?php
                                    }
                                } ?>
                            </select>
                            <span class="text text-danger" id="examcategory_id"></span>
                        </div>
                        <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <label for="exam_srno">Exam SR.No</label>
                            <input type="number" class="form-control" id="exam_srno" name="exam_srno">
                            <span class="text text-danger" id="exam_srno"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="load" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Saving..."><?php echo $this->lang->line('save') ?></button>
                </div>
            </div>
        </form>
    </div>
</div>
<div id="addSubject" class="modal fade" role="dialog">
    <div class="modal-dialog modal-xl">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('add') . " " . $this->lang->line('subjects') ?></h4>
            </div>
            <div class="modal-body subject-body">
            </div>
        </div>
    </div>
</div>

<div id="examconnectModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel"> <?php echo $this->lang->line('link') . " " . $this->lang->line('exam'); ?></h4>
            </div>
            <div class="modal-body examconnectModalBody">

            </div>
        </div>
    </div>
</div>
<div id="examjoinModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Join<?php echo $this->lang->line('join') . " " . $this->lang->line('exam'); ?></h4>
            </div>
            <div class="modal-body examjoinModalBody">

            </div>
        </div>
    </div>
</div>

<div class="delmodal modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $this->lang->line('confirmation'); ?></h4>
            </div>
            <div class="modal-body">
                <p><?php echo $this->lang->line('are_you_sure_want_to_delete') ?> <b class="invoice_no"></b> <?php echo $this->lang->line('record_this_action_is_irreversible'); ?></p>
                <p><?php echo $this->lang->line('do_you_want_to_proceed') ?></p>
                <p class="debug-url"></p>
                <input type="hidden" name="del_itemid" class="del_itemid" value="">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('cancel'); ?></button>
                <a class="btn btn-danger btn-ok"><?php echo $this->lang->line('delete'); ?></a>
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
<div id="teacherRemarkModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-xl">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('teacher_remark'); ?></h4>
            </div>

            <div class="modal-body">

            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div id="subjectModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

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

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('class'); ?><small class="req"> *</small></label>
                                <select id="class_id" name="class_id" class="form-control">
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
                            <!--./form-group-->
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('section'); ?><small class="req"> *</small></label>
                                    <select id="section_id" name="section_id" class="form-control">
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                                </div>
                            </div>
                            <!--./form-group-->
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('subject'); ?><small class="req"> *</small></label>
                                <select id="main_sub" name="main_sub" class="form-control">
                                    
                                </select>
                            </div>
                            <!--./form-group-->
                        </div>
                        <div class="col-sm-3">
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
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('session'); ?><small class="req"> *</small></label>
                                <select id="session_id" name="session_id" class="form-control">
                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    <?php
                                    foreach ($sessionlist as $session) {
                                    ?>
                                        <option value="<?php echo $session['id'] ?>" <?php echo set_select('session_id', 'session_id', (($current_session == $session['id']) ? true : false)); ?>><?php echo $session['session'] ?></option>
                                    <?php
                                    }
                                    ?>
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

<!-- Modal -->
<div id="allotStudentModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-xl">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"> <?php echo $this->lang->line('exam') . " " . $this->lang->line('students') ?></h4>
            </div>
            <div class="modal-body">
                <form role="form" id="allotStudentForm" action="<?php echo site_url('admin/examgroup/examstudent') ?>" method="post">
                    <input type="hidden" name="exam_id" value="0" class="exam_group_class_batch_exam_id">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('class'); ?></label><small class="req"> *</small>
                                <select autofocus="" id="class_id" name="class_id" class="form-control">
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
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('section'); ?></label><small class="req"> *</small>
                                <select id="section_id" name="section_id" class="form-control">
                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                </select>
                                <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <button type="submit" name="search" value="search_filter" class="btn btn-primary pull-right btn-sm checkbox-toggle"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                        </div>
                    </div>
                </form>
                <div class="studentAllotForm">

                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('.delmodal').modal({
            backdrop: 'static',
            keyboard: false,
            show: false
        })
        $('#confirm-delete').on('show.bs.modal', function(e) {
            var data = $(e.relatedTarget).data();
            $('.del_itemid', this).val("").val(data.id);
            $('.invoice_no', this).html(data.exam);
        });

        $('#confirm-delete').on('click', '.btn-ok', function(e) {
            var $modalDiv = $(e.delegateTarget);
            var id = $('.del_itemid').val();

            $.ajax({
                type: "post",
                url: '<?php echo site_url("admin/examgroup/deleteExam") ?>',
                dataType: 'JSON',
                data: {
                    'id': id
                },
                beforeSend: function() {
                    $modalDiv.addClass('modalloading');
                },
                success: function(data) {
                    if (data.status == 1) {
                        successMsg(data.message);
                        all_records();

                    } else {
                        errorMsg(data.message);
                    }
                },
                complete: function() {
                    $('.delmodal').modal('hide');
                    $modalDiv.removeClass('modalloading');

                }
            });
        });
    });
</script>
<script type="text/javascript">
    var batch_subjects = "";
    var main_subjects = "";
    var x = 1;
    var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy', 'M' => 'MM']) ?>';

    var date_format_time = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'DD', 'm' => 'MM', 'Y' => 'YYYY', 'M' => 'MMM']) ?>';
    $(document).ready(function() {
        $('#examconnectModal,#subjectmarkModal,#allotStudentModal,#teacherRemarkModal').modal({
            backdrop: 'static',
            keyboard: false,
            show: false
        });

        $('.date').datepicker({
            format: date_format,
            autoclose: true
        });
        // var excel_name = "<?php echo "marks_".date('d-m-Y-h-i-s').'.xlsx';?>";
        // $(document).on('click', '#btnExport', function () {
        //     let table = document.getElementById('table_html');
            
        //     TableToExcel.convert(table, {
        //         name: excel_name,
        //         sheet: {
        //             name: 'Sheet 1'
        //         }
        //     });
        // });

        $('.datetime').datetimepicker();
        $('#examModalButton').click(function() {
            $('#examModal').modal({
                backdrop: 'static',
                keyboard: false
            });
            $("#examlist").val('');
            $("#examcategory_id").val('');
            $("#examresulttype_id").val('');
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
        $(document).on('click', '.editexamModalButton', function(e) {

            reset_exm_form();
            var exam_id = $(this).data('exam_id');
            $.ajax({
                type: "POST",
                url: base_url + "admin/examgroup/getExamByID",
                data: {
                    'exam_id': exam_id
                },
                dataType: "json",
                beforeSend: function() {
                    $('#formadd')[0].reset();
                },
                success: function(data) {
                    console.log(data);
                    $("#formadd select[name=session_id] [value=" + data.exam.session_id + "]").attr('selected', 'true');
                    $("#formadd select[name=examlist] [value=" + data.exam.examlist_id + "]").attr('selected', 'true');
                    $("#formadd select[name=examcategory_id] [value=" + data.exam.examcategory_id + "]").attr('selected', 'true');
                    $("#formadd select[name=examresulttype_id] [value=" + data.exam.exam_result_type + "]").attr('selected', 'true');
                    $("#formadd input[name=exam]").val(data.exam.exam);
                    $("#formadd input[name=date_from]").val(data.exam.date_from);
                    $("#formadd input[name=exam_id]").val(data.exam.id);
                    $("#formadd input[name=date_to]").val(data.exam.date_to);
                    $("#formadd input[name=exam_srno]").val(data.exam.exam_srno);
                    $("#formadd select[name=class_id] [value=" + data.exam.class_id + "]").attr('selected', 'true');
                    $("#formadd textarea[name=description]").val(data.exam.description);
                    if (data.exam.is_active == 1) {

                        $("#formadd input[name=is_active]").prop('checked', true);
                    }

                    $("#formadd input[name=use_exam_roll_no][value='" + data.exam.use_exam_roll_no + "']").prop("checked", true);
                    // $("#formadd input[name=use_exam_roll_no]").prop('checked', true);

                    if (data.exam.is_publish == 1) {

                        $("#formadd input[name=is_publish]").prop('checked', true);
                    }
                    if (data.exam.mark_result == 1) {

                        $("#formadd input[name=mark_result]").prop('checked', true);
                    }

                    // getSectionByClass(data.exam.class_id, data.exam.class_section_id);
                    // getBatchByClassSection(data.exam.class_section_id, data.exam.batch_id);
                    $('#examModal').modal('show');
                },
                complete: function() {

                }
            });
        });

        $(document).on('click', '#subjectModalButton', function(e) {
            batch_subjects = "";
            main_subjects = "";
            x = 1;
            $('.subject-body').html('');
            var class_batch_id = $(this).data('class_batch_id');
            var exam_id = $(this).data('exam_id');
            var exam_group_id = $('#examgroup_id').val();


            $('#addSubject').modal({
                backdrop: 'static',
                keyboard: false
            });
            $.ajax({
                type: "POST",
                url: base_url + "admin/examgroup/getexamSubjects",
                data: {
                    'exam_group_id': exam_group_id,
                    'class_batch_id': class_batch_id,
                    'exam_id': exam_id
                },
                dataType: "json",
                beforeSend: function() {
                    $('.subject-body').html();
                },
                success: function(data) {
                    var s = data.subject_page;
                    $('.subject-body').html("").html(s);

                    var tmp_row = $('#item_table');

                    $('.datepicker_init', tmp_row).datetimepicker({
                        format: date_format_time,
                        showTodayButton: true,
                        ignoreReadonly: true
                    });

                    $('.datepicker_init_time', tmp_row).datetimepicker({
                        format: 'HH:mm:ss',
                        showTodayButton: true,
                        ignoreReadonly: true
                    });

                    batch_subjects = data.batch_subject_dropdown;
                    if (data.exam_subjects_count > 0) {
                        x = data.exam_subjects_count + 1;
                    }
                    main_subjects = data.main_subject_dropdown;
                    if (data.main_subjects_count > 0) {
                        x = data.main_subjects_count + 1;
                    }

                },
                complete: function() {

                }
            });
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


        $(document).on('click', '.model_grace_mark', function(e) {
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
                url: base_url + "admin/examgroup/getstudents_grace",
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

        function getSectionByClass(class_id, section_id) {
            if (class_id != 0 && class_id !== "") {
                $('#section_id').html("");
                $('#main_sub').html("");
                var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
                $.ajax({
                    type: "POST",
                    url: base_url + "admin/batchsubject/getSectionByClass",
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
                            if (section_id == obj.class_section_id) {
                                sel = "selected";
                            }
                            div_data += "<option value=" + obj.class_section_id + " " + sel + ">" + obj.section + "</option>";
                        });
                        $('#section_id').append(div_data);
                    },
                    complete: function() {
                        $('#section_id').removeClass('dropdownloading');
                    }
                });
            }
        }

        function getBatchByClassSection(section_id, batch_id) {
            if (section_id != "") {
                $('#batch_id').html("");
                var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';

                $.ajax({
                    type: "POST",
                    url: base_url + "admin/batchsubject/getBatchByClassSection",
                    data: {
                        'class_section_id': section_id
                    },
                    dataType: "JSON",
                    beforeSend: function() {
                        $('#batch_id').addClass('dropdownloading');
                    },
                    success: function(data) {
                        $.each(data, function(i, obj) {
                            var sel = "";
                            if (batch_id == obj.batch_id) {
                                sel = "selected";
                            }
                            div_data += "<option value=" + obj.id + " " + sel + ">" + obj.batch_name + "</option>";
                        });
                        $('#batch_id').append(div_data);
                    },
                    complete: function() {
                        $('#batch_id').removeClass('dropdownloading');
                    }
                });
            }
        }
    });

    $('#examconnectModal').on('show.bs.modal', function(e) {
        $('.examconnectModalBody').html("");
        var examgroup_id = $(e.relatedTarget).data('examgroup_id');
        $.ajax({
            type: "POST",
            url: base_url + "admin/examgroup/connectexams",
            data: {
                'examgroup_id': examgroup_id
            }, // serializes the form's elements.
            dataType: "JSON", // serializes the form's elements.
            beforeSend: function() {

            },
            success: function(data) {

                $('.examconnectModalBody').html(data.exam_page);
            },
            error: function(xhr) { // if error occured

                alert("<?php echo $this->lang->line('error_occured') . ", " . $this->lang->line('please_try_again') ?>");

            },
            complete: function() {

            }
        });
    });

    $('#examjoinModal').on('show.bs.modal', function(e) {
        $('.examjoinModalBody').html("");
        var examgroup_id = $(e.relatedTarget).data('examgroup_id');
        $.ajax({
            type: "POST",
            url: base_url + "admin/examgroup/joinexams",
            data: {
                'examgroup_id': examgroup_id
            }, // serializes the form's elements.
            dataType: "JSON", // serializes the form's elements.
            beforeSend: function() {

            },
            success: function(data) {

                $('.examjoinModalBody').html(data.exam_page);
            },
            error: function(xhr) { // if error occured

                alert("<?php echo $this->lang->line('error_occured') . ", " . $this->lang->line('please_try_again') ?>");

            },
            complete: function() {

            }
        });
    });

    $("#formadd").submit(function(e) {
        e.preventDefault(); // avoid to execute the actual submit of the form.
        $("span[id$='_error']").html("");
        var form = $(this);
        var url = form.attr('action');
        var submit_button = $(this).find(':submit');
        var post_params = $(this).serializeArray();
        post_params.push({
            name: 'exam_group_id',
            value: $('#examgroup_id').val()
        });

        $.ajax({
            type: "POST",
            url: url,
            data: post_params, // serializes the form's elements.
            dataType: "JSON", // serializes the form's elements.
            beforeSend: function() {
                submit_button.button('loading');
            },
            success: function(data) {

                if (!data.status) {
                    $.each(data.error, function(index, value) {
                        var errorDiv = '#' + index + '_error';
                        $(errorDiv).empty().append(value);
                    });
                } else if (data.status) {

                    $('#section_id').find('option').not(':first').remove();
                    $('#batch_id').find('option').not(':first').remove();
                    $('#formadd')[0].reset();
                    successMsg(data.message);
                    $('#examModal').modal('hide');
                    all_records();
                }
            },
            error: function(xhr) { // if error occured
                submit_button.button('reset');
                alert("<?php echo $this->lang->line('error_occured') . ", " . $this->lang->line('please_try_again') ?>");

            },
            complete: function() {
                submit_button.button('reset');
            }
        });
    });
</script>

<script type="text/javascript">
    $('.datepicker_init').datetimepicker({
        format: date_format_time,
        reReadonly: true
    });

    $(document).ready(function() {
        all_records();
    });

    function all_records() {
        $.ajax({
            type: "POST",
            url: base_url + "admin/examgroup/getexam",
            data: {
                examgroup_id: $('#examgroup_id').val()
            }, // serializes the form's elements.
            dataType: "JSON", // serializes the form's elements.
            beforeSend: function() {

            },
            success: function(data) {
                $('#exam_tbl').find('tbody').empty().append(data.exam_page);
                // console.log(data.query);

            },
            error: function(xhr) { // if error occured

                alert("<?php echo $this->lang->line('error_occured') . ", " . $this->lang->line('please_try_again') ?>");

            },
            complete: function() {

            }
        });
    }
</script>
<script>
    $(document).on('submit', '.ssaddSubject', function(e) {

        e.preventDefault();
        var form = $(this);
        var subsubmit_button = $(this).find(':submit');
        var formdata = form.serializeArray();
        formdata.push({
            name: 'examgroup_id',
            value: $('#examgroup_id').val()
        });
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
                    all_records();
                    successMsg(response.message);
                    $('#addSubject').modal('hide');
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

<script>
    $(document).on('click', '.add', function() {

        var xid = parseInt($(".parent_sub").last().data('id')) || 0;
        var x = parseInt(xid) + 1;

        var html = '';
        html += '<tr>';
        html += '<td width="250"><select name="main_sub[]" id="parent_sub_' + x + '" data-id="' + x + '" class="form-control parent_sub tddm200">' + main_subjects + '</select></td>';
        html += '<td width="250"><select name="subject[]" id="subject_' + x + '" class="form-control item_unit "> </select><input type="hidden" name="subparent[]" id="subparent_' + x + '"></td>';
        html += '<td width="250"><select name="input_type[]" id="input_type_' + x + '" class="form-control item_unit tddm200"><option value="">Select Type</option><option value="Grade">Grade</option><option value="Marks">Marks</option> </select></td>';
        //html += '<td><div class="input-group datepicker_init"><input type="text" name="date_from_' + x + '" class="form-control"/><span class="input-group-addon" id="basic-addon2"><i class="fa fa-calendar"></i></span></div></td>';
        //html += '<td><div class="input-group datepicker_init_time"><input type="text" name="time_from' + x + '" class="form-control"/><span class="input-group-addon" id="basic-addon2"><i class="fa fa-calendar"></i></span></div></td>';
        //html += '<td><input type="text" name="duration' + x + '" class="form-control duration" value="0"/></td>';
        //html += '<td><input type="number" name="credit_hours' + x + '" class="form-control credit_hours" value="0"/></td>';
        //html += '<td class=""><input type="text" name="room_no_' + x + '" class="form-control room_no" /></td>';
        html += '<td class=""><input type="number" name="max_marks[]" class="form-control max_marks" /></td>';
        html += '<td class=""><input type="hidden" name="rows[]" value="' + x + '"> <input name="prev_row[' + x + ']" type="hidden" value="0"><input type="number" name="min_marks[]" class="form-control min_marks" /></td>';
        html += '<td class=""><input type="number" name="convertTo[]" class="form-control convertTo" /></td>';
        html += '<td class="text-center" style="vertical-align: middle; cursor: pointer;"><span class="text text-danger remove fa fa-times mt5"></span></td></tr>';
        var tmp_row = $('#item_table').append(html);

        $('.datepicker_init', tmp_row).datetimepicker({
            format: date_format_time,
            showTodayButton: true,
            ignoreReadonly: true
        });

        $('.datepicker_init_time', tmp_row).datetimepicker({
            format: 'HH:mm:ss',
            showTodayButton: true,
            ignoreReadonly: true
        });
        x++;
    });

    $(document).on('click', '.remove', function() {
        $(this).closest('tr').remove();
    });

    $('#insert_form').on('submit', function(event) {
        event.preventDefault();
        var error = '';
        $('.item_name').each(function() {
            var count = 1;
            if ($(this).val() == '') {
                error += "<p>Enter Item Name at " + count + " Row</p>";
                return false;
            }
            count = count + 1;
        });

        $('.item_quantity').each(function() {
            var count = 1;
            if ($(this).val() == '') {
                error += "<p>Enter Item Quantity at " + count + " Row</p>";
                return false;
            }
            count = count + 1;
        });

        $('.item_unit').each(function() {
            var count = 1;
            if ($(this).val() == '') {
                error += "<p>Select Unit at " + count + " Row</p>";
                return false;
            }
            count = count + 1;
        });
        var form_data = $(this).serialize();
        if (error == '') {
            $.ajax({
                url: "insert.php",
                method: "POST",
                data: form_data,
                success: function(data) {
                    if (data == 'ok') {
                        $('#item_table').find("tr:gt(0)").remove();
                        $('#error').html('<div class="alert alert-success">Item Details Saved</div>');
                    }
                }
            });
        } else {
            $('#error').html('<div class="alert alert-danger">' + error + '</div>');
        }
    });
</script>
<script>
    $(document).on("change", ".parent_sub", function() {
        var id = $(this).attr('id');
        var dataid = $(this).data('id');

        var parent = $("#" + id + " option:selected").data('parent');
        var subparent = $("#" + id + " option:selected").data('subparent');
        var child = $("#" + id + " option:selected").data('child');
        var val = $("#" + id + " option:selected").val();
        $("#parent_" + dataid).val(parent);
        $("#subparent_" + dataid).val(subparent);
        $("#child_" + dataid).val(child);

        if (id != '') {
            $.ajax({
                url: '<?php echo base_url('admin/examgroup/getajaxdata'); ?>',
                type: 'POST',
                data: {
                    dataid: dataid,
                    parent: parent,
                    subparent: subparent,
                    child: child,
                    val: val,
                },
                dataType: 'json',
                success: function(data) {
                    $('#subject_' + dataid).html(' ');
                    $('#subject_' + dataid).html(data.html);
                }
            });
        }
    });
</script>
<script type="text/javascript">
    $(document).on('submit', 'form#connectExamForm', function(e) {

        e.preventDefault();
        var form = $(this);
        var sub_connect_exam = $("button[type=submit]:focus");
        var formdata = form.serializeArray();
        formdata.push({
            name: "action",
            value: sub_connect_exam.attr('name')
        });
        $.ajax({
            type: "POST",
            url: form.attr('action'),
            data: formdata, // serializes the form's elements.
            dataType: "JSON", // serializes the form's elements.
            beforeSend: function() {
                sub_connect_exam.button('loading');
                $('.error_connection').html("");
            },
            success: function(response) {

                if (response.status == 0) {
                    $('.error_connection').html($('<div>', {
                        class: 'alert alert-info',
                        text: response.message
                    }));
                } else {
                    successMsg(response.message);
                    $('#examconnectModal').modal('hide');
                }
                sub_connect_exam.button('reset');
            },
            error: function(xhr) { // if error occured

                alert("Error occured.please try again");
                sub_connect_exam.button('reset');

            },
            complete: function() {
                sub_connect_exam.button('reset');
            }
        });
    });
    $(document).on('submit', 'form#joinExamForm', function(e) {

        e.preventDefault();
        var form = $(this);
        var sub_connect_exam = $("button[type=submit]:focus");
        var formdata = form.serializeArray();
        formdata.push({
            name: "action",
            value: sub_connect_exam.attr('name')
        });
        $.ajax({
            type: "POST",
            url: form.attr('action'),
            data: formdata, // serializes the form's elements.
            dataType: "JSON", // serializes the form's elements.
            beforeSend: function() {
                sub_connect_exam.button('loading');
                $('.error_connection').html("");
            },
            success: function(response) {

                if (response.status == 0) {
                    $('.error_connection').html($('<div>', {
                        class: 'alert alert-info',
                        text: response.message
                    }));
                } else {
                    successMsg(response.message);
                    $('#examconnectModal').modal('hide');
                    location.reload();
                }
                sub_connect_exam.button('reset');
            },
            error: function(xhr) { // if error occured

                alert("Error occured.please try again");
                sub_connect_exam.button('reset');

            },
            complete: function() {
                sub_connect_exam.button('reset');
            }
        });
    });
    $(document).on('click', '#ckbCheckAll', function(e) {
        $(".checkBoxExam").prop('checked', $(this).prop('checked'));
    });

    $(document).on('click', '.examTeacherReamark', function() {
        var $this = $(this);
        console.log("sdfsfs");
        var recordid = $this.data('recordid');
        // $('input[name=recordid]').val(recordid);
        $.ajax({
            type: 'POST',
            url: baseurl + "admin/examgroup/getTeacherRemarkByExam",
            data: {
                'recordid': recordid
            },
            dataType: 'JSON',
            beforeSend: function() {
                $this.button('loading');
            },
            success: function(data) {
                $('#teacherRemarkModal .modal-body').html(data.subject_page);
                $('#teacherRemarkModal').modal('show');
                $this.button('reset');
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

    $(document).on('click', '.examMarksSubject', function() {
        var $this = $(this);
        var recordid = $this.data('recordid');
        $('input[name=recordid]').val(recordid);
        $.ajax({
            type: 'POST',
            url: baseurl + "admin/examgroup/getSubjectByExam",
            data: {
                'recordid': recordid
            },
            dataType: 'JSON',
            beforeSend: function() {
                $this.button('loading');
            },
            success: function(data) {
                $('#subjectmarkModal .modal-body').html(data.subject_page);
                $('#subjectmarkModal').modal('show');
                $this.button('reset');
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
</script>
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

    })
    $(document).on('click', '.model_mark', function(e) {
        var main_sub = $(this).data('main_sub');
        var subject_id = $(this).data('subject_id');
        var subject_name = $(this).data('subject_name');
        var teachersubject_id = $(this).data('teachersubject_id');
        $('.subjectmodal_header_1').html("").html(subject_name);
        $('.marksEntryForm').html("");
        $('.main_sub').val("").val(main_sub);
        $('.subject_id').val("").val(subject_id);
        $('.teachersubject_id').val("").val(teachersubject_id);
        $(e.currentTarget).find('input[name="subject_name"]').val(subject_name);
        var current_session = $('#current_session').val();
        $('#session_id option[value="' + current_session + '"]').prop("selected", true);

    })
    $(document).on('click', '.model_grace_mark', function(e) {
        var main_sub = $(this).data('main_sub');
        var subject_id = $(this).data('subject_id');
        var subject_name = $(this).data('subject_name');
        var teachersubject_id = $(this).data('teachersubject_id');
        $('.subjectmodal_header_1').html("").html(subject_name);
        $('.marksEntryForm').html("");
        $('.main_sub').val("").val(main_sub);
        $('.subject_id').val("").val(subject_id);
        $('.teachersubject_id').val("").val(teachersubject_id);
        $(e.currentTarget).find('input[name="subject_name"]').val(subject_name);
        var current_session = $('#current_session').val();
        $('#session_id option[value="' + current_session + '"]').prop("selected", true);

    })

    $(document).on('click', '.title_exam', function(e) {
        var title = $(this).data('mainexam');
        $('.subjectmodal_header').html("").html(title);
    })

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

    $(document).on('change', '#class_id', function(e) {
        $('#section_id').html("");
        var class_id = $(this).val();
        var selector = $(this).closest("div.modal-body").find('#section_id');

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

    $(document).on("click", ".first_modal", function(e) {
        var title_exam = $(this).data('mainexam');
        $('.subjectmodal_header1').html("").html(title_exam);
        var ids = $(this).data('exam_id');
        $("#dataexam_id").val(ids);
        $('#subjectModal').modal('show');
    });

    $("form#searchStudentSubject").on('submit', (function(e) {
        e.preventDefault(); // avoid to execute the actual submit of the form.
        var form = $(this);
        var type = $('#type').val();
        if (type != "") {
            if (type == 1) {
                var url = form.attr('action');
            }else if(type = 2)  {
                var url = "<?php echo base_url('admin/examgroup/getSubjectByClass')?>";
            }
        }else{
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
</script>
<script type="text/javascript">
    $(document).on('click', '.assignStudent', function() {
        var examid = $(this).data('examid');
        $('.exam_group_class_batch_exam_id').val(examid);
        $('#allotStudentModal').modal('show');
    });

    $("form#allotStudentForm").on('submit', (function(e) {
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
            },
            success: function(res) {

                if (res.status == 1) {
                    $('.studentAllotForm').html(res.page);


                } else {
                    var message = "";
                    $.each(res.error, function(index, value) {

                        message += value;

                    });
                    errorMsg(message);


                }

            },
            error: function(xhr) { // if error occured
                alert("Error occured.please try again");
                $this.button('reset');
            },
            complete: function() {
                $this.button('reset');
            }

        });
    }));
    $(document).on('submit', 'form#allot_exam_student', function(e) {

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
            },
            success: function(res) {
                if (res.status == 1) {
                    successMsg(res.message);
                    $('#allotStudentModal').modal('hide');

                } else {
                    errorMsg(res.message);
                }

                $this.button('reset');
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

    $(document).on('submit', 'form#remark_form', function(e) {

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
            },
            success: function(res) {
                if (res.status == 1) {
                    successMsg(res.message);
                    $('#teacherRemarkModal').modal('hide');

                } else {
                    errorMsg(res.message);
                }

                $this.button('reset');
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


    $('#allotStudentModal').on('hidden.bs.modal', function() {
        $('form#allotStudentForm').find('select#class_id').prop("selectedIndex", 0);
        $('form#allotStudentForm').find('select#section_id').find('option:not(:first)').remove();
        $('#allotStudentModal').find('div.studentAllotForm').html("");
        $("span[id$='_error']").html("");
    });

    $(document).on('click', '.select_all', function(e) {

        if (this.checked) {
            $(this).closest('div.table-responsive').find('[type=checkbox]').prop('checked', true);
        } else {
            $(this).closest('div.table-responsive').find('[type=checkbox]').prop('checked', false);
        }
    });

    $(document).ready(function() {
        $('body').tooltip({
            selector: "[data-toggle=tooltip]",
            container: "body"
        });
    });

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
        $('.examstudentId'+exam_studentid).addClass('success_box');
        // $('#assign_form2222').submit();
        
    });

    $(document).on('keyup', '.markss', function() { 
        var max_mark = $(this).data('max_marks');
        var min_mark = $(this).data('min_marks');
        var regex = /^\d*\.?\d*$/;
        var value = $(this).val();
        if ((parseFloat(value) > parseFloat(max_mark)) || !regex.test(value)) {
            $(this).val("");
        }else if ((parseFloat(value) < parseFloat(min_mark)) || parseFloat(value) < 0) {
            $(this).val("");
        }
    });
</script>