var timestamp = 0; 

$( document ).ready( function()
{
    if($( 'h2').first().html() !== 'Prošle narudžbe' )
        getActiveOrders();

    $( 'button[name="editFood"]' ).on('click', show_form );
    $( 'button[name="removeFood"]' ).on( 'click', show_form );
    $( 'button[name="addFood"]' ).on( 'click', show_form );
    $( 'button[name="changeDetails"]' ).on( 'click', show_form );
    $( 'button[name="addPhotos"]' ).on( 'click', show_form );
    $( 'button[name="addCategory"]' ).on( 'click', show_form );
    $( 'button[name="removeCategory"]' ).on( 'click', show_form );

    

    // Obrada formi
    $( 'form[klasa="editFood"]').on( 'submit', obradi_editFood );
    $( 'form[klasa="removeFood"]').on( 'submit', obradi_removeFood );
    $( 'form[klasa="addFood"]').on( 'submit', obradi_addFood );
    $( 'form[klasa="changeDetails"]' ).on( 'submit', obradi_changeDetails );
    //$( 'form[klasa="addPhotos"]' ).on( 'submit', obradi_addPhotos );

    
    // za editFood  prati checkboxove i otključava ih
    $( '#che1' ).on('click', sakrij_pokazi);
    $( '#che2' ).on('click', sakrij_pokazi);
    $( '#che3' ).on('click', sakrij_pokazi);
    $( '#che4' ).on('click', sakrij_pokazi);
    $( '#che5' ).on('click', sakrij_pokazi);
    //  Za removeFood otključava submit
    $( 'input.removeFood:checkbox' ).on('click', sakrij_pokazi_submit );

    //  samo za prošle narudđbe na pastOrders.php
    $( 'button[name="orderDetails"]' ).on( 'click', show_details);

});

/*
function show_form()
{
    var div = $( '<div>' ), title = $( '<h2>' ), box = $( '<div>'),
     close = $( '<span>' );

    //ostale buttone zaključamo - nemožemo imat više otvorenih prozora
    //$( 'button' ).prop( 'disabled', 'true');

    title.html( $(this).attr('title') )
        .css( 'color', 'black' );

    // link za zatvaranje boxa i okvira
    close.html('&times')
        .css( 'color', '#aaaaaa' )
        .css( 'float', 'right' )
        .css( 'font-size', '35')
        .css( 'font-weight', 'bold')
        .css( 'class', 'forma')
        .css( 'cursor', 'pointer' )
        .on( 'click', function(event){
            destroy($('div.okvir'));
        })         //izbrise sve kreirane elemente u divu kada se klikne
        .focus(function(){          // ne , drugačije riješit
            $(this).css( 'color', 'black' )
                    .css( 'text-decoration', 'none' )
                    .css( 'cursor', 'pointer' );
        })
        .on({
            mouseenter: function () {
                $(this).css('color', 'red')
            },
            mouseleave: function () {
                $(this).css( 'color', '#aaaaaa' );
            }
        });

    
     //  box za text i ostalo unutra okvira
     box.css( 'background-color', '#fefefe')
        .css( 'margin', 'auto')
        .css( 'padding', '20px')
        .css( 'border', '1px solid, /888')
        .css( 'width', '80%' )
        .css( 'overflow', 'auto')
        .prop( 'class', 'box')
        .append( close )
        .append( title );

    addCorrectForm( box, $(this).attr('name') );

    //  vanjski okvir    
    div.css( 'position', 'fixed')
        .css('display', 'block')
        .css( 'text-align', 'center')
        .css('top', '0%')
        .css('left', '0%')
        .css( 'padding-top', '100px')
        .css('height', '100%')
        .css( 'width', '100%' )
        .css( 'z-index', '1')
        .css( 'background-color', 'rgba(0,0,0,0.4)' )
        .css( 'overflow', 'auto' )
        .prop( 'class', 'okvir');
        
    
    div.append(box);
    $( "table[name='food']").after(div);

    //   Zatvara prozor ako se klikne van boxa
    div.on( 'click', function(event){
            if( $(event.target).attr('class') === 'okvir' )
                destroy($(event.target));
        });    
}*/

function show_form()
{

    var modalNaslov=$( '#modalFormaNaslov'), modal=$( '#modalForma'), modalBody=$( '#modalFormaTijelo'), modalFoot=$( '#modalFormaFoot');


    modalNaslov.html( $(this).attr('title'));

    addCorrectForm( modalBody, $(this).attr('name') , modalFoot);

    modal.modal('show');

    //   Zatvara prozor ako se klikne van boxa
    modal.on('hidden.bs.modal', function () {
        location.reload();
       })
}

