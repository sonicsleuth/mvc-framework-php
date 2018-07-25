<?php if (!defined('BASE_PATH')) exit('No direct script access allowed');

class App
{
    protected $url = [];
    protected $url_string = '';
    protected $controller_endpoint = '';
    protected $controller = '';
    protected $method_index = 1;
    protected $method = '';
    protected $params = [];
    protected $route = [];

    public function __construct($config = [], $route = [])
    {
        /*
         * Set the default Controller and Method to call when they are not specified in the URL.
         */
        $this->controller = $config['default_controller'];
        $this->method = $config['default_method'];

        /*
         * Parse the URL having the format of /controller/method/param1/param2 into an array.
         * Remove the Controller and Method indexes from the array  after we obtain them
         * so that only the parameters remain for us to pass on to the defined method.
         *
         * The controller file name and Class name must have use a CamelCase format like: Products, MyProducts
         * However, when calling these controllers from the URL use lowercase with hyphens.
         * Examples:
         * Calling: /my-products/list results in calling the Controller 'MyProducts' and the method 'list'.
         * Calling: /admin/top-sales/list results in calling the Controller 'TopSales' and the method 'list' from the sub-directory '/Admin/'.
         *
         * Controllers may also be nested in unlimited sub-directories allowing for the reuse of Controller names:
         * Examples:
         * Get Basic User: /user/get/1 (/controller/method/param1)
         * Get Admin User: /admin/user/get/1 (/directory/controller/method/param1)
         *
         * You may exclude adding the "index" method to the URL as this is set by default.
         * It will affect the parameters that follow in the URL.
         * Examples:
         * /sales-report/index/january is the equivalent to /sales-report/january
         * Which loads the SalesReport controller and calls the "index" method passing to it "january".
         *
         */
        $this->url = $this->parseUrl($config, $route);

        // Set Controller or use default Controller.
        // Walk down the URL assuming the previous index may be a directory under Controllers.
        $controller_exists = false;
        foreach($this->url as $key => $value )
        {
            $this->controller_endpoint .= $this->dashesToCamelCase($value);

            if(file_exists(CONTROLLERS_PATH . $this->controller_endpoint . '.php')) {
                $this->controller = $this->dashesToCamelCase($value);
                $this->method_index = $key + 1;
                unset($this->url[$key]);
                $controller_exists = true;
                break;
            }

            unset($this->url[$key]);
            $this->controller_endpoint .= '/';
        }

        if(!$controller_exists) {
            $this->controller_endpoint = $this->controller;
        }
        require_once CONTROLLERS_PATH . $this->controller_endpoint . '.php';
        $this->controller = new $this->controller;

        // Set Method or use default.
        if(isset($this->url[$this->method_index]))
        {
            if(method_exists($this->controller, $this->url[$this->method_index]))
            {
                $this->method = $this->url[$this->method_index];
                unset($this->url[$this->method_index]);
            }
        }

        // Rebase Parameters or set empty array.
        $this->params = $this->url ? array_values($this->url) : [];

        // Send Parameters to the Method of the Controller.
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    /**
     * @param $string
     * @param bool $capitalizeFirstCharacter
     * @return mixed|string
     *
     * Converts hyphenated-strings to CamelCase
     * If the second parameter is 'true' (default) - "camel-case" returns "CamelCase"
     * If the second parameter is 'false' - "camel-case" returns "camelCase"
     */
    protected function dashesToCamelCase($string, $capitalizeFirstCharacter = true)
    {
        $str = str_replace(' ', '', ucwords(str_replace('-', ' ', $string))); // < php5.5.9
        //$str = str_replace('-', '', ucwords($string, '-')); // > php5.5.9

        if (!$capitalizeFirstCharacter) {
            $str = lcfirst($str);
        }

        return $str;
    }

    /**
     * @param $url
     * @param $route
     * @return mixed
     *
     * Remaps the $url string provided when a RegEx pattern from $routes is matched,
     * otherwise, return $url unchanged.
     */
    protected function remapUrl($url, $route)
    {
        foreach($route as $pattern => $replacement)
        {
            $pattern = str_replace(":any", "(.+)", $pattern);
            $pattern = str_replace(":num", "(\d+)", $pattern);
            $pattern = '/' . str_replace("/", "\/", $pattern) . '/i';
            $replacement = '/'.$replacement.'/';
            $route_url = preg_replace($pattern, $replacement, $url);
            if($route_url !== $url && $route_url !== null) {
                return $route_url;
            }
        }
        return $url;
    }

    /**
     * @param array $config
     * @param $route
     * @return array
     *
     * Returns an array containing all parts of the active URL using the method specified in $config.
     * Also, performs URL remapping as needed.
     */
    protected function parseUrl($config = [], $route)
    {
        if(isset($config['uri_protocol']))
        {
            switch($config['uri_protocol'])
            {
                case 'PATH_INFO':
                    if(isset($_SERVER['PATH_INFO']))
                    {
                        $this->url_string = $this->remapUrl(filter_var(rtrim($_SERVER['PATH_INFO']), '/', FILTER_SANITIZE_URL), $route);
                        return $url = explode('/', $this->url_string);
                    } else {
                        return array();
                    }
                    break;
                case 'QUERY_STRING':
                    if(isset($_SERVER['QUERY_STRING']))
                    {
                        list($name,$value) = explode('=',$_SERVER['QUERY_STRING']);
                        $this->url_string = $this->remapUrl(filter_var(rtrim($value, '/'), FILTER_SANITIZE_URL), $route);
                        return $url = explode('/', $this->url_string);
                    } else {
                        return array();
                    }
                    break;
                case 'REQUEST_URI':
                    if(isset($_SERVER['REQUEST_URI']))
                    {
                        $this->url_string = $this->remapUrl(filter_var(ltrim($_SERVER['REQUEST_URI'], '/'), FILTER_SANITIZE_URL), $route);
                        return $url = explode('/', $this->url_string);
                    } else {
                        return array();
                    }
                    break;
                default:
                    if(isset($_GET['url']))
                    {
                        $this->url_string = $this->remapUrl(filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL), $route);
                        return $url = explode('/', $this->url_string);
                    } else {
                        return array();
                    }
                    break;
            }
        } else {
            die('The URI PROTOCOL configuration is not set.');
        }
    }
}