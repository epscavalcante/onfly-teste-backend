<p align="center"><a href="https://onfly.com.br" target="_blank"><img src="https://imgur.com/uuNxqx8.png" width="400" alt="OnFly Logo"></a></p>

## Sobre a aplicação

Você precisa desenvolver um microsserviço em Laravel para gerenciar pedidos de viagem corporativa. O microsserviço deve expor uma API REST para as seguintes operações:

- Criar um pedido de viagem: Um pedido deve incluir o ID do pedido, o nome do solicitante, o destino, a data de ida, a data de volta e o status (solicitado, aprovado, cancelado).

- Atualizar o status de um pedido de viagem: Possibilitar a atualização do status para "aprovado" ou "cancelado". (nota: o usuário que fez o pedido não pode alterar o status do mesmo)

- Consultar um pedido de viagem: Retornar as informações detalhadas de um pedido de viagem com base no ID fornecido.

- Listar todos os pedidos de viagem: Retornar todos os pedidos de viagem cadastrados, com a opção de filtrar por status.

- Cancelar pedido de viagem após aprovação: Implementar uma lógica de negócios que verifique se é possível cancelar um pedido já aprovado 

- Filtragem por período e destino: Adicionar filtros para listar pedidos de viagem por período de tempo (ex: pedidos feitos ou com datas de viagem dentro de uma faixa de datas) e/ou por destino.

- Notificação de aprovação ou cancelamento: Sempre que um pedido for aprovado ou cancelado, uma notificação deve ser enviada para o usuário que solicitou o pedido.

## 🔧 Ferramentas utilizadas

- Linguagem de programação PHP e o framework Laravel
- Banco de dados: MySql e SQLite
- PHPUnit para execução dos testes
- Swagger and Open API para documentar os endpoints
- Docker e Docker compose para fazer a configuração do ambiente de desenvolvimento:
    - PHP 8.4, 
    - MySql 8
    - Servidor SMTP ([Mailpit](https://github.com/axllent/mailpit))

## ⚡️ Executar a aplicação

**É necessário ter o docker e docker compose instalado na sua máquina**. Caso não tenha instaldo, [acesse a documentação oficial](https://docs.docker.com/engine/install/) e faça a instalação antes de continuar os passos.

1. Clone este projeto:
SSH: ```git clone git@github.com:epscavalcante/onfly-teste-backend.git```
HTTPS: ```git clone https://github.com/epscavalcante/onfly-teste-backend.git```

2. Acesse o diretório .docker/local
```
cd .docker/local
```

3. Iniciar os containers:
```
docker compose up -d --build
```

4. Entrar no container do app:
```
docker compose exec app bash
```

5. Crie o arquivo .env
```
cp .env.example .env
```

6. Instale as dependências 
```
composer install
```

7. Definir o APP_KEY
```
php artisan key:generate
```

8. Definir o JWT_SECRET
```
php artisan jwt:secret --force
```

9. Abra o arquivo .env e substitua as variaveis abaixo:
```
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=onfly
DB_USERNAME=root
DB_PASSWORD=root
```

10. Depois de configurado as variáveis de ambiente do DB, executar as migrations:
```
php artisan migrate:fresh --seed
```

11. [Optional] Configurar as variáveis de ambiente do SMTP. Esse passo é opcional por padrão, o driver de email é utilizado é LOG ele vai gerar o email em formato texto no arquivo em storage/logs/laravel.log. Uma das ferramentas configuradas no ambiente de desenvolvimento é um servidor SMTP voltado para desenvolvimento, para usar este, configure as seguintes variáveis de ambiente no arquivo .env:
```
MAIL_MAILER=smtp
MAIL_SCHEME=null
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_FROM_ADDRESS="notifications@onfly.teste.com"
MAIL_FROM_NAME="${APP_NAME}"
```

12. Com o banco de dados e servidor SMTP configurados, vamos levantar o servidor WEB:
```
php artisan serve --host 0.0.0.0
```

13. A aplicação agora deve estar rodando. Acesse a [página inicial](http://localhost:8000) ou a [Documentação da API](http://localhost:8000/api/docs), desenvolvida utilizando Swagger e Open API.
