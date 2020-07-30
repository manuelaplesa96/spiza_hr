<?php

require_once __DIR__ . '/database/db.class.php';

function sendJSONandExit($message)
{// Kao izlaz skripte pošalji $message u JSON formatu i
    // prekini izvođenje.
    header( 'Content-type:application/json;charset=utf-8');
    echo json_encode($message);
    flush();
    exit(0);
}

$msg = [];

if( !isset( $_GET['id'] ) )
    sendJSONandExit( $_GET);//['error' => 'Nije postavljen id!'] );




$db = DB::getConnection();


try{
    $st = $db->prepare( 'SELECT *  FROM spiza_orders WHERE active=:a AND id_deliverer=:val' );
    $st->execute( [ 'a' => 3 , 'val' => $_GET['id']] );

    if( intval($st->rowCount()) === 0)  //slobodan je za dostave
        $msg['rezultat'] = 1;
    else    // nije slobodan za dostave
        $msg['rezultat'] = 0;


}
catch( PDOException $e ) { 
    $message['greska'] = ' Getting max in database!';
    sendJSONandExit($e);
}

sendJSONandExit( $msg );

