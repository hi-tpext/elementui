<?php

namespace elementui\displayer;

use elementui\traits\HasVue;
use elementui\traits\HasVueField;

class WangEditor extends \tpext\builder\displayer\WangEditor
{
    use HasVue;
    use HasVueField;

    protected function editorScript()
    {
        $inputId = $this->getId();

        if (!isset($this->jsOptions['uploadImgServer']) || empty($this->jsOptions['uploadImgServer'])) {

            $token = $this->getCsrfToken();

            $this->jsOptions['uploadImgServer'] = url($this->getUploadUrl(), [
                'utype' => 'wangeditor',
                'token' => $token,
                'driver' => $this->getStorageDriver(),
                'is_rand_name' => $this->isRandName(),
                'image_driver' => $this->getImageDriver(),
                'image_commonds' => $this->getImageCommands()
            ]);
        }

        $this->jsOptions['uploadImgParams'] = [];

        $configs = json_encode($this->jsOptions);

        $vueFieldName =  $this->getVueFieldName();

        $script = <<<EOT

        var E = window.wangEditor;
        var editor = new E('#{$inputId}-div');

        editor.customConfig = {$configs};

        editor.customConfig.uploadImgHooks = {
            customInsert: function (insertImg, result, editor) {
                // 图片上传并返回结果，自定义插入图片的事件（而不是编辑器自动插入图片！！！）
                // insertImg 是插入图片的函数，editor 是编辑器对象，result 是服务器端返回的结果
                // 举例：假如上传图片成功后，服务器端返回的是 {url:'....'} 这种格式，即可这样插入图片：
                var url = result.url;
                insertImg(url);
                // result 必须是一个 JSON 格式字符串！！！否则报错
            }
        };
        // 设置内容
        editor.customConfig.onchange = function (html) {
            tpextApp.{$vueFieldName}.value = html;
        }
        editor.create();

EOT;
        $this->script[] = $script;

        return $script;
    }
}
