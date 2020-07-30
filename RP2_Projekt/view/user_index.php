<?php require_once __DIR__ . '/header&footer/_header.php'; ?>

<div class="list_group">
    <?php 
    if( $restaurantList != [] ){
        while ( max( array_column( $restaurantList, 1 )) != 0 ){
            $rat = max( array_column( $restaurantList, 1 ) );
            for( $i = 0; $i < count( $restaurantList ); $i++ ){
                if ( $restaurantList[$i][1] == $rat ){
                    echo //'<li class="list-group-item">' . 
                        '<a href="index.php?rt=user/restaurant&id_restaurant=' . $restaurantList[$i][0]->id_restaurant . '" class="list-group-item list-group-item-action">' . 
                        $restaurantList[$i][0]->name . '  <br><small>Vaša ocjena: </small>'. 
                        '<span class="badge badge-info">' . $restaurantList[$i][1] . '</span>' . '</a>';
                    $restaurantList[$i][1] = 0;
                    break;
                }
            }
        }
    }
    else echo '<li class="list-group-item">Niste ocijenili niti jedan restoran</li>';
    ?>
</div>

<div style="height: 250px;"> </div>

</div>
</div>
</div>

<?php require_once __DIR__ . '/header&footer/_footer.php'; ?>