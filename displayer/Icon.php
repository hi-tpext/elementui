<?php

namespace elementui\displayer;

use elementui\traits\HasVue;
use elementui\traits\HasVueField;

class Icon extends \tpext\builder\displayer\Icon
{
    use HasVue;
    use HasVueField;

    protected $js = [];

    protected $css = [];
}
