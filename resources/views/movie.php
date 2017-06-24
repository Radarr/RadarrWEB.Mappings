<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
 <base href="../" target="">
    <?php
	    include("../api/api_start.php");
	    header('Content-type: text/html');
	    
	    $tmdbid = $_GET["tmdbid"];
	    
	    $movie = array();
	    
	    $result = $db->query("SELECT m.* FROM movies m WHERE m.id = $tmdbid") or die(mysqli_error($db));

	
	while ($arr = $result->fetch_assoc()) {
		//var_dump($index);
		$movie = utf8ize($arr);
// 		var_dump($arr);
	}
	
	$title_and_year = "{$movie['title']} ({$movie['release_year']})";
	$poster_url = "https://image.tmdb.org/t/p/original{$movie['poster_path']}";
	    
	    ?>
    <link rel="icon" href="favicon.ico">

    <title><?php echo $title_and_year;?> - Mappings</title>

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
         <a class="navbar-left" href=""><img class="pull-left" src="logo.svg" width="50px;" style="padding-right: 10px; opacity: 0.6;"><h3 class="text-muted pull-left vertical-center">Radarr Mappings</h3></a>        </div>
	    
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
	      <div class="row vertical-center">
        <div class="col-md-4"><img src="<?php echo $poster_url;?>" style="width: 300px;"></div>
        <div class="col-md-8"><h1><?php echo $title_and_year; ?></h1>
        <p class="lead"><?php echo $movie["overview"]; ?></p>
        <p>
	        <a href="http://imdb.com/title/<?php echo $movie["imdb_id"]; ?>"><span class="label label-primary">IMDB</span></a>
	        <a href="http://themoviedb.com/movie/<?php echo $movie["id"]; ?>"><span class="label label-primary">TMDB</span></a>
        </p></div>
		      
		      
      </div>
	      
        
      </div>

      <div class="row marketing">
	      <h4>Alternative Titles</h4>
	  	<table id="aka-titles" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
	            <th>ID</th>
                <th>Alternative Title</th>
                <th>Reports</th>
                <th></th>
                <th></th>
                <th>Feedback</th>
            </tr>
        </thead>
    </table>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.10.0/bootstrap-table.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.15/js/dataTables.bootstrap.min.js"></script>
	<link href="https://cdn.datatables.net/1.10.15/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.10.0/bootstrap-table.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="css/font-awesome.min.css">
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
		window.location = window.location.href.substr(0, window.location.href.lastIndexOf('/')+1)+item.value;
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

    $('#aka-titles').DataTable( {
        "ajax": {
	        "url" : "example_ajax.json",
	        "dataSrc" : ""
	    },
	    order: [[1, "asc"]],
	    paging: false,
	    info: false,
	    searching: false,
        "columns": [
            { "data": "id" },
            { "data": "aka_title" },
            { "data": "report_count" },
            { "data": "total_reports" },
            { "data": "locked" },
            { "data": "id" }
        ],
        "columnDefs": [
	        {
		        "orderable": false,
		        "targets": 0
	        },
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
	                var label = "label-default";
	                if (data > 3) {
		                label = "label-success";
	                }
	                if (data < 0) {
		                label = "label-danger"
	                }
                    return "<span class='label "+label+"' data-toggle='tooltip' title='"+row["total_reports"]+" Votes'>"+data+"<span>";
                },
                "targets": 2
            },
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
	                img = "<i class='fa fa-lock fa-lg' aria-hidden='true'></i>";
	                text = "Reporting locked"
	                if (data === false) {
		                text = "Reporting unlocked";
		            	img = "<i class='fa fa-unlock fa-lg' aria-hidden='true'></i>";   
	                }
                    return "<span data-toggle='tooltip' title='"+text+"'>"+img+"<span>";
                },
                "targets": 4,
                "orderable": false,
                "visible": false
            },
            {
	            "render": function ( data, type, row ) {
		            if (row["locked"] == true) {
			            img = "<i class='fa fa-lock fa-lg' aria-hidden='true'></i>";
						text = "Reporting locked";
						return "<span data-toggle='tooltip' title='"+text+"'>"+img+"<span>";
		            }
                    return "<span data-toggle='tooltip' style='cursor:pointer;' title='This mapping is correct.' onclick='vote(this, "+data+", 1);'><i class='fa fa-check fa-lg green-fa' aria-hidden='true'></i></span>&nbsp;&nbsp;<span data-toggle='tooltip' style='cursor:pointer;' title='This mapping is wrong!' onclick='vote(this, "+data+", 1);'><i class='fa fa-times fa-lg red-fa' aria-hidden='true'></i></span>";
                },
                "targets": 5,
                "orderable": false
            },
            { "visible": false,  "targets": [ 3 ] }
        ]
    } );
    
    $('body').tooltip({
    selector: '[data-toggle=tooltip]'
});

	function vote(elem, id, direction){
		alert("Voting: " + id);
	}
	    
	</script>
  </body>
</html>
