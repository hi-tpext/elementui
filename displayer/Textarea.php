<?php

namespace elementui\displayer;

use elementui\traits\HasVue;
use elementui\traits\HasVueField;

class Textarea extends \tpext\builder\displayer\Textarea
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
        $this->addAttr('resize="vertical"');
        $this->addAttr(':autosize="{minRows:' . $this->rows . ', maxRows:6}"');

        if ($this->maxlength > 0) {
            $this->addAttr('show-word-limit="true"');
        }

        return $this;
    }
}
