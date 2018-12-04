<?php

return [

    // frontend
    'guest'      => [
        'admin/index'       => '*',
        'index/index'       => '*',
        'index/error'       => '*',
        'page/index'        => '*',
        'publication/index' => '*',
        'sitemap/index'     => '*',
        'api/index'         => '*',
        'api/district'         => '*',
        'api/upload'         => '*',
        'api/page'          => '*',
        'shop-mbn/index'    => '*',
        'post/index'        => '*',
        'post/category'     => '*',
        'post/tag'          => '*',
        'post/user'          => '*',
        'search/index'      => '*',
        'contact/index'      => '*',
        'product/index'        => '*',
        'product/category'     => '*',
        'cart/index'     => '*',
    ],
    'member'     => [
        'index/index' => '*',
        'shop-mbn/index'        => '*',
        'admin/index'       => [
            'login',
            //'index'
        ],
        'admin/admin-user'       => [
            'index',
            'profile',
            'activities',
            'listPost',
            'addPost',
            'editPost'
        ],
    ],
    // backend
    'journalist' => [
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
        'admin/admin-user'       => [
            'index',
            'profile',
            'activities'
        ],
    ],
    'admin'      => [
        'admin/admin-user'   => '*',
        'category/admin'     => '*',
        'tag/admin'          => '*',
        'post/admin'          => '*',
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
    ],
];