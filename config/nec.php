<?php

return [
    'regions' => [
        'bahr_el_ghazal' => [
            'name' => 'Bahr el Ghazal',
            'states' => ['Northern Bahr el Ghazal', 'Western Bahr el Ghazal', 'Warrap', 'Lakes'],
            'admin_areas' => ['Abyei Special Administrative Area'],
        ],
        'equatoria' => [
            'name' => 'Equatoria',
            'states' => ['Central Equatoria', 'Eastern Equatoria', 'Western Equatoria'],
            'admin_areas' => [],
        ],
        'greater_upper_nile' => [
            'name' => 'Greater Upper Nile',
            'states' => ['Jonglei', 'Unity', 'Upper Nile'],
            'admin_areas' => ['Greater Pibor Administrative Area', 'Ruweng Administrative Area'],
        ],
    ],

    'administrative_areas' => [
        'Abyei Special Administrative Area',
        'Greater Pibor Administrative Area',
        'Ruweng Administrative Area',
    ],

    'total_constituencies' => 102,
    'total_polling_stations' => 3284,
    'total_states' => 10,
    'election_year' => 2026,

    // Voter age eligibility. Citizens may pre-register from this age (measured
    // against 31 Dec of the election year) and become eligible to vote once 18.
    'minimum_registration_age' => 16,
    'voting_age' => 18,
];
