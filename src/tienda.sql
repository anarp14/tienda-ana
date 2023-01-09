DROP TABLE IF EXISTS articulos CASCADE;

CREATE TABLE articulos (
    id          bigserial     PRIMARY KEY,
    codigo      varchar(13)   NOT NULL UNIQUE,
    descripcion varchar(255)  NOT NULL,
    precio      numeric(7, 2) NOT NULL,
    descuento   numeric(3)          DEFAULT 0,
    nuevo_precio  numeric(7, 2) DEFAULT 0,
    stock       int           NOT NULL,
    visible     bool          NOT NULL,
    categoria_id bigint NOT NULL REFERENCES categorias(id)
    CHECK (descuento >= 0 AND descuento <= 100)          
);

DROP TABLE IF EXISTS categorias CASCADE;

CREATE TABLE categorias (
  id bigserial  PRIMARY KEY,
  categoria varchar(255) NOT NULL
);

DROP TABLE IF EXISTS usuarios CASCADE;

CREATE TABLE usuarios (
    id       bigserial    PRIMARY KEY,
    usuario  varchar(255) NOT NULL UNIQUE,
    password varchar(255) NOT NULL,
    validado bool         NOT NULL
);

DROP TABLE IF EXISTS facturas CASCADE;

CREATE TABLE facturas (
    id         bigserial  PRIMARY KEY,
    created_at timestamp  NOT NULL DEFAULT localtimestamp(0),
    usuario_id bigint NOT NULL REFERENCES usuarios (id)
);

DROP TABLE IF EXISTS articulos_facturas CASCADE;

CREATE TABLE articulos_facturas (
    articulo_id bigint NOT NULL REFERENCES articulos (id) ON DELETE CASCADE,
    factura_id  bigint NOT NULL REFERENCES facturas (id),
    cantidad    int    NOT NULL,
    PRIMARY KEY (articulo_id, factura_id)
);

-- Carga inicial de datos de prueba:

INSERT INTO articulos (codigo, descripcion, precio, stock,visible,categoria_id)
    VALUES ('18273892389', 'Yogur piña', 200.50, 4, false, 2),
           ('83745828273', 'Tigretón', 50.10, 2, true, 2),
           ('51736128495', 'Disco duro SSD 500 GB', 150.30, 0, true, 1),
           ('83746828273', 'Tigretón', 50.10, 3, true, 2),
           ('51786128435', 'Disco duro SSD 500 GB', 150.30, 5, true, 1),
           ('83745228673', 'Tigretón', 50.10, 8, true, 2),
           ('51786198495', 'Disco duro SSD 500 GB', 150.30, 1, true, 1);

INSERT INTO usuarios (usuario, password, validado)
    VALUES ('admin', crypt('admin', gen_salt('bf', 10)), true),
           ('pepe', crypt('pepe', gen_salt('bf', 10)), false);


INSERT INTO categorias (categoria) VALUES
('tecnologia'),
('alimentacion'),
('juguetes');
