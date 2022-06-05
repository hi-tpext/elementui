<?php

namespace elementui\displayer;

use elementui\traits\HasVue;
use elementui\traits\HasVueField;

class MultipleFile extends \tpext\builder\displayer\MultipleFile
{
    use HasVue;
    use HasVueField;

    protected $js = [];

    protected $css = [];
}
