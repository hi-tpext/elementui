<?php

namespace elementui\displayer;

use elementui\traits\HasVue;
use elementui\traits\HasVueField;

class TimeRange extends \tpext\builder\displayer\TimeRange
{
    use HasVue;
    use HasVueField;

    protected $view = 'time';

    protected $separator = ',';

    protected $js = [];

    protected $css = [];

    protected $format = 'HH:mm:ss';

    protected $befro = '';

    public function beforRender()
    {
        parent::beforRender();

        $this->addAttr('v-model="' . $this->getVueFieldName() . '.value"');
        $this->addAttr('size="small"');
        $this->addAttr('format="' . $this->format . '"');
        $this->addAttr('value-format="' . $this->format . '"');
        $this->addAttr(':is-range="true"');
        $this->addAttr('range-separator="' . $this->separator. '"');
        $this->addStyle('width:100%;max-width:220px;');

        return $this;
    }

    protected function dateTimeRangeScript()
    {
        return '';
    }
}
