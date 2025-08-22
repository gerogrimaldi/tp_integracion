CREATE TABLE usuarios (
  idUsuario INT NOT NULL,
  nombre VARCHAR(100) NOT NULL,
  email VARCHAR(500) NOT NULL,
  direccion VARCHAR(500) NOT NULL,
  telefono VARCHAR(20) NOT NULL,
  password VARCHAR(100) NOT NULL,
  fechaNac DATE NOT NULL,
  tipoUsuario ENUM('Propietario', 'Encargado') NOT NULL DEFAULT 'Propietario',
  user_token VARCHAR(64),
  user_token_expir DATETIME
);

CREATE TABLE granja (
    idGranja INT NOT NULL,
    nombre VARCHAR(80) NOT NULL,
    habilitacionSenasa VARCHAR(80),
    metrosCuadrados INT,
    ubicacion VARCHAR(80),
    PRIMARY KEY (idGranja)
);

CREATE TABLE compuesto (
    idCompuesto INT NOT NULL,
    nombre VARCHAR(80) NOT NULL,
    proveedor VARCHAR(80),
	PRIMARY KEY (idCompuesto)
);

CREATE TABLE tipoAve (
    idTipoAve INT NOT NULL,
    nombre VARCHAR(80),
    PRIMARY KEY (idTipoAve)
);

CREATE TABLE galpon (
    idGalpon INT NOT NULL,
    identificacion VARCHAR(80) NOT NULL,
    idTipoAve INT,
    capacidad INT,
    idGranja INT,
    FOREIGN KEY (idTipoAve) REFERENCES tipoAve(idTipoAve),
    FOREIGN KEY (idGranja) REFERENCES granja(idGranja),
    PRIMARY KEY (idGalpon)
);

CREATE TABLE tipoMantenimiento (
    idTipoMantenimiento INT NOT NULL,
    nombre VARCHAR(80),
    PRIMARY KEY (idTipoMantenimiento)
);

CREATE TABLE mantenimientoGranja (
    idMantenimientoGranja INT NOT NULL,
    fecha DATETIME,
    idGranja INT,
    idTipoMantenimiento INT,
    FOREIGN KEY (idGranja) REFERENCES granja(idGranja),
    FOREIGN KEY (idTipoMantenimiento) REFERENCES tipoMantenimiento(idTipoMantenimiento),
    PRIMARY KEY (idMantenimientoGranja)
);

CREATE TABLE mantenimientoGalpon (
    idMantenimientoGalpon INT NOT NULL,
    fecha DATETIME,
    idGalpon INT,
    idTipoMantenimiento INT,
    FOREIGN KEY (idGalpon) REFERENCES galpon(idGalpon),
    FOREIGN KEY (idTipoMantenimiento) REFERENCES tipoMantenimiento(idTipoMantenimiento),
    PRIMARY KEY (idMantenimientoGalpon)
);

CREATE TABLE loteAves (
    idLoteAves INT NOT NULL,
    identificador VARCHAR(20),
    fechaNacimiento DATE,
    fechaCompra DATE,
    cantidadAves INT,
    idTipoAve INT,
    FOREIGN KEY (idTipoAve) REFERENCES tipoAve(idTipoAve),
    PRIMARY KEY (idLoteAves)
);

CREATE TABLE viaAplicacion (
    idViaAplicacion INT NOT NULL,
    via VARCHAR(45),
    PRIMARY KEY (idViaAplicacion)
);

CREATE TABLE vacuna (
    idVacuna INT NOT NULL,
    nombre VARCHAR(40),
    idViaAplicacion INT,
    marca VARCHAR(40),
    enfermedad VARCHAR(60),
    FOREIGN KEY (idViaAplicacion) REFERENCES viaAplicacion(idViaAplicacion),
    PRIMARY KEY (idVacuna)
);

CREATE TABLE loteVacuna (
    idLoteVacuna INT NOT NULL,
    numeroLote VARCHAR(20),
    fechaCompra DATE,
    cantidad INT,
    vencimiento DATE,
    idVacuna INT,
    FOREIGN KEY (idVacuna) REFERENCES Vacuna(idVacuna),
    PRIMARY KEY (idLoteVacuna)
);

CREATE TABLE ventaLoteAves (
    idVentaLoteAves INT NOT NULL,
    fechaVenta DATE,
    precio FLOAT,
    idLoteAves INT,
    FOREIGN KEY (idLoteAves) REFERENCES loteAves(idLoteAves),
    PRIMARY KEY (idVentaLoteAves)
);

CREATE TABLE pesajeLoteAves (
    idPesaje INT NOT NULL AUTO_INCREMENT,
    fecha DATE,
    peso FLOAT,
    idLoteAves INT,
    FOREIGN KEY (idLoteAves) REFERENCES loteAves(idLoteAves),
    PRIMARY KEY (idPesaje)
);

CREATE TABLE mortandadAves (
    idMortandad INT NOT NULL AUTO_INCREMENT,
    fecha DATE,
    causa VARCHAR (100),
    cantidad INT,
    idLoteAves INT,
    FOREIGN KEY (idLoteAves) REFERENCES loteAves(idLoteAves),
    PRIMARY KEY (idMortandad)
);

CREATE TABLE compra (
    idGranja INT,
    idCompuesto INT,
    PRIMARY KEY (idGranja, idCompuesto),
    FOREIGN KEY (idGranja) REFERENCES granja(idGranja),
    FOREIGN KEY (idCompuesto) REFERENCES compuesto(idCompuesto)
);

CREATE TABLE galpon_loteAves (
    idGalpon_loteAve INT NOT NULL,
    idLoteAves INT,
    idGalpon INT,
    fechaInicio DATE,
    fechaFin DATE,
    PRIMARY KEY (idGalpon_loteAve),
    FOREIGN KEY (idLoteAves) REFERENCES loteAves(idLoteAves),
    FOREIGN KEY (idGalpon) REFERENCES galpon(idGalpon)
);

CREATE TABLE loteVacuna_loteAve (
    idloteVacuna_loteAve INT NOT NULL,
    idLoteAves INT,
    idLoteVacuna INT,
    fecha DATE,
    cantidad INT,
    PRIMARY KEY (idloteVacuna_loteAve),
    FOREIGN KEY (idLoteAves) REFERENCES loteAves(idLoteAves),
    FOREIGN KEY (idloteVacuna) REFERENCES loteVacuna(idloteVacuna)
);
