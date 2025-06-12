<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#424242" />
        <title>Parent Registration </title>
        <link href="<?php echo base_url(); ?>uploads/school_content/admin_small_logo/<?php $this->setting_model->getAdminsmalllogo();?>" rel="shortcut icon" type="image/x-icon">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,100,300,500">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/usertemplate/assets/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/usertemplate/assets/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/usertemplate/assets/css/form-elements.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/usertemplate/assets/css/style.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/usertemplate/assets/css/jquery.mCustomScrollbar.min.css">
        <style type="text/css">
            body{background:linear-gradient(to right,#676767 0,#dadada 100%);}
            /*.loginbg {background: #455a64;}*/
            .top-content{position: relative;}
            .mCSB_scrollTools .mCSB_dragger .mCSB_dragger_bar {
                background: rgb(53, 170, 71);}
            .bgoffsetbgno{background: transparent; border-right:0 !important; box-shadow: 0px 0px 12px rgba(0, 0, 0, 0.29); border-radius: 4px;}

            .loginradius{border-radius: 4px;}
        </style>
    </head>
    <body>
        <!-- Top content -->
        <div class="top-content">

            <div class="inner-bg">

                <div class="container">
                    <div class="row">
                        <?php
$empty_notice = 0;
$offset       = "";
$bgoffsetbg   = "bgoffsetbg";
$bgoffsetbgno = "";
if (empty($notice)) {
    $empty_notice = 1;
    $offset       = "col-md-offset-4";
    $bgoffsetbg   = "";
    $bgoffsetbgno = "bgoffsetbgno";
}
?>
                        <div class="<?php echo $bgoffsetbg; ?>">

                            <div class="col-lg-4 col-md-4 col-sm-12 nopadding <?php echo $bgoffsetbgno; ?> <?php echo $offset; ?>">
                                <div class="loginbg loginradius login390">
                                    <div class="form-top">
                                        <div class="form-top-left logowidth">
                                            <img src="<?php echo base_url(); ?>uploads/school_content/admin_logo/<?php $this->setting_model->getAdminlogo();?>" />
                                        </div>
                                    </div>
                                    <div class="form-bottom">
                                        <h3 class="font-white"><?php  echo "Parent Login"; ?></h3>
                                        <span class="text-danger" id="error_msg"></span>
                                        <?php
if (isset($error_message)) {
    echo "<div class='alert alert-danger'>" . $error_message . "</div>";
}
?>
                                        <?php
if ($this->session->flashdata('message')) {
    echo "<div class='alert alert-success'>" . $this->session->flashdata('message') . "</div>";
}
;
?>
                                        
                                            <?php echo $this->customlib->getCSRF(); ?>
                                            <div class="form-group has-feedback">
                                                <label class="sr-only" for="form-mobileno">
                                                <?php echo "Enter your mobile no "; ?></label>
                                                <input type="text" name="mobileno" value="<?php echo set_value("username"); ?>" placeholder="<?php echo "Your mobile no"; ?>" class="form-username form-control" id="mobileno">
                                                <span class="fa fa-envelope form-control-feedback"></span>
                                                <span class="text-danger"><?php echo form_error('mobileno'); ?></span>
                                            </div>
                                            <?php /* ?>
                                            <div class="form-group has-feedback">
                                                <label class="sr-only" for="form-grno">
                                                <?php echo "Enter Student GRNO "; ?></label>
                                                <input type="text" name="grno" value="<?php echo set_value("grno"); ?>" placeholder="<?php echo "Your GRNO"; ?>" class="form-grno form-control" id="grno">
                                                <span class="fa fa-envelope form-control-feedback"></span>
                                                <span class="text-danger"><?php echo form_error('grno'); ?></span>
                                            </div>
                                            <?php */ ?>
                                            <div class="form-group has-feedback">
                                                <label class="sr-only" for="form-dob">
                                                <?php echo "Enter Student DOB "; ?></label>
                                                <input type="date" name="dob" value="<?php echo set_value("dob"); ?>" class="form-dob form-control" id="dob">
                                                <span class="fa fa-envelope form-control-feedback"></span>
                                                <span class="text-danger"><?php echo form_error('dob'); ?></span>
                                                <br>
                                                <br>
                                                <button id="btn_check" type="button" class="btn"> <?php echo "Check"; ?></button>
                                            </div>
                                            <?php /* ?>
                                            <div class="form-group has-feedback">
                                                <input type="password" name="password" value="<?php echo set_value("password"); ?>" placeholder="<?php echo $this->lang->line('password'); ?>" class="form-password form-control" id="password">
                                                <span class="fa fa-lock form-control-feedback"></span>
                                                <span class="text-danger"><?php echo form_error('password'); ?></span>
                                            </div>

                                            <?php  if ($is_captcha) {?>
                                            <div class="form-group has-feedback row">
                                                <div class='col-lg-7 col-md-12 col-sm-6'>
                                                    <span id="captcha_image"><?php echo $captcha_image; ?></span>
                                                    <span class="fa fa-refresh catpcha" title='Refresh Catpcha' onclick="refreshCaptcha()"></span>
                                                </div>
                                                <div class='col-lg-5 col-md-12 col-sm-6'>
                                                    <input type="text" name="captcha" placeholder="<?php echo $this->lang->line('captcha'); ?>" autocomplete="off" class=" form-control" id="captcha">
                                                    <span class="text-danger"><?php echo form_error('captcha'); ?></span>
                                                </div>
                                            </div>
                                            <?php } */ ?>
                                            <form method="POST" action = "<?php echo base_url('site\reset_password'); ?>" >
                                            <div class="form-group has-feedback" id = "btnsubmit">
                                                <p>Please Check you child name ..</p>
                                                <p id ="student"></p>
                                                <input type="hidden" id="hashk" name = "hashk" value = "">
                                                <button type="submit" class="btn" > <?php echo "Submit"; ?></button>
                                            </div>
                                            </form>
                                        
                                

                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
        <script src="<?php echo base_url(); ?>backend/usertemplate/assets/js/jquery-1.11.1.min.js"></script>
        <script src="<?php echo base_url(); ?>backend/usertemplate/assets/bootstrap/js/bootstrap.min.js"></script>
        <script src="<?php echo base_url(); ?>backend/usertemplate/assets/js/jquery.backstretch.min.js"></script>
        <script src="<?php echo base_url(); ?>backend/usertemplate/assets/js/jquery.mCustomScrollbar.min.js"></script>
        <script src="<?php echo base_url(); ?>backend/usertemplate/assets/js/jquery.mousewheel.min.js"></script>
    </body>
</html>
<script type="text/javascript">
    $(document).ready(function () {
        //$('input[type="submit"]').attr('visibility','hidden');
        $('#btnsubmit').attr('style','visibility:hidden');
        $('#student').html('');
        $('.login-form input[type="text"], .login-form input[type="password"], .login-form textarea').on('focus', function () {
            $(this).removeClass('input-error');
        });
        $('.login-form').on('submit', function (e) {
            $(this).find('input[type="text"], input[type="password"], textarea').each(function () {
                if ($(this).val() == "") {
                    e.preventDefault();
                    $(this).addClass('input-error');
                } else {
                    $(this).removeClass('input-error');
                }
            });
        });
        $('#btn_check').on('click',function(e) {
            mobileno=$('#mobileno').val();
            grno=$('#grno').val();
            dob=$('#dob').val();
            if (mobileno=="")
            {
                alert('Please enter the mobile no .');
                $('#mobileno').focus();
                return false;
            }
            else if (grno=="")
            {
                alert('Please enter the GRNo.');
                $('#grno').focus();
                return false;
            }
            else if (dob=="")
            {
                alert('Please enter the date of birth of the student.');
                $('#dob').focus();
                return false;
            }
            $.ajax({
            type: "POST",
            url: "<?php echo base_url('site/checkdata_student'); ?>",
            data: {mobileno:mobileno,grno:grno,dob:dob},
            dataType: 'JSON',            
            success: function(data){
                if(data.success=='1')
                {
                    if(data.name!= '')
                    {
                    $('#student').html(data.name);
                    $('#hashk').val(data.hashkey);
                    $('#btnsubmit').attr('style','visibility:visible');
                    }
                    else
                    {
                        $('#error_msg').html(data.message);
                    }
                }
                else if(data.error=='1')
                {
                    $('#error_msg').html(data.message);
                }
            }
            });            
        });


    
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