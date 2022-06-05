<?php

namespace elementui\displayer;

use elementui\traits\HasVue;
use elementui\traits\HasVueField;

class Select extends \tpext\builder\displayer\Select
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
