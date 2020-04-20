@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <p>Welcome to this beautiful admin panel.</p>

    {{--@php
        $urlContent = file_get_contents('https://mcc.edu.ph');

        $dom = new DOMDocument();
        @$dom->loadHTML($urlContent);
        $xpath = new DOMXPath($dom);
        $hrefs = $xpath->evaluate("/html/body//a");

        for($i = 0; $i < $hrefs->length; $i++){
        $href = $hrefs->item($i);
        $url = $href->getAttribute('href');
        $url = filter_var($url, FILTER_SANITIZE_URL);
        // validate url
        if(!filter_var($url, FILTER_VALIDATE_URL) === false){
            echo '<a href="'.$url.'">'.$url.'</a><br />';
        }
        }
    @endphp--}}
@stop

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('/css/style.css')}}">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop
