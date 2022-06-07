<?php

namespace elementui\displayer;

use elementui\traits\HasVue;
use elementui\traits\HasVueField;

class Radio extends \tpext\builder\displayer\Radio
{
    use HasVue;
    use HasVueField;

    public function beforRender()
    {
        parent::beforRender();

        if (!($this->value === '' || $this->value === null)) {
            $this->checked = '' . $this->value;
        } else {
            $this->checked = '' . $this->default;
        }

        $this->value = $this->checked;

        if ($this->disabledOptions && !is_array($this->disabledOptions)) {
            $this->disabledOptions = explode(',', $this->disabledOptions);
        }

        if ($this->readonlyOptions && !is_array($this->readonlyOptions)) {
            $this->readonlyOptions = explode(',', $this->readonlyOptions);
        }

        $this->disabledOptions = array_merge($this->disabledOptions, $this->readonlyOptions);

        $options = [];

        foreach ($this->options as $key => $opt) {
            $options[] = [
                'key' => '' . $key,
                'label' => $opt,
                'disabled' => in_array($key, $this->disabledOptions)
            ];
        }

        $this->addAttr('v-model="' . $this->getVueFieldName() . '.value"');
        $this->vueData(['options' => $options]);
        $this->vueData(['checked' => $this->checked]);

        return $this;
    }

    public function render()
    {
        $vars = $this->commonVars();

        $vars = array_merge($vars, [
            'inline' => $this->inline && !$this->blockStyle ? true : false,
            'blockStyle' => $this->blockStyle ? true : false,
            'vueFieldName' => $this->getVueFieldName(),
            'vueEventName' => $this->getVueEventName(),
        ]);

        $viewshow = $this->getViewInstance();

        return $viewshow->assign($vars)->getContent();
    }
}
