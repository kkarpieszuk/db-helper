<?php
/*
 * Plugin Name: DB Helper
 * Author: Konrad Karpieszuk
 */

class DB_Helper_Plugin {
	
	private $DB_Helper_Backup;
	private $DB_Helper_Admin_Bar;
	
	public function __construct() {
		
		add_action('wp_enqueue_scripts', array($this, 'enqueue'));
		add_action('admin_enqueue_scripts', array($this, 'enqueue'));
		
		$this->admin_bar();
		
		add_action('init', array($this, 'backup'));
	}
	
	public function enqueue() {
		wp_enqueue_style('db_helper_styles', plugins_url('/styles/style.css', __FILE__));
		wp_enqueue_script('db_helper_script', plugins_url('/js/script.js', __FILE__), array('jquery'));
	}
		
	private function admin_bar() {
		require_once 'classes/class.DB_Helper_Admin_Bar.php';
		$this->DB_Helper_Admin_Bar = new DB_Helper_Admin_Bar();
		add_action( 'wp_before_admin_bar_render', array($this->DB_Helper_Admin_Bar, 'admin_bar_menu'));
	}
	
	public function backup() {
		if (isset($_POST['backup_dwn_file'])) {
			require_once 'classes/class.DB_Helper_Backup.php';
			require_once 'classes/class.DB_Helper_Backup_Database.php';
			
			$this->DB_Helper_Backup_Database = new DB_Helper_Backup_Database();
			
			$this->DB_Helper_Backup = new DB_Helper_Backup($this->DB_Helper_Backup_Database);
		}
	}
	
}

$DB_Helper_Plugin = new DB_Helper_Plugin();