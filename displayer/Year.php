<?php

namespace elementui\displayer;

use elementui\traits\HasVue;
use elementui\traits\HasVueField;

class Year extends \tpext\builder\displayer\Year
{
    use HasVue;
    use HasVueField;

    protected $view = 'datetime';

    protected $js = [];

    protected $css = [];

    protected $format = 'yyyy';

    protected $jsOptions = [];

    /**
     * Undocumented function
     * @param string $val yyyy
     * @return $this
     */
    public function format($val)
    {
        $this->format = str_replace('Y', 'y', $val);
        return $this;
    }

    protected function dateTimeScript()
    {
        $this->addAttr('v-model="' . $this->getVueFieldName() . '.value"');
        $this->addAttr('size="small"');
        $this->addAttr('format="' . $this->format . '"');
        $this->addAttr('value-format="' . $this->format . '"');
        $this->addAttr('type="year"');
        $this->addStyle('width:100%;max-width:220px;');

        return '';
    }
}
