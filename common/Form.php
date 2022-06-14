<?php

namespace elementui\common;

use tpext\builder\common\Form as baseForm;
use elementui\traits\HasVue;

/**
 * Form class
 */
class Form extends baseForm
{
    use HasVue;

    public function created()
    {
        parent::created();

        $this->class = 'el-form el-form--label-right';
        $this->butonsSizeClass = 'el-button--small';

        return $this;
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
     * @return void
     */
    public function applyVue($key)
    {
        if (!empty($this->vueData)) {
            Builder::getInstance()->vueData([$key => $this->vueData]);
        }
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

    protected function validatorScript()
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
            for(var k in tpextApp.{$vueFieldName})
            {
                tpextApp.{$vueFieldName}[k].value = tpextApp.{$vueFieldName}[k].origin_value;
            }
        }

EOT;
        $this->vueMethods($script);

        $script = <<<EOT

        window.focus();

        $(document).bind('keyup', function(event) {
            if (event.keyCode === 0x1B) {
                var index = layer.msg('关闭当前弹窗？', {
                    time: 2000,
                    btn: ['确定', '取消'],
                    yes: function (params) {
                        layer.close(index);
                        var index2 = parent.layer.getFrameIndex(window.name);
                        parent.layer.close(index2);
                    }
                });
                return false; //阻止系统默认esc事件
            }
        });
EOT;
        Builder::getInstance()->addScript($script);

        return $script;
    }

    /**
     * Undocumented function
     *
     * @param string $label
     * @param integer|string $size
     * @param string $class
     * @return $this
     */
    public function btnSubmit($label = '提&nbsp;&nbsp;交', $size = '6 col-lg-6 col-sm-6 col-xs-6', $class = 'btn-info')
    {
        $vueEventName = $this->getVueEventName();

        $this->bottomOffset();
        $this->button('button', $label, $size)->class($class . ' ' . $this->butonsSizeClass)->addAttr('@click="' . $vueEventName . 'Submit"');
        $this->botttomButtonsCalled = true;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param string $label
     * @param integer|string $size
     * @param string $class
     * @return $this
     */
    public function btnReset($label = '重&nbsp;&nbsp;置', $size = '6 col-lg-6 col-sm-6 col-xs-6', $class = 'btn-warning')
    {
        $vueEventName = $this->getVueEventName();

        $this->bottomOffset();
        $this->button('button', $label, $size)->class($class . ' ' . $this->butonsSizeClass)->addAttr('@click="' . $vueEventName . 'Reset"');
        $this->botttomButtonsCalled = true;
        return $this;
    }

    public function beforRender()
    {
        parent::beforRender();

        $this->applyVue($this->getFormId());

        return $this;
    }
}
