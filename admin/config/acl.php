<?php
return [

    // frontend
    'guest'      => [
        'admin/index'       => [
            'index',
            'login',
            'logout'
        ],
    ],
    'member'     => [
    ],
    // backend
    'journalist' => [
        'admin/index'       => '*',
        'index/index'       => '*',
        'index/error'       => '*',
        'page/index'        => '*',
        'publication/index' => '*',
        'sitemap/index'     => '*',
        'api/index'         => '*',
        'api/page'          => '*',
        'publication/admin'  => [
            'index',
            'add',
            'edit',
        ],
        'page/admin'         => [
            'index',
            'add',
            'edit',
        ],
        'file-manager/index' => '*',
        'category/admin' => '*',
    ],
    'editor'     => [
        'publication/admin'  => '*',
        'publication/type'   => '*',
        'cms/translate'      => '*',
        'widget/admin'       => '*',
        'file-manager/index' => '*',
        'page/admin'         => '*',
        'tree/admin'         => '*',
        'seo/sitemap'        => '*',
    ],
    'admin'      => [
        'admin/admin-user'   => '*',
        'media/index'        => '*',
        'category/admin'     => '*',
        'tag/admin'          => '*',
        'post/admin'         => '*',
        'menu/admin'         => '*',
        'cms/configuration'  => '*',
        'cms/translate'      => '*',
        'cms/language'       => '*',
        'cms/javascript'     => '*',
        'widget/admin'       => '*',
        'file-manager/index' => '*',
        'page/admin'         => '*',
        'publication/admin'  => '*',
        'publication/type'   => '*',
        'seo/robots'         => '*',
        'seo/sitemap'        => '*',
        'seo/manager'        => '*',
        'tree/admin'         => '*',
        'setting/index'      => '*',
        'theme/index'        => '*',
        'theme/default'      => '*',
        'theme/spectr'       => '*',
        'theme/hotmagazine'  => '*',
        'theme/blog'         => '*',
    ],
];