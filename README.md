# MVC Framework

- An Model-View-Controller (MVC) framework for PHP/MySQL (LAMP Stack)
- Detailed documentation installed with the framework beyond the summary below.

### Easy Setup

- Usage Documents:
  - Load the site in your you browser and go to the documentation here: www.example.com/docs
- Dependencies
  - A general understanding of Object-Oriented Programming using PHP.
  - The PHP PDO-Library for database connectivity.
  - PHP 7.2, but should work with minimal change for PHP 5.6.
- Database configuration - see /app/config/database.php
- General Configuration - see /app/config/\*

## Documentation

## Preface

A model-view-controller (MVC) framework is a design pattern for successfully and efficiently relating the user interface to underlying data models.

The MVC pattern has been heralded by many developers as a useful pattern for the reuse of object code and a pattern that allows them to significantly reduce the time it takes to develop applications with user interfaces.

The model-view-controller pattern has three main components or objects to be used in software development:

- A Model , which represents the underlying, logical structure of data in a software application and the high-level class associated with it. This object model does not contain any information about the user interface.
- A View , which is a collection of classes representing the elements in the user interface (all of the things the user can see and respond to on the screen, such as buttons, display boxes, and so forth).
- A Controller , which represents the classes connecting the model and the view, and is used to communicate between classes in the model and view.

## Requirements

- A general understanding of Object-Oriented Programming using PHP.
  The PHP PDO-Library for database connectivity.
- PHP-5.6 > (but future enhancements are leaning towards php7)

## Running this MVC Framework on your local computer

The root of this installation contains the following files for spinning up a local Virtual Server on your computer. While neither are a requirement, just a nice convienence, you can load this MVC Framework on any compatible hosting environment.

- A "Dockerfile" for running a Docker Container with Ubuntu/Apache/PHP-7.1.8
  - Run "docker-compose up" from the directory containing root/docker-compose.yml file.
  - Open a browser and go to: http://localhost
  - You do not have to stop/start the Docker container while editing code. Updates are relected in realtime.
- A "bootstrap.sh" shell script for building a Vagrant Virtual Machine with Ubuntu/Apache/PHP-7.2/MySQL

## Features of this MVC Framework

- Easy Configuration with the use of individual config files, like database, routes, etc.
- Routing which is the method in which you specify the URLs that will load your pages, for example:
  - Get a user: http://www.example.com/user/list
  - Get a product: http://www.example.com/product/id/123
  - Call and API for report data: http://www.example.com/api/v1/sales-report
- A Base Model which serves as an abstract to PDO and can be extended by any custom Model.
- An Organized Directory Structure where public access is separated from the core application.
- Support for Multiple Languagesspecified by URL's, like: www.domain.com/en/user/123

```
root/
    app/
        config/
            config.php - app configuration
            database.php - settings to connect to your database
            routes.php - custom URL routing patterns
        controllers/
            Docs.php - the controller serving up this documentation
        core/
            App.php - all the magic
            Controller.php - Base controller
            Model.php - Base model
        helpers/
            view.php
        /languages
            en_lang.php
            fr_lang.php
            sp_lang.php
        models/
            Users.php - a sample data model
            Sessions.php - database managed PHP Sessions
        views/
            common/
                footer.php - footer of this page
                header.php - header of this page
            docs/
                index.php - this page
        init.php
    public/
        css/ - your styles
        js/ - your scripts
        index.php - the front-loader and environment configurations
        .htaccess - URL routing to the front-loader
```

## URL Routing

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

