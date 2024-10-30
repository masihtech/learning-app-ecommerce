FROM mariadb

ENV MARIADB_DATABASE=ecomdb
ENV MARIADB_USER=example-user
ENV MARIADB_PASSWORD=my_cool_secret
ENV MARIADB_ROOT_PASSWORD=my-secret-pw

COPY . /docker-entrypoint-initdb.d
