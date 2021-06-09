<?php

namespace Kanboard\Helper;

use Kanboard\Core\Base;

/**
 * File helpers
 *
 * @package helper
 * @author  Frederic Guillot
 */
class FileHelper extends Base
{
    /**
     * Get file icon
     *
     * @access public
     * @param  string   $filename   Filename
     * @return string               Font-Awesome-Icon-Name
     */
    public function icon($filename)
    {
        switch (get_file_extension($filename)) {
            case 'jpeg':
            case 'jpg':
            case 'png':
            case 'gif':
                return 'fa-file-image-o';
            case 'xls':
            case 'xlsx':
                return 'fa-file-excel-o';
            case 'doc':
            case 'docx':
                return 'fa-file-word-o';
            case 'ppt':
            case 'pptx':
                return 'fa-file-powerpoint-o';
            case 'zip':
            case 'rar':
            case 'tar':
            case 'bz2':
            case 'xz':
            case 'gz':
                return 'fa-file-archive-o';
            case 'mp3':
                return 'fa-file-audio-o';
            case 'avi':
            case 'mov':
                return 'fa-file-video-o';
            case 'php':
            case 'html':
            case 'css':
                return 'fa-file-code-o';
            case 'pdf':
                return 'fa-file-pdf-o';
        }

        return 'fa-file-o';
    }

    /**
     * Return the image mimetype based on the file extension
     *
     * @access public
     * @param  $filename
     * @return string
     */
    public function getImageMimeType($filename)
    {
        switch (get_file_extension($filename)) {
            case 'jpeg':
            case 'jpg':
                return 'image/jpeg';
            case 'png':
                return 'image/png';
            case 'gif':
                return 'image/gif';
            default:
                return 'image/jpeg';
        }
    }

    /**
     * Get the preview type
     *
     * @access public
     * @param  string $filename
     * @return string
     */
    public function getPreviewType($filename)
    {
        switch (get_file_extension($filename)) {
            case 'md':
            case 'markdown':
                return 'markdown';
            case 'txt':
                return 'text';
        }

        return null;
    }

    /**
     * Return the browser view mime-type based on the file extension.
     *
     * @access public
     * @param  $filename
     * @return string
     */
    public function getBrowserViewType($filename)
    {
        switch (get_file_extension($filename)) {
            case 'pdf':
                return 'application/pdf';
            case 'mp3':
                return 'audio/mpeg';
        }

        return null;
    }

    /**
     * This function was found on StackOverflow at https://stackoverflow.com/a/2638272/802469 
     *
     *
     */
    function getRelativePath($from, $to)
    {
        // some compatibility fixes for Windows paths
        $from = is_dir($from) ? rtrim($from, '\/') . '/' : $from;
        $to   = is_dir($to)   ? rtrim($to, '\/') . '/'   : $to;
        $from = str_replace('\\', '/', $from);
        $to   = str_replace('\\', '/', $to);

        $from     = explode('/', $from);
        $to       = explode('/', $to);
        $relPath  = $to;

        foreach($from as $depth => $dir) {
            // find first non-matching dir
            if($dir === $to[$depth]) {
                // ignore this directory
                array_shift($relPath);
            } else {
                // get number of remaining dirs to $from
                $remaining = count($from) - $depth;
                if($remaining > 1) {
                    // add traversals up to first matching dir
                    $padLength = (count($relPath) + $remaining - 1) * -1;
                    $relPath = array_pad($relPath, $padLength, '..');
                    break;
                } else {
                    $relPath[0] = './' . $relPath[0];
                }
            }
        }
        return implode('/', $relPath);
    }

}
