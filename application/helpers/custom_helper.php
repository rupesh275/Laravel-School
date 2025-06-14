<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

if (!function_exists('is_subAttendence')) {

    function is_subAttendence() {

        $CI = &get_instance();
        $CI->db->select('sch_settings.id,sch_settings.lang_id,sch_settings.attendence_type,sch_settings.is_rtl,sch_settings.timezone,
          sch_settings.name,sch_settings.email,sch_settings.biometric,sch_settings.biometric_device,sch_settings.phone,languages.language,
          sch_settings.address,sch_settings.dise_code,sch_settings.date_format,sch_settings.currency,sch_settings.currency_symbol,sch_settings.start_month,sch_settings.session_id,sch_settings.image,sch_settings.theme,sessions.session'
        );
        $CI->db->from('sch_settings');
        $CI->db->join('sessions', 'sessions.id = sch_settings.session_id');
        $CI->db->join('languages', 'languages.id = sch_settings.lang_id');
        $CI->db->order_by('sch_settings.id');
        $query = $CI->db->get();
        $result = $query->row();

        if ($result->attendence_type) {
            return true;
        }
        return false;
    }

}

if (!function_exists('get_subjects')) {

    function get_subjects($class_batch_id) {
        $CI = &get_instance();
        $CI->db->select('class_batch_subjects.*,subjects.name as `subject_name`');
        $CI->db->from('class_batch_subjects');
        $CI->db->join('subjects', 'subjects.id = class_batch_subjects.subject_id');
        $CI->db->where('class_batch_id', $class_batch_id);
        $CI->db->order_by('class_batch_subjects.id', 'asc');

        $query = $CI->db->get();
        $return_string = '<option value="">--Select--</option>';
        $result = $query->result();
        if (!empty($result)) {
            foreach ($result as $result_key => $result_value) {
                $return_string .= '<option value="' . $result_value->id . '">' . $result_value->subject_name . '</option>';
            }
        }
        return $return_string;
    }

}

if (!function_exists('readmorelink')) {

    function readmorelink($string, $link = false) {
        $string = strip_tags($string);
        if (strlen($string) > 150) {

            // truncate string
            $stringCut = substr($string, 0, 150);
            $endPoint = strrpos($stringCut, ' ');

            //if the string doesn't contain any space then it will cut without word basis.
            $string = $endPoint ? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
            $string .= ($link) ? "<a href='" . $link . "' target='_blank'>Read more...</a>" : "....";
        }

        return $string;
    }

}

if (!function_exists('readmorelinkUser')) {

    function readmorelinkUser($string, $link = false) {
        $string = strip_tags($string);
        if (strlen($string) > 150) {

            // truncate string
            $stringCut = substr($string, 0, 150);
            $endPoint = strrpos($stringCut, ' ');

            //if the string doesn't contain any space then it will cut without word basis.
            $string = $endPoint ? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);

            $string .= ($link) ? "<a href='#" . $link . "' data-toggle='collapse' aria-expanded='false' aria-controls='" . $link . "' >Read more...</a>" : "....";
        }

        return $string;
    }

}

function expensegraphColors($color = null) {

    $colors = array(
        '1' => "#9966ff",
        '2' => "#36a2eb",
        '3' => "#ff9f40",
        '4' => "#715d20",
        '5' => "#c9cbcf",
        '6' => "#4bc0c0",
        '7' => "#ffcd56",
        '8' => "#66aa18",
    );
    if ($color == null) {
        return $colors;
    } else {
        return $colors[$color];
    }
}

function incomegraphColors($color = null) {

    $colors = array(
        '1' => "#66aa18",
        '2' => "#ffcd56",
        '3' => "#4bc0c0",
        '4' => "#c9cbcf",
        '5' => "#715d20",
        '6' => "#ff9f40",
        '7' => "#36a2eb",
        '8' => "#9966ff",
    );
    if ($color == null) {
        return $colors;
    } else {
        return $colors[$color];
    }
}

function isJSON($string) {
    return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
}

function currentTime() {
    return date("d/m/y : H:i:s", time());
}

