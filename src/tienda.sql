DROP TABLE IF EXISTS articulos CASCADE;

CREATE TABLE articulos (
    id          bigserial     PRIMARY KEY,
    codigo      varchar(13)   NOT NULL UNIQUE,
    descripcion varchar(255)  NOT NULL,
    precio      numeric(7, 2) NOT NULL,
    stock       int           NOT NULL,
    visible     bool          NOT NULL,
    categoria_id bigint NOT NULL REFERENCES categorias(id),
    descuento   numeric(3)    DEFAULT 0  CHECK (descuento >= 0 AND descuento <= 100),
    creacion    DATE      NOT NULL DEFAULT CURRENT_DATE
);

DROP TABLE IF EXISTS categorias CASCADE;
 
CREATE TABLE categorias (
    id      bigserial       PRIMARY KEY,
    categoria varchar(255)  NOT NULL
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
    usuario_id bigint NOT NULL REFERENCES usuarios (id),
    cupon   varchar(50)  
);

DROP TABLE IF EXISTS cupones CASCADE;

CREATE TABLE cupones (
  id            bigserial       PRIMARY KEY,
  cupon        VARCHAR(50)     NOT NULL,
  descuento     DECIMAL(10,2)   NOT NULL,
  fecha          DATE            NOT NULL
);

DROP TABLE IF EXISTS articulos_facturas CASCADE;

CREATE TABLE articulos_facturas (
    articulo_id bigint NOT NULL REFERENCES articulos (id),
    factura_id  bigint NOT NULL REFERENCES facturas (id),
    cantidad    int    NOT NULL,
    PRIMARY KEY (articulo_id, factura_id)
);





-- Carga inicial de datos de prueba:

INSERT INTO articulos (codigo, descripcion, precio, stock, visible, categoria_id)
    VALUES ('18273892389', 'Yogur piña', 200.50, 4, false, 1),
           ('83745828273', 'Tigretón', 50.10, 2, true,1),
           ('51736128495', 'Disco duro SSD 500 GB', 150.30, 0, true,2),
           ('83746828273', 'Tigretón', 50.10, 3, true,1),
           ('51786128435', 'Disco duro SSD 500 GB', 150.30, 5,true,2),
           ('83745228673', 'Tigretón', 50.10, 8,true,1),
           ('51786198495', 'Disco duro SSD 500 GB', 150.30, 1,false,2);

INSERT INTO usuarios (usuario, password, validado)
    VALUES ('admin', crypt('admin', gen_salt('bf', 10)), true),
           ('pepe', crypt('pepe', gen_salt('bf', 10)), false);

INSERT INTO categorias (categoria)
    VALUES ('Alimentación'),
            ('Tecnología');


INSERT INTO cupones (cupon, descuento, fecha)
     VALUES 
('DESCUENTO10', 10.00, '2023-04-01'),
('DESCUENTO20', 20.00, '2023-05-01'),
('DESCUENTO50', 50.00, '2023-02-01');