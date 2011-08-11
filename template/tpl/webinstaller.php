<?php
/**
 * Web installer class.
 *
 */
class WebInstaller
{
    public static function checkRequirements()
    {
        $errors = array();

        if ( ! function_exists('curl_init'))
        $errors[] = 'Curl is not available';

        if( ! function_exists('zip_open'))
        $errors[] = 'ZLib is not available';

        return $errors;
    }

    public static function fetchUri($uri, $target = false)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $uri);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 180);

        if($target)
        {
            // Write the response to a file
            $fp = fopen($target, 'w');

            if( ! $fp)
            throw new Exception('Can not open target file at: '.$this->target);

            // Use CURLOPT_FILE to speed things up
            curl_setopt($ch, CURLOPT_FILE, $fp);
        }
        else
        {
            // Return the response
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        }

        $response = curl_exec($ch);

        if (curl_errno($ch))
        throw new Exception('Curl Error: '.curl_error($ch));

        $info = curl_getinfo($ch);

        if(isset($info['http_code'])
        && $info['http_code'] != 200)
        $response = false;

        curl_close($ch);

        return $response;
    }

    public static function unzip($file)
    {
        $zip = zip_open(BASE_DIR.DS.$file);

        if ( ! is_resource($zip))
        throw new Exception('unable to open zip archive');

        $e = '';

        while($zipEntry = zip_read($zip))
        {
            $zdir = dirname(zip_entry_name($zipEntry));
            $zname = zip_entry_name($zipEntry);

            if( ! zip_entry_open($zip,$zipEntry, 'r'))
            {
                $e .= 'Unable to proccess file '.$zname;

                continue;
            }

            if( ! is_dir($zdir))
            self::mkdirr($zdir, 0777);

            $zip_fs = zip_entry_filesize($zipEntry);

            if(empty($zip_fs))
            continue;

            $zz = zip_entry_read($zipEntry, $zip_fs);

            $z = fopen($zname, 'w');

            fwrite($z, $zz);
            fclose($z);
            zip_entry_close($zipEntry);
        }

        zip_close($zip);

        return $e;
    }

    protected static function mkdirr($pn, $mode = null)
    {
        if(is_dir($pn) || empty($pn))
        return true;

        $pn = str_replace(array('/', ''), DS, $pn);

        if(is_file($pn))
        throw new Exception('mkdirr() File exists');

        $next_pathname = substr($pn, 0, strrpos($pn, DS));

        if(self::mkdirr($next_pathname, $mode))
        {
            if( ! file_exists($pn))
            {
                return mkdir($pn, $mode);
            }
        }

        return false;
    }//function

}//class