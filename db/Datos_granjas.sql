
INSERT INTO usuarios (idUsuario, nombre, email, direccion, telefono, password, fechaNac) VALUES
(0, 'Brian', 'bngbrian@gmail.com', 'Los Talas 180', '+54934345555', '123456', '2000-03-01'),
(1, 'Nahuel', 'nahuel@gmail.com', 'Los Colibries 1130', '+54933345555', '123456', '1998-03-01');


INSERT INTO granja (idGranja, nombre, habilitacionSenasa, metrosCuadrados, ubicacion) VALUES
(0, 'Granja la chorlita', '07-892-0467', '80', 'Aldea San Rafael'),
(1, 'Granja el nieto', '10-015-8905', '120', 'Aldea San Juan'),
(2, 'Avicola Maria Clara', '07-012-0405', '120', 'Aldea Santa Rosa');

INSERT INTO tipoAve (idTipoAve, nombre) VALUES
(0, 'Ponedora ligera'),
(1, 'Ponedora pesada'),
(2, 'Ponedora semipesada');

INSERT INTO galpon (idGalpon, identificacion, idTipoAve, capacidad, idGranja) VALUES
(0, '001-Frente', '0', '60000', '1'),
(1, '002-Fondo', '1', '120000', '1'),
(2, '003-Medio', '1', '40000', '1');

INSERT INTO tipoMantenimiento (idTipoMantenimiento, nombre) VALUES
(0, 'Corte de césped'),
(1, 'Fumigación contra plagas'),
(2, 'Colocación de cebos para roedores');

INSERT INTO mantenimientoGranja (idMantenimientoGranja, fecha, idGranja, idTipoMantenimiento) VALUES
(0, '2025-01-22', '0', '0'),
(1, '2025-01-01', '1', '1'),
(2, '2025-01-20', '1', '2');

INSERT INTO mantenimientoGalpon (idMantenimientoGalpon, fecha, idGalpon, idTipoMantenimiento) VALUES
(0, '2025-01-22', '0', '0'),
(1, '2025-01-01', '0', '1'),
(2, '2025-01-20', '1', '2');
