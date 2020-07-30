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




if( !isset($_POST['id']) )
    exit(1);

try
		{
            $db=DB::getConnection();
            $st=$db->prepare( 'UPDATE spiza_food SET in_offering=:val WHERE id_food=:val2' );
            $st->execute( [ 'val' => 0, 'val2' => intval( $_POST['id'] ) ] );
		}
        catch( PDOException $e ) { 
            $message['greska'] = 'Greška u bazi!';echo $e;
            sendJSONandExit($e);
            exit(2);
         }
    $message['rezultat'] = 'Changes commited!';
    sendJSONandExit($message);



?>