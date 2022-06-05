<?php

namespace elementui\displayer;

use elementui\traits\HasVue;
use elementui\traits\HasVueField;

class Image extends \tpext\builder\displayer\Image
{
    use HasVue;
    use HasVueField;

    protected $js = [];

    protected $css = [];
}
