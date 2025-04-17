<?php
return [
    // Section 2 fields with corrected names matching the database 
    's2_insured_name' => [
        'x' => 22.97,
        'y' => 60,
        'font_size' => 6,
        'description' => 'Insured name'
    ],
    's2_insured_firstname' => [
        'x' => 28,
        'y' => 65.13,
        'font_size' => 6,
        'description' => 'Insured firstname'
    ],
    's2_insured_address' => [
        'x' => 26.07,
        'y' => 70.36,
        'font_size' => 6,
        'description' => 'Insured address'
    ],
    's2_insured_postal' => ['x' => 32, 'y' => 74, 'font_size' => 6],
    's2_insured_country' => ['x' => 58, 'y' => 74, 'font_size' => 6],
    's2_insured_contact' => ['x' => 32, 'y' => 80, 'font_size' => 6],
    
    // Vehicle details A
    's2_vehicle_brand' => ['x' => 15, 'y' => 98, 'font_size' => 6],
    's2_vehicle_plate' => ['x' => 15, 'y' => 106.5, 'font_size' => 6],
    's2_vehicle_country' => ['x' => 15, 'y' => 114, 'font_size' => 6],
    's2_trailer_plate' => ['x' => 50, 'y' => 106.5, 'font_size' => 6],
    's2_trailer_country' => ['x' => 50, 'y' => 114, 'font_size' => 6],

    // Insurance details
    's2_insurance_name' => ['x' => 24, 'y' => 126, 'font_size' => 6],
    's2_insurance_contract' => ['x' => 32, 'y' => 131, 'font_size' => 6],
    's2_insurance_green_card' => ['x' => 37, 'y' => 136, 'font_size' => 6],
    's2_insurance_valid_from' => ['x' => 52, 'y' => 144, 'font_size' => 5],
    's2_insurance_valid_to' => ['x' => 70, 'y' => 144, 'font_size' => 5],
    's2_insurance_agency' => ['x' => 53, 'y' => 147, 'font_size' => 6],
    's2_agency_name' => ['x' => 27, 'y' => 152, 'font_size' => 6],
    's2_agency_address' => ['x' => 27, 'y' => 157, 'font_size' => 6],
    's2_agency_country' => ['x' => 47, 'y' => 161, 'font_size' => 6],
    's2_agency_phone' => ['x' => 30, 'y' => 166, 'font_size' => 6],
    's2_has_damage_coverage' => [
        'yes' => ['x' => 42, 'y' => 176, 'font_size' => 6],
        'no' => ['x' => 60, 'y' => 176, 'font_size' => 6]
    ],
    
    // Driver details
    's2_driver_name' => ['x' => 24, 'y' => 184, 'font_size' => 6],
    's2_driver_firstname' => ['x' => 27, 'y' => 189, 'font_size' => 6],
    's2_driver_birthdate' => ['x' => 38, 'y' => 195, 'font_size' => 6],
    's2_driver_address' => ['x' => 27, 'y' => 200, 'font_size' => 6],
    's2_driver_country' => ['x' => 47, 'y' => 204, 'font_size' => 6],
    's2_driver_contact' => ['x' => 30, 'y' => 208, 'font_size' => 6],
    's2_license_number' => ['x' => 41, 'y' => 213, 'font_size' => 6],
    's2_license_category' => ['x' => 39, 'y' => 218, 'font_size' => 6],
    's2_license_valid_until' => ['x' => 45, 'y' => 223, 'font_size' => 6],
    
    // Damage marks and descriptions
        's2_damage_description' => [
        'x' => 15,
        'y' => 276,
        'max_x' => 47,
        'max_y' => 284,
        'font_size' => 6,
        'spacing' => 3.7
    ],
    's2_observations' => [
        'x' => 14.5,
        'y' => 294.5,
        'max_x' => 71.5,
        'max_y' => 300.5,
        'font_size' => 6,
        'spacing' => 4.2
    ],

    // Impact point diagram coordinates from test-data.json
    's2_impact_point' => [
        'x' => 13.46,
        'y' => 243.84,
        'max_x' => 49.87,
        'max_y' => 267.46,
        'type' => 'image'
    ],

    // Signature field with coordinates from test-data.json P1-S6-N14-A1
    's6_signature_a' => [
        'x' => 82,
        'y' => 295,
        'max_x' => 125,
        'max_y' => 310,
        'type' => 'image'
    ]
];
