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
        return parent::getClass() . ($this->getDisplayer()->isRequired() ? ' is-required' : '');
    }

    public function getVueFieldName()
    {
        return $this->displayer->getVueFieldName();
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

            $this->addAttr(':class="{\'is-error\' : ' . $this->getVueFieldName() . '.isError}"');

            $data = $this->displayer->getVueData();
            $data['value'] = $this->displayer->renderValue();
            $data['origin_value'] = $this->displayer->renderValue();
            $data['isInput'] = $this->displayer->isInput();
            $data['isRequired'] = $this->displayer->isRequired() ? true : false;
            $data['isReadonly'] = $this->displayer->isReadonly() ? true : false;
            $data['isDisabled'] = $this->displayer->isDisabled() ? true : false;
            $data['isError'] = $this->displayer->getError() ? true : false;
            $data['label'] = $this->displayer->getLabel();

            $fieldName = $this->displayer->getName();

            $this->getForm()->vueData([$fieldName => $data]);
        }

        return $this;
    }
}
