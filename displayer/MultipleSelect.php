<?php

namespace elementui\displayer;

use elementui\traits\HasVue;
use elementui\traits\HasVueField;

class MultipleSelect extends \tpext\builder\displayer\MultipleSelect
{
    use HasVue;
    use HasVueField;

    protected $view = 'multipleselect';

    protected $js = [];

    protected $css = [];

    protected $attr = '';

    /**
     * Undocumented variable
     *
     * @var Select
     */
    protected $prevSelect = null;

    protected $jsOptions = [
        'placeholder' => '',
        'reserve_keyword' => true,
        'multiple_limit' => 0,
    ];

    public function beforRender()
    {
        $this->select2 = true;
        parent::beforRender();

        if (!($this->value === '' || $this->value === null || $this->value === [])) {
            $this->checked = is_array($this->value) ? $this->value : explode(',', $this->value);
        } else if (!($this->default === '' || $this->default === null || $this->default === [])) {
            $this->checked = is_array($this->default) ? $this->default : explode(',', $this->default);
        }

        foreach ($this->checked as &$ck) {
            $ck = '' . $ck;
        }

        unset($ck);

        if (isset($this->jsOptions['ajax']) && count($this->checked) > 0) {
            $this->value = [''];
            $this->options = ['' => '加载中...'];
        } else {
            $this->value = $this->checked;
        }

        if ($this->disabledOptions && !is_array($this->disabledOptions)) {
            $this->disabledOptions = explode(',', $this->disabledOptions);
        }

        $options = [];

        foreach ($this->options as $key => $opt) {
            $options[] = [
                'value' => '' . $key,
                'label' => $opt,
                'disabled' => in_array($key, $this->disabledOptions)
            ];
        }

        $this->addAttr('v-model="' . $this->getVueFieldName() . '.value"');
        $this->addAttr('size="small"');
        $this->addAttr(':clearable="true"');
        $this->addAttr(':filterable="true"');
        $this->addAttr(':multiple="true"');
        $this->addAttr(':reserve-keyword="' . $this->jsOptions['reserve_keyword'] . '"');
        $this->addAttr(':multiple-limit="' . $this->jsOptions['multiple_limit'] . '"');
        $this->addStyle('width:100%;');

        $this->vueData(['options' => $options]);
        $this->vueData(['checked' => $this->checked]);
        $this->vueData(['loadtext' => '']);

        return $this;
    }

    public function render()
    {
        $vars = $this->commonVars();

        $vars = array_merge($vars, [
            'group' => $this->group,
            'vueFieldName' => $this->getVueFieldName(),
            'vueEventName' => $this->getVueEventName(),
            'placeholder' => $this->jsOptions['placeholder'],
        ]);

        $viewshow = $this->getViewInstance();

        return $viewshow->assign($vars)->getContent();
    }

