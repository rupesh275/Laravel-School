<div class="content-wrapper" style="min-height: 946px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-user-plus"></i> <?php echo $this->lang->line('student_information'); ?> <small><?php echo $this->lang->line('student_fees1'); ?></small>
        </h1>

    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <?php if ($this->session->flashdata('msg')) { ?>
                        <?php echo $this->session->flashdata('msg') ?>
                    <?php } ?>
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <form id='form1' action="<?php echo site_url('admin/route/bulk_route_assign') ?>" method="post" accept-charset="utf-8">
                        <div class="box-body">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('class'); ?></label><small class="req"> *</small>
                                        <select autofocus="" id="class_id" name="class_id" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($classlist as $class) {
                                            ?>
                                                <option value="<?php echo $class['id'] ?>" <?php if (set_value('class_id') == $class['id']) echo "selected=selected"; ?>><?php echo $class['class'] ?></option>
                                            <?php
                                                $count++;
                                            }
                                            ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('class_id'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('division'); ?></label><small class="req"> *</small>
                                        <select id="section_id" name="section_id" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                                    </div>
                                </div>


                            </div>
                            <h4> <?php echo "Change Route To"; ?></h4>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo "To Route"; ?></label><small class="req"> *</small>
                                        <select id="route_id" name="route_id" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($listroute as  $route) {
                                            ?>
                                                <option value="<?php echo $route['id']; ?>" <?php if (set_value('route_id') == $route['id']) echo "selected=selected"; ?>><?php echo $route['route_title']; ?></option>
                                            <?php
                                            } ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('route_id'); ?></span>
                                    </div>
                                </div>
                            </div>

                            
                            <div class="box-footer">
                                <button type="submit" class="btn btn-primary btn-sm pull-right"><?php echo $this->lang->line('array_search(needle, haystack)'); ?><?php echo $this->lang->line('search'); ?></button>
                            </div>
                    </form>

                    <?php
                    if (isset($resultlist)) {
                    ?>

                        <div class="box-header ptbnull">
                            <h3 class="box-title"><i class="fa fa-list"></i><?php echo $this->lang->line('student_list'); ?> </h3>
                            <div class="box-tools pull-right">
                            </div>
                        </div>
                        <form action="<?php echo site_url("admin/route/bulkRouteChange") ?>" method="post" accept-charset="utf-8" class="promote_forms">
                            <div class="box-body">

                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <tbody>
                                            <tr>
                                                <th>
                                                    <div class="checkbox mb0 mt0">
                                                        <label class="labelbold"><input type="checkbox" id="select_all" /> <?php echo $this->lang->line('all'); ?></label>
                                                    </div>
                                                </th>
                                                <th><?php echo $this->lang->line('admission_no'); ?></th>
                                                <th><?php echo $this->lang->line('roll_no'); ?></th>
                                                <th><?php echo $this->lang->line('route'); ?></th>
                                                <th><?php echo $this->lang->line('student'); ?> <?php echo $this->lang->line('name'); ?></th>
                                                <th><?php echo $this->lang->line('gender'); ?></th>
                                                <th><?php echo $this->lang->line('father_name'); ?></th>
                                                <th><?php echo $this->lang->line('date_of_birth'); ?></th>
                                                <!-- <th class=""><?php echo $this->lang->line('current'); ?> <?php echo $this->lang->line('result'); ?></th>
                                                <th class=""><?php echo $this->lang->line('next_session_status'); ?></th> -->
                                            </tr>
                                            <?php if (empty($resultlist)) {
                                            ?>
                                                <tr>
                                                    <td colspan="13" class="text-danger text-center"><?php echo $this->lang->line('no_record_found'); ?></td>
                                                </tr>
                                                <?php
                                            } else {
                                                $count = 1;
                                                foreach ($resultlist as $student) {

                                                ?>
                                                    <input type="hidden" value="<?php echo $student['student_session_id']; ?>">
                                                    <input type="hidden" name="roll_no_<?php echo $student['student_session_id']; ?>" value="<?php echo $student['roll_no']; ?>">
                                                    <input type="hidden" name="route_id" value="<?php echo $route_id; ?>">
                                                    <tr>
                                                        <td><input class="checkbox" name="student_list[]" type="checkbox" autocomplete="off" value="<?php echo $student['student_session_id']; ?>"></td>
                                                        <td><?php echo $student['admission_no']; ?></td>
                                                        <td><?php echo $student['roll_no']; ?></td>
                                                        <td><?php echo $student['route_title']; ?></td>
                                                        <td><?php echo $this->customlib->getFullName($student['firstname'], $student['middlename'], $student['lastname'], $sch_setting->middlename, $sch_setting->lastname); ?></td>
                                                        <td><?php echo $student['gender']; ?></td>
                                                        <td><?php echo $student['father_name']; ?></td>
                                                        <td><?php
                                                            if ($student['dob'] != '0000-00-00' && $student['dob'] != '') {
                                                                echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($student['dob']));
                                                            }
                                                            ?></td>
                                                        <!-- <td>
                                                            <div class="radio-inline">
                                                                <label>
                                                                    <input type="radio" name="result_<?php echo $student['id']; ?>" checked="checked" value="pass">
                                                                    <?php echo $this->lang->line('pass'); ?>
                                                                </label>
                                                            </div>
                                                            <div class="radio-inline">
                                                                <label>
                                                                    <input type="radio" name="result_<?php echo $student['id']; ?>" value="fail">
                                                                    <?php echo $this->lang->line('fail'); ?>
                                                                </label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="radio-inline">
                                                                <label>
                                                                    <input type="radio" name="next_working_<?php echo $student['id']; ?>" checked="checked" value="countinue">
                                                                    <?php echo $this->lang->line('continue'); ?>
                                                                </label>
                                                            </div>
                                                            <div class="radio-inline">
                                                                <label>
                                                                    <input type="radio" name="next_working_<?php echo $student['id']; ?>" value="leave">
                                                                    <?php echo $this->lang->line('leave'); ?>
                                                                </label>
                                                            </div>
                                                        </td> -->
                                                    </tr>
                                            <?php
                                                }
                                                $count++;
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>

                            </div>

                            <div class="box-footer clearfix">
                                <?php
                                if (!empty($resultlist)) {
                                ?>

                                    <button type="submit" class="btn btn-sm btn-primary pull-right"><?php echo "Apply"; ?></button>
                                <?php
                                }
                                ?>
                            </div>
                        </form>

                </div>
            </div>
        <?php
                    } else {
                    }
        ?>
    </section>
</div>

<!--==confirm modal===-->


<div class="modal" id="pramoteStudentModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><?php echo $this->lang->line('promote') . " " . $this->lang->line('confirmation'); ?></h4>
            </div>
            <div class="modal-body">
                <p><?php echo $this->lang->line('are_you_sure_you_want_to_promote_confirm'); ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary pramote_student"><?php echo $this->lang->line('save'); ?></button>
            </div>
        </div>
    </div>
</div>


<!--===-->

<script type="text/javascript">
    $("#select_all").change(function() { //"select all" change 
        $(".checkbox").prop('checked', $(this).prop("checked")); //change all ".checkbox" checked status
    });

    //".checkbox" change 
    $('.checkbox').change(function() {
        //uncheck "select all", if one of the listed checkbox item is unchecked
        if (false == $(this).prop("checked")) { //if this item is unchecked
            $("#select_all").prop('checked', false); //change "select all" checked status to false
        }
        //check "select all" if all checkbox items are checked
        if ($('.checkbox:checked').length == $('.checkbox').length) {
            $("#select_all").prop('checked', true);
        }
    });

    function getSectionByClass(class_id, section_id) {
        if (class_id != "" && section_id != "") {
            $('#section_id').html("");
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            var url = "<?php
                        $userdata = $this->customlib->getUserData();
                        if (($userdata["role_id"] == 2)) {
                            echo "getClassTeacherSection";
                        } else {
                            echo "getByClass";
                        }
                        ?>";

            $.ajax({
                type: "GET",
                url: base_url + "sections/" + url,
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


        var class_promote_id = $('#class_promote_id').val();
        var section_promote_id = '<?php echo set_value('section_promote_id', 0) ?>';


        getPromotedSectionByClass(class_promote_id, section_promote_id);

        getSectionByClass(class_id, section_id);

        $(document).on('change', '#class_id', function(e) {
            $('#section_id').html("");
            var class_id = $(this).val();
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            var url = "<?php
                        $userdata = $this->customlib->getUserData();
                        if (($userdata["role_id"] == 2)) {
                            echo "getClassTeacherSection";
                        } else {
                            echo "getByClass";
                        }
                        ?>";

            $.ajax({
                type: "GET",
                url: base_url + "sections/" + url,
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
        $(document).on('change', '#feecategory_id', function(e) {
            $('#feetype_id').html("");
            var feecategory_id = $(this).val();
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: "GET",
                url: base_url + "feemaster/getByFeecategory",
                data: {
                    'feecategory_id': feecategory_id
                },
                dataType: "json",
                success: function(data) {
                    $.each(data, function(i, obj) {
                        div_data += "<option value=" + obj.id + ">" + obj.type + "</option>";
                    });
                    $('#feetype_id').append(div_data);
                }
            });
        });
    });




    function getPromotedSectionByClass(class_id, section_id) {

        if (class_id != "") {
            $('#section_promote_id').html("");
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
                    $('#section_promote_id').addClass('dropdownloading');
                },
                success: function(data) {
                    $.each(data, function(i, obj) {
                        var sel = "";
                        if (section_id == obj.section_id) {
                            sel = "selected";
                        }
                        div_data += "<option value=" + obj.section_id + " " + sel + ">" + obj.section + "</option>";
                    });
                    $('#section_promote_id').append(div_data);
                },
                complete: function() {
                    $('#section_promote_id').removeClass('dropdownloading');
                }
            });
        }
    }



    $(document).on('change', '#class_promote_id', function(e) {
        $('#section_promote_id').html("");
        var class_id = $(this).val();
        getPromotedSectionByClass(class_id, 0);
    });
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $('#pramoteStudentModal').modal({
            backdrop: 'static',
            keyboard: false,
            show: false
        })

        

    });
</script>