<?php

defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class Export extends Admin_Controller {

    public $sch_setting_detail = array();
    public function __construct()
    {
        parent::__construct();
        //$this->config->load("payroll");
        $this->config->load("app-config");
        $this->load->library('Enc_lib');
        $this->load->library('mailsmsconf');
        $this->load->model("staff_model");
        $this->load->library('encoding_lib');
        $this->load->model("leaverequest_model");
        // $this->contract_type      = $this->config->item('contracttype');
        // $this->marital_status     = $this->config->item('marital_status');
        // $this->staff_attendance   = $this->config->item('staffattendance');
        // $this->payroll_status     = $this->config->item('payroll_status');
        // $this->payment_mode       = $this->config->item('payment_mode');
        // $this->status             = $this->config->item('status');
        $this->sch_setting_detail = $this->setting_model->getSetting();
    }

    public function staffExport()
    {
        $spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1', 'Staff ID');
		$sheet->setCellValue('B1', 'Type');
		$sheet->setCellValue('C1', 'Role');
		$sheet->setCellValue('D1', 'Designation');
		$sheet->setCellValue('E1', 'Department');
		$sheet->setCellValue('F1', 'First Name');
		$sheet->setCellValue('G1', 'Middle Name');
		$sheet->setCellValue('H1', 'Last Name');
		$sheet->setCellValue('I1', 'Father Name');
		$sheet->setCellValue('J1', 'Mother Name');
		$sheet->setCellValue('K1', 'Marital Status');
		$sheet->setCellValue('L1', 'Spouse Name');
		$sheet->setCellValue('M1', 'Date of Birth');
		$sheet->setCellValue('N1', 'Caste');
		$sheet->setCellValue('O1', 'Subcaste');
		$sheet->setCellValue('P1', 'Religion');
		$sheet->setCellValue('Q1', 'Seniority ID');
		$sheet->setCellValue('R1', 'Gender');
		$sheet->setCellValue('S1', 'Grade');
		$sheet->setCellValue('T1', 'Date Of Joining');
		$sheet->setCellValue('U1', 'Date of Confirmation');
		$sheet->setCellValue('V1', 'Residence');
		$sheet->setCellValue('W1', 'Remarks');
		$sheet->setCellValue('X1', 'Qualification');
		$sheet->setCellValue('Y1', 'Work Experience');
		$sheet->setCellValue('Z1', 'Note');
		$sheet->setCellValue('AA1', 'Blood Group');
		$sheet->setCellValue('AB1', 'Aadhar No');
		$sheet->setCellValue('AC1', 'Pan No');
		$sheet->setCellValue('AD1', 'Biometric ID');
		$sheet->setCellValue('AE1', 'Email');
		$sheet->setCellValue('AF1', 'Phone');
		$sheet->setCellValue('AG1', 'Emergency Contact Number');
		$sheet->setCellValue('AH1', 'Mobile');
		$sheet->setCellValue('AI1', 'Phone 2');
		$sheet->setCellValue('AJ1', 'Current Address');
		$sheet->setCellValue('AK1', 'Current City');
		$sheet->setCellValue('AL1', 'Current State');
		$sheet->setCellValue('AM1', 'Current Country');
		$sheet->setCellValue('AN1', 'Current Pincode');
		$sheet->setCellValue('AO1', 'Permanent Address');
		$sheet->setCellValue('AP1', 'Permanent City');
		$sheet->setCellValue('AQ1', 'Permanent State');
		$sheet->setCellValue('AR1', 'Permanent Country');
		$sheet->setCellValue('AS1', 'Permanent Pincode');
		$sheet->setCellValue('AT1', 'EPF No');
		$sheet->setCellValue('AU1', 'Contract Type');
		$sheet->setCellValue('AV1', 'Basic Salary');
		$sheet->setCellValue('AW1', 'Work Shift');
		$sheet->setCellValue('AX1', 'Location');
		$sheet->setCellValue('AY1', 'Date Of Leaving');
		$sheet->setCellValue('AZ1', 'Scale Of Pay');
		$sheet->setCellValue('BA1', 'Basic Pay');
		$sheet->setCellValue('BB1', 'GP');
		$sheet->setCellValue('BC1', 'DA');
		$sheet->setCellValue('BD1', 'HRA');
		$sheet->setCellValue('BE1', 'TA');
		$sheet->setCellValue('BF1', 'Other Allowance');
		$sheet->setCellValue('BG1', 'PF');
		$sheet->setCellValue('BH1', 'Profession Tax');
		$sheet->setCellValue('BI1', 'PF Exempted');
		$sheet->setCellValue('BJ1', 'PT Exempted');
		$sheet->setCellValue('BK1', 'UAN NO');
		$sheet->setCellValue('BL1', 'IT Scheme');
		$sheet->setCellValue('BM1', 'DCPS No');
		$sheet->setCellValue('BN1', 'Gratuity No');
		$sheet->setCellValue('BO1', 'LWF Applicable');
		$sheet->setCellValue('BP1', 'LWF Grade');
		$sheet->setCellValue('BQ1', 'Increment Month');
		$sheet->setCellValue('BR1', 'Increment Amount');
		$sheet->setCellValue('BS1', 'ESI No');
		$sheet->setCellValue('BT1', 'ESI Dispensary');
		$sheet->setCellValue('BU1', 'ESI Exempted');
		$sheet->setCellValue('BV1', 'Passport No');
		$sheet->setCellValue('BW1', 'Place Of Issue');
		$sheet->setCellValue('BX1', 'Date Of Issue');
		$sheet->setCellValue('BY1', 'Date Of Expiry');
		$sheet->setCellValue('BZ1', 'Account Title');
		$sheet->setCellValue('CA1', 'Bank Account Number');
		$sheet->setCellValue('CB1', 'Bank Name');
		$sheet->setCellValue('CC1', 'IFSC Code');
		$sheet->setCellValue('CD1', 'Bank Branch Name');
		$sheet->setCellValue('CE1', 'Date of Retirement');
		$sheet->setCellValue('CF1', 'LeftOn');
		$sheet->setCellValue('CG1', 'Left Reason');
		$sheet->setCellValue('CH1', 'Facebook URL');
		$sheet->setCellValue('CI1', 'Twitter URL');
		$sheet->setCellValue('CJ1', 'Linkedin URL');
		$sheet->setCellValue('CK1', 'Instagram URL');
		$sheet->setCellValue('CL1', 'Full Name');
		$rows = 2;

       $sql= $this->staff_model->getstaffExports();
       
        foreach ($sql as $val) {
            $sheet->setCellValue('A' . $rows, $val['employee_id']);
            if ($val['record_type'] == 1) {
                $type = "Staff";
            }
            if ($val["date_of_confirmation"] != '0000-00-00' || $val["date_of_leaving"] != '0000-00-00' ) {
                $date_of_confirmation = $val["date_of_confirmation"];
                $date_of_leaving = $val["date_of_leaving"];
            } else {
                $date_of_confirmation = "";
                $date_of_leaving = "";
            }
            
            $sheet->setCellValue('B' . $rows, $type);
            $sheet->setCellValue('C' . $rows, $val['user_type']);
            $sheet->setCellValue('D' . $rows, $val['designation']);
            $sheet->setCellValue('E' . $rows, $val['department_name']);
            $sheet->setCellValue('F' . $rows, $val['name']);
            $sheet->setCellValue('G' . $rows, $val['middle_name']);
            $sheet->setCellValue('H' . $rows, $val['surname']);
            $sheet->setCellValue('I' . $rows, $val['father_name']);
            $sheet->setCellValue('J' . $rows, $val['mother_name']);
            $sheet->setCellValue('K' . $rows, $val['marital_status']);
            $sheet->setCellValue('L' . $rows, $val['spouse_name']);
            $sheet->setCellValue('M' . $rows, $val['dob'] != "" ? date('d-m-Y',strtotime($val['dob'])):"");
            $sheet->setCellValue('N' . $rows, $val['cast']);
            $sheet->setCellValue('O' . $rows, $val['subcaste']);
            $sheet->setCellValue('P' . $rows, $val['religion']);
            $sheet->setCellValue('Q' . $rows, $val['seniority_id']);
            $sheet->setCellValue('R' . $rows, $val['gender']);
            $sheet->setCellValue('S' . $rows, $val['grade']);
            $sheet->setCellValue('T' . $rows, $val['date_of_joining'] != "" ? date('d-m-Y',strtotime($val['date_of_joining'])):"");
            $sheet->setCellValue('U' . $rows, $date_of_confirmation != "" ? date('d-m-Y',strtotime($date_of_confirmation)):"");
            $sheet->setCellValue('V' . $rows, $val['residence']);
            $sheet->setCellValue('W' . $rows, $val['remarks']);
            $sheet->setCellValue('X' . $rows, $val['qualification']);
            $sheet->setCellValue('Y' . $rows, $val['work_exp']);
            $sheet->setCellValue('Z' . $rows, $val['note']);
            $sheet->setCellValue('AA'. $rows, $val['blood_group']);
            $sheet->setCellValue('AB'.$rows, $val['aadhar_no']);
            $sheet->setCellValue('AC'.$rows, $val['pan_no']);
            $sheet->setCellValue('AD'.$rows, $val['biometric_id']);
            $sheet->setCellValue('AE'.$rows, $val['email']);
            $sheet->setCellValue('AF'.$rows, $val['contact_no']);
            $sheet->setCellValue('AG'.$rows, $val['emergency_contact_no']);
            $sheet->setCellValue('AH'.$rows, $val['mobile2']);
            $sheet->setCellValue('AI'.$rows, $val['phone2']);
            $sheet->setCellValue('AJ'.$rows, $val['local_address']);
            $sheet->setCellValue('AK'.$rows, $val['current_city']);
            $sheet->setCellValue('AL'.$rows, $val['current_state']);
            $sheet->setCellValue('AM'.$rows, $val['current_country']);
            $sheet->setCellValue('AN'.$rows, $val['current_pincode']);
            $sheet->setCellValue('AO'.$rows, $val['permanent_address']);
            $sheet->setCellValue('AP'.$rows, $val['permanent_city']);
            $sheet->setCellValue('AQ'.$rows, $val['permanent_state']);
            $sheet->setCellValue('AR'.$rows, $val['permanent_country']);
            $sheet->setCellValue('AS'.$rows, $val['permanent_pincode']);
            $sheet->setCellValue('AT'.$rows, $val['epf_no']);
            $sheet->setCellValue('AU'.$rows, $val['contract_type']);
            $sheet->setCellValue('AV'.$rows, $val['basic_salary']);
            $sheet->setCellValue('AW'.$rows, $val['shift']);
            $sheet->setCellValue('AX'.$rows, $val['location']);
            $sheet->setCellValue('AY'.$rows, $date_of_leaving!= "" ? date('d-m-Y',strtotime($date_of_leaving)):"");
            $sheet->setCellValue('AZ'.$rows, $val['scale_of_pay']);
            $sheet->setCellValue('BA'.$rows, $val['basic_pay']);
            $sheet->setCellValue('BB'.$rows, $val['gp']);
            $sheet->setCellValue('BC'.$rows, $val['da']);
            $sheet->setCellValue('BD'.$rows, $val['hra']);
            $sheet->setCellValue('BE'.$rows, $val['ta']);
            $sheet->setCellValue('BF'.$rows, $val['other_allowance']);
            $sheet->setCellValue('BG'.$rows, $val['pf']);
            $sheet->setCellValue('BH'.$rows, $val['profession_tax']);
            if ($val['pf_exempted'] == 1 ||$val['pt_exempted'] == 1 || $val['lwf_applicable'] == 1 ||$val['esi_exempted'] == 1 ) {
                $pf_exempted= "Yes";
                $pt_exempted= "Yes";
                $lwf_applicable= "Yes";
                $esi_exempted= "Yes";
            }else {
                $pf_exempted="No";
                $pt_exempted="No";
                $lwf_applicable="No";
                $esi_exempted="No";
            }
            $sheet->setCellValue('BJ'.$rows, $pf_exempted);
            $sheet->setCellValue('BI'.$rows, $pt_exempted);
            $sheet->setCellValue('BK'.$rows, $val['uan_no']);
            $sheet->setCellValue('BL'.$rows, $val['it_scheme']);
            $sheet->setCellValue('BM'.$rows, $val['dcps_no']);
            $sheet->setCellValue('BN'.$rows, $val['gratuity_no']);
            $sheet->setCellValue('BO'.$rows, $lwf_applicable);
            $sheet->setCellValue('BP'.$rows, $val['lwf_grade']);
            $sheet->setCellValue('BQ'.$rows, $val['increment_month']);
            $sheet->setCellValue('BR'.$rows, $val['increment_amount']);
            $sheet->setCellValue('BS'.$rows, $val['esi_no']);
            $sheet->setCellValue('BT'.$rows, $val['esi_dispensary']);
            $sheet->setCellValue('BU'.$rows, $esi_exempted);
            $sheet->setCellValue('BV'.$rows, $val['passport_no']);
            $sheet->setCellValue('BW'.$rows, $val['place_of_issue']);
            $sheet->setCellValue('BX'.$rows, $val['date_of_issue']!= "" ? date('d-m-Y',strtotime($val['date_of_issue'])):"");
            $sheet->setCellValue('BY'.$rows, $val['date_of_expiry']!= "" ? date('d-m-Y',strtotime($val['date_of_expiry'])):"");
            $sheet->setCellValue('BZ'.$rows, $val['account_title']);
            $sheet->setCellValue('CA'.$rows, $val['bank_account_no']);
            $sheet->setCellValue('CB'.$rows, $val['bank_name']);
            $sheet->setCellValue('CC'.$rows, $val['ifsc_code']);
            $sheet->setCellValue('CD'.$rows, $val['bank_branch']);
            $sheet->setCellValue('CE'.$rows, $val['date_of_retirement'] != "" ? date('d-m-Y',strtotime($val['date_of_retirement'])):"");
            $sheet->setCellValue('CF'.$rows, $val['lefton']!= "" ? date('d-m-Y',strtotime($val['lefton'])):"");
            $sheet->setCellValue('CG'.$rows, $val['left_reason']);
            $sheet->setCellValue('CH'.$rows, $val['facebook']);
            $sheet->setCellValue('CI'.$rows, $val['twitter']);
            $sheet->setCellValue('CJ'.$rows, $val['linkedin']);
            $sheet->setCellValue('CK'.$rows, $val['instagram']);
            $full_name = $val['name'];
            if (!empty($val['middle_name'])) {
                $full_name .= " ".$val['middle_name'];
            }
            if (!empty($val['surname'])) {
                $full_name .= " ".$val['surname'];
            }
            $sheet->setCellValue('CL'.$rows, $full_name);
            
            $rows++;
        }
		$writer = new Xlsx($spreadsheet);

		$filename = 'Staff';

		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
		header('Cache-Control: max-age=0');
		
		$writer->save('php://output'); // download file 
    }

}

/* End of file Controllername.php */
