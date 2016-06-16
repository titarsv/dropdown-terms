jQuery(document).ready(function($){
    if($('body.taxonomy-product_cat').length || $('body.edit-tags-php').length){
        var list = $('#the-list').find('tr');
        list.each(function(){
            var dropdown_btn = $(this).find('.drop_children');
            var parent_id = $(this).find('.parent').text();
            var parent_btn = $('#tag-'+parent_id).find('.drop_children');
            if(parent_btn.length > 0){
                var data = parent_btn.data('children');
                if(data)
                    data[data.length] = $(this).attr('id');
                else
                    data = [$(this).attr('id')];
                parent_btn.data('children', data);
            }
            if(dropdown_btn.length == 0 || parent_btn.length > 0) {
                $(this).css('display', 'none');
                if(dropdown_btn.length > 0)
                    add_button_event(dropdown_btn);
            }else{
                $(this).css('display', 'table-row');
                add_button_event(dropdown_btn);
            }
        });
    }

    function add_button_event(obj){
        obj.on('click', function(e){
            e.preventDefault();
            var children = $(this).data('children');
            var i=0;
            if($(this).hasClass('active')){
                $(this).removeClass('active').text('+');
                for(i=0; i<children.length; i++){
                    $('#'+children[i]).css('display', 'none');
                }
            }else{
                $(this).addClass('active').text('-');
                for(i=0; i<children.length; i++){
                    $('#'+children[i]).css('display', 'table-row');
                }
            }
        });
    }

    var product_catchecklist = $('#product_catchecklist');
    if(product_catchecklist.length > 0){
        init_product_catchecklist(product_catchecklist);
    }

    var product_catchecklist_min = $('.product_cat-checklist');
    if(product_catchecklist_min.length > 0){
        $('.editinline').on('click', function(){
            setTimeout(function(){
                init_product_catchecklist($('.product_cat-checklist'));
            }, 100);
        });
    }

    function init_product_catchecklist(product_catchecklist){
        product_catchecklist.find('input:checked').parent().parent().addClass('active');
        product_catchecklist.find('input').on('change', function(){
            if($(this).prop('checked'))
                $(this).parent().parent().addClass('active');
            else {
                var parent = $(this).parent().parent();
                parent.find('.children input:checked').parent().parent().removeClass('active');
                parent.find('.children input').prop('checked', false);
                parent.removeClass('active');
            }
        });
    }
});