<?php

namespace elementui\search;

use tpext\builder\search\SRow as baseSRow;

class SRow extends baseSRow
{
    /**
     * Displayer
     *
     * @var \elementui\displayer\Field
     */
    protected $displayer;

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

            $data = $this->displayer->getVueData();
            $data['value'] = $this->displayer->renderValue();
            $data['origin_value'] = $this->displayer->renderValue();
            $data['isInput'] = $this->displayer->isInput();

            $fieldName = $this->displayer->getName();

            $this->getForm()->vueData([$fieldName => $data]);
        }

        return $this;
    }
}
