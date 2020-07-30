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

if( !isset( $_GET['timestamp'] ) )
    sendJSONandExit( $_GET);//['error' => 'Nije postavljen timestamp!'] );



$clientLastUpdate = (int) $_GET['timestamp'];
$dbLastUpdate = -1;

$db = DB::getConnection();

while( $dbLastUpdate <= $clientLastUpdate ) 
{
    try{
        $st = $db->prepare( 'SELECT MAX(lastchange_time) as maxUpdate FROM spiza_orders WHERE active=:a' );
        $st->execute( [ 'a' => 2 ] );

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
    $st = $db->prepare( 'SELECT * FROM spiza_orders WHERE active=:a' );
    $st->execute( [ 'a'=>2 ] );
}
catch( PDOException $e ) { 
    $message['greska'] = ' Getting order list in database!';
    sendJSONandExit($e);
}

$msg = [];
$msg['timestamp'] = $dbLastUpdate;
$msg['id_order'] = [];
$msg['restaurant'] = [];
$msg['user'] = [];
$msg['address'] = [];
$msg['food'] = [];
$msg['price_total'] = [];
$msg['discount'] = [];
$msg['note'] = [];

while( $row = $st->fetch() )
{
    $id_order=$row['id_order'];
    $id_user=$row['id_user'];
    $id_restorana=$row['id_restaurant']; 

    // traženje imena restorana
    try
	{
        $db=DB::getConnection();
        $st2=$db->prepare('SELECT * FROM spiza_restaurants WHERE id_restaurant=:rest');
        $st2->execute(['rest'=>$id_restorana]);
	}
    catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
    if ($st2->rowCount()!==1)
        $restaurant='';
    else{
        $row2=$st2->fetch();
        $restaurant=$row2['name'];
    }

    // ime korisnika koji je naručio
    try
    {
        $db=DB::getConnection();
        $st3=$db->prepare('SELECT * FROM spiza_users WHERE id_user=:user');
        $st3->execute(['user'=>$id_user]);
    }
    catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
    if ($st3->rowCount()!==1)
        $user='';
    else{
        $row3=$st3->fetch();
        $user=$row3['username'];
    }


    // dohvaćanje popisa hrane (ime i kolicina)
    $h='';
    try{
        $db = DB::getConnection();
        $st4 = $db->prepare( 'SELECT * FROM spiza_contains WHERE id_order=:id_order');
        $st4->execute( [ 'id_order' => $row['id_order'] ] );
    }
    catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
    while($row4=$st4->fetch()){
        // da bi iz id_food dobili ime jela i količinu
        try
		{
            $db=DB::getConnection();
            $st5=$db->prepare('SELECT * FROM spiza_food WHERE id_food=:hrana');
            $st5->execute(['hrana'=>$row4['id_food']]);
		}
        catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
        $row5=$st5->fetch();
        $h .= $row5['name'] . ' (' . $row4['quantity'] . ')<br>';
    }

    $msg['id_order'][] = $id_order;
    $msg['restaurant'][] = $restaurant;
    $msg['user'][] = $user;
    $msg['address'][] = $row['address'];
    $msg['food'][] = $h;
    $msg['price_total'][] = $row['price_total'];
    $msg['discount'][] = $row['discount'];
    $msg['note'][] = $row['note'];
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