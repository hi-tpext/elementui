<?php

namespace elementui\displayer;

use elementui\traits\HasVue;
use elementui\traits\HasVueField;

class Select extends \tpext\builder\displayer\Select
{
    use HasVue;
    use HasVueField;

    protected $js = [];

    protected $css = [];

    protected $size = [2, 3];

    protected $attr = '';

    /**
     * Undocumented variable
     *
     * @var Select
     */
    protected $prevSelect = null;

    protected $jsOptions = [
        'placeholder' => ''
    ];

    public function beforRender()
    {
        $this->select2 = true;
        parent::beforRender();

        if (!($this->value === '' || $this->value === null)) {
            $this->checked = $this->value;
        } else {
            $this->checked = $this->default;
        }

        $this->value = $this->checked;

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
        $this->addAttr(':loading="' . $this->getVueFieldName() . '.loading"');
        $this->addStyle('width:100%;max-width:220px;');

        $this->vueData(['options' => $options]);
        $this->vueData(['checked' => $this->checked]);
        $this->vueData(['loading' => false]);
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

        $prevVueEventName = $this->prevSelect->getVueEventName();

        $this->prevSelect->addAttr('@change="' . $prevVueEventName . 'Change"');

        $script = <<<EOT
            {$prevVueEventName}Change() {
                tpextApp.{$thisVueFieldName}.options = [];//清空选项
                tpextApp.{$thisVueFieldName}Remote('');//触发远程加载
            }
    EOT;
        $this->vueMethods($script);

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

            $this->addAttr(':remote="true"');
            $this->addAttr(':remote-method="' . $thisVueEventName . 'Remote"');
            $this->vueData(['page' => 1]);

            $ajax = $this->jsOptions['ajax'];
            $url = $ajax['url'];
            $id = $ajax['id'] ?: '_';
            $text = $ajax['text'] ?: '_';

            $prev_id = $this->prevSelect ? $this->prevSelect->getId() : '';

            $withParams = empty($this->withParams) ? '[]' : json_encode($this->withParams);

            $prevText = $this->prevSelect ? "tpextApp.{$prevVueFieldName}.value" : "''";

            $script = <<<EOT
        
            {$thisVueEventName}Remote(query) {
                let that = this;
                var withParams = JSON.parse('{$withParams}');
                var data = {
                    q: query,
                    page: tpextApp.{$thisVueFieldName}.page || 1,
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
                tpextApp.{$thisVueFieldName}.loading = true;
               
                axios({
                    method: 'get',
                    url: '{$url}',
                    params: data,
                    headers: {
                        'x-requested-with': 'xmlhttprequest',
                    },
                }).then(function (res) {
                    console.log(res)
                    var data = res.data;
                    tpextApp.{$thisVueFieldName}.loading = false;
                    var list = data.data ? data.data : data;
                    var options = [];
                    list.forEach(function(d){
                        options.push({
                            value : d.__id__ || d['{$id}'] || d.id,
                            label: d.__text__ || d['{$text}'] || d.text,
                            disabled : false
                        });
                    });
                    if(options.length > 0)
                    {
                        tpextApp.{$thisVueFieldName}.options = tpextApp.{$thisVueFieldName}.options.concat(options);//扩充选项
                        tpextApp.{$thisVueFieldName}.page += 1;
                    }
                }).catch(function (e) {
                    console.log(e);
                    tpextApp.{$thisVueFieldName}.loading = false;
                    tpextApp.\$message.error(JSON.stringify(e), 'error');
                });
            }
    
EOT;
            $this->vueMethods($script);

            $scriptInit = <<<EOT
        
            var selected{$key} = tpextApp.{$thisVueFieldName}.checked;
            var readonly{$key} = tpextApp.{$thisVueFieldName}.isReadonly;
    
            if(selected{$key} !== '')
            {
                axios({
                    method: 'get',
                    url: '{$url}',
                    params: {selected : selected{$key}},
                    headers: {
                        'x-requested-with': 'xmlhttprequest',
                    },
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
                        list.forEach(function(item){
                            options.push({
                                value : item.__id__ || d['{$id}'] || d.id,
                                label: d.__text__ || d['{$text}'] || d.text,
                                disabled : false
                            });
                        });
                        if(options.length > 0)
                        {
                            tpextApp.{$thisVueFieldName}.options = options;//初始化选项
                        }
                    }
                }).catch(function (e) {
                    console.log(e);
                    tpextApp.\$message.error(JSON.stringify(e), 'error');
                    if(readonly{$key})
                    {
                        tpextApp.{$thisVueFieldName}.loadtext = '-加载出错-';
                    }
                });
            }
            else
            {
                if(readonly{$key})
                {
                    tpextApp.{$thisVueFieldName}.loadtext = '加载中...';
                }
            }
    
EOT;
            $this->vueMounted($scriptInit);
        } else {
            //
        }

        return '';
    }
}
