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

<a href="/docs">Return to Documenation</a>

<h3>Example - Passing values from a Controller into a View</h3>

<p>Examine the following files for details:</p>
<ul>
    <li> /app/models/User.php -> method: getUsers() 
    <li> /app/controllers/Examples.php
    <li> /app/views/docs/passing_data.php (this file) 
</ul>


<pre><code class="language-php">PHP CODE:
// Dump the whole $data parameter passed into this View.
print_r($data);
// Access the values like so: $data['users'], $data['is_admin']
// However, see below for a more efficient way to get these values.
</code></pre>

<pre><code class="language-text">OUTPUT:
<?php
print_r($data);
?>
</code></pre>





<h3>Accessing $data values as PHP $variables:</h3>

<p>Passing in the associative array $data to a View results in the 
auto-magically created PHP variables for each Key in the $data array.
 This allows you to refer to the keys more easilly as shown below where
 $users is much cleaner than $data['users'].</p>




<pre><code class="language-php">PHP CODE:
// Looping over the list of $users passed into this View, if they exist.
if($users != '') {
    foreach($users as $user) {
        echo "Name: " . $user['name'] . "\r\n";
        print_r($user);
    }
}
</code></pre>

<pre><code class="language-text">OUTPUT:
<?php
if($users != '') {
    foreach($users as $user) {
        echo "Name: " . $user['name'] . "\r\n";
        print_r($user);
    }
}
?>
</code></pre>



<pre><code class="language-php">PHP CODE:
// Another example, where we are accessing a the string $is_admin below.
echo "Are you an Admin? " . $is_admin;
</code></pre>

<pre><code class="language-text">OUTPUT:
<?php
echo "Are you an Admin? " . $is_admin;
?>
</code></pre>




<?php extend_view(['common/footer'], $data) ?>
<?php load_script(['prism', 'main']); ?>
</body>
</html>