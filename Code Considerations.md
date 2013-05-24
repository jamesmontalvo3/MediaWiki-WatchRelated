## Manual:Hooks/ArticleSave
Use this for MW 1.20 and earlier

####Define Function:
public static function onArticleSave( &$article, &$user, &$text, &$summary,
$minor, $watchthis, $sectionanchor, &$flags, &$status ) { ... }

####Attach hook:
$wgHooks['ArticleSave'][] = 'MyExtensionHooks::onArticleSave';


## Manual:Hooks/PageContentSave

Use this for MW 1.21 and later

####Define function:
public static function onPageContentSave( $wikiPage, $user, $content, $summary, 
$isMinor, $isWatch, $section ) { ... }


####Attach Hook:
$wgHooks['PageContentSave'][] = 'MyExtensionHooks::onPageContentSave';

