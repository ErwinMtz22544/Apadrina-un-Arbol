-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 23-03-2026 a las 02:58:47
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `arbolbd`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `apadrinamientos`
--

CREATE TABLE `apadrinamientos` (
  `id_apadrinamiento` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `id_arbol` int(11) DEFAULT NULL,
  `nombre_arbol` varchar(100) DEFAULT NULL,
  `tipo_adopcion` varchar(50) DEFAULT NULL,
  `fecha_apadrinamiento` timestamp NOT NULL DEFAULT current_timestamp(),
  `monto` decimal(10,2) DEFAULT NULL,
  `metodo_pago` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `apadrinamientos`
--

INSERT INTO `apadrinamientos` (`id_apadrinamiento`, `id_usuario`, `id_arbol`, `nombre_arbol`, `tipo_adopcion`, `fecha_apadrinamiento`, `monto`, `metodo_pago`) VALUES
(14, 1, 4, 'lizeth', 'Individual', '2026-03-11 00:05:55', 100.00, 'Tarjeta'),
(15, 1, 6, 'Aporte de Mantenimiento', 'Donación', '2026-03-23 01:05:03', 100.00, 'Tarjeta');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `arboles`
--

CREATE TABLE `arboles` (
  `id_arbol` int(11) NOT NULL,
  `nombre_comun` varchar(100) DEFAULT NULL,
  `nombre_cientifico` varchar(150) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `altura` varchar(50) DEFAULT NULL,
  `epoca` varchar(50) DEFAULT NULL,
  `descripcion_larga` text DEFAULT NULL,
  `datos_interesantes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `arboles`
--

INSERT INTO `arboles` (`id_arbol`, `nombre_comun`, `nombre_cientifico`, `descripcion`, `imagen`, `altura`, `epoca`, `descripcion_larga`, `datos_interesantes`) VALUES
(1, 'Duraznillo', 'Cercis canadensis L', 'Árbol caducifolio famoso por su espectacular floración primaveral de color rosa brillante o magenta que aparece antes que sus hojas.', 'cercis-canadensis-img.jpg', '6m - 15m', 'Primavera', 'El Duraznillo o \"Árbol de Judas\" es una especie ornamental de tamaño pequeño a mediano. En primavera, sus ramas se cubren de racimos de flores rosadas antes de que broten sus hojas en forma de corazón. Es una especie muy valorada en el paisajismo de los campus por su belleza escénica y su capacidad para atraer mariposas y abejas, fomentando la biodiversidad.', 'Sus flores son comestibles y ricas en vitamina C, a menudo usadas en ensaladas gourmet. Además, pertenece a la familia de las leguminosas, por lo que ayuda a fijar nitrógeno, nutriendo la tierra a su alrededor.'),
(2, 'Bignonia amarilla', 'Tecoma stans', 'Arbusto o árbol pequeño muy resistente a la sequía, que produce deslumbrantes racimos de flores amarillas en forma de trompeta.', 'bignonia-amarilla.jpg', '6m - 9m', 'Primavera', 'La Bignonia amarilla, también conocida como \"Esperanza\", es una planta excepcionalmente resistente al clima cálido y seco del noreste de México. Su floración amarilla brillante ilumina los espacios durante gran parte del año. Al apadrinar esta especie, ayudas a mantener corredores biológicos urbanos, ya que es un imán natural para colibríes, abejas y mariposas.', 'En la herbolaria y medicina tradicional mexicana, las hojas y raíces de la Tecoma stans se han utilizado históricamente para preparar infusiones que ayudan a controlar los niveles de glucosa en la sangre.'),
(3, 'Anacahuita', 'Cordia boissieri', 'Árbol nativo de Nuevo León, de hojas ásperas y bellas flores blancas con centro amarillo, que produce frutos parecidos a pequeñas aceitunas.', 'anacahuita-img.jpg', '5m - 7m', 'Todo el año', 'La Anacahuita es un verdadero orgullo regional. Es un árbol perennifolio (siempre verde) de tamaño moderado que resiste maravillosamente las sequías y el calor de Santa Catarina. Sus flores blancas brotan casi todo el año y sus frutos alimentan a aves y fauna local, convirtiéndolo en un pilar vital para la salud del ecosistema dentro de la UTSC.', '¡Es oficialmente la flor representativa del estado de Nuevo León! Su fruto, aunque carnoso, es consumido principalmente por la fauna silvestre, y sus hojas se usan comúnmente en jarabes caseros contra la tos.'),
(4, 'Anacua', 'Ehretia anacua', 'Árbol frondoso y resistente, fácilmente reconocible porque sus hojas tienen una textura áspera como papel de lija. Produce abundantes florecillas blancas.', 'anacua.jpg', '4.5m - 15m', 'Primavera', 'La Anacua es una especie nativa altamente valorada por la densa sombra que proporciona gracias a su follaje perenne. Durante la primavera, se cubre por completo de pequeñas flores blancas muy aromáticas. Es un excelente guardián del campus debido a su alta tolerancia a suelos pobres y su capacidad para refrescar el ambiente en los meses más calurosos.', 'Sus hojas están cubiertas de diminutos pelos rígidos que le dan su característica textura de lija, lo cual es una adaptación natural para reducir la pérdida de agua en climas áridos.'),
(5, 'Ébano', 'Ebenopsis ebano', 'Árbol majestuoso del noreste de México, legendario por su madera dura y oscura. Tiene follaje denso y ofrece una de las mejores sombras de la región.', 'ebano.jpg', '6m - 9m', 'Primavera', 'El Ébano es un titán del matorral tamaulipeco. Es un árbol de crecimiento lento pero de una longevidad y resistencia impresionantes. Su densa copa de hojas verde oscuro proporciona un refugio fresco invaluable durante los intensos veranos regiomontanos. Además, al ser una leguminosa, sus raíces mejoran naturalmente la calidad del suelo del campus.', 'Las semillas que crecen dentro de sus gruesas vainas son comestibles y solían ser tostadas para usarlas como un sustituto del café. Su madera es tan densa y pesada que, a diferencia de otras, no flota en el agua.'),
(6, 'Mango', 'Mangifera indica', 'Majestuoso árbol frutal de copa amplia y densa, famoso por producir uno de los frutos tropicales más consumidos y deliciosos del mundo.', 'Mango.jpg', '10m - 20m', 'Primavera', 'Aunque es originario del sur de Asia, el árbol de Mango se ha adaptado a diversos microclimas en México. Es un árbol robusto de hojas perennes que proporciona una sombra densa y reparadora. Apadrinar un mango no solo embellece el entorno con su follaje exuberante, sino que fomenta una cultura de huertos y sustentabilidad dentro de la comunidad estudiantil.', '¡Existen más de 1,000 variedades de mango en el mundo! Curiosamente, este frutal está emparentado botánicamente con el árbol del pistache y con el de la nuez de la India.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_limpieza`
--

CREATE TABLE `historial_limpieza` (
  `id` int(11) NOT NULL,
  `fecha_limpieza` datetime DEFAULT current_timestamp(),
  `cantidad_eliminados` int(11) DEFAULT NULL,
  `admin_que_limpio` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `historial_limpieza`
--

INSERT INTO `historial_limpieza` (`id`, `fecha_limpieza`, `cantidad_eliminados`, `admin_que_limpio`) VALUES
(1, '2026-03-08 17:54:18', 2, 'erwinmtz22544@gmail.com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensajes_soporte`
--

CREATE TABLE `mensajes_soporte` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `asunto` varchar(150) DEFAULT NULL,
  `mensaje` text NOT NULL,
  `fecha_envio` datetime DEFAULT current_timestamp(),
  `leido` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mensajes_soporte`
--

INSERT INTO `mensajes_soporte` (`id`, `nombre`, `email`, `asunto`, `mensaje`, `fecha_envio`, `leido`) VALUES
(1, 'Erwin', 'erwinmartinez1317@gmail.com', 'Duda sobre apadrinamiento', 'prueba de mensaje para soporte ', '2026-03-08 18:27:08', 1),
(2, 'Erwin', 'erwin123@gmail.com', 'Problemas con la cuenta', 'prueba dos - contador', '2026-03-08 19:29:40', 1),
(3, 'luis', 'luis1234@gmail.com', 'Reportar un error en la web', 'prueba 3 - alertas', '2026-03-08 19:52:20', 1),
(4, 'liz', 'liz123@gmail.com', 'Problemas con la cuenta', 'lol', '2026-03-10 17:52:27', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id_rol` int(11) NOT NULL,
  `nombre_rol` varchar(50) NOT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id_rol`, `nombre_rol`, `descripcion`) VALUES
(1, 'Administrador', 'Acceso completo.'),
(2, 'Editor', 'Gestión de contenido en secciones asignadas.'),
(3, 'Usuario Registrado', 'Acceso a perfil personal.'),
(4, 'Visitante', 'Acceso solo a contenido público.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre_usuario` varchar(50) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `token` varchar(255) DEFAULT NULL,
  `verificado` tinyint(1) DEFAULT 0,
  `id_rol` int(11) DEFAULT 3,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre_usuario`, `nombre`, `email`, `password`, `token`, `verificado`, `id_rol`, `fecha_registro`) VALUES
(6, 'Erwin', NULL, 'erwinmtz22544@gmail.com', '$2y$10$lZy3sX3KWlwJ9LTY.ZfE6eunSg0dUjv.hD6UiMkDSoHscPmn4Hoyi', '6f8afb87dbc437d5953e86dbbf2e03c9', 1, 1, '2026-03-08 23:28:16');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `apadrinamientos`
--
ALTER TABLE `apadrinamientos`
  ADD PRIMARY KEY (`id_apadrinamiento`);

--
-- Indices de la tabla `arboles`
--
ALTER TABLE `arboles`
  ADD PRIMARY KEY (`id_arbol`);

--
-- Indices de la tabla `historial_limpieza`
--
ALTER TABLE `historial_limpieza`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `mensajes_soporte`
--
ALTER TABLE `mensajes_soporte`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre_usuario` (`nombre_usuario`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_usuario_rol` (`id_rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `apadrinamientos`
--
ALTER TABLE `apadrinamientos`
  MODIFY `id_apadrinamiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `arboles`
--
ALTER TABLE `arboles`
  MODIFY `id_arbol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `historial_limpieza`
--
ALTER TABLE `historial_limpieza`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `mensajes_soporte`
--
ALTER TABLE `mensajes_soporte`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_usuario_rol` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
