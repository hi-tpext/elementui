<?php

namespace elementui\displayer;

use elementui\traits\HasVue;
use elementui\traits\HasVueField;

class Transfer extends \tpext\builder\displayer\Transfer
{
    use HasVue;
    use HasVueField;

    protected $js = [];

    protected $css = [];

    protected function dualListScript()
    {
        return '';
    }

    public function beforRender()
    {
        parent::beforRender();

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

        foreach ($this->options as $key => $opt) {

            if (isset($opt['options']) && isset($opt['label'])) {

                foreach ($opt['options'] as $k => $o) {
                    $options[] = [
                        'key' => '' . $k,
                        'label' => $opt['label'] . '-' . $o,
                        'disabled' => in_array($key, $this->disabledOptions)
                    ];
                }

                continue;
            }

            $options[] = [
                'key' => '' . $key,
                'label' => $opt,
                'disabled' => in_array($key, $this->disabledOptions)
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

        $vars = array_merge($vars, [
            'group' => $this->group,
            'vueFieldName' => $this->getVueFieldName(),
            'vueEventName' => $this->getVueEventName(),
        ]);

        $viewshow = $this->getViewInstance();

        return $viewshow->assign($vars)->getContent();
    }
}
