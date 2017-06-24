@extends('default')
@section("base_url")
../@stop
@section("title")
	{{$movie->title}}
@stop
@section('content')
<div class="jumbotron">
	      <div class="row vertical-center">
        <div class="col-md-4"><img src="https://image.tmdb.org/t/p/original{{$movie->poster_path}}" style="width: 300px;"></div>
        <div class="col-md-8"><h1>{{$movie->title}} ({{$movie->release_year}})</h1>
        <p class="lead"><?php echo $movie["overview"]; ?></p>
        <p>
	        <a href="http://imdb.com/title/{{$movie->imdb_id}}"><span class="label label-primary">IMDB</span></a>
	        <a href="http://themoviedb.com/movie/{{$movie->id}}"><span class="label label-primary">TMDB</span></a>
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

<div class="row marketing">
    <h4>Alternative Years</h4>
    <table id="aka-years" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
        <tr>
            <th>ID</th>
            <th>Alternative Year</th>
            <th>Reports</th>
            <th></th>
            <th></th>
            <th>Feedback</th>
        </tr>
        </thead>
    </table>
</div>

@stop
@section("externalScripts")
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.10.0/bootstrap-table.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.15/js/dataTables.bootstrap.min.js"></script>
	<link href="https://cdn.datatables.net/1.10.15/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.10.0/bootstrap-table.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="css/font-awesome.min.css">
    
@stop
@section("javascript")
    var myTable = $('#aka-titles').DataTable( {
    "ajax": {
    "url" : "https://staging.api.radarr.video/mappings/find?type=title&tmdbid={{$movie->id}}",
    "dataSrc" : "mappings.titles"
    },
    order: [[1, "asc"]],
    paging: false,
    info: false,
    searching: false,
    "columns": [
    { "data": "id" },
    { "data": "info.aka_title" },
    { "data": "votes" },
    { "data": "vote_count" },
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
    return "<span class='label "+label+"' data-toggle='tooltip' title='"+row["vote_count"]+" Votes'>"+data+"<span>";
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
                    return "<span data-toggle='tooltip' style='cursor:pointer;' title='This mapping is correct.' onclick='vote(this, "+data+", 1);'><i class='fa fa-check fa-lg green-fa' aria-hidden='true'></i></span>&nbsp;&nbsp;<span data-toggle='tooltip' style='cursor:pointer;' title='This mapping is wrong!' onclick='vote(this, "+data+", -1);'><i class='fa fa-times fa-lg red-fa' aria-hidden='true'></i></span>";
                },
                "targets": 5,
                "orderable": false
            },
            { "visible": false,  "targets": [ 3 ] }
        ]
    } );

    var yearsTable = $('#aka-years').DataTable( {
        "ajax": {
	        "url" : "https://staging.api.radarr.video/mappings/find?type=year&tmdbid={{$movie->id}}",
	        "dataSrc" : "mappings.years"
	    },
	    order: [[1, "asc"]],
	    paging: false,
	    info: false,
	    searching: false,
        "columns": [
            { "data": "id" },
            { "data": "info.aka_year" },
            { "data": "votes" },
            { "data": "vote_count" },
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
                    return "<span class='label "+label+"' data-toggle='tooltip' title='"+row["vote_count"]+" Votes'>"+data+"<span>";
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
                    return "<span data-toggle='tooltip' style='cursor:pointer;' title='This mapping is correct.' onclick='voteY(this, "+data+", 1);'><i class='fa fa-check fa-lg green-fa' aria-hidden='true'></i></span>&nbsp;&nbsp;<span data-toggle='tooltip' style='cursor:pointer;' title='This mapping is wrong!' onclick='voteY(this, "+data+", -1);'><i class='fa fa-times fa-lg red-fa' aria-hidden='true'></i></span>";
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
		$.getJSON("https://staging.api.radarr.video/mappings/vote?id="+id+"&direction="+direction, function(data){
			myTable.row(function(idx, data, node){
				return data.id == id;
			}).data(data).draw();
		});
	}

	function voteY(elem, id, direction){
		$.getJSON("https://staging.api.radarr.video/mappings/vote?id="+id+"&direction="+direction, function(data){
			yearsTable.row(function(idx, data, node){
				return data.id == id;
			}).data(data).draw();
		});
	}
@stop