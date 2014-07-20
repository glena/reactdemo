@extends('layouts.master')

@section('inlineScripts')
<script>
    $(function(){
        // ctrl+enter & cmd+enter sends message
        $("#message").keydown(function (event) {
            if( (event.metaKey || event.ctrlKey) && event.keyCode == 13) enviar();
        });
    });
</script>
@stop

@section('content')

<h1>Demo</h1>

<div class="step step1">
    <p>Conectarse al servidor</p>
    <p>Esto inicializa el socket y se conecta al puerto expuesto por reactphp.</p>
    <button class="btn btn-default" onclick="connect();">Connect</button>
</div>

<div class="step step2">
    <p>Registramos al usuario con un username para que pueda empezar a interactuar en el chat.</p>

    <div class="row">
        <input class="col-xs-12 col-sm-4 col-md-3 col-lg-3 col-sm-offset-1 col-md-offset-1 col-lg-offset-1" type="text" id="username" placeholder="username" />
        <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
            <button class="btn btn-default" onclick="register();">Register</button>
        </div>
    </div>
</div>

<div class="step step3">
    <p>Listo!</p>


    <div class="row">
        <table class="table users table-condensed pull-left">
            <thead><tr><th>Usuarios</th></tr></thead>
            <tbody></tbody>
        </table>
        <table class="table data pull-left">
            <thead><tr><th>Chat</th></tr></thead>
            <tbody></tbody>
        </table>
    </div>

    <div class="row">
        <div class="users pull-left">
            &nbsp;
        </div>
        <div class="data pull-left">
            <div><textarea id="message"></textarea></div>
            <div><button class="btn btn-default" onclick="enviar();">Enviar</button></div>
        </div>
    </div>
</div>

@stop