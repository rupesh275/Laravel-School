<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1>
            <i class="fa fa-map-o"></i> <?php echo $this->lang->line('examinations'); ?> <small><?php echo $this->lang->line('student_fee1'); ?></small>
        </h1>
    </section>
    <!-- Main content -->

    <section class="content">

        <div class="box-body">
            <form action="<?php echo base_url('admin/teacher/working_days_entry') ?>" method="post">
            <?php if ($this->session->flashdata('msg')) { ?>
                <?php echo $this->session->flashdata('msg') ?>
            <?php } ?>
            <?php echo $this->customlib->getCSRF(); ?>
            <div class="table-responsive no-padding">
                    <?php
                    if (!empty($classlist)) {
                    ?>
                        <table class="table table-responsive table-striped ">
                            <thead>
                                <tr>
                                    <th style="width: 136px;"><?php echo $this->lang->line('class'); ?></th>
                                    <th style="width: 341px;"><?php echo "Working Days"; ?></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                        <?php } ?>
                        <tbody>
                            <?php
                            if (empty($classlist)) {
                            ?>
                                <!-- <tr>
                                <td colspan="7" class="text-danger text-center"><?php echo $this->lang->line('no_record_found'); ?></td>
                            </tr> -->
                                <?php
                            } else {
                                $i = 0;
                                foreach ($classlist as $class) {
                                    $update = $this->teacher_model->get_workingDays($class['id']);
                                    // echo "<pre>";
                                    // print_r($update);

                                ?>
                                    <tr>
                                        <input type="hidden" name="id[]" value="<?php if(isset($update)){ echo $update['id'];} ?>" id="id">
                                        <input type="hidden" name="class_id[]" value="<?php echo $class['id'] ?>" id="class">
                                        <td><?php echo $class['class']; ?></td>
                                        <td>
                                            <input type="text" name="working_days[]" class="form-control" id="working_days" value="<?php echo set_value('working_days',isset($update) ? $update['working_days'] :""); ?>">
                                            <span class="text-danger"><?php echo form_error('working_days'); ?></span>
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                            <?php
                                    $i++;
                                }
                            }
                            ?>
                        </tbody>
                        </table>
                        <?php
                        if (!empty($classlist)) {
                        ?>
                            <?php if ($this->rbac->hasPrivilege('attendenceEntry', 'can_edit')) {
                            ?>
                                <?php
                                ?>
                                <button type="submit" class="allot-fees btn btn-primary btn-sm pull-right" id="load" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Please Wait.."><?php echo $this->lang->line('save'); ?>

                                </button>
                        <?php }
                        } ?>
                </form>
            </div>
        </div>
    </section>
</div>





<script type="text/javascript">
    $(document).on('submit', 'form#allot_exam_student', function(e) {

        e.preventDefault(); // avoid to execute the actual submit of the form.
        var form = $(this);
        var $this = form.find("button[type=submit]:focus");
        var url = form.attr('action');
        $.ajax({
            url: url,
            type: "POST",
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function() {
                $this.button('loading');
            },
            success: function(res) {
                if (res.status == 1) {
                    successMsg(res.message);

                } else {
                    errorMsg(res.message);
                }

                $this.button('reset');
            },
            error: function(xhr) { // if error occured
                alert("Error occured.please try again");
                $this.button('reset');
            },
            complete: function() {
                $this.button('reset');
                setTimeout(function() {
                    location.reload();
                }, 1000)

            }

        });
    });
</script>