<?php require_once __DIR__ . '/header&footer/_header.php'; ?>

<div class="list-group">
    <?php 
    $i = 0;

    if( $restaurantList != [] ){
        foreach( $restaurantList as $restaurant ){
            echo '<a class="list-group-item list-group-item-action" href="index.php?rt=user/restaurant&id_restaurant=' . 
            $restaurant->id_restaurant . '">' . '<h5>' . $restaurant->name . '</h5>' . 'Ocjena: ' . '<span class="badge badge-info">' . $ratings[$i] . '</span><br>' ;
            echo '<small>' . $restaurant->description . '</small>';
            echo '</a>';
            $i++;
        }
    }
    else echo '<li class="list-group-item">Nema dostupnih restorana</li>';
    ?>
</div>

<div style="height: 50px;"> </div>

</div>
</div>
</div>

<!-- <div style="height: 250px;"> </div> -->

<?php require_once __DIR__ . '/header&footer/_footer.php'; ?>