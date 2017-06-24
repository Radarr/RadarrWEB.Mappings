@extends('default')
@section("base_url")
../@stop
@section("title")
	'{{$query}}' - Results
@stop
@section('content')
    <div class="jumbotron">
        <h1>Found {{count($results)}} {{str_plural("result", count($results))}} for '{{$query}}'</h1>
        @foreach ($results as $movie)
            <div class="row vertical-center result">
                <div class="col-md-4"><img src="https://image.tmdb.org/t/p/w300_and_h450_bestv2{{$movie->poster_path}}"></div>
                <div class="col-md-8"><a href="movie/{{$movie->id}}"><h2>{{$movie->title}} ({{$movie->release_year}})</h2></a>
                    <p class="lead">{{$movie->overview}}</p>
                    <p>
                        <a href="http://imdb.com/title/{{$movie->imdb_id}}"><span class="label label-primary">IMDB</span></a>
                        <a href="http://themoviedb.com/movie/{{$movie->id}}"><span class="label label-primary">TMDB</span></a>
                    </p>
                    <p class=".small">
                        Currently {{$movie->mappings_count}} {{str_plural("mapping", $movie->mappings_count)}}.
                    </p></div>
            </div>
            <hr>
            @endforeach
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
@stop