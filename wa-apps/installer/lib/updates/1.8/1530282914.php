<?php

// files to delete
$_file_paths = array();

// old controllers in installer settings
$_file_paths[] = wa()->getAppPath('js/settings.js', 'installer');

foreach ($_file_paths as $_file_path) {
    if (file_exists($_file_path)) {
        try {
            waFiles::delete($_file_path);
        } catch (Exception $e) {
        }
    }
}

waAppConfig::clearAutoloadCache('installer');
