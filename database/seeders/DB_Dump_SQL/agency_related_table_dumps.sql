INSERT INTO `agency_addresses` (`id`, `agency_id`, `address1`, `address2`, `country_id`, `state`, `city`, `pincode`, `created_at`, `updated_at`) VALUES (NULL, '1', '4th Floor, Sunrise Avenue, Stadium - Commerce Six Road, Opp: Saraspur Nagrik Bank, Navrangpura, Ahmedabad, Gujarat 380009', NULL, '80', 'Gujarat', 'Ahmedabad', '380009', NULL, NULL);

INSERT INTO `agency_addresses` (`id`, `agency_id`, `address1`, `address2`, `country_id`, `state`, `city`, `pincode`, `created_at`, `updated_at`) VALUES (NULL, '2', '4th Floor, Sunrise Avenue, Stadium - Commerce Six Road, Opp: Saraspur Nagrik Bank, Navrangpura, Ahmedabad, Gujarat 380009', NULL, '80', 'Gujarat', 'Ahmedabad', '380009', NULL, NULL);


INSERT INTO `agency_currencies` (`id`, `agency_id`, `currency_id`, `created_at`, `updated_at`) VALUES (NULL, '1', '64', NULL, NULL), (NULL, '1', '147', NULL, NULL), (NULL, '2', '49', NULL, NULL), (NULL, '2', '64', NULL, NULL), (NULL, '2', '147', NULL, NULL);

INSERT INTO `agency_payment_gateways` (`id`, `agency_id`, `core_payment_gateway_id`, `created_at`, `updated_at`) VALUES (NULL, '1', '1', NULL, NULL), (NULL, '2', '1', NULL, NULL);


INSERT INTO `agency_payment_types` (`id`, `agency_id`, `core_payment_type_id`, `created_at`, `updated_at`) VALUES (NULL, '1', '1', NULL, NULL), (NULL, '1', '2', NULL, NULL), (NULL, '1', '3', NULL, NULL), (NULL, '1', '4', NULL, NULL), (NULL, '1', '5', NULL, NULL), (NULL, '2', '1', NULL, NULL), (NULL, '2', '2', NULL, NULL), (NULL, '2', '3', NULL, NULL), (NULL, '2', '4', NULL, NULL), (NULL, '2', '5', NULL, NULL);

INSERT INTO `agency_service_types` (`id`, `agency_id`, `core_service_type_id`, `created_at`, `updated_at`) VALUES (NULL, '1', '1', NULL, NULL), (NULL, '1', '2', NULL, NULL), (NULL, '1', '3', NULL, NULL), (NULL, '1', '4', NULL, NULL), (NULL, '2', '5', NULL, NULL), (NULL, '2', '6', NULL, NULL), (NULL, '2', '7', NULL, NULL), (NULL, '2', '8', NULL, NULL);
