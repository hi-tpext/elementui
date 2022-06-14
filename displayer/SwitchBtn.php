<?php

namespace elementui\displayer;

use elementui\traits\HasVue;
use elementui\traits\HasVueField;

class SwitchBtn extends \tpext\builder\displayer\SwitchBtn
{
    use HasVue;
    use HasVueField;

    protected $js = [];

    protected $css = [];

    protected $jsOptions = [
        'active_color' => '#409eff',
        'inactive_color' => '#dcdfe6',
    ];

    protected function boxScript()
    {
        return '';
    }

    public function render()
    {
        $vars = $this->commonVars();

        $viewshow = $this->getViewInstance();

        return $viewshow->assign($vars)->getContent();
    }

    public function beforRender()
    {
        if (!($this->value === '' || $this->value === null)) {
            $this->checked = '' . $this->value;
        } else {
            $this->checked = '' . $this->default;
        }

        $this->value = $this->checked;

        $this->addAttr('v-model="' . $this->getVueFieldName() . '.value"');
        $this->addAttr('active-color="' . $this->jsOptions['active_color'] . '"');
        $this->addAttr('inactive-color="' . $this->jsOptions['inactive_color'] . '"');
        $this->addAttr(':active-value="\'' . ('' . $this->pair[0]) . '\'"');
        $this->addAttr(':inactive-value="\'' . ('' . $this->pair[1]) . '\'"');

        return parent::beforRender();
    }
}
