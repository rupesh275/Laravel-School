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
        <div class="box-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="sfborder">
                        <div class="col-md-2">
                            <img width="115" height="115" class="round5" src="<?php
                                                                                if (!empty($student['image'])) {
                                                                                    echo base_url() . $student['image'];
                                                                                } else {
                                                                                    echo base_url() . "uploads/student_images/no_image.png";
                                                                                }
                                                                                ?>" alt="No Image">
                        </div>

                        <div class="col-md-10">
                            <div class="row">
                                <table class="table table-striped mb0 font13">
                                    <tbody>
                                        <tr>
                                            <th class="bozero"><?php echo $this->lang->line('name'); ?></th>
                                            <td class="bozero"><?php echo $this->customlib->getFullName($student['firstname'], $student['middlename'], $student['lastname'], $sch_setting->middlename, $sch_setting->lastname); ?></td>

                                            <th class="bozero"><?php echo $this->lang->line('class_section'); ?></th>
                                            <td class="bozero"><?php echo $student['class'] . " (" . $student['section'] . ")" ?> </td>
                                        </tr>
                                        <tr>
                                            <th><?php echo $this->lang->line('father_name'); ?></th>
                                            <td><?php echo $student['father_name']; ?></td>
                                            <th><?php echo $this->lang->line('admission_no'); ?></th>
                                            <td><?php echo $student['admission_no']; ?></td>
                                        </tr>
                                        <tr>
                                            <th><?php echo $this->lang->line('mobile_no'); ?></th>
                                            <td><?php echo $student['mobileno']; ?></td>
                                            <th><?php echo $this->lang->line('roll_no'); ?></th>
                                            <td> <?php echo $student['roll_no']; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php echo $this->lang->line('category'); ?></th>
                                            <td>
                                                <?php
                                                foreach ($categorylist as $value) {
                                                    if ($student['category_id'] == $value['id']) {
                                                        echo $value['category'];
                                                    }
                                                }
                                                ?>
                                            </td>
                                            <?php if ($sch_setting->rte) { ?>
                                                <th><?php echo $this->lang->line('rte'); ?></th>
                                                <td><b class="text-danger"> <?php echo $student['rte']; ?> </b>
                                                </td>
                                            <?php } ?>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <form action="<?php echo site_url('student/feesubmit') ?>" method="post" id="assign_form1113">
                    <!-- general form elements -->
                    <div class="box box-primary">
                        <div class="box-header ptbnull">
                            <h3 class="box-title titlefix"><?php echo " Class Fees"; ?></h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-sm btn-primary addfeesmaster" data-feegroup_id="<?php //echo $fee['fees_group_id']; 
                                                                                                                        ?>" data-class_id="<?php echo $class_id; ?>" data-section_id="<?php echo $section_id; ?>" id="load" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait') ?>"><i class="fa fa-money"></i> Add</button>
                            </div><!-- /.box-tools -->
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            <div class="mailbox-messages table-responsive">
                                <div class="download_label"><?php echo " Class Fees"; ?></div>
                                <table class="table table-striped table-bordered table-hover ">
                                    <thead>
                                        <tr>

                                            <th><?php echo $this->lang->line(''); ?>
                                            <th><?php echo $this->lang->line('fees_group'); ?>
                                            <th><?php echo $this->lang->line('fees_code'); ?>

                                                <!-- <th><?php //echo $this->lang->line('amount'); 
                                                            ?></th> -->

                                            <th class="text text-right"><?php //echo $this->lang->line('action'); 
                                                                        ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (!empty($fees_array)) {
                                            foreach ($fees_array as $fee) {
                                                $feegroup_type = $this->feesessiongroup_model->getFeesByGroup($fee['fees_group_id']);
                                                foreach ($feegroup_type as $key => $feegroup) {
                                                    $this->db->where('class_id', $class_id);
                                                    // $this->db->where('section_id', $section_id);
                                                    $this->db->where('fees_group_id', $feegroup->id);
                                                    $feesarray         = $this->db->get('class_fees_mst')->row_array();

                                        ?>
                                                    <tr>
                                                        <td>

                                                            <input type="checkbox" name="fees_group_id[]" id="fees_group_id" value="<?php echo $feegroup->id ?>" <?php if (!empty($feesarray['fees_group_id']) == $feegroup->id) {
                                                                                                                                            echo "checked";
                                                                                                                                        } ?>>
                                                        </td>
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


                                                                    </li>

                                                                <?php
                                                                }
                                                                ?>
                                                            </ul>
                                                        </td>

                                                        <td class="mailbox-date pull-right white-space-nowrap">

                                                        </td>
                                                    </tr>
                                        <?php
                                                }
                                            }
                                        }
                                        ?>
                                        <?php
                                        $total = 0;
                                        foreach ($fees_array as $fee) {
                                            $feegroup_type = $this->feesessiongroup_model->getFeesByGroup($fee['fees_group_id']);

                                            foreach ($feegroup_type as $key => $feegroup) {
                                                foreach ($feegroup->feetypes as $feetype_key => $feetype_value) {


                                        ?>
                                                    <?php $total = $total + $feetype_value->amount; ?>

                                        <?php }
                                            }
                                        } ?>
                                        <tr>
                                            <td></td>
                                            <td><b>Total</b></td>
                                            <td><b><?php echo $currency_symbol . " " . $total; ?></b></td>
                                            <td></td>
                                        </tr>

                                    </tbody>

                                </table><!-- /.table -->

                                <h3 class="box-title titlefix"><?php echo " Discount"; ?></h3>
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>

                                            <th><?php echo $this->lang->line(''); ?>
                                            <th><?php echo $this->lang->line('discount'); ?>
                                            <th><?php echo $this->lang->line('discount_code'); ?>

                                                <!-- <th><?php //echo $this->lang->line('amount'); 
                                                            ?></th> -->

                                            <th class="text text-right"><?php //echo $this->lang->line('action'); 
                                                                        ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (!empty($feediscountList)) {
                                            foreach ($feediscountList as $feediscount) {

                                        ?>
                                                <tr>
                                                    <input type="hidden" name="student_session_id" value="<?php echo $student_session_id; ?>">
                                                    <td>
                                                        <input type="checkbox" name="discount_id[]" id="discount_id" value="<?php echo $feediscount['id']; ?>">
                                                    </td>
                                                    <td class="mailbox-name">
                                                        <a href="#" data-toggle="popover" class="detail_popover"><?php echo $feediscount['name']; ?></a>
                                                    </td>
                                                    <td class="mailbox-name">
                                                        <?php echo $feediscount['code']; ?>
                                                    </td>

                                                    <!-- <td class="mailbox-name">
                                                <?php //echo $class_section['amount']; 
                                                ?>
                                            </td> -->


                                                    <td class="mailbox-date pull-right white-space-nowrap">

                                                    </td>
                                                </tr>
                                        <?php
                                            }
                                        }
                                        ?>

                                    </tbody>

                                </table><!-- /.table -->
                                <div class="col-md-12">
                                    <button type="submit" class="allot-fees btn btn-primary btn-sm pull-right" id="load" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Please Wait.."><?php echo $this->lang->line('save'); ?>
                                    </button>
                                </div>
                            </div><!-- /.mail-box-messages -->
                        </div><!-- /.box-body -->

                    </div>
                </form>
            </div><!--/.col (left) -->
            <!-- right column -->

        </div>
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
                        1000
                    );
                }
            });
            // } else {
            //     console.log("does not validate");
            // }

        })

    });
</script>