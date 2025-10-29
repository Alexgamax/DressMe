FROM mongo:latest

RUN apt-get update && apt-get install -y dos2unix procps \
    && rm -rf /var/lib/apt/lists/*

COPY init/ /docker-entrypoint-initdb.d/

RUN dos2unix /docker-entrypoint-initdb.d/import-data.sh \
    && chmod +x /docker-entrypoint-initdb.d/import-data.sh