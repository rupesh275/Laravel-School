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
                    <th>S.B. A/c No.</th>
                    <th class="text-right">SALARY AMOUNT</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $totalNettSalary = 0;
                if (!empty($salaryArr)) {
                    $i = 1;
                    foreach ($salaryArr as $key => $salaryRow) {
                        $totalNettSalary += $salaryRow['nett_salary'];
                        ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo $salaryRow['name']." ".$salaryRow['surname']; ?></td>
                            <td><?php echo $salaryRow['bank_account_no']; ?></td>    
                            <td class="text-right"><?php echo $salaryRow['nett_salary']; ?></td>
                        </tr>
                        <?php
                        $i++;
                    }
                }?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-center"><b>TOTAL SALARY FOR <?php echo strtoupper($cheque['month'])." ".$cheque['year'];?></b></td>
                    <td class="text-right"><b><?php echo number_format($totalNettSalary,2);;?></b></td>
                </tr>
            </tfoot>
        </table>
        <b class="">In Words:- <?php echo strtoupper(markSheetDigitTwo($totalNettSalary));?>.</b><br><br>
        <b class="">Thanking You,<br>Yours Faithfully,<br>For <?php echo $setting->name;?></b>
        <br>
        <br>
        <br>
        <br>
        <b class="">MR. V. V. CHANDRAN<br>(TREASURER)</b>
    </div>
</body>
</html>