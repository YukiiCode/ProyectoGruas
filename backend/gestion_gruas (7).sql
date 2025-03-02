-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Servidor: mysql
-- Tiempo de generación: 01-03-2025 a las 18:50:37
-- Versión del servidor: 8.0.41
-- Versión de PHP: 8.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `gestion_gruas`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `logs`
--

CREATE TABLE `logs` (
  `id` int NOT NULL,
  `usuario_id` int NOT NULL,
  `accion` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `detalles` text COLLATE utf8mb4_general_ci,
  `fecha_hora` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_02_09_000000_modify_vehiculos_table_id', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_general_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_general_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 1, 'auth_token', '53de87be104b30f2cf1e6f3e8423159e7621495c0b34be23f9d7ee20623dda95', '[\"*\"]', NULL, NULL, '2025-02-08 23:00:57', '2025-02-08 23:00:57'),
(2, 'App\\Models\\User', 1, 'auth_token', '7deb642251e0e6efbfccf9c7cdd15cdac733e2fd1b23f794b04d493429f6a18e', '[\"*\"]', '2025-02-08 23:01:41', NULL, '2025-02-08 23:01:11', '2025-02-08 23:01:41'),
(3, 'App\\Models\\User', 1, 'auth_token', 'e254f7c56d0428cac0ab9ab468c1ed9321a3416c27c5fbd00aa3b437e0f019ee', '[\"*\"]', '2025-02-08 23:15:10', NULL, '2025-02-08 23:02:04', '2025-02-08 23:15:10'),
(4, 'App\\Models\\User', 1, 'auth_token', '8e38854e64e64554957677a0eaa6628aa7ca029756cf613d4e4b2f39a3a490e7', '[\"*\"]', '2025-02-08 23:19:49', NULL, '2025-02-08 23:15:27', '2025-02-08 23:19:49'),
(5, 'App\\Models\\User', 1, 'auth_token', 'e2a9a6923a2015d92486e4b50c50e261bec638851ab1ab6dfcfc0031c8326e4e', '[\"*\"]', '2025-02-08 23:20:06', NULL, '2025-02-08 23:20:06', '2025-02-08 23:20:06'),
(6, 'App\\Models\\User', 1, 'auth_token', '154725b004966467d09f778b9ac91a66172654cb946e206efb3f05ab1e9fd79e', '[\"*\"]', '2025-02-08 23:26:47', NULL, '2025-02-08 23:26:47', '2025-02-08 23:26:47'),
(7, 'App\\Models\\User', 1, 'auth_token', 'c733af08e842cceb71454133fd3a346d88f09f07aca2d58bd6d2ba8dc9c020e8', '[\"*\"]', NULL, NULL, '2025-02-08 23:42:50', '2025-02-08 23:42:50'),
(8, 'App\\Models\\User', 1, 'auth_token', '0869a931b1ed38d362aec8053669c4f3cedcec7de7e91eb42514126727170b72', '[\"*\"]', NULL, NULL, '2025-02-08 23:49:24', '2025-02-08 23:49:24'),
(9, 'App\\Models\\User', 1, 'auth_token', 'df4f86ba89ba1c1faea4b91575b9e384a9deae576b900ce36d69ae768aa255c3', '[\"*\"]', NULL, NULL, '2025-02-08 23:55:21', '2025-02-08 23:55:21'),
(10, 'App\\Models\\User', 1, 'auth_token', '573aed21cba47b569029696a3f0fca2077a577da3f076111f12e89813248421c', '[\"*\"]', NULL, NULL, '2025-02-10 07:18:18', '2025-02-10 07:18:18'),
(11, 'App\\Models\\User', 1, 'auth_token', 'be227741865beb805ac4e66cbb09b73baab2da80b15569cb898ee0c52f9973ee', '[\"*\"]', NULL, NULL, '2025-02-10 07:18:20', '2025-02-10 07:18:20'),
(12, 'App\\Models\\User', 1, 'auth_token', '01555f532b8d5b24fb3cf231be60ba7430746a5504c109d2150622ffa3cb3569', '[\"*\"]', NULL, NULL, '2025-02-10 07:18:20', '2025-02-10 07:18:20'),
(13, 'App\\Models\\User', 1, 'auth_token', '4a898a654cde625ed2d37342380a2e6a8336e8cfa2a4f02504a55b81c8b90c5f', '[\"*\"]', NULL, NULL, '2025-02-10 07:18:54', '2025-02-10 07:18:54'),
(14, 'App\\Models\\User', 1, 'auth_token', '28b054c50b57f6a48935f06120a94df444055baf605ae93b05ecf9503f09f460', '[\"*\"]', NULL, NULL, '2025-02-10 07:19:27', '2025-02-10 07:19:27'),
(15, 'App\\Models\\User', 1, 'auth_token', '22d143932ec053f97f3cca7b5188e9869843fad2b13bb2e25cfe0c126a110048', '[\"*\"]', NULL, NULL, '2025-02-10 07:26:18', '2025-02-10 07:26:18'),
(16, 'App\\Models\\User', 1, 'auth_token', 'c409fd95871ab74139cb5d8c2eeee018910da7f726294ac8b787fe2da935b02b', '[\"*\"]', '2025-02-10 08:02:05', NULL, '2025-02-10 07:26:19', '2025-02-10 08:02:05'),
(17, 'App\\Models\\User', 1, 'auth_token', '01dd62d6be39b6f52b803fd9a477cdd0350eeb393f2b7182ce36494b0a02e95f', '[\"*\"]', '2025-02-10 09:16:16', NULL, '2025-02-10 09:16:04', '2025-02-10 09:16:16'),
(18, 'App\\Models\\User', 1, 'auth_token', 'fa23c295ebb81fd64ac81afd1e7ed2c1f9c53163d2074becc8854cd802255636', '[\"*\"]', '2025-02-10 12:04:09', NULL, '2025-02-10 09:29:24', '2025-02-10 12:04:09'),
(19, 'App\\Models\\User', 1, 'auth_token', 'c7f01db792a39fa12b028d060c04753293c1fd45102e59ca0fb6c2a7b84875d2', '[\"*\"]', NULL, NULL, '2025-02-13 11:30:09', '2025-02-13 11:30:09'),
(20, 'App\\Models\\User', 1, 'auth_token', 'cf4518cd85bbca99c1a5ba5f20dc29bd10aa8725020d1e3c42cc066ff3264158', '[\"*\"]', '2025-02-13 12:10:53', NULL, '2025-02-13 11:30:10', '2025-02-13 12:10:53'),
(21, 'App\\Models\\User', 1, 'auth_token', '45ddb4b2b8859705782d7b067f3f513857bd59f70e6955d1bb8cce48886c4924', '[\"*\"]', '2025-02-17 09:45:48', NULL, '2025-02-13 12:11:51', '2025-02-17 09:45:48'),
(22, 'App\\Models\\User', 1, 'auth_token', 'b5ad5887ce7c22fa0b97c0c1c7b3c58c5b2c8ab095dfacb5a8ad839390028e0f', '[\"*\"]', NULL, NULL, '2025-02-22 11:44:06', '2025-02-22 11:44:06'),
(23, 'App\\Models\\User', 1, 'auth_token', '1fcad71ea5c68e05b06bf00b34f45e8227b78322cded70588762138130cb7f5c', '[\"*\"]', NULL, NULL, '2025-02-22 11:44:06', '2025-02-22 11:44:06'),
(24, 'App\\Models\\User', 1, 'auth_token', '4421c11475092eb7f006118a506dd7c0f13582e3e353f79afc77ee8902ca8dc2', '[\"*\"]', NULL, NULL, '2025-02-22 11:44:06', '2025-02-22 11:44:06'),
(25, 'App\\Models\\User', 1, 'auth_token', '94172018f73e1bd8a83b001e7ac6258d6b2ec017599d69a31d3a4bb31eab3c32', '[\"*\"]', NULL, NULL, '2025-02-22 11:44:07', '2025-02-22 11:44:07'),
(26, 'App\\Models\\User', 1, 'auth_token', 'bb4735e03c9a64e142188a18368969fab8bafc6879828d45f9bb7c7e5c067524', '[\"*\"]', NULL, NULL, '2025-02-22 11:44:07', '2025-02-22 11:44:07'),
(27, 'App\\Models\\User', 1, 'auth_token', 'edd5aaf98e14b5dda8b0fdc5c52d2ad7fd771ebe9398c9f2bbef30371098a011', '[\"*\"]', NULL, NULL, '2025-02-22 11:44:07', '2025-02-22 11:44:07'),
(28, 'App\\Models\\User', 1, 'auth_token', 'aed4e10c72a591801a92a5c330e4ea12cfd152b3e9cd09b8c9477600e79504ec', '[\"*\"]', NULL, NULL, '2025-02-22 11:44:07', '2025-02-22 11:44:07'),
(29, 'App\\Models\\User', 1, 'auth_token', '002c8efb9435728220583eddcd57715eb06caabb9a5b70f35e315bc1899dac82', '[\"*\"]', NULL, NULL, '2025-02-22 11:44:08', '2025-02-22 11:44:08'),
(30, 'App\\Models\\User', 1, 'auth_token', 'f55a4a4b2c1580e6843f3f14760f6f4aba96c53f87b8db118644948150af7cea', '[\"*\"]', NULL, NULL, '2025-02-22 11:44:09', '2025-02-22 11:44:09'),
(31, 'App\\Models\\User', 1, 'auth_token', '3d3f22e12fcb26deb8625b7e375aa1c09968aeb0488af819f3f8238333dc0db9', '[\"*\"]', NULL, NULL, '2025-02-22 11:44:10', '2025-02-22 11:44:10'),
(32, 'App\\Models\\User', 1, 'auth_token', 'fdc8339fe89e15256fc7bb08dac9f9fe9303cf5a7304baef0cbf46e9e2f11841', '[\"*\"]', NULL, NULL, '2025-02-22 11:44:10', '2025-02-22 11:44:10'),
(33, 'App\\Models\\User', 1, 'auth_token', '82603c16fbb657862478eb5818bf80bfce0a95eadab384a4787ba8b8c6a10452', '[\"*\"]', NULL, NULL, '2025-02-22 11:45:42', '2025-02-22 11:45:42'),
(34, 'App\\Models\\User', 1, 'auth_token', '01a3d214f43de45d63b7ada47e7897e9c8826ed7339ffec1c7d8c9e5ee7601a9', '[\"*\"]', '2025-02-22 11:51:26', NULL, '2025-02-22 11:47:18', '2025-02-22 11:51:26'),
(35, 'App\\Models\\User', 1, 'auth_token', '8ee2bd4b0fd2068d2f729239a9bb4d194a79a2b72cf4f79854b35388059fcc0e', '[\"*\"]', '2025-02-22 11:55:54', NULL, '2025-02-22 11:55:44', '2025-02-22 11:55:54'),
(36, 'App\\Models\\User', 1, 'auth_token', '7a904e1b794ec2990b37e8000f1ddb31a030a0b4e5e8e8512961a8050c5355ef', '[\"*\"]', '2025-02-22 12:36:45', NULL, '2025-02-22 12:36:44', '2025-02-22 12:36:45'),
(37, 'App\\Models\\User', 1, 'auth_token', '3a50db87ec6a80603a4efdb329ff0043e72964392f75dca712bbf60be060c74f', '[\"*\"]', '2025-02-22 17:51:39', NULL, '2025-02-22 12:46:31', '2025-02-22 17:51:39'),
(38, 'App\\Models\\User', 1, 'auth_token', '7a6fb09db1a202004f467b8c1dff6384ea4aeb7562a49628b6e4b61a61fd224b', '[\"*\"]', '2025-02-22 18:18:43', NULL, '2025-02-22 17:55:39', '2025-02-22 18:18:43'),
(39, 'App\\Models\\User', 1, 'auth_token', 'e003b52dcc5ca2c2b6e1da6a90d32eee5598d9bd64ac2b8cef0e5403321ec717', '[\"*\"]', NULL, NULL, '2025-02-24 07:32:41', '2025-02-24 07:32:41'),
(40, 'App\\Models\\User', 1, 'auth_token', 'a5f0c24b21bd67e257f72506dfa4940f6e58e45434352d703d0428f8bb01407f', '[\"*\"]', NULL, NULL, '2025-02-26 18:39:17', '2025-02-26 18:39:17'),
(41, 'App\\Models\\User', 1, 'auth_token', '188c1d8fa0c43a955ac3745f14d9576ad1d61d612515bf97c7690a14aa6082ce', '[\"*\"]', NULL, NULL, '2025-03-01 10:23:26', '2025-03-01 10:23:26'),
(42, 'App\\Models\\User', 1, 'auth_token', 'cc8b7c15124143ae58aa8d7b2d295a94bcf530570dae38b02f6310e6c8c2cafb', '[\"*\"]', NULL, NULL, '2025-03-01 11:00:23', '2025-03-01 11:00:23'),
(43, 'App\\Models\\User', 1, 'auth_token', 'ebfffa7ffd9cb47757a1f6c417b6c08c7e3e75e8b010bcf866809afb1670b5b8', '[\"*\"]', NULL, NULL, '2025-03-01 11:09:45', '2025-03-01 11:09:45'),
(44, 'App\\Models\\User', 1, 'auth_token', '9320bf9d5e0c5f4a30aaf089a6a5ef46200d44dd6221e2ef32e84b6fd9affc61', '[\"*\"]', NULL, NULL, '2025-03-01 11:11:42', '2025-03-01 11:11:42'),
(45, 'App\\Models\\User', 1, 'auth_token', '077824bb0fc1985f061a49e192f8a45f689f9b7a7e6268a864306d9892f112f1', '[\"*\"]', '2025-03-01 11:16:47', NULL, '2025-03-01 11:16:46', '2025-03-01 11:16:47'),
(46, 'App\\Models\\User', 1, 'auth_token', '0d893dd2fdc9a39e89ce54967bbc9c33d3a74f68e03e0b816b3baf2d7175ba6e', '[\"*\"]', '2025-03-01 11:17:10', NULL, '2025-03-01 11:17:09', '2025-03-01 11:17:10'),
(47, 'App\\Models\\User', 1, 'auth_token', 'fea0e9d22599817355ee6169dd89bed858ce63d4028738092f1023e24afe6fcf', '[\"*\"]', '2025-03-01 11:19:32', NULL, '2025-03-01 11:19:32', '2025-03-01 11:19:32'),
(48, 'App\\Models\\User', 1, 'auth_token', '74b7e4b022b69fdc3461c3561308f36679c7142e7aef98e452433d6662448a6d', '[\"*\"]', '2025-03-01 11:19:47', NULL, '2025-03-01 11:19:46', '2025-03-01 11:19:47'),
(49, 'App\\Models\\User', 1, 'auth_token', 'a7489df9b03ae3ed76267b378b9bd61ea2de383e876a82d4477d8e0c78bd573d', '[\"*\"]', '2025-03-01 11:21:38', NULL, '2025-03-01 11:21:37', '2025-03-01 11:21:38'),
(50, 'App\\Models\\User', 1, 'auth_token', 'e671cc521db27f67dc36e76cc4ae337b44550a45c3a580e460817adbfac7b97a', '[\"*\"]', '2025-03-01 11:26:31', NULL, '2025-03-01 11:26:21', '2025-03-01 11:26:31'),
(51, 'App\\Models\\User', 1, 'auth_token', '4a24ecb1d1cfcd810c84385f20d7772da5bae222b6440e89ff305539a410ff65', '[\"*\"]', '2025-03-01 11:26:50', NULL, '2025-03-01 11:26:45', '2025-03-01 11:26:50'),
(52, 'App\\Models\\User', 1, 'auth_token', '0179543d5bda30c295c0fd685785dd93e1406d8f736a421646fc989b9bdc2442', '[\"*\"]', '2025-03-01 11:27:16', NULL, '2025-03-01 11:27:01', '2025-03-01 11:27:16'),
(53, 'App\\Models\\User', 1, 'auth_token', '95209f5aba2536c2acb65d8fb49f504ea9e96a6ed8f9cb3dc7166e259b92f771', '[\"*\"]', '2025-03-01 11:29:12', NULL, '2025-03-01 11:29:09', '2025-03-01 11:29:12'),
(54, 'App\\Models\\User', 1, 'auth_token', 'db0f42ed648e524e05d7335e0d5fd21ea7a50bfd7f29fe4042daa48ff4445101', '[\"*\"]', '2025-03-01 11:29:58', NULL, '2025-03-01 11:29:18', '2025-03-01 11:29:58'),
(55, 'App\\Models\\User', 1, 'auth_token', 'b9644ed235fc7ab0901bf19efbe4013c82ea862b55973223f4563b959dc90fdd', '[\"*\"]', '2025-03-01 11:32:36', NULL, '2025-03-01 11:32:31', '2025-03-01 11:32:36'),
(56, 'App\\Models\\User', 1, 'auth_token', '79e7c3860d8b4c9948ad72358818b51b6044ee1c723123fcff9558b1c5d53593', '[\"*\"]', '2025-03-01 11:33:01', NULL, '2025-03-01 11:32:55', '2025-03-01 11:33:01'),
(57, 'App\\Models\\User', 1, 'auth_token', 'bea80a78c3144ed5906570423aaad0ddbb6fa52e3cc61f024dc3a73f91ae4581', '[\"*\"]', '2025-03-01 11:43:26', NULL, '2025-03-01 11:43:17', '2025-03-01 11:43:26'),
(58, 'App\\Models\\User', 1, 'auth_token', 'f389b6a5304a50e9ca72d6ac48bcdd35303267b311b56577230ccd37947870ec', '[\"*\"]', '2025-03-01 11:44:06', NULL, '2025-03-01 11:43:51', '2025-03-01 11:44:06'),
(59, 'App\\Models\\User', 1, 'auth_token', 'e0258617802188487fcbf3ceed8c54736104814bc4ae7233fcd80cab279c4fda', '[\"*\"]', '2025-03-01 11:44:37', NULL, '2025-03-01 11:44:19', '2025-03-01 11:44:37'),
(60, 'App\\Models\\User', 1, 'auth_token', '0bc4e93d0dfab409094fa615fab5c35b6dfdde6b0916ba0f6a9c76524306109c', '[\"*\"]', '2025-03-01 11:44:50', NULL, '2025-03-01 11:44:45', '2025-03-01 11:44:50'),
(61, 'App\\Models\\User', 1, 'auth_token', '856a56d2715e3a420ba28b51037eb9df87b3a2b17961121b4c1e31a281dbd994', '[\"*\"]', '2025-03-01 11:56:44', NULL, '2025-03-01 11:48:36', '2025-03-01 11:56:44'),
(62, 'App\\Models\\User', 1, 'auth_token', '221a3a5969b7c5007e3e8d7c1eb6325e5c1c04bd0062d2feb348ca2df0a71ce2', '[\"*\"]', '2025-03-01 12:54:07', NULL, '2025-03-01 11:57:41', '2025-03-01 12:54:07'),
(63, 'App\\Models\\User', 1, 'auth_token', 'c3bd8b86f7b5c8dc242d082cee050aa15df14ec0c6cb6737e4457b31bd9275bb', '[\"*\"]', '2025-03-01 18:50:15', NULL, '2025-03-01 17:57:47', '2025-03-01 18:50:15');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `retiradas`
--

CREATE TABLE `retiradas` (
  `id` int NOT NULL,
  `vehiculo_id` int NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `nif` varchar(15) COLLATE utf8mb4_general_ci NOT NULL,
  `domicilio` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `poblacion` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `provincia` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `permiso` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `fecha` datetime NOT NULL,
  `agente` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `importeretirada` decimal(10,2) NOT NULL,
  `importedeposito` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `opcionespago` enum('Metálico','Tarjeta','Bizum') COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `retiradas`
--

INSERT INTO `retiradas` (`id`, `vehiculo_id`, `nombre`, `nif`, `domicilio`, `poblacion`, `provincia`, `permiso`, `fecha`, `agente`, `importeretirada`, `importedeposito`, `total`, `opcionespago`) VALUES
(1, 1, 'Juan Pérez', '12345678A', 'Calle Mayor, 45', 'Madrid', 'Madrid', 'B-1234567', '2023-10-05 12:00:00', 'Agente01', 100.00, 50.00, 150.00, 'Metálico'),
(2, 2, 'Ana López', '87654321B', 'Avenida Libertad, 12', 'Barcelona', 'Barcelona', 'C-9876543', '2023-10-06 15:30:00', 'Agente02', 130.00, 60.00, 190.00, 'Tarjeta'),
(3, 1, 'Carlos Gómez', '11223344C', 'Plaza España, 10', 'Valencia', 'Valencia', 'D-5555555', '2023-10-07 10:45:00', 'Agente03', 100.00, 50.00, 150.00, 'Bizum');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tarifas`
--

CREATE TABLE `tarifas` (
  `id` int NOT NULL,
  `tipovehiculo` enum('Motocicleta','Turismo_hasta_12cv','Turismo_mas_12cv','Especial','Cortesia','Chatarra') COLLATE utf8mb4_general_ci NOT NULL,
  `costobase` decimal(10,2) NOT NULL,
  `horasgratis` int DEFAULT '0',
  `costoporhora` decimal(10,2) DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tarifas`
--

INSERT INTO `tarifas` (`id`, `tipovehiculo`, `costobase`, `horasgratis`, `costoporhora`) VALUES
(1, 'Motocicleta', 25.00, 0, 5.00),
(2, 'Turismo_hasta_12cv', 100.00, 24, 10.00),
(3, 'Turismo_mas_12cv', 130.00, 24, 15.00),
(4, 'Especial', 150.00, 0, 20.00),
(5, 'Cortesia', 0.00, 0, 0.00),
(6, 'Chatarra', 0.00, 0, 0.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `usuario` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `rol` enum('admin','operador') COLLATE utf8mb4_general_ci DEFAULT 'operador',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `usuario`, `password`, `rol`, `created_at`, `updated_at`) VALUES
(1, 'Administrador Principal', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', '2025-02-08 21:17:19', '2025-02-08 21:17:19'),
(2, 'Operador Grúa', 'operador', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'operador', '2025-02-08 21:17:19', '2025-02-08 21:17:19');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vehiculos`
--

CREATE TABLE `vehiculos` (
  `id` int NOT NULL,
  `fechaentrada` datetime DEFAULT NULL,
  `fechasalida` datetime DEFAULT NULL,
  `lugar` varchar(255) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `agente` varchar(255) DEFAULT NULL,
  `matricula` varchar(20) DEFAULT NULL,
  `marca` varchar(100) DEFAULT NULL,
  `modelo` varchar(100) DEFAULT NULL,
  `color` varchar(50) DEFAULT NULL,
  `tipovehiculo` varchar(255) DEFAULT NULL,
  `estado` varchar(50) DEFAULT NULL,
  `motivo` text,
  `grua` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `vehiculos`
--

INSERT INTO `vehiculos` (`id`, `fechaentrada`, `fechasalida`, `lugar`, `direccion`, `agente`, `matricula`, `marca`, `modelo`, `color`, `tipovehiculo`, `estado`, `motivo`, `grua`) VALUES
(1, '2023-10-01 10:00:00', NULL, 'Zona Centro', 'Calle Mayor, 123', 'Agente01', '1234ABC', 'Toyota', 'Corolla', 'Azul', 'Turismo_hasta_12cv', 'En depósito', 'Estacionamiento indebido', 'Grua01'),
(2, '2023-10-02 15:30:00', NULL, 'Zona Norte', 'Avenida Libertad, 45', 'Agente02', '5678XYZ', 'Honda', 'Civic', 'Blanco', 'Turismo_mas_12cv', 'En depósito', 'Vehículo abandonado', 'Grua02');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indices de la tabla `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indices de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indices de la tabla `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indices de la tabla `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indices de la tabla `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indices de la tabla `retiradas`
--
ALTER TABLE `retiradas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vehiculo_id` (`vehiculo_id`);

--
-- Indices de la tabla `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indices de la tabla `tarifas`
--
ALTER TABLE `tarifas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- Indices de la tabla `vehiculos`
--
ALTER TABLE `vehiculos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT de la tabla `retiradas`
--
ALTER TABLE `retiradas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tarifas`
--
ALTER TABLE `tarifas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `vehiculos`
--
ALTER TABLE `vehiculos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `logs_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
