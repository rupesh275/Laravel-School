<script src="<?php echo base_url('backend/dist/js/moment.min.js'); ?>"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>backend/datepicker/css/bootstrap-datetimepicker.css">
<script src="<?php echo base_url(); ?>backend/datepicker/js/bootstrap-datetimepicker.js"></script>

<?php
if (!$form_admission) {
    ?>
    <div class="alert alert-danger">
        <?php echo $this->lang->line('admission_form_disable_please_contact_to_administrator'); ?>
    </div>
    <?php
    return;
}
?>
<?php

if ($this->session->flashdata('msg')) {
    $message = $this->session->flashdata('msg');
    echo $message;
}
?>

    <div class="row justify-content-center align-items-center flex-wrap d-flex pt20">
        <div class="col-md-8">
            <h3 class="entered mt0"><?php echo $this->lang->line('online') . " " . $this->lang->line('registration'); ?></h3>
        </div>
        <div class="col-md-4">
            <a href="#myModal" class="statusright modalclosebtn" onclick="openmodal();" data-toggle="modal" data-target="#myModal"><?php echo $this->lang->line('check_your_form_status') ?></a>
        </div>
    </div>
   <form id="form1" class="spaceb60 spacet40 onlineform" action="<?php echo current_url() ?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8" enctype="multipart/form-data">
        <?php if($online_admission_instruction!=""){ ?>
        <div class="printcontent">
        <div class="row">
         <h4 class="pagetitleh2"><?php echo $this->lang->line('instructions'); ?></h4>      
            <div class="col-md-12">
              <div class="form-group"> 
                    <?php  echo $online_admission_instruction ;  ?>
                </div> 
            </div>
        </div>
    </div>
  <?php } ?>
 <div class="printcontent">
    <div class="row">   
    <h4 class="pagetitleh2"><?php echo $this->lang->line('basic_details'); ?></h4>                             
        <div class="col-md-3">
            <div class="form-group">
                <label><?php echo "Session" ?></label><small class="req"> *</small> 
                <select  id="session_id" name="session_id" class="form-control"  >
                    <option value="16">2021-22</option>
                    <option value="18">2022-23</option>
                </select>
                <span class="text-danger"><?php echo form_error('session_id'); ?></span>
            </div>
        </div>    
        <div class="col-md-3">
            <div class="form-group">
                <label><?php echo $this->lang->line('class'); ?></label><small class="req"> *</small> 
                <select  id="class_id" name="class_id" class="form-control"  >
                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                    <?php
                    foreach ($classlist as $class) {
                        ?>
                        <option value="<?php echo $class['id'] ?>"<?php if (set_value('class_id') == $class['id']) echo "selected=selected" ?>><?php echo $class['class'] ?></option>
                        <?php
                    }
                    ?>
                </select>
                <span class="text-danger"><?php echo form_error('class_id'); ?></span>
            </div>
        </div>
        <div class="col-md-3 displaynone">
            <div class="form-group">
                <label><?php echo $this->lang->line('section'); ?></label><small class="req"> *</small> 
                <select  id="section_id" name="section_id" class="form-control" >
                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                </select>
                <span class="text-danger"><?php echo form_error('section_id'); ?></span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label><?php echo $this->lang->line('first_name'); ?></label><small class="req"> *</small> 
                <input id="firstname" name="firstname" placeholder="" type="text" class="form-control"  value="<?php echo set_value('firstname'); ?>" autocomplete="off" />
                <span class="text-danger"><?php echo form_error('firstname'); ?></span>
            </div>
        </div>
         <?php if ($this->customlib->getfieldstatus('middlename')) {?>                             
         <div class="col-md-3">
            <div class="form-group">
                <label><?php echo $this->lang->line('middle_name'); ?></label>
                  <input id="middlename" name="middlename" placeholder="" type="text" class="form-control"  value="<?php echo set_value('middlename'); ?>" autocomplete="off" />
            </div>
        </div>
        <?php } ?>       
        <?php if ($this->customlib->getfieldstatus('lastname')) {?>                             
         <div class="col-md-3">
            <div class="form-group">
                <label><?php echo $this->lang->line('last_name'); ?></label>
                <input id="lastname" name="lastname" placeholder="" type="text" class="form-control"  value="<?php echo set_value('lastname'); ?>" autocomplete="off" />
                <span class="text-danger"><?php echo form_error('lastname'); ?></span>
            </div>
        </div>
        <?php } ?>        
    </div><!--./row--> 
    <div class="row"> 
        <div class="col-md-3">
            <div class="form-group">
                <label><?php echo $this->lang->line('gender'); ?></label><small class="req"> *</small> 
                <select class="form-control" name="gender">
                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                    <?php
                    foreach ($genderList as $key => $value) {
                        ?>
                        <option value="<?php echo $key; ?>" <?php if (set_value('gender') == $key) echo "selected"; ?>><?php echo $value; ?></option>
                        <?php
                    }
                    ?>
                </select>
                <span class="text-danger"><?php echo form_error('gender'); ?></span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label><?php echo $this->lang->line('date_of_birth'); ?></label><small class="req"> *</small> 
                <input  type="text" class="form-control date2"  value="<?php echo set_value('dob'); ?>" id="dob" name="dob" readonly="readonly"/>
                <span class="text-danger"><?php echo form_error('dob'); ?></span>
            </div>
        </div>
          <?php if ($this->customlib->getfieldstatus('mobile_no')) {?>            
        <div class="col-md-3">
            <div class="form-group">
                <label><?php echo $this->lang->line('mobile_no'); ?></label>
                <input  type="text" class="form-control"  value="<?php echo set_value('mobileno'); ?>" id="mobileno" name="mobileno" autocomplete="off"/>
                <span class="text-danger"><?php echo form_error('mobileno'); ?></span>
            </div>
        </div>
        <?php } ?>
         <?php if($this->customlib->getfieldstatus('student_email')){ ?>

        <div class="col-md-3">
            <div class="form-group">
                <label><?php echo $this->lang->line('email'); ?></label><small class="req"> *</small>
                <input  type="text" class="form-control"  value="<?php echo set_value('email'); ?>" id="email" name="email" autocomplete="off"/>
                <span class="text-danger"><?php echo form_error('email'); ?></span>
            </div>
        </div>
        <?php } ?>
    </div><!--./row--> 
    <div class="row">
      <?php if ($this->customlib->getfieldstatus('category')) {?>  
        <div class="col-md-3">
            <div class="form-group">
               <label><?php echo $this->lang->line('category'); ?></label>
                    <select  id="category_id" name="category_id" class="form-control" >
                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                            <?php foreach ($categorylist as $category) { ?>
                         <option value="<?php echo $category['id'] ?>" <?php
                          if (set_value('category_id') == $category['id']) {
                                 echo "selected=selected";
                           }
                         ?>><?php echo $category['category'] ?>
                         </option>
                                <?php $count++;
                            }
                            ?>
                     </select>
            </div>
        </div>
        <?php } ?>
       <?php  if ($this->customlib->getfieldstatus('religion')) { ?>
            <div class="col-md-3">
                <div class="form-group">
                    <label><?php echo $this->lang->line('religion'); ?></label>
                    <input id="religion" name="religion" placeholder="" type="text" class="form-control"  value="<?php echo set_value('religion'); ?>" autocomplete="off" />
                    <span class="text-danger"><?php echo form_error('religion'); ?></span>
                </div>
            </div>
        <?php } if ($this->customlib->getfieldstatus('cast')) { ?>
                <div class="col-md-3">
                    <div class="form-group">
                        <label><?php echo $this->lang->line('cast'); ?></label>
                        <input id="cast" name="cast" placeholder="" type="text" class="form-control" autocomplete="off"  value="<?php echo set_value('cast'); ?>" />
                        <span class="text-danger"><?php echo form_error('cast'); ?></span>
                    </div>
                </div>
        <?php } ?>
        <?php if ($this->customlib->getfieldstatus('is_student_house')) {  ?>
            <div class="col-md-3 col-xs-12">
                <div class="form-group">
                    <label><?php echo $this->lang->line('house') ?></label>
                    <select class="form-control" rows="3" placeholder="" name="house">
                        <option value=""><?php echo $this->lang->line('select') ?></option>
                        <?php foreach ($houses as $hkey => $hvalue) {
                            ?>
                            <option value="<?php echo $hvalue["id"] ?>"><?php echo $hvalue["house_name"] ?></option>

                    <?php } ?>
                    </select>
                    <span class="text-danger"><?php echo form_error('house'); ?></span>
                </div>
            </div>
            <?php
        }
        ?>       
    </div><!--./row-->
    <div class="row">
     <?php if ($this->customlib->getfieldstatus('is_blood_group')) { ?>
        <div class="col-md-3 col-xs-12">
            <div class="form-group">
                <label><?php echo $this->lang->line('blood_group'); ?></label>
                    <?php
                    ?>
                <select class="form-control" rows="3" placeholder="" name="blood_group">
                    <option value=""><?php echo $this->lang->line('select') ?></option>
                    <?php foreach ($bloodgroup as $bgkey => $bgvalue) {
                        ?>
                        <option value="<?php echo $bgvalue ?>"><?php echo $bgvalue ?></option>

                    <?php } ?>
                </select>
                <span class="text-danger"><?php echo form_error('blood_group'); ?></span>
            </div>
        </div>
        <?php } ?>        
          <?php if ($this->customlib->getfieldstatus('student_height')) { ?>
             <div class="col-md-3 col-xs-12">
                <div class="form-group">
                   <label><?php echo $this->lang->line('height'); ?></label>
                 <?php ?>
                <input type="text" name="height" class="form-control" value="<?php echo set_value('height'); ?>" autocomplete="off">
                <span class="text-danger"><?php echo form_error('height'); ?></span>
               </div>
             </div>
           <?php } if ($this->customlib->getfieldstatus('student_weight')) { ?>
            <div class="col-md-3 col-xs-12">
                 <div class="form-group">
                      <label><?php echo $this->lang->line('weight'); ?></label>
                    <input type="text" name="weight" class="form-control" value="<?php echo set_value('weight'); ?>" autocomplete="off" >
                    <span class="text-danger"><?php echo form_error('weight'); ?></span>
               </div>
            </div>
            <?php } ?>
             <?php  if ($this->customlib->getfieldstatus('measurement_date')) { ?>
            <div class="col-md-3 col-xs-12">
                <div class="form-group">
                  <label><?php echo $this->lang->line('measurement_date'); ?></label>
    
                <input type="text" id="measure_date" value="" name="measure_date" class="form-control date2" autocomplete="off" readonly="" >
                <span class="text-danger"><?php echo form_error('measure_date'); ?></span>
            </div>
        </div>
        <?php } ?>
    </div><!--./row--> 
    <div class="row">
        <?php  if ($this->customlib->getfieldstatus('student_photo')) { ?>
                <div class="col-md-3">
                    <div class="form-group">
                        <label><?php echo $this->lang->line('student') . " " . $this->lang->line('photo'); ?></label>
                        <div><input class="filestyle form-control" type='file' name='file' id="file" size='20' />
                        </div>
                        <span class="text-danger"><?php echo form_error('file'); ?></span></div>
                </div>
                <?php
            } ?>
    </div>  
    <div class="row">
            <?php
            echo display_admission_custom_fields('students');
            ?>
        </div>
     </div>   
    <?php if( $this->customlib->getfieldstatus('father_name') || $this->customlib->getfieldstatus('father_phone') || $this->customlib->getfieldstatus('father_occupation') || $this->customlib->getfieldstatus('father_pic') || $this->customlib->getfieldstatus('mother_name') || $this->customlib->getfieldstatus('mother_phone') || $this->customlib->getfieldstatus('mother_occupation') || $this->customlib->getfieldstatus('mother_pic') ){ ?>
    <div class="printcontent">
      <div class="row">  
        <h4 class="pagetitleh2"><?php echo $this->lang->line('parent_detail'); ?></h4>
       <?php if ($this->customlib->getfieldstatus('father_name')) {?>    
        <div class="col-md-3">
            <div class="form-group">
                <label><?php echo $this->lang->line('father_name'); ?></label>
                <input id="father_name" name="father_name" placeholder="" type="text" class="form-control" autocomplete="off"  value="<?php echo set_value('father_name'); ?>" />
                <span class="text-danger"><?php echo form_error('father_name'); ?></span>
            </div>
        </div>
        <?php } ?>
        <?php if ($this->customlib->getfieldstatus('father_phone')) {?>       
        <div class="col-md-3">
            <div class="form-group">
                <label><?php echo $this->lang->line('father_phone'); ?></label>
                <input id="father_phone" name="father_phone" placeholder="" type="text" class="form-control" autocomplete="off"  value="<?php echo set_value('father_phone'); ?>" />
                <span class="text-danger"><?php echo form_error('father_phone'); ?></span>
            </div>
        </div>
        <?php } ?>
        <?php if ($this->customlib->getfieldstatus('father_occupation')) {?>  
        <div class="col-md-3">
            <div class="form-group">
                <label><?php echo $this->lang->line('father_occupation'); ?></label>
                <input id="father_occupation" name="father_occupation" placeholder="" type="text" class="form-control"  value="<?php echo set_value('father_occupation'); ?> " autocomplete="off" />
                <span class="text-danger"><?php echo form_error('father_occupation'); ?></span>
            </div>
        </div>
        <?php } ?>
        <?php  if ($this->customlib->getfieldstatus('father_pic')) { ?>
            <div class="col-md-3">
                <div class="form-group">
                    <label><?php echo $this->lang->line('father'); ?> <?php echo $this->lang->line('photo'); ?></label>
                    <div><input class="filestyle form-control" type='file' name='father_pic' id="file" size='20' />
                    </div>
                    <span class="text-danger"><?php echo form_error('father_pic'); ?></span></div>
            </div>
        <?php } ?>
        </div><!---row-->       
        <div class="row">
         <?php if ($this->customlib->getfieldstatus('mother_name')) {?>  
        <div class="col-md-3">
            <div class="form-group">
                <label><?php echo $this->lang->line('mother_name'); ?></label>
                <input id="mother_name" name="mother_name" placeholder="" type="text" class="form-control"  value="<?php echo set_value('mother_name'); ?>" autocomplete="off"/>
                <span class="text-danger"><?php echo form_error('mother_name'); ?></span>
            </div>
        </div>
        <?php } ?>
        <?php if ($this->customlib->getfieldstatus('mother_phone')) {?>
        <div class="col-md-3">
            <div class="form-group">
                <label><?php echo $this->lang->line('mother_phone'); ?></label>
                <input id="mother_phone" name="mother_phone" placeholder="" type="text" class="form-control"  value="<?php echo set_value('mother_phone'); ?>" autocomplete="off"/>
                <span class="text-danger"><?php echo form_error('mother_phone'); ?></span>
            </div>
        </div>
        <?php } ?>
        <?php if ($this->customlib->getfieldstatus('mother_occupation')) {?>
        <div class="col-md-3">
            <div class="form-group">
                <label><?php echo $this->lang->line('mother_occupation'); ?></label>
                <input id="mother_occupation" name="mother_occupation" placeholder="" type="text" class="form-control"  value="<?php echo set_value('mother_occupation'); ?>" autocomplete="off"/>
                <span class="text-danger"><?php echo form_error('mother_occupation'); ?></span>
            </div>
        </div>
        <?php } ?>
        <?php  if ($this->customlib->getfieldstatus('mother_pic')) { ?>
            <div class="col-md-3">
                <div class="form-group">
                    <label><?php echo $this->lang->line('mother'); ?> <?php echo $this->lang->line('photo'); ?></label>
                    <div><input class="filestyle form-control" type='file' name='mother_pic' id="file" size='20' />
                    </div>
                    <span class="text-danger"><?php echo form_error('mother_pic'); ?></span></div>
            </div>
       <?php } ?>    
    </div><!--./row--> 
    </div><!--./printcontent-->    
    <?php } ?>       
     <?php if ($this->customlib->getfieldstatus('if_guardian_is')) {?>
    <div class="printcontent">    
       <div class="row">
        <h4 class="pagetitleh2"><?php echo $this->lang->line('guardian_details'); ?></h4>
          <div class="form-group col-md-12">
            <label><?php echo $this->lang->line('if_guardian_is'); ?><small class="req"> *</small>&nbsp;&nbsp;&nbsp;</label>
            <label class="radio-inline">
                <input type="radio" name="guardian_is" <?php
                echo set_value('guardian_is') == "father" ? "checked" : "";
                ?>   value="father"> <?php echo $this->lang->line('father'); ?>
            </label>
            <label class="radio-inline">
                <input type="radio" name="guardian_is" <?php
                echo set_value('guardian_is') == "mother" ? "checked" : "";
                ?>   value="mother"> <?php echo $this->lang->line('mother'); ?>
            </label>
            <label class="radio-inline">
                <input type="radio" name="guardian_is" <?php
                echo set_value('guardian_is') == "other" ? "checked" : "";
                ?>   value="other"> <?php echo $this->lang->line('other'); ?>
            </label>
            <span class="text-danger"><?php echo form_error('guardian_is'); ?></span>
        </div>
        <?php if ($this->customlib->getfieldstatus('guardian_name')) {?>
        <div class="col-md-3">
            <div class="form-group">
                <label><?php echo $this->lang->line('guardian_name'); ?></label><small class="req"> *</small>
                <input id="guardian_name" name="guardian_name" placeholder="" type="text" class="form-control"  value="<?php echo set_value('guardian_name'); ?>" autocomplete="off" />
                <span class="text-danger"><?php echo form_error('guardian_name'); ?></span>
            </div>
        </div>
        <?php } ?>
           <?php if ($this->customlib->getfieldstatus('guardian_relation')) {?>
        
        <div class="col-md-3">
            <div class="form-group">
                <label><?php echo $this->lang->line('guardian_relation'); ?></label><small class="req"> *</small>
                <input id="guardian_relation" name="guardian_relation" placeholder="" type="text" class="form-control"  value="<?php echo set_value('guardian_relation'); ?>" autocomplete="off"/>
                <span class="text-danger"><?php echo form_error('guardian_relation'); ?></span>
            </div>
        </div>
        <?php } ?>
         <?php if ($this->customlib->getfieldstatus('guardian_email')) {?>
        <div class="col-md-3">
            <div class="form-group">
                <label><?php echo $this->lang->line('guardian_email'); ?></label>
                <input id="guardian_email" name="guardian_email" placeholder="" type="text" class="form-control"  value="<?php echo set_value('guardian_email'); ?>" autocomplete="off"/>
                <span class="text-danger"><?php echo form_error('guardian_email'); ?></span>
            </div>
        </div>
        <?php } ?>
         <?php if($this->customlib->getfieldstatus('guardian_photo')){ ?>
          <div class="col-md-3">
            <div class="form-group">
                <label><?php echo $this->lang->line('guardian'); ?> <?php echo $this->lang->line('photo'); ?></label>
                <div><input class="filestyle form-control" type='file' name='guardian_pic' id="file" size='20' />
                </div> 
                <span class="text-danger"><?php echo form_error('guardian_pic'); ?></span></div>
        </div>        
        <?php } ?>    
    </div><!--./row-->    
    <div class="row">
    <?php if($this->customlib->getfieldstatus('guardian_phone')){ ?>
        <div class="col-md-3">
            <div class="form-group">
                <label><?php echo $this->lang->line('guardian_phone'); ?></label>
                <input id="guardian_phone" name="guardian_phone" placeholder="" type="text" class="form-control"  value="<?php echo set_value('guardian_phone'); ?>" autocomplete="off"/>
                <span class="text-danger"><?php echo form_error('guardian_phone'); ?></span>
            </div>
        </div>
        <?php } ?>
        <?php if ($this->customlib->getfieldstatus('guardian_occupation')) {?>
        <div class="col-md-3">
            <div class="form-group">
                <label><?php echo $this->lang->line('guardian_occupation'); ?></label>
                <input id="guardian_occupation" name="guardian_occupation" placeholder="" type="text" class="form-control"  value="<?php echo set_value('guardian_occupation'); ?>" autocomplete="off" />
                <span class="text-danger"><?php echo form_error('guardian_occupation'); ?></span>
            </div>
        </div>
        <?php } ?>
        
         <?php if ($this->customlib->getfieldstatus('guardian_address')) {?>
        <div class="col-md-6">
            <div class="form-group">   
                <label><?php echo $this->lang->line('guardian_address'); ?></label>
                <textarea id="guardian_address" name="guardian_address" placeholder="" class="form-control" rows="1" autocomplete="off"><?php echo set_value('guardian_address'); ?></textarea>
                <span class="text-danger"><?php echo form_error('guardian_address'); ?></span>
            </div>  
        </div>
        <?php } ?>
        </div>
       </div>
       <?php } ?>
       <?php if($this->customlib->getfieldstatus('current_address')  || $this->customlib->getfieldstatus('permanent_address') ){ ?>
    <div class="printcontent">    
        <div class="row">
            <h4 class="pagetitleh2"><?php echo $this->lang->line('student'); ?> <?php echo $this->lang->line('address'); ?> <?php echo $this->lang->line('details'); ?></h4>

            <?php if ($this->customlib->getfieldstatus('current_address')) { ?>
                <div class="col-md-6">
                 <?php if ($this->customlib->getfieldstatus('guardian_address')) {?>
                    <div class="checkbox">
                        <label> <input type="checkbox" id="autofill_current_address" onclick="return auto_fill_guardian_address();" autocomplete="off">
                        <?php echo $this->lang->line('if_guardian_address_is_current_address'); ?>
                         </label>
                    </div>
                    <?php }else{ echo "<div class='checkbox'><label>&nbsp;</label></div>"; } ?> 
                    <div class="form-group">
                        <label><?php echo $this->lang->line('current_address'); ?></label>
                        <textarea id="current_address" name="current_address" placeholder="" rows="1" class="form-control" autocomplete="off"><?php echo set_value('current_address'); ?><?php echo set_value('current_address'); ?></textarea>
                        <span class="text-danger"><?php echo form_error('current_address'); ?></span>
                    </div>
                </div>
                 <?php } if ($this->customlib->getfieldstatus('permanent_address')) { ?>
                    <div class="col-md-6">
                    	 <?php if ($this->customlib->getfieldstatus('current_address')) { ?>
                        <div class="checkbox">
                            <label> <input type="checkbox" id="autofill_address"onclick="return auto_fill_address();">
                    			<?php echo $this->lang->line('if_permanent_address_is_current_address'); ?>  </label>
                         </div>
                         <?php }else{ echo  "<div class='checkbox'><label>&nbsp;</label></div>"; } ?>
	                  <div class="form-group">
	                        <label><?php echo $this->lang->line('permanent_address'); ?></label>
	                        <textarea id="permanent_address" name="permanent_address" rows="1" placeholder="" class="form-control" autocomplete="off"></textarea>
	                        <span class="text-danger"><?php echo form_error('permanent_address'); ?></span>
	                    </div>
                </div>
                <?php } ?>
             </div>
            </div> 
             <?php } ?>  

             <?php if( $this->customlib->getfieldstatus('bank_account_no') || $this->customlib->getfieldstatus('bank_name') || $this->customlib->getfieldstatus('ifsc_code') || $this->customlib->getfieldstatus('national_identification_no') || $this->customlib->getfieldstatus('local_identification_no') || $this->customlib->getfieldstatus('rte') || $this->customlib->getfieldstatus('previous_school_details') || $this->customlib->getfieldstatus('student_note') ) { ?>
        <div class="printcontent">        
            <div class="row">
            	<h4 class="pagetitleh2"><?php echo $this->lang->line('miscellaneous_details'); ?></h4>
              <?php  if ($this->customlib->getfieldstatus('bank_account_no')) { ?>
                <div class="col-md-4">
                    <div class="form-group">
                        <label><?php echo $this->lang->line('bank_account_no'); ?></label>
                        <input id="bank_account_no" name="bank_account_no" placeholder="" type="text" class="form-control"  value="<?php echo set_value('bank_account_no'); ?>" autocomplete="off" />
                        <span class="text-danger"><?php echo form_error('bank_account_no'); ?></span>
                    </div>
                </div>                              
            <?php }  if ($this->customlib->getfieldstatus('bank_name')) { ?>
                <div class="col-md-4">
                    <div class="form-group">
                        <label><?php echo $this->lang->line('bank_name'); ?></label>
                        <input id="bank_name" name="bank_name" placeholder="" type="text" class="form-control"  value="<?php echo set_value('bank_name'); ?>" autocomplete="off" />
                        <span class="text-danger"><?php echo form_error('bank_name'); ?></span>
                    </div>
                </div><?php }
                if ($this->customlib->getfieldstatus('ifsc_code')) { ?>
                <div class="col-md-4">
                    <div class="form-group">
                        <label><?php echo $this->lang->line('ifsc_code'); ?></label>
                        <input id="ifsc_code" name="ifsc_code" placeholder="" type="text" class="form-control"  value="<?php echo set_value('ifsc_code'); ?>" autocomplete="off" />
                        <span class="text-danger"><?php echo form_error('ifsc_code'); ?></span>
                    </div>
                </div>
            <?php } ?>
        </div>
        <div class="row">
            <?php if ($this->customlib->getfieldstatus('national_identification_no')) { ?>
              <div class="col-md-4">
                <div class="form-group">
                 <label>  <?php echo $this->lang->line('national_identification_no'); ?>  </label>
                  <input id="adhar_no" name="adhar_no" placeholder="" type="text" class="form-control"  value="<?php echo set_value('adhar_no'); ?>"  autocomplete="off"/>
                  <span class="text-danger"><?php echo form_error('adhar_no'); ?></span>
                 </div>
               </div>
             <?php } if ($this->customlib->getfieldstatus('local_identification_no')) { ?>
             <div class="col-md-4">
                 <div class="form-group">
                    <label> <?php echo $this->lang->line('local_identification_no'); ?>     </label>
                     <input id="samagra_id" name="samagra_id" placeholder="" type="text" class="form-control"  value="<?php echo set_value('samagra_id'); ?>" autocomplete="off" />
                         <span class="text-danger"><?php echo form_error('samagra_id'); ?></span>
                 </div>
              </div>
           <?php } if ($this->customlib->getfieldstatus('rte')) { ?>
                    <div class="col-md-4">
                        <label><?php echo $this->lang->line('rte'); ?></label>
                         <div class="radio" style="margin-top: 2px;">
                          <label><input class="radio-inline" type="radio" name="rte" value="Yes"  <?php
                        echo set_value('rte') == "yes" ? "checked" : "";
                        ?>  ><?php echo $this->lang->line('yes'); ?></label>
                            <label><input class="radio-inline" checked="checked" type="radio" name="rte" value="No" <?php
                            echo set_value('rte') == "no" ? "checked" : "";
                            ?>  ><?php echo $this->lang->line('no'); ?></label>
                                </div>
                    <span class="text-danger"><?php echo form_error('rte'); ?></span>
                </div>
                <?php } ?>
        </div>
        <div class="row">
            <?php  if ($this->customlib->getfieldstatus('previous_school_details')) { ?>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo $this->lang->line('previous_school_details'); ?></label>
                        <textarea class="form-control" rows="1" placeholder="" name="previous_school" autocomplete="off"></textarea>
                        <span class="text-danger"><?php echo form_error('previous_school'); ?></span>
                    </div>
                </div>
                <?php } ?>
                <?php  if ($this->customlib->getfieldstatus('student_note')) { ?>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><?php echo $this->lang->line('note'); ?></label>
                            <textarea class="form-control" rows="1" placeholder="" name="note" autocomplete="off"></textarea>
                            <span class="text-danger"><?php echo form_error('note'); ?></span>
                        </div>
                    </div>
                <?php } ?>
            </div>
         </div>   
        <?php } ?>
        <div class="printcontent">
          <div class="row">
            <h4 class="pagetitleh2"><?php echo $this->lang->line('upload') . " " . $this->lang->line('documents'); ?></h4>
        	<div class="col-md-6">
              <div class="form-group">
                <label> <?php echo $this->lang->line('documents'); ?> </label> (<small><?php echo $this->lang->line('if_upload_multiple_document'); ?></small>)
                <input id="document" name="document"  type="file" class="form-control filestyle" value="<?php echo set_value('document'); ?>" />
                <span class="text-danger"><?php echo form_error('document'); ?></span>
            </div>
          </div>
         </div> 
        </div>
            <div class="row">
                
                   <?php if($is_captcha){ ?>
                    <div class="col-lg-4 col-md-5 col-sm-7">
                        <div class="d-flex align-items-center">   
                            <span id="captcha_image"><?php echo $this->captchalib->generate_captcha()['image']; ?></span>
                            <span class="fa fa-refresh capture-icon" title='Refresh Catpcha' onclick="refreshCaptcha()"></span>
                            <input type="text" name="captcha" placeholder="<?php echo $this->lang->line('captcha'); ?>" class=" form-control width-auto" id="captcha"  autocomplete="off"> 
                        </div> 
                    </div>   
                    <?php } ?>
                 
                <div class="col-lg-2 col-md-2 col-sm-5">
                    <div class="form-group <?php if($is_captcha){ echo 'btnMD'; } ?> ">   
                       <button type="submit" class="onlineformbtn mt10"><?php echo $this->lang->line('submit');  ?></button>
                    </div>
                </div>   
                <div class="col-md-7">
                    <span class="text-danger"><?php echo form_error('captcha'); ?></span>
                </div> 
            </div> 
    </div><!--./row-->    
