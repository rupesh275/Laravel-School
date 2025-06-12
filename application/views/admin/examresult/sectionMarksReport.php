<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1>
            <i class="fa fa-map-o"></i> <?php echo $this->lang->line('examinations'); ?> <small><?php echo $this->lang->line('student_fee1'); ?></small>
        </h1>
    </section>
    <!-- Main content -->
    <style>
        .chart {
            margin: auto;
            height: 100vh;
            width: 55vw;
        }
    </style>
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
                        <form role="form" action="<?php echo site_url('admin/examresult/sectionMarksReport') ?>" method="post">

                            <?php echo $this->customlib->getCSRF(); ?>

                            <div class="row">

                                <div class="col-sm-6 col-lg-3 col-md-12 col20">
                                    <div class="form-group">
                                        <label><?php echo "Sections"; ?></label><small class="req"> *</small>
                                        <select autofocus="" id="sch_section" name="sch_section" class="form-control select2">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($sectionlist as $sectionRow) {
                                            ?>
                                                <option value="<?php echo $sectionRow['id'] ?>" <?php
                                                                                                if (set_value('sch_section') == $sectionRow['id']) {
                                                                                                    echo "selected=selected";
                                                                                                }
                                                                                                ?>><?php echo $sectionRow['sch_section'] ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('sch_section'); ?></span>
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




                </div>


            </div>
        </div>
        <?php if (!empty($classList)) {

        ?>
            <!-- graph start -->
            <div class="box-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 ">

                        <div class="box box-primary borderwhite">
                            <div class="box-header with-border">
                                <h3 class="box-title"><?php echo  " Result Analysis " . $this->lang->line('report') . " (" . $sch_section_name . " Section)"; ?></h3>
                                <div class="box-tools pull-right">
                                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                </div>
                            </div>

                            <div class="box-body">
                                <div class="chart">
                                    <canvas  id="pieChart"></canvas>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
            <!-- graph end -->
        <?php
        } else {
        ?>
            <div class="alert alert-info">
                <strong>Sorry No data Available </strong>
            </div>
        <?php
        }
        ?>
    </section>
</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

    var ctx = document.getElementById("pieChart").getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Less than 40%', '40% - 60%', '60% - 70%', 'above 70%'],
            datasets: [{
                label: 'Students',
                data: [<?php echo $first; ?>, <?php echo $second; ?>, <?php echo $third; ?>, <?php echo $fourth; ?>],
                backgroundColor: ['rgba(255, 99, 132, 0.2)', 'rgb(54, 162, 235)', 'rgb(255, 205, 86)', 'rgb(82, 255, 160)'],
                hoverOffset: 4,
                borderColor: 'rgba(255,99,132,1)',
                borderWidth: 1,
                borderAlign: 'center',
                rotation: 2,
                weight: 2,
                

            }]
        },
        options: {
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        font: {
                            size: 25
                        }
                    }
                },



            }
        }
    });
</script>