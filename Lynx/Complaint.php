<?php

/**
 * Complaint short summary.
 *
 * Complaint description.
 *
 * @version 1.0
 * @author user
 */
class Complaint
{
    public $id;
    public $driverId;
    public $customerId;
    public $driverName;
    public $customerName;
    public $text;

    function set_id($id){
        $this->id = $id;
    }
    function get_id(){
        return $this->id;
    }
    function set_driverId($id){
        $this->driverId;
    }
    function get_driverId(){
        return $this->driverId;
    }
    function set_customerId($id){
        $this->customerId;
    }
    function get_customerId(){
        return $this->customerId;
    }
    function set_driverName($name){
        $this->driverName;
    }
    function get_driverName(){
        return $this->driverName;
    }
    function set_customerName($name){
        $this->customerName;
    }
    function get_customerName(){
        return $this->customerName;
    
    }
    function set_text($text){
        $this->text = $text;
    }
    function get_text(){
        return $this->text;
    }
}