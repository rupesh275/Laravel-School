<div class="content-wrapper">   
    <section class="content-header">
        <h1>
            <i class="fa fa-mortar-board"></i> <?php echo $this->lang->line('academics'); ?> <small><?php echo $this->lang->line('student_fees1'); ?></small>        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <?php
            if ($this->rbac->hasPrivilege('exam_category', 'can_add') || $this->rbac->hasPrivilege('exam_category', 'can_edit')) {
                ?>
                <div class="col-md-4">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $this->lang->line('edit'); ?> Exam Category Master</h3>
                        </div>
                        <form action="<?php echo site_url("admin/examgroup/edit_examcategory/" . $id) ?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8">
                            <div class="box-body">
                                <?php if ($this->session->flashdata('msg')) { ?>
                                    <?php echo $this->session->flashdata('msg') ?>
                                <?php } ?>   
                                <?php echo $this->customlib->getCSRF(); ?>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Exam Type</label><small class="req"> *</small>
                                    <input autofocus="" id="exam_category" name="exam_category" placeholder="" type="text" class="form-control"  value="<?php echo set_value('exam_category', $examcategory_array['name']); ?>" />
                                    <span class="text-danger"><?php echo form_error('exam_category'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Exam Group Type </label><small class="req"> *</small>
                                    <select name="group_type_id" class="form-control" id="group_type_id">
                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    <?php
                                        foreach ($examtype_result as  $examType) {
                                            ?>
                                            <option value="<?php echo $examType['id']; ?>" <?php echo $examcategory_array['group_type_id'] == $examType['id'] ? 'selected' :""; ?>><?php echo $examType['name']; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('group_type_id'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Status </label><small class="req"> *</small>
                                    <select name="status" class="form-control" id="status">
                                        <option <?php echo $examcategory_array['status'] ==1 ? 'selected' :""; ?> value="1">Active</option>
                                        <option <?php echo $examcategory_array['status'] ==0 ? 'selected' :""; ?> value="0">Inactive</option>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('status'); ?></span>
                                </div>
                            </div>
                            <div class="box-footer">
                                <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                            </div>
                        </form>
                    </div>              
                </div>
            <?php } ?>
            <div class="col-md-<?php
            if ($this->rbac->hasPrivilege('exam_category', 'can_add') || $this->rbac->hasPrivilege('exam_category', 'can_edit')) {
                echo "8";
            } else {
                echo "12";
            }
            ?>">             
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix">Exam Category Master List</h3>
                    </div>
                    <div class="box-body ">
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label">Exam Category Master</div>
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th>Exam Category Master</th>
                                        <th class="text-right"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>                                   

                                    <?php
                                    $count = 1;
                                    foreach ($examcategory_result as $examcategory) {
                                        ?>
                                        <tr>
                                            <td class="mailbox-name"> <?php echo $examcategory['name'] ?></td>
                                            <td class="mailbox-date pull-right">
                                                <?php
                                                if ($this->rbac->hasPrivilege('exam_category', 'can_edit')) {
                                                    ?>
                                                    <a data-placement="left" href="<?php echo base_url(); ?>admin/examgroup/edit_examcategory/<?php echo $examcategory['id'] ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                    <?php
                                                }
                                                if ($this->rbac->hasPrivilege('exam_category', 'can_delete')) {
                                                    ?>
                                                    <a data-placement="left" href="<?php echo base_url(); ?>admin/examgroup/delete_examcategory/<?php echo $examcategory['id'] ?>"class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('Exam category will  delete this action is irreversible --r');">
                                                        <i class="fa fa-remove"></i>
                                                    </a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    $count++;
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