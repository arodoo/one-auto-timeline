<?php
return [
    // Vehicle B fields (mirroring section2 but with s3_ prefix)
    's3_insured_name' => ['x' => 155, 'y' => 59, 'font_size' => 6],
    's3_insured_firstname' => ['x' => 155, 'y' => 64.5, 'font_size' => 6],
    's3_insured_address' => ['x' => 155, 'y' => 70, 'font_size' => 6],
    's3_insured_postal' => ['x' => 161, 'y' => 74, 'font_size' => 6],
    's3_insured_contact' => ['x' => 161, 'y' => 80, 'font_size' => 6],
    's3_insured_country' => ['x' => 187, 'y' => 74, 'font_size' => 6],

    // Vehicle details B
    's3_vehicle_brand' => ['x' => 144, 'y' => 98, 'font_size' => 6],
    's3_vehicle_plate' => ['x' => 144, 'y' => 106.5, 'font_size' => 6],
    's3_vehicle_country' => ['x' => 144, 'y' => 114, 'font_size' => 6],
    's3_trailer_plate' => ['x' => 179, 'y' => 106.5, 'font_size' => 6],
    's3_trailer_country' => ['x' => 179, 'y' => 114, 'font_size' => 6],

    // Insurance B
    's3_insurance_name' => ['x' => 153, 'y' => 126, 'font_size' => 6],
    's3_insurance_contract' => ['x' => 161, 'y' => 131, 'font_size' => 6],
    's3_insurance_green_card' => ['x' => 166, 'y' => 136, 'font_size' => 6],
    's3_insurance_valid_from' => ['x' => 181, 'y' => 144, 'font_size' => 5],
    's3_insurance_valid_to' => ['x' => 199, 'y' => 144, 'font_size' => 5],
    's3_insurance_agency' => ['x' => 182, 'y' => 147, 'font_size' => 6],
    's3_agency_name' => ['x' => 156, 'y' => 152, 'font_size' => 6],
    's3_agency_address' => ['x' => 156, 'y' => 157, 'font_size' => 6],
    's3_agency_country' => ['x' => 176, 'y' => 161, 'font_size' => 6],
    's3_agency_phone' => ['x' => 159, 'y' => 166, 'font_size' => 6],
    's3_has_damage_coverage' => [
        'yes' => ['x' => 172, 'y' => 176, 'font_size' => 6],
        'no' => ['x' => 190, 'y' => 176, 'font_size' => 6]
    ],
    's3_coverage_no' => ['x' => 190, 'y' => 176, 'font_size' => 6],

    // Driver B
    's3_driver_name' => ['x' => 153, 'y' => 184, 'font_size' => 6],
    's3_driver_firstname' => ['x' => 156, 'y' => 189, 'font_size' => 6],
    's3_driver_birthdate' => ['x' => 167, 'y' => 195, 'font_size' => 6],
    's3_driver_address' => ['x' => 156, 'y' => 200, 'font_size' => 6],
    's3_driver_country' => ['x' => 176, 'y' => 204, 'font_size' => 6],
    's3_driver_contact' => ['x' => 159, 'y' => 208, 'font_size' => 6],
    's3_license_number' => ['x' => 170, 'y' => 213, 'font_size' => 6],
    's3_license_category' => ['x' => 168, 'y' => 218, 'font_size' => 6],
    's3_license_valid_until' => ['x' => 174, 'y' => 223, 'font_size' => 6],

    // Impact point for vehicle B
    's3_impact_point' => [
        'x' => 176.53,
        'y' => 243.42,
        'max_x' => 213.02,
        'max_y' => 267.46,
        'type' => 'image'
    ],

    // Damage marks and descriptions B
    's3_damage_description' => [
        'x' => 178,
        'y' => 276,
        'max_x' => 210,
        'max_y' => 284,
        'font_size' => 6,
        'spacing' => 3.7
    ],
    's3_observations' => [
        'x' => 152,
        'y' => 294,
        'max_x' => 210,
        'max_y' => 300.5,
        'font_size' => 6,
        'spacing' => 4.2
    ],

    // Signature field for vehicle B
    's7_signature_b' => [
        'x' => 106,
        'y' => 295,
        'max_x' => 150,
        'max_y' => 310,
        'type' => 'image'
    ]
];
