<?php

namespace elementui\displayer;

use elementui\traits\HasVue;
use elementui\traits\HasVueField;

class TimeRange extends \tpext\builder\displayer\TimeRange
{
    use HasVue;
    use HasVueField;

    protected $js = [];

    protected $css = [];
}
