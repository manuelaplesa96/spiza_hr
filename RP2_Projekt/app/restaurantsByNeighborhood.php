<?php

require_once __DIR__ . '/database/db.class.php';
$q = $_GET[ "q" ];
$svi_kvartovi=[];

try
{
    $db=DB::getConnection();
    $st=$db->prepare('SELECT * FROM spiza_neighborhood');
    $st->execute([ ]);
}
catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
if ($st->rowCount()===0)
    return null;
while( $row = $st->fetch() )
        $svi_kvartovi[] = $row['neighborhood'];

$kvartovi = array_unique($svi_kvartovi);


// Protrči kroz sva imena i vrati HTML kod <option> za samo ona 
// koja sadrže string q kao podstring.
foreach( $kvartovi as $kvart )
    if( strpos( $kvart, $q ) !== false )
        echo "<option value='" . $kvart . "' />\n";
?>
