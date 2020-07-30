<?php require_once __DIR__ . '/header&footer/_header.php'; ?>

<form action="<?php echo __SITE_URL;?>/index.php?rt=user/searchNeighborhood" method="post">
    <h4>Odaberite kvart:</h4>
    <input class="form-control" type="text" list="datalist_kvartova" id="txt_kvart" name="kvart">
    <datalist id="datalist_kvartova"></datalist>
    <button class="btn btn-primary"type="submit">Pretraži</button>
</form>

<div style="height: 250px;"> </div>

</div>
</div>
</div>

<!-- <div style="height: 250px;"> </div> -->

<?php require_once __DIR__ . '/header&footer/_footer.php'; ?>