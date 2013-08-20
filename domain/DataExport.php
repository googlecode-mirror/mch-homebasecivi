<?php

/**
 * DataExport class for RMH homebase.
 * @version February 8, 2012
 */
class DataExport {

    private $export_date;  // date of export
    private $first_name;   // first name - string
    private $last_name; // last name - string	
    private $gender;    // gender - string
    private $type;      // e.g. "volunteer," "guest chef," "parking"
    private $notes;      // string
    private $address;    // street address - string 
    private $city;      // city - string
    private $state;      // state - string
    private $zip;      // zip - string
    private $county;    // county - string  
    private $phone1;    // main phone
    private $phone2;    // alternate phone
    private $email;      // email address - string
    private $employer;    // name of employer - string
    private $status;    // "active," "LOA," or "former"
    private $hours_worked;  // date & hours
    private $day_of_week;  // day of week - string
    private $month;      // month - string
    private $start_date;  // start date
    private $id;      // unique identifier: concatination of export date, 

    // name, and maine phone, with commas in between

    /**
     * constructor for all DataExports
     */
    function __construct($ed, $fn, $ln, $g, $t, $no, $a, $ci, $s, $z, $co, $p1, $p2, $e, $emp, $sts, $hw, $dow, $m, $sd) {
        $this->export_date = $ed;
        $this->first_name = $fn;
        $this->last_name = $ln;
        $this->gender = $g;
        $this->type = $t;
        $this->notes = $no;
        $this->address = $a;
        $this->city = $ci;
        $this->state = $s;
        $this->zip = $z;
        $this->county = $co;
        $this->phone1 = $p1;
        $this->phone2 = $p2;
        $this->email = $e;
        $this->employer = $emp;
        $this->status = $sts;
        $this->hours_worked = $hw;
        $this->day_of_week = $dow;
        $this->month = $m;
        $this->start_date = $sd;
        $this->id = $ed . "," . $n . "," . $p1;
    }

    /*
     *  getter functions
     */

    function get_export_date() {
        return $this->export_date;
    }

    function get_first_name() {
        return $this->first_name;
    }

    function get_last_name() {
        return $this->last_name;
    }

    function get_gender() {
        return $this->gender;
    }

    function get_type() {
        return $this->type;
    }

    function get_notes() {
        return $this->notes;
    }

    function get_address() {
        return $this->address;
    }

    function get_city() {
        return $this->city;
    }

    function get_state() {
        return $this->state;
    }

    function get_zip() {
        return $this->zip;
    }

    function get_county() {
        return $this->county;
    }

    function get_phone1() {
        return $this->phone1;
    }

    function get_phone2() {
        return $this->phone2;
    }

    function get_email() {
        return $this->email;
    }

    function get_employer() {
        return $this->employer;
    }

    function get_status() {
        return $this->status;
    }

    function get_hours_worked() {
        return $this->hours_worked;
    }

    function get_day_of_week() {
        return $this->day_of_week;
    }

    function get_month() {
        return $this->month;
    }

    function get_start_date() {
        return $this->start_date;
    }

    function get_id() {
        return $this->id;
    }

    /*
     * Setter functions
     */

    function set_export_date($ned) {
        $this->export_date = $ned;
    }

    function set_first_name($nfn) {
        $this->first_name = $nn;
    }

    function set_last_name($nln) {
        $this->last_name = $nln;
    }

    function set_gender($ng) {
        $this->gender = $ng;
    }

    function set_type($nt) {
        $this->type = $nt;
    }

    function set_notes($nno) {
        $this->notes = $nno;
    }

    function set_address($na) {
        $this->address = $na;
    }

    function set_city($nc) {
        $this->city = $nc;
    }

    function set_state($ns) {
        $this->state = $ns;
    }

    function set_zip($nz) {
        $this->zip = $nz;
    }

    function set_county($nco) {
        $this->county = $nco;
    }

    function set_phone1($np1) {
        $this->phone1 = $np1;
    }

    function set_phone2($np2) {
        $this->phone2 = $np2;
    }

    function set_email($ne) {
        $this->email = $ne;
    }

    function set_employer($nem) {
        $this->employer = $nem;
    }

    function set_status($nst) {
        $this->status = $nst;
    }

    function set_hours_worked($nhw) {
        $this->hours_worked = $nhw;
    }

    function set_day_of_week($ndow) {
        $this->day_of_week = $ndow;
    }

    function set_month($nm) {
        $this->month = $nm;
    }

    function set_start_date($nsd) {
        $this->start_date = $nsd;
    }

    function set_id($nid) {
        $this->id = $nid;
    }

}

?>