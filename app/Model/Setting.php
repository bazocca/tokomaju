<?php
class Setting extends AppModel {
	var $name = 'Setting';
	var $validate = array(
		'key' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);
	
	// DATABASE MODEL...
	var $User = NULL;
	var $UserMeta = NULL;
	var $Entry = NULL;
	var $EntryMeta = NULL;
	var $Account = NULL;
	
	public function __construct( $id = false, $table = NULL, $ds = NULL )
	{
		parent::__construct($id, $table, $ds);
		
		// set needed database model ...
		$this->User = ClassRegistry::init('User');
		$this->UserMeta = ClassRegistry::init('UserMeta');
		$this->Entry = ClassRegistry::init('Entry');
		$this->EntryMeta = ClassRegistry::init('EntryMeta');
		$this->Account = ClassRegistry::init('Account');
	}
	
	/**
	 * retrieve all template settings based on name and key in indexing array
	 * @return array $mySetting contains array of indexing settings
	 * @public
	 **/
	public function get_settings()
	{			
		$mySetting = $this->find('all',array('order' => array('Setting.id')));
		foreach ($mySetting as $key => $value) 
		{
			$mySetting[$value['Setting']['key']] = $value['Setting']['value'];
		}
		$mySetting['language'] = parse_lang($mySetting['language']);
		return $mySetting;
	}
	
	// ----------------------------------------------------------------------------------- >>>
	// -------------------- DATABASE BACKUP & RESTORE FUNCTION --------------------------- >>>
	// ----------------------------------------------------------------------------------- >>>
	/* backup the db OR just a table */
	function backup_tables($host,$user,$pass,$name,$tables = '*' , $delimiter = '/*cakepanel2014*/;')
	{ 
	  $link = mysql_connect($host,$user,$pass);
	  mysql_select_db($name,$link);
	  
	  //get all of the tables
	  if($tables == '*')
	  {
	    $tables = array();
	    $result = mysql_query('SHOW TABLES');
	    while($row = mysql_fetch_row($result))
	    {
	      $tables[] = $row[0];
	    }
	  }
	  else
	  {
	    $tables = is_array($tables) ? $tables : explode(',',$tables);
	  }
	  
	  //cycle through
	  $return = "";
	  foreach($tables as $table)
	  {
	    $result = mysql_query('SELECT * FROM '.$table);
	    $num_fields = mysql_num_fields($result);

	    $return.= 'DROP TABLE IF EXISTS '.$table.$delimiter;
	    $row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
	    $return.= "\n\n".$row2[1].$delimiter."\n\n";
	    
	    for ($i = 0; $i < $num_fields; $i++) 
	    {
	      while($row = mysql_fetch_row($result))
	      {
	        $return.= 'INSERT INTO '.$table.' VALUES(';
	        for($j=0; $j<$num_fields; $j++) 
	        {
	          $row[$j] = addslashes($row[$j]);
	          $row[$j] = preg_replace("/^\n/","\\n",$row[$j]);
	          if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
	          if ($j<($num_fields-1)) { $return.= ','; }
	        }
	        $return.= ")".$delimiter."\n";
	      }
	    }
	    $return.="\n\n\n";
	  }
	  return $return;
	}

