<?php

require('Bahai_Writings_Protocol_Redirector.php');

class BL_Protocol_Redirector extends Bahai_Writings_Protocol_Redirector {
    // JSON to-doos

    // Keep: JSON find-and-replace
    // },\{"name":"[^"]*","keyword":"([^"]*)","url":
    // \n\t\t\t],\n\t\t\t"$1": [\n\t\t\t\t"page":
    // Todo: Make own protocol for Bible and Qur'án
    /*
    "ddbc": {
        "page": "https://bahai-library.com/nsa_developing_distinctive_communities&chapter=1#%s"
    },
    */
    // Todo: Make jump-to-page link for ldg1 and ldg2 both as "ldg"
    // Todo: Make "pt" for "pta"?
    // TODO: Finish and standardize JSON based on reflib

    // Todo:
    // 1. Use external JSON file here
    // 2. Use it to dynamically generate our bookmarking HTML and JSON page;
    //     see https://bahai-library.com/abbreviations_bahai_writings#uses
    // 3. Make the Chrome import page (also on bahai-library.com) use
    //     the JSON instead of doing HTML parsing

    // Todo: Get "cdb" with real page/paragraph anchors:
    //  https://www.bahai.org/library/authoritative-texts/bahaullah/call-divine-beloved/5#117574345

    public $map = ;

    protected function check_match () {
        // $baseURL = 'https://bahai-library.com/';

        if (!is_array($this->map)) {
            $this->report_error(
                'You must supply an array or function as the second argument'
            );
        }
        $unit = $this->unit ? $this->unit : 'page';
        if (!isset($this->map[$this->language]) ||
            !isset($this->map[$this->language][$this->work]) ||
            !isset($this->map[$this->language][$this->work][$unit])) {

            // TODO: Already parsed ok, but not supported apparently, so redirecting to Reflib

            require('Reflib_Protocol_Redirector.php');
            $pr = new Reflib_Protocol_Redirector();
            $pr->redirect();

            return false;
        }
        return str_replace('%s', $this->query, $this->map[$this->language][$this->work][$unit]);
    }
}


?>
