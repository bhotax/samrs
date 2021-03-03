<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * ---------------------------------------------------------------
 * PRINT OUT
 * ---------------------------------------------------------------
 */
if (!function_exists('print_out')) {

    function print_out($params, $die = false) {
        ob_start();
        $header = "";
        $footer = "";

        $header .= '<!DOCTYPE html>' . "\r\n" . '<html lang="en">' . "\r\n" . '<head>' . "\r\n";
        $header .= '<meta charset="utf-8">' . "\r\n";
        $header .= '<meta http-equiv="X-UA-Compatible" content="IE=edge">' . "\r\n";
        $header .= '<meta name="viewport" content="width=device-width, initial-scale=1">' . "\r\n";
        $header .= '<style type="text/css">' . "\r\n";
        $header .= 'code {font-family: Consolas,Monaco,Courier New,Courier,monospace; font-size: 12px; background-color: #f9f9f9; border: 1px solid #D0D0D0; color: #002166;display: block; margin: 14px 0 14px 0; padding: 12px 10px 12px 10px;}' . "\r\n";
        $header .= 'code p {color: #ff0000;}';
        $header .= '</style>' . "\r\n" . '</head>' . "\r\n" . '<body>' . "\r\n";
        $header .= '<code>' . "\r\n" . '<p>[ Debug Print Out Start ]</p>' . "\r\n";

        $footer .= "\r\n" . '<p>[ Debug Print Out End ]</p>' . "\r\n" . '</code>' . "\r\n";
        $footer .= '</body>' . "\r\n" . '</html>' . "\r\n";

        if (!empty($params) or $params != '') {
            if (is_array($params) or is_object($params)) {
                echo $header;
                echo '<pre>';
                print_r($params);
                echo '</pre>';
                echo $footer;
            }
            else {
                $content1 = htmlspecialchars(htmlspecialchars_decode($params, ENT_QUOTES), ENT_QUOTES, 'utf-8');
                $content2 = str_replace(array(' ', "\n"), array('&nbsp;&nbsp;', '<br>'), $content1);
                $content3 = str_replace('&nbsp; ', '&nbsp;&nbsp;', $content2);
                echo $header;
                echo '<pre>';
                print_r($content3);
                echo '</pre>';
                echo $footer;
            }
        }
        else {
            echo $header;
            echo '<pre>';
            print_r('Output data is empty!');
            echo '</pre>';
            echo $footer;
        }

        if ($die) {
            die();
        }
    }

}

/*
 * ---------------------------------------------------------------
 * ASSETS URL
 * ---------------------------------------------------------------
 */
if (!function_exists('assets_url')) {

    function assets_url($path = '') {
        $ci = & get_instance();
        return $ci->config->slash_item('assets_url') . (($path) ? $path : '');
    }

}

/*
 * ---------------------------------------------------------------
 * TEMPLATES URL
 * ---------------------------------------------------------------
 */
if (!function_exists('templates_url')) {

    function templates_url($path = '') {
        $ci = & get_instance();
        return $ci->config->slash_item('templates_url') . $ci->templates . '/' . (($path) ? $path : '');
    }

}

/*
 * ---------------------------------------------------------------
 * SLUG
 * ---------------------------------------------------------------
 */
if (!function_exists('slug')) {

    function slug($string, $delimiter = '-', $lower = true) {
        if (!empty($string) or $string != '') {
            $patterns = array(
                '/[\s]+/',
                '/[^a-zA-Z0-9_\-\s]/',
                '/[_]+/',
                '/[-]+/',
                '/-/',
                '/[' . $delimiter . ']+/'
            );

            $replace = array(
                '-',
                '-',
                '-',
                '-',
                $delimiter,
                $delimiter
            );

            $string = preg_replace($patterns, $replace, $string);

            if ($lower == true) {
                return strtolower(trim($string));
            }
            else {
                return trim($string);
            }
        }

        return false;
    }

}

/*
 * ---------------------------------------------------------------
 * PARAMETER
 * ---------------------------------------------------------------
 */
if (!function_exists('parameter')) {

    function parameter($string, $delimiter = '_') {
        if (!empty($string) or $string != '') {
            $patterns = array(
                '/[\s]+/',
                '/[^a-zA-Z0-9_\-\s]/',
                '/[_]+/',
                '/[-]+/',
                '/-/',
                '/[' . $delimiter . ']+/'
            );

            $replace = array(
                '_',
                '_',
                '_',
                '_',
                $delimiter,
                $delimiter
            );

            $string = preg_replace($patterns, $replace, $string);

            return strtolower(trim($string));
        }

        return false;
    }

}

/*
 * ---------------------------------------------------------------
 * CHECKDIR
 * ---------------------------------------------------------------
 */
if (!function_exists('checkdir')) {

    function checkdir($path = '') {
        $dir = array();

        if ($path) {
            if ($handle = opendir($path)) {
                while (false !== ($entry = readdir($handle))) {
                    if ($entry != "." && $entry != "..") {
                        $dir[] = $entry;
                    }
                }
                closedir($handle);
                return $dir;
            }
        }

        return false;
    }

}

/*
 * ---------------------------------------------------------------
 * LOAD MODULE ASSETS
 * ---------------------------------------------------------------
 */
if (!function_exists('load_module_assets')) {

    function load_module_assets($type = '', $files = array()) {
        $ci = & get_instance();
        $loc = APPPATH . 'modules/' . $ci->module . '/assets/';
        $dir = checkdir($loc);
        $html = '';

        foreach ($dir as $file) {
            $strpos = strpos($file, ".");
            $filename = substr($file, 0, $strpos);
            $ext = substr($file, $strpos + 1);

            if (!empty($files) && is_array($files)) {
                foreach ($files as $fl) {
                    if ($filename == $fl) {
                        $filename = $fl;
                    }
                    else {
                        $filename = '';
                    }
                }
            }

            if ($filename) {
                if ($type == 'css' && $ext == 'css') {
                    $html .= '<link href="' . base_url() . APPNAME . '/modules/' . $ci->module . '/assets/' . $filename . '.css" rel="stylesheet" type="text/css" >' . "\r\n";
                }
                elseif ($type == 'js' && $ext == 'js') {
                    $html .= '<script src="' . base_url() . APPNAME . '/modules/' . $ci->module . '/assets/' . $filename . '.js"></script>' . "\r\n";
                }
            }
        }

        return $html;
    }

}

/*
 * ---------------------------------------------------------------
 * LOAD ASSETS
 * ---------------------------------------------------------------
 */
if (!function_exists('load_assets')) {

    function load_assets($filename = array(), $type = '', $directories = '') {
        if (!$type) {
            return false;
        }

        $home = substr(APPPATH, 0, -12);
        $dir = ($directories) ? $directories : $home . 'assets';
        $files = scandir($dir);

        foreach ($files as $value) {
            $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
            if (!is_dir($path)) {
                ksort($filename);
                foreach ($filename as $file) {
                    if ($file . ".$type" == $value) {
                        $pos = ($type == 'css') ? strpos($path, 'css') : strpos($path, 'js');
                        $path = substr($path, $pos);
                        $str = str_replace('\\', '/', $path);
                        if ($type == 'css') {
                            echo '<link href="' . assets_url() . $str . '" rel="stylesheet" type="text/css">' . "\r\n";
                        }
                        elseif ($type == 'js') {
                            echo '<script src="' . assets_url() . $str . '"></script>' . "\r\n";
                        }
                    }
                }
            }
            else if ($value != "." && $value != "..") {
                load_assets($filename, $type, $path);
            }
        }
    }

}