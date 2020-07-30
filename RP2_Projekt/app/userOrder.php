<?php

require_once __DIR__ . '/database/db.class.php';

//print_r($_GET['id_food']);

function sendJSONandExit($message)
{
    header( 'Content-type:application/json;charset=utf-8');
    echo json_encode($message);
    flush();
    exit(0);
}


$message = [];

$id_user = $_GET['id_user'];
$id_restaurant = $_GET['id_restaurant'];
$active = $_GET['active'];
$price_total = $_GET['price_total'];
$discount = $_GET['discount'];
$note = $_GET['note'];
$address = $_GET['address'];
$id_food = $_GET['id_food'];
$quantity = $_GET['quantity'];

//echo $id_user . ', ' . $id_restaurant . ', ' . $active . ', ' . $price_total . ', ' . $discount . ', ' . $note;

try {
    $db=DB::getConnection();

    $db->beginTransaction();

    $st=$db->prepare( 'INSERT INTO spiza_orders(id_user, id_restaurant, active, price_total, discount, note, address, rating, thumbs_up, thumbs_down) VALUES (:id_user, :id_restaurant, :active, :price_total, :discount, :note, :address, :rating, :up, :down)' );
    $st->execute( array( 'id_user' => intval( $id_user ),  'id_restaurant' => intval( $id_restaurant ), 'active' => intval( $active ), 'price_total' => floatval( $price_total ), 'discount' => floatval( $discount ), 'note' => $note, 'address' => $address, 'rating' => 0, 'up' => 0, 'down' => 0 ) );
    
        
    $lastInsertedID = $db->lastInsertId();

    for( $i = 0; $i < sizeof( $id_food ); ++$i ){
        $st2=$db->prepare( 'INSERT INTO spiza_contains(id_order, id_food, quantity) VALUES (:id_order, :id_food, :quantity)' );
        $st2->execute( array( 'id_order' => $lastInsertedID,  'id_food' => $id_food[$i], 'quantity' => $quantity[$i]) );
    }

    $db->commit();
}
catch( PDOException $e ) { 
    //echo $e->getMessage();
    $message['greska'] = 'Greška u bazi!';
    $db->rollBack();
    sendJSONandExit($e);
}

$message['rezultat'] = 'Changes commited!';
sendJSONandExit($message);

?>