<style type="text/css">
    @media print {
        .no-print,
        .no-print * {
            display: none !important;
        }
    }
</style>
<div class="content-wrapper" style="min-height: 946px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-calendar-check-o"></i> <?php echo $this->lang->line('attendance'); ?> <small> <?php echo $this->lang->line('by_date1'); ?></small>
        </h1>
    </section>
    <section class="content">
        <?php $this->load->view('reports/_examinations'); ?>
        <div class="row">
            <div class="col-md-12">
                <div class="box removeboxmius">
                    <div class="box-header ptbnull"></div>


                    <div class="box-header ptbnull"></div>
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><i class="fa fa-money"></i> <?php echo form_error('student'); ?> <?php echo $this->lang->line('marks') . "-" . $this->lang->line('report'); ?></h3>
                    </div>
                    <div class="box-body table-responsive">
                        <div class="download_label">Marks Report</div>


                        <?php
                        $i = 0;
                        //echo "<pre>";
                        if (!empty($class_section_list)) {
                            foreach ($class_section_list as $class_section_value) {
                                // print_r($class_section_value);
                                $exams = $this->examgroup_model->getexam_new($class_section_value->class_id, $class_section_value->section_id);
                                 //print_r($exams);

                        ?>
                                <div class="panel-group" id="accordion">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse_<?php echo $i; ?>"><?php echo $class_section_value->class . " (" . $class_section_value->section . ")" ?></a>
                                                <?php 
                                               $totalstudent = $this->student_model->getstudentbyclass_section($class_section_value->class_id,$class_section_value->section_id,$session_id)->result_array();

                                               $studentids = array_column($totalstudent, 'studentses_id');
                                               
                                            //    print_r($studentids);
                                                ?>
                                            </h4>
                                        </div>
                                        <div id="collapse_<?php echo $i; ?>" class="panel-collapse collapse">
                                            <div class="row">
                                                <table class="table table-bordered">
                                                    <th>
                                                        <tr>
                                                            <td style="padding-left: 17px;">
                                                                <h5><b>Title</b></h5>
                                                            </td>
                                                            <td>
                                                                <h5><b>Percentage(%)</b></h5>
                                                            </td>
                                                        </tr>

                                                    </th>
                                                    <?php if (!empty($exams)) {
                                                        foreach ($exams as $value) {
                                                            $examname = $this->examgroup_model->getExamByID($value['exam_group_class_batch_exam_id']);
                                                            $studentsCount = $this->examgroup_model->getcountstudents($value['exam_group_class_batch_exam_id'])->num_rows();
                                                    ?>
                                                            <tr>
                                                                <td style="padding-left: 17px;"><?php echo $examname->exam; ?>
                                                            <input type="hidden" name="exam_id" value="<?php echo $examname->id;?>">
                                                            </td>
                                                                <td><?php
                                                                        if (!empty($studentids)) {
                                                                            
                                                                            $studentsassign = $this->examgroup_model->getstudentassign($studentids)->num_rows();
                                                                            $assignedstu = $this->examgroup_model->getstudentassign($studentids)->result_array();
                                                                            $nostudent = array_column($assignedstu, 'id');
                                                                       
                                                                    // print_r($studentids); 
                                                                    // print_r($nostudent); 
                                                                    
                                                                    // echo '<br>';
                                                                    $studentsMark = $this->examgroup_model->getMarkEntry($nostudent,$examname->id)->num_rows();
                                                                    // print_r($this->db->last_query());
                                                                    // print_r($studentsMark); echo '=> ';
                                                                    if ($studentsMark > 0) {
                                                                        // $avg = ($studentsassign * 100) / $studentsMark;
                                                                        $avg = ($studentsMark * 100) / $studentsassign;
                                                                        echo $avg . ' %';
                                                                    } else {
                                                                        echo 0;
                                                                    }
                                                                }
                                                                    ?></td>

                                                            </tr>

                                                    <?php
                                                        }
                                                    } ?>

                                                </table>

                                            </div>
                                        </div>
                                    </div>
                                </div>


                        <?php
                                $i = $i + 1;
                            }
                        }
                        ?>

                    </div>
                </div>

            </div>
        </div>
    </section>
</div>

<script>
    $(document).on('change', '#class_id', function(e) {

        $('#section_id').html("");
        var class_id = $(this).val();

        var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
        var url = "";
        $.ajax({
            type: "GET",
            url: baseurl + "sections/getByClass",
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
</script>
<script type="text/javascript">
    $(document).ready(function() {
        $(document).on('submit', '#marksform', function(e) {
            // e.preventDefault(); // avoid to execute the actual submit of the form.
            var $this = $(this).find("button[type=submit]:focus");
            var form = $(this);
            var url = form.attr('action');
            var form_data = form.serializeArray();
            // form_data.push({name: 'search_type', value: $this.attr('value')});

            $.ajax({
                url: url,
                type: "POST",
                dataType: 'JSON',
                data: form_data, // serializes the form's elements.
                beforeSend: function() {
                    $('[id^=error]').html("");
                    $this.button('loading');
                    resetFields($this.attr('name'));
                },
                success: function(response) { // your success handler

                    if (!response.status) {
                        $.each(response.error, function(key, value) {
                            $('#error_' + key).html(value);
                        });
                    } else {

                        initDatatable('record-list', 'report/dtclasssubjectreport', response.params);
                    }
                },
                error: function() { // your error handler
                    $this.button('reset');
                },
                complete: function() {
                    $this.button('reset');
                }
            });

        });

    });

    function resetFields(search_type) {
        if (search_type == "keyword_search") {
            $('#class_id').prop('selectedIndex', 0);
            $('#section_id').find('option').not(':first').remove();
        } else if (search_type == "class_search") {

            $('#search_text').val("");
        }
    }
</script>