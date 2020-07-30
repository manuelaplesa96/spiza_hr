var timestamp = 0; 

$(document).ready(function()
{
    getActiveOrders();
});


// dohvaćanje aktivnih i slobodnih narudžbi 
function getActiveOrders()
{
    $.ajax(
        {
            url: location.protocol + "//" + location.hostname  + location.pathname.replace('index.php', '') + 'app/deliverersActiveOrders.php',
            method: 'get',
            data: 
            {
                timestamp: timestamp
            },
            success: function( data )
            {
                if( data.hasOwnProperty( 'greska' ) ){
                    console.log( data.greska );
                    p.html( 'ERROR in database' + data.greska);
                }
                else if( data.hasOwnProperty( 'nema' ) ){
                    var div = $( 'div.avalableOrders' ), p = $( '<p>' ).html( 'Trenutno nemate slobodnih narudžbi!');
                    div.append(p);
                }
                else{
                    timestamp = data.timestamp;

                    var div = $( 'div.avalableOrders' ), tbl = $( '<table>' ), tr_head = $( '<thead>' ), tbody= $('<tbody>');
                
                    tr_head.html( '<tr><th>Status</th><th>Broj narudžbe</th><th>Restoran</th><th>Klijent</th><th>Adresa</th><th>Sadržaj narudžbe</th><th>Ukupno</th><th>Popust</th><th>Napomena</th><th></th></tr>' );
                    tbl.append( tr_head)
                        .prop('class', 'table table-hover');
                    
                    
                    for( var i = 0; i < data.id_order.length; ++i )
                    {
                        var tr = $( '<tr orderid="'+data.id_order[i]+'">' );
                        var td_id_order = $( '<td>' ).html( data.id_order[i] );
                        var td_restaurant = $( '<td>' ).html( data.restaurant[i] );
                        var td_user = $( '<td>' ).html( data.user[i] );
                        var td_address = $( '<td>' ).html( data.address[i] );
                        var td_food = $( '<td>' ).html( data.food[i] );
                        var td_price_total = $( '<td>' ).html( data.price_total[i] + ' kn');
                        var td_discount = $( '<td>' ).html( data.discount[i]+'%' );
                        var td_note = $( '<td>' ).html( data.note[i] );
                        var td_active = $( '<td>' ).append( orderStatus( parseInt(2) ) );

                        tr.append( td_active )
                            .append( td_id_order )
                            .append( td_restaurant )
                            .append( td_user )
                            .append(td_address)
                            .append(td_food)
                            .append( td_price_total )
                            .append( td_discount )
                            .append( td_note )
                            .on( 'click', prikazi );

                        tbody.append( tr );

                        //  red ispod za listu  koja će sadržavat detalje
                        var tr_detalji = $( '<tr prikazid="'+data.id_order[i]+'" style="display: none;">'); 
                        var td_detalji = $( '<td colspan="9">' );
                        var lista_za_narudbu = $( '<ul class="list-group-item">' );


                        //  GUMB ZA PRIHVATI 
                        var prihvati = $( '<button type="button" class="btn btn-primary btn-block" name="prihvati" orderid="'+data.id_order[i]+'">').html('Prihvati narudžbu');
                        var inputVrijeme = $( '<div class="input-group mb-3" divVrijeme="'+data.id_order[i]+'">' ).html('<div class="input-group-prepend"><span class="input-group-text">Upiši vrijeme čekanja:</span></div>');
                        
                        inputVrijeme.append( $('<input type="number" inputVrijeme="'+data.id_order[i]+'" min="0" step="1" class="form-control" placeholder="npr. 50" required>') );

                        prihvati.on('click', acceptOrder );

                        lista_za_narudbu.append(inputVrijeme )
                            .append( prihvati );
                        
                        td_detalji.append( lista_za_narudbu );
                        tr_detalji.append( td_detalji );
                        tbody.append( tr_detalji );
                    }
                    tbl.append( tbody );

                    div.html( tbl );
                    checkDeliveryAvalible($('#slobodne').attr('id_deliverer'));

                    getActiveOrders();
                }
            },
            error: function( xhr, status )
            {
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
    changeOrderStatus(3, $(event.target).attr('orderid'), vrijeme.val(), $('#slobodne').attr('id_deliverer'));
    var orderno= $(event.target).attr('orderid');
    $( 'tr[orderid="'+orderno+'"]').remove();
    $( 'tr[prikazid="'+orderno+'"]').remove();
}



function changeOrderStatus(newStatus, orderID, vrijeme=-1, id)
{
    $.ajax(
        {
            url: location.protocol + "//" + location.hostname  + location.pathname.replace('index.php', '') + 'app/changeOrderStatus.php',
            method: 'get',
            data:
            {
                id_deliverer: id,
                order_id: orderID,
                status: newStatus,
                vrijeme: vrijeme
            },
            success: function( data )
            {
                if( data.hasOwnProperty( 'greska' ) ){
                    console.log( data.greska );
                }
                checkDeliveryAvalible(id);
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
    if( code === 2)
    {
        oznaka.prop('class', 'badge badge-primary')
            .html('Novo');
    }
    else if( code === 1){
        oznaka.prop('class', 'badge badge-success')
        .html('Poslana narudžba');
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
function checkDeliveryAvalible( id)
{   console.log(id);
    $.ajax(
        {
            url: location.protocol + "//" + location.hostname  + location.pathname.replace('index.php', '') + 'app/deliverersFree.php',
            method: 'get',
            data:
            {
                id: id

            },
            success: function( data )
            {
                //console.log('dostav', data['rezultat']);
                //console.log( $( 'tr[orderid]'));
                if( parseInt(data['rezultat']) === 0 ){
                    $( 'tr[orderid]').off('click', prikazi);
                    $('.toast').toast('show');
                }
                
            },
            error: function()
            {
                console.log( 'Greška u Ajax pozivu... changeOrderStatus');
            }
        });
}
