<?php

require('Protocol_Redirector.php');

abstract class Bahai_Writings_Protocol_Redirector extends Protocol_Redirector {
    public $protocol = 'web+bahaiwritings';

    public function parse_query_string () {
        if (!isset($_GET['q'])) {
            $this->report_error("No q parameter specified for the protocol query.");
        }
        $this->query = rawurldecode($_GET['q']);

        $matches = $this->get_params($this->query);
        if (!$matches) {
            $this->report_error(
                "Wrong link format provided; must contain a colon-separated " +
                "series of language, work abbreviation, and query item " +
                "(e.g., page number)."
            );
        }
        $this->language = $matches[1];
        $this->work = $matches[2];
        $this->query = $matches[3];
        $this->unit = isset($matches[4]) ? $matches[4] : '';

        // Not used yet but could allow
        //  `&query=<term>` and `&action=highlight` (ka:5:page:hilite:<freetext-string>)
        $this->action = isset($matches[5]) ? $matches[5] : '';
        $this->additional_argument = isset($matches[6]) ? $matches[6] : '';
    }

    protected function get_params ($query) {
        $matches = array();
        // e.g., "web+bahaiwritings:en-ka:5:par:hilite=some text in the par."
        $parse_regex = ':([^:]{2,}):([^:]*):([^:]*)(?::([^:]+))?(?::([^:]+))?(?::([^:]+))?';
        preg_match(
            '/^' . preg_quote($this->protocol) . $parse_regex . '$/',
            $this->query,
            $matches
        );
        return $matches;
    }

}

?>
