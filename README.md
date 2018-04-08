# mvc-framework-php

# MVC Framework ###
* An Model-View-Controller (MVC) framework for PHP/MySQL (LAMP Stack)
* Includes Vagrant Bootstrap file for spinning up a LAMP server on Ubuntu with Apache, MySQL, PHP-7.1 with dependencies.

### Easy Setup ###

* Usage Documents:
    * Load the site in your you browser and go to the documentation here: www.example.com/docs
* Dependencies
    * A general understanding of Object-Oriented Programming using PHP.
    * The PHP PDO-Library for database connectivity.
    * PHP-5.6 > (but future enhancements are leaning towards php7)
* Database configuration - see /app/config/database.php
* General Configuration - see /app/config/*

## Documentation

###Introduction
In object-oriented programming development, model-view-controller (MVC) is the name of a methodology or design pattern for successfully and efficiently relating the user interface to underlying data models.

The MVC pattern has been heralded by many developers as a useful pattern for the reuse of object code and a pattern that allows them to significantly reduce the time it takes to develop applications with user interfaces.

The model-view-controller pattern has three main components or objects to be used in software development:

* A Model , which represents the underlying, logical structure of data in a software application and the high-level class associated with it. This object model does not contain any information about the user interface.
* A View , which is a collection of classes representing the elements in the user interface (all of the things the user can see and respond to on the screen, such as buttons, display boxes, and so forth).
* A Controller , which represents the classes connecting the model and the view, and is used to communicate between classes in the model and view.

### Requirements
* A general understanding of Object-Oriented Programming using PHP.
T* he PHP PDO-Library for database connectivity.
* PHP-5.6 > (but future enhancements are leaning towards php7)

### Features of this MVC Framework
* Easy Configuration with the use of individual config files, like database, routes, etc.
* Routing which is the method in which you specify the URLs that will load your pages, for example:
    * Get a user: http://www.example.com/user/list
    * Get a product: http://www.example.com/product/id/123
    * Call and API for report data: http://www.example.com/api/v1/sales-report
* A Base Model which serves as an abstract to PDO and can be extended by any custom Model.
* An Organized Directory Structure where public access is separated from the core application.

```
root
    /app
        /config
            /config.php - app configuration
            /database.php - settings to connect to your database
            /routes.php - custom URL routing patterns
        /controllers
            Docs.php - the controller serving up this documentation
        /core
            App.php - all the magic
            Controller.php - Base controller
            Model.php - Base model
        /helpers
            view.php
        /models
            Users.php - a sample data model
        /views
            /common
                footer.php - footer of this page
                header.php - header of this page
            /docs
                index.php - this page
        init.php
    /public
        /css - your styles
        /js - your scripts
        index.php - the front-loader and environment configurations
        .htaccess - URL routing to the front-loader
```

##URL Routing
By default, a URL has a one-to-one relationship to the Controller and Method called which has the following format:
```
http://example.com/controller/method/param1/param2
```
In some instances, however, you may want to remap this relationship so that a different class/method can be called instead of the one corresponding to the URL. For example, let’s say you want your URLs to have this prototype:
```
example.com/product/1/
example.com/product/2/
example.com/product/3/
example.com/product/4/
```
Normally the second segment of the URL is reserved for the method name, but in the example above it instead has a product ID. To overcome routes allow you to remap the URI handler.

**NOTE** it is not a requirement that you pass all parameters in the URL. You can create URL routes having only a controller/method/ pattern and provide data via HTTP POST, for example, coming from a form.

You can add your custom routes to the routes configuration file located here: /app/config/routes.php

**Wildcards**

A typical wildcard route might look something like this:
```
$route['product/:num'] = 'catalog/product_lookup/$1';
```
In a route, the array key contains the URI to be matched, while the array value contains the destination it should be re-routed to. In the above example, if the literal word “product” is found in the first segment of the URL, and a number is found in the second segment, the “catalog” class and the “product_lookup” method are instead used.

You can match literal values or you can use two wildcard types: (:num) will match a segment containing only numbers. (:any) will match a segment containing any character (except for ‘/’, which is the segment delimiter).

**Regular Expressions**

If you prefer you can use regular expressions to define your routing rules. Any valid regular expression is allowed, as are back-references. If you use back-references you must use the dollar syntax rather than the double backslash syntax.

A typical RegEx route might look something like this:
```
$route['products/([a-z]+)/(\d+)'] = '$1/id_$2';
```
In the above example, a URI similar to products/shirts/123 would instead call the “shirts” controller class and the “id_123” method.

**NOTE:** Routes will run in the order they are defined. Higher routes will always take precedence over lower ones.
Route rules are not filters! Setting a rule of e.g. ‘foo/bar/(:num)’ will not prevent controller Foo and method bar to be called with a non-numeric value if that is a valid route.

**IMPORTANT! Do not use leading/trailing slashes.**

**EXAMPLES:**
```
$route['journals'] = 'blogs';
```
A URL containing the word “journals” in the first segment will be remapped to the “blogs” controller class.
```
$route['product/(:any)'] = 'catalog/product_lookup/$1';
```
A URL with “product” as the first segment, and anything in the second will be remapped to the “catalog” controller class and the “product_lookup” method.
```
$route['product/(:num)'] = 'catalog/product_lookup_by_id/$1';
```
A URL with “product” as the first segment, and a number in the second will be remapped to the “catalog” controller class and the “product_lookup_by_id” method passing in the match as a variable to the method.

##Models
Models that you create must be stored in the /app/models/ directory and MUST use a CamelCase.php file naming format. The Class name MUST also be named identically as the file name like so:
```
class CameCase extends Model
{ .. }
```
The base Model serves as an abstract to PDO and can be extended by any custom Model. The base Model will handle all the heavy lifting to create a proper PDO database query and return results, if any.

The Base Model located here **/app/core/Model.php** can be extended by your custom models like so:
Models

