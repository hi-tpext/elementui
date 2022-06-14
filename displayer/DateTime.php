<?php

namespace elementui\displayer;

use elementui\traits\HasVue;
use elementui\traits\HasVueField;

class DateTime extends \tpext\builder\displayer\DateTime
{
    use HasVue;
    use HasVueField;

    protected $view = 'datetime';

    protected $js = [];

    protected $css = [];

    protected $format = 'yyyy-MM-dd HH:mm:ss';

    protected $befro = '';

    protected $jsOptions = [
        'firstDayOfWeek' => 1, //周起始日
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

    protected function setPickerOptions()
    {
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

    protected function dateTimeScript()
    {
        $this->addAttr('v-model="' . $this->getVueFieldName() . '.value"');
        $this->addAttr('size="small"');
        $this->addAttr('format="' . $this->format . '"');
        $this->addAttr('value-format="' . $this->format . '"');
        $this->addAttr('type="datetime"');
        $this->addAttr(':firstDayOfWeek="' . $this->jsOptions['firstDayOfWeek'] . '"');
        $this->addStyle('width:100%;max-width:220px;');

        $this->setPickerOptions();

        return '';
    }
}
