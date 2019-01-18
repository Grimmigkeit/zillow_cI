<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Welcome to CodeIgniter</title>

	<script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

	<style type="text/css">

	h1 {
		color: #444;
		background-color: transparent;
		border-bottom: 1px solid #D0D0D0;
		font-size: 19px;
		font-weight: normal;
		margin: 0 0 14px 0;
		padding: 14px 15px 10px 15px;
	}

	.body {
		margin: 0 15px 0 15px;
	}

	.container {
		border: 1px solid #D0D0D0;
		box-shadow: 0 0 8px #D0D0D0;
	}

	form input[type=text],
	form button {
		display: inline-block;
	}


	</style>
</head>
<body>

<div class="container">
	<h1>Zillow info. Enter address, for example "2114 Bigelow Ave N, 98109"</h1>
	
	<div class="body">
		<form class="-form" type="post" action="">
			<input class="-address" type="text" name="address" placeholder="address, zip" required>
			<button class="-btn" type="submit">ok</button>
		</form>
		
		<div class="-images"></div>
		<p><span class="-street"></span> <span class="-price"></span></p>
		<p class="-rooms"></p>
		<p class="-sqft"></p>
		
	</div>
</div>

<script type="text/javascript">
	$( document ).ready( function() {

    $('.-form').on('submit', function(e) {
        e.preventDefault();

        var address = $('.-address').val(),
        	$btn    = $('.-btn'),
        	$images = $('.-images'),
        	$price  = $('.-price'),
			$rooms	= $('.-rooms'),
			$street	= $('.-street'),
			$sqft   = $('.-sqft');

    	$btn.attr('disabled', true);
    	$images.empty();
    	$price.empty();
    	$rooms.empty();
        $street.empty();
        $sqft.empty();

        $.post($(this).attr('action'), { address: address }, function() {})
        .done(function(data) {

        	if (data.error) {
        		alert(data.error);
        		return false;
        	}

        	$price.html(data.price);
        	$rooms.html(data.rooms);
            $street.html(data.street);
            $sqft.html(data.sqft);

            if (data.photos) {

            	$.each(data.photos, function(i, src) {
            		$('<img />')
                        .attr('src', src)
                        .appendTo($images);
            	});
            }

        })
        .fail(function() {
            alert('Error, try again');
        })
        .always(function() {
        	$btn.attr('disabled', false);
        });
    });

});
</script>

</body>
</html>