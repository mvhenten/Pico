<?php
/**
 * Application/plugin/ItemThumbnail/hook.php
 *
 * @author Matthijs van Henten <matthijs@ischen.nl>
 * @package default
 */


@list( , $controller, $action, $item_id ) = $request->pathParts;
?>

<?php if ( $start ):
    Nano_Autoloader::registerNamespace( 'Navtree', dirname(__FILE__)  );
endif; ?>

<?php if ( $end ): ?>
<?php endif; ?>
