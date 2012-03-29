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
    Nano_Autoloader::registerNamespace( 'Builder', dirname(__FILE__) . '/lib'  );
    $this->router->prepend( '/admin/builder(/\w+)?(/\d+)?', 'Builder_View' );
}
?>

<?php if ( $end ): ?>
    <?php $this->template->block( 'main-navigation' ); ?>
        <li><a href="/admin/builder">builder</a></li>
    <?php $this->template->endblock('main-navigation') ?>
<?php endif; ?>
