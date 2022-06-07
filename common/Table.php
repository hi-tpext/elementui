<?php

namespace elementui\common;

use tpext\builder\common\Table as baseTable;

/**
 * Table class
 */
class Table extends baseTable
{
    protected $js = [];

    protected $css = [];

    public function created()
    {
        parent::created();
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function applyVue($key)
    {
        if (!empty($this->vueData)) {
            Builder::getInstance()->vueData([$key => $this->vueData]);
        }
    }

    public function getVueFieldName()
    {
        return preg_replace('/[^\w\.]/', '_', $this->getTableId());
    }

    public function getVueEventName()
    {
        return preg_replace('/\W/', '_', $this->getVueFieldName());
    }
}
