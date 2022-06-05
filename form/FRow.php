<?php

namespace elementui\form;

use tpext\builder\form\FRow as baseFRow;

class FRow extends baseFRow
{
    /**
     * Displayer
     *
     * @var \elementui\displayer\Field
     */
    protected $displayer;

    public function getClass()
    {
        return parent::getClass() . ($this->getDisplayer()->isRequired() ? ' tpext-form-item el-form-item  is-required' : ' el-form-item tpext-form-item');
    }

    /**
     * Undocumented function
     *
     * @return $this
     */
    public function beforRender()
    {
        parent::beforRender();

        $size = $this->displayer->getSize();

        if (preg_match('/^([\d\.]{1,4})\s*/', $size[0], $mch)) {
            $col = intval($mch[1] * 2);
            if ($col >= 12) { // label col-size超过一半，那就是上下结构，让label文字靠左显示。
                $this->addClass('el-form--label-left no-margin-bottom');
            }
        }

        if ($this->displayer->isInput()) {

            $data = $this->displayer->getVueData();
            $data['value'] = $this->displayer->renderValue();

            $fieldName = $this->displayer->getName();

            $this->getForm()->vueData([$fieldName => $data]);
        }

        return $this;
    }
}
