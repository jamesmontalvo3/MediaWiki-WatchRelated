<?php
/** 
 * The CopyWatchers extension allows editors to copy watchers from other pages
 * using the parser function #copywatchers.
 * 
 * Documentation: http://???
 * Support:       http://???
 * Source code:   http://???
 *
 * @file CopyWatchers.php
 * @addtogroup Extensions
 * @author James Montalvo
 * @copyright © 2013 by James Montalvo
 * @licence GNU GPL v3+
 */

# Not a valid entry point, skip unless MEDIAWIKI is defined
if (!defined('MEDIAWIKI')) {
	die( "CopyWatchers extension" );
}

$wgExtensionCredits['specialpage'][] = array(
	'path'           => __FILE__,
	'name'           => 'Copy Watchers',
	'url'            => 'http://www.mediawiki.org/wiki/Extension:CopyWatchers',
	'author'         => 'James Montalvo',
	'descriptionmsg' => 'copywatchers-desc',
	'version'        => '0.1.0'
);

$dir = dirname( __FILE__ ) . '/';
$wgExtensionMessagesFiles['CopyWatchers'] = $dir . 'CopyWatchers.i18n.php';
$wgAutoloadClasses['CopyWatchers'] = $dir . 'CopyWatchers.body.php';

// Specify the function that will initialize the parser function.
$wgHooks['ParserFirstCallInit'][] = 'CopyWatchers::setup';


/*
$wgAvailableRights[] = 'semanticwatch';
$wgAvailableRights[] = 'semanticwatchgroups';

$wgHooks['LoadExtensionSchemaUpdates'][] = 'SWLHooks::onSchemaUpdate';
$wgHooks['SMWStore::updateDataBefore'][] = 'SWLHooks::onDataUpdate';
$wgHooks['GetPreferences'][] = 'SWLHooks::onGetPreferences';
$wgHooks['UserSaveOptions'][] = 'SWLHooks::onUserSaveOptions';
$wgHooks['AdminLinks'][] = 'SWLHooks::addToAdminLinks';
$wgHooks['PersonalUrls'][] = 'SWLHooks::onPersonalUrls';
$wgHooks['SWLGroupNotify'][] = 'SWLHooks::onGroupNotify';

$wgExtensionFunctions[]        = 'wfSetupCatHook';
$wgHooks['LanguageGetMagic'][] = 'wfCatHookLanguageGetMagic';

$wgHooks['ParserAfterTidy'][]  = 'wfUserPageViewTracker';

class CategoryWatch {
	function __construct() {
		global $wgHooks;
		$wgHooks['ArticleSave'][] = $this;
		$wgHooks['ArticleSaveComplete'][] = $this;
	}
	...
}



*/
