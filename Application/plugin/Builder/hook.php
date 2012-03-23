<?php
/**
 * Application/plugin/ItemThumbnail/hook.php
 *
 * @author Matthijs van Henten <matthijs@ischen.nl>
 * @package default
 */


@list( , $controller, $action, $item_id ) = $request->pathParts;
?>

<?php
if ( $start ) {
    Nano_Autoloader::registerNamespace( 'Builder', dirname(__FILE__)  );
    $this->router->prepend( '/admin/plugin/thumb/\w+(/\d+)?', 'ItemThumb_Plugin' );
}
?>

<?php if ( $end ): ?>
<?php endif; ?>
