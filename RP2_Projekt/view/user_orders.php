<?php require_once __DIR__ . '/header&footer/_header.php'; ?>



<div class="row">
<div class="col-sm-6 left">
<h4>Popis aktivnih narudžbi:</h4>
<ul class="list-group">
    <?php 
    if( $orderList != [] ){
        $provjera = 0;
        foreach( $orderList as $order ){
            if ( $order[0]->active != 0 && $order[0]->active != -1 && $order[0]->active != -2 ){
                $provjera = 1;
                if( $order[0]->active == 1 )
                    $status = 'Narudžba poslana. Čeka se odgovor restorana.';
                else if( $order[0]->active == 2 )
                    $status = 'Restoran je prihvatio Vašu narudžbu.';
                else if( $order[0]->active == 3 )
                    $status = 'Restoran je prihvatio Vašu narudžbu. Procijenjeno vrijeme čekanja je ' . strtotime( $order[0]->delivery_time ) . ' minuta.';
            
                echo '<li class="list-group-item">' .
                    'Iz ' . '<a href="index.php?rt=user/restaurant&id_restaurant=' . $order[2] . '">' . $order[0]->id_restaurant . '</a>:<br>' .
                    'Status narudžbe: ' . $status . '<br>';
                foreach ( $order[1] as $food )
                    echo $food[0]->name . ' (' . $food[1] . ')<br>';
                echo 'Ukupna cijena: ' . $order[0]->price_total . ' kn<br>';
                if( $order[0]->discount != 0 ){
                    $spopustom = round( $order[0]->price_total * 0.9, 2 );
                    echo 'Cijena s popustom: ' . $spopustom . ' kn<br>';
                }
                echo '</li>';
            }
        }
        if( $provjera == 0 ) echo '<li class="list-group-item">Nemate aktivnih narudžbi</li>';
    }
    else echo '<li class="list-group-item">Nemate aktivnih narudžbi</li>';
    ?>
</ul>
</div>

<div class="col-sm-6 left">
<h4>Popis prethodnih narudžbi:</h4>
<ul class="list-group">
    <?php 
    if( $orderList != [] ){
        $provjera = 0;
        foreach( $orderList as $order ){
            if ( $order[0]->active == 0 ){
                $provjera = 1;
                echo '<li class="list-group-item">' .
                    'Iz ' . '<a href="index.php?rt=user/restaurant&id_restaurant=' . $order[2] . '">' . $order[0]->id_restaurant . '</a>:<br>';
                foreach ( $order[1] as $food )
                    echo $food[0]->name . ' (' . $food[1] . ')<br>';
                echo 'Ukupna cijena: ' . $order[0]->price_total . ' kn<br>';
                if( $order[0]->discount != 0 ){
                    $spopustom = $order[0]->price_total * 0.9;
                    echo 'Cijena s popustom: ' . $spopustom . ' kn<br>';
                }
                echo '<span class="badge badge-success">Dostavljeno</span><br>';
                if( $order[0]->rating != 1 && $order[0]->rating != 2 && $order[0]->rating != 3 && $order[0]->rating != 4 && $order[0]->rating != 5 && $order[0]->rating != 6 && $order[0]->rating != 7 && $order[0]->rating != 8 && $order[0]->rating != 9 && $order[0]->rating != 10)
                    echo "<button class='btn btn-primary'klasa='ocijeni' ord='" . $order[0]->id_order . "'>Ocijeni</button>";
                echo '</li>';
            }
            else if( $order[0]->active == -1 || $order[0]->active == -2 ){
                $provjera = 1;
                echo '<li class="list-group-item">' .
                    'Iz ' . '<a href="index.php?rt=user/restaurant&id_restaurant=' . $order[2] . '">' . $order[0]->id_restaurant . '</a>:<br>';
                foreach ( $order[1] as $food )
                    echo $food[0]->name . ' (' . $food[1] . ')<br>';
                echo 'Ukupna cijena: ' . $order[0]->price_total . ' kn<br>';
                if( $order[0]->discount != 0 ){
                    $spopustom = round( $order[0]->price_total * 0.9, 2 );
                    echo 'Cijena s popustom: ' . $spopustom . ' kn<br>';
                }
                echo '<span class="badge badge-danger">Odbijeno</span>';
                echo '</li>';
            }
        }
        if( $provjera == 0 ) echo '<li class="list-group-item">Nemate prethodnih narudžbi</li>';
    }
    else echo '<li class="list-group-item">Nemate prethodnih narudžbi</li>';
    ?>
</ul>
</div>
</div>

<div style="height: 250px;"> </div>

</div>
</div>
</div>

<form class="oc" hidden>
    Unesite recenziju: <input type="text" name="recenzija">
    Ocijeni: <input type='number' name='ocjena' required>
    <input type="submit"  value="Ocijeni"></input>`
</form>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.js"></script>
<script src="<?php echo __SITE_URL; ?>/view/javascript/orders.js"></script>

<?php require_once __DIR__ . '/header&footer/_footer.php'; ?>