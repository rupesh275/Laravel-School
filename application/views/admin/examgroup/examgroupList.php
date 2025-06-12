<?php $currency_symbol = $this->customlib->getSchoolCurrencyFormat(); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">  

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <?php
            if ($this->rbac->hasPrivilege('exam_group', 'can_add')) {
                ?>
                <div class="col-md-4">
                    <!-- Horizontal Form -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"> <?php echo $this->lang->line('add') . " " . $this->lang->line('exam') . " " . $this->lang->line('group'); ?></h3>
                        </div><!-- /.box-header -->
                        <form id="form1" action="<?php echo site_url('admin/examgroup') ?>"  id="examgroupform" name="examgroupform" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                            <div class="box-body">
                                <?php if ($this->session->flashdata('msg')) { ?>
                                    <?php echo $this->session->flashdata('msg') ?>
                                <?php } ?>
                                <?php
                                if (isset($error_message)) {
                                    echo "<div class='alert alert-danger'>" . $error_message . "</div>";
                                }
                                ?>
                                <?php echo $this->customlib->getCSRF(); ?>


                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('name'); ?> <small class="req">*</small></label> 
                                    <input id="name" autofocus="" name="name" placeholder="" type="text" class="form-control"  value="<?php echo set_value('name'); ?>" />
                                    <span class="text-danger"><?php echo form_error('name'); ?></span>
                                </div>
                                <!-- <div class="form-group">
                                    <label for="exampleInputEmail1"> <?php echo $this->lang->line('exam') . " " . $this->lang->line('type') ?></label> <small class="req">*</small>
                                    <select id="name" name="exam_type" placeholder="" type="text" class="form-control">
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php
                                        foreach ($examType as $examType_key => $examType_value) {
                                            ?>
                                            <option value="<?php echo $examType_key; ?>"><?php echo $examType_value; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>

                                    <span class="text-danger"><?php echo form_error('exam_type'); ?></span>
                                </div> -->
                                <div class="form-group">
                                    <label for="exampleInputEmail1"> <?php echo $this->lang->line('exam') . " " . $this->lang->line('type'). " " . $this->lang->line('id') ?></label> <small class="req">*</small>
                                    <select id="exam_type" name="exam_type" placeholder="" type="text" class="form-control">
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php
                                        foreach ($examGroupType as $examType_key => $examType_value) {
                                            ?>
                                            <option value="<?php echo $examType_value['key']; ?>"  data-id="<?php echo $examType_value['id']; ?>"><?php echo $examType_value['name']; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                    <input type="hidden" name="examtype_id" value="" id="examtype_id">

                                    <span class="text-danger"><?php echo form_error('exam_type'); ?></span>
                                </div>



                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('description'); ?></label>
                                    <textarea class="form-control" id="description" name="description" placeholder="" rows="3"><?php echo set_value('description'); ?></textarea>
                                    <span class="text-danger"></span>
                                </div>
                            </div><!-- /.box-body -->

                            <div class="box-footer">
                                <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                            </div>
                        </form>
                    </div>

                </div><!--/.col (right) -->
                <!-- left column -->
            <?php } ?>
            <div class="col-md-<?php
            if ($this->rbac->hasPrivilege('exam_group', 'can_add')) {
                echo "8";
            } else {
                echo "12";
            }
            ?>">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"> <?php echo $this->lang->line('exam') . " " . $this->lang->line('group') . " " . $this->lang->line('list') ?></h3>
                        <div class="box-tools pull-right">
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="mailbox-messages table-responsive">
                            <div class="download_label"> <?php echo $this->lang->line('exam') . " " . $this->lang->line('group') . " " . $this->lang->line('list') ?></div>
                            <table class="table table-hover table-striped table-bordered example">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('name'); ?></th>
                                        <th><?php echo $this->lang->line('no') . " " . $this->lang->line('of') . " " . $this->lang->line('exams'); ?></th>
                                        <th><?php echo $this->lang->line('exam') . " " . $this->lang->line('type'); ?></th>
                                        <th class="text-right"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (empty($examgrouplist)) {
                                        ?>

                                        <?php
                                    } else {
                                        foreach ($examgrouplist as $examgroup) {
                                            ?>
                                            <tr>
                                                <td class="mailbox-name">
                                                    <a href="#" data-toggle="popover" class="detail_popover"><?php echo $examgroup->name; ?></a>

                                                    <div class="fee_detail_popover" style="display: none">
                                                        <?php
                                                        if ($examgroup->description == "") {
                                                            ?>
                                                            <p class="text text-danger"><?php echo $this->lang->line('no_description'); ?></p>
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <p class="text text-info"><?php echo $examgroup->description; ?></p>
                                                            <?php
                                                        }
                                                        ?>
                                                    </div>

                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo $examgroup->counter; ?>

                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo $examgroup->examname; ?>

                                                </td>


                                                <td class="mailbox-date pull-right white-space-nowrap">
                                                    <a data-placement="left" href="javascript:void(0);" class="btn btn-default btn-xs assign_class" data-examgroup_id="<?php echo $examgroup->id; ?>"  data-toggle="tooltip" title="<?php echo "Class Assign"; ?>">
                                                        <i class="fa fa-tag"></i>
                                                    </a>
                                                    <?php if ($this->rbac->hasPrivilege('exam', 'can_view')) { ?>
                                                        
                                                        <a href="<?php echo base_url(); ?>admin/examgroup/addexam/<?php echo $examgroup->id ?>"
                                                           class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('add') . " " . $this->lang->line('exam') ?>">
                                                            <i class="fa fa-plus"></i>
                                                        </a>
                                                        <?php
                                                    }


                                                    if ($this->rbac->hasPrivilege('exam_group', 'can_edit')) {
                                                        ?>
                                                        <a data-placement="left" href="<?php echo site_url('admin/examgroup/edit/' . $examgroup->id); ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                            <i class="fa fa-pencil"></i>
                                                        </a>
                                                        <?php
                                                    }
                                                    if ($this->rbac->hasPrivilege('exam_group', 'can_delete')) {
                                                        ?>
                                                        <a data-placement="left" href="<?php echo site_url('admin/examgroup/delete/' . $examgroup->id); ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                            <i class="fa fa-remove"></i>
                                                        </a>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>

                                </tbody>
                            </table><!-- /.table -->



                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->
                </div>
            </div><!--/.col (left) -->

        </div>

    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<!-- Modal -->
<div id="assign_class_modal" class="modal fade" role="dialog">
    <form id="assign_class_form" action="<?php echo site_url('admin/examgroup/assign_class_validation') ?>" method="post">
        <div class="modal-dialog modal-md">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"> <?php echo $this->lang->line('assign') . " " . $this->lang->line('class'); ?></h4>
                </div>
                <div class="modal-body" id="assign_class_body">
                    
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="examgroup_id" id="examgroup_id" value="">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </form>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy',]) ?>';


        $('.detail_popover').popover({
            placement: 'right',
            trigger: 'hover',
            container: 'body',
            html: true,
            content: function () {
                return $(this).closest('td').find('.fee_detail_popover').html();
            }
        });

        $('#exam_type').on('change',function(){
            var id = $('#exam_type option:selected').data('id');
            $('#examtype_id').val(id);
        });

        $('.assign_class').on('click', function () {
            var examgroup_id = $(this).data('examgroup_id');
            $('#assign_class_body').html(" ");

            $.ajax({
                url: '<?php echo base_url(); ?>admin/examgroup/get_class_by_examgroup',
                type: "POST",
                data: {'examgroup_id': examgroup_id},
                dataType: "json",
                success: function (data) {
                    $('#examgroup_id').val(examgroup_id);
                    $('#assign_class_body').html(data.page);
                    $('#assign_class_modal').modal('show');
                },  error: function () {

                }    
            });
        });

        $("#assign_class_form").submit(function(e) {
            e.preventDefault(); // avoid to execute the actual submit of the form.
            var form = $(this);
            var url = form.attr('action');
            var submit_button = $(this).find(':submit');
            var post_params = $(this).serializeArray();
            $.ajax({
                type: "POST",
                url: url,
                data: post_params, // serializes the form's elements.
                dataType: "JSON", // serializes the form's elements.
                beforeSend: function() {
                    submit_button.button('loading');
                },
                success: function(data) {

                    if (!data.status) {
                        $.each(data.error, function(index, value) {
                            var errorDiv = '#' + index + '_error';
                            $(errorDiv).empty().append(value);
                        });
                    } else if (data.status) {
                        successMsg(data.message);
                        $('#assign_class_modal').modal('hide');
                    }
                },
                error: function(xhr) { // if error occured
                    submit_button.button('reset');
                    alert("<?php echo $this->lang->line('error_occured') . ", " . $this->lang->line('please_try_again') ?>");

                },
                complete: function() {
                    submit_button.button('reset');
                }
            });
        });
    });
</script>