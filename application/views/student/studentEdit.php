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
                <div class="box box-primary">
                    <div class="">
                        <div class="pull-right box-tools">
                        </div>
                    </div>
                    <form action="<?php echo site_url("student/edit/" . $id) ?>" id="employeeform" name="employeeform" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                        <div class="box-body">
                            <div class="tshadow mb25 bozero">
                            <div class="around10">
                                <div class="row">
                                <h4 class="pagetitleh2" ><?php echo $this->lang->line('profile'); ?></h4>
                                <div class="col-md-2">
                                <img style="width: 150px;padding-top: 2px;height: 160px;" src="<?php
                                if (!empty($student["image"])) {
                                    echo base_url() . $student["image"];
                                } else {

                                    if ($student['gender'] == 'Female') {
                                        echo base_url() . "uploads/student_images/default_female.jpg";
                                    } elseif ($student['gender'] == 'Male') {
                                        echo base_url() . "uploads/student_images/default_male.jpg";
                                    }
                                }
                                ?>" alt="User Image" srcset="">
                                </div>
                                <?php
                                        if ($sch_setting->student_photo) { ?>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputFile"><?php echo $this->lang->line('student') . "  " . $this->lang->line('photo'); ?></label>
                                                    <input class="filestyle form-control" type='file' name='file' id="file" size='20' />
                                                </div>
                                                <span class="text-danger"><?php echo form_error('file'); ?></span>
                                            </div>
                                        <?php } ?>
                                </div>
                                </div>
                                <h3 class="pagetitleh2"> <?php echo $this->lang->line('edit'); ?> <?php echo $this->lang->line('student'); ?></h3>
                                <div class="around10">
                                    <?php if ($this->session->flashdata('msg')) { ?>
                                        <?php echo $this->session->flashdata('msg'); unset($_SESSION['msg']); ?>
                                    <?php } ?>
                                    <?php echo $this->customlib->getCSRF(); ?>
                                    <input type="hidden" name="student_id"  id="student_id"  value="<?php echo set_value('id', $student['id']); ?>">
                                    <input type="hidden" name="sibling_name" value="<?php echo set_value('sibling_name', 0); ?>" id="sibling_name_next">
                                    <input type="hidden" name="sibling_id" value="<?php echo set_value('sibling_id', 0); ?>" id="sibling_id">
                                    <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1"><?php echo "Application No"; ?></label> 

                                            <input autofocus="" id="application_no" name="application_no" placeholder="" type="text" class="form-control" value="<?php echo set_value('application_no',$student['application_no']); ?>" />
                                            <span class="text-danger"><?php echo form_error('application_no'); ?></span>
                                        </div>
                                    </div>
                                    </div>
                                    <div class="row">
                                        <?php if (!$adm_auto_insert) { ?>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('admission_no'); ?></label><small class="req"> *</small>
                                                    <input autofocus="" id="admission_no" name="admission_no" placeholder="" type="text" class="form-control" value="<?php echo set_value('admission_no', $student['admission_no']); ?>" />
                                                    <span class="text-danger"><?php echo form_error('admission_no'); ?></span>
                                                </div>
                                            </div>
                                        <?php }
                                        if ($sch_setting->roll_no) { ?>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('roll_no'); ?></label>
                                                    <input id="roll_no" name="roll_no" placeholder="" type="text" class="form-control" value="<?php echo set_value('roll_no', $student['roll_no']); ?>" />
                                                    <span class="text-danger"><?php echo form_error('roll_no'); ?></span>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('class'); ?></label><small class="req"> *</small>
                                                <!-- <select id="class_id" name="class_id" class="form-control">
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                    <?php
                                                    foreach ($classlist as $class) {
                                                    ?>
                                                        <option value="<?php echo $class['id'] ?>" <?php
                                                                                                    if ($student['class_id'] == $class['id']) {
                                                                                                        echo "selected =selected";
                                                                                                    }
                                                                                                    ?>><?php echo $class['class'] ?></option>
                                                    <?php
                                                        $count++;
                                                    }
                                                    ?>
                                                </select> -->
                                                <input id="" readonly name="" placeholder="" type="text" class="form-control" value="<?php echo $student['class']; ?>" />
                                                <input id="class_id" name="class_id" placeholder="" type="hidden" class="form-control" value="<?php echo $student['class_id']; ?>" />
                                                <span class="text-danger"><?php echo form_error('class_id'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('section'); ?></label><small class="req"> *</small>
                                                <!-- <select id="section_id" name="section_id" class="form-control">
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                </select> -->
                                                <input id="" readonly name="" placeholder="" type="text" class="form-control" value="<?php echo $student['section']; ?>" />
                                                <input id="section_id" name="section_id" placeholder="" type="hidden" class="form-control" value="<?php echo $student['section_id']; ?>" />
                                                <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('first_name'); ?></label><small class="req"> *</small>
                                                <input id="firstname" name="firstname" placeholder="" type="text" class="form-control" value="<?php echo set_value('firstname', $student['firstname']); ?>" />
                                                <input type="hidden" name="studentid" value="<?php echo $student["id"] ?>">
                                                <span class="text-danger"><?php echo form_error('first_name'); ?></span>
                                            </div>
                                        </div>
                                        <?php if ($sch_setting->middlename) { ?>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('middle_name'); ?></label>
                                                    <input id="middlename" name="middlename" placeholder="" type="text" class="form-control" value="<?php echo set_value('middlename', $student['middlename']); ?>" />
                                                    <span class="text-danger"><?php echo form_error('middlename'); ?></span>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <?php if ($sch_setting->lastname) { ?>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('last_name'); ?></label>
                                                    <input id="lastname" name="lastname" placeholder="" type="text" class="form-control" value="<?php echo set_value('lastname', $student['lastname']); ?>" />
                                                    <span class="text-danger"><?php echo form_error('lastname'); ?></span>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo "Student Name(As per aadhar)"; ?><small class="req"> *</small></label>
                                                <input id="aadhar_name" name="aadhar_name" placeholder="" type="text" class="form-control" value="<?php echo set_value('aadhar_name', $student['aadhar_name']); ?>" />
                                                <span class="text-danger"><?php echo form_error('aadhar_name'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputFile"> <?php echo $this->lang->line('gender'); ?> </label><small class="req"> *</small>
                                                <select class="form-control" name="gender">
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                    <?php
                                                    foreach ($genderList as $key => $value) {
                                                    ?>
                                                        <option value="<?php echo $key; ?>" <?php
                                                                                            if ($student['gender'] == $key) {
                                                                                                echo "selected";
                                                                                            }
                                                                                            ?>><?php echo $value; ?></option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('gender'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('date_of_birth'); ?></label><small class="req"> *</small>
                                                <?php
                                                $dob = "";
                                                if ($student['dob'] != '0000-00-00' && $student['dob'] != '') {
                                                    $dob = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($student['dob']));
                                                }
                                                ?>

                                                <input id="dob" name="dob" placeholder="" type="text" class="form-control date" value="<?php echo set_value('dob', $dob) ?>" />

                                                <span class="text-danger"><?php echo form_error('dob'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-xs-12">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo "Place Of Birth"; ?></label>
                                                <?php ?>
                                                <input type="text" id="place_of_birth" value="<?php echo set_value('place_of_birth',$student['place_of_birth']); ?>" name="place_of_birth" class="form-control">
                                                <span class="text-danger"><?php echo form_error('place_of_birth'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-xs-12">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('mobile_no'); ?></label>
                                                    <input id="mobileno" name="mobileno" placeholder="" type="text" class="form-control" value="<?php echo set_value('mobileno', $student['mobileno']); ?>" />
                                                    <span class="text-danger"><?php echo form_error('mobileno'); ?></span>
                                                </div>

                                        </div>
                                        <div class="col-md-3 col-xs-12">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo "Disability (%)"; ?></label>
                                                    <?php ?>
                                                    <input type="text" id="disability" value="<?php echo set_value('disability',$student['disability']); ?>" name="disability" class="form-control">
                                                    <span class="text-danger"><?php echo form_error('disability'); ?></span>
                                                </div>
                                            </div>
                                    </div>
                                    <div class="row">
                                        <?php if ($sch_setting->category) {
                                        ?>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('category'); ?></label>
                                                    <select id="category_id" name="category_id" class="form-control">
                                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                        <?php
                                                        foreach ($categorylist as $category) {
                                                        ?>
                                                            <option value="<?php echo $category['id'] ?>" <?php
                                                                                                            if ($student['category_id'] == $category['id']) {
                                                                                                                echo "selected =selected";
                                                                                                            }
                                                                                                            ?>><?php echo $category['category']; ?></option>
                                                        <?php
                                                            $count++;
                                                        }
                                                        ?>
                                                    </select>
                                                    <span class="text-danger"><?php echo form_error('category_id'); ?></span>
                                                </div>
                                            </div>
                                        <?php }
                                        if ($sch_setting->religion) { ?>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('religion'); ?></label>
                                                    <input id="religion" name="religion" placeholder="" type="text" class="form-control" value="<?php echo set_value('religion', $student['religion']); ?>" />
                                                    <span class="text-danger"><?php echo form_error('religion'); ?></span>
                                                </div>
                                            </div>
                                        <?php }
                                        if ($sch_setting->cast) { ?>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('cast'); ?></label>
                                                    <input id="cast" name="cast" placeholder="" type="text" class="form-control" value="<?php echo set_value('cast', $student['cast']); ?>" />
                                                    <span class="text-danger"><?php echo form_error('cast'); ?></span>
                                                </div>
                                            </div>
                                        <?php }
                                        if ($sch_setting->mobile_no) { ?>
                                            <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo "Sub Caste"; ?></label>
                                                <?php ?>
                                                <input type="text" id="sub_caste" value="<?php echo set_value('sub_caste',$student['sub_caste']); ?>" name="sub_caste" class="form-control">
                                                <span class="text-danger"><?php echo form_error('sub_caste'); ?></span>
                                            </div>                                                
                                            </div>
                                        <?php } ?>
                                        
                                    </div>
                                    <div class="row">
                                    <?php
                                        if ($sch_setting->student_email) { ?>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('email'); ?></label>
                                                    <input id="email" name="email" placeholder="" type="text" class="form-control" value="<?php echo set_value('email', $student['email']); ?>" />
                                                    <span class="text-danger"><?php echo form_error('email'); ?></span>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <?php if ($sch_setting->admission_date) {
                                            $admission_date = "";
                                            if ($student['admission_date'] != '0000-00-00' && $student['admission_date'] != '') {
                                                $admission_date = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($student['admission_date']));
                                            }

                                        ?>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('admission_date'); ?></label>
                                                    <input id="admission_date" name="admission_date" placeholder="" type="text" class="form-control date" value="<?php echo set_value('admission_date', $admission_date) ?>" readonly="readonly" />
                                                    <span class="text-danger"><?php echo form_error('admission_date'); ?></span>
                                                </div>
                                            </div>
                                        <?php }?>
                                        
                                        <?php
                                        if ($sch_setting->is_blood_group) {
                                        ?>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('blood_group'); ?></label>
                                                    <?php ?>
                                                    <select class="form-control" rows="3" placeholder="" name="blood_group">
                                                        <option value=""><?php echo $this->lang->line('select') ?></option>
                                                        <?php foreach ($bloodgroup as $bgkey => $bgvalue) {
                                                        ?>
                                                            <option value="<?php echo $bgvalue ?>" <?php
                                                                                                    if ($bgvalue == $student["blood_group"]) {
                                                                                                        echo "selected";
                                                                                                    }
                                                                                                    ?>><?php echo $bgvalue ?></option>

                                                        <?php } ?>
                                                    </select>

                                                    <span class="text-danger"><?php echo form_error('house'); ?></span>
                                                </div>
                                            </div>
                                        <?php }
                                        if ($sch_setting->is_student_house) {
                                        ?>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('house') ?></label>
                                                    <select class="form-control" rows="3" placeholder="" name="house">
                                                        <option value=""><?php echo $this->lang->line('select') ?></option>
                                                        <?php foreach ($houses as $hkey => $hvalue) {
                                                        ?>
                                                            <option value="<?php echo $hvalue["id"] ?>" <?php
                                                                                                        if ($hvalue["id"] == $student["house_id"]) {
                                                                                                            echo "selected";
                                                                                                        }
                                                                                                        ?>><?php echo $hvalue["house_name"] ?></option>

                                                        <?php } ?>
                                                    </select>
                                                    <span class="text-danger"><?php echo form_error('house'); ?></span>
                                                </div>
                                            </div>

                                        <?php }
                                        if ($sch_setting->student_height) {
                                        ?>
                                            <div class="col-md-3 col-xs-12">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('height'); ?></label>
                                                    <?php ?>
                                                    <input type="text" value="<?php echo $student["height"] ?>" name="height" class="form-control" value="<?php echo set_value('height', $student['height']); ?>">
                                                    <span class="text-danger"><?php echo form_error('height'); ?></span>
                                                </div>
                                            </div>
                                        <?php }
                                        if ($sch_setting->student_weight) {
                                        ?>
                                            <div class="col-md-3 col-xs-12">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('weight'); ?></label>
                                                    <?php ?>
                                                    <input type="text" value="<?php echo $student["weight"] ?>" name="weight" class="form-control" value="<?php echo set_value('weight', $student['weight']); ?>">
                                                    <span class="text-danger"><?php echo form_error('height'); ?></span>
                                                </div>
                                            </div>
                                        <?php }
                                        if ($sch_setting->measurement_date) {
                                            $measurement_date = "";
                                            if ($student['admission_date'] != '0000-00-00' && $student['admission_date'] != '') {
                                                $measurement_date = $this->customlib->dateformat($student['measurement_date']);
                                            }
                                        ?>
                                            <div class="col-md-3 col-xs-12">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('measurement_date'); ?></label>

                                                    <input id="measure_date" name="measure_date" placeholder="" type="text" class="form-control date" value="<?php echo set_value('measure_date', $measurement_date); ?>" readonly="readonly" />
                                                    <span class="text-danger"><?php echo form_error('measure_date'); ?></span>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <div class="col-md-3 col-xs-12">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo "PEN No (UID No)"; ?></label>
                                                    <?php ?>
                                                    <input type="text" id="uid_no" value="<?php echo set_value('uid_no',$student['uid_no']); ?>" name="uid_no" class="form-control">
                                                    <span class="text-danger"><?php echo form_error('uid_no'); ?></span>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-xs-12">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo "SARAL ID (Department Student ID)"; ?></label>
                                                    <?php ?>
                                                    <input type="text" id="dep_student_id" value="<?php echo set_value('dep_student_id',$student['dep_student_id']); ?>" name="dep_student_id" class="form-control">
                                                    <span class="text-danger"><?php echo form_error('dep_student_id'); ?></span>
                                                </div>
                                            </div>
                                        <div class="col-md-3 col-xs-12">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo "TC No"; ?></label>
                                                    <?php ?>
                                                    <input type="text" id="tc_no" value="<?php echo set_value('tc_no',$student['tc_no']); ?>" name="tc_no" class="form-control">
                                                    <span class="text-danger"><?php echo form_error('tc_no'); ?></span>
                                                </div>
                                            </div>
                                        <div class="col-md-3 col-xs-12">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo "Duplicate TC No"; ?></label>
                                                    <?php ?>
                                                    <input type="text" id="duplicate_tc_no" value="<?php echo set_value('duplicate_tc_no',$student['duplicate_tc_no']); ?>" name="duplicate_tc_no" class="form-control">
                                                    <span class="text-danger"><?php echo form_error('duplicate_tc_no'); ?></span>
                                                </div>
                                            </div>
                                        <div class="col-md-3 col-xs-12">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo "Disability Type"; ?></label>
                                                    <?php ?>
                                                    <input type="text" id="disability_type" value="<?php echo set_value('disability_type',$student['disability_type']); ?>" name="disability_type" class="form-control">
                                                    <span class="text-danger"><?php echo form_error('disability_type'); ?></span>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-xs-12">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo "Disability Card No"; ?></label>
                                                    <?php ?>
                                                    <input type="text" id="disability_card_no" value="<?php echo set_value('disability_card_no',$student['disability_card_no']); ?>" name="disability_card_no" class="form-control">
                                                    <span class="text-danger"><?php echo form_error('disability_card_no'); ?></span>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-xs-12">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo "Aadhar No"; ?></label>
                                                    <?php ?>
                                                    <input type="text" id="adhar_no" value="<?php echo set_value('adhar_no',$student['adhar_no']); ?>" name="adhar_no" class="form-control">
                                                    <span class="text-danger"><?php echo form_error('adhar_no'); ?></span>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-xs-12">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo "Apaar Id"; ?></label>
                                                    <?php ?>
                                                    <input type="text" id="aapar_id" value="<?php echo set_value('aapar_id',$student['aapar_id']); ?>" name="aapar_id" class="form-control">
                                                    <span class="text-danger"><?php echo form_error('aapar_id'); ?></span>
                                                </div>
                                            </div>
                                        
                                        <div class="col-md-3 pt25">
                                            <div class="row">
                                                <div class="col-lg-5 col-md-6 col-sm-3 col-xs-5">
                                                <?php if($add_sibling) { ?>
                                                    <button type="button" class="btn btn-sm btn-primary mysiblings anchorbtn"><i class="fa fa-plus"></i> <?php echo $this->lang->line('add'); ?> <?php echo $this->lang->line('sibling'); ?></button>
                                                    <?php } ?>
                                                </div>
                                                <div class="col-lg-7 col-md-6 col-sm-9 col-xs-7">
                                                    <div class="pt6 overflowtextdot">
                                                        <?php
                                                        $sibling_id = $this->student_model->getsibling($student["id"]);
                                                        if (!empty($sibling_id)) {
                                                            $studentss = $this->student_model->get($sibling_id["sibling_student_id"]);
                                                            if (!empty($studentss)) {
                                                            $full_name = $studentss['firstname'] . " " . $studentss['lastname'];
                                                            }}
                                                        ?>
                                                        <span id="sibling_name" class="label label-success"><?php echo set_value('sibling_name', !empty($full_name) ? $full_name : ""); ?></span>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>


                                    
                                    <div class="col-md-3 pt25">
                                        <div class="row">
                                            <div class="col-lg-5 col-md-6 col-sm-3 col-xs-5">
                                                <button type="button" class="btn btn-sm btn-primary mymember anchorbtn"><i class="fa fa-plus"></i> <?php echo $this->lang->line('add'); ?> <?php echo "Member"; ?></button>
                                            </div>
                                            <div class="col-lg-7 col-md-6 col-sm-9 col-xs-7">
                                                <div class="pt6 overflowtextdot">
                                                    <?php
                                                    $member = $this->student_model->getmember($student["id"]);
                                                    if (!empty($member)) {
                                                        $full_namemember = $member['member_name'];
                                                    }
                                                    ?>
                                                    <span id="memberName" class="label label-success"><?php echo set_value('memberName', !empty($full_namemember) ? $full_namemember : ""); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3 pt25">
                                        <div class="row">
                                            <div class="col-lg-5 col-md-6 col-sm-3 col-xs-5">
                                                <button type="button" data-current_student_id="<?php echo $student['id'];?>" class="btn btn-sm btn-primary referred anchorbtn"><i class="fa fa-plus"></i> <?php echo "Refer"; ?></button>
                                            </div>
                                            <div class="col-lg-7 col-md-6 col-sm-9 col-xs-7">
                                                <div class="pt6 overflowtextdot">
                                                    <!-- <?php
                                                            $member = $this->student_model->getmember($student["id"]);
                                                            if (!empty($member)) {
                                                                $full_namemember = $member['member_name'];
                                                            }
                                                            ?>
                                                    <span id="memberName" class="label label-success"><?php echo set_value('memberName', !empty($full_namemember) ? $full_namemember : ""); ?></span> -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                    <div class="row">

                                        <?php
                                        echo display_custom_fields('students', $student['id']);
                                        ?>
                                    </div>
                                </div>
                            </div>


                            <?php
                            
                            if (!empty($siblings) || !empty($siblings_reverse)) {
                            ?>
                                <div class="tshadow mb25 bozero sibling_div relative">
                                    <h3 class="pagetitleh2"><?php echo $this->lang->line('sibling'); ?></h3>
                                    <?php if($del_sibling) { ?>
                                    <div class="box-tools sibbtnposition">
                                        <button type="button" class="btn btn-primary btn-sm remove_sibling"><?php echo $this->lang->line('remove'); ?> <?php echo $this->lang->line('sibling'); ?>
                                        </button>
                                    </div>
                                    <?php } ?>

                                    <div class="around10">
                                        <div class="row">
                                            <input type="hidden" name="siblings_counts" class="siblings_counts" value="<?php echo $siblings_counts; ?>">
                                            <?php
                                            if (empty($siblings)) {
                                            } else {

                                                foreach ($siblings as $sibling_key => $sibling_value) {
                                            ?>
                                                    <div class="col-xs-12 col-sm-6 col-md-4 sib_div" id="sib_div_<?php echo $sibling_value->id ?>" data-sibling_id="<?php echo $sibling_value->id ?>">
                                                        <div class="withsiblings">
                                                            <img src="<?php echo base_url() . $sibling_value->image ?>" alt="" class="" />
                                                            <div class="withsiblings-content">
                                                                <h5><a href="#"><?php echo $this->customlib->getFullname($sibling_value->firstname, $sibling_value->middlename, $sibling_value->lastname, $sch_setting->middlename, $sch_setting->lastname) ?></a></h5>
                                                                <p>
                                                                    <b><?php echo $this->lang->line('admission_no'); ?></b>:<?php echo $sibling_value->admission_no; ?><br />
                                                                    <b><?php echo $this->lang->line('class'); ?></b>:<?php echo $sibling_value->class; ?><br />
                                                                    <b><?php echo $this->lang->line('section'); ?></b>:<?php echo $sibling_value->section; ?>
                                                                </p>
                                                                <!-- Split button -->
                                                            </div>
                                                        </div>
                                                    </div>
                                            <?php
                                                }
                                            }
                                            if (empty($siblings_reverse)) {
                                            } else {

                                                foreach ($siblings_reverse as $sibling_key => $sibling_value) {
                                            ?>
                                                    <div class="col-xs-12 col-sm-6 col-md-4 sib_div" id="sib_div_<?php echo $sibling_value->id ?>" data-sibling_id="<?php echo $sibling_value->id ?>">
                                                        <div class="withsiblings">
                                                            <img src="<?php echo base_url() . $sibling_value->image ?>" alt="" class="" />
                                                            <div class="withsiblings-content">
                                                                <h5><a href="#"><?php echo $this->customlib->getFullname($sibling_value->firstname, $sibling_value->middlename, $sibling_value->lastname, $sch_setting->middlename, $sch_setting->lastname) ?></a></h5>
                                                                <p>
                                                                    <b><?php echo $this->lang->line('admission_no'); ?></b>:<?php echo $sibling_value->admission_no; ?><br />
                                                                    <b><?php echo $this->lang->line('class'); ?></b>:<?php echo $sibling_value->class; ?><br />
                                                                    <b><?php echo $this->lang->line('section'); ?></b>:<?php echo $sibling_value->section; ?>
                                                                </p>
                                                                <!-- Split button -->
                                                            </div>
                                                        </div>
                                                    </div>
                                            <?php
                                                }
                                            }

                                            ?>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            }
                            ?>
                            <div class="around10">
                                <div class="row">
                                    <h4 class="pagetitleh2"><?php echo $this->lang->line('discount'); ?></h4>
                                    <br>
                                    <?php
                                     $this->db->where('student_id',$student['id']);
                                     $this->db->where('session_id',$session);
                                    $student_session= $this->db->get('student_session')->row_array();

                                    $this->db->where('student_session_id', $student_session['id']);
                                    $this->db->where('fees_discount_id', 5);
                                    $discount = $this->db->get('student_fees_discounts')->row_array();

                                    $this->db->where('student_session_id', $student_session['id']);
                                    $this->db->where('fees_discount_id', 7);
                                    $latediscount = $this->db->get('student_fees_discounts')->row_array();

                                    if (!empty($latediscount)) {
                                        $lateamt = $latediscount['amount'];
                                    }else {
                                        $lateamt = 0;
                                    }

                                    if (!empty($discount)) {
                                        $amt = $discount['amount'];
                                        if ($discount['status'] == "assigned") {
                                            $readonly = "";
                                         }else {
                                             $readonly = "readonly";
                                         }                                        
                                    }else{
                                        $amt = '0';
                                        $readonly = "";
                                    }

                                    
                                     
                                    ?>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('amount'); ?></label>
                                            <input id="amount" name="amount" <?php echo $readonly;?> placeholder="" type="text" class="form-control " value="<?php echo set_value('amount',$amt); ?>" />
                                            <span class="text-danger"><?php echo form_error('amount'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1"><?php echo "Late Admission Disount"; ?></label>
                                            <input id="late_adm_discount" name="late_adm_discount"  placeholder="" type="text" class="form-control " value="<?php echo set_value('late_adm_discount',$lateamt); ?>" />
                                            <span class="text-danger"><?php echo form_error('late_adm_discount'); ?></span>
                                        </div>
                                    </div>
                                </div>
                                </div>
                            <?php if ($sch_setting->route_list) {
                            ?>
                                <?php
                                if ($this->module_lib->hasActive('transport')) {
                                ?>
                                    <div class="tshadow mb25 bozero">
                                        <h3 class="pagetitleh2">
                                            <?php echo $this->lang->line('transport') . " " . $this->lang->line('details'); ?>
                                        </h3>

                                        <div class="around10">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1">
                                                            <?php echo $this->lang->line('route_list'); ?>
                                                        </label>
                                                        <select class="form-control" name="vehroute_id" id="vehroute_id">


                                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                            <?php
                                                            foreach ($vehroutelist as $vehroute) {
                                                            ?>
                                                                <optgroup label=" <?php echo $vehroute->route_title; ?>">
                                                                    <?php
                                                                    $vehicles = $vehroute->vehicles;
                                                                    if (!empty($vehicles)) {
                                                                        foreach ($vehicles as $key => $value) {

                                                                            $st = set_value('vehroute_id', $student['vehroute_id']) == $value->vec_route_id ? true : false;
                                                                    ?>

                                                                            <option value="<?php echo $value->vec_route_id ?>" <?php echo set_select('vehroute_id', $value->vec_route_id, $st); ?> data-fee="<?php echo $vehroute->fare; ?>">
                                                                                <?php echo $value->vehicle_no ?>
                                                                            </option>
                                                                    <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </optgroup>
                                                            <?php
                                                            }
                                                            ?>
                                                        </select>
                                                        <span class="text-danger"><?php echo form_error('transport_fees'); ?></span>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                <?php } ?> <?php } ?>
                            <?php if ($sch_setting->hostel_id) {
                            ?>
                                <?php
                                if ($this->module_lib->hasActive('hostel')) {
                                ?>
                                    <div class="tshadow mb25 bozero">
                                        <h3 class="pagetitleh2">
                                            <?php echo $this->lang->line('hostel'); ?></label> <?php echo $this->lang->line('details'); ?></label>
                                        </h3>

                                        <div class="around10">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('hostel'); ?></label>

                                                        <select class="form-control" id="hostel_id" name="hostel_id">

                                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                            <?php
                                                            foreach ($hostelList as $hostel_key => $hostel_value) {
                                                            ?>


                                                                <option value="<?php echo $hostel_value['id'] ?>" <?php
                                                                                                                    echo set_value('hostel_id', $student['hostel_id']) == $hostel_value['id'] ? "selected='selected'" : "";
                                                                                                                    ?>>
                                                                    <?php echo $hostel_value['hostel_name']; ?>
                                                                </option>
                                                            <?php
                                                            }
                                                            ?>
                                                        </select>
                                                        <span class="text-danger"><?php echo form_error('hostel_id'); ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('room_no'); ?></label>
                                                        <select id="hostel_room_id" name="hostel_room_id" class="form-control">
                                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                        </select>
                                                        <span class="text-danger"><?php echo form_error('hostel_room_id'); ?></span>
                                                    </div>
                                                </div>


                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            <?php }
                            if (($sch_setting->father_name) || ($sch_setting->father_phone) || ($sch_setting->father_occupation) || ($sch_setting->father_pic) || ($sch_setting->mother_name) || ($sch_setting->mother_phone) || ($sch_setting->mother_occupation) || ($sch_setting->mother_pic) || ($sch_setting->guardian_relation) || ($sch_setting->guardian_phone) || ($sch_setting->guardian_email) || ($sch_setting->guardian_pic) || ($sch_setting->guardian_address)) { ?>
                                <div class="tshadow mb25 bozero">
                                    <h4 class="pagetitleh2"><?php echo $this->lang->line('parent_guardian_detail'); ?></h4>

                                    <div class="around10">
                                        <div class="row">
                                            <?php if ($sch_setting->father_name) { ?>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('father_name')." (As per aadhar)"; ?></label>
                                                        <input id="father_name" name="father_name" placeholder="" type="text" class="form-control" value="<?php echo set_value('father_name', $student['father_name']); ?>" />
                                                        <span class="text-danger"><?php echo form_error('father_name'); ?></span>
                                                    </div>
                                                </div>
                                            <?php }
                                            if ($sch_setting->father_phone) { ?>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('phone'); ?> <?php echo $this->lang->line('no'); ?></label>
                                                        <input id="father_phone" name="father_phone" placeholder="" type="text" class="form-control" value="<?php echo set_value('father_phone', $student['father_phone']); ?>" />
                                                        <span class="text-danger"><?php echo form_error('father_phone'); ?></span>
                                                    </div>
                                                </div>
                                            <?php }
                                            if ($sch_setting->father_occupation) { ?>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('father_occupation'); ?></label>
                                                        <input id="father_occupation" name="father_occupation" placeholder="" type="text" class="form-control" value="<?php echo set_value('father_occupation', $student['father_occupation']); ?>" />
                                                        <span class="text-danger"><?php echo form_error('father_occupation'); ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('father'); ?> Annual Income</label>
                                                        <input id="father_annual_income" name="father_annual_income" placeholder="" type="text" class="form-control" value="<?php echo set_value('father_annual_income', $student['father_annual_income']); ?>" />
                                                        <span class="text-danger"><?php echo form_error('father_annual_income'); ?></span>
                                                    </div>
                                                </div>
                                            <?php }
                                            if ($sch_setting->father_pic) { ?>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="exampleInputFile"><?php echo $this->lang->line('father') . " " . $this->lang->line('photo'); ?></label>
                                                        <div><input class="filestyle form-control" type='file' name='father_pic' id="file" size='20' />
                                                        </div>
                                                        <span class="text-danger"><?php echo form_error('father_pic'); ?></span>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                            <div class="col-md-1">
                                                <div class="form-group">
                                                    <label for="exampleInputFile"><?php echo " Status"; ?></label>
                                                    <div>
                                                        <select class="form-control" name="father_status" id="father_status">
                                                            <option value="Alive" <?php if ($student['father_status'] == "Alive") {echo "selected";}?>>Alive</option>
                                                            <option value="Expired" <?php if ($student['father_status'] == "Expired") { echo "selected"; } ?>>Expired</option>
                                                            <option value="Divorced" <?php if ($student['father_status'] == "Divorced") { echo "selected"; } ?>>Divorced</option>
                                                            <option value="Single" <?php if ($student['father_status'] == "Single") { echo "selected"; } ?>>Single</option>
                                                        </select>
                                                    </div>
                                                    <span class="text-danger"><?php echo form_error('father_status'); ?></span>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="row">
                                            <?php if ($sch_setting->mother_name) { ?>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('mother_name')." (As per aadhar)"; ?></label>
                                                        <input id="mother_name" name="mother_name" placeholder="" type="text" class="form-control" value="<?php echo set_value('mother_name', $student['mother_name']); ?>" />
                                                        <span class="text-danger"><?php echo form_error('mother_name'); ?></span>
                                                    </div>
                                                </div>
                                            <?php }
                                            if ($sch_setting->mother_phone) { ?>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('mother_phone'); ?></label>
                                                        <input id="mother_phone" name="mother_phone" placeholder="" type="text" class="form-control" value="<?php echo set_value('mother_phone', $student['mother_phone']); ?>" />
                                                        <span class="text-danger"><?php echo form_error('mother_phone'); ?></span>
                                                    </div>
                                                </div>
                                            <?php }
                                            if ($sch_setting->mother_occupation) { ?>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('mother_occupation'); ?></label>
                                                        <input id="mother_occupation" name="mother_occupation" placeholder="" type="text" class="form-control" value="<?php echo set_value('mother_occupation', $student['mother_occupation']); ?>" />
                                                        <span class="text-danger"><?php echo form_error('mother_occupation'); ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('mother'); ?> Annual Income</label>
                                                        <input id="mother_annual_income" name="mother_annual_income" placeholder="" type="text" class="form-control" value="<?php echo set_value('mother_annual_income', $student['mother_annual_income']); ?>" />
                                                        <span class="text-danger"><?php echo form_error('mother_annual_income'); ?></span>
                                                    </div>
                                                </div>
                                            <?php }
                                            if ($sch_setting->mother_pic) { ?>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="exampleInputFile"><?php echo $this->lang->line('mother') . " " . $this->lang->line('photo'); ?></label>
                                                        <div><input class="filestyle form-control" type='file' name='mother_pic' id="file" size='20' />
                                                        </div>
                                                        <span class="text-danger"><?php echo form_error('mother_pic'); ?></span>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                            <div class="col-md-1">
                                                <div class="form-group">
                                                    <label style="font-size: 12px;" for="exampleInputFile"><?php echo " Status"; ?></label>
                                                    <div>
                                                        <select class="form-control" name="mother_status" id="mother_status">
                                                            <option value="Alive" <?php if ($student['mother_status'] == "Alive") {echo "selected";}?>>Alive</option>
                                                            <option value="Expired" <?php if ($student['mother_status'] == "Expired") { echo "selected"; } ?>>Expired</option>
                                                            <option value="Divorced" <?php if ($student['mother_status'] == "Divorced") { echo "selected"; } ?>>Divorced</option>
                                                            <option value="Single" <?php if ($student['mother_status'] == "Single") { echo "selected"; } ?>>Single</option>
                                                        </select>
                                                    </div>
                                                    <span class="text-danger"><?php echo form_error('mother_status'); ?></span>
                                                </div>
                                            </div>
                                           <?php if ($sch_setting->mother_pic) { ?>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="exampleInputFile"><?php echo "PAN No. (Father)"; ?></label>
                                                        <input id="pan_no_father" name="pan_no_father" placeholder="" type="text" class="form-control" value="<?php echo set_value('pan_no_father', $student['pan_no_father']); ?>" />
                                                        <span class="text-danger"><?php echo form_error('pan_no_father'); ?></span>
                                                    </div>
                                                </div>
                                            <?php } 
                                            //if ($sch_setting->pan_no_mother) { ?>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="exampleInputFile"><?php echo "PAN No. (Mother)"; ?> </label>
                                                        <div><input id="pan_no_mother" class="form-control" type='text' name='pan_no_mother' value="<?php echo set_value('pan_no_mother', $student['pan_no_mother']);?>" />
                                                        </div>
                                                        <span class="text-danger"><?php echo form_error('pan_no_mother'); ?></span>
                                                    </div>
                                                </div>
                                            <?php //} 
                                            //if ($sch_setting->pan_no_mother) { ?>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="exampleInputFile"><?php echo "National Identification Number (Parent)"; ?> </label>
                                                        <div><input id="parent_aadhar_no" class="form-control" type='text' name='parent_aadhar_no' value="<?php echo set_value('parent_aadhar_no', $student['parent_aadhar_no']);?>" />
                                                        </div>
                                                        <span class="text-danger"><?php echo form_error('parent_aadhar_no'); ?></span>
                                                    </div>
                                                </div>
                                            <?php //} ?>
                                        </div>
                                        </div>
                                        <?php if ($sch_setting->guardian_name) { ?>
                                            <div class="row">
                                                <div class="form-group col-md-12">
                                                    <label><?php echo $this->lang->line('if_guardian_is'); ?></label>&nbsp;&nbsp;&nbsp;
                                                    <label class="radio-inline">
                                                        <input type="radio" name="guardian_is" <?php
                                                                                                if ($student['guardian_is'] == "father") {
                                                                                                    echo "checked";
                                                                                                }
                                                                                                ?> value="father"> <?php echo $this->lang->line('father'); ?>
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="guardian_is" <?php
                                                                                                if ($student['guardian_is'] == "mother") {
                                                                                                    echo "checked";
                                                                                                }
                                                                                                ?> value="mother"> <?php echo $this->lang->line('mother'); ?>
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="guardian_is" <?php
                                                                                                if ($student['guardian_is'] == "other") {
                                                                                                    echo "checked";
                                                                                                }
                                                                                                ?> value="other"> <?php echo $this->lang->line('other'); ?>
                                                    </label>
                                                    <span class="text-danger"><?php echo form_error('guardian_is'); ?></span>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <div class="row">

                                            <div class="col-md-6">
                                                <div class="row">
                                                    <?php if ($sch_setting->guardian_name) { ?>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('guardian_name'); ?></label>
                                                                <input id="guardian_name" name="guardian_name" placeholder="" type="text" class="form-control" value="<?php echo set_value('guardian_name', $student['guardian_name']); ?>" />
                                                                <span class="text-danger"><?php echo form_error('guardian_name'); ?></span>
                                                            </div>
                                                        </div>
                                                    <?php }
                                                    if ($sch_setting->guardian_relation) { ?>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('guardian_relation'); ?></label>
                                                                <input id="guardian_relation" name="guardian_relation" placeholder="" type="text" class="form-control" value="<?php echo set_value('guardian_relation', $student['guardian_relation']); ?>" />
                                                                <span class="text-danger"><?php echo form_error('guardian_relation'); ?></span>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                                <div class="row">
                                                    <?php if ($sch_setting->guardian_phone) { ?>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('guardian_phone'); ?></label>
                                                                <input id="guardian_phone" name="guardian_phone" placeholder="" type="text" class="form-control" value="<?php echo set_value('guardian_phone', $student['guardian_phone']); ?>" />
                                                                <span class="text-danger"><?php echo form_error('guardian_phone'); ?></span>
                                                            </div>
                                                        </div>
                                                    <?php }
                                                    if ($sch_setting->guardian_occupation) { ?>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('guardian_occupation'); ?></label>
                                                                <input id="guardian_occupation" name="guardian_occupation" placeholder="" type="text" class="form-control" value="<?php echo set_value('guardian_occupation', $student['guardian_occupation']); ?>" />
                                                                <span class="text-danger"><?php echo form_error('guardian_occupation'); ?></span>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <?php if ($sch_setting->guardian_email) { ?>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('guardian_email'); ?></label>
                                                        <input id="guardian_email" name="guardian_email" placeholder="" type="text" class="form-control" value="<?php echo set_value('guardian_email', $student['guardian_email']); ?>" />
                                                        <span class="text-danger"><?php echo form_error('guardian_email'); ?></span>
                                                    </div>

                                                </div>
                                            <?php }
                                            if ($sch_setting->guardian_pic) { ?>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="exampleInputFile"><?php echo $this->lang->line('guardian') . " " . $this->lang->line('photo'); ?></label>
                                                        <div><input class="filestyle form-control" type='file' name='guardian_pic' id="file" size='20' />
                                                        </div>
                                                        <span class="text-danger"><?php echo form_error('guardian_pic'); ?></span>
                                                    </div>
                                                </div>
                                            <?php }
                                            if ($sch_setting->guardian_address) { ?>
                                                <div class="col-md-6">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('guardian_address'); ?></label>
                                                    <textarea id="guardian_address" name="guardian_address" placeholder="" class="form-control" rows="4"><?php echo set_value('guardian_address', $student['guardian_address']); ?></textarea>
                                                    <span class="text-danger"><?php echo form_error('guardian_address'); ?></span>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="tshadow mb25 bozero">
                                <h3 class="pagetitleh2"><?php echo $this->lang->line('address_details'); ?></h3>
                                <div class="around10">
                                    <div class="row">
                                        <?php if ($sch_setting->current_address) { ?>
                                            <div class="col-md-6">
                                                <label>
                                                    <input type="checkbox" id="autofill_current_address" onclick="return auto_fill_guardian_address();">
                                                    <?php echo $this->lang->line('if_guardian_address_is_current_address'); ?>
                                                </label>
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('current_address'); ?></label>
                                                    <textarea id="current_address" name="current_address" placeholder="" class="form-control"><?php echo set_value('current_address', $student['current_address']); ?></textarea>
                                                    <span class="text-danger"><?php echo form_error('current_address'); ?></span>
                                                </div>
                                                <div class="checkbox">
                                                </div>
                                            </div>
                                        <?php }
                                        if ($sch_setting->permanent_address) { ?>
                                            <div class="col-md-6">
                                                <label>
                                                    <input type="checkbox" id="autofill_address" onclick="return auto_fill_address();">
                                                    <?php echo $this->lang->line('if_permanent_address_is_current_address'); ?>
                                                </label>
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('permanent_address'); ?></label>
                                                    <textarea id="permanent_address" name="permanent_address" placeholder="" class="form-control"><?php echo set_value('permanent_address', $student['permanent_address']) ?></textarea>
                                                    <span class="text-danger"><?php echo form_error('permanent_address', $student['permanent_address']); ?></span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo "City"; ?></label>
                                                    <input id="city" name="city" placeholder="" type="text" class="form-control" value="<?php echo set_value('city', $student['city']); ?>" />
                                                    <span class="text-danger"><?php echo form_error('city'); ?></span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo "State"; ?></label>
                                                    <input id="state" name="state" placeholder="" type="text" class="form-control" value="<?php echo set_value('state', $student['state']); ?>" />
                                                    <span class="text-danger"><?php echo form_error('state'); ?></span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo "Pincode"; ?></label>
                                                    <input id="pincode" name="pincode" placeholder="" type="text" class="form-control" value="<?php echo set_value('pincode', $student['pincode']); ?>" />
                                                    <span class="text-danger"><?php echo form_error('pincode'); ?></span>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="tshadow bozero">
                                <h3 class="pagetitleh2"><?php echo $this->lang->line('miscellaneous_details'); ?></h3>
                                <div class="around10">

                                    <div class="row">
                                        <?php if ($sch_setting->bank_account_no) { ?>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('bank_account_no'); ?></label>
                                                    <input id="bank_account_no" name="bank_account_no" placeholder="" type="text" class="form-control" value="<?php echo set_value('bank_account_no', $student['bank_account_no']); ?>" />
                                                    <span class="text-danger"><?php echo form_error('bank_account_no'); ?></span>
                                                </div>
                                            </div>
                                        <?php }
                                        if ($sch_setting->bank_name) { ?>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('bank_name'); ?></label>
                                                    <input id="bank_name" name="bank_name" placeholder="" type="text" class="form-control" value="<?php echo set_value('bank_name', $student['bank_name']); ?>" />
                                                    <span class="text-danger"><?php echo form_error('bank_name'); ?></span>
                                                </div>
                                            </div>
                                        <?php }
                                        if ($sch_setting->ifsc_code) { ?>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('ifsc_code'); ?></label>
                                                    <input id="ifsc_code" name="ifsc_code" placeholder="" type="text" class="form-control" value="<?php echo set_value('ifsc_code', $student['ifsc_code']); ?>" />
                                                    <span class="text-danger"><?php echo form_error('ifsc_code'); ?></span>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>

                                    <div class="row">
                                        <?php if ($sch_setting->national_identification_no) { ?>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('national_identification_no'); ?>
                                                    </label>
                                                    <input id="adharno" name="adharno" placeholder="" type="text" class="form-control" value="<?php echo set_value('adharno', $student['adharno']); ?>" />
                                                    <span class="text-danger"><?php echo form_error('adharno'); ?></span>
                                                </div>
                                            </div>
                                        <?php }
                                        if ($sch_setting->local_identification_no) { ?>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('local_identification_no'); ?>
                                                    </label>
                                                    <input id="samagra_id" name="samagra_id" placeholder="" type="text" class="form-control" value="<?php echo set_value('samagra_id', $student['samagra_id']); ?>" />
                                                    <span class="text-danger"><?php echo form_error('samagra_id'); ?></span>
                                                </div>
                                            </div>
                                        <?php }
                                        if ($sch_setting->rte) {
                                        ?>
                                            <div class="col-md-4">
                                                <label><?php echo $this->lang->line('rte'); ?></label>
                                                <div class="radio" style="margin-top: 2px;">
                                                    <label><input class="radio-inline" type="radio" name="rte" value="Yes" <?php
                                                                                                                            echo set_value('rte', $student['rte']) == "Yes" ? "checked" : "";
                                                                                                                            ?>><?php echo $this->lang->line('yes'); ?></label>
                                                    <label><input class="radio-inline" type="radio" name="rte" value="No" <?php
                                                                                                                            echo set_value('rte', $student['rte']) == "No" ? "checked" : "";
                                                                                                                            ?>><?php echo $this->lang->line('no'); ?></label>
                                                </div>
                                                <span class="text-danger"><?php echo form_error('rte'); ?></span>
                                            </div>
                                        <?php }
                                        if ($sch_setting->previous_school_details) { ?>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('previous_school_details'); ?></label>
                                                    <textarea class="form-control" rows="3" placeholder="" name="previous_school"><?php echo set_value('previous_school', $student['previous_school']); ?></textarea>
                                                    <span class="text-danger"><?php echo form_error('previous_school'); ?></span>
                                                </div>
                                            </div>
                                        <?php }
                                        if ($sch_setting->student_note) { ?>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('note'); ?></label>
                                                    <textarea class="form-control" rows="3" placeholder="" name="note"><?php echo set_value('note', $student['note']); ?></textarea>
                                                    <span class="text-danger"><?php echo form_error('previous_school'); ?></span>
                                                </div>
                                            </div>
                                        <?php } ?>

                                    </div>
                                </div>
                            </div>
                            <div class="tshadow bozero">
                                <h3 class="pagetitleh2">General Detail</h3>
                                <div class="around10">

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Remarks</label>
                                                <select name="remark" id="remark" class="form-control">
                                                    <option value="Outstanding" <?php if ($student['remark'] == 'Outstanding') {
                                                                                    echo 'selected';
                                                                                }; ?>>Outstanding</option>
                                                    <option value="Excellent" <?php if ($student['remark'] == 'Excellent') {
                                                                                    echo 'selected';
                                                                                }; ?>>Excellent</option>
                                                    <option value="Very Good" <?php if ($student['remark'] == 'Very Good') {
                                                                                    echo 'selected';
                                                                                }; ?>>Very Good</option>
                                                    <option value="Good" <?php if ($student['remark'] == 'Good') {
                                                                                echo 'selected';
                                                                            }; ?>>Good</option>
                                                    <option value="Scope For Improvement" <?php if ($student['remark'] == 'Scope For Improvement') {
                                                                                                echo 'selected';
                                                                                            }; ?>>Scope For Improvement</option>
                                                </select>
                                                <!-- <input id="remark" name="remark" placeholder="" type="text" class="form-control" value="<?php echo set_value('remark', $student['remark']); ?>" /> -->
                                                <span class="text-danger"><?php echo form_error('remark'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Total Attendence</label>
                                                <input id="total_att" name="total_att" placeholder="" type="text" class="form-control" value="<?php echo set_value('total_att', $student['total_att']); ?>" />
                                                <span class="text-danger"><?php echo form_error('total_att'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Student Attendence</label>
                                                <input id="student_att" name="student_att" placeholder="" type="text" class="form-control" value="<?php echo set_value('student_att', $student['student_att']); ?>" />
                                                <span class="text-danger"><?php echo form_error('student_att'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Pass Status</label>
                                                <?php $status = $student['pass_status']; ?>
                                                <select name="pass_status" id="pass_status" class="form-control">
                                                    <option value="1" <?php if ($status == 1) echo  'selected'; ?>>Yes</option>
                                                    <option value="0" <?php if ($status == 0) echo  'selected'; ?>>No</option>
                                                </select>
                                                <!-- <input id="pass_status" name="pass_status" placeholder="" type="text" class="form-control" value="<?php echo set_value('pass_status', $student['pass_status']); ?>" /> -->
                                                <span class="text-danger"><?php echo form_error('pass_status'); ?></span>
                                            </div>
                                        </div>

                                    </div>


                                </div>
                            </div>
                            <div class="box-footer">

                                <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
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
        var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy']) ?>';
        var class_id = $('#class_id').val();
        var section_id = '<?php echo set_value('section_id', $student['section_id']) ?>';
        var hostel_id = $('#hostel_id').val();
        var hostel_room_id = '<?php echo set_value('hostel_room_id', $student['hostel_room_id']) ?>';
        getHostel(hostel_id, hostel_room_id);
        getSectionByClass(class_id, section_id, 'section_id');

        $(document).on('change', '#class_id', function(e) {
            $('#section_id').html("");
            var class_id = $(this).val();
            getSectionByClass(class_id, 0, 'section_id');
        });

        $(document).on('change', '#sibiling_class_id', function() {
            var class_id = $(this).val();
            getSectionByClass(class_id, 0, 'sibiling_section_id');
        });

        $("#btnreset").click(function() {
            $("#form1")[0].reset();
        });

        $(document).on('change', '#hostel_id', function(e) {
            var hostel_id = $(this).val();
            getHostel(hostel_id, 0);

        });

        $(document).on('change', '#sibiling_section_id', function(e) {
            getStudentsByClassAndSection();
        });

        function getStudentsByClassAndSection() {
            $('#sibiling_student_id').html("");
            var class_id = $('#sibiling_class_id').val();
            var section_id = $('#sibiling_section_id').val();
            var current_student_id = $('.current_student_id').val();
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';

            $.ajax({
                type: "GET",
                url: baseurl + "student/getByClassAndSectionExcludeMe",
                data: {
                    'class_id': class_id,
                    'section_id': section_id,
                    'current_student_id': current_student_id
                },
                dataType: "json",
                beforeSend: function() {
                    $('#sibiling_student_id').addClass('dropdownloading');
                },
                success: function(data) {
                    $.each(data, function(i, obj) {
                        var sel = "";
                        if (section_id == obj.section_id) {
                            sel = "selected=selected";
                        }

                        if (obj.roll_no == null) {

                            div_data += "<option value=" + obj.id + ">" + obj.full_name + "</option>";


                        } else {
                            div_data += "<option value=" + obj.id + ">" + obj.full_name + " (" + obj.roll_no + ") " + "</option>";
                        }
                    });
                    $('#sibiling_student_id').append(div_data);
                },
                complete: function() {
                    $('#sibiling_student_id').removeClass('dropdownloading');
                }
            });

        }





        function getSectionByClass(class_id, section_id, select_control) {
            if (class_id != "") {
                $('#' + select_control).html("");
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
                        $('#' + select_control).addClass('dropdownloading');
                    },
                    success: function(data) {
                        $.each(data, function(i, obj) {
                            var sel = "";
                            if (section_id == obj.section_id) {
                                sel = "selected";
                            }
                            div_data += "<option value=" + obj.section_id + " " + sel + ">" + obj.section + "</option>";
                        });
                        $('#' + select_control).append(div_data);
                    },
                    complete: function() {
                        $('#' + select_control).removeClass('dropdownloading');
                    }
                });
            }
        }


        function getHostel(hostel_id, hostel_room_id) {
            if (hostel_room_id == "") {
                hostel_room_id = 0;
            }

            if (hostel_id != "") {

                $('#hostel_room_id').html("");


                var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
                $.ajax({
                    type: "GET",
                    url: baseurl + "admin/hostelroom/getRoom",
                    data: {
                        'hostel_id': hostel_id
                    },
                    dataType: "json",
                    beforeSend: function() {
                        $('#hostel_room_id').addClass('dropdownloading');
                    },
                    success: function(data) {
                        $.each(data, function(i, obj) {
                            var sel = "";
                            if (hostel_room_id == obj.id) {
                                sel = "selected";
                            }

                            div_data += "<option value=" + obj.id + " " + sel + ">" + obj.room_no + " (" + obj.room_type + ")" + "</option>";

                        });
                        $('#hostel_room_id').append(div_data);
                    },
                    complete: function() {
                        $('#hostel_room_id').removeClass('dropdownloading');
                    }
                });
            }
        }

    });

    function auto_fill_guardian_address() {
        if ($("#autofill_current_address").is(':checked')) {
            $('#current_address').val($('#guardian_address').val());
        }
    }

    function auto_fill_address() {
        if ($("#autofill_address").is(':checked')) {
            $('#permanent_address').val($('#current_address').val());
        }
    }
    $('input:radio[name="guardian_is"]').change(
        function() {
            if ($(this).is(':checked')) {
                var value = $(this).val();
                if (value == "father") {
                    var father_relation = "<?php echo $this->lang->line('father'); ?>";
                    $('#guardian_name').val($('#father_name').val());
                    $('#guardian_phone').val($('#father_phone').val());
                    $('#guardian_occupation').val($('#father_occupation').val());
                    $('#guardian_relation').val(father_relation);
                } else if (value == "mother") {
                    var mother_relation = "<?php echo $this->lang->line('mother'); ?>";
                    $('#guardian_name').val($('#mother_name').val());
                    $('#guardian_phone').val($('#mother_phone').val());
                    $('#guardian_occupation').val($('#mother_occupation').val());
                    $('#guardian_relation').val(mother_relation);
                } else {
                    $('#guardian_name').val("");
                    $('#guardian_phone').val("");
                    $('#guardian_occupation').val("");
                    $('#guardian_relation').val("")
                }
            }
        });
</script>


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

<div class="modal" id="mymemberModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title modal_member_title"></h4>
            </div>
            <div class="modal-body modal_sibling_body">
                <div class="form-horizontal">
                    <input type="hidden" name="current_student_id" class="current_student_id" value="0">
                    <div class="sibling_content">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <div class="sibling_msg">
                                </div>
                                <label for="inputEmail3" class="col-sm-3 control-label"><?php echo "Member Id"; ?></label>
                                <div class="col-sm-9">
                                    <input type="text" name="membership_id" id="membership_id" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputPassword3" class="col-sm-3 control-label"><?php echo "Member Name"; ?></label>
                                <div class="col-sm-9">
                                    <input type="text" name="member_name" id="members_name" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputPassword3" class="col-sm-3 control-label"><?php echo "Member Relation"; ?></label>
                                <div class="col-sm-9">
                                    <input type="text" name="member_relation" id="member_relation" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="clear: both;">
                <button type="button" class="btn btn-primary btn-sm add_member" id="load" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Processing"><i class="fa fa-user"></i> <?php echo $this->lang->line('add'); ?></button>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="referredModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title modal_member_title"></h4>
            </div>
            <div class="modal-body modal_sibling_body">
                <form id="form">
                    <div class="form-horizontal">
                        <input type="hidden" name="current_student_id" class="current_student_id" value="0">
                        <div class="sibling_content">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <div class="sibling_msg">
                                    </div>
                                    <label for="inputEmail3" class="col-sm-3 control-label"><?php echo "Refered By"; ?></label>
                                    <div class="col-sm-9">
                                        <input type="text" name="referred_by" id="referred_by" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputPassword3" class="col-sm-3 control-label"><?php echo "Designation"; ?></label>
                                    <div class="col-sm-9">
                                        <input type="text" name="designation" id="designation" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputPassword3" class="col-sm-3 control-label"><?php echo "Letter No"; ?></label>
                                    <div class="col-sm-9">
                                        <input type="number" name="letter_no" id="letter_no" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputPassword3" class="col-sm-3 control-label"><?php echo "Date of Approval"; ?></label>
                                    <div class="col-sm-9">
                                        <input type="text" name="approval_date" id="approval_date" class="form-control date">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputPassword3" class="col-sm-3 control-label"><?php echo "Discount Amount"; ?></label>
                                    <div class="col-sm-9">
                                        <input type="text" name="discount_amt" id="discount_amt" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputPassword3" class="col-sm-3 control-label"><?php echo "Document"; ?></label>
                                    <div class="col-sm-9">
                                        <input type="file" name="document" id="document" class="form-control filestyle ">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-right" style="clear: both;">
                        <button type="submit" class="btn btn-primary btn-sm add_referred" name="submit" id="load" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Processing"><i class="fa fa-user"></i> <?php echo $this->lang->line('add'); ?></button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<div class="modal" id="deleteModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title del_modal_title"></h4>
            </div>
            <div class="modal-hidden">
                <input type="hidden" name="id" value="0" class="hd_input">
            </div>
            <div class="modal-body del_modal_body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-primary delete_confirm"><?php echo $this->lang->line('confirm'); ?></button>
                <button type="button" class="btn btn-sm btn-default" data-dismiss="modal"><?php echo $this->lang->line('cancel'); ?></button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('#deleteModal').on('shown.bs.modal', function() {
        console.log("srer");
        $(".del_modal_title").html("<?php echo $this->lang->line('delete_confirm') ?>");
        $(".del_modal_body").html("<p><?php echo $this->lang->line('are_you_sure_you_want_to_remove_sibling'); ?></p>");
    })

    $(document).on('click', '.remove_sibling', function() {
        $('#deleteModal').modal('show');
    });


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

    $(document).on('click', '.mysiblings', function() {
        $('#mySiblingModal').modal('show');

    });

    $(document).on('click', '.add_member', function() {
        var current_student_id = $('.current_student_id').val();
        var membership_id = $('#membership_id').val();
        var members_name = $('input#members_name').val();
        var member_relation = $('#member_relation').val();
        var $this = $(this);
        // console.log(current_student_id);
        // console.log(membership_id);
        // console.log(members_name);
        // console.log(member_relation);
        $.ajax({
            type: "post",
            url: baseurl + "student/add_member",
            data: {
                'current_student_id': current_student_id,
                'membership_id': membership_id,
                'member_name': members_name,
                'member_relation': member_relation,
            },
            dataType: "json",
            beforeSend: function() {
                $this.button('loading');
            },
            success: function(data) {
                console.log(data);
                $('#memberName').text("Member: " + data.member_name);
                $('#member_relation').val(data.member_relation);
                $('#members_name').val(data.member_name);
                $('#membership_id').val(data.membership_id);

                $('#mymemberModal').modal('hide');
            },
            complete: function() {
                $this.button('reset');
            }

        });



    });

    $(document).on('click', '.mymember', function() {
        $('#mymemberModal').modal('show');

    });



    $('#mySiblingModal').on('shown.bs.modal', function() {
        $('.sibling_msg').html("");
        $('.modal_sibling_title').html('<b>' + "<?php echo $this->lang->line('sibling'); ?>" + '</b>');
        $('.current_student_id').val($("input[name='student_id']").val());
        if ($('.siblings_counts').length && $('.siblings_counts').val().length) {
            var msg = "";
            msg += "<div class='alert alert-danger text-center'>";
            msg += "Please remove previous siblings";
            msg += "</div>";
            $('.sibling_msg').html(msg);

            $(".sibling_content, .modal-footer", this).css("display", "none");
        } else {
            $(".sibling_content, .modal-footer", this).css("display", "block");
        }

    });

    $('#mymemberModal').on('shown.bs.modal', function() {
        $('.sibling_msg').html("");
        $('.modal_member_title').html('<b>' + "<?php echo "Member"; ?>" + '</b>');
        $('.current_student_id').val($("input[name='student_id']").val());
        if ($('.siblings_counts').length && $('.siblings_counts').val().length) {
            var msg = "";
            msg += "<div class='alert alert-danger text-center'>";
            msg += "Please remove previous siblings";
            msg += "</div>";
            $('.sibling_msg').html(msg);

            $(".sibling_content, .modal-footer", this).css("display", "none");
        } else {
            $(".sibling_content, .modal-footer", this).css("display", "block");
        }

    });


   

    // $(document).on('click', '.add_referred', function() {
    //     var current_student_id = $('.current_student_id').val();
    //     var referred_by = $('input#referred_by').val();
    //     var designation = $('input#designation').val();
    //     var letter_no = $('input#letter_no').val();
    //     var approval_date = $('input#approval_date').val();
    //     var discount_amt = $('   input#discount_amt').val();
    //     var document = $('input#document').val();
    //     var $this = $(this);

    //     $.ajax({
    //         type: "post",
    //         url: baseurl + "student/add_referred",

    //         data: {
    //             'current_student_id': current_student_id,
    //             'referred_by': referred_by,
    //             'designation': designation,
    //             'letter_no': letter_no,
    //             'approval_date': approval_date,
    //             'discount_amt': discount_amt,
    //             'document': document,
    //         },
    //         dataType: "json",
    //         beforeSend: function() {
    //             $this.button('loading');
    //         },
    //         success: function(data) {
    //             console.log(data);
    //             // $('#memberName').text("Member: " + data.member_name);
    //             $('#document').val(data.document);
    //             $('#designation').val(data.designation);
    //             $('#letter_no').val(data.letter_no);
    //             $('#approval_date').val(data.approval_date);
    //             $('#discount_amt').val(data.discount_amt);
    //             $('#document').val(data.document);

    //             $('#referredModal').modal('hide');
    //         },
    //         complete: function() {
    //             $this.button('reset');
    //         }

    //     });



    // });



    $('#referredModal').on('shown.bs.modal', function() {
        $('.sibling_msg').html("");
        $('.modal_member_title').html('<b>' + "<?php echo "Referal"; ?>" + '</b>');
        $('.current_student_id').val($("input[name='student_id']").val());
        if ($('.siblings_counts').length && $('.siblings_counts').val().length) {
            var msg = "";
            msg += "<div class='alert alert-danger text-center'>";
            msg += "Please remove previous siblings";
            msg += "</div>";
            $('.sibling_msg').html(msg);

            $(".sibling_content, .modal-footer", this).css("display", "none");
        } else {
            $(".sibling_content, .modal-footer", this).css("display", "block");
        }

    });



    $(document).ready(function() {

        $('#mySiblingModal').modal({
            backdrop: 'static',
            keyboard: false,
            show: false
        });

        $('#mymemberModal').modal({
            backdrop: 'static',
            keyboard: false,
            show: false
        });

        $('#referredModal').modal({
            backdrop: 'static',
            keyboard: false,
            show: false
        });

        $('#deleteModal').modal({
            backdrop: 'static',
            keyboard: false,
            show: false
        });

        $(document).on('click', '.delete_confirm', function() {
            $('#deleteModal').modal('hide');
            var stid=$('#student_id').val();
        $.ajax({
                type: "post",
                url: baseurl + "student/remove_sibling",
                data: {
                    'id': stid,
                },
                dataType: "json",
                beforeSend: function() {
                    //$this.button('loading');
                },
                success: function(data) {
                    
                    if(data.success)
                    {
                        alert('Succesfully Removed Sibling');
                        $('.sibling_div').remove();
                        $('#sibling_name').hide();
                    }
                    else
                    {
                        alert('Error in removing sibling');
                    }
                },
                complete: function() {
                    //$this.button('reset');
                }
            });


        });

        $(document).on('click', '.referred', function() {
        $('#referredModal').modal('show');
        // var current_student_id = $(this).data('current_student_id');
        //     $.ajax({
        //         url: baseurl + "student/get_referredlist",
        //         type: "POST",
        //         data:  {
        //             'current_student_id': current_student_id
        //         },
        //         dataType: "json",
        //         beforeSend: function(data) {
        //             // $this.button('loading');
        //         },
        //         success: function(data) {
        //             $('#document').val(data.document);
        //             $('#designation').val(data.designation);
        //             $('#letter_no').val(data.letter_no);
        //             $('#approval_date').val(data.approval_date);
        //             $('#discount_amt').val(data.discount_amt);
        //             $('#document').val(data.document);

        //         },
        //         complete: function() {
        //             // $this.button('reset');
        //         }
        //     });

    });

        $("#form").on("submit", function(e) {
            e.preventDefault();
            $.ajax({
                url: baseurl + "student/add_referred",
                type: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                dataType: "json",
                beforeSend: function(data) {
                    // $this.button('loading');
                },
                success: function(data) {
                    $('#document').val(data.document);
                    $('#designation').val(data.designation);
                    $('#letter_no').val(data.letter_no);
                    $('#approval_date').val(data.approval_date);
                    $('#discount_amt').val(data.discount_amt);
                    $('#document').val(data.document);

                    $('#referredModal').modal('hide');
                },
                complete: function() {
                    // $this.button('reset');
                }
            });
        });

        $(document).on('click', '.editexamModalButton', function(e) {

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


    });
</script>



<script type="text/javascript" src="<?php echo base_url(); ?>backend/dist/js/savemode.js"></script>
<script>
$(document).ready(function() {
    $('#file').on('change', function(event) {
        var file = event.target.files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var img = new Image();
                img.src = e.target.result;
                img.onload = function() {
                    // Create a canvas element for image processing
                    var canvas = document.createElement('canvas');
                    var ctx = canvas.getContext('2d');
                    
                    // Define target dimensions for the resized image
                    var maxWidth = 800; // Maximum width
                    var maxHeight = 600; // Maximum height
                    var width = img.width;
                    var height = img.height;

                    // Calculate new dimensions
                    if (width > height) {
                        if (width > maxWidth) {
                            height *= maxWidth / width;
                            width = maxWidth;
                        }
                    } else {
                        if (height > maxHeight) {
                            width *= maxHeight / height;
                            height = maxHeight;
                        }
                    }

                    // Set canvas dimensions
                    canvas.width = width;
                    canvas.height = height;

                    // Draw the image on the canvas
                    ctx.drawImage(img, 0, 0, width, height);

                    // Convert canvas to Blob
                    canvas.toBlob(function(blob) {
                        var compressedFile = new File([blob], file.name, { type: file.type });
                        var dataTransfer = new DataTransfer();
                        dataTransfer.items.add(compressedFile);
                        $('#file')[0].files = dataTransfer.files;

                        // Preview the compressed image
                        // var preview = $('#preview');
                        // preview.attr('src', URL.createObjectURL(compressedFile));
                    }, file.type, 0.7); // Adjust quality if needed (0.0 to 1.0)
                    
                };
            };
            reader.readAsDataURL(file);
        }
    });
});
</script>  