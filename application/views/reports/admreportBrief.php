<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<style type="text/css">
    /*REQUIRED*/
    .carousel-row {
        margin-bottom: 10px;
    }

    .slide-row {
        padding: 0;
        background-color: #ffffff;
        min-height: 150px;
        border: 1px solid #e7e7e7;
        overflow: hidden;
        height: auto;
        position: relative;
    }

    .slide-carousel {
        width: 20%;
        float: left;
        display: inline-block;
    }

    .slide-carousel .carousel-indicators {
        margin-bottom: 0;
        bottom: 0;
        background: rgba(0, 0, 0, .5);
    }

    .slide-carousel .carousel-indicators li {
        border-radius: 0;
        width: 20px;
        height: 6px;
    }

    .slide-carousel .carousel-indicators .active {
        margin: 1px;
    }

    .slide-content {
        position: absolute;
        top: 0;
        left: 20%;
        display: block;
        float: left;
        width: 80%;
        max-height: 76%;
        padding: 1.5% 2% 2% 2%;
        overflow-y: auto;
    }

    .slide-content h4 {
        margin-bottom: 3px;
        margin-top: 0;
    }

    .slide-footer {
        position: absolute;
        bottom: 0;
        left: 20%;
        width: 78%;
        height: 20%;
        margin: 1%;
    }

    /* Scrollbars */
    .slide-content::-webkit-scrollbar {
        width: 5px;
    }

    .slide-content::-webkit-scrollbar-thumb:vertical {
        margin: 5px;
        background-color: #999;
        -webkit-border-radius: 5px;
    }

    .slide-content::-webkit-scrollbar-button:start:decrement,
    .slide-content::-webkit-scrollbar-button:end:increment {
        height: 5px;
        display: block;
    }

    table,
    th,
    td {
        border: 1px solid black !important;
    }
</style>

