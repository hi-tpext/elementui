<?php

namespace elementui\common;

use tpext\builder\common\Builder as baseBuilder;
use tpext\builder\common\Module as builderModule;
use tpext\builder\common\Widget;
use tpext\builder\common\Wrapper;

class Builder extends baseBuilder
{
    protected $vueData = [];
    protected $vueMounted = [];
    protected $vueMethods = [];
    protected $vueWatch = [];

    protected $commonJs = [
        '/assets/elementui/lib/vue.min.js',
        '/assets/elementui/lib/index.js',
        '/assets/elementui/lib/axios.min.js',
        //
        '/assets/tpextbuilder/js/jquery-validate/jquery.validate.min.js',
        '/assets/tpextbuilder/js/jquery-validate/messages_zh.min.js',
        '/assets/tpextbuilder/js/layer/layer.js',
        '/assets/elementui/js/tpextbuilder.js',
    ];

    protected $commonCss = [
        '/assets/elementui/css/index.css',
        '/assets/elementui/lib/theme-chalk/index.css',
        '/assets/tpextbuilder/css/tpextbuilder.css',
        '/assets/elementui/css/tpextbuilder.css'
    ];

    /**
     * Undocumented function
     *
     * @return $this
     */
    public function created()
    {
        parent::created();

        return $this;

        Column::extend(['Column' => Column::class]);

        Widget::extend([
            'SizeAdapter' => \elementui\common\SizeAdapter::class,
            'Form' => \elementui\common\Form::class,
            'Table' => \elementui\common\Table::class,
            'Search' => \elementui\common\Search::class,
            'FRow' => \elementui\form\FRow::class,
            'SRow' => \elementui\search\SRow::class,
            'TColumn' => \elementui\table\TColumn::class,
        ]);

        Wrapper::setDefaultFieldClass([
            \elementui\displayer\Button::class => 'el-button--default',
            \elementui\displayer\Checkbox::class => '',
            \elementui\displayer\Radio::class => '',
            \elementui\displayer\SwitchBtn::class => '',
        ]);

        Wrapper::extend([
            'field' => \elementui\displayer\Field::class,
            'text' => \elementui\displayer\Text::class,
            'textarea' => \elementui\displayer\Textarea::class,
            'html' => \elementui\displayer\Html::class,
            'divider' => \elementui\displayer\Divider::class,
            'raw' => \elementui\displayer\Raw::class,
            'checkbox' => \elementui\displayer\Checkbox::class,
            'radio' => \elementui\displayer\Radio::class,
            'button' => \elementui\displayer\Button::class,
            'select' => \elementui\displayer\Select::class,
            'multipleSelect' => \elementui\displayer\MultipleSelect::class,
            'dualListbox' => \elementui\displayer\Transfer::class,
            'transfer' => \elementui\displayer\Transfer::class,
            'hidden' => \elementui\displayer\Hidden::class,
            'switchBtn' => \elementui\displayer\SwitchBtn::class,
            'tags' => \elementui\displayer\Tags::class,
            'datetime' => \elementui\displayer\DateTime::class,
            'date' => \elementui\displayer\Date::class,
            'time' => \elementui\displayer\Time::class,
            'datetimeRange' => \elementui\displayer\DateTimeRange::class,
            'dateRange' => \elementui\displayer\DateRange::class,
            'timeRange' => \elementui\displayer\TimeRange::class,
            'color' => \elementui\displayer\Color::class,
            'number' => \elementui\displayer\Number::class,
            'icon' => \elementui\displayer\Icon::class,
            'wangEditor' => \elementui\displayer\WangEditor::class,
            'tinymce' => \elementui\displayer\Tinymce::class,
            'ueditor' => \elementui\displayer\UEditor::class,
            'ckeditor' => \elementui\displayer\CKEditor::class,
            'mdeditor' => \elementui\displayer\MDEditor::class,
            'mdreader' => \elementui\displayer\MDReader::class,
            'editor' => \elementui\displayer\WangEditor::class,
            'rate' => \elementui\displayer\Rate::class,
            'month' => \elementui\displayer\Month::class,
            'year' => \elementui\displayer\Year::class,
            'multipleFile' => \elementui\displayer\MultipleFile::class,
            'file' => \elementui\displayer\File::class,
            'files' => \elementui\displayer\MultipleFile::class,
            'multipleImage' => \elementui\displayer\MultipleImage::class,
            'image' => \elementui\displayer\Image::class,
            'images' => \elementui\displayer\MultipleImage::class,
            'rangeSlider' => \elementui\displayer\RangeSlider::class,
            'match' => \elementui\displayer\Matche::class,
            'matches' => \elementui\displayer\Matches::class,
            'show' => \elementui\displayer\Show::class,
            'password' => \elementui\displayer\Password::class,
            'fields' => \elementui\displayer\Fields::class,
            'map' => \elementui\displayer\Map::class,
            'items' => \elementui\displayer\Items::class,
            'load' => \elementui\displayer\Load::class,
            'loads' => \elementui\displayer\Loads::class,
        ]);

        $viewPath = Module::getInstance()->getRoot() . implode(DIRECTORY_SEPARATOR, ['view', '']);
        $this->view = $viewPath . 'content.html';

        builderModule::getInstance()->setViewsPath($viewPath);
        builderModule::getInstance()->setConfig(['table_empty_text' => Module::getInstance()->config('table_empty_text', '<el-empty description="暂无相关数据~"></el-empty>')]);

        parent::created();

        return $this;
    }

    /**
     * Undocumented function
     *
     * @return array
     */
    public function customVars()
    {
        $vueData = json_encode($this->vueData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        $vueMounted = implode(PHP_EOL, $this->vueMounted);
        $vueMethods = implode(PHP_EOL, $this->vueMethods);
        $vueWatch = implode(PHP_EOL, $this->vueWatch);

        $vuescript = <<<EOT

    var tpextApp = null;
    new Vue({
        el: "#app",
        data() {
            return {$vueData};   
        },
        mounted() {
            tpextApp = this;
            {$vueMounted}
        },
        watch: {
            {$vueWatch}
        },
        methods: {
            {$vueMethods}
        }
    });

EOT;

        return [
            'vuescript' => $vuescript,
        ];
    }

    public function beforRender()
    {
        return parent::beforRender();
    }

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
            $k = str_replace('-', '_', $k);
            $this->vueData[$k] = $v;
        }

        return $this;
    }

    /**
     * 添加vue mounted 事件
     * vueMounted('console.log("666");this.test();')
     * @param string $code
     * @return $this
     */
    public function vueMounted($code)
    {
        if (!empty($code)) {
            $this->vueMounted[] = $code;
        }

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
        if (!empty($code)) {
            $this->vueMethods[] = rtrim($code, "\t\n\r,") . ",";
        }

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
        if (!empty($code)) {
            $this->vueWatch[] = rtrim($code, "\t\n\r,") . ",";
        }

        return $this;
    }
}
