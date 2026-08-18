<?php

if (! function_exists('file_type')) {
    
    function file_type($mime) 
    {
        $config = new \Config\Images();
        $image_mime = explode(',', $config->imageMimeIn);
        $audio_mime = explode(',', $config->audioMimeIn);
        $video_mime = explode(',', $config->videoMimeIn);
        $type = '';
        if(!empty($image_mime) && in_array($mime, $image_mime)) {
            $type = 'image';
        } elseif(!empty($audio_mime) && in_array($mime, $audio_mime)) {
            $type = 'audio';
        } elseif(!empty($video_mime) && in_array($mime, $video_mime)) {
            $type = 'video';
        } else {
            switch($mime) {
                case 'image/jpg':
                case 'image/jpeg':
                case 'image/gif':
                case 'image/png':
                case 'image/webp':
                case 'image/svg+xml':
                case 'image/bmp':
                case 'image/vnd.microsoft.icon':
                case 'image/tiff':
                    $type = 'image';
                    break;
                case 'application/octet-stream':
                case 'application/x-abiword':
                case 'application/msword':
                case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
                case 'application/vnd.oasis.opendocument.text':
                case 'application/vnd.ms-fontobject':
                case 'application/vnd.ms-word.document.macroEnabled.12':
                case 'application/vnd.ms-word.template.macroEnabled.12':
                case 'application/vnd.apple.pages':
                case 'application/vnd.ms-xpsdocument':
                case 'application/oxps':
                case 'application/rtf':
                case 'application/wordperfect':
                case 'application/epub+zip':
                case 'application/pdf':
                case 'text/csv':
                case 'text/plain':
                    $type = 'document';
                    break;
                case 'audio/aac':
                case 'audio/mpeg':
                case 'audio/ogg':
                case 'audio/wav':
                    $type = 'audio';
                    break;
                case 'video/x-msvideo':
                case 'video/mp4':
                case 'video/ogg':
                    $type = 'video';
                    break;
                case 'application/vnd.oasis.opendocument.presentation':
                case 'application/vnd.ms-powerpoint':
                case 'application/vnd.openxmlformats-officedocument.presentationml.presentation':
                    $type = 'presentation';
                    break;
                case 'application/vnd.apple.numbers':
                case 'application/vnd.oasis.opendocument.spreadsheet':
                case 'application/vnd.ms-excel':
                case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
                case 'application/vnd.ms-excel.sheet.macroEnabled.12':
                case 'application/vnd.ms-excel.sheet.binary.macroEnabled.12':
                    $type = 'spreadsheet';
                    break;
                case 'application/x-bzip':
                case 'application/x-bzip2':
                case 'application/gzip':
                case 'application/x-gzip':
                case 'application/vnd.rar':
                case 'application/rar':
                case 'application/x-tar':
                case 'application/zip':
                case 'application/x-7z-compressed':
                    $type = 'archive';
                    break;
            }
        }
        return $type;
    }
}


if (! function_exists('sanitize_file_name')) {
    
    function sanitize_file_name($str) 
    {
        $str = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $str);
        $str = mb_ereg_replace("([\.]{2,})", '', $str);
        return $str;
    }
}