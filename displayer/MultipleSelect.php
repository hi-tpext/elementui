<?php

namespace elementui\displayer;

use elementui\traits\HasVue;
use elementui\traits\HasVueField;

class MultipleSelect extends \tpext\builder\displayer\MultipleSelect
{
    use HasVue;
    use HasVueField;

    protected $js = [];

    protected $css = [];

    protected function select2Script()
    {
        return '';
    }
}
