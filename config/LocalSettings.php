<?php

/* wikiStax is released under the GNU GPL.

wikiStax is developed by DAFO Creative Ltd and released as is with no warranty.

See wikistax.org for documentation.

This file is used to call Wiki fields within a Wiki Farm.

Protect against web entry */

//require_once ("/var/www/html/common/CommonSettings.php");
//require_once ("/var/www/html/common/Permissions.php");

// Include common settings to all wikis before this line (eg. database configuration)

$callingurl = $callingurl = strtolower( $_SERVER['REQUEST_URI'] ); // get the calling url
//wiki field start wikiname
if ( strpos( $callingurl, '/field1' )  === 0 ) {
        require_once 'LocalSettings_field1.php';
//wiki field end


} else {
    header('HTTP/1.1 404 Not Found');
    echo 'This wiki is not available. Please contact the SysAdmin with error: WIKI_NOT_FOUND';
    exit(0);
}

/*} else
  {
        switch ( $_SERVER['SERVER_NAME'] ) {

                case 'custom1.com':
                        require_once 'LocalSettings_custom1.php';
                       break;

                case 'custom2.com':
                        require_once 'LocalSettings_custom2.php';
                        break;

                default:
                        header( 'HTTP/1.1 404 Not Found' );
                        echo 'This wiki is not available.
Please contact the SysAdmin with error:
WIKI_NOT_FOUND';
                  exit( 0 );
        }

}
*/