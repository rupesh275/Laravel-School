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
            <?php if ($this->rbac->hasPrivilege('assign_driver', 'can_add')) { ?>
                <div class="col-md-4">
                    <div class="box box-primary" >
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $this->lang->line('assign_driver'); ?></h3>
                        </div>
                        <form id="form1" action="<?php echo site_url('admin/driver/assign_driver') ?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8">

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
                                    <label for="driver_id"><?php echo $this->lang->line('driver_list'); ?></label><small class="req"> *</small>
                                    <select id="driver_id" name="driver_id" class="form-control">
                                        <option value=""><?php echo $this->lang->line('select').' '.$this->lang->line('driver'); ?></option>
                                        <?php foreach($driverList as $drRow) { ?>
                                        <option value="<?php echo $drRow['id']; ?>" <?php echo set_select('driver_id',$drRow['id'],isset($update) && $drRow['id'] == $update['driver_id'] ? True : false);?>><?php echo $drRow['driver_name']; ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('driver_id'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="vehicle_id"><?php echo $this->lang->line('vehicle_list'); ?></label><small class="req"> *</small>
                                    <select id="vehicle_id" name="vehicle_id" class="form-control">
                                        <option value=""><?php echo $this->lang->line('select').' '.$this->lang->line('vehicle'); ?></option>
                                        <?php foreach($vehicleList as $vehRow) { ?>
                                        <option value="<?php echo $vehRow['id']; ?>" <?php echo set_select('vehicle_id',$vehRow['id'],isset($update) && $vehRow['id'] == $update['vehicle_id'] ? True : false);?>><?php echo $vehRow['vehicle_no']; ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('vehicle_id'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="start_date"><?php echo $this->lang->line('start_date'); ?></label><small class="req"> *</small>
                                    <input id="start_date" name="start_date" placeholder="" type="text" class="form-control date"  value="<?php echo set_value('start_date',isset($update) && $update['start_date']!='' ? date('d-m-Y',strtotime($update['start_date'])) : '');?>" />
                                    <span class="text-danger"><?php echo form_error('start_date'); ?></span>
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
            if ($this->rbac->hasPrivilege('assign_driver', 'can_add')) {
                echo "8";
            } else {
                echo "12";
            }
            ?>">
                <div class="box box-primary" id="driver">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('assign_driver').' '.$this->lang->line('list'); ?></h3>
                    </div>
                    <div class="box-body">
                        <div class="mailbox-controls">                         
                            <div class="pull-right">
                            </div>
                        </div>
                        <div class="mailbox-messages table-responsive">
                            <div class="download_label"><?php echo $this->lang->line('assign_driver').' '.$this->lang->line('list'); ?></div>
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('start_date'); ?></th>
                                        <th><?php echo $this->lang->line('driver'); ?></th>
                                        <th><?php echo $this->lang->line('vehicle'); ?></th>
                                        <th class="text-right no-print"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($assDriverList)) {
                                        ?>

                                        <?php
                                    } else {
                                        $count = 1;
                                        foreach ($assDriverList as $data) {
                                            $drRow = $this->driver_model->get($data['driver_id']);
                                            $vehiRow = $this->vehicle_model->get($data['vehicle_id']);
                                            ?>
                                            <tr>
                                                <td class="mailbox-name"> <?php echo date('d-m-Y',strtotime($data['start_date'])); ?></td>
                                                <td class="mailbox-name"> <?php echo $drRow['driver_name']; ?></td>      
                                                <td class="mailbox-name"> <?php echo !empty($vehiRow->vehicle_no) ? $vehiRow->vehicle_no :""; ?></td>     
                                                <td class="mailbox-date pull-right no-print">
                                                    <?php if ($this->rbac->hasPrivilege('assign_driver', 'can_edit')) { ?>
                                                        <a data-placement="left" href="<?php echo base_url(); ?>admin/driver/assign_driver/<?php echo $data['id'] ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                            <i class="fa fa-pencil"></i>
                                                        </a>
                                                    <?php }if ($this->rbac->hasPrivilege('assign_driver', 'can_delete')) { ?>
                                                        <!-- <a data-placement="left" href="<?php echo base_url(); ?>admin/driver/assign_driver_delete/<?php echo $data['id'] ?>"class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');"> -->
                                                            <!-- <i class="fa fa-remove"></i>
                                                        </a> -->
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