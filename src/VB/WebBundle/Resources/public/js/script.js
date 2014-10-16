$(document).ready(function() {

    $('select.top-cat-menu').change(function() {
        var loc = ($(this).find('option:selected').val());
        window.location = loc;

    });

    $('[data-hover="dropdown"]').dropdownHover();
//    $('.basket .close-btn').click(function() {
//        $(this).parent().parent().fadeOut(function() {
//            $(this).remove();
//            checkBasketDropdown(true);
//        });
//    });

    $('[data-placeholder]').focus(function() {
        var input = $(this);
        if (input.val() == input.attr('data-placeholder')) {
            input.val('');
            // input.removeClass('placeholder');
        }
    }).blur(function() {
            var input = $(this);
            if (input.val() == '' || input.val() == input.attr('data-placeholder')) {
                input.addClass('placeholder');
                input.val(input.attr('data-placeholder'));
            }
        }).blur();

    $('[data-placeholder]').parents('form').submit(function() {
        $(this).find('[data-placeholder]').each(function() {
            var input = $(this);
            if (input.val() == input.attr('data-placeholder')) {
                input.val('');
            }
        })
    });

    if($(".chosen-select").length>0){
        $(".chosen-select").chosen({disable_search_threshold: 10});
    }

    if ($('.product-gallery').length > 0) {
        $('.product-gallery').flexslider({
            animation: "slide",
            pauseOnAction: false,
            directionNav: false,

            controlNav: "thumbnails"
        });
    }

//    $.get(Routing.generate('facebook_access_token'), function(fbAccessToken) {
//        $.ajax({
//            url: "https://graph.facebook.com/1455693958001368/feed",
//            dataType: "jsonp",
//            data: {
//                access_token: fbAccessToken,
//                limit: 5
//            },
//            success: function( response ) {// server response
//                var html = '';
//                for (var i=0,len=response.data.length; i<len; i++)
//                {
//                    var entry = response.data[i];
//
//                    var img = '';
//                    if(entry.picture){
//                        img = "<img style='width: 30px' src='"+entry.picture+"'>"
//                    }
//                    html += '<li>';
//                    html += "<a href='"+entry.link+"'>"+img+" "+entry.name+"</a>";
//                    html += '</li>';
//                }
//                $('.facebook-feeds-holder').append(html);
//            }
//        });
//    });

    if($('.map-holder').length>0){
        $('.map').gmap3({
            map:{
                options:{
                    center:[21.0333,105.85],

                    zoom:15,
                    mapTypeControl: true,

                    navigationControl: true,
                    scrollwheel: false,
                    streetViewControl: true
                }
            }
//            ,
//            marker:{
//                latLng:[21.0333,105.85]
//            }
        });
    }

    function checkBasketDropdown(remove) {
        if (remove) {
            cn = parseInt($('.basket-item-count').text());
            nn = cn - 1;
            $('.basket-item-count').text(nn);
        }
        if ($('.basket .basket-item').length <= 0) {
            $('.basket .dropdown-menu').prepend("<li class='empty'>Empty</li>");
        }
    }

    // google custom search
    var resultBox = $('.search-result');
    $('#search-box').keyup(function(){
        var searchResult = google.search.cse.element.getElement('virgosbeautysearch');
        var query = $.trim($(this).val());
        if(query != '' && $(window).width() > 767){
            searchResult.execute(query);
            if(resultBox)
                resultBox.show();
        }else{
            searchResult.clearAllResults();
            if(resultBox)
                resultBox.hide();
        }
    });
    $('#search-box').focusin(function(){
        var query = $.trim($(this).val());
        if(query != '' && $(window).width() > 767){
            if(resultBox)
                resultBox.show();
        }
    });
    $('body').on('click tap', function(e) {
        if(!$(e.target).parents('.search-field-holder').length){
            if(resultBox)
                resultBox.hide();
        }
    });
    // end google custom search
});
