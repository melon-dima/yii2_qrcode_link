# yii2_qrcode_link

Сервис сокращения ссылок на Yii2.

## Развертывание (Docker Compose)

### 1. Клонирование репозитория

```bash
git clone git@github.com:melon-dima/yii2_qrcode_link.git
cd yii2_qrcode_link
```

### 2. Подготовка

Файл `.env` уже добавлен в репозиторий для быстрого старта.



Если внешняя сеть `traefik_default` еще не создана, создайте ее:

```bash
docker network create traefik_default
```

### 3. Запуск контейнеров

```bash
docker compose up -d --build
```

На первом старте контейнера `task_php` автоматически выполняется `composer install`, это может занять некоторое время.

Проверка статуса:

```bash
docker compose ps
```

Приложение будет доступно по адресу: `http://localhost:5005`

### 4. Миграции

Вход в контейнер 

```bash
docker exec -it task_php bash
```

После входа:

```bash
php yii migrate
```

На вопрос `Apply the above migration? (yes|no) [no]:` ответьте `y`.
