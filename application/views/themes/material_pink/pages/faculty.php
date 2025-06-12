<script src="<?php echo base_url('backend/dist/js/moment.min.js'); ?>"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>backend/datepicker/css/bootstrap-datetimepicker.css">
<script src="<?php echo base_url(); ?>backend/datepicker/js/bootstrap-datetimepicker.js"></script>
        <div class="container">
            <div class="row">
                <img  src="<?php echo base_url(); ?>uploads/common/faculty.jfif" style="display: block; margin-left: auto; margin-right: auto; width: 100%;" />
                <div class="col-md-8 col-sm-12 col-md-offset-2 text-center pb30">
                    <br>
                    <br>
                    <br>
                    <p style="text-align: justify;">The school has a very dynamic and talented faculty, highly qualified and experienced. We believe that every teacher is also a learner who needs to continually adapt to the dynamic needs of the twenty-first century. Hence our qualified faculty makes it a point to attend workshops conducted in all other CBSE schools. This is of course apart from regular in-house training sessions conducted in the school. These sessions provides opportunities for our teachers to share experiences, teaching practices and methodologies from differing perspectives.</p>
                    <div class="divider">&nbsp;</div>
                </div> 
            </div>
        </div>

    <section class="facilitieswrapper-co">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-sm-12 col-md-offset-2 text-center pb30">
                    <h2 class="head-title">Principal</h2>
                    <p></p>
                    <div class="divider">&nbsp;</div>
                </div>     
                <div class="row">
                        <div class="col-md-4 col-sm-4">
                        </div>
                        <div class="col-md-4 col-sm-4">
                            <div class="courses-box" style="padding:50px;"  >
                                <div class="courses-box-img" ><img
                                        src="<?php echo base_url(); ?>uploads/staff_images/<?php echo $faculty_princ[0]['image']; ?>" /></div>
                                <!--./courses-box-img-->
                                <div class="course-inner" style="height:100px;text-align: center;"><a class="course-subject" href="#"></a>
                                    <h4><?php echo $faculty_princ[0]['name']; ?></h4>
                                    <h5><?php echo $faculty_princ[0]['qualification']; ?></h5>
                                    <p><?php echo $faculty_princ[0]['note']; ?></p>
                                </div>
                            </div>
                            <!--./courses-box-->
                        </div>   
                        <div class="col-md-4 col-sm-4">
                        </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8 col-sm-12 col-md-offset-2 text-center pb30">
                    <h2 class="head-title">Secondary Section</h2>
                    <p></p>
                    <div class="divider">&nbsp;</div>
                </div>  
                <div class="row">
                             <?php
                              foreach ($faculty_secondary as $key => $staffs) {
                              ?>                    
                        <div class="col-md-3 col-sm-3">
                            <div class="courses-box" style="padding:50px;"  >
                                <div class="courses-box-img" ><img
                                        src="<?php echo base_url(); ?>uploads/staff_images/<?php echo $staffs['image']; ?>" style="border-radius: 10px;height:200px;" /></div>
                                <!--./courses-box-img-->
                                <div class="course-inner" style="height:100px;text-align: center;"><a class="course-subject" href="#"></a>
                                    <h4><?php echo $staffs['name']; ?></h4>
                                    <h5><?php echo $staffs['qualification']; ?></h5>
                                    <?php echo $staffs['note']; ?>
                                </div>
                                </div>
                        </div>  
                        <?php } ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8 col-sm-12 col-md-offset-2 text-center pb30">
                    <h2 class="head-title">Primary Section</h2>
                    <p></p>
                    <div class="divider">&nbsp;</div>
                </div>  
                <div class="row">
                             <?php
                              foreach ($faculty_primary as $key => $staffs) {
                              ?>                    
                        <div class="col-md-3 col-sm-3">
                            <div class="courses-box" style="padding:50px;"  >
                                <div class="courses-box-img" ><img
                                        src="<?php echo base_url(); ?>uploads/staff_images/<?php echo $staffs['image']; ?>" style="border-radius: 10px;height:200px;" /></div>
                                <!--./courses-box-img-->
                                <div class="course-inner" style="height:100px;text-align: center;"><a class="course-subject" href="#"></a>
                                    <h4><?php echo $staffs['name']; ?></h4>
                                    <h5><?php echo $staffs['qualification']; ?></h5>
                                    <?php echo $staffs['note']; ?></div>
                                </div>
                        </div>  
                        <?php } ?>
                </div>
            </div>            
            <div class="row">
                <div class="col-md-8 col-sm-12 col-md-offset-2 text-center pb30">
                    <h2 class="head-title">Pre-Primary Section</h2>
                    <p></p>
                    <div class="divider">&nbsp;</div>
                </div>  
                <div class="row">
                             <?php
                              foreach ($faculty_preprimary as $key => $staffs) {
                              ?>                    
                        <div class="col-md-3 col-sm-3">
                            <div class="courses-box" style="padding:50px;"  >
                                <div class="courses-box-img" ><img
                                        src="<?php echo base_url(); ?>uploads/staff_images/<?php echo $staffs['image']; ?>" style="border-radius: 10px;height:200px;" /></div>
                                <!--./courses-box-img-->
                                <div class="course-inner" style="height:100px;text-align: center;"><a class="course-subject" href="#"></a>
                                    <h4><?php echo $staffs['name']; ?></h4>
                                    <h5><?php echo $staffs['qualification']; ?></h5>
                                    <?php echo $staffs['note']; ?></div>
                                </div>
                        </div>  
                        <?php } ?>
                </div>
            </div>            
            <div class="row">
                <div class="col-md-8 col-sm-12 col-md-offset-2 text-center pb30">
                    <h2 class="head-title">Administrative Section</h2>
                    <p></p>
                    <div class="divider">&nbsp;</div>
                </div>  
                <div class="row">
                             <?php
                              foreach ($faculty_admin as $key => $staffs) {
                              ?>                    
                        <div class="col-md-3 col-sm-3">
                            <div class="courses-box" style="padding:50px;"  >
                                <div class="courses-box-img" ><img
                                        src="<?php echo base_url(); ?>uploads/staff_images/<?php echo $staffs['image']; ?>" style="border-radius: 10px;" /></div>
                                <!--./courses-box-img-->
                                <div class="course-inner" style="height:100px;text-align: center;"><a class="course-subject" href="#"></a>
                                    <h4><?php echo $staffs['name']; ?></h4>
                                    <h5><?php echo $staffs['qualification']; ?></h5>
                                    <?php echo $staffs['note']; ?></div>
                                </div>
                        </div>  
                        <?php } ?>
                </div>
            </div>            
        </div>
        <!--./container-->
    </section>
    
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