function addCorrectForm( box,title, foot)
{
    if( title === 'editFood'){
        var table = $( 'table[name="food"]' ).clone();
        //subTitle
        box.append( table );              // Prikaz trenutne hrane u ponudi
        addEditForm(box, foot);
    }
    else if( title === 'removeFood' ){
        var form = $( 'form[klasa="removeFood"]' ).removeAttr( 'hidden' );
        foot.append( form.children( 'input[type="submit"]') );
        box.append( form );
    }
    else if( title === 'addFood' ){
        var form = $( 'form[klasa="addFood"]' ).removeAttr( 'hidden' );
        foot.append( form.children( 'input[type="submit"]') );
        box.append( form );
    }
    else if( title === 'changeDetails'){
        var form = $( 'form[klasa="changeDetails"]' ).removeAttr( 'hidden' );
        foot.append( form.children( 'input[type="submit"]') );
        box.append( form );
    }
    else if( title === 'addPhotos'){
        var form = $( 'form[klasa="addPhotos"]' ).removeAttr( 'hidden' );
        foot.append( form.children( 'input[type="submit"]') );
        box.append( form );
    }
    else if( title === 'addCategory'){
        var form = $( 'form[klasa="addCategory"]' ).removeAttr( 'hidden' );
        foot.append( form.children( 'input[type="submit"]') );
        box.append( form );
    }
    else if( title === 'removeCategory'){
        var form = $( 'form[klasa="removeCategory"]' ).removeAttr( 'hidden' );
        foot.append( form.children( 'input[type="submit"]') );
        box.append( form );
    }
}



function addEditForm(box, foot)
{
    var form = $( 'form[klasa="editFood"]' ), select = $( 'select[name="editFood"]' );

    form.removeAttr('hidden');
    var inputi = form.children( 'input[type="submit"]');
    foot.append(inputi);
    box.append(form);
}

function sakrij_pokazi(event)
{
    var input = $( 'input[type="text"][name="'+ $(event.target).attr('name')+'"]');
    if( input.length === 0)
        input = $( 'input[type="number"][name="'+ $(event.target).attr('name')+'"]');
    if( input.length === 0)
        input = $( 'input[type="file"][name="'+ $(event.target).attr('name')+'"]');
    
    if( typeof input.attr('disabled') !== typeof undefined && input.attr('disabled') !== false )
        input.removeAttr( 'disabled' );
    else{
        input.prop( 'disabled', true);
        input.val('');
    }
}

function sakrij_pokazi_submit()
{
    if( $( 'input.removeFood:checkbox:checked' ).length === 0)
        $( 'input[type="submit"][value="Remove selected food"]').prop( 'disabled', true);
    else
        $( 'input[type="submit"][value="Remove selected food"]').removeAttr( 'disabled' );
}

function obradi_addFood()
{
    var fd = new FormData();
    var p = $( '<p>' ), files = $( 'input[name="imgFood_input"]' )[0].files[0];
    
    event.preventDefault();

    //console.log( $( 'input[name="imgFood_input"]' ) );
    //console.log( $( 'input[name="imgFood_input"]' )[0] );
    //console.log( $( 'input[name="imgFood_input"]' )[0].files[0] );
    //console.log( fd );


    fd.append('file', files);
    fd.append( 'id_restaurant',  $( 'form[klasa="addFood"]' ).attr( 'restaurant' ));
    fd.append( 'name',  $( 'input[name="name_input"]' ).val() );
    fd.append( 'price', $( 'input[name="price_input"]' ).val() );
    fd.append( 'description', $( 'input[name="description_input"]' ).val() );
    fd.append( 'waitingTime', $( 'input[name="waitingTime_input"]' ).val() );


    $( this ).append( p );

    $.ajax(
        {
            url: location.protocol + "//" + location.hostname  + location.pathname.replace('index.php', '') + 'app/addFood.php',
            method: 'post',
            data: fd,
            contentType: false,
            processData: false,
            success: function( str )
            {
                //console.log( str );
                p.html( str );
            },
            error: function()
            {
                console.log( 'Greška u Ajax pozivu...');
                p.html( 'ERROR in Ajax!' );
            }
        });
}



