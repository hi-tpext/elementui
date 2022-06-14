<?php

namespace elementui\displayer;

use elementui\traits\HasVue;
use elementui\traits\HasVueField;

class Time extends \tpext\builder\displayer\Time
{
    use HasVue;
    use HasVueField;

    protected $view = 'time';

    protected $js = [];

    protected $css = [];

    protected $format = 'HH:mm:ss';

    protected $befro = '';

    protected function dateTimeScript()
    {
        $this->addAttr('v-model="' . $this->getVueFieldName() . '.value"');
        $this->addAttr('size="small"');
        $this->addAttr('format="' . $this->format . '"');
        $this->addAttr('value-format="' . $this->format . '"');
        $this->addStyle('width:100%;max-width:220px;');
        
        return '';
    }
}
