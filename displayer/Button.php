<?php

namespace elementui\displayer;

use elementui\traits\HasVue;
use elementui\traits\HasVueField;

class Button extends \tpext\builder\displayer\Button
{
    use HasVue;
    use HasVueField;

    protected $size = [0, '24 el-col-lg-24 el-col-sm-24 el-col-xs-24'];

    public function getClass()
    {
        $class = parent::getClass();

        return str_replace(
            ['btn-default', 'btn-primary', 'btn-success', 'btn-info', 'btn-warning', 'btn-danger'],
            ['el-button--default', 'el-button--primary', 'el-button--success', 'el-button--primary', 'el-button--warning', 'el-button--danger'],
            $class
        );
    }

    public function beforRender()
    {
        parent::beforRender();

        $this->addAttr('size="small"');

        return $this;
    }
}
