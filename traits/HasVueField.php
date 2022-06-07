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
            return $this->getWrapper()->getForm()->getVueFieldName() . '[\'' . $name . '\']';
        } else {
            return $this->getWrapper()->getTable()->getVueFieldName() . '_item[\'' . $name . '\']';
        }
    }

    public function getVueEventName()
    {
        return preg_replace('/\W/', '_', $this->getVueFieldName());
    }
}
