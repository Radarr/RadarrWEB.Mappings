<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

Route::get('/', function () {
    $total_count = DB::select("SELECT COUNT(id) as count FROM leonard1_mappings.mappings")[0]->count;
    $useful_count = DB::select("SELECT COUNT(id) as count FROM leonard1_mappings.mappings WHERE votes >= 3")[0]->count;
    return view('index', ["total_count" => $total_count, "useful_count" => $useful_count]);
});

Route::get('/movie/{id}', function ($id) {
	$movie = \App\Movie::find($id);
    return view('movie', ["movie" => $movie]);
});

Route::get('/mapping/{id}', function($id) {
   $movieId = DB::select("SELECT id, tmdbid FROM leonard1_mappings.mappings WHERE id = ?", array($id))[0]->tmdbid;
   return redirect('/movie/'.$movieId.'#'.$id);
});

Route::get('/search/{term}', function ($term) {

    $results =  Cache::remember($term, 10, function() use ($term){
        preg_match('/^(?<title>(?![([]).+?)?(?:(?:[-\W](?<![)[!]))*(?<year>(19|20)\d{2}(?!p|i|\d+|]|\W\d+)))+(\W+|_|$)(?!\\\\)/', $term, $match);

        $title = $term;

        $year = false;

        if ($match) {
            $title = $match["title"];
            $year = $match["year"];
        }

        $title = \App\Helpers\Helper::clean_title($title);

        $result = array();

        if ($year != false) {
            $result = DB::select("SELECT res.* FROM ((SELECT m.*, map.id as map_id, COUNT(m.id) as mappings_count FROM movies m, leonard1_mappings.mappings map WHERE m.clean_title LIKE '%$title%' AND m.release_year = $year AND map.tmdbid = m.id GROUP BY m.id) UNION (SELECT m.*, 0 as map_id, 0 as mappings_count FROM movies m, leonard1_mappings.mappings map WHERE m.clean_title LIKE '%$title%' AND m.release_year = $year AND map.tmdbid != m.id)) res GROUP BY res.id ORDER BY res.popularity DESC");
        } else {
            $result = DB::select("SELECT res.* FROM ((SELECT m.*, map.id as map_id, COUNT(m.id) as mappings_count FROM movies m, leonard1_mappings.mappings map WHERE m.clean_title LIKE '%$title%' AND map.tmdbid = m.id GROUP BY m.id) UNION (SELECT m.*, 0 as map_id, 0 as mappings_count FROM movies m, leonard1_mappings.mappings map WHERE m.clean_title LIKE '%$title%' AND map.tmdbid != m.id)) res GROUP BY res.id ORDER BY res.popularity DESC");
        }

        return $result;
    });

    //dd($results);

    return view('search', ["results" => $results, "query" => $term]);

});

