@extends('layouts.app')

@section('content')
 
<!DOCTYPE html>
<html>
  <head>
    <title>MovieBox</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <link rel="stylesheet" type="text/css" href="css/style.css" />
    <link
      href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script
      src="https://kit.fontawesome.com/ee1ec2542e.js"
      crossorigin="anonymous"
    ></script>

    <meta name="viewport" content="width=device-width, initial-scale=1">

  </head>
  <body>
    <div class="wrapper">
      <div class="grey-background">
      <div class="bgslide">
          @if($popularMovie != null)
          <img class="bgimage" src="{{'https://image.tmdb.org/t/p/original'.$popularMovie[0]->poster_path}}" alt="backgrouind">
          <h1 class="header-line">{{$popularMovie[0]->title}}</h1>
          <div class="filter-navbar">
            @foreach ($popularMovieGenres[$popularMovie[0]->id] as $genre)
              <p>{{$genre}}</p>
            @endforeach
          </div>
          <div class="button-navbar">
            @if(count($videos)>0)
            <button class="btn-watch-trailer" onclick="revealVideo('video','youtube')"><a href="https://www.youtube.com/embed/{{$videos[0]['key']}}" target="targetVideo">Watch Trailer</a></button>
            @endif
            <button class="btn-view-info"><a href="{{ route('movies.showMovie', $popularMovie[0]->id) }}">VIEW INFO</a></button>
            <button class="btn-add-to-wishlist"><a href="{{'addmovie/'. $popularMovie[0]->id}}">+ ADD TO WISHLIST</a></button>
          </div>
          <div class="rating-card-wrapper">
            <div class="rating-card">
              <h3>
                Rating
                <span class="reviews-letter">based on 3.546 reviews</span>
              </h3>
              <div class="rating-stars">
                <p class="rating-number">{{ $popularMovie[0]->rating }}</p>
              </div>
            </div>
          </div>
          @endif
      </div>
      <div class="movie-poster-wrapper">
        <div class="movie-categorisation">
        <button class="btn-movie-filter btn-rated"><a href="{{route('movies.showNowPlayingMovies')}}" >Now Playing</a></button>
          <button class="btn-movie-filter btn-rated"><a href="{{route('movies.showTopRatedMovies')}}" >Top Rated</a></button>
            <button class="btn-movie-filter btn-rated"><a href="{{route('movies.showUpcomingMovies')}}" >Upcoming</a></button>
              <button class="btn-movie-filter btn-rated"><a href="{{route('movies.showPopularMovies')}}">Popular</a></button>
          
          <div class="grid-list-icons">
            <button class="icon-view icon-list-1 active-nav" onclick="movieListView()"><i class="fas fa-stream"></i></button>
            <button class="icon-view icon-grid" onclick="movieGridView()"><i class="fas fa-th-large"></i>
            </button>
          </div>
		</div>
        <hr />
        
        <div class="form-filter-inline">
          <form action="{{ route('movies.filter')}}">
          {{-- Rating --}}

          <label for="rating"></label>
          <input class="filter-rating" id="rating" name="rating" type="number" min="1" max="10" value="1">
  
          {{-- Date --}}

          <label for="release_date"></label>
          <select class="genre-options" id="release_date" name="release_date" id="year"></select>
          
          {{-- Genre --}}

            <label for="genre"></label>
              <select class="genre-options" name="genre" id="genre">
                @foreach ($genres as $genre)
                  <option value="{{$genre['id']}}">{{$genre['name']}}</option>
                @endforeach
              </select>
              <button type="submit" name="Filter" value="Filter" class="button btn-success"> FILTER</button>
        </form>
        </div>
        <div class="movie-trailer-grid js-all-movies">
        {{-- <div class="movie-trailer-grid js-all-movies"> --}}
          @if($movies->total() == 0)
            <h1>No movies of this criteria</h1>
          @else
          @foreach ($movies as $movie)
          <a href="{{ route('movies.showMovie', $movie->id) }}">
          <div class="trailer-card">
            <div class="movie-date-wrapper">
              <span>{{\Carbon\Carbon::parse($movie->release_date)->format('Y')}}</span>
              <img src="{{'https://image.tmdb.org/t/p/original'. $movie->poster_path}}" alt="poster" class="poster-image">
            </div>
            @if(auth()->user())
            <button class="btn-wishlist" disabled="primary"><a href="{{'addmovie/'. $movie->id}}">Add to wishlist</a></button>
          @endif
            <div class="card-trailer-bottom">
            <a href="{{ route('movies.showMovie', $movie->id) }}"><p class="movie-title">{{ mb_strimwidth($movie->title, 0, 19, "...") }}</p></a>
            <div class="movie-rating-wrapper">
              <span class="ml-1"><span class="movie-rating">{{ $movie->rating }}</span> </span>
        </div>
        <div class="genre-wrapper">
          @foreach (array_slice($moviesgenres[$movie->id], 0,3) as $genre)
            {{$genre}}
          @endforeach
        </div>
        
        </div>
          </div>
        </a>
          @endforeach
       @endif
          
        {{-- </div> --}}
      </div>
      
     <div class="home-page-pagination">{!! $movies->links() !!}</div>
     
      </div>
      <div class="footer">
		<div class="footer-navmeni">
			<p class="nav-items">Contact</p>
			<p class="nav-items">Ratings</p>
			<p class="nav-items">Movies</p>
			<p class="nav-items">About</p>
		  </div>
    <h2 class="footer-logo-title">THEMOVIEBOX</h2>
      <p class="copyright">Powerd by <a href="https://www.themoviedb.org/documentation/api">TMDb API</a></p>
      
      </div>
    </div>
    <div id="video" class="lightbox" onclick="hideVideo('video','youtube')">
      <div class="lightbox-container">  
        <div class="lightbox-content">
          
          <button onclick="hideVideo('video','youtube')" class="lightbox-close">
            Close | ✕
          </button>
          <div class="video-container">
            <iframe id="youtube" width="960" name="targetVideo" height="540" frameborder="0" allowfullscreen></iframe>
          </div>      
          
        </div>
      </div>
    </div>
    <script>

// Count for year start from 1900 to 2020
var start = 1900;
var end = new Date().getFullYear();
var options = "";
for(var year = start ; year <=end; year++){
  options += "<option>"+ year +"</option>";
}
document.getElementById("release_date").innerHTML = options;

    // list and grid view

var elements = document.getElementsByClassName("js-all-movies");
var i;

function movieListView() {
  for (i = 0; i < elements.length; i++) {
    elements[i].classList.add("js-acitive");
    elements[i].classList.remove("js-acitive");
    elements[i].style.flexFlow = "column";
    elements[i].style.alignItems = "center";
  }
}

function movieGridView() {
  for (i = 0; i < elements.length; i++) {
    elements[i].classList.add("js-acitive");
    elements[i].classList.remove("js-acitive");
    elements[i].style.flexFlow = "row wrap";
    elements[i].style.alignItems = 'none';
  }
}

    // video trailer js

  function revealVideo(div,video_id) {
  var video = document.getElementById(video_id).src;
  document.getElementById(video_id).src = video+'&autoplay=1'; 
  document.getElementById(div).style.display = 'block';
  }

  function hideVideo(div,video_id) {
  var video = document.getElementById(video_id).src;
  var cleaned = video.replace('&autoplay=1',''); 
  document.getElementById(video_id).src = cleaned;
  document.getElementById(div).style.display = 'none';
  }
    </script>
  </body>
</html>

@endsection

