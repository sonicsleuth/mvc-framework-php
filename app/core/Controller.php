<?php if (!defined('BASE_PATH')) exit('No direct script access allowed');

class Controller
{
    /**
     * @param $model
     * @return bool
     *
     * Load specified Model if the file exists.
     */
    protected function model($model)
    {
        if(file_exists(MODELS_PATH . $model . '.php'))
        {
            require_once MODELS_PATH . $model . '.php';
            return new $model();
        } else {
            return false;
        }
    }

    /**
     * @param $view
     * @param array $data
     * @return bool
     *
     * Load specified View if the file exists.
     * The values of $data are available to the View as are the
     * index of each $data as it's own variable.
     */
    protected function view($view, $data = [])
    {
        if(file_exists(VIEWS_PATH . $view . '.php'))
        {
            // Set each index of data to its named variable.
            if( is_array($data[0]) ) {
                foreach($data[0] as $key => $value) {
                    $$key = $value;
                }
            }
            require_once VIEWS_PATH . $view . '.php';
        } else {
            return false;
        }
    }

    /**
     * @param array $files
     *
     * Load Helper files.
     *
     */
    protected function load_helper($files = [])
    {
        foreach ($files as $file)
        {
            require_once HELPERS_PATH . $file . '.php';
        }
    }
}