<?php

require_once __DIR__ . '/database/db.class.php';

function sendJSONandExit($message)
{
    header( 'Content-type:application/json;charset=utf-8');
    echo json_encode($message);
    flush();
    exit(0);
}


$message=[];

$polja = array();
$vrjednosti = array();
$id_restaurant = $_POST['id'];

array_push($polja, 'rating');
array_push($vrjednosti, intval( $_POST['rating'] ) );

if( $_POST['feedback'] !== '' ){
    array_push($polja, 'feedback');
    array_push($vrjednosti, $_POST['feedback'] );
}

$upit = 'UPDATE spiza_orders SET ';
$val = '';
$ex = array();

for( $i = 0; $i < count($polja); ++$i)
{
    $upit .=  $polja[$i] . '=:val'. $i;
    $ex[ 'val' . $i ]= $vrjednosti[$i];
    if( $i < count($polja) - 1 )
        $upit = $upit . ', ';

}
$upit .= ' WHERE id_order=:val10';

$ex['val10'] = intval($_POST['id']);

if( intval( $_POST['rating'] ) > 0 && intval( $_POST['rating'] ) < 11 ){
    try
    {
        $db=DB::getConnection();
        $st=$db->prepare( $upit );
        $st->execute( $ex );
    }
    catch( PDOException $e ) { 
        $message['greska'] = 'Greška u bazi!';
        sendJSONandExit($e);
    }
    $message['rezultat'] = 'Changes commited!';
    sendJSONandExit($message);
}

else{
    $message['greska'] = 'Nije uspjelo! Ocjena mora biti između 1 i 10!';
    sendJSONandExit($message);
}



?>