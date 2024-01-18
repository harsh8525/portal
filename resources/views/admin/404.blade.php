@extends('admin.layout.app')
@section('title', @$header['title'])

@section('content')

<style>
    .unauthorized_cont img {
        margin: 0 auto;
        display: flex;
        justify-content: center;
        width: 40%;
        padding-top: 1rem;
    }

    .unauthorized_cont p {
        font-size: 22px;
        color: #ee3137;
        text-transform: uppercase;
        text-align: center;
        margin-top: 1.5rem;
    }
</style>

<div class="unauthorized_cont">
    <img src="{{ URL::asset('assets/dist/img/401Img.png') }}" alt="">
    <p>You have no permission to access this page</p>

</div>


@append