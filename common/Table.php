<?php

namespace elementui\common;

use tpext\builder\common\Table as baseTable;
use tpext\builder\table\Paginator;
use elementui\traits\HasVue;

/**
 * Table class
 */
class Table extends baseTable
{
    use HasVue;

    protected $js = [];

    protected $css = [];

    public function created()
    {
        parent::created();
        $this->emptyText = Module::config('table_empty_text');

        return $this;
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

    public function beforRender()
    {
        parent::beforRender();

        return $this;
    }

    /**
     * Undocumented function
     *
     * @return array
     */
    public function customVars()
    {
        return [
            'vueFieldName' => $this->getVueFieldName(),
            'vueEventName' => $this->getVueEventName(),
        ];
    }

    protected function initData()
    {
        parent::initData();

        $this->vueData(['tableHeaders' => $this->headers]);
        $this->vueData(['tableData' => $this->list]);

        $this->addAttr('stripe="true"');

        $this->applyVue($this->getTableId());
    }

    /**
     * Undocumented function
     *
     * @return string|\think\response\View
     */
    public function render()
    {
        if (!$this->isInitData) {
            $this->initData();
        }

        $viewshow = view($this->getViewemplate());

        $count = count($this->data);
        if (!$this->paginator) {
            $this->pageSize = $count ? $count : 10;
            $this->paginator = Paginator::make($this->data, $this->pageSize, 1, $count);
            $this->usePagesizeDropdown = false;
        }

        if ($this->paginator->total() <= 6) {
            $this->usePagesizeDropdown = false;
        }

        $sort = input('get.__sort__', $this->sortOrder);
        $sortKey = '';
        $sortOrder = '';

        if ($sort) {
            $arr = explode(' ', $sort);
            if (count($arr) == 2) {
                $sortKey = $arr[0];
                $sortOrder = $arr[1];
                if (!empty($this->sortable) && !in_array($sortKey, $this->sortable)) {
                    $this->sortable[] = $sortKey;
                }
            }
        }

        if ($this->usePagesizeDropdown && $this->pageSize && empty($this->pagesizeDropdown)) {
            $items = [
                0 => '默认', 6 => '6', 10 => '10', 14 => '14', 20 => '20', 30 => '30', 40 => '40', 50 => '50', 60 => '60', 90 => '90', 120 => '120', 200 => '200', 350 => '350',
            ];

            ksort($items);

            $this->pagesizeDropdown($items);
        }

        $vars = [
            'class' => $this->class,
            'attr' => $this->getAttrWithStyle(),
            'headers' => $this->headers,
            'cols' => $this->cols,
            'list' => $this->list,
            'data' => $this->data,
            'emptyText' => $this->emptyText,
            'headTextAlign' => $this->headTextAlign,
            'ids' => $this->ids,
            'sortable' => $this->sortable,
            'sortKey' => $sortKey,
            'sortOrder' => $sortOrder,
            'sort' => $sort,
            'useCheckbox' => $this->useCheckbox && $this->useToolbar,
            'name' => time() . mt_rand(1000, 9999),
            'tdClass' => $this->verticalAlign . ' ' . $this->textAlign,
            'verticalAlign' => $this->verticalAlign,
            'textAlign' => $this->textAlign,
            'id' => $this->id,
            'paginator' => $this->paginator,
            'partial' => $this->partial ? 1 : 0,
            'searchForm' => !$this->partial ? $this->searchForm : null,
            'toolbar' => $this->useToolbar && !$this->partial ? $this->toolbar : null,
            'actionbars' => $this->actionbars,
            'actionRowText' => $this->actionRowText,
            'checked' => $this->checked,
            'pagesizeDropdown' => $this->usePagesizeDropdown ? $this->pagesizeDropdown : null,
            'addTop' => $this->addTop,
            'addBottom' => $this->addBottom,
        ];

        $customVars = $this->customVars();

        if (!empty($customVars)) {
            $vars = array_merge($vars, $customVars);
        }

        if ($this->partial) {
            return $viewshow->assign($vars);
        }

        return $viewshow->assign($vars)->getContent();
    }
}