</form>
<div id="myModal" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header modal-header-small">
        <button type="button" class="close closebtnmodal" data-dismiss="modal">&times;</button>
        <h4 ><?php echo $this->lang->line('check_your_form_status') ?></h4> 
      </div>
       <form action="<?php echo base_url().'welcome/checkadmissionstatus' ?>" method="post" class="onlineform" id="checkstatusform">
          <div class="modal-body">
            <div class="form-group">
            <label><?php echo $this->lang->line('enter_your_reference_number'); ?></label><small class="req"> *</small> 
               <input type="text" class="form-control" name="refno" id="refno" autocomplete="off">
                 <span class="text-danger" id="error_refno"></span>
            </div>
             <div class="form-group mb10">
              <label><?php echo $this->lang->line('select_your_date_of_birth'); ?></label><small class="req"> *</small> 
               <input type="text"  class="form-control date2"  name="student_dob" id="student_dob" autocomplete="off" readonly="">
                <span class="text-danger" id="error_dob"></span>
                
            </div>
             <span class="text-danger" id="invaliderror"></span>
          </div>
          <div class="modal-footer">
          <button type="button" class="modalclosebtn btn  mdbtn" data-dismiss="modal"><?php echo $this->lang->line('close');  ?></button>
            <button type="submit" class="onlineformbtn mdbtn" ><?php echo $this->lang->line('submit');  ?></button>            
          </div>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript">
