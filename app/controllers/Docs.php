<?php if (!defined('BASE_PATH')) exit('No direct script access allowed');

class Docs extends Controller
{
    public function __construct()
    {
        $this->load_helper(['view']);
    }

    public function index($param1 = '', $param2 = '')
    {
        $this->view('docs/index');
    }

    public function phpinfo()
    {
        echo phpinfo();
    }

    public function session()
    {
        $session = $this->model('Session');

        $_SESSION['fname'] = 'Walter';
        $_SESSION['lname'] = 'Smith';
        $_SESSION['title'] = 'Sales Manager';

        echo "<pre>";
        echo "GET ALL SESSION DATA:";
        echo "\r\n\r\n ";
        $data = $session->getSessionData();
        print_r($data);

    }

    public function language()
    {
        $this->view('docs/language');
    }

}