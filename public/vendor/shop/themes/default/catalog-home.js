$(document).ready(function(){
    getProductOfCategoryId(15, 'sub_featured_products');
    getProductOfCategoryId(16, 'best_selling_products');
    getProductOfCategoryId(17, 'lastest_products');
    getProductOfCategoryId(18, 'top_rated_products');
    function getProductOfCategoryId(cate_id, html_obj_add){
        $.ajax({
            method: 'GET',
            dataType: 'json',
            url: '/jsonapi/product?filter[f_catid]=' + cate_id + '&include=media,price', // returned from OPTIONS call
        }).done( function( result ) {
            // console.log(result.data)
            var data = result.data;
            var included = result.included;
            var products = [];
            var medias = [];
            var prices = [];

            for(var i = 0; i < included.length; i++){ // lay danh sach product thuoc catalog
                if(included[i].type == 'media'){
                    medias.push(included[i]);
                }
                if(included[i].type == 'price'){
                    prices.push(included[i]);
                }
            }

            for(var i = 0; i < data.length; i++){ // lay danh sach product thuoc catalog
                var product = data[i].attributes;
                var metalistMedia = data[i].relationships.media.data;
                var metalistPrice = data[i].relationships.price.data;
                console.log(metalistPrice)
                product.images = [];
                product.prices = [];
                
                for(var j = 0; j < metalistMedia.length; j++){ // lay danh sach anh cua moi product
                    var image = medias.find(x => x.id === metalistMedia[j].id).attributes['media.preview'];
                    product.images.push(image)
                }
                
                for(var j = 0; j < metalistPrice.length; j++){ // lay danh sach gia cua moi product
                    var price = prices.find(x => x.id === metalistPrice[j].id).attributes['price.value'];
                    product.prices.push(price)
                }
                products.push(product);
            }
            console.log(products);
            var html = '';
            for(var i = 0; i < products.length; i++){
                html = '<div class="product-default left-details product-widget">\
                            <figure>\
                                <a href="/shop/'+products[i]['product.url']+'">';
                                for(var j = 0; j < products[i].images.length; j++){
                                    if(products[i].images[j].includes("https://")){
                                        html += '<img src="'+products[i].images[j]+'" width="84" height="84" alt="product">';
                                    }else{
                                        html += '<img src="/aimeos/'+products[i].images[j]+'" width="84" height="84" alt="product">';
                                    }
                                }
                html +=                 '</a>\
                            </figure>\
                            <div class="product-details">\
                                <h3 class="product-title"> <a href="'+products[i]['product.url']+'">'+products[i]['product.label']+'</a>\
                                </h3>\
                                <div class="ratings-container">\
                                    <div class="product-ratings">\
                                        <span class="ratings" style="width:100%"></span>\
                                        <!-- End .ratings -->\
                                        <span class="tooltiptext tooltip-top"></span>\
                                    </div>\
                                    <!-- End .product-ratings -->\
                                </div>\
                                <!-- End .product-container -->\
                                <div class="price-box">\
                                    <span class="product-price">$'+products[i].prices[0]+'</span>\
                                </div>\
                                <!-- End .price-box -->\
                            </div>\
                            <!-- End .product-details -->\
                        </div>';
                $('#' + html_obj_add).append(html)
            }
        });
    }
})