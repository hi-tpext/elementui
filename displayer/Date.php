<?php

namespace elementui\displayer;

use elementui\traits\HasVue;
use elementui\traits\HasVueField;

class Date extends \tpext\builder\displayer\Date
{
    use HasVue;
    use HasVueField;

    protected $view = 'datetime';

    protected $js = [];

    protected $css = [];

    protected $format = 'yyyy-MM-dd';

    protected $befro = '';

    protected $jsOptions = [
        'type' => '', //date/month
        'firstDayOfWeek' => 1, //周起始日
    ];

    /**
     * Undocumented function
     * @param string $val yyyy-MM-dd
     * @return $this
     */
    public function format($val)
    {
        $this->format = str_replace(['Y', 'm', 'D'], ['y', 'M', 'd'], $val);
        return $this;
    }

    protected function setPickerOptions()
    {
        if ($this->jsOptions['type'] == 'date') {

            $pickerOptions = <<<EOT
{
    shortcuts: [{
        text: '今天',
        onClick(picker) {
            picker.\$emit('pick', new Date());
        }
    }, {
        text: '昨天',
        onClick(picker) {
            var date = new Date();
            date.setTime(date.getTime() - 3600 * 1000 * 24);
            picker.\$emit('pick', date);
        }
    }, {
        text: '明天',
        onClick(picker) {
            var date = new Date();
            date.setTime(date.getTime() + 3600 * 1000 * 24);
            picker.\$emit('pick', date);
        }
    }, {
        text: '一周前',
        onClick(picker) {
            var date = new Date();
            date.setTime(date.getTime() - 3600 * 1000 * 24 * 7);
            picker.\$emit('pick', date);
        }
    }, {
        text: '一周后',
        onClick(picker) {
            var date = new Date();
            date.setTime(date.getTime() + 3600 * 1000 * 24 * 7);
            picker.\$emit('pick', date);
        }
    }, {
        text: '30天前',
        onClick(picker) {
            var date = new Date();
            date.setTime(date.getTime() - 3600 * 1000 * 24 * 30);
            picker.\$emit('pick', date);
        }
    }, {
        text: '30天后',
        onClick(picker) {
            var date = new Date();
            date.setTime(date.getTime() + 3600 * 1000 * 24 * 30);
            picker.\$emit('pick', date);
        }
    }]
}
EOT;

            $this->addAttr(':picker-options="' . $pickerOptions . '"');
        }
    }

    protected function dateTimeScript()
    {
        $this->addAttr('v-model="' . $this->getVueFieldName() . '.value"');
        $this->addAttr('size="small"');
        $this->addAttr('format="' . $this->format . '"');
        $this->addAttr('value-format="' . $this->format . '"');
        $this->addAttr(':firstDayOfWeek="' . $this->jsOptions['firstDayOfWeek'] . '"');
        $this->addStyle('width:100%;max-width:220px;');

        if (empty($this->jsOptions['type'])) { //未明确指定类型

            if (!preg_match('/d$/i', $this->format)) { //yyyy-MM格式，选择的到月份
                $this->jsOptions['type'] = 'month';
            } else {
                $this->jsOptions['type'] = 'date';
            }
        }

        $this->addAttr('type="' . $this->jsOptions['type'] . '"');

        $this->setPickerOptions();

        return '';
    }
}
