<?php
/**
 * Created by IntelliJ IDEA.
 * User: online2
 * Date: 15/03/2018
 * Time: 13:55
 */


global $custom_table_example_db_version;
$custom_table_example_db_version = '1.0'; // version changed from 1.0 to 1.1

function custom_table_example_install()
{
    global $wpdb;
    global $custom_table_example_db_version;

    $table_name = $wpdb->prefix . 'miss_vote';
    $sql        = "CREATE TABLE $table_name (
			  id int(11) NOT NULL AUTO_INCREMENT,
			  idphase int(11) NULL,
			  idetape int(11) NULL,
			  idparticipant int(11) NULL,
			  idcandidat int(11) NULL,
			  idserie int(11) NULL ,
			  point int (11) NULL,
			  type_vote varchar (255) NULL,
			  PRIMARY KEY (id)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

    $table_name = $wpdb->prefix . 'miss_session';
    $sql        .= "CREATE TABLE $table_name (
			  id int(11) NOT NULL AUTO_INCREMENT,
			  idparticipant int(11) NULL,
			  etape_list varchar (255) NULL,
			  status int(11) default 1,
			  created_at datetime default CURRENT_TIMESTAMP,
			  update_at datetime default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
			  PRIMARY KEY (id)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

    // we do not execute sql directly
    // we are calling dbDelta which cant migrate database
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    dbDelta($sql);

    // save current database version for later use (on upgrade)
    add_option('custom_table_example_db_version', $custom_table_example_db_version);

    /**
     * [OPTIONAL] Example of updating to 1.1 version
     *
     * If you develop new version of plugin
     * just increment $custom_table_example_db_version variable
     * and add following block of code
     *
     * must be repeated for each new version
     * in version 1.1 we change email field
     * to contain 200 chars rather 100 in version 1.0
     * and again we are not executing sql
     * we are using dbDelta to migrate table changes
     */
//    $installed_ver = get_option('custom_table_example_db_version');
//    if ($installed_ver != $custom_table_example_db_version) {
//        $sql = "CREATE TABLE " . $table_name . " (
//            id int(11) NOT NULL AUTO_INCREMENT,
//            name tinytext NOT NULL,
//            email VARCHAR(200) NOT NULL,
//            age int(11) NULL,
//            PRIMARY KEY  (id)
//        );";
//
//        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
//        dbDelta($sql);
//
//        // notice that we are updating option, rather than adding it
//        update_option('custom_table_example_db_version', $custom_table_example_db_version);
//    }
}

function create_datatable($oldname, $oldtheme=false)
{

    custom_table_example_install();
}

add_action("after_switch_theme", "create_datatable", 10 , 2);

function deleteTable($newname, $newtheme){

    global $wpdb;

    $table_name = $wpdb->prefix . 'miss_vote';

    $sql = "DROP TABLE IF EXISTS ". $table_name;
//    $wpdb->query($sql);

    $table_name = $wpdb->prefix . 'miss_parrain';

    $sql = "DROP TABLE IF EXISTS ". $table_name;
//    $wpdb->query($sql);

    delete_option("custom_table_example_db_version");

}

add_action("switch_theme", "deleteTable", 10 , 2);
