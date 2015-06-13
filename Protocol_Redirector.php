<?php

abstract class Protocol_Redirector {
    public $protocol;
    public $map;
    
    abstract public function parse_query_string();
    abstract protected function check_match();

    public function __construct ($protocol = null, $map = null) {
        $this->init($protocol, $map);
        $this->parse_query_string();
    }
    protected function init ($protocol, $map) { // Override to set up
        if (!isset($this->protocol)) {
            $this->protocol = $protocol;
        }
        if (!isset($this->map)) {
            $this->map = $map;
        }
    }
    public function redirect () {
        $match = $this->check_match();
        if (!$match) {
            $this->report_error("The query provided is not supported in this protocol or is not supported by this handler.");
        }
        $this->relocate($match);
    }
    
    protected function relocate ($url) {
        print 'Redirecting to... ' . $url;
        //exit;
        header('Location: ' . $url);
    }
    
    protected function report_error ($msg) {
        //throw new Exception($msg);
        print $msg;
        exit;
    }
    
}

?>