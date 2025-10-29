FROM mongo:latest

RUN apt-get update && apt-get install -y dos2unix \
    && rm -rf /var/lib/apt/lists/*

RUN dos2unix /docker-entrypoint-initdb.d/init/import-data.sh

RUN chmod +x /docker-entrypoint-initdb.d/init/import-data.sh