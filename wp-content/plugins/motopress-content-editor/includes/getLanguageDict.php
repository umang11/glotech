<?php

function motopressCEGetLanguageDict() {
    global $motopressCESettings;

    if (isset($motopressCESettings)) {
        $langFile = $motopressCESettings['plugin_root'].'/'.$motopressCESettings['plugin_name'].'/lang/'.$motopressCESettings['lang'];
    } else {
        $langFile = dirname(__FILE__) . '/../lang/' . (get_option('motopress-language') ? get_option('motopress-language') : 'en.json');
    }

    $contents = json_decode(file_get_contents($langFile));

    return $contents->lang;
}