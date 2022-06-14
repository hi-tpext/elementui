<?php

namespace elementui\displayer;

use elementui\traits\HasVue;
use elementui\traits\HasVueField;

class UEditor extends \tpext\builder\displayer\UEditor
{
    use HasVue;
    use HasVueField;

    protected function editorScript()
    {
        if (!class_exists('\\tpext\\builder\\ueditor\\common\\Resource')) {
            $this->js = [];
            $this->script[] = 'layer.alert("未安装ueditor资源包！<pre>composer require ichynul/builder-ueditor</pre>");';
            return;
        }

        $inputId = $this->getId();

        $vueFieldName =  $this->getVueFieldName();

        $script = <<<EOT

        var ue = UE.getEditor('{$inputId}');
        ue.addListener('contentChange', function(editor){
            tpextApp.{$vueFieldName}.value = ue.getContent();
        });

EOT;
        $this->script[] = $script;

        return $script;
    }
}