```
class User extends Model
{
    private $db;

    public function __construct()
    {
       $this->db = new Model();
    }

    public function getUsers()
    {
```
##Views
Views are the presentation part of the MVC pattern and as such they define design/layout of the page. A typical View might look like the following:

Sometimes you may have reusable parts of your page such as a header and footer. The View Helper loads by default and allows you to "extend" your View. In this example, we are adding the common header and footer View fragments by specifying their location in the sub-directory called "common" within the Views directory, located here: /app/views/common/
```
extend_view(['common/header'], $data)
... the main body of your html page ...
extend_view(['common/footer'], $data)
```
The second optional parameter $data is used to pass an array collection of data to this view fragment.
```
load_style(['reset','main'])
```
The load_style() function will load a list of CSS style files located in your public directory here: /app/public/css/ *You do not need to specify the file extension ".css"
```
load_script(['main','other']);
```
The load_script() function will load a list of Javascript files located in your public directory here: /app/public/js/ *You do not need to specify the file extension ".js"

**NOTE**

*You do not need to specify the file extension of the view but the View MUST be a PHP file.
*You can create any directory organizational structure under the /app/views/ directory so long that you specify the path when loading a View from the Controller or extending it within the View, for example:
```
extend_view(['reports/daily/common/header'], $data)
extend_view(['reports/weekly/common/header'], $data)
```

##Controllers
To understand how Controllers work we need to back up a little bit and recall how we format a URL. For this example lets say we need to query information about a user and display the information on a report.
```
http://www.acme.com/user/report/123
```

Our URL could have this form, where we are requiring the "User" Controller class and passing into the "report" method the value of "123" (the user id).

Our Controller might look like the following:

```
class User extends Controller {

    // Load the Home Page if the URL does not specify a method
    public function index()
    {
        $this->view('home/index');
    }

    // Show our User Profile report
    public function report($user_id)
    {
        // Load the User Model so that we can query the database
        $user = $this->model('User');
        // Get this user
        $bind = [':id' => $user_id];
        $data = $user->select('users','id = :id', $bind);
        // Load the View passing to it the Users information as $data.
        $this->view('reports/user_profile', $data);
    }
}
```

Let's assume the record returned from the above query for the user had the following data:
```
['name' => 'Bob Smith', 'age' => '24', 'email' => 'bobsmith@example.com']
```

Within your View file you could access these values in one of two ways:

As the key-name of the $data array, or
as a variable name directly.

```
echo $data['name'] // outputs "Bob Smith"
echo $name // also outputs "Bob Smith"
```

Why offer both options? because developers have preferences on style.

**Note:**

* When specifying a Controller class such as "UserHistory" you must hyphenate the class name in the URL like so **/user-history/method/param1/param2**
* If your Controller class is located in a sub-directory within the **/apps/controllers/** directory you must specify it in the URL like so **/directory/user-history/method/param1/param2**

However, you may also use custom routing to hide a sub-directory. See the **Routing** section above.

(end of documentation)



