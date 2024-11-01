<?php

include_once('ThumbGettys_LifeCycle.php');

class ThumbGettys_Plugin extends ThumbGettys_LifeCycle {

    /**
     * See: http://plugin.michael-simpson.com/?page_id=31
     * @return array of option meta data.
     */
    public function getOptionMetaData() {
        //  http://plugin.michael-simpson.com/?page_id=31
        return array(
            //'_version' => array('Installed Version'), // Leave this one commented-out. Uncomment to test upgrades.
            'APPTextInput' => array(__('App ID', 'thumbgettys')),
			'Domain' => array(__('Domain', 'thumbgettys'))
        );
    }

//    protected function getOptionValueI18nString($optionValue) {
//        $i18nValue = parent::getOptionValueI18nString($optionValue);
//        return $i18nValue;
//    }

    protected function initOptions() {
        $options = $this->getOptionMetaData();
        if (!empty($options)) {
            foreach ($options as $key => $arr) {
                if (is_array($arr) && count($arr > 1)) {
                    $this->addOption($key, $arr[1]);
                }
            }
        }
    }

    public function getPluginDisplayName() {
        return 'ThumbGettys';
    }

    protected function getMainPluginFileName() {
        return 'thumbgettys.php';
    }

    /**
     * See: http://plugin.michael-simpson.com/?page_id=101
     * Called by install() to create any database tables if needed.
     * Best Practice:
     * (1) Prefix all table names with $wpdb->prefix
     * (2) make table names lower case only
     * @return void
     */
    protected function installDatabaseTables() {
                global $wpdb;
                $tableName = $this->prefixTableName('thumbgettys');
                $wpdb->query("CREATE TABLE IF NOT EXISTS `$tableName` (
                    `app` VARCHAR(255) NOT NULL, `domain` VARCHAR(255) NOT NULL");
    }

    /**
     * See: http://plugin.michael-simpson.com/?page_id=101
     * Drop plugin-created tables on uninstall.
     * @return void
     */
    protected function unInstallDatabaseTables() {
				global $wpdb;
                $tableName = $this->prefixTableName('thumbgettys');
                $wpdb->query("DROP TABLE IF EXISTS `$tableName`");
    }


    /**
     * Perform actions when upgrading from version X to version Y
     * See: http://plugin.michael-simpson.com/?page_id=35
     * @return void
     */
    public function upgrade() {
    }	

    public function addActionsAndFilters() {

        add_action('admin_menu', array(&$this, 'addSettingsSubMenuPage'));
		
		wp_enqueue_script('jquery');
		wp_register_style( 'thumbgettysstyle', plugins_url('thumbgettys_tooltip.css', __FILE__) );
		wp_enqueue_style( 'thumbgettysstyle' );
		
		//wp_enqueue_script( 'thumbgettysscript',	plugins_url( "/wp-thumbgettys-js.php?app=" . $_app . "&domain=" . $_domain , __FILE__ ) );
		
		add_filter('the_content', array(&$this, 'set_div'));
    }
	
	public function set_div($content) {
		$_app = $this->getOption('APPTextInput');
		$_domain = $this->getOption('Domain');	
		$content = preg_replace('/(.+?)<a href="(.+?)">(.+?)<\/a>(.+?)/sim', '$1<a href="$2" rel="nofollow" class="tooltip">$3<span><img src="http://www.thumbgettys.com/main/v/' . $_app . '/site/' . $_domain . '/?url=$2" /></span></a>$4', $content);
		return $content;
	}

}
