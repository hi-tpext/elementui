<?php

namespace elementui\displayer;

use elementui\traits\HasVue;
use elementui\traits\HasVueField;

class Fields extends \tpext\builder\displayer\Fields
{
    use HasVue;
    use HasVueField;

    protected $js = [];

    protected $css = [];
}
