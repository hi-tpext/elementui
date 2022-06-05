<?php

namespace elementui\displayer;

use elementui\traits\HasVue;
use elementui\traits\HasVueField;

class RangeSlider extends \tpext\builder\displayer\RangeSlider
{
    use HasVue;
    use HasVueField;

    protected $js = [];

    protected $css = [];
}
