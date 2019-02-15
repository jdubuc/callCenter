<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Call Center</title>

	<!--<link href="{{ asset('/css/app.css') }}" rel="stylesheet">-->

	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css"/>
	<!--<link rel="stylesheet" href="../css/table.css"/>-->
	<!--<link href="{{ URL::asset('http://localhost/wp/wickedpicker.min.css') }}" rel="stylesheet">-->

	<!-- Fonts -->
	<!--<link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'> -->
	<link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
    <!-- Scripts -->
  <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>
  <!--<script src="{{ URL::asset('http://localhost/wp/wickedpicker.min.js') }}"></script>-->

  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
	@yield('view-css')

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
	  <style>
.container {
  width: 1000px;
  margin: auto;
  background: #FFFFFF;
  padding: 10px 20px;
  /*margin-top: 10%;*/
}

h2 { 
  font-size: 16px; 
  font-weight: lighter; 
  background: rgba(255,60,0,0.7); 
  display: inline-block;
  padding: 5px 20px;
  margin-left: -20px;
  color: #FFFFFF;
  letter-spacing: 1px;
  text-transform: uppercase;
}

ol.ordered-list {
    counter-reset:li; /* Initiate a counter */
    margin-left:0; /* Remove the default left margin */
    padding-left:0; /* Remove the default left padding */
}
ol.ordered-list > li {
    position:relative; /* Create a positioning context */
    margin:0 0 6px 2em; /* Give each list item a left margin to make room for the numbers */
    padding:4px 8px; /* Add some spacing around the content */
    list-style:none; /* Disable the normal item numbering */
    border-top:2px solid #ccc;
    background:#fff;
    cursor: default
}
ol.ordered-list > li:before {
    content:counter(li); /* Use the counter as content */
    counter-increment:li; /* Increment the counter by 1 */
    /* Position and style the number */
    position:absolute;
    top:-2px;
    left:-2em;
    -moz-box-sizing:border-box;
    -webkit-box-sizing:border-box;
    box-sizing:border-box;
    width:2em;
    /* Some space between the number and the content in browsers that support
       generated content but not positioning it (Camino 2 is one example) */
    margin-right:8px;
    padding:4px;
    border-top:2px solid #ccc;
    color:#fff;
    background:#ccc;
    font-weight:bold;
    font-family:"Helvetica Neue", Arial, sans-serif;
    text-align:center;
}

ol.ordered-list > li:hover, ol > li:hover {
    border-color: rgba(255,60,0,0.7);
    background-color: #f5f5f5; }

ol.ordered-list > li:hover:before, ol > li:hover:before {
    background-color: rgba(255,60,0,0.7);
    border-color: rgba(255,60,0,0.7); }

.ordered-list li ol,
.ordered-list li ul {margin-top:6px;}
.ordered-list ol ol li:last-child {margin-bottom:0;}




textarea {
  width: 100%;
  min-height: 100px;
  resize: none;
  border: 1px solid #ddd;
  outline: none;
  padding: 0.5rem;
  color: #666;
  box-shadow: inset 0 0 0.25rem #ddd;
  &:focus {
    outline: none;
    border: 1px solid darken(#ddd, 5%);
    box-shadow: inset 0 0 0.5rem darken(#ddd, 5%);
  }
  &[placeholder] { 
    font-style: italic;
    font-size: 0.875rem;
  }
}

#the-count {
  float: right;
  padding: 0.1rem 0 0 0;
  font-size: 0.875rem;
}
#q{
  width: 30%;
}

      .ui-tooltip, .arrow:after {
  
    border: 2px solid  white;
    /*width: 20%;
     word-wrap: break-word;*/
  }
  .ui-tooltip {
    padding: 10px 20px;
    color: black;
    
  
  }
  /* Tooltip container */
.tooltip {
    position: relative;
    display: inline-block;
    border-bottom: 1px dotted black; /* If you want dots under the hoverable text */
}

/* Tooltip text */
.tooltip .tooltiptext {
    visibility: hidden;
    width: 120px;
    background-color: black;
    color: #fff;
    text-align: center;
    padding: 5px 0;
    border-radius: 6px;
 
    /* Position the tooltip text - see examples below! */
    position: absolute;
    z-index: 1;
}

/* Show the tooltip text when you mouse over the tooltip container */
.tooltip:hover .tooltiptext {
    visibility: visible;
}
</style>
</head>
<body>
	@include('nav')

	<div class="container" style="margin-left: 10%;">
	@yield('content')
	</div>


<script>


function formEnterAsTab() {
  $("input").keydown(enterastab);
}
function formDisableEnter() {
    $("form input").keypress(disableEnter);

}
function enterastab(e) {
  if (e.keyCode==13) {
    var focusable = $('input,a,select,button,textarea').filter(':visible');
    focusable[focusable.index($(this))+1].focus();
    return false;
  }
}
function getKey(e){
  //compatibilidad con varios navegadores
  if(e.which)return e.which;
  if(e.keyCode)return e.keyCode;
}
function disableEnter(e) {
  if (getKey(e)==13) {
    console.log('DisableEnter2');
    $('button[type=submit] .default').click();
    return false;
  } else {
    return true;
  }
}
$( document ).ready(function() {
   var textarea = $('#textarea');
     textarea.hide();
     formDisableEnter();
     console.log('DisableEnter');
});


$('#select').change(function(){
        var textarea = $('#textarea');
        var select   = $(this).val();

       
        if (select == '9'){
          textarea.show();
        }
        if (select != '9'){
          textarea.hide();
        }});

function optionTextArea() {
 var textarea = $('#textarea');
        var select = $('#select').find('option:selected').val();
        console.log(select);
        if (select == '9'){
          textarea.show();
        }
        if (select != '9'){
          textarea.hide();
        }
}
$( document ).ready(function() {
 optionTextArea();
});



 




</script>

	@yield('view-js')
</body>
</html>
