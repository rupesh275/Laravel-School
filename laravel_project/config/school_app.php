<?php

return [
    'admission_digit_length' => 6, // from adm_digit_length

    'exam_types' => [ // from exam_type, lang helpers will be replaced later
        'basic_system'        => 'Basic System', // Placeholder, replace with __() later
        'school_grade_system' => 'School Grade System', // Placeholder
        'coll_grade_system'   => 'College Grade System', // Placeholder
        'eva6_grade_system'   => 'Evaluation 6 Grade System', // Placeholder
        'gpa'                 => 'GPA', // Placeholder
    ],

    // Custom values from CodeIgniter's config.php
    'routine_session' => 16,
    'routine_update' => 1752604200,
    'installed' => true,

    // Note: File validation rules (image_validate, csv_validate, file_validate)
    // from CI's app-config.php will be translated to Laravel validation rules
    // directly in controllers/form requests, not stored as config arrays here.
];
