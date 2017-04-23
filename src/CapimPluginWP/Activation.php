<?php
namespace CapimPluginWP;

class Activation
{
    public function load()
    {
        global $wpdb;
        if($wpdb && function_exists("dbDelta")) {
            $table_name = "cm_options";
            $charset_collate = $wpdb->get_charset_collate();
            $sql = "CREATE TABLE $table_name (
  id mediumint(9) NOT NULL AUTO_INCREMENT,
  name tinytext NOT NULL,
  value text NOT NULL,
  PRIMARY KEY  (id)
) $charset_collate;";
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
        }
    }
}