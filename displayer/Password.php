<?php

namespace elementui\displayer;

use elementui\traits\HasVue;
use elementui\traits\HasVueField;

class Password extends \tpext\builder\displayer\Password
{
    use HasVue;
    use HasVueField;

    protected $js = [];

    protected $css = [];

    public function beforRender()
    {
        parent::beforRender();

        $this->addAttr('v-model="' . $this->getVueFieldName() . '.value"');
        $this->addAttr('size="small"');
        $this->addAttr(':clearable="true"');
        $this->addAttr(':show-password="true"');

        if ($this->maxlength > 0) {
            $this->addAttr('show-word-limit="true"');
        }

        return $this;
    }
}
