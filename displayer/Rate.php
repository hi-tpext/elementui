<?php

namespace elementui\displayer;

use elementui\traits\HasVue;
use elementui\traits\HasVueField;

class Rate extends \tpext\builder\displayer\Rate
{
    use HasVue;
    use HasVueField;

    protected $view = 'text';

    protected $js = [];

    protected $css = [];

    protected $after = '%';

    protected $size = [2, 2];

    public function beforRender()
    {
        parent::beforRender();

        $vueFieldName =  $this->getVueFieldName();

        $this->addAttr('v-model="' . $vueFieldName . '.value"');
        $this->addAttr('size="small"');

        $vueEventName = $this->getVueEventName();
        $this->addAttr('@keyup.native="' . $vueEventName  . 'Change"');

        $script = <<<EOT

        {$vueEventName}Keyup(e) {
            tpextApp.{$vueFieldName}.value = tpextApp.{$vueFieldName}.value.replace(/[^\d\.]/g, '');
        },
EOT;
        $this->vueMethods($script);

        return $this;
    }
}
