<style type="text/css">
    .nav-tabs-custom>.nav-tabs>li.active {
        border-top-color: #faa21c;
    }
    tfoot {
        display: table-footer-group;
    }
</style>
<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1><i class="fa fa-sitemap"></i> <?php echo $this->lang->line('human_resource'); ?></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <?php $this->load->view('reports/_human_resource'); ?>
        <div class="row">
            <div class="col-md-12">
                <div class="box removeboxmius">
                    <div class="box-header ptbnull"></div>
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>
                    <div class="box-body">

                        <form role="form" action="<?php echo site_url('report/pf_report') ?>" method="post" class="">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('month'); ?><small class="req"> *</small></label>
                                            <select name="month" class="form-control">
                                                <option value=""><?php
                                                                    echo $this->lang->line(
                                                                        'select'
                                                                    )
                                                                    ?></option>
                                                <?php foreach ($monthlist as $monthkey => $monthvalue) { ?>
                                                    <option <?php
                                                            if ($month == $monthvalue) {
                                                                echo "selected";
                                                            }
                                                            ?> value="<?php echo $monthvalue ?>"><?php echo $monthvalue; ?></option>
                                                <?php } ?>
                                            </select>
                                            <span class="text-danger"><?php echo form_error('month'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('year'); ?><small class="req"> *</small></label>
                                            <select name="year" class="form-control">
                                                <option value=""><?php
                                                                    echo $this->lang->line(
                                                                        'select'
                                                                    )
                                                                    ?></option>
                                                <?php foreach ($yearlist as $yearkey => $yearvalue) { ?>
                                                    <option <?php
                                                            if (($year == $yearvalue["year"])) {
                                                                echo "selected";
                                                            }
                                                            ?> value="<?php echo $yearvalue["year"]; ?>"><?php echo $yearvalue["year"]; ?></option>
                                                <?php } ?>
                                            </select>
                                            <span class="text-danger"><?php echo form_error('year'); ?></span>
                                        </div>
                                    </div>


                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <button type="submit" style="margin-left: 9px;" name="search" value="search_filter" class="btn btn-primary btn-sm checkbox-toggle pull-right"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div><!--./box-body-->

                    <?php if (isset($result)) {
                    ?>
                        <div class="">
                            <div class="box-header ptbnull"></div>
                            <div class="box-header with-border">
                                <h3 class="box-title"><i class="fa fa-users"></i> <?php echo $this->lang->line('payroll'); ?> <?php echo $this->lang->line('report'); ?></h3>
                            </div>
                            <div class="box-body table-responsive" id="transfee">
                                <div class="tab-content">
                                    <div class="tab-pane active table-responsive" id="tab_parent">
                                        <div class="download_label"><?php echo $this->lang->line('payroll'); ?> <?php echo $this->lang->line('report_for') . "<br>";
                                                                                                                $this->customlib->get_postmessage(); ?></div>
                                <a class="btn btn-default btn-xs pull-right" id="print" onclick="printDiv()"><i class="fa fa-print"></i></a>
                                <a class="btn btn-default btn-xs pull-right" id="btnExport" onclick="exportToExcel();"> <i class="fa fa-file-excel-o"></i> </a>

                                        <table class="table table-striped table-bordered table-hover " id="headerTable">
                                            <thead class="header">
                                                <tr>
                                                    <th><?php echo "Sr. No"; ?></th>
                                                    <th><?php echo $this->lang->line('name'); ?></th>
                                                    <th><?php echo $this->lang->line('designation'); ?></th>
                                                    <th><?php echo "Scale Of Pay"; ?></th>
                                                    <th class="text text-right"><?php echo "PF " . $this->lang->line('earning'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                                    <th class="text text-right"><?php echo "PF " . $this->lang->line('earning'); ?> <span><?php echo "( After Deduct " . $currency_symbol . ")"; ?></span></th>

                                                    <th class="text text-right"><?php echo "PF"; ?></th>
                                                    <th class="text text-right"><?php echo "Management Contribution"; ?></span></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $earnings = 0;
                                                $totalPf = 0;
                                                if (empty($result)) {
                                                ?>

                                                    <?php
                                                } else {
                                                    $count = 1;

                                                    foreach ($result as $key => $value) {

                                                        $earnings += $value["pf_earning"];
                                                        $totalPf += $value["pf"];
                                                    ?>
                                                        <tr>
                                                            <td><?php echo $count; ?></td>


                                                            <td style="text-transform: capitalize;">
                                                                <span data-toggle="popover" class="detail_popover" data-original-title="" title=""><a href="#"><?php echo $value['name'] . " " . $value['surname']; ?></a></span>
                                                                <div class="fee_detail_popover" style="display: none"><?php echo $this->lang->line('staff_id'); ?><?php echo ": " . $value['employee_id']; ?></div>
                                                            </td>
                                                            <td>
                                                                <span data-original-title="" title=""><?php echo $value['designation']; ?></span>
                                                            </td>
                                                            <td>
                                                                <?php echo $value['scale_of_pay']; ?>
                                                            </td>
                                                            <td class="text text-right">
                                                                <?php echo number_format($value['pf_earning'], 2); ?>
                                                            </td>

                                                            <td class="text text-right">
                                                                <?php echo (number_format($value['pf_earning'], 2)); ?>
                                                            </td>
                                                            <td class="text text-right">
                                                                <?php
                                                                $t = ($value['pf']);
                                                                echo (number_format($t, 2))
                                                                ?>
                                                            </td>
                                                            <td class="text text-right">
                                                                <?php
                                                                $t = ($value['pf']);
                                                                echo (number_format($t, 2))
                                                                ?>
                                                            </td>
                                                        </tr>
                                                    <?php
                                                        $count++;
                                                    }
                                                    ?>

                                                <?php } ?>
                                                <!-- <tfoot> -->
                                                    <tr>
                                                        <th colspan="4">Total </th>
                                                        <th class="text text-right"><?php echo number_format($earnings, 2); ?></th>
                                                        <th class="text text-right"><?php echo number_format($earnings, 2); ?></th>
                                                        <th class="text text-right"><?php echo number_format($totalPf, 2); ?></th>
                                                        <th class="text text-right"><?php echo number_format($totalPf, 2); ?></th>
                                                    </tr>
                                                <!-- </tfoot> -->
                                            </tbody>
                                        </table>
                                    </div>


                                </div>

                            </div>
                        </div><!--./tabs-->
                </div><!--./box box-primary-->
            <?php
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
                return $(this).closest('td').find('.fee_detail_popover').html();
            }
        });
    });
