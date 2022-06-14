<?php

namespace elementui\common;

use tpext\builder\common\Search as baseSearch;
use elementui\traits\HasVue;

/**
 * Search class
 */
class Search extends baseSearch
{
    use HasVue;

    protected $vueData = [];

    public function created()
    {
        parent::created();
        $this->class = 'el-form el-form--label-right';
        $this->butonsSizeClass = 'el-button--mini';
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
        return preg_replace('/[^\w\.]/', '_', $this->getFormId());
    }

    public function getVueEventName()
    {
        return preg_replace('/\W/', '_', $this->getVueFieldName());
    }

    /**
     * Undocumented function
     *
     * @return $this
     */
    public function searchButtons($create = true)
    {
        if ($create) {
            $vueEventName = $this->getVueEventName();

            $this->fieldsEnd();
            $this->fields('search_buttons', ' ', '3 col-lg-3 col-sm-12 col-xs-12 search-buttons')
                ->size('3 col-lg-4 col-sm-2 col-xs-12', '9 col-lg-8 col-sm-8 col-xs-12')
                ->with(
                    $this->button('button', '筛&nbsp;&nbsp;选', '6 col-lg-6 col-sm-6 col-xs-6')->class('btn-info ' . $this->butonsSizeClass)->addAttr('@click="' . $vueEventName . 'Submit"'),
                    $this->button('button', '重&nbsp;&nbsp;置', '6 col-lg-6 col-sm-6 col-xs-6')->class('btn-default ' . $this->butonsSizeClass)->attr('@click="' . $vueEventName . 'Reset"')
                );
        }

        $this->searchButtonsCalled = true;
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

    protected function searchScript()
    {
        $vueEventName = $this->getVueEventName();
        $vueFieldName = $this->getVueFieldName();

        $form = $this->getFormId();

        $script = <<<EOT
        
        {$vueEventName}Submit() {
            var data = {};
            var errors = [];
            for(var k in tpextApp.{$vueFieldName})
            {
                var field = tpextApp.{$vueFieldName}[k];

                if(field.isRequired && (field.value === '' || field.value === null || field.value === undefined))
                {
                    errors.push(field.label + '是必填字段');
                    tpextApp.{$vueFieldName}[k].isError = true;
                }
                else
                {
                    tpextApp.{$vueFieldName}[k].isError = false;
                }

                if(field.isInput)
                {
                    if(k.indexOf('.') > -1)
                    {
                        var arr = k.split('.');
                        if(data[arr[0]] === undefined)
                        {
                            data[arr[0]] = {};
                        }
                        data[arr[0]][arr[1]] = field.value;
                    }
                    else
                    {
                        data[k] = field.value;
                    }
                }
            }

            if(errors.length)
            {
                tpextApp.\$message({
                    message: errors[0],
                    type: 'error',
                    center: true,
                    offset: 200
                });

                return false;
            }

            return window.__forms__['{$form}'].formSubmit(data);
        },
        {$vueEventName}Reset() {
            location.replace(location.href);
        }

EOT;
        $this->vueMethods($script);

        return parent::searchScript();
    }

    public function beforRender()
    {
        parent::beforRender();

        $this->applyVue($this->getFormId());

        return $this;
    }
}
