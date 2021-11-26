$(document).ready(function(){

const $cartQuantity  = $('#cart-quantity');

const $itemQuantities = $('.item-quantity');

$('.add-to-cart-btn').click(function(e){

    e.preventDefault();

    const  $this = $(e.target);

    // const id =$('.product-item' ).attr('data-key'); - Does not work
    const id =$this.closest('.product-item' ).data('key');
    
    $.ajax({
        url :  $this.attr('href'),
        method : 'post',
        data : { 
            id : id 
        },
        success : function(data){
            $cartQuantity.text( parseInt($cartQuantity.text() || 0 ) + 1 );
        },
        error : (error)=>{
            console.log(error);
        }
    });
})

$itemQuantities.change(function(e){

    const $this = $(e.target);
    const $tr = $this.closest('tr')
    
    const $id = $tr.data('id');
    const $url = $tr.data('url');

    $.ajax({
        method : 'post',
        url : $url,
        data :{
            id : $id ,
            quantity : $this.val()
        } ,
        success : function(totalQuantity){
           $cartQuantity.text(totalQuantity);
        }
    })

})

})