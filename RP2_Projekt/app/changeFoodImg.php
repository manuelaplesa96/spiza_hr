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

if( !isset($_FILES['file']) || !isset($_POST['id_food']) )
{
    echo 'ERROR: Parameters missing!';
    exit(1);
}

//           Priprema za spremanje slike

$filename = $_FILES['file']['name'];
$tmp = explode( '.', $filename);

// Ime slike će biti [id_food].jpg/.jpeg/.png
$location = 'images/food/' . $_POST['id_food'] . '.'.end($tmp);
$uploadOk = 1;
$imgType = pathinfo( $location, PATHINFO_EXTENSION );

$valid_extesnsions = array( "jpg", "jpeg", "png" );
if( !in_array( strtolower($imgType) , $valid_extesnsions ) )
    $uploadOk = 0;

if( $uploadOk === 0){
    echo 'ERROR: upload!';
    exit(3);
}

try{    
    $db=DB::getConnection();
   
    $st=$db->prepare( 'SELECT image_path FROM spiza_food WHERE id_food=:val2' );
    $st->execute( array( 'val2' => $_POST['id_food'] ) );	
    $row = $st->fetch();	
}
catch( PDOException $e ) { 
    echo $e;
    exit(4);
}

$stara_slika =  __DIR__. str_replace( '/app', '', $row['image_path'] );


try{    // Pomoću transakcije ubacujemo u bazu info o hrani pa postavljamo ime slike
        // , u slučaju da se ne uspiju u bacit
        // sve promjene u bazi se poništavaju
        $db=DB::getConnection();

        $db->beginTransaction();
        
        $st=$db->prepare( 'UPDATE spiza_food SET image_path=:val WHERE id_food=:val2' );
        $st->execute( array( 'val2' => $_POST['id_food'],  'val' => '/app/'.$location ) );		

        $db->commit();
    }
    catch( PDOException $e ) { 
        echo $e;
        $db->rollBack();
        exit(2);
    }
    unlink( $stara_slika );

    //  Premještamo preimenovanu sliku u folder za slike 
if( move_uploaded_file($_FILES['file']['tmp_name'],$location) ){
    echo 'Food picture added!';
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