<div class="content-wrapper" style="min-height: 946px;">

    <section class="content-header">
        <h1>
            <i class="fa fa-bus"></i> <?php echo $this->lang->line('transport'); ?>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <?php $this->load->view('reports/_admission'); ?>
        <div class="row">
            <div class="col-md-12">
                <div class="box removeboxmius">
                    <div class="box-header ptbnull"></div>
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>

                    <form action="<?php echo site_url('report/adm_report_brief') ?>" method="post" class="" id="s">
                        <div class="box-body row">
                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('search') . " " . $this->lang->line('type'); ?></label>
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
                                    <span class="text-danger" id="error_search_type"></span>
                                </div>
                            </div>

                            <div id='date_result'>

                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button type="submit" name="search" value="search_filter" class="btn btn-primary btn-sm  pull-right"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                </div>
                            </div>
                        </div>
                    </form>


                    <div class="" id="transfee">
                        <div class="box-header ptbnull text-center">
                        </div>
                        <div class="box-header ptbnull">
                            <h3 class="box-title titlefix"><i class="fa fa-money"></i> <?php echo  "Admission " . $this->lang->line('report') . " Brief For The Year " . $current_session ?></h3>
                        </div>
                        <div class="box-body table-responsive">
                            <a class="btn btn-default btn-xs pull-right" id="print" onclick="printDiv()"><i class="fa fa-print"></i></a> <button class="btn btn-default btn-xs pull-right" id="btnExport" onclick="fnExcelReport();"> <i class="fa fa-file-excel-o"></i> </button>
                            <h4>Section Wise Report</h4>
                            <table class="table table-striped table-bordered  table-hover" id="headerTable">
                                <thead>
                                    <tr>
                                        <th class="text-center"><?php echo "Total Admission"; ?></th>
                                        <th class="text-center"><?php echo "Pre - Primary"; ?></th>
                                        <th class="text-center"><?php echo "Primary"; ?></th>
                                        <th class="text-center"><?php echo "Secondary"; ?></th>
                                    </tr>
                                </thead>
                                <?php
                                $prePrimary = $this->student_model->getSchSectionData($start_date, $end_date, $id = 1);
                                $primary = $this->student_model->getSchSectionData($start_date, $end_date, $id = 2);
                                $secondary = $this->student_model->getSchSectionData($start_date, $end_date, $id = 3);

                                ?>
                                <tr>
                                    <td class="text-center"><?php echo count($resultlist); ?></td>
                                    <td class="text-center"><?php echo count($prePrimary); ?></td>
                                    <td class="text-center"><?php echo count($primary); ?></td>
                                    <td class="text-center"><?php echo count($secondary); ?></td>
                                </tr>
                            </table>
                            <br>
                            <h4>Category Wise Report</h4>
                            <table class="table table-striped table-bordered  table-hover " id="headerTable2" data-export-title="<?php echo $this->lang->line('admission') . " " . $this->lang->line('report'); ?>">
                                <thead>
                                    <tr>
                                        <th class="text-center"><?php echo "Total Admission"; ?></th>
                                        <th class="text-center">SC</th>
                                        <th class="text-center"><?php echo "ST"; ?></th>
                                        <th class="text-center"><?php echo "General"; ?></th>
                                        <th class="text-center"><?php echo "OBC"; ?></th>
                                        <th class="text-center"><?php echo "Others"; ?></th>

                                    </tr>
                                </thead>
                                <?php
                                $countSC = 0;
                                $countST = 0;
                                $countGen = 0;
                                $countObc = 0;
                                $countOth = 0;
                                if (!empty($resultlist)) {
                                    //sc3,st4,gen2,oth6
                                    foreach ($resultlist as $key => $value) {
                                        if ($value['category_id'] == 3) {
                                            $countSC++;
                                        } elseif ($value['category_id'] == 4) {
                                            $countST++;
                                        } elseif ($value['category_id'] == 2) {
                                            $countGen++;
                                        } elseif ($value['category_id'] == 5) {
                                            $countObc++;
                                        } else {
                                            $countOth++;
                                        }
                                    }
                                }

                                ?>
                                <tbody>

                                    <tr>
                                        <?php
                                        $grandTotalAdm = 0;
                                        $totalMale = 0;
                                        $totalFemale = 0;

                                        ?>
                                        <td class="text-center"><?php echo count($resultlist); ?></td>
                                        <td class="text-center"><?php echo $countSC; ?></td>

                                        <td class="text-center"><?php echo $countST; ?></td>
                                        <td class="text-center"> <?php echo $countGen; ?></td>
                                        <td class="text-center"> <?php echo $countObc; ?></td>
                                        <td class="text-center"> <?php echo $countOth; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                            <br>
                            <h4>Religion Wise Report</h4>
                            <table class="table table-striped table-bordered  table-hover" id="headerTable3">
                                <thead>
                                    <tr>
                                        <th class="text-center"><?php echo "Total Admission"; ?></th>
                                        <th class="text-center"><?php echo "Muslim"; ?></th>
                                        <th class="text-center"><?php echo "Christian"; ?></th>
                                        <th class="text-center"><?php echo "Sikh"; ?></th>
                                        <th class="text-center"><?php echo "Buddhist"; ?></th>
                                        <th class="text-center"><?php echo "Parsi"; ?></th>
                                        <th class="text-center"><?php echo "Jain"; ?></th>
                                        <th class="text-center"><?php echo "Others"; ?></th>
                                    </tr>
                                </thead>
                                <?php
                                $countMuslim = 0;
                                $countChristion = 0;
                                $countSikh = 0;
                                $countBuddhist = 0;
                                $countParsi = 0;
                                $countJain = 0;
                                $countOth = 0;
                                if (!empty($resultlist)) {
                                    foreach ($resultlist as $key => $value) {
                                        if ($value['religion'] == "Muslim") {
                                            $countMuslim++;
                                        } elseif ($value['religion'] == "Christian") {
                                            $countChristion++;
                                        } elseif ($value['religion'] == "Sikh") {
                                            $countSikh++;
                                        } elseif ($value['religion'] == "Buddhist") {
                                            $countBuddhist++;
                                        } elseif ($value['religion'] == "Parsi") {
                                            $countParsi++;
                                        } elseif ($value['religion'] == "Jain") {
                                            $countJain++;
                                        } else {
                                            $countOth++;
                                        }
                                    }
                                }

                                ?>
                                <tr>
                                    <td class="text-center"><?php echo count($resultlist); ?></td>
                                    <td class="text-center"><?php echo $countMuslim; ?></td>
                                    <td class="text-center"><?php echo $countChristion; ?></td>
                                    <td class="text-center"><?php echo $countSikh; ?></td>
                                    <td class="text-center"><?php echo $countBuddhist; ?></td>
                                    <td class="text-center"><?php echo $countParsi; ?></td>
                                    <td class="text-center"><?php echo $countJain; ?></td>
                                    <td class="text-center"><?php echo $countOth; ?></td>
                                </tr>
                            </table>
                            <br>
                            <h4>Age Wise Report</h4>
                            <table class="table table-striped table-bordered  table-hover" id="headerTable4">
                                <thead>
                                    <tr>
                                        <th class="text-center"><?php echo "Age"; ?></th>
                                        <?php
                                        foreach ($classlist as $class) {
                                        ?>
                                            <th class="text-center"><?php echo $class['code']; ?></th>
                                        <?php
                                        }
                                        ?>
                                    </tr>
                                </thead>
                                <?php


                                for ($i = 5; $i <= 16; $i++) {
                                    $nurseryAge = 0;
                                    $jrkgAge = 0;
                                    $srkgAge = 0;
                                    $class1Age = 0;
                                    $class2Age = 0;
                                    $class3Age = 0;
                                    $class4Age = 0;
                                    $class5Age = 0;
                                    $class6Age = 0;
                                    $class7Age = 0;
                                    $class8Age = 0;
                                    $class9Age = 0;
                                    $class10Age = 0;
                                    if (!empty($resultlist)) {
                                        foreach ($resultlist as $key => $value) {
                                            // $year = (date('Y') - date('Y',strtotime($value['dob'])));

                                            // echo "<pre>";
                                            // print_r ($value);
                                            // echo "</pre>";
                                            $today = date('Y-m-d');
                                            $diff = date_diff(date_create($value['dob']), date_create($today));
                                            $year = $diff->format('%y');

                                            if ($value['class'] == "Nursery" && $year < 5) {
                                                $nurseryAge++;
                                            } elseif ($value['class'] == "Jr.KG" && $year < 5) {
                                                $jrkgAge++;
                                            } elseif ($value['class'] == "Sr.KG" && $year < 5) {
                                                $srkgAge++;
                                            } elseif ($value['code'] == "I" && $year < 5) {
                                                $class1Age++;
                                            } elseif ($value['code'] == "II" && $year < 5) {
                                                $class2Age++;
                                            } elseif ($value['code'] == "III" && $year < 5) {
                                                $class3Age++;
                                            } elseif ($value['code'] == "IV" && $year < 5) {
                                                $class4Age++;
                                            } elseif ($value['code'] == "V" && $year < 5) {
                                                $class5Age++;
                                            } elseif ($value['code'] == "VI" && $year < 5) {
                                                $class6Age++;
                                            } elseif ($value['code'] == "VII" && $year < 5) {
                                                $class7Age++;
                                            } elseif ($value['code'] == "VIII" && $year < 5) {
                                                $class8Age++;
                                            } elseif ($value['code'] == "IX" && $year < 5) {
                                                $class9Age++;
                                            } elseif ($value['code'] == "X" && $year < 5) {
                                                $class10Age++;
                                            }
                                        }
                                    }
                                }

                                ?>
                                <tr>
                                    <td class="text-center"><?php echo "<5"; ?></td>
                                    <td class="text-center"><?php echo $nurseryAge; ?></td>
                                    <td class="text-center"><?php echo $jrkgAge; ?></td>
                                    <td class="text-center"><?php echo $srkgAge; ?></td>
                                    <td class="text-center"><?php echo $class1Age; ?></td>
                                    <td class="text-center"><?php echo $class2Age; ?></td>
                                    <td class="text-center"><?php echo $class3Age; ?></td>
                                    <td class="text-center"><?php echo $class4Age; ?></td>
                                    <td class="text-center"><?php echo $class5Age; ?></td>
                                    <td class="text-center"><?php echo $class6Age; ?></td>
                                    <td class="text-center"><?php echo $class7Age; ?></td>
                                    <td class="text-center"><?php echo $class8Age; ?></td>
                                    <td class="text-center"><?php echo $class9Age; ?></td>
                                    <td class="text-center"><?php echo $class10Age; ?></td>
                                </tr>
                                
                                <?php
                                $studentAge = $this->student_model->getstudentByAge($start_date,$end_date);
                               
                                 for ($i = 5; $i <= 16; $i++) {
                                     $nur = 0;
                                     $jrkg = 0;
                                     $srkg = 0;
                                     $class1 = 0;
                                     $class2 = 0;
                                     $class3 = 0;
                                     $class4 = 0;
                                     $class5 = 0;
                                     $class6 = 0;
                                     $class7 = 0;
                                     $class8 = 0;
                                     $class9 = 0;
                                     $class10 = 0;
                                     
                                    foreach ($studentAge as $key => $ageRow) {
                                        if ($ageRow['class'] == "Nursery" && $ageRow['age'] == $i) {
                                            $nur++;
                                        }
                                        if ($ageRow['class'] == "Jr.KG" && $ageRow['age'] == $i) {
                                            $jrkg++;
                                        }
                                        if ($ageRow['class'] == "Sr.KG" && $ageRow['age'] == $i) {
                                            $srkg++;
                                        }
                                        if ($ageRow['code'] == "I" && $ageRow['age'] == $i) {
                                            $class1++;
                                        }
                                        if ($ageRow['code'] == "II" && $ageRow['age'] == $i) {
                                            $class2++;
                                        }
                                        if ($ageRow['code'] == "III" && $ageRow['age'] == $i) {
                                            $class3++;
                                        }
                                        if ($ageRow['code'] == "IV" && $ageRow['age'] == $i) {
                                            $class4++;
                                        }
                                        if ($ageRow['code'] == "V" && $ageRow['age'] == $i) {
                                            $class5++;
                                        }
                                        if ($ageRow['code'] == "VI" && $ageRow['age'] == $i) {
                                            $class6++;
                                        }
                                        if ($ageRow['code'] == "VII" && $ageRow['age'] == $i) {
                                            $class7++;
                                        }
                                        if ($ageRow['code'] == "VIII" && $ageRow['age'] == $i) {
                                            $class8++;
                                        }
                                        if ($ageRow['code'] == "IX" && $ageRow['age'] == $i) {
                                            $class9++;
                                        }
                                        if ($ageRow['code'] == "X" && $ageRow['age'] == $i) {
                                            $class10++;
                                        }
                                    }
                                    
                                    ?>
                                    <tr>
                                        <td class="text-center"><?php echo $i; ?></td>
                                        <??>
                                        <td class="text-center"><?php echo $nur; 
                                                                ?></td>
                                        <td class="text-center"><?php echo $jrkg; 
                                                                ?></td>
                                        <td class="text-center"><?php echo $srkg; 
                                                                ?></td>
                                        <td class="text-center"><?php echo $class1; 
                                                                ?></td>
                                        <td class="text-center"><?php echo $class2; 
                                                                ?></td>
                                        <td class="text-center"><?php echo $class3; 
                                                                ?></td>
                                        <td class="text-center"><?php echo $class4; 
                                                                ?></td>
                                        <td class="text-center"><?php echo $class5; 
                                                                ?></td>
                                        <td class="text-center"><?php echo $class6; 
                                                                ?></td>
                                        <td class="text-center"><?php echo $class7; 
                                                                ?></td>
                                        <td class="text-center"><?php echo $class8; 
                                                                ?></td>
                                        <td class="text-center"><?php echo $class9; 
                                                                ?></td>
                                        <td class="text-center"><?php echo $class10; 
                                                                ?></td>
                                    </tr>
                                <?php } ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>
