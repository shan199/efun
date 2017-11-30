<?php
namespace app\base\Common;

/**
 * 公共站点控制器
 */

class SiteCommon extends \app\base\controller\BaseController {

    protected $siteConfig = [];
    protected $pageInfo = [
        'title' => '',
        'keyword' => '',
        'description' => '',
        'crumb' => []
    ];
    protected $detect = null;

    public function __construct() {
        parent::__construct();
        $this->detect = new \dux\vendor\MobileDetect();

        if(LAYER_NAME == 'controller' && ($this->detect->isMobile() || $this->detect->isTablet())) {
            $modules = \Config::get('dux.module');
            $this->redirect('/' . $modules['mobile'] . $_SERVER["REQUEST_URI"]);
        }

        if($this->detect->isMobile() || $this->detect->isTablet()) {
            target('system/Statistics', 'service')->incStats('mobile');
        }else {
            target('system/Statistics', 'service')->incStats('web');
        }
        $this->siteConfig = target('site/SiteConfig')->getConfig();
    }

    protected function siteDisplay($tpl = 'index') {
        $this->assign('site', $this->siteConfig);
        $this->assign('pageInfo', $this->pageInfo);

        if(LAYER_NAME == 'mobile') {
            $this->siteConfig['tpl_name'] = $this->siteConfig['tpl_name'] . '_mobile';
        }

        $tpl = 'mobile/' . $this->siteConfig['tpl_name'] . '/' . strtolower($tpl);
        $this->_getView()->addTag(function () {
            return [
                '/<!--#include\s*file=[\"|\'](.*)\.(html|htm)[\"|\']-->/' => "<?php \$__Template->render(\"" . 'mobile/' . $this->siteConfig['tpl_name'] . "/$1\"); ?>",
                '/<(.*?)(src=|href=|value=|background=)[\"|\'](images\/|img\/|css\/|js\/|style\/)(.*?)[\"|\'](.*?)>/' => [$this, 'parseLoad'],
                '/__TPL__/' => ROOT_URL . '/mobile/' . $this->siteConfig['tpl_name']
            ];
        });
        $this->display($tpl);
        exit;
    }

    protected function setMeta($title = '', $keyword = '', $description = '') {
        $title = $title ? $title . ' - ' : '';
        $this->pageInfo['title'] = $title . $this->siteConfig['info_title'];
        $this->pageInfo['keyword'] = $keyword ? $keyword : $this->siteConfig['info_keyword'];
        $this->pageInfo['description'] = $description ? $description : $this->siteConfig['info_desc'];
    }

    protected function setCrumb($data) {
        $this->pageInfo['crumb'] = $data;
    }

    protected function pageData($sumLimit, $pageLimit, $params = []) {
        $pageObj = new \dux\lib\Pagination($sumLimit, request('get', 'page', 1), $pageLimit);
        $pageData = $pageObj->build();
        $limit = [$pageData['offset'], $pageLimit];
        $pageData['prevUrl'] = $this->createPageUrl($pageData['prev'], $params);
        $pageData['nextUrl'] = $this->createPageUrl($pageData['next'], $params);
        $html = '<div class="dux-pages"><a href="{prevUrl}"> <  Prev</a>';
        foreach ($pageData['pageList'] as $vo) {
            if ($vo == $pageData['current']) {
                $html .= '<span class="current">' . $vo . '</span>';
            } else {
                $html .= '<a href="' . $this->createPageUrl($vo, $params) . '">' . $vo . '</a>';
            }
        }
        $html .= '<a href="{nextUrl}">Next  > </a></div>';
        foreach ($pageData as $key => $vo) {
            $html = str_replace('{' . $key . '}', $vo, $html);
        }
        return [
            'html' => $html,
            'limit' => $limit,
        ];
    }

    protected function mobilePageData($sumLimit, $pageLimit, $params = []) {
        $pageObj = new \dux\lib\Pagination($sumLimit, request('get', 'page', 1), $pageLimit);
        $pageData = $pageObj->build();
        $limit = [$pageData['offset'], $pageLimit];
        $pageData['prevUrl'] = $this->createPageUrl($pageData['prev'], $params);
        $pageData['nextUrl'] = $this->createPageUrl($pageData['next'], $params);
        $str = '第 '.request('get','page',1). '/'. ceil($sumLimit/10) .'页';

        $html = '<div style="left: 0;font-size: 14px;">'.$str.'</div><ul class="mui-pagination mui-pagination-sm"><li class="mui-previous"><a href="{prevUrl}"> 上一页</a>';
        foreach ($pageData['pageList'] as $vo) {
            if ($vo == $pageData['current']) {
                $html .= '<li class="mui-active"><a>' . $vo . '</a></li>';
            } else {
                $html .= '<li><a href="' . $this->createPageUrl($vo, $params) . '">' . $vo . '</a></li>';
            }
        }
        $html .= ' <li class="mui-next"><a href="{nextUrl}">下一页</a></ul>';
        foreach ($pageData as $key => $vo) {
            $html = str_replace('{' . $key . '}', $vo, $html);
        }
        return [
            'html' => $html,
            'limit' => $limit,
        ];
    }
    protected function createPageUrl($page = 1, $params = []) {
        return $url = url(APP_NAME . '/' . MODULE_NAME . '/' . ACTION_NAME, array_merge($params, ['page' => $page]));
    }

    public function parseLoad($var) {
        $file = $var[3] . $var[4];
        $url = 'mobile' . '/' . $this->siteConfig['tpl_name'];
        if (substr($url, 0, 1) == '.') {
            $url = substr($url, 1);
        }
        $url = str_replace('\\', '/', $url);
        $url = ROOT_URL . '/' . $url . '/' . $file;
        $html = '<' . $var[1] . $var[2] . '"' . $url . '"' . $var[5] . '>';
        return $html;
    }

}