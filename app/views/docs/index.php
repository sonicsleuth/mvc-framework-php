<!doctype html>
<html>
<head>
    <meta charset="uft-8">
    <meta name="author" content="Richard Soares">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MVC Framework</title>
    <link href="https://fonts.googleapis.com/css?family=Droid+Sans:400,700" rel="stylesheet">
    <?php load_style(['reset','prism','main']) ?>
</head>
<body>
<?php extend_view(['common/header'], $data) ?>

<h3>Preface</h3>
<p>
    A model-view-controller (MVC) framework is a design
    pattern for successfully and efficiently relating the user interface to underlying data models.
</p>
<p>
    The MVC pattern has been heralded by many developers as a useful pattern for the reuse of object code and a pattern
    that allows them to significantly reduce the time it takes to develop applications with user interfaces.
</p>
<p>
    The model-view-controller pattern has three main components or objects to be used in software development:
</p>
<ul>
    <li>A <strong>Model</strong> , which represents the underlying, logical structure of data in a software application and the high-level
        class associated with it. This object model does not contain any information about the user interface.</li>
    <li>A <strong>View</strong> , which is a collection of classes representing the elements in the user interface (all of the things the
        user can see and respond to on the screen, such as buttons, display boxes, and so forth).</li>
    <li>A <strong>Controller</strong> , which represents the classes connecting the model and the view, and is used to communicate 
        between classes in the model and view.</li>
</ul>

<h3>Requirements</h3>
<ul>
    <li>A general understanding of Object-Oriented Programming using PHP.</li>
    <li>Optimize for PHP-7.2, but will run PHP-5.6 with a few adjustments as noted.</li>
    <li>The PHP PDO-Library for database connectivity. <br />
        <em>If using less-than PHP-7.2, uncomment lines 19, 20 of /app/core/Model.php (ie: the USE PDO lines) </em>
    </li>

<h3>Running this MVC Framework on your local computer</h3>
<p>
The root of this installation contains the following files for spinning up a local Virtual Server on your computer. While neither
are a requirement, just a nice convienence, you can load this MVC Framework on any compatible hosting environment.
</p>
<ul>
    <li>A "Dockerfile" for running a Docker Container with Ubuntu/Apache/PHP-7.2
    <li>A "bootstrap.sh" shell script for building a Vagrant Virtual Machine with Ubuntu/Apache/PHP-7.2/MySQL
</ul>

<h3>Features of this MVC Framework</h3>
<ul>
    <li>
        <strong>Easy Configuration</strong> with the use of individual config files, like database, routes, etc.
    </li>
    <li>
        <strong>Routing</strong> which is the method in which you specify the URLs that will load your pages, for example:
        <ul>
            <li>Get a user: http://www.example.com/user/list</li>
            <li>Get a product: http://www.example.com/product/id/123</li>
            <li>Call and API for report data: http://www.example.com/api/v1/sales-report</li>
        </ul>
    </li>
    <li>
        A <strong>Base Model</strong> which serves as an abstract to PDO and can be extended by any custom Model.
    </li>
    <li>
        An <strong>Organized Directory Structure</strong> where public access is separated from the core application.
<pre><code class="language-text">root
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
    </code></pre>
    </li>
</ul>

<a id="routing"></a>
<h3>URL Routing</h3>
<p>
    By default, a URL has a one-to-one relationship to the Controller and Method called which has the following format:

</p>
<pre><code class="language-text">http://example.com/controller/method/param1/param2</code></pre>
<p>
In some instances, however, you may want to remap this relationship so that a different class/method can be
called instead of the one corresponding to the URL. For example, let’s say you want your URLs to have this prototype:
<pre><code class="language-text">example.com/product/1/
example.com/product/2/
example.com/product/3/
example.com/product/4/
</code></pre>
</p>
<p>
Normally the second segment of the URL is reserved for the method name, but in the example above it instead
has a product ID. To overcome routes allow you to remap the URI handler.
</p>
<p>
    <strong>NOTE</strong> it is not a requirement that you pass all parameters in the URL. You can create URL routes
    having only a controller/method/ pattern and provide data via HTTP POST, for example, coming from a form.
</p>
<p>
    You can add your custom routes to the routes configuration file located here: <strong>/app/config/routes.php</strong>