function addFoodImg( fd , p)
{
    //console.log( fd );

    $.ajax(
        {
            url: location.protocol + "//" + location.hostname  + location.pathname.replace('index.php', '') + 'app/addFoodImg.php',
            method: 'post',
            data: fd,
            contentType: false,
            processData: false,
            success: function( data )
            {
                if( data.hasOwnProperty( 'greska' ) ){
                    console.log( data.greska );
                    p.html( 'PICTURE ERROR in database' + data.greska);
                }
                else if( data.hasOwnProperty( 'rezultat' ) ){
                    p.html( data.rezultat +' Please refresh page to see changes!');
                    //console.log( data.rezultat );
                }
            },
            error: function()
            {
                console.log( 'Greška u Ajax pozivu...');
                p.html( 'ERROR in Ajax!' );
            }
        });
}

function obradi_removeFood()
{
    var p = $( '<p>' ), checkboxes = $( 'input.removeFood:checkbox:checked' );

    event.preventDefault();
    $( this ).append( p );

    if( checkboxes.length === 0 )
    {
        p.html( 'No food selected! Please select al least 1 food item from offering.' );
        return;
    }

    checkboxes.each(function(){
        //console.log( $(this).val() );

        $.ajax(
            {
                url: location.protocol + "//" + location.hostname  + location.pathname.replace('index.php', '') + 'app/removeFood.php',
                method: 'post',
                data:
                {
                    id: $(this).val(),
                },
                success: function( data )
                {
                    if( data.hasOwnProperty( 'greska' ) ){
                        console.log( data.greska );
                        p.html( 'ERROR in database' + data.greska);
                    }
                    else if( data.hasOwnProperty( 'rezultat' ) ){
                        p.html( data.rezultat +' Please refresh page to see changes!');
                        //console.log( data.rezultat );
                    }
                },
                error: function()
                {
                    console.log( 'Greška u Ajax pozivu...');
                    p.html( 'ERROR in Ajax!' );
                }
            });
    });


}
function changeFoodImage(  )
{
    var fd = new FormData();
    var p = $( '<p>' ), files = $( 'input[name="imgFood_edit"]' )[0].files[0];

    if(!$( 'input[id="che5"]:checked' ).length ) // ako nije postavljen checkbox onda ništa
        return;

    fd.append( 'file', files );

    //console.log( $( 'select.editFood option:selected' ).val() );
    //console.log( $( 'input[name="imgFood_edit"]' )[0] );
    //console.log( $( 'input[name="imgFood_edit"]' )[0].files[0] );
    //console.log( fd );


    fd.append( 'file', files );
    fd.append( 'id_food',  $( 'select[name="editFood"] option:selected' ).val() );

    $.ajax(
        {
            url: location.protocol + "//" + location.hostname  + location.pathname.replace('index.php', '') + 'app/changeFoodImg.php',
            method: 'post',
            data: fd,
            contentType: false,
            processData: false,
            success: function( str )
            {
                //console.log( str );
                p.html( str );
            },
            error: function()
            {
                console.log( 'Greška u Ajax pozivu...');
                p.html( 'ERROR in Ajax!' );
            }
        });
    return p;
}


function obradi_editFood(event)
{
    event.preventDefault();

    var name = $( 'input[type="text"][name="foodName"]').val(),
        price = $( 'input[type="number"][name="foodPrice"]').val(),
        description = $( 'input[type="text"][name="foodDescription"]').val(),
        time =  $( 'input[type="number"][name="foodWaitingTime"]').val(),
        //image =  $( 'input[type="file"][name="imgFood_edit"]'),
        p = $( '<p>' );
        console.log(name==='', price, description, time);
    
    var h = changeFoodImage(  );
    $(this).append(p).append(h);

    $.ajax(
        {
            url: location.protocol + "//" + location.hostname  + location.pathname.replace('index.php', '') + 'app/editFood.php',
            method: 'post',
            data:
            {
                id: $( 'select[name="editFood"] option:selected' ).val(),
                name: name,
                price: price,
                description: description,
                waitingTime: time
            },
            success: function( data )
            {
                if( data.hasOwnProperty( 'greska' ) ){
                    console.log( data.greska );
                    p.html( 'ERROR in database' + data.greska);
                }
                else if( data.hasOwnProperty( 'rezultat' ) ){
                    p.html( data.rezultat +' Please refresh page to see changes!');
                    console.log( data.rezultat );
                }
            },
            error: function()
            {
                console.log( 'Greška u Ajax pozivu...');
                p.html( 'ERROR in Ajax!' );

            }
        });
        

}

