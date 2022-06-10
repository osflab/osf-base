<?php 

class HaruEncoder
{
    const TYPE_SINGLE_BYTE = 0;
    const TYPE_DOUBLE_BYTE = 1;
    const TYPE_UNINITIALIZED = 2;
    const UNKNOWN = 3;
    const BYTE_TYPE_SINGLE = 0;
    const BYTE_TYPE_LEAD = 1;
    const BYTE_TYPE_TRAIL = 2;
    const BYTE_TYPE_UNKNOWN = 3;
    const WMODE_HORIZONTAL = 0;
    const WMODE_VERTICAL = 1;

    public function getType() {}
    public function getByteType($text, $index) {}
    public function getUnicode($character) {}
    public function getWritingMode() {}
}
