<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-sitemap"></i> <?php echo $this->lang->line('human_resource'); ?></h1>
    </section>
    <!-- Main content -->

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">


                    <form id="form1" action="<?php echo site_url('admin/staff/edit/' . $staff["id"]) ?>" id="employeeform" name="employeeform" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                        <div class="box-body">
                            <div class="alert alert-info">
                                Staff email is their login username, password is generated automatically and send to staff email. Superadmin can change staff password on their staff profile page.

                            </div>
                            <div class="tshadow mb25 bozero">
                                <div class="around10">
                                    <?php if ($this->session->flashdata('msg')) { ?>
                                        <?php echo $this->session->flashdata('msg') ?>
                                    <?php } ?>
                                    <div class="row">
                                        <h4 class="pagetitleh2"><?php echo $this->lang->line('profile'); ?></h4>
                                        <div class="col-md-2">
                                            <img style="width: 150px;padding-top: 2px;height: 160px;" src="<?php
                                                                                                            if (!empty($staff["image"])) {
                                                                                                                echo base_url('uploads/staff_images/') . $staff["image"];
                                                                                                            } else {

                                                                                                                if ($staff['gender'] == 'Female') {
                                                                                                                    echo base_url() . "uploads/staff_images/default_female.jpg";
                                                                                                                } elseif ($staff['gender'] == 'Male') {
                                                                                                                    echo base_url() . "uploads/staff_images/default_male.jpg";
                                                                                                                }
                                                                                                            }
                                                                                                            ?>" alt="User Image" srcset="">
                                        </div>
                                        <?php
                                        if ($sch_setting->staff_photo) { ?>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputFile"><?php echo $this->lang->line('photo'); ?></label>
                                                    <div><input class="filestyle form-control" type='file' name='file' id="file" size='20' />
                                                    </div>
                                                    <span class="text-danger"><?php echo form_error('file'); ?></span>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>

                                <h4 class="pagetitleh2"><?php echo $this->lang->line('basic_information'); ?> </h4>
                                <div class="around10">

                                    <?php echo $this->customlib->getCSRF(); ?>

                                    <div class="row">
                                        <?php if (!$staffid_auto_insert) { ?>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('staff_id'); ?></label><small class="req"> *</small>
                                                    <input autofocus="" id="employee_id" name="employee_id" placeholder="" value="<?php echo $staff["employee_id"] ?>" type="text" class="form-control" value="" />
                                                    <span class="text-danger"><?php echo form_error('employee_id'); ?></span>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('type'); ?></label>
                                                <select id="record_type" name="record_type" class="form-control">
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                    <option value="1" <?php if (!empty($staff["record_type"]) && $staff["record_type"] == 1) {
                                                                            echo "selected";
                                                                        } ?>><?php echo $this->lang->line('staff'); ?></option>
                                                    <option value="2" <?php if (!empty($staff["record_type"]) && $staff["record_type"] == 2) {
                                                                            echo "selected";
                                                                        } ?>><?php echo $this->lang->line('user'); ?></option>
                                                    <span class="text-danger"><?php echo form_error('record_type'); ?></span>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">

                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('role'); ?></label><small class="req"> *</small>
                                                <input type="hidden" name="editid"  value="<?php echo $staff['id']; ?>">
                                                <select id="role" name="role" class="form-control">
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                    <?php
                                                    foreach ($getStaffRole as $key => $role) {
                                                    ?>
                                                        <option value="<?php echo $role["id"] ?>" <?php
                                                                                                    if ($staff["user_type"] == $role["type"]) {
                                                                                                        echo "selected";
                                                                                                    }
                                                                                                    ?>><?php echo $role["type"] ?></option>
                                                    <?php }
                                                    ?>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('role'); ?></span>
                                            </div>
                                        </div>
                                        <?php if ($sch_setting->staff_designation) { ?>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('designation'); ?></label>

                                                    <select id="designation" name="designation" placeholder="" type="text" class="form-control">
                                                        <option value="select"><?php echo $this->lang->line('select') ?></option>
                                                        <?php foreach ($designation as $key => $value) {
                                                        ?>
                                                            <option value="<?php echo $value["id"] ?>" <?php
                                                                                                        if ($staff["designation"] == $value["id"]) {
                                                                                                            echo "selected";
                                                                                                        }
                                                                                                        ?>><?php echo $value["designation"] ?></option>
                                                        <?php }
                                                        ?>
                                                    </select>
                                                    <span class="text-danger"><?php echo form_error('designation'); ?></span>
                                                </div>
                                            </div>
                                        <?php }
                                        if ($sch_setting->staff_department) { ?>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('department'); ?></label>
                                                    <select id="department" name="department" placeholder="" type="text" class="form-control">
                                                        <option value="select"><?php echo $this->lang->line('select') ?></option>
                                                        <?php foreach ($department as $key => $value) {
                                                        ?>
                                                            <option value="<?php echo $value["id"] ?>" <?php
                                                                                                        if ($staff["department"] == $value["id"]) {
                                                                                                            echo "selected";
                                                                                                        }
                                                                                                        ?>><?php echo $value["department_name"] ?></option>
                                                        <?php }
                                                        ?>
                                                    </select>
                                                    <span class="text-danger"><?php echo form_error('department'); ?></span>
                                                </div>
                                            </div>
                                            <?php } ?>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('staff').' '.$this->lang->line('category'); ?></label>
                                                    <select id="payroll_category_id" name="payroll_category_id" placeholder="" type="text" class="form-control">
                                                        <option value="select"><?php echo $this->lang->line('select') ?></option>
                                                        <?php foreach ($payroll_category as $key => $paycategoryRow) {
                                                        ?>
                                                            <option value="<?php echo $paycategoryRow["id"] ?>" <?php
                                                                                                        if ($staff["payroll_category_id"] == $paycategoryRow["id"]) {
                                                                                                            echo "selected";
                                                                                                        }
                                                                                                        ?>><?php echo $paycategoryRow["category_name"] ?></option>
                                                        <?php }
                                                        ?>
                                                    </select>
                                                    <span class="text-danger"><?php echo form_error('department'); ?></span>
                                                </div>
                                            </div>
                                    </div>
                                    <div class="row">

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('first_name'); ?></label><small class="req"> *</small>
                                                <input id="firstname" name="name" placeholder="" type="text" class="form-control" value="<?php echo set_value('name', $staff["name"]); ?>" />
                                                <span class="text-danger"><?php echo form_error('name'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('middle_name'); ?></label>
                                                <input id="middle_name" name="middle_name" placeholder="" type="text" class="form-control" value="<?php echo set_value('middle_name', !empty($staff["middle_name"]) ? $staff["middle_name"] : ""); ?>" />
                                                <span class="text-danger"><?php echo form_error('middle_name'); ?></span>
                                            </div>
                                        </div>
                                        <?php if ($sch_setting->staff_last_name) { ?>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('last_name'); ?></label>
                                                    <input id="surname" name="surname" placeholder="" type="text" class="form-control" value="<?php echo set_value('surname', $staff["surname"]); ?>" />
                                                    <span class="text-danger"><?php echo form_error('surname'); ?></span>
                                                </div>
                                            </div>
                                        <?php }
                                        if ($sch_setting->staff_father_name) { ?>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('father_name'); ?></label>
                                                    <input id="father_name" name="father_name" placeholder="" type="text" class="form-control" value="<?php echo set_value('father_name', $staff["father_name"]); ?>" />
                                                    <span class="text-danger"><?php echo form_error('father_name'); ?></span>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <? if ($sch_setting->staff_mother_name) { ?>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('mother_name'); ?></label>
                                                    <input id="mother_name" name="mother_name" placeholder="" type="text" class="form-control" value="<?php echo set_value('mother_name', $staff["mother_name"]); ?>" />
                                                    <span class="text-danger"><?php echo form_error('mother_name'); ?></span>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <?php
                                        if ($sch_setting->staff_marital_status) { ?>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('marital_status'); ?></label>
                                                    <select class="form-control" name="marital_status">
                                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                        <?php foreach ($marital_status as $makey => $mavalue) {
                                                        ?>
                                                            <option <?php
                                                                    if ($staff["marital_status"] == $mavalue) {
                                                                        echo "selected";
                                                                    }
                                                                    ?> value="<?php echo $mavalue; ?>"><?php echo $mavalue; ?></option>
                                                        <?php } ?>

                                                    </select>
                                                    <span class="text-danger"><?php echo form_error('marital_status'); ?></span>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo "Spouse Name"; ?></label>
                                                <input id="spouse_name" name="spouse_name" placeholder="" type="text" class="form-control" value="<?php echo set_value('spouse_name', !empty($staff_subRow["spouse_name"]) ?  $staff_subRow["spouse_name"] : ""); ?>" />
                                                <span class="text-danger"><?php echo form_error('spouse_name'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('date_of_birth'); ?></label><small class="req"> *</small>
                                                <input id="dob" name="dob" placeholder="" type="text" class="form-control date" value="<?php
                                                                                                                                        if (!empty($staff["dob"])) {
                                                                                                                                            echo date($this->customlib->getSchoolDateFormat(), strtotime($staff["dob"]));
                                                                                                                                        }
                                                                                                                                        ?>" />
                                                <span class="text-danger"><?php echo form_error('dob'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo "Caste"; ?></label>
                                                <input id="cast" name="cast" placeholder="" type="text" class="form-control" value="<?php echo set_value('cast', !empty($staff_subRow["cast"]) ? $staff_subRow["cast"] : ""); ?>" />
                                                <span class="text-danger"><?php echo form_error('cast'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo "Subcaste"; ?></label>
                                                <input id="subcaste" name="subcaste" placeholder="" type="text" class="form-control" value="<?php echo set_value('subcaste', !empty($staff_subRow["subcaste"]) ? $staff_subRow["subcaste"] : ""); ?>" />
                                                <span class="text-danger"><?php echo form_error('subcaste'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo "Religion"; ?></label>
                                                <input id="religion" name="religion" placeholder="" type="text" class="form-control" value="<?php echo set_value('religion', !empty($staff_subRow["religion"]) ? $staff_subRow["religion"] : ""); ?>" />
                                                <span class="text-danger"><?php echo form_error('religion'); ?></span>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo "Seniority ID"; ?></label>
                                                <input id="seniority_id" name="seniority_id" placeholder="" type="text" class="form-control" value="<?php echo  set_value('seniority_id', $staff["seniority_id"]) ?>" />
                                                <span class="text-danger"><?php echo form_error('seniority_id'); ?></span>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputFile"> <?php echo $this->lang->line('gender'); ?></label><small class="req"> *</small>
                                                <select class="form-control" name="gender">
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                    <?php
                                                    foreach ($genderList as $key => $value) {
                                                    ?>
                                                        <option value="<?php echo $key; ?>" <?php if ($staff['gender'] == $key) echo "selected"; ?>><?php echo $value; ?></option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('gender'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo "Grade"; ?></label>
                                                <input id="grade" name="grade" placeholder="" type="text" class="form-control" value="<?php echo set_value('grade', !empty($staff_subRow["grade"]) ? $staff_subRow["grade"] : ""); ?>" />
                                                <span class="text-danger"><?php echo form_error('grade'); ?></span>
                                            </div>
                                        </div>
                                        <?php if ($sch_setting->staff_date_of_joining) { ?>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('date_of_joining'); ?></label>
                                                    <input id="date_of_joining" name="date_of_joining" placeholder="" type="text" class="form-control date" value="<?php
                                                                                                                                                                    if (!empty($staff["date_of_joining"]) && $staff["date_of_joining"] != '0000-00-00') {
                                                                                                                                                                        echo date($this->customlib->getSchoolDateFormat(), strtotime($staff["date_of_joining"]));
                                                                                                                                                                    }
                                                                                                                                                                    ?>" />
                                                    <span class="text-danger"><?php echo form_error('date_of_joining'); ?></span>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo "Date of Confirmation"; ?></label>
                                                <input id="date_of_confirmation" name="date_of_confirmation" placeholder="" type="text" class="form-control date" value="<?php
                                                                                                                                                                            if (!empty($staff_subRow["date_of_confirmation"]) && $staff_subRow["date_of_confirmation"] != '0000-00-00') {
                                                                                                                                                                                echo date($this->customlib->getSchoolDateFormat(), strtotime($staff_subRow["date_of_confirmation"]));
                                                                                                                                                                            }
                                                                                                                                                                            ?>" />
                                                <span class="text-danger"><?php echo form_error('date_of_confirmation'); ?></span>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">


                                        <?php
                                        if ($sch_setting->staff_photo) { ?>
                                            <!-- <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputFile"><?php echo $this->lang->line('photo'); ?></label>
                                                    <div><input class="filestyle form-control" type='file' name='file' id="file" size='20' />
                                                    </div>
                                                    <span class="text-danger"><?php echo form_error('file'); ?></span></div>
                                            </div>                           -->
                                        <?php } ?>

                                    </div>
                                    <div class="row">




                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputFile"><?php echo "Residence"; ?></label>
                                                <div><textarea name="residence" class="form-control"><?php echo set_value('residence', !empty($staff_subRow["residence"]) ? $staff_subRow["residence"] : ""); ?></textarea>
                                                </div>
                                                <span class="text-danger"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputFile"><?php echo "Remarks"; ?></label>
                                                <div><textarea name="remarks" class="form-control"><?php echo set_value('remarks', !empty($staff_subRow["remarks"]) ? $staff_subRow["remarks"] : ""); ?></textarea>
                                                </div>
                                                <span class="text-danger"></span>
                                            </div>
                                        </div>



                                        <?php if ($sch_setting->staff_qualification) { ?>
                                            <div class="col-md-3">

                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('qualification'); ?></label>
                                                    <textarea id="qualification" name="qualification" placeholder="" class="form-control"><?php echo set_value('qualification', $staff["qualification"]); ?></textarea>
                                                    <span class="text-danger"><?php echo form_error('qualification'); ?></span>
                                                </div>
                                            </div>
                                        <?php }
                                        if ($sch_setting->staff_work_experience) { ?>
                                            <div class="col-md-3">

                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('work_experience'); ?></label>
                                                    <textarea id="permanent_address" name="work_exp" placeholder="" class="permanent_address form-control"><?php echo set_value('work_exp', $staff["work_exp"]) ?></textarea>
                                                    <span class="text-danger"><?php echo form_error('work_exp'); ?></span>
                                                </div>
                                            </div>
                                        <?php }
                                        if ($sch_setting->staff_note) { ?>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="exampleInputFile"><?php echo $this->lang->line('note'); ?></label>
                                                    <div><textarea name="note" class="form-control"><?php echo set_value('note', $staff["note"]) ?></textarea>
                                                    </div>
                                                    <span class="text-danger"></span>
                                                </div>
                                            </div>
                                        <?php } ?>

                                    </div>
                                    <div class="row">

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo "Blood Group"; ?></label>
                                                <input id="blood_group" name="blood_group" placeholder="" type="text" class="form-control" value="<?php echo  set_value('blood_group', $staff["blood_group"]) ?>" />
                                                <span class="text-danger"><?php echo form_error('blood_group'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo "Aadhar No"; ?></label>
                                                <input id="aadhar_no" name="aadhar_no" placeholder="" type="text" class="form-control" value="<?php echo  set_value('aadhar_no', $staff["aadhar_no"]) ?>" />
                                                <span class="text-danger"><?php echo form_error('aadhar_no'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo "Pan No"; ?></label>
                                                <input id="pan_no" name="pan_no" placeholder="" type="text" class="form-control" value="<?php echo  set_value('pan_no', $staff["pan_no"]) ?>" />
                                                <span class="text-danger"><?php echo form_error('pan_no'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo "Biometric ID"; ?></label>
                                                <input id="biometric_id" name="biometric_id" placeholder="" type="text" class="form-control" value="<?php echo set_value('biometric_id', !empty($staff["biometric_id"]) ? $staff["biometric_id"] : ""); ?>" />
                                                <span class="text-danger"><?php echo form_error('biometric_id'); ?></span>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="tshadow mb25 bozero">
                                    <h4 class="pagetitleh2"><?php echo $this->lang->line('contact'); ?>
                                    </h4>
                                    <div class="row around10">
                                        <br>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('email'); ?></label><small class="req"> *</small>
                                                <input id="email" name="email" placeholder="" type="text" class="form-control" value="<?php echo set_value('email', $staff["email"]); ?>" />
                                                <span class="text-danger"><?php echo form_error('email'); ?></span>
                                            </div>
                                        </div>
                                        <?php if ($sch_setting->staff_phone) { ?>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo "WhatsApp No"; ?></label>
                                                    <input id="mobileno" name="contactno" placeholder="" type="text" class="form-control" value="<?php echo set_value('contactno', $staff["contact_no"]); ?>" />
                                                    <input id="editid" name="editid" placeholder="" type="hidden" class="form-control" value="<?php echo $staff["id"]; ?>" />

                                                    <span class="text-danger"><?php echo form_error('contactno'); ?></span>
                                                </div>
                                            </div>
                                        <?php }
                                        if ($sch_setting->staff_emergency_contact) { ?>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('emergency_contact_number'); ?></label>
                                                    <input id="mobileno" name="emergency_no" placeholder="" type="text" class="form-control" value="<?php echo set_value('emergency_no', $staff["emergency_contact_no"]); ?>" />
                                                    <span class="text-danger"><?php echo form_error('emergency_no'); ?></span>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo "Mobile"; ?></label>
                                                    <input id="mobile2" name="mobile2" placeholder="" type="text" class="form-control" value="<?php echo set_value('mobile2', !empty($staff_subRow["mobile2"]) ? $staff_subRow["mobile2"] : ""); ?>" />
                                                    <span class="text-danger"><?php echo form_error('mobile2'); ?></span>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo "Phone 2"; ?></label>
                                                    <input id="phone2" name="phone2" placeholder="" type="text" class="form-control" value="<?php echo set_value('phone2', !empty($staff_subRow["phone2"]) ? $staff_subRow["phone2"] : ""); ?>" />
                                                    <span class="text-danger"><?php echo form_error('phone2'); ?></span>
                                                </div>
                                            </div>

                                        <?php } ?>
                                    </div>
                                </div>


                                <div class="tshadow mb25 bozero">
                                    <h4 class="pagetitleh2"><?php echo $this->lang->line('address'); ?>
                                    </h4>
                                    <div class="row around10">
                                        <?php if ($sch_setting->staff_current_address) { ?>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" id="autofill_current_address" onclick="return auto_fill_guardian_address();">
                                                    <?php echo "If Permanent Adress Is Same"; ?>
                                                </label>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="exampleInputFile"><?php echo $this->lang->line('current'); ?> <?php echo $this->lang->line('address'); ?></label>
                                                    <div><textarea name="address" id="addresss" class="form-control"><?php echo set_value('address', $staff["local_address"]) ?></textarea>
                                                    </div>
                                                    <span class="text-danger"></span>
                                                </div>
                                            </div>
                                        <?php }
                                        if ($sch_setting->staff_permanent_address) { ?>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="exampleInputFile"><?php echo $this->lang->line('permanent_address'); ?></label>
                                                    <div><textarea name="permanent_address" id="permanent_address" class="permanent_address form-control"><?php echo set_value('permanent_address', $staff["permanent_address"]); ?></textarea>
                                                    </div>
                                                    <span class="text-danger"></span>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputFile"><?php echo "Current City"; ?></label>
                                                <input id="current_city" id="current_city" name="current_city" placeholder="" type="text" class="form-control" value="<?php echo  set_value('current_city', !empty($staff_subRow["current_city"]) ? $staff_subRow["current_city"] : "") ?>" />
                                                <span class="text-danger"><?php echo form_error('current_city'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">

                                            <div class="form-group">
                                                <label for="exampleInputFile"><?php echo "Permanent City"; ?></label>
                                                <input id="permanent_city" id="permanent_city" name="permanent_city" placeholder="" type="text" class="form-control" value="<?php echo  set_value('permanent_city', !empty($staff_subRow["permanent_city"]) ? $staff_subRow["permanent_city"] : "") ?>" />
                                                <span class="text-danger"><?php echo form_error('permanent_city'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputFile"><?php echo "Current State"; ?></label>
                                                <input id="current_state" id="current_state" name="current_state" placeholder="" type="text" class="form-control" value="<?php echo  set_value('current_state', !empty($staff_subRow["current_state"]) ? $staff_subRow["current_state"] : "") ?>" />
                                                <span class="text-danger"><?php echo form_error('current_state'); ?></span>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputFile"><?php echo "Permanent State"; ?></label>
                                                <input id="permanent_state" id="permanent_state" name="permanent_state" placeholder="" type="text" class="form-control" value="<?php echo  set_value('permanent_state', !empty($staff_subRow["permanent_state"]) ? $staff_subRow["permanent_state"] : "") ?>" />
                                                <span class="text-danger"><?php echo form_error('permanent_state'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputFile"><?php echo "Current Country"; ?></label>
                                                <input id="current_country" id="current_country" name="current_country" placeholder="" type="text" class="form-control" value="<?php echo  set_value('current_country', !empty($staff_subRow["current_country"]) ? $staff_subRow["current_country"] : "") ?>" />
                                                <span class="text-danger"><?php echo form_error('current_country'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputFile"><?php echo "Permanent Country"; ?></label>
                                                <input id="permanent_country" id="permanent_country" name="permanent_country" placeholder="" type="text" class="form-control" value="<?php echo  set_value('permanent_country', !empty($staff_subRow["permanent_country"]) ? $staff_subRow["permanent_country"] : "") ?>" />
                                                <span class="text-danger"><?php echo form_error('permanent_country'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputFile"><?php echo "Current Pincode"; ?></label>
                                                <input id="current_pincode" id="current_pincode" name="current_pincode" placeholder="" type="text" class="form-control" value="<?php echo  set_value('current_pincode', !empty($staff_subRow["current_pincode"]) ? $staff_subRow["current_pincode"] : "") ?>" />
                                                <span class="text-danger"><?php echo form_error('current_pincode'); ?></span>
                                            </div>
                                        </div>


                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputFile"><?php echo "Permanent Pincode"; ?></label>
                                                <input id="permanent_pincode" id="permanent_pincode" name="permanent_pincode" placeholder="" type="text" class="form-control" value="<?php echo  set_value('permanent_pincode', !empty($staff_subRow["permanent_pincode"]) ? $staff_subRow["permanent_pincode"] : "") ?>" />
                                                <span class="text-danger"><?php echo form_error('permanent_pincode'); ?></span>
                                            </div>
                                        </div>


                                    </div>
                                </div>
                                <div class="row">

                                    <?php
                                    echo display_custom_fields('staff', $staff['id']);
                                    ?>
                                </div>

                            </div>

                            <div class="box-group collapsed-box">
                                <div class="panel box box-success collapsed-box">
                                    <div class="box-header with-border">
                                        <a data-widget="collapse" data-original-title="Collapse" aria-expanded="false" class="collapsed btn boxplus">
                                            <i class="fa fa-fw fa-plus"></i><?php echo $this->lang->line('add_more_details'); ?>
                                        </a>
                                    </div>
                                    <div class="box-body">


                                        <div class="tshadow mb25 bozero">
                                            <h4 class="pagetitleh2"><?php echo $this->lang->line('payroll'); ?>
                                            </h4>

                                            <div class="row around10">
                                                <?php if ($sch_setting->staff_epf_no) { ?>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('epf_no'); ?></label>
                                                            <input id="epf_no" name="epf_no" placeholder="" type="text" class="form-control" value="<?php echo $staff["epf_no"] ?>" />
                                                            <span class="text-danger"><?php echo form_error('epf_no'); ?></span>
                                                        </div>
                                                    </div>
                                                <?php }
                                                if ($sch_setting->staff_contract_type) { ?>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('contract_type'); ?></label>
                                                            <select class="form-control" name="contract_type">
                                                                <option value=""><?php echo $this->lang->line('select') ?></option>

                                                                <?php foreach ($contract_type as $key => $value) { ?>
                                                                    <option value="<?php echo $key ?>" <?php
                                                                                                        if ($staff["contract_type"] == $key) {
                                                                                                            echo "selected";
                                                                                                        }
                                                                                                        ?>><?php echo $value ?></option>

                                                                <?php } ?>

                                                            </select>
                                                            <span class="text-danger"><?php echo form_error('contract_type'); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2" style="margin-top: 25px;">
                                                        <div class="form-group">
                                                            <input type="checkbox" name="contract_status" id="contract_status" value="1" <?php echo ($staff["contract_status"] == 1) ? "checked" : "" ?> class="square-purple">
                                                            <label for="exampleInputEmail1"><?php echo "Contract Status"; ?></label> 
                                                        </div>
                                                    </div>
                                                <?php }
                                                if ($sch_setting->staff_basic_salary) { ?>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('basic_salary'); ?></label>
                                                            <input type="text" value="<?php echo $staff["basic_salary"] ?>" class="form-control" name="basic_salary">
                                                        </div>
                                                    </div>
                                                <?php }
                                                if ($sch_setting->staff_work_shift) { ?>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('work_shift'); ?></label>
                                                            <input id="shift" name="shift" placeholder="" type="text" class="form-control" value="<?php echo $staff["shift"] ?>" />

                                                        </div>
                                                    </div>
                                                <?php }
                                                if ($sch_setting->staff_work_location) { ?>
                                                    <div class="col-md-4">
                                                        <div class="form-group">

                                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('work_location'); ?></label>
                                                            <input id="location" name="location" placeholder="" type="text" class="form-control" value="<?php echo $staff["location"] ?>" />

                                                        </div>
                                                    </div>
                                                <?php } ?>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('date_of_leaving'); ?></label>
                                                        <input id="date_of_leaving" name="date_of_leaving" placeholder="" type="text" class="form-control" value="<?php
                                                                                                                                                                    if ($staff["date_of_leaving"] != '') {
                                                                                                                                                                        echo date($this->customlib->getSchoolDateFormat(), strtotime($staff["date_of_leaving"]));
                                                                                                                                                                    }
                                                                                                                                                                    ?>" />
                                                        <span class="text-danger"><?php echo form_error('date_of_leaving'); ?></span>
                                                    </div>
                                                </div>
                                                <?php
                                                $staff_session = $this->staff_model->getstaff_session($staff['id']);
                                                ?>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo "Scale Of Pay"; ?></label>
                                                        <input id="staff_session_id" name="staff_session_id" placeholder="" type="hidden" class="form-control" value="<?php echo !empty($staff_session["id"]) ? $staff_session["id"] : "" ?>" />
                                                        <input id="scale_of_pay" name="scale_of_pay" placeholder="" type="text" class="form-control" value="<?php echo !empty($staff_session["scale_of_pay"]) ? $staff_session["scale_of_pay"] : "" ?>" />
                                                        <span class="text-danger"><?php echo form_error('scale_of_pay'); ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo "Basic Pay"; ?></label>
                                                        <input id="basic_pay" name="basic_pay" placeholder="" type="text" class="form-control" value="<?php echo !empty($staff_session["basic_pay"]) ? $staff_session["basic_pay"] : "" ?>" />
                                                        <span class="text-danger"><?php echo form_error('basic_pay'); ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo "GP"; ?></label>
                                                        <input id="gp" name="gp" placeholder="" type="text" class="form-control" value="<?php echo !empty($staff_session["gp"]) ? $staff_session["gp"] : "" ?>" />
                                                        <span class="text-danger"><?php echo form_error('gp'); ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo "Personal Pay"; ?></label>
                                                        <input id="personal_pay" name="personal_pay" placeholder="" type="text" class="form-control" value="<?php echo !empty($staff_session["personal_pay"]) ? $staff_session["personal_pay"] : "" ?>" />
                                                        <span class="text-danger"><?php echo form_error('personal_pay'); ?></span>
                                                    </div>
                                                </div>                                                 
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo "DA"; ?></label>
                                                        <input id="da" name="da" placeholder="" type="text" class="form-control" value="<?php echo !empty($staff_session["da"]) ? $staff_session["da"] : "" ?>" />
                                                        <span class="text-danger"><?php echo form_error('da'); ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo "HRA"; ?></label>
                                                        <input id="hra" name="hra" placeholder="" type="text" class="form-control" value="<?php echo !empty($staff_session["hra"]) ? $staff_session["hra"] : "" ?>" />
                                                        <span class="text-danger"><?php echo form_error('hra'); ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo "TA"; ?></label>
                                                        <input id="ta" name="ta" placeholder="" type="text" class="form-control" value="<?php echo !empty($staff_session["ta"]) ? $staff_session["ta"] : "" ?>" />
                                                        <span class="text-danger"><?php echo form_error('ta'); ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo "Other Allowance"; ?></label>
                                                        <input id="other_allowance" name="other_allowance" placeholder="" type="text" class="form-control" value="<?php echo !empty($staff_session["other_allowance"]) ? $staff_session["other_allowance"] : "" ?>" />
                                                        <span class="text-danger"><?php echo form_error('other_allowance'); ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo "PF"; ?></label>
                                                        <input id="pf" name="pf" placeholder="" type="text" class="form-control" value="<?php echo !empty($staff_session["pf"]) ? $staff_session["pf"] : "" ?>" />
                                                        <span class="text-danger"><?php echo form_error('pf'); ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo "Profession Tax"; ?></label>
                                                        <input id="profession_tax" name="profession_tax" placeholder="" type="text" class="form-control" value="<?php echo !empty($staff_session["profession_tax"]) ? $staff_session["profession_tax"] : "" ?>" />
                                                        <span class="text-danger"><?php echo form_error('profession_tax'); ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo "Personal Profit"; ?></label>
                                                        <input id="personal_profit" name="personal_profit" placeholder="" type="text" class="form-control" value="<?php echo !empty($staff_session["personal_profit"]) ? $staff_session["personal_profit"] : "" ?>" />
                                                        <span class="text-danger"><?php echo form_error('personal_profit'); ?></span>
                                                    </div>
                                                </div>
                                               
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo "Income Tax"; ?></label>
                                                        <input id="income_tax" name="income_tax" placeholder="" type="text" class="form-control" value="<?php echo !empty($staff_session["income_tax"]) ? $staff_session["income_tax"] : "" ?>" />
                                                        <span class="text-danger"><?php echo form_error('income_tax'); ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo "PF Exempted"; ?></label>
                                                        <select id="pf_exempted" name="pf_exempted" class="form-control">
                                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                            <option value="1" <?php if (!empty($staff_subRow["pf_exempted"]) && $staff_subRow["pf_exempted"] == 1) {
                                                                                    echo "selected";
                                                                                } ?>><?php echo $this->lang->line('yes'); ?></option>
                                                            <option value="2" <?php if (!empty($staff_subRow["pf_exempted"]) && $staff_subRow["pf_exempted"] == 2) {
                                                                                    echo "selected";
                                                                                } ?>><?php echo $this->lang->line('no'); ?></option>
                                                        </select>
                                                        <span class="text-danger"><?php echo form_error('pf_exempted'); ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo "PT Exempted"; ?></label>
                                                        <select id="pt_exempted" name="pt_exempted" class="form-control">
                                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                            <option value="1" <?php if (!empty($staff_subRow["pt_exempted"]) && $staff_subRow["pt_exempted"] == 1) {
                                                                                    echo "selected";
                                                                                } ?>><?php echo $this->lang->line('yes'); ?></option>
                                                            <option value="2" <?php if (!empty($staff_subRow["pt_exempted"]) && $staff_subRow["pt_exempted"] == 2) {
                                                                                    echo "selected";
                                                                                } ?>><?php echo $this->lang->line('no'); ?></option>
                                                        </select>
                                                        <span class="text-danger"><?php echo form_error('pt_exempted'); ?></span>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="row around10">

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo "UAN NO"; ?></label>
                                                        <input id="uan_no" name="uan_no" placeholder="" type="text" class="form-control" value="<?php echo !empty($staff_subRow["uan_no"]) ? $staff_subRow["uan_no"] : "" ?>" />
                                                        <span class="text-danger"><?php echo form_error('uan_no'); ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo "IT Scheme"; ?></label>
                                                        <select id="it_scheme" name="it_scheme" class="form-control">
                                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                            <option value="1" <?php if (!empty($staff_subRow["it_scheme"]) && $staff_subRow["it_scheme"] == 1) {
                                                                                    echo "selected";
                                                                                } ?>><?php echo $this->lang->line('new'); ?></option>
                                                            <option value="2" <?php if (!empty($staff_subRow["it_scheme"]) && $staff_subRow["it_scheme"] == 2) {
                                                                                    echo "selected";
                                                                                } ?>><?php echo "Old"; ?></option>
                                                        </select>
                                                        <span class="text-danger"><?php echo form_error('it_scheme'); ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo "DCPS No"; ?></label>
                                                        <input id="dcps_no" name="dcps_no" placeholder="" type="text" class="form-control" value="<?php echo !empty($staff_subRow["dcps_no"]) ? $staff_subRow["dcps_no"] : "" ?>" />
                                                        <span class="text-danger"><?php echo form_error('dcps_no'); ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo "Gratuity No"; ?></label>
                                                        <input id="gratuity_no" name="gratuity_no" placeholder="" type="text" class="form-control" value="<?php echo !empty($staff_subRow["gratuity_no"]) ? $staff_subRow["gratuity_no"] : "" ?>" />
                                                        <span class="text-danger"><?php echo form_error('gratuity_no'); ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo "LWF Applicable "; ?></label>
                                                        <select id="lwf_applicable" name="lwf_applicable" class="form-control">
                                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                            <option value="1" <?php if (!empty($staff_subRow["lwf_applicable"]) && $staff_subRow["lwf_applicable"] == 1) {
                                                                                    echo "selected";
                                                                                } ?>><?php echo $this->lang->line('yes'); ?></option>
                                                            <option value="2" <?php if (!empty($staff_subRow["lwf_applicable"]) && $staff_subRow["lwf_applicable"] == 2) {
                                                                                    echo "selected";
                                                                                } ?>><?php echo $this->lang->line('no'); ?></option>
                                                        </select>
                                                        <span class="text-danger"><?php echo form_error('lwf_applicable'); ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo "LWF Grade"; ?></label>
                                                        <input id="lwf_grade" name="lwf_grade" placeholder="" type="text" class="form-control" value="<?php echo !empty($staff_subRow["lwf_grade"]) ? $staff_subRow["lwf_grade"] : "" ?>" />
                                                        <span class="text-danger"><?php echo form_error('lwf_grade'); ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo "Increment Month"; ?></label>
                                                        <input id="increment_month" name="increment_month" placeholder="" type="text" class="form-control" value="<?php echo !empty($staff_subRow["increment_month"]) ? $staff_subRow["increment_month"] : "" ?>" />
                                                        <span class="text-danger"><?php echo form_error('increment_month'); ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo "Increment Amount"; ?></label>
                                                        <input id="increment_amount" name="increment_amount" placeholder="" type="text" class="form-control" value="<?php echo !empty($staff_subRow["increment_amount"]) ? $staff_subRow["increment_amount"] : "" ?>" />
                                                        <span class="text-danger"><?php echo form_error('increment_amount'); ?></span>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="tshadow mb25 bozero">
                                            <h4 class="pagetitleh2"><?php echo "ESI"; ?>
                                            </h4>
                                            <div class="row around10">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo "ESI No"; ?></label>
                                                        <input id="esi_no" name="esi_no" placeholder="" type="text" class="form-control" value="<?php echo !empty($staff_subRow["esi_no"]) ? $staff_subRow["esi_no"] : "" ?>" />
                                                        <span class="text-danger"><?php echo form_error('esi_no'); ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo "ESI Dispensary"; ?></label>
                                                        <input id="esi_dispensary" name="esi_dispensary" placeholder="" type="text" class="form-control" value="<?php echo !empty($staff_subRow["esi_dispensary"]) ? $staff_subRow["esi_dispensary"] : "" ?>" />
                                                        <span class="text-danger"><?php echo form_error('esi_dispensary'); ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo "ESI Exempted"; ?></label>
                                                        <select id="esi_exempted" name="esi_exempted" class="form-control">
                                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                            <option value="1" <?php if (!empty($staff_subRow["esi_exempted"]) && $staff_subRow["esi_exempted"] == 1) {
                                                                                    echo "selected";
                                                                                } ?>><?php echo $this->lang->line('yes'); ?></option>
                                                            <option value="2" <?php if (!empty($staff_subRow["esi_exempted"]) && $staff_subRow["esi_exempted"] == 2) {
                                                                                    echo "selected";
                                                                                } ?>><?php echo $this->lang->line('no'); ?></option>
                                                        </select>
                                                        <span class="text-danger"><?php echo form_error('esi_exempted'); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php if ($sch_setting->staff_leaves) { ?>
                                            <div class="tshadow mb25 bozero">
                                                <div class="pagetitleh2">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <h4><?php echo $this->lang->line('leaves'); ?></h4>
                                                        </div>
                                                        <div class="col-md-6 text-right">
                                                            <button class="btn btn-primary first_modal" type="button"><?php echo $this->lang->line('leaves'); ?> Opening</button>
                                                        </div>
                                                    </div>


                                                </div>

                                                <div class="row around10">
                                                    <?php
                                                    $j = 0;
                                                    foreach ($leavetypeList as $key => $leave) {
                                                        # code...
                                                    ?>

                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="exampleInputEmail1"><?php echo $leave["type"]; ?></label>


                                                                <input id="ifsc_code" name="alloted_leave[]" placeholder="Number of leaves" type="text" class="form-control" value="<?php
                                                                                                                                                                                    if (array_key_exists($j, $staffLeaveDetails)) {
                                                                                                                                                                                        echo $staffLeaveDetails[$j]["alloted_leave"];
                                                                                                                                                                                    }
                                                                                                                                                                                    ?>" />

                                                                <input name="leave_type[]" placeholder="" type="hidden" readonly class="form-control" value="<?php echo $leave["type"] ?>" />

                                                                <input name="altid[]" placeholder="" type="hidden" readonly class="form-control" value="<?php
                                                                                                                                                        if (array_key_exists($j, $staffLeaveDetails)) {
                                                                                                                                                            echo $staffLeaveDetails[$j]["altid"];
                                                                                                                                                        }
                                                                                                                                                        ?>" />

                                                                <input name="leave_type_id[]" placeholder="" type="hidden" class="form-control" value="<?php echo $leave["id"]; ?>" />
                                                                <span class="text-danger"><?php echo form_error('ifsc_code'); ?></span>
                                                            </div>
                                                        </div>
                                                    <?php
                                                        $j++;
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <div class="tshadow mb25 bozero">
                                            <h4 class="pagetitleh2"><?php echo "Passport Details"; ?>
                                            </h4>
                                            <div class="row around10">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo "Passport No"; ?></label>
                                                        <input id="passport_no" name="passport_no" placeholder="" type="text" class="form-control" value="<?php echo !empty($staff_subRow["passport_no"]) ?  $staff_subRow["passport_no"] : "" ?>" />
                                                        <span class="text-danger"><?php echo form_error('passport_no'); ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo "Place Of Issue"; ?></label>
                                                        <input id="place_of_issue" name="place_of_issue" placeholder="" type="text" class="form-control" value="<?php echo !empty($staff_subRow["place_of_issue"]) ?  $staff_subRow["place_of_issue"] : "" ?>" />
                                                        <span class="text-danger"><?php echo form_error('place_of_issue'); ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo "Date Of Issue"; ?></label>
                                                        <input id="date_of_issue" name="date_of_issue" placeholder="" type="text" class="form-control date" value="<?php
                                                                                                                                                                    if (!empty($staff_subRow["date_of_issue"]) && $staff_subRow["date_of_issue"]  != '0000-00-00') {
                                                                                                                                                                        echo date($this->customlib->getSchoolDateFormat(), strtotime($staff_subRow["date_of_issue"]));
                                                                                                                                                                    }
                                                                                                                                                                    ?>" />
                                                        <span class="text-danger"><?php echo form_error('date_of_issue'); ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo "Date Of Expiry"; ?></label>
                                                        <input id="date_of_expiry" name="date_of_expiry" placeholder="" type="text" class="form-control date" value="<?php
                                                                                                                                                                        if (!empty($staff_subRow["date_of_expiry"]) && $staff_subRow["date_of_expiry"] != '0000-00-00') {
                                                                                                                                                                            echo date($this->customlib->getSchoolDateFormat(), strtotime($staff_subRow["date_of_expiry"]));
                                                                                                                                                                        }
                                                                                                                                                                        ?>" />
                                                        <span class="text-danger"><?php echo form_error('date_of_expiry'); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                        if ($sch_setting->staff_account_details) { ?>
                                            <div class="tshadow mb25 bozero">
                                                <h4 class="pagetitleh2"><?php echo $this->lang->line('bank_account_details'); ?>
                                                </h4>
                                                <div class="row around10">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('account_title'); ?></label>
                                                            <input id="account_title" name="account_title" placeholder="" type="text" class="form-control" value="<?php echo $staff["account_title"] ?>" />
                                                            <span class="text-danger"><?php echo form_error('bank_account_no'); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('bank_account_no'); ?></label>
                                                            <input id="bank_account_no" name="bank_account_no" placeholder="" type="text" class="form-control" value="<?php echo $staff["bank_account_no"] ?>" />
                                                            <span class="text-danger"><?php echo form_error('bank_account_no'); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('bank_name'); ?></label>
                                                            <input id="bank_name" name="bank_name" placeholder="" type="text" class="form-control" value="<?php echo $staff["bank_name"] ?>" />
                                                            <span class="text-danger"><?php echo form_error('bank_name'); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('ifsc_code'); ?></label>
                                                            <input id="ifsc_code" name="ifsc_code" placeholder="" type="text" class="form-control" value="<?php echo $staff["ifsc_code"] ?>" />
                                                            <span class="text-danger"><?php echo form_error('ifsc_code'); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('bank_branch_name'); ?></label>
                                                            <input id="bank_branch" name="bank_branch" placeholder="" type="text" class="form-control" value="<?php echo $staff["bank_branch"] ?>" />
                                                            <span class="text-danger"><?php echo form_error('bank_branch'); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Salary To Bank </label><small class="req"> </small>
                                                            <select name="salary_to_bank" id="salary_to_bank" class="form-control">
                                                                <option value="">Select</option>
                                                                <?php if (!empty($bank_list)) {
                                                                    foreach ($bank_list as $key => $value) {
                                                                        ?>
                                                                        <option value="<?php echo $value['id']; ?>" <?php
                                                                        if (!empty($staff['salary_to_bank']) && $staff['salary_to_bank'] == $value['id']) {
                                                                            echo "selected";
                                                                        }
                                                                        ?> >
                                                                            <?php echo $value['bank_head']; ?>
                                                                        </option>
                                                                        <?php
                                                                    }
                                                                }?>
                                                            </select>
                                                            <span class="text-danger"><?php echo form_error('salary_to_bank'); ?></span>
                                                        </div>
                                                    </div>
                                                </div>


                                            </div>
                                            <div class="tshadow mb25 bozero">
                                                <h4 class="pagetitleh2"><?php echo "Left Details"; ?>
                                                </h4>
                                                <div class="row around10">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1"><?php echo "Date of Retirement"; ?></label>
                                                            <input id="date_of_retirement" name="date_of_retirement" placeholder="" type="text" class="form-control date" value="<?php
                                                                                                                                                                                    if (!empty($staff_subRow) && $staff_subRow["date_of_retirement"] != '' && $staff_subRow["date_of_retirement"] != '0000-00-00') {
                                                                                                                                                                                        echo date($this->customlib->getSchoolDateFormat(), strtotime($staff_subRow["date_of_retirement"]));
                                                                                                                                                                                    }
                                                                                                                                                                                    ?>" />
                                                            <span class="text-danger"><?php echo form_error('date_of_retirement'); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1"><?php echo "LeftOn"; ?></label>
                                                            <input id="lefton" name="lefton" placeholder="" type="text" class="form-control date" value="<?php
                                                                                                                                                            if (!empty($staff_subRow["lefton"]) && $staff_subRow["lefton"] != '0000-00-00') {
                                                                                                                                                                echo date($this->customlib->getSchoolDateFormat(), strtotime($staff_subRow["lefton"]));
                                                                                                                                                            }
                                                                                                                                                            ?>" />
                                                            <span class="text-danger"><?php echo form_error('lefton'); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1"><?php echo "Left Reason"; ?></label>
                                                            <input id="left_reason" name="left_reason" placeholder="" type="text" class="form-control" value="<?php echo set_value('left_reason', !empty($staff_subRow["left_reason"]) ? $staff_subRow["left_reason"] : ""); ?>" />
                                                            <span class="text-danger"><?php echo form_error('left_reason'); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1"><?php echo "Salary Upto"; ?></label>
                                                            <input id="salary_upto_month" name="salary_upto_month" placeholder="" type="text" class="form-control" value="<?php echo set_value('salary_upto_month', !empty($staff["salary_upto_month"]) ? $staff["salary_upto_month"] : ""); ?>" />
                                                            <span class="text-danger"><?php echo form_error('salary_upto_month'); ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php }
                                        if ($sch_setting->staff_social_media) { ?>
                                            <div class="tshadow mb25 bozero">
                                                <h4 class="pagetitleh2"><?php echo $this->lang->line('social_media'); ?>
                                                </h4>

                                                <div class="row around10">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('facebook_url'); ?></label>
                                                            <input id="bank_account_no" name="facebook" placeholder="" type="text" class="form-control" value="<?php echo $staff["facebook"] ?>" />

                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('twitter_url'); ?></label>
                                                            <input id="bank_account_no" name="twitter" placeholder="" type="text" class="form-control" value="<?php echo $staff["twitter"] ?>" />

                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('linkedin_url'); ?></label>
                                                            <input id="bank_name" name="linkedin" placeholder="" type="text" class="form-control" value="<?php echo $staff["linkedin"] ?>" />

                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('instagram_url'); ?></label>
                                                            <input id="instagram" name="instagram" placeholder="" type="text" class="form-control" value="<?php echo $staff["instagram"] ?>" />

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php }
                                        if ($sch_setting->staff_upload_documents) { ?>
                                            <div id='upload_documents_hide_show'>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="tshadow bozero">
                                                            <h4 class="pagetitleh2"><?php echo $this->lang->line('upload_documents'); ?></h4>
                                                            <div class="row around10">
                                                                <div class="col-md-6">
                                                                    <table class="table">
                                                                        <tbody>
                                                                            <tr>
                                                                                <th style="width: 10px">#</th>
                                                                                <th><?php echo $this->lang->line('title'); ?></th>
                                                                                <th><?php echo $this->lang->line('documents'); ?></th>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>1.</td>
                                                                                <td><?php echo $this->lang->line('resume'); ?></td>
                                                                                <td>
                                                                                    <input class="filestyle form-control" type='file' name='first_doc' id="doc1">
                                                                                    <input class=" form-control" type='hidden' name='resume' value="<?php echo $staff["resume"] ?>">
                                                                                    <span class="text-danger"><?php echo form_error('first_doc'); ?></span>
                                                                                </td>
                                                                            </tr>

                                                                            <tr>
                                                                                <td>3.</td>
                                                                                <td><?php echo $this->lang->line('resignation_letter'); ?></td>
                                                                                <td>
                                                                                    <input class="filestyle form-control" type='file' name='third_doc' id="doc3">
                                                                                    <input class=" form-control" type='hidden' name='resignation_letter' value="<?php echo $staff["resignation_letter"] ?>">
                                                                                    <span class="text-danger"><?php echo form_error('resignation_letter'); ?></span>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <table class="table">
                                                                        <tbody>
                                                                            <tr>
                                                                                <th style="width: 10px">#</th>
                                                                                <th><?php echo $this->lang->line('title'); ?></th>
                                                                                <th><?php echo $this->lang->line('documents'); ?></th>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>2.</td>
                                                                                <td><?php echo $this->lang->line('joining_letter'); ?></td>
                                                                                <td>
                                                                                    <input class="filestyle form-control" type='file' name='second_doc' id="doc2">
                                                                                    <input class=" form-control" type='hidden' name='joining_letter' value="<?php echo $staff["joining_letter"] ?>">
                                                                                    <span class="text-danger"><?php echo form_error('second_doc'); ?></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>4.</td>
                                                                                <td><?php echo $this->lang->line('other_documents'); ?><input type="hidden" name='fourth_title' value="<?php echo $staff["other_document_file"] ?>" class="form-control" placeholder="Other Documents">
                                                                                </td>
                                                                                <td>
                                                                                    <input class="filestyle form-control" type='file' name='fourth_doc' id="doc4">
                                                                                    <input class=" form-control" type='hidden' name='other_document_file' value="<?php echo $staff["other_document_file"] ?>">
                                                                                    <span class="text-danger"><?php echo form_error('other_document_file'); ?></span>
                                                                                </td>
                                                                            </tr>

                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            
                            
                        </div>
                        <div class="box-footer">
                            <input type="hidden" name="staff_sub_id" value="<?php echo !empty($staff_subRow['id']) ? $staff_subRow['id'] : "" ?>">
                            <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
</div>
</section>
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
                <form  id="saveStaffleaveopening" action="<?php echo site_url('admin/staff/saveStaffleaveopening'); ?>" method="post" class="mb10">
                    <div class="row">
                        <?php
                        foreach ($leavetypeList as $leave) {
                            $update = $this->staff_model->getstaffleaveOpen($leave['id'],$id);
                        ?>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label><?php echo $leave["type"]; ?><small class="req"> *</small></label>
                                    <input type="text" name="opening[]" id="opening" class="form-control" value="<?php echo !empty($update) ? $update['opening'] : "" ;?>">
                                    <input type="hidden" name="id[]" id="id" value="<?php echo !empty($update) ? $update['id'] : "" ;?>">
                                    <input type="hidden" name="leaves_type_id[]" id="leaves_type_id" value="<?php echo $leave['id'];?>">
                                    <span class="text-danger"><?php echo form_error('opening'); ?></span>
                                </div>
                                <!--./form-group-->
                            </div>
                        <?php
                        }
                        ?>
                        <div class="col-sm-12">
                            <div class="form-group">
                                
                                <input type="hidden" name="staff_id" id="staff_id" value="<?php echo $id;?>">
                                <button type="submit" name="save" class="btn btn-primary pull-right btn-sm checkbox-toggle "> <?php echo $this->lang->line('save'); ?></button>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
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
        var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy',]) ?>';
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



        $("#btnreset").click(function() {
            $("#form1")[0].reset();
        });

    });


    $(document).ready(function() {
        $('#subjectModal').modal({
            backdrop: 'static',
            keyboard: false,
            show: false
        })
    });

    $(document).on("click", ".first_modal", function(e) {
        var title_exam = "Leave";
        $('.subjectmodal_header1').html("").html(title_exam);
        $('#subjectModal').modal('show');
    });

    $("form#saveStaffleaveopening").on('submit', (function(e) {
        e.preventDefault(); // avoid to execute the actual submit of the form.
        var form = $(this);
        var $this = form.find("button[type=submit]:focus");
        var url = form.attr('action');

        $.ajax({
            url: url,
            type: "POST",
            data:form.serialize(),
            dataType: 'json',
            beforeSend: function() {
                $this.button('loading');
            },
            success: function(data) {
                if (data.status == "0") {
                    var message = "";
                    $.each(data.error, function(index, value) {

                        message += value;
                    });
                    errorMsg(message);
                } else {
                    $('#subjectModal').modal('hide');
                    location.reload();
                }


            },
            error: function(xhr) { // if error occured
                // alert("Error occured.please try again");
                $this.button('reset');
                // setTimeout(function() {
                //     history.go(0);
                // }, 3000);
            },
            complete: function() {
                $this.button('reset');

            }

        });
    }));
    // function auto_fill_guardian_address() {
    //     if ($("#autofill_current_address").is(':checked')) {
    //         $('#current_address').val($('#guardian_address').val());
    //     }
    // }

    // function auto_fill_address() {
    //     if ($("#autofill_address").is(':checked')) {
    //         $('#permanent_address').val($('#current_address').val());
    //     }
    // }
    $('input:radio[name="guardian_is"]').change(
        function() {
            if ($(this).is(':checked')) {
                var value = $(this).val();
                if (value == "father") {
                    $('#guardian_name').val($('#father_name').val());
                    $('#guardian_phone').val($('#father_phone').val());
                    $('#guardian_occupation').val($('#father_occupation').val());
                    $('#guardian_relation').val("Father")
                } else if (value == "mother") {
                    $('#guardian_name').val($('#mother_name').val());
                    $('#guardian_phone').val($('#mother_phone').val());
                    $('#guardian_occupation').val($('#mother_occupation').val());
                    $('#guardian_relation').val("Mother")
                } else {
                    $('#guardian_name').val("");
                    $('#guardian_phone').val("");
                    $('#guardian_occupation').val("");
                    $('#guardian_relation').val("")
                }
            }
        });
</script>

<script type="text/javascript">
    $(".mysiblings").click(function() {
        $('.sibling_msg').html("");
        $('.modal_title').html('<b>' + "<?php echo $this->lang->line('sibling'); ?>" + '</b>');
        $('#mySiblingModal').modal({
            backdrop: 'static',
            keyboard: false,
            show: true
        });
    });
</script>

<script type="text/javascript">
    $(document).on('change', '#sibiling_class_id', function(e) {
        $('#sibiling_section_id').html("");
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
                $('#sibiling_section_id').append(div_data);
            }
        });
    });

    $(document).on('change', '#sibiling_section_id', function(e) {
        getStudentsByClassAndSection();
    });

    function getStudentsByClassAndSection() {
        $('#sibiling_student_id').html("");
        var class_id = $('#sibiling_class_id').val();
        var section_id = $('#sibiling_section_id').val();
        var student_id = '<?php echo set_value('student_id') ?>';
        var base_url = '<?php echo base_url() ?>';
        var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
        $.ajax({
            type: "GET",
            url: base_url + "student/getByClassAndSection",
            data: {
                'class_id': class_id,
                'section_id': section_id
            },
            dataType: "json",
            success: function(data) {
                $.each(data, function(i, obj) {
                    var sel = "";
                    if (section_id == obj.section_id) {
                        sel = "selected=selected";
                    }
                    div_data += "<option value=" + obj.id + ">" + obj.firstname + " " + obj.lastname + "</option>";
                });
                $('#sibiling_student_id').append(div_data);
            }
        });
    }

    // $(document).on('click', '.add_sibling', function() {
    //     var student_id = $('#sibiling_student_id').val();
    //     var base_url = '<?php echo base_url() ?>';
    //     if (student_id.length > 0) {
    //         $.ajax({
    //             type: "GET",
    //             url: base_url + "student/getStudentRecordByID",
    //             data: {
    //                 'student_id': student_id
    //             },
    //             dataType: "json",
    //             success: function(data) {
    //                 $('#sibling_name').text("Sibling: " + data.firstname + " " + data.lastname);
    //                 $('#sibling_name_next').val(data.firstname + " " + data.lastname);
    //                 $('#sibling_id').val(student_id);
    //                 $('#father_name').val(data.father_name);
    //                 $('#father_phone').val(data.father_phone);
    //                 $('#father_occupation').val(data.father_occupation);
    //                 $('#mother_name').val(data.mother_name);
    //                 $('#mother_phone').val(data.mother_phone);
    //                 $('#mother_occupation').val(data.mother_occupation);
    //                 $('#guardian_name').val(data.guardian_name);
    //                 $('#guardian_relation').val(data.guardian_relation);
    //                 $('#guardian_address').val(data.guardian_address);
    //                 $('#guardian_phone').val(data.guardian_phone);
    //                 $('#state').val(data.state);
    //                 $('#city').val(data.city);
    //                 $('#pincode').val(data.pincode);
    //                 $('#current_address').val(data.current_address);
    //                 $('#permanent_address').val(data.permanent_address);
    //                 $('#guardian_occupation').val(data.guardian_occupation);
    //                 $("input[name=guardian_is][value='" + data.guardian_is + "']").prop("checked", true);
    //                 $('#mySiblingModal').modal('hide');
    //             }
    //         });
    //     } else {
    //         $('.sibling_msg').html("<div class='alert alert-danger'>No Student Selected</div>");
    //     }

    // });

    function auto_fill_guardian_address() {
        if ($("#autofill_current_address").is(':checked')) {
            $('.permanent_address').val($('#addresss').val());
            $('#permanent_city').val($('#current_city').val());
            $('#permanent_state').val($('#current_state').val());
            $('#permanent_country').val($('#current_country').val());
            $('#permanent_pincode').val($('#current_pincode').val());
        }
    }
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

    var date_format = "mm-yyyy"; // Specify the date format
    var start_week = 0; // Start the week on Sunday (0) or Monday (1)

    // Initialize the datepicker with month and year view
    $('#salary_upto_month').datepicker({
      todayHighlight: false,
      format: date_format,
      autoclose: true,
      startView: 1,  // Start view to month and year selection
      minViewMode: 1,  // Only show months and years, no days
      weekStart: start_week,  // Week starts on Sunday or Monday
      language: 'en'
    });
});
</script>  