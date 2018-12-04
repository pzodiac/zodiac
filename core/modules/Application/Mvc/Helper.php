<?php

/**
 * Helper
 * @copyright Copyright (c) 2017 MuaBanNhanh FlatForm
 * @author Nhan Phong <nhanphong@vinadesign.vn>
 */

namespace Application\Mvc;

use Application\Mvc\Router\DefaultRouter;
use Cms\Model\Language;
use Lib\Menu\Helper\Menu;

class Helper extends \Phalcon\Mvc\User\Component
{
    const StaticWidgetDefaultOptions = [
        'lifetime' => 120
    ];

    private $translate = null;
    private $admin_translate = null;

    public $menu;

    public function __construct()
    {
       // $this->menu = Menu::getInstance();
    }

    /**
     * 
     */
    public function translate($string, $placeholders = null)
    {
        if (!$this->translate) {
            $this->translate = $this->getDi()->get('translate');
        }
        return $this->translate->query($string, $placeholders);

    }

    /**
     * 
     */
    public function at($string, $placeholders = null)
    {
        if (!$this->admin_translate) {
            $this->admin_translate = $this->getDi()->get('admin_translate');
        }
        return $this->admin_translate->query($string, $placeholders);

    }

    public function widget($namespace = 'Index', array $params = [])
    {
        return new \Application\Widget\Proxy($namespace, $params);
    }

    public function staticWidget($id, $params = [])
    {
        $mergeConfig = array_merge(self::StaticWidgetDefaultOptions, $params);
        $widget = \Widget\Model\Widget::findFirst(["id='{$id}'", "cache" => ["lifetime" => $mergeConfig["lifetime"], "key" => HOST_HASH . md5("Widget::findFirst({$id})")]]);
        if ($widget) {
            return $widget->getHtml();
        }
    }

    public function langUrl($params)
    {
        $routeName = $params['for'];
        $routeName = DefaultRouter::ML_PREFIX . $routeName . '_' . LANG;
        $params['for'] = $routeName;
        return $this->url->get($params);
    }

    public function languages()
    {
        return Language::findCachedLanguages();

    }

    public function langSwitcher($lang, $string)
    {
        $helper = new \Application\Mvc\Helper\LangSwitcher();
        return $helper->render($lang, $string);
    }

    public function cacheExpire($seconds)
    {
        $response = $this->getDi()->get('response');
        $expireDate = new \DateTime();
        $expireDate->modify("+$seconds seconds");
        $response->setExpires($expireDate);
        $response->setHeader('Cache-Control', "max-age=$seconds");
    }

    public function isAdminSession()
    {
        $session = $this->getDi()->get('session');
        $auth = $session->get('auth');
        if ($auth) {
            if ($auth->admin_session == true) {
                return true;
            }
        }
    }

    public function error($code = 404)
    {
        $helper = new \Application\Mvc\Helper\ErrorReporting();
        return $helper->{'error' . $code}();

    }

    public function title($title = null, $h1 = false)
    {
        return \Application\Mvc\Helper\Title::getInstance($title, $h1);
    }

    public function meta()
    {
        return \Application\Mvc\Helper\Meta::getInstance();
    }

    public function activeMenu()
    {
        return \Application\Mvc\Helper\ActiveMenu::getInstance();
    }

    public function announce($incomeString, $num)
    {
        $object = new \Application\Mvc\Helper\Announce();
        return $object->getString($incomeString, $num);
    }

    public function dbProfiler()
    {
        $object = new \Application\Mvc\Helper\DbProfiler();
        return $object->DbOutput();
    }

    public function constant($name)
    {
        return get_defined_constants()[$name];
    }

    public function image($args, $attributes = [])
    {
        $imageFilter = new \Image\Storage($args, $attributes);
        return $imageFilter;
    }

    public function querySymbol()
    {
        $object = new \Application\Mvc\Helper\RequestQuery();
        return $object->getSymbol();
    }

    public function javascript($id)
    {
        $javascript = \Cms\Model\Javascript::findCachedById($id);
        if ($javascript) {
            return $javascript->getText();
        }
    }

    public function modulePartial($template, $data, $module = null)
    {
        $view = clone $this->getDi()->get('view');
        $partialsDir = '';
        $session = $this->getDi()->get('session');
        $device = $session->get('device_detect');
        if ($module) {
            $moduleName = \Application\Utils\ModuleName::camelize($module);
            $partialsDir = THEME_PATH . '/views/' . $device . '/' . $moduleName . '/';
        }
        $view->setPartialsDir($partialsDir);

        return $view->partial($template, $data);
    }

    public function modulePartialAdmin($template, $data, $module = null)
    {
        $view = clone $this->getDi()->get('view');
        $partialsDir = '';
        if ($module) {
            $moduleName = \Application\Utils\ModuleName::camelize($module);
            $partialsDir = '../../../modules/' . $moduleName . '/views/';
        }
        $view->setPartialsDir($partialsDir);
        return $view->partial($template, $data);
    }

    public function modulePartialLib($template, $data, $module = null)
    {
        $view = clone $this->getDi()->get('view');
        $partialsDir = '';
        if ($module) {
            $moduleName = $module;
            $partialsDir = LIBRARY_PATH .'/modules/' . $moduleName . '/views/';
        }

        $view->setPartialsDir($partialsDir);
        return $view->partial($template, $data);
    }

