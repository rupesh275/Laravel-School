<!doctype html>
<html lang="en">

<head>
    <title>Title</title>
    <!-- Required meta tags -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CSS -->
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous"> -->
</head>

<body>


    <style>
        .table,
        td {
            font-size: 12px;
        }

        .table thead th {
            border: 2px solid #000
        }

        .table-bordered th {
            border: 1px solid #737576 !important;
        }

        .table-bordered td {
            border: 1px solid #000 !important;
        }

        .bg {
            background: #ff0000b0 !important;
            width: 12px;
            font-size: 9px;
            vertical-align: middle;
        }

        .table td,
        .table th {
            padding: 5px 2px;
            text-align: center;
            vertical-align: middle;
        }

        .modal-title {
            font-weight: 600;
            text-transform: uppercase
        }
    </style>

    <div class="tab-content" style="margin:15px 0px">
        <div class="tab-pane active table-responsive no-padding" id="tab_1">
            <table class="table-bordered" style="margin-bottom:21px;width:100%">
                <tr>
                    <td>
                        <h4 class="modal-title text-center" style="font-size:28px"><?php echo $sch_setting->name; ?>[C.B.S.E]</h4>
                    </td>
                </tr>
                <tr>
                    <td style="width:100%">
                        <h4 class="modal-title text-center"> ATTENDANCE SHEET FOR THE MONTH OF <?php echo $month . " " . date('Y'); ?></h4>
                    </td>
                </tr>
                <tr>
                    <td style="width:100%">
                        <h4 class="modal-title text-center"> <?php echo $classSection['class'] . " " . $classSection['section']; ?></h4>
                    </td>
                </tr>
            </table>



            <table class="table table-striped table-bordered table-hover student-listssa" style="margin:15px 0px">
                <thead>
                    <tr style="margin-top: 21px;">
                        <th style="width:30px;vertical-align: middle;"><?php echo $this->lang->line('roll_no'); ?></th>
                        <th style="width:300px;vertical-align: middle;"><?php echo $this->lang->line('student_name'); ?></th>
                        <th style="width:50px;vertical-align: middle;"><?php echo "House"; ?></th>
                        <?php
                        $yr = date('F', strtotime($month));
                        $monthCount = date("t", strtotime($yr)) + 1;
                        for ($i = 1; $i < $monthCount; $i++) {
                            $dateStr = (string)$month . ' ' . $i . ' ' . date('Y');
                            $dateNo = date('w', strtotime($dateStr));
                            if ($dateNo == 0) {
                                $dateTxt = $i . '<br>(S)';
                                $dateClass = 'bg';
                            } else {
                                $dateTxt = $i;
                                $dateClass = '';
                            }
                        ?>
                            <!-- <th><?php //echo $i; 
                                        ?></th> -->
                            <th class="<?php echo $dateClass; ?>" style="width:28px;vertical-align: middle;"><?php echo $dateTxt; ?></th>
                        <?php
                        }
                        ?>
                        <th style="width:28px;"><?php echo $this->lang->line('total'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    // echo "<pre>";
                    // print_r ($studentList);
                    // echo "</pre>";

                    if (!empty($studentList)) {
                        foreach ($studentList as  $student) {

                    ?>
                            <tr>
                                <td><?php echo $student['roll_no']; ?></td>
                                <td><?php echo $this->customlib->getFullName($student['firstname'], $student['middlename'], $student['lastname'], $sch_setting->middlename, $sch_setting->lastname); ?></td>
                                <td><?php echo $student['house_name']; ?></td>
                                <?php

                                for ($i = 1; $i < $monthCount; $i++) {
                                ?>
                                    <td><?php //echo $i; 
                                        ?></td>
                                <?php
                                }
                                ?>
                                <td></td>
                            </tr>
                    <?php
                        }
                    } ?>
                </tbody>
            </table>
        </div>

    </div>

</body>

</html>