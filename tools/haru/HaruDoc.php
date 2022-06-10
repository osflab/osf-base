<?php 

class HaruDoc
{
    const CS_DEVICE_GRAY = 0;
    const CS_DEVICE_RGB = 1;
    const CS_DEVICE_CMYK = 2;
    const CS_CAL_GRAY = 3;
    const CS_CAL_RGB = 4;
    const CS_LAB = 5;
    const CS_ICC_BASED = 6;
    const CS_SEPARATION = 7;
    const CS_DEVICE_N = 8;
    const CS_INDEXED = 9;
    const CS_PATTERN = 10;
    const ENABLE_READ = 0;
    const ENABLE_PRINT = 4;
    const ENABLE_EDIT_ALL = 8;
    const ENABLE_COPY = 16;
    const ENABLE_EDIT = 32;
    const ENCRYPT_R2 = 2;
    const ENCRYPT_R3 = 3;
    const INFO_AUTHOR = 2;
    const INFO_CREATOR = 3;
    const INFO_TITLE = 5;
    const INFO_SUBJECT = 6;
    const INFO_KEYWORDS = 7;
    const INFO_CREATION_DATE = 0;
    const INFO_MOD_DATE = 1;
    const COMP_NONE = 0;
    const COMP_TEXT = 1;
    const COMP_IMAGE = 2;
    const COMP_METADATA = 4;
    const COMP_ALL = 15;
    const PAGE_LAYOUT_SINGLE = 0;
    const PAGE_LAYOUT_ONE_COLUMN = 1;
    const PAGE_LAYOUT_TWO_COLUMN_LEFT = 2;
    const PAGE_LAYOUT_TWO_COLUMN_RIGHT = 3;
    const PAGE_MODE_USE_NONE = 0;
    const PAGE_MODE_USE_OUTLINE = 1;
    const PAGE_MODE_USE_THUMBS = 2;
    const PAGE_MODE_FULL_SCREEN = 3;


    public function __construct() {}
    public function resetError() {}
    public function save($file) {}
    public function output() {}
    public function saveToStream() {}
    public function resetStream() {}
    public function getStreamSize() {}
    public function readFromStream($bytes) {}
    /**
     * @return HaruPage
     */
    public function addPage() {}
    public function insertPage(HaruPage $page) {}
    /**
     * @return HaruPage
     */
    public function getCurrentPage() {}
    /**
     * @return HaruEncoder
     */
    public function getEncoder($encoding) {}
    /**
     * @return HaruEncoder
     */
    public function getCurrentEncoder() {}
    public function setCurrentEncoder(HaruEncoder $encoding) {}
    public function setPageLayout($layout) {}
    public function getPageLayout() {}
    public function setPageMode($mode) {}
    public function getPageMode() {}
    public function setInfoAttr($type, $info) {}
    public function setInfoDateAttr($type, $year, $month, $day, $hour, $min, $sec, $ind, $off_hour, $off_min) {}
    public function getInfoAttr($type) {}
    public function getFont($fontname, $encoding) {}
    public function loadTTF($fontfile, $embed) {}
    public function loadTTC($fontfile, $index, $embed) {}
    public function loadType1($afmfile, $pfmfile) {}
    public function loadPNG($filename, $deferred) {}
    public function loadJPEG($filename) {}
    public function loadRaw($filename, $width, $height, $color_space) {}
    public function setPassword($owner_password, $user_password) {}
    public function setPermission($permission) {}
    public function setEncryptionMode($mode, $key_len) {}
    public function setCompressionMode($mode) {}
    public function setPagesConfiguration($page_per_pages) {}
    public function setOpenAction($destination) {}
    public function createOutline($title, $parent_outline, $encoder) {}
    public function addPageLabel($first_page, $style, $first_num, $prefix) {}
    public function useJPFonts() {}
    public function useKRFonts() {}
    public function useJPEncodings() {}
    public function useUTFEncodings() {}
    public function useKREncodings() {}
    public function useCNSFonts() {}
    public function useCNSEncodings() {}
    public function useCNTFonts() {}
    public function useCNTEncodings() {}
}
