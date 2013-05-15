<?php
/** 
 * The WatchRelated extension, an extension to add a related article indicator
 * to a page, which then adds all watchers of that related article as watchers
 * to the page. Adds parser function #getwatchers.
 * 
 * Documentation: http://???
 * Support:       http://???
 * Source code:   http://???
 *
 * @file WatchRelated.php
 * @addtogroup Extensions
 * @author James Montalvo
 * @copyright © 2013 by James Montalvo
 * @licence GNU GPL v3+
 */

# Not a valid entry point, skip unless MEDIAWIKI is defined
if (!defined('MEDIAWIKI')) {
	die( "WatchRelated extension" );
}

$wgExtensionCredits['specialpage'][] = array(
	'path'           => __FILE__,
	'name'           => 'Watch Related',
	'url'            => 'https://???',
	'author'         => 'James Montalvo',
	'descriptionmsg' => 'watchrelated-desc',
	'version'        => '0.0.1 alpha'
);

$dir = dirname( __FILE__ ) . '/';
$wgExtensionMessagesFiles['WatchRelated'] = $dir . 'WatchRelated.i18n.php';
$wgExtensionMessagesFiles['WatchRelatedAlias'] = $dir . 'WatchRelated.alias.php';
$wgAutoloadClasses['WatchRelated'] = $dir . 'WatchRelated.body.php';

// Specify the function that will initialize the parser function.
$wgHooks['ParserFirstCallInit'][] = 'WatchRelated::setup';


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
