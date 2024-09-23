<?php

if (!function_exists('highlight')) {
    function highlight($text, $search)
    {
        if (!$search) {
            return $text;
        }

        // Tìm kiếm và thay thế từ khóa bằng cách thêm thẻ <strong>
        return preg_replace('/(' . preg_quote($search, '/') . ')/i', '<strong style="color: red;">$1</strong>', $text);
    }
}