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

if( $_POST['name'] !== '' ){
    array_push($polja, 'name');
    array_push($vrjednosti, $_POST['name']);
}
if( $_POST['description'] !== '' ){
    array_push($polja, 'description');
    array_push($vrjednosti, $_POST['description'] );
}
if( $_POST['address'] !== '' ){
    array_push($polja, 'address');
    array_push($vrjednosti, $_POST['address']);
}

$upit = 'UPDATE spiza_restaurants SET ';
$val = '';
$ex = array();

for( $i = 0; $i < count($polja); ++$i)
{
    $upit .=  $polja[$i] . '=:val'. $i;
    $ex[ 'val' . $i ]= $vrjednosti[$i];
    if( $i < count($polja) - 1 )
        $upit = $upit . ', ';

}
$upit .= ' WHERE id_restaurant=:val10';

$ex['val10'] = intval($_POST['id']);

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



?>