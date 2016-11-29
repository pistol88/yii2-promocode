if (typeof usesgraphcrt == "undefined" || !usesgraphcrt) {
    var usesgraphcrt = {};
}

usesgraphcrt.promocode = {
    init: function () {
        $targetModel = $('[data-role=target-model]');
        $targetsList = $('[data-role=model-list]');
        $productModels = {};


        $(document).on('click','[data-role=remove-target-item]',function () {

            if(!confirm('Вы действительно хотите удалить этот элемент?')) {
                return false;
            }
            var delete_url = $(this).data('href');
            var block = $(this).closest('[data-role=item]');
            var data = {
                'promocodeId' : usesgraphcrt.promocode.getUrlVar()["id"],
                'targetModel' : $(this).data('target-model'),
                'targetModelId' : $(this).data('target-model-id')
            };
            usesgraphcrt.promocode.deleteTargetItem(delete_url,data,block);
            $(currentBlock).remove();
        });
    },

    getTargetItem: function (targetModelId,targetModel) {
        var check = $(document).find('[data-name=' + targetModel.replace(/\\/g,'') + targetModelId + ']');
        
        if (check.length > 0) {
            return true;
        }
        return false;
    },


    deleteTargetItem: function (url,data,$block) {
        currentBlock = $block;
        $.ajax({
            type: 'POST',
            url: url,
            data: {data: data},
            success: function (response) {
                if (response.status === 'success') {
                    $(currentBlock).remove();
                }
            },
            fail: function () {
                alert('Что-то пошло не так :\'(');
            }
        });
    },

    updateModelList: function (targetModelId,targetModel,targetModelName) {
        $targetsList.append($($('<tr data-role="item">')
            .append('<td><label>'+targetModelName +'</label><input type="hidden" data-role="product-model" ' +
                'name="targetModels[' + targetModel + '][' + targetModelId + ']"' +
                ' data-name="' + targetModel.replace(/\\/g,'') + targetModelId + '"/></td>')
            .append('<td><span class="btn glyphicon glyphicon-remove" style="color: red;" data-role="remove-target-item"' +
        ' data-target-model="' + targetModel + '"' +
        ' data-target-model-id="' + targetModelId + '"></span></td>')
        ));
    },



    getUrlVar: function () {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
        vars[key] = value;
    });
    return vars;
}



};
usesgraphcrt.promocode.init();