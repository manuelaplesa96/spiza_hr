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

$id_user = $_POST['id_user'];

try
		{
            $db=DB::getConnection();
            $st=$db->prepare( 'SELECT COUNT(*) AS count FROM spiza_orders WHERE id_user=:user and active=:stat' );
            $st->execute( ['user'=>$id_user, 'stat'=>0] );
		}
        catch( PDOException $e ) { 
            $message['greska'] = 'Greška u bazi!';
            sendJSONandExit($e);
         }
        $row = $st->fetch();
    $message['rezultat'] = intval($row['count']);
    sendJSONandExit($message);



?>