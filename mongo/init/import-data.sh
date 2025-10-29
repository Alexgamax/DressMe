DB_NAME="DBProyecto"

mongoimport --db $DB_NAME --collection outfits --file /docker-entrypoint-initdb.d/outfits.json --jsonArray

mongoimport --db $DB_NAME --collection prendas --file /docker-entrypoint-initdb.d/prendas.json --jsonArray

mongoimport --db $DB_NAME --collection publicaciones --file /docker-entrypoint-initdb.d/publicaciones.json --jsonArray

mongoimport --db $DB_NAME --collection tendencias --file /docker-entrypoint-initdb.d/tendencias.json --jsonArray

mongoimport --db $DB_NAME --collection usuarios --file /docker-entrypoint-initdb.d/usuarios.json --jsonArray
