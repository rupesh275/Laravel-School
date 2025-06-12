<!DOCTYPE html> 
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#424242" />
        <title><?php echo $setting->name;?></title>
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/bootstrap/css/bootstrap.min.css"> 
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/font-awesome.min.css"> 
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/style-main.css"> 
        <style type="text/css">
            .table2 tr.border_bottom td {
                box-shadow: none;
                border-radius: 0;
                border-bottom: 1px solid #e6e6e6;
            }
            .table2 td {
                padding-bottom: 3px;
                padding-top: 6px;
            }
            .title{
                color: #0084B4;
                font-weight: 600 !important;
                font-size: 15px !important;;
                display: inline;

            }
            .product-description {
                display: block;
                color: #999;
                overflow: hidden;
                white-space: nowrap;
                text-overflow: ellipsis;
            }
            .text-fine{
                color: #bf4f4d;
            }
        </style> 
    </head>
    <body style="background: #ededed;">
        <div class="container">
            <div class="row">
                <div class="paddtop20">
                    <div class="col-md-8 col-md-offset-2 text-center">
                        <img width = "300"  height = "300" src="<?php echo base_url('uploads/school_content/logo/' . $setting->image); ?>">
                    </div>
                    <div class="col-md-6 col-md-offset-3 mt20">
                        <div class="paymentbg">
                            <div class="invtext"><?php echo $this->lang->line('payment_details'); ?> </div>
                            <div class="padd2 paddtzero">
                                    <div>
                                            Payment Failed.......
                                    </div>
                            </div>
                        </div>
                        <div class="" style="justify-content: center;display: flex;">
                        <?php if($source=='parent') { ?>
                            <a href="<?php echo base_url('user/user/getfees')  ?>" class="btn btn-primary" id="btn2">Back</a>
                        <?php }elseif($source=='counter-main') { ?> 
                            <a href="<?php echo base_url('studentfee')  ?>" class="btn btn-primary" id="btn2">Back</a>
                        <?php } ?>
                    </div>                         
                    </div>
                                       
                </div>  
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script> 
    </body>
</html>