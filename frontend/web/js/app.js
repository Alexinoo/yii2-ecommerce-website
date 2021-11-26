$(document).ready(function(){

const $cartQuantity  = $('#cart-quantity');

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

})