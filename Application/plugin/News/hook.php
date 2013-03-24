<?php
/**
 * Application/plugin/ItemThumbnail/hook.php
 *
 * @author Matthijs van Henten <matthijs@ischen.nl>
 * @package default
 */


@list( , $controller, $action ) = $request->pathParts;
?>

<?php if ( $start ):

    Nano_Autoloader::registerNamespace( 'News', dirname(__FILE__)  );
$this->router->prepend( '/admin/news(/\w+)?(/\d+)?', 'News_Plugin' );

endif; ?>

<?php if ( $end && ! $request->isPost() ): ?>
    <?php $this->template->block('module-navigation') ?>
	<li><a href="/admin/news">news</a></li>
    <?php $this->template->endblock('module-navigation') ?>

    <?php $this->template->block( 'head' ); ?>
    <?php $this->template->endBlock( 'head' ); ?>

    <?php $this->template->block('sidebar'); ?>
    <?php $this->template->endblock('sidebar'); ?>
<?php endif; ?>
