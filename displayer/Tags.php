<?php

namespace elementui\displayer;

use elementui\traits\HasVue;
use elementui\traits\HasVueField;

class Tags extends \tpext\builder\displayer\Tags
{
    use HasVue;
    use HasVueField;

    protected $js = [];

    protected $css = [];
}
