<?php

namespace WebWhales\PhpPdf\tests\Unit;

use mikehaertl\tmp\File;
use PhpUnit\Framework\TestCase;
use WebWhales\PhpPdf\PdfBuilder;

/**
 * @internal
 *
 * @small
 * @coversNothing
 */
class PhpPdfTest extends TestCase
{
    /**
     *
     * @var string
     */
    private $assetsRoot;

    /**
     *
     * @var string
     */
    private $writeRoot;

    /**
     * Run.
     */
    protected function setUp() : void
    {
        $this->assetsRoot = \dirname(__DIR__).\DIRECTORY_SEPARATOR.'assets'.\DIRECTORY_SEPARATOR;
        $this->writeRoot  = \dirname(__DIR__).\DIRECTORY_SEPARATOR.'_tmp'.\DIRECTORY_SEPARATOR;

        //
        // empty _tmp folder
        //
        $files = \glob($this->writeRoot.'*'); // get all file names
        foreach ($files as $file) { // iterate files
            if (\is_file($file)) {
                \unlink($file);
            } // delete file
        }
    }

    /**
     * @testdox - Short Description of unit/feature test -
     * @test
     * @covers - class(::method)s -
     */
    public function SHOULD Generate_pdf WHEN parse_template()
    {
        //
        // Init builder
        //
        $allowPath  = \dirname(__DIR__).\DIRECTORY_SEPARATOR.'assets'.\DIRECTORY_SEPARATOR.'wkhtmltopdf_files'.\DIRECTORY_SEPARATOR;
        $stylesheet = new File('.ww-quickscan__single__results__questions_answers__body__section * {page-break-inside: avoid;}', '.css');
        \var_dump($allowPath);

        $pdfBuilder = new PdfBuilder();
        $pdfBuilder->setOptions([
            'allow'            => $allowPath,
            'user-style-sheet' => $stylesheet,
            'header-html'      => $this->assetsRoot . '/header.html',
            'footer-html'      => $this->assetsRoot . '/footer.html',
            'disable-smart-shrinking',
            //Prevent dpi/pixel converting
            'header-spacing' => 5,
            'footer-spacing' => 5,
        ]);

        //
        // add content
        //

        $content = \file_get_contents($this->assetsRoot.'wkhtmltopdf.html');
        $pdfBuilder->addPage($content,[]);

        //
        // Write pdf
        //
        $pdfPath = $this->writeRoot .'site_wkhtmltopdf.pdf';
        $success = $pdfBuilder->saveAs($pdfPath);

        //
        // Test if the pdfBuilder has saved the pdf
        //
        $this->assertTrue($success, $pdfBuilder->getError());

        //
        // Test if the file exists
        //
        $this->assertFileExists($pdfPath);
    }
}