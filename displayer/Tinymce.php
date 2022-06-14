<?php

namespace elementui\displayer;

use elementui\traits\HasVue;
use elementui\traits\HasVueField;

class Tinymce extends \tpext\builder\displayer\Tinymce
{
    use HasVue;
    use HasVueField;

    protected function editorScript()
    {
        if (!class_exists('\\tpext\\builder\\tinymce\\common\\Resource')) {
            $this->js = [];
            $this->script[] = 'layer.alert("未安装tinymce资源包！<pre>composer require ichynul/builder-tinymce</pre>");';
            return;
        }

        $inputId = $this->getId();

        if (!isset($this->jsOptions['images_upload_url']) || empty($this->jsOptions['images_upload_url'])) {

            $token = $this->getCsrfToken();

            $this->jsOptions['images_upload_url'] = url($this->getUploadUrl(), [
                'utype' => 'tinymce',
                'token' => $token,
                'driver' => $this->getStorageDriver(),
                'is_rand_name' => $this->isRandName(),
                'image_driver' => $this->getImageDriver(),
                'image_commonds' => $this->getImageCommands()
            ]);
        }

        $this->jsOptions['selector'] = "#{$inputId}";

        $configs = json_encode($this->jsOptions);
        $configs = substr($configs, 1, strlen($configs) - 2);

        $vueFieldName =  $this->getVueFieldName();

        $script = <<<EOT

        tinymce.init(
            {{$configs},
            init_instance_callback: function(editor) {
                console.log('init_instance_callback');
                editor.on('Change', function(e) {
                    tpextApp.{$vueFieldName}.value = editor.getContent();
                });
            }}
        );

EOT;
        $this->script[] = $script;

        return $script;
    }
}
