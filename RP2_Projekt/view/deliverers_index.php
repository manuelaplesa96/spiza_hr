<?php require_once __DIR__ . '/header&footer/_header_deliverers.php'; ?>

<!--            NOTIFIKACIJA            -->
<div style="position: relative;" >
<div class="toast" data-autohide="false"  style=" z-index: 1;  background-color: #DCDCDC; position: absolute; top: 0; right: 10px;">
    <div class="toast-header">
      <strong class="mr-auto text-primary">Imate prihvaćenu narudžbu</strong>     
      <small class="text-muted"><!--5 min--></small>
      <button type="button" class="ml-2 mb-1 close" data-dismiss="toast">&times;</button>
    </div>
    <div class="toast-body">
      Pogledajte <a class="link" href="<?php echo __SITE_URL;?>/index.php?rt=deliverers/active">aktivne narudžbe</a> za detalje.
      Prihvaćanje novih narudžbi je onemogućeno.
    </div>
</div>
</div>

<div class="container">
  <div class="row">
    <div class="col-12">
<h4>Slobodne narudžbe</h4>
<p>Prihvaćanje novih narudžbi je onemogućeno u slučaju da postoji već prihvaćena, ne dostavljena narudžba.</p>

<div class="avalableOrders" id="slobodne" id_deliverer="<?php echo $_SESSION['deliverers']->id;?>"></div>





<div style="height: 250px;"> </div>

</div>
</div>
</div>

<?php require_once __DIR__ . '/header&footer/_footer.php'; ?>