<?php

namespace elementui\displayer;

use elementui\traits\HasVue;
use elementui\traits\HasVueField;

class Rate extends \tpext\builder\displayer\Rate
{
    use HasVue;
    use HasVueField;

    protected $js = [];

    protected $css = [];
}
