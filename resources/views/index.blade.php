@extends('default')
@section("title")
	Movie Scene Mappings
@stop
@section('content')
          <div class="jumbotron">
        <h1>Movie Scene Mappings</h1>
        <p class="lead">This website aims to provide mappings between alternative movie titles and years and their respective <a href="https://themoviedb.org">TMDB</a> equivalent.
        It is mainly used to correctly map releases (with wrong years or titles) found by <a href="https://radarr.video">Radarr</a> and the corresponding movies on TMDB. However, there is an API available for other projects to use it.
        </p>
        <p>
	                Current total mappings: {{$total_count}}.
        </p>
              <p>
                  Current approved mappings: {{$useful_count}}.
              </p>
      </div>

     <div class="row marketing">
	      <h4>Latest Mappings</h4>
         <table id="aka-titles" class="table table-striped table-bordered" cellspacing="0" width="100%">
             <thead>
             <tr>
                 <th>ID</th>
                 <th>Title / Year</th>
                 <th>Reports</th>
                 <th>Movie</th>
             </tr>
             </thead>
         </table>
      </div>
@stop
@section("javascript")
    var myTable = $('#aka-titles').DataTable( {
    "ajax": {
    "url" : "https://staging.api.radarr.video/mappings/latest",
    "dataSrc" : ""
    },
    //order: [[1, "asc"]],
    ordering : false,
    paging: false,
    info: false,
    searching: false,
    "columns": [
    { "data": "mapping.id" },
    { "data": "mapping" },
    { "data": "mapping" },
    { "data": "mapping.movie" }
    ],
    "columnDefs": [
    {
         "orderable": false,
            "targets": 1,
        "render" : function ( data, type, row ) {
            var text = data["info"]["aka_year"];
            if (data["info"]["aka_title"] != "" && data["info"]["aka_title"] != undefined)
            {
                text = data["info"]["aka_title"];
            }

            return "<a href='movie/"+data["tmdbid"]+ "'>"+text+"</a>";
        },
    },
    {
        "orderable": false,
        "targets": 3,
        "render" : function ( data, type, row ) {
            return "<a href='movie/"+data["id"]+ "'>"+data["title"]+"</a>";
        },
    },
    {
        "render" : function ( data, type, row ) {
            var count = data["votes"];
            var label = "label-default";
            if (count > 3) {
            label = "label-success";
            }
            if (count < 0) {
            label = "label-danger"
            }
            return "<span class='label "+label+"' data-toggle='tooltip' title='"+data["vote_count"]+" Votes'>"+count+"<span>";
    },
                "targets": 2,
                "orderable": false
            },
        ]
    } );
                $('body').tooltip({
    selector: '[data-toggle=tooltip]'
});
@stop
@section("externalScripts")
                                <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.10.0/bootstrap-table.min.js"></script>
                                <script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
                                <script src="https://cdn.datatables.net/1.10.15/js/dataTables.bootstrap.min.js"></script>
                                <link href="https://cdn.datatables.net/1.10.15/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
                                <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.10.0/bootstrap-table.min.css" rel="stylesheet" type="text/css" />
                                <link rel="stylesheet" href="css/font-awesome.min.css">

@stop