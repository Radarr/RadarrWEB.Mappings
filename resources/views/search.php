<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">
    <base href="../" target="">
    
     <?php
	    include("../api/api_start.php");
	    header('Content-type: text/html; charset=utf-8');
	    
	    $query = $_GET["query"];
	    
	    $match = false;
	    //var_dump($query);
	    preg_match('/^(?<title>(?![([]).+?)?(?:(?:[-\W](?<![)[!]))*(?<year>(19|20)\d{2}(?!p|i|\d+|]|\W\d+)))+(\W+|_|$)(?!\\\\)/', $query, $match);

	    //preg_match("/^(?<title>(?![(\[]).+?)?(?:(?:[-_\W](?<![)\[!]))*(?<year>(19|20)\d{2}(?!p|i|\d+|\]|\W\d+)))+(\W+|_|$)(?!\\)/gi", $query, $output_array);
	    //var_dump($output_array);
	    
	    $title = $query;
	    
	    $year = false;
	    
	    if ($match) {
		    //var_dump($match);
		    $title = $match["title"];
		    $year = $match["year"];
	    }
	    
	    $title = clean_title($title);
	    
	    $results = array();
	    
	    $result = array();
	    
	    if ($year != false) {
	    	$result = $db->query("SELECT res.* FROM ((SELECT m.*, map.id as map_id, COUNT(m.id) as mappings_count FROM movies m, leonard1_mappings.mappings map WHERE m.clean_title LIKE '%$title%' AND m.release_year = $year AND map.tmdbid = m.id GROUP BY m.id) UNION (SELECT m.*, 0 as map_id, 0 as mappings_count FROM movies m, leonard1_mappings.mappings map WHERE m.clean_title LIKE '%$title%' AND m.release_year = $year AND map.tmdbid != m.id)) res GROUP BY res.id ORDER BY res.popularity DESC") or die(mysqli_error($db));
	    } else {
		    $result = $db->query("SELECT res.* FROM ((SELECT m.*, map.id as map_id, COUNT(m.id) as mappings_count FROM movies m, leonard1_mappings.mappings map WHERE m.clean_title LIKE '%$title%' AND map.tmdbid = m.id GROUP BY m.id) UNION (SELECT m.*, 0 as map_id, 0 as mappings_count FROM movies m, leonard1_mappings.mappings map WHERE m.clean_title LIKE '%$title%' AND map.tmdbid != m.id)) res GROUP BY res.id ORDER BY res.popularity DESC") or die(mysqli_error($db));
	    }

	
	while ($arr = $result->fetch_assoc()) {
		//var_dump($index);
		$results[] = utf8ize($arr);
// 		var_dump($arr);
	}
	    
	    ?>

    <title><?php echo_plural("Search Result", count($results)); ?>: <?php echo $query; ?></title>

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
        <form class="navbar-form navbar-right" action="search.php">
			<div class="input-group search-group">
              <input type="text" placeholder="Search" class="form-control" id="search" name="query" autocomplete="off">
              <div class="input-group-btn">
            <button type="submit" class="btn btn-success">Go</button>
              </div>
			</div>
          </form>
        </div>
      </div>

      <div class="jumbotron">
	  	<h1>Found <?php echo_plural("result", count($results)); ?> for '<?php echo $query; ?>'</h1>
	  	<?php
		  	
		  	foreach ($results as $movie) { 
			  	
			  	$title_and_year = "{$movie['title']} ({$movie['release_year']})";
	$poster_url = "https://image.tmdb.org/t/p/w300_and_h450_bestv2{$movie['poster_path']}";
		  	?>
			  	<div class="row vertical-center result">
        <div class="col-md-4"><img src="<?php echo $poster_url;?>"></div>
        <div class="col-md-8"><a href="movie/<?php echo $movie["id"]; ?>"><h2><?php echo $title_and_year; ?></h2></a>
        <p class="lead"><?php echo $movie["overview"];?></p>
        <p>
	        <a href="http://imdb.com/title/<?php echo $movie["imdb_id"]; ?>"><span class="label label-primary">IMDB</span></a>
	        <a href="http://themoviedb.com/movie/<?php echo $movie["id"]; ?>"><span class="label label-primary">TMDB</span></a>
        </p>
        <p class=".small">
	        Currently <?php echo_plural("mapping", $movie["mappings_count"]);?>.
        </p></div>
		      </div>
      <hr>
		  	<?php }
		  	
		  	?>
      </div>

      <footer class="footer">
        <p>&copy; 2017 Radarr</p>
      </footer>
    </div> <!-- /container -->
    
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="dist/js/bootstrap.min.js"></script>
    <script src="bootstrap-typeahead.min.js"></script>
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
			 window.location = window.location.href + "movie/"+item.value;
		
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
	    
	</script>
  </body>
</html>
