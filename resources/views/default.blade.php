<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">
    <base href="@yield('base_url')" target="">

    <title>@yield("title")</title>

    <!-- Bootstrap core CSS -->
    <link href="dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    

    <!-- Custom styles for this template -->
    <link href="jumbotron-narrow.css" rel="stylesheet">

    

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <div class="container">
      <div class="header clearfix">
	      <div class="navbar-header">
		      
          <a class="navbar-left" href=""><img class="pull-left" src="logo.svg" width="50px;" style="padding-right: 10px; opacity: 0.6;"><h3 class="text-muted pull-left vertical-center">Radarr Mappings</h3></a>
        </div>
	    
        
        <div class="row">
        <form class="navbar-form navbar-right" action="search.php" id="search-form" onsubmit="return sub(this);">
			<div class="input-group search-group">
              <input type="text" placeholder="Search" class="form-control" id="search" name="query" autocomplete="off">
              <div class="input-group-btn">
            <button type="submit" class="btn btn-success">Go</button>
              </div>
			</div>
          </form>
        </div>
      </div>

	      @yield("content")


      <footer class="footer">
        <p>&copy; 2017 Radarr</p>
      </footer>
    </div>
    
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="dist/js/bootstrap.min.js"></script>
    <script src="bootstrap-typeahead.min.js"></script>
    @yield("externalScripts")
    <script type="text/javascript">
	    /*$('#search').typeahead({
    source: function (query, process) {
        return $.get('https://api.radarr.video/searching/suggestions.php', { q: query }, function (data) {
            return process(data);
        });
    }
});*/

$('#search').typeahead({
	 ajax: 'https://api.radarr.video/searching/suggestions.php',
	 onSelect: function(item) {
			 window.location = "{{url("movie")}}" + "/"+item.value;
		
	},
	 render: function (items) {
            var that = this, display, isString = typeof that.options.displayField === 'string';
            
            ht = "<li><a href=''></a></li>";

            items = $(items).map(function (i, item) {
                if (typeof item === 'object') {
                    display = item["name"] + " ("+item["release_year"]+")";
                    i = $(ht).attr('data-value', item["id"]);
                    i.find("a").attr("href", "movie/" + item["id"]);
                }
                i.find('a').html(that.highlighter(display));
                return i[0];
            });

            this.$menu.html(items);
            return this;
        },
});

function sub(scope){
    window.location = window.location.href + "/../" + "@yield("base_url")" + "search/" + $("#search").val();
    console.log(window.location.href + "/../" + "@yield("base_url")" + "search/" + $("#search").val());
    return false;
}

@yield("javascript")
	    
	</script>
  </body>
</html>
