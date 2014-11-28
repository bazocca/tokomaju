<?php
/**
 * This file is loaded automatically by the app/webroot/index.php file after core.php
 *
 * This file should load/create any application wide configuration settings, such as
 * Caching, Logging, loading additional configuration files.
 *
 * You should also use this file to include any files that provide global functions/constants
 * that your application uses.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.10.8.2117
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

// Setup a 'default' cache configuration for use in the application.
Cache::config('default', array('engine' => 'File'));

/**
 * The settings below can be used to set additional paths to models, views and controllers.
 *
 * App::build(array(
 *     'Model'                     => array('/path/to/models/', '/next/path/to/models/'),
 *     'Model/Behavior'            => array('/path/to/behaviors/', '/next/path/to/behaviors/'),
 *     'Model/Datasource'          => array('/path/to/datasources/', '/next/path/to/datasources/'),
 *     'Model/Datasource/Database' => array('/path/to/databases/', '/next/path/to/database/'),
 *     'Model/Datasource/Session'  => array('/path/to/sessions/', '/next/path/to/sessions/'),
 *     'Controller'                => array('/path/to/controllers/', '/next/path/to/controllers/'),
 *     'Controller/Component'      => array('/path/to/components/', '/next/path/to/components/'),
 *     'Controller/Component/Auth' => array('/path/to/auths/', '/next/path/to/auths/'),
 *     'Controller/Component/Acl'  => array('/path/to/acls/', '/next/path/to/acls/'),
 *     'View'                      => array('/path/to/views/', '/next/path/to/views/'),
 *     'View/Helper'               => array('/path/to/helpers/', '/next/path/to/helpers/'),
 *     'Console'                   => array('/path/to/consoles/', '/next/path/to/consoles/'),
 *     'Console/Command'           => array('/path/to/commands/', '/next/path/to/commands/'),
 *     'Console/Command/Task'      => array('/path/to/tasks/', '/next/path/to/tasks/'),
 *     'Lib'                       => array('/path/to/libs/', '/next/path/to/libs/'),
 *     'Locale'                    => array('/path/to/locales/', '/next/path/to/locales/'),
 *     'Vendor'                    => array('/path/to/vendors/', '/next/path/to/vendors/'),
 *     'Plugin'                    => array('/path/to/plugins/', '/next/path/to/plugins/'),
 * ));
 *
 */

/**
 * Custom Inflector rules can be set to correctly pluralize or singularize table, model, controller names or whatever other
 * string is passed to the inflection functions
 *
 * Inflector::rules('singular', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 * Inflector::rules('plural', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 *
 */

/**
 * Plugins need to be loaded manually, you can either load them one by one or all of them in a single call
 * Uncomment one of the lines below, as you need. Make sure you read the documentation on CakePlugin to use more
 * advanced ways of loading plugins
 *
 * CakePlugin::loadAll(); // Loads all plugins at once
 * CakePlugin::load('DebugKit'); //Loads a single plugin named DebugKit
 *
 */

/**
 * You can attach event listeners to the request lifecycle as Dispatcher Filter. By default CakePHP bundles two filters:
 *
 * - AssetDispatcher filter will serve your asset files (css, images, js, etc) from your themes and plugins
 * - CacheDispatcher filter will read the Cache.check configure variable and try to serve cached content generated from controllers
 *
 * Feel free to remove or add filters as you see fit for your application. A few examples:
 *
 * Configure::write('Dispatcher.filters', array(
 *		'MyCacheFilter', //  will use MyCacheFilter class from the Routing/Filter package in your app.
 *		'MyPlugin.MyFilter', // will use MyFilter class from the Routing/Filter package in MyPlugin plugin.
 * 		array('callable' => $aFunction, 'on' => 'before', 'priority' => 9), // A valid PHP callback type to be called on beforeDispatch
 *		array('callable' => $anotherMethod, 'on' => 'after'), // A valid PHP callback type to be called on afterDispatch
 *
 * ));
 */
Configure::write('Dispatcher.filters', array(
	'AssetDispatcher',
	'CacheDispatcher'
));

/**
 * Configures default file logging options
 */
App::uses('CakeLog', 'Log');
CakeLog::config('debug', array(
	'engine' => 'File',
	'types' => array('notice', 'info', 'debug'),
	'file' => 'debug',
));
CakeLog::config('error', array(
	'engine' => 'File',
	'types' => array('warning', 'error', 'critical', 'alert', 'emergency'),
	'file' => 'error',
));

date_default_timezone_set("Asia/Jakarta"); 

/**
	 * print_r text with pre html tag
	 * @param mixed $text all kind of text want to be printed
	 * @return true
	 * @public
	 **/ 
