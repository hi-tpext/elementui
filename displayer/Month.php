<?php

namespace elementui\displayer;

use elementui\traits\HasVue;
use elementui\traits\HasVueField;

class Month extends \tpext\builder\displayer\Month
{
    use HasVue;
    use HasVueField;

    protected $view = 'datetime';

    protected $js = [];

    protected $css = [];

    protected $format = 'MM';

    protected $jsOptions = [];

    /**
     * Undocumented function
     * @param string $val MM
     * @return $this
     */
    public function format($val)
    {
        $this->format = str_replace('m', 'M', $val);
        return $this;
    }

    protected function dateTimeScript()
    {
        $this->addAttr('v-model="' . $this->getVueFieldName() . '.value"');
        $this->addAttr('size="small"');
        $this->addAttr('format="' . $this->format . '"');
        $this->addAttr('value-format="' . $this->format . '"');
        $this->addAttr('type="month"');
        $this->addStyle('width:100%;max-width:220px;');

        return '';
    }
}
