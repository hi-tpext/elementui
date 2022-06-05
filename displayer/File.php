<?php

namespace elementui\displayer;

use elementui\traits\HasVue;
use elementui\traits\HasVueField;

class File extends \tpext\builder\displayer\File
{
    use HasVue;
    use HasVueField;

    protected $js = [];

    protected $css = [];
}
