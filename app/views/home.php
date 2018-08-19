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

<h3>Welcome</h3>

<p>This MVC Framework is working if you are seeing this page.</p>

<p>Begin learning how to implement this MVC Framework by reading the <a href="/docs">documentation</a>.</p>

<?php extend_view(['common/footer'], $data) ?>
<?php load_script(['prism', 'main']); ?>
</body>
</html>