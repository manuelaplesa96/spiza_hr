<?php require_once __DIR__ . '/header&footer/_header_restaurants.php'; ?>

<!--            NOTIFIKACIJA            -->
<div style="position: relative;" >
<div class="toast" data-autohide="true"  data-delay="4000" style=" z-index: 1; background-color: #DCDCDC; position: absolute; top: 0; right: 10px;">
    <div class="toast-header" >
        <strong class="mr-auto text-primary">Nova narudžba</strong>     
        <small class="text-muted"><!--5 min--></small>
        <button type="button" class="ml-2 mb-1 close" data-dismiss="toast">&times;</button>
    </div>
    <div class="toast-body" >
      Pristigla je nova narudžba! Pogledajte trenutne narudžbe.
    </div>
</div>
</div>

<!--        BODY            -->
<div class="container">
  <div class="row">
    <div class="col-12">
<h2>Trenutne narudžbe</h2>
<p>Kako bi vidjeli detalje narudžbi klinkite na njih. Također potrebno je novopristigle narudžbe prihvatiti ili odbiti. U slučaju da prihvatite narudžbu
    neophodno je upisati vrijeme koje vam je potrebno za pripremu hrane.
</p>
<div class="activeOrders" id_restaurant="<?php echo $_SESSION['restaurants']->id_restaurant;?>"></div>

<h3>Menu:</h3>

<?php if($FoodList!=[]) 
{ ?>

    <p>Kako bi vidjeli slike detaljnije kliknite na njih te će vam se prikazati uvećane.</p>

    <table class="table table" name="food" >
        <thead>
        <tr>
            <th>Jelo </th>
            <th>Cijena </th>
            <th>Opis </th>
            <th>Čekanje (u minutama) </th>
            <th>Slika </th>
        </tr>
        </thead>
        <tbody>
<?php
    }

    if( $FoodList != [] )
    {
        foreach( $FoodList as $food)
        {

                echo "<tr id=".$food->id_food.">\n";
                echo "<td>". $food->name ."</td>\n";
                echo "<td>". $food->price . ' kn ' . "</td>\n";
                echo "<td>". $food->description ."</td>\n";
                echo "<td>". $food->waiting_time . "'" . "</td>\n";
                echo "<td>";
                    if( $food->image_path !== null )
                        echo '<img src="'. __SITE_URL . $food->image_path .'" width="100" height="100" name="' .$food->name. '">';
                echo "</td>\n";
                echo "</tr>\n";
        }
    }
    else 
        echo '<li class="list-group-item">Menu je prazan</li>';
?>
    </tbody>
</table>

<!--                BUTTONI     ZA      EDIT       FOOD             -->
<div class="btn-group btn-block">
    <button type="button" class="btn btn-primary  dropdown-toggle" data-toggle="dropdown">
      Uredi jelovnik
    </button>
    <div class="dropdown-menu btn-block">
        <button class="dropdown-item" name="editFood" title="Uredi jelo iz ponude" target="<?php echo __SITE_URL;?>/index.php?rt=restaurants/editFood">Uredi jelo</button>
        <button class="dropdown-item" name="addFood" title="Dodaj jelo">Dodaj jelo</button>
        <button class="dropdown-item" name="removeFood" title="Uredi ponudu">Ukloni jelo</button>
    </div>
</div>




<!--                EDIT       FOOD             -->
<form klasa="editFood" id="editFood" target="<?php echo __SITE_URL;?>/app/editFood.php" hidden>
    <h3>Odaberite hranu koju želite promijeniti:</h3>
    
    <select class="custom-select" style="width:auto;" name="editFood">
            <?php
                    foreach( $FoodList as $food)
                    {
                        echo "<option value='".$food->id_food."'>".$food->name."</option>\n";
                    }
            ?>
    </select>
    
    <table class="editFood" style="margin-left: auto; margin-right: auto;">
        <tr>
            <th> Ime jela: </th>    <div class="form-group">

            <td><input class="form-control" type="text" name="foodName" disabled=true></td>
            <td><input type="checkbox" id="che1" name="foodName" value="change"></td>   </div>
        </tr>
        <tr>
            <th>Cijena: </th>       <div class="form-group">

            <td><input class="form-control" type="number" name="foodPrice" disabled=true></td>
            <td><input type="checkbox" id="che2" name="foodPrice" value="change"></td></div>
        </tr>
        <tr>
            <th>Opis jela: </th>
            <td><input class="form-control" type="text" name="foodDescription" disabled=true></td>
            <td><input type="checkbox"  id="che3" name="foodDescription" value="change"></td>
        </tr>
        <tr>
            <th>Trajanje pripreme: </th>
            <td><input class="form-control" type="number" name="foodWaitingTime" disabled=true></td>
            <td><input type="checkbox"  id="che4" name="foodWaitingTime" value="change"></td>
        </tr>
        <tr>
            <th>Slika hrane: </th>
            <td>
                <div class="custom-file">
                    <input class="custom-file-input" type="file" name="imgFood_edit" disabled=true>
                    <label class="custom-file-label" for="customFile">Izaberi sliku</label>
                </div>
            </td>
            <td><input type="checkbox"  id="che5" name="imgFood_edit" value="change"></td>
        </tr>
    </table>
    <input type="submit" class="btn btn-primary btn-block" form="editFood" value="Change food">
