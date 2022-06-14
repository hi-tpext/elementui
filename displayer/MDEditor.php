<?php

namespace elementui\displayer;

use elementui\traits\HasVue;
use elementui\traits\HasVueField;

class MDEditor extends \tpext\builder\displayer\MDEditor
{
    use HasVue;
    use HasVueField;

    /*模板样式里面有一个css会影响editor.md的图标,这里重设下*/
    protected $stylesheet =
        '
        .editormd
        {
            margin:15px 0;
        }
        .editormd .divider {
            width: auto;
        }
        .editormd .divider:before,
        .editormd .divider:after {
            margin: 0px;

        }
        '
    ;

    protected function editorScript()
    {
        if (!class_exists('\\tpext\\builder\\mdeditor\\common\\Resource')) {
            $this->js = [];
            $this->css = [];
            $this->script[] = 'layer.alert("未安装mdeditor资源包！<pre>composer require ichynul/builder-mdeditor</pre>");';
            return;
        }

        $inputId = $this->getId();

        /**
         * 上传的后台只需要返回一个 JSON 数据，结构如下：
         * {
         *      success : 0 | 1,           // 0 表示上传失败，1 表示上传成功
         *      message : "提示的信息，上传成功或上传失败及错误信息等。",
         *      url     : "图片地址"        // 上传成功时才返回
         *  }
         */
        if (!isset($this->jsOptions['imageUploadURL']) || empty($this->jsOptions['imageUploadURL'])) {

            $token = $this->getCsrfToken();

            $this->jsOptions['imageUploadURL'] = url($this->getUploadUrl(), [
                'utype' => 'editormd',
                'token' => $token,
                'driver' => $this->getStorageDriver(),
                'is_rand_name' => $this->isRandName(),
                'image_driver' => $this->getImageDriver(),
                'image_commonds' => $this->getImageCommands()
            ]);
        }

        $configs = json_encode($this->jsOptions);

        $vueFieldName =  $this->getVueFieldName();

        $script = <<<EOT

        var mdeditor = editormd("{$inputId}-div", {$configs});

        mdeditor.on('change', function(e){
            tpextApp.{$vueFieldName}.value = mdeditor.markdownTextarea.val();
        });

EOT;
        $this->script[] = $script;

        return $script;
    }
}
