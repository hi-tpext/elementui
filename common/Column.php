<?php

namespace elementui\common;

use tpext\builder\common\Column as baseColumn;

class Column extends baseColumn
{
    /**
     * Undocumented function
     *
     * @return int|string
     */
    public function getSize()
    {
        $cloSize = $this->size;

        if (preg_match('/^([\d\.]{1,4})\s*/', $cloSize, $mch)) {
            $col = intval($mch[1] * 2);
            $cloSize = preg_replace('/^([\d\.]{1,4})/', $col, $cloSize);
        }

        $cloSize = preg_replace_callback('/\s+col\-(lg|md|sm|xs)\-([\d\.]+?)/is', function ($mch) {
            return ' el-col-' . $mch[1] . '-' . (intval($mch[2]) * 2);
        }, $cloSize);

        return $cloSize;
    }
}
