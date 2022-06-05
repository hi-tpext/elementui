<?php

namespace elementui\displayer;

use elementui\traits\HasVue;
use elementui\traits\HasVueField;

class DualListbox extends \tpext\builder\displayer\DualListbox
{
    use HasVue;
    use HasVueField;

    protected $js = [];

    protected $css = [];

    public function beforRender()
    {
        //$this->whenScript();

        if (!($this->value === '' || $this->value === null || $this->value === [])) {
            $this->checked = is_array($this->value) ? $this->value : explode(',', $this->value);
        } else if (!($this->default === '' || $this->default === null || $this->default === [])) {
            $this->checked = is_array($this->default) ? $this->default : explode(',', $this->default);
        }

        $this->value = $this->checked;

        if ($this->disabledOptions && !is_array($this->disabledOptions)) {
            $this->disabledOptions = explode(',', $this->disabledOptions);
        }

        $options = [];

        foreach ($this->options as $k => $opt) {
            $options[] = [
                'key' => '' . $k,
                'label' => $opt,
                'disabled' => in_array($k, $this->disabledOptions)
            ];
        }

        $this->vueData(['options' => $options]);
        $this->vueData(['checked' => $this->checked]);

        $vueFieldName = $this->getVueFieldName();

        $this->addAttr('v-model="' . $vueFieldName . '.value"');
        $this->addAttr(':right-default-checked="' . $vueFieldName . '.checked"');
        $this->addAttr(':titles="[\'请选择\', \'已选择\']"');
        $this->addAttr(':button-texts="[\'到左边\', \'到右边\']"');
        $this->addAttr(':data="' . $vueFieldName . '.options"');

        return $this;
    }

    public function render()
    {
        $vars = $this->commonVars();
        $viewshow = $this->getViewInstance();
        return $viewshow->assign($vars)->getContent();
    }
}
