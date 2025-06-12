<style type="text/css">
    @media print {

        .no-print,
        .no-print * {
            display: none !important;
        }
    }
    table{
        border-collapse: collapse;
    }
    td{
        border: 1px solid #000 !important;
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
        <?php $this->load->view('reports/_attendance'); ?>
        <div class="row">
            <div class="col-md-12">
                <div class="box removeboxmius">
                    <div class="box-header ptbnull"></div>
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>
                    <form id='form1' action="<?php echo site_url('admin/stuattendence/classattendencereport') ?>" method="post" accept-charset="utf-8">
                        <div class="box-body">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('class'); ?></label><small class="req"> *</small>
                                        <select autofocus="" id="class_id" name="class_id" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($classlist as $class) {
                                            ?>
                                                <option value="<?php echo $class['id'] ?>" <?php
                                                                                            if ($class_id == $class['id']) {
                                                                                                echo "selected =selected";
                                                                                            }
                                                                                            ?>><?php echo $class['class'] ?></option>
                                            <?php
                                                $count++;
                                            }
                                            ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('class_id'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('division'); ?></label><small class="req"> *</small>
                                        <select id="section_id" name="section_id" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('month'); ?></label><small class="req"> *</small>
                                        <select id="month" name="month" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($monthlist as $m_key => $month) {
                                            ?>
                                                <option value="<?php echo $m_key ?>" <?php
                                                                                        if ($month_selected == $m_key) {
                                                                                            echo "selected =selected";
                                                                                        }
                                                                                        ?>><?php echo $month; ?></option>
                                            <?php
                                                $count++;
                                            }
                                            ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('month'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('year'); ?></label>
                                        <select id="year" name="year" class="form-control">

                                            <?php
                                            // $yearlist  = array('2018' => '2018' );
                                            foreach ($yearlist as $y_key => $year) {
                                            ?>
                                                <option value="<?php echo $year["year"] ?>" <?php
                                                                                            if ($year_selected == $year["year"]) {
                                                                                                echo "selected =selected";
                                                                                            }
                                                                                            ?>><?php echo $year["year"]; ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('year'); ?></span>
                                    </div>
                                </div>


                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button type="submit" name="search" value="search" class="btn btn-primary btn-sm pull-right checkbox-toggle"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>


                    <?php
                    if ($this->module_lib->hasActive('student_attendance')) {

                        if (isset($resultlist)) {
                    ?>
                            <div class="" id="attendencelist">
                                <div class="box-header ptbnull"></div>
                                <div class="box-header with-border">
                                    <div class="row">
                                        <div class="col-md-4 col-sm-4">
                                            <h3 class="box-title"><i class="fa fa-users"></i> <?php echo $this->lang->line('student'); ?> <?php echo $this->lang->line('attendance'); ?> <?php echo $this->lang->line('report'); ?></h3>
                                        </div>
                                        <div class="col-md-8 col-sm-8">
                                            <div class="lateday">
                                                <?php
                                                foreach ($attendencetypeslist as $key_type => $value_type) {
                                                ?>
                                                    &nbsp;&nbsp;
                                                    <b>
                                                        <?php
                                                        $att_type = str_replace(" ", "_", strtolower($value_type['type']));
                                                        if (strip_tags($value_type["key_value"]) != "E") {

                                                            echo $this->lang->line($att_type) . ": " . $value_type['key_value'] . "";
                                                        }
                                                        ?>
                                                    </b>
                                                <?php
                                                }
                                                ?>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="box-body table-responsive" id="transfee">
                                    <?php
                                    if (!empty($resultlist)) {
                                    ?>
                                        <div class="mailbox-controls">
                                            <div class="pull-right">
                                            </div>
                                        </div>
                                        <a class="btn btn-default btn-xs pull-right" id="print" onclick="printDiv()"><i class="fa fa-print"></i></a> <button class="btn btn-default btn-xs pull-right" id="btnExport" onclick="fnExcelReport();"> <i class="fa fa-file-excel-o"></i> </button>
                                        <div class="download_label"><?php echo $this->lang->line('student'); ?> <?php echo $this->lang->line('attendance'); ?> <?php echo $this->lang->line('report') . " ";
                                                                                                                                                                $this->customlib->get_postmessage();
                                                                                                                                                                ?>
                                        </div>
                                        <table class="table table-striped table-bordered table-hover" id="headerTable">
                                            <thead>
                                                <tr>
                                                    <th><?php echo $this->lang->line('roll_no'); ?></th>
                                                    <th>
                                                        <?php echo $this->lang->line('student'); ?> / <?php echo $this->lang->line('date'); ?>
                                                    </th>
                                                    <th><br /><span data-toggle="tooltip" title="<?php echo "Gross Present Percentage(%)"; ?>">%</span></th>

                                                    <?php
                                                    foreach ($attendencetypeslist as $key => $value) {
                                                        if (strip_tags($value["key_value"]) != "E") {
                                                    ?>
                                                            <th colspan=""><span data-toggle="tooltip" title="<?php echo "Total " . $value["type"]; ?>"><?php echo strip_tags($value["key_value"]); ?>

                                                                </span></th>

                                                    <?php
                                                        }
                                                    }
                                                    ?>
                                                    <?php
                                                    foreach ($attendence_array as $at_key => $at_value) {
                                                        if (date('D', $this->customlib->dateyyyymmddTodateformat($at_value)) == "Sun") {
                                                    ?>
                                                            <th class="tdcls text text-center bg-danger">
                                                                <?php
                                                                echo date('d', $this->customlib->dateyyyymmddTodateformat($at_value)) . "<br/>" .
                                                                    date('D', $this->customlib->dateyyyymmddTodateformat($at_value));
                                                                ?>
                                                            </th>
                                                        <?php
                                                        } else {
                                                        ?>
                                                            <th class="tdcls text text-center">
                                                                <?php
                                                                echo date('d', $this->customlib->dateyyyymmddTodateformat($at_value)) . "<br/>" .
                                                                    date('D', $this->customlib->dateyyyymmddTodateformat($at_value));
                                                                ?>
                                                            </th>
                                                    <?php
                                                        }
                                                    }
                                                    ?>
                                                    <th><?php echo $this->lang->line('total')." Working Day"; ?></th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (empty($student_array)) {
                                                ?>
                                                    <tr>
                                                        <td colspan="32" class="text-danger text-center"><?php echo $this->lang->line('no_record_found'); ?></td>
                                                    </tr>
                                                    <?php
                                                } else {
                                                    $row_count = 1;
                                                    $i = 0;


                                                    foreach ($student_array as $student_key => $student_value) {
                                                        $admission_date = $student_value['admission_date'];
                                                        $admission_timestamp = strtotime($admission_date);
                                                        $this->db->select('COUNT(*) as count');
                                                        $this->db->from('student_attendences');
                                                        $this->db->where('student_session_id', $student_value['student_session_id']);
                                                        $this->db->where('MONTH(date)', date('m', strtotime($month_selected)));
                                                        $this->db->where('YEAR(date)', $year_selected);
                                                        $this->db->where('attendence_type_id !=', 5);
                                                        $query = $this->db->get();
                                                        
                                                        $result1 = $query->row();  // Get the result row
                                                        $workingDays = 0;
                                                        foreach ($attendence_array as $at_key => $at_value) {
                                                            $attendance_date = $at_value; // assuming 'date' key holds the date in format YYYY-MM-DD
                                                            $attendance_timestamp = strtotime($attendance_date);
                                                            if ($attendance_timestamp >= $admission_timestamp) {
                                                                // If it's a working day (assuming working days are weekdays: Monday to Friday)
                                                                $day_of_week = date('N', $attendance_timestamp); // 1 (for Monday) through 7 (for Sunday)
                                                                
                                                                if ($day_of_week <= 5) { // Only count weekdays (1 to 5 are Monday to Friday)
                                                                    $workingDays++;
                                                                }
                                                            }
                                                        }
                                                        $working_days = $result1->count;
                                                        // if ($workingDays == 0) {
                                                        //     $working_days = $workingDays;
                                                        // }elseif($monthAttendance[$i][$student_value['student_session_id']]['present'] == 0){
                                                        //     $working_days = 0;
                                                        // }else{
                                                        //     $working_days = $working_days;
                                                        // }
                                                        // if ($userdata["id"] == 1) {
                                                        //     echo "<pre>";
                                                        //     print_r($student_value);
                                                        //     echo "</pre>";
                                                            
                                                        // }

                                                    ?>
                                                        <tr>
                                                            <td><?php echo $student_value['roll_no']; ?></td>
                                                            <td class="tdclsname">
                                                                <span data-toggle="popover" class="detail_popover" data-original-title="" title=""><a href="#" style="color:#333"><?php echo $this->customlib->getFullName($student_value['firstname'], $student_value['middlename'], $student_value['lastname'], $sch_setting->middlename, $sch_setting->lastname); ?></a></span>
                                                                <div class="fee_detail_popover" style="display: none"><?php echo "Admission No: " . $student_value['admission_no']; ?></div>
                                                            </td>
                                                            <td><?php
                                                                $total_present = ($monthAttendance[$i][$student_value['student_session_id']]['present'] + $monthAttendance[$i][$student_value['student_session_id']]['late_with_excuse'] + $monthAttendance[$i][$student_value['student_session_id']]['half_day'] + $monthAttendance[$i][$student_value['student_session_id']]['late']);
                                                                $month_number = date("m", strtotime($month_selected));
                                                                $num_of_days = cal_days_in_month(CAL_GREGORIAN, $month_number, date("Y"));
                                                                $total_school_days = $monthAttendance[$i][$student_value['student_session_id']]['present'] + $monthAttendance[$i][$student_value['student_session_id']]['late_with_excuse'] + $monthAttendance[$i][$student_value['student_session_id']]['late'] + $monthAttendance[$i][$student_value['student_session_id']]['half_day'] + $monthAttendance[$i][$student_value['student_session_id']]['absent'];
                                                                if ($total_school_days == 0) {
                                                                    $percentage = -1;
                                                                    $print_percentage = "-";
                                                                } else {

                                                                    $percentage = ($total_present / $total_school_days) * 100;
                                                                    $print_percentage = round($percentage, 0);
                                                                }

                                                                if (($percentage < 75) && ($percentage >= 0)) {
                                                                    $label = "class='label label-danger'";
                                                                } else if ($percentage > 75) {

                                                                    $label = "class='label label-success'";
                                                                } else {

                                                                    $label = "class='label label-default'";
                                                                }
                                                                echo "<label $label>" . $print_percentage . "</label>";
                                                                ?></td>
                                                            <?php //if($working_days != 0 && $monthAttendance[$i][$student_value['student_session_id']]['present'] != 0){ ?>
                                                            <td><?php print_r($monthAttendance[$i][$student_value['student_session_id']]['present']); ?></td>
                                                            <!-- <td><?php //print_r($monthAttendance[$i][$student_value['student_session_id']]['late'] + $monthAttendance[$i][$student_value['student_session_id']]['late_with_excuse']); ?></td> -->
                                                            <td><?php print_r($monthAttendance[$i][$student_value['student_session_id']]['late']); ?></td>
                                                            <td><?php print_r($monthAttendance[$i][$student_value['student_session_id']]['absent']); ?></td>
                                                            <td><?php print_r($monthAttendance[$i][$student_value['student_session_id']]['holiday']); ?></td>
                                                            <td><?php print_r($monthAttendance[$i][$student_value['student_session_id']]['half_day']); ?></td>
                                                            <td><?php print_r($monthAttendance[$i][$student_value['student_session_id']]['late_with_excuse']); ?></td>
                                                            <?php /* }else{ ?>
                                                            <td>0</td>
                                                            <!-- <td>0</td> -->
                                                            <td>0</td>
                                                            <td>0</td>
                                                            <td>0</td>
                                                            <td>0</td>
                                                            <td>0</td>
                                                            <?php } */?>
                                                            <?php
                                                            $holiday_count = 0;
                                                            $workingDays = 0;
                                                            foreach ($attendence_array as $at_key => $at_value) {
                                                                $attendance_date = $at_value; // assuming 'date' key holds the date in format YYYY-MM-DD
                                                                $attendance_timestamp = strtotime($attendance_date);
                                                                if ($attendance_timestamp >= $admission_timestamp) {
                                                                    // If it's a working day (assuming working days are weekdays: Monday to Friday)
                                                                    $day_of_week = date('N', $attendance_timestamp); // 1 (for Monday) through 7 (for Sunday)
                                                                    
                                                                    if ($day_of_week <= 5) { // Only count weekdays (1 to 5 are Monday to Friday)
                                                                        $workingDays++;
                                                                    }
                                                                }
                                                            ?>
                                                                <td class="tdcls text text-center">

                                                                    <span data-toggle="popover" class="detail_popover" data-original-title="" title="">
                                                                        <a href="#" style="color:#333"><?php
                                                                                                        echo $resultlist[$at_value][$student_value['admission_date']]['key'];
                                                                                                        if (strip_tags($resultlist[$at_value][$student_value['student_session_id']]['key']) == "E") {

                                                                                                            $attendence_key = "L";
                                                                                                            $remark = "Late With Excuse";
                                                                                                        } else {
                                                                                                            
                                                                                                            // echo "<pre>";
                                                                                                            // print_r ($student_value);
                                                                                                            // echo "</pre>";
                                                                                                            
                                                                                                            // echo $resultlist[$student_value['admission_date']]['key'];
                                                                                                            if ($resultlist[$at_value][$student_value['admission_date']]['key'] > $at_value) {
                                                                                                                
                                                                                                                $attendence_key = [];
                                                                                                            } else {
                                                                                                                $attendence_key = $resultlist[$at_value][$student_value['student_session_id']]['key'];
                                                                                                                
                                                                                                            }
                                                                                                            
                                                                                                            $remark = $resultlist[$at_value][$student_value['student_session_id']]['remark'];
                                                                                                        }
                                                                                                        if ($attendence_key == "H") {
                                                                                                            $holiday_count++;
                                                                                                        }
                                                                                                        print_r($attendence_key);
                                                                                                        ?></a></span>
                                                                    <div class="fee_detail_popover" style="display: none"><?php echo $remark; ?></div>

                                                                </td>

                                                                
                                                                
                                                                <?php }
                                                            ?>


                                                        <?php
                                                            
                                                            $i++;
                                                            ?>
                                                            <td><?php echo $working_days ; ?></td>
                                                            <!-- <td><?php //echo $num_of_days - $sunday_count - $holiday_count; ?></td> -->


                                                        </tr>
                                                <?php
                                                    }
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    <?php
                                    } else {
                                    ?>
                                        <div class="alert alert-info">
                                            <?php echo $this->lang->line('no_attendance_prepare'); ?>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                </div><!-- ./box box-primary -->
        <?php
                        }
                    }
        ?>
            </div>
        </div>
    </section>
</div>


<script type="text/javascript">
    $(document).ready(function() {

        $('.detail_popover').popover({
            placement: 'right',
            title: '',
            trigger: 'hover',
            container: 'body',
            html: true,
            content: function() {
                return $(this).closest('th').find('.fee_detail_popover').html();
            }
        });

        var section_id_post = '<?php echo $section_id; ?>';
        var class_id_post = '<?php echo $class_id; ?>';
        populateSection(section_id_post, class_id_post);

        function populateSection(section_id_post, class_id_post) {
            $('#section_id').html("");
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {
                    'class_id': class_id_post
                },
                dataType: "json",
                success: function(data) {
                    $.each(data, function(i, obj) {
                        var select = "";
                        if (section_id_post == obj.section_id) {
                            var select = "selected=selected";
                        }
                        div_data += "<option value=" + obj.section_id + " " + select + ">" + obj.section + "</option>";
                    });
                    $('#section_id').append(div_data);
                }
            });
        }
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
        var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy',]) ?>';
        $('#date').datepicker({
            format: date_format,
            autoclose: true
        });
    });
</script>
<script type="text/javascript">
    var base_url = '<?php echo base_url() ?>';

    function printDiv() {
        document.getElementById("print").style.display = "none";
        document.getElementById("btnExport").style.display = "none";
        var divElements = document.getElementById('transfee').innerHTML;
        var oldPage = document.body.innerHTML;
        document.body.innerHTML =
            "<html><head><title></title></head><body><h3 style='text-align:center;'>Sree Narayana Guru Central School</h3>" +
            divElements + "</body>";
        window.print();
        document.body.innerHTML = oldPage;

        location.reload(true);
    }
    // function Popup(data) {

    //     var frame1 = $('<iframe />');
    //     frame1[0].name = "frame1";
    //     frame1.css({
    //         "position": "absolute",
    //         "top": "-1000000px"
    //     });
    //     $("body").append(frame1);
    //     var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
    //     frameDoc.document.open();
    //     //Create a new HTML document.
    //     frameDoc.document.write('<html>');
    //     frameDoc.document.write('<head>');
    //     frameDoc.document.write('<title></title>');
    //     frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/bootstrap/css/bootstrap.min.css">');
    //     frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/font-awesome.min.css">');
    //     frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/ionicons.min.css">');
    //     frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/AdminLTE.min.css">');
    //     frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/skins/_all-skins.min.css">');
    //     frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/iCheck/flat/blue.css">');
    //     frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/morris/morris.css">');


    //     frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/jvectormap/jquery-jvectormap-1.2.2.css">');
    //     frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/datepicker/datepicker3.css">');
    //     frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/daterangepicker/daterangepicker-bs3.css">');
    //     frameDoc.document.write('</head>');
    //     frameDoc.document.write('<body>');
    //     frameDoc.document.write(data);
    //     frameDoc.document.write('</body>');
    //     frameDoc.document.write('</html>');
    //     frameDoc.document.close();
    //     setTimeout(function() {
    //         window.frames["frame1"].focus();
    //         window.frames["frame1"].print();
    //         frame1.remove();
    //     }, 500);


    //     return true;
    // }

    // function fnExcelReport() {
    //     var tab_text = "<table border='2px'><tr >";
    //     var textRange;
    //     var j = 0;
    //     tab = document.getElementById('headerTable'); // id of table

    //     for (j = 0; j < tab.rows.length; j++) {
    //         tab_text = tab_text + tab.rows[j].innerHTML + "</tr>";
    //         //tab_text=tab_text+"</tr>";
    //     }

    //     tab_text = tab_text + "</table>";
    //     tab_text = tab_text.replace(/<A[^>]*>|<\/A>/g, ""); //remove if u want links in your table
    //     tab_text = tab_text.replace(/<img[^>]*>/gi, ""); // remove if u want images in your table
    //     tab_text = tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params

    //     var ua = window.navigator.userAgent;
    //     var msie = ua.indexOf("MSIE ");

    //     if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) // If Internet Explorer
    //     {
    //         txtArea1.document.open("txt/html", "replace");
    //         txtArea1.document.write(tab_text);
    //         txtArea1.document.close();
    //         txtArea1.focus();
    //         sa = txtArea1.document.execCommand("SaveAs", true, "Say Thanks to Sumit.xls");
    //     } else //other browser not tested on IE 11
    //         sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));
    //     return (sa);
    // }
    function fnExcelReport() {
        var tab_text = "<table border='2px'><tr>";
        var tab;
        var j = 0;

        // Get the table by its ID
        tab = document.getElementById('headerTable'); // Ensure this ID is correct

        if (!tab) {
            console.error("Table with ID 'headerTable' not found!");
            return;
        }

        // Iterate through rows of the table and build the HTML content
        for (j = 0; j < tab.rows.length; j++) {
            tab_text += "<tr>" + tab.rows[j].innerHTML + "</tr>";
        }

        // Close the table tag
        tab_text += "</table>";

        // Clean up content: remove links, images, and input elements if needed
        tab_text = tab_text.replace(/<A[^>]*>|<\/A>/g, ""); // Remove links
        tab_text = tab_text.replace(/<img[^>]*>/gi, ""); // Remove images
        tab_text = tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // Remove input elements

        // Check for Internet Explorer
        var ua = window.navigator.userAgent;
        var msie = ua.indexOf("MSIE ");
        var trident = ua.indexOf('Trident/');

        if (msie > 0 || trident > 0) {
            // For IE browsers (including IE 11)
            var txtArea1 = document.createElement('iframe'); // Create an iframe
            txtArea1.style.position = "absolute";
            txtArea1.style.width = "0px";
            txtArea1.style.height = "0px";
            document.body.appendChild(txtArea1);

            txtArea1.document.open("txt/html", "replace");
            txtArea1.document.write(tab_text);
            txtArea1.document.close();
            txtArea1.focus();
            var sa = txtArea1.document.execCommand("SaveAs", true, "Say Thanks to Sumit.xls");

            if (sa) {
                document.body.removeChild(txtArea1); // Remove the iframe after saving
            }

        } else {
            // For other modern browsers
            var blob = new Blob([tab_text], { type: "application/vnd.ms-excel" });
            var link = document.createElement("a");
            link.href = URL.createObjectURL(blob);
            link.download = "attendence_report.xls";
            link.click();
        }

        return true;
    }

</script>