    /**
     * register parameter breadcumb in view
     */
    public function breadcrumb($breadcrumb = null, $status = false)
    {
        return \Application\Mvc\Helper\Breadcrumb::getInstance($breadcrumb, $status);
    }

    public function htmlSelectCategory($cate, $valueActive, $valueDefault)
    {
        $helper = new \Application\Mvc\Helper\Category();
        return $helper->categorySelect($cate, $valueActive, $valueDefault);
    }

    public function htmlTableCategory($cate, $valueActive = 0)
    {
        $helper = new \Application\Mvc\Helper\Category();
        return $helper->tableCategory($cate, $valueActive);
    }

    public function htmlTableCategoryProduct($cate, $valueActive = 0)
    {
        $helper = new \Application\Mvc\Helper\Category();
        return $helper->tableCategoryProduct($cate, $valueActive);
    }

    public function htmlTableTag($tag)
    {
        $helper = new \Application\Mvc\Helper\Tag();
        return $helper->tableTag($tag);
    }

    public function getCategoryName($id)
    {
        $helper = new \Application\Mvc\Helper\Category();
        return $helper->getName($id);
    }

    public function getTagName($id)
    {
        $helper = new \Application\Mvc\Helper\Tag();
        return $helper->getName($id);
    }

    public function getTagSlug($id)
    {
        $helper = new \Application\Mvc\Helper\Tag();
        return $helper->getSlug($id);
    }

    public function getTagId($name)
    {
        $helper = new \Application\Mvc\Helper\Tag();
        return $helper->getId($name);
    }

    public function flashSuccessHtml($text)
    {
        $html = '<button data-dismiss="alert" class="close">
                    ×
                </button>
                <i class="fa fa-check-circle"></i>';
        $html .= ' ' . $text;  
        return $html;      
    }

    public function flashInfoHtml($text)
    {
        $html = '<button data-dismiss="alert" class="close">
                    ×
                </button>
                <i class="fa fa-info-circle"></i>';
        $html .= ' ' . $text;  
        return $html;      
    }

    public function flashWarningHtml($text)
    {
        $html = '<button data-dismiss="alert" class="close">
                    ×
                </button>
                <i class="fa fa-exclamation-triangle"></i>';
        $html .= ' ' . $text;  
        return $html;      
    }

    public function flashErrorHtml($text)
    {
        $html = '<button data-dismiss="alert" class="close">
                    ×
                </button>
                <i class="fa fa-times-circle"></i>';
        $html .= ' ' . $text;  
        return $html;      
    }

    public function slug($string, $separator = '-')
    {
        $string = self::ascii($string);
        $string = trim(preg_replace('/[^a-zA-Z0-9]/', ' ', $string));
        $string = trim(preg_replace('/[\s]+/', ' ', $string));
        $string = trim(preg_replace('/\s/', $separator, $string));

        return strtolower($string);
    }

    public static function ascii($string)
    {
        $string = preg_replace('/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/', 'a', $string);
        $string = preg_replace('/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/', 'e', $string);
        $string = preg_replace('/(ì|í|ị|ỉ|ĩ)/', 'i', $string);
        $string = preg_replace('/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/', 'o', $string);
        $string = preg_replace('/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/', 'u', $string);
        $string = preg_replace('/(ỳ|ý|ỵ|ỷ|ỹ)/', 'y', $string);
        $string = preg_replace('/(đ)/', 'd', $string);

        $string = preg_replace('/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/', 'A', $string);
        $string = preg_replace('/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/', 'E', $string);
        $string = preg_replace('/(Ì|Í|Ị|Ỉ|Ĩ)/', 'I', $string);
        $string = preg_replace('/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/', 'O', $string);
        $string = preg_replace('/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/', 'U', $string);
        $string = preg_replace('/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/', 'Y', $string);
        $string = preg_replace('/(Đ)/', 'D', $string);

        $string = trim($string);

        return $string;
    }

    public function niceWordsByChars($text, $max_char = 100, $end = '...')
    {
        $text = trim(strip_tags($text));
        $max_char = (int) $max_char;
        $end = trim($end);

        if ($text != '') {
            $text = self::removeJunkSpace($text);
        }

        $output = '';

        if (mb_strlen($text, 'UTF-8') > $max_char) {
            $words = explode(' ', $text);
            $i = 0;

            while (1) {
                $length = mb_strlen($output, 'UTF-8') + mb_strlen($words[$i], 'UTF-8');

                if ($length > $max_char) {
                    break;
                } else {
                    $output .= ' ' . $words[$i];
                    ++$i;
                }
            }

            $output .= $end;
        } else {
            $output = $text;
        }

        return trim($output);
    }

    public static function removeJunkSpace($string)
    {
        $words = array_filter(explode(' ', trim($string)));
        return trim(implode(' ', $words));
    }

    public function currencyFormat($number)
    {
        return number_format($number, 0, ',', '.');
    }

    public function getGravatar( $email = '', $s = 80, $d = 'mp', $r = 'g', $img = false, $atts = array() ) {
        $url = 'https://www.gravatar.com/avatar/';
        $url .= md5( strtolower( trim( $email ) ) );
        $url .= "?s=$s&d=$d&r=$r";
        if ( $img ) {
            $url = '<img src="' . $url . '"';
            foreach ( $atts as $key => $val )
                $url .= ' ' . $key . '="' . $val . '"';
            $url .= ' />';
        }
        return $url;
    }
}
