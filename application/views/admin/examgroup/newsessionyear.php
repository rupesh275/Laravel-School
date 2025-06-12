<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1>
            <i class="fa fa-mortar-board"></i> <?php echo $this->lang->line('academics'); ?> <small><?php echo $this->lang->line('student_fees1'); ?></small>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <?php
            if ($this->rbac->hasPrivilege('newsessionyear', 'can_add')) {
            ?>
                <div class="col-md-4">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">New Session Start Date</h3>
                        </div>
                        <form action="<?php echo site_url('admin/examgroup/newsessionyear') ?>" id="employeeform" name="employeeform" method="post" accept-charset="utf-8">
                            <div class="box-body">
                                <?php if ($this->session->flashdata('msg')) { ?>
                                    <?php echo $this->session->flashdata('msg') ?>
                                <?php } ?>
                                <?php echo $this->customlib->getCSRF(); ?>
                                <div class="form-group">
                                    <?php //print_r($update);
                                    ?>
                                    <label for="exampleInputEmail1">Sch Section Id</label><small class="req"> *</small>
                                    <select name="sch_section_id" id="sch_section_id" class="form-control">
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php
                                        if (!empty($sch_section_result)) {
                                            foreach ($sch_section_result as  $sch_section) {
                                        ?>
                                                <option value="<?php echo $sch_section['id']; ?>" <?php echo set_select('sch_section_id',$sch_section['id'], isset($update['sch_section_id']) && $update['sch_section_id'] == $sch_section['id'] ? true : false ); ?>><?php echo $sch_section['sch_section']; ?></option>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('sch_section'); ?></span>
                                    <input type="hidden" name="id" value="<?php if (isset($update)) {
                                                                                echo $update['id'];
                                                                            } ?>">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Start Date</label><small class="req"> *</small>
                                    <input autofocus="" id="start_date" name="start_date" placeholder="" type="text" class="form-control date_fee" value="<?php echo set_value('start_date', isset($update['start_date']) ? date('d-m-Y',strtotime($update['start_date'])) : ""); ?>" />
                                    <span class="text-danger"><?php echo form_error('start_date'); ?></span>
                                    <input type="hidden" name="id" value="<?php if (isset($update)) {
                                                                                echo $update['id'];
                                                                            } ?>">
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
                                if ($this->rbac->hasPrivilege('newsessionyear', 'can_add')) {
                                    echo "8";
                                } else {
                                    echo "12";
                                }
                                ?>">
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix">New Session Start Date List</h3>
                    </div>
                    <div class="box-body ">
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label">Sch Section </div>
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th>Sch Section</th>
                                        <th>Start Date</th>
                                        <th class="text-right"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
                                    $count = 1;
                                    foreach ($newsession_result as $newsession) {
                                    ?>
                                        <tr>
                                            <td class="mailbox-name"> <?php echo $newsession['sch_section'] ?></td>
                                            <td class="mailbox-name"> <?php echo date('d-m-Y', strtotime($newsession['start_date'])) ?></td>
                                            <td class="mailbox-date pull-right">
                                                <?php
                                                if ($this->rbac->hasPrivilege('exam_scheme', 'can_edit')) {
                                                ?>
                                                    <a data-placement="left" href="<?php echo base_url(); ?>admin/examgroup/newsessionyear/<?php echo $newsession['id'] ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                <?php
                                                }
                                                if ($this->rbac->hasPrivilege('exam_scheme', 'can_delete')) {
                                                ?>
                                                    <a data-placement="left" href="<?php echo base_url(); ?>admin/examgroup/delete_newsessionyear/<?php echo $newsession['id'] ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm(' delete this action is irreversible --r');">
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

<script>
    $(document).ready(function() {
        $('body').on('focus', ".date_fee", function() {
            $(this).datepicker({
                format: date_format,
                autoclose: true,
                language: 'en',
                // endDate: '+0d',
                weekStart: start_week,
                todayHighlight: true
            });
        });
    });
</script>