function dpr($text)
{	
	echo '<pre>';
	print_r($text);
	echo '</pre>';
}

/**
	* convert formated string to display string
	* @param string $str contains string want to be converted
	* @return string $result contains string that can be published
	* @public
	**/
function string_unslug($str)
{
	return Inflector::humanize(  str_replace('-','_', $str )  );
}

function get_slug($value)
{
    return Inflector::slug( strtolower($value) , '-');
}

/**
	* convert date text to selected date format from template settings
	* @param date $value contains source date
	* @param string $date contains date format selected
 	* @param string $time contains time format selected
	* @return string new date format
	* @public
	**/
function date_converter($value , $date , $time=NULL)
{	
	$value = strtotime($value);
	$newDate = date($date , $value);
	$newTime = date($time , $value);
	return $newDate.(empty($time)?'':' @ '.$newTime);
}
/**
	* retrieve certain value attribute of selected input validation
	* @param string $str contains source of validation value, separated by "|"
	* @param string $value contains selected validation
	* @return string $result contain attribute of selected validation
	* @public
	**/
function get_input_attrib($src , $value)
{
	$temp = stripos($src, $value);
	if($temp === FALSE)
	{
		$result = "";
	}
	else 
	{
		$src = substr($src, $temp); //  ???|??|MIN_LENGTH_5|???|??  => MIN_LENGTH_5|???|???
		$pos = strpos($src, '|');
		$src = ($pos === FALSE?$src:substr($src, 0 , $pos));
		$result = substr($src, strrpos($src, '_')+1);
	}
	return $result;
}
/**
	* convert formated language to display language
	* @param string $str contains source language want to be converted
	* @return string $result contains language that can be published
	* @public
	**/
function lang_unslug($str)
{
	$temp = explode('_', $str);
	$result = strtoupper($temp[0]).' - '. strtoupper(substr($temp[1], 0 , 1)).substr($temp[1], 1);
	return $result;
}
/**
	* retrieve list of language option used in settings form
	* @param array $src contains group of used language in settings
	* @return array $langlist contains array of language that will be displayed as language option
	* @public
	**/
function get_list_lang($src = array())
{
	$langlist[] = 'en_english';
	$langlist[] = 'id_indonesia';
	$langlist[] = 'zh_chinese';
	
	$existlang = explode(chr(13).chr(10), $src);
	foreach ($existlang as $key => $value) 
	{
		$state = 0;
		foreach ($langlist as $key10 => $value10) 
		{
			if($value == $value10)
			{
				$state = 1;
				break;
			}
		}
		if($state == 0)
		{
			$langlist[] = $value;
		}
	}
	return $langlist;
}
function parse_lang($src = array())
{
	$temp = explode(chr(13).chr(10), $src);
	foreach ($temp as $key => $value) 
	{
		$result[] = strtoupper(substr($value, 0,2))." - ".strtoupper(substr($value, 3,1)).substr($value, 4);
	}
	return $result;
}
function get_more_extension($url)
{
	$moreExtension = "";
	$start = 0;
	foreach ($url as $key => $value) 
	{
		if($key != "url")
		{
			if($start == 0)
			{								
				$moreExtension .= $key."=".$value;
				$start = 1;
			}
			else 
			{
				$moreExtension .= "&".$key."=".$value;
			}
		}
	}
	if($start == 1)
	{
		$moreExtension = "?".$moreExtension;
	}
	return $moreExtension;
}

function isLocalhost()
{
	$test = getcwd();

	if(stripos($test, DS."htdocs".DS) !== FALSE || stripos($test, DS."sandbox".DS) !== FALSE || stripos($test, DS."creazidigital".DS) !== FALSE)
    {
        return true;
    }
    else
    {
        return false;
    }
}

function redirectSessionNow($url)
{
	return substr($url, strpos($url, '/',(isLocalhost()?1:0)));
}
function gmt_adjustment()
{
    date_default_timezone_set("Asia/Jakarta");
    return time();
}
function getAppRootPath()
{
	$str = substr(WWW_ROOT, 0 , strlen(WWW_ROOT)-1); // buang DS trakhir...
	$str = substr($str, 0 , strripos($str, DS)+1); // buang webroot...
	return $str;
}
function calcNumberWidth($value)
{
	$result = 36 + strlen(strval($value)) * 6;
	return $result;
}

function file_get_contents_curl($url) {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);       

    $data = curl_exec($ch);
    curl_close($ch);

    return $data;
}

function swap_value(&$a , &$b)
{
	$temp = $a;
	$a = $b;
	$b = $temp;
}

