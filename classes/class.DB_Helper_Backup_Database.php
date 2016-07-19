<?php

/**
 * This file contains the Backup_Database class wich performs
 * a partial or complete backup of any given MySQL database
 * @author Daniel López Azaña <http://www.azanweb.com-->
 * @version 1.0
 */

/**
 * Define database parameters here
 */

class DB_Helper_Backup_Database {
    /**
     * Host where database is located
     */
    var $host = '';

    /**
     * Username used to connect to database
     */
    var $username = '';

    /**
     * Password used to connect to database
     */
    var $passwd = '';

    /**
     * Database to backup
     */
    var $dbName = '';

    /**
     * Database charset
     */
    var $charset = '';

    /**
     * Constructor initializes database
     */
    public function __construct()
    {
        $this->host     = DB_HOST;
        $this->username = DB_USER;
        $this->passwd   = DB_PASSWORD;
        $this->dbName   = DB_NAME;
        $this->charset  = DB_CHARSET;
    }


    /**
     * Backup the whole database or just some tables
     * Use '*' for whole database or 'table1 table2 table3...'
     * @param string $tables
     */
    public function backupTables($outputDir = '.')
    {
		global $wpdb;
	
		$sql = 'CREATE DATABASE IF NOT EXISTS '.$this->dbName.";\n\n";
		$sql .= 'USE '.$this->dbName.";\n\n";
		
		$tables = $wpdb->get_results('SHOW TABLES', ARRAY_A);
		
		foreach ($tables as $table) {
			$table_name = current($table);
			
			$sql .= "DROP TABLE IF EXISTS ".$table_name.";\n\n";
			
			$show_create_table = $wpdb->get_results('SHOW CREATE TABLE '.$table_name, ARRAY_N);
			$show_create_table = $show_create_table[0][1];
			
			$sql .= $show_create_table . ";\n\n";
			
			$all_from_table = $wpdb->get_results('SELECT * FROM '.$table_name, ARRAY_A);
			if ($all_from_table) {	
				foreach($all_from_table as $all_from_table_row) {
					foreach($all_from_table_row as $column_name => $value) {
						$all_from_table_row[$column_name] = str_replace("\n","\\n",addslashes($value));
					}

					$values = implode("','", $all_from_table_row);

					$sql .= 'INSERT INTO '.$table_name." VALUES('" . $values . "');\n" ;
				}

				
			}
			
			$sql .= "\n\n";
		}
		
		return $this->sendFile($sql);
		
        
    }
	
	protected function sendFile($sql) {
		$filename = sanitize_title($_POST['backup_file_name']) . ".sql";
		header("HTTP/1.1 200 OK");
		header('Content-Disposition: attachment; filename='.$filename);  
		header('Content-Type: application/x-sql'); 
		
		echo $sql;
		exit();
	}

	/**
     * Save SQL to file
     * @param string $sql
     */
    protected function saveFile(&$sql, $outputDir = '.')
    {
        if (!$sql) return false;

        try
        {
            $handle = fopen($outputDir.'/db-backup-'.$this->dbName.'-'.date("Ymd-His", time()).'.sql','w+');
            fwrite($handle, $sql);
            fclose($handle);
        }
        catch (Exception $e)
        {
            var_dump($e->getMessage());
            return false;
        }

        return true;
    }
}
