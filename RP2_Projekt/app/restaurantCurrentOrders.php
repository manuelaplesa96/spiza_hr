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

//debug();

if( !isset( $_GET['timestamp'] ) )
    sendJSONandExit( $_GET);//['error' => 'Nije postavljen timestamp!'] );
elseif( !isset( $_GET['id_restaurant'] ) )
    sendJSONandExit( ['error' => 'Nije postavljen id_restaurant!'] );

$clientLastUpdate = (int) $_GET['timestamp'];
$dbLastUpdate = -1;

$db = DB::getConnection();

while( $dbLastUpdate <= $clientLastUpdate ) 
{
    try{
        $st = $db->prepare( 'SELECT MAX(lastchange_time) as maxUpdate FROM spiza_orders WHERE id_restaurant=:val' );
        $st->execute( [ 'val' => $_GET['id_restaurant'] ] );

        $row = $st->fetch();
        $dbLastUpdate = strtotime( $row['maxUpdate'] );

        if( is_null($row['maxUpdate']) )
        {
            $dbLastUpdate = 0;
            sendJSONandExit( [ 'nema' => 1 ] );
        }

        usleep( 10000 );    //  10ms
    }
    catch( PDOException $e ) { 
        $message['greska'] = ' Getting max in database!';
        sendJSONandExit($e);
     }
}

try{
    $st = $db->prepare( 'SELECT * FROM spiza_orders WHERE id_restaurant=:val AND active <> 0 AND active <> -1 AND active <> -2' );
    $st->execute( [ 'val' => $_GET['id_restaurant'] ] );
}
catch( PDOException $e ) { 
    $message['greska'] = ' Getting order list in database!';
    sendJSONandExit($e);
}

$msg = [];
$msg['timestamp'] = $dbLastUpdate;
$msg['id_order'] = [];
$msg['id_user'] = [];
$msg['active'] = [];
$msg['order_time '] = [];
$msg['price_total'] = [];
$msg['discount'] = [];
$msg['note'] = [];
$msg['contains'] = [];


while( $row = $st->fetch() )
{
    $msg['id_order'][] = $row['id_order'];
    $msg['id_user'][] = $row['id_user'];
    $msg['order_time'][] = $row['order_time'];
    $msg['active'][] = $row['active'];
    $msg['price_total'][] = $row['price_total'];
    $msg['discount'][] = $row['discount'];
    $msg['note'][] = $row['note'];

    try{
        $st2 = $db->prepare( 'SELECT name, quantity  FROM spiza_food, spiza_contains WHERE spiza_contains.id_order=:val AND spiza_food.id_food=spiza_contains.id_food' );
        $st2->execute( [ 'val' => $row['id_order'] ] );
    }
    catch( PDOException $e ) { 
        $message['greska'] = ' Getting contents list in database!';
        sendJSONandExit($e);
    }

    $temp['name']=[];
    $temp['quantity']=[];
    while( $row2 = $st2->fetch() )
    {
        $temp['name'][] = $row2['name'];
        $temp['quantity'][] = $row2['quantity'];
    }
    $msg['contains'][] = $temp;
    


}


sendJSONandExit( $msg );


function debug()
{
    echo '<pre>';
    if( isset( $_POST ) )
    {
        echo '$_POST=';
        print_r( $_POST );
    }
    if( isset( $_GET ) )
    {
        echo '$_GET=';
        print_r( $_GET );
    }
    if( isset( $_FILES ) )
    {
        echo '$_FILES=';
        print_r( $_FILES );
    }

    echo '</pre>';
}



?>