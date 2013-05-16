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

class WatchRelated
{

	static public function setup () {

		$parser->setFunctionHook( 'copywatchers', array( &$this, 'copyWatchers' ) );
		
		// return something?
		
		/*
		if ( defined( get_class( $parser ) . '::SFH_OBJECT_ARGS' ) ) {
			$parser->setFunctionHook( 'copywatchers', array( '&$this', 'copyWatchers' ), SFH_OBJECT_ARGS );
		} else {
			$parser->setFunctionHook( 'copywatchers', array( 'SFParserFunctions', 'renderArrayMap' ) );
		}
		*/
	
	}


	static public function copyWatchers (&$parser, $pagesToCopyWatchers) {
		
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