var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy',]) ?>';
var datetime_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'DD', 'm' => 'MM', 'M' => 'MMM', 'Y' => 'YYYY']) ?>';

    $(document).ready(function () {
console.log(datetime_format);
        var class_id = $('#class_id').val();
        var section_id = '<?php echo set_value('section_id', 0) ?>';

        getSectionByClass(class_id, section_id);

        $(document).on('change', '#class_id', function (e) {
            $('#section_id').html("");
            var class_id = $(this).val();
            getSectionByClass(class_id, 0);
        });

        $('.date2').datepicker({
            autoclose: true,
             format: date_format,
            todayHighlight: true
        });
        $('.date').datepicker({
            autoclose: true,
             format: date_format,
            todayHighlight: true
        });
        $('.datetime').datetimepicker({         
         format: datetime_format + ' hh:mm a',
          locale:'en'
        });

        function getSectionByClass(class_id, section_id) {

            if (class_id !== "") {
                $('#section_id').html("");

                var div_data = '';
                var url = "";

                $.ajax({
                    type: "POST",
                    url: base_url + "welcome/getSections",
                    data: {'class_id': class_id},
                    dataType: "json",
                    beforeSend: function () {
                        $('#section_id').addClass('dropdownloading');
                    },
                    success: function (data) {
                        $.each(data, function (i, obj)
                        {
                            var sel = "";
                            if (section_id === obj.section_id) {
                                sel = "selected";
                            }
                            div_data += "<option value=" + obj.id + " " + sel + ">" + obj.section + "</option>";
                        });
                        $('#section_id').append(div_data);
                    },
                    complete: function () {
                        $('#section_id').removeClass('dropdownloading');
                    }
                });
            }
        }
    });
    function auto_fill_guardian_address() {
        if ($("#autofill_current_address").is(':checked'))
        {
            $('#current_address').val($('#guardian_address').val());
        }
    }
    function auto_fill_address() {
        if ($("#autofill_address").is(':checked'))
        {
            $('#permanent_address').val($('#current_address').val());
        }
    }
    $('input:radio[name="guardian_is"]').change(
            function () {
                if ($(this).is(':checked')) {
                    var value = $(this).val();
                    if (value === "father") {
                        var father_relation = "<?php echo $this->lang->line('father'); ?>";
                        $('#guardian_name').val($('#father_name').val());
                        $('#guardian_phone').val($('#father_phone').val());
                        $('#guardian_occupation').val($('#father_occupation').val());
                        $('#guardian_relation').val(father_relation);
                    } else if (value === "mother") {
                        var mother_relation = "<?php echo $this->lang->line('mother'); ?>";
                        $('#guardian_name').val($('#mother_name').val());
                        $('#guardian_phone').val($('#mother_phone').val());
                        $('#guardian_occupation').val($('#mother_occupation').val());
                        $('#guardian_relation').val(mother_relation);
                    } else {
                        $('#guardian_name').val("");
                        $('#guardian_phone').val("");
                        $('#guardian_occupation').val("");
                        $('#guardian_relation').val("");
                    }
                }
            });

