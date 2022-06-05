<?php

namespace elementui\displayer;

use elementui\traits\HasVue;
use elementui\traits\HasVueField;

class Color extends \tpext\builder\displayer\Color
{
    use HasVue;
    use HasVueField;

    protected $js = [];

    protected $css = [];

    protected $jsOptions = [
        'format' => 'hex',
    ];

    protected function colorScript()
    {
        return '';
    }

    public function beforRender()
    {
        parent::beforRender();

        $this->addAttr('v-model="' . $this->getVueFieldName() . '.value"');
        $this->addAttr(':show-alpha="' . ($this->jsOptions['format'] == 'rgba' ? 'true' : 'false') . '"');
        $this->addAttr('color-format="' . ($this->jsOptions['format'] == 'rgba' ? 'rgb' : $this->jsOptions['format']) . '"');
        $this->addAttr('size="small"');

        return $this;
    }
}
