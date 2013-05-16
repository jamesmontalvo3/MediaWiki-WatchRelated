<?php
/**
 * The CopyWatchers extension allows editors to copy watchers from other pages
 * using the parser function #copywatchers. This file defines the class
 * 
 * Some code adapted from Extension:WhoIsWatching
 * 
 * Documentation: http://???
 * Support:       http://???
 * Source code:   http://???
 *
 * @file CopyWatchers.body.php
 * @addtogroup Extensions
 * @author James Montalvo
 * @copyright © 2013 by James Montalvo
 * @licence GNU GPL v3+
 */

class CopyWatchers
{

	static function setup ( &$parser ) {
		
		// I'm not really sure what the benefits of two ways of calling setFunctionHook
		// but I've implemented them both here. They both essentially end up calling
		// the same method: renderCopyWatchers().
		if ( defined( get_class( $parser ) . '::SFH_OBJECT_ARGS' ) ) {
			$parser->setFunctionHook( 'copywatchers', array( 'CopyWatchers', 'renderCopyWatchersObj' ), SFH_OBJECT_ARGS );
		} else {
			$parser->setFunctionHook( 'copywatchers', array( 'CopyWatchers', 'renderCopyWatchersNonObj' ) );
		}

		return true;
		
	}

	static function renderCopyWatchersNonObj (&$parser, $pagesToCopyWatchers='', $showOutput=false) {
		
		$pagesToCopyWatchers = explode(',', $pagesToCopyWatchers);
		
		if ( $showOutput == 'true' )
			$showOutput = true;
		
		return self::renderCopyWatchers( $parser, $pagesToCopyWatchers, $showOutput );
		
	}
	
	static function renderCopyWatchersObj ( &$parser, $frame, $args ) {
		
		$pagesToCopyWatchers = explode(',', $frame->expand( $args[0] ) );
	
		if ( isset( $args[1] ) && trim( $frame->expand( $args[1] ) ) == 'true' )
			$showOutput = true;
		else
			$showOutput = false;
	
		return self::renderCopyWatchers( $parser, $pagesToCopyWatchers, $showOutput );
	
	}
	
	static function renderCopyWatchers ( &$parser, $pagesToCopyArray, $showOutput ) {
		global $wgCanonicalNamespaceNames;

		$newWatchers = array();
		
		$output = "Copied watchers from:\n\n";
		
		foreach( $pagesToCopyArray as $page ) {
			
			$output .= "* $page";

			// returns Title object
			$titleObj = self::getNamespaceAndTitle( trim($page) );
			
			if ( $titleObj->isRedirect() ) {
				$redirectArticle = new Article( $titleObj );
				
				// FIXME: thought newFromRedirectRecurse() would find the ultimate page
				// but it doesn't appear to be doing that
				$titleObj = Title::newFromRedirectRecurse( $redirectArticle->getContent() );
				$output .= " (redirects to " . $titleObj->getFullText() . ")";
				
				// FIXME: Do this for MW 1.19+ ???
				// $wp = new WikiPage( $titleObj );
				// $titleObj = $wp->followRedirect();
				
				// FIXME: Do one of these for MW 1.21+ ???
				// WikiPage::followRedirect()
				// Content::getUltimateRedirectTarget()

			}
			
			$ns_num = $titleObj->getNamespace();
			$title  = $titleObj->getDBkey();			

			unset( $titleObj ); // prob not necessary since it will be reset shortly.
			
			$watchers = self::getPageWatchers( $ns_num, $title );
			$num_watchers = count($watchers);
			
			if ($num_watchers == 1)
				$output .= " (" . count($watchers) . " watcher)\n";
			else
				$output .= " (" . count($watchers) . " watchers)\n";

			foreach ( $watchers as $userID => $dummy ) {
				$newWatchers[$userID] = 0; // only care about $userID, and want unique.
			}

		}
		
		// add list of usernames as watchers to this Title
		foreach ($newWatchers as $userID => $dummy) {
			$u = User::newFromId($userID);
			$u->addWatch( $parser->getTitle() );
		}
		
		if ( $showOutput )
			return $output;
		else
			return "";
			
	}
	
	static function getNamespaceAndTitle ( $pageName ) {
	
		// defaults
		$ns_num = NS_MAIN;
		$title = $pageName;

		$colonPosition = strpos( $pageName, ':' ); // location of colon if exists
		
		// this won't test for a leading colon...but shouldn't use parser function that way anyway...
		if ( $colonPosition ) {
			$test_ns = self::getNamespaceNumber( 
				substr( $pageName, 0, $colonPosition )
			);
			
			// only reset $ns and $title if has colon, and pre-colon text actually is a namespace
			if ( $test_ns !== false ) {
				$ns_num = $test_ns;
				$title = substr( $pageName, $colonPosition+1 );
			}
		}
		
		return Title::makeTitle( $ns_num, $title );
		//return (object)array("ns_num"=>$ns_num, "title"=>$title);
	
	}
	
	// returns number of namespace (can be zero) or false. Use ===.
	static function getNamespaceNumber ( $ns ) {
		global $wgCanonicalNamespaceNames;
		
		foreach ( $wgCanonicalNamespaceNames as $i => $text ) {
			if (preg_match("/$ns/i", $text)) {
				return $i;
			}
		}
	
		return false; // if $ns not found above, does not exist
	}
	
	static function getPageWatchers ($ns, $title) {
		
		// code adapted from Extension:WhoIsWatching
		$dbr = wfGetDB( DB_SLAVE );
		$watchingUserIDs = array();
		
		
		$res = $dbr->select(
			'watchlist',
			'wl_user', 
			array('wl_namespace'=>$ns, 'wl_title'=>$title),
			__METHOD__
		);
		foreach ( $res as $row ) {
			$watchingUserIDs[ $row->wl_user ] = 0; // only care about the user ID, and want unique
		}

		return $watchingUserIDs;
			
	}
}