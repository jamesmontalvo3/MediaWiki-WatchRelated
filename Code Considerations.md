## Manual:Hooks/ArticleSave
Use

'''Define Function:

public static function onArticleSave( &$article, &$user, &$text, &$summary,
$minor, $watchthis, $sectionanchor, &$flags, &$status ) { ... }


Attach hook:

$wgHooks['ArticleSave'][] = 'MyExtensionHooks::onArticleSave';


Called from: WikiPage.php
