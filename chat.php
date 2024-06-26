<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>WebSocket</title>
</head>
<body>

<div class="container mt-5">
    <h2>Chat</h2>

    <div class="field_username col-md-3">
        <label for="username">Digite o seu nome</label>
        <input class="form-control" type="text" name="username" id="username">
        <button class="btn btn-primary mt-3" onclick="register_username()">Registrar</button>
    </div>


    <div class="field_message col-md-3" hidden>
        <label for="message">Nova mensagem</label>
        <input class="form-control" type="text" name="message" id="message" placeholder="Digite a mensagem...">
        <button class="btn btn-primary mt-3" id="btn_message" onclick="send_message()">Enviar</button>
    </div>

    <span id="message_chat" hidden>
        <h2 class="mt-5">Mensagens</h2>
    </span>
</div>

<script>
    const register_username = () => {
        const username = document.getElementById('username')
        document.querySelector('.field_username').setAttribute('hidden', true)
        document.querySelector('.field_message').removeAttribute('hidden')
        document.querySelector('#message_chat').removeAttribute('hidden')
        return username.value
    }

    const messageChat = document.getElementById('message_chat')

    const ws = new WebSocket('ws://localhost:8080')

    ws.onopen = (e) => {
        if (e.isTrusted === true) {
            console.log('Chat conectado!')
        }
    }

    const send_message = () => {

        let message = document.getElementById('message');

        let data = {
            username: register_username(),
            message: message.value
        }

        const div = document.createElement('div')
        const span_name = document.createElement('span')
        const span_message = document.createElement('span')
        div.classList.add('mt-3')
        div.append(span_name, span_message)
        span_name.textContent = data.username
        span_name.classList.add('fw-bold')
        span_name.classList.add('d-block')
        span_message.textContent = data.message
        messageChat.append(div)

        ws.send(JSON.stringify(data))

        message.value = ''
    }

    ws.onmessage = (received_message) => {
        let result = JSON.parse(received_message.data);

        const div = document.createElement('div')
        const span_name = document.createElement('span')
        const span_message = document.createElement('span')
        div.classList.add('mt-3')
        div.append(span_name, span_message)
        span_name.textContent = result.username
        span_name.classList.add('fw-bold')
        span_name.classList.add('d-block')
        span_message.textContent = result.message
        messageChat.append(div)
    }

</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>
</html>
