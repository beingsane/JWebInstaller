<?php
/**
 * @version $Id: builder.php 481 2011-08-04 17:36:33Z elkuku $
 * @package    SingleFileBuilder
 * @subpackage Stand alone - Builder
 * @author     Nikolai Plath (elkuku) {@link http://www.nik-it.de NiK-IT.de}
 * @author     Created on 10-Sep-2010
 */

error_reporting(-1);

define('DS', DIRECTORY_SEPARATOR);
define('NL', "\n");
define('BR', '<br />');

try
{
    $options = parse_ini_file('builder.ini', true);

    if( ! $options)
    throw new Exception('Invalid builder.ini');

    var_dump($options);

    $templateContents = file_get_contents('template/template.php');

    foreach ($options['replacements'] as $replacement => $commandString)
    {
        $command = substr($commandString, 0, strpos($commandString, ':'));

        $cOptions = substr($commandString, strpos($commandString, ':') + 1);

        echo $command.BR;
        echo $cOptions.BR;

        switch ($command)
        {
            case 'file' :
                $contents = file_get_contents('template/tpl/'.$cOptions);

                if(0 === strpos($contents, '<?php'))
                $contents = substr($contents, 6);
                break;

            default:
                $contents = '';
                break;
        }//switch

        if($contents)
        $templateContents = str_replace('/**@@'.$replacement.'@@**/', $contents, $templateContents);
    }//foreach

    $result = $templateContents;

    //echo '<pre>'.htmlentities($result).'</pre>';

    $fileName = 'build/'.$options['common']['result_file_name'];

    file_put_contents($fileName, $result);

    echo '<h2>Finished: '.$fileName.'</h2>';
}
catch (Exception $e)
{
    echo $e->getMessage();

    die();
}//try
