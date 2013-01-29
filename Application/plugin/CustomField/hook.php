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

    Nano_Autoloader::registerNamespace( 'CustomField', dirname(__FILE__)  );
    $this->router->prepend( '/admin/plugin/customfield/\w+/\d+?', 'CustomField_Plugin' );

    if ( $action && $action == 'delete' ) {

    }

endif; ?>

<?php if ( $end ): ?>
    <?php if ( $action == 'edit' && in_array( $controller, array('image', 'label' ) ) ): ?>
    <?php $this->template->block( 'head' ); ?>
    <?php $this->template->endBlock( 'head' ); ?>

    <?php $this->template->block('sidebar'); ?>
    <?php $item = $this->template->item ?>
        <?php if( $config->custom_field ): ?>
        <ul class="verticalnav">
        <li><h4>Custom fields</h4></li>
            <li>
                <form action="/admin/plugin/customfield/save/<?php echo $item_id ?>" method="post" enctype="multipart/form-data">
                    <fieldset style="padding:5px">
                    <?php foreach( $config->custom_field->fields as $name => $title ): ?>
                        <div>
                            <label for="custom_field_<?php echo $name ?>"><?php echo $title ?></label>
                            <input class="input-text"
                                id="custom_field_<?php echo $name ?>"
                                name="custom_field[<?php echo $name ?>]"
                                type="text"
                                value="<?php echo $this->template->customfield( $item, $name ) ?>" />                            
                        </div>
                    <?php endforeach; ?>                                        
                    </fieldset>
                <div class="submit-wrapper">
                    <input type="submit" value="Save" name="save-custom-field" class="input-submit">		
        	</div>
                </form>
            </li>
        </ul>
        <?php endif; ?>
    <?php $this->template->endblock('sidebar'); ?>
    <?php endif; ?>
<?php endif; ?>
