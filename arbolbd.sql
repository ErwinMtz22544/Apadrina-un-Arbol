-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 01-06-2026 a las 21:35:16
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
(18, 7, 3, 'prueba2', 'Individual', '2026-03-23 20:47:55', 500.00, 'Tarjeta'),
(19, 7, 6, 'Aporte de Mantenimiento', 'Donación', '2026-03-24 18:41:36', 100.00, 'Tarjeta'),
(20, 7, 6, 'Fido', 'Individual', '2026-03-24 18:42:49', 500.00, 'Tarjeta'),
(21, 9, 2, 'Fido', 'Individual', '2026-03-24 20:42:43', 500.00, 'Tarjeta'),
(22, 10, 6, 'Mangazo', 'Individual', '2026-03-25 01:00:50', 50000.00, 'PayPal'),
(23, 7, 3, 'Fido23', 'Individual', '2026-03-25 07:01:25', 500.00, 'Tarjeta'),
(24, 7, 2, 'Ola', 'Individual', '2026-03-25 07:21:52', 500.00, 'Oxxo'),
(25, 7, 2, 'paypal', 'Amigos', '2026-03-25 07:22:34', 500.00, 'PayPal'),
(26, 7, 2, 'ola2', 'Amigos', '2026-03-25 07:23:01', 500.00, 'Tarjeta'),
(27, 7, 2, 'Fido3333', 'Individual', '2026-03-25 07:23:33', 500.00, 'Tarjeta'),
(28, 7, 2, 'Fido3333', 'Individual', '2026-03-25 07:23:44', 500.00, 'Tarjeta'),
(29, 7, 2, 'prueba4', 'Individual', '2026-03-25 07:24:12', 500.00, 'Tarjeta'),
(30, 7, 5, 'eeeeee', 'Individual', '2026-03-25 07:24:44', 500.00, 'Tarjeta'),
(31, 7, 2, 'eeeeee', 'Individual', '2026-03-25 07:27:42', 100.00, 'Tarjeta'),
(32, 7, 6, 'prueba rubi', 'Individual', '2026-03-25 07:50:50', 100.00, 'Tarjeta'),
(33, 7, 5, 'Fido', 'Amigos', '2026-03-25 07:52:57', 100.00, 'Tarjeta'),
(34, 7, 2, 'prueba', 'Individual', '2026-03-25 07:58:18', 100.00, 'Tarjeta'),
(35, 7, 6, 'Aporte de Mantenimiento', 'Donación', '2026-03-25 07:59:01', 88888.00, 'Tarjeta'),
(36, 7, 2, 'pruebadefinitiva', 'Amigos', '2026-03-25 08:29:17', 100.00, 'Tarjeta'),
(37, 12, 1, 'UTSC', 'Individual', '2026-03-26 01:34:35', 100.00, 'Tarjeta'),
(38, 12, 5, 'Roblox', 'Individual', '2026-03-26 01:35:56', 100.00, 'Tarjeta'),
(39, 12, 5, 'Mi arbol', 'Individual', '2026-03-27 04:54:35', 100.00, 'Tarjeta');

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
  `datos_interesantes` text DEFAULT NULL,
  `disponible` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `arboles`
--

