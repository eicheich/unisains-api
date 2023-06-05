<?php

namespace App\Helpers;

class UrlHelper
{
    public static function formatImageCourseUrl($data)
    {
        return asset('storage/images/thumbnail_course/' . $data);
    }
    public static function formatImageModuleUrl($data)
    {
        return asset('storage/images/module/' . $data);
    }
    public static function formatVideoUrl($data)
    {
        return asset('storage/video/rangkuman/' . $data);
    }
    public static function formatImageArUrl($data)
    {
        return asset('storage/images/ar/' . $data);
    }
}
