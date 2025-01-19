
INSERT INTO usuarios (idUsuario, nombre, email, direccion, telefono, password, fechaNac) VALUES
(1, 'Brian', 'bngbrian@gmail.com', 'Los Talas 180', '+54934345555', '123456', '2000-03-01'),
(2, 'Nahuel', 'nahuel@gmail.com', 'Los Colibries 1130', '+54933345555', '123456', '1998-03-01');


INSERT INTO granja (idGranja, nombre, habilitacionSenasa, metrosCuadrados, ubicacion) VALUES
(1, 'Granja la chorlita', '07-892-0467', '80', 'Aldea San Rafael'),
(2, 'Granja el nieto', '10-015-8905', '120', 'Aldea San Juan'),
(3, 'Avicola Maria Clara', '07-012-0405', '120', 'Aldea Santa Rosa');

INSERT INTO tipoAve (idTipoAve, nombre) VALUES
(0, 'Ponedora ligera'),
(1, 'Ponedora pesada'),
(2, 'Ponedora semipesada');

INSERT INTO galpon (idGalpon, identificacion, idTipoAve, capacidad, idGranja) VALUES
(0, '001-Frente', '0', '60000', '1'),
(1, '002-Fondo', '1', '120000', '1'),
(2, '003-Medio', '1', '40000', '1');