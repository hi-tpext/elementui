<?php

namespace elementui\displayer;

use elementui\traits\HasVue;
use elementui\traits\HasVueField;

class MultipleImage extends \tpext\builder\displayer\MultipleImage
{
    use HasVue;
    use HasVueField;

    protected $js = [];

    protected $css = [];
}