</section>
</div>

<script>
    <?php
    if ($search_type == 'period') {
    ?>
        $(document).ready(function() {
            showdate('period');
        });

    <?php
    }
    ?>
</script>
<script>
    $(document).ready(function() {
        emptyDatatable('record-list', 'data');
    });
</script>
<script type="text/javascript">
    $(document).ready(function() {
        $(document).on('submit', '#reportform', function(e) {
            e.preventDefault(); // avoid to execute the actual submit of the form.
            var $this = $(this).find("button[type=submit]:focus");
            var form = $(this);
            var url = form.attr('action');
            var form_data = form.serializeArray();
            $.ajax({
                url: url,
                type: "POST",
                dataType: 'JSON',
                data: form_data, // serializes the form's elements.
                beforeSend: function() {
                    $('[id^=error]').html("");
                    $this.button('loading');
                },
                success: function(response) { // your success handler

                    if (!response.status) {
                        $.each(response.error, function(key, value) {
                            $('#error_' + key).html(value);
                        });
                    } else {

                        initDatatable('record-list', 'report/dtadmissionreport', response.params, [], 100);
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
</script>
<script>
    document.getElementById("print").style.display = "block";
    document.getElementById("btnExport").style.display = "block";

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

    function fnExcelReport() {
        var tab_text = "<table border='2px'><tr >";
        var textRange;
        var j = 0;
        tab = document.getElementById('headerTable'); // id of table
        tab2 = document.getElementById('headerTable2'); // id of table
        tab3 = document.getElementById('headerTable3'); // id of table
        tab4 = document.getElementById('headerTable4'); // id of table

        for (j = 0; j < tab.rows.length; j++) {
            tab_text = tab_text + tab.rows[j].innerHTML + "</tr>";
            //tab_text=tab_text+"</tr>";
        }
        for (j = 0; j < tab2.rows.length; j++) {
            tab_text = tab_text + tab2.rows[j].innerHTML + "</tr>";
            //tab_text=tab_text+"</tr>";
        }
        for (j = 0; j < tab3.rows.length; j++) {
            tab_text = tab_text + tab3.rows[j].innerHTML + "</tr>";
            //tab_text=tab_text+"</tr>";
        }
        for (j = 0; j < tab4.rows.length; j++) {
            tab_text = tab_text + tab4.rows[j].innerHTML + "</tr>";
            //tab_text=tab_text+"</tr>";
        }

        tab_text = tab_text + "</table>";
        tab_text = tab_text.replace(/<A[^>]*>|<\/A>/g, ""); //remove if u want links in your table
        tab_text = tab_text.replace(/<img[^>]*>/gi, ""); // remove if u want images in your table
        tab_text = tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params

        var ua = window.navigator.userAgent;
        var msie = ua.indexOf("MSIE ");

        if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) // If Internet Explorer
        {
            txtArea1.document.open("txt/html", "replace");
            txtArea1.document.write(tab_text);
            txtArea1.document.close();
            txtArea1.focus();
            sa = txtArea1.document.execCommand("SaveAs", true, "Say Thanks to Sumit.xls");
        } else //other browser not tested on IE 11
            sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));
        return (sa);
    }
</script>

<script type="text/javascript">
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
</script>