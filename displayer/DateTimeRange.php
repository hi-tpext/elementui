<?php

namespace elementui\displayer;

use elementui\traits\HasVue;
use elementui\traits\HasVueField;

class DateTimeRange extends \tpext\builder\displayer\DateTimeRange
{
    use HasVue;
    use HasVueField;

    protected $view = 'datetime';

    protected $js = [];

    protected $css = [];

    protected $format = 'yyyy-MM-dd HH:mm:ss';

    protected $separator = ',';

    protected $jsOptions = [
        'start_placeholder' => '选择起始时间',
        'end_placeholder' => '选择截止时间',
    ];

    /**
     * Undocumented function
     * @param string $val yyyy-MM-dd HH:mm:ss
     * @return $this
     */
    public function format($val)
    {
        $this->format = str_replace(['Y', 'D'], ['y', 'd'], $val);
        return $this;
    }

    public function beforRender()
    {
        parent::beforRender();

        $this->addAttr('v-model="' . $this->getVueFieldName() . '.value"');
        $this->addAttr('size="small"');
        $this->addAttr('format="' . $this->format . '"');
        $this->addAttr('start-placeholder="' . $this->jsOptions['start_placeholder'] . '"');
        $this->addAttr('end-placeholder="' . $this->jsOptions['end_placeholder'] . '"');
        $this->addAttr('type="datetimerange"');
        $this->addAttr('range-separator="' . $this->separator. '"');

        $this->setPickerOptions();

        return $this;
    }

    protected function setPickerOptions()
    {
        $today = strtotime(date('Y-m-d')) * 1000;

        $pickerOptions = <<<EOT
        {
            shortcuts: [{
                text: '今天',
                onClick(picker) {
                    var end = {$today} + 3600 * 1000 * 24 - 1;
                    var start = {$today};
                    picker.\$emit('pick', [start, end]);
                }
            }, {
                text: '昨天',
                onClick(picker) {
                    var end = {$today} - 1;
                    var start = {$today} - 3600 * 1000 * 24;
                    picker.\$emit('pick', [start, end]);
                }
            }, {
                text: '明天',
                onClick(picker) {
                    var end = {$today} + 3600 * 1000 * 48 - 1;
                    var start = {$today} + 3600 * 1000 * 24;
                    picker.\$emit('pick', [start, end]);
                }
            }, {
                text: '一周前',
                onClick(picker) {
                    var end = new Date();
                    var start = new Date();
                    start.setTime(start.getTime() - 3600 * 1000 * 24 * 7);
                    picker.\$emit('pick', [start, end]);
                }
            }, {
                text: '一周后',
                onClick(picker) {
                    var end = new Date();
                    var start = new Date();
                    end.setTime(end.getTime() + 3600 * 1000 * 24 * 7);
                    picker.\$emit('pick', [start, end]);
                }
            }, {
                text: '30天前',
                onClick(picker) {
                    var end = new Date();
                    var start = new Date();
                    start.setTime(start.getTime() - 3600 * 1000 * 24 * 30);
                    picker.\$emit('pick', [start, end]);
                }
            }, {
                text: '30天后',
                onClick(picker) {
                    var end = new Date();
                    var start = new Date();
                    end.setTime(end.getTime() + 3600 * 1000 * 24 * 30);
                    picker.\$emit('pick', [start, end]);
                }
            }]
        }
        EOT;

        $this->addAttr(':picker-options="' . $pickerOptions . '"');
    }

    protected function dateTimeRangeScript()
    {
        return '';
    }
}
