<?php

namespace elementui\displayer;

use elementui\traits\HasVue;
use elementui\traits\HasVueField;

class DateRange extends \tpext\builder\displayer\DateRange
{
    use HasVue;
    use HasVueField;

    protected $view = 'datetime';

    protected $js = [];

    protected $css = [];

    protected $format = 'yyyy-MM-dd';

    protected $separator = ',';

    protected $befro = '';

    protected $jsOptions = [
        'type' => '', //date/month
        'start_placeholder' => '起始日期',
        'end_placeholder' => '截止日期',
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

    public function beforRender()
    {
        parent::beforRender();

        $this->addAttr('v-model="' . $this->getVueFieldName() . '.value"');
        $this->addAttr('size="small"');
        $this->addAttr('format="' . $this->format . '"');
        $this->addAttr('value-format="' . $this->format . '"');
        $this->addAttr('start-placeholder="' . $this->jsOptions['start_placeholder'] . '"');
        $this->addAttr('end-placeholder="' . $this->jsOptions['end_placeholder'] . '"');
        $this->addAttr('range-separator="' . $this->separator . '"');
        $this->addAttr(':firstDayOfWeek="' . $this->jsOptions['firstDayOfWeek'] . '"');
        $this->addStyle('width:100%;max-width:220px;');

        if (empty($this->jsOptions['type'])) { //未明确指定类型

            if (!preg_match('/d$/i', $this->format)) { //yyyy-MM格式，选择的到月份
                $this->jsOptions['type'] = 'monthrange';
            } else {
                $this->jsOptions['type'] = 'daterange';
            }
        }

        $this->addAttr('type="' . $this->jsOptions['type'] . '"');

        $this->setPickerOptions();

        return $this;
    }

    protected function setPickerOptions()
    {
        if ($this->jsOptions['type'] == 'daterange') {
            $yearStart = strtotime(date('Y-01-01')) * 1000;
            $yearHalf0 = strtotime(date('Y-05-31')) * 1000;
            $yearHalf1 = strtotime(date('Y-06-01')) * 1000;
            $yearEnd = strtotime(date('Y-12-31')) * 1000;

            $pickerOptions = <<<EOT
{
    shortcuts: [{
        text: '最近一周',
        onClick(picker) {
            var end = new Date();
            var start = new Date();
            start.setTime(start.getTime() - 3600 * 1000 * 24 * 7);
            picker.\$emit('pick', [start, end]);
        }
    }, {
        text: '最近一个月',
        onClick(picker) {
            var end = new Date();
            var start = new Date();
            start.setTime(start.getTime() - 3600 * 1000 * 24 * 30);
            picker.\$emit('pick', [start, end]);
        }
    }, {
        text: '最近三个月',
        onClick(picker) {
            var end = new Date();
            var start = new Date();
            start.setTime(start.getTime() - 3600 * 1000 * 24 * 90);
            picker.\$emit('pick', [start, end]);
        }
    }, {
        text: '今年上半年',
        onClick(picker) {
            picker.\$emit('pick', [{$yearStart}, {$yearHalf0}]);
        }
    }, {
        text: '今年下半年',
        onClick(picker) {
            picker.\$emit('pick', [{$yearHalf1}, {$yearEnd}]);
        }
    }, {
        text: '今年全年',
        onClick(picker) {
            picker.\$emit('pick', [{$yearStart}, {$yearEnd}]);
        }
    }]
    }
EOT;

            $this->addAttr(':picker-options="' . $pickerOptions . '"');
        } else if ($this->jsOptions['type'] == 'monthrange') {
            $yearStart = strtotime(date('Y-01')) * 1000;
            $yearHalf = strtotime(date('Y-06')) * 1000;
            $yearEnd = strtotime(date('Y-12')) * 1000;

            $pickerOptions = <<<EOT
{
    shortcuts: [{
        text: '最近三个月',
        onClick(picker) {
            var end = new Date();
            var start = new Date();
            start.setTime(start.getTime() - 3600 * 1000 * 24 * 90);
            picker.\$emit('pick', [start, end]);
        }
    }, {
        text: '今年上半年',
        onClick(picker) {
            picker.\$emit('pick', [{$yearStart}, {$yearHalf}]);
        }
    }, {
        text: '今年下半年',
        onClick(picker) {
            picker.\$emit('pick', [{$yearHalf}, {$yearEnd}]);
        }
    }, {
        text: '今年全年',
        onClick(picker) {
            picker.\$emit('pick', [{$yearStart}, {$yearEnd}]);
        }
    }]
    }
EOT;

            $this->addAttr(':picker-options="' . $pickerOptions . '"');
        }
    }

    protected function dateRangeScript()
    {
        return '';
    }
}
