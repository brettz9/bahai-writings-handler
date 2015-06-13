<?php

require('Bahai_Writings_Protocol_Redirector.php');

class BL_Protocol_Redirector extends Bahai_Writings_Protocol_Redirector {
    public $map = array(
        'en' => array(
            'ka'=> array(
                'page' => 'writings/bahaullah/aqdas/kaall.html#',
                'par' => 'writings/bahaullah/aqdas/kaall.html#par'
            ),
            'abl' => array(
                'page' => 'writings/abdulbaha/abl/abdulbahalondon.html#',
            ),
        ),
        'ar' => array (
            // ...
        ),
        'fa' => array (
            // ...
        )
        // TODO: Finish and standardize based on reflib
        // TODO: Make derivative script for bookmarking shortcuts
    );

    protected function check_match () {
        $baseURL = 'http://bahai-library.com/';
        
        if (!is_array($this->map)) {
            $this->report_error("You must supply an array or function as the second argument");
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
        return $baseURL . $this->map[$this->language][$this->work][$unit] . $this->query;
    }
}


?>