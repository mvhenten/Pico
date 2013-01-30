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

    Nano_Autoloader::registerNamespace( 'Video', dirname(__FILE__)  );
$this->router->prepend( '/admin/video(/\w+)?(/\d+)?', 'Video_Plugin' );


endif; ?>

<?php if ( $end ): ?>
    <?php $this->template->block('module-navigation') ?>
	<li><a href="/admin/video">video</a></li>
    <?php $this->template->endblock('module-navigation') ?>

    <?php $this->template->block( 'head' ); ?>
    <?php $this->template->endBlock( 'head' ); ?>

    <?php $this->template->block('sidebar'); ?>
    <?php $this->template->endblock('sidebar'); ?>
<?php endif; ?>
