<?php 
define('_ACCESS_OK', true);
require_once 'init.php';

function adminer_object() {
    // required to run any plugin
    include_once "vendors/adminer/plugin.php";
    
    // autoloader
    foreach (glob("vendors/adminer/*.php") as $filename) {
        include_once "./$filename";
    }

    $plugins = array(
        // specify enabled plugins here
        // new AdminerWymeditor,
        new AdminerTinymce,
        new AdminerSqlLog(__DIR__.'/vendors/adminer/logs/'),
        new AdminerDumpBz2,
        new AdminerDumpDate,
        new AdminerDumpJson,
        new AdminerDumpXml,
        new AdminerDumpZip,
        new AdminerTablesFilter,
        new AdminerStructComments,
        new AdminerEditCalendar,
        new AdminerForeignSystem,
        new AdminerTableHeaderScroll,
        new AdminerColorfields,
        // new AdminerDesigns('konya'),
    );
    
    class AdminerSoftware extends Adminer {
        function login($login, $password) {
            global $jush;
            if ($jush == "sqlite")
                return ($login === 'root') && ($password === 'admin');
            return true;
        }

        //for sqlite db
        //put db full path here to access the sqlite db
        /*function databases($flush = true) {
            if (isset($_GET['sqlite']))
                return ["easymobile3.db"];
            return get_databases($flush);
        }*/
    }
    return new AdminerSoftware;
    
    return new AdminerPlugin($plugins);
}

// include original Adminer or Adminer Editor
include "vendors/adminer/adminer.php";