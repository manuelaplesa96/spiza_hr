<?php require_once __DIR__ . '/header&footer/_header_deliverers.php'; ?>

<!--        BODY            -->
<div class="container">
  <div class="row">
    <div class="col-12">

<ul>
    <?php 
    if( $currentOrder!== NULL){
        echo '<h4>Dostava u tijeku!</h4><div>';        
    
    echo 'Broj narudžbe: ' . $currentOrder[0]->id_order . '<br>';
    echo 'Korisnik: ' . $currentOrder[1] . '<br>';
    echo 'Adresa: ' . $currentOrder[0]->address . '<br>';
    echo 'Popust: ' . $currentOrder[0]->discount .'<br>';
    echo 'Napomena: ' . $currentOrder[0]->note .'<br>';

    echo 'Restoran: ' . $currentOrder[2] .'<br>';
    echo 'Sadržaj narudžbe: <ul class="list-group">';
    foreach($currentOrder[3] as $hrana)
        echo '<li class="list-group-item">' . $hrana[0] . ' (' . $hrana[1] .  ')</li></div>';

    
 
    echo '<form action="'. __SITE_URL .'/index.php?rt=deliverers/delivered" method="post" class="was-validated">
    <div class="form-group form-check">
        <label class="form-check-label">
            <input type="checkbox" class="form-check-input" name="dostavljeno" id="dostavljeno"  value="dostavljeno" required/> Dostavljeno<br> 
            <div class="valid-feedback">Ispunjeno.</div>
            <div class="invalid-feedback">Označite da ste izvršili dostavu.</div>
        </label>
    </div>
    <button type="submit" class="btn btn-primary btn-block" name="btn_dostavljeno" value="'. $currentOrder[0]->id_order. '" >Dostavljeno</button><br>
    </form>';
    }
    else
        echo '<h4>Nemate aktivnih dostava.</h4><div>';        

    ?>

</ul>



<div style="height: 250px;"> </div>

</div>
</div>
</div>

<?php require_once __DIR__ . '/header&footer/_footer.php'; ?>