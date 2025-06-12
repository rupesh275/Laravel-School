<?php $currency_symbol = $this->customlib->getSchoolCurrencyFormat(); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <section class="content-header">
        <h1>
            <i class="fa fa-money"></i> <?php echo $this->lang->line('fees_collection'); ?>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">

            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo " Class Fees"; ?></h3>
                        <div class="box-tools pull-right">
                            <a href="<?php echo base_url('studentfee/assign_students/'.$class_id."/".$section_id);?>" class="btn btn-sm btn-primary"><i class="fa fa-tag"></i> Assign</a>
                            <button type="button" class="btn btn-sm btn-primary addfeesmaster" data-feegroup_id="<?php //echo $fee['fees_group_id']; 
                                                                                                                    ?>" data-class_id="<?php echo $class_id; ?>" data-section_id="<?php echo $section_id; ?>" id="load" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait') ?>"><i class="fa fa-money"></i> Add</button>
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="mailbox-messages table-responsive">
                            <div class="download_label"><?php echo " Class Fees"; ?></div>
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>

                                        <th><?php echo $this->lang->line('fees_group'); ?>
                                        <th><?php echo $this->lang->line('fees_code'); ?>

                                            <!-- <th><?php //echo $this->lang->line('amount'); 
                                                        ?></th> -->

                                        <th class="text text-right"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // echo "<pre>";
                                    // print_r($fees_array);
                                    if (!empty($fees_array)) {
                                        foreach ($fees_array as $fee) {
                                            $feegroup_type = $this->feesessiongroup_model->getFeesByGroup($fee['fees_group_id']);
                                            foreach ($feegroup_type as $key => $feegroup) {

                                                // echo "<pre>";
                                                // print_r ($feegroup_type);
                                                // echo "</pre>";

                                    ?>
                                                <tr>
                                                    <td class="mailbox-name">
                                                        <a href="#" data-toggle="popover" class="detail_popover"><?php echo $feegroup->group_name; ?></a>
                                                    </td>
                                                    <td class="mailbox-name">
                                                        <ul class="liststyle1">
                                                            <?php
                                                            foreach ($feegroup->feetypes as $feetype_key => $feetype_value) {
                                                            ?>
                                                                <li> <i class="fa fa-money"></i>
                                                                    <?php echo $feetype_value->code . " " . $currency_symbol . $feetype_value->amount; ?> &nbsp;&nbsp;
                                                                    <?php /*if ($this->rbac->hasPrivilege('fees_master', 'can_edit')) { ?>
                                                                    <a href="<?php echo base_url(); ?>admin/feemaster/edit/<?php echo $feetype_value->id ?>" data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                                        <i class="fa fa-pencil"></i>
                                                                    </a>&nbsp;
                                                                <?php
                                                                }
                                                                if ($this->rbac->hasPrivilege('fees_master', 'can_delete')) {
                                                                ?>
                                                                    <a href="<?php echo base_url(); ?>admin/feemaster/delete/<?php echo $feetype_value->id ?>" data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                                        <i class="fa fa-remove"></i>
                                                                    </a>
                                                                <?php } */ ?>

                                                                </li>

                                                            <?php
                                                            }
                                                            ?>
                                                        </ul>
                                                    </td>

                                                    <!-- <td class="mailbox-name">
                                                <?php //echo $class_section['amount']; 
                                                ?>
                                            </td> -->


                                                    <td class="mailbox-date pull-right white-space-nowrap">
                                                        <?php
                                                        if ($this->rbac->hasPrivilege('class_wise_fees', 'can_view')) {
                                                        ?>
                                                            <!-- <button type="button" class="btn btn-sm btn-primary addfeesmaster" data-feegroup_id="<?php echo $fee['fees_group_id']; ?>" data-class_id="<?php echo $class_id; ?>" data-section_id="<?php echo $section_id; ?>" id="load" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait') ?>"><i class="fa fa-money"></i> Add</button> -->
                                                            <!-- <a data-placement="left" class="addfeesmaster" href="javascript:void();" class="btn btn-default btn-xs" data-toggle="tooltip" title="View">
                                                                <i class="fa fa-plus"></i>
                                                            </a> -->
                                                            <a data-placement="left" href="<?php echo base_url(); ?>studentfee/removeclassfees/<?php echo $class_id;
                                                                                                                                                ?>/<?php echo $section_id;
                                                                                                                                                ?>/<?php echo $fee['fees_group_id']; ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="View" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                                <i class="fa fa-remove"></i>
                                                            </a>
                                                        <?php
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                    <?php
                                            }
                                        }
                                    }
                                    ?>

                                </tbody>
                            </table><!-- /.table -->


                            <div id="StudentFeesModel" class="modal fade" role="dialog">
                                <div class="modal-dialog modal-lg">
                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title subjectmodal_header_1"> Fees</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="examheight100 relative">
                                                <div id="examfade"></div>
                                                <div id="exammodal">
                                                    <img id="loader" src="<?php echo base_url() ?>/backend/images/loading_blue.gif" />
                                                </div>
                                                <div class="classfeesForm">

                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->

                </div>
            </div><!--/.col (left) -->
            <!-- right column -->

        </div>
        <div class="row">
            <!-- left column -->

            <!-- right column -->
            <div class="col-md-12">

            </div><!--/.col (right) -->
        </div> <!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<script type="text/javascript">
    $(document).ready(function() {


        $("#btnreset").click(function() {
            $("#form1")[0].reset();
        });

    });
</script>
<script>
    $(document).ready(function() {
        $('.detail_popover').popover({
            placement: 'right',
            trigger: 'hover',
            container: 'body',
            html: true,
            content: function() {
                return $(this).closest('td').find('.fee_detail_popover').html();
            }
        });

        $(document).on('click', '.addfeesmaster', function() {
            var $this = $(this);
            var feegroup_id = $(this).data('feegroup_id');
            var class_id = $(this).data('class_id');
            var section_id = $(this).data('section_id');


            $.ajax({
                type: 'POST',
                url: base_url + "studentfee/getfeemaster",
                data: {
                    feegroup_id: feegroup_id,
                    class_id: class_id,
                    section_id: section_id,
                },
                dataType: "JSON",
                beforeSend: function() {
                    $this.button('loading');
                },
                success: function(data) {

                    $('.classfeesForm').html(data.page);
                    $('#StudentFeesModel').modal('show');
                    $this.button('reset');
                },
                error: function(xhr) { // if error occured
                    alert("Error occured.please try again");

                },
                complete: function() {
                    $this.button('reset');
                }
            });

        });

        $(document).on('submit', 'form#assign_form1112', function(event) {
            event.preventDefault();

            var $this = $('.allot-fees');
            $.ajax({
                type: "POST",
                dataType: 'Json',
                url: $("#assign_form1112").attr('action'),
                data: $("#assign_form1112").serialize(), // serializes the form's elements.
                beforeSend: function() {
                    $this.button('loading');

                },
                success: function(data) {
                    if (data.status == "fail") {
                        var message = "";
                        $.each(data.error, function(index, value) {

                            message += value;
                        });
                        errorMsg(message);
                    } else {
                        successMsg(data.message);
                        $('#StudentFeesModel').modal('hide');
                    }
                },
                complete: function() {
                    $this.button('reset');
                    window.setTimeout(
                        function() {
                            location.reload(true)
                        },
                        2000
                    );
                }
            });
            // } else {
            //     console.log("does not validate");
            // }

        })

    });
</script>