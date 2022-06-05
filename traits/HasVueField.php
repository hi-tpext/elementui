<?php

namespace elementui\traits;

trait HasVueField
{
    public function getVueFieldName()
    {
        $name = $this->getName();

        if (strstr($name, '[')) { //多维 'a[b]' = 'c' 转换为 'a.b' = 'c'
            $name = str_replace(['[', ']'], ['.', ''], $name);
        }

        $wrapper = $this->getWrapper();

        if (($wrapper instanceof \elementui\form\FRow) || ($wrapper instanceof \elementui\search\SRow)) {
            return preg_replace('/[^\w\.]/', '_', $this->getWrapper()->getForm()->getFormId()) . '.' . $name;
        } else {
            return preg_replace('/[^\w\.]/', '_', $this->getWrapper()->getTable()->getTableId()) . '_item.' . $name;
        }
    }
}
