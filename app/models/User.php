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

    public function getUsers()
    {
        // For the purpose of this example, we are hardcoding
        // a set of user records, but normally you would pull
        // these records from your Model like so:
        //
        // $records = $this->db->select('users', 'id=25');

        $records = [
            0 => [
                'name' => 'Robert Smith',
                'email' => 'robert-smith@email.com',
                'acctId' => 12345
            ],
            1 => [
                'name' => 'Jane Dow',
                'email' => 'jane-doe@email.com',
                'acctId' => 54321
            ],
            2 => [
                'name' => 'Mary Poppins',
                'email' => 'mary-poppins@email.com',
                'acctId' => 15254
            ],
        ];

        return $records;
    }

}