</script>

<script type="text/javascript">
    document.getElementById("print").style.display = "block";
    document.getElementById("btnExport").style.display = "block";
    // document.getElementById("printhead").style.display = "none";

    function printDiv() {
        document.getElementById("print").style.display = "none";
        document.getElementById("btnExport").style.display = "none";
        // document.getElementById("printhead").style.display = "block";
        var divElements = document.getElementById('transfee').innerHTML;
        var oldPage = document.body.innerHTML;
        document.body.innerHTML =
            "<html><head><title>PF Report</title></head><body><h3 style='text-align:center;'>Sree Narayana Guru Central School</h3>" +
            divElements + "</body>";
        window.print();
        document.body.innerHTML = oldPage;
        // document.getElementById("printhead").style.display = "none";
        location.reload(true);
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
        link.download = "pf_report.xls";
        link.href = uri + base64(format(template, ctx));
        link.click();
    }

    // $(document).ready(function() {
    //     $.extend($.fn.dataTable.defaults, {
    //         ordering: false,
    //         paging: false,
    //         bSort: false,
    //         info: false
    //     });
    // })
    // $(document).ready(function() {
    //     $('.table-fixed-header').fixedHeader();
    // });

    // (function($) {

    //     $.fn.fixedHeader = function(options) {
    //         var config = {
    //             topOffset: 50

    //         };
    //         if (options) {
    //             $.extend(config, options);
    //         }

    //         return this.each(function() {
    //             var o = $(this);

    //             var $win = $(window);
    //             var $head = $('thead.header', o);
    //             var isFixed = 0;
    //             var headTop = $head.length && $head.offset().top - config.topOffset;

    //             function processScroll() {
    //                 if (!o.is(':visible')) {
    //                     return;
    //                 }
    //                 if ($('thead.header-copy').size()) {
    //                     $('thead.header-copy').width($('thead.header').width());
    //                 }
    //                 var i;
    //                 var scrollTop = $win.scrollTop();
    //                 var t = $head.length && $head.offset().top - config.topOffset;
    //                 if (!isFixed && headTop !== t) {
    //                     headTop = t;
    //                 }
    //                 if (scrollTop >= headTop && !isFixed) {
    //                     isFixed = 1;
    //                 } else if (scrollTop <= headTop && isFixed) {
    //                     isFixed = 0;
    //                 }
    //                 isFixed ? $('thead.header-copy', o).offset({
    //                     left: $head.offset().left
    //                 }).removeClass('hide') : $('thead.header-copy', o).addClass('hide');
    //             }
    //             $win.on('scroll', processScroll);

    //             // hack sad times - holdover until rewrite for 2.1
    //             $head.on('click', function() {
    //                 if (!isFixed) {
    //                     setTimeout(function() {
    //                         $win.scrollTop($win.scrollTop() - 47);
    //                     }, 10);
    //                 }
    //             });

    //             $head.clone().removeClass('header').addClass('header-copy header-fixed').appendTo(o);
    //             var header_width = $head.width();
    //             o.find('thead.header-copy').width(header_width);
    //             o.find('thead.header > tr:first > th').each(function(i, h) {
    //                 var w = $(h).width();
    //                 o.find('thead.header-copy> tr > th:eq(' + i + ')').width(w);
    //             });
    //             $head.css({
    //                 margin: '0 auto',
    //                 width: o.width(),
    //                 'background-color': config.bgColor
    //             });
    //             processScroll();
    //         });
    //     };

    // })(jQuery);
</script>