</form>


<!--                REMOVE       FOOD               -->
<form klasa="removeFood" id="removeFood" hidden>
    <h3>Odaberite hranu koju želite maknuti iz ponude:</h3>


    <table class="removeFood" style="margin-left: auto; margin-right: auto;">
        <tr>
            <th></th>
            <th>Jelo: </th>
            <th>Cijena: </th>
            <th>Opis: </th>
            <th>Čekanje (u minutama): </th>
        </tr>
        <?php
            foreach( $FoodList as $food)
            {
                        echo "<tr>\n";
                        echo '<td><input type="checkbox"  class="removeFood" value="'.$food->id_food.'"></td>';
                        echo "\n<td>". $food->name ."</td>\n";
                        echo "<td>". $food->price ."</td>\n";
                        echo "<td>". $food->description ."</td>\n";
                        echo "<td>". $food->waiting_time ."</td>\n";
                        echo "</tr>\n";
            }
        ?>
    </table>
    <input type="submit" class="btn btn-primary btn-block" form="removeFood" value="Remove selected food" disabled>
</form>


<!--                ADD       FOOD               -->

<form klasa="addFood" method="post"  id="addFood" enctype="multipart/form-data" restaurant="<?php echo $_SESSION['restaurants']->id_restaurant;?>" hidden>
    <h3>Dodaj novu hranu u ponudu:</h3>

    <table class="addFood" style="margin-left: auto; margin-right: auto;">
        <tr>
            <th>Jelo: </th>
            <td><input type="text" class="form-control"  name="name_input" required></td>
        </tr>
        <tr>
            <th>Cijena: </th>
            <td><input type="number" class="form-control" name="price_input" required></td>
        </tr>
        <tr>
            <th>Opis: </th>
            <td><input type="text" class="form-control" name="description_input" required></td>
        </tr>
        <tr>
            <th>Čekanje (u minutama): </th>
            <td><input type="number" class="form-control" name="waitingTime_input" required></td>
        </tr>
        <tr>
            <th>Slika: </th>
            <td>
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="customFile" name="imgFood_input" accept="image/png, image/jpeg, image/jpg"required>
                    <label class="custom-file-label" for="customFile">Izaberi sliku</label>
                </div>
            </td>
        </tr>
    </table>
    <input type="submit" class="btn btn-primary btn-block" form="addFood" value="Dodaj jelo u meni">
</form>


<br><br>
<h3>Ocjena Vašeg restorana je <?php echo $restaurantRating;?>.</h3>
<br>


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
                echo '<img src="'. __SITE_URL . $restaurantImages['image'][$i] .'" alt="Slika" width="1100" height="500" name="restaurantImg" >';
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

<!--          DODAVANJE SLIKA RESTORANA      -->

<button class="btn btn-primary btn-block" name="addPhotos" title="addPhotos">Dodaj slike u galeriju</button>
<form klasa="addPhotos" method="post" id="addPhotos" enctype="multipart/form-data" action="<?php echo __SITE_URL;?>/index.php?rt=restaurants/addPhotos" restaurant="<?php echo $_SESSION['restaurants']->id_restaurant;?>" hidden>
    <h3>Dodaj slike restorana u galeriju:</h3>
    <br>
   <div class="custom-file" >
        <input type="file" class="custom-file-input" id="galerija" name="addPhotos[]" multiple accept="image/png, image/jpeg, image/jpg"  required>
        <label class="custom-file-label" for="galerija">Izaberi sliku</label>
    </div>
    <input type="submit"  class="btn btn-primary btn-block" form="addPhotos"  value="Dodaj slike" >
