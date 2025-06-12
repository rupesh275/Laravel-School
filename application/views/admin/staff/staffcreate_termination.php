<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-sitemap"></i> <?php echo $this->lang->line('human_resource'); ?></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">

                    <form id="form1" action="<?php echo site_url('admin/staff/submit_termination') ?>" id="employeeform" name="employeeform" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                        <div class="box-body">
                            <!-- <div class="alert alert-info">
                                Staff email is their login username, password is generated automatically and send to staff email. Superadmin can change staff password on their staff profile page.

                            </div> -->
                            <div class="tshadow mb25 bozero">
                                <!-- <div class="box-tools pull-right pt3">
                                    <a class="btn btn-sm btn-primary" href="<?php echo base_url(); ?>admin/staff/import" autocomplete="off"><i class="fa fa-plus"></i> <?php echo $this->lang->line('import') . " " . $this->lang->line('staff') ?></a> 

                                </div> -->
                                <h4 class="pagetitleh2"><?php echo "Staff Termination"; ?> </h4>



                                <div class="around10">

                                    <!-- <?php if ($this->session->flashdata('msg')) { ?>
                                        <?php echo $this->session->flashdata('msg') ?>
                                    <?php } ?> -->
                                    <?php echo $this->customlib->getCSRF(); ?>

                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo "Letter Submission  Date"; ?></label><small class="req"> *</small>
                                                <input autofocus="" id="date_of_termination" name="date_of_termination" placeholder="" type="text" class="form-control date" value="<?php echo set_value('date_of_termination') ?> <?php if (!empty($update)) {
                                                                                                                                                                                                                                        echo date('d-m-Y', strtotime($update['date_of_termination']));
                                                                                                                                                                                                                                    } else {
                                                                                                                                                                                                                                        echo date('d-m-Y');
                                                                                                                                                                                                                                    } ?>" />
                                                <span class="text-danger"><?php echo form_error('date_of_termination'); ?></span>
                                            </div>
                                        </div>

                                        <div class="col-md-3">

                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo "Type Of Termination"; ?></label>
                                                <!-- <input autofocus="" id="typeof_termination" name="typeof_termination" placeholder="" type="text" class="form-control" value="<?php echo set_value('typeof_termination') ?> <?php if (!empty($update)) {
                                                                                                                                                                                                                                    echo $update['typeof_termination'];
                                                                                                                                                                                                                                } ?>" /> -->
                                                <select name="typeof_termination" id="typeof_termination" class="form-control">
                                                    <option <?php if (!empty($update) && $update['typeof_termination'] == 1) {
                                                                echo "selected";
                                                            } ?> value="1">Retired</option>
                                                    <option <?php if (!empty($update) && $update['typeof_termination'] == 2) {
                                                                echo "selected";
                                                            } ?> value="2">Resigned</option>
                                                    <option <?php if (!empty($update) && $update['typeof_termination'] == 3) {
                                                                echo "selected";
                                                            } ?> value="3">Others</option>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('typeof_termination'); ?></span>
                                            </div>
                                        </div>

                                        <div class="col-md-3">

                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo "Remarks"; ?></label>
                                                <input autofocus="" id="remarks" name="remarks" placeholder="" type="text" class="form-control" value="<?php echo set_value('remarks') ?> <?php if (!empty($update)) {
                                                                                                                                                                                                echo $update['remarks'];
                                                                                                                                                                                            } ?>" />
                                                <span class="text-danger"><?php echo form_error('remarks'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">

                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo "Last Working Date"; ?></label>
                                                <input autofocus="" id="last_working_date" name="last_working_date" placeholder="" type="text" class="form-control date" value="<?php echo set_value('last_working_date') ?> <?php if (!empty($update)) {
                                                                                                                                                                                                echo $update['last_working_date'];
                                                                                                                                                                                            } ?>" />
                                                <span class="text-danger"><?php echo form_error('last_working_date'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">

                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo "Salary Up To Month"; ?></label>
                                                <input autofocus="" id="salary_upto_month" name="salary_upto_month" placeholder="" type="text" class="form-control" value="<?php echo set_value('salary_upto_month') ?>" />
                                                <span class="text-danger"><?php echo form_error('salary_upto_month'); ?></span>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <?php
                                            // echo display_custom_fields('staff');
                                            ?>
                                        </div>

                                    </div>
                                </div>


                            </div>
                            <div class="box-footer">
                                <input type="hidden" name="staff_id" value="<?php echo $staff_id; ?>">
                                <input type="hidden" name="session_id" value="<?php echo $session_id; ?>">
                                <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                            </div>
                    </form>
                </div>
            </div>
        </div>
</div>
</section>
</div>


<script type="text/javascript">


$(document).ready(function() {
    var date_format = "mm-yyyy"; // Specify the date format
    var start_week = 0; // Start the week on Sunday (0) or Monday (1)

    // Initialize the datepicker with month and year view
    $('#salary_upto_month').datepicker({
      todayHighlight: false,
      format: date_format,
      autoclose: true,
      startView: 1,  // Start view to month and year selection
      minViewMode: 1,  // Only show months and years, no days
      weekStart: start_week,  // Week starts on Sunday or Monday
      language: 'en'
    });
  });




</script>
<script type="text/javascript" src="<?php echo base_url(); ?>backend/dist/js/savemode.js"></script>