------------------------------
-- Archivo de base de datos --
------------------------------

DROP TABLE IF EXISTS grupos CASCADE;

CREATE TABLE grupos
(
    id BIGSERIAL PRIMARY KEY
);

DROP TABLE IF EXISTS images CASCADE;

CREATE TABLE images
(
    id         BIGSERIAL    PRIMARY KEY,
    grupo_id   BIGINT       REFERENCES grupos(id)
                            ON UPDATE CASCADE
                            ON DELETE SET NULL,
    nombre     VARCHAR(255) NOT NULL,
    extension  VARCHAR(255) NOT NULL,
    uri        VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP
);
