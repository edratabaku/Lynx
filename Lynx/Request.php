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
        return $this->isAccepted;
    }
    function set_isSeen($isSeen){
        $this->isSeen = $isSeen;
    }
    function get_isSeen(){
        return $this->isSeen;
    }
}