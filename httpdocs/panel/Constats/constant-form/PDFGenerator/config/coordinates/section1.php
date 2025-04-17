<?php
return [
    // Date, time and location
    's1_accident_date' => [
        'x' => 15.97, 
        'y' => 29.13, 
        'font_size' => 8,
        'description' => 'Date of accident'
    ],
    's1_accident_time' => [
        'x' => 46, 
        'y' => 29.13, 
        'font_size' => 8,
        'description' => 'Time of accident'
    ],
    's1_accident_location' => [
        'x' => 73.07, 
        'y' => 28.36, 
        'font_size' => 8,
        'description' => 'Country'
    ],
    's1_accident_place' => [
        'x' => 99.02, 
        'y' => 21.13, 
        'max_x' => 120.5,
        'max_y' => 28.5,
        'font_size' => 5,
        'description' => 'Detailed location'
    ],

    // Injuries and damages
    's1_has_injuries' => [
        'yes' => ['x' => 136.76, 'y' => 29.04, 'font_size' => 8],
        'no' => ['x' => 153.76, 'y' => 29.04, 'font_size' => 8]
    ],
    's1_has_vehicle_damage' => [
        'yes' => ['x' => 20.16, 'y' => 44.03, 'font_size' => 8],
        'no' => ['x' => 36.5, 'y' => 44.03, 'font_size' => 8]
    ],
    's1_has_object_damage' => [
        'yes' => ['x' => 53.5, 'y' => 44.03, 'font_size' => 8],
        'no' => ['x' => 70, 'y' => 44.03, 'font_size' => 8]
    ],

    // Single witness field
    's1_witnesses_info' => [
        'x' => 84, 
        'y' => 38.5,
        'max_x' => 205.5,
        'max_y' => 44.5,
        'font_size' => 8,
        'description' => 'Witnesses information'
    ],
];
