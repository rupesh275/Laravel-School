<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-money"></i> <?php echo $this->lang->line('fees_collection'); ?> <small> <?php echo $this->lang->line('filter_by_name1'); ?></small>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <?php $this->load->view('reports/_finance'); ?>
        <div class="row">
            <div class="col-md-12">
                <div class="box removeboxmius">
                    <div class="box-header ptbnull"></div>
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>
                    <form action="<?php echo site_url('studentfee/reportduefees') ?>" method="post" accept-charset="utf-8">
                        <div class="box-body">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('class'); ?></label>
                                        <select autofocus="" id="class_id" name="class_id" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($classlist as $class) {
                                            ?>
                                                <option value="<?php echo $class['id'] ?>" <?php if (set_value('class_id') == $class['id']) echo "selected=selected" ?>><?php echo $class['class'] ?></option>
                                            <?php
                                                $count++;
                                            }
                                            ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('class_id'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('section'); ?></label>
                                        <select id="section_id" name="section_id" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($section_list as $value) {
                                            ?>
                                                <option <?php
                                                        if ($value['section_id'] == $section_id) {
                                                            echo "selected";
                                                        }
                                                        ?> value="<?php echo $value['section_id']; ?>"><?php echo $value['section']; ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <div class="resp">

                            </div>

                            <button type="submit" class="btn btn-primary btn-sm pull-right"><i class="fa fa-search"></i> <?php echo $this->lang->line('search') ?></button>
                        </div>
                    </form>
                    <div class="row">
                        <?php
                        if (isset($student_due_fee)) {
                        ?>
                            <div class="" id="transfee">
                                <div class="box-header ptbnull">
                                    <h3 class="box-title titlefix"><i class="fa fa-users"></i> <?php echo $this->lang->line('balance_fees_statement'); ?> As On <?php echo date('d/m/Y'); ?></h3>
                                </div>
                                <div class="box-body">
                                    <?php
                                    if (!empty($student_due_fee)) {
                                    ?>
                                        <button type="button" class="btn btn-sm btn-info mb10 print" id="load" data-class-id="<?php echo $class_id; ?>" data-section-id="<?php echo $section_id; ?>" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Please wait"><i class="fa fa-print"></i> <?php echo $this->lang->line('print') ?> </button>
                                        <div class="clearfix"></div>
                                        <?php
                                        foreach ($student_due_fee as $student_key => $student_value) {
                                        ?>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <label> <?php echo $this->lang->line('admission_no') ?>: </label>

                                                    <?php echo $student_value['admission_no']; ?>
                                                </div>
                                                <div class="col-md-3">
                                                    <label> <?php echo $this->lang->line('name') ?>: </label>

                                                    <?php echo $this->customlib->getFullName($student_value['firstname'], $student_value['middlename'], $student_value['lastname'], $sch_setting->middlename, $sch_setting->lastname); ?>

                                                </div>
                                                <div class="col-md-3">
                                                    <label><?php echo $this->lang->line('father_name') ?>: </label>
                                                    <?php echo $student_value['father_name'] ?>
                                                </div>
                                                <div class="col-md-3">
                                                    <label> <?php echo $this->lang->line('class_section') ?>: </label>

                                                    <?php echo $student_value['class'] . " (" . $student_value['section'] . ")" ?>
                                                </div>

                                            </div>
                                            <hr class="mb10 mt10">
                                            <div class="table-responsive">
                                                <table class="table table-striped table-bordered table-hover">
                                                    <thead class="header">
                                                        <tr>
                                                            <th align="left"><?php echo $this->lang->line('fees_group'); ?></th>
                                                            <th align="left"><?php echo $this->lang->line('fees_code'); ?></th>
                                                            <th align="left" class="text text-left"><?php echo $this->lang->line('due_date'); ?></th>
                                                            <th align="left" class="text text-left"><?php echo $this->lang->line('status'); ?></th>
                                                            <th class="text text-right"><?php echo $this->lang->line('amount') ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                                            <th class="text text-left"><?php echo $this->lang->line('payment_id'); ?></th>
                                                            <th class="text text-left"><?php echo $this->lang->line('mode'); ?></th>
                                                            <th class="text text-left"><?php echo $this->lang->line('date'); ?></th>
                                                            <th class="text text-right"><?php echo $this->lang->line('discount'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                                            <th class="text text-right"><?php echo $this->lang->line('fine'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                                            <th class="text text-right"><?php echo $this->lang->line('paid'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                                            <th class="text text-right"><?php echo $this->lang->line('balance'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $total_amount = 0;
                                                        $total_deposite_amount = 0;
                                                        $total_discount_amount = 0;
                                                        $total_fine_amount = 0;
                                                        $total_fees_fine_amount = 0;
                                                        $total_balance_amount = 0;

                                                        foreach ($student_value['fees_list'] as $fee_key => $fee_value) {
                                                            if (($fee_value->due_date != "0000-00-00" && $fee_value->due_date != NULL) && (strtotime($fee_value->due_date) < strtotime(date('Y-m-d')))) {

                                                                $total_fees_fine_amount += $fee_value->fine_amount;
                                                            }
                                                            //======================
                                                            $fee_paid = 0;
                                                            $fee_discount = 0;
                                                            $fee_fine = 0;
                                                            $fees_fine_amount = 0;
                                                            if (!empty($fee_value->amount_detail)) {
                                                                $fee_deposits = json_decode(($fee_value->amount_detail));

                                                                foreach ($fee_deposits as $fee_deposits_key => $fee_deposits_value) {
                                                                    $fee_paid += $fee_deposits_value->amount;
                                                                    $fee_discount += $fee_deposits_value->amount_discount;
                                                                    $fee_fine += $fee_deposits_value->amount_fine;
                                                                }
                                                            }
                                                            // print_r($fee_value);

                                                            $fee_type_amount = ($fee_value->is_system) ? $fee_value->previous_amount : $fee_value->amount;
                                                            $feetype_balance = $fee_type_amount - ($fee_paid + $fee_discount);
                                                            $total_amount += $fee_type_amount;
                                                            $total_discount_amount += $fee_discount;
                                                            $total_fine_amount += $fee_fine;
                                                            $total_deposite_amount += $fee_paid;
                                                            $total_balance_amount += $feetype_balance;

                                                            //===============================
                                                        ?>
                                                            <tr class="dark-gray">

                                                                <td align="left"><?php
                                                                                    echo $fee_value->fee_group_name . " (" . $fee_value->type . ")";
                                                                                    ?></td>
                                                                <td align="left"><?php echo $fee_value->code; ?></td>
                                                                <td align="left" class="text text-left">

                                                                    <?php
                                                                    if ($fee_value->due_date == "0000-00-00") {
                                                                    } else {

                                                                        echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($fee_value->due_date));
                                                                    }
                                                                    ?>
                                                                </td>
                                                                <td align="left" class="text text-left width85">
                                                                    <?php
                                                                    if ($feetype_balance == 0) {
                                                                    ?><span class="label label-success"><?php echo $this->lang->line('paid'); ?></span><?php
                                                                                                                                                        } else if (!empty($fee_value->amount_detail)) {
                                                                                                                                                            ?><span class="label label-warning"><?php echo $this->lang->line('partial'); ?></span><?php
                                                                                                                                                            } else {
                                                                                                                                                                ?><span class="label label-danger"><?php echo $this->lang->line('unpaid'); ?></span><?php
                                                                                                                                                            }
                                                                                                                                                            ?>

                                                                </td>
                                                                <td class="text text-right">
                                                                    <?php
                                                                    echo $fee_type_amount;
                                                                    if (($fee_value->due_date != "0000-00-00" && $fee_value->due_date != NULL) && (strtotime($fee_value->due_date) < strtotime(date('Y-m-d')))) {
                                                                    ?>
                                                                        <span class="text text-danger"><?php echo " + " . ($fee_value->fine_amount); ?></span>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </td>
                                                                <td class="text text-left"></td>
                                                                <td class="text text-left"></td>
                                                                <td class="text text-left"></td>
                                                                <td class="text text-right"><?php
                                                                                            echo (number_format($fee_discount, 2, '.', ''));
                                                                                            ?></td>
                                                                <td class="text text-right"><?php
                                                                                            echo (number_format($fee_fine, 2, '.', ''));
                                                                                            ?></td>
                                                                <td class="text text-right"><?php
                                                                                            echo (number_format($fee_paid, 2, '.', ''));
                                                                                            ?></td>
                                                                <td class="text text-right"><?php
                                                                                            $display_none = "ss-none";
                                                                                            if ($feetype_balance > 0) {
                                                                                                $display_none = "";

                                                                                                echo (number_format($feetype_balance, 2, '.', ''));
                                                                                            }
                                                                                            ?>
                                                                </td>
                                                            </tr> <!-- //============== -->

                                                            <!-- //============ -->
                                                        <?php
                                                        }
                                                        //=================================
                                                        ?>
                                                        <tr class="box box-solid total-bg">

                                                            <td align="left"></td>
                                                            <td align="left"></td>
                                                            <td align="left"></td>
                                                            <td align="left" class="text text-left"><?php echo $this->lang->line('grand_total'); ?></td>
                                                            <td class="text text-right">
                                                                <?php
                                                                echo $currency_symbol . number_format($total_amount, 2, '.', '') . "<span class='text text-danger'>+" . number_format($total_fees_fine_amount, 2, '.', '') . "</span>";
                                                                ?>

                                                            </td>
                                                            <td class="text text-left"></td>
                                                            <td class="text text-left"></td>
                                                            <td class="text text-left"></td>

                                                            <td class="text text-right"><?php
                                                                                        echo ($currency_symbol . number_format($total_discount_amount, 2, '.', ''));
                                                                                        ?></td>
                                                            <td class="text text-right"><?php
                                                                                        echo ($currency_symbol . number_format($total_fine_amount, 2, '.', ''));
                                                                                        ?></td>
                                                            <td class="text text-right"><?php
                                                                                        echo ($currency_symbol . number_format($total_deposite_amount, 2, '.', ''));
                                                                                        ?></td>
                                                            <td class="text text-right"><?php
                                                                                        echo ($currency_symbol . number_format($total_balance_amount, 2, '.', ''));
                                                                                        ?></td>

                                                        </tr>
                                                        <?php
                                                        //=================================
                                                        ?>

                                                    </tbody>
                                                </table>

                                            </div>
                                        <?php
                                        }
                                    } else {
                                        ?>
                                        <div class="alert alert-info">
                                            <?php echo $this->lang->line('no_record_found'); ?>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                    </div>
                <?php
                        }
                ?>
                </div>
            </div>
    </section>
</div>

<script type="text/javascript">
    $(document).ready(function() {

        var class_id = $('#class_id').val();
        var section_id = '<?php echo set_value('section_id', 0) ?>';
        getSectionByClass(class_id, section_id);
    });

    $(document).on('change', '#class_id', function(e) {
        $('#section_id').html("");
        var class_id = $(this).val();
        getSectionByClass(class_id, 0);
    });

    function getSectionByClass(class_id, section_id) {

        if (class_id != "") {
            $('#section_id').html("");
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {
                    'class_id': class_id
                },
                dataType: "json",
                beforeSend: function() {
                    $('#section_id').addClass('dropdownloading');
                },
                success: function(data) {
                    $.each(data, function(i, obj) {
                        var sel = "";
                        if (section_id == obj.section_id) {
                            sel = "selected";
                        }
                        div_data += "<option value=" + obj.section_id + " " + sel + ">" + obj.section + "</option>";
                    });
                    $('#section_id').append(div_data);
                },
                complete: function() {
                    $('#section_id').removeClass('dropdownloading');
                }
            });
        }
    }
    $(document).on('click', '.print', function(e) {

        var $this = $(this);
        var class_id = $this.data('classId');
        var section_id = $this.data('sectionId');
        $.ajax({
            type: "POST",
            url: base_url + 'studentfee/printreportduefees',
            dataType: 'JSON',
            data: {
                'class_id': class_id,
                'section_id': section_id
            }, // serializes the form's elements.
            beforeSend: function() {
                $this.button('loading');
            },
            success: function(response) {
                Popup(response.page);

            },
            error: function(xhr) { // if error occured

                alert("Error occured.please try again");

            },
            complete: function() {
                $this.button('reset');
            }
        });

        e.preventDefault(); // avoid to execute the actual submit of the form.

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
        frameDoc.document.write('<html>');
        frameDoc.document.write('<head>');
        frameDoc.document.write('<title></title>');
        frameDoc.document.write('</head>');
        frameDoc.document.write('<body>');
        frameDoc.document.write(data);
        frameDoc.document.write('</body>');
        frameDoc.document.write('</html>');
        frameDoc.document.close();
        setTimeout(function() {
            document.getElementById('printDiv').contentWindow.focus();
            document.getElementById('printDiv').contentWindow.print();
            $("#printDiv", top.document).remove();
            // frame1.remove();
            if (winload) {
                window.location.reload(true);
            }
        }, 500);


        return true;
    }
</script>