</p>
<p>
<strong>WILDCARDS</strong><br>
A typical wildcard route might look something like this:
<pre><code class="language-text">$route['product/:num'] = 'catalog/product_lookup/$1';</code></pre>
</p>
<p>
In a route, the array key contains the URI to be matched, while the array value contains the destination it should
be re-routed to. In the above example, if the literal word “product” is found in the first segment of the URL, and a
number is found in the second segment, the “catalog” class and the “product_lookup” method are instead used.
</p>
<p>
You can match literal values or you can use two wildcard types:
(:num) will match a segment containing only numbers. (:any) will match a segment containing any character
(except for ‘/’, which is the segment delimiter).
</p>
<p>
<strong>REGULAR EXPRESSIONS</strong><br>
If you prefer you can use regular expressions to define your routing rules. Any valid regular expression is allowed,
as are back-references. If you use back-references you must use the dollar syntax rather than the double backslash syntax.
</p>
<p>
    A typical RegEx route might look something like this:</p>
<pre><code class="language-text">$route['products/([a-z]+)/(\d+)'] = '$1/id_$2';</code></pre>
<p>
    In the above example, a URI similar to products/shirts/123 would instead call the “shirts” controller class
and the “id_123” method.
</p>
<p>
<strong>NOTE:</strong><br>
    <ul>
    <li>
        Routes will run in the order they are defined. Higher routes will always take precedence over lower ones.
    </li>
    <li>
        Route rules are not filters! Setting a rule of e.g. ‘foo/bar/(:num)’ will not prevent controller Foo and method bar
        to be called with a non-numeric value if that is a valid route.
    </li>
</ul>
</p>
<p>
<strong>IMPORTANT!</strong> Do not use leading/trailing slashes.
</p>
<p><strong>EXAMPLES:</strong></p>
<pre><code class="language-text">$route['journals'] = 'blogs';</code></pre>
<p>
A URL containing the word “journals” in the first segment will be remapped to the “blogs” controller class.
</p>
<pre><code class="language-text">$route['product/(:any)'] = 'catalog/product_lookup/$1';</code></pre>
<p>A URL with “product” as the first segment, and anything in the second will be remapped to the “catalog” controller class
and the “product_lookup” method.
</p>
<pre><code class="language-text">$route['product/(:num)'] = 'catalog/product_lookup_by_id/$1';</code></pre>
<p>A URL with “product” as the first segment, and a number in the second will be remapped to the “catalog” controller class and
the “product_lookup_by_id” method passing in the match as a variable to the method.
</p>

<h3>Models</h3>
<p>
    Models that you create must be stored in the <strong>/app/models/</strong> directory and MUST use a <strong>CamelCase.php</strong>
    file naming format. The Class name MUST also be named identically as the file name like so:
</p>
<pre><code class="language-php">class CameCase extends Model
{ .. }</code></pre>
<p>
    The base Model serves as an abstract to PDO and can be extended by any custom Model. The base Model will handle
    all the heavy lifting to create a proper PDO database query and return results, if any.
</p>
<p>
    The Base Model located here <strong>/app/core/Model.php</strong> can be extended by your custom models like so:
</p>
<pre><code class="language-php">class User extends Model
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
</code></pre>
<p>
    The Base Model class is fully commented providing all the standard Create, Read, Update, Delete functionality.
</p>
<p>
    <strong>Important!</strong> The Base Model performs automatic mapping of table columns names to the key-names of
    the data array you pass into it.  Any key-names not matching a table column name will be dropped.
</p>

<p>
    <strong>Important!</strong> A Model that returns records from a database query will always return an array(array) 
    even for a single record, and FALSE if no records are returned.  For example: 
</p>

<pre><code class="language-php">// Return two or more records.
$users = $user->select('users','dept = 12');
$print_r($users);
// OUTPUT:
array(
    [0] => array('name' => 'Bob', 'dept' => '12'),
    [1] => array('name' => 'Mary', 'dept' => '12'),
    [2] => array('name' => 'Sue', 'dept' => '12'),
)

// Return one record.
$user = $user->select('users','id = 100');
$print_r($user);
// OUTPUT:
array(
    [0] => array('name' => 'Bob', 'id' => '12')
)

// Display values from this single record.
// Note we have to access index [0] of the inner array for the value.
echo "Welcome " . $user[0]['name']; 
// OUTPUT
Welcome Bob
</code></pre>


<h3>Views</h3>
<p>
    Views are the presentation part of the MVC pattern and as such they define design/layout of the page.
    A typical View might look like the following:
</p>
<p>
Sometimes you may have reusable parts of your page such as a header and footer. The View Helper loads by default and
    allows you to "extend" your View. In this example, we are adding the common header and footer View fragments by specifying
    their location in the sub-directory called "common" within the Views directory, located here: <strong>/app/views/common/</strong>
</p>

<pre><code class="language-php">extend_view(['common/header'], $data)
... the main body of your html page ...
extend_view(['common/footer'], $data)</code></pre>
<p>
    The second optional parameter <strong>$data</strong> is used to pass an array collection of data to this view fragment.
