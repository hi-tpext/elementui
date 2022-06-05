<?php

namespace elementui\displayer;

use elementui\traits\HasVue;
use elementui\traits\HasVueField;

/**
 * Field class
 */
class Field extends \tpext\builder\displayer\Field
{
    use HasVue;
    use HasVueField;
    
    protected $js = [];

    protected $css = [];
}
