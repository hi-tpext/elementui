<?php

namespace elementui\displayer;

use elementui\traits\HasVue;
use elementui\traits\HasVueField;

class Number extends \tpext\builder\displayer\Number
{
    use HasVue;
    use HasVueField;

    protected $js = [];

    protected $css = [];

    protected $jsOptions = [
        'min' => 0,
        'max' => 9999999,
        'step' => 1,
        'step_strictly' => false, //如果这个属性被设置为true，则只能输入步数的倍数。
        'precision' => 0, //小数点位数, 值必须是一个非负整数，并且不能小于 step 的小数位数。
    ];

    protected function numberScript()
    {
        return '';
    }

    public function beforRender()
    {
        parent::beforRender();

        $this->addAttr('v-model="' . $this->getVueFieldName() . '.value"');
        $this->addAttr('size="small"');
        $this->addAttr('controls-position="right"');
        $this->addAttr('step="' . $this->jsOptions['step'] . '"');
        $this->addStyle('width:100%;max-width:220px;');

        if ($this->jsOptions['step_strictly']) {
            $this->addAttr(':step-strictly="true"');
        }

        if (strpos($this->jsOptions['step'], '.') !== false) {
            $p = explode('.', $this->jsOptions['step'])[1];
            $len = strlen($p);
            if ($this->jsOptions['precision'] < $len) {
                $this->jsOptions['precision'] = $len;
            }
            $this->addAttr(':precision="' . $this->jsOptions['precision'] . '"');
        }

        return $this;
    }
}
