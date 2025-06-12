<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<div class="content-wrapper">
    <section class="content-header"></section>
    <!-- Main content -->
    <section class="content">
        <?php $this->load->view('reports/_finance'); ?>
        <div class="row">
            <div class="col-md-12">
                <div class="box removeboxmius">
                    <div class="box-header ptbnull"></div>
                    <div class="box-header ">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>
                    <form role="form" action="<?php echo site_url('admin/feemaster/chequeReport') ?>" method="post" class="">
                        <div class="box-body row" style="display:show">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="col-sm-3 col-lg-3 col-md-3">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('search') . " " . $this->lang->line('duration'); ?><small class="req"> *</small></label>
                                    <select class="form-control" name="search_type" onchange="showdate(this.value)">

                                        <?php foreach ($searchlist as $key => $search) {
                                        ?>
                                            <option value="<?php echo $key ?>" <?php
                                                                                if ((isset($search_type)) && ($search_type == $key)) {
                                                                                    echo "selected";
                                                                                }
                                                                                ?>><?php echo $search ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('search_type'); ?></span>
                                </div>
                            </div>


                            <div class="col-sm-3 col-lg-3 col-md-3">
                                <div class="form-group">
                                    <label><?php echo "Cheque Status"; ?></label>
                                    <select class="form-control" name="chq_status">
                                        <option value=""><?php echo $this->lang->line('select') ?></option>
                                        <option value="collected" <?php
                                                                    if ((isset($chq_status)) && ($chq_status == "collected")) {
                                                                        echo "selected";
                                                                    }
                                                                    ?>><?php echo "Collected"; ?></option>
                                        <option value="deposit" <?php
                                                                if ((isset($chq_status)) && ($chq_status == "deposit")) {
                                                                    echo "selected";
                                                                }
                                                                ?>><?php echo "Deposit"; ?></option>
                                        <option value="passed" <?php
                                                                if ((isset($chq_status)) && ($chq_status == "passed")) {
                                                                    echo "selected";
                                                                }
                                                                ?>><?php echo "Passed"; ?></option>
                                        <option value="bounced" <?php
                                                                if ((isset($chq_status)) && ($chq_status == "bounced")) {
                                                                    echo "selected";
                                                                }
                                                                ?>><?php echo "Bounced"; ?></option>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('chq_status'); ?></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button type="submit" name="search" value="search_filter" class="btn btn-primary btn-sm checkbox-toggle pull-right"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <?php
                    if (empty($results)) {
                    ?>

                        <div class="alert alert-info">
                            <?php echo $this->lang->line('no_record_found'); ?>
                        </div>
                    <?php
                    } else { ?>
                        <div class="">
                            <div class="box-header ptbnull"></div>
                            <div class="box-header ptbnull">
                                <h3 class="box-title titlefix"><i class="fa fa-money"></i> <?php ?> <?php echo "Cheque " . $this->lang->line('report'); ?> From <?php echo date('d/m/Y', strtotime($start_date)) . " To " . date('d/m/y', strtotime($end_date)); ?></h3>
                            </div>
                            <div class="box-body table-responsive" id="transfee">
                                <div id="printhead">
                                    <center>
                                        <h5><?php ?> <?php echo "Cheque " . $this->lang->line('report') . "<br>";
                                                        $this->customlib->get_postmessage();
                                                        ?></h5>
                                    </center>
                                </div>
                                <div class="download_label"><?php ?> <?php echo "Cheque " . $this->lang->line('report') . "<br>";
                                                                        $this->customlib->get_postmessage();
                                                                        ?></div>

                                <a class="btn btn-default btn-xs pull-right" id="print" onclick="printDiv()"><i class="fa fa-print"></i></a>
                                <a class="btn btn-default btn-xs pull-right" id="btnExport" onclick="exportToExcel();"> <i class="fa fa-file-excel-o"></i> </a>

                                <table class="table table-striped table-bordered table-hover " id="headerTable">
                                    <thead class="header">
                                        <tr>
                                            <th><?php echo "Cheque No/Date"; ?></th>
                                            <th><?php echo "Class"; ?></th>
                                            <th><?php echo "Student"; ?></th>
                                            <th><?php echo "Collection Date"; ?></th>
                                            <th><?php echo "Passing Date"; ?></th>
                                            <th><?php echo "Bounced Date"; ?></th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($results as $key => $value) {
                                            $deposit_date   = !empty($value['deposit_date']) && $value['deposit_date'] != "0000-00-00" ? date('d-m-Y', strtotime($value['deposit_date'])) : "";
                                            $chq_pass_date  = !empty($value['chq_pass_date']) && $value['chq_pass_date'] != "1970-01-01" ? date('d-m-Y', strtotime($value['chq_pass_date'])) : "";
                                            $bounce_date    = !empty($value['bounce_date']) && $value['bounce_date'] != "0000-00-00" ? date('d-m-Y', strtotime($value['bounce_date'])) : "";
                                            $class          = !empty($value['class']) ? $value['class'] . " [" . $value['section'] . "]" : "";
                                            $studentname    = !empty($value['firstname']) ? $value['firstname'] . " " . $value['lastname'] . " [" . $value['roll_no'] . "]" : "";
                                        ?>
                                            <tr>
                                                <td><?php echo $value['chq_no'] . " [" . date('d-m-Y', strtotime($value['chq_date'])) . "]"; ?> </td>
                                                <td><?php echo $class; ?> </td>
                                                <td><?php echo $studentname; ?> </td>
                                                <td><?php echo date('d-m-Y', strtotime($value['chq_date'])); ?> </td>
                                                <td><?php echo $chq_pass_date; ?> </td>
                                                <td><?php echo $bounce_date; ?> </td>
                                            </tr>
                                        <?php
                                        }
                                        ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>
</div>
</section>
</div>


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
    <?php
    if ($search_type == 'period') {
    ?>

        $(document).ready(function() {
            showdate('period');
        });

    <?php
    }
    ?>


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
        link.download = "studentfee_collection_report.xls";
        link.href = uri + base64(format(template, ctx));
        link.click();
    }

    $(document).ready(function() {
        $('.detail_popover').popover({
            placement: 'right',
            title: '',
            trigger: 'hover',
            container: 'body',
            html: true,
            content: function() {
                return $(this).closest('td').find('.fee_detail_popover').html();
            }
        });
    });
</script>