## Models

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
        /* get all users */
        $results = $this->db->select("users");
    }

    public function getMaleUsers()
    {
        /* get a specific user */
        $results = $this->db->select("users", "Gender = 'male'");
    }

    public function addUser($fname, $lname, $age, $gender)
    {
        /* add a new user */
        $data = [
                "fname"  => $fname,
                "lname"  => $lname,
                "age"    => $age,
                "gender" => $gender
                ];
        $this->db->insert("users", $data);
    }
}
```

The Base Model class is fully commented providing all the standard Create, Read, Update, Delete functionality.

Important! The Base Model performs automatic mapping of table columns names to the key-names of the data array you pass into it. Any key-names not matching a table column name will be dropped.

Selecting Records: There are two methods available for selecting records.

select() - Use this method to return multiple records. For example:

```
// Use select() to return multiple records.
$users = $user->select('users','dept = 12');
$print_r($users);
// OUTPUT:
array(
    [0] => array('name' => 'Bob', 'dept' => '12'),
    [1] => array('name' => 'Mary', 'dept' => '12'),
    [2] => array('name' => 'Sue', 'dept' => '12'),
)

// Loop thru a set of records.
foreach($users as $user) {
    echo "Employee: " . $user['name'] . "\r\n";
}
// OUTPUT
Employee: Bob
Employee: Mary
Employee: Sue
```

selectOne() - Use this method to return a single record. For example:

```
// Use selectOne() to return a single record.
$user = $user->selectOne('users','id = 100');
$print_r($user);
// OUTPUT:
array('name' => 'Bob', 'id' => '12')

// Display values from this single record.
echo "Welcome " . $user['name'];
// OUTPUT
Welcome Bob
```

## Views

Views are the presentation part of the MVC pattern and as such they define design/layout of the page. A typical View might look like the following:

Sometimes you may have reusable parts of your page such as a header and footer. The View Helper loads by default and allows you to "extend" your View. In this example, we are adding the common header and footer View fragments by specifying their location in the sub-directory called "common" within the Views directory, located here: /app/views/common/

```
extend_view(['common/header'], $data)
... the main body of your html page ...
extend_view(['common/footer'], $data)
```

The second optional parameter \$data is used to pass an array collection of data to this view fragment.

```
load_style(['reset','main'])
```

The load_style() function will load a list of CSS style files located in your public directory here: /app/public/css/ \*You do not need to specify the file extension ".css"

```
load_script(['main','other']);
```

The load_script() function will load a list of Javascript files located in your public directory here: /app/public/js/ \*You do not need to specify the file extension ".js"

**NOTE**

*You do not need to specify the file extension of the view but the View MUST be a PHP file.
*You can create any directory organizational structure under the /app/views/ directory so long that you specify the path when loading a View from the Controller or extending it within the View, for example:

```
extend_view(['reports/daily/common/header'], $data)
extend_view(['reports/weekly/common/header'], $data)
```

## Controllers

To understand how Controllers work we need to back up a little bit and recall how we format a URL. For this example lets say we need to query information about a user and display the information on a report.

```
http://www.acme.com/user/report/123
```

Our URL could have this form, where we are requiring the "User" Controller class and passing into the "report" method the value of "123" (the user id).

Our Controller might look like the following:

```
class User extends Controller {

    public function __construct()
    {
        // Load the View Helper other methods in the Class will need.
        $this->load_helper(['view']);
        // You can also preload Models as properties of a Controller Class
        // if they are frequently used by Methods, however, it's more efficent
        // to instanciate a Model from within the method, as shown below.
    }

    // Load the Home Page if the URL does not specify a method
    public function index()
    {
        $this->view('home/index');
    }