    /**
     * Undocumented function
     *
     * @param Select $nextSelect
     * @return $this
     */
    public function withNext($nextSelect)
    {
        $nextSelect->withPrev($this);
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param Select $prevSelect
     * @return $this
     */
    public function withPrev($prevSelect)
    {
        $this->prevSelect = $prevSelect;

        return $this;
    }

    /**
     * ajax 时，附带其他字段的值。
     * 与级联有所不同，级联时上级改变会清空下级，并重新加载。
     * 附带字段的值改变不会触发此控件的重新加载，只是在此控件重新加载的时候附加参数。
     * @param array|string $val
     * @return $this
     */
    public function withParams($val)
    {
        $this->withParams = is_array($val) ? $val : explode(',', $val);

        return $this;
    }

    protected function withPrevScript()
    {
        $thisVueFieldName = $this->getVueFieldName();

        $prevVueFieldName = $this->prevSelect->getVueFieldName(true);
        $thisVueEventName = $this->getVueEventName();

        $script = <<<EOT
            '{$prevVueFieldName}.value'(value) {
                console.log(value)
                tpextApp.{$thisVueFieldName}.options = []; //清空选项
                tpextApp.{$thisVueFieldName}.value = ''; //清空值
                tpextApp.{$thisVueEventName}Focus(); //触发远程加载
            }
    EOT;
        $this->vueWatch($script);

        return $this;
    }

    protected function select2Script()
    {
        $selectId = $this->getId();

        $key = preg_replace('/\W/', '', $selectId);

        if (isset($this->jsOptions['ajax'])) {

            $thisVueFieldName = $this->getVueFieldName();
            $thisVueEventName = $this->getVueEventName();

            $prevVueFieldName = $this->prevSelect ? $this->prevSelect->getVueFieldName() : '';
            $formVueFieldName = $this->getWrapper()->getForm()->getVueFieldName();

            $this->addAttr(':filter-method="' . $thisVueEventName . 'Filter"');
            $this->addAttr('popper-class="' . $selectId . '-dropdown"');
            $this->addAttr('@focus="' . $thisVueEventName . 'Focus"');
            $this->addAttr('autocomplete="off"');

            $this->vueData(['page' => 1]);
            $this->vueData(['query' => '']);
            $this->vueData(['has_more' => false]);

            $ajax = $this->jsOptions['ajax'];
            $url = $ajax['url'];
            $id = $ajax['id'] ?: '_';
            $text = $ajax['text'] ?: '_';

            $prev_id = $this->prevSelect ? $this->prevSelect->getId() : '';

            $withParams = empty($this->withParams) ? '[]' : json_encode($this->withParams);

            $prevText = $this->prevSelect ? "tpextApp.{$prevVueFieldName}.value" : "''";

            $script = <<<EOT

            {$thisVueEventName}Focus(e) {
                tpextApp.{$thisVueFieldName}.page = 1;
                tpextApp.{$thisVueFieldName}.options = [];
                tpextApp.{$thisVueFieldName}.query = '';
                tpextApp.{$thisVueEventName}Load();
            },
            {$thisVueEventName}Filter(query) {
                tpextApp.{$thisVueFieldName}.query = query;
                tpextApp.{$thisVueFieldName}.page = 1;
                tpextApp.{$thisVueFieldName}.options = [];
                tpextApp.{$thisVueEventName}Load();
            },
            {$thisVueEventName}Load() {
                var withParams = JSON.parse('{$withParams}');
                var data = {
                    q: tpextApp.{$thisVueFieldName}.query,
                    page: tpextApp.{$thisVueFieldName}.page,
                    prev_val : {$prevText},
                    ele_id : '{$selectId}',
                    prev_ele_id : '{$prev_id}',
                    idField : '{$id}' == '_' ? null : '{$id}',
                    textField : '{$text}' == '_' ? null : '{$text}'
                };
                if(withParams.length)
                {
                    for(var i in withParams)
                    {
                        data[withParams[i]] = tpextApp.{$formVueFieldName}[withParams[i]] ? tpextApp.{$formVueFieldName}[withParams[i]].value : '';
                    }
                }
                axios.get('{$url}', {
                    params: data
                }).then(function (res) {
                    var data = res.data;
                    var list = data.data ? data.data : data;
                    var options = [];
                    list.forEach(function(d){
                        options.push({
                            value : '' + (d.__id__ || d['{$id}'] || d.id),
                            label: '' + (d.__text__ || d['{$text}'] || d.text),
                            disabled : false
                        });
                    });
                    if(options.length > 0)
                    {
                        options = tpextApp.{$thisVueFieldName}.options.concat(options);//扩充选项
                        tpextApp.{$thisVueFieldName}.options = options;
                    }
                    tpextApp.{$thisVueFieldName}.has_more = data.has_more || false;
                }).catch(function (e) {
                    console.log(e);
                    tpextApp.\$message.error(JSON.stringify(e), 'error');
                });
            },
EOT;
            $this->vueMethods($script);

            $scriptInit = <<<EOT
        
            var selected{$key} = tpextApp.{$thisVueFieldName}.checked.join(',');
            var readonly{$key} = tpextApp.{$thisVueFieldName}.isReadonly;
    
            if(selected{$key} !== '')
            {
                axios.get('{$url}', {
                    params: {selected : selected{$key}}
                }).then(function (res) {
                    var data = res.data;
                    var list = data.data ? data.data : data;
                    var options = [];
                    if(readonly{$key})
                    {
                        var texts = [];
                        for(var i in list)
                        {
                            d = list[i];
                            texts.push(d.__text__ || d['{$text}'] || d.text);
                        }
                        tpextApp.{$thisVueFieldName}.loadtext = texts.length ? texts.join('、') : '-空-';
                    }
                    else
                    {
                        
                        list.forEach(function(d){
                            options.push({
                                value : '' + (d.__id__ || d['{$id}'] || d.id),
                                label: '' + (d.__text__ || d['{$text}'] || d.text),
                                disabled : false
                            });
                        });
                        tpextApp.{$thisVueFieldName}.value = tpextApp.{$thisVueFieldName}.checked;
                        if(options.length > 0)
                        {
                            tpextApp.{$thisVueFieldName}.options = options;//初始化选项
                        }
                    }
                }).catch(function (e) {
                    tpextApp.\$message.error(JSON.stringify(e), 'error');
                    if(readonly{$key})
                    {
                        tpextApp.{$thisVueFieldName}.loadtext = '-加载出错-';
                    }
                    else
                    {
                        tpextApp.{$thisVueFieldName}.options = [];
                        tpextApp.{$thisVueFieldName}.value = tpextApp.{$thisVueFieldName}.checked;
                    }
                });
            }
            else
            {
                tpextApp.{$thisVueFieldName}.value = tpextApp.{$thisVueFieldName}.checked;

                if(readonly{$key})
                {
                    tpextApp.{$thisVueFieldName}.loadtext = '加载中...';
                }
                else
                {
                    tpextApp.{$thisVueFieldName}.options = [];
                    tpextApp.{$thisVueFieldName}.value = tpextApp.{$thisVueFieldName}.checked;
                }
            }

            var {$key}dropdown__wrap = document.querySelector('.{$selectId}-dropdown .el-select-dropdown__wrap')
            {$key}dropdown__wrap.addEventListener('scroll', function() {
                var condition = this.scrollHeight - this.scrollTop <= this.clientHeight
                if (condition) 
                {
                    if(tpextApp.{$thisVueFieldName}.has_more)
                    {
                        tpextApp.{$thisVueFieldName}.page += 1;
                        tpextApp.{$thisVueEventName}Load();
                    }
                }
            });
EOT;
            $this->vueMounted($scriptInit);
        } else {
            //
        }

        return '';
    }
}
