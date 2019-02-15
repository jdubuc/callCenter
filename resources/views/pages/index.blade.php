@extends('app')
@section('view-css')
	<style>
		.container {
			text-align: center;
			display: table-cell;
			vertical-align: middle;
		}

		.content {
			text-align: center;
			display: inline-block;
		}

		.title {
			font-size: 96px;
			margin-bottom: 40px;
		}

		.quote {
			font-size: 24px;
		}
	</style>
@endsection
@section('content')
		<div class="container">
			<div class="content">
			<div class="title">Call Center</div>
				<div class="title">Laravel 5</div>
				<div id="results"></div>
				
			</div>
			</div>
		</div>
@endsection
<script type="text/javascript" src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script type="text/javascript">
jQuery(document).ready(function(){

	  $.ajax({
	  	type: "GET",
    url: "http://localhost/callcenterWs/public/items",

    

    //data: {accion: "iniciarSesion", user: user, pssw: pssw},

    dataType: 'json',

    success:function(data){  
    	//alert(data);
    	var html_str ='<ul>';
       //console.log(data);
       for(var i=0; i<data.length; i++)
       {
       html_str += '<li>' + data[i].title + '</li>';
       }
       //e.preventDefault();
       //html_str +='</ul>';
       jQuery('#results').html(html_str);
      

    }

  });

}); 
</script>