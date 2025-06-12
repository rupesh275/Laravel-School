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
            <!-- left column -->
            <div class="col-md-12">
                <div class="box-body" style="padding-top:0;">
                    <div class="box-header">
                        <div class="row">
                            <div class="col-md-4">
                                <h3 class="box-title"><?php echo "Penalty"; ?></h3>
                            </div>
                            <div class="col-md-8">
                                <div class="btn-group pull-right">
                                    <a href="<?php echo base_url() ?>studentfee/student_penalty" type="button" class="btn btn-primary btn-xs">
                                        <i class="fa fa-arrow-left"></i> <?php echo $this->lang->line('back'); ?></a>
                                </div>
                            </div>

                        </div>
                    </div><!--./box-header-->
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

                                                    <th class="bozero"><?php echo $this->lang->line('class') . " " . $this->lang->line('section'); ?></th>
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
                        <!-- <form action="<?php //echo site_url('student/feesubmit') 
                                            ?>" method="post" id="assign_form1113"> -->
                        <!-- general form elements -->
                        <div class="box box-primary">
                            <div class="box-header ptbnull">
                                <h3 class="box-title titlefix"><?php echo " Student Penalty"; ?></h3>
                                <div class="box-tools pull-right">

                                </div><!-- /.box-tools -->
                            </div><!-- /.box-header -->
                            <div class="box-body">
                                <div class="mailbox-messages ">
                                    <div class="download_label"><?php echo " Student Division Change"; ?></div>
                                    <!-- <div class=" box-tools impbtntitle"> -->
                                    <form action="" id="form" method="post">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('fee') . " " . $this->lang->line('date'); ?></label> <small class="req"> *</small>
                                                    <input id="fee_date" name="fee_date" placeholder="" type="text" class="form-control date" value="<?php echo set_value('fee_date'); ?>" />
                                                    <span class="text-danger"><?php echo form_error('fee_date'); ?></span>
                                                    <div class="text-danger" id="error-fee_date"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('fee_type'); ?></label> <small class="req"> *</small>
                                                    <select name="fee_type" id="fee_type" class="form-control">
                                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                        <option value="1" <?php echo set_value('fee_type'); ?>><?php echo $this->lang->line('lost'); ?><?php echo " & Damage"; ?></option>
                                                        <option value="2" <?php echo set_value('fee_type'); ?>><?php echo "Behaviour"; ?></option>

                                                    </select>
                                                    <span class="text-danger"><?php echo form_error('fee_type'); ?></span>
                                                    <div class="text-danger" id="error-fee_type"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('subject'); ?></label>
                                                    <input id="subject" name="subject" placeholder="" type="text" class="form-control " value="<?php echo set_value('subject'); ?>" />
                                                    <span class="text-danger"><?php echo form_error('subject'); ?></span>
                                                    <div class="text-danger" id="error-subject"></div>
                                                </div>
                                            </div>
                                            <!-- </div> -->
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('description'); ?></label>
                                                <textarea id="description" name="description" placeholder="" class="form-control" rows="2"><?php echo set_value('description'); ?></textarea>
                                                <span class="text-danger"><?php echo form_error('description'); ?></span>
                                                <div class="text-danger" id="error-description"></div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('fine'); ?></label>
                                                    <input id="fine" name="fine" placeholder="" type="text" class="form-control " value="<?php echo set_value('fine'); ?>" />
                                                    <span class="text-danger"><?php echo form_error('fine'); ?></span>
                                                    <div class="text-danger" id="error-fine"></div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <input type="hidden" name="student_session_id" value="<?php echo $student_session_id; ?>">
                                            <button type="submit" class="allot-fees btn btn-primary btn-sm pull-right" id="load" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Please Wait.."><?php echo $this->lang->line('save'); ?>
                                            </button>
                                        </div>
                                    </form>
                                </div><!-- /.mail-box-messages -->
                            </div><!-- /.box-body -->

                        </div>
                        <!-- </form> -->
                    </div><!--/.col (left) -->
                    <!-- right column -->

                </div>

                <div class="row">
                    <!-- left column -->

                    <!-- right column -->
                    <div class="col-md-12">

                    </div><!--/.col (right) -->
                </div> <!-- /.row -->
            </div> <!-- /.row -->
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

        // $(document).on('click', '.addfeesmaster', function() {
        //     var $this = $(this);
        //     var feegroup_id = $(this).data('feegroup_id');
        //     var class_id = $(this).data('class_id');
        //     var section_id = $(this).data('section_id');


        //     $.ajax({
        //         type: 'POST',
        //         url: base_url + "studentfee/getfeemaster",
        //         data: {
        //             feegroup_id: feegroup_id,
        //             class_id: class_id,
        //             section_id: section_id,
        //         },
        //         dataType: "JSON",
        //         beforeSend: function() {
        //             $this.button('loading');
        //         },
        //         success: function(data) {

        //             $('.classfeesForm').html(data.page);
        //             $('#StudentFeesModel').modal('show');
        //             $this.button('reset');
        //         },
        //         error: function(xhr) { // if error occured
        //             alert("Error occured.please try again");

        //         },
        //         complete: function() {
        //             $this.button('reset');
        //         }
        //     });

        // });

        $("#form").on("submit", function(e) {
            event.preventDefault();

            var $this = $('.allot-fees');
            $.ajax({
                url: "<?php echo site_url('studentfee/penalty_update') ?>",
                type: "POST",
                data: new FormData(this), // serializes the form's elements.
                contentType: false,
                cache: false,
                processData: false,
                dataType: 'Json',
                beforeSend: function() {
                    // $this.button('loading');

                },
                success: function(data) {
                    // $("#spinner").hide(); 
                    // $("#submit").attr("disabled",false);
                    if (data.error) {
                        $.each(data, function(key, value) {
                            if (value) {
                                $('#error-' + key).html(value);
                                $('#input-' + key).addClass("border-danger");
                            } else {
                                $('#error-' + key).html(" ");
                                $('#input-' + key).removeClass("border-danger");
                            }
                        });
                    }
                    if (data.success) {
                        $('#form .form-control').removeClass("error");
                        $('#form .error').html(" ");
                        Popup(data.response);
                    }
                }
            });
            // } else {
            //     console.log("does not validate");
            // }

        })

    });

    function Popup(data, winload = false) {
        var frame1 = $('<iframe />').attr("id", "printDiv");
        frame1[0].name = "frame1";
        frame1.css({
            "position": "absolute",
            "top": "-1000000px"
        });
        $("body").append(frame1);
        var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
        frameDoc.document.open();
        //Create a new HTML document.
        // frameDoc.document.write('<html>');
        // frameDoc.document.write('<head>');
        // frameDoc.document.write('<title></title>');
        // frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/jvectormap/jquery-jvectormap-1.2.2.css">');
        // frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/bootstrap/css/bootstrap.min.css">');
        // frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/font-awesome.min.css">');
        // frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/ionicons.min.css">');
        // frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/AdminLTE.min.css">');
        // frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/skins/_all-skins.min.css">');
        // frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/iCheck/flat/blue.css">');
        // frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/morris/morris.css">');
        // frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/datepicker/datepicker3.css">');
        // frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/daterangepicker/daterangepicker-bs3.css">');
        // frameDoc.document.write('</head>');
        // frameDoc.document.write('<body>');
        frameDoc.document.write(data);
        //frameDoc.document.write('</body>');
        //frameDoc.document.write('</html>');
        frameDoc.document.close();
        setTimeout(function() {
            document.getElementById('printDiv').contentWindow.focus();
            document.getElementById('printDiv').contentWindow.print();
            $("#printDiv", top.document).remove();
            // frame1.remove();
            console.log(winload);
            if (winload == false) {
                window.location.href='<?php echo site_url('studentfee/student_penalty') ?>';
            }
        }, 500);

        return true;
    }
</script>