function obradi_changeDetails()
{
    event.preventDefault();

    var name = $( 'input[type="text"][name="name_change"]').val(),
        desc = $( 'input[type="text"][name="desc_change"]').val(),
        address = $( 'input[type="text"][name="address_change"]').val(),
        p = $( '<p>' );
    $( this ).append( p );
    $.ajax(
        {
            url: location.protocol + "//" + location.hostname  + location.pathname.replace('index.php', '') + 'app/changeDetails.php',
            method: 'post',
            data:
            {
                id: $( 'form[klasa="changeDetails"]' ).attr( 'restaurant' ),
                name: name,
                description: desc,
                address: address
            },
            success: function( data )
            {
                if( data.hasOwnProperty( 'greska' ) ){
                    console.log( data.greska );
                    p.html( 'ERROR in database' + data.greska);
                }
                else if( data.hasOwnProperty( 'rezultat' ) ){
                    p.html( data.rezultat +' Please refresh page to see changes!');
                    //console.log( data.rezultat );
                }
            },
            error: function()
            {
                console.log( 'Greška u Ajax pozivu...');
                p.html( 'ERROR in Ajax!' );

            }
        });
}

function getActiveOrders()
{
    //console.log(timestamp);
    //console.log( $( 'div.activeOrders' ).attr( 'id_restaurant' ) );
    $.ajax(
        {
            url: location.protocol + "//" + location.hostname  + location.pathname.replace('index.php', '') + 'app/restaurantCurrentOrders.php',
            method: 'get',
            data: 
            {
                timestamp: timestamp, 
                id_restaurant: $( 'div.activeOrders' ).attr( 'id_restaurant' )
            },
            success: function( data )
            {
                var div = $( 'div.activeOrders' );
                div.html('');
                
                if( data.hasOwnProperty( 'greska' ) ){
                    console.log( data.greska );
                    p.html( 'ERROR in database' + data.greska);
                }
                else if( data.hasOwnProperty( 'nema' ) ){
                    var p = $( '<p>' ).html( 'Trenutno nemate novih narudžbi!');
                    div.append(p);
                }
                else{
                    timestamp = data.timestamp;

                    console.log(data.active);

                    var tbl = $( '<table>' ), tr_head = $( '<thead>' ), tbody= $('<tbody>');

                    tr_head.html( '<tr><th>Status</th><th>Broj narudžbe</th><th>Broj klijenta</th><th>Vrijeme narudžbe</th><th>Ukupno</th><th>Popust</th><th>Napomena</th></tr>' );
                    tbl.append( tr_head)
                        .prop('class', 'table table-hover');
                    
                    
                    for( var i = 0; i < data.id_order.length; ++i )
                    {
                        var tr = $( '<tr orderid="'+data.id_order[i]+'">' );
                        var td_id_order = $( '<td>' ).html( data.id_order[i] );
                        var td_id_user = $( '<td>' ).html( data.id_user[i] );
                        var td_order_time = $( '<td>' ).html( data.order_time[i] );
                        var td_price_total = $( '<td>' ).html( data.price_total[i] );
                        var td_discount = $( '<td>' ).html( data.discount[i] + '%' );
                        var td_note = $( '<td>' ).html( data.note[i] );
                        var td_active = $( '<td>' ).append( orderStatus( parseInt(data.active[i]) ) );

                        tr.append( td_active )
                            .append( td_id_order )
                            .append( td_id_user )
                            .append( td_order_time )
                            .append( td_price_total )
                            .append( td_discount )
                            .append( td_note )
                            .on( 'click', prikazi );

                        tbody.append( tr );

                        //  red ispod za listu  koja će sadržavat detalje
                        var tr_detalji = $( '<tr prikazid="'+data.id_order[i]+'" style="display: none;">'); 
                        var td_detalji = $( '<td colspan="7">' );
                        var lista_za_narudbu = $( '<ul class="list-group-item">' );

                        // dohvatimo detalje
                        for( var j = 0; j < data.contains[i].name.length; ++j )
                        {
                            var li = $('<li class="list-group-item">').html( data.contains[i].quantity[j] +' &times '+ data.contains[i].name[j]);
                            lista_za_narudbu.append(li);
                        }

                        //  dodat gumb za prihvat ili otkaz novo pristigle narudžbe
                        if( parseInt(data.active[i]) === 1 )
                        {
                            var prihvati = $( '<button type="submit" class="btn btn-primary btn-block" name="prihvati" orderid="'+data.id_order[i]+'">').html('Prihvati narudžbu');
                            var odbij = $( '<button type="submit" class="btn btn-danger btn-block" name="odbij" orderid="'+data.id_order[i]+'">').html('Odbij narudžbu');
                            var inputVrijeme = $( '<div class="input-group mb-3" divVrijeme="'+data.id_order[i]+'">' ).html('<div class="input-group-prepend"><span class="input-group-text">Upiši vrijeme čekanja:</span></div>');
                            inputVrijeme.append( $('<input type="number" inputVrijeme="'+data.id_order[i]+'" min="1" step="1" class="form-control" placeholder="npr. 50" required>') );

                            odbij.on('click', refuseOrder );
                            prihvati.on('click', acceptOrder );

                            lista_za_narudbu.append(inputVrijeme )
                                .append( prihvati )
                                .append( odbij );
                            
                                //  prikaz notifokacije
                            $('.toast').toast('show');
                        }

                        td_detalji.append( lista_za_narudbu );
                        tr_detalji.append( td_detalji );
                        tbody.append( tr_detalji );

                    }

                    tbl.append( tbody );
                    div.html( tbl );
                    getActiveOrders();
                }
            },
            error: function( xhr, status )
            {
                //console.log( status );
                if( status === 'timeout' )
                    dohvatiCijene(); 
            }
        });
}


