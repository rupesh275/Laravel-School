<div class="content-wrapper" style="min-height: 946px;">

    <section class="content-header">
        <h1>
            <i class="fa fa-user-plus"></i> <?php echo $this->lang->line('student_information'); ?> <small><?php echo $this->lang->line('student1'); ?></small>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <form role="form" action="<?php echo site_url('student/generate_roll_no') ?>" method="post" id="generate_form" class="">
                                <?php echo $this->customlib->getCSRF(); ?>
                                <div class="col-sm-4">
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
                                                $count++;
                                            }
                                            ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('class_id'); ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('division'); ?></label><small class="req"> *</small>
                                        <select id="section_id" name="section_id" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('select'); ?></label>
                                        <select autofocus="" id="by_wise" name="by_wise" class="form-control">
                                            <option value="Gender"><?php echo "Gender Wise"; ?></option>
                                            <option value="Name"><?php echo "Name Wise"; ?></option>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('by_wise'); ?></span>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-sm pull-right checkbox-toggle"> <?php echo $this->lang->line('generate'); ?></button>
                                    </div>
                                </div>
                            </form>



                        </div>
                    </div>

                    <div class="">
                        <div class="box-header ptbnull"></div>
                        <div class="box-header ptbnull">
                            <h3 class="box-title titlefix"><i class="fa fa-users"></i> <?php echo $this->lang->line('student'); ?> <?php echo $this->lang->line('list'); ?>
                                <?php echo form_error('student'); ?></h3>
                            <div class="box-tools pull-right"></div>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">


                                <table class="table table-striped table-bordered table-hover student-list" data-export-title="<?php echo $this->lang->line('student') . " " . $this->lang->line('list'); ?>">
                                    <thead>

                                        <tr>
                                            <th><?php echo $this->lang->line('class'); ?></th>
                                            <th><?php echo $this->lang->line('section'); ?></th>

                                            <th><?php echo $this->lang->line('roll_no'); ?></th>
                                            <th><?php echo $this->lang->line('admission_no'); ?></th>

                                            <th><?php echo $this->lang->line('student'); ?> <?php echo $this->lang->line('name'); ?></th>
                                            <?php if ($sch_setting->father_name) { ?>
                                                <th><?php echo $this->lang->line('father_name'); ?></th>
                                            <?php } ?>


                                        </tr>
                                    </thead>
                                    <tbody id="studentbody">

                                    </tbody>
                                </table>
                            </div>
                        </div>


                        <div class="modal" id="confirmModal">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                        <h4 class="modal-title"><?php echo $this->lang->line('confirmation') ?></h4>
                                    </div>
                                    <div class="modal-body">
                                        <p><?php echo "Are you sure?" ?></p>
                                    </div>
                                    <div class="modal-footer">
                                        <a href="#" data-dismiss="modal" aria-hidden="true" class="btn btn-danger btn secondary"><?php echo $this->lang->line('no') ?></a>
                                        <a href="#" id="delete-btn" class="btn btn-confirm confirm"><?php echo $this->lang->line('yes') ?></a>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--./box-body-->
                    </div>



                </div>
            </div>
    </section>
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
<script type="text/javascript">
    // $("input[name='checkAll']").click(function () {
    //     $("input[name='student[]']").not(this).prop('checked', this.checked);
    // });
    $(document).on('submit', '#generate_form', function(e) {
        e.preventDefault();
        $('#confirmModal').modal({
            backdrop: 'static',
            keyboard: false
        });
    });

    $(document).on('click', '#delete-btn', function(e) {
        var confirm_modal = $('#confirmModal');

        $.ajax({
            type: "POST",
            dataType: 'Json',
            url: $("#generate_form").attr('action'),
            data: $("#generate_form").serialize(),
            success: function(data) {
                if (data.status == "fail") {
                    var message = "";
                    if (data.message != "") {
                        errorMsg(data.message);
                    }else{

                        $.each(data.error, function(index, value) {
    
                            message += value;
                        });
                        errorMsg(message);
                    }
                } else {
                    successMsg(data.message);
                }
                confirm_modal.modal('hide');

            },
            error: function(xhr) { // if error occured
                confirm_modal.removeClass('modal_loading');
            },
            complete: function() {
                divFunction();
            },
        });
    });

    $(document).on("change", "#section_id", function() {
        var collect = divFunction();

        // alert('pay_btn');
    });


    function divFunction() {
        // $(document).on('change', '#section_id', function() {
        var $this = $(this);
        var class_id = $('#class_id').val();
        var section_id = $('#section_id').val();
        var html_data = '';
        $.ajax({
            type: "POST",
            url: base_url + "student/getstudentsbyclass_section",
            data: {
                'class_id': class_id,
                'section_id': section_id,
            },
            dataType: "json",
            beforeSend: function() {
                $('#student_id').addClass('dropdownloading');
            },
            success: function(data) {
                $.each(data, function(i, obj) {
                    html_data += "<tr> <td> " + obj.class + " </td> <td> " + obj.section + " </td> <td> " + obj.roll_no + " </td> <td> " + obj.admission_no + " </td> <td> " + obj.firstname + " " + obj.lastname + " </td> <td> " + obj.father_name + " </td> </tr>"
                    // div_data += "<option value=" + obj.id + " " + sel + ">" + obj.firstname + " " + obj.lastname + "</option>";
                });
                $('#studentbody').empty().append(html_data);
            },
            complete: function() {
                $('#student_id').removeClass('dropdownloading');
            }
        });
        // });
    }
</script>