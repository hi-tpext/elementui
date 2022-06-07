<?php

namespace elementui\common;

use tpext\builder\common\Search as baseSearch;
use elementui\traits\HasVue;

/**
 * Search class
 */
class Search extends baseSearch
{
    use HasVue;

    protected $vueData = [];

    public function created()
    {
        parent::created();
        $this->class = 'el-form el-form--label-right';
        $this->butonsSizeClass = 'el-button--mini';
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function applyVue($key)
    {
        if (!empty($this->vueData)) {
            Builder::getInstance()->vueData([$key => $this->vueData]);
        }
    }

    protected function eventScript()
    {
        $vueEventName = $this->getVueEventName();
        $vueFieldName = $this->getVueFieldName();

        $script = <<<EOT
        
        {$vueEventName}Submit() {
            console.log(this.{$vueFieldName})
        }

EOT;
        $this->vueMethods($script);
        return $this;
    }

    public function getVueFieldName()
    {
        return preg_replace('/[^\w\.]/', '_', $this->getFormId());
    }

    public function getVueEventName()
    {
        return preg_replace('/\W/', '_', $this->getVueFieldName());
    }

    public function beforRender()
    {
        parent::beforRender();

        $this->applyVue($this->getFormId());

        return $this;
    }
}
