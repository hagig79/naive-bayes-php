FROM php:8.1

RUN apt-get update \
    && apt-get install -y mecab unzip

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN useradd -m user
USER user
