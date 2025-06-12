<script src="<?php echo base_url(); ?>backend/js/sstoast.js"></script>

<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1>
            <i class="fa fa-user-plus"></i> <?php echo $this->lang->line('student_information'); ?>
        </h1>
    </section> 
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <?php
            if (($this->rbac->hasPrivilege('medical_master', 'can_add'))) {
                ?>
                <div class="col-md-4">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fa fa-users"></i> <?php echo $this->lang->line('add') . " Medical Examination Master"; ?></h3>
                        </div> 

                        <form id="form1" action="<?php echo base_url(); ?>student/medical_master"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8">
                            <div class="box-body">
                                <?php
                                if ($this->session->flashdata('msg')) {
                                    $msg = $this->session->flashdata('msg');
                                    ?>

                                    <?php echo $this->session->flashdata('msg');unset($_SESSION['msg']); ?>
                                <?php } ?>    
                                <?php echo $this->customlib->getCSRF(); ?>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="pwd"><?php echo $this->lang->line('name'); ?></label><small class="req"> *</small>
                                            <input type="text" name="name" id="name" value="<?php echo !empty($update) ? $update['name']:"";  ?>" class="form-control ">
                                            <span class="text-danger"><p><?php echo form_error('name') ?></p></span>
                                        </div>
                                    </div>
                                </div> 
                            </div>
                            <div class="box-footer">
                                <input type="hidden" name="id" value="<?php echo !empty($update) ? $update['id']:"";  ?>">
                                <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                            </div>
                        </form>
                    </div>  
                </div> 
            <?php } ?>
            <div class="col-md-<?php
            if (($this->rbac->hasPrivilege('medical_master', 'can_add'))) {
                echo "8";
            } else {
                echo "12";
            }
            ?>">             
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title"><i class="fa fa-users"></i> <?php echo  " Medical Examination Master"  . " " . $this->lang->line('list'); ?></h3>                
                    </div>
                    <div class="box-body">
                        <div class="download_label"><?php echo " Medical Master". " " . $this->lang->line('list'); ?></div>
                        <div class="mailbox-messages table-responsive">
                            <table class="table table-hover table-striped table-bordered example">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('name') ?>

                                        </th>
                                        <th class="text-right"><?php echo $this->lang->line('action'); ?>

                                        </th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($results as $value) { 
                                         $this->db->where('exammst_id', $value['id']);
                                         $q = $this->db->get('medical_result')->num_rows();
                                        ?>
                                        <tr>
                                            <input type="hidden" name="delete_id" id="delete_id<?php echo $value['id'];?>" value="<?php echo $q;?>">
                                            <td><?php echo $value['name']; ?></td>
                                            <td class="text-right"><a data-placement="left" class="btn btn-default btn-xs" href="<?php echo base_url(); ?>student/medical_master/<?php echo $value['id']; ?>" data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>"><i class="fa fa-pencil"></i></a>

                                                <!-- <a data-placement="left" onclick="return confirm('<?php echo $this->lang->line('delete_confirm'); ?>')" class="btn btn-default btn-xs" href="<?php echo base_url() ?>student/deletemed_master/<?php echo $value['id'] ?>" data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>"><i class="fa fa-remove"></i></a></td> -->
                                                <a data-placement="left"  class="btn btn-default btn-xs delete" data-id="<?php echo $value['id'] ?>" href="javascript:void(0)" data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>"><i class="fa fa-remove"></i></a></td>

                                        </tr>
                                        <?php
                                    }
                                    ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
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

    $(document).on('click', '.delete', function() {
        var id = $(this).data('id');
        var delete_id = $('#delete_id'+ id).val();
        if (delete_id != 0) {
            alert('Already Entries Available Cannot Delete');
        } else {
            confirm('<?php echo $this->lang->line('delete_confirm'); ?>')
            $.ajax({
                url: '<?php echo site_url("student/deletemed_master") ?>',
                type: 'post',
                data: {
                    'id': id,
                },
                success: function(data) {
                    // Popup(response);
                    // successMsg(data.message);
                    // setTimeout(function() {
                    location.href="<?php echo base_url('student/medical_master')?>";
                    // }, 2500);
                }
            });
        }
    });
</script>