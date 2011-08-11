<?php
defined('DS') || define('DS', DIRECTORY_SEPARATOR);

define('BASE_DIR', dirname(__FILE__));

ini_set('implicit_flush', 1);

$fileName = 'Joomla_1.7.0-Stable-Full_Package.zip';
//$fileName = 'blabla.zip';

$target = BASE_DIR.DS.$fileName;

$package = '15278';
$id = '66555';

$uri = 'http://joomlacode.org/gf/download/frsrelease/'.$package.'/'.$id.'/'.$fileName;

//$uri = 'http://indigogit2.kuku/testinstaller/'.$fileName;


try
{
    /*
    $uri = 'http://update.joomla.org/core/extension.xml';
    $updates = WebInstaller::fetchUri($uri);

    if( ! $updates)
    throw new Exception('No updates found');

    $xml = simplexml_load_string($updates);

    //     var_dump($xml);

    if( ! isset($xml->update))
    throw new Exception('Invalid update file');

    foreach ($xml->update as $update)
    {
    echo $update->description;
    echo $update->version;
    echo '<br />';
    var_dump($update);
    }

    return;
    */

    if( ! file_exists($target))
    {
        echo 'Downloading '.$fileName.' ...';

        if( ! WebInstaller::fetchUri($uri, $target))
        throw new Exception('Unable to download file');
    }

    echo 'Unzipping...';

    WebInstaller::unzip($fileName);

    echo 'alles schön :)';

    echo "\n";

}
catch (Exception $e)
{
    echo $e->getMessage();
}

/**@@WEBINSTALLER_CLASS@@**/

