<?php require_once __DIR__ . '/header&footer/_header.php'; ?>

<div class="list-group">
    <?php 
    if( $restaurantList != [] ){
        while ( max( array_column( $restaurantList, 1 )) != 0 ){
            $rat = max( array_column( $restaurantList, 1 ) );
            for( $i = 0; $i < count( $restaurantList ); $i++ ){
                if ( $restaurantList[$i][1] == $rat ){
                    echo '<a class="list-group-item list-group-item-action" href="index.php?rt=user/restaurant&id_restaurant=' . $restaurantList[$i][0]->id_restaurant . '">' . 
                        $restaurantList[$i][0]->name . '<br><small>Ocjena korisnika: </small>'. 
                        '<span class="badge badge-info">' . $restaurantList[$i][1] .'</span></a>';
                    $restaurantList[$i][1] = 0;
                    break;
                }
            }
        }
    }
    else echo '<li class="list-group-item">Nema popularnih restorana</li>';
    ?>
</div>

</div>
</div>
</div>

<!-- <div style="height: 250px;"> </div> -->

<?php require_once __DIR__ . '/header&footer/_footer.php'; ?>