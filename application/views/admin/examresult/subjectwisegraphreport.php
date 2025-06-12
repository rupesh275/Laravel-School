<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1>
            <i class="fa fa-map-o"></i> <?php echo $this->lang->line('examinations'); ?> <small><?php echo $this->lang->line('student_fee1'); ?></small>
        </h1>
    </section>
    <!-- Main content -->

    <section class="content">
        <?php $this->load->view('reports/_examinations'); ?>
        <div class="row">
            <div class="col-md-12">
                <div class="box removeboxmius">
                    <div class="box-header ptbnull"></div>
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria') . ""; ?></h3>
                    </div>
                    <div class="box-body">
                        <form role="form" action="<?php echo site_url('admin/examresult/subjectwisereport') ?>" method="post">

                            <?php echo $this->customlib->getCSRF(); ?>

                            <div class="row">

                                <div class="col-sm-6 col-lg-3 col-md-12 col20">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('class'); ?></label><small class="req"> *</small>
                                        <select autofocus="" id="class_id" name="class_id" class="form-control select2">
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

                                <div class="col-sm-6 col-lg-3 col-md-12 col20">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('section'); ?></label><small class="req"> </small>
                                        <select id="section_id" name="section_id" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <button type="submit" name="search" value="search_filter" class="btn btn-primary pull-right btn-sm checkbox-toggle"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- graph start -->
                    <div class="box-body">
                        <div class="row">
                            <?php if (!empty($exams)) {
                                $i = 0;
                                foreach ($exams as  $exam) {

                            ?>
                                    <div class="col-lg-6 col-md-6 col-sm-6 ">

                                        <div class="box box-primary borderwhite">
                                            <div class="box-header with-border">
                                                <h3 class="box-title"><?php echo $exam['exam']; ?></h3>
                                                <div class="box-tools pull-right">
                                                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                                    <!-- <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button> -->
                                                </div>
                                            </div>

                                            <div class="box-body">
                                                <div class="chart">
                                                    <canvas id="barChart_<?php echo $i; ?>"></canvas>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                            <?php $i++;
                                }
                            } ?>
                        </div>
                    </div>
                    <!-- graph end -->
                </div>


    </section>
</div>



<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
<!--  graph start -->

<script type="text/javascript">
    $(document).ready(function() {
        $('.select2').select2();

    });

    var class_id = '<?php echo set_value('class_id') ?>';
    var section_id = '<?php echo set_value('section_id') ?>';
    getSectionByClass(class_id, section_id);



    $(document).on('change', '#class_id', function(e) {
        $('#section_id').html("");
        var class_id = $(this).val();
        getSectionByClass(class_id, 0);
    });

    function getSectionByClass(class_id, section_id) {

        if (class_id !== "") {
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
                        if (section_id === obj.section_id) {
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

    <?php 
    if(!empty($exam)){
    $i = 0;
    foreach ($exams as  $exam) {
        $subjectsarray = $this->examgroup_model->getstudentSubjectslist2($exam['id']);
        $subjects = [];
        $datas = [];
        foreach ($subjectsarray as $subjectRow) {

            if (!empty($subjectRow['main_sub'])) {
                $subject = $this->subject_model->getSubjectByID($subjectRow['main_sub']);
                $status = $this->examgroupstudent_model->getClassExamMainSubjectStatus($class_id,$section_id,$exam['id'],$subjectRow['main_sub']);
                $subjects[] = $subject['code'];
                $datas[] = $status;
            }
            
        }
        $bar_id = 'barChart_' . $i;
        $labelsArr = "['" . implode("', '", $subjects) . "']";
        $dataArr = "['" . implode("', '", $datas) . "']";

        
    ?>
        bar_chart(<?php echo "'".$bar_id."'"; ?>, <?php echo $labelsArr; ?>, <?php echo $dataArr; ?>);

        <?php

        ?>
    <?php $i++;
    }} ?>

    function bar_chart(bar_id, labelsArr, dataArr) {
        var ctx = document.getElementById(bar_id).getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labelsArr,
                datasets: [{
                    label: ' Status ',
                    barPercentage: 0.9,
                    data: dataArr,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgb(75, 192, 192)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
    }
</script>
<!-- graph end -->