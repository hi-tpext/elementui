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
        $this->whenScript();

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

        foreach ($this->options as $k => $opt) {
            $options[] = [
                'key' => '' . $k,
                'label' => $opt,
                'disabled' => in_array($k, $this->disabledOptions)
            ];
        }

        $this->vueData(['options' => $options]);
        $this->vueData(['checked' => $this->checked]);

        $this->eventScript();

        return $this;
    }

    protected function eventScript()
    {
        $vueFieldName = $this->getVueFieldName();
        $vueEventName = preg_replace('/\W/', '_', $vueFieldName);

        $script = <<<EOT

          {$vueEventName}CheckedChange(value) {
            //
            console.log(value);
          }

EOT;
        $this->vueMethods($script);
        return $this;
    }

    public function render()
    {
        $vars = $this->commonVars();

        $vueFieldName = $this->getVueFieldName();

        $vars = array_merge($vars, [
            'inline' => $this->inline && !$this->blockStyle ? true : false,
            'blockStyle' => $this->blockStyle ? true : false,
            'vueFieldName' => $vueFieldName,
            'vueEventName' => preg_replace('/\W/', '_', $vueFieldName),
        ]);

        $viewshow = $this->getViewInstance();

        return $viewshow->assign($vars)->getContent();
    }
}
