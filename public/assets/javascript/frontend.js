var socket;

function connect()
{
    try {
        if (!WebSocket) {
            alert("No websocket support");
        } else {
            socket = new WebSocket("ws://127.0.0.1:7778/");
            socket.addEventListener("open", function (e) {
                console.log(e);
                changeStep(2);
                logConnected();
            });
            socket.addEventListener("error", function (e) {
                console.log(e);
            });
            socket.addEventListener("message", function (e) {
                console.log(e);
                var data = JSON.parse(e.data);
                switch (data.action)
                {
                    case 'message': showMessage(data); break;
                    case 'connection': addUsers(data); break;
                    case 'disconnection': removeUsers(data); break;
                }

            });
            console.log("socket:", socket);
            window.socket = socket;
        }
    } catch (e) {
        console.log("exception: " + e);
    }
}

function changeStep(n)
{
    $('.step').hide();
    $('.step'+n).show();
}

function log(classname, text)
{
    $('table.data tbody').append('<tr class="'+classname+'"><th>'+text+'</th></tr>');
}

function logUser(id, name)
{
    $('table.users tbody').append('<tr class="user'+id+'"><th>'+name+'</th></tr>');
}

function logConnected()
{
    log('info', 'Conectado!');
}

function register()
{
    var username = $('#username').val();
    socket.send('{"action":"register","name":"'+username+'"}');
    logUser('yo', username + ' (yo)');
    changeStep(3);
}

function enviar()
{
    var message = $('#message').val();
    $('#message').val('');
    socket.send('{"action":"message","message":"'+message+'"}');
    log('active', '<b>Me:</b>' + message);
}

function showMessage(data)
{
    log('', '<b>'+data.user.name+':</b>' + data.message);
}

function addUsers(data)
{
    data.users.forEach(function(user){
        logUser(user.id, user.name);
        log('warning', '<b>'+user.name+' connected</b>');
    });
}
function removeUsers(data)
{
    $('.user'+data.user.id).remove();
    log('warning', '<b>'+data.user.name+' disconnected</b>');
}