<?php

/**
 * Review short summary.
 *
 * Review description.
 *
 * @version 1.0
 * @author user
 */
class Review
{
    public $id;
    public $driverId;
    public $customerId;
    public $driverName;
    public $customerName;
    public $rating;
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
        $this->customerId = $id;
    }
    function get_customerId(){
        return $this->customerId;
    }
    function set_driverName($name){
        $this->driverName = $name;
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
    function set_rating($rating){
        $this->rating = $rating;
    }
    function get_rating(){
        return $this->rating;
    }
    function set_text($text){
        $this->text = $text;
    }
    function get_text(){
        return $this->text;
    }
}