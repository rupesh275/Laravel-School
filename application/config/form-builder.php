<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

$this->CI = &get_instance();
$this->CI->load->library('captchalib');
$this->CI->load->helper('custom');
// $is_captcha         = $this->CI->captchalib->is_captcha('login');

// array(/* HIDDEN */
//                     'id' => 'id',
//                     'type' => 'hidden',
//                     'value' => $item->id
//             ),
//             array(/* INPUT */
//                     'id' => 'color',
//                     'placeholder' => 'Item Color',
//                     'input_addons' => array(
//                             'pre' => 'color: #',
//                             'post' => ';'
//                     ),
//                     'help' => 'this is a help block'
//             ),
//             array(/* DROP DOWN */
//                     'id' => 'published',
//                     'type' => 'dropdown',
//                     'options' => array(
//                             '1' => 'Published',
//                             '2' => 'Disabled'
//                     )
//             ),
//             array(/* TEXTAREA */
//                     'id' => 'description',
//                     'type' => 'textarea',
//                     'class' => 'wysihtml5',
//                     'placeholder' => 'Item Description (HTML or rich text)',
//                     'value' => html_entity_decode($item->description)
//             ),
//             array(/* COMBINE */
//                     'id' => 'expiration_date',
//                     'type' => 'combine', /* use `combine` to put several input inside the same block */
//                     'elements' => array(
//                             array(
//                                     'id' => 'cc_exp_month',
//                                     'label' => 'Expiration Date',
//                                     'autocomplete' => 'cc-exp-month',
//                                     'type' => 'dropdown',
//                                     'options' => $exp_month_options,
//                                     'class' => $input_span . 'required input-small',
//                                     'required' => '',
//                                     'data-items' => '4',
//                                     'pattern' => '\d{1,2}',
//                                     'style' => 'width: auto;',
//                                     'value' => (isset($cc_exp_month) ? $cc_exp_month : '')
//                             ),
//                             array(
//                                     'id' => 'cc_exp_year',
//                                     'label' => 'Expiration Date',
//                                     'autocomplete' => 'cc-exp-year',
//                                     'type' => 'dropdown',
//                                     'options' => $exp_year_options,
//                                     'class' => $input_span . 'required input-small',
//                                     'required' => '',
//                                     'data-items' => '4',
//                                     'pattern' => '\d{4}',
//                                     'style' => 'width: auto; margin-left: 5px;',
//                                     'value' => (isset($cc_exp_year) ? $cc_exp_year : '')
//                             )
//                     )
//             ),
//             array(/* DATE */
//                     'id' => 'date',
//                     'type' => 'date'
//             ),
//             array(/* CHECKBOX */
//                     'id' => 'checkbox_group',
//                     'label' => 'Checkboxes',
//                     'type' => 'checkbox',
//                     'options' => array(
//                             array(
//                                     'id' => 'checkbox1',
//                                     'value' => 1
//                                     // If no label is set, the value will be used
//                             ),
//                             array(
//                                     'id' => 'checkbox2',
//                                     'value' => 2,
//                                     'label' => 'Two'
//                             )
//                     )
//             ),
//             array(/* RADIO */
//                     'id' => 'radio_group',
//                     'label' => 'Radio buttons',
//                     'type' => 'radio',
//                     'options' => array(
//                             array(
//                                     'id' => 'radio_button_yes',
//                                     'value' => 1,
//                                     'label' => 'Yes'
//                             ),
//                             array(
//                                     'id' => 'radio_button_no',
//                                     'value' => 0,
//                                     'label' => 'No'
//                             )
//                     )
//             ),
//             array(/* SUBMIT */
//                     'id' => 'submit',
//                     'type' => 'submit'
//             )
// Config variables

$config['contact_us'] = array(

    'email_title' => array( /* HIDDEN */
        'id'            => 'email_title',
        'type'          => 'hidden',
        'value'         => 'New Inquiry from Contact US',
        'mail_response' => lang('thanks_for_contacting_us'),
    ),
    'name'        => array(
        'id'          => 'name',
        'placeholder' => lang('enter') . " " . lang('your') . " " . lang('name'),
        'validation'  => 'trim|required|xss_clean',
    ),
    'email'       => array(
        'id'          => 'email',
        'type'        => 'email',
        'placeholder' => lang('enter') . " " . lang('your') . " " . lang('email'),
        'validation'  => 'trim|required|valid_email|xss_clean',
    ),
    'subject'     => array(
        'id'          => 'subject',
        'placeholder' => lang('enter') . " " . lang('subject'),
        'validation'  => 'trim|required|xss_clean',
    ),
    'description' => array( /* TEXTAREA */
        'id'          => 'description',
        'type'        => 'textarea',
        'placeholder' => lang('enter') . " " . lang('description'),
    ),
    'submit'      => array( /* SUBMIT */
        'id'   => 'submit',
        'type' => 'submit',
    ),
);