function orderby_metavalue($data = array() , $metatable = NULL , $metakey , $sortorder = NULL , $input_type = NULL)
{
    if(empty($sortorder))
	{
		$sortorder = "ASC";
	}
	$sortorder = strtoupper($sortorder);
	
	$keysort = array();
	$valuesort = array();
	
	if($metakey == 'created' || $metakey == 'modified')
	{
		foreach ($data as $key => $value) 
		{
			array_push($keysort , $key);
			
			$tempvalue = (empty($metatable)?$value[$metakey]:$value[$metatable][$metakey]);
			array_push($valuesort , $tempvalue);
		}
	}
	else
	{
        if($input_type == 'gallery')
        {
            $metakey = 'count-form-'.$metakey;
        }
        
		foreach ($data as $key => $value) 
		{
			array_push($keysort , $key);
			
			$tempvalue = (empty($metatable)?$value[$metakey]:$value[$metatable][$metakey]);
			
			// test if element value is a date value or not !!
			$temptime = strtotime($tempvalue);
			if($temptime !== FALSE)
			{
				$tempvalue = date('Y-m-d H:i:s' , $temptime);
			}
					
			array_push($valuesort , $tempvalue);
		}
	}
	
	array_multisort($valuesort , ($sortorder == "ASC"?SORT_ASC:SORT_DESC) , $keysort);
	
	$result = array();
	foreach ($keysort as $key => $value) 
	{
		array_push($result,$data[$value]);
	}
	return $result;
}

function parseTime($s)
{
    $c = explode(':' , $s);
    return $c[0] * 60 + $c[1];
}

function toMoney($amount,$separator=true,$simple=false){
    return
        (true===$separator?
            (false===$simple?
                number_format($amount,2,'.',','):
                str_replace('.00','',toMoney($amount))
            ):
            (false===$simple?
                number_format($amount,2,'.',''):
                str_replace('.00','',toMoney($amount,false))
            )
        );
}

function getTempFolderPath()
{
	return WWW_ROOT.'files'.DS;
}

/**
	* convert older excel version to newer Excel 2007 version
	* @param string $inputPath contains location path of file excel to be converted
	* @param string $outputPath contains RESULT location path file excel
	* @return true
	* @public
	**/
function convertExcelVersion($inputPath,$outputPath)
{
	App::import('Vendor', 'phpexcel');

    $inputFileType = PHPExcel_IOFactory::identify($inputPath);
    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
  
    $objExcel = $objReader->load($inputPath);

    $outputFileType = 'Excel2007';
    $objWriter = PHPExcel_IOFactory::createWriter($objExcel,$outputFileType);
    $objWriter->setPreCalculateFormulas(false);
    $objWriter->save($outputPath);
}

function getSizeFile($url) {
    if (substr($url,0,4)=='http') {
        $x = array_change_key_case(get_headers($url, 1),CASE_LOWER);
        if ( strcasecmp($x[0], 'HTTP/1.1 200 OK') != 0 ) { $x = $x['content-length'][1]; }
        else { $x = $x['content-length']; }
    }
    else { $x = filesize($url); }

    return $x;
}

function promptDownloadFile($file)
{
	header('Content-Description: File Transfer');
	header('Content-Type: application/octet-stream');
	header('Content-Disposition: attachment; filename='.str_replace(" ", "_", basename($file) ));
	header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header("Cache-Control: no-cache, must-revalidate, post-check=0, pre-check=0");
    header('Pragma: public');
    if(file_exists($file))	header('Content-Length: ' . getSizeFile($file));
    ob_clean();
    flush();
    if(file_exists($file))	readfile($file);
}

/*
 * Upload Single File Function >>>
 * */
function uploadFile($image)
{
	$upFile = WWW_ROOT.'files'.DS.$image['name'];
	move_uploaded_file($image['tmp_name'], $upFile);
	chmod ($upFile , 0777);
}

function getValidFileName($fullname)
{
	$counter = 0;
	$path_parts = pathinfo($fullname);	
	
	$mySlug = $slug = Inflector::slug($path_parts['filename']);
	
	$path = WWW_ROOT.'files'.DS;
	while(TRUE)
	{
		if(file_exists($path.$mySlug.'.'.$path_parts['extension']))
		{
			$mySlug = $slug.'_'.(++$counter);
		}
		else {
			break;
		}
	}
	return $mySlug.'.'.$path_parts['extension'];
}

function deleteFile($title)
{
	$upFile = WWW_ROOT.'files'.DS.$title;
	unlink($upFile);
}
/*
 * End Of Upload Single File Function >>>
 * */
 
