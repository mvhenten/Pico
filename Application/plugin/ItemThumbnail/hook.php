<?php
if( $start ):

    Nano_Autoloader::registerNamespace( 'ItemThumb', dirname(__FILE__)  );
    $this->router->addRoute( '/admin/plugin/thumb/\w+(/\d+)?', 'ItemThumb_Plugin' );

endif;
?>
<?php if( $end ): ?>
<?php $this->template->block('sidebar'); ?>
<div id="vertical-nav-2" class="fifth">
    TODO
</div>
<?php $this->template->endblock('sidebar'); ?>
<?php endif; ?>
