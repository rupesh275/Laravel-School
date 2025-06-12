<style type="text/css">
    @media print
    {
        .no-print, .no-print *
        {
            display: none !important;
        }
    }
</style>
<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-bus"></i> <?php echo $this->lang->line('transport'); ?></h1>
    </section>
    <section class="content">
        <div class="row">
            <?php if ($this->rbac->hasPrivilege('driver', 'can_add')) { ?>
                <div class="col-md-4">
                    <div class="box box-primary" >
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $this->lang->line('add_driver'); ?></h3>
                        </div>
                        <form id="form1" action="<?php echo site_url('admin/driver/index') ?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8">

                            <div class="box-body">
                                <?php if ($this->session->flashdata('msg')) { ?>
                                    <?php echo $this->session->flashdata('msg');  unset($_SESSION['msg']); ?>
                                <?php } ?>
                                <?php
                                if (isset($error_message)) {
                                    echo "<div class='alert alert-danger'>" . $error_message . "</div>";
                                }
                                ?>      
                                <?php echo $this->customlib->getCSRF(); ?>                     
                                <div class="form-group">
                                    <label for="driver_name"><?php echo $this->lang->line('driver_name'); ?></label><small class="req"> *</small>
                                    <input autofocus="" id="driver_name" name="driver_name" placeholder="" type="text" class="form-control"  value="<?php echo set_value('driver_name',isset($update) ? $update['driver_name'] : ''); ?>" />
                                    <span class="text-danger"><?php echo form_error('driver_name'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="driver_license_no"><?php echo $this->lang->line('driver_license_no'); ?> *</label>
                                    <input id="driver_license_no" name="driver_license_no" placeholder="" type="text" class="form-control"  value="<?php echo set_value('driver_license_no',isset($update) ? $update['driver_license_no'] : ''); ?>" />
                                    <span class="text-danger"><?php echo form_error('driver_license_no'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="driver_mobileno"><?php echo $this->lang->line('driver_mobileno'); ?> *</label>
                                    <input id="driver_mobileno" name="driver_mobileno" placeholder="" type="text" class="form-control"  value="<?php echo set_value('driver_mobileno',isset($update) ? $update['driver_mobileno'] : '');?>" />
                                    <span class="text-danger"><?php echo form_error('driver_mobileno'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="driver_address"><?php echo $this->lang->line('driver_address'); ?> *</label>
                                    <textarea id="driver_address" name="driver_address" rows="3" class="form-control"><?php echo set_value('driver_address',isset($update) ? $update['driver_address'] : '');?></textarea>
                                    <span class="text-danger"><?php echo form_error('driver_address'); ?></span>
                                </div>
                            </div>
                            <div class="box-footer">
                                <input type="hidden" name="id" value="<?php echo isset($update) ? $update['id'] : ''?>">
                                <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                            </div>
                        </form>
                    </div>
                </div>     
            <?php } ?>    
            <div class="col-md-<?php
            if ($this->rbac->hasPrivilege('driver', 'can_add')) {
                echo "8";
            } else {
                echo "12";
            }
            ?>">
                <div class="box box-primary" id="driver">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('driver_list'); ?></h3>
                    </div>
                    <div class="box-body">
                        <div class="mailbox-controls">                         
                            <div class="pull-right">
                            </div>
                        </div>
                        <div class="mailbox-messages table-responsive">
                            <div class="download_label"><?php echo $this->lang->line('driver_list'); ?></div>
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('driver_name'); ?></th>
                                        <th><?php echo $this->lang->line('driver_license_no'); ?></th>
                                        <th><?php echo $this->lang->line('driver_mobileno'); ?></th>
                                        <th><?php echo $this->lang->line('driver_address'); ?></th>
                                        <th class="text-right no-print"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($driverList)) {
                                        ?>

                                        <?php
                                    } else {
                                        $count = 1;
                                        foreach ($driverList as $data) {
                                            ?>
                                            <tr>
                                                <td class="mailbox-name"> <?php echo $data['driver_name']; ?></td>
                                                <td class="mailbox-name"> <?php echo $data['driver_license_no']; ?></td>      
                                                <td class="mailbox-name"> <?php echo $data['driver_mobileno']; ?></td>      
                                                <td class="mailbox-name"> <?php echo $data['driver_address']; ?></td>      
                                                <td class="mailbox-date pull-right no-print">
                                                    <?php if ($this->rbac->hasPrivilege('driver', 'can_edit')) { ?>
                                                        <a data-placement="left" href="<?php echo base_url(); ?>admin/driver/index/<?php echo $data['id'] ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                            <i class="fa fa-pencil"></i>
                                                        </a>
                                                    <?php }if ($this->rbac->hasPrivilege('driver', 'can_delete')) { ?>
                                                        <a data-placement="left" href="<?php echo base_url(); ?>admin/driver/delete/<?php echo $data['id'] ?>"class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                            <i class="fa fa-remove"></i>
                                                        </a>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <?php
                                        $count++;
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>  

        </div>
        <div class="row">           
            <div class="col-md-12">
            </div>
        </div> 
    </section>
</div>
<script type="text/javascript">

    $(document).ready(function () {
      
        $("#btnreset").click(function () {
            $("#form1")[0].reset();
        });
    });

    var base_url = '<?php echo base_url() ?>';
    function printDiv(elem) {
        Popup(jQuery(elem).html());
    }

    function Popup(data)
    {

        var frame1 = $('<iframe />');
        frame1[0].name = "frame1";
        frame1.css({"position": "absolute", "top": "-1000000px"});
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
        setTimeout(function () {
            window.frames["frame1"].focus();
            window.frames["frame1"].print();
            frame1.remove();
        }, 500);


        return true;
    }



    $(document).ready(function () {
        $('.detail_popover').popover({
            placement: 'right',
            trigger: 'hover',
            container: 'body',
            html: true,
            content: function () {
                return $(this).closest('td').find('.vehicle_detail_popover').html();
            }
        });
    });



</script>