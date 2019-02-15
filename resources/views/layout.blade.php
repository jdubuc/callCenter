<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>@yield('title', 'Zitat Bienvenido')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- Bootstrap --}}
   
    {{ HTML::style('assets/css/bootstrap.min.css', array('media' => 'screen')) }}
    {{ HTML::style('assets/css/demou.css', array('media' => 'screen')) }}
    {{ HTML::style('assets/css/componentu.css', array('media' => 'screen')) }}
    {{ HTML::style('assets/css/defaultmu.css', array('media' => 'screen')) }}
    {{ HTML::style('assets/css/componentmu.css', array('media' => 'screen')) }}
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

     
    {{ HTML::style('assets/css/wickedpicker.css', array('media' => 'screen')) }}
    {{ HTML::script('assets/js/wickedpicker.js') }}
    {{ HTML::script('assets/js/modernizr.custom.js') }}

<style>
    blockquote{
  display:block;
  background: #fff;
  padding: 15px 20px 15px 45px;
  margin: 0 0 20px;
  position: relative;
  
  /*Font*/
  font-family: Georgia, serif;
  font-size: 14px;
  line-height: 1.2;
  color: #666;

  /*Box Shadow - (Optional)*/
  -moz-box-shadow: 2px 2px 15px #ccc;
  -webkit-box-shadow: 2px 2px 15px #ccc;
  box-shadow: 2px 2px 15px #ccc;

  /*Borders - (Optional)*/
  border-left-style: solid;
  border-left-width: 15px;
  border-right-style: solid;
  border-right-width: 2px;    
}

/*blockquote::before{
  content: "\201C"; /*Unicode for Left Double Quote*/
  
  /*Font*/
 /* font-family: Georgia, serif;
  font-size: 60px;
  font-weight: bold;
  color: #999;*/
  
  /*Positioning*/
  /*position: absolute;
  left: 10px;
  top:5px;
  
}

/*blockquote::after{*/
  /*Reset to make sure*/
  /*content: "";
}*/

blockquote a{
  text-decoration: none;
  background: #eee;
  cursor: pointer;
  padding: 0 3px;
  color: #c76c0c;
}

blockquote a:hover{
 color: #666;
}

blockquote em{
  font-style: italic;
}

  /*Default Color Palette*/
blockquote.default{ 
  border-left-color: #656d77;
  border-right-color: #434a53;  
}

/*Grapefruit Color Palette*/
blockquote.grapefruit{
  border-left-color: #ed5565;
  border-right-color: #da4453;
}

/*Bittersweet Color Palette*/
blockquote.bittersweet{
  border-left-color: #fc6d58;
  border-right-color: #e95546;
}

/*Sunflower Color Palette*/
blockquote.sunflower{
  border-left-color: #ffcd69;
  border-right-color: #f6ba59;
}

/*Grass Color Palette*/
blockquote.grass{
  border-left-color: #9fd477;
  border-right-color: #8bc163;
}

/*Mint Color Palette*/
blockquote.mint{
  border-left-color: #46cfb0;
  border-right-color: #34bc9d;
}

/*Aqua Color Palette*/
blockquote.aqua{
  border-left-color: #4fc2e5;
  border-right-color: #3bb0d6;
}

/*Blue Jeans Color Palette*/
blockquote.bluejeans{
  border-left-color: #5e9de6;
  border-right-color: #4b8ad6;
}

/*Lavander Color Palette*/
blockquote.lavander{
  border-left-color: #ad93e6;
  border-right-color: #977bd5;
}

/*Pinkrose Color Palette*/
blockquote.pinkrose{
  border-left-color: #ed87bd;
  border-right-color: #d870a9;
}

/*Light Color Palette*/
blockquote.light{
  border-left-color: #f5f7fa;
  border-right-color: #e6e9ed;
}

/*Gray Color Palette*/
blockquote.gray{
  border-left-color: #ccd1d8;
  border-right-color: #aab2bc;
}


/* These CSS classes used just for Demo purpose */
.heading{
   font-family:Montez;
   text-align:center;
   font-size:30px;
}
code{
  color:#da4453;
}
span{
  font-weight:bolder;
  
}
h1{
  text-align:left;
  font-size:16px;
  font-family: 'Francois One', sans-serif;
}