	//
	// split_sql_file will split an uploaded sql file into single sql statements.
	// Note: expects trim() to have already been run on $sql.
	//
	function split_sql_file($sql, $delimiter = '/*cakepanel2014*/;')
	{
	   // Split up our string into "possible" SQL statements.
	   $tokens = explode($delimiter, $sql);

	   // try to save mem.
	   $sql = "";
	   $output = array();

	   // we don't actually care about the matches preg gives us.
	   $matches = array();

	   // this is faster than calling count($oktens) every time thru the loop.
	   $token_count = count($tokens);
	   for ($i = 0; $i < $token_count; $i++)
	   {
		  // Don't wanna add an empty string as the last thing in the array.
		  if (($i != ($token_count - 1)) || (strlen($tokens[$i] > 0)))
		  {
			 // This is the total number of single quotes in the token.
			 $total_quotes = preg_match_all("/'/", $tokens[$i], $matches);
			 // Counts single quotes that are preceded by an odd number of backslashes,
			 // which means they're escaped quotes.
			 $escaped_quotes = preg_match_all("/(?<!\\\\)(\\\\\\\\)*\\\\'/", $tokens[$i], $matches);

			 $unescaped_quotes = $total_quotes - $escaped_quotes;

			 // If the number of unescaped quotes is even, then the delimiter did NOT occur inside a string literal.
			 if (($unescaped_quotes % 2) == 0)
			 {
				// It's a complete sql statement.
				$output[] = $tokens[$i];
				// save memory.
				$tokens[$i] = "";
			 }
			 else
			 {
				// incomplete sql statement. keep adding tokens until we have a complete one.
				// $temp will hold what we have so far.
				$temp = $tokens[$i] . $delimiter;
				// save memory..
				$tokens[$i] = "";

				// Do we have a complete statement yet?
				$complete_stmt = false;

				for ($j = $i + 1; (!$complete_stmt && ($j < $token_count)); $j++)
				{
				   // This is the total number of single quotes in the token.
				   $total_quotes = preg_match_all("/'/", $tokens[$j], $matches);
				   // Counts single quotes that are preceded by an odd number of backslashes,
				   // which means they're escaped quotes.
				   $escaped_quotes = preg_match_all("/(?<!\\\\)(\\\\\\\\)*\\\\'/", $tokens[$j], $matches);

				   $unescaped_quotes = $total_quotes - $escaped_quotes;

				   if (($unescaped_quotes % 2) == 1)
				   {
					  // odd number of unescaped quotes. In combination with the previous incomplete
					  // statement(s), we now have a complete statement. (2 odds always make an even)
					  $output[] = $temp . $tokens[$j];

					  // save memory.
					  $tokens[$j] = "";
					  $temp = "";

					  // exit the loop.
					  $complete_stmt = true;
					  // make sure the outer loop continues at the right point.
					  $i = $j;
				   }
				   else
				   {
					  // even number of unescaped quotes. We still don't have a complete statement.
					  // (1 odd and 1 even always make an odd)
					  $temp .= $tokens[$j] . $delimiter;
					  // save memory.
					  $tokens[$j] = "";
				   }

				} // for..
			 } // else
		  }
	   }

	   return $output;
	}

	function executeSql($hostname,$db_user,$db_password,$database_name,$sqlFileToExecute)
	{
		$message = "success";
		$link = mysql_connect($hostname, $db_user, $db_password);
		if (!$link) 
		{
		    $message = "MySQL Connection error!";
		    return $message;
		}
		
		$db_selected = mysql_select_db($database_name, $link);
		if(!$db_selected)
		{
			$message = "Wrong MySQL Database!";
			return $message;
		}
		
		// clean database first !!
		$this->cleanDatabase();
		
		// read the sql file
		$sql_query = @fread(@fopen($sqlFileToExecute, 'r'), @filesize($sqlFileToExecute)) or die('problem!');
		$sql_query = $this->split_sql_file($sql_query);
		foreach($sql_query as $sql){
			mysql_query($sql) or die('error in query!');
		}
		
		unlink($sqlFileToExecute);
		return $message;
	}
	
	function cleanDatabase()
	{
		// delete User , sisakan 1 user (super admin) ...
/*		$super = $this->Account->findByRoleId("1");
		
		// delete USER DATA...
		$this->User->deleteAll(array('User.id !=' => $super['User']['id']));		
		$this->UserMeta->deleteAll(array('UserMeta.user_id !=' => $super['User']['id']));		
		$this->Account->deleteAll(array('Account.user_id !=' => $super['User']['id']));
*/		// delete ENTRY DATA...
		$this->Entry->deleteAll(array('1 = 1'));
		$this->EntryMeta->deleteAll(array('1 = 1'));
	}
}
