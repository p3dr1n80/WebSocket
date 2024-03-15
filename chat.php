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

    <!-- Campo para cadastrar o usuário -->
    <div class="field_username col-md-3">
        <label for="username">Digite o seu nome</label>
        <input class="form-control" type="text" name="username" id="username">
        <button class="btn btn-primary mt-3" onclick="register_username()">Registrar</button>
    </div>


    <!-- Campo para o usuario digitar a nova mensagem -->
    <div class="field_message col-md-3" hidden>
        <label for="message">Nova mensagem</label>
        <input class="form-control" type="text" name="message" id="message" placeholder="Digite a mensagem...">
        <button class="btn btn-primary mt-3" id="btn_message" onclick="send_message()">Enviar</button>
    </div>

    <!-- Receber as mensagens do chat enviado pelo JS -->
    <span id="message_chat" hidden>
        <h2 class="mt-5">Mensagens</h2>
    </span>
</div>

<script>
    // Registra o nome do usuário
    const register_username = () => {
        const username = document.getElementById('username')
        document.querySelector('.field_username').setAttribute('hidden', true)
        document.querySelector('.field_message').removeAttribute('hidden')
        document.querySelector('#message_chat').removeAttribute('hidden')
        return username.value
    }

    // Recuperar o id que deve receber as mensagens no chat
    const messageChat = document.getElementById('message_chat')

    // Endereço do WS
    const ws = new WebSocket('ws://localhost:8080')

    // Realizar a conexão com o WS
    ws.onopen = (e) => {
        if (e.isTrusted === true) {
            console.log('Chat conectado!')
        }
    }

    // Função para enviar a mensagem
    const send_message = () => {

        // Recuperar o id do campo mensagem
        let message = document.getElementById('message');

        // Criar o array de dados para enviar para o WS
        let data = {
            username: register_username(),
            message: message.value
        }

        // Mostrar histórico de mensagens para o remetente
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

        // Enviar a mensagem para o WS
        ws.send(JSON.stringify(data))

        // Limpar o campo mensagem
        message.value = ''
    }

    // Receber a mensagem do WS
    ws.onmessage = (received_message) => {
        // Ler as mensagens enviadas pelo WS
        let result = JSON.parse(received_message.data);

        // Enviar a mensagem para o HTML, e inserir no final da lista de mensagens
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