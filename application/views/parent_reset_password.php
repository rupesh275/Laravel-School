<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#424242" />
        <title>Password reset</title>
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
                                            <form method="POST" action = "<?php echo base_url('site\reset_password_submit'); ?>" >
                                            <?php echo $this->customlib->getCSRF(); ?>
                                            <div class="form-group has-feedback">
                                                <label class="sr-only" for="form-mobileno">
                                                <?php echo "Enter new password "; ?></label>
                                                <input type="password" name="pwd" value="<?php echo set_value("pwd"); ?>"  class="form-username form-control" id="pwd">
                                                <span class="fa fa-envelope form-control-feedback"></span>
                                                <span class="text-danger"><?php echo form_error('pwd'); ?></span>
                                            </div>
                                            <div class="form-group has-feedback">
                                                <label class="sr-only" for="form-mobileno">
                                                <?php echo "Re-enter password "; ?></label>
                                                <input type="password" name="repwd" value="<?php echo set_value("repwd"); ?>"  class="form-username form-control" id="repwd">
                                                <span class="fa fa-envelope form-control-feedback"></span>
                                                <span class="text-danger"><?php echo form_error('repwd'); ?></span>
                                            </div>
                                            
                                            <div class="form-group has-feedback" id = "btnsubmit">
                                                <input type="hidden" id="hashk" name = "hashk" value = "<?php echo $hashk; ?>">
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
            $.ajax({
            type: "POST",
            url: "<?php echo base_url('site/checkdata_student'); ?>",
            data: {mobileno:mobileno,grno:grno,dob:dob},
            dataType: 'JSON',            
            success: function(data){
                if(data.success='1')
                {
                    $('#student').html(data.name);
                    $('#hashk').html(data.hashkey);
                    $('#btnsubmit').attr('style','visibility:visible');
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