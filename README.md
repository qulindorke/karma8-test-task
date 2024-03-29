# Expired Subscription Notification Service

## Описание решения

- В качестве БД использвана PostgreSQL
- В таблицу `users` добавлен partial index:

```postgresql
CREATE INDEX idx_partial_confirmed_validts ON users (confirmed, validts)
    WHERE confirmed IS TRUE AND validts <> 0;
```

- Очереди сделаны с помощью таблицы в PostgreSQL
- Через docker-compose стартуют:
    - Шедулер, ответственный за запуск команд по расписанию (проверка подписок, проверка failed джоб)
    - Несколько инстансов queue worker
- Раз в сутки (00:00, настраивается) запускается команда, которая ищет всех пользователей, с истекающими подписками и
  подтвержденными email. Если email не провалидирован, создается джоба на его валидацию (сначала через регулярку, потом
  через платную функцию). Если провалидирован и все с ним ок, ставим джобу на отправку письма.

**Что можно сделать лучше:**

- Каждое выполнение job запускать в новом процессе, тогда мы сможем контролировать максимальное время выполнения. Не
  считаю, что в данном решение это критикал, потому что джоба может зависнуть только если сервис будет очень долго
  отвечать. У нас сейчас заглушки, в реальной жизни там guzzle или что-нибудь еще, где, с вероятностью 99% можно
  проставить таймауты, и будет вылетать Exception, который можно отловить и зафейлить джобу

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

PostgreSQL при старте контейнера выполнит `./dev-data/init.sql`, в котором есть структура БД + генерирование тестовых
данных.

4) Все контейнеры пишут лог в stdout/stderr, их можно смотреть командой `docker-compose logs -f`

5) Для того, чтобы форсировать выполнение скрипта проверки подписок, запустите команду

```shell
 docker-compose --env-file=.env run --rm scheduler php run check-subscriptions
```

> Для вашего удобства, в корне проекта лежит Makefile
