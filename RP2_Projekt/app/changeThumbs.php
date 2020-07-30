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
$id_order = $_POST['id'];
$thumbs = $_POST['thumbs'] ;
if( $_POST['vrsta'] == 'gori' ) $vrsta = 'thumbs_up';
else $vrsta = 'thumbs_down';

try
		{
            $db=DB::getConnection();
            $st=$db->prepare( 'UPDATE spiza_orders SET ' . $vrsta . '=:val WHERE id_order=:ord');
            $st->execute( ['val'=>$thumbs, 'ord'=>$id_order] );
		}
        catch( PDOException $e ) { 
            $message['greska'] = 'Greška u bazi!';
            sendJSONandExit($e);
         }
    $message['rezultat'] = 'Changes commited!';
    sendJSONandExit($message);



?>