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
    public $subjectId;
    public $writerId;
    public $subjectName;
    public $writerName;
    public $text;
    public $isActive;
    function set_id($id){
        $this->id = $id;
    }
    function get_id(){
        return $this->id;
    }
    function set_subjectId($id){
        $this->subjectId = $id;
    }
    function get_subjectId(){
        return $this->subjectId;
    }
    function set_writerId($id){
        $this->writerId = $id;
    }
    function get_writerId(){
        return $this->writerId;
    }
    function set_writerName($name){
        $this->writerName = $name;
    }
    function get_writerName(){
        return $this->writerName;
    }
    function set_subjectName($name){
        $this->subjectName = $name;
    }
    function get_subjectName(){
        return $this->subjectName;

    }
    function set_text($text){
        $this->text = $text;
    }
    function get_text(){
        return $this->text;
    }
    function set_isActive($isActive){
        $this->isActive = $isActive;
    }
    function get_isActive(){
        return $this->isActive;
    }
}