$config['complain'] = array(
    
    'email_title' => array( /* HIDDEN */
        'id'            => 'email_title',
        'type'          => 'hidden',
        'value'         => 'New Inquiry from Complain',
        'mail_response' => lang('thank_you_for_your_complain'), 
    ),
    'name'        => array(
        'id'          => 'name',
        'placeholder' => lang('enter') . " " . lang('your') . " " . lang('name'),
        'validation'  => 'trim|required|xss_clean',
    ),
    'email'       => array(
        'id'          => 'email',
        'type'        => 'email',
        'placeholder' => lang('enter') . " " . lang('your') . " " . lang('email'),
        'validation'  => 'trim|required|valid_email|xss_clean',
    ),
    'contact_no'  => array(
        'id'          => 'contact_no',
        'placeholder' => lang('enter') . " " . lang('contact_number'),
        'validation'  => 'trim|required|xss_clean',
    ),
    'description' => array( /* TEXTAREA */
        'id'          => 'description',
        'type'        => 'textarea',
        'placeholder' => lang('enter') . " " . lang('description'),
    ),

    'submit'      => array( /* SUBMIT */
        'id'   => 'submit',
        'type' => 'submit',
    ),
);
/*
    $options = "array(";
                            foreach ($Reference as $key => $value) { 
                             $options .= $value["source"] . "=>". $value["source"] .",";
                             }  
                             $options .= ")";
*/
$config['enquiry'] = array(

    'email_title' => array( /* HIDDEN */
        'id'            => 'email_title',
        'type'          => 'hidden',
        'value'         => 'New Enquiry For Admission',
        'mail_response' => lang('thanks_for_contacting_us'),
    ),
    'name'        => array(
        'id'          => 'name',
        'placeholder' => lang('enter') . " " . lang('your') . " " . lang('name'),
        'validation'  => 'trim|required|xss_clean',
    ),
    'contact'        => array(
        'id'          => 'contact',
        'placeholder' => lang('enter') . " " . lang('your') . " " . "Contact",
        'validation'  => 'trim|required|xss_clean',
    ),
    'addess'        => array(
        'id'          => 'address',
        'rows'        => '5',
        'type'        => 'textarea',
        'placeholder' => lang('enter') . " " . lang('your') . " " . "Address",
        'validation'  => 'trim|required|xss_clean',
    ),  
    'email'       => array(
        'id'          => 'email',
        'type'        => 'email',
        'placeholder' => lang('enter') . " " . lang('your') . " " . lang('email'),
        'validation'  => 'trim|required|valid_email|xss_clean',
    ),
  
    'reference'       => array(
        'id'          => 'reference',
        'type'        => 'dropdown',
        'options'     => array(
                        'By Direct' => 'By Direct',
                        'Google Ads' => 'Google Ads',
                        'Facebook' => 'Facebook',
                        'Other Add' => 'Other Add',
                        'Staff' => 'Staff',
                        'Parents' => 'Parents',
                        'Trust Member' => 'Trust Members'
        ),
        'validation'  => 'trim|required|xss_clean'
    ),
    'class'       => array(
        'id'          => 'class',
        'type'        => 'dropdown',
        'options'     => array(
                        'Nursery' => 'Nursery',
                        'Jr.Kg' => 'Jr.Kg',
                        'Sr.Kg' => 'Sr.Kg',
                        'Class I' => 'Class I',
                        'Class II' => 'Class II',
                        'Class III' => 'Class III',
                        'Class IV' => 'Class IV',
                        'Class V' => 'Class V',
                        'Class VI' => 'Class VI',
                        'Class VII' => 'Class VII',
        ),
        'validation'  => 'trim|required|xss_clean'
    ),
    'no_of_child'        => array(
        'id'          => 'no_of_child',
        'placeholder' => "No.of Child",
        'validation'  => 'trim|required|xss_clean',
    ),
    
    'description' => array( /* TEXTAREA */
        'id'          => 'description',
        'type'        => 'textarea',
        'placeholder' => lang('enter') . " " . lang('description'),
    ),
    'submit'      => array( /* SUBMIT */
        'id'   => 'submit',
        'type' => 'submit',
    ),
);

$is_captcha_complain = $this->CI->captchalib->is_captcha('complain');

if ($is_captcha_complain) {
    $complain_captcha = array( /* TEXTAREA */
        'id'          => 'captcha',
        'type'        => 'captcha',
        'placeholder' => lang('enter') . " " . lang('captcha'),
        'validation'  => 'trim|required|xss_clean|callback_check_captcha',
    );
    array_insert(
        $config['complain'],
        'submit',
        [
            'captcha' => $complain_captcha,
        ]
    );

}
$is_captcha_contact_us = $this->CI->captchalib->is_captcha('contact_us');

if ($is_captcha_contact_us) {
    $contact_us_captcha = array( /* TEXTAREA */
        'id'          => 'captcha',
        'type'        => 'captcha',
        'placeholder' => lang('enter') . " " . lang('captcha'),
        'validation'  => 'trim|required|xss_clean|callback_check_captcha',
    );
    array_insert(
        $config['contact_us'],
        'submit',
        [
            'captcha' => $contact_us_captcha,
        ]
    );

}
