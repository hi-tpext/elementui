<?php

namespace elementui\common;

use tpext\myadmin\common\Module as adminModule;
use tpext\builder\common\Module as builderModule;
use tpext\common\Module as baseModule;
use tpext\myadmin\common\MinifyTool;
use think\facade\View;

class Module extends baseModule
{
    protected $version = '1.0.1';

    protected $name = 'element.ui';

    protected $title = 'Elementui UI';

    protected $description = '基于[Vue+Element]对tpextmyadmin和tpextbuilder的UI替换';

    protected $root = __DIR__ . '/../';

    protected $assets = 'assets';

    public function configPath()
    {
        return realpath($this->getRoot() . 'config.php');
    }

    public function loaded()
    {
        $indexView = $this->getRoot() . implode(DIRECTORY_SEPARATOR, ['admin', 'view', 'index', 'index.html']);
        $loginView = $this->getRoot() . implode(DIRECTORY_SEPARATOR, ['admin', 'view', 'index', 'login.html']);

        adminModule::getInstance()->addIndexView($indexView, 'COOL-ADMIN样式');
        adminModule::getInstance()->addLoginView($loginView, 'COOL-ADMIN样式');

        builderModule::getInstance()->addUiDriver(Builder::class, 'ElementUI样式');

        $adminConfig = adminModule::getInstance()->getConfig();
        $builderConfig = builderModule::getInstance()->getConfig();

        $adminIndexView = $adminConfig['index_page_style'] ?? '';

        $uiDriver = $builderConfig['ui_driver'] ?? '';

        if ($adminIndexView == str_replace(app()->getRootPath(), '__WWW__', $indexView)) { //本扩展提供的index主页样式正在被使用

        }

        if ($uiDriver == Builder::class) { //本扩展提供的UI正在被使用

            $admin_layout = Module::getInstance()->getRoot() . implode(DIRECTORY_SEPARATOR, ['admin', 'view', 'layout.html']);

            View::assign([
                'admin_layout' => $admin_layout,
            ]);

            //$this->initMinify();
        }
    }

    private function initMinify()
    {
        MinifyTool::removeJs([
            // '/assets/lightyearadmin/js/jquery.min.js',
            //'/assets/lightyearadmin/js/bootstrap.min.js',
            '/assets/lightyearadmin/js/jquery.lyear.loading.js',
            '/assets/lightyearadmin/js/bootstrap-notify.min.js',
            '/assets/lightyearadmin/js/jconfirm/jquery-confirm.min.js',
            // '/assets/lightyearadmin/js/lightyear.js',
            // '/assets/lightyearadmin/js/main.min.js',
        ]);

        MinifyTool::removeCss([
            // '/assets/lightyearadmin/css/bootstrap.min.css',
            // '/assets/lightyearadmin/css/materialdesignicons.min.css',
            // '/assets/lightyearadmin/css/animate.css',
            // '/assets/lightyearadmin/css/style.min.css',
            '/assets/lightyearadmin/js/jconfirm/jquery-confirm.min.css',
        ]);

        MinifyTool::addJs('/assets/elementui/js/tpextbuilder.js');
    }
}
