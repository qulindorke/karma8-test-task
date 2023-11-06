# Expired Subscription Notification Service

## Запуск проект локально:

1) Склонируйте репозиторий и откройте папку с проектом

2) Создайте `.env` файл с помощью команды:

```shell
cp .env.example .env
```

3) Используйте docker-compose для запуска проекта

```shell
docker-compose --env-file=.env up -d
```

4) Для того, чтобы форсировать выполнение скрипта проверки подписок, запустите команду

```shell
 docker-compose --env-file=.env run --rm scheduler php run check-subscriptions
```

> Для вашего удобства, в корне проекта лежит Makefile