function acceptOrder(event)
{
    var vrijeme = $( 'input[inputVrijeme="'+$(event.target).attr('orderid')+'"]' );
    if(vrijeme.val()==='')
        return;
    $( 'button[orderid="'+$(event.target).attr('orderid')+'"]' ).remove();
    changeOrderStatus(2, $(event.target).attr('orderid'), vrijeme.val());

}

function refuseOrder(event)
{   
    

    $( 'button[orderid="'+$(event.target).attr('orderid')+'"]' ).remove();
    $( 'div[divVrijeme="'+$(event.target).attr('orderid')+'"]' ).remove();
    changeOrderStatus(-1, $(event.target).attr('orderid'));
    //$( 'tr[orderid="7"]').remove();
    //$( 'tr[prikazid="7"]').remove();

}

function changeOrderStatus(newStatus, orderID, vrijeme=-1)
{
    $.ajax(
        {
            url: location.protocol + "//" + location.hostname  + location.pathname.replace('index.php', '') + 'app/changeOrderStatus.php',
            method: 'get',
            data:
            {
                order_id: orderID,
                status: newStatus,
                vrijeme: vrijeme
            },
            success: function( data )
            {
                if( data.hasOwnProperty( 'greska' ) ){
                    console.log( data.greska );
                }
//                else if( data.hasOwnProperty( 'rezultat' ) ){}

            },
            error: function()
            {
                console.log( 'Greška u Ajax pozivu... changeOrderStatus');
            }
        });
}

function prikazi(event)
{
    var tr_select = $(event.target).parent();
    var skriven = $( 'tr[prikazid="'+tr_select.attr('orderid')+'"]' );
    
    if( skriven.is(":visible"))
        skriven.hide();
    else 
        skriven.show();
}

function orderStatus( code)       // za status stavljam oznaku
{
    var oznaka= $( '<span>')
    if( code === 1)
    {
        oznaka.prop('class', 'badge badge-primary')
            .html('Novo');
    }
    else if( code === 2){
        oznaka.prop('class', 'badge badge-success')
        .html('Prihvaćena');
    }
    else if( code === 3){
        oznaka.prop('class', 'badge badge-info')
        .html('Dostaljač prihvatio');
    }
    else if( code === 0){
        oznaka.prop('class', 'badge badge-secondary')
        .html('Dostavljena');
    }
    else if( code === -1){
        oznaka.prop('class', 'badge badge-danger')
        .html('Odbijeno');
    }
    else if( code === -2){
        oznaka.prop('class', 'badge badge-dark')
        .html('Nema dostavljača');
    }
    return oznaka;
}

function destroy( vari = null)
{
    var parent = vari;
    if( parent.attr('class') === 'okvir' )
        parent.remove();
    if( (parent = parent.parent() ).attr('class') === 'okvir' )
        parent.remove();
    else
        parent.parent().remove();
    location.reload();
   
}

function obradi_addPhotos(){
    var fd = new FormData();
    var p = $( '<p>' ), files = $( 'input[name="addPhotos"]' )[0].files[0];
    
    event.preventDefault();

    console.log( $( 'input[name="addPhotos"]' ) );


}

///////////////////////////  <link rel="stylesheet"  href="<?php echo __SITE_URL; ?>/css/preIgnore.css">
//       Fje za pastOrders.php

function show_details(event)
{
    var target = $(event.target);
    var details = $( 'tr[ordernumber="'+target.attr('ordernumber')+'"]')

    if( target.html().substr(0,6) !== 'Sakrij'){ //  otrij detalje
        details.show();
        target.html('Sakrij detalje &#8595;');
    }
    else{   //  sakrij detalje
        details.hide();
        target.html('Prikaži detalje  &#8592;');
    }

}


