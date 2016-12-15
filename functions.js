function api(method,id,number) 
{
	$.ajax(
		{
	    type: "GET",
	    url: "inc/api.php",
		data: 
		{
			method : method,
			id : id,
			number : number
		},
		    success: function(response){
		    	if (response == 'added')
		    	{
		    		alert(number + ' items were added to your cart');
		    		location.reload();
		    	}
		    	if (response == 'removed')
		    	{
		    		alert(number + ' items were removed from your cart');
		    		location.reload();
		    	}
		    	if (response == 'star.added')
		    	{
		    		alert('You added your vote successfully');
		    		location.reload();
		    	}
		    	if (response == 'selected.transport')
		    	{
		    		alert('You selected a transport successfully');
		    		location.reload();
		    	}
		    	if (response == 'deselected.transport')
		    	{
		    		alert('You deselected a transport successfully');
		    		location.reload();
		    	}
		    	if (response == 'checkout.success')
		    	{
		    		alert('You completed the purchase successfully');
		    		location.reload();
		    	}
		    	if (response == 'checkout.error')
		    	{
		    		alert('Sorry, you do not have enough money');
		    	}
		    	if (response == 'checkout.error.transport')
		    	{
		    		alert('Sorry, you must select a transport');
		    	}
		    }
	    }
	);
}