</form>
<br>

<!--        KATEGORIJA HRANE        -->
<h3>Kategorija hrane u ponudi:</h3>

U ponudi je: 
<?php 
for( $i = 0; $i < sizeof( $foodType ); ++$i)
{
    echo $foodType[$i]->name;
    if( $i === sizeof($foodType)-1 )
        echo '.';
    else 
        echo ', ';
}
?>

<!--        GUMB ZA PRIKAZ FORME ZA MJENJANJE HRANE     -->
<div class="btn-group btn-block">
    <button type="button" class="btn btn-primary  dropdown-toggle" data-toggle="dropdown">
      Uredi kategoriju hrane
    </button>
    <div class="dropdown-menu btn-block">
        <button class="dropdown-item" name="addCategory" title="addCategory">Dodaj kategoriju hrane u ponudu</button>
        <button class="dropdown-item" name="removeCategory" title="removeCategory">Makni kategoriju hrane iz ponude</button>
    </div>
</div>

<!--         FORME ZA MJENJANJE HRANE     -->
<form klasa="addCategory" method="post"  id="addCategory" action="<?php echo __SITE_URL;?>/index.php?rt=restaurants/addCategory" restaurant="<?php echo $_SESSION['restaurants']->id_restaurant;?>" hidden>
    <h3>Dodaj kategoriju hrane u ponudu restorana:</h3>

    <select class="custom-select" style="width:auto;" name="addCategory">
            <?php
                    foreach( $foodTypeList as $food)
                    {
                        echo "<option value='".$food->id_foodType."'>".$food->name."</option>\n";
                    }
            ?>
    </select>
    <input type="submit" class="btn btn-primary btn-block" form="addCategory" value="Dodaj kategoriju hrane u ponudu">
</form>
<form klasa="removeCategory" method="post"  id="removeCategory" action="<?php echo __SITE_URL;?>/index.php?rt=restaurants/removeCategory" restaurant="<?php echo $_SESSION['restaurants']->id_restaurant;?>" hidden>
    <h3>Makni kategoriju iz ponude:</h3>

    <select class="custom-select" style="width:auto;" name="removeCategory">
            <?php
                    foreach( $foodType as $food)
                    {
                        echo "<option value='".$food->id_foodType."'>".$food->name."</option>\n";
                    }
            ?>
    </select>

    <input type="submit" class="btn btn-primary btn-block" form="removeCategory" value="Dodaj kategoriju hrane u ponudu">
</form>




<h3>Podaci o restoranu: </h3>
Ime: <?php echo $restaurantInfo->name;?>
<br>
Opis: <?php echo $restaurantInfo->description;?>
<br>
Adresa: <?php echo $restaurantInfo->address;?>
<br>
E-mail: <?php echo $restaurantInfo->email;?>
<br>
<br>

<!--                PROMIN DETALJE              -->
<button class="btn btn-primary btn-block" name="changeDetails" title="changeDetails">Promijeni detalje</button>
<form klasa="changeDetails" method="post" id="changeDetails" restaurant="<?php echo $_SESSION['restaurants']->id_restaurant;?>" hidden>
    <h3>Promijeni detalje svog restorana:</h3>

    <table class="changeDetails" style="margin-left: auto; margin-right: auto;">
        <tr>
            <th>Ime: </th>
            <th>Opis: </th>
            <th>Adresa: </th>
        </tr>
            <td><input type="text" class="form-control" name="name_change"></td>
            <td><input type="text" class="form-control" name="desc_change"></td>
            <td><input type="text" class="form-control" name="address_change"></td>
    </table>
    <input type="submit"  class="btn btn-primary btn-block" form="changeDetails" value="Promijeni" >
</form>


</div>
</div>
</div>

<!----          OKVIR       ZA      FORME           ----->
<div class="modal" id="modalForma">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="modalFormaNaslov"></h4>
        <button type="button" class="close" data-dismiss="modal" style="font-size: 35px;">&times;</button>
      </div>
      <div class="modal-body" id="modalFormaTijelo" style="text-align: center; overflow: auto;"></div>
      <div class="modal-footer" id="modalFormaFoot">
      </div>
    </div>
  </div>
</div>


<script>
$(".custom-file-input").on("change", function() {
  var fileName = $(this).val().split("\\").pop();
  $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
});
</script>

<hr>

<?php require_once __DIR__ . '/header&footer/_footer.php'; ?>