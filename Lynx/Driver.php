<?php
/**
 * Driver short summary.
 *
 * Driver description.
 *
 * @version 1.0
 * @author user
 */
class Driver
{
    public $firstName;
    public $lastName;
    public $averageRating;
    public $age;
    public $gender;
    public $area;
    public $numberOfDrives;
    public $numberOfComplaints;
    public $numberOfComments;
    public $id;
    public $userId;
    public $licenseId;
    public $licenseDate;
    public $licenseExpireDate;
    public $car;
    public $supervisorId;
    public $username;
    public $email;
    public $phoneNumber;
    public $comments = array();
    public $complaints = array();

    function set_id($id){
        $this->id = $id;
    }
    function get_id() {
        return $this->id;
    }
    function set_userId($id){
        $this->userId = $id;
    }
    function get_userId() {
        return $this->userId;
    }
    function set_licenseId($licenseId){
        $this->licenseId = $licenseId;
    }
    function get_licenseId() {
        return $this->licenseId;
    }
    function set_licenseDate($licenseDate){
        $this->licenseDate = $licenseDate;
    }
    function get_licenseDate() {
        return $this->licenseDate;
    }
    function set_licenseExpireDate($licenseExpireDate){
        $this->licenseExpireDate = $licenseExpireDate;
    }
    function get_licenseExpireDate() {
        return $this->licenseExpireDate;
    }
    function set_car($car){
        $this->car = $car;
    }
    function get_car() {
        return $this->car;
    }
    function set_supervisorId($supervisorId){
        $this->supervisorId = $supervisorId;
    }
    function get_supervisorId() {
        return $this->supervisorId;
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
    function set_age($dateOfBirth){
        $birthDate = explode("-", $dateOfBirth);
        //get age from date or birthdate
        $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[0], $birthDate[1], $birthDate[2]))) > date("md")
          ? ((date("Y") - $birthDate[0]) - 1)
          : (date("Y") - $birthDate[0]));
        $this->age = $age;
    }
    function get_age() {
        return $this->age;
    }
    function set_gender($gender){
        $this->gender = $gender;
    }
    function get_gender() {
        return $this->gender;
    }
    function set_area($area){
        $this->area = $area;
    }
    function get_area() {
        return $this->area;
    }
    function set_averageRating($area){
        $this->area = $area;
    }
    function get_averageRating() {
        return $this->area;
    }
    function set_numberOfDrives($noDrives){
        $this->numberOfDrives = $noDrives;
    }
    function get_numberOfDrives() {
        return $this->numberOfDrives;
    }
    function set_numberOfComplaints($numberOfComplaints){
        $this->numberOfComplaints = $numberOfComplaints;
    }
    function get_numberOfComplaints() {
        return $this->numberOfComplaints;
    }
    function set_numberOfComments($numberOfComments){
        $this->numberOfComments = $numberOfComments;
    }
    function get_numberOfComments() {
        return $this->numberOfComments;
    }
    function set_comments($comments){
        $this->comments = $comments;
    }
    function get_comments() {
        return $this->comments;
    }
    function set_username($username){
        $this->username = $username;
    }
    function get_username() {
        return $this->username;
    }
    function set_email($email){
        $this->email = $email;
    }
    function get_email() {
        return $this->email;
    }
    function set_phoneNumber($phoneNumber){
        $this->phoneNumber = $phoneNumber;
    }
    function get_phoneNumber() {
        return $this->phoneNumber;
    }
    function add_comment($comment){
        array_push($this->comments,$comment);
    }
    function add_complaint($complaint){
        array_push($this->complaints,$complaint);
    }
}