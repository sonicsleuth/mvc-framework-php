<?php if (!defined('BASE_PATH')) exit('No direct script access allowed');

/**
 * This Controller is a collection of example code. 
 * 
 * Some of these example wil require the setup of a database, 
 * but generally should be used as a reference point for best practice.
 */

class Examples extends Controller
{
    public function __construct()
    {
        $this->load_helper(['view']);
    }

    public function passing_data()
    {
        $user = $this->model('User'); 

        $users = $user->getUsers();

        $data = [
            'is_admin' => 'yes', // some arbitrary value we may need in the View.
            'users' => $users // Zero or more user records.
        ];

        $this->view('docs/passing_data', $data);
        
    }

}