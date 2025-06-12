<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1>
            <i class="fa fa-line-chart"></i> <?php echo $this->lang->line('reports'); ?> <small> <?php echo $this->lang->line('filter_by_name1'); ?></small>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <?php $this->load->view('reports/_studentinformation'); ?>
        <div class="row">
            <div class="col-md-12">

                <div class="box removeboxmius">

                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><i class="fa fa-users"></i> <?php echo "Class Wise Student Strength Report"; ?> </h3>
                    </div>
                    <div class="box-body table-responsive" id="transfee">
                    <div id="printhead">
                            <center>
                                <h5><?php echo "Class Wise Student Strength Report"; ?></h5>
                            </center>
                                </div>
                        <?php

                        if (!empty($class_section_list)) {
                        ?>
                            <div class="download_label"><?php echo "Class Wise Student Strength Report"; ?></div>
                            <a class="btn btn-default btn-xs pull-right" id="print" onclick="printDiv()"><i class="fa fa-print"></i></a>
                            <a class="btn btn-default btn-xs pull-right" id="btnExport" onclick="exportToExcel();"> <i class="fa fa-file-excel-o"></i> </a>
                            <table class="table table-striped table-bordered table-hover exxxample" id="headerTable">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('s_no'); ?></th>
                                        <th style="text-align:center;"><?php echo $this->lang->line('class'); ?></th>
                                        <th style="text-align:center;"><?php echo $this->lang->line('students'); ?></th>
                                        <th style="text-align:center;"><?php echo "Total Boys"; ?></th>
                                        <th style="text-align:center;"><?php echo "Total Girls"; ?></th>
                                        <th class="text text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $count = 1;
                                    $totalStudent = 0;
                                    $totalBoys = 0;
                                    $totalGirls = 0;
                                    // echo "<pre>";
                                    // print_r($class_section_list);die();
                                    foreach ($class_section_list as $class_section_key => $class_section_value) {
                                        $studentCount = $this->student_model->getcountBygender($class_section_value->class_id,$class_section_value->section_id);
                                        $totalGirl = $class_section_value->student_count - $studentCount;
                                        $totalStudent += $class_section_value->student_count;
                                        $totalBoys += $studentCount;
                                        $totalGirls += $totalGirl;
                                    ?>
                                        <tr>
                                            <td><?php echo $count; ?></td>
                                            <td style="text-align:center;"><?php echo $class_section_value->class . " (" . $class_section_value->section . ")" ?></td>
                                            <td style="text-align:center;"><?php echo $class_section_value->student_count; ?></td>
                                            <td style="text-align:center;"><?php echo $studentCount;?></td>
                                            <td style="text-align:center;"><?php echo $totalGirl;?></td>
                                            <td class="text text-right">

                                                <button type="button" class="btn btn-default btn-xs studentlist" id="load" data-toggle="tooltip" data-clssection-id="<?php echo $class_section_value->id; ?>" title="<?php echo $this->lang->line('view') . ' ' . $this->lang->line('students'); ?>" data-loading-text="<i class='fa fa-spinner fa-spin'></i>"><i class="fa fa-eye"></i></button>
                                            </td>
                                        </tr>
                                    <?php
                                        $count += 1;
                                    }

                                    ?>
                                    <tr>
                                        <td></td>
                                        <td style="text-align:center;"><b>Total</b></td>
                                        <td style="text-align:center;"><b><?php echo $totalStudent;?></b></td>
                                        <td style="text-align:center;"><b><?php echo $totalBoys;?></b></td>
                                        <td style="text-align:center;"><b><?php echo $totalGirls;?></b></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        <?php
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
            <!--./box box-primary -->
        </div><!-- ./col-md-12 -->
    </section>
</div>

<div id="studentModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-xl">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('student') . ' ' . $this->lang->line('list'); ?></h4>
            </div>

            <div class="modal-body">

            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function() {
        $('#studentModal').modal({
            backdrop: 'static',
            keyboard: false,
            show: false
        });
    });

    $(document).on('click', '.studentlist', function() {
        var $this = $(this);
        var date = $this.data('date');

        $.ajax({
            type: 'POST',
            url: baseurl + "student/getStudentByClassSection",
            data: {
                'cls_section_id': $this.data('clssectionId')
            },
            dataType: 'JSON',
            beforeSend: function() {
                $this.button('loading');
            },
            success: function(data) {
                $('#studentModal .modal-body').html(data.page);
                $('#studentModal').modal('show');
                $this.button('reset');
            },
            error: function(xhr) { // if error occured
                alert("Error occured.please try again");
                $this.button('reset');
            },
            complete: function() {
                $this.button('reset');
            }
        });

    });
</script>

<script>
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
            if (winload) {
                window.location.reload(true);
            }
        }, 500);

        return true;
    }

    document.getElementById("print").style.display = "block";
    document.getElementById("btnExport").style.display = "block";
    document.getElementById("printhead").style.display = "none";

    function printDiv() {
        document.getElementById("print").style.display = "none";
        document.getElementById("btnExport").style.display = "none";
        document.getElementById("printhead").style.display = "block";
        var divElements = document.getElementById('transfee').innerHTML;
        var oldPage = document.body.innerHTML;
        document.body.innerHTML =
            "<html><head><title>Fee Collection Report</title></head><body><h3 style='text-align:center;'>Sree Narayana Guru Central School</h3>" +
            divElements + "</body>";
        window.print();
        document.body.innerHTML = oldPage;
        document.getElementById("printhead").style.display = "none";
        location.reload(true);
    }

    function fnExcelReport() {
        exportToExcel();


    }




    function exportToExcel() {
        var htmls = "";
        var uri = 'data:application/vnd.ms-excel;base64,';
        var template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>';
        var base64 = function(s) {
            return window.btoa(unescape(encodeURIComponent(s)))
        };

        var format = function(s, c) {
            return s.replace(/{(\w+)}/g, function(m, p) {
                return c[p];
            })
        };
        var tab_text = "<tr >";
        var textRange;
        var j = 0;
        var val = "";
        tab = document.getElementById('headerTable'); // id of table

        for (j = 0; j < tab.rows.length; j++) {

            tab_text = tab_text + tab.rows[j].innerHTML + "</tr>";
            //tab_text=tab_text+"</tr>";
        }

        var ctx = {
            worksheet: 'Worksheet',
            table: tab_text
        }


        var link = document.createElement("a");
        link.download = "class_wise_strength_report.xls";
        link.href = uri + base64(format(template, ctx));
        link.click();
    }
    
</script>