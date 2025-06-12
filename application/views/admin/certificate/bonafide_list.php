<style type="text/css">
    @media print {

        .no-print,
        .no-print * {
            display: none !important;
        }
    }
</style>
<div class="content-wrapper" style="min-height: 946px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-calendar-check-o"></i> <?php echo  $this->lang->line('list'); ?> <small> </small>
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">

                <div class="box box-info" id="attendencelist">
                    <div class="box-header with-border">
                        <div class="row">
                            <div class="box-tools pull-right">
                                <small class="pull-right"><a href="<?php echo base_url('admin/generatecertificate/generate_bonafide'); ?>" class="btn btn-primary btn-sm checkbox-toggle pull-right " data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Processing"><?php echo "Add"; ?></a></small>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <h3 class="box-title"><i class="fa fa-users"></i><?php echo $this->lang->line('list'); ?></h3>
                            </div>
                            <div class="col-md-8 col-sm-8">
                                <div class="lateday">

                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="box-body table-responsive">


                        <div class="mailbox-controls">
                            <div class="pull-right">
                            </div>
                        </div>
                        <div class="download_label"><?php echo "List"; ?></div>
                        <table class="table table-striped table-bordered table-hover example xyz">
                            <thead>
                                <tr>
                                    <th><?php echo "Sr.no"; ?></th>
                                    <th><?php echo $this->lang->line('roll_no'); ?></th>
                                    <th><?php echo $this->lang->line('admission_no'); ?></th>
                                    <th><?php echo $this->lang->line('student') . " " . $this->lang->line('name'); ?></th>
                                    <th><?php echo $this->lang->line('class'); ?></th>

                                    <th><?php echo "Date Of Birth"; ?></th>
                                    <th><?php echo "Gender"; ?></th>
                                    <th class="pull-right"><?php echo $this->lang->line('action'); ?></th>


                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                $i = 1;
                                foreach ($resultlist as $value) { ?>
                                    <tr>
                                        <td><?php echo $value['srno']; ?></td>
                                        <td><?php echo $value['roll_no']; ?>
                                        <td><?php echo $value['admission_no']; ?>
                                        <td><a href="<?php echo base_url(); ?>student/view/<?php echo $value['student_id']; ?>"><?php echo $value['firstname'] . " " . $value['lastname'];
                                                                                                                                ?></a></td>
                                        <td><?php echo $value['class'] . " " . $value['section']; ?>


                                        </td>

                                        <td><?php echo date('d-m-Y', strtotime($value['dob'])); ?></td>
                                        <td><?php echo $value['gender']; ?></td>

                                        <td class="pull-right">
                                            <button class="btn btn-default btn-xs printInv" data-student_info_id="<?php //echo $value['id'];
                                                                                                                    ?>" data-student_id="<?php echo $value['id']; ?>" data-toggle="tooltip" title="<?php echo $this->lang->line('print'); ?>"><i class="fa fa-print"></i></button>
                                            <?php
                                            if ($this->rbac->hasPrivilege('generate_bonafide', 'can_edit')) { ?>
                                                <!-- <a style="vertical-align: middle" class="label label-info btn-xs btn" href="<?php //echo base_url(); 
                                                                                                                                    ?>admin/certificate/tc_register/<?php //echo $value['id'] 
                                                                                                                                                                                            ?>"><?php echo $this->lang->line('edit'); ?></a> -->
                                                <?php } else { ?><?php }
                                                                if ($this->rbac->hasPrivilege('generate_bonafide', 'can_delete')) { ?>
                                                <a class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm'); ?>');" href="<?php echo base_url(); ?>admin/generatecertificate/deleteBonafide/<?php echo $value['id'] ?>"><i class="fa fa-remove"></i></a>
                                            <?php
                                                                }
                                            ?>

                                        </td>
                                    </tr>
                                <?php $i++;
                                } ?>

                            </tbody>
                        </table>

                    </div>
                </div>

    </section>
</div>


<script>
    var base_url = '<?php echo base_url() ?>';

    function Popup(data) {

        var frame1 = $('<iframe />');
        frame1[0].name = "frame1";

        $("body").append(frame1);
        var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
        frameDoc.document.open();
        //Create a new HTML document.
        frameDoc.document.write('<html>');
        frameDoc.document.write('<head>');
        frameDoc.document.write('<title></title>');
        // frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/idcard.css">');

        frameDoc.document.write('</head>');
        frameDoc.document.write('<body>');
        frameDoc.document.write(data);
        frameDoc.document.write('</body>');
        frameDoc.document.write('</html>');
        frameDoc.document.close();
        setTimeout(function() {
            window.frames["frame1"].focus();
            window.frames["frame1"].print();
            frame1.remove();
        }, 500);


        return true;
    }
</script>





<script type="text/javascript">
    $(document).on('click', '.printInv', function() {
        //var student_id = $(this).data('student_id');
        var student_id = $(this).data('student_id');
        $.ajax({
            url: '<?php echo site_url("admin/generatecertificate/print_bonafide_certificate") ?>',
            type: 'post',
            data: {
                'student_id': student_id,
            },
            success: function(response) {
                Popup(response);
            }
        });
    });
</script>