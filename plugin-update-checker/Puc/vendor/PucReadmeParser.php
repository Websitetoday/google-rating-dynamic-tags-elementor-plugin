<?php
// File: plugin-update-checker/Puc/v5p5/PucReadmeParser.php
namespace YahnisElsts\PluginUpdateChecker\v5p5;

// Extends the Parsedown library to stub out readme parsing
class PucReadmeParser extends \Parsedown {
    /**
     * Constructor accepts the raw readme contents
     * @param string $text The README.md content
     */
    public function __construct( $text = '' ) {
        parent::__construct();
        // Store the text for potential parsing
        \$this->text = \$text;
    }

    /**
     * Return an array of header lines (e.g. Version, Description) from the readme
     * @return array<string, string>
     */
    public function getHeaderLines() {
        // Minimal stub: no actual parsing, return empty array
        return [];
    }
}