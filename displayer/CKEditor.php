<?php

namespace elementui\displayer;

use elementui\traits\HasVue;
use elementui\traits\HasVueField;

class CKEditor extends \tpext\builder\displayer\CKEditor
{
    
    use HasVue;
    use HasVueField;

    protected function editorScript()
    {
        if (!class_exists('\\tpext\\builder\\ckeditor\\common\\Resource')) {
            $this->js = [];
            $this->script[] = 'layer.alert("未安装ckeditor资源包！<pre>composer require ichynul/builder-ckeditor</pre>");';
            return;
        }
        // 配置可放在config.js中
        // 成功返回格式{"uploaded":1,"fileName":"图片名称","url":"图片访问路径"}
        // 失败返回格式{"uploaded":0,"error":{"message":"失败原因"}}

        if (!isset($this->jsOptions['filebrowserImageUploadUrl']) || empty($this->jsOptions['filebrowserImageUploadUrl'])) {

            $token = $this->getCsrfToken();

            $this->jsOptions['filebrowserImageUploadUrl'] = url($this->getUploadUrl(), [
                'utype' => 'ckeditor',
                'token' => $token,
                'driver' => $this->getStorageDriver(),
                'is_rand_name' => $this->isRandName(),
                'image_driver' => $this->getImageDriver(),
                'image_commonds' => $this->getImageCommands()
            ]);
        }

        $configs = json_encode($this->jsOptions);

        $vueFieldName =  $this->getVueFieldName();

        // 配置可放在config.js中

        $script = <<<EOT

        var editor = CKEDITOR.replace('{$this->name}', {$configs});
        
        editor.on('change', function(e){
            tpextApp.{$vueFieldName}.value = editor.getData();
        });

EOT;
        $this->script[] = $script;

        return $script;
    }
}
