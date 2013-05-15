<?php
/**
 * The WatchRelated extension, an extension to add a related article indicator
 * to a page, which then adds all watchers of that related article as watchers
 * to the page.
 * 
 * Documentation: http://???
 * Support:       http://???
 * Source code:   http://???
 *
 * @file WatchRelated.body.php
 * @addtogroup Extensions
 * @author James Montalvo
 * @copyright © 2013 by James Montalvo
 * @licence GNU GPL v3+
 */
 
/*

foreach ($stuff_in_parser_function as $page) {

	foreach($page->getWatchers() as $user) {
	
		if ( ! $wgArticle->isWatcher() ) {
			$wgArticle->addWatcher($user);
		}
		
		
	}

}


if ( $page->has_category("Meeting Minutes") ) {


    // Loop through all the related articles on the meeting minutes page
    // This may actually be more difficult than this, since related articles
    // are attached to the meeting topic, not to the meeting itself...
    foreach($page->get_property_list("Related articles") as $related_article) {
        
        // note: $related_article refers to a different page in the wiki
        // for each time through this loop
        foreach($related_article->get_page_watchers() as $user) {

            // add the meeting minutes page to this user's watchlist
            $user->add_page_to_watchlist($page);

            /**
             *  If adding related-article-watchers to this page can happen 
             *  early in the page-creation process, prior to emails being 
             *  sent to page-watchers, then no explicit email function is
             *  required. However, since being able to tell if the page is
             *  in the Meeting Minutes category, and being able read the 
             *  property "Related article" may not be possible early enough
             *  in page-processing, an explicit re-emailing may be required
             **/
            $user->email();

        }
    }
}


*/
class WatchRelated
{

	static public function copyWatchers ($parser, $pagesToCopyWatchers) {
		
		$titleObj = $parser->getTitle(); //
		$userObj  = $parser->getUser(); //
		$userObj->addWatch( $someTitle ); //
		
		
		$pagesToCopyWatchers = explode(',', $pagesToCopyWatchers);
		
		$newWatchers = array();
		
		foreach( $pagesToCopyWatchers as $page ) {
	
			$pageObj = $this->getNamespaceAndTitle( $page );
			
			$watchers = $this->getPageWatchers( $pageObj->ns_num, $pageObj->title );
			
			foreach ( $watchers as $userID => $dummy ) {
				$newWatchers[$userID] = 0; // only care about $userID, and want unique.
			}

		}
		
		// add list of usernames as watchers to this Title
		foreach ($newWatchers as $userID => $dummy) {
			$u = User::newFromId($userID);
			$u->addWatch( $parser->getTitle() );
		}
		
	}
	
	protected function getNamespaceAndTitle ( $pageName ) {
	
		// defaults
		$ns_num = NS_MAIN;
		$title = $pageName;

		$colonPosition = strpos( $pageName, ':' ); // location of colon if exists
		
		// this won't test for a leading colon...but shouldn't use parser function that way anyway...
		if ( $colonPosition ) {
			$test_ns = $this->getNamespaceNumber( 
				substr( $pageName, 0, $colonPosition )
			);
			
			// only reset $ns and $title if has colon, and pre-colon text actually is a namespace
			if ( $test_ns !== false ) {
				$ns_num = $test_ns;
				$title = substr( $pageName, $colonPosition+1 );
			}
		}
		
		return (object)array("ns_num"=>$ns_num, "title"=>$title);
	
	}
	
	// returns number of namespace (can be zero) or false. Use ===.
	protected function getNamespaceNumber ( $ns ) {
		global $wgCanonicalNamespaceNames;
		
		foreach ( $wgCanonicalNamespaceNames as $i => $text ) {
			if (preg_match("/$ns/i", $text)) {
				return $i;
			}
		}
	
		return false; // if $ns not found above, does not exist
	}
	
	protected function getPageWatchers ($ns, $title) {
		
		// code adapted from Extension:WhoIsWatching
		$dbr = wfGetDB( DB_SLAVE );
		$watchingUserIDs = array();
		$res = $dbr->select( 'watchlist', 'wl_user', array('wl_namespace'=>$ns, 'wl_title'=>$title), __METHOD__);
		for ( $res as $row ) {
			$watchingUserIDs[$row->wl_user] = 0; // only care about the user ID, and want unique
		}

		return $watchingUserIDs;
			
	}
}