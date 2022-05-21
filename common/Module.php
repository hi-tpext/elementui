<?php

namespace elementui\common;

use tpext\common\Module as baseModule;
use tpext\myadmin\common\Module as adminModule;

class Module extends baseModule
{
    protected $version = '1.0.1';

    protected $name = 'element.ui';

    protected $title = 'Elementui UI';

    protected $description = '基于 [Vue + Element] 对 tpextmyadmin 和 tpextbuilder 的UI替换方案';

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

        adminModule::getInstance()->addIndexView($indexView, 'ElementUI样式');
        adminModule::getInstance()->addLoginView($loginView, 'ElementUI样式');
    }
}
