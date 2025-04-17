<?php
return [
    // Police report section - matching data-db-name attributes
    's10_has_police_report' => [
        'yes' => ['x' => 111, 'y' => 140, 'font_size' => 8],
        'no' => ['x' => 126.5, 'y' => 140, 'font_size' => 8]
    ],
    's10_has_police_statement' => [
        'yes' => ['x' => 189, 'y' => 140, 'font_size' => 8],
        'no' => ['x' => 204.6, 'y' => 140, 'font_size' => 8]
    ],
    's10_has_incident_report' => [
        'yes' => ['x' => 66, 'y' => 149, 'font_size' => 8],
        'no' => ['x' => 81.6, 'y' => 149, 'font_size' => 8]
    ],
    's10_police_station' => ['x' => 148.5, 'y' => 148.5, 'font_size' => 8],

    // Vehicle section
    's10_vehicle_garage' => ['x' => 96.5, 'y' => 157, 'font_size' => 8],
    's10_repair_shop' => [
        'x' => 150,
        'y' => 162.5,
        'max_x' => 208,
        'max_y' => 169,
        'font_size' => 8,
        'spacing' => 1
    ],
    's10_repair_phone' => ['x' => 32, 'y' => 175, 'font_size' => 8],
    's10_repair_fax' => ['x' => 96, 'y' => 175, 'font_size' => 8],
    's10_repair_email' => ['x' => 166.5, 'y' => 175, 'font_size' => 8],
    's10_contact_phone' => ['x' => 162.5, 'y' => 180.5, 'font_size' => 8],
    's10_police_date' => ['x' => 40, 'y' => 180.5, 'font_size' => 8],

    // Heavy vehicle section
    's10_truck_weight' => ['x' => 155, 'y' => 195.3, 'font_size' => 8],
    's10_trailer_weight' => ['x' => 190, 'y' => 198.4, 'font_size' => 8],
    's10_trailer_insurance' => ['x' => 87.5, 'y' => 204.6, 'font_size' => 6],
    's10_trailer_contract' => ['x' => 171, 'y' => 204, 'font_size' => 8],

    // Other damage
    's10_other_damage' => [
        'x' => 24,
        'y' => 217.6,
        'max_x' => 203.8,
        'max_y' => 222,
        'font_size' => 8,
        'spacing' => 1.6
    ]
];
