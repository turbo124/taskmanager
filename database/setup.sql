-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 04, 2020 at 07:39 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `task_manager`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

DROP TABLE IF EXISTS `accounts`;
CREATE TABLE `accounts` (
  `id` int(10) UNSIGNED NOT NULL,
  `ip` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `subdomain` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `first_day_of_week` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `first_month_of_year` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `portal_domain` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `enable_modules` smallint(6) NOT NULL DEFAULT 0,
  `custom_fields` text COLLATE utf8_unicode_ci NOT NULL,
  `settings` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `domain_id` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `slack_webhook_url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `ip`, `subdomain`, `first_day_of_week`, `first_month_of_year`, `portal_domain`, `enable_modules`, `custom_fields`, `settings`, `created_at`, `updated_at`, `deleted_at`, `domain_id`, `slack_webhook_url`) VALUES
(1, NULL, '', NULL, NULL, NULL, 0, '', '{\"name\":\"test mike\",\"website\":\"testmike.com\",\"phone\":\"01425 629322\",\"email\":\"michaelhamptondesign@yahoo.com\",\"vat_number\":\"bvat number\",\"currency_id\":\"2\",\"email_style\":\"plain\",\"inclusive_taxes\":true,\"address1\":\"billing 1n\",\"address2\":\"billing 2\",\"city\":\"billing 4\",\"postal_code\":\"billing 3\",\"state\":\"Port Robbie\",\"country_id\":\"225\",\"invoice_terms\":\"invoice terms\",\"invoice_footer\":\"invoice footer\",\"quote_terms\":\"quote terms\",\"quote_footer\":\"quote footer\",\"credit_terms\":\"credit terms\",\"credit_footer\":\"credit footrt\",\"order_terms\":\"order terms\",\"order_footer\":\"order footer\",\"company_logo\":\"storage\\/logos\\/1588537209.jpeg\",\"slack_enabled\":true,\"late_fee_endless_percent\":0,\"late_fee_endless_amount\":0,\"should_email_invoice\":true,\"should_email_quote\":true,\"should_email_order\":true,\"reminder_send_time\":32400,\"email_sending_method\":\"default\",\"counter_number_applied\":\"when_saved\",\"quote_number_applied\":\"when_saved\",\"email_subject_custom1\":\"\",\"email_subject_custom2\":\"\",\"email_subject_custom3\":\"\",\"email_template_custom1\":\"\",\"email_template_custom2\":\"\",\"email_template_custom3\":\"\",\"enable_reminder1\":false,\"enable_reminder2\":false,\"enable_reminder3\":false,\"num_days_reminder1\":0,\"num_days_reminder2\":0,\"num_days_reminder3\":0,\"schedule_reminder1\":\"\",\"schedule_reminder2\":\"\",\"schedule_reminder3\":\"\",\"late_fee_amount1\":0,\"late_fee_amount2\":0,\"late_fee_amount3\":0,\"endless_reminder_frequency_id\":0,\"document_email_attachment\":false,\"enable_email_markup\":true,\"email_template_statement\":\"\",\"email_subject_statement\":\"\",\"show_signature_on_pdf\":false,\"page_size\":\"A4\",\"font_size\":12,\"primary_font\":\"Roboto\",\"secondary_font\":\"Roboto\",\"embed_documents\":false,\"all_pages_header\":false,\"all_pages_footer\":false,\"task_number_pattern\":\"\",\"task_number_counter\":1,\"expense_number_pattern\":\"\",\"expense_number_counter\":1,\"company_number_pattern\":\"\",\"company_number_counter\":1,\"case_number_pattern\":\"\",\"case_number_counter\":1,\"payment_number_pattern\":\"\",\"payment_number_counter\":1,\"reply_to_email\":\"\",\"bcc_email\":\"\",\"pdf_email_attachment\":false,\"ubl_email_attachment\":false,\"email_style_custom\":\"light\",\"customer_number_pattern\":\"\",\"customer_number_counter\":1,\"credit_number_pattern\":\"\",\"credit_number_counter\":2,\"order_number_pattern\":\"\",\"order_number_counter\":1,\"recurringinvoice_number_pattern\":\"\",\"recurringinvoice_number_counter\":1,\"recurringquote_number_pattern\":\"\",\"recurringquote_number_counter\":1,\"custom_value1\":\"\",\"custom_value2\":\"\",\"custom_value3\":\"\",\"custom_value4\":\"\",\"default_task_rate\":0,\"email_signature\":\"\",\"email_subject_invoice\":\"\",\"email_subject_quote\":\"\",\"email_subject_credit\":\"\",\"email_subject_payment\":\"\",\"email_subject_lead\":\"\",\"email_subject_order\":\"\",\"email_subject_payment_partial\":\"\",\"email_template_invoice\":\"\",\"email_template_quote\":\"\",\"email_template_credit\":\"\",\"email_template_payment\":\"\",\"email_template_lead\":\"\",\"email_template_order\":\"\",\"email_template_payment_partial\":\"\",\"email_subject_reminder1\":\"\",\"email_subject_reminder2\":\"\",\"email_subject_reminder3\":\"\",\"email_subject_reminder_endless\":\"\",\"email_template_reminder1\":\"\",\"email_template_reminder2\":\"\",\"email_template_reminder3\":\"\",\"email_template_reminder_endless\":\"\",\"invoice_number_pattern\":\"\",\"invoice_number_counter\":2,\"invoice_design_id\":\"1\",\"invoice_labels\":\"\",\"payment_terms\":-1,\"payment_type_id\":\"0\",\"quote_design_id\":\"1\",\"credit_design_id\":\"1\",\"order_design_id\":\"1\",\"type_id\":1,\"quote_number_pattern\":\"\",\"quote_number_counter\":2,\"recurring_number_prefix\":\"R\",\"id_number\":\"\",\"tax_name1\":\"\",\"tax_name2\":\"\",\"tax_name3\":\"\",\"tax_rate1\":0,\"tax_rate2\":0,\"tax_rate3\":0,\"timezone_id\":\"\",\"date_format_id\":\"\",\"language_id\":\"\",\"show_currency_code\":false,\"send_reminders\":false,\"should_archive_invoice\":false,\"should_archive_quote\":true,\"should_convert_quote\":true,\"should_convert_order\":true,\"should_archive_order\":true,\"should_archive_lead\":true,\"should_update_inventory\":true,\"shared_invoice_quote_counter\":false,\"counter_padding\":4,\"design\":\"views\\/pdf\\/design1.blade.php\",\"pdf_variables\":{\"customer_details\":[\"$customer.name\",\"$customer.id_number\",\"$customer.vat_number\",\"$customer.address1\",\"$customer.address2\",\"$customer.city_state_postal\",\"$customer.country\",\"$contact.email\"],\"account_details\":[\"$account.name\",\"$account.id_number\",\"$account.vat_number\",\"$account.website\",\"$account.email\",\"$account.phone\"],\"account_address\":[\"$account.address1\",\"$account.address2\",\"$account.city_state_postal\",\"$account.country\"],\"invoice\":[\"$invoice.invoice_number\",\"$invoice.po_number\",\"$invoice.invoice_date\",\"$invoice.due_date\",\"$invoice.balance_due\",\"$invoice.invoice_total\"],\"order\":[\"$order.order_number\",\"$order.po_number\",\"$order.order_date\",\"$order.due_date\",\"$order.balance_due\",\"$order.order_total\"],\"quote\":[\"$quote.quote_number\",\"$quote.po_number\",\"$quote.quote_date\",\"$quote.valid_until\",\"$quote.balance_due\",\"$quote.quote_total\"],\"credit\":[\"$credit.credit_number\",\"$credit.po_number\",\"$credit.credit_date\",\"$credit.credit_balance\",\"$credit.credit_amount\"],\"product_columns\":[\"$product.product_key\",\"$product.notes\",\"$product.cost\",\"$product.quantity\",\"$product.discount\",\"$product.tax\",\"$product.line_total\"],\"task_columns\":[\"$task.product_key\",\"$task.notes\",\"$task.cost\",\"$task.quantity\",\"$task.discount\",\"$task.tax\",\"$task.line_total\"]}}', '2020-05-03 17:24:22', '2020-05-03 19:38:55', NULL, 5, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `account_user`
--

DROP TABLE IF EXISTS `account_user`;
CREATE TABLE `account_user` (
  `id` int(10) UNSIGNED NOT NULL,
  `account_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `permissions` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `settings` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_owner` tinyint(1) NOT NULL DEFAULT 0,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `is_locked` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `slack_webhook_url` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `notifications` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `default_notification_type` enum('mail','slack','','') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'mail'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `account_user`
--

INSERT INTO `account_user` (`id`, `account_id`, `user_id`, `permissions`, `settings`, `is_owner`, `is_admin`, `is_locked`, `created_at`, `updated_at`, `deleted_at`, `slack_webhook_url`, `notifications`, `default_notification_type`) VALUES
(3, 1, 5, NULL, NULL, 1, 1, 0, NULL, NULL, NULL, NULL, '{\"email\":[]}', 'mail');

-- --------------------------------------------------------

--
-- Table structure for table `addresses`
--

DROP TABLE IF EXISTS `addresses`;
CREATE TABLE `addresses` (
  `id` int(10) UNSIGNED NOT NULL,
  `alias` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address_1` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address_2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `zip` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `province_id` int(11) DEFAULT NULL,
  `country_id` int(10) UNSIGNED NOT NULL DEFAULT 225,
  `customer_id` int(10) UNSIGNED NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `address_type` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `addresses`
--

INSERT INTO `addresses` (`id`, `alias`, `address_1`, `address_2`, `zip`, `state_code`, `city`, `province_id`, `country_id`, `customer_id`, `status`, `created_at`, `updated_at`, `deleted_at`, `address_type`) VALUES
(1, '', 'billing field 1a', 'billing field 2a', 'billing field 4a', NULL, 'billing field 3a', NULL, 225, 5, 1, '2020-05-03 18:15:10', '2020-05-03 18:15:10', NULL, 1),
(2, '', 'billing1a', 'billing2a', 'billing3a', NULL, 'billing4a', NULL, 225, 5, 1, '2020-05-03 18:15:10', '2020-05-03 18:15:10', NULL, 2);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `cover` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `_lft` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `_rgt` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `parent_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `cover`, `status`, `created_at`, `updated_at`, `_lft`, `_rgt`, `parent_id`) VALUES
(1, 'test mike', '', 'test mike', '', 1, '2020-05-03 19:05:23', '2020-05-03 19:05:23', 1, 2, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `category_product`
--

DROP TABLE IF EXISTS `category_product`;
CREATE TABLE `category_product` (
  `id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `category_product`
--

INSERT INTO `category_product` (`id`, `category_id`, `product_id`) VALUES
(1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

DROP TABLE IF EXISTS `cities`;
CREATE TABLE `cities` (
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `state_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `province_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `client_contacts`
--

DROP TABLE IF EXISTS `client_contacts`;
CREATE TABLE `client_contacts` (
  `id` int(10) UNSIGNED NOT NULL,
  `account_id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `first_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_value1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_value2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_value3` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_value4` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email_verified_at` datetime DEFAULT NULL,
  `confirmation_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT 0,
  `confirmed` tinyint(1) NOT NULL DEFAULT 0,
  `last_login` datetime DEFAULT NULL,
  `failed_logins` smallint(6) DEFAULT NULL,
  `accepted_terms_version` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `avatar_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `avatar_size` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_locked` tinyint(1) NOT NULL DEFAULT 0,
  `send_email` tinyint(1) NOT NULL DEFAULT 1,
  `contact_key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `client_contacts`
--

INSERT INTO `client_contacts` (`id`, `account_id`, `customer_id`, `user_id`, `first_name`, `last_name`, `phone`, `custom_value1`, `custom_value2`, `custom_value3`, `custom_value4`, `email`, `email_verified_at`, `confirmation_code`, `is_primary`, `confirmed`, `last_login`, `failed_logins`, `accepted_terms_version`, `avatar`, `avatar_type`, `avatar_size`, `password`, `is_locked`, `send_email`, `contact_key`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 5, 5, 'Michael', 'Hampton', '01425 629322', NULL, NULL, NULL, NULL, 'michaelhamptondesign@yahoo.coim', NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$pXRdik18FDDfwbR6VqR6wujUt3lVed5gHSWsn42WdXhseiCIH1ZPm', 0, 1, 'L58IXZbm5VSf5utADM8emZgNyvtOF7KnXNlkOiSz', NULL, '2020-05-03 18:15:10', '2020-05-03 18:15:10', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `client_gateway_tokens`
--

DROP TABLE IF EXISTS `client_gateway_tokens`;
CREATE TABLE `client_gateway_tokens` (
  `id` int(10) UNSIGNED NOT NULL,
  `account_id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(10) UNSIGNED DEFAULT NULL,
  `token` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `company_gateway_id` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `gateway_customer_reference` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gateway_type_id` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `meta` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
CREATE TABLE `comments` (
  `id` int(10) UNSIGNED NOT NULL,
  `comment` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_active` int(11) NOT NULL DEFAULT 1,
  `parent_id` int(11) DEFAULT NULL,
  `parent_type` int(11) NOT NULL DEFAULT 1,
  `account_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `comment_task`
--

DROP TABLE IF EXISTS `comment_task`;
CREATE TABLE `comment_task` (
  `id` int(10) UNSIGNED NOT NULL,
  `task_id` int(10) UNSIGNED NOT NULL,
  `comment_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `comment_type`
--

DROP TABLE IF EXISTS `comment_type`;
CREATE TABLE `comment_type` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

DROP TABLE IF EXISTS `companies`;
CREATE TABLE `companies` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `website` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `phone_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address_1` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address_2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `town` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `postcode` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `country_id` int(10) UNSIGNED NOT NULL,
  `currency_id` int(10) UNSIGNED DEFAULT NULL,
  `industry_id` int(11) DEFAULT NULL,
  `settings` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `assigned_user_id` int(10) UNSIGNED DEFAULT NULL,
  `private_notes` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `account_id` int(10) UNSIGNED NOT NULL,
  `custom_value1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_value2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_value3` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_value4` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `vat_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `transaction_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `id_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `balance` decimal(16,4) DEFAULT NULL,
  `paid_to_date` decimal(16,4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`id`, `name`, `created_at`, `updated_at`, `website`, `phone_number`, `email`, `address_1`, `address_2`, `town`, `city`, `postcode`, `country_id`, `currency_id`, `industry_id`, `settings`, `assigned_user_id`, `private_notes`, `user_id`, `account_id`, `custom_value1`, `custom_value2`, `custom_value3`, `custom_value4`, `deleted_at`, `is_deleted`, `vat_number`, `transaction_name`, `id_number`, `balance`, `paid_to_date`) VALUES
(1, 'test mike', '2020-05-03 17:54:57', '2020-05-03 17:54:57', 'testmike.com', '01425 629322', 'michaelhamptondesign@yahoo.com', 'billing 1n', 'billing 2', 'billing 4', 'billing 4', 'billing 3', 225, 2, 3, NULL, 5, NULL, 5, 1, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `company_contacts`
--

DROP TABLE IF EXISTS `company_contacts`;
CREATE TABLE `company_contacts` (
  `id` int(10) UNSIGNED NOT NULL,
  `account_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT 0,
  `first_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact_key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_value1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_value2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_value3` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_value4` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `customer_id` int(10) UNSIGNED NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `company_contacts`
--

INSERT INTO `company_contacts` (`id`, `account_id`, `user_id`, `company_id`, `created_at`, `updated_at`, `deleted_at`, `is_primary`, `first_name`, `last_name`, `email`, `phone`, `contact_key`, `custom_value1`, `custom_value2`, `custom_value3`, `custom_value4`, `customer_id`, `password`) VALUES
(1, 1, 5, 1, '2020-05-03 17:54:58', '2020-05-03 17:54:58', NULL, 0, 'Lexie', 'Hampton', 'lexie.hampton@yahoo.com', '01425629322', NULL, NULL, NULL, NULL, NULL, 0, '$2y$10$8zgxwnAf3Ao1N06bumCli.rhMgE50VooaKyWJGEZtVLV6Daaz2czK');

-- --------------------------------------------------------

--
-- Table structure for table `company_gateways`
--

DROP TABLE IF EXISTS `company_gateways`;
CREATE TABLE `company_gateways` (
  `id` int(10) UNSIGNED NOT NULL,
  `account_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `gateway_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `accepted_credit_cards` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `require_cvv` tinyint(1) NOT NULL DEFAULT 1,
  `show_billing_address` tinyint(1) DEFAULT 1,
  `show_shipping_address` tinyint(1) DEFAULT 1,
  `update_details` tinyint(1) DEFAULT 0,
  `config` text COLLATE utf8_unicode_ci NOT NULL,
  `fees_and_limits` text COLLATE utf8_unicode_ci NOT NULL,
  `custom_value1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_value2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_value3` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_value4` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `company_ledgers`
--

DROP TABLE IF EXISTS `company_ledgers`;
CREATE TABLE `company_ledgers` (
  `id` int(10) UNSIGNED NOT NULL,
  `account_id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(10) UNSIGNED DEFAULT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `adjustment` decimal(16,4) DEFAULT NULL,
  `balance` decimal(16,4) DEFAULT NULL,
  `notes` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `hash` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `company_ledgerable_id` int(10) UNSIGNED NOT NULL,
  `company_ledgerable_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `company_tokens`
--

DROP TABLE IF EXISTS `company_tokens`;
CREATE TABLE `company_tokens` (
  `id` int(10) UNSIGNED NOT NULL,
  `account_id` int(10) UNSIGNED NOT NULL,
  `domain_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_web` smallint(6) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `company_tokens`
--

INSERT INTO `company_tokens` (`id`, `account_id`, `domain_id`, `user_id`, `token`, `name`, `is_web`, `created_at`, `updated_at`, `deleted_at`, `is_deleted`) VALUES
(2, 1, 5, 5, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC90YXNrbWFuMi5kZXZlbG9wXC9hcGlcL2xvZ2luIiwiaWF0IjoxNTg4NjAzOTMwLCJleHAiOjE1ODg2MTQ3MzAsIm5iZiI6MTU4ODYwMzkzMCwianRpIjoiaGdZblRQUlVjUklucUk4dyIsInN1YiI6NSwicHJ2IjoiODdlMGFmMWVmOWZkMTU4MTJmZGVjOTcxNT', NULL, 1, '2020-05-03 17:30:53', '2020-05-04 13:52:10', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `company_user`
--

DROP TABLE IF EXISTS `company_user`;
CREATE TABLE `company_user` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_admin` int(11) NOT NULL,
  `is_owner` int(11) NOT NULL,
  `is_locked` int(11) NOT NULL,
  `permissions` text COLLATE utf8_unicode_ci NOT NULL,
  `settings` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

DROP TABLE IF EXISTS `countries`;
CREATE TABLE `countries` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `iso` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `iso3` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `numcode` int(11) DEFAULT NULL,
  `phonecode` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `swap_postal_code` tinyint(1) NOT NULL DEFAULT 0,
  `swap_currency_symbol` tinyint(1) NOT NULL DEFAULT 0,
  `thousand_separator` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `decimal_separator` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `name`, `iso`, `iso3`, `numcode`, `phonecode`, `status`, `created_at`, `updated_at`, `swap_postal_code`, `swap_currency_symbol`, `thousand_separator`, `decimal_separator`) VALUES
(1, 'AFGHANISTAN', 'AF', 'AFG', 4, 93, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(2, 'ALBANIA', 'AL', 'ALB', 8, 355, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(3, 'ALGERIA', 'DZ', 'DZA', 12, 213, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(4, 'AMERICAN SAMOA', 'AS', 'ASM', 16, 1684, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(5, 'ANDORRA', 'AD', 'AND', 20, 376, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(6, 'ANGOLA', 'AO', 'AGO', 24, 244, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(7, 'ANGUILLA', 'AI', 'AIA', 660, 1264, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(8, 'ANTARCTICA', 'AQ', NULL, NULL, 0, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(9, 'ANTIGUA AND BARBUDA', 'AG', 'ATG', 28, 1268, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(10, 'ARGENTINA', 'AR', 'ARG', 32, 54, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(11, 'ARMENIA', 'AM', 'ARM', 51, 374, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(12, 'ARUBA', 'AW', 'ABW', 533, 297, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(13, 'AUSTRALIA', 'AU', 'AUS', 36, 61, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(14, 'AUSTRIA', 'AT', 'AUT', 40, 43, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(15, 'AZERBAIJAN', 'AZ', 'AZE', 31, 994, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(16, 'BAHAMAS', 'BS', 'BHS', 44, 1242, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(17, 'BAHRAIN', 'BH', 'BHR', 48, 973, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(18, 'BANGLADESH', 'BD', 'BGD', 50, 880, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(19, 'BARBADOS', 'BB', 'BRB', 52, 1246, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(20, 'BELARUS', 'BY', 'BLR', 112, 375, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(21, 'BELGIUM', 'BE', 'BEL', 56, 32, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(22, 'BELIZE', 'BZ', 'BLZ', 84, 501, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(23, 'BENIN', 'BJ', 'BEN', 204, 229, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(24, 'BERMUDA', 'BM', 'BMU', 60, 1441, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(25, 'BHUTAN', 'BT', 'BTN', 64, 975, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(26, 'BOLIVIA', 'BO', 'BOL', 68, 591, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(27, 'BOSNIA AND HERZEGOVINA', 'BA', 'BIH', 70, 387, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(28, 'BOTSWANA', 'BW', 'BWA', 72, 267, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(29, 'BOUVET ISLAND', 'BV', NULL, NULL, 0, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(30, 'BRAZIL', 'BR', 'BRA', 76, 55, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(31, 'BRITISH INDIAN OCEAN TERRITORY', 'IO', NULL, NULL, 246, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(32, 'BRUNEI DARUSSALAM', 'BN', 'BRN', 96, 673, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(33, 'BULGARIA', 'BG', 'BGR', 100, 359, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(34, 'BURKINA FASO', 'BF', 'BFA', 854, 226, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(35, 'BURUNDI', 'BI', 'BDI', 108, 257, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(36, 'CAMBODIA', 'KH', 'KHM', 116, 855, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(37, 'CAMEROON', 'CM', 'CMR', 120, 237, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(38, 'CANADA', 'CA', 'CAN', 124, 1, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(39, 'CAPE VERDE', 'CV', 'CPV', 132, 238, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(40, 'CAYMAN ISLANDS', 'KY', 'CYM', 136, 1345, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(41, 'CENTRAL AFRICAN REPUBLIC', 'CF', 'CAF', 140, 236, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(42, 'CHAD', 'TD', 'TCD', 148, 235, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(43, 'CHILE', 'CL', 'CHL', 152, 56, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(44, 'CHINA', 'CN', 'CHN', 156, 86, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(45, 'CHRISTMAS ISLAND', 'CX', NULL, NULL, 61, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(46, 'COCOS (KEELING) ISLANDS', 'CC', NULL, NULL, 672, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(47, 'COLOMBIA', 'CO', 'COL', 170, 57, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(48, 'COMOROS', 'KM', 'COM', 174, 269, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(49, 'CONGO', 'CG', 'COG', 178, 242, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(50, 'CONGO, THE DEMOCRATIC REPUBLIC OF THE', 'CD', 'COD', 180, 242, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(51, 'COOK ISLANDS', 'CK', 'COK', 184, 682, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(52, 'COSTA RICA', 'CR', 'CRI', 188, 506, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(53, 'COTE D\'IVOIRE', 'CI', 'CIV', 384, 225, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(54, 'CROATIA', 'HR', 'HRV', 191, 385, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(55, 'CUBA', 'CU', 'CUB', 192, 53, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(56, 'CYPRUS', 'CY', 'CYP', 196, 357, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(57, 'CZECH REPUBLIC', 'CZ', 'CZE', 203, 420, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(58, 'DENMARK', 'DK', 'DNK', 208, 45, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(59, 'DJIBOUTI', 'DJ', 'DJI', 262, 253, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(60, 'DOMINICA', 'DM', 'DMA', 212, 1767, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(61, 'DOMINICAN REPUBLIC', 'DO', 'DOM', 214, 1809, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(62, 'ECUADOR', 'EC', 'ECU', 218, 593, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(63, 'EGYPT', 'EG', 'EGY', 818, 20, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(64, 'EL SALVADOR', 'SV', 'SLV', 222, 503, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(65, 'EQUATORIAL GUINEA', 'GQ', 'GNQ', 226, 240, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(66, 'ERITREA', 'ER', 'ERI', 232, 291, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(67, 'ESTONIA', 'EE', 'EST', 233, 372, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(68, 'ETHIOPIA', 'ET', 'ETH', 231, 251, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(69, 'FALKLAND ISLANDS (MALVINAS)', 'FK', 'FLK', 238, 500, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(70, 'FAROE ISLANDS', 'FO', 'FRO', 234, 298, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(71, 'FIJI', 'FJ', 'FJI', 242, 679, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(72, 'FINLAND', 'FI', 'FIN', 246, 358, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(73, 'FRANCE', 'FR', 'FRA', 250, 33, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(74, 'FRENCH GUIANA', 'GF', 'GUF', 254, 594, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(75, 'FRENCH POLYNESIA', 'PF', 'PYF', 258, 689, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(76, 'FRENCH SOUTHERN TERRITORIES', 'TF', NULL, NULL, 0, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(77, 'GABON', 'GA', 'GAB', 266, 241, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(78, 'GAMBIA', 'GM', 'GMB', 270, 220, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(79, 'GEORGIA', 'GE', 'GEO', 268, 995, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(80, 'GERMANY', 'DE', 'DEU', 276, 49, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(81, 'GHANA', 'GH', 'GHA', 288, 233, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(82, 'GIBRALTAR', 'GI', 'GIB', 292, 350, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(83, 'GREECE', 'GR', 'GRC', 300, 30, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(84, 'GREENLAND', 'GL', 'GRL', 304, 299, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(85, 'GRENADA', 'GD', 'GRD', 308, 1473, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(86, 'GUADELOUPE', 'GP', 'GLP', 312, 590, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(87, 'GUAM', 'GU', 'GUM', 316, 1671, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(88, 'GUATEMALA', 'GT', 'GTM', 320, 502, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(89, 'GUINEA', 'GN', 'GIN', 324, 224, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(90, 'GUINEA-BISSAU', 'GW', 'GNB', 624, 245, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(91, 'GUYANA', 'GY', 'GUY', 328, 592, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(92, 'HAITI', 'HT', 'HTI', 332, 509, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(93, 'HEARD ISLAND AND MCDONALD ISLANDS', 'HM', NULL, NULL, 0, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(94, 'HOLY SEE (VATICAN CITY STATE)', 'VA', 'VAT', 336, 39, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(95, 'HONDURAS', 'HN', 'HND', 340, 504, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(96, 'HONG KONG', 'HK', 'HKG', 344, 852, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(97, 'HUNGARY', 'HU', 'HUN', 348, 36, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(98, 'ICELAND', 'IS', 'ISL', 352, 354, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(99, 'INDIA', 'IN', 'IND', 356, 91, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(100, 'INDONESIA', 'ID', 'IDN', 360, 62, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(101, 'IRAN, ISLAMIC REPUBLIC OF', 'IR', 'IRN', 364, 98, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(102, 'IRAQ', 'IQ', 'IRQ', 368, 964, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(103, 'IRELAND', 'IE', 'IRL', 372, 353, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(104, 'ISRAEL', 'IL', 'ISR', 376, 972, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(105, 'ITALY', 'IT', 'ITA', 380, 39, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(106, 'JAMAICA', 'JM', 'JAM', 388, 1876, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(107, 'JAPAN', 'JP', 'JPN', 392, 81, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(108, 'JORDAN', 'JO', 'JOR', 400, 962, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(109, 'KAZAKHSTAN', 'KZ', 'KAZ', 398, 7, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(110, 'KENYA', 'KE', 'KEN', 404, 254, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(111, 'KIRIBATI', 'KI', 'KIR', 296, 686, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(112, 'KOREA, DEMOCRATIC PEOPLE\'S REPUBLIC OF', 'KP', 'PRK', 408, 850, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(113, 'KOREA, REPUBLIC OF', 'KR', 'KOR', 410, 82, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(114, 'KUWAIT', 'KW', 'KWT', 414, 965, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(115, 'KYRGYZSTAN', 'KG', 'KGZ', 417, 996, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(116, 'LAO PEOPLE\'S DEMOCRATIC REPUBLIC', 'LA', 'LAO', 418, 856, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(117, 'LATVIA', 'LV', 'LVA', 428, 371, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(118, 'LEBANON', 'LB', 'LBN', 422, 961, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(119, 'LESOTHO', 'LS', 'LSO', 426, 266, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(120, 'LIBERIA', 'LR', 'LBR', 430, 231, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(121, 'LIBYAN ARAB JAMAHIRIYA', 'LY', 'LBY', 434, 218, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(122, 'LIECHTENSTEIN', 'LI', 'LIE', 438, 423, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(123, 'LITHUANIA', 'LT', 'LTU', 440, 370, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(124, 'LUXEMBOURG', 'LU', 'LUX', 442, 352, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(125, 'MACAO', 'MO', 'MAC', 446, 853, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(126, 'MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF', 'MK', 'MKD', 807, 389, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(127, 'MADAGASCAR', 'MG', 'MDG', 450, 261, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(128, 'MALAWI', 'MW', 'MWI', 454, 265, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(129, 'MALAYSIA', 'MY', 'MYS', 458, 60, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(130, 'MALDIVES', 'MV', 'MDV', 462, 960, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(131, 'MALI', 'ML', 'MLI', 466, 223, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(132, 'MALTA', 'MT', 'MLT', 470, 356, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(133, 'MARSHALL ISLANDS', 'MH', 'MHL', 584, 692, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(134, 'MARTINIQUE', 'MQ', 'MTQ', 474, 596, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(135, 'MAURITANIA', 'MR', 'MRT', 478, 222, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(136, 'MAURITIUS', 'MU', 'MUS', 480, 230, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(137, 'MAYOTTE', 'YT', NULL, NULL, 269, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(138, 'MEXICO', 'MX', 'MEX', 484, 52, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(139, 'MICRONESIA, FEDERATED STATES OF', 'FM', 'FSM', 583, 691, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(140, 'MOLDOVA, REPUBLIC OF', 'MD', 'MDA', 498, 373, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(141, 'MONACO', 'MC', 'MCO', 492, 377, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(142, 'MONGOLIA', 'MN', 'MNG', 496, 976, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(143, 'MONTSERRAT', 'MS', 'MSR', 500, 1664, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(144, 'MOROCCO', 'MA', 'MAR', 504, 212, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(145, 'MOZAMBIQUE', 'MZ', 'MOZ', 508, 258, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(146, 'MYANMAR', 'MM', 'MMR', 104, 95, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(147, 'NAMIBIA', 'NA', 'NAM', 516, 264, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(148, 'NAURU', 'NR', 'NRU', 520, 674, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(149, 'NEPAL', 'NP', 'NPL', 524, 977, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(150, 'NETHERLANDS', 'NL', 'NLD', 528, 31, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(151, 'NETHERLANDS ANTILLES', 'AN', 'ANT', 530, 599, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(152, 'NEW CALEDONIA', 'NC', 'NCL', 540, 687, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(153, 'NEW ZEALAND', 'NZ', 'NZL', 554, 64, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(154, 'NICARAGUA', 'NI', 'NIC', 558, 505, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(155, 'NIGER', 'NE', 'NER', 562, 227, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(156, 'NIGERIA', 'NG', 'NGA', 566, 234, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(157, 'NIUE', 'NU', 'NIU', 570, 683, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(158, 'NORFOLK ISLAND', 'NF', 'NFK', 574, 672, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(159, 'NORTHERN MARIANA ISLANDS', 'MP', 'MNP', 580, 1670, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(160, 'NORWAY', 'NO', 'NOR', 578, 47, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(161, 'OMAN', 'OM', 'OMN', 512, 968, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(162, 'PAKISTAN', 'PK', 'PAK', 586, 92, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(163, 'PALAU', 'PW', 'PLW', 585, 680, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(164, 'PALESTINIAN TERRITORY, OCCUPIED', 'PS', NULL, NULL, 970, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(165, 'PANAMA', 'PA', 'PAN', 591, 507, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(166, 'PAPUA NEW GUINEA', 'PG', 'PNG', 598, 675, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(167, 'PARAGUAY', 'PY', 'PRY', 600, 595, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(168, 'PERU', 'PE', 'PER', 604, 51, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(169, 'PHILIPPINES', 'PH', 'PHL', 608, 63, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(170, 'PITCAIRN', 'PN', 'PCN', 612, 0, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(171, 'POLAND', 'PL', 'POL', 616, 48, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(172, 'PORTUGAL', 'PT', 'PRT', 620, 351, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(173, 'PUERTO RICO', 'PR', 'PRI', 630, 1787, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(174, 'QATAR', 'QA', 'QAT', 634, 974, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(175, 'REUNION', 'RE', 'REU', 638, 262, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(176, 'ROMANIA', 'RO', 'ROM', 642, 40, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(177, 'RUSSIAN FEDERATION', 'RU', 'RUS', 643, 70, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(178, 'RWANDA', 'RW', 'RWA', 646, 250, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(179, 'SAINT HELENA', 'SH', 'SHN', 654, 290, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(180, 'SAINT KITTS AND NEVIS', 'KN', 'KNA', 659, 1869, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(181, 'SAINT LUCIA', 'LC', 'LCA', 662, 1758, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(182, 'SAINT PIERRE AND MIQUELON', 'PM', 'SPM', 666, 508, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(183, 'SAINT VINCENT AND THE GRENADINES', 'VC', 'VCT', 670, 1784, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(184, 'SAMOA', 'WS', 'WSM', 882, 684, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(185, 'SAN MARINO', 'SM', 'SMR', 674, 378, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(186, 'SAO TOME AND PRINCIPE', 'ST', 'STP', 678, 239, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(187, 'SAUDI ARABIA', 'SA', 'SAU', 682, 966, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(188, 'SENEGAL', 'SN', 'SEN', 686, 221, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(189, 'SERBIA AND MONTENEGRO', 'CS', NULL, NULL, 381, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(190, 'SEYCHELLES', 'SC', 'SYC', 690, 248, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(191, 'SIERRA LEONE', 'SL', 'SLE', 694, 232, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(192, 'SINGAPORE', 'SG', 'SGP', 702, 65, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(193, 'SLOVAKIA', 'SK', 'SVK', 703, 421, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(194, 'SLOVENIA', 'SI', 'SVN', 705, 386, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(195, 'SOLOMON ISLANDS', 'SB', 'SLB', 90, 677, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(196, 'SOMALIA', 'SO', 'SOM', 706, 252, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(197, 'SOUTH AFRICA', 'ZA', 'ZAF', 710, 27, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(198, 'SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS', 'GS', NULL, NULL, 0, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(199, 'SPAIN', 'ES', 'ESP', 724, 34, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(200, 'SRI LANKA', 'LK', 'LKA', 144, 94, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(201, 'SUDAN', 'SD', 'SDN', 736, 249, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(202, 'SURINAME', 'SR', 'SUR', 740, 597, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(203, 'SVALBARD AND JAN MAYEN', 'SJ', 'SJM', 744, 47, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(204, 'SWAZILAND', 'SZ', 'SWZ', 748, 268, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(205, 'SWEDEN', 'SE', 'SWE', 752, 46, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(206, 'SWITZERLAND', 'CH', 'CHE', 756, 41, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(207, 'SYRIAN ARAB REPUBLIC', 'SY', 'SYR', 760, 963, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(208, 'TAIWAN, PROVINCE OF CHINA', 'TW', 'TWN', 158, 886, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(209, 'TAJIKISTAN', 'TJ', 'TJK', 762, 992, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(210, 'TANZANIA, UNITED REPUBLIC OF', 'TZ', 'TZA', 834, 255, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(211, 'THAILAND', 'TH', 'THA', 764, 66, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(212, 'TIMOR-LESTE', 'TL', NULL, NULL, 670, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(213, 'TOGO', 'TG', 'TGO', 768, 228, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(214, 'TOKELAU', 'TK', 'TKL', 772, 690, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(215, 'TONGA', 'TO', 'TON', 776, 676, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(216, 'TRINIDAD AND TOBAGO', 'TT', 'TTO', 780, 1868, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(217, 'TUNISIA', 'TN', 'TUN', 788, 216, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(218, 'TURKEY', 'TR', 'TUR', 792, 90, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(219, 'TURKMENISTAN', 'TM', 'TKM', 795, 7370, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(220, 'TURKS AND CAICOS ISLANDS', 'TC', 'TCA', 796, 1649, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(221, 'TUVALU', 'TV', 'TUV', 798, 688, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(222, 'UGANDA', 'UG', 'UGA', 800, 256, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(223, 'UKRAINE', 'UA', 'UKR', 804, 380, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(224, 'UNITED ARAB EMIRATES', 'AE', 'ARE', 784, 971, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(225, 'UNITED KINGDOM', 'GB', 'GBR', 826, 44, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(226, 'UNITED STATES OF AMERICA', 'US', 'USA', 840, 1, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(227, 'UNITED STATES MINOR OUTLYING ISLANDS', 'UM', NULL, NULL, 1, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(228, 'URUGUAY', 'UY', 'URY', 858, 598, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(229, 'UZBEKISTAN', 'UZ', 'UZB', 860, 998, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(230, 'VANUATU', 'VU', 'VUT', 548, 678, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(231, 'VENEZUELA', 'VE', 'VEN', 862, 58, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(232, 'VIET NAM', 'VN', 'VNM', 704, 84, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(233, 'VIRGIN ISLANDS, BRITISH', 'VG', 'VGB', 92, 1284, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(234, 'VIRGIN ISLANDS, U.S.', 'VI', 'VIR', 850, 1340, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(235, 'WALLIS AND FUTUNA', 'WF', 'WLF', 876, 681, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(236, 'WESTERN SAHARA', 'EH', 'ESH', 732, 212, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(237, 'YEMEN', 'YE', 'YEM', 887, 967, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(238, 'ZAMBIA', 'ZM', 'ZMB', 894, 260, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL),
(239, 'ZIMBABWE', 'ZW', 'ZWE', 716, 263, 1, '2020-05-03 17:42:13', '2020-05-03 17:42:13', 0, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `credits`
--

DROP TABLE IF EXISTS `credits`;
CREATE TABLE `credits` (
  `id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `assigned_user_id` int(10) UNSIGNED DEFAULT NULL,
  `account_id` int(10) UNSIGNED NOT NULL,
  `status_id` int(10) UNSIGNED NOT NULL,
  `number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date` date DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `total` decimal(16,4) NOT NULL,
  `balance` decimal(16,4) NOT NULL,
  `last_viewed` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `invoice_id` int(10) UNSIGNED DEFAULT NULL,
  `footer` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `public_notes` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `terms` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_value1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_value2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_value3` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_value4` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `line_items` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `partial_due_date` datetime DEFAULT NULL,
  `is_amount_discount` tinyint(1) NOT NULL DEFAULT 0,
  `po_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `discount_total` decimal(16,4) NOT NULL DEFAULT 0.0000,
  `tax_total` decimal(16,4) NOT NULL DEFAULT 0.0000,
  `private_notes` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `tax_rate_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tax_rate` decimal(13,3) NOT NULL DEFAULT 0.000,
  `design_id` int(10) UNSIGNED DEFAULT NULL,
  `custom_surcharge1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_surcharge2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_surcharge3` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_surcharge4` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_surcharge_tax1` tinyint(1) NOT NULL DEFAULT 0,
  `custom_surcharge_tax2` tinyint(1) NOT NULL DEFAULT 0,
  `custom_surcharge_tax3` tinyint(1) NOT NULL DEFAULT 0,
  `custom_surcharge_tax4` tinyint(1) NOT NULL DEFAULT 0,
  `sub_total` decimal(16,4) NOT NULL,
  `partial` decimal(16,4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `credits`
--

INSERT INTO `credits` (`id`, `customer_id`, `user_id`, `assigned_user_id`, `account_id`, `status_id`, `number`, `date`, `is_deleted`, `total`, `balance`, `last_viewed`, `created_at`, `updated_at`, `deleted_at`, `invoice_id`, `footer`, `public_notes`, `terms`, `custom_value1`, `custom_value2`, `custom_value3`, `custom_value4`, `line_items`, `due_date`, `partial_due_date`, `is_amount_discount`, `po_number`, `discount_total`, `tax_total`, `private_notes`, `tax_rate_name`, `tax_rate`, `design_id`, `custom_surcharge1`, `custom_surcharge2`, `custom_surcharge3`, `custom_surcharge4`, `custom_surcharge_tax1`, `custom_surcharge_tax2`, `custom_surcharge_tax3`, `custom_surcharge_tax4`, `sub_total`, `partial`) VALUES
(1, 5, 5, NULL, 1, 1, '0001', '2020-05-04', 0, '1994.3300', '1994.3300', NULL, '2020-05-03 19:38:55', '2020-05-03 19:38:55', NULL, NULL, 'credit footrt', 'public', 'credit terms', NULL, NULL, NULL, NULL, '[{\"custom_value1\":\"\",\"custom_value2\":\"\",\"custom_value3\":\"\",\"custom_value4\":\"\",\"tax_rate_name\":\"basic\",\"tax_rate_id\":1,\"type_id\":1,\"quantity\":1,\"notes\":\"\",\"unit_price\":1699,\"unit_discount\":2,\"unit_tax\":17.5,\"sub_total\":1994,\"line_total\":1699,\"discount_total\":2,\"tax_total\":297.33,\"is_amount_discount\":true,\"product_id\":\"1\",\"description\":\"\"}]', '2020-05-04', '2020-05-04 00:00:00', 1, NULL, '2.0000', '297.3300', NULL, NULL, '0.000', NULL, '0', '0', '0', '0', 0, 0, 0, 0, '1699.0000', '0.0000');

-- --------------------------------------------------------

--
-- Table structure for table `credit_invitations`
--

DROP TABLE IF EXISTS `credit_invitations`;
CREATE TABLE `credit_invitations` (
  `id` int(10) UNSIGNED NOT NULL,
  `account_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `client_contact_id` int(10) UNSIGNED NOT NULL,
  `credit_id` int(10) UNSIGNED NOT NULL,
  `key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `transaction_reference` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `message_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email_error` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `signature_base64` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `signature_date` datetime DEFAULT NULL,
  `sent_date` datetime DEFAULT NULL,
  `viewed_date` datetime DEFAULT NULL,
  `opened_date` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `credit_invitations`
--

INSERT INTO `credit_invitations` (`id`, `account_id`, `user_id`, `client_contact_id`, `credit_id`, `key`, `transaction_reference`, `message_id`, `email_error`, `signature_base64`, `signature_date`, `sent_date`, `viewed_date`, `opened_date`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 5, 1, 1, 'LV6AV27OdgOB05QVn2vSvwvGDssurupCmMphXr2D', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020-05-03 19:38:55', '2020-05-03 19:38:55', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

DROP TABLE IF EXISTS `currencies`;
CREATE TABLE `currencies` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `symbol` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `precision` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `thousand_separator` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `decimal_separator` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `swap_currency_symbol` int(11) NOT NULL,
  `exchange_rate` decimal(13,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`id`, `name`, `symbol`, `precision`, `thousand_separator`, `decimal_separator`, `code`, `swap_currency_symbol`, `exchange_rate`, `created_at`, `updated_at`) VALUES
(1, 'US Dollar', '$', '2', ',', '.', 'USD', 0, '0.00', '2020-05-03 17:42:08', '2020-05-03 17:42:08'),
(2, 'British Pound', '£', '2', ',', '.', 'GBP', 0, '0.00', '2020-05-03 17:42:08', '2020-05-03 17:42:08'),
(3, 'Euro', '€', '2', '.', ',', 'EUR', 0, '0.00', '2020-05-03 17:42:08', '2020-05-03 17:42:08'),
(4, 'South African Rand', 'R', '2', ',', '.', 'ZAR', 0, '0.00', '2020-05-03 17:42:08', '2020-05-03 17:42:08'),
(5, 'Danish Krone', 'kr', '2', '.', ',', 'DKK', 1, '0.00', '2020-05-03 17:42:08', '2020-05-03 17:42:08'),
(6, 'Israeli Shekel', 'NIS ', '2', ',', '.', 'ILS', 0, '0.00', '2020-05-03 17:42:08', '2020-05-03 17:42:08'),
(7, 'Swedish Krona', 'kr', '2', '.', ',', 'SEK', 1, '0.00', '2020-05-03 17:42:08', '2020-05-03 17:42:08'),
(8, 'Kenyan Shilling', 'KSh ', '2', ',', '.', 'KES', 0, '0.00', '2020-05-03 17:42:08', '2020-05-03 17:42:08'),
(9, 'Canadian Dollar', 'C$', '2', ',', '.', 'CAD', 0, '0.00', '2020-05-03 17:42:08', '2020-05-03 17:42:08'),
(10, 'Philippine Peso', 'P ', '2', ',', '.', 'PHP', 0, '0.00', '2020-05-03 17:42:08', '2020-05-03 17:42:08'),
(11, 'Indian Rupee', 'Rs. ', '2', ',', '.', 'INR', 0, '0.00', '2020-05-03 17:42:08', '2020-05-03 17:42:08'),
(12, 'Australian Dollar', '$', '2', ',', '.', 'AUD', 0, '0.00', '2020-05-03 17:42:08', '2020-05-03 17:42:08'),
(13, 'Singapore Dollar', '', '2', ',', '.', 'SGD', 0, '0.00', '2020-05-03 17:42:08', '2020-05-03 17:42:08'),
(14, 'Norske Kroner', 'kr', '2', '.', ',', 'NOK', 1, '0.00', '2020-05-03 17:42:08', '2020-05-03 17:42:08'),
(15, 'New Zealand Dollar', '$', '2', ',', '.', 'NZD', 0, '0.00', '2020-05-03 17:42:08', '2020-05-03 17:42:08'),
(16, 'Vietnamese Dong', '', '0', '.', ',', 'VND', 0, '0.00', '2020-05-03 17:42:08', '2020-05-03 17:42:08'),
(17, 'Swiss Franc', '', '2', '\'', '.', 'CHF', 0, '0.00', '2020-05-03 17:42:08', '2020-05-03 17:42:08'),
(18, 'Guatemalan Quetzal', 'Q', '2', ',', '.', 'GTQ', 0, '0.00', '2020-05-03 17:42:08', '2020-05-03 17:42:08'),
(19, 'Malaysian Ringgit', 'RM', '2', ',', '.', 'MYR', 0, '0.00', '2020-05-03 17:42:09', '2020-05-03 17:42:09'),
(20, 'Brazilian Real', 'R$', '2', '.', ',', 'BRL', 0, '0.00', '2020-05-03 17:42:09', '2020-05-03 17:42:09'),
(21, 'Thai Baht', '', '2', ',', '.', 'THB', 0, '0.00', '2020-05-03 17:42:09', '2020-05-03 17:42:09'),
(22, 'Nigerian Naira', '', '2', ',', '.', 'NGN', 0, '0.00', '2020-05-03 17:42:09', '2020-05-03 17:42:09'),
(23, 'Argentine Peso', '$', '2', '.', ',', 'ARS', 0, '0.00', '2020-05-03 17:42:09', '2020-05-03 17:42:09'),
(24, 'Bangladeshi Taka', 'Tk', '2', ',', '.', 'BDT', 0, '0.00', '2020-05-03 17:42:09', '2020-05-03 17:42:09'),
(25, 'United Arab Emirates Dirham', 'DH ', '2', ',', '.', 'AED', 0, '0.00', '2020-05-03 17:42:09', '2020-05-03 17:42:09'),
(26, 'Hong Kong Dollar', '', '2', ',', '.', 'HKD', 0, '0.00', '2020-05-03 17:42:09', '2020-05-03 17:42:09'),
(27, 'Indonesian Rupiah', 'Rp', '2', ',', '.', 'IDR', 0, '0.00', '2020-05-03 17:42:09', '2020-05-03 17:42:09'),
(28, 'Mexican Peso', '$', '2', ',', '.', 'MXN', 0, '0.00', '2020-05-03 17:42:09', '2020-05-03 17:42:09'),
(29, 'Egyptian Pound', 'E£', '2', ',', '.', 'EGP', 0, '0.00', '2020-05-03 17:42:09', '2020-05-03 17:42:09'),
(30, 'Colombian Peso', '$', '2', '.', ',', 'COP', 0, '0.00', '2020-05-03 17:42:09', '2020-05-03 17:42:09'),
(31, 'West African Franc', 'CFA ', '2', ',', '.', 'XOF', 0, '0.00', '2020-05-03 17:42:09', '2020-05-03 17:42:09'),
(32, 'Chinese Renminbi', 'RMB ', '2', ',', '.', 'CNY', 0, '0.00', '2020-05-03 17:42:09', '2020-05-03 17:42:09'),
(33, 'Rwandan Franc', 'RF ', '2', ',', '.', 'RWF', 0, '0.00', '2020-05-03 17:42:09', '2020-05-03 17:42:09'),
(34, 'Tanzanian Shilling', 'TSh ', '2', ',', '.', 'TZS', 0, '0.00', '2020-05-03 17:42:09', '2020-05-03 17:42:09'),
(35, 'Netherlands Antillean Guilder', '', '2', '.', ',', 'ANG', 0, '0.00', '2020-05-03 17:42:09', '2020-05-03 17:42:09'),
(36, 'Trinidad and Tobago Dollar', 'TT$', '2', ',', '.', 'TTD', 0, '0.00', '2020-05-03 17:42:10', '2020-05-03 17:42:10'),
(37, 'East Caribbean Dollar', 'EC$', '2', ',', '.', 'XCD', 0, '0.00', '2020-05-03 17:42:10', '2020-05-03 17:42:10'),
(38, 'Ghanaian Cedi', '', '2', ',', '.', 'GHS', 0, '0.00', '2020-05-03 17:42:10', '2020-05-03 17:42:10'),
(39, 'Bulgarian Lev', '', '2', ' ', '.', 'BGN', 0, '0.00', '2020-05-03 17:42:10', '2020-05-03 17:42:10'),
(40, 'Aruban Florin', 'Afl. ', '2', ' ', '.', 'AWG', 0, '0.00', '2020-05-03 17:42:10', '2020-05-03 17:42:10'),
(41, 'Turkish Lira', 'TL ', '2', '.', ',', 'TRY', 0, '0.00', '2020-05-03 17:42:10', '2020-05-03 17:42:10'),
(42, 'Romanian New Leu', '', '2', ',', '.', 'RON', 0, '0.00', '2020-05-03 17:42:10', '2020-05-03 17:42:10'),
(43, 'Croatian Kuna', 'kn', '2', '.', ',', 'HRK', 1, '0.00', '2020-05-03 17:42:10', '2020-05-03 17:42:10'),
(44, 'Saudi Riyal', '', '2', ',', '.', 'SAR', 0, '0.00', '2020-05-03 17:42:10', '2020-05-03 17:42:10'),
(45, 'Japanese Yen', '¥', '0', ',', '.', 'JPY', 0, '0.00', '2020-05-03 17:42:10', '2020-05-03 17:42:10'),
(46, 'Maldivian Rufiyaa', '', '2', ',', '.', 'MVR', 0, '0.00', '2020-05-03 17:42:10', '2020-05-03 17:42:10'),
(47, 'Costa Rican Colón', '', '2', ',', '.', 'CRC', 0, '0.00', '2020-05-03 17:42:10', '2020-05-03 17:42:10'),
(48, 'Pakistani Rupee', 'Rs ', '0', ',', '.', 'PKR', 0, '0.00', '2020-05-03 17:42:10', '2020-05-03 17:42:10'),
(49, 'Polish Zloty', 'zł', '2', ' ', ',', 'PLN', 1, '0.00', '2020-05-03 17:42:10', '2020-05-03 17:42:10'),
(50, 'Sri Lankan Rupee', 'LKR', '2', ',', '.', 'LKR', 1, '0.00', '2020-05-03 17:42:10', '2020-05-03 17:42:10'),
(51, 'Czech Koruna', 'Kč', '2', ' ', ',', 'CZK', 1, '0.00', '2020-05-03 17:42:10', '2020-05-03 17:42:10'),
(52, 'Uruguayan Peso', '$', '2', '.', ',', 'UYU', 0, '0.00', '2020-05-03 17:42:10', '2020-05-03 17:42:10'),
(53, 'Namibian Dollar', '$', '2', ',', '.', 'NAD', 0, '0.00', '2020-05-03 17:42:10', '2020-05-03 17:42:10'),
(54, 'Tunisian Dinar', '', '2', ',', '.', 'TND', 0, '0.00', '2020-05-03 17:42:10', '2020-05-03 17:42:10'),
(55, 'Russian Ruble', '', '2', ',', '.', 'RUB', 0, '0.00', '2020-05-03 17:42:10', '2020-05-03 17:42:10'),
(56, 'Mozambican Metical', 'MT', '2', '.', ',', 'MZN', 1, '0.00', '2020-05-03 17:42:11', '2020-05-03 17:42:11'),
(57, 'Omani Rial', '', '2', ',', '.', 'OMR', 0, '0.00', '2020-05-03 17:42:11', '2020-05-03 17:42:11'),
(58, 'Ukrainian Hryvnia', '', '2', ',', '.', 'UAH', 0, '0.00', '2020-05-03 17:42:11', '2020-05-03 17:42:11'),
(59, 'Macanese Pataca', 'MOP$', '2', ',', '.', 'MOP', 0, '0.00', '2020-05-03 17:42:11', '2020-05-03 17:42:11'),
(60, 'Taiwan New Dollar', 'NT$', '2', ',', '.', 'TWD', 0, '0.00', '2020-05-03 17:42:11', '2020-05-03 17:42:11'),
(61, 'Dominican Peso', 'RD$', '2', ',', '.', 'DOP', 0, '0.00', '2020-05-03 17:42:11', '2020-05-03 17:42:11'),
(62, 'Chilean Peso', '$', '0', '.', ',', 'CLP', 0, '0.00', '2020-05-03 17:42:11', '2020-05-03 17:42:11'),
(63, 'Icelandic Króna', 'kr', '2', '.', ',', 'ISK', 1, '0.00', '2020-05-03 17:42:11', '2020-05-03 17:42:11'),
(64, 'Papua New Guinean Kina', 'K', '2', ',', '.', 'PGK', 0, '0.00', '2020-05-03 17:42:11', '2020-05-03 17:42:11'),
(65, 'Jordanian Dinar', '', '2', ',', '.', 'JOD', 0, '0.00', '2020-05-03 17:42:11', '2020-05-03 17:42:11'),
(66, 'Myanmar Kyat', 'K', '2', ',', '.', 'MMK', 0, '0.00', '2020-05-03 17:42:11', '2020-05-03 17:42:11'),
(67, 'Peruvian Sol', 'S/ ', '2', ',', '.', 'PEN', 0, '0.00', '2020-05-03 17:42:11', '2020-05-03 17:42:11'),
(68, 'Botswana Pula', 'P', '2', ',', '.', 'BWP', 0, '0.00', '2020-05-03 17:42:11', '2020-05-03 17:42:11'),
(69, 'Hungarian Forint', 'Ft', '0', '.', ',', 'HUF', 1, '0.00', '2020-05-03 17:42:11', '2020-05-03 17:42:11'),
(70, 'Ugandan Shilling', 'USh ', '2', ',', '.', 'UGX', 0, '0.00', '2020-05-03 17:42:11', '2020-05-03 17:42:11'),
(71, 'Barbadian Dollar', '$', '2', ',', '.', 'BBD', 0, '0.00', '2020-05-03 17:42:11', '2020-05-03 17:42:11'),
(72, 'Brunei Dollar', 'B$', '2', ',', '.', 'BND', 0, '0.00', '2020-05-03 17:42:12', '2020-05-03 17:42:12'),
(73, 'Georgian Lari', '', '2', ' ', ',', 'GEL', 0, '0.00', '2020-05-03 17:42:12', '2020-05-03 17:42:12'),
(74, 'Qatari Riyal', 'QR', '2', ',', '.', 'QAR', 0, '0.00', '2020-05-03 17:42:12', '2020-05-03 17:42:12'),
(75, 'Honduran Lempira', 'L', '2', ',', '.', 'HNL', 0, '0.00', '2020-05-03 17:42:12', '2020-05-03 17:42:12'),
(76, 'Surinamese Dollar', 'SRD', '2', '.', ',', 'SRD', 0, '0.00', '2020-05-03 17:42:12', '2020-05-03 17:42:12'),
(77, 'Bahraini Dinar', 'BD ', '2', ',', '.', 'BHD', 0, '0.00', '2020-05-03 17:42:12', '2020-05-03 17:42:12'),
(78, 'Venezuelan Bolivars', 'Bs.', '2', '.', ',', 'VES', 0, '0.00', '2020-05-03 17:42:12', '2020-05-03 17:42:12'),
(79, 'South Korean Won', 'W ', '2', '.', ',', 'KRW', 0, '0.00', '2020-05-03 17:42:12', '2020-05-03 17:42:12'),
(80, 'Moroccan Dirham', 'MAD ', '2', ',', '.', 'MAD', 0, '0.00', '2020-05-03 17:42:12', '2020-05-03 17:42:12'),
(81, 'Jamaican Dollar', '$', '2', ',', '.', 'JMD', 0, '0.00', '2020-05-03 17:42:12', '2020-05-03 17:42:12'),
(82, 'Angolan Kwanza', 'Kz', '2', '.', ',', 'AOA', 0, '0.00', '2020-05-03 17:42:13', '2020-05-03 17:42:13'),
(83, 'Haitian Gourde', 'G', '2', ',', '.', 'HTG', 0, '0.00', '2020-05-03 17:42:13', '2020-05-03 17:42:13'),
(84, 'Zambian Kwacha', 'ZK', '2', ',', '.', 'ZMW', 0, '0.00', '2020-05-03 17:42:13', '2020-05-03 17:42:13'),
(85, 'Nepalese Rupee', 'Rs. ', '2', ',', '.', 'NPR', 0, '0.00', '2020-05-03 17:42:13', '2020-05-03 17:42:13'),
(86, 'CFP Franc', '', '2', ',', '.', 'XPF', 0, '0.00', '2020-05-03 17:42:13', '2020-05-03 17:42:13'),
(87, 'Mauritian Rupee', 'Rs', '2', ',', '.', 'MUR', 0, '0.00', '2020-05-03 17:42:13', '2020-05-03 17:42:13'),
(88, 'Cape Verdean Escudo', '', '2', '.', '$', 'CVE', 0, '0.00', '2020-05-03 17:42:13', '2020-05-03 17:42:13'),
(89, 'Kuwaiti Dinar', 'KD', '2', ',', '.', 'KWD', 0, '0.00', '2020-05-03 17:42:13', '2020-05-03 17:42:13'),
(90, 'Algerian Dinar', 'DA', '2', ',', '.', 'DZD', 0, '0.00', '2020-05-03 17:42:13', '2020-05-03 17:42:13'),
(91, 'Macedonian Denar', 'ден', '2', ',', '.', 'MKD', 0, '0.00', '2020-05-03 17:42:13', '2020-05-03 17:42:13'),
(92, 'Fijian Dollar', 'FJ$', '2', ',', '.', 'FJD', 0, '0.00', '2020-05-03 17:42:13', '2020-05-03 17:42:13'),
(93, 'Bolivian Boliviano', 'Bs', '2', ',', '.', 'BOB', 0, '0.00', '2020-05-03 17:42:13', '2020-05-03 17:42:13'),
(94, 'Albanian Lek', 'L ', '2', '.', ',', 'ALL', 0, '0.00', '2020-05-03 17:42:13', '2020-05-03 17:42:13'),
(95, 'Serbian Dinar', 'din', '2', '.', ',', 'RSD', 0, '0.00', '2020-05-03 17:42:13', '2020-05-03 17:42:13'),
(96, 'Lebanese Pound', 'LL ', '2', ',', '.', 'LBP', 0, '0.00', '2020-05-03 17:42:13', '2020-05-03 17:42:13'),
(97, 'Armenian Dram', '', '2', ',', '.', 'AMD', 0, '0.00', '2020-05-03 17:42:13', '2020-05-03 17:42:13'),
(98, 'Azerbaijan Manat', '', '2', ',', '.', 'AZN', 0, '0.00', '2020-05-03 17:42:13', '2020-05-03 17:42:13'),
(99, 'Bosnia and Herzegovina Convertible Mark', '', '2', ',', '.', 'BAM', 0, '0.00', '2020-05-03 17:42:13', '2020-05-03 17:42:13'),
(100, 'Belarusian Ruble', '', '2', ',', '.', 'BYN', 0, '0.00', '2020-05-03 17:42:13', '2020-05-03 17:42:13'),
(101, 'Gibraltar Pound', 'GIP', '2', ',', '.', '', 0, '0.00', '2020-05-03 17:42:13', '2020-05-03 17:42:13');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
CREATE TABLE `customers` (
  `id` int(10) UNSIGNED NOT NULL,
  `account_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `currency_id` int(10) UNSIGNED DEFAULT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `default_payment_method` int(10) UNSIGNED DEFAULT NULL,
  `assigned_user_id` int(10) UNSIGNED DEFAULT NULL,
  `status` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `website` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `logo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `balance` decimal(16,4) NOT NULL DEFAULT 0.0000,
  `paid_to_date` decimal(16,4) NOT NULL DEFAULT 0.0000,
  `credit_balance` decimal(16,4) NOT NULL DEFAULT 0.0000,
  `last_login` datetime DEFAULT NULL,
  `settings` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `group_settings_id` int(10) UNSIGNED DEFAULT NULL,
  `vat_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `custom_value1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_value2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_value3` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_value4` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `id_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `public_notes` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `private_notes` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `industry_id` int(10) UNSIGNED DEFAULT NULL,
  `size_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `account_id`, `user_id`, `currency_id`, `company_id`, `default_payment_method`, `assigned_user_id`, `status`, `name`, `website`, `logo`, `phone`, `balance`, `paid_to_date`, `credit_balance`, `last_login`, `settings`, `is_deleted`, `group_settings_id`, `vat_number`, `created_at`, `updated_at`, `deleted_at`, `custom_value1`, `custom_value2`, `custom_value3`, `custom_value4`, `id_number`, `public_notes`, `private_notes`, `industry_id`, `size_id`) VALUES
(5, 1, 5, 2, NULL, 13, NULL, 1, 'Michael Hampton', 'www.testwebsite.com', NULL, '01590 677428', '0.0000', '0.0000', '0.0000', NULL, '{\"payment_terms\":30,\"customer_number_counter\":\"1\",\"payment_type_id\":\"0\",\"customer_number_pattern\":\"\", \"language_id\": 1}', 0, NULL, 'vat number', '2020-05-03 18:15:10', '2020-05-03 18:15:10', NULL, NULL, NULL, NULL, NULL, '0002', 'public', 'provate', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `customer_type`
--

DROP TABLE IF EXISTS `customer_type`;
CREATE TABLE `customer_type` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_types`
--

DROP TABLE IF EXISTS `customer_types`;
CREATE TABLE `customer_types` (
  `id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `datetime_formats`
--

DROP TABLE IF EXISTS `datetime_formats`;
CREATE TABLE `datetime_formats` (
  `id` int(10) UNSIGNED NOT NULL,
  `format` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `format_moment` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `format_dart` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `datetime_formats`
--

INSERT INTO `datetime_formats` (`id`, `format`, `format_moment`, `format_dart`, `updated_at`, `created_at`) VALUES
(1, 'd/M/Y g:i a', 'DD/MMM/YYYY h:mm:ss a', 'dd/MMM/yyyy h:mm a', '2020-05-03 17:51:16', '2020-05-03 17:51:16'),
(2, 'd-M-Y g:i a', 'DD-MMM-YYYY h:mm:ss a', 'dd-MMM-yyyy h:mm a', '2020-05-03 17:51:16', '2020-05-03 17:51:16'),
(3, 'd/F/Y g:i a', 'DD/MMMM/YYYY h:mm:ss a', 'dd/MMMM/yyyy h:mm a', '2020-05-03 17:51:16', '2020-05-03 17:51:16'),
(4, 'd-F-Y g:i a', 'DD-MMMM-YYYY h:mm:ss a', 'dd-MMMM-yyyy h:mm a', '2020-05-03 17:51:16', '2020-05-03 17:51:16'),
(5, 'M j, Y g:i a', 'MMM D, YYYY h:mm:ss a', 'MMM d, yyyy h:mm a', '2020-05-03 17:51:16', '2020-05-03 17:51:16'),
(6, 'F j, Y g:i a', 'MMMM D, YYYY h:mm:ss a', 'MMMM d, yyyy h:mm a', '2020-05-03 17:51:16', '2020-05-03 17:51:16'),
(7, 'D M jS, Y g:i a', 'ddd MMM Do, YYYY h:mm:ss a', 'EEE MMM d, yyyy h:mm a', '2020-05-03 17:51:16', '2020-05-03 17:51:16'),
(8, 'Y-m-d g:i a', 'YYYY-MM-DD h:mm:ss a', 'yyyy-MM-dd h:mm a', '2020-05-03 17:51:16', '2020-05-03 17:51:16'),
(9, 'd-m-Y g:i a', 'DD-MM-YYYY h:mm:ss a', 'dd-MM-yyyy h:mm a', '2020-05-03 17:51:16', '2020-05-03 17:51:16'),
(10, 'm/d/Y g:i a', 'MM/DD/YYYY h:mm:ss a', 'MM/dd/yyyy h:mm a', '2020-05-03 17:51:16', '2020-05-03 17:51:16'),
(11, 'd.m.Y g:i a', 'D.MM.YYYY h:mm:ss a', 'dd.MM.yyyy h:mm a', '2020-05-03 17:51:17', '2020-05-03 17:51:17'),
(12, 'j. M. Y g:i a', 'DD. MMM. YYYY h:mm:ss a', 'd. MMM. yyyy h:mm a', '2020-05-03 17:51:17', '2020-05-03 17:51:17'),
(13, 'j. F Y g:i a', 'DD. MMMM YYYY h:mm:ss a', 'd. MMMM yyyy h:mm a', '2020-05-03 17:51:17', '2020-05-03 17:51:17'),
(14, 'dd/mm/yyyy g:i a', 'DD/MM/YYYY h:mm:ss a', 'dd/MM/yyyy h:mm a', '2020-05-03 17:51:17', '2020-05-03 17:51:17');

-- --------------------------------------------------------

--
-- Table structure for table `date_formats`
--

DROP TABLE IF EXISTS `date_formats`;
CREATE TABLE `date_formats` (
  `id` int(10) UNSIGNED NOT NULL,
  `format` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `format_moment` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `format_dart` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
CREATE TABLE `departments` (
  `id` int(10) UNSIGNED NOT NULL,
  `department_manager` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `_lft` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `_rgt` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `parent_id` int(10) UNSIGNED DEFAULT NULL,
  `account_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `department_user`
--

DROP TABLE IF EXISTS `department_user`;
CREATE TABLE `department_user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `department_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `designs`
--

DROP TABLE IF EXISTS `designs`;
CREATE TABLE `designs` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `account_id` int(10) UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_custom` tinyint(1) NOT NULL DEFAULT 1,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `design` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` datetime NOT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `designs`
--

INSERT INTO `designs` (`id`, `user_id`, `account_id`, `name`, `is_custom`, `is_active`, `design`, `created_at`, `updated_at`, `deleted_at`, `is_deleted`) VALUES
(1, NULL, NULL, 'Basic', 0, 1, '{\"header\":\" <div class=\\\"px-2 py-4\\\">\\r\\n<div>\\r\\n    $account_logo\\r\\n    <div class=\\\"inline-block\\\" style=\\\"word-break: break-word\\\">\\r\\n        $account_details <br>\\r\\n        $account_address\\r\\n    <\\/div>\\r\\n<\\/div>\\r\\n    <div class=\\\"inline-block mr-4 mt-4\\\" style=\\\"width: 60%;\\\">\\r\\n        <div class=\\\"\\\">\\r\\n            <section class=\\\"\\\">\\r\\n                $entity_details\\r\\n            <\\/section>\\r\\n        <\\/div>\\r\\n<\\/div>\",\"body\":\"<div class=\\\"inline-block\\\">\\r\\n    $customer_details\\r\\n<\\/div>\\r\\n\\r\\n$table_here\\r\\n\\r\\n<div class=\\\"mt-4\\\">\\r\\n<div class=\\\"inline-block col-6\\\" style=\\\"width: 70%\\\">\\r\\n    <div class=\\\"\\\">\\r\\n        <p>$entity.public_notes<\\/p>\\r\\n        <div class=\\\"pt-4\\\">\\r\\n            <p class=\\\"font-weight-bold\\\">$terms_label<\\/p>\\r\\n            <p>$terms<\\/p>\\r\\n        <\\/div>\\r\\n    <\\/div>\\r\\n<\\/div>\\r\\n<div class=\\\"inline-block\\\" style=\\\"width: 20%;\\\">\\r\\n    <div class=\\\"inline-block px-3\\\">\\r\\n        <div class=\\\"col-6 text-left\\\">\\r\\n            <span style=\\\"margin-right: 20px\\\"> $discount_label <\\/span>  $discount<br>\\r\\n            <span style=\\\"margin-right: 20px\\\">$tax_label<\\/span> $tax<br>\\r\\n            <span style=\\\"margin-right: 20px\\\"> $balance_due_label <\\/span>  $balance_due<br>\\r\\n        <\\/div>\\r\\n    <\\/div>\\r\\n<\\/div>\\r\\n<\\/div>\\r\\n\",\"table\":\"<table class=\\\"w-100 table-auto mt-4\\\">\\r\\n    <thead class=\\\"text-left bg-secondary\\\">\\r\\n        $product_table_header\\r\\n    <\\/thead>\\r\\n    <tbody>\\r\\n        $product_table_body\\r\\n    <\\/tbody>\\r\\n<\\/table>\",\"task_table\":\"\\r\\n<table class=\\\"w-100 table-auto mt-4\\\">\\r\\n    <thead class=\\\"text-left\\\">\\r\\n        $task_table_header\\r\\n    <\\/thead>\\r\\n    <tbody>\\r\\n        $task_table_body\\r\\n    <\\/tbody>\\r\\n<\\/table>\",\"product\":\"\",\"task\":\"\",\"footer\":\"\\r\\n        <div class=\\\"text-center mb-2\\\">\\r\\n               $signature_here\\r\\n           <\\/div>\\r\\n        \\r\\n        <div class=\\\"footer_class py-4 px-4\\\" style=\\\"page-break-inside: avoid;\\\">\\r\\n        <\\/div>\\r\\n<\\/body>\\r\\n<\\/html>\"}', '2020-05-03 17:47:42', '2020-05-03 17:48:50', '0000-00-00 00:00:00', 0),
(2, NULL, NULL, 'Danger', 0, 1, '{\"header\":\"<div class=\\\"py-4 px-4 mt-2\\\">\\r\\n            <div style=\\\"width: 100%\\\">\\r\\n                <div class=\\\"inline-block mt-4\\\" style=\\\"width: 30%\\\">\\r\\n                    <div class=\\\"inline-block\\\">\\r\\n                        $customer_details\\r\\n                    <\\/div>\\r\\n                    <div class=\\\"inline-block ml-4\\\">\\r\\n                        $account_details\\r\\n                    <\\/div>\\r\\n                    <div class=\\\"inline-block ml-4 mr-4\\\">\\r\\n                        $account_address\\r\\n                    <\\/div>\\r\\n                <\\/div>\\r\\n                \\r\\n                <div class=\\\"mt-4\\\" style=\\\"width: 60%\\\">\\r\\n    $account_logo\\r\\n    <\\/div>\\r\\n<\\/div>\",\"body\":\"<div class=\\\"mt-4\\\">\\r\\n<div class=\\\"inline-block\\\" style=\\\"width: 60%\\\">\\r\\n    <h1 class=\\\"text-uppercase font-weight-bold\\\">$entity_label<\\/h1>\\r\\n    <i class=\\\"ml-4 text-danger\\\">$entity_number<\\/i>\\r\\n<\\/div>\\r\\n<div class=\\\"inline-block text-left\\\" style=\\\"width: 30%\\\">\\r\\n    <div class=\\\"inline-block\\\">\\r\\n        $entity_labels\\r\\n    <\\/div>\\r\\n    <div class=\\\"inline-block text-right\\\">\\r\\n        $entity_details\\r\\n    <\\/div>\\r\\n<\\/div>\\r\\n<\\/div>\\r\\n\\r\\n$table_here\\r\\n\\r\\n<div class=\\\"border-top-4 border-danger\\\">\\r\\n<div class=\\\"mt-4 px-4 pb-4\\\">\\r\\n    <div class=\\\"inline-block\\\" style=\\\"width: 70%\\\">\\r\\n        <div class=\\\"\\\">\\r\\n            <p>$entity.public_notes<\\/p>\\r\\n        <\\/div>\\r\\n    <\\/div>\\r\\n    <div class=\\\"inline-block\\\" style=\\\"width: 20%\\\">\\r\\n        <div class=\\\"px-3 mt-2\\\">\\r\\n            <div class=\\\"inline-block col-6 text-left\\\">\\r\\n                <span style=\\\"margin-right: 80px\\\">$subtotal_label<\\/span> $subtotal <br>\\r\\n                <span style=\\\"margin-right: 80px\\\">$discount_label<\\/span> $discount <br>\\r\\n                <span style=\\\"margin-right: 80px\\\">$tax_label<\\/span> $tax <br>\\r\\n                <span style=\\\"margin-right: 80px\\\">$balance_due_label<\\/span> <span class=\\\"text-danger font-weight-bold\\\">$balance_due<\\/span> <br>\\r\\n            <\\/div>\\r\\n        <\\/div>\\r\\n    <\\/div>\\r\\n<\\/div>\\r\\n<div class=\\\"mt-1 pb-4 px-4\\\">\\r\\n    <div style=\\\"width: 70%\\\">\\r\\n        <div>\\r\\n            <p class=\\\"font-weight-bold\\\">$terms_label<\\/p>\\r\\n            <p>$terms<\\/p>\\r\\n        <\\/div>\\r\\n    <\\/div>\\r\\n<\\/div>\\r\\n<\\/div>\\r\\n<\\/div>\\r\\n\\r\\n\",\"table\":\"<table class=\\\"w-100 table-auto mt-4 border-top-4 border-danger bg-white\\\">\\r\\n<thead class=\\\"text-left rounded\\\">\\r\\n    $product_table_header\\r\\n<\\/thead>\\r\\n<tbody>\\r\\n    $product_table_body\\r\\n<\\/tbody>\\r\\n<\\/table>\",\"task_table\":\"<table class=\\\"w-100 table-auto mt-4 border-top-4 border-danger bg-white\\\">\\r\\n    <thead class=\\\"text-left rounded\\\">\\r\\n        $task_table_header\\r\\n    <\\/thead>\\r\\n    <tbody>\\r\\n        $task_table_body\\r\\n    <\\/tbody>\\r\\n<\\/table>\",\"product\":\"\",\"task\":\"\",\"footer\":\"\\r\\n        <div class=\\\"text-center mb-2\\\">\\r\\n               $signature_here\\r\\n           <\\/div>\\r\\n        \\r\\n        <div class=\\\"footer_class py-4 px-4\\\" style=\\\"page-break-inside: avoid;\\\">\\r\\n        <\\/div>\\r\\n<\\/footer>\\r\\n<\\/body>\\r\\n<\\/html>\"}', '2020-05-03 17:47:42', '2020-05-03 17:48:50', '0000-00-00 00:00:00', 0),
(3, NULL, NULL, 'Dark', 0, 1, '{\"header\":\"<div class=\\\"py-4 px-4\\\">\\r\\n<div class=\\\"border-4 border-dark mb-4\\\">\\r\\n    <div class=\\\"inline-block mt-2\\\" style=\\\"margin-bottom: 15px; width: 60%; margin-top: 20px;\\\">\\r\\n        $account_logo\\r\\n    <\\/div>\\r\\n    <div class=\\\"inline-block text-right\\\" style=\\\"width: 40%; margin-top: 20px\\\">\\r\\n        <div class=\\\"inline-block mr-4\\\">\\r\\n            $entity_labels\\r\\n        <\\/div>\\r\\n        <div class=\\\"inline-block text-right\\\">\\r\\n            $entity_details\\r\\n        <\\/div>\\r\\n    <\\/div>\\r\\n<\\/div>\\r\\n<div class=\\\"border-bottom border-dark mt-1\\\"><\\/div>\",\"body\":\"<div class=\\\"pt-4\\\">\\r\\n<div class=\\\"inline-block border-right border-dashed border-dark pt-4\\\" style=\\\"width: 40%; margin-left: 40px;\\\">\\r\\n    $customer_details\\r\\n<\\/div>\\r\\n\\r\\n<div class=\\\"inline-block pl-4\\\" style=\\\"width: 20%\\\">\\r\\n $account_details\\r\\n$account_address\\r\\n<\\/div>\\r\\n   \\r\\n    \\r\\n<\\/div>\\r\\n<\\/div>\\r\\n\\r\\n<\\/div>\\r\\n\\r\\n$table_here\\r\\n\\r\\n<div class=\\\"mt-2 px-4 pb-4\\\">\\r\\n    <div class=\\\"inline-block\\\" style=\\\"width: 70%\\\">\\r\\n        <div>\\r\\n            <p>$entity.public_notes<\\/p>\\r\\n        <\\/div>\\r\\n    <\\/div>\\r\\n    <div class=\\\"inline-block\\\" style=\\\"width: 20%\\\">\\r\\n        <div class=\\\"px-3 mt-2\\\">\\r\\n            <div class=\\\"inline-block col-6 text-left\\\">\\r\\n                <span style=\\\"margin-right: 20px\\\"> $discount_label <\\/span> $discount<br>\\r\\n                <span style=\\\"margin-right: 20px\\\">$tax_label <\\/span> $tax\\r\\n            <\\/div>\\r\\n        <\\/div>\\r\\n    <\\/div>\\r\\n<\\/div>\\r\\n<div class=\\\"mt-1 pb-4 px-4\\\">\\r\\n    <div class=\\\"inline-block\\\" style=\\\"width: 70%\\\">\\r\\n        <div>\\r\\n            <p class=\\\"font-weight-bold\\\">$terms_label<\\/p>\\r\\n            <p>$terms<\\/p>\\r\\n        <\\/div>\\r\\n    <\\/div>\\r\\n    <div class=\\\"inline-block\\\" style=\\\"width: 20%\\\">\\r\\n        <section class=\\\"py-2 pt-4 text-success border-top border-bottom border-dashed border-dark px-2 mt-1\\\">\\r\\n            <p class=\\\"text-right\\\">$balance_due_label<\\/p>\\r\\n            <p class=\\\"text-right\\\">$balance_due<\\/p>\\r\\n        <\\/section>\\r\\n    <\\/div>\\r\\n<\\/div>\\r\\n        <div class=\\\"border-bottom-4 ml-4 border-dark mt-4\\\">\\r\\n        <h4 class=\\\"font-weight-bold mb-4\\\">Thanks for shopping with us<\\/h4>\\r\\n    <\\/div>\\r\\n    <div class=\\\"border-bottom border-dark mt-1\\\"><\\/div>\\r\\n<\\/div>\\r\\n\\r\\n\",\"table\":\"<table class=\\\"w-100 table-auto mb-4 mt-4\\\">\\r\\n    <thead class=\\\"text-left border-dashed border-bottom border-dark\\\">\\r\\n        $product_table_header\\r\\n    <\\/thead>\\r\\n    <tbody>\\r\\n        $product_table_body\\r\\n    <\\/tbody>\\r\\n<\\/table>\",\"task_table\":\"\",\"product\":\"\",\"task\":\"\",\"footer\":\"\\r\\n        <div class=\\\"text-center mb-2\\\">\\r\\n                $signature_here\\r\\n            <\\/div>\\r\\n\\r\\n        <div class=\\\"footer_class py-4 px-4\\\" style=\\\"page-break-inside: avoid;\\\">\\r\\n        <\\/div>\\r\\n<\\/footer>\\r\\n<\\/body>\\r\\n<\\/html>\"}', '2020-05-03 17:47:42', '2020-05-03 17:48:50', '0000-00-00 00:00:00', 0),
(4, NULL, NULL, 'Happy', 0, 1, '{\"header\":\"<div class=\\\"\\\">\\r\\n<div class=\\\"mt-4\\\">\\r\\n    <div class=\\\"inline-block col-6 ml-4\\\" style=\\\"width: 40%\\\">\\r\\n        $account_logo\\r\\n    <\\/div>\\r\\n    <div class=\\\"inline-block bg-info p-4\\\" style=\\\"width: 40%\\\">\\r\\n        <div>\\r\\n            <div class=\\\"text-white mr-4\\\">\\r\\n                $entity_labels\\r\\n            <\\/div>\\r\\n            <div class=\\\"text-right text-white\\\">\\r\\n                $entity_details\\r\\n            <\\/div>\\r\\n        <\\/div>\\r\\n    <\\/div>\\r\\n<\\/div>\",\"body\":\"<div class=\\\"mt-4 border-dashed border-top-4 border-bottom-4 border-info\\\">\\r\\n<div class=\\\"inline-block\\\" style=\\\"width: 50%\\\">\\r\\n    <div>\\r\\n        <p class=\\\"font-weight-bold bg-info pl-4\\\">$entity_label<\\/p>\\r\\n        <div class=\\\"py-4 mt-4 pl-4\\\">\\r\\n            <section>\\r\\n                $customer_details\\r\\n            <\\/section>\\r\\n        <\\/div>\\r\\n    <\\/div>\\r\\n<\\/div>\\r\\n<div class=\\\"inline-block col-6 ml-4\\\" style=\\\"width: 40%\\\">\\r\\n    <div>\\r\\n        <p class=\\\"font-weight-bold text-info pl-4\\\">$from_label:<\\/p>\\r\\n        <div class=\\\"border-dashed border-top-4 border-bottom-4 border-info py-4 mt-2 pl-4\\\">\\r\\n            <section>\\r\\n                $account_details\\r\\n            <\\/section>\\r\\n        <\\/div>\\r\\n    <\\/div>\\r\\n<\\/div>\\r\\n<\\/div>\\r\\n\\r\\n$table_here\\r\\n\\r\\n<div class=\\\"mt-3 px-4\\\">\\r\\n<div class=\\\"inline-block col-6\\\" style=\\\"width: 70%\\\">\\r\\n    <div>\\r\\n        <p>$entity.public_notes<\\/p>\\r\\n    <\\/div>\\r\\n<\\/div>\\r\\n<div class=\\\"inline-block\\\" style=\\\"width: 30%\\\">\\r\\n    <div class=\\\"px-3 mt-2\\\">\\r\\n        <div class=\\\"col-6 text-right\\\">\\r\\n            <span style=\\\"margin-right: 20px\\\"> $discount_label <\\/span> $discount <br>\\r\\n            <span style=\\\"margin-right: 20px\\\"> $tax_label <\\/span> $tax <br>\\r\\n        <\\/div>\\r\\n    <\\/div>\\r\\n<\\/div>\\r\\n<\\/div>\\r\\n<div class=\\\"w-100 mt-4 pb-4 px-4 mt-2\\\">\\r\\n<div class=\\\"inline-block\\\" style=\\\"width: 70%\\\">\\r\\n    <div>\\r\\n        <p class=\\\"font-weight-bold\\\">$terms_label<\\/p>\\r\\n        <p>$terms<\\/p>\\r\\n    <\\/div>\\r\\n<\\/div>\\r\\n<div class=\\\"inline-block\\\" style=\\\"width: 30%\\\">\\r\\n    <section class=\\\"bg-info py-2 px-3 pt-4 text-white\\\">\\r\\n        <p class=\\\"text-right\\\">$balance_due_label<\\/p>\\r\\n        <p class=\\\"text-right\\\">$balance_due<\\/p>\\r\\n    <\\/section>\\r\\n<\\/div>\\r\\n<\\/div>\\r\\n<\\/div>\\r\\n<\\/div>\\r\\n\",\"table\":\"<table class=\\\"w-100\\\">\\r\\n<thead class=\\\"text-left bg-info rounded\\\">\\r\\n    $product_table_header\\r\\n<\\/thead>\\r\\n<tbody>\\r\\n    $product_table_body\\r\\n<\\/tbody>\\r\\n<\\/table>\",\"task_table\":\"\",\"product\":\"\",\"task\":\"\",\"footer\":\"\\r\\n        <div class=\\\"text-center mb-2\\\">\\r\\n               $signature_here\\r\\n           <\\/div>\\r\\n        \\r\\n        <div class=\\\"footer_class py-4 px-4\\\" style=\\\"page-break-inside: avoid;\\\">\\r\\n        <\\/div>\\r\\n<\\/body>\\r\\n<\\/html>\"}', '2020-05-03 17:47:42', '2020-05-03 17:48:50', '0000-00-00 00:00:00', 0),
(5, NULL, NULL, 'Info', 0, 1, '{\"header\":\"<div class=\\\"bg-secondary p-4\\\">\\r\\n<div class=\\\"col-6 inline-block mt-4\\\">\\r\\n    <div class=\\\"bg-white pt-4 px-4 pb-4 inline-block\\\">\\r\\n        $account_logo\\r\\n    <\\/div>\\r\\n<\\/div>\\r\\n<div class=\\\"col-6 inline-block\\\">\\r\\n    <div class=\\\"text-white\\\">\\r\\n        $account_details\\r\\n    <\\/div>\\r\\n    <div class=\\\"inline-block text-white\\\">\\r\\n        $account_address\\r\\n    <\\/div>\\r\\n<\\/div>\\r\\n<\\/div>\",\"body\":\"<div class=\\\"mt-4 pl-4\\\">\\r\\n    <div class=\\\"inline-block col-6 mr-4\\\" style=\\\"width: 40%\\\">\\r\\n        <h2 class=\\\"text-uppercase font-weight-bolder text-info\\\">$entity_label<\\/h2> $customer_details\\r\\n    <\\/div>\\r\\n    <div class=\\\"inline-block col-6\\\">\\r\\n        <div class=\\\"bg-info px-4 py-3 text-white\\\">\\r\\n            <div class=\\\"text-white\\\">\\r\\n                $entity_details\\r\\n            <\\/div>\\r\\n        <\\/div>\\r\\n    <\\/div>\\r\\n<\\/div>\\r\\n\\r\\n$table_here\\r\\n\\r\\n   <div class=\\\"px-4 mt-4\\\" style=\\\"width: 100%\\\">\\r\\n    <div class=\\\"inline-block\\\" style=\\\"width: 70%\\\">\\r\\n        $entity.public_notes\\r\\n    <\\/div>\\r\\n    <div class=\\\"inline-block\\\" style=\\\"width: 20%\\\">\\r\\n        <div class=\\\"col-6 text-left\\\">\\r\\n            <span style=\\\"margin-right: 20px\\\">$subtotal_label<\\/span> $subtotal<br>\\r\\n            <span style=\\\"margin-right: 20px\\\">$discount_label<\\/span> $discount<br>\\r\\n            <span style=\\\"margin-right: 20px\\\">$tax_label<\\/span> $tax<br>\\r\\n            <span class=\\\"font-weight-bold\\\" style=\\\"margin-right: 20px\\\">$balance_due_label<\\/span> \\r\\n            <span class=\\\"text-info\\\"> $balance_due<\\/span><br>\\r\\n        <\\/div>\\r\\n    <\\/div>\\r\\n<\\/div>\\r\\n<div class=\\\"px-4 mt-4\\\" style=\\\"width: 100%\\\">\\r\\n    <div class=\\\"inline-block\\\" style=\\\"width: 70%\\\">\\r\\n        <p class=\\\"font-weight-bold\\\">$terms_label<\\/p>\\r\\n        $terms\\r\\n    <\\/div>\\r\\n<\\/div>\",\"table\":\"<table class=\\\"w-100 table-auto mt-4\\\">\\r\\n    <thead class=\\\"text-left\\\">\\r\\n        $product_table_header\\r\\n    <\\/thead>\\r\\n    <tbody>\\r\\n        $product_table_body\\r\\n    <\\/tbody>\\r\\n<\\/table>\",\"task_table\":\"<table class=\\\"w-100 table-auto mt-4\\\">\\r\\n    <thead class=\\\"text-left\\\">\\r\\n        $task_table_header\\r\\n    <\\/thead>\\r\\n    <tbody>\\r\\n        $task_table_body\\r\\n    <\\/tbody>\\r\\n<\\/table>\",\"product\":\"\",\"task\":\"\",\"footer\":\"\\r\\n        <div class=\\\"text-center mb-2\\\">\\r\\n               $signature_here\\r\\n           <\\/div>\\r\\n        \\r\\n        <div class=\\\"footer_class py-4 px-4\\\" style=\\\"page-break-inside: avoid;\\\">\\r\\n        <\\/div>\"}', '2020-05-03 17:47:42', '2020-05-03 17:48:50', '0000-00-00 00:00:00', 0),
(6, NULL, NULL, 'Jazzy', 0, 1, '{\"header\":\"<div class=\\\"px-4 py-4\\\">\\r\\n<div class=\\\"mt-4 mb-4\\\" style=\\\"width: 100%\\\">\\r\\n    <div class=\\\"inline-block border-left pl-2 border-dark mr-4 mt-4\\\" style=\\\"width: 30%\\\">\\r\\n        <p class=\\\"font-weight-bold text-uppercase text-info\\\">From:<\\/p>\\r\\n        <div>\\r\\n            <div class=\\\"mr-5\\\">\\r\\n                $account_details\\r\\n            <\\/div>\\r\\n            <div>\\r\\n                $account_address\\r\\n            <\\/div>\\r\\n        <\\/div>\\r\\n    <\\/div>\\r\\n    <div class=\\\"border-left pl-4 border-dark inline-block\\\" style=\\\"width: 30%\\\">\\r\\n        <p class=\\\"font-weight-bold text-uppercase text-info\\\">To:<\\/p>\\r\\n        $customer_details\\r\\n    <\\/div>\\r\\n    <div class=\\\"inline-block mt-4 h-16\\\" style=\\\"width: 30%\\\">\\r\\n        $account_logo\\r\\n    <\\/div>\\r\\n<\\/div>\",\"body\":\"<div class=\\\"mx-4 mt-4\\\">\\r\\n<h1 class=\\\"font-weight-bold text-uppercase\\\">$entity_label<\\/h1>\\r\\n<div class=\\\"mt-1\\\">\\r\\n    <span class=\\\"font-weight-bold text-uppercase text-info\\\">$entity_number<\\/span>\\r\\n    <div class=\\\"inline-block ml-4\\\">\\r\\n        <span class=\\\"text-uppercase\\\">$date_label<\\/span>\\r\\n        <span>$date<\\/span>\\r\\n    <\\/div>\\r\\n    <div class=\\\"inline-block ml-4\\\">\\r\\n        <span class=\\\"text-uppercase\\\">$due_date_label<\\/span>\\r\\n        <span>$due_date<\\/span>\\r\\n    <\\/div>\\r\\n    <div class=\\\"inline-block ml-4\\\">\\r\\n        <span class=\\\"text-uppercase\\\">$balance_due_label<\\/span>\\r\\n        <span class=\\\"text-info\\\">$balance_due<\\/span>\\r\\n    <\\/div>\\r\\n<\\/div>\\r\\n<\\/div>\\r\\n\\r\\n$table_here\\r\\n\\r\\n<div class=\\\"mt-4\\\">\\r\\n<div class=\\\"inline-block\\\" style=\\\"width: 70%\\\">\\r\\n    <div>\\r\\n        <p>$entity.public_notes<\\/p>\\r\\n        <div class=\\\"pt-4\\\">\\r\\n            <p class=\\\"font-weight-bold\\\">$terms_label<\\/p>\\r\\n            <p>$terms<\\/p>\\r\\n        <\\/div>\\r\\n    <\\/div>\\r\\n<\\/div>\\r\\n<div class=\\\"inline-block\\\" style=\\\"width: 20%\\\">\\r\\n        <div class=\\\"col-6 text-left\\\">\\r\\n            <span style=\\\"margin-right: 60px\\\">$discount_label<\\/span> $discount<br>\\r\\n            <span style=\\\"margin-right: 60px\\\">$tax_label<\\/span> $tax<br>\\r\\n             <span style=\\\"margin-right: 60px\\\">$balance_due_label<\\/span> $balance_due<br>\\r\\n        <\\/div>\\r\\n<\\/div>\\r\\n<\\/div>\\r\\n<\\/div>\\r\\n\",\"table\":\"<table class=\\\"mt-4 w-100 table-auto\\\">\\r\\n<thead class=\\\"text-left\\\">\\r\\n    $product_table_header\\r\\n<\\/thead>\\r\\n<tbody>\\r\\n    $product_table_body\\r\\n<\\/tbody>\\r\\n<\\/table>\",\"task_table\":\"<table class=\\\"w-100 table-auto mt-4\\\">\\r\\n<thead class=\\\"text-left\\\">\\r\\n    $task_table_header\\r\\n<\\/thead>\\r\\n<tbody>\\r\\n    $task_table_body\\r\\n<\\/tbody>\\r\\n<\\/table>\",\"product\":\"\",\"task\":\"\",\"footer\":\"\\r\\n        <div class=\\\"text-center mb-2\\\">\\r\\n               $signature_here\\r\\n           <\\/div>\\r\\n        \\r\\n        <div class=\\\"footer_class py-4 px-4\\\" style=\\\"page-break-inside: avoid;\\\">\\r\\n\\r\\n      <\\/div>\\r\\n<\\/body>\\r\\n<\\/html>\"}', '2020-05-03 17:47:42', '2020-05-03 17:48:50', '0000-00-00 00:00:00', 0),
(7, NULL, NULL, 'Picture', 0, 1, '{\"header\":\"<div class=\\\"px-4 py-4\\\">\\r\\n<div class=\\\"mt-4\\\">\\r\\n    <div class=\\\"inline-block\\\" ref=\\\"logo\\\" style=\\\"width: 50%\\\">\\r\\n        $account_logo\\r\\n    <\\/div>\\r\\n    <div class=\\\"inline-block\\\" style=\\\"width: 40%\\\">\\r\\n        <div class=\\\"inline-block-col mr-4\\\">\\r\\n            $entity_labels\\r\\n        <\\/div>\\r\\n        <div class=\\\"inline-block text-right\\\">\\r\\n            $entity_details\\r\\n        <\\/div>\\r\\n    <\\/div>\\r\\n<\\/div>\\r\\n<\\/div>\",\"body\":\"<div class=\\\"bg-secondary h-auto p-4 pt-4\\\">\\r\\n<div>\\r\\n    <div class=\\\"inline-block mr-4\\\" style=\\\"width: 40%\\\">\\r\\n        <p class=\\\"text-uppercase text-warning\\\">$to_label:<\\/p>\\r\\n        <div class=\\\"ml-4 mt-4 text-white\\\">\\r\\n            $customer_details\\r\\n        <\\/div>\\r\\n    <\\/div>\\r\\n    <div class=\\\"inline-block\\\" style=\\\"width: 30%\\\">\\r\\n        <p class=\\\"text-uppercase text-warning\\\">$from_label:<\\/p>\\r\\n        <div class=\\\"ml-4 text-white\\\">\\r\\n            $account_details\\r\\n        <\\/div>\\r\\n    <\\/div>\\r\\n<\\/div>\\r\\n<\\/div>\\r\\n\\r\\n$table_here\\r\\n\\r\\n<div class=\\\"mt-2 px-4 pb-4\\\">\\r\\n<div class=\\\"inline-block mt-4\\\" style=\\\"width: 70%\\\">\\r\\n    <div>\\r\\n        <p>$entity.public_notes<\\/p>\\r\\n    <\\/div>\\r\\n<\\/div>\\r\\n<div class=\\\"inline-block\\\" style=\\\"width: 20%\\\">\\r\\n    <div class=\\\"px-3 mt-2\\\">\\r\\n        <div class=\\\"inline-block col-6 text-right\\\">\\r\\n            <span style=\\\"margin-right: 20px\\\">$discount_label<\\/span> $discount<br>\\r\\n            <span style=\\\"margin-right: 20px\\\">$tax_label<\\/span> $tax<br>\\r\\n        <\\/div>\\r\\n    <\\/div>\\r\\n<\\/div>\\r\\n<\\/div>\\r\\n<div class=\\\"mt-4 pb-4 px-4\\\">\\r\\n<div class=\\\"inline-block\\\" style=\\\"width: 70%\\\">\\r\\n    <div>\\r\\n        <p class=\\\"font-weight-bold\\\">$terms_label<\\/p>\\r\\n        <p>$terms<\\/p>\\r\\n    <\\/div>\\r\\n<\\/div>\\r\\n<div class=\\\"inline-block\\\" style=\\\"width: 20%\\\">\\r\\n    <section class=\\\"bg-warning py-2 pt-4 pr-4 text-white px-2 mt-1\\\">\\r\\n        <p class=\\\"text-right\\\">$balance_due_label<\\/p>\\r\\n        <p class=\\\"text-right\\\">$balance_due<\\/p>\\r\\n    <\\/section>\\r\\n<\\/div>\\r\\n<\\/div>\\r\\n<\\/div>\\r\\n<\\/div>\",\"table\":\"<table class=\\\"w-100 table-auto\\\">\\r\\n<thead class=\\\"text-left border-bottom-4 border-dark\\\">\\r\\n    $product_table_header\\r\\n<\\/thead>\\r\\n<tbody>\\r\\n    $product_table_body\\r\\n<\\/tbody>\\r\\n<\\/table>\",\"task_table\":\"\\r\\n<table class=\\\"w-100 table-auto\\\">\\r\\n<thead class=\\\"text-left border-bottom-4 border-dark\\\">\\r\\n    $task_table_header\\r\\n<\\/thead>\\r\\n<tbody>\\r\\n    $task_table_body\\r\\n<\\/tbody>\\r\\n<\\/table>\",\"product\":\"\",\"task\":\"\",\"footer\":\"\\r\\n        <div class=\\\"text-center mb-2\\\">\\r\\n               $signature_here\\r\\n           <\\/div>\\r\\n        \\r\\n        <div class=\\\"footer_class py-4 px-4\\\" style=\\\"page-break-inside: avoid;\\\">\\r\\n        <\\/div>\\r\\n<\\/footer>\\r\\n<\\/body>\\r\\n<\\/html>\"}', '2020-05-03 17:47:42', '2020-05-03 17:48:50', '0000-00-00 00:00:00', 0),
(8, NULL, NULL, 'Secondary', 0, 1, '{\"header\":\"<div class=\\\"my-4 mx-4\\\">\\r\\n<div>\\r\\n    <div class=\\\"inline-block\\\" style=\\\"width: 10%\\\">\\r\\n        $account_logo\\r\\n    <\\/div>\\r\\n    <div class=\\\"ml-4 inline-block\\\" style=\\\"width: 80%\\\">\\r\\n        <div class=\\\"text-secondary\\\">\\r\\n            $account_details\\r\\n        <\\/div>\\r\\n        <div class=\\\"text-secondary ml-4\\\">\\r\\n            $account_address\\r\\n        <\\/div>\\r\\n    <\\/div>\\r\\n<\\/div>\",\"body\":\"<div class=\\\"mt-4\\\">\\r\\n    <div class=\\\"inline-block\\\" style=\\\"width: 40%\\\">\\r\\n        <span>$entity_label<\\/span>\\r\\n        <section class=\\\"text-warning mt-4\\\">\\r\\n            $customer_details\\r\\n        <\\/section>\\r\\n    <\\/div>\\r\\n    <div class=\\\"inline-block col-6 ml-4 bg-warning px-4 py-4 rounded\\\" style=\\\"width: 40%;\\\">\\r\\n        <div class=\\\"text-white\\\">\\r\\n            <section class=\\\"col-6\\\">\\r\\n                $entity_labels\\r\\n            <\\/section>\\r\\n            <section class=\\\"\\\">\\r\\n                $entity_details\\r\\n            <\\/section>\\r\\n        <\\/div>\\r\\n    <\\/div>\\r\\n<\\/div>\\r\\n\\r\\n$table_here\\r\\n\\r\\n<div class=\\\"mt-4 px-4 pt-4 pb-4 bg-secondary rounded py-2 text-white\\\" style=\\\"width: 100%\\\">\\r\\n    <div class=\\\"inline-block\\\" style=\\\"width: 70%\\\">\\r\\n        <div>\\r\\n            <p>$entity.public_notes<\\/p>\\r\\n        <\\/div>\\r\\n    <\\/div>\\r\\n    <div class=\\\"inline-block px-3 mt-1\\\" style=\\\"width: 20%\\\">\\r\\n            <div class=\\\"col-6 text-left\\\">\\r\\n                <span style=\\\"margin-right: 40px\\\">$discount_label <\\/span>$discount <br>\\r\\n                <span style=\\\"margin-right: 40px\\\">$tax_label <\\/span>$tax <br>\\r\\n                <span style=\\\"margin-right: 40px\\\">$balance_due_label <\\/span>$balance_due <br>\\r\\n            <\\/div>\\r\\n    <\\/div>\\r\\n<\\/div>\\r\\n<div class=\\\"mt-4 pb-4 px-4\\\" style=\\\"width: 100%\\\">\\r\\n<div class=\\\"inline-block\\\" style=\\\"width: 70%\\\">\\r\\n    <div class=\\\"\\\">\\r\\n        <p class=\\\"font-weight-bold\\\">$terms_label<\\/p>\\r\\n        <p>$terms<\\/p>\\r\\n    <\\/div>\\r\\n<\\/div>\\r\\n<\\/div>\\r\\n<\\/div>\",\"table\":\"\\r\\n        <table class=\\\"w-100 table-auto mt-4\\\">\\r\\n    <thead class=\\\"text-left\\\">\\r\\n        $product_table_header\\r\\n    <\\/thead>\\r\\n    <tbody>\\r\\n        $product_table_body\\r\\n    <\\/tbody>\\r\\n<\\/table>\",\"task_table\":\"\\r\\n<table class=\\\"w-100 table-auto mt-4\\\">\\r\\n    <thead class=\\\"text-left\\\">\\r\\n        $task_table_header\\r\\n    <\\/thead>\\r\\n    <tbody>\\r\\n        $task_table_body\\r\\n    <\\/tbody>\\r\\n<\\/table>\",\"product\":\"\",\"task\":\"\",\"footer\":\"\\r\\n        <div class=\\\"text-center mb-2\\\">\\r\\n               $signature_here\\r\\n           <\\/div>\\r\\n        \\r\\n        <div class=\\\"footer_class py-4 px-4\\\" style=\\\"page-break-inside: avoid;\\\">\\r\\n\\r\\n        <\\/div>\\r\\n<\\/body>\\r\\n<\\/html>\"}', '2020-05-03 17:47:42', '2020-05-03 17:48:51', '0000-00-00 00:00:00', 0),
(9, NULL, NULL, 'Simple', 0, 1, '{\"header\":\"<div class=\\\"px-3 my-4\\\">\\r\\n<div class=\\\"\\\">\\r\\n    <div class=\\\"inline-block mt-2\\\">\\r\\n        <div class=\\\"\\\">$account_logo<\\/div>\\r\\n    <\\/div>\\r\\n    <div class=\\\"inline-block\\\">\\r\\n        <div class=\\\"mr-4 text-secondary\\\">\\r\\n            $account_details\\r\\n        <\\/div>\\r\\n        <div class=\\\"ml-5 text-secondary\\\">\\r\\n            $account_address\\r\\n        <\\/div>\\r\\n    <\\/div>\\r\\n<\\/div>\",\"body\":\"<h1 class=\\\"mt-4 text-uppercase text-primary ml-4\\\">\\r\\n    $entity_label\\r\\n<\\/h1>\\r\\n<div class=\\\"border-bottom border-secondary\\\"><\\/div>\\r\\n<div class=\\\"ml-4 py-4\\\">\\r\\n    <div class=\\\"\\\">\\r\\n        <div>\\r\\n            $entity_labels\\r\\n        <\\/div>\\r\\n        <div style=\\\"width: 60%\\\" class=\\\"inline-block\\\">\\r\\n            $entity_details\\r\\n        <\\/div>\\r\\n        <div class=\\\"inline-block mt-4\\\">\\r\\n            $customer_details\\r\\n        <\\/div>\\r\\n    <\\/div>\\r\\n<\\/div>\\r\\n<div class=\\\"border-bottom border-secondary\\\"><\\/div>\\r\\n\\r\\n$table_here\\r\\n\\r\\n<div class=\\\"px-4 mt-4\\\" style=\\\"width: 100%\\\">\\r\\n    <div class=\\\"inline-block\\\" style=\\\"width: 80%\\\">\\r\\n        $entity.public_notes\\r\\n    <\\/div>\\r\\n    <div class=\\\"inline-block\\\" style=\\\"width: 20% !important;\\\">\\r\\n        <div class=\\\"inline-block col-6 text-left\\\">\\r\\n            <span style=\\\"margin-right: 20px\\\"> $discount_label <\\/span> $discount <br>\\r\\n            <span style=\\\"margin-right: 20px\\\"> $tax_label <\\/span> $tax\\r\\n        <\\/div>\\r\\n    <\\/div>\\r\\n<\\/div>\\r\\n    <div class=\\\"px-4 mt-4\\\" style=\\\"width: 100%\\\">\\r\\n        <div class=\\\"inline-block\\\" style=\\\"width: 80%\\\">\\r\\n            <p class=\\\"font-weight-bold\\\">$terms_label<\\/p>\\r\\n            $terms\\r\\n        <\\/div>\\r\\n        <div class=\\\"inline-block\\\" style=\\\"width: 20%\\\">\\r\\n            <div class=\\\"text-left col-6\\\">\\r\\n                <span style=\\\"margin-right: 20px\\\">$balance_due_label<\\/span> <span class=\\\"text-primary\\\">$balance_due<\\/span>\\r\\n            <\\/div>\\r\\n        <\\/div>\\r\\n    <\\/div>\\r\\n<\\/div>\",\"table\":\"\\r\\n        <table class=\\\"w-100 col-6 table-auto mt-4\\\">\\r\\n    <thead class=\\\"text-left\\\">\\r\\n        $product_table_header\\r\\n    <\\/thead>\\r\\n    <tbody>\\r\\n        $product_table_body\\r\\n    <\\/tbody>\\r\\n<\\/table>\",\"task_table\":\"<table class=\\\"w-100 table-auto mt-4\\\">\\r\\n    <thead class=\\\"text-left\\\">\\r\\n        $task_table_header\\r\\n    <\\/thead>\\r\\n    <tbody>\\r\\n        $task_table_body\\r\\n    <\\/tbody>\\r\\n<\\/table>\",\"product\":\"\",\"task\":\"\",\"footer\":\"\\r\\n        <div class=\\\"text-center mb-2\\\">\\r\\n               $signature_here\\r\\n           <\\/div>\\r\\n        \\r\\n        <div class=\\\"footer_class py-4 px-4\\\" style=\\\"page-break-inside: avoid;\\\">\\r\\n\\r\\n        <\\/div>\\r\\n<\\/footer>\\r\\n<\\/body>\\r\\n<\\/html>\"}', '2020-05-03 17:47:42', '2020-05-03 17:48:51', '0000-00-00 00:00:00', 0),
(11, NULL, 1, 'Warning', 0, 1, '{\"header\":\"<div class=\\\"header_class bg-warning\\\" style=\\\"page-break-inside: avoid;\\\">\\r\\n<div class=\\\"inline-block ml-3\\\" style=\\\"width: 50%\\\">\\r\\n\\t<h1 class=\\\"text-white font-weight-bold\\\">$account.name<\\/h1>\\r\\n<\\/div>\\r\\n<div class=\\\"inline-block mt-3 mb-3\\\" style=\\\"width: 40%\\\">\\r\\n\\t<div class=\\\"inline-block text-white\\\">\\r\\n\\t\\t$entity_labels\\r\\n\\t<\\/div>\\r\\n\\t<div class=\\\"inline-block text-left text-white\\\">\\r\\n\\t\\t$entity_details\\r\\n\\t<\\/div>\\r\\n<\\/div>\\r\\n<\\/div>\",\"body\":\"<table class=\\\"container\\\"><thead><tr><td><div class=\\\"header-space\\\"><\\/div><\\/td><\\/tr><\\/thead>\\r\\n<tbody><tr><td>\\r\\n<div class=\\\"px-4 pt-4\\\" style=\\\"width: 100%\\\">\\r\\n    <div class=\\\"inline-block p-3\\\" style=\\\"width: 50%\\\">\\r\\n\\t\\t$account_logo\\r\\n\\t<\\/div>\\r\\n    <div class=\\\"inline-block\\\" style=\\\"width: 40%\\\">\\r\\n\\t\\t$customer_details\\r\\n    <\\/div>\\r\\n<\\/div>\\r\\n<div class=\\\"px-4 pt-4 pb-4\\\">\\r\\n\\r\\n$table_here\\r\\n\\r\\n<div class=\\\"px-4 mt-4 w-100\\\" style=\\\"page-break-inside: avoid;\\\">\\r\\n\\t\\t\\t        <div class=\\\"inline-block\\\" style=\\\"width: 70%\\\">\\r\\n\\t\\t\\t            $entity.public_notes\\r\\n\\t\\t\\t        <\\/div>\\r\\n\\t\\t\\t        <div class=\\\"inline-block\\\" style=\\\"page-break-inside: avoid; width: 20%\\\">\\r\\n\\t\\t\\t            <div class=\\\"inline-block col-6 text-left\\\" style=\\\"page-break-inside: avoid;\\\">\\r\\n\\t\\t\\t            \\t<span style=\\\"margin-right: 20px\\\">$discount_label<\\/span> $discount <br>\\r\\n\\t\\t\\t                <span style=\\\"margin-right: 20px\\\">$tax_label<\\/span> $tax <br>\\r\\n\\t\\t\\t            <\\/div>\\r\\n\\t\\t\\t        <\\/div>\\r\\n\\t\\t\\t    <\\/div>\\r\\n\\t\\t\\t    <div class=\\\"px-4 mt-4 mt-4\\\" style=\\\"page-break-inside: avoid; width: 100%\\\">\\r\\n\\t\\t\\t        <div style=\\\"page-break-inside: avoid; width: 70%\\\">\\r\\n\\t\\t\\t            <p class=\\\"font-weight-bold\\\">$terms_label<\\/p>\\r\\n\\t\\t\\t            $terms\\r\\n\\t\\t\\t        <\\/div>\\r\\n\\t\\t\\t    <\\/div>\\r\\n\\t\\t\\t    <div class=\\\"mt-4 px-4 py-2 bg-secondary text-white\\\" style=\\\"page-break-inside: avoid; width: 100%\\\">\\r\\n\\t\\t\\t        <div class=\\\"inline-block\\\" style=\\\"width: 70%\\\"><\\/div>\\r\\n\\t\\t\\t        <div class=\\\"\\\" style=\\\"page-break-inside: avoid; width: 20%\\\" >\\r\\n\\t\\t\\t            <div style=\\\"page-break-inside: avoid;\\\">\\r\\n\\t\\t\\t                <p class=\\\"font-weight-bold\\\">$balance_due_label<\\/p>\\r\\n\\t\\t\\t            <\\/div>\\r\\n\\t\\t\\t            <p>$balance_due<\\/p>\\r\\n\\t\\t\\t        <\\/div>\\r\\n\\t\\t\\t    <\\/div>\\r\\n<\\/div>\\r\\n<\\/td><\\/tr><\\/tbody><tfoot><tr><td><div class=\\\"footer-space\\\"><\\/div><\\/td><\\/tr><\\/tfoot><\\/table>\\r\\n\",\"table\":\"<table class=\\\"w-100 table-auto mt-4\\\">\\r\\n    <thead class=\\\"text-left text-white bg-secondary\\\">\\r\\n       $product_table_header\\r\\n    <\\/thead>\\r\\n    <tbody>\\r\\n            $product_table_body\\r\\n    <\\/tbody>\\r\\n<\\/table>\",\"task_table\":\"\\r\\n<table class=\\\"w-100 table-auto mt-4\\\">\\r\\n    <thead class=\\\"text-left text-white bg-secondary\\\">\\r\\n        $task_table_header\\r\\n    <\\/thead>\\r\\n    <tbody>\\r\\n            $task_table_body\\r\\n    <\\/tbody>\\r\\n<\\/table>\",\"product\":\"\",\"task\":\"\",\"footer\":\"\\r\\n\\t\\t<div class=\\\"text-center mb-2\\\">\\r\\n               $signature_here\\r\\n           <\\/div>\\r\\n\\t\\t\\r\\n\\t\\t<div class=\\\"footer_class bg-warning py-4 px-4 pt-4\\\" style=\\\"page-break-inside: avoid; width: 100%\\\"> \\r\\n\\r\\n             <div class=\\\"inline-block\\\" style=\\\"width: 10%\\\">\\r\\n\\t\\t\\t        <!-- \\/\\/ -->\\r\\n\\t\\t\\t    <\\/div>\\r\\n\\t\\t\\t    <div class=\\\"inline-block mt-2\\\" style=\\\"width: 70%\\\">\\r\\n\\t\\t\\t        <div class=\\\"inline-block text-white\\\" style=\\\"width: 40%\\\">\\r\\n\\t\\t\\t            $account_details\\r\\n\\t\\t\\t        <\\/div>\\r\\n\\t\\t\\t        <div class=\\\"inline-block text-left text-white\\\" style=\\\"width: 40%\\\">\\r\\n\\t\\t\\t            $account_address\\r\\n\\t\\t\\t        <\\/div>\\r\\n\\t\\t\\t    <\\/div>\\r\\n\\t\\t\\t<\\/div>\\r\\n               \\r\\n\\t\\t\\r\\n\\t\\t\\t<\\/html>\\r\\n\\t\\t\"}', '2020-05-03 17:47:42', '2020-05-03 17:48:51', '0000-00-00 00:00:00', 0),
(12, NULL, 1, 'Simple', 0, 1, '{\"header\":\"<div class=\\\"px-3 my-4\\\">\\r\\n<div class=\\\"\\\">\\r\\n    <div class=\\\"inline-block mt-2\\\">\\r\\n        <div class=\\\"\\\">$account_logo<\\/div>\\r\\n    <\\/div>\\r\\n    <div class=\\\"inline-block\\\">\\r\\n        <div class=\\\"mr-4 text-secondary\\\">\\r\\n            $account_details\\r\\n        <\\/div>\\r\\n        <div class=\\\"ml-5 text-secondary\\\">\\r\\n            $account_address\\r\\n        <\\/div>\\r\\n    <\\/div>\\r\\n<\\/div>\",\"body\":\"<h1 class=\\\"mt-4 text-uppercase text-primary ml-4\\\">\\r\\n    $entity_label\\r\\n<\\/h1>\\r\\n<div class=\\\"border-bottom border-secondary\\\"><\\/div>\\r\\n<div class=\\\"ml-4 py-4\\\">\\r\\n    <div class=\\\"\\\">\\r\\n        <div>\\r\\n            $entity_labels\\r\\n        <\\/div>\\r\\n        <div style=\\\"width: 60%\\\" class=\\\"inline-block\\\">\\r\\n            $entity_details\\r\\n        <\\/div>\\r\\n        <div class=\\\"inline-block mt-4\\\">\\r\\n            $customer_details\\r\\n        <\\/div>\\r\\n    <\\/div>\\r\\n<\\/div>\\r\\n<div class=\\\"border-bottom border-secondary\\\"><\\/div>\\r\\n\\r\\n$table_here\\r\\n\\r\\n<div class=\\\"px-4 mt-4\\\" style=\\\"width: 100%\\\">\\r\\n    <div class=\\\"inline-block\\\" style=\\\"width: 80%\\\">\\r\\n        $entity.public_notes\\r\\n    <\\/div>\\r\\n    <div class=\\\"inline-block\\\" style=\\\"width: 20% !important;\\\">\\r\\n        <div class=\\\"inline-block col-6 text-left\\\">\\r\\n            <span style=\\\"margin-right: 20px\\\"> $discount_label <\\/span> $discount <br>\\r\\n            <span style=\\\"margin-right: 20px\\\"> $tax_label <\\/span> $tax\\r\\n        <\\/div>\\r\\n    <\\/div>\\r\\n<\\/div>\\r\\n    <div class=\\\"px-4 mt-4\\\" style=\\\"width: 100%\\\">\\r\\n        <div class=\\\"inline-block\\\" style=\\\"width: 80%\\\">\\r\\n            <p class=\\\"font-weight-bold\\\">$terms_label<\\/p>\\r\\n            $terms\\r\\n        <\\/div>\\r\\n        <div class=\\\"inline-block\\\" style=\\\"width: 20%\\\">\\r\\n            <div class=\\\"text-left col-6\\\">\\r\\n                <span style=\\\"margin-right: 20px\\\">$balance_due_label<\\/span> <span class=\\\"text-primary\\\">$balance_due<\\/span>\\r\\n            <\\/div>\\r\\n        <\\/div>\\r\\n    <\\/div>\\r\\n<\\/div>\",\"table\":\"\\r\\n        <table class=\\\"w-100 col-6 table-auto mt-4\\\">\\r\\n    <thead class=\\\"text-left\\\">\\r\\n        $product_table_header\\r\\n    <\\/thead>\\r\\n    <tbody>\\r\\n        $product_table_body\\r\\n    <\\/tbody>\\r\\n<\\/table>\",\"task_table\":\"<table class=\\\"w-100 table-auto mt-4\\\">\\r\\n    <thead class=\\\"text-left\\\">\\r\\n        $task_table_header\\r\\n    <\\/thead>\\r\\n    <tbody>\\r\\n        $task_table_body\\r\\n    <\\/tbody>\\r\\n<\\/table>\",\"product\":\"\",\"task\":\"\",\"footer\":\"\\r\\n        <div class=\\\"text-center mb-2\\\">\\r\\n               $signature_here\\r\\n           <\\/div>\\r\\n        \\r\\n        <div class=\\\"footer_class py-4 px-4\\\" style=\\\"page-break-inside: avoid;\\\">\\r\\n\\r\\n        <\\/div>\\r\\n<\\/footer>\\r\\n<\\/body>\\r\\n<\\/html>\"}', '2020-05-03 17:47:42', '2020-05-03 17:48:51', '0000-00-00 00:00:00', 0),
(13, NULL, 1, 'Warning', 0, 1, '{\"header\":\"<div class=\\\"header_class bg-warning\\\" style=\\\"page-break-inside: avoid;\\\">\\r\\n<div class=\\\"inline-block ml-3\\\" style=\\\"width: 50%\\\">\\r\\n\\t<h1 class=\\\"text-white font-weight-bold\\\">$account.name<\\/h1>\\r\\n<\\/div>\\r\\n<div class=\\\"inline-block mt-3 mb-3\\\" style=\\\"width: 40%\\\">\\r\\n\\t<div class=\\\"inline-block text-white\\\">\\r\\n\\t\\t$entity_labels\\r\\n\\t<\\/div>\\r\\n\\t<div class=\\\"inline-block text-left text-white\\\">\\r\\n\\t\\t$entity_details\\r\\n\\t<\\/div>\\r\\n<\\/div>\\r\\n<\\/div>\",\"body\":\"<table class=\\\"container\\\"><thead><tr><td><div class=\\\"header-space\\\"><\\/div><\\/td><\\/tr><\\/thead>\\r\\n<tbody><tr><td>\\r\\n<div class=\\\"px-4 pt-4\\\" style=\\\"width: 100%\\\">\\r\\n    <div class=\\\"inline-block p-3\\\" style=\\\"width: 50%\\\">\\r\\n\\t\\t$account_logo\\r\\n\\t<\\/div>\\r\\n    <div class=\\\"inline-block\\\" style=\\\"width: 40%\\\">\\r\\n\\t\\t$customer_details\\r\\n    <\\/div>\\r\\n<\\/div>\\r\\n<div class=\\\"px-4 pt-4 pb-4\\\">\\r\\n\\r\\n$table_here\\r\\n\\r\\n<div class=\\\"px-4 mt-4 w-100\\\" style=\\\"page-break-inside: avoid;\\\">\\r\\n\\t\\t\\t        <div class=\\\"inline-block\\\" style=\\\"width: 70%\\\">\\r\\n\\t\\t\\t            $entity.public_notes\\r\\n\\t\\t\\t        <\\/div>\\r\\n\\t\\t\\t        <div class=\\\"inline-block\\\" style=\\\"page-break-inside: avoid; width: 20%\\\">\\r\\n\\t\\t\\t            <div class=\\\"inline-block col-6 text-left\\\" style=\\\"page-break-inside: avoid;\\\">\\r\\n\\t\\t\\t            \\t<span style=\\\"margin-right: 20px\\\">$discount_label<\\/span> $discount <br>\\r\\n\\t\\t\\t                <span style=\\\"margin-right: 20px\\\">$tax_label<\\/span> $tax <br>\\r\\n\\t\\t\\t            <\\/div>\\r\\n\\t\\t\\t        <\\/div>\\r\\n\\t\\t\\t    <\\/div>\\r\\n\\t\\t\\t    <div class=\\\"px-4 mt-4 mt-4\\\" style=\\\"page-break-inside: avoid; width: 100%\\\">\\r\\n\\t\\t\\t        <div style=\\\"page-break-inside: avoid; width: 70%\\\">\\r\\n\\t\\t\\t            <p class=\\\"font-weight-bold\\\">$terms_label<\\/p>\\r\\n\\t\\t\\t            $terms\\r\\n\\t\\t\\t        <\\/div>\\r\\n\\t\\t\\t    <\\/div>\\r\\n\\t\\t\\t    <div class=\\\"mt-4 px-4 py-2 bg-secondary text-white\\\" style=\\\"page-break-inside: avoid; width: 100%\\\">\\r\\n\\t\\t\\t        <div class=\\\"inline-block\\\" style=\\\"width: 70%\\\"><\\/div>\\r\\n\\t\\t\\t        <div class=\\\"\\\" style=\\\"page-break-inside: avoid; width: 20%\\\" >\\r\\n\\t\\t\\t            <div style=\\\"page-break-inside: avoid;\\\">\\r\\n\\t\\t\\t                <p class=\\\"font-weight-bold\\\">$balance_due_label<\\/p>\\r\\n\\t\\t\\t            <\\/div>\\r\\n\\t\\t\\t            <p>$balance_due<\\/p>\\r\\n\\t\\t\\t        <\\/div>\\r\\n\\t\\t\\t    <\\/div>\\r\\n<\\/div>\\r\\n<\\/td><\\/tr><\\/tbody><tfoot><tr><td><div class=\\\"footer-space\\\"><\\/div><\\/td><\\/tr><\\/tfoot><\\/table>\\r\\n\",\"table\":\"<table class=\\\"w-100 table-auto mt-4\\\">\\r\\n    <thead class=\\\"text-left text-white bg-secondary\\\">\\r\\n       $product_table_header\\r\\n    <\\/thead>\\r\\n    <tbody>\\r\\n            $product_table_body\\r\\n    <\\/tbody>\\r\\n<\\/table>\",\"task_table\":\"\\r\\n<table class=\\\"w-100 table-auto mt-4\\\">\\r\\n    <thead class=\\\"text-left text-white bg-secondary\\\">\\r\\n        $task_table_header\\r\\n    <\\/thead>\\r\\n    <tbody>\\r\\n            $task_table_body\\r\\n    <\\/tbody>\\r\\n<\\/table>\",\"product\":\"\",\"task\":\"\",\"footer\":\"\\r\\n\\t\\t<div class=\\\"text-center mb-2\\\">\\r\\n               $signature_here\\r\\n           <\\/div>\\r\\n\\t\\t\\r\\n\\t\\t<div class=\\\"footer_class bg-warning py-4 px-4 pt-4\\\" style=\\\"page-break-inside: avoid; width: 100%\\\"> \\r\\n\\r\\n             <div class=\\\"inline-block\\\" style=\\\"width: 10%\\\">\\r\\n\\t\\t\\t        <!-- \\/\\/ -->\\r\\n\\t\\t\\t    <\\/div>\\r\\n\\t\\t\\t    <div class=\\\"inline-block mt-2\\\" style=\\\"width: 70%\\\">\\r\\n\\t\\t\\t        <div class=\\\"inline-block text-white\\\" style=\\\"width: 40%\\\">\\r\\n\\t\\t\\t            $account_details\\r\\n\\t\\t\\t        <\\/div>\\r\\n\\t\\t\\t        <div class=\\\"inline-block text-left text-white\\\" style=\\\"width: 40%\\\">\\r\\n\\t\\t\\t            $account_address\\r\\n\\t\\t\\t        <\\/div>\\r\\n\\t\\t\\t    <\\/div>\\r\\n\\t\\t\\t<\\/div>\\r\\n               \\r\\n\\t\\t\\r\\n\\t\\t\\t<\\/html>\\r\\n\\t\\t\"}', '2020-05-03 17:47:43', '2020-05-03 17:48:51', '0000-00-00 00:00:00', 0),
(14, NULL, 1, 'Secondary', 0, 1, '{\"header\":\"<div class=\\\"my-4 mx-4\\\">\\r\\n<div>\\r\\n    <div class=\\\"inline-block\\\" style=\\\"width: 10%\\\">\\r\\n        $account_logo\\r\\n    <\\/div>\\r\\n    <div class=\\\"ml-4 inline-block\\\" style=\\\"width: 80%\\\">\\r\\n        <div class=\\\"text-secondary\\\">\\r\\n            $account_details\\r\\n        <\\/div>\\r\\n        <div class=\\\"text-secondary ml-4\\\">\\r\\n            $account_address\\r\\n        <\\/div>\\r\\n    <\\/div>\\r\\n<\\/div>\",\"body\":\"<div class=\\\"mt-4\\\">\\r\\n    <div class=\\\"inline-block\\\" style=\\\"width: 40%\\\">\\r\\n        <span>$entity_label<\\/span>\\r\\n        <section class=\\\"text-warning mt-4\\\">\\r\\n            $customer_details\\r\\n        <\\/section>\\r\\n    <\\/div>\\r\\n    <div class=\\\"inline-block col-6 ml-4 bg-warning px-4 py-4 rounded\\\" style=\\\"width: 40%;\\\">\\r\\n        <div class=\\\"text-white\\\">\\r\\n            <section class=\\\"col-6\\\">\\r\\n                $entity_labels\\r\\n            <\\/section>\\r\\n            <section class=\\\"\\\">\\r\\n                $entity_details\\r\\n            <\\/section>\\r\\n        <\\/div>\\r\\n    <\\/div>\\r\\n<\\/div>\\r\\n\\r\\n$table_here\\r\\n\\r\\n<div class=\\\"mt-4 px-4 pt-4 pb-4 bg-secondary rounded py-2 text-white\\\" style=\\\"width: 100%\\\">\\r\\n    <div class=\\\"inline-block\\\" style=\\\"width: 70%\\\">\\r\\n        <div>\\r\\n            <p>$entity.public_notes<\\/p>\\r\\n        <\\/div>\\r\\n    <\\/div>\\r\\n    <div class=\\\"inline-block px-3 mt-1\\\" style=\\\"width: 20%\\\">\\r\\n            <div class=\\\"col-6 text-left\\\">\\r\\n                <span style=\\\"margin-right: 40px\\\">$discount_label <\\/span>$discount <br>\\r\\n                <span style=\\\"margin-right: 40px\\\">$tax_label <\\/span>$tax <br>\\r\\n                <span style=\\\"margin-right: 40px\\\">$balance_due_label <\\/span>$balance_due <br>\\r\\n            <\\/div>\\r\\n    <\\/div>\\r\\n<\\/div>\\r\\n<div class=\\\"mt-4 pb-4 px-4\\\" style=\\\"width: 100%\\\">\\r\\n<div class=\\\"inline-block\\\" style=\\\"width: 70%\\\">\\r\\n    <div class=\\\"\\\">\\r\\n        <p class=\\\"font-weight-bold\\\">$terms_label<\\/p>\\r\\n        <p>$terms<\\/p>\\r\\n    <\\/div>\\r\\n<\\/div>\\r\\n<\\/div>\\r\\n<\\/div>\",\"table\":\"\\r\\n        <table class=\\\"w-100 table-auto mt-4\\\">\\r\\n    <thead class=\\\"text-left\\\">\\r\\n        $product_table_header\\r\\n    <\\/thead>\\r\\n    <tbody>\\r\\n        $product_table_body\\r\\n    <\\/tbody>\\r\\n<\\/table>\",\"task_table\":\"\\r\\n<table class=\\\"w-100 table-auto mt-4\\\">\\r\\n    <thead class=\\\"text-left\\\">\\r\\n        $task_table_header\\r\\n    <\\/thead>\\r\\n    <tbody>\\r\\n        $task_table_body\\r\\n    <\\/tbody>\\r\\n<\\/table>\",\"product\":\"\",\"task\":\"\",\"footer\":\"\\r\\n        <div class=\\\"text-center mb-2\\\">\\r\\n               $signature_here\\r\\n           <\\/div>\\r\\n        \\r\\n        <div class=\\\"footer_class py-4 px-4\\\" style=\\\"page-break-inside: avoid;\\\">\\r\\n\\r\\n        <\\/div>\\r\\n<\\/body>\\r\\n<\\/html>\"}', '2020-05-03 17:47:43', '2020-05-03 17:48:51', '0000-00-00 00:00:00', 0),
(15, NULL, 1, 'Picture', 0, 1, '{\"header\":\"<div class=\\\"px-4 py-4\\\">\\r\\n<div class=\\\"mt-4\\\">\\r\\n    <div class=\\\"inline-block\\\" ref=\\\"logo\\\" style=\\\"width: 50%\\\">\\r\\n        $account_logo\\r\\n    <\\/div>\\r\\n    <div class=\\\"inline-block\\\" style=\\\"width: 40%\\\">\\r\\n        <div class=\\\"inline-block-col mr-4\\\">\\r\\n            $entity_labels\\r\\n        <\\/div>\\r\\n        <div class=\\\"inline-block text-right\\\">\\r\\n            $entity_details\\r\\n        <\\/div>\\r\\n    <\\/div>\\r\\n<\\/div>\\r\\n<\\/div>\",\"body\":\"<div class=\\\"bg-secondary h-auto p-4 pt-4\\\">\\r\\n<div>\\r\\n    <div class=\\\"inline-block mr-4\\\" style=\\\"width: 40%\\\">\\r\\n        <p class=\\\"text-uppercase text-warning\\\">$to_label:<\\/p>\\r\\n        <div class=\\\"ml-4 mt-4 text-white\\\">\\r\\n            $customer_details\\r\\n        <\\/div>\\r\\n    <\\/div>\\r\\n    <div class=\\\"inline-block\\\" style=\\\"width: 30%\\\">\\r\\n        <p class=\\\"text-uppercase text-warning\\\">$from_label:<\\/p>\\r\\n        <div class=\\\"ml-4 text-white\\\">\\r\\n            $account_details\\r\\n        <\\/div>\\r\\n    <\\/div>\\r\\n<\\/div>\\r\\n<\\/div>\\r\\n\\r\\n$table_here\\r\\n\\r\\n<div class=\\\"mt-2 px-4 pb-4\\\">\\r\\n<div class=\\\"inline-block mt-4\\\" style=\\\"width: 70%\\\">\\r\\n    <div>\\r\\n        <p>$entity.public_notes<\\/p>\\r\\n    <\\/div>\\r\\n<\\/div>\\r\\n<div class=\\\"inline-block\\\" style=\\\"width: 20%\\\">\\r\\n    <div class=\\\"px-3 mt-2\\\">\\r\\n        <div class=\\\"inline-block col-6 text-right\\\">\\r\\n            <span style=\\\"margin-right: 20px\\\">$discount_label<\\/span> $discount<br>\\r\\n            <span style=\\\"margin-right: 20px\\\">$tax_label<\\/span> $tax<br>\\r\\n        <\\/div>\\r\\n    <\\/div>\\r\\n<\\/div>\\r\\n<\\/div>\\r\\n<div class=\\\"mt-4 pb-4 px-4\\\">\\r\\n<div class=\\\"inline-block\\\" style=\\\"width: 70%\\\">\\r\\n    <div>\\r\\n        <p class=\\\"font-weight-bold\\\">$terms_label<\\/p>\\r\\n        <p>$terms<\\/p>\\r\\n    <\\/div>\\r\\n<\\/div>\\r\\n<div class=\\\"inline-block\\\" style=\\\"width: 20%\\\">\\r\\n    <section class=\\\"bg-warning py-2 pt-4 pr-4 text-white px-2 mt-1\\\">\\r\\n        <p class=\\\"text-right\\\">$balance_due_label<\\/p>\\r\\n        <p class=\\\"text-right\\\">$balance_due<\\/p>\\r\\n    <\\/section>\\r\\n<\\/div>\\r\\n<\\/div>\\r\\n<\\/div>\\r\\n<\\/div>\",\"table\":\"<table class=\\\"w-100 table-auto\\\">\\r\\n<thead class=\\\"text-left border-bottom-4 border-dark\\\">\\r\\n    $product_table_header\\r\\n<\\/thead>\\r\\n<tbody>\\r\\n    $product_table_body\\r\\n<\\/tbody>\\r\\n<\\/table>\",\"task_table\":\"\\r\\n<table class=\\\"w-100 table-auto\\\">\\r\\n<thead class=\\\"text-left border-bottom-4 border-dark\\\">\\r\\n    $task_table_header\\r\\n<\\/thead>\\r\\n<tbody>\\r\\n    $task_table_body\\r\\n<\\/tbody>\\r\\n<\\/table>\",\"product\":\"\",\"task\":\"\",\"footer\":\"\\r\\n        <div class=\\\"text-center mb-2\\\">\\r\\n               $signature_here\\r\\n           <\\/div>\\r\\n        \\r\\n        <div class=\\\"footer_class py-4 px-4\\\" style=\\\"page-break-inside: avoid;\\\">\\r\\n        <\\/div>\\r\\n<\\/footer>\\r\\n<\\/body>\\r\\n<\\/html>\"}', '2020-05-03 17:47:43', '2020-05-03 17:48:51', '0000-00-00 00:00:00', 0);
INSERT INTO `designs` (`id`, `user_id`, `account_id`, `name`, `is_custom`, `is_active`, `design`, `created_at`, `updated_at`, `deleted_at`, `is_deleted`) VALUES
(16, NULL, 1, 'Jazzy', 0, 1, '{\"header\":\"<div class=\\\"px-4 py-4\\\">\\r\\n<div class=\\\"mt-4 mb-4\\\" style=\\\"width: 100%\\\">\\r\\n    <div class=\\\"inline-block border-left pl-2 border-dark mr-4 mt-4\\\" style=\\\"width: 30%\\\">\\r\\n        <p class=\\\"font-weight-bold text-uppercase text-info\\\">From:<\\/p>\\r\\n        <div>\\r\\n            <div class=\\\"mr-5\\\">\\r\\n                $account_details\\r\\n            <\\/div>\\r\\n            <div>\\r\\n                $account_address\\r\\n            <\\/div>\\r\\n        <\\/div>\\r\\n    <\\/div>\\r\\n    <div class=\\\"border-left pl-4 border-dark inline-block\\\" style=\\\"width: 30%\\\">\\r\\n        <p class=\\\"font-weight-bold text-uppercase text-info\\\">To:<\\/p>\\r\\n        $customer_details\\r\\n    <\\/div>\\r\\n    <div class=\\\"inline-block mt-4 h-16\\\" style=\\\"width: 30%\\\">\\r\\n        $account_logo\\r\\n    <\\/div>\\r\\n<\\/div>\",\"body\":\"<div class=\\\"mx-4 mt-4\\\">\\r\\n<h1 class=\\\"font-weight-bold text-uppercase\\\">$entity_label<\\/h1>\\r\\n<div class=\\\"mt-1\\\">\\r\\n    <span class=\\\"font-weight-bold text-uppercase text-info\\\">$entity_number<\\/span>\\r\\n    <div class=\\\"inline-block ml-4\\\">\\r\\n        <span class=\\\"text-uppercase\\\">$date_label<\\/span>\\r\\n        <span>$date<\\/span>\\r\\n    <\\/div>\\r\\n    <div class=\\\"inline-block ml-4\\\">\\r\\n        <span class=\\\"text-uppercase\\\">$due_date_label<\\/span>\\r\\n        <span>$due_date<\\/span>\\r\\n    <\\/div>\\r\\n    <div class=\\\"inline-block ml-4\\\">\\r\\n        <span class=\\\"text-uppercase\\\">$balance_due_label<\\/span>\\r\\n        <span class=\\\"text-info\\\">$balance_due<\\/span>\\r\\n    <\\/div>\\r\\n<\\/div>\\r\\n<\\/div>\\r\\n\\r\\n$table_here\\r\\n\\r\\n<div class=\\\"mt-4\\\">\\r\\n<div class=\\\"inline-block\\\" style=\\\"width: 70%\\\">\\r\\n    <div>\\r\\n        <p>$entity.public_notes<\\/p>\\r\\n        <div class=\\\"pt-4\\\">\\r\\n            <p class=\\\"font-weight-bold\\\">$terms_label<\\/p>\\r\\n            <p>$terms<\\/p>\\r\\n        <\\/div>\\r\\n    <\\/div>\\r\\n<\\/div>\\r\\n<div class=\\\"inline-block\\\" style=\\\"width: 20%\\\">\\r\\n        <div class=\\\"col-6 text-left\\\">\\r\\n            <span style=\\\"margin-right: 60px\\\">$discount_label<\\/span> $discount<br>\\r\\n            <span style=\\\"margin-right: 60px\\\">$tax_label<\\/span> $tax<br>\\r\\n             <span style=\\\"margin-right: 60px\\\">$balance_due_label<\\/span> $balance_due<br>\\r\\n        <\\/div>\\r\\n<\\/div>\\r\\n<\\/div>\\r\\n<\\/div>\\r\\n\",\"table\":\"<table class=\\\"mt-4 w-100 table-auto\\\">\\r\\n<thead class=\\\"text-left\\\">\\r\\n    $product_table_header\\r\\n<\\/thead>\\r\\n<tbody>\\r\\n    $product_table_body\\r\\n<\\/tbody>\\r\\n<\\/table>\",\"task_table\":\"<table class=\\\"w-100 table-auto mt-4\\\">\\r\\n<thead class=\\\"text-left\\\">\\r\\n    $task_table_header\\r\\n<\\/thead>\\r\\n<tbody>\\r\\n    $task_table_body\\r\\n<\\/tbody>\\r\\n<\\/table>\",\"product\":\"\",\"task\":\"\",\"footer\":\"\\r\\n        <div class=\\\"text-center mb-2\\\">\\r\\n               $signature_here\\r\\n           <\\/div>\\r\\n        \\r\\n        <div class=\\\"footer_class py-4 px-4\\\" style=\\\"page-break-inside: avoid;\\\">\\r\\n\\r\\n      <\\/div>\\r\\n<\\/body>\\r\\n<\\/html>\"}', '2020-05-03 17:47:43', '2020-05-03 17:48:51', '0000-00-00 00:00:00', 0),
(17, NULL, 1, 'Info', 0, 1, '{\"header\":\"<div class=\\\"bg-secondary p-4\\\">\\r\\n<div class=\\\"col-6 inline-block mt-4\\\">\\r\\n    <div class=\\\"bg-white pt-4 px-4 pb-4 inline-block\\\">\\r\\n        $account_logo\\r\\n    <\\/div>\\r\\n<\\/div>\\r\\n<div class=\\\"col-6 inline-block\\\">\\r\\n    <div class=\\\"text-white\\\">\\r\\n        $account_details\\r\\n    <\\/div>\\r\\n    <div class=\\\"inline-block text-white\\\">\\r\\n        $account_address\\r\\n    <\\/div>\\r\\n<\\/div>\\r\\n<\\/div>\",\"body\":\"<div class=\\\"mt-4 pl-4\\\">\\r\\n    <div class=\\\"inline-block col-6 mr-4\\\" style=\\\"width: 40%\\\">\\r\\n        <h2 class=\\\"text-uppercase font-weight-bolder text-info\\\">$entity_label<\\/h2> $customer_details\\r\\n    <\\/div>\\r\\n    <div class=\\\"inline-block col-6\\\">\\r\\n        <div class=\\\"bg-info px-4 py-3 text-white\\\">\\r\\n            <div class=\\\"text-white\\\">\\r\\n                $entity_details\\r\\n            <\\/div>\\r\\n        <\\/div>\\r\\n    <\\/div>\\r\\n<\\/div>\\r\\n\\r\\n$table_here\\r\\n\\r\\n   <div class=\\\"px-4 mt-4\\\" style=\\\"width: 100%\\\">\\r\\n    <div class=\\\"inline-block\\\" style=\\\"width: 70%\\\">\\r\\n        $entity.public_notes\\r\\n    <\\/div>\\r\\n    <div class=\\\"inline-block\\\" style=\\\"width: 20%\\\">\\r\\n        <div class=\\\"col-6 text-left\\\">\\r\\n            <span style=\\\"margin-right: 20px\\\">$subtotal_label<\\/span> $subtotal<br>\\r\\n            <span style=\\\"margin-right: 20px\\\">$discount_label<\\/span> $discount<br>\\r\\n            <span style=\\\"margin-right: 20px\\\">$tax_label<\\/span> $tax<br>\\r\\n            <span class=\\\"font-weight-bold\\\" style=\\\"margin-right: 20px\\\">$balance_due_label<\\/span> \\r\\n            <span class=\\\"text-info\\\"> $balance_due<\\/span><br>\\r\\n        <\\/div>\\r\\n    <\\/div>\\r\\n<\\/div>\\r\\n<div class=\\\"px-4 mt-4\\\" style=\\\"width: 100%\\\">\\r\\n    <div class=\\\"inline-block\\\" style=\\\"width: 70%\\\">\\r\\n        <p class=\\\"font-weight-bold\\\">$terms_label<\\/p>\\r\\n        $terms\\r\\n    <\\/div>\\r\\n<\\/div>\",\"table\":\"<table class=\\\"w-100 table-auto mt-4\\\">\\r\\n    <thead class=\\\"text-left\\\">\\r\\n        $product_table_header\\r\\n    <\\/thead>\\r\\n    <tbody>\\r\\n        $product_table_body\\r\\n    <\\/tbody>\\r\\n<\\/table>\",\"task_table\":\"<table class=\\\"w-100 table-auto mt-4\\\">\\r\\n    <thead class=\\\"text-left\\\">\\r\\n        $task_table_header\\r\\n    <\\/thead>\\r\\n    <tbody>\\r\\n        $task_table_body\\r\\n    <\\/tbody>\\r\\n<\\/table>\",\"product\":\"\",\"task\":\"\",\"footer\":\"\\r\\n        <div class=\\\"text-center mb-2\\\">\\r\\n               $signature_here\\r\\n           <\\/div>\\r\\n        \\r\\n        <div class=\\\"footer_class py-4 px-4\\\" style=\\\"page-break-inside: avoid;\\\">\\r\\n        <\\/div>\"}', '2020-05-03 17:47:43', '2020-05-03 17:48:51', '0000-00-00 00:00:00', 0),
(18, NULL, 1, 'Happy', 0, 1, '{\"header\":\"<div class=\\\"\\\">\\r\\n<div class=\\\"mt-4\\\">\\r\\n    <div class=\\\"inline-block col-6 ml-4\\\" style=\\\"width: 40%\\\">\\r\\n        $account_logo\\r\\n    <\\/div>\\r\\n    <div class=\\\"inline-block bg-info p-4\\\" style=\\\"width: 40%\\\">\\r\\n        <div>\\r\\n            <div class=\\\"text-white mr-4\\\">\\r\\n                $entity_labels\\r\\n            <\\/div>\\r\\n            <div class=\\\"text-right text-white\\\">\\r\\n                $entity_details\\r\\n            <\\/div>\\r\\n        <\\/div>\\r\\n    <\\/div>\\r\\n<\\/div>\",\"body\":\"<div class=\\\"mt-4 border-dashed border-top-4 border-bottom-4 border-info\\\">\\r\\n<div class=\\\"inline-block\\\" style=\\\"width: 50%\\\">\\r\\n    <div>\\r\\n        <p class=\\\"font-weight-bold bg-info pl-4\\\">$entity_label<\\/p>\\r\\n        <div class=\\\"py-4 mt-4 pl-4\\\">\\r\\n            <section>\\r\\n                $customer_details\\r\\n            <\\/section>\\r\\n        <\\/div>\\r\\n    <\\/div>\\r\\n<\\/div>\\r\\n<div class=\\\"inline-block col-6 ml-4\\\" style=\\\"width: 40%\\\">\\r\\n    <div>\\r\\n        <p class=\\\"font-weight-bold text-info pl-4\\\">$from_label:<\\/p>\\r\\n        <div class=\\\"border-dashed border-top-4 border-bottom-4 border-info py-4 mt-2 pl-4\\\">\\r\\n            <section>\\r\\n                $account_details\\r\\n            <\\/section>\\r\\n        <\\/div>\\r\\n    <\\/div>\\r\\n<\\/div>\\r\\n<\\/div>\\r\\n\\r\\n$table_here\\r\\n\\r\\n<div class=\\\"mt-3 px-4\\\">\\r\\n<div class=\\\"inline-block col-6\\\" style=\\\"width: 70%\\\">\\r\\n    <div>\\r\\n        <p>$entity.public_notes<\\/p>\\r\\n    <\\/div>\\r\\n<\\/div>\\r\\n<div class=\\\"inline-block\\\" style=\\\"width: 30%\\\">\\r\\n    <div class=\\\"px-3 mt-2\\\">\\r\\n        <div class=\\\"col-6 text-right\\\">\\r\\n            <span style=\\\"margin-right: 20px\\\"> $discount_label <\\/span> $discount <br>\\r\\n            <span style=\\\"margin-right: 20px\\\"> $tax_label <\\/span> $tax <br>\\r\\n        <\\/div>\\r\\n    <\\/div>\\r\\n<\\/div>\\r\\n<\\/div>\\r\\n<div class=\\\"w-100 mt-4 pb-4 px-4 mt-2\\\">\\r\\n<div class=\\\"inline-block\\\" style=\\\"width: 70%\\\">\\r\\n    <div>\\r\\n        <p class=\\\"font-weight-bold\\\">$terms_label<\\/p>\\r\\n        <p>$terms<\\/p>\\r\\n    <\\/div>\\r\\n<\\/div>\\r\\n<div class=\\\"inline-block\\\" style=\\\"width: 30%\\\">\\r\\n    <section class=\\\"bg-info py-2 px-3 pt-4 text-white\\\">\\r\\n        <p class=\\\"text-right\\\">$balance_due_label<\\/p>\\r\\n        <p class=\\\"text-right\\\">$balance_due<\\/p>\\r\\n    <\\/section>\\r\\n<\\/div>\\r\\n<\\/div>\\r\\n<\\/div>\\r\\n<\\/div>\\r\\n\",\"table\":\"<table class=\\\"w-100\\\">\\r\\n<thead class=\\\"text-left bg-info rounded\\\">\\r\\n    $product_table_header\\r\\n<\\/thead>\\r\\n<tbody>\\r\\n    $product_table_body\\r\\n<\\/tbody>\\r\\n<\\/table>\",\"task_table\":\"\",\"product\":\"\",\"task\":\"\",\"footer\":\"\\r\\n        <div class=\\\"text-center mb-2\\\">\\r\\n               $signature_here\\r\\n           <\\/div>\\r\\n        \\r\\n        <div class=\\\"footer_class py-4 px-4\\\" style=\\\"page-break-inside: avoid;\\\">\\r\\n        <\\/div>\\r\\n<\\/body>\\r\\n<\\/html>\"}', '2020-05-03 17:47:43', '2020-05-03 17:48:51', '0000-00-00 00:00:00', 0),
(19, NULL, 1, 'Dark', 0, 1, '{\"header\":\"<div class=\\\"py-4 px-4\\\">\\r\\n<div class=\\\"border-4 border-dark mb-4\\\">\\r\\n    <div class=\\\"inline-block mt-2\\\" style=\\\"margin-bottom: 15px; width: 60%; margin-top: 20px;\\\">\\r\\n        $account_logo\\r\\n    <\\/div>\\r\\n    <div class=\\\"inline-block text-right\\\" style=\\\"width: 40%; margin-top: 20px\\\">\\r\\n        <div class=\\\"inline-block mr-4\\\">\\r\\n            $entity_labels\\r\\n        <\\/div>\\r\\n        <div class=\\\"inline-block text-right\\\">\\r\\n            $entity_details\\r\\n        <\\/div>\\r\\n    <\\/div>\\r\\n<\\/div>\\r\\n<div class=\\\"border-bottom border-dark mt-1\\\"><\\/div>\",\"body\":\"<div class=\\\"pt-4\\\">\\r\\n<div class=\\\"inline-block border-right border-dashed border-dark pt-4\\\" style=\\\"width: 40%; margin-left: 40px;\\\">\\r\\n    $customer_details\\r\\n<\\/div>\\r\\n\\r\\n<div class=\\\"inline-block pl-4\\\" style=\\\"width: 20%\\\">\\r\\n $account_details\\r\\n$account_address\\r\\n<\\/div>\\r\\n   \\r\\n    \\r\\n<\\/div>\\r\\n<\\/div>\\r\\n\\r\\n<\\/div>\\r\\n\\r\\n$table_here\\r\\n\\r\\n<div class=\\\"mt-2 px-4 pb-4\\\">\\r\\n    <div class=\\\"inline-block\\\" style=\\\"width: 70%\\\">\\r\\n        <div>\\r\\n            <p>$entity.public_notes<\\/p>\\r\\n        <\\/div>\\r\\n    <\\/div>\\r\\n    <div class=\\\"inline-block\\\" style=\\\"width: 20%\\\">\\r\\n        <div class=\\\"px-3 mt-2\\\">\\r\\n            <div class=\\\"inline-block col-6 text-left\\\">\\r\\n                <span style=\\\"margin-right: 20px\\\"> $discount_label <\\/span> $discount<br>\\r\\n                <span style=\\\"margin-right: 20px\\\">$tax_label <\\/span> $tax\\r\\n            <\\/div>\\r\\n        <\\/div>\\r\\n    <\\/div>\\r\\n<\\/div>\\r\\n<div class=\\\"mt-1 pb-4 px-4\\\">\\r\\n    <div class=\\\"inline-block\\\" style=\\\"width: 70%\\\">\\r\\n        <div>\\r\\n            <p class=\\\"font-weight-bold\\\">$terms_label<\\/p>\\r\\n            <p>$terms<\\/p>\\r\\n        <\\/div>\\r\\n    <\\/div>\\r\\n    <div class=\\\"inline-block\\\" style=\\\"width: 20%\\\">\\r\\n        <section class=\\\"py-2 pt-4 text-success border-top border-bottom border-dashed border-dark px-2 mt-1\\\">\\r\\n            <p class=\\\"text-right\\\">$balance_due_label<\\/p>\\r\\n            <p class=\\\"text-right\\\">$balance_due<\\/p>\\r\\n        <\\/section>\\r\\n    <\\/div>\\r\\n<\\/div>\\r\\n        <div class=\\\"border-bottom-4 ml-4 border-dark mt-4\\\">\\r\\n        <h4 class=\\\"font-weight-bold mb-4\\\">Thanks for shopping with us<\\/h4>\\r\\n    <\\/div>\\r\\n    <div class=\\\"border-bottom border-dark mt-1\\\"><\\/div>\\r\\n<\\/div>\\r\\n\\r\\n\",\"table\":\"<table class=\\\"w-100 table-auto mb-4 mt-4\\\">\\r\\n    <thead class=\\\"text-left border-dashed border-bottom border-dark\\\">\\r\\n        $product_table_header\\r\\n    <\\/thead>\\r\\n    <tbody>\\r\\n        $product_table_body\\r\\n    <\\/tbody>\\r\\n<\\/table>\",\"task_table\":\"\",\"product\":\"\",\"task\":\"\",\"footer\":\"\\r\\n        <div class=\\\"text-center mb-2\\\">\\r\\n                $signature_here\\r\\n            <\\/div>\\r\\n\\r\\n        <div class=\\\"footer_class py-4 px-4\\\" style=\\\"page-break-inside: avoid;\\\">\\r\\n        <\\/div>\\r\\n<\\/footer>\\r\\n<\\/body>\\r\\n<\\/html>\"}', '2020-05-03 17:47:43', '2020-05-03 17:48:51', '0000-00-00 00:00:00', 0),
(20, NULL, 1, 'Danger', 0, 1, '{\"header\":\"<div class=\\\"py-4 px-4 mt-2\\\">\\r\\n            <div style=\\\"width: 100%\\\">\\r\\n                <div class=\\\"inline-block mt-4\\\" style=\\\"width: 30%\\\">\\r\\n                    <div class=\\\"inline-block\\\">\\r\\n                        $customer_details\\r\\n                    <\\/div>\\r\\n                    <div class=\\\"inline-block ml-4\\\">\\r\\n                        $account_details\\r\\n                    <\\/div>\\r\\n                    <div class=\\\"inline-block ml-4 mr-4\\\">\\r\\n                        $account_address\\r\\n                    <\\/div>\\r\\n                <\\/div>\\r\\n                \\r\\n                <div class=\\\"mt-4\\\" style=\\\"width: 60%\\\">\\r\\n    $account_logo\\r\\n    <\\/div>\\r\\n<\\/div>\",\"body\":\"<div class=\\\"mt-4\\\">\\r\\n<div class=\\\"inline-block\\\" style=\\\"width: 60%\\\">\\r\\n    <h1 class=\\\"text-uppercase font-weight-bold\\\">$entity_label<\\/h1>\\r\\n    <i class=\\\"ml-4 text-danger\\\">$entity_number<\\/i>\\r\\n<\\/div>\\r\\n<div class=\\\"inline-block text-left\\\" style=\\\"width: 30%\\\">\\r\\n    <div class=\\\"inline-block\\\">\\r\\n        $entity_labels\\r\\n    <\\/div>\\r\\n    <div class=\\\"inline-block text-right\\\">\\r\\n        $entity_details\\r\\n    <\\/div>\\r\\n<\\/div>\\r\\n<\\/div>\\r\\n\\r\\n$table_here\\r\\n\\r\\n<div class=\\\"border-top-4 border-danger\\\">\\r\\n<div class=\\\"mt-4 px-4 pb-4\\\">\\r\\n    <div class=\\\"inline-block\\\" style=\\\"width: 70%\\\">\\r\\n        <div class=\\\"\\\">\\r\\n            <p>$entity.public_notes<\\/p>\\r\\n        <\\/div>\\r\\n    <\\/div>\\r\\n    <div class=\\\"inline-block\\\" style=\\\"width: 20%\\\">\\r\\n        <div class=\\\"px-3 mt-2\\\">\\r\\n            <div class=\\\"inline-block col-6 text-left\\\">\\r\\n                <span style=\\\"margin-right: 80px\\\">$subtotal_label<\\/span> $subtotal <br>\\r\\n                <span style=\\\"margin-right: 80px\\\">$discount_label<\\/span> $discount <br>\\r\\n                <span style=\\\"margin-right: 80px\\\">$tax_label<\\/span> $tax <br>\\r\\n                <span style=\\\"margin-right: 80px\\\">$balance_due_label<\\/span> <span class=\\\"text-danger font-weight-bold\\\">$balance_due<\\/span> <br>\\r\\n            <\\/div>\\r\\n        <\\/div>\\r\\n    <\\/div>\\r\\n<\\/div>\\r\\n<div class=\\\"mt-1 pb-4 px-4\\\">\\r\\n    <div style=\\\"width: 70%\\\">\\r\\n        <div>\\r\\n            <p class=\\\"font-weight-bold\\\">$terms_label<\\/p>\\r\\n            <p>$terms<\\/p>\\r\\n        <\\/div>\\r\\n    <\\/div>\\r\\n<\\/div>\\r\\n<\\/div>\\r\\n<\\/div>\\r\\n\\r\\n\",\"table\":\"<table class=\\\"w-100 table-auto mt-4 border-top-4 border-danger bg-white\\\">\\r\\n<thead class=\\\"text-left rounded\\\">\\r\\n    $product_table_header\\r\\n<\\/thead>\\r\\n<tbody>\\r\\n    $product_table_body\\r\\n<\\/tbody>\\r\\n<\\/table>\",\"task_table\":\"<table class=\\\"w-100 table-auto mt-4 border-top-4 border-danger bg-white\\\">\\r\\n    <thead class=\\\"text-left rounded\\\">\\r\\n        $task_table_header\\r\\n    <\\/thead>\\r\\n    <tbody>\\r\\n        $task_table_body\\r\\n    <\\/tbody>\\r\\n<\\/table>\",\"product\":\"\",\"task\":\"\",\"footer\":\"\\r\\n        <div class=\\\"text-center mb-2\\\">\\r\\n               $signature_here\\r\\n           <\\/div>\\r\\n        \\r\\n        <div class=\\\"footer_class py-4 px-4\\\" style=\\\"page-break-inside: avoid;\\\">\\r\\n        <\\/div>\\r\\n<\\/footer>\\r\\n<\\/body>\\r\\n<\\/html>\"}', '2020-05-03 17:47:43', '2020-05-03 17:48:51', '0000-00-00 00:00:00', 0),
(21, NULL, 1, 'Basic', 0, 1, '{\"header\":\" <div class=\\\"px-2 py-4\\\">\\r\\n<div>\\r\\n    $account_logo\\r\\n    <div class=\\\"inline-block\\\" style=\\\"word-break: break-word\\\">\\r\\n        $account_details <br>\\r\\n        $account_address\\r\\n    <\\/div>\\r\\n<\\/div>\\r\\n    <div class=\\\"inline-block mr-4 mt-4\\\" style=\\\"width: 60%;\\\">\\r\\n        <div class=\\\"\\\">\\r\\n            <section class=\\\"\\\">\\r\\n                $entity_details\\r\\n            <\\/section>\\r\\n        <\\/div>\\r\\n<\\/div>\",\"body\":\"<div class=\\\"inline-block\\\">\\r\\n    $customer_details\\r\\n<\\/div>\\r\\n\\r\\n$table_here\\r\\n\\r\\n<div class=\\\"mt-4\\\">\\r\\n<div class=\\\"inline-block col-6\\\" style=\\\"width: 70%\\\">\\r\\n    <div class=\\\"\\\">\\r\\n        <p>$entity.public_notes<\\/p>\\r\\n        <div class=\\\"pt-4\\\">\\r\\n            <p class=\\\"font-weight-bold\\\">$terms_label<\\/p>\\r\\n            <p>$terms<\\/p>\\r\\n        <\\/div>\\r\\n    <\\/div>\\r\\n<\\/div>\\r\\n<div class=\\\"inline-block\\\" style=\\\"width: 20%;\\\">\\r\\n    <div class=\\\"inline-block px-3\\\">\\r\\n        <div class=\\\"col-6 text-left\\\">\\r\\n            <span style=\\\"margin-right: 20px\\\"> $discount_label <\\/span>  $discount<br>\\r\\n            <span style=\\\"margin-right: 20px\\\">$tax_label<\\/span> $tax<br>\\r\\n            <span style=\\\"margin-right: 20px\\\"> $balance_due_label <\\/span>  $balance_due<br>\\r\\n        <\\/div>\\r\\n    <\\/div>\\r\\n<\\/div>\\r\\n<\\/div>\\r\\n\",\"table\":\"<table class=\\\"w-100 table-auto mt-4\\\">\\r\\n    <thead class=\\\"text-left bg-secondary\\\">\\r\\n        $product_table_header\\r\\n    <\\/thead>\\r\\n    <tbody>\\r\\n        $product_table_body\\r\\n    <\\/tbody>\\r\\n<\\/table>\",\"task_table\":\"\\r\\n<table class=\\\"w-100 table-auto mt-4\\\">\\r\\n    <thead class=\\\"text-left\\\">\\r\\n        $task_table_header\\r\\n    <\\/thead>\\r\\n    <tbody>\\r\\n        $task_table_body\\r\\n    <\\/tbody>\\r\\n<\\/table>\",\"product\":\"\",\"task\":\"\",\"footer\":\"\\r\\n        <div class=\\\"text-center mb-2\\\">\\r\\n               $signature_here\\r\\n           <\\/div>\\r\\n        \\r\\n        <div class=\\\"footer_class py-4 px-4\\\" style=\\\"page-break-inside: avoid;\\\">\\r\\n        <\\/div>\\r\\n<\\/body>\\r\\n<\\/html>\"}', '2020-05-03 17:47:43', '2020-05-03 17:48:51', '0000-00-00 00:00:00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `domains`
--

DROP TABLE IF EXISTS `domains`;
CREATE TABLE `domains` (
  `id` int(10) UNSIGNED NOT NULL,
  `payment_id` int(10) UNSIGNED DEFAULT NULL,
  `default_account_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `domains`
--

INSERT INTO `domains` (`id`, `payment_id`, `default_account_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(5, NULL, 1, '2020-05-03 17:24:22', '2020-05-03 17:24:22', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `emails`
--

DROP TABLE IF EXISTS `emails`;
CREATE TABLE `emails` (
  `id` int(11) NOT NULL,
  `recipient` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `account_id` int(10) UNSIGNED NOT NULL,
  `subject` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  `entity` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `entity_id` int(11) NOT NULL,
  `direction` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `sent_at` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `recipient_email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `design` text COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `emails`
--

INSERT INTO `emails` (`id`, `recipient`, `user_id`, `account_id`, `subject`, `body`, `entity`, `entity_id`, `direction`, `sent_at`, `created_at`, `updated_at`, `deleted_at`, `recipient_email`, `design`) VALUES
(1, 'Michael Hampton', 5, 1, '', '', 'App\\Invoice', 1, 'OUT', '2020-05-03', '2020-05-03 19:36:30', '2020-05-03 19:36:30', NULL, 'michaelhamptondesign@yahoo.coim', '\"\"'),
(2, 'Michael Hampton', 5, 1, '', '', 'App\\Quote', 1, 'OUT', '2020-05-03', '2020-05-03 19:37:36', '2020-05-03 19:37:36', NULL, 'michaelhamptondesign@yahoo.coim', '\"\"'),
(3, 'Michael Hampton', 5, 1, '', '', 'App\\Quote', 1, 'OUT', '2020-05-03', '2020-05-03 19:38:28', '2020-05-03 19:38:28', NULL, 'michaelhamptondesign@yahoo.coim', '\"\"'),
(4, 'Michael Hampton', 5, 1, '', '', 'App\\Credit', 1, 'OUT', '2020-05-03', '2020-05-03 19:39:18', '2020-05-03 19:39:18', NULL, 'michaelhamptondesign@yahoo.coim', '\"\"'),
(5, 'Michael Hampton', 5, 1, '', '', 'App\\Credit', 1, 'OUT', '2020-05-03', '2020-05-03 19:39:51', '2020-05-03 19:39:51', NULL, 'michaelhamptondesign@yahoo.coim', '\"\"');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
CREATE TABLE `events` (
  `id` int(10) UNSIGNED NOT NULL,
  `beginDate` datetime NOT NULL,
  `endDate` datetime NOT NULL,
  `customer_id` int(10) UNSIGNED NOT NULL,
  `location` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `event_type` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `account_id` int(10) UNSIGNED NOT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event_status`
--

DROP TABLE IF EXISTS `event_status`;
CREATE TABLE `event_status` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event_task`
--

DROP TABLE IF EXISTS `event_task`;
CREATE TABLE `event_task` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `task_id` int(10) UNSIGNED NOT NULL,
  `event_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event_types`
--

DROP TABLE IF EXISTS `event_types`;
CREATE TABLE `event_types` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `event_types`
--

INSERT INTO `event_types` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Event', NULL, NULL),
(2, 'Call', NULL, NULL),
(3, 'Meeting', NULL, NULL),
(4, 'Task', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `event_user`
--

DROP TABLE IF EXISTS `event_user`;
CREATE TABLE `event_user` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `event_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

DROP TABLE IF EXISTS `expenses`;
CREATE TABLE `expenses` (
  `id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `account_id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `invoice_id` int(10) UNSIGNED DEFAULT NULL,
  `customer_id` int(10) UNSIGNED DEFAULT NULL,
  `bank_id` int(10) UNSIGNED DEFAULT NULL,
  `invoice_currency_id` int(10) UNSIGNED NOT NULL,
  `expense_currency_id` int(10) UNSIGNED NOT NULL,
  `invoice_category_id` int(10) UNSIGNED DEFAULT NULL,
  `payment_type_id` int(10) UNSIGNED DEFAULT NULL,
  `recurring_expense_id` int(10) UNSIGNED DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `amount` decimal(13,2) NOT NULL,
  `foreign_amount` decimal(13,2) NOT NULL,
  `exchange_rate` decimal(13,4) NOT NULL,
  `tax_name1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tax_rate1` decimal(13,3) NOT NULL DEFAULT 0.000,
  `tax_name2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tax_rate2` decimal(13,3) NOT NULL DEFAULT 0.000,
  `tax_name3` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tax_rate3` decimal(13,3) NOT NULL DEFAULT 0.000,
  `expense_date` date DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `public_notes` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `transaction_reference` text COLLATE utf8_unicode_ci NOT NULL,
  `should_be_invoiced` tinyint(1) NOT NULL DEFAULT 0,
  `invoice_documents` tinyint(1) DEFAULT 1,
  `transaction_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_value1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_value2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_value3` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_value4` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status_id` int(11) NOT NULL DEFAULT 1,
  `private_notes` text COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expense_categories`
--

DROP TABLE IF EXISTS `expense_categories`;
CREATE TABLE `expense_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `account_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

DROP TABLE IF EXISTS `files`;
CREATE TABLE `files` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_active` int(11) NOT NULL DEFAULT 1,
  `file_path` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `account_id` int(10) UNSIGNED NOT NULL,
  `preview` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `size` int(10) UNSIGNED DEFAULT NULL,
  `width` int(10) UNSIGNED DEFAULT NULL,
  `height` int(10) UNSIGNED DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `documentable_id` int(10) UNSIGNED NOT NULL,
  `documentable_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `custom_value1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_value2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_value3` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_value4` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `form_category`
--

DROP TABLE IF EXISTS `form_category`;
CREATE TABLE `form_category` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `form_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `frequencies`
--

DROP TABLE IF EXISTS `frequencies`;
CREATE TABLE `frequencies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date_interval` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gateways`
--

DROP TABLE IF EXISTS `gateways`;
CREATE TABLE `gateways` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `provider` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 10000,
  `site_url` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_offsite` tinyint(1) NOT NULL DEFAULT 0,
  `is_secure` tinyint(1) NOT NULL DEFAULT 0,
  `fields` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `default_gateway_type_id` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gateway_types`
--

DROP TABLE IF EXISTS `gateway_types`;
CREATE TABLE `gateway_types` (
  `id` int(10) UNSIGNED NOT NULL,
  `alias` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `group_settings`
--

DROP TABLE IF EXISTS `group_settings`;
CREATE TABLE `group_settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `account_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `settings` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `industries`
--

DROP TABLE IF EXISTS `industries`;
CREATE TABLE `industries` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `industries`
--

INSERT INTO `industries` (`id`, `name`) VALUES
(1, 'Accounting & Legal'),
(2, 'Advertising'),
(3, 'Aerospace'),
(4, 'Agriculture'),
(5, 'Automotive'),
(6, 'Banking & Finance'),
(7, 'Biotechnology'),
(8, 'Broadcasting'),
(9, 'Business Services'),
(10, 'Commodities & Chemicals'),
(11, 'Communications'),
(12, 'Computers & Hightech'),
(13, 'Defense'),
(14, 'Energy'),
(15, 'Entertainment'),
(16, 'Government'),
(17, 'Healthcare & Life Sciences'),
(18, 'Insurance'),
(19, 'Manufacturing'),
(20, 'Marketing'),
(21, 'Media'),
(22, 'Nonprofit & Higher Ed'),
(23, 'Pharmaceuticals'),
(24, 'Professional Services & Consulting'),
(25, 'Real Estate'),
(26, 'Retail & Wholesale'),
(27, 'Sports'),
(28, 'Transportation'),
(29, 'Travel & Luxury'),
(30, 'Other'),
(31, 'Photography'),
(32, 'Construction'),
(33, 'Restaurant & Catering');

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

DROP TABLE IF EXISTS `invoices`;
CREATE TABLE `invoices` (
  `id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `assigned_user_id` int(10) UNSIGNED DEFAULT NULL,
  `status_id` int(10) UNSIGNED NOT NULL,
  `recurring_id` int(10) UNSIGNED DEFAULT NULL,
  `number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_amount_discount` tinyint(1) NOT NULL DEFAULT 0,
  `is_recurring` int(11) DEFAULT NULL,
  `po_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date` date DEFAULT NULL,
  `due_date` datetime DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `recurring_due_date` date DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `line_items` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `footer` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `public_notes` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `terms` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `total` decimal(16,4) NOT NULL,
  `sub_total` decimal(16,4) NOT NULL,
  `tax_total` decimal(16,4) NOT NULL,
  `discount_total` decimal(16,4) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `frequency` int(11) DEFAULT NULL,
  `balance` decimal(16,4) NOT NULL,
  `partial` decimal(16,4) DEFAULT NULL,
  `partial_due_date` datetime DEFAULT NULL,
  `last_viewed` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `account_id` int(10) UNSIGNED NOT NULL,
  `task_id` int(10) UNSIGNED DEFAULT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `next_send_date` date DEFAULT NULL,
  `custom_value1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_value2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_value3` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_value4` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_sent_date` date DEFAULT NULL,
  `private_notes` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `uses_inclusive_taxes` tinyint(1) NOT NULL DEFAULT 0,
  `tax_rate` decimal(13,3) NOT NULL DEFAULT 0.000,
  `tax_rate_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `design_id` int(10) UNSIGNED DEFAULT NULL,
  `custom_surcharge1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_surcharge2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_surcharge3` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_surcharge4` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_surcharge_tax1` tinyint(1) NOT NULL DEFAULT 0,
  `custom_surcharge_tax2` tinyint(1) NOT NULL DEFAULT 0,
  `custom_surcharge_tax3` tinyint(1) NOT NULL DEFAULT 0,
  `custom_surcharge_tax4` tinyint(1) NOT NULL DEFAULT 0,
  `order_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `customer_id`, `user_id`, `assigned_user_id`, `status_id`, `recurring_id`, `number`, `is_amount_discount`, `is_recurring`, `po_number`, `date`, `due_date`, `start_date`, `end_date`, `recurring_due_date`, `is_deleted`, `line_items`, `footer`, `public_notes`, `terms`, `total`, `sub_total`, `tax_total`, `discount_total`, `parent_id`, `frequency`, `balance`, `partial`, `partial_due_date`, `last_viewed`, `created_at`, `updated_at`, `deleted_at`, `account_id`, `task_id`, `company_id`, `next_send_date`, `custom_value1`, `custom_value2`, `custom_value3`, `custom_value4`, `last_sent_date`, `private_notes`, `uses_inclusive_taxes`, `tax_rate`, `tax_rate_name`, `design_id`, `custom_surcharge1`, `custom_surcharge2`, `custom_surcharge3`, `custom_surcharge4`, `custom_surcharge_tax1`, `custom_surcharge_tax2`, `custom_surcharge_tax3`, `custom_surcharge_tax4`, `order_id`) VALUES
(1, 5, 5, NULL, 1, NULL, '0001', 0, NULL, NULL, '2020-05-04', '2020-05-04 00:00:00', NULL, NULL, NULL, 0, '[{\"custom_value1\":\"\",\"custom_value2\":\"\",\"custom_value3\":\"\",\"custom_value4\":\"\",\"tax_rate_name\":\"basic\",\"tax_rate_id\":1,\"type_id\":1,\"quantity\":1,\"notes\":\"\",\"unit_price\":1699,\"unit_discount\":2,\"unit_tax\":17.5,\"sub_total\":1962,\"line_total\":1699,\"discount_total\":33.98,\"tax_total\":297.33,\"is_amount_discount\":false,\"product_id\":\"1\",\"description\":\"\"}]', 'invoice footer', 'public', 'invoice terms', '1962.3500', '1699.0000', '297.3300', '33.9800', NULL, NULL, '1962.3500', '0.0000', '2020-05-04 00:00:00', NULL, '2020-05-03 19:22:05', '2020-05-03 19:22:05', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '0.000', NULL, NULL, '0', '0', '0', '0', 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `invoices_backup`
--

DROP TABLE IF EXISTS `invoices_backup`;
CREATE TABLE `invoices_backup` (
  `id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(11) NOT NULL,
  `payment_type` int(11) NOT NULL,
  `total` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `invoice_status` int(11) NOT NULL DEFAULT 1,
  `due_date` datetime NOT NULL,
  `finance_type` int(11) NOT NULL,
  `sub_total` decimal(8,2) NOT NULL,
  `tax_total` decimal(8,2) NOT NULL,
  `discount_total` decimal(8,2) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `is_recurring` int(11) NOT NULL DEFAULT 0,
  `invoice_date` date NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `recurring_due_date` date NOT NULL,
  `frequency` int(11) NOT NULL,
  `notes` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `partial` decimal(8,2) NOT NULL,
  `balance` decimal(8,2) NOT NULL,
  `partial_due_date` date DEFAULT NULL,
  `terms` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `footer` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_invitations`
--

DROP TABLE IF EXISTS `invoice_invitations`;
CREATE TABLE `invoice_invitations` (
  `id` int(10) UNSIGNED NOT NULL,
  `account_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `client_contact_id` int(10) UNSIGNED NOT NULL,
  `invoice_id` int(10) UNSIGNED NOT NULL,
  `key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `transaction_reference` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `message_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email_error` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `signature_base64` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `signature_date` datetime DEFAULT NULL,
  `sent_date` datetime DEFAULT NULL,
  `viewed_date` datetime DEFAULT NULL,
  `opened_date` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `invoice_invitations`
--

INSERT INTO `invoice_invitations` (`id`, `account_id`, `user_id`, `client_contact_id`, `invoice_id`, `key`, `transaction_reference`, `message_id`, `email_error`, `signature_base64`, `signature_date`, `sent_date`, `viewed_date`, `opened_date`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 5, 1, 1, 'GUWgkFjNoHc2zvdCRRJd', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020-05-03 19:22:06', '2020-05-03 19:22:06', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `invoice_status`
--

DROP TABLE IF EXISTS `invoice_status`;
CREATE TABLE `invoice_status` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

DROP TABLE IF EXISTS `languages`;
CREATE TABLE `languages` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `locale` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `name`, `locale`) VALUES
(1, 'English', 'en'),
(2, 'Italian', 'it'),
(3, 'German', 'de'),
(4, 'French', 'fr'),
(5, 'Portuguese - Brazilian', 'pt_BR'),
(6, 'Dutch', 'nl'),
(7, 'Spanish', 'es'),
(8, 'Norwegian', 'nb_NO'),
(9, 'Danish', 'da'),
(10, 'Japanese', 'ja'),
(11, 'Swedish', 'sv'),
(12, 'Spanish - Spain', 'es_ES'),
(13, 'French - Canada', 'fr_CA'),
(14, 'Lithuanian', 'lt'),
(15, 'Polish', 'pl'),
(16, 'Czech', 'cs'),
(17, 'Croatian', 'hr'),
(18, 'Albanian', 'sq'),
(19, 'Greek', 'el'),
(20, 'English - United Kingdom', 'en_GB'),
(21, 'Portuguese - Portugal', 'pt_PT'),
(22, 'Slovenian', 'sl'),
(23, 'Finnish', 'fi'),
(24, 'Romanian', 'ro'),
(25, 'Turkish - Turkey', 'tr_TR'),
(26, 'Thai', 'th'),
(27, 'Macedonian', 'mk_MK'),
(28, 'Chinese - Taiwan', 'zh_TW');

-- --------------------------------------------------------

--
-- Table structure for table `leads`
--

DROP TABLE IF EXISTS `leads`;
CREATE TABLE `leads` (
  `id` int(10) UNSIGNED NOT NULL,
  `first_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `job_title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `account_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `source_type` int(10) UNSIGNED NOT NULL,
  `task_status` int(10) UNSIGNED NOT NULL DEFAULT 5,
  `address_1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address_2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `zip` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `public_notes` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `private_notes` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `valued_at` decimal(8,2) NOT NULL DEFAULT 0.00,
  `company_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `assigned_user_id` int(10) UNSIGNED DEFAULT NULL,
  `custom_value1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_value2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_value3` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_value4` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country_id` int(11) NOT NULL DEFAULT 225,
  `industry_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE `messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(10) UNSIGNED NOT NULL,
  `message` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `has_seen` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `direction` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `metrics`
--

DROP TABLE IF EXISTS `metrics`;
CREATE TABLE `metrics` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value` double(8,2) NOT NULL DEFAULT 1.00,
  `resolution` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `metrics`
--

INSERT INTO `metrics` (`id`, `name`, `type`, `value`, `resolution`, `metadata`, `created_at`, `updated_at`) VALUES
(1, 'user-created', 'marker-meter', 1.00, NULL, NULL, '2020-05-03 17:16:07', '2020-05-03 17:16:07'),
(2, 'user-created', 'marker-meter', 1.00, NULL, NULL, '2020-05-03 17:24:23', '2020-05-03 17:24:23'),
(3, 'customer-created', 'marker-meter', 1.00, NULL, NULL, '2020-05-03 18:15:11', '2020-05-03 18:15:11'),
(4, 'invoice-created', 'marker-meter', 1.00, NULL, NULL, '2020-05-03 19:22:14', '2020-05-03 19:22:14');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2020_04_30_113506_create_account_user_table', 1),
(2, '2020_04_30_113506_create_accounts_table', 1),
(3, '2020_04_30_113506_create_addresses_table', 1),
(4, '2020_04_30_113506_create_categories_table', 1),
(5, '2020_04_30_113506_create_category_product_table', 1),
(6, '2020_04_30_113506_create_cities_table', 1),
(7, '2020_04_30_113506_create_client_contacts_table', 1),
(8, '2020_04_30_113506_create_client_gateway_tokens_table', 1),
(9, '2020_04_30_113506_create_comment_task_table', 1),
(10, '2020_04_30_113506_create_comment_type_table', 1),
(11, '2020_04_30_113506_create_comments_table', 1),
(12, '2020_04_30_113506_create_companies_table', 1),
(13, '2020_04_30_113506_create_company_contacts_table', 1),
(14, '2020_04_30_113506_create_company_gateways_table', 1),
(15, '2020_04_30_113506_create_company_ledgers_table', 1),
(16, '2020_04_30_113506_create_company_tokens_table', 1),
(17, '2020_04_30_113506_create_company_user_table', 1),
(18, '2020_04_30_113506_create_countries_table', 1),
(19, '2020_04_30_113506_create_credit_invitations_table', 1),
(20, '2020_04_30_113506_create_credits_table', 1),
(21, '2020_04_30_113506_create_currencies_table', 1),
(22, '2020_04_30_113506_create_customer_type_table', 1),
(23, '2020_04_30_113506_create_customer_types_table', 1),
(24, '2020_04_30_113506_create_customers_table', 1),
(25, '2020_04_30_113506_create_date_formats_table', 1),
(26, '2020_04_30_113506_create_datetime_formats_table', 1),
(27, '2020_04_30_113506_create_department_user_table', 1),
(28, '2020_04_30_113506_create_departments_table', 1),
(29, '2020_04_30_113506_create_designs_table', 1),
(30, '2020_04_30_113506_create_domains_table', 1),
(31, '2020_04_30_113506_create_emails_table', 1),
(32, '2020_04_30_113506_create_event_status_table', 1),
(33, '2020_04_30_113506_create_event_task_table', 1),
(34, '2020_04_30_113506_create_event_types_table', 1),
(35, '2020_04_30_113506_create_event_user_table', 1),
(36, '2020_04_30_113506_create_events_table', 1),
(37, '2020_04_30_113506_create_expense_categories_table', 1),
(38, '2020_04_30_113506_create_expenses_table', 1),
(39, '2020_04_30_113506_create_files_table', 1),
(40, '2020_04_30_113506_create_form_category_table', 1),
(41, '2020_04_30_113506_create_frequencies_table', 1),
(42, '2020_04_30_113506_create_gateway_types_table', 1),
(43, '2020_04_30_113506_create_gateways_table', 1),
(44, '2020_04_30_113506_create_group_settings_table', 1),
(45, '2020_04_30_113506_create_industries_table', 1),
(46, '2020_04_30_113506_create_invoice_invitations_table', 1),
(47, '2020_04_30_113506_create_invoice_status_table', 1),
(48, '2020_04_30_113506_create_invoices_backup_table', 1),
(49, '2020_04_30_113506_create_invoices_table', 1),
(50, '2020_04_30_113506_create_languages_table', 1),
(51, '2020_04_30_113506_create_leads_table', 1),
(52, '2020_04_30_113506_create_messages_table', 1),
(53, '2020_04_30_113506_create_notifications_table', 1),
(54, '2020_04_30_113506_create_order_invitations_table', 1),
(55, '2020_04_30_113506_create_order_status_table', 1),
(56, '2020_04_30_113506_create_password_resets_table', 1),
(57, '2020_04_30_113506_create_payment_methods_table', 1),
(58, '2020_04_30_113506_create_payment_statuses_table', 1),
(59, '2020_04_30_113506_create_paymentables_table', 1),
(60, '2020_04_30_113506_create_payments_table', 1),
(61, '2020_04_30_113506_create_permission_role_table', 1),
(62, '2020_04_30_113506_create_permission_user_table', 1),
(63, '2020_04_30_113506_create_product_attributes_table', 2),
(64, '2020_04_30_113506_create_product_images_table', 2),
(65, '2020_04_30_113506_create_product_task_table', 2),
(66, '2020_04_30_113506_create_products_table', 2),
(67, '2020_04_30_113506_create_projects_table', 2),
(68, '2020_04_30_113506_create_provinces_table', 2),
(69, '2020_04_30_113506_create_quote_invitations_table', 2),
(70, '2020_04_30_113506_create_quotes_table', 2),
(71, '2020_04_30_113506_create_recurring_invoices_table', 2),
(72, '2020_04_30_113506_create_recurring_quotes_table', 2),
(73, '2020_04_30_113506_create_role_user_table', 2),
(74, '2020_04_30_113506_create_roles_table', 2),
(75, '2020_04_30_113506_create_source_type_table', 2),
(76, '2020_04_30_113506_create_states_table', 2),
(77, '2020_04_30_113506_create_subscriptions_table', 2),
(78, '2020_04_30_113506_create_system_logs_table', 2),
(79, '2020_04_30_113506_create_task_comment_table', 2),
(80, '2020_04_30_113506_create_task_statuses_table', 2),
(81, '2020_04_30_113506_create_task_type_table', 2),
(82, '2020_04_30_113506_create_task_user_table', 2),
(83, '2020_04_30_113506_create_tasks_table', 2),
(84, '2020_04_30_113506_create_tax_rates_table', 2),
(85, '2020_04_30_113506_create_users_table', 2),
(86, '2020_04_30_113518_add_foreign_keys_to_account_user_table', 2),
(87, '2020_04_30_113518_add_foreign_keys_to_accounts_table', 2),
(88, '2020_04_30_113518_add_foreign_keys_to_addresses_table', 2),
(89, '2020_04_30_113518_add_foreign_keys_to_cities_table', 2),
(90, '2020_04_30_113518_add_foreign_keys_to_client_contacts_table', 2),
(91, '2020_04_30_113518_add_foreign_keys_to_client_gateway_tokens_table', 2),
(92, '2020_04_30_113518_add_foreign_keys_to_comment_task_table', 2),
(93, '2020_04_30_113518_add_foreign_keys_to_comments_table', 2),
(94, '2020_04_30_113518_add_foreign_keys_to_companies_table', 2),
(95, '2020_04_30_113518_add_foreign_keys_to_company_contacts_table', 2),
(96, '2020_04_30_113518_add_foreign_keys_to_company_gateways_table', 2),
(97, '2020_04_30_113518_add_foreign_keys_to_company_ledgers_table', 2),
(98, '2020_04_30_113518_add_foreign_keys_to_company_tokens_table', 2),
(99, '2020_04_30_113518_add_foreign_keys_to_credit_invitations_table', 2),
(100, '2020_04_30_113518_add_foreign_keys_to_credits_table', 2),
(101, '2020_04_30_113518_add_foreign_keys_to_customers_table', 2),
(102, '2020_04_30_113518_add_foreign_keys_to_department_user_table', 2),
(103, '2020_04_30_113518_add_foreign_keys_to_departments_table', 2),
(104, '2020_04_30_113518_add_foreign_keys_to_emails_table', 2),
(105, '2020_04_30_113518_add_foreign_keys_to_event_task_table', 2),
(106, '2020_04_30_113518_add_foreign_keys_to_event_user_table', 2),
(107, '2020_04_30_113518_add_foreign_keys_to_events_table', 2),
(108, '2020_04_30_113518_add_foreign_keys_to_expenses_table', 2),
(109, '2020_04_30_113518_add_foreign_keys_to_files_table', 2),
(110, '2020_04_30_113518_add_foreign_keys_to_group_settings_table', 2),
(111, '2020_04_30_113518_add_foreign_keys_to_invoice_invitations_table', 2),
(112, '2020_04_30_113518_add_foreign_keys_to_invoices_table', 2),
(113, '2020_04_30_113518_add_foreign_keys_to_leads_table', 2),
(114, '2020_04_30_113518_add_foreign_keys_to_messages_table', 2),
(115, '2020_04_30_113518_add_foreign_keys_to_order_invitations_table', 2),
(116, '2020_04_30_113518_add_foreign_keys_to_paymentables_table', 2),
(117, '2020_04_30_113518_add_foreign_keys_to_payments_table', 2),
(118, '2020_04_30_113518_add_foreign_keys_to_permission_user_table', 2),
(119, '2020_04_30_113518_add_foreign_keys_to_product_attributes_table', 2),
(120, '2020_04_30_113518_add_foreign_keys_to_product_images_table', 2),
(121, '2020_04_30_113518_add_foreign_keys_to_product_task_table', 2),
(122, '2020_04_30_113518_add_foreign_keys_to_products_table', 2),
(123, '2020_04_30_113518_add_foreign_keys_to_projects_table', 2),
(124, '2020_04_30_113518_add_foreign_keys_to_provinces_table', 2),
(125, '2020_04_30_113518_add_foreign_keys_to_quote_invitations_table', 2),
(126, '2020_04_30_113518_add_foreign_keys_to_quotes_table', 2),
(127, '2020_04_30_113518_add_foreign_keys_to_recurring_invoices_table', 2),
(128, '2020_04_30_113518_add_foreign_keys_to_recurring_quotes_table', 2),
(129, '2020_04_30_113518_add_foreign_keys_to_role_user_table', 2),
(130, '2020_04_30_113518_add_foreign_keys_to_roles_table', 2),
(131, '2020_04_30_113518_add_foreign_keys_to_states_table', 2),
(132, '2020_04_30_113518_add_foreign_keys_to_subscriptions_table', 2),
(133, '2020_04_30_113518_add_foreign_keys_to_system_logs_table', 2),
(134, '2020_04_30_113518_add_foreign_keys_to_task_user_table', 2),
(135, '2020_04_30_113518_add_foreign_keys_to_tasks_table', 2),
(136, '2020_04_30_113518_add_foreign_keys_to_tax_rates_table', 2),
(137, '2020_04_30_113518_add_foreign_keys_to_users_table', 3),
(138, '2020_05_01_130411_create_metrics_table', 3),
(139, '2020_05_03_204128_create_timers_table', 4),
(140, '2020_05_03_204420_create_account_user_table', 0),
(141, '2020_05_03_204420_create_accounts_table', 0),
(142, '2020_05_03_204420_create_addresses_table', 0),
(143, '2020_05_03_204420_create_categories_table', 0),
(144, '2020_05_03_204420_create_category_product_table', 0),
(145, '2020_05_03_204420_create_cities_table', 0),
(146, '2020_05_03_204420_create_client_contacts_table', 0),
(147, '2020_05_03_204420_create_client_gateway_tokens_table', 0),
(148, '2020_05_03_204420_create_comment_task_table', 0),
(149, '2020_05_03_204420_create_comment_type_table', 0),
(150, '2020_05_03_204420_create_comments_table', 0),
(151, '2020_05_03_204420_create_companies_table', 0),
(152, '2020_05_03_204420_create_company_contacts_table', 0),
(153, '2020_05_03_204420_create_company_gateways_table', 0),
(154, '2020_05_03_204420_create_company_ledgers_table', 0),
(155, '2020_05_03_204420_create_company_tokens_table', 0),
(156, '2020_05_03_204420_create_company_user_table', 0),
(157, '2020_05_03_204420_create_countries_table', 0),
(158, '2020_05_03_204420_create_credit_invitations_table', 0),
(159, '2020_05_03_204420_create_credits_table', 0),
(160, '2020_05_03_204420_create_currencies_table', 0),
(161, '2020_05_03_204420_create_customer_type_table', 0),
(162, '2020_05_03_204420_create_customer_types_table', 0),
(163, '2020_05_03_204420_create_customers_table', 0),
(164, '2020_05_03_204420_create_date_formats_table', 0),
(165, '2020_05_03_204420_create_datetime_formats_table', 0),
(166, '2020_05_03_204420_create_department_user_table', 0),
(167, '2020_05_03_204420_create_departments_table', 0),
(168, '2020_05_03_204420_create_designs_table', 0),
(169, '2020_05_03_204420_create_domains_table', 0),
(170, '2020_05_03_204420_create_emails_table', 0),
(171, '2020_05_03_204420_create_event_status_table', 0),
(172, '2020_05_03_204420_create_event_task_table', 0),
(173, '2020_05_03_204420_create_event_types_table', 0),
(174, '2020_05_03_204420_create_event_user_table', 0),
(175, '2020_05_03_204420_create_events_table', 0),
(176, '2020_05_03_204420_create_expense_categories_table', 0),
(177, '2020_05_03_204420_create_expenses_table', 0),
(178, '2020_05_03_204420_create_files_table', 0),
(179, '2020_05_03_204420_create_form_category_table', 0),
(180, '2020_05_03_204420_create_frequencies_table', 0),
(181, '2020_05_03_204420_create_gateway_types_table', 0),
(182, '2020_05_03_204420_create_gateways_table', 0),
(183, '2020_05_03_204420_create_group_settings_table', 0),
(184, '2020_05_03_204420_create_industries_table', 0),
(185, '2020_05_03_204420_create_invoice_invitations_table', 0),
(186, '2020_05_03_204420_create_invoice_status_table', 0),
(187, '2020_05_03_204420_create_invoices_table', 0),
(188, '2020_05_03_204420_create_invoices_backup_table', 0),
(189, '2020_05_03_204420_create_languages_table', 0),
(190, '2020_05_03_204420_create_leads_table', 0),
(191, '2020_05_03_204420_create_messages_table', 0),
(192, '2020_05_03_204420_create_metrics_table', 0),
(193, '2020_05_03_204420_create_notifications_table', 0),
(194, '2020_05_03_204420_create_order_invitations_table', 0),
(195, '2020_05_03_204420_create_order_status_table', 0),
(196, '2020_05_03_204420_create_password_resets_table', 0),
(197, '2020_05_03_204420_create_payment_methods_table', 0),
(198, '2020_05_03_204420_create_payment_statuses_table', 0),
(199, '2020_05_03_204420_create_paymentables_table', 0),
(200, '2020_05_03_204420_create_payments_table', 0),
(201, '2020_05_03_204420_create_permission_role_table', 0),
(202, '2020_05_03_204420_create_permission_user_table', 0),
(203, '2020_05_03_204420_create_permissions_table', 0),
(204, '2020_05_03_204420_create_product_attributes_table', 0),
(205, '2020_05_03_204420_create_product_images_table', 0),
(206, '2020_05_03_204420_create_product_task_table', 0),
(207, '2020_05_03_204420_create_products_table', 0),
(208, '2020_05_03_204420_create_projects_table', 0),
(209, '2020_05_03_204420_create_provinces_table', 0),
(210, '2020_05_03_204420_create_quote_invitations_table', 0),
(211, '2020_05_03_204420_create_quotes_table', 0),
(212, '2020_05_03_204420_create_recurring_invoices_table', 0),
(213, '2020_05_03_204420_create_recurring_quotes_table', 0),
(214, '2020_05_03_204420_create_role_user_table', 0),
(215, '2020_05_03_204420_create_roles_table', 0),
(216, '2020_05_03_204420_create_source_type_table', 0),
(217, '2020_05_03_204420_create_states_table', 0),
(218, '2020_05_03_204420_create_subscriptions_table', 0),
(219, '2020_05_03_204420_create_system_logs_table', 0),
(220, '2020_05_03_204420_create_task_comment_table', 0),
(221, '2020_05_03_204420_create_task_statuses_table', 0),
(222, '2020_05_03_204420_create_task_type_table', 0),
(223, '2020_05_03_204420_create_task_user_table', 0),
(224, '2020_05_03_204420_create_tasks_table', 0),
(225, '2020_05_03_204420_create_tax_rates_table', 0),
(226, '2020_05_03_204420_create_timers_table', 0),
(227, '2020_05_03_204420_create_users_table', 0),
(228, '2020_05_03_204435_add_foreign_keys_to_account_user_table', 0),
(229, '2020_05_03_204435_add_foreign_keys_to_accounts_table', 0),
(230, '2020_05_03_204435_add_foreign_keys_to_addresses_table', 0),
(231, '2020_05_03_204435_add_foreign_keys_to_cities_table', 0),
(232, '2020_05_03_204435_add_foreign_keys_to_client_contacts_table', 0),
(233, '2020_05_03_204435_add_foreign_keys_to_client_gateway_tokens_table', 0),
(234, '2020_05_03_204435_add_foreign_keys_to_comment_task_table', 0),
(235, '2020_05_03_204435_add_foreign_keys_to_comments_table', 0),
(236, '2020_05_03_204435_add_foreign_keys_to_companies_table', 0),
(237, '2020_05_03_204435_add_foreign_keys_to_company_contacts_table', 0),
(238, '2020_05_03_204435_add_foreign_keys_to_company_gateways_table', 0),
(239, '2020_05_03_204435_add_foreign_keys_to_company_ledgers_table', 0),
(240, '2020_05_03_204435_add_foreign_keys_to_company_tokens_table', 0),
(241, '2020_05_03_204435_add_foreign_keys_to_credit_invitations_table', 0),
(242, '2020_05_03_204435_add_foreign_keys_to_credits_table', 0),
(243, '2020_05_03_204435_add_foreign_keys_to_customers_table', 0),
(244, '2020_05_03_204435_add_foreign_keys_to_department_user_table', 0),
(245, '2020_05_03_204435_add_foreign_keys_to_departments_table', 0),
(246, '2020_05_03_204435_add_foreign_keys_to_emails_table', 0),
(247, '2020_05_03_204435_add_foreign_keys_to_event_task_table', 0),
(248, '2020_05_03_204435_add_foreign_keys_to_event_user_table', 0),
(249, '2020_05_03_204435_add_foreign_keys_to_events_table', 0),
(250, '2020_05_03_204435_add_foreign_keys_to_expenses_table', 0),
(251, '2020_05_03_204435_add_foreign_keys_to_files_table', 0),
(252, '2020_05_03_204435_add_foreign_keys_to_group_settings_table', 0),
(253, '2020_05_03_204435_add_foreign_keys_to_invoice_invitations_table', 0),
(254, '2020_05_03_204435_add_foreign_keys_to_invoices_table', 0),
(255, '2020_05_03_204435_add_foreign_keys_to_leads_table', 0),
(256, '2020_05_03_204435_add_foreign_keys_to_messages_table', 0),
(257, '2020_05_03_204435_add_foreign_keys_to_order_invitations_table', 0),
(258, '2020_05_03_204435_add_foreign_keys_to_paymentables_table', 0),
(259, '2020_05_03_204435_add_foreign_keys_to_payments_table', 0),
(260, '2020_05_03_204435_add_foreign_keys_to_permission_user_table', 0),
(261, '2020_05_03_204435_add_foreign_keys_to_product_attributes_table', 0),
(262, '2020_05_03_204435_add_foreign_keys_to_product_images_table', 0),
(263, '2020_05_03_204435_add_foreign_keys_to_product_task_table', 0),
(264, '2020_05_03_204435_add_foreign_keys_to_products_table', 0),
(265, '2020_05_03_204435_add_foreign_keys_to_projects_table', 0),
(266, '2020_05_03_204435_add_foreign_keys_to_provinces_table', 0),
(267, '2020_05_03_204435_add_foreign_keys_to_quote_invitations_table', 0),
(268, '2020_05_03_204435_add_foreign_keys_to_quotes_table', 0),
(269, '2020_05_03_204435_add_foreign_keys_to_recurring_invoices_table', 0),
(270, '2020_05_03_204435_add_foreign_keys_to_recurring_quotes_table', 0),
(271, '2020_05_03_204435_add_foreign_keys_to_role_user_table', 0),
(272, '2020_05_03_204435_add_foreign_keys_to_roles_table', 0),
(273, '2020_05_03_204435_add_foreign_keys_to_states_table', 0),
(274, '2020_05_03_204435_add_foreign_keys_to_subscriptions_table', 0),
(275, '2020_05_03_204435_add_foreign_keys_to_system_logs_table', 0),
(276, '2020_05_03_204435_add_foreign_keys_to_task_user_table', 0),
(277, '2020_05_03_204435_add_foreign_keys_to_tasks_table', 0),
(278, '2020_05_03_204435_add_foreign_keys_to_tax_rates_table', 0),
(279, '2020_05_03_204435_add_foreign_keys_to_timers_table', 0),
(280, '2020_05_03_204435_add_foreign_keys_to_users_table', 0),
(281, '2020_05_03_214845_create_account_user_table', 0),
(282, '2020_05_03_214845_create_accounts_table', 0),
(283, '2020_05_03_214845_create_addresses_table', 0),
(284, '2020_05_03_214845_create_categories_table', 0),
(285, '2020_05_03_214845_create_category_product_table', 0),
(286, '2020_05_03_214845_create_cities_table', 0),
(287, '2020_05_03_214845_create_client_contacts_table', 0),
(288, '2020_05_03_214845_create_client_gateway_tokens_table', 0),
(289, '2020_05_03_214845_create_comment_task_table', 0),
(290, '2020_05_03_214845_create_comment_type_table', 0),
(291, '2020_05_03_214845_create_comments_table', 0),
(292, '2020_05_03_214845_create_companies_table', 0),
(293, '2020_05_03_214845_create_company_contacts_table', 0),
(294, '2020_05_03_214845_create_company_gateways_table', 0),
(295, '2020_05_03_214845_create_company_ledgers_table', 0),
(296, '2020_05_03_214845_create_company_tokens_table', 0),
(297, '2020_05_03_214845_create_company_user_table', 0),
(298, '2020_05_03_214845_create_countries_table', 0),
(299, '2020_05_03_214845_create_credit_invitations_table', 0),
(300, '2020_05_03_214845_create_credits_table', 0),
(301, '2020_05_03_214845_create_currencies_table', 0),
(302, '2020_05_03_214845_create_customer_type_table', 0),
(303, '2020_05_03_214845_create_customer_types_table', 0),
(304, '2020_05_03_214845_create_customers_table', 0),
(305, '2020_05_03_214845_create_date_formats_table', 0),
(306, '2020_05_03_214845_create_datetime_formats_table', 0),
(307, '2020_05_03_214845_create_department_user_table', 0),
(308, '2020_05_03_214845_create_departments_table', 0),
(309, '2020_05_03_214845_create_designs_table', 0),
(310, '2020_05_03_214845_create_domains_table', 0),
(311, '2020_05_03_214845_create_emails_table', 0),
(312, '2020_05_03_214845_create_event_status_table', 0),
(313, '2020_05_03_214845_create_event_task_table', 0),
(314, '2020_05_03_214845_create_event_types_table', 0),
(315, '2020_05_03_214845_create_event_user_table', 0),
(316, '2020_05_03_214845_create_events_table', 0),
(317, '2020_05_03_214845_create_expense_categories_table', 0),
(318, '2020_05_03_214845_create_expenses_table', 0),
(319, '2020_05_03_214845_create_files_table', 0),
(320, '2020_05_03_214845_create_form_category_table', 0),
(321, '2020_05_03_214845_create_frequencies_table', 0),
(322, '2020_05_03_214845_create_gateway_types_table', 0),
(323, '2020_05_03_214845_create_gateways_table', 0),
(324, '2020_05_03_214845_create_group_settings_table', 0),
(325, '2020_05_03_214845_create_industries_table', 0),
(326, '2020_05_03_214845_create_invoice_invitations_table', 0),
(327, '2020_05_03_214845_create_invoice_status_table', 0),
(328, '2020_05_03_214845_create_invoices_table', 0),
(329, '2020_05_03_214845_create_invoices_backup_table', 0),
(330, '2020_05_03_214845_create_languages_table', 0),
(331, '2020_05_03_214845_create_leads_table', 0),
(332, '2020_05_03_214845_create_messages_table', 0),
(333, '2020_05_03_214845_create_metrics_table', 0),
(334, '2020_05_03_214845_create_notifications_table', 0),
(335, '2020_05_03_214845_create_order_invitations_table', 0),
(336, '2020_05_03_214845_create_order_status_table', 0),
(337, '2020_05_03_214845_create_password_resets_table', 0),
(338, '2020_05_03_214845_create_payment_methods_table', 0),
(339, '2020_05_03_214845_create_payment_statuses_table', 0),
(340, '2020_05_03_214845_create_paymentables_table', 0),
(341, '2020_05_03_214845_create_payments_table', 0),
(342, '2020_05_03_214845_create_permission_role_table', 0),
(343, '2020_05_03_214845_create_permission_user_table', 0),
(344, '2020_05_03_214845_create_permissions_table', 0),
(345, '2020_05_03_214845_create_product_attributes_table', 0),
(346, '2020_05_03_214845_create_product_images_table', 0),
(347, '2020_05_03_214845_create_product_task_table', 0),
(348, '2020_05_03_214845_create_products_table', 0),
(349, '2020_05_03_214845_create_projects_table', 0),
(350, '2020_05_03_214845_create_provinces_table', 0),
(351, '2020_05_03_214845_create_quote_invitations_table', 0),
(352, '2020_05_03_214845_create_quotes_table', 0),
(353, '2020_05_03_214845_create_recurring_invoices_table', 0),
(354, '2020_05_03_214845_create_recurring_quotes_table', 0),
(355, '2020_05_03_214845_create_role_user_table', 0),
(356, '2020_05_03_214845_create_roles_table', 0),
(357, '2020_05_03_214845_create_source_type_table', 0),
(358, '2020_05_03_214845_create_states_table', 0),
(359, '2020_05_03_214845_create_subscriptions_table', 0),
(360, '2020_05_03_214845_create_system_logs_table', 0),
(361, '2020_05_03_214845_create_task_comment_table', 0),
(362, '2020_05_03_214845_create_task_statuses_table', 0),
(363, '2020_05_03_214845_create_task_type_table', 0),
(364, '2020_05_03_214845_create_task_user_table', 0),
(365, '2020_05_03_214845_create_tasks_table', 0),
(366, '2020_05_03_214845_create_tax_rates_table', 0),
(367, '2020_05_03_214845_create_timers_table', 0),
(368, '2020_05_03_214845_create_users_table', 0),
(369, '2020_05_03_214910_add_foreign_keys_to_account_user_table', 0),
(370, '2020_05_03_214910_add_foreign_keys_to_accounts_table', 0),
(371, '2020_05_03_214910_add_foreign_keys_to_addresses_table', 0),
(372, '2020_05_03_214910_add_foreign_keys_to_cities_table', 0),
(373, '2020_05_03_214910_add_foreign_keys_to_client_contacts_table', 0),
(374, '2020_05_03_214910_add_foreign_keys_to_client_gateway_tokens_table', 0),
(375, '2020_05_03_214910_add_foreign_keys_to_comment_task_table', 0),
(376, '2020_05_03_214910_add_foreign_keys_to_comments_table', 0),
(377, '2020_05_03_214910_add_foreign_keys_to_companies_table', 0),
(378, '2020_05_03_214910_add_foreign_keys_to_company_contacts_table', 0),
(379, '2020_05_03_214910_add_foreign_keys_to_company_gateways_table', 0),
(380, '2020_05_03_214910_add_foreign_keys_to_company_ledgers_table', 0),
(381, '2020_05_03_214910_add_foreign_keys_to_company_tokens_table', 0),
(382, '2020_05_03_214910_add_foreign_keys_to_credit_invitations_table', 0),
(383, '2020_05_03_214910_add_foreign_keys_to_credits_table', 0),
(384, '2020_05_03_214910_add_foreign_keys_to_customers_table', 0),
(385, '2020_05_03_214910_add_foreign_keys_to_department_user_table', 0),
(386, '2020_05_03_214910_add_foreign_keys_to_departments_table', 0),
(387, '2020_05_03_214910_add_foreign_keys_to_emails_table', 0),
(388, '2020_05_03_214910_add_foreign_keys_to_event_task_table', 0),
(389, '2020_05_03_214910_add_foreign_keys_to_event_user_table', 0),
(390, '2020_05_03_214910_add_foreign_keys_to_events_table', 0),
(391, '2020_05_03_214910_add_foreign_keys_to_expenses_table', 0),
(392, '2020_05_03_214910_add_foreign_keys_to_files_table', 0),
(393, '2020_05_03_214910_add_foreign_keys_to_group_settings_table', 0),
(394, '2020_05_03_214910_add_foreign_keys_to_invoice_invitations_table', 0),
(395, '2020_05_03_214910_add_foreign_keys_to_invoices_table', 0),
(396, '2020_05_03_214910_add_foreign_keys_to_leads_table', 0),
(397, '2020_05_03_214910_add_foreign_keys_to_messages_table', 0),
(398, '2020_05_03_214910_add_foreign_keys_to_order_invitations_table', 0),
(399, '2020_05_03_214910_add_foreign_keys_to_paymentables_table', 0),
(400, '2020_05_03_214910_add_foreign_keys_to_payments_table', 0),
(401, '2020_05_03_214910_add_foreign_keys_to_permission_user_table', 0),
(402, '2020_05_03_214910_add_foreign_keys_to_product_attributes_table', 0),
(403, '2020_05_03_214910_add_foreign_keys_to_product_images_table', 0),
(404, '2020_05_03_214910_add_foreign_keys_to_product_task_table', 0),
(405, '2020_05_03_214910_add_foreign_keys_to_products_table', 0),
(406, '2020_05_03_214910_add_foreign_keys_to_projects_table', 0),
(407, '2020_05_03_214910_add_foreign_keys_to_provinces_table', 0),
(408, '2020_05_03_214910_add_foreign_keys_to_quote_invitations_table', 0),
(409, '2020_05_03_214910_add_foreign_keys_to_quotes_table', 0),
(410, '2020_05_03_214910_add_foreign_keys_to_recurring_invoices_table', 0),
(411, '2020_05_03_214910_add_foreign_keys_to_recurring_quotes_table', 0),
(412, '2020_05_03_214910_add_foreign_keys_to_role_user_table', 0),
(413, '2020_05_03_214910_add_foreign_keys_to_roles_table', 0),
(414, '2020_05_03_214910_add_foreign_keys_to_states_table', 0),
(415, '2020_05_03_214910_add_foreign_keys_to_subscriptions_table', 0),
(416, '2020_05_03_214910_add_foreign_keys_to_system_logs_table', 0),
(417, '2020_05_03_214910_add_foreign_keys_to_task_user_table', 0),
(418, '2020_05_03_214910_add_foreign_keys_to_tasks_table', 0),
(419, '2020_05_03_214910_add_foreign_keys_to_tax_rates_table', 0),
(420, '2020_05_03_214910_add_foreign_keys_to_timers_table', 0),
(421, '2020_05_03_214910_add_foreign_keys_to_users_table', 0),
(422, '2020_05_04_170551_create_account_user_table', 0),
(423, '2020_05_04_170551_create_accounts_table', 0),
(424, '2020_05_04_170551_create_addresses_table', 0),
(425, '2020_05_04_170551_create_categories_table', 0),
(426, '2020_05_04_170551_create_category_product_table', 0),
(427, '2020_05_04_170551_create_cities_table', 0),
(428, '2020_05_04_170551_create_client_contacts_table', 0),
(429, '2020_05_04_170551_create_client_gateway_tokens_table', 0),
(430, '2020_05_04_170551_create_comment_task_table', 0),
(431, '2020_05_04_170551_create_comment_type_table', 0),
(432, '2020_05_04_170551_create_comments_table', 0),
(433, '2020_05_04_170551_create_companies_table', 0),
(434, '2020_05_04_170551_create_company_contacts_table', 0),
(435, '2020_05_04_170551_create_company_gateways_table', 0),
(436, '2020_05_04_170551_create_company_ledgers_table', 0),
(437, '2020_05_04_170551_create_company_tokens_table', 0),
(438, '2020_05_04_170551_create_company_user_table', 0),
(439, '2020_05_04_170551_create_countries_table', 0),
(440, '2020_05_04_170551_create_credit_invitations_table', 0),
(441, '2020_05_04_170551_create_credits_table', 0),
(442, '2020_05_04_170551_create_currencies_table', 0),
(443, '2020_05_04_170551_create_customer_type_table', 0),
(444, '2020_05_04_170551_create_customer_types_table', 0),
(445, '2020_05_04_170551_create_customers_table', 0),
(446, '2020_05_04_170551_create_date_formats_table', 0),
(447, '2020_05_04_170551_create_datetime_formats_table', 0),
(448, '2020_05_04_170551_create_department_user_table', 0),
(449, '2020_05_04_170551_create_departments_table', 0),
(450, '2020_05_04_170551_create_designs_table', 0),
(451, '2020_05_04_170551_create_domains_table', 0),
(452, '2020_05_04_170551_create_emails_table', 0),
(453, '2020_05_04_170551_create_event_status_table', 0),
(454, '2020_05_04_170551_create_event_task_table', 0),
(455, '2020_05_04_170551_create_event_types_table', 0),
(456, '2020_05_04_170551_create_event_user_table', 0),
(457, '2020_05_04_170551_create_events_table', 0),
(458, '2020_05_04_170551_create_expense_categories_table', 0),
(459, '2020_05_04_170551_create_expenses_table', 0),
(460, '2020_05_04_170551_create_files_table', 0),
(461, '2020_05_04_170551_create_form_category_table', 0),
(462, '2020_05_04_170551_create_frequencies_table', 0),
(463, '2020_05_04_170551_create_gateway_types_table', 0),
(464, '2020_05_04_170551_create_gateways_table', 0),
(465, '2020_05_04_170551_create_group_settings_table', 0),
(466, '2020_05_04_170551_create_industries_table', 0),
(467, '2020_05_04_170551_create_invoice_invitations_table', 0),
(468, '2020_05_04_170551_create_invoice_status_table', 0),
(469, '2020_05_04_170551_create_invoices_table', 0),
(470, '2020_05_04_170551_create_invoices_backup_table', 0),
(471, '2020_05_04_170551_create_languages_table', 0),
(472, '2020_05_04_170551_create_leads_table', 0),
(473, '2020_05_04_170551_create_messages_table', 0),
(474, '2020_05_04_170551_create_metrics_table', 0),
(475, '2020_05_04_170551_create_notifications_table', 0),
(476, '2020_05_04_170551_create_order_invitations_table', 0),
(477, '2020_05_04_170551_create_order_status_table', 0),
(478, '2020_05_04_170551_create_password_resets_table', 0),
(479, '2020_05_04_170551_create_payment_methods_table', 0),
(480, '2020_05_04_170551_create_payment_statuses_table', 0),
(481, '2020_05_04_170551_create_paymentables_table', 0),
(482, '2020_05_04_170551_create_payments_table', 0),
(483, '2020_05_04_170551_create_permission_role_table', 0),
(484, '2020_05_04_170551_create_permission_user_table', 0),
(485, '2020_05_04_170551_create_permissions_table', 0),
(486, '2020_05_04_170551_create_product_attributes_table', 0),
(487, '2020_05_04_170551_create_product_images_table', 0),
(488, '2020_05_04_170551_create_product_task_table', 0),
(489, '2020_05_04_170551_create_products_table', 0),
(490, '2020_05_04_170551_create_projects_table', 0),
(491, '2020_05_04_170551_create_provinces_table', 0),
(492, '2020_05_04_170551_create_quote_invitations_table', 0),
(493, '2020_05_04_170551_create_quotes_table', 0),
(494, '2020_05_04_170551_create_recurring_invoices_table', 0),
(495, '2020_05_04_170551_create_recurring_quotes_table', 0),
(496, '2020_05_04_170551_create_role_user_table', 0),
(497, '2020_05_04_170551_create_roles_table', 0),
(498, '2020_05_04_170551_create_source_type_table', 0),
(499, '2020_05_04_170551_create_states_table', 0),
(500, '2020_05_04_170551_create_subscriptions_table', 0),
(501, '2020_05_04_170551_create_system_logs_table', 0),
(502, '2020_05_04_170551_create_task_comment_table', 0),
(503, '2020_05_04_170551_create_task_statuses_table', 0),
(504, '2020_05_04_170551_create_task_type_table', 0),
(505, '2020_05_04_170551_create_task_user_table', 0),
(506, '2020_05_04_170551_create_tasks_table', 0),
(507, '2020_05_04_170551_create_tax_rates_table', 0),
(508, '2020_05_04_170551_create_timers_table', 0),
(509, '2020_05_04_170551_create_users_table', 0),
(510, '2020_05_04_170638_add_foreign_keys_to_account_user_table', 0),
(511, '2020_05_04_170638_add_foreign_keys_to_accounts_table', 0),
(512, '2020_05_04_170638_add_foreign_keys_to_addresses_table', 0),
(513, '2020_05_04_170638_add_foreign_keys_to_cities_table', 0),
(514, '2020_05_04_170638_add_foreign_keys_to_client_contacts_table', 0),
(515, '2020_05_04_170638_add_foreign_keys_to_client_gateway_tokens_table', 0),
(516, '2020_05_04_170638_add_foreign_keys_to_comment_task_table', 0),
(517, '2020_05_04_170638_add_foreign_keys_to_comments_table', 0),
(518, '2020_05_04_170638_add_foreign_keys_to_companies_table', 0),
(519, '2020_05_04_170638_add_foreign_keys_to_company_contacts_table', 0),
(520, '2020_05_04_170638_add_foreign_keys_to_company_gateways_table', 0),
(521, '2020_05_04_170638_add_foreign_keys_to_company_ledgers_table', 0),
(522, '2020_05_04_170638_add_foreign_keys_to_company_tokens_table', 0),
(523, '2020_05_04_170638_add_foreign_keys_to_credit_invitations_table', 0),
(524, '2020_05_04_170638_add_foreign_keys_to_credits_table', 0),
(525, '2020_05_04_170638_add_foreign_keys_to_customers_table', 0),
(526, '2020_05_04_170638_add_foreign_keys_to_department_user_table', 0),
(527, '2020_05_04_170638_add_foreign_keys_to_departments_table', 0),
(528, '2020_05_04_170638_add_foreign_keys_to_emails_table', 0),
(529, '2020_05_04_170638_add_foreign_keys_to_event_task_table', 0),
(530, '2020_05_04_170638_add_foreign_keys_to_event_user_table', 0),
(531, '2020_05_04_170638_add_foreign_keys_to_events_table', 0),
(532, '2020_05_04_170638_add_foreign_keys_to_expenses_table', 0),
(533, '2020_05_04_170638_add_foreign_keys_to_files_table', 0),
(534, '2020_05_04_170638_add_foreign_keys_to_group_settings_table', 0),
(535, '2020_05_04_170638_add_foreign_keys_to_invoice_invitations_table', 0),
(536, '2020_05_04_170638_add_foreign_keys_to_invoices_table', 0),
(537, '2020_05_04_170638_add_foreign_keys_to_leads_table', 0),
(538, '2020_05_04_170638_add_foreign_keys_to_messages_table', 0),
(539, '2020_05_04_170638_add_foreign_keys_to_order_invitations_table', 0),
(540, '2020_05_04_170638_add_foreign_keys_to_paymentables_table', 0),
(541, '2020_05_04_170638_add_foreign_keys_to_payments_table', 0),
(542, '2020_05_04_170638_add_foreign_keys_to_permission_user_table', 0),
(543, '2020_05_04_170638_add_foreign_keys_to_product_attributes_table', 0),
(544, '2020_05_04_170638_add_foreign_keys_to_product_images_table', 0),
(545, '2020_05_04_170638_add_foreign_keys_to_product_task_table', 0),
(546, '2020_05_04_170638_add_foreign_keys_to_products_table', 0),
(547, '2020_05_04_170638_add_foreign_keys_to_projects_table', 0),
(548, '2020_05_04_170638_add_foreign_keys_to_provinces_table', 0),
(549, '2020_05_04_170638_add_foreign_keys_to_quote_invitations_table', 0),
(550, '2020_05_04_170638_add_foreign_keys_to_quotes_table', 0),
(551, '2020_05_04_170638_add_foreign_keys_to_recurring_invoices_table', 0),
(552, '2020_05_04_170638_add_foreign_keys_to_recurring_quotes_table', 0),
(553, '2020_05_04_170638_add_foreign_keys_to_role_user_table', 0),
(554, '2020_05_04_170638_add_foreign_keys_to_roles_table', 0),
(555, '2020_05_04_170638_add_foreign_keys_to_states_table', 0),
(556, '2020_05_04_170638_add_foreign_keys_to_subscriptions_table', 0),
(557, '2020_05_04_170638_add_foreign_keys_to_system_logs_table', 0),
(558, '2020_05_04_170638_add_foreign_keys_to_task_user_table', 0),
(559, '2020_05_04_170638_add_foreign_keys_to_tasks_table', 0),
(560, '2020_05_04_170638_add_foreign_keys_to_tax_rates_table', 0),
(561, '2020_05_04_170638_add_foreign_keys_to_timers_table', 0),
(562, '2020_05_04_170638_add_foreign_keys_to_users_table', 0);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `notifiable_id` bigint(20) UNSIGNED NOT NULL,
  `data` text COLLATE utf8_unicode_ci NOT NULL,
  `read_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `account_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`, `account_id`) VALUES
(1, 'App\\Listeners\\Customer\\CustomerCreatedActivity', 'App\\Customer', 5, '{\"id\":5,\"message\":\"A new customer was created\"}', NULL, '2020-05-03 18:15:11', '2020-05-03 18:15:11', 1),
(2, 'App\\Listeners\\Invoice\\InvoiceCreatedActivity', 'App\\Invoice', 5, '{\"id\":1,\"message\":\"A new invoice was created\"}', NULL, '2020-05-03 19:22:13', '2020-05-03 19:22:13', 1),
(3, 'App\\Listeners\\Invoice\\InvoiceEmailActivity', 'App\\Invoice', 5, '{\"id\":1,\"message\":\"An invoice was emailed\",\"client_contact_id\":null}', NULL, '2020-05-03 19:36:31', '2020-05-03 19:36:31', 1),
(4, 'App\\Listeners\\Quote\\QuoteCreatedActivity', 'App\\Quote', 5, '{\"id\":1,\"message\":\"A quote was created\"}', NULL, '2020-05-03 19:37:09', '2020-05-03 19:37:09', 1),
(5, 'App\\Listeners\\Credit\\CreditCreatedActivity', 'App\\Credit', 5, '{\"id\":1,\"message\":\"A credit was created\"}', NULL, '2020-05-03 19:38:56', '2020-05-03 19:38:56', 1);

-- --------------------------------------------------------

--
-- Table structure for table `order_invitations`
--

DROP TABLE IF EXISTS `order_invitations`;
CREATE TABLE `order_invitations` (
  `id` int(10) UNSIGNED NOT NULL,
  `account_id` int(10) UNSIGNED NOT NULL,
  `client_contact_id` int(10) UNSIGNED NOT NULL,
  `order_id` int(10) UNSIGNED NOT NULL,
  `key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `transaction_reference` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `message_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email_error` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `signature_base64` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `signature_date` datetime DEFAULT NULL,
  `sent_date` datetime DEFAULT NULL,
  `viewed_date` datetime DEFAULT NULL,
  `opened_date` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `user_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_status`
--

DROP TABLE IF EXISTS `order_status`;
CREATE TABLE `order_status` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `paymentables`
--

DROP TABLE IF EXISTS `paymentables`;
CREATE TABLE `paymentables` (
  `payment_id` int(10) UNSIGNED NOT NULL,
  `paymentable_id` int(10) UNSIGNED NOT NULL,
  `amount` decimal(16,4) NOT NULL DEFAULT 0.0000,
  `paymentable_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `id` int(10) UNSIGNED NOT NULL,
  `refunded` decimal(16,4) NOT NULL DEFAULT 0.0000,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
CREATE TABLE `payments` (
  `id` int(10) UNSIGNED NOT NULL,
  `account_id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `assigned_user_id` int(10) UNSIGNED DEFAULT NULL,
  `invitation_id` int(10) UNSIGNED DEFAULT NULL,
  `company_gateway_id` int(10) UNSIGNED DEFAULT NULL,
  `type_id` int(10) UNSIGNED DEFAULT NULL,
  `status_id` int(10) UNSIGNED NOT NULL,
  `amount` decimal(16,4) NOT NULL DEFAULT 0.0000,
  `refunded` decimal(16,4) NOT NULL DEFAULT 0.0000,
  `date` date DEFAULT NULL,
  `transaction_reference` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payer_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `is_manual` tinyint(1) NOT NULL DEFAULT 0,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `applied` decimal(16,4) NOT NULL DEFAULT 0.0000,
  `custom_value1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_value2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_value3` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_value4` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `private_notes` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `client_contact_id` int(10) UNSIGNED DEFAULT NULL,
  `exchange_rate` decimal(16,6) NOT NULL DEFAULT 1.000000,
  `currency_id` int(10) UNSIGNED NOT NULL,
  `exchange_currency_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

DROP TABLE IF EXISTS `payment_methods`;
CREATE TABLE `payment_methods` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `gateway_type_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `payment_methods`
--

INSERT INTO `payment_methods` (`id`, `name`, `gateway_type_id`, `created_at`, `updated_at`) VALUES
(1, 'Apply Credit', NULL, '2020-05-03 17:42:04', '2020-05-03 17:42:04'),
(2, 'Bank Transfer', 2, '2020-05-03 17:42:04', '2020-05-03 17:42:04'),
(3, 'Cash', NULL, '2020-05-03 17:42:04', '2020-05-03 17:42:04'),
(4, 'Debit', 1, '2020-05-03 17:42:04', '2020-05-03 17:42:04'),
(5, 'ACH', 2, '2020-05-03 17:42:04', '2020-05-03 17:42:04'),
(6, 'Visa Card', 1, '2020-05-03 17:42:04', '2020-05-03 17:42:04'),
(7, 'MasterCard', 1, '2020-05-03 17:42:05', '2020-05-03 17:42:05'),
(8, 'American Express', 1, '2020-05-03 17:42:05', '2020-05-03 17:42:05'),
(9, 'Discover Card', 1, '2020-05-03 17:42:05', '2020-05-03 17:42:05'),
(10, 'Diners Card', 1, '2020-05-03 17:42:05', '2020-05-03 17:42:05'),
(11, 'EuroCard', 1, '2020-05-03 17:42:05', '2020-05-03 17:42:05'),
(12, 'Nova', 1, '2020-05-03 17:42:05', '2020-05-03 17:42:05'),
(13, 'Credit Card Other', 1, '2020-05-03 17:42:05', '2020-05-03 17:42:05'),
(14, 'PayPal', 3, '2020-05-03 17:42:05', '2020-05-03 17:42:05'),
(15, 'Google Wallet', NULL, '2020-05-03 17:42:05', '2020-05-03 17:42:05'),
(16, 'Check', NULL, '2020-05-03 17:42:05', '2020-05-03 17:42:05'),
(17, 'Carte Blanche', 1, '2020-05-03 17:42:05', '2020-05-03 17:42:05'),
(18, 'UnionPay', 1, '2020-05-03 17:42:05', '2020-05-03 17:42:05'),
(19, 'JCB', 1, '2020-05-03 17:42:05', '2020-05-03 17:42:05'),
(20, 'Laser', 1, '2020-05-03 17:42:05', '2020-05-03 17:42:05'),
(21, 'Maestro', 1, '2020-05-03 17:42:05', '2020-05-03 17:42:05'),
(22, 'Solo', 1, '2020-05-03 17:42:05', '2020-05-03 17:42:05'),
(23, 'Switch', 1, '2020-05-03 17:42:05', '2020-05-03 17:42:05'),
(24, 'iZettle', 1, '2020-05-03 17:42:05', '2020-05-03 17:42:05'),
(25, 'Swish', 2, '2020-05-03 17:42:05', '2020-05-03 17:42:05'),
(26, 'Venmo', NULL, '2020-05-03 17:42:05', '2020-05-03 17:42:05'),
(27, 'Money Order', NULL, '2020-05-03 17:42:05', '2020-05-03 17:42:05'),
(28, 'Alipay', 4, '2020-05-03 17:42:05', '2020-05-03 17:42:05'),
(29, 'Sofort', 5, '2020-05-03 17:42:05', '2020-05-03 17:42:05'),
(30, 'SEPA', 6, '2020-05-03 17:42:05', '2020-05-03 17:42:05'),
(31, 'GoCardless', 7, '2020-05-03 17:42:05', '2020-05-03 17:42:05'),
(32, 'Crypto', 8, '2020-05-03 17:42:05', '2020-05-03 17:42:05');

-- --------------------------------------------------------

--
-- Table structure for table `payment_statuses`
--

DROP TABLE IF EXISTS `payment_statuses`;
CREATE TABLE `payment_statuses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `payment_statuses`
--

INSERT INTO `payment_statuses` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Pending', '2020-05-03 17:42:07', '2020-05-03 17:42:07'),
(2, 'Voided', '2020-05-03 17:42:07', '2020-05-03 17:42:07'),
(3, 'Failed', '2020-05-03 17:42:07', '2020-05-03 17:42:07'),
(4, 'Completed', '2020-05-03 17:42:07', '2020-05-03 17:42:07'),
(5, 'Partially Refunded', '2020-05-03 17:42:08', '2020-05-03 17:42:08'),
(6, 'Refunded', '2020-05-03 17:42:08', '2020-05-03 17:42:08');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `created_at`, `updated_at`, `deleted_at`, `description`) VALUES
(1, 'dashboardstatscontroller.index', '2020-05-03 17:25:25', '2020-05-03 17:25:25', NULL, NULL),
(2, 'workloadcontroller.index', '2020-05-03 17:25:25', '2020-05-03 17:25:25', NULL, NULL),
(3, 'mastersupervisorcontroller.index', '2020-05-03 17:25:25', '2020-05-03 17:25:25', NULL, NULL),
(4, 'monitoringcontroller.index', '2020-05-03 17:25:26', '2020-05-03 17:25:26', NULL, NULL),
(5, 'monitoringcontroller.store', '2020-05-03 17:25:26', '2020-05-03 17:25:26', NULL, NULL),
(6, 'monitoringcontroller.paginate', '2020-05-03 17:25:26', '2020-05-03 17:25:26', NULL, NULL),
(7, 'monitoringcontroller.destroy', '2020-05-03 17:25:26', '2020-05-03 17:25:26', NULL, NULL),
(8, 'jobmetricscontroller.index', '2020-05-03 17:25:26', '2020-05-03 17:25:26', NULL, NULL),
(9, 'jobmetricscontroller.show', '2020-05-03 17:25:26', '2020-05-03 17:25:26', NULL, NULL),
(10, 'queuemetricscontroller.index', '2020-05-03 17:25:26', '2020-05-03 17:25:26', NULL, NULL),
(11, 'queuemetricscontroller.show', '2020-05-03 17:25:26', '2020-05-03 17:25:26', NULL, NULL),
(12, 'recentjobscontroller.index', '2020-05-03 17:25:26', '2020-05-03 17:25:26', NULL, NULL),
(13, 'recentjobscontroller.show', '2020-05-03 17:25:26', '2020-05-03 17:25:26', NULL, NULL),
(14, 'failedjobscontroller.index', '2020-05-03 17:25:26', '2020-05-03 17:25:26', NULL, NULL),
(15, 'failedjobscontroller.show', '2020-05-03 17:25:26', '2020-05-03 17:25:26', NULL, NULL),
(16, 'retrycontroller.store', '2020-05-03 17:25:26', '2020-05-03 17:25:26', NULL, NULL),
(17, 'homecontroller.index', '2020-05-03 17:25:26', '2020-05-03 17:25:26', NULL, NULL),
(18, 'losure.closure', '2020-05-03 17:25:26', '2020-05-03 17:25:26', NULL, NULL),
(19, 'taskstatuscontroller.index', '2020-05-03 17:25:26', '2020-05-03 17:25:26', NULL, NULL),
(20, 'dashboardcontroller.index', '2020-05-03 17:25:26', '2020-05-03 17:25:26', NULL, NULL),
(21, 'activitycontroller.index', '2020-05-03 17:25:26', '2020-05-03 17:25:26', NULL, NULL),
(22, 'support\\messages\\sendingcontroller.app\\http\\controllers\\support\\messages\\sendingcontroller', '2020-05-03 17:25:26', '2020-05-03 17:25:26', NULL, NULL),
(23, 'companyledgercontroller.index', '2020-05-03 17:25:26', '2020-05-03 17:25:26', NULL, NULL),
(24, 'tokencontroller.index', '2020-05-03 17:25:26', '2020-05-03 17:25:26', NULL, NULL),
(25, 'tokencontroller.create', '2020-05-03 17:25:26', '2020-05-03 17:25:26', NULL, NULL),
(26, 'tokencontroller.store', '2020-05-03 17:25:27', '2020-05-03 17:25:27', NULL, NULL),
(27, 'tokencontroller.show', '2020-05-03 17:25:27', '2020-05-03 17:25:27', NULL, NULL),
(28, 'tokencontroller.edit', '2020-05-03 17:25:27', '2020-05-03 17:25:27', NULL, NULL),
(29, 'tokencontroller.update', '2020-05-03 17:25:27', '2020-05-03 17:25:27', NULL, NULL),
(30, 'tokencontroller.destroy', '2020-05-03 17:25:27', '2020-05-03 17:25:27', NULL, NULL),
(31, 'subscriptioncontroller.subscribe', '2020-05-03 17:25:27', '2020-05-03 17:25:27', NULL, NULL),
(32, 'subscriptioncontroller.unsubscribe', '2020-05-03 17:25:27', '2020-05-03 17:25:27', NULL, NULL),
(33, 'subscriptioncontroller.index', '2020-05-03 17:25:27', '2020-05-03 17:25:27', NULL, NULL),
(34, 'subscriptioncontroller.create', '2020-05-03 17:25:27', '2020-05-03 17:25:27', NULL, NULL),
(35, 'subscriptioncontroller.store', '2020-05-03 17:25:27', '2020-05-03 17:25:27', NULL, NULL),
(36, 'subscriptioncontroller.show', '2020-05-03 17:25:27', '2020-05-03 17:25:27', NULL, NULL),
(37, 'subscriptioncontroller.edit', '2020-05-03 17:25:27', '2020-05-03 17:25:27', NULL, NULL),
(38, 'subscriptioncontroller.update', '2020-05-03 17:25:27', '2020-05-03 17:25:27', NULL, NULL),
(39, 'subscriptioncontroller.destroy', '2020-05-03 17:25:27', '2020-05-03 17:25:27', NULL, NULL),
(40, 'designcontroller.index', '2020-05-03 17:25:27', '2020-05-03 17:25:27', NULL, NULL),
(41, 'designcontroller.create', '2020-05-03 17:25:27', '2020-05-03 17:25:27', NULL, NULL),
(42, 'designcontroller.store', '2020-05-03 17:25:27', '2020-05-03 17:25:27', NULL, NULL),
(43, 'designcontroller.show', '2020-05-03 17:25:27', '2020-05-03 17:25:27', NULL, NULL),
(44, 'designcontroller.edit', '2020-05-03 17:25:27', '2020-05-03 17:25:27', NULL, NULL),
(45, 'designcontroller.update', '2020-05-03 17:25:27', '2020-05-03 17:25:27', NULL, NULL),
(46, 'designcontroller.destroy', '2020-05-03 17:25:28', '2020-05-03 17:25:28', NULL, NULL),
(47, 'messagecontroller.getcustomers', '2020-05-03 17:25:28', '2020-05-03 17:25:28', NULL, NULL),
(48, 'messagecontroller.index', '2020-05-03 17:25:28', '2020-05-03 17:25:28', NULL, NULL),
(49, 'messagecontroller.store', '2020-05-03 17:25:28', '2020-05-03 17:25:28', NULL, NULL),
(50, 'companycontroller.index', '2020-05-03 17:25:28', '2020-05-03 17:25:28', NULL, NULL),
(51, 'companycontroller.restore', '2020-05-03 17:25:28', '2020-05-03 17:25:28', NULL, NULL),
(52, 'companycontroller.store', '2020-05-03 17:25:28', '2020-05-03 17:25:28', NULL, NULL),
(53, 'companycontroller.archive', '2020-05-03 17:25:28', '2020-05-03 17:25:28', NULL, NULL),
(54, 'companycontroller.destroy', '2020-05-03 17:25:28', '2020-05-03 17:25:28', NULL, NULL),
(55, 'companycontroller.show', '2020-05-03 17:25:28', '2020-05-03 17:25:28', NULL, NULL),
(56, 'companycontroller.update', '2020-05-03 17:25:28', '2020-05-03 17:25:28', NULL, NULL),
(57, 'companycontroller.getindustries', '2020-05-03 17:25:28', '2020-05-03 17:25:28', NULL, NULL),
(58, 'categorycontroller.index', '2020-05-03 17:25:28', '2020-05-03 17:25:28', NULL, NULL),
(59, 'categorycontroller.store', '2020-05-03 17:25:28', '2020-05-03 17:25:28', NULL, NULL),
(60, 'categorycontroller.destroy', '2020-05-03 17:25:28', '2020-05-03 17:25:28', NULL, NULL),
(61, 'categorycontroller.edit', '2020-05-03 17:25:28', '2020-05-03 17:25:28', NULL, NULL),
(62, 'categorycontroller.update', '2020-05-03 17:25:28', '2020-05-03 17:25:28', NULL, NULL),
(63, 'commentcontroller.index', '2020-05-03 17:25:28', '2020-05-03 17:25:28', NULL, NULL),
(64, 'commentcontroller.destroy', '2020-05-03 17:25:28', '2020-05-03 17:25:28', NULL, NULL),
(65, 'commentcontroller.update', '2020-05-03 17:25:28', '2020-05-03 17:25:28', NULL, NULL),
(66, 'commentcontroller.store', '2020-05-03 17:25:29', '2020-05-03 17:25:29', NULL, NULL),
(67, 'eventcontroller.index', '2020-05-03 17:25:29', '2020-05-03 17:25:29', NULL, NULL),
(68, 'eventcontroller.archive', '2020-05-03 17:25:29', '2020-05-03 17:25:29', NULL, NULL),
(69, 'eventcontroller.destroy', '2020-05-03 17:25:29', '2020-05-03 17:25:29', NULL, NULL),
(70, 'eventcontroller.update', '2020-05-03 17:25:29', '2020-05-03 17:25:29', NULL, NULL),
(71, 'eventcontroller.show', '2020-05-03 17:25:29', '2020-05-03 17:25:29', NULL, NULL),
(72, 'eventcontroller.store', '2020-05-03 17:25:29', '2020-05-03 17:25:29', NULL, NULL),
(73, 'eventcontroller.geteventsfortask', '2020-05-03 17:25:29', '2020-05-03 17:25:29', NULL, NULL),
(74, 'eventcontroller.geteventsforuser', '2020-05-03 17:25:29', '2020-05-03 17:25:29', NULL, NULL),
(75, 'eventcontroller.geteventtypes', '2020-05-03 17:25:29', '2020-05-03 17:25:29', NULL, NULL),
(76, 'eventcontroller.filterevents', '2020-05-03 17:25:29', '2020-05-03 17:25:29', NULL, NULL),
(77, 'eventcontroller.updateeventstatus', '2020-05-03 17:25:29', '2020-05-03 17:25:29', NULL, NULL),
(78, 'eventcontroller.restore', '2020-05-03 17:25:29', '2020-05-03 17:25:29', NULL, NULL),
(79, 'productcontroller.index', '2020-05-03 17:25:29', '2020-05-03 17:25:29', NULL, NULL),
(80, 'productcontroller.store', '2020-05-03 17:25:29', '2020-05-03 17:25:29', NULL, NULL),
(81, 'productcontroller.bulk', '2020-05-03 17:25:29', '2020-05-03 17:25:29', NULL, NULL),
(82, 'productcontroller.archive', '2020-05-03 17:25:29', '2020-05-03 17:25:29', NULL, NULL),
(83, 'productcontroller.destroy', '2020-05-03 17:25:29', '2020-05-03 17:25:29', NULL, NULL),
(84, 'productcontroller.removethumbnail', '2020-05-03 17:25:29', '2020-05-03 17:25:29', NULL, NULL),
(85, 'productcontroller.update', '2020-05-03 17:25:29', '2020-05-03 17:25:29', NULL, NULL),
(86, 'ordercontroller.getorderfortask', '2020-05-03 17:25:29', '2020-05-03 17:25:29', NULL, NULL),
(87, 'productcontroller.filterproducts', '2020-05-03 17:25:29', '2020-05-03 17:25:29', NULL, NULL),
(88, 'productcontroller.getproduct', '2020-05-03 17:25:29', '2020-05-03 17:25:29', NULL, NULL),
(89, 'productcontroller.restore', '2020-05-03 17:25:30', '2020-05-03 17:25:30', NULL, NULL),
(90, 'projectcontroller.index', '2020-05-03 17:25:30', '2020-05-03 17:25:30', NULL, NULL),
(91, 'projectcontroller.store', '2020-05-03 17:25:30', '2020-05-03 17:25:30', NULL, NULL),
(92, 'projectcontroller.show', '2020-05-03 17:25:30', '2020-05-03 17:25:30', NULL, NULL),
(93, 'projectcontroller.update', '2020-05-03 17:25:30', '2020-05-03 17:25:30', NULL, NULL),
(94, 'projectcontroller.archive', '2020-05-03 17:25:30', '2020-05-03 17:25:30', NULL, NULL),
(95, 'projectcontroller.destroy', '2020-05-03 17:25:30', '2020-05-03 17:25:30', NULL, NULL),
(96, 'projectcontroller.restore', '2020-05-03 17:25:30', '2020-05-03 17:25:30', NULL, NULL),
(97, 'ordercontroller.update', '2020-05-03 17:25:30', '2020-05-03 17:25:30', NULL, NULL),
(98, 'ordercontroller.store', '2020-05-03 17:25:30', '2020-05-03 17:25:30', NULL, NULL),
(99, 'ordercontroller.action', '2020-05-03 17:25:30', '2020-05-03 17:25:30', NULL, NULL),
(100, 'uploadcontroller.store', '2020-05-03 17:25:30', '2020-05-03 17:25:30', NULL, NULL),
(101, 'uploadcontroller.index', '2020-05-03 17:25:30', '2020-05-03 17:25:30', NULL, NULL),
(102, 'uploadcontroller.destroy', '2020-05-03 17:25:30', '2020-05-03 17:25:30', NULL, NULL),
(103, 'taskstatuscontroller.search', '2020-05-03 17:25:30', '2020-05-03 17:25:30', NULL, NULL),
(104, 'taskstatuscontroller.store', '2020-05-03 17:25:30', '2020-05-03 17:25:30', NULL, NULL),
(105, 'taskstatuscontroller.update', '2020-05-03 17:25:30', '2020-05-03 17:25:30', NULL, NULL),
(106, 'taskstatuscontroller.destroy', '2020-05-03 17:25:30', '2020-05-03 17:25:30', NULL, NULL),
(107, 'invoicecontroller.store', '2020-05-03 17:25:30', '2020-05-03 17:25:30', NULL, NULL),
(108, 'invoicecontroller.archive', '2020-05-03 17:25:30', '2020-05-03 17:25:30', NULL, NULL),
(109, 'invoicecontroller.destroy', '2020-05-03 17:25:30', '2020-05-03 17:25:30', NULL, NULL),
(110, 'invoicecontroller.restore', '2020-05-03 17:25:30', '2020-05-03 17:25:30', NULL, NULL),
(111, 'invoicecontroller.bulk', '2020-05-03 17:25:30', '2020-05-03 17:25:30', NULL, NULL),
(112, 'invoicecontroller.index', '2020-05-03 17:25:31', '2020-05-03 17:25:31', NULL, NULL),
(113, 'invoicecontroller.getinvoicelinesfortask', '2020-05-03 17:25:31', '2020-05-03 17:25:31', NULL, NULL),
(114, 'invoicecontroller.show', '2020-05-03 17:25:31', '2020-05-03 17:25:31', NULL, NULL),
(115, 'invoicecontroller.update', '2020-05-03 17:25:31', '2020-05-03 17:25:31', NULL, NULL),
(116, 'invoicecontroller.getinvoicesbystatus', '2020-05-03 17:25:31', '2020-05-03 17:25:31', NULL, NULL),
(117, 'invoicecontroller.action', '2020-05-03 17:25:31', '2020-05-03 17:25:31', NULL, NULL),
(118, 'recurringinvoicecontroller.store', '2020-05-03 17:25:31', '2020-05-03 17:25:31', NULL, NULL),
(119, 'recurringinvoicecontroller.bulk', '2020-05-03 17:25:31', '2020-05-03 17:25:31', NULL, NULL),
(120, 'recurringinvoicecontroller.update', '2020-05-03 17:25:31', '2020-05-03 17:25:31', NULL, NULL),
(121, 'recurringinvoicecontroller.archive', '2020-05-03 17:25:31', '2020-05-03 17:25:31', NULL, NULL),
(122, 'recurringinvoicecontroller.destroy', '2020-05-03 17:25:31', '2020-05-03 17:25:31', NULL, NULL),
(123, 'recurringinvoicecontroller.index', '2020-05-03 17:25:31', '2020-05-03 17:25:31', NULL, NULL),
(124, 'recurringinvoicecontroller.restore', '2020-05-03 17:25:31', '2020-05-03 17:25:31', NULL, NULL),
(125, 'recurringquotecontroller.update', '2020-05-03 17:25:31', '2020-05-03 17:25:31', NULL, NULL),
(126, 'recurringquotecontroller.index', '2020-05-03 17:25:31', '2020-05-03 17:25:31', NULL, NULL),
(127, 'recurringquotecontroller.store', '2020-05-03 17:25:31', '2020-05-03 17:25:31', NULL, NULL),
(128, 'recurringquotecontroller.bulk', '2020-05-03 17:25:31', '2020-05-03 17:25:31', NULL, NULL),
(129, 'recurringquotecontroller.archive', '2020-05-03 17:25:31', '2020-05-03 17:25:31', NULL, NULL),
(130, 'recurringquotecontroller.destroy', '2020-05-03 17:25:31', '2020-05-03 17:25:31', NULL, NULL),
(131, 'recurringquotecontroller.restore', '2020-05-03 17:25:32', '2020-05-03 17:25:32', NULL, NULL),
(132, 'creditcontroller.store', '2020-05-03 17:25:32', '2020-05-03 17:25:32', NULL, NULL),
(133, 'creditcontroller.archive', '2020-05-03 17:25:32', '2020-05-03 17:25:32', NULL, NULL),
(134, 'creditcontroller.destroy', '2020-05-03 17:25:32', '2020-05-03 17:25:32', NULL, NULL),
(135, 'creditcontroller.index', '2020-05-03 17:25:32', '2020-05-03 17:25:32', NULL, NULL),
(136, 'creditcontroller.update', '2020-05-03 17:25:32', '2020-05-03 17:25:32', NULL, NULL),
(137, 'creditcontroller.restore', '2020-05-03 17:25:32', '2020-05-03 17:25:32', NULL, NULL),
(138, 'creditcontroller.action', '2020-05-03 17:25:32', '2020-05-03 17:25:32', NULL, NULL),
(139, 'expensecontroller.store', '2020-05-03 17:25:32', '2020-05-03 17:25:32', NULL, NULL),
(140, 'expensecontroller.archive', '2020-05-03 17:25:32', '2020-05-03 17:25:32', NULL, NULL),
(141, 'expensecontroller.destroy', '2020-05-03 17:25:32', '2020-05-03 17:25:32', NULL, NULL),
(142, 'expensecontroller.index', '2020-05-03 17:25:32', '2020-05-03 17:25:32', NULL, NULL),
(143, 'expensecontroller.update', '2020-05-03 17:25:32', '2020-05-03 17:25:32', NULL, NULL),
(144, 'expensecontroller.restore', '2020-05-03 17:25:32', '2020-05-03 17:25:32', NULL, NULL),
(145, 'quotecontroller.convert', '2020-05-03 17:25:32', '2020-05-03 17:25:32', NULL, NULL),
(146, 'quotecontroller.archive', '2020-05-03 17:25:32', '2020-05-03 17:25:32', NULL, NULL),
(147, 'quotecontroller.destroy', '2020-05-03 17:25:32', '2020-05-03 17:25:32', NULL, NULL),
(148, 'quotecontroller.approve', '2020-05-03 17:25:32', '2020-05-03 17:25:32', NULL, NULL),
(149, 'quotecontroller.store', '2020-05-03 17:25:32', '2020-05-03 17:25:32', NULL, NULL),
(150, 'quotecontroller.update', '2020-05-03 17:25:32', '2020-05-03 17:25:32', NULL, NULL),
(151, 'quotecontroller.index', '2020-05-03 17:25:33', '2020-05-03 17:25:33', NULL, NULL),
(152, 'quotecontroller.show', '2020-05-03 17:25:33', '2020-05-03 17:25:33', NULL, NULL),
(153, 'quotecontroller.action', '2020-05-03 17:25:33', '2020-05-03 17:25:33', NULL, NULL),
(154, 'quotecontroller.getquotelinesfortask', '2020-05-03 17:25:33', '2020-05-03 17:25:33', NULL, NULL),
(155, 'quotecontroller.restore', '2020-05-03 17:25:33', '2020-05-03 17:25:33', NULL, NULL),
(156, 'accountcontroller.store', '2020-05-03 17:25:33', '2020-05-03 17:25:33', NULL, NULL),
(157, 'accountcontroller.savecustomfields', '2020-05-03 17:25:33', '2020-05-03 17:25:33', NULL, NULL),
(158, 'accountcontroller.update', '2020-05-03 17:25:33', '2020-05-03 17:25:33', NULL, NULL),
(159, 'accountcontroller.getallcustomfields', '2020-05-03 17:25:33', '2020-05-03 17:25:33', NULL, NULL),
(160, 'accountcontroller.getcustomfields', '2020-05-03 17:25:33', '2020-05-03 17:25:33', NULL, NULL),
(161, 'accountcontroller.index', '2020-05-03 17:25:33', '2020-05-03 17:25:33', NULL, NULL),
(162, 'accountcontroller.show', '2020-05-03 17:25:33', '2020-05-03 17:25:33', NULL, NULL),
(163, 'accountcontroller.getdateformats', '2020-05-03 17:25:33', '2020-05-03 17:25:33', NULL, NULL),
(164, 'accountcontroller.destroy', '2020-05-03 17:25:34', '2020-05-03 17:25:34', NULL, NULL),
(165, 'emailcontroller.send', '2020-05-03 17:25:34', '2020-05-03 17:25:34', NULL, NULL),
(166, 'accountcontroller.changeaccount', '2020-05-03 17:25:34', '2020-05-03 17:25:34', NULL, NULL),
(167, 'groupsettingcontroller.index', '2020-05-03 17:25:34', '2020-05-03 17:25:34', NULL, NULL),
(168, 'groupsettingcontroller.archive', '2020-05-03 17:25:34', '2020-05-03 17:25:34', NULL, NULL),
(169, 'groupsettingcontroller.destroy', '2020-05-03 17:25:34', '2020-05-03 17:25:34', NULL, NULL),
(170, 'groupsettingcontroller.update', '2020-05-03 17:25:34', '2020-05-03 17:25:34', NULL, NULL),
(171, 'groupsettingcontroller.store', '2020-05-03 17:25:34', '2020-05-03 17:25:34', NULL, NULL),
(172, 'groupsettingcontroller.restore', '2020-05-03 17:25:34', '2020-05-03 17:25:34', NULL, NULL),
(173, 'templatecontroller.show', '2020-05-03 17:25:34', '2020-05-03 17:25:34', NULL, NULL),
(174, 'companygatewaycontroller.index', '2020-05-03 17:25:35', '2020-05-03 17:25:35', NULL, NULL),
(175, 'companygatewaycontroller.show', '2020-05-03 17:25:35', '2020-05-03 17:25:35', NULL, NULL),
(176, 'companygatewaycontroller.update', '2020-05-03 17:25:35', '2020-05-03 17:25:35', NULL, NULL),
(177, 'companygatewaycontroller.store', '2020-05-03 17:25:35', '2020-05-03 17:25:35', NULL, NULL),
(178, 'taxratecontroller.index', '2020-05-03 17:25:35', '2020-05-03 17:25:35', NULL, NULL),
(179, 'taxratecontroller.store', '2020-05-03 17:25:35', '2020-05-03 17:25:35', NULL, NULL),
(180, 'taxratecontroller.archive', '2020-05-03 17:25:35', '2020-05-03 17:25:35', NULL, NULL),
(181, 'taxratecontroller.destroy', '2020-05-03 17:25:35', '2020-05-03 17:25:35', NULL, NULL),
(182, 'taxratecontroller.edit', '2020-05-03 17:25:35', '2020-05-03 17:25:35', NULL, NULL),
(183, 'taxratecontroller.update', '2020-05-03 17:25:35', '2020-05-03 17:25:35', NULL, NULL),
(184, 'taxratecontroller.restore', '2020-05-03 17:25:35', '2020-05-03 17:25:35', NULL, NULL),
(185, 'paymentcontroller.index', '2020-05-03 17:25:35', '2020-05-03 17:25:35', NULL, NULL),
(186, 'paymentcontroller.show', '2020-05-03 17:25:35', '2020-05-03 17:25:35', NULL, NULL),
(187, 'paymentcontroller.store', '2020-05-03 17:25:35', '2020-05-03 17:25:35', NULL, NULL),
(188, 'paymentcontroller.bulk', '2020-05-03 17:25:35', '2020-05-03 17:25:35', NULL, NULL),
(189, 'paymentcontroller.archive', '2020-05-03 17:25:35', '2020-05-03 17:25:35', NULL, NULL),
(190, 'paymentcontroller.destroy', '2020-05-03 17:25:35', '2020-05-03 17:25:35', NULL, NULL),
(191, 'paymentcontroller.update', '2020-05-03 17:25:35', '2020-05-03 17:25:35', NULL, NULL),
(192, 'paymentcontroller.refund', '2020-05-03 17:25:35', '2020-05-03 17:25:35', NULL, NULL),
(193, 'paymentcontroller.action', '2020-05-03 17:25:35', '2020-05-03 17:25:35', NULL, NULL),
(194, 'paymentcontroller.restore', '2020-05-03 17:25:35', '2020-05-03 17:25:35', NULL, NULL),
(195, 'paymenttypecontroller.index', '2020-05-03 17:25:35', '2020-05-03 17:25:35', NULL, NULL),
(196, 'customercontroller.index', '2020-05-03 17:25:36', '2020-05-03 17:25:36', NULL, NULL),
(197, 'customercontroller.dashboard', '2020-05-03 17:25:36', '2020-05-03 17:25:36', NULL, NULL),
(198, 'customercontroller.show', '2020-05-03 17:25:36', '2020-05-03 17:25:36', NULL, NULL),
(199, 'customercontroller.update', '2020-05-03 17:25:36', '2020-05-03 17:25:36', NULL, NULL),
(200, 'customercontroller.store', '2020-05-03 17:25:36', '2020-05-03 17:25:36', NULL, NULL),
(201, 'customercontroller.bulk', '2020-05-03 17:25:36', '2020-05-03 17:25:36', NULL, NULL),
(202, 'customercontroller.archive', '2020-05-03 17:25:36', '2020-05-03 17:25:36', NULL, NULL),
(203, 'customercontroller.destroy', '2020-05-03 17:25:36', '2020-05-03 17:25:36', NULL, NULL),
(204, 'customercontroller.getcustomertypes', '2020-05-03 17:25:36', '2020-05-03 17:25:36', NULL, NULL),
(205, 'customercontroller.restore', '2020-05-03 17:25:36', '2020-05-03 17:25:36', NULL, NULL),
(206, 'taskcontroller.restore', '2020-05-03 17:25:36', '2020-05-03 17:25:36', NULL, NULL),
(207, 'taskcontroller.update', '2020-05-03 17:25:36', '2020-05-03 17:25:36', NULL, NULL),
(208, 'taskcontroller.store', '2020-05-03 17:25:36', '2020-05-03 17:25:36', NULL, NULL),
(209, 'taskcontroller.gettasksforproject', '2020-05-03 17:25:36', '2020-05-03 17:25:36', NULL, NULL),
(210, 'taskcontroller.markascompleted', '2020-05-03 17:25:36', '2020-05-03 17:25:36', NULL, NULL),
(211, 'taskcontroller.destroy', '2020-05-03 17:25:36', '2020-05-03 17:25:36', NULL, NULL),
(212, 'taskcontroller.updatestatus', '2020-05-03 17:25:36', '2020-05-03 17:25:36', NULL, NULL),
(213, 'taskcontroller.getdeals', '2020-05-03 17:25:36', '2020-05-03 17:25:36', NULL, NULL),
(214, 'taskcontroller.index', '2020-05-03 17:25:36', '2020-05-03 17:25:36', NULL, NULL),
(215, 'taskcontroller.getsubtasks', '2020-05-03 17:25:37', '2020-05-03 17:25:37', NULL, NULL),
(216, 'taskcontroller.getproducts', '2020-05-03 17:25:37', '2020-05-03 17:25:37', NULL, NULL),
(217, 'taskcontroller.gettaskswithproducts', '2020-05-03 17:25:37', '2020-05-03 17:25:37', NULL, NULL),
(218, 'taskcontroller.getsourcetypes', '2020-05-03 17:25:37', '2020-05-03 17:25:37', NULL, NULL),
(219, 'taskcontroller.gettasktypes', '2020-05-03 17:25:37', '2020-05-03 17:25:37', NULL, NULL),
(220, 'taskcontroller.converttodeal', '2020-05-03 17:25:37', '2020-05-03 17:25:37', NULL, NULL),
(221, 'taskcontroller.updatetimer', '2020-05-03 17:25:37', '2020-05-03 17:25:37', NULL, NULL),
(222, 'taskcontroller.archive', '2020-05-03 17:25:37', '2020-05-03 17:25:37', NULL, NULL),
(223, 'leadcontroller.index', '2020-05-03 17:25:37', '2020-05-03 17:25:37', NULL, NULL),
(224, 'leadcontroller.update', '2020-05-03 17:25:37', '2020-05-03 17:25:37', NULL, NULL),
(225, 'leadcontroller.archive', '2020-05-03 17:25:37', '2020-05-03 17:25:37', NULL, NULL),
(226, 'leadcontroller.destroy', '2020-05-03 17:25:37', '2020-05-03 17:25:37', NULL, NULL),
(227, 'leadcontroller.restore', '2020-05-03 17:25:37', '2020-05-03 17:25:37', NULL, NULL),
(228, 'usercontroller.archive', '2020-05-03 17:25:37', '2020-05-03 17:25:37', NULL, NULL),
(229, 'usercontroller.destroy', '2020-05-03 17:25:37', '2020-05-03 17:25:37', NULL, NULL),
(230, 'usercontroller.store', '2020-05-03 17:25:37', '2020-05-03 17:25:37', NULL, NULL),
(231, 'usercontroller.dashboard', '2020-05-03 17:25:37', '2020-05-03 17:25:37', NULL, NULL),
(232, 'usercontroller.edit', '2020-05-03 17:25:37', '2020-05-03 17:25:37', NULL, NULL),
(233, 'usercontroller.update', '2020-05-03 17:25:37', '2020-05-03 17:25:37', NULL, NULL),
(234, 'usercontroller.index', '2020-05-03 17:25:38', '2020-05-03 17:25:38', NULL, NULL),
(235, 'usercontroller.upload', '2020-05-03 17:25:38', '2020-05-03 17:25:38', NULL, NULL),
(236, 'usercontroller.bulk', '2020-05-03 17:25:38', '2020-05-03 17:25:38', NULL, NULL),
(237, 'usercontroller.profile', '2020-05-03 17:25:38', '2020-05-03 17:25:38', NULL, NULL),
(238, 'usercontroller.filterusersbydepartment', '2020-05-03 17:25:38', '2020-05-03 17:25:38', NULL, NULL),
(239, 'usercontroller.restore', '2020-05-03 17:25:38', '2020-05-03 17:25:38', NULL, NULL),
(240, 'permissioncontroller.index', '2020-05-03 17:25:38', '2020-05-03 17:25:38', NULL, NULL),
(241, 'permissioncontroller.store', '2020-05-03 17:25:38', '2020-05-03 17:25:38', NULL, NULL),
(242, 'permissioncontroller.destroy', '2020-05-03 17:25:38', '2020-05-03 17:25:38', NULL, NULL),
(243, 'permissioncontroller.edit', '2020-05-03 17:25:38', '2020-05-03 17:25:38', NULL, NULL),
(244, 'permissioncontroller.update', '2020-05-03 17:25:39', '2020-05-03 17:25:39', NULL, NULL),
(245, 'rolecontroller.index', '2020-05-03 17:25:39', '2020-05-03 17:25:39', NULL, NULL),
(246, 'rolecontroller.store', '2020-05-03 17:25:39', '2020-05-03 17:25:39', NULL, NULL),
(247, 'rolecontroller.destroy', '2020-05-03 17:25:39', '2020-05-03 17:25:39', NULL, NULL),
(248, 'rolecontroller.edit', '2020-05-03 17:25:39', '2020-05-03 17:25:39', NULL, NULL),
(249, 'rolecontroller.update', '2020-05-03 17:25:39', '2020-05-03 17:25:39', NULL, NULL),
(250, 'departmentcontroller.index', '2020-05-03 17:25:39', '2020-05-03 17:25:39', NULL, NULL),
(251, 'departmentcontroller.store', '2020-05-03 17:25:39', '2020-05-03 17:25:39', NULL, NULL),
(252, 'departmentcontroller.destroy', '2020-05-03 17:25:39', '2020-05-03 17:25:39', NULL, NULL),
(253, 'departmentcontroller.edit', '2020-05-03 17:25:39', '2020-05-03 17:25:39', NULL, NULL),
(254, 'departmentcontroller.update', '2020-05-03 17:25:39', '2020-05-03 17:25:39', NULL, NULL),
(255, 'countrycontroller.index', '2020-05-03 17:25:39', '2020-05-03 17:25:39', NULL, NULL),
(256, 'currencycontroller.index', '2020-05-03 17:25:40', '2020-05-03 17:25:40', NULL, NULL),
(257, 'logincontroller.showlogin', '2020-05-03 17:25:40', '2020-05-03 17:25:40', NULL, NULL),
(258, 'logincontroller.dologin', '2020-05-03 17:25:40', '2020-05-03 17:25:40', NULL, NULL),
(259, 'logincontroller.dologout', '2020-05-03 17:25:40', '2020-05-03 17:25:40', NULL, NULL),
(260, 'auth\\passwordresetcontroller.create', '2020-05-03 17:25:40', '2020-05-03 17:25:40', NULL, NULL),
(261, 'passwordresetcontroller.find', '2020-05-03 17:25:40', '2020-05-03 17:25:40', NULL, NULL),
(262, 'auth\\passwordresetcontroller.reset', '2020-05-03 17:25:40', '2020-05-03 17:25:40', NULL, NULL),
(263, 'categorycontroller.getrootcategories', '2020-05-03 17:25:40', '2020-05-03 17:25:40', NULL, NULL),
(264, 'categorycontroller.getchildcategories', '2020-05-03 17:25:40', '2020-05-03 17:25:40', NULL, NULL),
(265, 'categorycontroller.getform', '2020-05-03 17:25:40', '2020-05-03 17:25:40', NULL, NULL),
(266, 'categorycontroller.getcategory', '2020-05-03 17:25:40', '2020-05-03 17:25:40', NULL, NULL),
(267, 'taskcontroller.addproducts', '2020-05-03 17:25:40', '2020-05-03 17:25:40', NULL, NULL),
(268, 'productcontroller.getproductsforcategory', '2020-05-03 17:25:40', '2020-05-03 17:25:40', NULL, NULL),
(269, 'taskcontroller.createdeal', '2020-05-03 17:25:40', '2020-05-03 17:25:40', NULL, NULL),
(270, 'leadcontroller.store', '2020-05-03 17:25:40', '2020-05-03 17:25:40', NULL, NULL),
(271, 'leadcontroller.convert', '2020-05-03 17:25:41', '2020-05-03 17:25:41', NULL, NULL),
(272, 'invoicecontroller.markviewed', '2020-05-03 17:25:41', '2020-05-03 17:25:41', NULL, NULL),
(273, 'quotecontroller.markviewed', '2020-05-03 17:25:41', '2020-05-03 17:25:41', NULL, NULL),
(274, 'creditcontroller.markviewed', '2020-05-03 17:25:41', '2020-05-03 17:25:41', NULL, NULL),
(275, 'ordercontroller.markviewed', '2020-05-03 17:25:41', '2020-05-03 17:25:41', NULL, NULL),
(276, 'invoicecontroller.downloadpdf', '2020-05-03 17:25:41', '2020-05-03 17:25:41', NULL, NULL),
(277, 'quotecontroller.downloadpdf', '2020-05-03 17:25:41', '2020-05-03 17:25:41', NULL, NULL),
(278, 'ordercontroller.downloadpdf', '2020-05-03 17:25:41', '2020-05-03 17:25:41', NULL, NULL),
(279, 'creditcontroller.downloadpdf', '2020-05-03 17:25:41', '2020-05-03 17:25:41', NULL, NULL),
(280, 'paymentcontroller.completepayment', '2020-05-03 17:25:41', '2020-05-03 17:25:41', NULL, NULL),
(281, 'recurringinvoicecontroller.requestcancellation', '2020-05-03 17:25:41', '2020-05-03 17:25:41', NULL, NULL),
(282, 'quotecontroller.bulk', '2020-05-03 17:25:42', '2020-05-03 17:25:42', NULL, NULL),
(283, 'ordercontroller.bulk', '2020-05-03 17:25:42', '2020-05-03 17:25:42', NULL, NULL),
(284, 'productcontroller.show', '2020-05-03 17:25:42', '2020-05-03 17:25:42', NULL, NULL),
(285, 'previewcontroller.show', '2020-05-03 17:25:42', '2020-05-03 17:25:42', NULL, NULL),
(286, 'setupcontroller.welcome', '2020-05-03 17:25:42', '2020-05-03 17:25:42', NULL, NULL),
(287, 'setupcontroller.requirements', '2020-05-03 17:25:42', '2020-05-03 17:25:42', NULL, NULL),
(288, 'setupcontroller.permissions', '2020-05-03 17:25:42', '2020-05-03 17:25:42', NULL, NULL),
(289, 'setupcontroller.environmentmenu', '2020-05-03 17:25:42', '2020-05-03 17:25:42', NULL, NULL),
(290, 'setupcontroller.database', '2020-05-03 17:25:42', '2020-05-03 17:25:42', NULL, NULL),
(291, 'setupcontroller.user', '2020-05-03 17:25:42', '2020-05-03 17:25:42', NULL, NULL),
(292, 'setupcontroller.finish', '2020-05-03 17:25:42', '2020-05-03 17:25:42', NULL, NULL),
(293, 'setupcontroller.environmentwizard', '2020-05-03 17:25:42', '2020-05-03 17:25:42', NULL, NULL),
(294, 'setupcontroller.saveuser', '2020-05-03 17:25:42', '2020-05-03 17:25:42', NULL, NULL),
(295, 'setupcontroller.savewizard', '2020-05-03 17:25:42', '2020-05-03 17:25:42', NULL, NULL),
(296, 'setupcontroller.environmentclassic', '2020-05-03 17:25:42', '2020-05-03 17:25:42', NULL, NULL),
(297, 'illuminate\\routing\\viewcontroller.\\illuminate\\routing\\viewcontroller', '2020-05-03 17:25:42', '2020-05-03 17:25:42', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `permission_role`
--

DROP TABLE IF EXISTS `permission_role`;
CREATE TABLE `permission_role` (
  `permission_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `permission_role`
--

INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES
(1, 3),
(2, 3),
(3, 3),
(4, 3),
(5, 3),
(6, 3),
(7, 3),
(8, 3),
(9, 3),
(10, 3),
(11, 3),
(12, 3),
(13, 3),
(14, 3),
(15, 3),
(16, 3),
(17, 3),
(18, 3),
(19, 3),
(20, 3),
(21, 3),
(22, 3),
(23, 3),
(24, 3),
(25, 3),
(26, 3),
(27, 3),
(28, 3),
(29, 3),
(30, 3),
(31, 3),
(32, 3),
(33, 3),
(34, 3),
(35, 3),
(36, 3),
(37, 3),
(38, 3),
(39, 3),
(40, 3),
(41, 3),
(42, 3),
(43, 3),
(44, 3),
(45, 3),
(46, 3),
(47, 3),
(48, 3),
(49, 3),
(50, 3),
(51, 3),
(52, 3),
(53, 3),
(54, 3),
(55, 3),
(56, 3),
(57, 3),
(58, 3),
(59, 3),
(60, 3),
(61, 3),
(62, 3),
(63, 3),
(64, 3),
(65, 3),
(66, 3),
(67, 3),
(68, 3),
(69, 3),
(70, 3),
(71, 3),
(72, 3),
(73, 3),
(74, 3),
(75, 3),
(76, 3),
(77, 3),
(78, 3),
(79, 3),
(80, 3),
(81, 3),
(82, 3),
(83, 3),
(84, 3),
(85, 3),
(86, 3),
(87, 3),
(88, 3),
(89, 3),
(90, 3),
(91, 3),
(92, 3),
(93, 3),
(94, 3),
(95, 3),
(96, 3),
(97, 3),
(98, 3),
(99, 3),
(100, 3),
(101, 3),
(102, 3),
(103, 3),
(104, 3),
(105, 3),
(106, 3),
(107, 3),
(108, 3),
(109, 3),
(110, 3),
(111, 3),
(112, 3),
(113, 3),
(114, 3),
(115, 3),
(116, 3),
(117, 3),
(118, 3),
(119, 3),
(120, 3),
(121, 3),
(122, 3),
(123, 3),
(124, 3),
(125, 3),
(126, 3),
(127, 3),
(128, 3),
(129, 3),
(130, 3),
(131, 3),
(132, 3),
(133, 3),
(134, 3),
(135, 3),
(136, 3),
(137, 3),
(138, 3),
(139, 3),
(140, 3),
(141, 3),
(142, 3),
(143, 3),
(144, 3),
(145, 3),
(146, 3),
(147, 3),
(148, 3),
(149, 3),
(150, 3),
(151, 3),
(152, 3),
(153, 3),
(154, 3),
(155, 3),
(156, 3),
(157, 3),
(158, 3),
(159, 3),
(160, 3),
(161, 3),
(162, 3),
(163, 3),
(164, 3),
(165, 3),
(166, 3),
(167, 3),
(168, 3),
(169, 3),
(170, 3),
(171, 3),
(172, 3),
(173, 3),
(174, 3),
(175, 3),
(176, 3),
(177, 3),
(178, 3),
(179, 3),
(180, 3),
(181, 3),
(182, 3),
(183, 3),
(184, 3),
(185, 3),
(186, 3),
(187, 3),
(188, 3),
(189, 3),
(190, 3),
(191, 3),
(192, 3),
(193, 3),
(194, 3),
(195, 3),
(196, 3),
(197, 3),
(198, 3),
(199, 3),
(200, 3),
(201, 3),
(202, 3),
(203, 3),
(204, 3),
(205, 3),
(206, 3),
(207, 3),
(208, 3),
(209, 3),
(210, 3),
(211, 3),
(212, 3),
(213, 3),
(214, 3),
(215, 3),
(216, 3),
(217, 3),
(218, 3),
(219, 3),
(220, 3),
(221, 3),
(222, 3),
(223, 3),
(224, 3),
(225, 3),
(226, 3),
(227, 3),
(228, 3),
(229, 3),
(230, 3),
(231, 3),
(232, 3),
(233, 3),
(234, 3),
(235, 3),
(236, 3),
(237, 3),
(238, 3),
(239, 3),
(240, 3),
(241, 3),
(242, 3),
(243, 3);

-- --------------------------------------------------------

--
-- Table structure for table `permission_user`
--

DROP TABLE IF EXISTS `permission_user`;
CREATE TABLE `permission_user` (
  `permission_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `user_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `sku` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `price` decimal(8,2) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `cover` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `quantity` decimal(16,4) NOT NULL DEFAULT 0.0000,
  `cost` decimal(16,4) NOT NULL DEFAULT 0.0000,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `account_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `assigned_user_id` int(10) UNSIGNED DEFAULT NULL,
  `notes` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `custom_value1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_value2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_value3` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_value4` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `company_id`, `sku`, `name`, `slug`, `description`, `price`, `status`, `created_at`, `updated_at`, `cover`, `quantity`, `cost`, `deleted_at`, `account_id`, `user_id`, `assigned_user_id`, `notes`, `is_deleted`, `custom_value1`, `custom_value2`, `custom_value3`, `custom_value4`) VALUES
(1, 1, 'testmike', 'test mikes product', '', 'test mike', '1699.00', 1, '2020-05-03 19:06:45', '2020-05-03 19:22:11', 'undefined', '49.0000', '1699.0000', NULL, 1, 5, 5, NULL, 0, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_attributes`
--

DROP TABLE IF EXISTS `product_attributes`;
CREATE TABLE `product_attributes` (
  `id` int(10) UNSIGNED NOT NULL,
  `range_from` decimal(8,2) NOT NULL,
  `range_to` decimal(8,2) NOT NULL,
  `interest_rate` decimal(8,2) DEFAULT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `minimum_downpayment` double(8,2) DEFAULT 0.00,
  `payable_months` double(8,2) NOT NULL DEFAULT 12.00,
  `number_of_years` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

DROP TABLE IF EXISTS `product_images`;
CREATE TABLE `product_images` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `src` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_task`
--

DROP TABLE IF EXISTS `product_task`;
CREATE TABLE `product_task` (
  `id` int(10) UNSIGNED NOT NULL,
  `task_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `account_id` int(10) UNSIGNED NOT NULL,
  `status_id` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `balance` decimal(16,4) NOT NULL,
  `tax_total` decimal(16,4) NOT NULL,
  `sub_total` decimal(16,4) NOT NULL,
  `discount_total` decimal(16,4) NOT NULL,
  `total` decimal(16,4) NOT NULL,
  `public_notes` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `private_notes` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `terms` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `footer` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `line_items` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `tax_rate_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tax_rate` decimal(13,3) NOT NULL,
  `date` date NOT NULL,
  `partial` decimal(16,4) DEFAULT NULL,
  `partial_due_date` datetime DEFAULT NULL,
  `customer_id` int(10) UNSIGNED NOT NULL,
  `custom_value1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_value2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_value3` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_value4` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_amount_discount` tinyint(1) NOT NULL DEFAULT 0,
  `design_id` int(10) UNSIGNED DEFAULT NULL,
  `due_date` datetime DEFAULT NULL,
  `custom_surcharge1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_surcharge2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_surcharge3` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_surcharge4` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_surcharge_tax1` tinyint(1) NOT NULL DEFAULT 0,
  `custom_surcharge_tax2` tinyint(1) NOT NULL DEFAULT 0,
  `custom_surcharge_tax3` tinyint(1) NOT NULL DEFAULT 0,
  `custom_surcharge_tax4` tinyint(1) NOT NULL DEFAULT 0,
  `number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `quote_id` int(10) UNSIGNED DEFAULT NULL,
  `invoice_id` int(10) UNSIGNED DEFAULT NULL,
  `is_deleted` tinyint(4) NOT NULL DEFAULT 0,
  `po_number` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

DROP TABLE IF EXISTS `projects`;
CREATE TABLE `projects` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `is_completed` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `account_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `assigned_user_id` int(10) UNSIGNED DEFAULT NULL,
  `notes` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `budgeted_hours` double DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `provinces`
--

DROP TABLE IF EXISTS `provinces`;
CREATE TABLE `provinces` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `country_id` int(10) UNSIGNED NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quotes`
--

DROP TABLE IF EXISTS `quotes`;
CREATE TABLE `quotes` (
  `id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `assigned_user_id` int(10) UNSIGNED DEFAULT NULL,
  `account_id` int(10) UNSIGNED NOT NULL,
  `status_id` int(10) UNSIGNED NOT NULL,
  `recurring_id` int(10) UNSIGNED DEFAULT NULL,
  `design_id` int(10) UNSIGNED DEFAULT NULL,
  `number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `discount` double(8,2) NOT NULL DEFAULT 0.00,
  `is_amount_discount` tinyint(1) NOT NULL DEFAULT 0,
  `po_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date` date DEFAULT NULL,
  `due_date` datetime DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `line_items` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `footer` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `public_notes` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `private_notes` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `terms` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `sub_total` decimal(16,4) NOT NULL,
  `tax_total` decimal(16,4) NOT NULL,
  `discount_total` decimal(16,4) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `uses_inclusive_taxes` tinyint(1) NOT NULL DEFAULT 0,
  `total` decimal(16,4) NOT NULL,
  `balance` decimal(16,4) NOT NULL,
  `partial` decimal(16,4) DEFAULT NULL,
  `partial_due_date` datetime DEFAULT NULL,
  `last_viewed` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `task_id` int(10) UNSIGNED DEFAULT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `custom_value1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_value2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_value3` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_value4` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_sent_date` date DEFAULT NULL,
  `invoice_id` int(10) UNSIGNED DEFAULT NULL,
  `tax_rate` decimal(13,3) NOT NULL DEFAULT 0.000,
  `tax_rate_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_surcharge1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_surcharge2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_surcharge3` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_surcharge4` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_surcharge_tax1` tinyint(1) NOT NULL DEFAULT 0,
  `custom_surcharge_tax2` tinyint(1) NOT NULL DEFAULT 0,
  `custom_surcharge_tax3` tinyint(1) NOT NULL DEFAULT 0,
  `custom_surcharge_tax4` tinyint(1) NOT NULL DEFAULT 0,
  `order_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `quotes`
--

INSERT INTO `quotes` (`id`, `customer_id`, `user_id`, `assigned_user_id`, `account_id`, `status_id`, `recurring_id`, `design_id`, `number`, `discount`, `is_amount_discount`, `po_number`, `date`, `due_date`, `is_deleted`, `line_items`, `footer`, `public_notes`, `private_notes`, `terms`, `sub_total`, `tax_total`, `discount_total`, `parent_id`, `uses_inclusive_taxes`, `total`, `balance`, `partial`, `partial_due_date`, `last_viewed`, `created_at`, `updated_at`, `deleted_at`, `task_id`, `company_id`, `custom_value1`, `custom_value2`, `custom_value3`, `custom_value4`, `last_sent_date`, `invoice_id`, `tax_rate`, `tax_rate_name`, `custom_surcharge1`, `custom_surcharge2`, `custom_surcharge3`, `custom_surcharge4`, `custom_surcharge_tax1`, `custom_surcharge_tax2`, `custom_surcharge_tax3`, `custom_surcharge_tax4`, `order_id`) VALUES
(1, 5, 5, NULL, 1, 1, NULL, NULL, '0001', 0.00, 1, NULL, '2020-05-04', '2020-05-04 00:00:00', 0, '[{\"custom_value1\":\"\",\"custom_value2\":\"\",\"custom_value3\":\"\",\"custom_value4\":\"\",\"tax_rate_name\":\"basic\",\"tax_rate_id\":1,\"type_id\":1,\"quantity\":1,\"notes\":\"\",\"unit_price\":1699,\"unit_discount\":2,\"unit_tax\":17.5,\"sub_total\":1994,\"line_total\":1699,\"discount_total\":2,\"tax_total\":297.33,\"is_amount_discount\":true,\"product_id\":\"1\",\"description\":\"\"}]', 'quote footer', 'public', NULL, 'quote terms', '1699.0000', '297.3300', '2.0000', NULL, 0, '1994.3300', '1994.3300', '0.0000', '2020-05-04 00:00:00', NULL, '2020-05-03 19:37:08', '2020-05-03 19:37:08', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.000', NULL, '0', '0', '0', '0', 0, 0, 0, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `quote_invitations`
--

DROP TABLE IF EXISTS `quote_invitations`;
CREATE TABLE `quote_invitations` (
  `id` int(10) UNSIGNED NOT NULL,
  `account_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `client_contact_id` int(10) UNSIGNED NOT NULL,
  `quote_id` int(10) UNSIGNED NOT NULL,
  `key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `transaction_reference` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `message_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email_error` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `signature_base64` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `signature_date` datetime DEFAULT NULL,
  `sent_date` datetime DEFAULT NULL,
  `viewed_date` datetime DEFAULT NULL,
  `opened_date` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `quote_invitations`
--

INSERT INTO `quote_invitations` (`id`, `account_id`, `user_id`, `client_contact_id`, `quote_id`, `key`, `transaction_reference`, `message_id`, `email_error`, `signature_base64`, `signature_date`, `sent_date`, `viewed_date`, `opened_date`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 5, 1, 1, 'i4YLIzsvWn2HUCY1hfWc', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020-05-03 19:37:09', '2020-05-03 19:37:09', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `recurring_invoices`
--

DROP TABLE IF EXISTS `recurring_invoices`;
CREATE TABLE `recurring_invoices` (
  `id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `assigned_user_id` int(10) UNSIGNED DEFAULT NULL,
  `account_id` int(10) UNSIGNED NOT NULL,
  `status_id` int(10) UNSIGNED NOT NULL,
  `number` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `discount` double(8,2) NOT NULL DEFAULT 0.00,
  `sub_total` decimal(16,4) NOT NULL DEFAULT 0.0000,
  `tax_total` decimal(16,4) NOT NULL DEFAULT 0.0000,
  `discount_total` decimal(16,4) NOT NULL DEFAULT 0.0000,
  `is_amount_discount` tinyint(1) NOT NULL DEFAULT 0,
  `po_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date` date DEFAULT NULL,
  `due_date` datetime DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `line_items` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `footer` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `public_notes` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `terms` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `total` decimal(16,4) NOT NULL,
  `balance` decimal(16,4) NOT NULL,
  `partial` decimal(16,4) DEFAULT NULL,
  `last_viewed` datetime DEFAULT NULL,
  `frequency_id` int(10) UNSIGNED NOT NULL,
  `start_date` datetime DEFAULT NULL,
  `last_sent_date` datetime DEFAULT NULL,
  `next_send_date` datetime DEFAULT NULL,
  `remaining_cycles` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `task_id` int(10) UNSIGNED DEFAULT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `custom_value1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_value2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_value3` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_value4` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `private_notes` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `tax_rate` decimal(13,3) NOT NULL DEFAULT 0.000,
  `tax_rate_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `recurring_quotes`
--

DROP TABLE IF EXISTS `recurring_quotes`;
CREATE TABLE `recurring_quotes` (
  `id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `assigned_user_id` int(10) UNSIGNED DEFAULT NULL,
  `account_id` int(10) UNSIGNED NOT NULL,
  `status_id` int(10) UNSIGNED NOT NULL,
  `discount` double(8,2) NOT NULL DEFAULT 0.00,
  `sub_total` decimal(16,4) NOT NULL DEFAULT 0.0000,
  `tax_total` decimal(16,4) NOT NULL DEFAULT 0.0000,
  `discount_total` decimal(16,4) NOT NULL DEFAULT 0.0000,
  `is_amount_discount` tinyint(1) NOT NULL DEFAULT 0,
  `number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `po_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date` date DEFAULT NULL,
  `due_date` datetime DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `line_items` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `footer` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `public_notes` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `terms` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `total` decimal(16,4) NOT NULL DEFAULT 0.0000,
  `balance` decimal(16,4) NOT NULL DEFAULT 0.0000,
  `last_viewed` datetime DEFAULT NULL,
  `frequency_id` int(10) UNSIGNED NOT NULL,
  `start_date` date DEFAULT NULL,
  `last_sent_date` datetime DEFAULT NULL,
  `next_send_date` datetime DEFAULT NULL,
  `remaining_cycles` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `task_id` int(10) UNSIGNED DEFAULT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `custom_value1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_value2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_value3` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_value4` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `private_notes` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `tax_rate_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tax_rate` decimal(13,3) NOT NULL DEFAULT 0.000
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `account_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `display_name`, `description`, `created_at`, `updated_at`, `account_id`, `user_id`) VALUES
(3, 'Admin', 'Admin', 'Admin', NULL, NULL, 1, 5),
(4, 'Manager', 'Manager', 'Manager', NULL, NULL, 1, 5);

-- --------------------------------------------------------

--
-- Table structure for table `role_user`
--

DROP TABLE IF EXISTS `role_user`;
CREATE TABLE `role_user` (
  `role_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `user_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `role_user`
--

INSERT INTO `role_user` (`role_id`, `user_id`, `user_type`) VALUES
(3, 5, ''),
(4, 5, '');

-- --------------------------------------------------------

--
-- Table structure for table `source_type`
--

DROP TABLE IF EXISTS `source_type`;
CREATE TABLE `source_type` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `source_type`
--

INSERT INTO `source_type` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Personal Contact', NULL, NULL),
(2, 'Call', NULL, NULL),
(3, 'Email', NULL, NULL),
(4, 'Other', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `states`
--

DROP TABLE IF EXISTS `states`;
CREATE TABLE `states` (
  `state` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `state_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `country_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

DROP TABLE IF EXISTS `subscriptions`;
CREATE TABLE `subscriptions` (
  `id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `target_url` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `event_id` int(10) UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `account_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `format` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `system_logs`
--

DROP TABLE IF EXISTS `system_logs`;
CREATE TABLE `system_logs` (
  `id` int(10) UNSIGNED NOT NULL,
  `account_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `customer_id` int(10) UNSIGNED DEFAULT NULL,
  `category_id` int(10) UNSIGNED DEFAULT NULL,
  `event_id` int(10) UNSIGNED DEFAULT NULL,
  `type_id` int(10) UNSIGNED DEFAULT NULL,
  `log` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

DROP TABLE IF EXISTS `tasks`;
CREATE TABLE `tasks` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `task_color` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `due_date` datetime NOT NULL,
  `is_completed` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_active` int(11) NOT NULL DEFAULT 1,
  `task_status` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `task_type` int(11) NOT NULL DEFAULT 1,
  `rating` int(10) UNSIGNED DEFAULT NULL,
  `customer_id` int(10) UNSIGNED NOT NULL,
  `valued_at` decimal(8,2) DEFAULT NULL,
  `parent_id` int(11) NOT NULL DEFAULT 0,
  `source_type` int(10) UNSIGNED DEFAULT 1,
  `start_date` datetime DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `assigned_user_id` int(10) UNSIGNED DEFAULT NULL,
  `account_id` int(10) UNSIGNED NOT NULL,
  `custom_value1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_value2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `company_id` int(11) DEFAULT NULL,
  `task_status_sort_order` smallint(6) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `custom_value3` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_value4` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `project_id` int(10) UNSIGNED DEFAULT NULL,
  `invoice_id` int(10) UNSIGNED DEFAULT NULL,
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 9874,
  `public_notes` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `private_notes` text COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `title`, `content`, `task_color`, `due_date`, `is_completed`, `created_at`, `updated_at`, `is_active`, `task_status`, `created_by`, `task_type`, `rating`, `customer_id`, `valued_at`, `parent_id`, `source_type`, `start_date`, `deleted_at`, `assigned_user_id`, `account_id`, `custom_value1`, `custom_value2`, `company_id`, `task_status_sort_order`, `is_deleted`, `custom_value3`, `custom_value4`, `project_id`, `invoice_id`, `user_id`, `public_notes`, `private_notes`) VALUES
(442, 'test task', 'test task', '', '2020-06-30 00:00:00', 0, '2020-05-04 07:04:57', '2020-05-04 07:04:57', 1, 1, 5, 1, NULL, 5, NULL, 0, 1, '2020-05-29 00:00:00', NULL, NULL, 1, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 5, 'public', 'private'),
(443, 'test task', 'test task', '', '2020-06-30 00:00:00', 0, '2020-05-04 07:05:33', '2020-05-04 07:05:33', 1, 1, 5, 1, NULL, 5, NULL, 0, 1, '2020-05-29 00:00:00', NULL, NULL, 1, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 5, 'public', 'private');

-- --------------------------------------------------------

--
-- Table structure for table `task_comment`
--

DROP TABLE IF EXISTS `task_comment`;
CREATE TABLE `task_comment` (
  `id` int(10) UNSIGNED NOT NULL,
  `task_id` int(10) UNSIGNED NOT NULL,
  `comment_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `task_statuses`
--

DROP TABLE IF EXISTS `task_statuses`;
CREATE TABLE `task_statuses` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `icon` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_active` int(11) NOT NULL DEFAULT 1,
  `task_type` int(11) NOT NULL DEFAULT 1,
  `column_color` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `task_statuses`
--

INSERT INTO `task_statuses` (`id`, `title`, `description`, `icon`, `created_at`, `updated_at`, `is_active`, `task_type`, `column_color`) VALUES
(1, 'TODO', 'You can do what you want to do with this column', 'fa-bars', NULL, NULL, 1, 1, ''),
(2, 'Blocked', 'You can do what you want to do with this column', 'fa-lightbulb', NULL, NULL, 1, 1, ''),
(3, 'In Progress', 'You can do what you want to do with this column', 'fa-spinner', NULL, NULL, 1, 1, ''),
(4, 'Done', 'You can do what you want to do with this column', 'fa-check', NULL, NULL, 1, 1, '');

-- --------------------------------------------------------

--
-- Table structure for table `task_type`
--

DROP TABLE IF EXISTS `task_type`;
CREATE TABLE `task_type` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `task_user`
--

DROP TABLE IF EXISTS `task_user`;
CREATE TABLE `task_user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `task_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `task_user`
--

INSERT INTO `task_user` (`id`, `task_id`, `user_id`, `created_at`, `updated_at`) VALUES
(15, 442, 5, NULL, NULL),
(16, 443, 5, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tax_rates`
--

DROP TABLE IF EXISTS `tax_rates`;
CREATE TABLE `tax_rates` (
  `id` int(10) UNSIGNED NOT NULL,
  `account_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `rate` decimal(13,3) NOT NULL DEFAULT 0.000,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tax_rates`
--

INSERT INTO `tax_rates` (`id`, `account_id`, `user_id`, `created_at`, `updated_at`, `deleted_at`, `name`, `rate`, `is_deleted`) VALUES
(1, 1, 5, '2020-05-03 19:07:50', '2020-05-03 19:07:50', NULL, 'basic', '17.500', 0);

-- --------------------------------------------------------

--
-- Table structure for table `timers`
--

DROP TABLE IF EXISTS `timers`;
CREATE TABLE `timers` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `started_at` timestamp NULL DEFAULT NULL,
  `stopped_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `task_id` int(11) UNSIGNED NOT NULL,
  `account_id` int(11) UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `timers`
--

INSERT INTO `timers` (`id`, `name`, `user_id`, `started_at`, `stopped_at`, `created_at`, `updated_at`, `task_id`, `account_id`, `deleted_at`) VALUES
(14, '', 5, '2020-05-04 08:27:31', '2020-05-04 16:00:00', '2020-05-04 16:01:34', '2020-05-04 16:01:34', 443, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `first_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `profile_photo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `auth_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_active` int(11) NOT NULL DEFAULT 1,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `gender` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `job_description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_value1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_value2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_value3` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_value4` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_deleted` int(11) NOT NULL DEFAULT 0,
  `accepted_terms_version` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `confirmation_code` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `ip` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `domain_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `profile_photo`, `username`, `created_at`, `updated_at`, `email`, `password`, `auth_token`, `is_active`, `deleted_at`, `gender`, `phone_number`, `dob`, `job_description`, `custom_value1`, `custom_value2`, `custom_value3`, `custom_value4`, `is_deleted`, `accepted_terms_version`, `confirmation_code`, `last_login`, `ip`, `domain_id`) VALUES
(5, 'Michael', 'Hampton', NULL, '', '2020-05-03 17:24:23', '2020-05-04 13:52:10', 'michaelhamptondesign@yahoo.com', '$2y$10$3K5RC532qSIN.Ow8lUUIt.EJFDoQyxxDme43jDGZXaBjzOhMCbvRq', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC90YXNrbWFuMi5kZXZlbG9wXC9hcGlcL2xvZ2luIiwiaWF0IjoxNTg4NjAzOTMwLCJleHAiOjE1ODg2MTQ3MzAsIm5iZiI6MTU4ODYwMzkzMCwianRpIjoiaGdZblRQUlVjUklucUk4dyIsInN1YiI6NSwicHJ2IjoiODdlMGFmMWVmOWZkMTU4MTJmZGVjOTcxNT', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 'X8W7qfielINfN4Lswdadh7p1GabUyx3PmatOblwGNkLtc0hhbehj79Ccs2ZCIdU9', '2020-05-03 18:24:22', '127.0.0.1', 5);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `accounts_domain_id_index` (`domain_id`);

--
-- Indexes for table `account_user`
--
ALTER TABLE `account_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account_user_account_id_index` (`account_id`),
  ADD KEY `account_user_user_id_index` (`user_id`);

--
-- Indexes for table `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `addresses_country_id_index` (`country_id`),
  ADD KEY `addresses_customer_id_index` (`customer_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_name_unique` (`name`),
  ADD KEY `categories__lft__rgt_parent_id_index` (`_lft`,`_rgt`,`parent_id`);

--
-- Indexes for table `category_product`
--
ALTER TABLE `category_product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_product_category_id_index` (`category_id`),
  ADD KEY `category_product_product_id_index` (`product_id`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD KEY `cities_province_id_foreign` (`province_id`);

--
-- Indexes for table `client_contacts`
--
ALTER TABLE `client_contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_contacts_account_id_index` (`account_id`),
  ADD KEY `client_contacts_customer_id_index` (`customer_id`),
  ADD KEY `client_contacts_user_id_index` (`user_id`);

--
-- Indexes for table `client_gateway_tokens`
--
ALTER TABLE `client_gateway_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_gateway_tokens_account_id_foreign` (`account_id`),
  ADD KEY `client_gateway_tokens_customer_id_foreign` (`customer_id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `comments_user_id_foreign` (`user_id`),
  ADD KEY `account_id` (`account_id`);

--
-- Indexes for table `comment_task`
--
ALTER TABLE `comment_task`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_comment_task_id_foreign` (`task_id`),
  ADD KEY `task_comment_comment_id_foreign` (`comment_id`);

--
-- Indexes for table `comment_type`
--
ALTER TABLE `comment_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `country_id` (`country_id`),
  ADD KEY `currency_id` (`currency_id`),
  ADD KEY `companies_user_id_index` (`user_id`),
  ADD KEY `companies_account_id_index` (`account_id`);

--
-- Indexes for table `company_contacts`
--
ALTER TABLE `company_contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `company_contacts_account_id_foreign` (`account_id`),
  ADD KEY `company_contacts_user_id_foreign` (`user_id`),
  ADD KEY `company_contacts_company_id_index` (`company_id`);

--
-- Indexes for table `company_gateways`
--
ALTER TABLE `company_gateways`
  ADD PRIMARY KEY (`id`),
  ADD KEY `company_gateways_account_id_foreign` (`account_id`),
  ADD KEY `company_gateways_user_id_foreign` (`user_id`),
  ADD KEY `company_gateways_gateway_key_foreign` (`gateway_key`);

--
-- Indexes for table `company_ledgers`
--
ALTER TABLE `company_ledgers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `company_ledgers_account_id_foreign` (`account_id`),
  ADD KEY `company_ledgers_customer_id_foreign` (`customer_id`);

--
-- Indexes for table `company_tokens`
--
ALTER TABLE `company_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `company_tokens_account_id_index` (`account_id`),
  ADD KEY `company_tokens_domain_id_foreign` (`domain_id`),
  ADD KEY `company_tokens_user_id_foreign` (`user_id`);

--
-- Indexes for table `company_user`
--
ALTER TABLE `company_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `countries_name_unique` (`name`),
  ADD UNIQUE KEY `countries_iso_unique` (`iso`);

--
-- Indexes for table `credits`
--
ALTER TABLE `credits`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `credits_account_id_number_unique` (`account_id`,`number`),
  ADD KEY `credits_customer_id_index` (`customer_id`),
  ADD KEY `credits_user_id_foreign` (`user_id`),
  ADD KEY `credits_account_id_index` (`account_id`);

--
-- Indexes for table `credit_invitations`
--
ALTER TABLE `credit_invitations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `credit_invitations_customer_id_credit_id_unique` (`client_contact_id`,`credit_id`),
  ADD KEY `credit_invitations_deleted_at_credit_id_index` (`deleted_at`,`credit_id`),
  ADD KEY `credit_invitations_account_id_foreign` (`account_id`),
  ADD KEY `credit_invitations_user_id_foreign` (`user_id`),
  ADD KEY `credit_invitations_credit_id_index` (`credit_id`),
  ADD KEY `credit_invitations_key_index` (`key`);

--
-- Indexes for table `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customers_account_id_foreign` (`account_id`),
  ADD KEY `company_id` (`company_id`),
  ADD KEY `industry_id` (`industry_id`);

--
-- Indexes for table `customer_type`
--
ALTER TABLE `customer_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_types`
--
ALTER TABLE `customer_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `datetime_formats`
--
ALTER TABLE `datetime_formats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `date_formats`
--
ALTER TABLE `date_formats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `departments__lft__rgt_parent_id_index` (`_lft`,`_rgt`,`parent_id`),
  ADD KEY `departments_department_manager_foreign` (`department_manager`),
  ADD KEY `account_id` (`account_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `department_user`
--
ALTER TABLE `department_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `designs`
--
ALTER TABLE `designs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `designs_account_id_index` (`account_id`);

--
-- Indexes for table `domains`
--
ALTER TABLE `domains`
  ADD PRIMARY KEY (`id`),
  ADD KEY `domains_payment_id_index` (`payment_id`);

--
-- Indexes for table `emails`
--
ALTER TABLE `emails`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `account_id` (`account_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `event_type` (`event_type`),
  ADD KEY `account_id` (`account_id`);

--
-- Indexes for table `event_status`
--
ALTER TABLE `event_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `event_task`
--
ALTER TABLE `event_task`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_id` (`task_id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `event_types`
--
ALTER TABLE `event_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `event_user`
--
ALTER TABLE `event_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `expenses_account_id_index` (`account_id`),
  ADD KEY `expenses_user_id_foreign` (`user_id`);

--
-- Indexes for table `expense_categories`
--
ALTER TABLE `expense_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `expense_categories_account_id_index` (`account_id`);

--
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `files_user_id_foreign` (`user_id`),
  ADD KEY `files_account_id_index` (`account_id`);

--
-- Indexes for table `form_category`
--
ALTER TABLE `form_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `frequencies`
--
ALTER TABLE `frequencies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gateways`
--
ALTER TABLE `gateways`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `gateways_key_unique` (`key`);

--
-- Indexes for table `gateway_types`
--
ALTER TABLE `gateway_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `group_settings`
--
ALTER TABLE `group_settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `group_settings_account_id_foreign` (`account_id`);

--
-- Indexes for table `industries`
--
ALTER TABLE `industries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoices_customer_id_index` (`customer_id`),
  ADD KEY `invoices_user_id_foreign` (`user_id`),
  ADD KEY `account_id` (`account_id`);

--
-- Indexes for table `invoices_backup`
--
ALTER TABLE `invoices_backup`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoice_invitations`
--
ALTER TABLE `invoice_invitations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoice_invitations_customer_id_invoice_id_unique` (`client_contact_id`,`invoice_id`),
  ADD KEY `invoice_invitations_deleted_at_invoice_id_index` (`deleted_at`,`invoice_id`),
  ADD KEY `invoice_invitations_account_id_foreign` (`account_id`),
  ADD KEY `invoice_invitations_user_id_foreign` (`user_id`),
  ADD KEY `invoice_invitations_invoice_id_index` (`invoice_id`),
  ADD KEY `invoice_invitations_key_index` (`key`);

--
-- Indexes for table `invoice_status`
--
ALTER TABLE `invoice_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leads`
--
ALTER TABLE `leads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account_id` (`account_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `source_type` (`source_type`),
  ADD KEY `assigned_user_id` (`assigned_user_id`),
  ADD KEY `industry_id` (`industry_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `messages_user_id_foreign` (`user_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `metrics`
--
ALTER TABLE `metrics`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_invitations`
--
ALTER TABLE `order_invitations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account_id` (`account_id`),
  ADD KEY `client_contact_id` (`client_contact_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_status`
--
ALTER TABLE `order_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `paymentables`
--
ALTER TABLE `paymentables`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payment_id` (`payment_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payments_account_id_foreign` (`account_id`),
  ADD KEY `payments_customer_id_foreign` (`customer_id`),
  ADD KEY `payments_user_id_foreign` (`user_id`),
  ADD KEY `payments_company_gateway_id_foreign` (`company_gateway_id`),
  ADD KEY `payments_payment_type_id_foreign` (`type_id`),
  ADD KEY `payments_client_contact_id_foreign` (`client_contact_id`);

--
-- Indexes for table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_statuses`
--
ALTER TABLE `payment_statuses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permission_role`
--
ALTER TABLE `permission_role`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `permission_role_role_id_foreign` (`role_id`);

--
-- Indexes for table `permission_user`
--
ALTER TABLE `permission_user`
  ADD PRIMARY KEY (`user_id`,`permission_id`,`user_type`),
  ADD KEY `permission_user_permission_id_foreign` (`permission_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_brand_id_index` (`company_id`),
  ADD KEY `products_account_id_foreign` (`account_id`);

--
-- Indexes for table `product_attributes`
--
ALTER TABLE `product_attributes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_attributes_product_id_foreign` (`product_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_images_product_id_index` (`product_id`);

--
-- Indexes for table `product_task`
--
ALTER TABLE `product_task`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_task_task_id_foreign` (`task_id`),
  ADD KEY `account_id` (`account_id`),
  ADD KEY `status` (`status_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `projects_account_id_index` (`account_id`),
  ADD KEY `projects_user_id_index` (`user_id`);

--
-- Indexes for table `provinces`
--
ALTER TABLE `provinces`
  ADD PRIMARY KEY (`id`),
  ADD KEY `provinces_country_id_index` (`country_id`);

--
-- Indexes for table `quotes`
--
ALTER TABLE `quotes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quotes_customer_id_index` (`customer_id`),
  ADD KEY `quotes_user_id_foreign` (`user_id`),
  ADD KEY `quotes_account_id_index` (`account_id`);

--
-- Indexes for table `quote_invitations`
--
ALTER TABLE `quote_invitations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quote_invitations_deleted_at_quote_id_index` (`deleted_at`,`quote_id`),
  ADD KEY `quote_invitations_account_id_foreign` (`account_id`),
  ADD KEY `quote_invitations_user_id_foreign` (`user_id`),
  ADD KEY `quote_invitations_customer_id_foreign` (`client_contact_id`),
  ADD KEY `quote_invitations_quote_id_index` (`quote_id`),
  ADD KEY `quote_invitations_key_index` (`key`);

--
-- Indexes for table `recurring_invoices`
--
ALTER TABLE `recurring_invoices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `recurring_invoices_customer_id_index` (`customer_id`),
  ADD KEY `recurring_invoices_user_id_foreign` (`user_id`),
  ADD KEY `recurring_invoices_account_id_index` (`account_id`),
  ADD KEY `recurring_invoices_status_id_index` (`status_id`);

--
-- Indexes for table `recurring_quotes`
--
ALTER TABLE `recurring_quotes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `recurring_quotes_customer_id_index` (`customer_id`),
  ADD KEY `recurring_quotes_user_id_foreign` (`user_id`),
  ADD KEY `recurring_quotes_account_id_index` (`account_id`),
  ADD KEY `recurring_quotes_status_id_index` (`status_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_unique` (`name`),
  ADD KEY `account_id` (`account_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `role_user`
--
ALTER TABLE `role_user`
  ADD PRIMARY KEY (`user_id`,`role_id`,`user_type`),
  ADD KEY `role_user_role_id_foreign` (`role_id`);

--
-- Indexes for table `source_type`
--
ALTER TABLE `source_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `states`
--
ALTER TABLE `states`
  ADD KEY `states_country_id_foreign` (`country_id`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account_id` (`account_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `system_logs`
--
ALTER TABLE `system_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `system_logs_account_id_foreign` (`account_id`),
  ADD KEY `system_logs_customer_id_foreign` (`customer_id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tasks_customer_id_foreign` (`customer_id`),
  ADD KEY `tasks_source_type_foreign` (`source_type`),
  ADD KEY `tasks_account_id_index` (`account_id`),
  ADD KEY `invoice_id` (`invoice_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `task_comment`
--
ALTER TABLE `task_comment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `task_statuses`
--
ALTER TABLE `task_statuses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `task_type`
--
ALTER TABLE `task_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `task_user`
--
ALTER TABLE `task_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_user_task_id_foreign` (`task_id`),
  ADD KEY `task_user_user_id_foreign` (`user_id`);

--
-- Indexes for table `tax_rates`
--
ALTER TABLE `tax_rates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tax_rates_account_id_index` (`account_id`),
  ADD KEY `tax_rates_user_id_foreign` (`user_id`);

--
-- Indexes for table `timers`
--
ALTER TABLE `timers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `timers_user_id_foreign` (`user_id`),
  ADD KEY `task_id` (`task_id`),
  ADD KEY `account_id` (`account_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `users_api_token_unique` (`auth_token`),
  ADD KEY `domain_id` (`domain_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=158;

--
-- AUTO_INCREMENT for table `account_user`
--
ALTER TABLE `account_user`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `addresses`
--
ALTER TABLE `addresses`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=123;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=430;

--
-- AUTO_INCREMENT for table `category_product`
--
ALTER TABLE `category_product`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=137;

--
-- AUTO_INCREMENT for table `client_contacts`
--
ALTER TABLE `client_contacts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `client_gateway_tokens`
--
ALTER TABLE `client_gateway_tokens`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT for table `comment_task`
--
ALTER TABLE `comment_task`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `comment_type`
--
ALTER TABLE `comment_type`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3791;

--
-- AUTO_INCREMENT for table `company_contacts`
--
ALTER TABLE `company_contacts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `company_gateways`
--
ALTER TABLE `company_gateways`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `company_ledgers`
--
ALTER TABLE `company_ledgers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=877;

--
-- AUTO_INCREMENT for table `company_tokens`
--
ALTER TABLE `company_tokens`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `company_user`
--
ALTER TABLE `company_user`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=240;

--
-- AUTO_INCREMENT for table `credits`
--
ALTER TABLE `credits`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=141;

--
-- AUTO_INCREMENT for table `credit_invitations`
--
ALTER TABLE `credit_invitations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `currencies`
--
ALTER TABLE `currencies`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3108;

--
-- AUTO_INCREMENT for table `customer_type`
--
ALTER TABLE `customer_type`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_types`
--
ALTER TABLE `customer_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `datetime_formats`
--
ALTER TABLE `datetime_formats`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `date_formats`
--
ALTER TABLE `date_formats`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=241;

--
-- AUTO_INCREMENT for table `department_user`
--
ALTER TABLE `department_user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `designs`
--
ALTER TABLE `designs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `domains`
--
ALTER TABLE `domains`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `emails`
--
ALTER TABLE `emails`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

--
-- AUTO_INCREMENT for table `event_status`
--
ALTER TABLE `event_status`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `event_task`
--
ALTER TABLE `event_task`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `event_types`
--
ALTER TABLE `event_types`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `event_user`
--
ALTER TABLE `event_user`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `expense_categories`
--
ALTER TABLE `expense_categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `form_category`
--
ALTER TABLE `form_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `frequencies`
--
ALTER TABLE `frequencies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gateways`
--
ALTER TABLE `gateways`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gateway_types`
--
ALTER TABLE `gateway_types`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `group_settings`
--
ALTER TABLE `group_settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `industries`
--
ALTER TABLE `industries`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=272;

--
-- AUTO_INCREMENT for table `invoices_backup`
--
ALTER TABLE `invoices_backup`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoice_invitations`
--
ALTER TABLE `invoice_invitations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `invoice_status`
--
ALTER TABLE `invoice_status`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `leads`
--
ALTER TABLE `leads`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `metrics`
--
ALTER TABLE `metrics`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=277;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=563;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=644;

--
-- AUTO_INCREMENT for table `order_invitations`
--
ALTER TABLE `order_invitations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `order_status`
--
ALTER TABLE `order_status`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `paymentables`
--
ALTER TABLE `paymentables`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=138;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=256;

--
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `payment_statuses`
--
ALTER TABLE `payment_statuses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=418;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=410;

--
-- AUTO_INCREMENT for table `product_attributes`
--
ALTER TABLE `product_attributes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=181;

--
-- AUTO_INCREMENT for table `product_task`
--
ALTER TABLE `product_task`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=122;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT for table `provinces`
--
ALTER TABLE `provinces`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quotes`
--
ALTER TABLE `quotes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=131;

--
-- AUTO_INCREMENT for table `quote_invitations`
--
ALTER TABLE `quote_invitations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `recurring_invoices`
--
ALTER TABLE `recurring_invoices`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `recurring_quotes`
--
ALTER TABLE `recurring_quotes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=170;

--
-- AUTO_INCREMENT for table `source_type`
--
ALTER TABLE `source_type`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `system_logs`
--
ALTER TABLE `system_logs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=475;

--
-- AUTO_INCREMENT for table `task_comment`
--
ALTER TABLE `task_comment`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `task_statuses`
--
ALTER TABLE `task_statuses`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT for table `task_type`
--
ALTER TABLE `task_type`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `task_user`
--
ALTER TABLE `task_user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `tax_rates`
--
ALTER TABLE `tax_rates`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `timers`
--
ALTER TABLE `timers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11489;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `accounts`
--
ALTER TABLE `accounts`
  ADD CONSTRAINT `accounts_domain_id_foreign` FOREIGN KEY (`domain_id`) REFERENCES `domains` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `account_user`
--
ALTER TABLE `account_user`
  ADD CONSTRAINT `account_user_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `account_user_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `addresses`
--
ALTER TABLE `addresses`
  ADD CONSTRAINT `addresses_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `addresses_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `cities`
--
ALTER TABLE `cities`
  ADD CONSTRAINT `cities_province_id_foreign` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`);

--
-- Constraints for table `client_contacts`
--
ALTER TABLE `client_contacts`
  ADD CONSTRAINT `client_contacts_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `client_gateway_tokens`
--
ALTER TABLE `client_gateway_tokens`
  ADD CONSTRAINT `client_gateway_tokens_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `client_gateway_tokens_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `comment_task`
--
ALTER TABLE `comment_task`
  ADD CONSTRAINT `task_comment_comment_id_foreign` FOREIGN KEY (`comment_id`) REFERENCES `comments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `companies`
--
ALTER TABLE `companies`
  ADD CONSTRAINT `companies_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `companies_ibfk_1` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `companies_ibfk_2` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `companies_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `company_contacts`
--
ALTER TABLE `company_contacts`
  ADD CONSTRAINT `company_contacts_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `company_contacts_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `company_contacts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `company_gateways`
--
ALTER TABLE `company_gateways`
  ADD CONSTRAINT `company_gateways_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `company_gateways_gateway_key_foreign` FOREIGN KEY (`gateway_key`) REFERENCES `gateways` (`key`),
  ADD CONSTRAINT `company_gateways_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `company_ledgers`
--
ALTER TABLE `company_ledgers`
  ADD CONSTRAINT `company_ledgers_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `company_ledgers_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `company_tokens`
--
ALTER TABLE `company_tokens`
  ADD CONSTRAINT `company_tokens_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `company_tokens_domain_id_foreign` FOREIGN KEY (`domain_id`) REFERENCES `domains` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `company_tokens_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `credits`
--
ALTER TABLE `credits`
  ADD CONSTRAINT `credits_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `credits_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `credits_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `credit_invitations`
--
ALTER TABLE `credit_invitations`
  ADD CONSTRAINT `credit_invitations_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `credit_invitations_credit_id_foreign` FOREIGN KEY (`credit_id`) REFERENCES `credits` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `credit_invitations_customer_id_foreign` FOREIGN KEY (`client_contact_id`) REFERENCES `client_contacts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `credit_invitations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `customers`
--
ALTER TABLE `customers`
  ADD CONSTRAINT `customers_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `customers_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `customers_ibfk_2` FOREIGN KEY (`industry_id`) REFERENCES `industries` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `departments`
--
ALTER TABLE `departments`
  ADD CONSTRAINT `departments_department_manager_foreign` FOREIGN KEY (`department_manager`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `departments_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `departments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `department_user`
--
ALTER TABLE `department_user`
  ADD CONSTRAINT `department_user_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `emails`
--
ALTER TABLE `emails`
  ADD CONSTRAINT `emails_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `emails_ibfk_2` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `events_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `events_ibfk_3` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `events_ibfk_4` FOREIGN KEY (`event_type`) REFERENCES `event_types` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `event_task`
--
ALTER TABLE `event_task`
  ADD CONSTRAINT `event_task_ibfk_1` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `event_task_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `event_user`
--
ALTER TABLE `event_user`
  ADD CONSTRAINT `event_user_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `event_user_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `expenses_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `expenses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `files`
--
ALTER TABLE `files`
  ADD CONSTRAINT `files_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `files_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `group_settings`
--
ALTER TABLE `group_settings`
  ADD CONSTRAINT `group_settings_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `invoices_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `invoices_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `invoice_invitations`
--
ALTER TABLE `invoice_invitations`
  ADD CONSTRAINT `invoice_invitations_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `invoice_invitations_customer_id_foreign` FOREIGN KEY (`client_contact_id`) REFERENCES `client_contacts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `invoice_invitations_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `invoice_invitations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `leads`
--
ALTER TABLE `leads`
  ADD CONSTRAINT `leads_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `leads_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `leads_ibfk_3` FOREIGN KEY (`source_type`) REFERENCES `source_type` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `leads_ibfk_4` FOREIGN KEY (`assigned_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `leads_ibfk_5` FOREIGN KEY (`industry_id`) REFERENCES `industries` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `messages_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_invitations`
--
ALTER TABLE `order_invitations`
  ADD CONSTRAINT `order_invitations_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `order_invitations_ibfk_2` FOREIGN KEY (`client_contact_id`) REFERENCES `client_contacts` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `order_invitations_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `order_invitations_ibfk_4` FOREIGN KEY (`order_id`) REFERENCES `product_task` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `paymentables`
--
ALTER TABLE `paymentables`
  ADD CONSTRAINT `paymentables_ibfk_1` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_client_contact_id_foreign` FOREIGN KEY (`client_contact_id`) REFERENCES `client_contacts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `payments_company_gateway_id_foreign` FOREIGN KEY (`company_gateway_id`) REFERENCES `company_gateways` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `payments_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_payment_type_id_foreign` FOREIGN KEY (`type_id`) REFERENCES `payment_methods` (`id`),
  ADD CONSTRAINT `payments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `permission_user`
--
ALTER TABLE `permission_user`
  ADD CONSTRAINT `permission_user_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `products_brand_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_attributes`
--
ALTER TABLE `product_attributes`
  ADD CONSTRAINT `product_attributes_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_task`
--
ALTER TABLE `product_task`
  ADD CONSTRAINT `product_task_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `product_task_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `product_task_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`);

--
-- Constraints for table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `projects_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `provinces`
--
ALTER TABLE `provinces`
  ADD CONSTRAINT `provinces_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`);

--
-- Constraints for table `quotes`
--
ALTER TABLE `quotes`
  ADD CONSTRAINT `quotes_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `quotes_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `quotes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `quote_invitations`
--
ALTER TABLE `quote_invitations`
  ADD CONSTRAINT `quote_invitations_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `quote_invitations_customer_id_foreign` FOREIGN KEY (`client_contact_id`) REFERENCES `client_contacts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `quote_invitations_quote_id_foreign` FOREIGN KEY (`quote_id`) REFERENCES `quotes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `quote_invitations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `recurring_invoices`
--
ALTER TABLE `recurring_invoices`
  ADD CONSTRAINT `recurring_invoices_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `recurring_invoices_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `recurring_invoices_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `recurring_quotes`
--
ALTER TABLE `recurring_quotes`
  ADD CONSTRAINT `recurring_quotes_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `recurring_quotes_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `recurring_quotes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `roles`
--
ALTER TABLE `roles`
  ADD CONSTRAINT `roles_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `roles_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `role_user`
--
ALTER TABLE `role_user`
  ADD CONSTRAINT `role_user_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `role_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `states`
--
ALTER TABLE `states`
  ADD CONSTRAINT `states_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`);

--
-- Constraints for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD CONSTRAINT `subscriptions_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `subscriptions_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `system_logs`
--
ALTER TABLE `system_logs`
  ADD CONSTRAINT `system_logs_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `system_logs_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tasks_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `tasks_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `tasks_source_type_foreign` FOREIGN KEY (`source_type`) REFERENCES `source_type` (`id`);

--
-- Constraints for table `task_user`
--
ALTER TABLE `task_user`
  ADD CONSTRAINT `task_user_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`),
  ADD CONSTRAINT `task_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `tax_rates`
--
ALTER TABLE `tax_rates`
  ADD CONSTRAINT `tax_rates_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tax_rates_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `timers`
--
ALTER TABLE `timers`
  ADD CONSTRAINT `timers_ibfk_1` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `timers_ibfk_2` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `timers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`domain_id`) REFERENCES `domains` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
