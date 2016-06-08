if (typeof pistol88 == "undefined" || !pistol88) {
    var pistol88 = {};
}

pistol88.promocode = {
    init: function() {
        $(document).on('click', '.promo-code-enter-btn', this.enter);
        $(document).on('click', '.promo-code-clear-btn', this.clear);
        
        return true;
    },
    clear: function() {
        var form = $(this).parents('form');
        var data = $(form).serialize();
        data = data+'&clear=1';
        
        jQuery.post($(form).attr('action'), data,
            function(json) {
                if(json.result == 'success') {
                    $(form).find('input[type=text]').css({'border': '1px solid #ccc'}).val('');
                    $(form).find('.promo-code-discount').remove();
                    if(json.newCost) {
                        $('.pistol88-cart-price').html(json.newCost);
                    }
                }
                else {
                    $(form).find('input[type=text]').css({'border': '1px solid red'});
                    console.log(json.errors);
                }

                alert(json.message);
                
                return true;

            }, "json");
            
        return false;
    },
    enter: function() {
        var form = $(this).parents('form');
        var data = $(form).serialize();

        jQuery.post($(form).attr('action'), data,
            function(json) {
                if(json.result == 'success') {
                    $(form).find('input[type=text]').css({'border': '1px solid green'});

                    if(json.newCost) {
                        $('.pistol88-cart-price').html(json.newCost);
                    }
                }
                else {
                    $(form).find('input[type=text]').css({'border': '1px solid red'});
                    console.log(json.errors);
                }
                alert(json.message);
                
                return true;

            }, "json");
            
        return false;
    }
};

pistol88.promocode.init();