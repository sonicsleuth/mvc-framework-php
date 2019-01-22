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

<h3>Language Dictionaries</h3>

<p>The following output shows the current language dictionary being used for this URL.</p>
<pre><code class="language-php"><?php print_r(LANG); ?></code></pre>
<br>
<p>Try these links to load another language dictionary.</p>

<ul></ul>
    <li><a href="/docs/language">Default - http://localhost/docs/language</a><br><br></li>
    <li><a href="/en/docs/language">English - http://localhost/en/docs/language</a><br><br></li>
    <li><a href="/fr/docs/language">French - http://localhost/fr/docs/language</a><br><br></li>
    <li><a href="/sp/docs/language">Spanish - http://localhost/sp/docs/language</a><br><br></li>
</ul>

<p>Notice how each URL after http://localhost begins with the language specifier /en/ /fr/ /sp/</p>

<p style="color:red;">NOTE: If a language URL segment <i>does not match</i> a language dictionary file within the 
<strong>/app/languages/</strong> directory, then the default Home page will load. Example: http://domain.com/<strong>xx</strong>/user/1</p>

<p>You can specify the default Language and Available Languages in the <strong>/app/config/config.php</strong> file:</p>

<pre><code class="language-php">$config['default_language'] = 'en';
$config['available_languages'] = ['en', 'fr', 'sp'];</code></pre>


<h3>Setting up Language Dictionaries</h3>

<p>For each language setting in <strong>$config['available_languages']</strong>, you must create a language specific file with a name format like <strong>en_lang.php</strong> 
and place it in the <strong>/app/language/</strong> directory. When you installed this framework three
language dictionary files already exist to get you started - English, French, Spanish.</p>

<pre><code class="language-text">/app/languages/en_lang.php
/app/languages/fr_lang.php
/app/languages/sp_lang.php</code></pre>

<br>
<p>You can define an unlimited number of Native-Language to Foreign-Language definitions within 
each language dictionary file as shown below. Create new language files as needed. Ensure each language 
file contains an identical list of Native-Language array keys for each Foreign-Language value as shown in the examples below.</p>

<strong>/app/languages/en_lang.php</strong>
<pre><code class="language-php">define('LANG', [
    'Welcome' => 'Welcome',
    'Hello' => 'Hello',
    'Subscribe' => 'Subscribe',
]);</code></pre>

<strong>/app/languages/fr_lang.php</strong>
<pre><code class="language-php">define('LANG', [
    'Welcome' => 'Bienvenue',
    'Hello' => 'Bonjour',
    'Subscribe' => 'Souscrire',
]);</code></pre>

<strong>/app/languages/en_lang.php</strong>
<pre><code class="language-php">define('LANG', [
    'Welcome' => 'Bienvenido',
    'Hello' => 'Hola',
    'Subscribe' => 'Suscribir',
]);</code></pre>


<h3>Settings Links with the Current Language</h3>

<p>Within your Controller or View file include the Language Helper file.
Then pass the URL into the <strong>language_url()</strong> function as shown below.</p>

<pre><code class="language-php">require_once('../app/helpers/language.php');
language_url('/doc/language'); // Output: http://localhost/en/doc/language</code></pre>

<?php extend_view(['common/footer'], $data) ?>
<?php load_script(['prism', 'main']); ?>
</body>
</html>