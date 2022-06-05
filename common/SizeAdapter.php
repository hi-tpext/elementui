<?php

namespace elementui\common;

use tpext\builder\common\SizeAdapter as baseSizeAdapter;

class SizeAdapter extends baseSizeAdapter
{
    /**
     * Undocumented function
     *
     * @param string $cloSize
     * @return string
     */
    public function adjustColSize($cloSize = '')
    {
        $cloSize = parent::adjustColSize($cloSize);

        if (preg_match('/^([\d\.]{1,4})\s*/', $cloSize, $mch)) {
            $col = intval($mch[1] * 2);
            $cloSize = preg_replace('/^([\d\.]{1,4})/', $col, $cloSize);
        }

        $cloSize = preg_replace_callback('/\bcol\-(lg|md|sm|xs)\-([\d\.]+?)\b/is', function ($mch) {
            return ' el-col-' . $mch[1] . '-' . (intval($mch[2]) * 2);
        }, $cloSize);

        return $cloSize;
    }

    /**
     * Undocumented function
     *
     * @param array $size
     * @return array
     */
    public function adjustDisplayerSize($size = [2, 8])
    {
        $size = parent::adjustDisplayerSize($size);

        if (preg_match('/^([\d\.]{1,4})\s*/', $size[0], $mch)) {
            $col = intval($mch[1] * 2);
            $size[0] = preg_replace('/^([\d\.]{1,4})/', $col, $size[0]);
        }

        $size[0] = preg_replace_callback('/\bcol\-(lg|md|sm|xs)\-([\d\.]+)\b/is', function ($mch) {
            return 'el-col-' . $mch[1] . '-' . (intval($mch[2]) * 2);
        }, (string)$size[0]);

        //

        if (preg_match('/^([\d\.]{1,4})\s*/', $size[1], $mch)) {
            $col = intval($mch[1] * 2);
            $size[1] = preg_replace('/^([\d\.]{1,4})/', $col, $size[1]);
        }

        $size[1] = preg_replace_callback('/\bcol\-(lg|md|sm|xs)\-([\d\.]+)\b/is', function ($mch) {
            return 'el-col-' . $mch[1] . '-' . (intval($mch[2]) * 2);
        }, (string)$size[1]);

        return $size;
    }
}
