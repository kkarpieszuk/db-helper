<?php
class DB_Helper_Backup {
	
	private $DB_Helper_Backup_Database;
	
	public function __construct($DB_Helper_Backup_Database) {
		$this->DB_Helper_Backup_Database = $DB_Helper_Backup_Database;
		if (check_admin_referer( 'backup_dwn_file' )) {
			$this->do_backup();
		}
	}
	
	private function do_backup() {
		$this->DB_Helper_Backup_Database->backupTables();
	}
}
