<?php

namespace elementui\displayer;

use elementui\traits\HasVue;
use elementui\traits\HasVueField;

class SwitchBtn extends \tpext\builder\displayer\SwitchBtn
{
    use HasVue;
    use HasVueField;

    protected $js = [];

    protected $css = [];
}
