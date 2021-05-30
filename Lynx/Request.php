<?php

/**
 * Request short summary.
 *
 * Request description.
 *
 * @version 1.0
 * @author user
 */
class Request
{
    public $firstName;
    public $lastName;
    public $driverId;
    public $timeOfRequest;
    public $isAccepted;
    public $isSeen;
    public $requestedById;
    public $driverFullName;
    public $customerFullName;
    public $driverUserName;
    public $customerUserName;
    public $address;
    public $destination;

    function set_requestedById($requestedById){
        $this->requestedById = $requestedById;
    }
    function get_requestedById() {
        return $this->requestedById;
    }
    function set_driverId($id){
        $this->driverId = $id;
    }
    function get_driverId() {
        return $this->driverId;
    }
    function set_timeOfRequest($timeOfRequest){
        $this->timeOfRequest = $timeOfRequest;
    }
    function get_timeOfRequest() {
        return $this->timeOfRequest;
    }
    function set_firstName($name){
        $this->firstName = $name;
    }
    function get_firstName() {
        return $this->firstName;
    }
    function set_lastName($name){
        $this->lastName = $name;
    }
    function get_lastName() {
        return $this->lastName;
    }
    function set_isAccepted($isAccepted){
        $this->isAccepted = $isAccepted;
    }
    function get_isAccepted(){
        if($this->isAccepted == 1){
            return "Yes";
        }
        else{
            return "No";
        }
    }
    function set_isSeen($isSeen){
        $this->isSeen = $isSeen;
    }
    function get_isSeen(){
        if($this->isSeen == 1){
            return "Yes";
        }
        else{
            return "No";
        }
    }
    function set_driverFullName($fullname){
        $this->driverFullName = $fullname;
    }
    function get_driverFullName(){
        return $this->driverFullName;
    }
    function set_customerFullName($fullname){
        $this->customerFullName = $fullname;
    }
    function get_customerFullName(){
        return $this->customerFullName;
    }
    function set_customerUserName($username){
        $this->customerUserName = $username;
    }
    function get_customerUserName(){
        return $this->customerUserName;
    }
    function set_driverUserName($username){
        $this->driverUserName = $username;
    }
    function get_driverUserName(){
        return $this->driverUserName;
    }
    function set_address($address){
        $this->address = $address;
    }
    function get_address(){
        return $this->address;
    }
    function set_destination($destination){
        $this->destination = $destination;
    }
    function get_destination(){
        return $this->destination;
    }
}