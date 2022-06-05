<?php

namespace elementui\common;

use tpext\builder\common\Form as baseForm;
use elementui\traits\HasVue;

/**
 * Form class
 */
class Form extends baseForm
{
    use HasVue;

    public function created()
    {
        parent::created();

        $this->class = 'el-form el-form--label-right';
        $this->butonsSizeClass = 'el-button--small';

        return $this;
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

    public function beforRender()
    {
        parent::beforRender();

        $this->applyVue($this->getFormId());

        return $this;
    }
}
