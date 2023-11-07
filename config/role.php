<?php

$arr = [
    'dashboard' => [
        'label' => "Dashboard",
        'access' => [
            'view' => [
                'admin.dashboard'
            ],
            'add' => [],
            'edit' => [],
            'delete' => [],
        ],
    ],

    'manage_staff' => [
        'label' => "Manage Staff",
        'access' => [
            'view' => [
                'admin.staff',
            ],
            'add' => [
                'admin.staff.create',
            ],
            'edit' => [
                'admin.staff.edit',
            ],
            'delete' => [
                'admin.staff.delete',
            ],
        ],
    ],

    'ipblock_management' => [
        'label' => "Ip Block Management",
        'access' => [
            'view' => [
                'admin.ipblock',
            ],
            'add' => [
                'admin.ipblock.create',
            ],
            'edit' => [
                'admin.ipblock.edit',
            ],
            'delete' => [
                'admin.ipblock.delete',
            ],
        ],
    ],

    'tags_management' => [
        'label' => "Tags Management",
        'access' => [
            'view' => [
                'admin.tags',
            ],
            'add' => [
                'admin.tags.create',
            ],
            'edit' => [
                'admin.tags.edit',
            ],
            'delete' => [
                'admin.tags.delete',
            ],
        ],
    ],

    'domain_management' => [
        'label' => "Domain Management",
        'access' => [
            'view' => [
                'admin.domain',
            ],
            'add' => [
                'admin.domain.create',
            ],
            'edit' => [
                'admin.domain.edit',
            ],
            'delete' => [
                'admin.domain.delete',
            ],
        ],
    ],

    'wallpapers_management' => [
        'label' => "Wallpapers Management",
        'access' => [
            'view' => [
                'admin.wallpapers',
            ],
            'add' => [
                'admin.wallpapers.create',
            ],
            'edit' => [
                'admin.wallpapers.edit',
            ],
            'delete' => [
                'admin.wallpapers.delete',
            ],
        ],
    ],

    'ringtones_management' => [
        'label' => "Ringtones Management",
        'access' => [
            'view' => [
                'admin.ringtones',
            ],
            'add' => [
                'admin.ringtones.create',
            ],
            'edit' => [
                'admin.ringtones.edit',
            ],
            'delete' => [
                'admin.ringtones.delete',
            ],
        ],
    ],

    'musics_management' => [
        'label' => "Musics Management",
        'access' => [
            'view' => [
                'admin.musics',
            ],
            'add' => [
                'admin.musics.create',
            ],
            'edit' => [
                'admin.musics.edit',
            ],
            'delete' => [
                'admin.musics.delete',
            ],
        ],
    ],

    'website_controls' => [
        'label' => "Website Controls",
        'access' => [
            'view' => [
                'admin.basic-controls',
                'admin.color-settings',
            ],
            'add' => [],
            'edit' => [
                'admin.basic-controls.update',
                'admin.color-settings.update',
            ],
            'delete' => [],
        ],
    ],

    'language_settings' => [
        'label' => "Language Settings",
        'access' => [
            'view' => [
                'admin.language.index',
            ],
            'add' => [
                'admin.language.create',
            ],
            'edit' => [
                'admin.language.edit',
                'admin.language.keywordEdit',
            ],
            'delete' => [
                'admin.language.delete',
            ],
        ],
    ],

    'theme_settings' => [
        'label' => "Theme Settings",
        'access' => [
            'view' => [
                'admin.logo-seo',
                'admin.breadcrumb',
            ],
            'add' => [
            ],
            'edit' => [
                'admin.logoUpdate',
                'admin.seoUpdate',
                'admin.breadcrumbUpdate',
            ],
            'delete' => [
            ],
        ],
    ],
];

return $arr;