</p>
<pre><code class="language-php">load_style(['reset','main'])</code></pre>
<p>
    The <strong>load_style()</strong> function will load a list of CSS style files located in your public directory here:
    <strong>/app/public/css/</strong> *You do not need to specify the file extension ".css"
</p>

<pre><code class="language-php">load_script(['main','other']);</code></pre>
<p>
    The <strong>load_script()</strong> function will load a list of Javascript files located in your public directory here:
    <strong>/app/public/js/</strong> *You do not need to specify the file extension ".js"
</p>
<p>
    <strong>NOTE</strong>
    <ul>
    <li>You do not need to specify the file extension of the view but the View MUST be a PHP file.</li>
    <li>You can create any directory organizational structure under the <strong>/app/views/</strong> directory
        so long that you specify the path when loading a View from the Controller or extending it within the View, for example:</li>
</ul>

</p>
<pre><code class="language-php">extend_view(['reports/daily/common/header'], $data)
extend_view(['reports/weekly/common/header'], $data)</code></pre>
<p>

<p>
For a comprehensive example of passing data into a View, see: <a href="/examples/passing_data">Example for passing values into Views</a>.
</p>

<h3>Controllers</h3>
<p>
    To understand how Controllers work we need to back up a little bit and recall how we format a URL. For this example
    lets say we need to query information about a user and display the information on a report.
</p>
<pre><code class="language-text">http://www.acme.com/user/report/123</code></pre>
<p>
    Our URL could have this form, where we are requiring the "User" Controller class and passing into the "report" method the value of "123" (the user id).
</p>
<p>Our Controller might look like the following:</p>
<pre><code class="language-php">
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

        // Get this user
        $bind = [':id' => $user_id];
        $records = $user->select('users','id = :id', $bind);

        // Prepare the values we will pass into the View.
        $data = [
            'users' => $records;
            'is_admin' => 'yes' // some other arbitrary value.
        ];

        // Load the View. 
        // The second atribute $data is optional and only required 
        // when passing data into a View.
        $this->view('reports/user_profile', $data);
    }
}
</code></pre>
<br>
<p>
    Let's assume the we received back the following data when accessing http://my-domain.com/user/report
</p>
<pre><code class="language-text">['name' => 'Bob Smith', 'age' => '24', 'email' => 'bobsmith@example.com']</code></pre>
<br>
<p>
    Within your View file you could access these values in one of two ways:
    <ul>
    <li>As the key-name of the $data array,</li>
    <li>or as a magically-generated PHP variable.</li>
</ul>
</p>
<pre><code class="language-php">echo $data['name'] // outputs "Bob Smith"
echo $name // also outputs "Bob Smith"</code></pre>
<p>
    <strong>Why offer both options?</strong> because developers have preferences on style.
</p>

<p>
For a comprehensive example of passing data into a View, see: <a href="/examples/passing_data">Example for passing values into Views</a>.
</p>

<p>
    <strong>Note:</strong>
    <ul>
    <li>When specifying a Controller class such as "UserHistory" you must hyphenate the class name in the URL like
        so <strong>/user-history/</strong>method/param1/param2</li>
    <li>If your Controller class is located in a sub-directory within the <strong>/apps/controllers/</strong> directory
        you must specify it in the URL like so <strong>/directory/user-history/</strong>method/param1/param2 *</li>
</ul>
* However, you may also use custom routing to hide a sub-directory. See the <a href="#routing">Routing section</a> above.
</p>

<h3>Sessions</h3>
<p>
Session data can be managed using the Session Model within your Controllers making this data persist between browser sessions. 
</p>
<p>
The Session Model will initally check if a Session Cookie exists, and if so, the PHP Session will be loaded with the data stored 
in the database. If no Session Cookie exists, then a new Session database record and cookie will be generated.
</p>
<p>
Run the following example from here: <a href="/docs/session">/docs/session</a>, then reference:
<ul>
<li>/app/controllers/Docs/Docs.php - function session()
<li>/app/models/Sessions.php
</ul>
<br />Note: You must first set up your database, see "setting up your database" below.
</p>
<pre><code class="language-php">
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
</code></pre>
<br />
<p>Loading the url (http://localhost/docs/session) will generate the following output:</p>

<pre><code class="language-text">
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
</code></pre>
<br />
<p>
<strong>Setting up your database:</strong>
<ul>
<li>Apply your database configurations here /app/config/database.php</li>
<li>Create the following MySQL table in your database. </li>
</ul>
</p>

<pre><code class="language-text">
CREATE TABLE sessions ( 
        session_id CHAR(32) NOT NULL, 
        session_data TEXT NOT NULL, 
        session_lastaccesstime TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
        PRIMARY KEY (session_id)
    );
</code></pre>


<?php extend_view(['common/footer'], $data) ?>
<?php load_script(['prism', 'main']); ?>
</body>
</html>