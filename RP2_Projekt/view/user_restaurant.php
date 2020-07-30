<?php require_once __DIR__ . '/header&footer/_header.php'; ?>

<?php 
if( $foodList != null )
    echo '<span id="idjevi" id_restaurant="' . $foodList[0]->id_restaurant . '" id_user="' . $_SESSION['user']->id . '" hidden></span>';
echo '<span id="gl_adresa" gl_adresa="' . $_SESSION['user']->address . '" hidden></span>'; ?>

<script src="<?php echo __SITE_URL; ?>/view/javascript/gallery.js"></script>

<h5>Ocjena: <?php echo '<span class="badge badge-info">' . $rating . '</span>'; ?></h5><br><br>


<h3>Galerija restorana</h3>

<!--        GALERIJA        RESTORANI       -->
<div id="slike_restorani" class="carousel slide" brslika="<?php echo sizeof($restaurantImages['image']);?>" data-ride="carousel">

  <!-- SLIDEOVI -->
  <ul class="carousel-indicators">
    <li data-target="#slike_restorani" data-slide-to="0" class="active"></li>
    <?php 
        for( $i=1; $i < sizeof($restaurantImages['image']); ++$i ) // as $image )
        {
            echo '<li data-target="#slike_restorani" data-slide-to="'.$i.'" ></li>';
        }
    ?>
  </ul>
  
  <!-- SLIKE -->
  <div class="carousel-inner">
    <?php 
        echo '<div class="carousel-item active">';
            echo '<img src="'. __SITE_URL . $restaurantImages['image'][0] .'" alt="Slika" width="1100" height="500" name="restaurantImg"  style="color:black;">';
        echo '</div>';
        for( $i=1; $i < sizeof($restaurantImages['image']); ++$i ) // as $image )
        {
            echo '<div class="carousel-item">';
                echo '<img src="'. __SITE_URL . $restaurantImages['image'][$i] .'" alt="Slika" width="1100" height="500" name="restaurantImg" style="width:1100; height:500;" >';
            echo '</div>';
        }
    ?>
  </div>
  <br>
  
  <!-- STRELICE -->
  <a class="carousel-control-prev" href="#slike_restorani" data-slide="prev">
    <span class="carousel-control-prev-icon"></span>
  </a>
  <a class="carousel-control-next" href="#slike_restorani" data-slide="next">
    <span class="carousel-control-next-icon"></span>
  </a>
</div>

<h3> Meni: </h3>

<?php
if( $foodList != null ){
?>
<table class="table table" name="food" >
    <thead>
    <tr>
        <th>Jelo </th>
        <th>Cijena </th>
        <th>Opis </th>
        <th>Slika </th>
        <th></th>
    </tr>
    </thead>
    <tbody>

    <?php
    foreach( $foodList as $food)
    {

            echo "<tr id=".$food->id_food.">\n";
            echo "<td>". $food->name ."</td>\n";
            echo "<td>". $food->price . ' kn ' . "</td>\n";
            echo "<td>". $food->description ."</td>\n";
            echo "<td>";
                if( $food->image_path !== null )
                    echo '<img src="'. __SITE_URL . $food->image_path .'" width="100" height="100" name="' .$food->name. '">';
            echo "</td>\n";
            echo '<td><button class="btn btn-primary" img="' . $food->image_path . '" klasa="dodaj" id="' . $food->id_food . ', ' . $food->name . ', ' . $food->price . '">Dodaj u košaricu</button></li></td>';
            echo "</tr>\n";
    }
?>
    </tbody>
</table>
<?php } ?>

<?php if( $foodList == null ) echo '<div class="list-group-item">Restoran još nema jela u ponudi.</div><br>'; ?>

<!--
<ul>
    <?php /*
    foreach( $foodList as $food ){
        echo '<li>' .
             $food->name . ': ' . $food->price . '<br>' .
             $food->description . '<br>';
        if( $food->image_path !== null )
            echo '<img src="'. __SITE_URL . $food->image_path .'"width="100" height="100"><br>';
        echo '<button img="' . $food->image_path . '" class="dodaj" id="' . $food->id_food . ', ' . $food->name . ', ' . $food->price . '">Dodaj u košaricu</button></li>';
    }*/
    ?>
</ul> -->

<h3>Recenzije: </h3>
<ul class="list-group">
    <?php 
    $i=0;
    if( $orderList != null ){
        foreach( $orderList as $order ){
            if( $order->active == 0 ){
                echo '<li class="list-group-item">' .
                    'Ocjena: ' . $order->rating . '<br>'
                    . $order->id_user . '<br>' .
                    '<div id="ovaj' . $i . '">' . $order->feedback . '</div><br>' .
                    'Je li ovaj komentar bio koristan?<br>' . 
                    '<button class="btn btn-primary" klasa="thumbs" id="' . $order->id_order . '" palac="gori" broj="' . $order->thumbs_up . '"> &#x1F44D;' . $order->thumbs_up . '<span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span></button>' . 
                    '<button class="btn btn-primary" klasa="thumbs" id="' . $order->id_order . '" palac="doli" broj="' . $order->thumbs_down . '">  &#x1F44E;' . $order->thumbs_down . '<span class="glyphicon glyphicon-thumbs-down" aria-hidden="true"></span></button>' . 
                    '</li>';
                $i++;
            }
        }
    } 
    else echo '<li class="list-group-item">Restoran nema niti jednu recenziju.</span>'?>
</ul>

<span id="povratna" hidden>Narudžba poslana restoranu. Za više detalja pogledajte <a href="<?php echo __SITE_URL; ?>/index.php?rt=user/orders">Moje narudžbe</a>.</span>

<div style="height: 250px;"> </div>

</div>
</div>
</div>

<script>
$( document ).ready( function() 
{
    var i = 0;
    var n = <?php echo $i; ?>;
    for ( i = 0; i < n;  i++){
        var text = $( '#ovaj' + i ).html();
        var char_limit = 200;

        if(text.length < char_limit)
            $( '#ovaj' + i ).html( text );
        else
            $( '#ovaj' + i ).html( '<span class="short-text">' + text.substr(0, char_limit) + '</span><span class="long-text" style="display:none">' + text.substr(char_limit) + '</span><span class="text-dots">...</span><span class="show-more-button" data-more="0" style="color:blue">Read More</span>' );
    }


    $(".show-more-button").on('click', function() {
	// If text is shown less, then show complete
	if($(this).attr('data-more') == 0) {
		$(this).attr('data-more', 1);
		$(this).css('display', 'block');
		$(this).text('Read Less');

		$(this).prev().css('display', 'none');
		$(this).prev().prev().css('display', 'inline');
	}
	// If text is shown complete, then show less
	else if(this.getAttribute('data-more') == 1) {
		$(this).attr('data-more', 0);
		$(this).css('display', 'inline');
		$(this).text('Read More');

		$(this).prev().css('display', 'inline');
		$(this).prev().prev().css('display', 'none');
	}
    });
} );
</script>

<?php require_once __DIR__ . '/header&footer/_footer.php'; ?>