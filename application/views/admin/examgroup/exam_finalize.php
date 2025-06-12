<style>
    .print-table {
        font-family: arial, sans-serif;
        border-collapse: collapse;
        width: 96%;
        font-size: 7px;
        /* justify-content: center; */
        /* display: flex; */
    }
    .w-50 {
        width: 50%;
    }
    .print-table>td,
    th {
        border: 1px solid #9f9c9c;
        text-align: left;
        padding: 3px;
    }
    .wt {
        width: 100px;
    }
    .bg {
        background-color: #e3dede;
    }
</style>
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-map-o"></i> <?php echo $this->lang->line('examinations'); ?> <small><?php echo $this->lang->line('student_fee1'); ?></small>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>
                    <div class="box-body">
                        <!--<form role="form" action="<?php echo site_url('admin/examgroup/exam_finalize') ?>" method="post" class="row"> -->   
                        <form role="form" id="myform" method="post" class="row">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="col-sm-6 col-lg-4 col-md-4">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('class'); ?></label><small class="req"> *</small>
                                    <select id="class_id" name="class_id" class="form-control">
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php
                                        foreach ($classlist as $class) {
                                        ?>
                                            <option value="<?php echo $class['id'] ?>" <?php
                                                                                        if (set_value('class_id') == $class['id']) {
                                                                                            echo "selected=selected";
                                                                                        }
                                                                                        ?>><?php echo $class['class'] ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('class_id'); ?></span>
                                </div>
                            </div>

                            <div class="col-sm-6 col-lg-4 col-md-4">
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('division'); ?></label><small class="req"> *</small>
                                    <select id="section_id" name="section_id" class="form-control">
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-4 col-md-4">
                                <div class="checkbox-inline">
                                    <label>
                                        <input type="checkbox" value="1" name="reset_subject">Reset Subjects
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-4 col-md-4">
                                <div class="checkbox-inline">
                                    <label>
                                        <input type="checkbox" value="1" name="verification_mode">Verification Mode
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <button type="submit" name="search" value="search_filter" class="btn btn-primary pull-right btn-sm checkbox-toggle"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                </div>
                            </div>
                        </form>

                    </div>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('.select2').select2();

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

</script>
<script>
$(document).on('submit', 'form#myform', function(e) {
    e.preventDefault();
        var form = $(this);
        var subsubmit_button = $(this).find(':submit');
        var formdata = form.serializeArray();    
    $.ajax({
                type: "POST",
                url: "<?php echo site_url('admin/examgroup/class_exam_finalize'); ?>",
                data: formdata, // serializes the form's elements.
                dataType: "JSON", // serializes the form's elements.
                beforeSend: function() {
                    subsubmit_button.button('loading');
                },
                success: function(res) {
                if (res.status == "success") {
                    successMsg(res.message);

                } else {
                    errorMsg(res.message);
                }
                },
                error: function(xhr) { // if error occured

                    alert("Error occured.please try again");
                    subsubmit_button.button('reset');
                },
                complete: function() {
                    subsubmit_button.button('reset');
                }
            });
});    
</script>
<script>
    $(document).on('submit', 'form#printMarksheet', function(e) {

        e.preventDefault();
        var form = $(this);
        var subsubmit_button = $(this).find(':submit');
        var formdata = form.serializeArray();

        var list_selected = $('form#printMarksheet input[name="exam_group_class_batch_exam_student_id[]"]:checked').length;
        if (list_selected > 0) {
            $.ajax({
                type: "POST",
                url: form.attr('action'),
                data: formdata, // serializes the form's elements.
                dataType: "JSON", // serializes the form's elements.
                beforeSend: function() {
                    subsubmit_button.button('loading');
                },
                success: function(response) {
                    Popup(response.page);
                },
                error: function(xhr) { // if error occured

                    alert("Error occured.please try again");
                    subsubmit_button.button('reset');
                },
                complete: function() {
                    subsubmit_button.button('reset');
                }
            });
        } else {
            confirm("<?php echo $this->lang->line('please_select_student'); ?>");
        }
    });


    $(document).on('click', '#select_all', function() {
        $(this).closest('table').find('td input:checkbox').prop('checked', this.checked);
    });
</script>
