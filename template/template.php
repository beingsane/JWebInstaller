<?php
define('DS', DIRECTORY_SEPARATOR);
// define('NL', "\n");
define('NL', '<br />');
define('BASE_DIR', dirname(__FILE__));

ini_set('implicit_flush', 1);

$fileName = 'Joomla_1.7.0-Stable-Full_Package.zip';

$target = BASE_DIR.DS.$fileName;

$package = '15278';
$id = '66555';

$uri = 'http://joomlacode.org/gf/download/frsrelease/'.$package.'/'.$id.'/'.$fileName;

//$uri = 'http://indigogit2.kuku/testinstaller/'.$fileName;

$action = @$_GET['action'];
?>
<!DOCTYPE html>
<html>
<head>
<title>JWebInstaller</title>
</head>
<body>
<h1>JWebInstaller</h1>
<?php
try
{
    $errors = WebInstaller::checkRequirements();

    if($errors)
    throw new Exception(implode(NL, $errors));

    if( ! $action)
    {
    ?>
        <p><?php echo $fileName; ?></p>
        <p>This will Download Joomla! and unzip it to the root of your web space.</p>
        <a href="<?php echo $_SERVER['PHP_SELF']; ?>?action=go">Start</a>
    <?php
    }
    else
    {
        if( ! file_exists($target))
        {
            echo 'Downloading '.$fileName.' ...';

            if( ! WebInstaller::fetchUri($uri, $target))
            throw new Exception('Unable to download file');
        }

        echo 'Unzipping...';

        WebInstaller::unzip($fileName);

        echo 'Finished =;)'.NL;

        echo '<h3><a href="index.php">Proceed with the Joomla! installation.</a></h3>';

        echo '<h2 style="color: red;">Do not forget to remove this script !</h2>';
    }
}
catch(Exception $e)
{
    echo $e->getMessage();
}

?>
</body>
</html>
<?php

/**@@WEBINSTALLER_CLASS@@**/

