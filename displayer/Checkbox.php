<?php

namespace elementui\displayer;

use elementui\traits\HasVue;
use elementui\traits\HasVueField;

class Checkbox extends \tpext\builder\displayer\Checkbox
{
    use HasVue;
    use HasVueField;

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

        $checkall = false;
        $indeterminate = false;

        if ($this->checkallBtn) {
            $count = 0;
            foreach ($this->options as $key => $opt) {
                if (in_array($key, $this->checked)) {
                    $count += 1;
                }
            }
            $checkall = $count > 0 && $count == count($this->options);
            $indeterminate = $count > 0 && $count < count($this->options);
        }

        $this->vueData(['options' => $options]);
        $this->vueData(['checked' => $this->checked]);
        $this->vueData(['checkAll' => $checkall]);
        $this->vueData(['indeterminate' => $indeterminate]);

        $this->eventScript();

        return $this;
    }

    protected function eventScript()
    {
        $vueFieldName = $this->getVueFieldName();
        $vueEventName = $this->getVueEventName();

        $script = <<<EOT
        
        {$vueEventName}CheckAllChange(checked) {
            tpextApp.{$vueFieldName}.value = [];
            var value = [];
            if(checked)
            {
                tpextApp.{$vueFieldName}.options.forEach(function(item){
                    if(!item.disabled)
                    {
                        value.push(item.key);
                    }
                });
            }

            tpextApp.{$vueFieldName}.value = value;
            var checkedCount = value.length;
            var checkAll = checkedCount > 0 && checkedCount === tpextApp.{$vueFieldName}.options.length;
            var indeterminate = checkedCount > 0 && checkedCount < tpextApp.{$vueFieldName}.options.length;

            tpextApp.{$vueFieldName}.checkAll = checkAll;
            tpextApp.{$vueFieldName}.indeterminate = indeterminate;
        },
        {$vueEventName}CheckedChange(value) {
            var checkedCount = value.length;
            var checkAll = checkedCount > 0 && checkedCount === tpextApp.{$vueFieldName}.options.length;
            var indeterminate = checkedCount > 0 && checkedCount < tpextApp.{$vueFieldName}.options.length;

            tpextApp.{$vueFieldName}.checkAll = checkAll;
            tpextApp.{$vueFieldName}.indeterminate = indeterminate;
        }

EOT;
        $this->vueMethods($script);
        return $this;
    }

    public function render()
    {
        $vars = $this->commonVars();

        $vars = array_merge($vars, [
            'inline' => $this->inline && !$this->blockStyle ? true : false,
            'blockStyle' => $this->blockStyle ? true : false,
            'checkallBtn' => $this->checkallBtn,
            'vueFieldName' => $this->getVueFieldName(),
            'vueEventName' => $this->getVueEventName(),
        ]);

        $viewshow = $this->getViewInstance();

        return $viewshow->assign($vars)->getContent();
    }
}
