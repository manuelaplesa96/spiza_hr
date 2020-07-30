<?php

require_once __DIR__ . '/database/db.class.php';


function sendJSONandExit($message)
{
    header( 'Content-type:application/json;charset=utf-8');
    echo json_encode($message);
    flush();
    exit(0);
}
//debug();

$message=[];

if( !isset($_POST['name']) || !isset($_POST['price']) || !isset($_POST['description']) ||
    !isset($_POST['waitingTime']) || !isset($_POST['id_restaurant']) )
{
    echo 'ERROR: Parameters missing!';
    exit(1);
}

//           Priprema za spremanje slike

$filename = $_FILES['file']['name'];
$tmp = explode( '.', $filename);

// Ime slike će biti [id_food].jpg/.jpeg/.png
$location = 'images/food/'. $filename;          //'images/food/' . $_POST['id_food']. '.'.end($tmp);
$uploadOk = 1;
$imgType = pathinfo( $location, PATHINFO_EXTENSION );

$valid_extesnsions = array( "jpg", "jpeg", "png" );
if( !in_array( strtolower($imgType) , $valid_extesnsions ) )
    $uploadOk = 0;

if( $uploadOk === 0){
    echo 'ERROR: upload!';
    exit(3);
}



try{    // Pomoću transakcije ubacujemo u bazu info o hrani pa postavljamo ime slike
        // id_food.___ pa ubacujemo path u bazu, u slučaju da se ne uspiju u bacit
        // sve promjene u bazi se poništavaju
        $db=DB::getConnection();

        $db->beginTransaction();

        $st=$db->prepare( 'INSERT INTO spiza_food(name, description, waiting_time, id_restaurant, price, in_offering)  VALUES (:name, :description, :waiting_time, :id_restaurant, :price, :in_offering)' );
        $st->execute( array( 'name' => $_POST['name'],  'description' => $_POST['description'], 'waiting_time' => intval( $_POST['waitingTime'] ), 'id_restaurant' => intval( $_POST['id_restaurant'] ), 'price' => intval( $_POST['price'] ), 'in_offering' => 1) );		
        
        $lastInsertedID = $db->lastInsertId();

        $location = 'images/food/' . $lastInsertedID . '.'.end($tmp);
        $st2=$db->prepare( 'UPDATE spiza_food SET image_path=:val WHERE id_food=:val2' );
        $st2->execute( array(  'val' => '/app/' . $location , 'val2' =>  $lastInsertedID ) );		

        $db->commit();
    }
    catch( PDOException $e ) { 
        $message['greska'] = 'Greška u bazi!';echo $e;
        $db->rollBack();
        exit(2);
    }

    //  Premještamo preimenovanu sliku u folder za slike 
if( move_uploaded_file($_FILES['file']['tmp_name'],$location) ){
    echo 'Food and image added for ' . $_POST['name'] . '.';
    return;
}   // U slučaju da premještanje nije uspjelo poništavamo unos hrane u bazu
else{
    $st=$db->prepare( 'DELETE FROM spiza_food WHERE id_food=:val' );
    $st->execute( array( 'val' => $lastInsertedID ) );		

    echo 'ERROR: Moveing image! Changes not applied!';
}




//  -------------------------------------------------------------------

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