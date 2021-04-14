@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
          <p>Today's News for {{$ticker_simbol}}</p>

            @foreach($news as $news_data)
             <div class="card">
                 <div class="card-header">
                    <a  href="{{ $news_data->url }}"  target="_blank">
                    {{ $news_data->title}}
                    </a>
                   </div>
                 <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                          <img src="{{ $news_data->image}}" style="width:100%"/>
                        </div>
                        <div class="col-6">
                          <p>{{ $news_data->summary }}</p>
                        </div>
                    </div>
                 </div>
            </div>
            <br>
            @endforeach

        </div>
    </div>
</div>
@endsection