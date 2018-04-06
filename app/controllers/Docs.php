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

}