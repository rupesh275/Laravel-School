<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salary Slip</title>
    <style>
         table td {
            font-size: 13px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <br>
            <br>
            <br>
            <br>
            <div class="col-md-6" style="margin-bottom: 30px;">
                <p>Dt. <?php echo $letter_date;?></p>
                <p>SNGCS/ACCTS/<?php echo $cheque['year']."/".$cheque['month'];?></p>
                <p>To</p>
                <p>The Manager,</p>
                <p>Sree Narayana Guru Co-Op. Bank Ltd</p>
                <p>Chembur (W), Mumbai</p>
                <br>
                <p>Ref:- S.B. Account No:- 002027005581 (<?php echo $setting->name;?>)</p>

            </div>
            <div class="col-md-6">
            </div>
        </div>
        <p class="mt-3">Dear Sir,</p>
        <p>I request you to credit salary of our staff for the month of <?php echo $cheque['month'];?> <?php echo $cheque['year'];?> vide Ch.No.<?php echo $cheque['chq_no'];?> Dt.<?php echo date('d-m-Y', strtotime($cheque['chq_date']));?>.</p>
        <p class="mt-3">The Name of Staff and Account No. mentioned as below:-</p>
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>Sr. No.</th>
                    <th>NAME OF THE EMPLOYEES</th>
                    <th class="text-right">PF EARNING</th>
                    <th class="text-right">PF</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($salaryArr)) {
                    $i = 1;
                    $totalNettPF = 0;
                    $totalPFEarning = 0;
                    foreach ($salaryArr as $key => $salaryRow) {
                        ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo $salaryRow['name']." ".$salaryRow['surname']; ?></td>
                            <?php
                            if($salaryRow['pf_earning']>=$payroll_settings['pf_earning_limit']){
                                $pf_earning = $payroll_settings['pf_earning_limit'];
                            } else {
                                $pf_earning = $salaryRow['pf_earning'];
                            }
                            ?>
                            <td class="text-right"><?php echo number_format($pf_earning,2); $totalPFEarning+=$pf_earning; ?></td>    
                            <td class="text-right"><?php echo $salaryRow['pf']; $totalNettPF+=$salaryRow['pf']; ?></td>
                        </tr>
                        <?php
                        $i++;
                    }
                }?>
            </tbody>
            
                <tr>
                    <td colspan="2" class="text-center"><b>TOTAL SALARY FOR <?php echo strtoupper($cheque['month'])." ".$cheque['year'];?></b></td>
                    <td class="text-right"><b><?php echo number_format($totalPFEarning,2);;?></b></td>
                    <td class="text-right"><b><?php echo number_format($totalNettPF,2);;?></b></td>
                </tr>
            
        </table>
        <br>
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>Descrption</th>
                    <th class="text-right">PF Earnings</th>
                    <th class="text-right">Percentage</th>
                    <th class="text-right">PF</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $amountPf = ($payroll_settings['ey_pf'] / 100) * $totalPFEarning;
                $amountPf_er = ($payroll_settings['er_epf'] / 100) * $totalPFEarning;
                $amountPf_er_eps = ($payroll_settings['er_eps'] / 100) * $totalPFEarning;
                $amountPf_er_edli = ($payroll_settings['er_edli'] / 100) * $totalPFEarning;
                $amountPf_er_admin = ($payroll_settings['er_admin'] / 100) * $totalPFEarning;
                ?>
                <!-- <tr>
                    <td colspan="4">Employee Share</td>
                </tr> -->
                <tr>
                    <td>Employee's Share</td>
                    <td class="text-right"><b><?php echo number_format($totalPFEarning,2);;?></b></td>
                    <td class="text-right"><?php echo $payroll_settings['ey_pf'];?></td>
                    <td class="text-right"><?php echo number_format(round($amountPf),2);?></td>
                </tr>
                <tr>
                    <td>Employer PF Share</td>
                    <td class="text-right"><b><?php echo number_format($totalPFEarning,2);;?></b></td>
                    <td class="text-right"><?php echo $payroll_settings['er_epf'];?></td>
                    <td class="text-right"><?php echo number_format(round($amountPf_er),2);?></td>
                </tr>
                <tr>
                    <td>Employee Family Pension Fund</td>
                    <td class="text-right"><b><?php echo number_format($totalPFEarning,2);;?></b></td>
                    <td class="text-right"><?php echo $payroll_settings['er_eps'];?></td>
                    <td class="text-right"><?php echo number_format(round($amountPf_er_eps),2);?></td>
                </tr>
                <tr>
                    <td>EDLI</td>
                    <td class="text-right"><b><?php echo number_format($totalPFEarning,2);;?></b></td>
                    <td class="text-right"><?php echo $payroll_settings['er_edli'];?></td>
                    <td class="text-right"><?php echo number_format(round($amountPf_er_edli),2);?></td>
                </tr>
                <tr>
                    <td>Admin Charges</td>
                    <td class="text-right"><b><?php echo number_format($totalPFEarning,2);;?></b></td>
                    <td class="text-right"><?php echo $payroll_settings['er_admin'];?></td>
                    <td class="text-right"><?php echo number_format(round($amountPf_er_admin),2);?></td>
                </tr>
                <?php $allTotal = round($amountPf) + round($amountPf_er) + round($amountPf_er_eps) + round($amountPf_er_edli) + round($amountPf_er_admin);?>
                <tr>
                    <td class="text-center" colspan="3"><b>Total PF FOR <?php echo strtoupper($cheque['month'])." ".$cheque['year'];?></b></td>
                    <td class="text-right"><b><?php echo number_format(round($allTotal),2);?></b></td>
                </tr>
            </tbody>
        </table>
        <b class="">In Words:- <?php echo strtoupper(markSheetDigitTwo($totalNettPF));?>.</b><br><br>
        <b class="">Thanking You,<br>Yours Faithfully,<br>For <?php echo $setting->name;?></b>
        <br>
        <br>
        <br>
        <br>
        <b class="">MR. V. V. CHANDRAN<br>(TREASURER)</b>
    </div>
</body>
</html>