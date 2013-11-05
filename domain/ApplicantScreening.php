<?php

/** 
 * Our initial idea would be to discard this domain class, as MCHPP doesn't currently
 * do applicant screening of the type that RMH did. But on out call with Brady on Tuesday,
 * he expressed interest in keeping this around because the organization may want to use that
 * functionality in the future. We'll leave this as-is for now.
 * @author Brian, Simon
 * @version Sept. 20, 2013
**/


/**
 * ApplicantScreening class for RMH homebase
 * @author Jackson Moniaga
 * @version March 4, 2012
 */
class ApplicantScreening {

    private $type; // Unique identifier for this screening template -- reflects
    // type of position for which it will be used. 
    // Eg, "volunteer2" or "guestchef1"
    private $creator; // (string) id of who created it e.g. "Gabrielle1111234567"
    private $steps; // array of strings describing the individual steps
    private $status; // "unpublished" or "published"

    /**
     * constructor for all ApplicantScreenings
     */

    function __construct($t, $c, $s, $st) {
        $this->type = $t;
        $this->creator = $c;
        if ($s == "")
            $this->steps = array();
        else
            $this->steps = explode(',', $s);
        $this->status = $st;
    }

    /**
     * getter functions
     */
    function get_type() {
        return $this->type;
    }

    function get_creator() {
        return $this->creator;
    }

    function get_steps() {
        return $this->steps;
    }

    function get_status() {
        return $this->status;
    }

    /*
     * Setter functions
     */

    function set_type($new_type) {
        $this->type = $new_type;
    }

    function set_creator($new_creator) {
        $this->creator = $new_creator;
    }

    function set_steps($new_steps) {
        $this->steps = explode(',', $new_steps);
    }

    function set_status($new_status) {
        $this->status = $new_status;
    }

}

?>