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


if( !isset($_GET['order_id'])||!isset($_GET['status']) )
    exit(1);

if( intval($_GET['status']) === 2 || intval($_GET['status']) === -1)//restoran postavlja i vrijeme dostave, vrijeme potrebno za pripremu hrane se postavlja u delivery_time, koristimo date("Y-m-d H:i:s", 1388516401); za pretvaranje pa će dotavljač obrnuto
{// inverzna funkcija za to je strtotime()
    $vrijeme = $_GET['vrijeme'];
    if(intval($_GET['vrijeme']) === -1)
        $vrijeme = 1;
    try{
        $db=DB::getConnection();
        $st=$db->prepare( 'UPDATE spiza_orders SET active=:val, delivery_time=:val1  WHERE id_order=:val2 ' );
        $st->execute( [ 'val' => intval($_GET['status']), 'val1'=> date("Y-m-d H:i:s", $vrijeme), 'val2' => $_GET['order_id']] );
    }catch( PDOException $e ) { 
        $message['greska'] = 'Greška u bazi!';echo $e;
        sendJSONandExit($e);
        exit(2);
    }
}
if( intval($_GET['status']) === 3 )//dostavljač postavlja i vrijeme dostave, vrijeme potrebno za dostavu hrane se postavlja u delivery_time, 
{// inverzna funkcija za to je strtotime()
    try{
        $db=DB::getConnection();

        $db->beginTransaction();    //   ZAPOČINJE TRANSAKCIJA

        $st4 = $db->prepare( 'SELECT * FROM spiza_orders WHERE id_order=:id_order');
        $st4->execute( [ 'id_order' => $_GET['order_id'] ] );

        $row1 = $st4->fetch();

        $st=$db->prepare( 'UPDATE spiza_orders SET active=:val, delivery_time=:val1, id_deliverer=:val3  WHERE id_order=:val2 ' );
        $st->execute( [ 'val' => intval($_GET['status']), 'val1'=> date("Y-m-d H:i:s", $_GET['vrijeme']+strtotime($row1['delivery_time'])), 'val2' => $_GET['order_id'], 'val3' => $_GET['id_deliverer'] ] );


        $db->commit();

    }catch( PDOException $e ) { 
        $message['greska'] = 'Greška u bazi!';
        $db->rollBack();
        echo $e;
        sendJSONandExit($e);
        exit(2);
    }
}/*
else{// ne koristi se jos, bez vremena
    try{
        $db=DB::getConnection();
        $st=$db->prepare( 'UPDATE spiza_orders SET active=:val WHERE id_order=:val2' );
        $st->execute( [ 'val' => intval($_GET['status']), 'val2' => $_GET['order_id']] );
    }catch( PDOException $e ) { 
        $message['greska'] = 'Greška u bazi!';echo $e;
        sendJSONandExit($e);
        exit(2);
    }
}*/


$message['rezultat'] = 'Changes commited!';
sendJSONandExit($message);




?>