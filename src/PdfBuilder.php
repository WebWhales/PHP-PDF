<?php

namespace WebWhales\PhpPdf;

use mikehaertl\wkhtmlto\Pdf;

class PdfBuilder extends Pdf
{
    const BIN_DIRECTORY = __DIR__.\DIRECTORY_SEPARATOR.'bin';

    /**
     * {@inheritdoc}
     */
    public function __construct($options = null)
    {
        $newOptions = [
            'binary' => $this->getSystemBinary(),
        ];

        if (\is_string($options)) {
            parent::__construct($options);

            $this->setOptions($newOptions);

            return;
        }

        if (\is_array($options)) {
            $newOptions = \array_merge($newOptions, $options);
        }

        parent::__construct($newOptions);
    }

    private function getSystemBinary() : string
    {
        if (\mb_strtolower(\mb_substr(\PHP_OS, 0, 3)) === 'win') {
            return self::BIN_DIRECTORY.'/win64/wkhtmltopdf.exe';
        }

        return self::BIN_DIRECTORY.'/linux/wkhtmltopdf_linux_amd64';
//        $osVariables = [];
//
//        try {
//            $osRelease   = \shell_exec('cat /etc/os-release');
//            $osRelease   = \explode("\n", $osRelease);
//
//            foreach ($osRelease as $osReleaseLine) {
//                [$osReleaseLineKey, $osReleaseLineValue] = \explode('=', $osReleaseLine, 2);
//
//                $osVariables[\mb_strtolower($osReleaseLineKey)] = $osReleaseLineValue;
//            }
//        } catch (\Exception $e) {
//        }
//
//        if (empty($osVariables['name']) && empty($osVariables['id'])) {
//            throw new \Exception('Could not determine Linux OS version');
//        }
//
//        if ($osVariables['id'] === 'ubuntu' || \mb_stripos($osVariables['name'], 'ubuntu') !== false) {
//            if (\mb_stripos($osVariables['version'], 'bionic') !== false) {
//                return self::BIN_DIRECTORY.'/ubuntu/wkhtmltox_0.12.5-1.bionic_amd64.deb';
//            } elseif (\mb_stripos($osVariables['version'], 'xenial') !== false) {
//                return self::BIN_DIRECTORY.'/ubuntu/wkhtmltox_0.12.5-1.xenial_amd64.deb';
//            } elseif (\mb_stripos($osVariables['version'], 'trusty') !== false) {
//                return self::BIN_DIRECTORY.'/ubuntu/wkhtmltox_0.12.5-1.trusty_amd64.deb';
//            }
//        } elseif ($osVariables['id'] === 'debian' || \mb_stripos($osVariables['name'], 'debian') !== false) {
//            if (\mb_stripos($osVariables['version'], 'stretch') !== false) {
//                return self::BIN_DIRECTORY.'/debian/wkhtmltox_0.12.5-1.stretch_amd64.deb';
//            } elseif (\mb_stripos($osVariables['version'], 'jessie') !== false) {
//                return self::BIN_DIRECTORY.'/debian/wkhtmltox_0.12.5-1.jessie_amd64.deb';
//            }
//        }
//
//        $linuxVersion = ($osVariables['id'] ?? $osVariables['name'])." {$osVariables['version']}";
//
//        throw new \Exception("Unsupported Linux distro: {$linuxVersion}");
    }
}

/*
 * WkHTMLtoPdf exitcodes
 *
EXITCODE     EXPLANATION
0            All OK
1            PDF generated OK, but some request(s) did not return HTTP 200
2            Could not something something
X            Could not write PDF: File in use
Y            Could not write PDF: No write permission
Z            PDF generated OK, but some JavaScript requests(s) timeouted
A            Invalid arguments provided
B            Could not find input file(s)
C            Process timeout

*/