</script>
<script type="text/javascript">
    function refreshCaptcha(){
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('site/refreshCaptcha'); ?>",
            data: {},
            success: function(captcha){
                $("#captcha_image").html(captcha);
            }
        });
    }    
</script>
<script type="text/javascript">
$(document).ready(function(){ 
$(document).on('submit','#checkstatusform',function(e){
   e.preventDefault(); // avoid to execute the actual submit of the form.
    var form = $(this);
    var url = form.attr('action');
    var form_data = form.serializeArray();
    
    $.ajax({
           url: url,
           type: "POST",
           dataType:'JSON',
           data: form_data, // serializes the form's elements.
              beforeSend: function () {
               
               },
              success: function(response) { // your success handler
                if(response.status==0){
                    
                    $.each(response.error, function(key, value) {
                     
                    $('#error_' + key).html(value);
                    });
                }else if(response.status==2){
                 
                    $('#error_dob' ).html("");
                    $('#error_refno' ).html("");
                    $('#invaliderror').html(response.error);
                } else{
                    
                    var refno =response.refno ;
                    window.location.href="<?php echo base_url().'welcome/online_admission_review/' ?>"+refno ;
                }
              },
             error: function() { // your error handler
             
             },
             complete: function() {
           
             }  
         });
});
});
</script>
<script>
    function openmodal(){
      $('#error_dob' ).html("");
      $('#error_refno' ).html("");
      $('#invaliderror').html("");
      $('#student_dob').val("");
      $('#student_dob').html("");
      $('#refno' ).val("");
      $(':input').val('');

    }
     function auto_fill_guardian_address() {
        if ($("#autofill_current_address").is(':checked'))
        {
            $('#current_address').val($('#guardian_address').val());
        }
    }
    function auto_fill_address() {
        if ($("#autofill_address").is(':checked'))
        {
            $('#permanent_address').val($('#current_address').val());
        }
    }
</script>
<script>
$(function(){
    $('#myModal').modal({
         backdrop: 'static',
         keyboard: false,
         show: false
    });
});
</script>