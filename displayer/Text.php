<?php

namespace elementui\displayer;

use elementui\traits\HasVue;
use elementui\traits\HasVueField;

class Text extends \tpext\builder\displayer\Text
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

        if ($this->maxlength > 0) {
            $this->addAttr('show-word-limit="true"');
        }

        return $this;
    }
}
