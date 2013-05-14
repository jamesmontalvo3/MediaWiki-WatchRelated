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
	static public function getWatchers ($parser, $pages_with_watchers) {
		
		$titleObj = $parser->getTitle(); //
		$userObj  = $parser->getUser(); //
		$userObj->addWatch( $someTitle ); //
		
		
		$pages_with_watchers = explode(',', $pages_with_watchers);
		
		foreach( $pages_with_watchers as $page ) {
		
			
		
		}
		
	}
}