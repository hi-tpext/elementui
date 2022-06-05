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

            if (strstr($k, '.')) { //'a.b' = 'c' 转换为多维 {a: {b: 'c'}}
                $arr = explode('.', $k);
                $size = count($arr);

                for ($i = 1; $i < $size; $i += 1) {
                    if (!isset($this->vueData[$arr[$i - 1]])) {
                        $this->vueData[$arr[$i - 1]] = [];
                    }
                    $this->vueData[$arr[$i - 1]][$arr[$i]] = $v;
                }
            } else {
                $this->vueData[$k] = $v;
            }
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