function makeClickableLinks($s) 
{
  return preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@', '<a href="$1" target="_blank">$1</a>', $s);
}

// ===========================>>
// IN ARRAY RECURSIVE !!
// ===========================>>
function in_array_r($needle, $haystack, $strict = false) {
    foreach ($haystack as $item) {
        if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
            return true;
        }
    }

    return false;
}

/**
 * Get either a Gravatar URL or complete image tag for a specified email address.
 *
 * @param string $email The email address
 * @param string $s Size in pixels, defaults to 80px [ 1 - 2048 ]
 * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
 * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
 * @param boole $img True to return a complete IMG tag False for just the URL
 * @param array $atts Optional, additional key/value attributes to include in the IMG tag
 * @return String containing either just a URL or a complete image tag
 * @source http://gravatar.com/site/implement/images/php/
 */
function get_gravatar( $email, $s = 80, $d = 'mm', $r = 'g', $img = false, $atts = array() ) {
    $url = 'http://www.gravatar.com/avatar/';
    $url .= md5( strtolower( trim( $email ) ) );
    $url .= "?s=$s&d=$d&r=$r";
    if ( $img ) {
        $url = '<img src="' . $url . '"';
        foreach ( $atts as $key => $val )
            $url .= ' ' . $key . '="' . $val . '"';
        $url .= ' />';
    }
    return $url;
}

/*
 * New Function in CakePHP 2.x
 */
function parseQueryStringUrl($url)
{
	$result = array();
	$qmark = strrpos($url , '?');

	if($qmark !== FALSE)
	{
		$potong1 = substr($url , $qmark+1);

		$pecah1 = explode('&' , $potong1);

		foreach ($pecah1 as $key => $value) 
		{
			$subpecah = explode('=' , $value);
			$result[$subpecah[0]] = $subpecah[1];
		}
	}
	return $result;
}

function composeQueryStringUrl($query = array())
{
	$chainUrl = '';
	$counter = 0;
	foreach ($query as $key => $value) 
	{
		$chainUrl .= ($counter==0?'?':'&').$key.'='.$value;
		$counter++;
	}
	return $chainUrl;
}

function breakEntryMetas($myEntry , $metatable = 'EntryMeta')
{
	foreach ($myEntry[$metatable] as $key => $value) 
	{
		$myEntry[$metatable][(substr($value['key'], 0,5)=='form-'?substr($value['key'], 5):$value['key'])] = $value['value'];
	}
	return $myEntry;
}

function checkExpired($expired_date)
{
	if(empty($expired_date))
	{
		return false;
	}
	else
	{
		$expired_date = new DateTime($expired_date);
	    $now = new DateTime(date('m/d/Y' , gmt_adjustment()));
	    return $expired_date < $now;
	}
}

/*
 * Convert the share url to direct download url (Google Drive) !!
 */
function parseGoogleDriveUrl($shareurl)
{
	$pecahshare = explode("/", $shareurl);
	$id_drive = $pecahshare[count($pecahshare)-2];
	return "https://docs.google.com/uc?export=download&id=".$id_drive;
}

/*
back-up project files into zip file.
*/
function Zip($source, $destination, $include_dir = false)
{
	ini_set('memory_limit', '-1'); // unlimited memory limit to process backup files.

	if (!extension_loaded('zip') || !file_exists($source)) {
        return false;
    }

    // If the zip file exists, the file will be deleted as well.
    if (file_exists($destination)) {
        unlink ($destination);
    }

    $zip = new ZipArchive();
    if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {
        return false;
    }
    $source = str_replace('\\', '/', realpath($source));

    if (is_dir($source) === true)
    {
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

        if ($include_dir) {

            $arr = explode("/",$source);
            $maindir = $arr[count($arr)- 1];

            $source = "";
            for ($i=0; $i < count($arr) - 1; $i++) { 
                $source .= '/' . $arr[$i];
            }

            $source = substr($source, 1);

            $zip->addEmptyDir($maindir);
        }

        foreach ($files as $file)
        {
            $file = str_replace('\\', '/', $file);

            // Ignore "." and ".." folders
            if( in_array(substr($file, strrpos($file, '/')+1), array('.', '..')) )
                continue;

            $file = realpath($file);

            if (is_dir($file) === true)
            {
                $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
            }
            else if (is_file($file) === true)
            {
                $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
            }
        }
    }
    else if (is_file($source) === true)
    {
        $zip->addFromString(basename($source), file_get_contents($source));
    }

	$zip->close();

	$file = $destination;
	if(file_exists($file)) 
	{
		promptDownloadFile($file);
		unlink($file); // delete temp files.
		exit;
	}
	else
	{
		return false;
	} 
}