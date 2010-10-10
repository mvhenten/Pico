<?php
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', "true");
ini_set('display_warnings', "true");
//ini_set('upload_max_filesize', '16M');
//ini_set('post_max_size', '16M');

$old_db = 'pico_frs_old';
$new_db = 'pico_frs';


$types = array(
    'image' => 1,
    'page'  => 2,
    'label' => 3,
    'nav'   => 4,
    'cat'   => 5
);

$dba = new PDO('mysql:host=127.0.0.1;dbname=' . $old_db, 'pico', 'pico' );
$dbb = new PDO('mysql:host=127.0.0.1;dbname=' . $new_db, 'pico', 'pico' );



function db_query( $db, $query, $values ){
    $sth = $db->prepare($query);

    if( false == $sth ){
        $error = print_r( $errorInfo(), true );
        throw new Exception( 'Query failed: PDOStatement::errorCode():' . $error );
    }
    else if( !$sth->execute( array_values($values) ) ){
       $error = print_r( $sth->errorInfo(), true );
       throw new Exception( 'Query failed: PDOStatement::errorCode():' . $error );
    }
}


$labels_ids = array();
$sth = $dba->query("SELECT * FROM items WHERE type = 'label'");

//var_dump( $dba->errorInfo() );
while( $item = $sth->fetchObject() ){
    $sql = 'INSERT INTO `item` (name, type, visible, inserted) VALUES (?, 3, 1, NOW())';

    db_query( $dbb, $sql, array($item->title) );

    $id = $dbb->lastInsertId();

    $labels_ids[intval($item->id)] = $id;


    if( strlen( $item->html ) > 0 ){
        $sql = 'INSERT INTO `item_content` (item_id, value) VALUES (?,?)';
        db_query( $dbb, $sql, array($id, $qr->html));
    }
}

$sth = $dba->query("SELECT *, items.id  AS item_id FROM items LEFT JOIN images ON imageFk = items.id WHERE type = 'image' ORDER BY items.id");

while( $item = $sth->fetchObject() ){
    $sql = 'INSERT INTO `item` (name, type, visible, inserted) VALUES (?, 1, 1, NOW())';

    db_query( $dbb, $sql, array($item->title) );

    $id = $dbb->lastInsertId();

    $sql = '
        INSERT INTO image_data
        (image_id, size, width, height, mime, filename, data, type)
        VALUES
        (?, ?, ?, ?, ?, ?, ?, 1 )
    ';

    $size = strlen($item->image);
    $mime = $item->mimetype;

    $gd = imagecreatefromstring( $item->image );

    $width = imagesx( $gd );
    $height = imagesy( $gd );

    db_query( $dbb, $sql, array( $id, $size, $width, $height, $mime, $item->filename, $item->image ));

    $lsth = $dba->query('SELECT labelFk, orderK FROM img2label WHERE itemFk = ' . $item->item_id );
    $labels = array(); while($qr = $lsth->fetchObject()) $labels[intval($qr->labelFk)] = $qr->orderK;


    foreach( $labels as $label_id => $priority ){
        if( isset( $labels_ids[$label_id])){
            $sql = 'INSERT INTO image_label (image_id, label_id, priority)
            VALUES (?, ?, ?)';

            db_query($dbb, $sql, array($id, $labels_ids[$label_id], $priority));
        }
    }
}

$sth = $dba->query("SELECT * FROM items WHERE type = 'page'");
while( $qr = $sth->fetchObject() ){
    $item = array(
        'name'  => $qr->title,
        'type'  => $types[$qr->type],
        'visible'   => 1
    );

    $sql = 'INSERT INTO `item` (name, type, visible, inserted) VALUES (?, 2, 1, NOW())';
    db_query( $dbb, $sql, array($qr->title) );

    $id = $dbb->lastInsertId();

    $sql = 'INSERT INTO `item_content` (item_id, value) VALUES (?,?)';

    db_query( $dbb, $sql, array($id, $qr->html));
}