span.Cdefault{
  color:#434a53;
}
span.Cgrapefruit{
  color:#da4453;
}
span.Cbittersweet{
  color:#e95546;
}
span.Csunflower{
  color:#f6ba59;
}
span.Cgrass{
  color:#8bc163;
}
span.Cmint{
  color:#34bc9d;
}
span.Caqua{
  color:#3bb0d6;
}
span.Cbluejeans{
  color:#4b8ad6;
}
span.Clavander{
  color:#977bd5;
}
span.Cpinkrose{
  color:#d870a9;
}
span.Clight{
  color:#e6e9ed;
}
span.Cgray{
  color:#aab2bc;
}

</style>
<style type="text/css" media="print">
    @page 
    {
        size: auto;   /* auto is the initial value */
        margin: 0mm;  /* this affects the margin in the printer settings */
        #botones{margin: 0mm; }
    }
</style>
    {{-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries --}}
    <!--[if lt IE 9]>
        {{ HTML::script('assets/js/html5shiv.js') }}
        {{ HTML::script('assets/js/respond.min.js') }}
    <![endif]-->
     <?php
      $paciente = paciente::orderBy('nombre')->get();
    ?>
    <script>
  $(function() {
    var projects = [
       <?php foreach ($paciente as $paciente) { ?>
        {
        value: "<?php echo $paciente->id ?>",
        label: "<?php echo $paciente->nombre. $paciente->apellido ?>",
        desc: "Cedula:<?php echo $paciente->cedula ?>"
        
        },
    <?php } ?>
    ];
 
    $( "#project" ).autocomplete({
      minLength: 0,
      source: projects,
      focus: function( event, ui ) {
        $( "#project" ).val( ui.item.label );
        return false;
      },
      select: function( event, ui ) {
        $( "#project" ).val( ui.item.label );
        $( "#project-id" ).val( ui.item.value );
        $( "#project-description" ).html( ui.item.desc );
        $( "#project-icon" ).attr( "src", "images/" + ui.item.icon );
 
        return false;
      }
    })
    .autocomplete( "instance" )._renderItem = function( ul, item ) {
      return $( "<li>" )
        .append( "<a>" + item.label + "<br>" + item.desc + "</a>" )
        .appendTo( ul );
    };
  });
  </script>
  </head>
  <body>
    <img src="<?php echo asset('assets/images/zitat.png'); ?>">
    {{-- Wrap all page content here --}}
    <div id="wrap">
      {{-- Begin page content --}}
      <div class="container">
        @yield('content')
      </div>
    </div>


<ul id="cbp-tm-menu" class="cbp-tm-menu">
        <li>
          <a href="/directoriomedico/public/">Inicio</a>
        </li>
        <!--<li>
          <a href="#">Informacion</a>
          <ul class="cbp-tm-submenu">
            <li><a href="#" class="cbp-tm-icon-archive">Sorrel desert</a></li>
            <li><a href="#" class="cbp-tm-icon-cog">Raisin kakadu</a></li>
            <li><a href="#" class="cbp-tm-icon-location">Plum salsify</a></li>
            <li><a href="#" class="cbp-tm-icon-users">Bok choy celtuce</a></li>
            <li><a href="#" class="cbp-tm-icon-earth">Onion endive</a></li>
            <li><a href="#" class="cbp-tm-icon-location">Bitterleaf</a></li>
            <li><a href="#" class="cbp-tm-icon-mobile">Sea lettuce</a></li>
          </ul>
        </li>
        <li>
          <a href="#">Contacto</a>
          <ul class="cbp-tm-submenu">
            <li><a href="#" class="cbp-tm-icon-archive">Brussels sprout</a></li>
          </ul>
        </li>-->
        <li>
          <a href="/directoriomedico/public/logout">Cerrar Sesión</a>
        </li>
      </ul>

    {{-- jQuery (necessary for Bootstrap's JavaScript plugins) --}}
    
    {{-- Include all compiled plugins (below), or include individual files as needed --}}
    {{ HTML::script('assets/js/bootstrap.min.js') }}
    {{ HTML::script('assets/js/admin.js') }}
  
    <script>
function myFunction() {
    alert("se agrego la relacion creo!");
}
</script>

{{ HTML::script('assets/js/cbpTooltipMenu.min.js') }}
    <script>
      var menu = new cbpTooltipMenu( document.getElementById( 'cbp-tm-menu' ) );
    </script>

  
    {{ HTML::script('assets/js/cbpFWTabs.js') }}
    <script>
      new CBPFWTabs( document.getElementById( 'tabs' ) );
    </script>


  </body>
</html>