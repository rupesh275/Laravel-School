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
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria') . ""; ?></h3>
                    </div>
                    <div class="box-body">
                        <form role="form" action="<?php echo site_url('admin/examgroup/healthEntry') ?>" method="post">

                            <?php echo $this->customlib->getCSRF(); ?>

                            <div class="row">

                                <div class="col-sm-6 col-lg-3 col-md-12 col20">
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

                                <div class="col-sm-6 col-lg-3 col-md-12 col20">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('section'); ?></label><small class="req"> </small>
                                        <select id="section_id" name="section_id" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <button type="submit" name="search" value="search_filter" class="btn btn-primary pull-right btn-sm checkbox-toggle"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>




                </div>


            </div>
        </div>



        <div class="box-body">
            <div class="table-responsive no-padding">
                <form action="<?php echo base_url('admin/examgroup/healthsubmit') ?>" id="allot_exam_student" method="post">
                    <?php
                    if (!empty($studentList)) {
                    ?>
                        <table class="table table-responsive table-striped ">
                            <thead>
                                <tr>
                                    <th style="width: 136px;"><?php echo $this->lang->line('Gr'); ?>Gr.No</th>
                                    <th style="width: 110px;"><?php echo $this->lang->line('jh'); ?>R.No</th>
                                    <th style="width: 341px;"><?php echo $this->lang->line('student_name'); ?></th>
                                    <th style="width: 179px;"><?php echo "Health"; ?></th>
                                    <th style="width: 252px;"><?php echo $this->lang->line('weight'); ?></th>
                                </tr>
                            </thead>
                        <?php } ?>
                        <tbody>
                            <?php
                            if (empty($studentList)) {
                            ?>
                                <!-- <tr>
                                <td colspan="7" class="text-danger text-center"><?php echo $this->lang->line('no_record_found'); ?></td>
                            </tr> -->
                                <?php
                            } else {
                                $i = 0;
                                foreach ($studentList as $student) {
                                    $update = $this->studentsession_model->getStudentByStudentId($student['id']);
                                    // echo "<pre>";
                                    // print_r($update);

                                ?>
                                    <tr class="std_adm_<?php echo $i; ?>">
                                        <input type="hidden" name="student_id[]" value="<?php echo $student['student_session_id']; ?>">
                                        <td><?php echo $student['admission_no']; ?></td>
                                        <td><?php echo $student['roll_no']; ?></td>
                                        <td><?php echo $this->customlib->getFullName($student['firstname'], $student['middlename'], $student['lastname'], $sch_setting->middlename, $sch_setting->lastname); ?></td>
                                        <td>
                                            <input type="number" name="height[]" class="form-control" step="any" id="height" value="<?php echo set_value('student_att', $update['height']); ?>">
                                        </td>
                                        <td>
                                            <input type="number" name="weight[]" class="form-control" step="any" id="weight" value="<?php echo set_value('student_att', $update['weight']); ?>">
                                        </td>

                                    </tr>
                            <?php
                                    $i++;
                                }
                            }
                            ?>
                        </tbody>
                        </table>
                        <?php
                        if (!empty($studentList)) {
                        ?>
                            <?php if ($this->rbac->hasPrivilege('healthEntry', 'can_edit')) {
                            ?>
                                <?php
                                ?>
                                <button type="submit" class="allot-fees btn btn-primary btn-sm pull-right" id="load" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Please Wait.."><?php echo $this->lang->line('save'); ?>

                                </button>
                        <?php }
                        } ?>
                </form>
            </div>
        </div>
    </section>
</div>





<script type="text/javascript">
    $(document).ready(function() {
        $('.select2').select2();

    });


    var class_id = '<?php echo set_value('class_id') ?>';
    var section_id = '<?php echo set_value('section_id') ?>';
    getSectionByClass(class_id, section_id);



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


    $(document).on('click', '.editsub', function(e) {
        var $this = $(this);
        var exam_id = $(this).data('exam_id');
        var class_id = $(this).data('class_id');
        var section_id = $(this).data('section_id');
        $(".subjectmodal_header_1").text('Pending Subject List');
        $('#StudentMark').modal('show');

        $.ajax({
            type: "POST",
            url: base_url + "admin/examresult/getSubjectslist",
            data: {
                'exam_id': exam_id,
                'class_id': class_id,
                'section_id': section_id,
            },
            dataType: "json",
            beforeSend: function() {

            },
            success: function(data) {
                //    console.log(data);
                $('.marksEntryFormOne').html(data.page);
                $('#StudentMark').modal('show');
                $('#examfade,#exammodal').css({
                    'display': 'none'
                });

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
                setTimeout(function() {
                    location.reload();
                }, 1000)

            }

        });
    });
</script>