    // Get some User data and pass it into the View "user_profile".
    public function report($user_id)
    {
        // Load the User Model so that we can query the database
        $user = $this->model('User');

        // Get this user.
        // Use the selectOne() method for single record returns.
        $bind = [':id' => $user_id];
        $record = $user->select('users','id = :id', $bind);

        // Prepare the values we will pass into the View.
        $data = [
            'users' => $record;
            'is_admin' => 'yes' // some other arbitrary value.
        ];

        // Load the View.
        // The second atribute $data is optional and only required
        // when passing data into a View.
        $this->view('reports/user_profile', $data);
    }
}
```

Let's assume the we received back the following data when accessing http://my-domain.com/user/report

```
['name' => 'Bob Smith', 'age' => '24', 'email' => 'bobsmith@example.com']
```

Within your View file you could access these values in one of two ways:

As the key-name of the \$data array,
or as a magically-generated PHP variable.

```
echo $data['name'] // outputs "Bob Smith"
echo $name // also outputs "Bob Smith"
```

Why offer both options? because developers have preferences on style.

**Note:**

- When specifying a Controller class such as "UserHistory" you must hyphenate the class name in the URL like so **/user-history/method/param1/param2**
- If your Controller class is located in a sub-directory within the **/apps/controllers/** directory you must specify it in the URL like so **/directory/user-history/method/param1/param2**

However, you may also use custom routing to hide a sub-directory. See the **Routing** section above.

## Global Variable and Constants

Using global variables and constants within your application is done by requiring the **/helper/global.php** file within your Controller, as shown below, then addin your Constants and Variables-by-referenced functions. See the contents of **/helper/global.php** for examples of use.

```
class MyController extends Controller
{
    public function __construct()
    {
        $this->load_helper(['global_constants']);
    }
}
```

## Sessions

Session data can be managed using the Session Model within your Controllers making this data persist between browser sessions.

The Session Model will initally check if a Session Cookie exists, and if so, the PHP Session will be loaded with the data stored in the database. If no Session Cookie exists, then a new Session database record and cookie will be generated.

The following example is available by visiting here: /docs/session
Note: You must first set up your database, see below.

```
class Docs extends Controller()
{
    public function session()
    {
        // Add the following line to enable database sessions.
        // You do NOT need to call session_start() before using PHP sessions.
        $session = $this->model('Session');

        // Use PHP Sessions like normal.
        $_SESSION['fname'] = 'Walter';
        $_SESSION['lname'] = 'Smith';
        $_SESSION['title'] = 'Sales Manager';

        // For debugging needs, use the getSessionData() function.
        echo "GET ALL SESSION DATA:";
        $data = $session->getSessionData();
        print_r($data);

    }
}
```

Loading the url (http://localhost/docs/session) will generate the following output:

```
GET ALL SESSION DATA:

Array
(
    [0] => Array
        (
            [session_id] => 262d5637c3e56a577a6ca3aab4df5466
            [session_data] => fname|s:6:"Walter";lname|s:5:"Smith";title|s:13:"Sales Manager";
            [session_lastaccesstime] => 2018-08-13 20:47:23
        )

)
```

Setting up your database:

- Apply your database configurations here /app/config/database.php
- Create the following MySQL table in your database.

```
CREATE TABLE sessions (
        session_id CHAR(32) NOT NULL,
        session_data TEXT NOT NULL,
        session_lastaccesstime TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (session_id)
    );
```

## Language Dictionaries

You can specify the default Language and Available Languages in the **/app/config/config.php** file:

```
$config['default_language'] = 'en';
$config['available_languages'] = ['en', 'fr', 'sp'];
```

**Setting up Language Dictionaries**

For each language setting in **\$config['available_languages']**, you must create a language specific file with a name format like **en_lang.php**
and place it in the **/app/language/** directory. When you installed this framework three
language dictionary files already exist to get you started - English, French, Spanish.

```
/app/languages/en_lang.php
/app/languages/fr_lang.php
/app/languages/sp_lang.php
```

You can define an unlimited number of Native-Language to Foreign-Language definitions within
each language dictionary file as shown below. Create new language files as needed. Ensure each language
file contains an identical list of Native-Language array keys for each Foreign-Language value as shown in the examples below.

**/app/languages/en_lang.php**

```
define('LANG', [
    'Welcome' => 'Welcome',
    'Hello' => 'Hello',
    'Subscribe' => 'Subscribe',
]);
```

**Settings Links with the Current Language**

Within your Controller or View file include the Language Helper file.
Then pass the URL into the **language_url()** function as shown below.

```
require_once('../app/helpers/language.php');
language_url('/doc/language'); // Output: http://localhost/en/doc/language
```

(end of documentation)
