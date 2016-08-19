if (typeof pistol88 == "undefined" || !pistol88) {
    var pistol88 = {};
}

pistol88.promocodeCreate = {
    init: function() {
        $(document).on('submit', '#promoCodesCreate form', this.addNew)
    },
    addNew: function() {
        var form = $(this);
        var data = $(form).serialize();
        data = data+'&ajax=1';

        jQuery.post($(form).attr('action'), data,
            function(json) {
                if(json.result == 'success') {
                    $('#promoCodesCreate').modal('hide');
                    $('.place-to-new-promocode').val(json.promocode).focus().select();
                }
                else {
                    console.log(json.errors);
                    alert(json.errors);
                }

                return true;

            }, "json");
            
        return false;
    },
};

pistol88.promocodeCreate.init();