<?php
@list( , $controller, $action, $item_id ) = $request->pathParts;
?>

<?php if ( $start ):

    Nano_Autoloader::registerNamespace( 'Wufoo', dirname(__FILE__)  );
    $this->router->prepend( '/admin/wufoo/(\w+)/(\d+)?', 'Wufoo_Plugin' );

endif; ?>

<?php if ( $end ): ?>
    <?php if ( $action == 'edit' && in_array( $controller, array('page')) ): ?>
    <?php $this->template->block('sidebar'); ?>
    <?php $item = $this->template->item ?>
    <form method="post" action="/admin/wufoo/save/<?php echo $item->id ?>">
    <ul class="verticalnav">
    <li><h4>Wufoo Form</h4></li>
        <li style="padding:0.5em;">
            <strong>Copy paste the form-embed code here</strong>
            <textarea style="width:90%; height:3em;" name="embed_code"><?php 
            if( isset($item->appendix->wufoo_form) ):
                echo htmlentities($item->appendix->wufoo_form);
            endif;
            ?></textarea>
        </li>
        <li style="padding:0.5em;">
             <input class="input-submit" type="submit" name="delete" value="clear form" />
             <input class="input-submit" type="submit" name="save" value="save form" />
        </li>
    </ul>
    </form>
    <?php $this->template->endblock('sidebar'); ?>
    <?php endif; ?>
<?php endif; ?>
