<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'JST Content',
    'description' => 'Provides several flux content elements.',
    'category' => 'misc',
    'author' => 'Josua Stabenow',
    'author_email' => 'josua.stabenow@gmx.de',
    'author_company' => 'private',
    'shy' => '',
    'dependencies' => 'vhs,flux',
    'conflicts' => '',
    'priority' => '',
    'module' => '',
    'state' => 'stable',
    'internal' => '',
    'uploadfolder' => 0,
    'createDirs' => '',
    'modify_tables' => '',
    'clearCacheOnLoad' => 1,
    'lockType' => '',
    'version' => '1.0',
    'constraints' => [
        'depends' => [
            'typo3' => '8.7.4-8.7.99',
            'vhs' => '',
			'flux' => ''
        ],
        'conflicts' => [],
        'suggests' => [
			'gridelements' => '',
			'flux' => '',
			'frontend' => '',
			'fluidbootstraptheme' => '',			
			'jst_onepage' => '',
		],
    ]
];
