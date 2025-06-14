<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-money"></i> <?php echo $this->lang->line('fees_collection'); ?>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <?php if ($this->session->flashdata('msg')) { ?>
                <?php echo $this->session->flashdata('msg') ?>
            <?php } ?>
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>
                    <div class="box-body">
                        <form role="form" action="<?php echo site_url('admin/feemaster/assign_class/' . $id) ?>" method="post" class="row">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('class'); ?></label>
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
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('section'); ?></label>
                                    <select id="section_id" name="section_id" class="form-control">
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                                </div>
                            </div>
                            <!-- <?php if ($sch_setting->category) { ?>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('category'); ?></label>
                                        <select id="category_id" name="category_id" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($categorylist as $category) {
                                            ?>
                                                <option value="<?php echo $category['id'] ?>" <?php if (set_value('category_id') == $category['id']) echo "selected=selected"; ?>><?php echo $category['category'] ?></option>
                                            <?php
                                                $count++;
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            <?php } ?> -->
                            <!-- <div class="col-sm-2">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('gender'); ?></label>
                                    <select class="form-control" name="gender">
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php
                                        foreach ($genderList as $key => $value) {
                                        ?>
                                            <option value="<?php echo $key; ?>" <?php if (set_value('gender') == $key) echo "selected"; ?>><?php echo $value; ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div> -->
                            <!-- <?php if ($sch_setting->rte) { ?>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('rte'); ?></label>
                                        <select id="rte" name="rte" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($RTEstatusList as $k => $rte) {
                                            ?>
                                                <option value="<?php echo $k; ?>" <?php if (set_value('rte') == $k) echo "selected"; ?>><?php echo $rte; ?></option>

                                            <?php
                                                $count++;
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            <?php } ?> -->
                            <!-- <div class="col-sm-2">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('route'); ?></label>
                                    <select class="form-control" name="vehroute_id">
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php
                                        foreach ($vehroutelist as $vehroute) {
                                        ?>
                                            <optgroup label=" <?php echo $vehroute->route_title; ?>">
                                                <?php
                                                $vehicles = $vehroute->vehicles;
                                                if (!empty($vehicles)) {
                                                    foreach ($vehicles as $key => $value) {
                                                ?>
                                                        <option value="<?php echo $value->vec_route_id ?>" <?php if (set_value('vehroute_id') == $value->vec_route_id) echo "selected"; ?> data-fee="<?php echo $vehroute->fare; ?>">
                                                            <?php echo $value->vehicle_no ?>
                                                        </option>
                                                <?php
                                                    }
                                                }
                                                ?>
                                            </optgroup>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div> -->


                            <div class="col-sm-12">
                                <div class="form-group">
                                    <button type="submit" name="search" value="search_filter" class="btn btn-primary pull-right btn-sm checkbox-toggle"> <?php echo $this->lang->line('submit'); ?></button>
                                </div>
                            </div>
                        </form>

                    </div>
                    <form method="post" action="<?php echo site_url('studentfee/addfeegroup') ?>" id="assign_form">


                        <?php
                        if (!empty($resultlist)) {
                        ?>
                            <div class="box-header ptbnull"></div>
                            <div class="">
                                <div class="box-header with-border">
                                    <h3 class="box-title"><i class="fa fa-users"></i> <?php echo $this->lang->line('assign_fees_group'); ?>
                                        <?php echo form_error('student'); ?></h3>
                                    <div class="box-tools pull-right">
                                    </div>
                                </div>
                                <div class="box-body">
                                    <div class="row">
                                        <div class="">
                                            <!-- <div class="col-md-4">
                        <div class="table-responsive">
                            

                        </div> -->
                                        </div>
                                        <div class="col-md-8">
                                            <div class=" table-responsive">
                                                <table class="table table-striped">
                                                    <tbody>
                                                        <tr>
                                                            <th>Class</th>
                                                            <th>Section</th>
                                                            <th>Action</th>

                                                        </tr>
                                                        <?php
                                                        if (!empty($resultlist)) {

                                                            $count = 1;
                                                            foreach ($resultlist as $class) {
                                                        ?>
                                                                <tr>
                                                                    <td><?php echo $class['class']; ?></td>
                                                                    <td><?php echo $class['section']; ?></td>
                                                                    <td class="mailbox-date pull-center">
                                                                        <a data-placement="top" href="<?php echo base_url(); ?>admin/feemaster/deleteclass_mst/<?php echo $class['classfees_id'] ?>/<?php echo $class['fees_group_id'] ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                                            <i class="fa fa-remove"></i>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                        <?php
                                                            }
                                                            $count++;
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>

                                            </div>
                                            <!-- <button type="submit" class="allot-fees btn btn-primary btn-sm pull-right" id="load" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Please Wait.."><?php echo $this->lang->line('save'); ?>
                        </button> -->

                                            <br />
                                            <br />
                                        </div>
                                    </div>
                                </div>

                            </div>
                </div>
            <?php
                        }
            ?>

            </form>

            </div>
        </div>

</div>

</section>
</div>

<script type="text/javascript">
    //select all checkboxes
    $("#select_all").change(function() { //"select all" change 
        $(".checkbox").prop('checked', $(this).prop("checked")); //change all ".checkbox" checked status
    });

    //".checkbox" change 
    $('.checkbox').change(function() {
        //uncheck "select all", if one of the listed checkbox item is unchecked
        if (false == $(this).prop("checked")) { //if this item is unchecked
            $("#select_all").prop('checked', false); //change "select all" checked status to false
        }
        //check "select all" if all checkbox items are checked
        if ($('.checkbox:checked').length == $('.checkbox').length) {
            $("#select_all").prop('checked', true);
        }
    });

    function getSectionByClass(class_id, section_id) {
        if (class_id != "" && section_id != "") {
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
                success: function(data) {
                    $.each(data, function(i, obj) {
                        var sel = "";
                        if (section_id == obj.section_id) {
                            sel = "selected";
                        }
                        div_data += "<option value=" + obj.section_id + " " + sel + ">" + obj.section + "</option>";
                    });
                    $('#section_id').append(div_data);
                }
            });
        }
    }

    $(document).ready(function() {
        var class_id = $('#class_id').val();
        var section_id = '<?php echo set_value('section_id') ?>';
        getSectionByClass(class_id, section_id);
        $(document).on('change', '#class_id', function(e) {
            $('#section_id').html("");
            var class_id = $(this).val();
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {
                    'class_id': class_id
                },
                dataType: "json",
                success: function(data) {
                    $.each(data, function(i, obj) {
                        div_data += "<option value=" + obj.section_id + ">" + obj.section + "</option>";
                    });
                    $('#section_id').append(div_data);
                }
            });
        });
    });

    // $("#assign_form").submit(function(e) {
    //     if (confirm('<?php echo $this->lang->line('are_you_sure'); ?>')) {
    //         var $this = $('.allot-fees');
    //         $this.button('loading');
    //         $.ajax({
    //             type: "POST",
    //             dataType: 'Json',
    //             url: $("#assign_form").attr('action'),
    //             data: $("#assign_form").serialize(), // serializes the form's elements.
    //             success: function(data) {
    //                 if (data.status == "fail") {
    //                     var message = "";
    //                     $.each(data.error, function(index, value) {

    //                         message += value;
    //                     });
    //                     errorMsg(message);
    //                 } else {
    //                     successMsg(data.message);
    //                 }

    //                 $this.button('reset');
    //             }
    //         });

    //     }
    //     e.preventDefault();

    // });
</script>