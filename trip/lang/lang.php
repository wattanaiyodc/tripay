<?php
function load_lang_csv($file, $lang = 'th')
{
    $dict = [];

    if (!file_exists($file)) {
        return $dict; // ไฟล์ไม่เจอ
    }

    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $i => $line) {
        $line = trim($line);
        if ($line === '') continue;
        if ($i === 0) continue; // ข้าม header

        $parts = explode(';', $line);

        $key = trim($parts[0] ?? '');
        $en  = trim($parts[1] ?? '');
        $th  = trim(str_replace('::', '', $parts[2] ?? ''));

        if ($key === '') continue;

        $dict[$key] = ($lang === 'en') ? $en : $th;
    }

    return $dict;
}

?>