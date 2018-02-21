

// keeps nibble character count up to date
function countChars(el)
{
    $('#count').html($(el).val().length + 1);
}


// toggles the nibble post box
function toggleBox()
{
    $('#box').toggle();

    countChars('#chars');
}

$(document).ready(function() {
    // Hides nibble box when clicking off of it
    $('.container').click(function(evt) {
        if(evt.target.nodeName =='BUTTON') {
            return false;
        }
        switch(evt.target.id) {
            case 'box':
            case 'chars':
                return false;
        }

        $('#box').hide();
    });

    // initially disable the button
    $('#nibble').prop('disabled', true);

    // When at least one character is in the nibble, enable the button
    $('#chars').on('input', function() {
        if ($(this).val().length > 0) {

            $('#nibble').prop('disabled', false);
        } else {

            $('#nibble').prop('disabled', true);
        }
    });


    $('#nibble').click(function(e) {

       e.preventDefault();

       var form = $(this).closest('form');


       //post nibble
        $.ajax({
            type: "POST",
            url: '/post',
            data: form.serialize(),
            success: function(response) {

                if(response.error) {
                    alert(response.error);

                    return false;
                }

                //reset box count
                $('#chars').val('');
                $('#count').html('0');

                /**
                 * gets the last nibble and uses it as template to produce a new nibble
                 */

                // get template
                var html = $('.nibbles').children().first().clone();

                $(html).find('p').replaceWith('<p>'+response.post+'</p>');
                $(html).find('.date').replaceWith('<span class="well-sm date">' + response.date + '</span>');

                $('.nibbles').prepend(html);
                $( "#box" ).fadeOut( "slow");
            },
            dataType: 'json'
        });

    });
});