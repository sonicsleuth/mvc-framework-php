<?php if (!defined('BASE_PATH')) exit('No direct script access allowed');

class Home extends Controller
{
    public function __construct()
    {
        $this->load_helper(['view']);
    }

    public function index($param1 = '', $param2 = '')
    {
        $this->view('home');
    }

    public function phpinfo()
    {
        echo phpinfo();
    }

}