<?php

namespace elementui\displayer;

use elementui\traits\HasVue;
use elementui\traits\HasVueField;

class RangeSlider extends \tpext\builder\displayer\RangeSlider
{
    use HasVue;
    use HasVueField;

    protected $view = 'rangeslider';

    protected $js = [];

    protected $css = [];

    protected $jsOptions = [
        'range' => false,
        'min' => 0,
        'max' => 100,
    ];

    protected function rangeScript()
    {
        return '';
    }

    public function beforRender()
    {
        parent::beforRender();

        if (!empty($this->value)) {
            $this->checked = is_array($this->value) ? $this->value : explode(';', str_replace(',', ';', $this->value));
        } else if (!empty($this->default)) {
            $this->checked = is_array($this->default) ? $this->default : explode(';', str_replace(',', ';', $this->default));
        }

        if (count($this->checked) > 1) {
            $this->jsOptions['range'] = true;

            foreach ($this->checked as &$ck) {
                $ck = doubleval($ck);
            }
        } else {
            $this->checked = doubleval($this->checked[0]);
        }

        $this->value = $this->checked;

        $this->addAttr('v-model="' . $this->getVueFieldName() . '.value"');
        $this->addAttr(':range="' . ($this->jsOptions['range'] ? 'true' : 'false') . '"');
        $this->addAttr(':min="' . $this->jsOptions['min'] . '"');
        $this->addAttr(':max="' . $this->jsOptions['max'] . '"');
        $this->addStyle('width:100%;');

        return $this;
    }
}
