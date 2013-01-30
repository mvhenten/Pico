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

    Nano_Autoloader::registerNamespace( 'ItemThumb', dirname(__FILE__)  );
$this->router->prepend( '/admin/plugin/thumb/\w+(/\d+)?', 'ItemThumb_Plugin' );

if ( $action && $action == 'delete' ) {
    $image_data = new Pico_Model_ImageData();
    $image_data->delete( array( 'image_id' => $item_id ) );
}

endif; ?>

<?php if ( $end ): ?>
    <?php if ( $action == 'edit' && in_array( $controller, array('page', 'label', 'video')) ): ?>
    <?php $this->template->block( 'head' ); ?>
    <style>
    <?php readfile( dirname(__FILE__) .  '/style.css' ); ?>
    </style>
    <?php $this->template->endBlock( 'head' ); ?>
    <?php $this->template->block('sidebar'); ?>
    <?php $item = $this->template->item ?>
    <ul class="verticalnav">
    <li><h4><?php echo ucfirst($controller) ?> thumbnail</h4></li>
        <li>
            <div class="thumbnail-holder">
                <?php if ( isset($item->appendix->thumbnail) ): ?>
                <img src="/image/original/<?php echo $item->id ?>?v=<?php echo $item->appendix->thumbnail ?>" />
                <?php else: ?>
                    <em>no image found</em>
                <?php endif; ?>
            </div>
        </li>
        <li>
            <form action="/admin/plugin/thumb/upload/<?php echo $item->id ?>" method="post" enctype="multipart/form-data">
                <div class="fakefile">
                    <?php if ( isset($item->appendix->thumbnail) ): ?>
                    <a class="button" href="/admin/plugin/thumb/delete/<?php echo $item->id ?>" title="delete thumbnail">delete</a>
                    <label for="thumbnail-file"><span>upload...</span>
                    <?php else: ?>
                    <label for="thumbnail-file"><span>upload image</span>
                    <?php endif; ?>
                        <input onchange="$(this.form).submit();$(this).prev('span').html(this.value);$('#thumb-submit').show()" class="hidden" id="thumbnail-file" type="file" name="image" />
                    </label>
                </div>
                <div id="thumb-submit" class="submit-wrapper">
                    <input class="input-submit" type="submit" id="thumb-save" value="save" />
                </div>
                <script>$('#thumb-submit').hide()</script>
            </form>
        </li>
    </ul>
    <?php $this->template->endblock('sidebar'); ?>
    <?php endif; ?>
<?php endif; ?>