INSERT INTO `arboles` (`id_arbol`, `nombre_comun`, `nombre_cientifico`, `descripcion`, `imagen`, `altura`, `epoca`, `descripcion_larga`, `datos_interesantes`, `disponible`) VALUES
(1, 'Duraznillo', 'Cercis canadensis L', 'Árbol caducifolio famoso por su espectacular floración primaveral de color rosa brillante o magenta que aparece antes que sus hojas.', 'cercis-canadensis-img.jpg', '6m - 15m', 'Primavera', 'El Duraznillo o \"Árbol de Judas\" es una especie ornamental de tamaño pequeño a mediano. En primavera, sus ramas se cubren de racimos de flores rosadas antes de que broten sus hojas en forma de corazón. Es una especie muy valorada en el paisajismo de los campus por su belleza escénica y su capacidad para atraer mariposas y abejas, fomentando la biodiversidad.', 'Sus flores son comestibles y ricas en vitamina C, a menudo usadas en ensaladas gourmet. Además, pertenece a la familia de las leguminosas, por lo que ayuda a fijar nitrógeno, nutriendo la tierra a su alrededor.', 1),
(2, 'Bignonia amarilla', 'Tecoma stans', 'Arbusto o árbol pequeño muy resistente a la sequía, que produce deslumbrantes racimos de flores amarillas en forma de trompeta.', 'bignonia-amarilla.jpg', '6m - 9m', 'Primavera', 'La Bignonia amarilla, también conocida como \"Esperanza\", es una planta excepcionalmente resistente al clima cálido y seco del noreste de México. Su floración amarilla brillante ilumina los espacios durante gran parte del año. Al apadrinar esta especie, ayudas a mantener corredores biológicos urbanos, ya que es un imán natural para colibríes, abejas y mariposas.', 'En la herbolaria y medicina tradicional mexicana, las hojas y raíces de la Tecoma stans se han utilizado históricamente para preparar infusiones que ayudan a controlar los niveles de glucosa en la sangre.', 1),
(3, 'Anacahuita', 'Cordia boissieri', 'Árbol nativo de Nuevo León, de hojas ásperas y bellas flores blancas con centro amarillo, que produce frutos parecidos a pequeñas aceitunas.', 'anacahuita-img.jpg', '5m - 7m', 'Todo el año', 'La Anacahuita es un verdadero orgullo regional. Es un árbol perennifolio (siempre verde) de tamaño moderado que resiste maravillosamente las sequías y el calor de Santa Catarina. Sus flores blancas brotan casi todo el año y sus frutos alimentan a aves y fauna local, convirtiéndolo en un pilar vital para la salud del ecosistema dentro de la UTSC.', '¡Es oficialmente la flor representativa del estado de Nuevo León! Su fruto, aunque carnoso, es consumido principalmente por la fauna silvestre, y sus hojas se usan comúnmente en jarabes caseros contra la tos.', 1),
(4, 'Anacua', 'Ehretia anacua', 'Árbol frondoso y resistente, fácilmente reconocible porque sus hojas tienen una textura áspera como papel de lija. Produce abundantes florecillas blancas.', 'anacua.jpg', '4.5m - 15m', 'Primavera', 'La Anacua es una especie nativa altamente valorada por la densa sombra que proporciona gracias a su follaje perenne. Durante la primavera, se cubre por completo de pequeñas flores blancas muy aromáticas. Es un excelente guardián del campus debido a su alta tolerancia a suelos pobres y su capacidad para refrescar el ambiente en los meses más calurosos.', 'Sus hojas están cubiertas de diminutos pelos rígidos que le dan su característica textura de lija, lo cual es una adaptación natural para reducir la pérdida de agua en climas áridos.', 1),
(5, 'Ébano', 'Ebenopsis ebano', 'Árbol majestuoso del noreste de México, legendario por su madera dura y oscura. Tiene follaje denso y ofrece una de las mejores sombras de la región.', 'ebano.jpg', '6m - 9m', 'Primavera', 'El Ébano es un titán del matorral tamaulipeco. Es un árbol de crecimiento lento pero de una longevidad y resistencia impresionantes. Su densa copa de hojas verde oscuro proporciona un refugio fresco invaluable durante los intensos veranos regiomontanos. Además, al ser una leguminosa, sus raíces mejoran naturalmente la calidad del suelo del campus.', 'Las semillas que crecen dentro de sus gruesas vainas son comestibles y solían ser tostadas para usarlas como un sustituto del café. Su madera es tan densa y pesada que, a diferencia de otras, no flota en el agua.', 1),
(6, 'Mango', 'Mangifera indica', 'Majestuoso árbol frutal de copa amplia y densa, famoso por producir uno de los frutos tropicales más consumidos y deliciosos del mundo.', 'mango.jpg', '10m - 20m', 'Primavera', 'Aunque es originario del sur de Asia, el árbol de Mango se ha adaptado a diversos microclimas en México. Es un árbol robusto de hojas perennes que proporciona una sombra densa y reparadora. Apadrinar un mango no solo embellece el entorno con su follaje exuberante, sino que fomenta una cultura de huertos y sustentabilidad dentro de la comunidad estudiantil.', '¡Existen más de 1,000 variedades de mango en el mundo! Curiosamente, este frutal está emparentado botánicamente con el árbol del pistache y con el de la nuez de la India.', 1);

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
(4, 'liz', 'liz123@gmail.com', 'Problemas con la cuenta', 'lol', '2026-03-10 17:52:27', 1),
(5, 'Liz', 'lizethlzvn@gmail.com', 'Problemas con la cuenta', 'Esto es una prueba', '2026-03-24 11:48:08', 0),
(6, 'Erwin', 'erwinmartinez1317@gmail.com', 'Reportar un error en la web', 'No jala el apartado de cuenta en el menú hamburguesa en celular aifon ', '2026-03-24 14:04:55', 0),
(7, 'Erick Solis', 'solissanchez987@gmail.com', 'Reportar un error en la web', 'Se la volaron equipo, esta perrisima esta pagina', '2026-03-24 15:32:04', 0),
(8, 'Liz', 'lizethlzvn@gmail.com', 'Reportar un error en la web', 'OLAOLAOALA', '2026-03-25 18:36:32', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `noticias`
--

CREATE TABLE `noticias` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `contenido` text NOT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `fecha_publicacion` date DEFAULT curdate(),
  `estado` tinyint(1) DEFAULT 1 COMMENT '1 = Activa, 0 = Inactiva',
  `destacado` tinyint(1) DEFAULT 0 COMMENT '1 = Visible en Inicio, 0 = Solo en Noticias'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `noticias`
--

INSERT INTO `noticias` (`id`, `titulo`, `contenido`, `imagen`, `fecha_publicacion`, `estado`, `destacado`) VALUES
(1, 'Conoce más sobre Dwight, el primer árbol de nuestra campaña', 'Nos enorgullece presentar a Dwight, el primer árbol nativo en ser adoptado oficialmente bajo este programa institucional de la UTSC.\n\nEste ejemplar ha sido geolocalizado y asignado a su padrino para realizar un seguimiento activo de su crecimiento.', '../assets/img/noticias/el_primer_arbol.png', '2026-05-18', 1, 0),
(2, '¡Llegamos a los 20 árboles apadrinados!', 'Gracias al enorme compromiso de los estudiantes, docentes y el comité de TroncosTeam, hemos alcanzado la increíble cifra de 20 boles apadrinados en la plataforma digital.\n\nCada uno de estos ejemplares representa un pulmón nuevo para nuestra institución.', '../assets/img/noticias/noticia-2.jpg', '2026-05-18', 1, 0),
(3, 'Pancrasio, el futuro árbol revolucionario', 'Te presentamos la historia de Pancrasio, el árbol que se ha convertido en el símbolo de la innovación en reforestación comunitaria.\n\nPancrasio forma parte del nuevo sector de riego optimizado de la universidad.', '../assets/img/noticias/noticia-3.jpg', '2026-05-18', 1, 0),
(4, '¡Entrevista a Fabián: el primer apadrinador que donó $10,000 MXN!', 'El día de hoy tuvimos el honor de entrevistar a Fabián, un alumno de la UTSC que sorprendió a todo el proyecto de Apadrina un Árbol al realizar una generosa donación de $10,000 MXN para la conservación de nuestras áreas verdes. Aquí les compartimos un fragmento de lo que platicamos con él:\n\n<strong>Apadrina un Árbol: Fabián, una donación de esta magnitud dejó sorprendido a todo el equipo. ¿Qué te motivó a dar este gran paso?</strong>\n\nFabián: (Se muestra divertido y entre risas) Porque ayuda a la gente y es bueno, ¡y porque ayuda a la gente! Ya en serio, siempre he creído que como estudiantes de la UTSC tenemos la responsabilidad de cuidar nuestro entorno. El proyecto me pareció transparente, confiable y una excelente forma de dejar una huella verde duradera.\n\n<strong>Apadrina un Árbol: ¿Qué le dirías a los demás compañeros que aún están pensando en adoptar?</strong>\n\nFabián: Que no lo piensen tanto. Cada peso cuenta y ver que puedes seguir el crecimiento de tu ejemplar desde la plataforma te da la certeza de que tu apoyo realmente está cambiando el campus.\n\nPor otro lado, nuestro compañero Erick, gran amigo del donador, se mostró sumamente conmovido y declaró estar profundamente agradecido con Fabián por demostrar el verdadero espíritu de nuestra comunidad universitaria. \n\n¡Un agradecimiento enorme a ambos! Y como el TroncosTeam sabe reconocer el gran corazón de sus patrocinadores (y la enorme ayuda que nos dio), ya quedó pactado que al final le pichamos unos buenos tacos a Fabián para celebrar este gran logro por el campus.', '../assets/img/noticias/noticia-4.png', '2026-05-31', 1, 1),
(5, '¡El esperado regreso de RulisYT! Su primer video es apadrinando un árbol', '¡El internet está rompiendo el contador de vistas! Después de meses de ausencia en las redes sociales, el famoso creador de contenido RulisYT ha sorprendido a todos sus seguidores con su gran video de regreso. Pero lo que nadie se esperaba era el increíble propósito de su nuevo metraje: unirse a la causa ambiental.\n\nEn su video titulado \"Volví... y cambié la vida de alguien\", RulisYT documentó todo su proceso utilizando nuestra plataforma para adoptar un ejemplar, invitando a sus millones de suscriptores a sumarse al TroncosTeam. Tuvimos la oportunidad de mandarle un mensaje rápido y esto fue lo que nos platicó:\n\n<strong>Apadrina un Árbol: Rulis, ¡bienvenido de vuelta! Todos tus fans están vueltos locos. ¿Por qué decidiste regresar con una temática de reforestación?</strong>\n\nRulisYT: ¡Qué onda, plebes! Pues miren, quería que mi regreso no fuera solo hacer ruido por hacer ruido. Quería aprovechar que la gente iba a estar atenta para armar algo chido que de verdad dejara un impacto positivo, y el proyecto de Apadrina un Árbol me pareció la opción más perrona para empezar esta nueva etapa.\n\n<strong>Apadrina un Árbol: En tu video mencionas que retaste a otros creadores de contenido a adoptar. ¿Es real?</strong>\n\nRulisYT: ¡Al 100%! Ya nominé a varios amigos del medio en los comentarios del video. El reto es ver quién junta el bosque más grande en la plataforma antes de que acabe el mes. ¡Así que pónganse las pilas todos!\n\nEl impacto del video ha sido tan grande que los servidores del proyecto no han dejado de recibir tráfico de nuevos usuarios interesados en el campus. ¡Muchas gracias por el enorme apoyo, Rulis!', '../assets/img/noticias/noticia-5.png', '2026-06-01', 1, 1),
(6, '¡Jenn adopta el último árbol por pánico escolar!', '¡El poder de la motivación académica! La plataforma de Apadrina un Árbol colapsó por unos minutos esta tarde debido a un incremento masivo de tráfico de última hora. ¿La razón? Se corrió el rumor en los pasillos de que registrar un árbol en el campus otorgaba puntos directos para salvar el cuatrimestre.\n\nLa estudiante Jenn rompió el récord de velocidad de la plataforma al completar su proceso de adopción en menos de 15 segundos. Fuimos a buscarla para conocer su historia de éxito ambiental:\n\n<strong>Apadrina un Árbol: Jenn, ¡felicidades por tu nuevo árbol! Te vimos teclear a la velocidad de la luz. ¿Siempre fuiste una apasionada del ODS 15?</strong>\n\nJenn: (Recuperando el aliento) ¡Totalmente! O sea, yo amo la naturaleza, claro que sí... pero amo más pasar mis materias. En cuanto escuché lo de los puntos, abrí el celular, ignoré cualquier error de red y le di clic a \"Apadrinar\" como si mi vida dependiera de ello. De hecho, me contaron que Liz se estuvo burlando de mí porque dice que ignoré a medio mundo y casi aviento a tres compañeros en el pasillo con tal de asegurar ese árbol antes que todos.\n\n<strong>Apadrina un Árbol: El TroncosTeam reporta que le pusiste un nombre muy original a tu árbol en la base de datos. ¿Nos puedes contar el significado?</strong>\n\nJenn: ¡Ah, claro! Lo registré oficialmente como \"Milagrito Verde\". Es un recordatorio de que gracias a su sombra y a su existencia, mi promedio va a sobrevivir un cuatrimestre más. Ya descargué mi certificado PDF y lo tengo de fondo de pantalla por si las dudas.\n\nAl final, no importa si es por amor a la ecología o por puro pánico escolar, lo importante es que el campus tiene un árbol más plantado y Jenn un respiro en sus calificaciones. ¡Eso es un ganar-ganar para todos!', '../assets/img/noticias/noticia-6.png', '2026-06-01', 1, 1);

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
(6, 'Erwin', NULL, 'erwinmtz22544@gmail.com', '$2y$10$lZy3sX3KWlwJ9LTY.ZfE6eunSg0dUjv.hD6UiMkDSoHscPmn4Hoyi', '6f8afb87dbc437d5953e86dbbf2e03c9', 1, 1, '2026-03-08 23:28:16'),
(7, 'liz', NULL, 'lizethlzvn@gmail.com', '$2y$10$XAq6HWnpTa94UVR6OXgKU.Qt.0aQPTMEAaIm4q.YnvbdIF6k1NZM.', NULL, 1, 1, '2026-03-23 20:16:32'),
(10, 'Filomeno Montes de Oca', NULL, 'otistotis10@gmail.com', '$2y$10$SlnnkEjnxyruqaM/UCmV8ehIO1kvGubdr/R2JEzdNfloYAbBzpdyy', NULL, 1, 3, '2026-03-25 00:57:40'),
(11, 'Esmeralda', NULL, 'esmeraldagomezcruz613@gmail.com', '$2y$10$mPHR3ZEcmSFuABATikUu0emSzUvZnC80R4qw4UcXlrAprcPoo0AX6', '1cb3fdc286ba4df893f786359af8c9e7', 1, 3, '2026-03-25 06:25:22'),
(12, 'Soporte', NULL, 'apadrinaunarbolutsc@gmail.com', '$2y$10$JRVLNMBL.ci9NcVOo/4jluLK6vKkW0kWrKTGk7YF.qYpcM1Tc6zgS', 'dddc96853e0d19c8c2dbf9a5fa23e98c', 1, 1, '2026-03-26 01:32:30');

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
-- Indices de la tabla `noticias`
--
ALTER TABLE `noticias`
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
  MODIFY `id_apadrinamiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `noticias`
--
ALTER TABLE `noticias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

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
