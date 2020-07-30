<?php require_once __DIR__ . '/header&footer/_header_restaurants.php'; ?>


<div class="container">
  <div class="row">
    <div class="col-12">
<h3>Prošle narudžbe</h3>
<p>Kako bi vidjeli detalje narudžbe te recenzije kliknite na prikaži detalje.</p>

<?php if( $orderList != [] ){ ?>
<table class="table table-hover">
    <thead>
        <tr>
            <th>Status narudžbe</th>
            <th>Broj narudžbe</th>
            <th>Broj klijenta</th>
            <th>Vrijeme narudžbe</th>
            <th>Vrijeme dostave</th>
            <th>Ukupna cijena</th>
            <th>Ocijena</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    <?php   
    foreach( $orderList as $order)
    {
        if( intval($order[0]->active) <= 0){
            echo "<tr>\n";
            if( intval( $order[0]->active ) === 0)
                echo "<td><span class='badge badge-secondary'>Dostavljena</span></td>\n";
            elseif( intval( $order[0]->active ) === -1)
                echo "<td><span class='badge badge-danger'>Odbijena</span></td>\n";
            elseif( intval( $order[0]->active ) === -2)
                echo "<td><span class='badge badge-dark'>Odbijena od dostavljaća</span></td>\n";


                //echo "<td>".$order[0]->active."</td>\n";
            
                echo "<td>".$order[0]->id_order."</td>\n";
                echo "<td>".$order[0]->id_user."</td>\n";
                echo "<td>".$order[0]->order_time."</td>\n";
                echo "<td>".$order[0]->delivery_time."</td>\n";
                echo "<td>".$order[0]->price_total."kn</td>\n";
                echo "<td>".$order[0]->rating."</td>\n";
                echo "<td style='cursor: pointer;' ><button type='button' class='btn btn-info' name='orderDetails' ordernumber='".$order[0]->id_order."'>Prikaži detalje  &#8592;</td></button>\n";
            echo "</tr>\n";
            echo "<tr class='orderDetails' ordernumber='".$order[0]->id_order."' style='display: none;'>\n";
                echo "<td colspan='7'>\n";
                echo "<ul class='list-group'>\n";
                    if( $order[0]->note !== '')
                        echo "<li class='list-group-item'>Napomena uz narudžbu: ".$order[0]->note."</li>\n";
                    echo "<li class='list-group-item'>Jela iz narudžbe: ";
                    for( $i = 1; $i < sizeof($order); ++$i){                    
                        echo ' '.$order[$i][0]->name;
                        if( $i !== sizeof($order)-1)
                            echo ',';
                    }
                    echo "</li>\n";
                    echo "<li class='list-group-item'>Popust: ";
                    if( !is_numeric($order[0]->discount) )
                        echo '0.0';
                    else 
                        echo $order[0]->discount;
                    echo "% </li>\n";
                    echo "<li class='list-group-item'>Recenzija: ".$order[0]->feedback."</li>\n";
                    echo "<li class='list-group-item'>Palac gore: ".$order[0]->thumbs_up."</li>\n";
                    echo "<li class='list-group-item'>Palac dole: ".$order[0]->thumbs_down."</li>\n";
                echo "</ul>\n";
                echo "</td>\n";
            echo "</tr>\n";
        }
    }
    ?>
    </tbody>
</table>
<?php }
else echo "Nemate prethodnih narudžbi"; ?>


<div style="height: 250px;"> </div>

        </div>
    </div>
</div>



<hr>
<?php require_once __DIR__ . '/header&footer/_footer.php'; ?>