<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-gears"></i> <?php echo $this->lang->line('fees') . " " . $this->lang->line('reminder'); ?>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <!-- left column -->
                <form  action="<?php echo site_url('admin/feereminder/send_fees_dues_reminder') ?>" id="feereminder" name="feereminder" method="post" accept-charset="utf-8">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"> <?php echo $this->lang->line('fees') . " " . $this->lang->line('reminder'); ?></h3>
                        </div>
                        <div class="box-body">
                            <?php if ($this->session->flashdata('msg')) { ?>
                                <?php echo $this->session->flashdata('msg');unset($_SESSION['msg']); ?>
                            <?php } ?>

                            <!-- /.box-header -->

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"> <?php echo "Sch Section"; ?><small class="req"> *</small></label>
                                        <select id="sch_section" name="sch_section" class="form-control">
                                            <option value=""><?php echo "Select Section"; ?></option>
                                            <option value="All" <?php
                                                                                                if (set_value('sch_section') == "All") {
                                                                                                    echo "selected = selected";
                                                                                                }
                                                                                                ?>><?php echo "All"; ?></option>
                                            <?php
                                            if (!empty($sch_section_result)) {
                                                foreach ($sch_section_result as $key => $row) {
                                            ?>
                                                    <option value="<?php echo $row['id']; ?>" <?php
                                                                                                if (set_value('sch_section') == $row['id']) {
                                                                                                    echo "selected = selected";
                                                                                                }
                                                                                                ?>><?php echo $row['sch_section']; ?></option>
                                            <?php
                                                }
                                            } ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('sch_section'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"> <?php echo "Type"; ?><small class="req"> *</small></label>
                                        <select id="type" name="type" class="form-control">
                                            <option value="Whatsapp"><?php echo "Whatsapp"; ?></option>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('type'); ?></span>
                                    </div>
                                </div>
                            </div>


                            <table class="table table-hover ">
                                <thead>
                                    <th><input type="checkbox" name="" id="checkAll"></th>
                                    <th><?php echo $this->lang->line('class') . " (" . $this->lang->line('section') . ")"; ?></th>
                                </thead>
                                <tbody id="classbody">


                                </tbody>
                            </table>
                        </div>
                        <div class="box-footer">
                            <?php if ($this->rbac->hasPrivilege('fees_dues_reminder', 'can_edit')) {
                            ?>
                                <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('send'); ?></button>
                            <?php }
                            ?>

                            <?php ?>
                        </div>
                </form>
            </div>

        </div>
</div><!--./wrapper-->

</section><!-- /.content -->
</div>

<script>
    $(document).ready(function(e) {

        $('#checkAll').on('click', function() {
            var isChecked = $(this).prop('checked');
            $('.itemCheckbox').prop('checked', isChecked);
        });

        $("#sch_section").change(function() {
            var sch_section = $("#sch_section").val();
            $('#classbody').html('');
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>classes/getclassBySchsection',
                data: 'sch_section=' + sch_section,
                dataType: "json",
                success: function(data) { 
                    $('#classbody').html(data);
                }
            });
        });
    });
</script>