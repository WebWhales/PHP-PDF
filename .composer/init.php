<?php

echo \PHP_EOL.\PHP_EOL.'webwhales/php-pdf  ==> Composer::init'.\PHP_EOL.\PHP_EOL;

//
// 1 - Build Path
//
$s    = \DIRECTORY_SEPARATOR;
$path = \dirname(__DIR__).$s.'src'.$s.'bin'.$s.'*'.$s.'*.deb';

//
// 2 - Find files and set permissions
//
$files = \glob($path); // get all file names
foreach ($files as $file) { // iterate files
    echo getFilePermissions($file).'  '.$file.\PHP_EOL;
    \chmod($file, 0755);
}

echo \PHP_EOL.\PHP_EOL.'=========';

/**
 * Helper function to print user friendly permissions.
 *
 * @param $path
 *
 * @return string
 */
function getFilePermissions($path)
{
    if ( ! \file_exists($path)) {
        return 'null';
    }
    $perms = \fileperms($path);

    switch ($perms & 0xF000) {
    case 0xC000: // socket
        $info = 's';
        break;

    case 0xA000: // symbolic link
        $info = 'l';
        break;

    case 0x8000: // regular
        $info = 'r';
        break;

    case 0x6000: // block special
        $info = 'b';
        break;

    case 0x4000: // directory
        $info = 'd';
        break;

    case 0x2000: // character special
        $info = 'c';
        break;

    case 0x1000: // FIFO pipe
        $info = 'p';
        break;

    default: // unknown
        $info = 'u';
}

    // Owner
    $info .= (($perms & 0x0100) ? 'r' : '-');
    $info .= (($perms & 0x0080) ? 'w' : '-');
    $info .= (($perms & 0x0040) ?
    (($perms & 0x0800) ? 's' : 'x') :
    (($perms & 0x0800) ? 'S' : '-'));

    // Group
    $info .= (($perms & 0x0020) ? 'r' : '-');
    $info .= (($perms & 0x0010) ? 'w' : '-');
    $info .= (($perms & 0x0008) ?
    (($perms & 0x0400) ? 's' : 'x') :
    (($perms & 0x0400) ? 'S' : '-'));

    // World
    $info .= (($perms & 0x0004) ? 'r' : '-');
    $info .= (($perms & 0x0002) ? 'w' : '-');
    $info .= (($perms & 0x0001) ?
    (($perms & 0x0200) ? 't' : 'x') :
    (($perms & 0x0200) ? 'T' : '-'));

    return $info;
}