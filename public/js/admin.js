$(document).ready(function(){




//add new product image
$('#add-product-image-button').click(function(){
	
	var input = $('script#product-image-field-template').html();
	var count = $('#product-image-form').data('fieldcount');
	var newField = $('#product-image-form .fields').append(input);

	$('#product-image-form').data('fieldcount', count + 1);	

	newField.find('.image-field-path').attr('name', 'image[' + count + '][path]');
	newField.find('.image-field-order').attr('name', 'image[' + count + '][order]');
	newField.find('.image-field-delete').attr('name', 'image[' + count + '][delete]');

});




}); /** end document ready */