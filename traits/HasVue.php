<?php

namespace elementui\traits;

use elementui\common\Builder;

trait HasVue
{
    protected $vueData = [];
    protected $vueMounted = [];
    protected $vueMethods = [];
    protected $vueWatch = [];

    /**
     * 添加数据到vue
     * vueData(['name'=>'小明'])
     *
     * @param array $data
     * @return $this
     */
    public function vueData($data)
    {
        foreach ($data as $k => $v) {
            $k = str_replace(['-', '[', ']'], ['_', '.', ''], $k);
            $this->vueData[$k] = $v;
        }

        return $this;
    }

    public function getVueData()
    {
        return $this->vueData;
    }

    /**
     * 添加vue mounted 事件
     * vueMounted('console.log("666");this.test();')
     * @param string $code
     * @return $this
     */
    public function vueMounted($code)
    {
        Builder::getInstance()->vueMounted($code);

        return $this;
    }

    /**
     * 添加vue 方法
     * vueMounted('test(){console.log(777)},')
     * @param string $code
     * @return $this
     */
    public function vueMethods($code)
    {
        Builder::getInstance()->vueMethods($code);

        return $this;
    }

    /**
     * 添加vue 监听
     * vueData('name(val){console.log(888+val)},')
     * @param string $code
     * @return $this
     */
    public function vueWatch($code)
    {
        Builder::getInstance()->vueWatch($code);

        return $this;
    }
}
