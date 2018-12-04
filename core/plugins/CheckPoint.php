<?php
/**
 * Created by PhpStorm.
 * User: nhanphong
 * Date: 6/4/18
 * Time: 10:51 AM
 *
 * Router, ví dụ: '/index.php/news' -> '/ news'
 * Giải pháp này đã được thực hiện để tạo thuận lợi cho cấu hình của máy chủ web và việc thực hiện các yêu cầu SEO
 */

namespace Zodiac\Plugin;

use Phalcon\Http\Request;

class CheckPoint
{

    public function __construct(Request $request)
    {
        if (strpos($request->getURI(), 'index.php') || strpos($request->getURI(), 'index.html')) {
            header('HTTP/1.0 301 Moved Permanently');
            $replaced_url = str_replace(
                ['index.php/', 'index.php', 'index.html'],
                ['', '', ''],
                str_replace('?', '', $request->getURI())
            );
            header('Location: http://' . $request->getHttpHost() . $replaced_url);
            exit(0);
        }
    }

}