function markSheetDigit() {
    $number = 190908100.25;
    $no = floor($number);
    $point = round($number - $no, 2) * 100;
    $hundred = null;
    $digits_1 = strlen($no);
    $i = 0;
    $str = array();
    $words = array('0' => '', '1' => 'one', '2' => 'two',
        '3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
        '7' => 'seven', '8' => 'eight', '9' => 'nine',
        '10' => 'ten', '11' => 'eleven', '12' => 'twelve',
        '13' => 'thirteen', '14' => 'fourteen',
        '15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
        '18' => 'eighteen', '19' => 'nineteen', '20' => 'twenty',
        '30' => 'thirty', '40' => 'forty', '50' => 'fifty',
        '60' => 'sixty', '70' => 'seventy',
        '80' => 'eighty', '90' => 'ninety');
    $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
    while ($i < $digits_1) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += ($divider == 10) ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
            $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
            $str[] = ($number < 21) ? $words[$number] .
                    " " . $digits[$counter] . $plural . " " . $hundred :
                    $words[floor($number / 10) * 10]
                    . " " . $words[$number % 10] . " "
                    . $digits[$counter] . $plural . " " . $hundred;
        } else {
            $str[] = null;
        }
    }
    $str = array_reverse($str);
    $result = implode('', $str);
    $points = ($point) ?
            "." . $words[$point / 10] . " " .
            $words[$point = $point % 10] : '';
    return $result . $points;
}


function markSheetDigitTwo($digit) {
    $number = (float)$digit;
    $no = floor($number);
    $point = round($number - $no, 2) * 100;
    $hundred = null;
    $digits_1 = strlen($no);
    $i = 0;
    $str = array();
    $words = array('0' => '', '1' => 'one', '2' => 'two',
        '3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
        '7' => 'seven', '8' => 'eight', '9' => 'nine',
        '10' => 'ten', '11' => 'eleven', '12' => 'twelve',
        '13' => 'thirteen', '14' => 'fourteen',
        '15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
        '18' => 'eighteen', '19' => 'nineteen', '20' => 'twenty',
        '30' => 'thirty', '40' => 'forty', '50' => 'fifty',
        '60' => 'sixty', '70' => 'seventy',
        '80' => 'eighty', '90' => 'ninety');
    $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
    while ($i < $digits_1) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += ($divider == 10) ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? '' : null;
            $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
            $str[] = ($number < 21) ? $words[$number] .
                    " " . $digits[$counter] . $plural . " " . $hundred :
                    $words[floor($number / 10) * 10]
                    . " " . $words[$number % 10] . " "
                    . $digits[$counter] . $plural . " " . $hundred;
        } else {
            $str[] = null;
        }
    }
    $str = array_reverse($str);
    $result = implode('', $str);
    $points = ($point) ?
            "." . $words[$point / 10] . " " .
            $words[$point = $point % 10] : '';
    return $result . $points;
}

function getSecondsFromHMS($time) {
    $timeArr = array_reverse(explode(":", $time));    
    $seconds = 0;
    foreach ($timeArr as $key => $value) {
        if ($key > 2)
            break;
        $seconds += pow(60, $key) * $value;
    }
    return $seconds;
}

function getHMSFromSeconds($seconds) {
  $t = round($seconds);
  return sprintf('%02d:%02d:%02d', ($t/3600),($t/60%60), $t%60);
}


function array_insert(&$array, $position, $insert)
{
    if (is_int($position)) {
        array_splice($array, $position, 0, $insert);
    } else {
        $pos   = array_search($position, array_keys($array));
        $array = array_merge(
            array_slice($array, 0, $pos),
            $insert,
            array_slice($array, $pos)
        );
    }
}

function amountFormat($amount){
    return number_format((float)$amount, 2, '.', '');
}
function user_tracking()
{
    $CI1 = &get_instance();
    $userdata           = $CI1->session->userdata('admin');
    
    if(!empty($userdata))
    {
        
        $data = array(
            'user_id' => $userdata['id'],
            'url'     => "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]",
            'url_time' => date('Y-m-d H:i:s'),
        );
        $CI1->db->insert('user_activity',$data);
    }
}
