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

                            <?php echo $this->customlib->getCSRF(); ?>
                            <?php if ($this->session->flashdata('msg')) { ?>
                                <?php echo $this->session->flashdata('msg') ?>
                            <?php } ?>
                            <div class="row">
                                <div class="col-sm-3 col-lg-3 col-md-3 ">
                                    <div class="form-group" style="height: 50px;">
                                        <label for="exampleInputEmail1"><?php echo "TC No."; ?></label>
                                        <input type="text" name="tc_certificate_no" id="tc_certificate_no" class="form-control" value="<?php if (isset($update)) {
                                                                                                                                echo $update['tc_certificate_no'];
                                                                                                                            } ?>">
                                    </div>
                                    <span class="text-danger"><?php echo form_error('tc_certificate_no'); ?></span>
                                    <div class="text-danger" id="error-tc_certificate_no"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3 col-lg-3 col-md-3 ">
                                    <div class="form-group" style="height: 50px;">
                                        <label><?php echo $this->lang->line('session'); ?></label>
                                        <select autofocus="" id="session_id" name="session_id" class="form-control select2">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            // print_r($classlist);die;
                                            foreach ($sessionlist as $session) {
                                            ?>
                                                <option value="<?php echo $session['id'] ?>" <?php
                                                                                            if (set_value('session_id') == $session['id']) {
                                                                                                echo "selected=selected";
                                                                                            }
                                                                                            ?><?php echo set_select('session_id', $session['id'], isset($update['st_session_id']) && $update['st_session_id'] == $session['id'] ? true : false); ?>><?php echo $session['session'] ?></option>
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
                                        <label for="exampleInputEmail1"><?php echo "Mother Tongue"; ?></label>
                                        <input type="text" name="mother_tongue" id="mother_tongue" class="form-control" value="<?php if (isset($update)) {
                                                                                                                                    echo $update['mother_tongue'];
                                                                                                                                } ?>">
                                    </div>
                                    <span class="text-danger"><?php echo form_error('mother_tongue'); ?></span>
                                    <div class="text-danger" id="error-mother_tongue"></div>
                                </div>

                                <div class="col-sm-4 col-lg-4 col-md-4 ">
                                    <div class="form-group" style="height: 50px;">
                                        <label for="exampleInputEmail1"><?php echo "Nationality"; ?></label>
                                        <input type="text" name="nationality" id="nationality" class="form-control" value="<?php if (isset($update)) {
                                                                                                                                echo $update['nationality'];
                                                                                                                            } ?>">
                                    </div>
                                    <span class="text-danger"><?php echo form_error('nationality'); ?></span>
                                    <div class="text-danger" id="error-nationality"></div>
                                </div>

                                <div class="col-sm-4 col-lg-4 col-md-4 ">
                                    <div class="form-group" style="height: 50px;">
                                        <label for="exampleInputEmail1"><?php echo "Place Of Birth"; ?></label>
                                        <input type="text" name="pob" id="pob" class="form-control" value="<?php if (isset($update)) {
                                                                                                                echo $update['pob'];
                                                                                                            } ?>">
                                    </div>
                                    <span class="text-danger"><?php echo form_error('pob'); ?></span>
                                    <div class="text-danger" id="error-pob"></div>
                                </div>

                                <div class="col-sm-4 col-lg-4 col-md-4 ">
                                    <div class="form-group" style="height: 50px;">
                                        <label for="exampleInputEmail1"><?php echo "Religion"; ?></label>
                                        <input type="text" name="religion" id="religion" class="form-control" value="<?php if (isset($update)) {
                                                                                                                echo $update['religion'];
                                                                                                            } ?>">
                                    </div>
                                    <span class="text-danger"><?php echo form_error('religion'); ?></span>
                                    <div class="text-danger" id="error-religion"></div>
                                </div>

                                <div class="col-sm-4 col-lg-4 col-md-4 ">
                                    <div class="form-group" style="height: 50px;">
                                        <label for="exampleInputEmail1"><?php echo "Cast"; ?></label>
                                        <input type="text" name="cast" id="cast" class="form-control" value="<?php if (isset($update)) {
                                                                                                                echo $update['cast'];
                                                                                                            } ?>">
                                    </div>
                                    <span class="text-danger"><?php echo form_error('cast'); ?></span>
                                    <div class="text-danger" id="error-cast"></div>
                                </div>

                                <div class="col-sm-4 col-lg-4 col-md-4 ">
                                    <div class="form-group" style="height: 50px;">
                                        <label for="exampleInputEmail1"><?php echo "Date of First Admission with class"; ?></label>
                                        <input type="text" name="first_adm_class" id="first_adm_class" class="form-control date" value="<?php if (isset($update)) {
                                                                                                                                            echo date('d-m-Y', strtotime($update['first_adm_class']));
                                                                                                                                        } ?>">
                                    </div>
                                    <span class="text-danger"><?php echo form_error('first_adm_class'); ?></span>
                                    <div class="text-danger" id="error-first_adm_class"></div>
                                </div>



                                <div class="col-sm-4 col-lg-4 col-md-4 ">
                                    <div class="form-group" style="height: 50px;">
                                        <label for="exampleInputEmail1"><?php echo "Previous School & Board"; ?></label>
                                        <input type="text" name="prev_school_board" id="prev_school_board" class="form-control" value="<?php if (isset($update)) {
                                                                                                                                            echo $update['prev_school_board'];
                                                                                                                                        } ?>">
                                    </div>
                                    <span class="text-danger"><?php echo form_error('prev_school_board'); ?></span>
                                    <div class="text-danger" id="error-prev_school_board"></div>
                                </div>

                                <div class="col-sm-4 col-lg-4 col-md-4 ">
                                    <div class="form-group" style="height: 50px;">
                                        <label for="exampleInputEmail1"><?php echo "Failed & Repetated Class"; ?></label>
                                        <input type="text" name="repeated_class" id="repeated_class" class="form-control" value="<?php if (isset($update)) {
                                                                                                                                        echo $update['repeated_class'];
                                                                                                                                    } ?>">
                                    </div>
                                    <span class="text-danger"><?php echo form_error('repeated_class'); ?></span>
                                    <div class="text-danger" id="error-repeated_class"></div>
                                </div>

                                <div class="col-sm-4 col-lg-4 col-md-4 ">
                                    <div class="form-group" style="height: 70px;">
                                        <label for="exampleInputEmail1"><?php echo "Subject studied"; ?></label>
                                        <!-- <input type="text" name="subject_studied" id="subject_studied" class="form-control" value="<?php if (isset($update)) {
                                                                                                                                        echo $update['subject_studied'];
                                                                                                                                    } ?>"> -->
                                        <select autofocus="" id="subject_studied" name="subject_studied[]" class="form-control select2" multiple style="min-height: 150px;overflow-y: auto;">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            // print_r($classlist);die;
                                            $subjects = explode(',',$update['subject_studied']);
                                            foreach ($subjectlist as $subject) {
                                            ?>
                                                <!-- <option value="<?php echo $subject['name'] ?>" <?php
                                                                                            if (set_value('subject_id') == $subject['name']) {
                                                                                                echo "selected=selected";
                                                                                            }
                                                                                            ?><?php echo set_select('subject_studied', $subject['name'], isset($update['subject_studied']) && $update['subject_studied'] == $subject['name'] ? true : false); ?>><?php echo $subject['name'] ?></option> -->
                                                <option value="<?php echo $subject['name'] ?>" <?php
                                                                                            if (set_value('subject_id') == $subject['name']) {
                                                                                                echo "selected=selected";
                                                                                            }
                                                                                            ?> <?php  if(in_array($subject['name'],$subjects)) {echo "selected=selected";} ?>><?php  echo $subject['name']; ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <span class="text-danger"><?php echo form_error('subject_studied'); ?></span>
                                    <div class="text-danger" id="error-subject_studied"></div>
                                </div>

                                <div class="col-sm-4 col-lg-4 col-md-4 ">
                                    <div class="form-group" style="height: 50px;">
                                        <label for="exampleInputEmail1"><?php echo "Passed and promoted"; ?></label>
                                        <input type="text" name="passed_promoted" id="passed_promoted" class="form-control" value="<?php if (isset($update)) {
                                                                                                                                        echo $update['passed_promoted'];
                                                                                                                                    } ?>">
                                    </div>
                                    <span class="text-danger"><?php echo form_error('passed_promoted'); ?></span>
                                    <div class="text-danger" id="error-passed_promoted"></div>
                                </div>

                                <div class="col-sm-4 col-lg-4 col-md-4 ">
                                    <div class="form-group" style="height: 50px;">
                                        <label for="exampleInputEmail1"><?php echo "School dues"; ?></label>
                                        <input type="text" name="school_dues" id="school_dues" class="form-control" value="<?php if (isset($update)) {
                                                                                                                                echo $update['school_dues'];
                                                                                                                            } ?>">
                                    </div>
                                    <span class="text-danger"><?php echo form_error('school_dues'); ?></span>
                                    <div class="text-danger" id="error-school_dues"></div>
                                </div>

                                <div class="col-sm-4 col-lg-4 col-md-4 ">
                                    <div class="form-group" style="height: 50px;">
                                        <label for="exampleInputEmail1"><?php echo "Fee concession"; ?></label>
                                        <input type="text" name="fee_concession" id="fee_concession" class="form-control" value="<?php if (isset($update)) {
                                                                                                                                        echo $update['fee_concession'];
                                                                                                                                    } ?>">
                                    </div>
                                    <span class="text-danger"><?php echo form_error('fee_concession'); ?></span>
                                    <div class="text-danger" id="error-fee_concession"></div>
                                </div>
                                <div class="col-sm-4 col-lg-4 col-md-4 ">
                                    <div class="form-group" style="height: 50px;">
                                        <label for="exampleInputEmail1"><?php echo "Working days in the academic session"; ?></label>
                                        <input type="text" name="working_academic" id="working_academic" class="form-control" value="<?php if (isset($update)) {
                                                                                                                                            echo $update['working_academic'];
                                                                                                                                        } ?>">
                                    </div>
                                    <span class="text-danger"><?php echo form_error('working_academic'); ?></span>
                                    <div class="text-danger" id="error-working_academic"></div>
                                </div>

                                <div class="col-sm-4 col-lg-4 col-md-4 ">
                                    <div class="form-group" style="height: 50px;">
                                        <label for="exampleInputEmail1"><?php echo "Working days pupil present"; ?></label>
                                        <input type="text" name="working_present" id="working_present" class="form-control" value="<?php if (isset($update)) {
                                                                                                                                        echo $update['working_present'];
                                                                                                                                    } ?>">
                                    </div>
                                    <span class="text-danger"><?php echo form_error('working_present'); ?></span>
                                    <div class="text-danger" id="error-working_present"></div>
                                </div>

                                <div class="col-sm-4 col-lg-4 col-md-4 ">
                                    <div class="form-group" style="height: 50px;">
                                        <label for="exampleInputEmail1"><?php echo "NCC Cadet/boy Scout/Girl Guide"; ?></label>
                                        <input type="text" name="special_category" id="special_category" class="form-control" value="<?php if (isset($update)) {
                                                                                                                                            echo $update['special_category'];
                                                                                                                                        } ?>">
                                    </div>
                                    <span class="text-danger"><?php echo form_error('special_category'); ?></span>
                                    <div class="text-danger" id="error-special_category"></div>
                                </div>

                                <div class="col-sm-4 col-lg-4 col-md-4 ">
                                    <div class="form-group" style="height: 50px;">
                                        <label for="exampleInputEmail1"><?php echo "Extra-currical activities"; ?></label>
                                        <input type="text" name="curricular_activities" id="curricular_activities" class="form-control" value="<?php if (isset($update)) {
                                                                                                                                                    echo $update['curricular_activities'];
                                                                                                                                                } ?>">
                                    </div>
                                    <span class="text-danger"><?php echo form_error('curricular_activities'); ?></span>
                                    <div class="text-danger" id="error-curricular_activities"></div>
                                </div>

                                <div class="col-sm-4 col-lg-4 col-md-4 ">
                                    <div class="form-group" style="height: 50px;">
                                        <label for="exampleInputEmail1"><?php echo "General Conduct"; ?></label>
                                        <!-- <input type="text" name="general_conduct" id="general_conduct" class="form-control" value="<?php if (isset($update)) {
                                                                                                                                        echo $update['general_conduct'];
                                                                                                                                    } ?>"> -->
                                        <input list="general_conducts" name="general_conduct" value="<?php if (isset($update)) {echo $update['general_conduct'];} ?>" id="general_conduct" class="form-control">
                                        <datalist id="general_conducts">
                                        <?php 
                                        if (!empty($conductlist)) {
                                            foreach ($conductlist as  $condRow) {       
                                        ?>
                                            <option value="<?php echo $condRow['general_conduct'];?>">
                                            <?php }
                                        }?>
                                        </datalist>
                                    </div>
                                    <span class="text-danger"><?php echo form_error('general_conduct'); ?></span>
                                    <div class="text-danger" id="error-general_conduct"></div>
                                </div>

                                <div class="col-sm-4 col-lg-4 col-md-4 ">
                                    <div class="form-group" style="height: 50px;">
                                        <label for="exampleInputEmail1"><?php echo "Date of Application"; ?></label>
                                        <input type="text" name="doa" id="doa" class="form-control date" value="<?php if (isset($update)) {
                                                                                                                    echo  date('d-m-Y', strtotime($update['doa']));
                                                                                                                }else{ echo  date('d-m-Y');} ?>">
                                    </div>
                                    <span class="text-danger"><?php echo form_error('doa'); ?></span>
                                    <div class="text-danger" id="error-doa"></div>
                                </div>

                                <div class="col-sm-4 col-lg-4 col-md-4 ">
                                    <div class="form-group" style="height: 50px;">
                                        <label for="exampleInputEmail1"><?php echo "Day of issue of certificate"; ?></label>
                                        <input type="text" name="doic" id="doic" class="form-control date" value="<?php if (isset($update)) {
                                                                                                                        echo  date('d-m-Y', strtotime($update['doic']));
                                                                                                                    }else{ echo  date('d-m-Y');} ?>">
                                    </div>
                                    <span class="text-danger"><?php echo form_error('doic'); ?></span>
                                    <div class="text-danger" id="error-doic"></div>
                                </div>

                                <div class="col-sm-4 col-lg-4 col-md-4 ">
                                    <div class="form-group" style="height: 50px;">
                                        <label for="exampleInputEmail1"><?php echo "Reason for leaving school"; ?></label>
                                        <!-- <input type="text" name="reason_leave" id="reason_leave" class="form-control" value="<?php if (isset($update)) {
                                                                                                                                        echo $update['reason_leave'];
                                                                                                                                    } ?>"> -->
                                        <input list="reason_leaves" name="reason_leave" value="<?php if (isset($update)) {echo $update['reason_leave'];} ?>" id="reason_leave" class="form-control">
                                        <datalist id="reason_leaves">
                                        <?php 
                                        if (!empty($reasonlist)) {
                                            foreach ($reasonlist as  $value) {       
                                        ?>
                                            <option value="<?php echo $value['reason_leave'];?>">
                                            <?php }
                                        }?>
                                        </datalist>
                                    </div>
                                    <span class="text-danger"><?php echo form_error('reason_leave'); ?></span>
                                    <div class="text-danger" id="error-reason_leave"></div>
                                </div>


                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <input type="hidden" name="student_session_id" id="student_session_id" value="<?php if (isset($update)) {
                                                                                                    echo $update['student_session_id'];
                                                                                                } ?>">

                                        <button type="submit" name="search" value="search_filter" class="btn btn-primary pull-right btn-sm checkbox-toggle" style="margin-left: 12px;"> <?php echo $this->lang->line('save'); ?></button>
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
                    div_data += "<option value=" + obj.studentses_id + " " + sel + ">" + obj.firstname + " " + obj.lastname + " ("+ obj.roll_no + ")" + "</option>";
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
        var pob = $('#pob').val();
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
                $('#religion').val(data.religion);
                $('#cast').val(data.cast);
                if (pob == "") {
                    $('#pob').val(data.place_of_birth);
                }
                if (nationality == "") {
                    $('#nationality').val('Indian');
                }
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
            url: "<?php echo site_url('admin/certificate/trutc_registerValid') ?>",
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
                window.location.href='<?php echo site_url('admin/certificate/tru_tc')?>';
            }
        }, 500);

        return true;
    }
</script>