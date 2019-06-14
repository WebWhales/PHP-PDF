<?php

namespace WebWhales\PhpPdf;

use mikehaertl\wkhtmlto\Pdf;

class PdfBuilder extends Pdf
{
    const BIN_DIRECTORY = __DIR__.'./bin';

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

    private function getSystemBinary(): string
    {
        if (\strtolower(substr(PHP_OS, 0, 3)) === 'win') {
            return self::BIN_DIRECTORY.'/win64/wkhtmltopdf.exe';
        }

        $osVariables = [];

        try {
            $osRelease   = \shell_exec('cat /etc/os-release');
            $osRelease   = explode("\n", $osRelease);

            foreach ($osRelease as $osReleaseLine) {
                list($osReleaseLineKey, $osReleaseLineValue) = explode('=', $osReleaseLine, 2);

                $osVariables[\strtolower($osReleaseLineKey)] = $osReleaseLineValue;
            }
        } catch (\Exception $e) {
        }

        if (empty($osVariables['name']) && empty($osVariables['id'])) {
            throw new \Exception('Could not determine Linux OS version');
        }

        if ($osVariables['id'] === 'ubuntu' || \stripos($osVariables['name'], 'ubuntu') !== false) {
            if (\stripos($osVariables['version'], 'bionic') !== false) {
                return self::BIN_DIRECTORY.'/ubuntu/wkhtmltox_0.12.5-1.bionic_amd64.deb';
            } elseif (\stripos($osVariables['version'], 'xenial') !== false) {
                return self::BIN_DIRECTORY.'/ubuntu/wkhtmltox_0.12.5-1.xenial_amd64.deb';
            } elseif (\stripos($osVariables['version'], 'trusty') !== false) {
                return self::BIN_DIRECTORY.'/ubuntu/wkhtmltox_0.12.5-1.trusty_amd64.deb';
            }
        } elseif ($osVariables['id'] === 'debian' || \stripos($osVariables['name'], 'debian') !== false) {
            if (\stripos($osVariables['version'], 'stretch') !== false) {
                return self::BIN_DIRECTORY.'/debian/wkhtmltox_0.12.5-1.stretch_amd64.deb';
            } elseif (\stripos($osVariables['version'], 'jessie') !== false) {
                return self::BIN_DIRECTORY.'/debian/wkhtmltox_0.12.5-1.jessie_amd64.deb';
            }
        }

        $linuxVersion = ($osVariables['id'] ?? $osVariables['name'])." {$osVariables['version']}";

        throw new \Exception("Unsupported Linux distro: {$linuxVersion}");
    }
}