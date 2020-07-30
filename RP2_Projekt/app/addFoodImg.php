<?php

require_once __DIR__ . '/database/db.class.php';


function sendJSONandExit($message)
{
    header( 'Content-type:application/json;charset=utf-8');
    echo json_encode($message);
    flush();
    exit(0);
}
debug();

$filename = $_FILES['file']['name'];
$tmp = explode( '.', $filename);

// Ime slike će biti [id_food].jpg/.jpeg/.png
$location = 'images/food/' . $_POST['id_food']. '.'.end($tmp);
$uploadOk = 1;
$imgType = pathinfo( $location, PATHINFO_EXTENSION );

$valid_extesnsions = array( "jpg", "jpeg", "png" );
if( !in_array( strtolower($imgType) , $valid_extesnsions ) )
    $uploadOk = 0;

if( $uploadOk === 0)
    echo 'ERROR: upload!';
else{
    if(move_uploaded_file($_FILES['file']['tmp_name'],$location)){
        echo $location;
     }else{
        echo 'ERROR moveing image!';
    }
}



/*
if( !isset($_post['name']) || !isset($_post['price']) || !isset($_post['description']) ||
    !isset($_post['waitingTime']) || !isset($_post['id_restaurant']) )
{
    $message['greska'] = 'Parameters missing!';
    sendJSONandExit($message);
    exit(1);
}


try
		{
            $db=DB::getConnection();
            $st=$db->prepare( 'INSERT INTO spiza_food(name, description, waiting_time, id_restaurant, price, in_offering) VALUES (:name, :description, :waiting_time, :id_restaurant, :price, :in_offering)' );
            $st->execute( array( 'name' => $_post['name'],  'description' => $_post['description'], 'waiting_time' => intval( $_post['waitingTime'] ), 'id_restaurant' => intval( $_post['id_restaurant'] ), 'price' => intval( $_post['price'] ), 'in_offering' => 1) );		
        }
        catch( PDOException $e ) { 
            $message['greska'] = 'Greška u bazi!';echo $e;
            sendJSONandExit($e);
            exit(2);
         }
    $message['rezultat'] = 'Added food ' . $_post['name'] . '!';
    sendJSONandExit($message);
*/
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