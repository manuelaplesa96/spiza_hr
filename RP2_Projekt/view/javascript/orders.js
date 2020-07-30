$( document ).ready( function(){
    var ord, p;
    $( "button[klasa='ocijeni']" ).on( 'click', show_form);
})

function show_form()
{
    var div = $( '<div>' ), title = $( '<h2>' ), box = $( '<div id="boks">' ), close = $( '<span>' );

    title.html( 'Ocijeni ovu narudžbu' )
        .css( 'color', 'black' );

    // link za zatvaranje boxa i okvira
    close.html('&times')
        .css( 'color', '#aaaaaa' )
        .css( 'float', 'right' )
        .css( 'font-size', '35')
        .css( 'font-weight', 'bold')
        .css( 'cursor', 'pointer' )
        .on( 'click', function(event){
            destroy($(event.target));
        })
        .on({
            mouseenter: function () {
                $(this).css('color', 'black')
            },
            mouseleave: function () {
                $(this).css( 'color', '#aaaaaa' );
            }
        });

    console.log( $( this ).attr( 'id' ) );
    ord = $( this ).attr( 'ord' );

    var form = $( 'form.oc' ).removeAttr( 'hidden' );

    console.log( ord );

     //  box za text i ostalo unutra okvira
     box.css( 'background-color', '#fefefe')
        .css( 'margin', 'auto')
        .css( 'padding', '20px')
        .css( 'border', '1px solid, /888')
        .css( 'width', '80%' )
        .prop( 'class', 'box')
        .append( close )
        .append( title )
        .append( form );

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

    //   Zatvara prozor ako se klikne van boxa
    /*div.on( 'click', function(event){
            if( $(event.target).attr('class') === 'okvir' )
                destroy( $(event.target) );
        });*/


    $(this).after(div);

    form.on( 'submit', obradi_formu );
}

function obradi_formu(){
    event.preventDefault();

    var feedback = $( 'input[name="recenzija"]').val(),
        rating = $( 'input[name="ocjena"]').val();
    p = $( '<p id="poruka">' );
    $( "#boks" ).append( p );

    console.log( feedback, rating );
    $.ajax(
        {
            url: location.protocol + "//" + location.hostname  + location.pathname.replace('index.php', '') + 'app/addFeedback.php',
            method: 'post',
            data:
            {
                id: ord,
                feedback: feedback,
                rating: rating
            },
            success: function( data )
            {
                if( data.hasOwnProperty( 'greska' ) ){
                    console.log( data.greska );
                    $( "#poruka" ).html( data.greska);
                    //$( "#boks" ).append( "<p>" + data.greska + "</p>")
                }
                else if( data.hasOwnProperty( 'rezultat' ) ){
                    $( "#poruka" ).html( 'Recenzija uspješno poslana!' );
                    //$( "#boks" ).append( "<p>Recenzija uspješno poslana!</p>" );
                    console.log( data.rezultat );
                    $( "form.oc" ).attr( "hidden", true );
                }
            },
            error: function()
            {
                console.log( 'Greška u Ajax pozivu...');
                p.html( 'ERROR in Ajax!' );

            }
        });
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