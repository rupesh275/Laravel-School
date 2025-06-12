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
            <h3 class="entered mt0"><?php echo $this->lang->line('online') . " " . $this->lang->line('admission'); ?></h3>
        </div>
        <div class="col-md-4">
            <a href="#myModal" class="statusright modalclosebtn" onclick="openmodal();" data-toggle="modal" data-target="#myModal"><?php echo $this->lang->line('check_your_form_status') ?></a>
        </div>
    </div>
   <form id="form1" class="spaceb60 spacet40 onlineform" action="<?php echo current_url() ?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                        <div class="box-body row">
                            <div class="col-sm-3 col-md-3">
                                <div class="form-group">  
                                    <label><?php echo $this->lang->line('source'); ?></label>
                                    <select  id="source" name="source" class="form-control" >
                                        <option value=""><?php echo $this->lang->line('select') ?></option>
                                        <?php foreach ($sourcelist as $key => $value) { ?>
                                            <option <?php
                                            if ($value["source"] == $source_select) {
                                                echo "selected";
                                            }
                                            ?> value="<?php echo $value["source"] ?>"><?php echo $value["source"] ?></option>
                                            <?php } ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('source'); ?></span>
                                </div>  
                            </div>
                             <div class="col-sm-3 col-md-3">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('enquiry')." ".$this->lang->line('from'); ?> <?php echo $this->lang->line('date'); ?></label>                                 
                                        
                                        <input type="text" autocomplete="off" name="from_date" class="form-control  date"  value="<?php  echo set_value('from_date') ?>">
                                    </div><span class="text-danger"><?php echo form_error('from_date'); ?></span>              
                            </div> 
                            <div class="col-sm-3 col-md-3">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('enquiry')." ".$this->lang->line('to'); ?> <?php echo $this->lang->line('date'); ?></label>                                        
                                        <input type="text" autocomplete="off" name="to_date" class="form-control  date"  value="<?php  echo set_value('to_date') ?>">
                                    </div><span class="text-danger"><?php echo form_error('to_date'); ?></span>                            
                            </div> 
                            <div class="col-sm-3 col-md-3">
                                <div class="form-group">  
                                    <label><?php echo $this->lang->line('status'); ?></label>
                                    <select  id="status" name="status" class="form-control" >
                                        <option value=""><?php echo $this->lang->line('select') ?></option>
                                        <option value="all" <?php
                                        if ($status == "all") {
                                            echo "selected";
                                        }
                                        ?>><?php echo $this->lang->line('all') ?></option>
                                                <?php foreach ($enquiry_status as $enkey => $envalue) {
                                                    ?>
                                            <option <?php
                                            if ($enkey == $status) {
                                                echo "selected";
                                            }
                                            ?> value="<?php echo $enkey ?>"><?php echo $envalue ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('status'); ?></span>
                                </div>  
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button type="submit" name="search" value="search_filter" class="btn btn-primary btn-sm checkbox-toggle pull-right"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
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