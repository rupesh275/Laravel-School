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
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('fees_discount_list') . " Approval"; ?></h3>
                        <div class="box-tools pull-right">
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="mailbox-messages table-responsive">
                            <div class="download_label"><?php echo $this->lang->line('fees_discount_list') . " Approval"; ?></div>
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>

                                        <th><?php echo $this->lang->line('roll') . " " . $this->lang->line('no'); ?>
                                        <th><?php echo $this->lang->line('student') . " " . $this->lang->line('name'); ?>

                                            <?php if (!empty($feediscountList) && $feediscountList[0]['fees_discount_id']  == 1) {
                                            ?>
                                        <th><?php echo $this->lang->line('sibling'); ?></th>
                                    <?php
                                            } ?>

                                            <?php if (!empty($feediscountList) && $feediscountList[0]['fees_discount_id']  == 2) {
                                            ?>
                                        <th><?php echo "Member ".$this->lang->line('name'); ?></th>
                                    <?php
                                            } ?>

                                            <?php if (!empty($feediscountList) && $feediscountList[0]['fees_discount_id']  == 2) {
                                            ?>
                                        <th><?php echo "Member Relation"; ?></th>
                                    <?php
                                            } ?>

                                            <?php if (!empty($feediscountList) && $feediscountList[0]['fees_discount_id']  == 3) {
                                            ?>
                                        <th><?php echo "Refer By"; ?></th>
                                    <?php
                                            } ?>

                                            <?php if (!empty($feediscountList) && $feediscountList[0]['fees_discount_id']  == 3) {
                                            ?>
                                        <th><?php echo "Refer Designation"; ?></th>
                                    <?php
                                            } ?>
                                    <th><?php echo $this->lang->line('approve'); ?>
                                    <th></th>
                                    <th></th>
                                    </th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // print_r($feediscountList);
                                    if (!empty($feediscountList)) {
                                        foreach ($feediscountList as $feediscount) {
                                            $student_session_row = $this->studentsession_model->searchStudentsBySession($feediscount['student_session_id']);
                                            if(!empty($student_session_row)){
                                            $sibling = $this->student_model->getsibling($student_session_row['student_id']);
                                            }
                                            if(!empty($sibling)){
                                            $siblingRow = $this->student_model->get($sibling['sibling_student_id']);
                                            }
                                            if(!empty($sibling)){
                                            $siblingdetail = $this->studentsession_model->searchStudentsBySession($sibling['sibling_student_id']);
                                            }
                                            $member = $this->student_model->getmember($student_session_row['student_id']);
                                            $referaRow = $this->student_model->getreferred($student_session_row['id']);
                                            // echo "<pre>";
                                            // print_r($siblingRow);
                                            // print_r($siblingdetail);
                                    ?>
                                            <tr>

                                                <td><?php echo $student_session_row['roll_no']; ?></td>
                                                <td class="mailbox-name">
                                                    <a href="#" data-toggle="popover" class="detail_popover"><?php echo $feediscount['firstname'] . " " . $feediscount['lastname'] . "  (" . $student_session_row['class'] . " " . $student_session_row['section'] . ")"; ?></a>
                                                </td>
                                                <?php if (!empty($feediscountList) && $feediscountList[0]['fees_discount_id']  == 1) {
                                                ?>
                                                    <td>
                                                        <a href="#" data-toggle="popover" class="detail_popover"><?php echo $siblingRow['firstname'] . " " . $siblingRow['lastname'] . "  (" . $siblingdetail['class'] . " " . $siblingdetail['section'] . ")"; ?></a>
                                                    </td>
                                                <?php
                                                } ?>

                                                <?php if (!empty($feediscountList) && $feediscountList[0]['fees_discount_id']  == 2) {
                                                ?>

                                                    <td><?php echo $member['member_name']; ?></td>
                                                <?php
                                                } ?>

                                                <?php if (!empty($feediscountList) && $feediscountList[0]['fees_discount_id']  == 2) {
                                                ?>

                                                    <td><?php echo $member['member_relation']; ?></td>
                                                <?php
                                                } ?>

                                                <?php if (!empty($feediscountList) && $feediscountList[0]['fees_discount_id']  == 3) {
                                                ?>

                                                    <td><?php echo $referaRow['referred_by']; ?></td>
                                                <?php
                                                } ?>

                                                <?php if (!empty($feediscountList) && $feediscountList[0]['fees_discount_id']  == 3) {
                                                ?>

                                                    <td><?php echo $referaRow['designation']; ?></td>
                                                <?php
                                                } ?>


                                                <td class="mailbox-name ml-2">
                                                    <input type="checkbox" class="discount_check" name="discount_check" id="discount_check_<?php echo $feediscount['id']; ?>" <?php echo $feediscount['is_active'] == 'Yes' ? 'checked' : ''; ?> value="<?php echo $feediscount['id']; ?>">
                                                </td>
                                                <td></td>
                                                <td></td>

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
    });

    $(document).ready(function() {
        $(".discount_check").on("click", function() {
            var id = $(this).val();
            if (id != '') {
                if ($('#discount_check_' + id).is(":checked")) {
                    var check = 'Yes';
                } else {
                    var check = 'no';
                }
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('admin/feediscount/approveBy'); ?>",
                    data: {
                        id: id,
                        check: check
                    },
                    dataType: "json",
                    success: function(data) {
                        if (data.success) {
                            return true;
                        }
                    }
                });
            }
        });
    });
</script>