<?php require_once __DIR__ . '/header&footer/_header.php'; ?>

<br>
<div class="container" style="width: 75%;">
    <?php 
        if( $foodType != [] ){
            foreach( $foodType as $food ){ //u css-u maknuti točkice kod liste
                echo '<a style="display:block" href="index.php?rt=user/restaurantsByFoodType&id_foodType=' . $food->id_foodType . '">';
                echo '<div class="card">';
                if( $food->image_path !== null )
                    echo '<img class="card-img-top" src="'. __SITE_URL . $food->image_path .'" style="width:50%; margin: auto;">';
                
                echo '<div class="card-body" style="text-align: center">
                    <h4 class="card-title" style="color: black;">' . ucfirst( $food->name ) . '</h4></div>';
                echo '</div></a>';
            }
        }
        else echo '<div class="list-group-item">Trenutno nema raspoloživih vrsta hrane</div>';
    ?>
</div>

<div style="height: 250px;"> </div>

</div>
</div>
</div>

<!-- <div style="height: 250px;"> </div> -->

<?php require_once __DIR__ . '/header&footer/_footer.php'; ?>