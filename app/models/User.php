<?php if (!defined('BASE_PATH')) exit('No direct script access allowed');

class User extends Model
{
    public $fname;
    public $lname;
    private $db;

    public function __construct()
    {
       $this->db = new Model();
    }

    public function getName()
    {
        return $this->fname . ' ' . $this->lname;
    }

}