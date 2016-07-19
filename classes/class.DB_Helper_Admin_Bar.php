<?php

class DB_Helper_Admin_Bar {
	public function admin_bar_menu() {
		global $wp_admin_bar;
		
		$wp_admin_bar->add_menu(
			array(
				'parent' => false,
				'id' => 'db_helper_plugin_menu_top',
				'title' => '<span class="db_helper_plugin_menu_main_icon"></span> DB Helper',
				'meta'=> array(
					'class' => 'db_helper_plugin_menu_main'
				)
			)
		);
		
		$wp_admin_bar->add_menu(
			array(
				'parent' => 'db_helper_plugin_menu_top',
				'id' => 'db_helper_plugin_menu_backup',
				'title' => $this->backup_ui(),
				'meta' => array(
					'class' => 'db_helper_plugin_menu_backup'
				)
			)
		);
		
	}
	
	private function backup_ui() {
		$ui = "
<form method='post' id='dbhelper_backup_form'>
<input type='submit' 
		name='backup_dwn_file' 
		value='".__("Download sql file", "dbhelper")."'
		class='submit backup_dwn_file' 
		title='".__("Download sql file", "dbhelper")."'>
" . wp_nonce_field('backup_dwn_file', "_wpnonce", true , false) . "
<input type='text' name='backup_file_name' value='{$this->default_file_name()}' class='submit'
	title='".__("Choose file name", "dbhelper")."'>.sql
</form>
		";
		
		return $ui;
	}
	
	private function default_file_name() {
		$sitename = get_bloginfo('site_name');
		
		$date = date_i18n("Y-m-d_G:i:s");
		
		$file_name = $sitename . "_" . $date;
		
		return $file_name;
		
	}
}
