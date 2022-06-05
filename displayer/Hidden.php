<?php

namespace elementui\displayer;

use elementui\traits\HasVue;
use elementui\traits\HasVueField;

class Hidden extends \tpext\builder\displayer\Hidden
{
    use HasVue;
    use HasVueField;

    protected $js = [];

    protected $css = [];

    public function beforRender()
    {
        parent::beforRender();

        $this->addAttr('v-model="' . $this->getVueFieldName() . '.value"');

        return $this;
    }
}
