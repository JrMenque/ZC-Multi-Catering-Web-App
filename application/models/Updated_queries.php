<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Updated_queries extends CI_Model
{
	public function __construct()
	{
		// parent::__construct();
		$this->db->query("SET sql_mode = ''");
		$this->load->dbforge();
	}
	public function install_version($version)
	{
		$data = [];

		do {
			if ($version > 1.2 && $version < 2.4) {
				$new_version = 2.4;
				$data = ['st' => 3, "msg" => 'You have to update it from using YOUR_URL/update', 'version' => $new_version];
				break;
			}



			if ($version < 2.5) :
				$new_version = 2.5;

				$check_slug = $this->check_slug('paystack', 'payment_method_list');
				if ($check_slug == 0) :
					$this->db->query('INSERT INTO payment_method_list(name,slug,active_slug,status_slug,status) VALUES ("Paystack","paystack","paystack_status","is_paystack",1)');
				endif;


				$addColumnQueries = [

					'settings' => [
						'paystack_status' => "int  NOT NULL DEFAULT 1",
						'is_paystack' => "int  NOT NULL DEFAULT 0",
						'paystack_config' => "LONGTEXT NULL",
						'nearby_length' => "VARCHAR(20) NULL DEFAULT 5",
						'extras' => "LONGTEXT NULL",
						'notifications' => "LONGTEXT NULL",
					],

					'restaurant_list' => [
						'paystack_status' => "int  NOT NULL DEFAULT 1",
						'is_paystack' => "int  NOT NULL DEFAULT 0",
						'paystack_config' => "LONGTEXT NULL",
						'is_admin_onsignal' => "INT NOT NULL DEFAULT 0",
					],

					'item_extras' => [
						'ex_id' => "INT NOT NULL DEFAULT 0",
					],

					'users_active_order_types' => [
						'is_required' => "INT NOT NULL DEFAULT 0",
					],

				];


				if (!$this->db->table_exists('extra_libraries')) :
					$this->db->query("CREATE TABLE `extra_libraries` ( `id` INT NOT NULL AUTO_INCREMENT , `user_id` INT NOT NULL , `shop_id` INT NOT NULL , `name` VARCHAR(200) NOT NULL ,`price` VARCHAR(200) NOT NULL , `status` VARCHAR(200) NOT NULL DEFAULT 1, PRIMARY KEY (`id`)) ENGINE = InnoDB;");
				endif;


				if (!$this->db->table_exists('extra_libraries')) :
					$this->db->query('CREATE TABLE `extra_libraries` (
					  	`id` int(11) NOT NULL AUTO_INCREMENT,
					  	`shop_id` int(11) NOT NULL,
					  	`auth_id` int(11) NOT NULL,
					  	`user_id` VARCHAR(200) NOT NULL,
					  	`created_at` datetime NOT NULL,
					  	`status` int(11) NOT NULL DEFAULT 1,
					  	PRIMARY KEY (`id`)
					  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;');
				endif;





				$keywords = ['payment_required', 'hide_pay_later', 'notifications_send_successfully', 'send_notifications', 'paystack_secret_key', 'all_extras'];
				$check_keywords = $this->check_keywords($keywords);
				if ($check_keywords == 0) :
					$this->db->query("INSERT INTO `language_data` (`keyword`, `type`, `details`, `english`) VALUES
						('payment_required','admin','Payment Required','Payment Required'),
						('hide_pay_later','admin','Hide Pay later','Hide Pay later'),
						('notifications_send_successfully','admin','Notifications send successfully','Notifications send successfully'),
						('send_notifications','admin','Send Notification','Send Notification'),
						('custom_link','admin','Custom Link','Custom Link'),
						('disabled_onsignal_access','admin','Disabled onSignal Notification','Disabled onSignal Notification'),
						('allow_onsignal_access','admin','Allow onSignal Notification','Allow onSignal Notification'),
						('user_auth_key','admin','User Auth Key','User Auth Key'),
						('onsignal_app_id','admin','Onesignal App ID','Onesignal App ID'),
						('onsignal_api','admin','onSignal API','onSignal API'),
						('add_extra','admin','Add Extra','Add Extra'),
						('all_extras','admin','All Extras','All Extras'),
						('nearby_radius','admin','Nearby Radius','Nearby Radius'),
						('paystack_payment_gateways','admin','Paystack Payment Gateways','Paystack Payment Gateways'),
						('paystack_secret_key','admin','Paystack Secret Key','Paystack Secret Key'),
						('paystack_publick_key','admin','Paystack Public Key','Paystack Public Key'),
						('paystack','admin','Paystack','Paystack');");

				endif;



				$addColumn = $this->sql_command($addColumnQueries);

				if (isset($addColumn['st']) && $addColumn['st'] == 0) :
					$data = ["st" => 0, "msg" => $addColumn['msg'], 'version' => $new_version];
				else :

					$data = ['st' => 1, "msg" => 'Updated Successfully', 'version' => $new_version];
				endif;

				break;
			endif;
			/*----------------------------------------------
			  				End version 2.5
			----------------------------------------------*/


			if ($version < 2.6) :
				$new_version = 2.6;


				$this->db->query('ALTER TABLE items MODIFY 	allergen_id VARCHAR(200) NULL');
				$this->db->query('ALTER TABLE settings MODIFY `social_sites` LONGTEXT NULL');

				$addColumnQueries = [

					'order_user_list' => [
						'is_change' => "INT  NOT NULL DEFAULT 0",
						'change_amount' => "VARCHAR(50) NOT NULL DEFAULT 0",
					],

					'restaurant_list' => [
						'is_question' => "INT  NOT NULL DEFAULT 0",
						'is_radius' => "INT  NOT NULL DEFAULT 0",
						'radius_config' => "LONGTEXT NULL",
						'is_tax' => "INT NOT NULL DEFAULT 0",
						'tax_status' => "VARCHAR(10)  NOT NULL DEFAULT '+'",
						'is_kds_pin' => "INT(11)  NOT NULL DEFAULT 0",
						'kds_pin' => "VARCHAR(20) NULL",
					],

					'staff_list' => [
						'question' => "LONGTEXT  NULL",
					],

					'items' => [
						'tax_fee' => "VARCHAR(10)  NOT NULL DEFAULT 0",
						'tax_status' => "VARCHAR(10)  NOT NULL DEFAULT '+'",
					],

				];



				if (!$this->db->table_exists('question_list')) :
					$this->db->query('CREATE TABLE `question_list` (
						`id` int(11) NOT NULL AUTO_INCREMENT,
						`title` VARCHAR(200) NOT NULL,
						`user_id` VARCHAR(200) NOT NULL,
						`created_at` datetime NOT NULL,
						`status` int(11) NOT NULL DEFAULT 1,
						PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;');
				endif;



				$keywords = ['enter_pin', 'kds_pin', 'tax_excluded', 'tax_included', 'item_tax_status', 'security_question', 'signup_questions'];
				$check_keywords = $this->check_keywords($keywords);
				if ($check_keywords == 0) :
					$this->db->query("INSERT INTO `language_data` (`keyword`, `type`, `details`, `english`) VALUES
						('enter_pin','admin','Enter Pin','Enter Pin'),
						('kds_pin','admin','KDS Pin','KDS Pin'),
						('tax_excluded','admin','Tax Excluded','Tax Excluded'),
						('tax_included','admin','Tax Included','Tax Included'),
						('item_tax_status','admin','Item Tax Status','Item Tax Status'),
						('price_tax_msg','admin','Tax are only for showing tax status in invoice. Set your price including/excluding tax','Tax are only for showing tax status in invoice. Set your price including/excluding tax'),
						('not_found_msg','admin','Not Found Message','Not Found Message'),
						('radius','admin','Radius','Radius'),
						('radius_base_delivery_settings','admin','Enable Radius Based Delivery Settings','Radius Based Delivery Settings'),
						('delivery_settings','admin','Delivery Settings','Delivery Settings'),
						('enable_radius_base_delivery','admin','Enable Raduis Based Delivery','Enable Radius Based Delivery'),
						('change_amount','admin','Change Amount','Change Amount'),
						('change','admin','Change','Change'),
						('security_question_ans_not_correct','admin','Security Questions answer is not correct','Security Questions answer is not correct'),
						('enable_security_question','admin','Enable Security Question','Enable Security Question'),
						('write_your_answer_here','admin','Write your answer here','Write your answer here'),
						('security_question','admin','Security Question','Security Question'),
						('signup_questions','admin','Signup Questions','Signup Questions'),
						('half_yearly','admin','Half Year / 6 month','Half Year- 6 month'),
						('6_month','admin','Half Year / 6 month','Half Year / 6 month'),
						('table_no','admin','Table No','Table No');");

				endif;



				$addColumn = $this->sql_command($addColumnQueries);

				if (isset($addColumn['st']) && $addColumn['st'] == 0) :
					$data = ["st" => 0, "msg" => $addColumn['msg'], 'version' => $new_version];
				else :

					$data = ['st' => 1, "msg" => 'Updated Successfully', 'version' => $new_version];
				endif;

				break;
			endif;



			if ($version < 2.7) {
				$new_version = 2.7;

				if ($this->checkExistFields('restaurant_list', 'is_questions') == 1) {
					$this->db->query('ALTER TABLE restaurant_list CHANGE  `is_questions` `is_question` INT  NOT NULL DEFAULT 0');
				}

				if ($this->checkExistFields('staff_list', 'questions') == 1) {
					$this->db->query('ALTER TABLE staff_list CHANGE  `questions` `question` LONGTEXT  NULL');
				}

				$addColumnQueries = [
					'settings' => [
						'restaurant_demo' => "VARCHAR(50) NULL",
						'sendgrid_api_key' => "LONGTEXT NULL",
						'currency_position' => "INT NOT NULL DEFAULT 1",
						'number_formats' => "INT NOT NULL DEFAULT 1",
						'offline_status' => "INT NOT NULL DEFAULT 1",
						'is_offline' => "INT NOT NULL DEFAULT 1",
						'offline_config' => "LONGTEXT NULL",
					],

					'user_settings' => [
						'onesignal_config' => "LONGTEXT NULL",
						'extra_config' => "LONGTEXT NULL",
					],

					'restaurant_list' => [
						'order_view_style' => "INT NOT NULL DEFAULT 1",
					],

					'reservation_date' => [
						'is_24' => "INT NOT NULL DEFAULT 0",
					],

					'packages' => [
						'custom_fields_config' => "LONGTEXT NULL",
					],


				];


				$check_slug = $this->check_slug('offline', 'payment_method_list');
				if ($check_slug == 0) :
					$this->db->query('INSERT INTO payment_method_list(name,slug,active_slug,status_slug,status) VALUES ("Offline","offline","offline_status","is_offline",1)');
				endif;

				$check_slug = $this->check_slug('pwa-push', 'features');
				if ($check_slug == 0) :
					$this->db->query("INSERT INTO features(id,features,slug,status,is_features,created_at) VALUES 
						('11','OneSignal & PWA','pwa-push','1','1','2022-09-08 23:04:31')");
				endif;

				if (!$this->db->table_exists('admin_notification')) :
					$this->db->query('CREATE TABLE `admin_notification` (
						`id` int(11) NOT NULL AUTO_INCREMENT,
						`notification_id` INT(11) NULL,
						`restaurant_id` INT(11)  NULL,
						`status` INT(11)  NOT NULL DEFAULT 1,
						`seen_status` INT(11)  NOT NULL DEFAULT 0,
						`is_admin_enable` INT(11)  NOT NULL DEFAULT 1,
						`created_at` datetime  NULL,
						`send_at` datetime  NULL,
						`seen_time` datetime  NULL,
						PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;');
				endif;


				if (!$this->db->table_exists('admin_notification_list')) :
					$this->db->query('CREATE TABLE `admin_notification_list` (
						`id` int(11) NOT NULL AUTO_INCREMENT,
						`title` VARCHAR(200) NULL,
						`details` LONGTEXT NULL ,
						`status` INT(11)  NOT NULL DEFAULT 1,
						`created_at` datetime  NULL,
						PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;');
				endif;


				$addColumn = $this->sql_command($addColumnQueries);

				if (isset($addColumn['st']) && $addColumn['st'] == 0) :
					$data = ["st" => 0, "msg" => $addColumn['msg'], 'version' => $new_version];
				else :

					$data = ['st' => 1, "msg" => 'Updated Successfully', 'version' => $new_version];
				endif;

				break;
			}



			if ($version < 2.8) {
				$new_version = 2.8;

				$addColumnQueries = [

					'order_user_list' => [
						'is_restaurant_payment' => "INT NOT NULL DEFAULT 0",
						'is_db_request' => "INT NOT NULL DEFAULT 0",
						'db_completed_by' => "VARCHAR(200) NOT NULL DEFAULT 'staff'",
						'hotel_id' => "INT NOT NULL",
						'room_number' => "VARCHAR(200) NULL",
					],

					'restaurant_list' => [
						'room_number' => "VARCHAR(200) NULL",
						'is_db_request' => "INT NOT NULL DEFAULT 0",
						'db_completed_by' => "VARCHAR(200) NOT NULL DEFAULT 'staff'",
						'hotel_id' => "INT NOT NULL",
						'whatsapp_enable_for' => "longtext NOT NULL",
					],

					'reservation_date' => [
						'is_24' => "INT NOT NULL DEFAULT 0",
					],

				];


				$this->db->query('ALTER TABLE settings MODIFY version VARCHAR(20) NULL');

				if (!$this->db->table_exists('hotel_list')) :
					$this->db->query('CREATE TABLE `hotel_list`(
						`id` int(11) NOT NULL AUTO_INCREMENT,
						`hotel_name` VARCHAR(200) NULL,
						`user_id` INT(11) NOT NULL,
						`shop_id` INT(11) NOT NULL,
						`room_numbers` LONGTEXT NULL ,
						`status` INT(11)  NOT NULL DEFAULT 1,
						`created_at` datetime  NULL,
						PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;');
				endif;



				$check_slug = $this->check_slug('pay-cash', 'order_types');
				if ($check_slug == 0) :
					$this->db->query("INSERT INTO order_types(id,name,slug,status,is_order_types,created_at) VALUES 
							('9','Pay cash','pay-cash','1','1','2022-09-20 23:04:31')");
				endif;


				$check_slug = $this->check_slug('room-service', 'order_types');
				if ($check_slug == 0) :
					$this->db->query("INSERT INTO order_types(id,name,slug,status,is_order_types,created_at) VALUES 
							('8','Room Service','room-service','1','1','2022-09-20 23:04:31')");
				endif;




				$keywords = ['package_restaurant_dine_in', 'room_number', 'sorry_room_numbers_not_available', 'enable_whatsapp_for_order', 'completed_paid', 'add_delivery_boy'];
				$check_keywords = $this->check_keywords($keywords);
				if ($check_keywords == 0) :
					$this->db->query("INSERT INTO `language_data` (`keyword`, `type`, `details`, `english`) VALUES
						('package_restaurant_dine_in','admin','Package / Restaurant Dine-In','Package / Restaurant Dine-In'),
						('room_number','admin','Room Number','Room Number'),
						('sorry_room_numbers_not_available','admin','Sorry Room Not found','Sorry Room Not found'),
						('room_numbers','admin','Room Numbers','Room Numbers'),
						('hotel_list','admin','Hotel List','Hotel List'),
						('hotel_name','admin','Hotel Name','Hotel Name'),
						('room_services','admin','Room services','Room services'),
						('enable_whatsapp_for_order','admin','Enable WhatsApp For order','Enable WhatsApp For order'),
						('table-dine-in','admin','Table / Dine-in','Table / Dine-in'),
						('sorry_today_pickup_time_is_not_available','admin','Sorry, Pickup Time is not available today','Sorry, Pickup Time is not available today'),
						('please_login_to_continue','admin','Please Login to continue.','Please Login to continue.'),
						('account_confirmation_link_msg','admin','The account confirmation link has been emailed to you, follow the link in the email to continue.','The account confirmation link has been emailed to you, follow the link in the email to continue.'),
						('account_created_successfully','admin','Account Created Successfully','Account Created Successfully'),
						('vendor','admin','Vendor','Vendor'),
						('selectd_by_restaurant','admin','Selected by Restaurant','Selected by Restaurant'),
						('dboy_name','admin','Delivery Guy','Delivery Guy'),
						('add_delivery_boy','admin','Add delivery Boy','Add delivery guy'),
						('completed_paid','admin','Completed & Paid','Completed & Paid'),
						('mark_as_completed_paid','admin','Mark as completed & Paid','Mark as completed & Paid'),
						('unpaid','admin','Unpaid','Unpaid'),
						('mark_as_paid','admin','Mark as Paid','Mark as Paid'),
						('select_delivery_boy','admin','Select Delivery Boy','Select Delivery Boy'),
						('delivered','admin','Delivered','Delivered'),
						('open_24_hours','admin','Open 24 Hours','Open 24 Hours'),
						('enable_24_hours','admin','Enable 24 Hours','Enable 24 Hours'),
						('select_room_number','admin','Select Room Number','Select Room Number'),
						('mark_as_delivered','admin','Mark as delivered','Mark as delivered');");

				endif;

				$addColumn = $this->sql_command($addColumnQueries);

				if (isset($addColumn['st']) && $addColumn['st'] == 0) :
					$data = ["st" => 0, "msg" => $addColumn['msg'], 'version' => $new_version];
				else :

					$data = ['st' => 1, "msg" => 'Updated Successfully', 'version' => $new_version];
				endif;
				break;
			}

			if ($version < 2.9) {
				$new_version = 2.9;

				$addColumnQueries = [
					'order_user_list' => [
						'payment_notes' => "TEXT NULL",
						'sell_notes' => "TEXT NULL",
						'received_amount' => "VARCHAR(200) NULL",
						'is_pos' => "INT NOT NULL DEFAULT 0",
						'is_live_order' => "INT NOT NULL DEFAULT 1",
					],

					'restaurant_list' => [
						'room_number' => "VARCHAR(200) NULL",
						'is_db_request' => "INT NOT NULL DEFAULT 0",
						'db_completed_by' => "VARCHAR(200) NOT NULL DEFAULT 'staff'",
						'hotel_id' => "INT NOT NULL",
						'time_zone' => "VARCHAR(200) NOT NULL DEFAULT 'Asia/Dhaka'",
						'is_checkout_mail' => "INT NOT NULL DEFAULT 0",
					],

					'reservation_date' => [
						'is_24' => "INT NOT NULL DEFAULT 0",
					],

					'user_settings' => [
						'pos_config' => "LONGTEXT NULL",
						'order_mail_config' => "LONGTEXT NULL",
						'sendgrid_api_key' => "TEXT NULL",
					],

					'order_item_list' => [
						'item_comments' => "TEXT NULL",
					],

					'packages' => [
						'custom_fields_config' => "LONGTEXT NULL",
					],

				];


				$check_id = $this->check_id(9, 'order_types');
				if ($check_id == 0) :
					$this->db->query("INSERT INTO order_types(id,name,slug,status,is_order_types,created_at) VALUES 
							('9','Pay cash','pay-cash','1','1','2022-09-20 23:04:31')");
				endif;



				$check_slug = $this->check_slug('package-dine-in', 'order_types');
				if ($check_slug == 0) :
					$data = [
						'id' => 7,
						'name' => 'Package / Restaurant Dine-in',
						'slug' => 'package-dine-in',
						'status' => 1,
						'is_order_types' => 0,
						'created_at' => '2022-09-20 23:04:31',
					];

					$this->common_m->update($data, 7, 'order_types');
				endif;



				if (!$this->db->table_exists('customer_list')) :
					$this->db->query('CREATE TABLE `customer_list`(
						`id` int(11) NOT NULL AUTO_INCREMENT,
						`user_id` INT NOT NULL, 
						`shop_id` INT NOT NULL , 
						`customer_name` VARCHAR(200) NOT NULL, 
						`email` VARCHAR(200)  NULL , 
						`phone` VARCHAR(50)  NULL , 
						`country` VARCHAR(50)  NULL ,
						`city` VARCHAR(50)  NULL , 
						`address` TEXT NULL , 
						`tax_number` VARCHAR(200)  NULL ,
						`is_membership` INT  NULL DEFAULT 0,
						`status` INT NOT NULL DEFAULT 1,
						`created_at` DATETIME NOT NULL , 
						PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;');
				endif;


				if (!$this->db->table_exists('addons_list')) :
					$this->db->query('CREATE TABLE `addons_list`(
						`id` int(11) NOT NULL AUTO_INCREMENT,
						`user_id` INT NOT NULL , 
						`script_name` VARCHAR(200) NOT NULL, 
						`slug` VARCHAR(200) NOT NULL, 
						`item_id` VARCHAR(200) NOT NULL, 
						`purchase_code` VARCHAR(200)  NULL , 
						`script_purchase_code` VARCHAR(200)  NULL , 
						`license_name` VARCHAR(200)  NULL , 
						`site_url` VARCHAR(200)  NULL , 
						`active_key` VARCHAR(200) NULL ,
						`active_code` VARCHAR(200) NULL ,
						`license_code` VARCHAR(200) NULL,
						`purchase_date` DATETIME NULL ,
						`active_date` DATETIME  NULL ,
						`activated_date` DATETIME  NULL ,
						`is_active` INT  NULL DEFAULT 0,
						`is_install` INT  NULL DEFAULT 0,
						`status` INT NOT NULL DEFAULT 1,
						`created_at` DATETIME NOT NULL , 
						PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;');
				endif;





				$keywords = ['add_ons', 'waiting_for_picked', 'your_order_is_ready_to_delivery', 'customer_mail', 'item_sales_count', 'previous_week_earning'];
				$check_keywords = $this->check_keywords($keywords);
				if ($check_keywords == 0) :
					$this->db->query("INSERT INTO `language_data` (`keyword`, `type`, `details`, `english`) VALUES
					('waiter_calling','admin','Waiter Calling','Waiter Calling'),
					('have_a_new_order','admin','Have a new Order','Have a new Order'),
					('there_are_customers','admin','There Are Customers','There Are Customers'),
					('the_table_is_empty','admin','The Table is empty','The Table is empty'),
					('add_ons','admin','Add-Ons','Add-Ons'),
					('waiting_for_picked','admin','Waiting For Picked','Waiting For Picked'),
					('your_order_is_ready_to_delivery','admin','Your Order is ready to delivery','Your Order is ready to delivery'),
					('enable_mail_in_checkout','admin','Enable Mail in checkout','Enable Mail in checkout'),
					('customer_mail','admin','Customer mail','Customer mail'),
					('order_receive_mail','admin','Order Reveiver Mail','Order Receiver Mail'),
					('enable_mail','admin','Enable Mail','Enable Mail'),
					('restaurant_owner','admin','Restaurant Owner','Restaurant Owner'),
					('order_mail','admin','Order Mail','Order Mail'),
					('previous_week_earning','admin','Previous Week Earning','Previous Week Earning'),
					('weekly_earning','admin','Weekly Earning','Weekly Earning'),
					('previous_month_earning','admin','Previous Month Earning','Previous Month Earning'),
					('monthly_earning','admin','Monthly Earning','Monthly Earning'),
					('todays_earning','admin','Todays Earning','Todays Earning'),
					('balance','admin','Balance','Balance'),
					('all_time','admin','All Time','All Time'),
					('total_order','admin','Total Order','Total Orders'),
					('item_sales_count','admin','Item Sales Count','Item Sales Count'),
					('reports','admin','Reports','Reports'),
					('earnings','admin','Earnings','Earnings'),
					('of','admin','Of','Of'),
					('to','admin','To','To'),
					('showing','admin','Showing','Showing'),
					('entries','admin','Entries','Entries'),
					('last','admin','Last','Last'),
					('first','admin','First','First'),
					('previous','admin','Previous','Previous'),
					('next','admin','Next','Next'),
					('restaurant_email','admin','Restaurant Email','Restaurant Email'),
					('scroll_top_arrow','admin','Scroll Top Arrow','Scroll Top Arrow'),
					('pagination_limit','admin','Pagination Limit','Pagination Limit'),
					('total_items','admin','Total Items','Total Items'),
					('pos','admin','POS','POS'),
					('bank_transfer','admin','Bank Transfer','Bank Transfer'),
					('cheques','admin','Cheques','Cheques'),
					('cash','admin','Cash','Cash'),
					('sell_notes','admin','Sell Notes','Sell Notes'),
					('payment_notes','admin','Payment Notes','Payment Notes'),
					('change_return','admin','Change Return','Change Return'),
					('paying_amount','admin','Paying Amount','Paying Amount'),
					('received_amount','admin','Received Amount','Received Amount'),
					('payment_type','admin','Payment Type','Payment Type'),
					('process_to_complete','admin','Process to complete','Process to complete'),
					('booked','admin','Booked','Booked'),
					('remaining_person','admin','Remaining Person','Remaining Person'),
					('shipping_charge','admin','Shipping Charge','Shipping Charge'),
					('check_coupon_code','admin','Check Coupon Code','Check Coupon Code'),
					('coupon','admin','Coupon','Coupon');");

				endif;



				$addColumn = $this->sql_command($addColumnQueries);

				if (isset($addColumn['st']) && $addColumn['st'] == 0) :
					$data = ["st" => 0, "msg" => $addColumn['msg'], 'version' => $new_version];
				else :

					$data = ['st' => 1, "msg" => 'Updated Successfully', 'version' => $new_version];
				endif;
				break;
			}

			if ($version < 3.0) {
				$new_version = 3.0;

				$addColumnQueries = [
					'users_active_order_types' => [
						'is_admin_enable' => "INT NOT NULL DEFAULT 1",
					],

					'restaurant_list' => [
						'whatsapp_enable_for' => "longtext NOT NULL",
					],

					'order_user_list' => [
						'is_draft' => "INT NOT NULL DEFAULT 0",
					],



				];

				$keywords = ['tax_number', 'city', 'i_need_change', 'language_switcher'];
				$check_keywords = $this->check_keywords($keywords);
				if ($check_keywords == 0) :
					$this->db->query("INSERT INTO `language_data` (`keyword`, `type`, `details`, `english`) VALUES
					 	('tax_number','admin','Tax Number','Tax Number'),
					 	('city','admin','City','City'),
					 	('i_need_change','admin','I need Change','I need Change'),
					 	('language_switcher','admin','Language switcher','Language switcher'),
					 	('enable_coupon','admin','Enable Coupon','Enable Coupon');");

				endif;



				$addColumn = $this->sql_command($addColumnQueries);

				if (isset($addColumn['st']) && $addColumn['st'] == 0) :
					$data = ["st" => 0, "msg" => $addColumn['msg'], 'version' => $new_version];
				else :

					$data = ['st' => 1, "msg" => 'Updated Successfully', 'version' => $new_version];
				endif;
			}

			if ($version < '3.1.0') {
				$new_version = '3.1.0';

				$addColumnQueries = [
					'permission_list' => [
						'role' => "VARCHAR(20) NOT NULL DEFAULT 'user'",
					],

					'users' => [
						'staff_id' => "INT NOT NULL DEFAULT 1",
					],

					'packages' => [
						'duration' => "VARCHAR(20) NULL",
						'duration_period' => "VARCHAR(20) NULL",
					],

					'settings' => [
						'site_color' => "VARCHAR(50) NOT NULL DEFAULT '29c7ac'",
						'site_theme' => "INT(11) NOT NULL DEFAULT 1",
						'pagadito_config' => "LONGTEXT NULL",
						'is_pagadito' => "INT NOT NULL DEFAULT 0",
						'pagadito_status' => "INT NOT NULL DEFAULT 0",
					],

					'user_settings' => [
						'site_theme' => "INT(11) NOT NULL DEFAULT 1",
					],

					'order_item_list' => [
						'is_merge' => "INT(11) NOT NULL DEFAULT 0",
					],

					'order_user_list' => [
						'is_order_merge' => "INT(11) NOT NULL DEFAULT 0",
					],

					'restaurant_list' => [
						'order_merge_config' => "LONGTEXT NULL",
						'is_cart' => "INT NOT NULL DEFAULT 1", 'pagadito_config' => "LONGTEXT NULL",
						'is_pagadito' => "INT NOT NULL DEFAULT 0",
						'pagadito_status' => "INT NOT NULL DEFAULT 0",
					],


				];



				$check_slug = $this->check_slug('pay-in-cash', 'order_types');
				if ($check_slug == 1) :
					$data = [
						'is_order_types' => 0,
					];

					$this->admin_m->update_slug($data, 'pay-in-cash', 'order_types');
				endif;


				if ($this->checkExistFields('permission_list', 'role') == 0) :
					$this->db->query('ALTER TABLE permission_list ADD (role VARCHAR(20) NOT NULL DEFAULT "user")');
				endif;



				if ($this->db->table_exists('permission_list')) :
					$this->db->truncate('permission_list');
					$this->db->query("INSERT INTO `permission_list` (`id`, `title`, `slug`, `status`, `role`) VALUES
						(1, 'Add New Item', 'add', 1, 'user'),
						(2, 'Update', 'update', 1, 'user'),
						(3, 'Delete', 'delete', 1, 'user'),
						(4, 'Settings Control', 'setting-control', 1, 'user'),
						(5, 'Order Control', 'order-control', 1, 'user'),
						(6, 'Profile Control', 'profile-control', 1, 'user'),
						(7, 'Change status', 'change-status', 1, 'user'),
						(8, 'Order cancel', 'order-cancel', 1, 'user'),
						(9, 'POS Order', 'pos-order', 1, 'user'),
						(10, 'POS Settings', 'pos-settings', 1, 'user'),
						(11, 'Add New User', 'add-user', 1, 'admin_staff'),
						(12, 'Change Package', 'change-package', 1, 'admin_staff'),
						(13, 'Package Control', 'package-control', 1, 'admin_staff'),
						(14, 'Language Control', 'language-control', 1, 'admin_staff'),
						(15, 'Home Control', 'home-control', 1, 'admin_staff'),
						(16, 'Reset Password', 'reset-password', 1, 'admin_staff'),
						(17, 'Access User Account', 'access-user-account', 1, 'admin_staff'),
						(18, 'Page Control', 'page-control', 1, 'admin_staff'),
						(19, 'Settings Control', 'settings-control', 1, 'admin_staff'),
						(20, 'Change user operation', 'change-user-operation', 1, 'admin_staff');");

				endif;





				if (!$this->db->table_exists('staff_activities')) :
					$this->db->query('CREATE TABLE `staff_activities` ( 
						`id` INT NOT NULL AUTO_INCREMENT, 
						`staff_id` INT NOT NULL,
						`user_id` INT NOT NULL,
						`auth_id` INT NOT NULL, 
						`role` VARCHAR(50) NOT NULL,
						`active_date` DATETIME NOT NULL,
						`is_renewal` INT NOT NULL DEFAULT 0,
						`old_package_id` INT NOT NULL DEFAULT 0,
						`renew_date` DATETIME NOT NULL ,
						`is_change_package` INT NOT NULL DEFAULT 0,
						`is_new` INT NOT NULL DEFAULT 0,
						`price` DOUBLE NULL  ,
						`package_id` INT NOT NULL, PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;');
				endif;


				$keywords = ['edit_order_details', 'make_it_as_single_order', 'order_merge', 'enable_order_merge'];
				$check_keywords = $this->check_keywords($keywords);
				if ($check_keywords == 0) :
					$this->db->query("INSERT INTO `language_data` (`keyword`, `type`, `details`, `english`) VALUES
					 	('edit_order_details','admin','Edit order details','Edit order details'),
						('make_it_as_single_order','admin','Make it as a single order','Make it as a single order'),
						('order_merge','admin','Order Merge','Order Merge'),
						('allow_customers_to_select','admin','Allow Customers to select','Allow Customers to select'),
						('merge_automatically','admin','Merge Automatically','Merge Automatically'),
						('enable_order_merge','admin','Enable Order merge','Enable Order merge'),
						('previous_order','admin','Previous Order','Previous Order'),
						('grand_total','admin','Grand Total','Grand Total'),
						('merge_with_previous_order','admin','Merge with previous order','Merge with previous order'),
						('add_extras_from_library','admin','Add Extras from library','Add Extras from library'),
						('dark','admin','Dark','Dark'),
						('light','admin','Light','Light'),
						('frontend_color','admin','Frontend Color','Frontend Color'),
						('appearance','admin','Appearance','Appearance'),
						('years','admin','Years','Years'),
						('months','admin','Months','Months'),
						('set_duration','admin','Set Duration','Set Duration'),
						('custom_days','admin','Custom Days','Custom Days'),
						('username_already_exists','admin','Username Already Exists','Username Already Exists'),
						('robot_verification_failed','admin','Robot verification Failed','Robot verification Failed'),
						('reset_password','admin','Reset Password','Reset Password'),
						('permission','admin','Permission','Permission'),
						('supervised_by','admin','supervised by','supervised by'),
						('table_already_booked_try_different_one','admin','Table already Booked try different one','Table already Booked try different one'),
						('staff_name','admin','Staff Name','Staff Name'),
						('staff_activities','admin','Staff Activities','Staff Activities'),
						('important_steps_to_fill','admin','Those Steps are most important to configure first','Those Steps are most important to configure first'),
						('renewal','admin','Renewal','Renewal'),
						('newly_added','admin','Newly added','Newly added'),
						('item_limit','admin','Item Limit','Item Limit'),
						('order_limits','admin','Order Limit','Order Limit'),
						('staff_login','admin','Staff Login','Staff Login'),
						('table_qr_builder','admin','Table Qr Builder','Table Qr Builder'),
						('package_qr_builder','admin','Package Qr Builder','Package Qr Builder');");

				endif;




				$check_slug = $this->check_slug('pagadito', 'payment_method_list');
				if ($check_slug == 0) :
					$this->db->query('INSERT INTO payment_method_list(name,slug,active_slug,status_slug,status) VALUES ("Pagadito","pagadito","pagadito_status","is_pagadito",1)');
				endif;





				$addColumn = $this->sql_command($addColumnQueries);


				if (isset($addColumn['st']) && $addColumn['st'] == 0) :
					$data = ["st" => 0, "msg" => $addColumn['msg'], 'version' => $new_version];
				else :

					$data = ['st' => 1, "msg" => 'Updated Successfully', 'version' => $new_version];
				endif;

				break;
			}


			if ($version < '3.1.1') {
				$new_version = '3.1.1';
				$addColumnQueries = [
					'settings' => [
						'pagadito_config' => "LONGTEXT NULL",
						'is_pagadito' => "INT NOT NULL DEFAULT 0",
						'pagadito_status' => "INT NOT NULL DEFAULT 0",
						'custom_domain_comments' => "LONGTEXT NULL",
						'is_custom_domain' => "INT NOT NULL DEFAULT 0",
					],

					'restaurant_list' => [
						'pagadito_config' => "LONGTEXT NULL",
						'is_pagadito' => "INT NOT NULL DEFAULT 0",
						'pagadito_status' => "INT NOT NULL DEFAULT 0",
					],

				];

				$this->db->query('ALTER TABLE staff_list MODIFY question LONGTEXT NULL');

				$check_slug = $this->check_slug('pagadito', 'payment_method_list');
				if ($check_slug == 0) :
					$this->db->query('INSERT INTO payment_method_list(name,slug,active_slug,status_slug,status) VALUES ("Pagadito","pagadito","pagadito_status","is_pagadito",1)');
				endif;



				if (!$this->db->table_exists('custom_domain_list')) :
					$this->db->query('CREATE TABLE `custom_domain_list` (
						  `id` int(11) NOT NULL AUTO_INCREMENT,
						  `request_id` varchar(25) NOT NULL,
						  `username` varchar(200) NOT NULL,
						  `user_id` int(11) NOT NULL,
						  `request_name` varchar(200) NOT NULL,
						  `url` varchar(200) NOT NULL,
						  `is_subdomain` int(11) NOT NULL DEFAULT 0,
						  `is_domain` int(11) NOT NULL DEFAULT 0,
						  `approved_date` datetime NOT NULL,
						  `request_date` datetime NOT NULL,
						  `status` int(11) NOT NULL DEFAULT 0,
						  `is_ready` int(11) NOT NULL DEFAULT 0,
						  `staff_id` int(11) NOT NULL DEFAULT 0,
						  `domain_type` varchar(200) NOT NULL,
						  `comments` longtext DEFAULT NULL, PRIMARY KEY (`id`)
						) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;');
				endif;



				$keywords = ['canceled_message', 'set_comments', 'url', 'running'];
				$check_keywords = $this->check_keywords($keywords);
				if ($check_keywords == 0) :
					$this->db->query("INSERT INTO `language_data` (`keyword`, `type`, `details`, `english`) VALUES
					 	('canceled_message','admin','Canceled Messge','Canceled Messge'),
						('approved_message','admin','Approved message','Approved message'),
						('approved_date','admin','Approved Date','Approved Date'),
						('set_comments','admin','Set Comments','Set Comments'),
						('domain_list','admin','Domain List','Domain List'),
						('custom_domain','admin','Custom Domain','Custom Domain'),
						('running','admin','Running','Running'),
						('url','admin','URL','URL'),
						('current_name','admin','Current Name','Current Name'),
						('request_name','admin','Request Name','Request Name'),
						('request_id','admin','Request ID','Request ID'),
						('pagadito','admin','Pagadito','Pagadito'),
						('send_request','admin','Send Request','Send Request'),
						('hold','admin','Hold','Hold');");

				endif;

				$addColumn = $this->sql_command($addColumnQueries);

				if (isset($addColumn['st']) && $addColumn['st'] == 0) :
					$data = ["st" => 0, "msg" => $addColumn['msg'], 'version' => $new_version];
				else :

					$data = ['st' => 1, "msg" => 'Updated Successfully', 'version' => $new_version];
				endif;
				break;
			}

			if ($version < '3.1.2') {
				$new_version = '3.1.2';
				$addColumnQueries = [
					'settings' => [
						'pusher_config' => "LONGTEXT NULL",
					],

					'user_settings' => [
						'pusher_config' => "LONGTEXT NULL",
						'is_banner' => "INT NOT NULL DEFAULT 0",
						'is_footer' => "INT NOT NULL DEFAULT 0",
					],

					'order_user_list' => [
						'merge_status' => "INT(11) NOT NULL DEFAULT 0",
						'is_guest_login' => "INT(11) NOT NULL DEFAULT 0",
					],

					'order_item_list' => [
						'merge_id' => "VARCHAR(200) NULL",
					],

					'restaurant_list' => [
						'guest_config' => "LONGTEXT NULL",
						'tips_config' => "LONGTEXT NULL",
					],

				];


				if (!$this->db->table_exists('expense_category_list')) :
					$this->db->query('CREATE TABLE `expense_category_list` (
						  `id` int(11) NOT NULL AUTO_INCREMENT,
						  `category_name` VARCHAR(25) NOT NULL,
						  `user_id` int(11) NOT NULL,
						  `shop_id` INT NOT NULL, PRIMARY KEY (`id`)
						) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;');
				endif;


				if (!$this->db->table_exists('expense_list')) :
					$this->db->query('CREATE TABLE `expense_list` (
						  `id` int(11) NOT NULL AUTO_INCREMENT,
						  `category_id` INT(11) NOT NULL,
						  `user_id` INT(11) NOT NULL,
						  `shop_id` INT NOT NULL, 
						  `title` VARCHAR(200)  NULL,
						  `created_at` date  NULL,
						  `amount` VARCHAR(200)  NULL,
						  `images` VARCHAR(200)  NULL,
						  `notes` LONGTEXT  NULL, PRIMARY KEY (`id`)

						) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;');
				endif;



				$this->db->query('ALTER TABLE order_user_list MODIFY email VARCHAR(200) NULL');
				$this->db->query('ALTER TABLE order_user_list MODIFY phone VARCHAR(200) NULL');
				$this->db->query('ALTER TABLE order_user_list MODIFY name VARCHAR(200) NULL');
				$this->db->query('ALTER TABLE order_user_list MODIFY address TEXT NULL');





				$keywords = ['enabled_for_restaurant', 'add_tip', 'login_as_guest', 'hide_footer'];
				$check_keywords = $this->check_keywords($keywords);
				if ($check_keywords == 0) :
					$this->db->query("INSERT INTO `language_data` (`keyword`, `type`, `details`, `english`) VALUES
					 	('set_tip_percent','admin','Set tip percent','Set tip percent'),
						('add_tip','admin','Add Tip','Add Tip'),
						('tips','admin','Tip','Tip'),
						('login_invalid','admin','Login invalid','Login invalid'),
						('account_verified_successfully','admin','Your account verified successfully','Your account verified successfully'),
						('username_is_available','admin','Congratulations! Username is available.','Congratulations! Username is available.'),
						('walk_in_customer','admin','Walk in customer','Walk in customer'),
						('or','admin','OR','OR'),
						('login_as_guest','admin','Login as guest','Login as guest'),
						('pay_cash','admin','Pay Cash','Pay Cash'),
						('guest_login','admin','Guest Login','Guest Login'),
						('enable_to_allow_guest_login_for_dine_in_pay_cash','admin','Enable to allow guest login for Dine-in & pay in cash.','Enable to allow guest login for Dine-in'),
						('notes','admin','Notes','Notes'),
						('expenses','admin','Expenses','Expenses'),
						('enable_development_mode','admin','Enable Development Mode','Enable Development Mode'),
						('enabled_for_restaurant','admin','Enable for restauratn','Status for restaurants'),
						('enabled','admin','Enabled','Enabled'),
						('disabled','admin','Disabled','Disabled'),
						('merged_item','admin','Merged Item','Merged Item'),
						('order_merged','admin','Order Merged','Order Merged'),
						('merge_id','admin','Merge ID','Merge ID'),
						('order_id_is_merged','admin','Order is merged','ORDER_ID is merged'),
						('a_new_order_is_merge','admin','A new order is merged','A new order is merged'),
						('auth_key','admin','Auth Key','Auth Key'),
						('cluster','admin','Cluster','Cluster'),
						('secret','admin','Secret','Secret'),
						('key','admin','Key','Key'),
						('pusher','admin','Pusher','Pusher'),
						('serve','admin','Serve','Serve'),
						('served','admin','Served','Served'),
						('get_google_location','admin','Get Google locaction','Get Google locaction'),
						('hide_footer','admin','Hide Footer','Hide Footer'),
						('hide_banner','admin','Hide Banner','Hide Banner'),
						('payment_failed','admin','Payment Failed','Payment Failed'),
						('payment_success','admin','Payment Successfull','Payment Successfull'),
						('order_cancel_msg','admin','Order not confirm please try again!','Order not confirm please try again!'),
						('order_confirm_msg','admin','Order Confirm. Track you order using your phone number','Order Confirm. Track you order using your phone number'),
						('the_transaction_was_successfull','admin','The transaction was successful. Transaction details are given below:','The transaction was successful. Transaction details are given below:'),
						('thankyou_for_your_payment','admin','Thanks for your Payment!','Thanks for your Payment!'),
						('a_new_order_placed','admin','A New order placed','A New order placed'),
						('onesignal_user_id','admin','OneSignal User ID','OneSignal User ID'),
						('digital_payment','admin','Digital Payment','Digital Payment');");

				endif;

				$addColumn = $this->sql_command($addColumnQueries);

				if (isset($addColumn['st']) && $addColumn['st'] == 0) :
					$data = ["st" => 0, "msg" => $addColumn['msg'], 'version' => $new_version];
				else :

					$data = ['st' => 1, "msg" => 'Updated Successfully', 'version' => $new_version];
				endif;

				break;
			}

			if ($version < '3.1.3') {
				$new_version = '3.1.3';

				$addColumnQueries = [
					'menu_type' => [
						'language' => "VARCHAR(20) NOT NULL DEFAULT 'english'",
						'category_id' => "INT NOT NULL",
					],

					'items' => [
						'language' => "VARCHAR(20) NOT NULL DEFAULT 'english'",
						'item_id' => "INT NOT NULL",
					],

					'restaurant_list' => [
						'is_multi_lang' => "INT NOT NULL DEFAULT 0",
						'whatsapp_message_config' => "LONGTEXT NULL",
						'netseasy_config' => "LONGTEXT NULL",
						'is_netseasy' => "INT NOT NULL DEFAULT 0",
						'netseasy_status' => "INT NOT NULL DEFAULT 0",
					],

					'settings' => [
						'email_template_config' => "LONGTEXT NULL",
						'is_dynamic_mail' => "INT NOT NULL DEFAULT 1",
						'netseasy_config' => "LONGTEXT NULL",
						'is_netseasy' => "INT NOT NULL DEFAULT 0",
						'netseasy_status' => "INT NOT NULL DEFAULT 0",
					],
					'order_user_list' => [
						'merge_ids' => "LONGTEXT NULL",
					],

				];




				if (!$this->db->table_exists('item_category_list')) :
					$this->db->query("CREATE TABLE `item_category_list` (
						  `id` int(11) NOT NULL AUTO_INCREMENT,
						  `user_id` INT(11) NOT NULL,
						  `shop_id` INT NOT NULL, 
						  `status` INT NOT NULL DEFAULT '1', PRIMARY KEY (`id`)
						) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");
				endif;

				if (!$this->db->table_exists('item_list')) :
					$this->db->query("CREATE TABLE `item_list` (
						  `id` int(11) NOT NULL AUTO_INCREMENT,
						  `user_id` INT(11) NOT NULL,
						  `shop_id` INT NOT NULL, 
						  `status` INT NOT NULL DEFAULT '1', PRIMARY KEY (`id`)
						) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");
				endif;






				$keywords = ['checkout_key', 'select_items', 'instance_id', 'email_template'];
				$check_keywords = $this->check_keywords($keywords);
				if ($check_keywords == 0) :
					$this->db->query("INSERT INTO `language_data` (`keyword`, `type`, `details`, `english`) VALUES
					 	('merge','admin','Merge','Merge'),
						('order_time','admin','Order Time','Order Time'),
						('multi_merge','admin','Multipe Merge','Multipe Merge'),
						('grid_view','admin','Grid View','Grid View'),
						('list_view','admin','List View','List View'),
					 	('checkout_key','admin','Checkout Key','Checkout Key'),
						('merchant_id','admin','Merchant Id','Merchant Id'),
						('netseasy','admin','Netseasy','Netseasy'),
						('email_template','admin','Email Template','Email Template'),
						('select_items','admin','Select items','Select items'),
						('is_price','admin','Is Price','Is Price'),
						('enable_to_allow','admin','Enable to allow','Enable to allow'),
						('just_now','admin','Just Now','Just Now'),
						('token','admin','Token','Token'),
						('instance_id','admin','Instance ID','Instance ID'),
						('whatsapp_message_for_order_status','admin','WhatsApp Message for order status','WhatsApp Message for order status'),
						('whatsapp_share','admin','WhatsApp Share','WhatsApp Share'),
						('whatsapp_message','admin','Whatsapp Message','Whatsapp Message'),
						('phone_with_international_format','admin','Phone with international format e.g. 1408XXXXXXX','Phone with international format e.g. 1408XXXXXXX'),
						('enable_push_for_new_order','admin','Enable Push Notification for new order','Enable Push Notification for new order'),
						('rest_api_key','admin','Rest API key','Rest API key'),
						('welcome_message','admin','Welcome Message','Welcome Message'),
						('font_size','admin','Font Size','Font Size'),
						('pos_print_size','admin','Pos Print size','Pos Print size'),
						('show_live_order_btn','admin','Show Live Order Button','Show Live Order Button'),
						('set_as_default','admin','Set as Default','Set as Default'),
						('enable_live_order_button','admin','Enable Live order Button','Enable Live order Button'),
						('thank_you_for_your_payment','admin','Thank you for your Payment!','Thank you for your Payment!'),
						('mail_send_successfully','admin','Mail send successfully.','Mail send successfully.'),
						('payment_request_details','admin','Payement Request details are given below','Payement Request details are given below'),
						('order_not_confirmed_please_try_again','admin','Order not confirm please try again!','Order not confirm please try again!'),
						('show_live_order_button','admin','Show live order button','Show live order button'),
						('days_left','admin','Days Left','Days Left');");

				endif;



				$keywords = ['install_app', 'enable_multi_lang_category_items', 'account_expire_mail', 'send_payment_verified_email'];
				$check_keywords = $this->check_keywords($keywords);
				if ($check_keywords == 0) :
					$this->db->query("INSERT INTO `language_data` (`keyword`, `type`, `details`, `english`) VALUES
						('install_app','admin','Install App','Install App'),
						('enable_multi_lang_category_items','admin','Enable Multi-language categories & Items','Enable Multi-language categories & Items'),
						('account_expire_mail','admin','Account expire mail','Accounts expire mail'),
						('expire_reminder_mail','admin','Account expire reminder mail','Account expires reminder mail'),
						('send_payment_verified_email','admin','Payment verification mail','Payment verification mail'),
						('offline_payment_request_mail','admin','Offline payment request mail','Offline payment request mail'),
						('new_user_mail','admin','New user subscription mail','New user subscription mail'),
						('account_create_invoice','admin','Account create invoice','Account create an invoice'),
						('email_verification_mail','admin','Account verification mail','Account verification mail'),
						('resend_verify_mail','admin','Resend account verification mail','Resend account verification mail'),
						('contact_mail','admin','Contact Mail','Contact Mail'),
						('recovery_mail','admin','Password Recovery Mail','Password Recovery Mail'),
						('show_live_order_button','admin','Show live order button','Show live order button');");

				endif;


				$this->db->query('ALTER TABLE order_payment_info MODIFY order_id VARCHAR(200) NULL');



				$oldConfig = [
					'permission_list' => [
						'role' => "VARCHAR(20) NOT NULL DEFAULT 'user'",
					],

					'users' => [
						'staff_id' => "INT NOT NULL DEFAULT 1",
					],

					'packages' => [
						'duration' => "VARCHAR(20) NULL",
						'duration_period' => "VARCHAR(20) NULL",
					],

					'settings' => [
						'site_color' => "VARCHAR(50) NOT NULL DEFAULT '29c7ac'",
						'site_theme' => "INT(11) NOT NULL DEFAULT 1",
					],

					'user_settings' => [
						'site_theme' => "INT(11) NOT NULL DEFAULT 1",
					],

					'order_item_list' => [
						'is_merge' => "INT(11) NOT NULL DEFAULT 0",
					],

					'order_user_list' => [
						'is_order_merge' => "INT(11) NOT NULL DEFAULT 0",
					],

					'restaurant_list' => [
						'order_merge_config' => "LONGTEXT NULL",
						'is_cart' => "INT NOT NULL DEFAULT 1",
					],


				];

				$this->sql_command($oldConfig);
				$addColumn = $this->sql_command($addColumnQueries);

				if (isset($addColumn['st']) && $addColumn['st'] == 0) :
					$data = ["st" => 0, "msg" => $addColumn['msg'], 'version' => $new_version];
				else :

					$data = ['st' => 1, "msg" => 'Updated Successfully', 'version' => $new_version];
				endif;

				break;
			}


			if ($version < '3.1.4') {
				$new_version = '3.1.4';

				$addColumnQueries = [
					'users' => [
						'company_name' => "VARCHAR(50) NULL",
					],
					'settings' => [
						'invoice_config' => "LONGTEXT NULL",
					],


					'items' => [
						'uid' => "VARCHAR(50) NULL",
					],

					'restaurant_list' => [
						'is_image' => "int(2) NOT NULL DEFAULT 0",
					],

					'order_user_list' => [
						'is_rating_approved' => "INT(11) NOT NULL DEFAULT 0",
					],

					'payment_info' => [
						'is_self' => "INT(11) NOT NULL DEFAULT 0",
						'billing_address' => "LONGTEXT NULL",
						'expire_date' => "DATETIME NULL",
						'is_running' => "INT(11) NOT NULL DEFAULT 0",
					],


					'customer_list' => [
						'is_pos' => "INT(11) NOT NULL DEFAULT 0",
						'login_method' => "VARCHAR(50) NULL",
						'uid' => "VARCHAR(200) NULL",
						'photoUrl' => "VARCHAR(200) NULL",
						'login_time' => "DATETIME NULL",
						'images' => "VARCHAR(200) NULL",
						'thumb' => "VARCHAR(200) NULL",
						'country_id' => "INT(11) NOT NULL DEFAULT 0",
						'gmap_link' => "VARCHAR(200) NULL",
						'password' => "VARCHAR(50) NULL",
						'question' => "LONGTEXT NULL",
						'old_id' => "VARCHAR(20) NULL",
						'is_update' => "INT NOT NULL DEFAULT 0 ",
						'role' => "VARCHAR(30) NOT NULL DEFAULT 'customer' ",
					],



				];



				$keywords = ['company_details', 'category_id', 'shop_reviews', 'pickup', 'billing_address', 'payment_status'];
				$check_keywords = $this->check_keywords($keywords);
				if ($check_keywords == 0) :
					$this->db->query("INSERT INTO `language_data` (`keyword`, `type`, `details`, `english`) VALUES
						('company_details','admin','Company / Organization Details','Company / Organization Details'),
						('category_id','admin','Category ID','Category ID'),
						('by','admin','by','by'),
						('reject','admin','Reject','Reject'),
						('shop_reviews','admin','Shop Reviews','Shop Reviews'),
						('pay-cash','admin','Pay cash','Pay cash'),
						('room-service','admin','Room Service','Room Service'),
						('package-dine-in','admin','Package / Restaurant Dine-in','Package / Restaurant Dine-in'),
						('pay-in-cash','admin','Delivery / Digital Payment','Delivery / Digital Payment'),
						('pickup','admin','Pickup','Pickup'),
						('booking','admin','Booking','Booking'),
						('cash-on-delivery','admin','Delivery / Pay on receipt','Delivery / Pay on receipt'),
						('billing_address','admin','Billing Address','Billing Address'),
						('subscription_invoice','admin','Subscriptions invoice','Subscriptions invoice'),
						('tax_percent_for_subscription','admin','Tax Percent for subscription','Tax Percent for subscription'),
						('order_no','admin','Order NO','Order NO'),
						('expire_date','admin','Expire_date','Expire date'),
						('payment_status','admin','Payment Status','Payment Status'),
						('last_billing','admin','Last Billing','Last Billing'),
						('would_you_like_to_clear_the_cart','admin','would you like to clear the cart?','would you like to clear the cart?'),
						('your_cart_alreay_contains_items_from','admin','Your cart already contains items from','Your cart already contains items from'),
						('start_new_cart','admin','Start New cart?','Start New cart?'),
						('new_cart','admin','New cart','New cart'),
						('billing_cycle','admin','Billing Cycle','Billing Cycle');");

				endif;


				$this->db->query('ALTER TABLE order_payment_info MODIFY order_id VARCHAR(200) NULL');


				$addColumn = $this->sql_command($addColumnQueries);


				$this->get_customers();

				if (isset($addColumn['st']) && $addColumn['st'] == 0) :
					$data = ["st" => 0, "msg" => $addColumn['msg'], 'version' => $new_version];
				else :

					$data = ['st' => 1, "msg" => 'Updated Successfully', 'version' => $new_version];
				endif;

				break;
			}


			if ($version < '3.1.5') {
				$new_version = '3.1.5';

				$addColumnQueries = [
					'users' => [
						'company_name' => "VARCHAR(50) NULL",
					],

					'customer_list' => [
						'is_pos' => "INT(11) NOT NULL DEFAULT 0",
						'login_method' => "VARCHAR(50) NULL",
						'uid' => "VARCHAR(200) NULL",
						'photoUrl' => "VARCHAR(200) NULL",
						'login_time' => "DATETIME NULL",
						'images' => "VARCHAR(200) NULL",
						'thumb' => "VARCHAR(200) NULL",
						'country_id' => "INT(11) NOT NULL DEFAULT 0",
						'gmap_link' => "VARCHAR(200) NULL",
						'password' => "VARCHAR(50) NULL",
						'question' => "LONGTEXT NULL",
						'old_id' => "VARCHAR(20) NULL",
						'is_update' => "INT NOT NULL DEFAULT 0 ",
						'role' => "VARCHAR(30) NOT NULL DEFAULT 'customer' ",
					],



				];



				$keywords = ['would_you_like_to_clear_the_cart', 'start_new_cart', 'new_cart'];
				$check_keywords = $this->check_keywords($keywords);
				if ($check_keywords == 0) :
					$this->db->query("INSERT INTO `language_data` (`keyword`, `type`, `details`, `english`) VALUES
						('would_you_like_to_clear_the_cart','admin','would you like to clear the cart?','would you like to clear the cart?'),
						('your_cart_alreay_contains_items_from','admin','Your cart already contains items from','Your cart already contains items from'),
						('start_new_cart','admin','Start New cart?','Start New cart?'),
						('new_cart','admin','New cart','New cart');");

				endif;



				$addColumn = $this->sql_command($addColumnQueries);

				if (isset($addColumn['st']) && $addColumn['st'] == 0) :
					$data = ["st" => 0, "msg" => $addColumn['msg'], 'version' => $new_version];
				else :

					$data = ['st' => 1, "msg" => 'Updated Successfully', 'version' => $new_version];
				endif;

				break;
			}


			if ($version < '3.1.6') {
				$new_version = '3.1.6';

				$addColumnQueries = [

					'items' => [
						'uid' => "VARCHAR(50) NULL",
					],

					'item_list' => [
						'uid' => "VARCHAR(50) NULL",
					],

					'settings' => [
						'language_type' => "VARCHAR(10) NOT NULL DEFAULT 'system'",
						'glanguage' => 'TEXT NULL',
					],

					'order_user_list' => [
						'staff_id' => "INT(10) NULL",
						'staff_action' => "VARCHAR(10) NULL",
						'reject_reason' => "TEXT NULL",
						'action_by' => "VARCHAR(50)  NULL DEFAULT 'vendor'",
					],

					'restaurant_list' => [
						'is_login' => "INT(2) NOT NULL DEFAULT 1",
						'is_branding' => "INT(2) NOT NULL DEFAULT 1",
						'whatsapp_support' => "LONGTEXT NULL",
					],

					'allergens' => [
						'language' => "VARCHAR(20) NOT NULL DEFAULT 'english'",
					],

					'extra_libraries' => [
						'language' => "VARCHAR(20) NOT NULL DEFAULT 'english'",
					],

					'item_packages' => [
						'language' => "VARCHAR(20) NOT NULL DEFAULT 'english'",
						'uid' => "VARCHAR(20)  NULL",
						'package_id' => "INT NOT NULL DEFAULT 0",
					],

					'users_active_features' => [
						'language' => "VARCHAR(20) NOT NULL DEFAULT 'english'",
					],


					'users' => [
						'referal_code' => "VARCHAR(10) NULL",
					],

					'item_packages_list' => [
						'created_at' => "DATETIME NULL",
						'is_special' => "INT(2) NOT NULL DEFAULT 0",
						'language' => "VARCHAR(30)  NOT NULL DEFAULT 'english'",
					],

					'staff_list' => [
						'shop_id' => "INT(11) NOT NULL DEFAULT 0",
					],



				];

				if (!$this->db->table_exists('vendor_page_list')) :
					$this->db->query("CREATE TABLE `vendor_page_list` (
						  `id` int(11) NOT NULL AUTO_INCREMENT,
						  `user_id` INT(11) NOT NULL,
						  `shop_id` INT NOT NULL, 
						  `language` VARCHAR(20) NOT NULL DEFAULT 'english', 
						  `title` VARCHAR(200)  NULL, 
						  `slug` VARCHAR(200)  NULL, 
						  `details` LONGTEXT  NULL, 
						  `is_menu_bar` INT  NOT NULL DEFAULT 0, 
						  `status` INT NOT NULL DEFAULT '1', PRIMARY KEY (`id`)
						) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");
				endif;

				if (!$this->db->table_exists('item_packages_list')) :
					$this->db->query("CREATE TABLE `item_packages_list` (
						  `id` int(11) NOT NULL AUTO_INCREMENT,
						  `user_id` INT(11) NOT NULL,
						  `shop_id` INT NOT NULL, 
						  `created_at` DATETIME  NULL, 
						  `language` VARCHAR(30)  NOT NULL DEFAULT 'english', 
						  `is_special` INT(2) NOT NULL DEFAULT 0, PRIMARY KEY (`id`)
						) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");
				endif;


				if (!$this->db->table_exists('vendor_question_list')) :
					$this->db->query("CREATE TABLE `vendor_question_list` (
						  `id` int(11) NOT NULL AUTO_INCREMENT,
						  `user_id` INT(11) NOT NULL,
						  `shop_id` INT NOT NULL, 
						  `language` VARCHAR(80) NOT NULL DEFAULT 'english', 
						  `title` VARCHAR(200)  NULL, 
						  `access_for` VARCHAR(50)  NULL, 
						  `type` VARCHAR(50)  NULL, 
						  `status` INT NOT NULL DEFAULT '1', PRIMARY KEY (`id`)
						) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");
				endif;


				if (!$this->db->table_exists('admin_tutorial_list')) :
					$this->db->query("CREATE TABLE `admin_tutorial_list` (
						  `id` int(11) NOT NULL AUTO_INCREMENT,
						  `page_title` text  NULL,
						  `title` VARCHAR(200)  NULL, 
						  `uid` VARCHAR(50)  NULL, 
						  `details` LONGTEXT  NULL, 
						  `status` INT NOT NULL DEFAULT '1', PRIMARY KEY (`id`)
						) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");
				endif;

				if (!$this->db->table_exists('admin_config')) :
					$this->db->query("CREATE TABLE `admin_config` (
						  `id` int(11) NOT NULL AUTO_INCREMENT,
						  `key` VARCHAR(200)  NULL, 
						  `value` LONGTEXT  NULL, 
						  `status` INT NOT NULL DEFAULT '1', PRIMARY KEY (`id`)
						) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");
				endif;


				$check_slug = $this->check_slug('admin-delete', 'permission_list');
				if ($check_slug == 0) :
					$this->db->query("INSERT INTO permission_list(title,slug,status,role) VALUES 
						('Delete','admin-delete','1','admin_staff')");
				endif;

				$check_slug = $this->check_slug('review-control', 'permission_list');
				if ($check_slug == 0) :
					$this->db->query("INSERT INTO permission_list(title,slug,status,role) VALUES 
						('Reviews Control','review-control','1','admin_staff')");
				endif;



				$keywords = ['template', 'support_until', 'page_title', 'order_reject'];
				$check_keywords = $this->check_keywords($keywords);
				if ($check_keywords == 0) :
					$this->db->query("INSERT INTO `language_data` (`keyword`, `type`, `details`, `english`) VALUES
						('template','admin','Template','Template'),
						('support_until','admin','Support Until','Support Until'),
						('whatsapp_support','admin','WhatsApp support','WhatsApp support'),
						('dboy','admin','Delivery Guys','Delivery Guys'),
						('page_title','admin','Page Titles','Page Titles'),
						('tutorials','admin','Tutorials','Tutorials'),
						('branding','admin','Branding','Branding'),
						('created_by','admin','Created by','Created by'),
						('delivery_guy','admin','Delivery Guys','Delivery Guys'),
						('access_for','admin','Access for','Access for'),
						('order_reject','admin','Order Reject','Order Reject'),
						('add_reason','admin','Add Reason','Add Reason'),
						('reason_list','admin','Reason List','Reason List'),
						('reject_reasons','admin','Reject Reasons','Reject Reasons'),
						('question_list','admin','Question List','Question List'),
						('show_in_menu_bar','admin','Show in Menu bar','Show in Menu bar'),
						('login_btn_in_menu','admin','Login button in menu bar','Login button in menu bar'),
						('variant_name','admin','Variant Name','Variant Name'),
						('select_category','admin','Select Category','Select Category'),
						('add_variants','admin','Add Variants','Add Variants'),
						('save_and_new_order','admin','Save and new order','Save and new order'),
						('all_categories','admin','All Categories','All Categories'),
						('draft','admin','Draft','Draft'),
						('todays_orders','admin','Todays orders','Todays orders'),
						('system_language','admin','System Language','System Language'),
						('google_translator','admin','Google translator','Google translator'),
						('site_language','admin','Site Language','Site Language'),
						('default_language','admin','Default Language','Default Language');");

				endif;

				$this->db->query('ALTER TABLE restaurant_list MODIFY about LONGTEXT NULL');
				$this->db->query('ALTER TABLE items MODIFY price LONGTEXT NULL');


				$addColumn = $this->sql_command($addColumnQueries);

				//transfer package & specialitis
				@$this->transfer_pacakge();




				if (isset($addColumn['st']) && $addColumn['st'] == 0) :
					$data = ["st" => 0, "msg" => $addColumn['msg'], 'version' => $new_version];
				else :

					$data = ['st' => 1, "msg" => 'Updated Successfully', 'version' => $new_version];
				endif;

				break;
			}


			if ($version < '3.1.7') {
				$new_version = '3.1.7';

				$addColumnQueries = [

					'restaurant_list' => [
						'referal_code' => "VARCHAR(10) NULL",
						'service_charge_config' => "LONGTEXT NULL",
						'tax_number' => "VARCHAR(50) NULL",
						'delivery_area_style' => "INT(2) NOT NULL DEFAULT 1",
					],

					'payment_info' => [
						'package_price' => "VARCHAR(10) NULL",
						'referal_code' => "VARCHAR(10) NULL",
						'ref_id' => "INT NULL",
						'is_payment' => "INT NOT NULL DEFAULT 1",
					],

					'user_settings' => [
						'vendor_affiliate_settings' => "LONGTEXT NULL",
					],

					'order_item_list' => [
						'status' => "INT NOT NULL DEFAULT 1",
					],


					'features' => [
						'is_header' => "INT NOT NULL DEFAULT 1",
					],


					'items' => [
						'is_pos_only' => "INT NOT NULL DEFAULT 0",
					],

					'menu_type' => [
						'is_pos_only' => "INT NOT NULL DEFAULT 0",
					],

					'order_user_list' => [
						'is_item_tax' => "INT NOT NULL DEFAULT 0",
						'is_confirm' => "INT NOT NULL DEFAULT 1",
						'confirm_at' => "DATETIME NULL",
						'service_charge' => "VARCHAR(30) NOT NULL DEFAULT 0",
					],





				];


				if (!$this->db->table_exists('admin_config')) :
					$this->db->query("CREATE TABLE `admin_config` (
						  `id` int(11) NOT NULL AUTO_INCREMENT,
						  `key` VARCHAR(200)  NULL, 
						  `value` LONGTEXT  NULL, 
						  `status` INT NOT NULL DEFAULT '1', PRIMARY KEY (`id`)
						) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");
				endif;

				if (!$this->db->table_exists('vendor_affiliate_list')) :
					$this->db->query("CREATE TABLE `vendor_affiliate_list` (
						  `id` int(11) NOT NULL AUTO_INCREMENT,
						  `uid` VARCHAR(50) NOT NULL,
						  `subscriber_id` INT(11) NOT NULL,
						  `refer_from_id` INT NOT NULL, 
						  `amount` VARCHAR(20)  NULL, 
						  `package_id` INT NOT  NULL DEFAULT 0,
						  `package_price` VARCHAR(20)  NULL, 
						  `commision_amount` VARCHAR(20)  NULL, 
						  `referal_code` VARCHAR(20)  NULL, 
						  `ref_type` VARCHAR(80)  NULL, 
						  `commision_rate` VARCHAR(20)  NULL, 
						  `commision_type` VARCHAR(20)  NULL, 
						  `subscriber_commision_rate` VARCHAR(20)  NULL, 
						  `subscriber_commision_type` VARCHAR(20)  NULL, 
						  `created_at` datetime null, 
						  `request_date` datetime null, 
						  `status` INT NOT NULL DEFAULT '1',
						  `is_request` INT NOT NULL DEFAULT 0,
						  `is_payment` INT NOT NULL DEFAULT 0,
						   PRIMARY KEY (`id`)
						) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");
				endif;


				if (!$this->db->table_exists('affiliate_payout_list')) :
					$this->db->query("CREATE TABLE `affiliate_payout_list` (
						`id` int(11) NOT NULL AUTO_INCREMENT,
						`uid` VARCHAR(50) NOT NULL,
						`request_id` INT(11) NULL,
						`total_referel` INT(11) NULL,
						`balance` VARCHAR(20)  NULL, 
						`ids` LONGTEXT  NULL, 
						`referel_type` VARCHAR(50) NULL, 
						`request_date` datetime null, 
						`complete_date` datetime null, 
						`payout_month` VARCHAR(50) null, 
						`status` INT NOT NULL DEFAULT 0,
						`is_payment` INT NOT NULL DEFAULT 0,
						PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");
				endif;

				if (!$this->db->table_exists('admin_config')) :
					$this->db->query("CREATE TABLE `admin_config` (
						  `id` int(11) NOT NULL AUTO_INCREMENT,
						  `key` VARCHAR(200)  NULL, 
						  `value` LONGTEXT  NULL, 
						  `status` INT NOT NULL DEFAULT '1', PRIMARY KEY (`id`)
						) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");
				endif;


				$keywords = ['excel', 'pdf', 'preparing', 'xreport'];
				$check_keywords = $this->check_keywords($keywords);
				if ($check_keywords == 0) :
					$this->db->query("INSERT INTO `language_data` (`keyword`, `type`, `details`, `english`) VALUES
					('service_charge','admin','Service Charge','Service Charge'),
					('sales_graph','admin','Sales Graph','Sales Graph'),
					('mark_as_hold','admin','Mark as hold','Mark as hold'),
					('completed_payout','admin','Completed Payout','Completed Payout'),
					('referal_code','admin','Referral Code','Referral Code'),
					('transactions','admin','Transactions','Transactions'),
					('flat','admin','Flat','Flat'),
					('minimum_payout','admin','Minimum Payout','Minimum Payout'),
					('subscriber_commission_rate','admin','Subscriber Commission Rate','Subscriber Commission Rate'),
					('commission_rate','admin','Commission Rate','Commission Rate'),
					('enable_affiliate','admin','Enable Affiliate','Enable Affiliate'),
					('affiliate_from','admin','Affiliate From','Affiliate From'),
					('package_price','admin','Package Price','Package Price'),
					('in_progress','admin','In-progress','In-progress'),
					('vendor_name','admin','Vendor Name','Vendor Name'),
					('dropdown','admin','Dropdown','Dropdown'),
					('hide_product_image','admin','Hide Product Image','Hide Product Image'),
					('login_btn','admin','Login Button','Login Button'),
					('payment_details','admin','Payment Details','Payment Details'),
					('payment_email','admin','Payment Email','Payment Email'),
					('payment_method','admin','Payment Method','Payment Method'),
					('total_income','admin','Total Income','Total Income'),
					('created_at','admin','Created at','Created at'),
					('commision_price','admin','Commission Price','Commission Price'),
					('subscriber','admin','Subscriber','Subscriber'),
					('pending_payment','admin','Pending Payment','Pending Payment'),
					('payout_list','admin','Payout List','Payout List'),
					('complete_date','admin','Complete Date','Complete Date'),
					('payout','admin','Payout','Payout'),
					('affiliate_list','admin','Affiliate List','Affiliate List'),
					('payout_request','admin','Payout Request','Payout Request'),
					('referral_guidelines','admin','Referral Guidelines','Referral Guidelines'),
					('get_commision_details','admin','Earn commission for their first subscription plan payments!','Earn commission for their first subscription plan payments!'),
					('get_comminsions','admin','Get Commissions','Get Commissions'),
					('affiliate_registration_details','admin','Let them register using your referral link.','Let them register using your referral link.'),
					('invitation_details','admin','Send your referral link to your friends and tell them how cool is this!','Send your referral link to your friends and tell them how cool this is!'),
					('send_invitation','admin','Send Invitation','Send Invitation'),
					('referal_url','admin','Referal URL','Referal URL'),
					('total_withdraw','admin','Total Withdraw','Total Withdraw'),
					('blance','admin','Balance','Balance'),
					('total_referal','admin','Total Referal','Total Referal'),
					('affiliate','admin','Affiliate','Affiliate'),
					('only_for_pos','admin','Only for pos','Only for pos'),
					('orientation','admin','Orientation','Orientation'),
					('deleted_successfully','admin','Deleted Successfully','Deleted Successfully'),
					('upload_by_cropper','admin','Upload by cropper','Upload by cropper'),
					('crop','admin','Crop','Crop'),
					('walk-in-customer','admin','Walk in customer','Walk-in customer'),
					('mail_change_details','admin','Send mail with changes','Send a mail with the changes'),
					('reservation_placed_successfully','admin','Reservation Placed successfully','Reservation Placed successfully'),
					('enable_reservation_mail','admin','Enable Reservation Mail','Enable Reservation Mail'),
					('reservation_mail','admin','Reservation Mail','Reservation Mail'),
					('orders_mail','admin','Orders Mail','Orders Mail'),
					('orders_email_settings','admin','Orders email settings','Orders email settings'),
					('please_wait_its_working','admin','Please Wait, Its working................','Please Wait, Its working................'),
					('order_placed_successfully','admin','Order placed successfully','Order placed successfully'),
					('a_new_order_is_placed','admin','A new order is placed','A new order is placed'),
					('by_kds','admin','From KDS','From KDS'),
					('by_staff','admin','By staff','by staff'),
					('email_already_exists','admin','The email is already Exists','The email already Exists'),
					('this_job_already_handle_by_someone','admin','Sorry this job already handle by someone','Sorry, this job is already handled by someone'),
					('order_amount','admin','Ordered amount','Ordered amount'),
					('pos_font_size','admin','Pos invoice font size','pos invoice font size'),
					('add_new_order','admin','Create New Order','Create a new order'),
					('mark_as_served','admin','Mark as served','Mark as served'),
					('week','admin','Week','Week'),
					('shipping_address_not_found','admin','Delivery area/shipping address not found','Delivery area/shipping address not found'),
					('sign_up_text','admin','Signup to discover your shop','Signup to discover your shop'),
					('reject_reasons','admin','Reject Reasons','Reject Reasons'),
					('question_list','admin','Question List','Question List'),
					('new_cart','admin','New Cart','New Cart'),
					('draft','admin','Draft','Draft'),
					('excel','admin','EXCEL','EXCEL'),
					('pdf','admin','PDF','PDF'),
					('csv','admin','CSV','CSV'),
					('xreport','admin','Xreport','Xreport'),
					('summaryreport','admin','Summary Report','Summary Report'),
					('expensereport','admin','Expense Report','Expense Report'),
					('category','admin','Category','Category'),
					('ready','admin','Ready','Ready'),
					('preparing','admin','Preparing','Preparing'),
					('value','admin','Value','Value'),
					('new_code','admin','New Code','New Code'),
					('referal_discount','admin','Referral Discount','Referral Discount'),
					('language','admin','Language','Language'),
					('start_new_cart','admin','Start a new cart?','Start a new cart?');");

				endif;

				$this->db->query('ALTER TABLE restaurant_list MODIFY about LONGTEXT NULL');
				$this->db->query('ALTER TABLE items MODIFY price LONGTEXT NULL');



				$addColumn = $this->sql_command($addColumnQueries);

				//change price to package price
				$this->change_price();

				// add affiliate as a feature for packages
				$this->add_features('affiliate');


				// add index number in the column
				$this->indexNumbers();

				$this->activeFeatures();


				if (isset($addColumn['st']) && $addColumn['st'] == 0) :
					$data = ["st" => 0, "msg" => $addColumn['msg'], 'version' => $new_version];
				else :

					$data = ['st' => 1, "msg" => 'Updated Successfully', 'version' => $new_version];
				endif;

				break;
			}



			if ($version < '3.1.8') {
				$new_version = '3.1.8';


				$keywords = ['excel', 'pdf', 'preparing', 'xreport'];
				$check_keywords = $this->check_keywords($keywords);
				if ($check_keywords == 0) :
					$this->db->query("INSERT INTO `language_data` (`keyword`, `type`, `details`, `english`) VALUES
					('service_charge','admin','Service Charge','Service Charge'),
					('sales_graph','admin','Sales Graph','Sales Graph'),
					('mark_as_hold','admin','Mark as hold','Mark as hold'),
					('completed_payout','admin','Completed Payout','Completed Payout'),
					('referal_code','admin','Referral Code','Referral Code'),
					('transactions','admin','Transactions','Transactions'),
					('flat','admin','Flat','Flat'),
					('minimum_payout','admin','Minimum Payout','Minimum Payout'),
					('subscriber_commission_rate','admin','Subscriber Commission Rate','Subscriber Commission Rate'),
					('commission_rate','admin','Commission Rate','Commission Rate'),
					('enable_affiliate','admin','Enable Affiliate','Enable Affiliate'),
					('affiliate_from','admin','Affiliate From','Affiliate From'),
					('package_price','admin','Package Price','Package Price'),
					('in_progress','admin','In-progress','In-progress'),
					('vendor_name','admin','Vendor Name','Vendor Name'),
					('dropdown','admin','Dropdown','Dropdown'),
					('hide_product_image','admin','Hide Product Image','Hide Product Image'),
					('login_btn','admin','Login Button','Login Button'),
					('payment_details','admin','Payment Details','Payment Details'),
					('payment_email','admin','Payment Email','Payment Email'),
					('payment_method','admin','Payment Method','Payment Method'),
					('total_income','admin','Total Income','Total Income'),
					('created_at','admin','Created at','Created at'),
					('commision_price','admin','Commission Price','Commission Price'),
					('subscriber','admin','Subscriber','Subscriber'),
					('pending_payment','admin','Pending Payment','Pending Payment'),
					('payout_list','admin','Payout List','Payout List'),
					('complete_date','admin','Complete Date','Complete Date'),
					('payout','admin','Payout','Payout'),
					('affiliate_list','admin','Affiliate List','Affiliate List'),
					('payout_request','admin','Payout Request','Payout Request'),
					('referral_guidelines','admin','Referral Guidelines','Referral Guidelines'),
					('get_commision_details','admin','Earn commission for their first subscription plan payments!','Earn commission for their first subscription plan payments!'),
					('get_comminsions','admin','Get Commissions','Get Commissions'),
					('affiliate_registration_details','admin','Let them register using your referral link.','Let them register using your referral link.'),
					('invitation_details','admin','Send your referral link to your friends and tell them how cool is this!','Send your referral link to your friends and tell them how cool this is!'),
					('send_invitation','admin','Send Invitation','Send Invitation'),
					('referal_url','admin','Referal URL','Referal URL'),
					('total_withdraw','admin','Total Withdraw','Total Withdraw'),
					('blance','admin','Balance','Balance'),
					('total_referal','admin','Total Referal','Total Referal'),
					('affiliate','admin','Affiliate','Affiliate'),
					('only_for_pos','admin','Only for pos','Only for pos'),
					('orientation','admin','Orientation','Orientation'),
					('deleted_successfully','admin','Deleted Successfully','Deleted Successfully'),
					('upload_by_cropper','admin','Upload by cropper','Upload by cropper'),
					('crop','admin','Crop','Crop'),
					('walk-in-customer','admin','Walk in customer','Walk-in customer'),
					('mail_change_details','admin','Send mail with changes','Send a mail with the changes'),
					('reservation_placed_successfully','admin','Reservation Placed successfully','Reservation Placed successfully'),
					('enable_reservation_mail','admin','Enable Reservation Mail','Enable Reservation Mail'),
					('reservation_mail','admin','Reservation Mail','Reservation Mail'),
					('orders_mail','admin','Orders Mail','Orders Mail'),
					('orders_email_settings','admin','Orders email settings','Orders email settings'),
					('please_wait_its_working','admin','Please Wait, Its working................','Please Wait, Its working................'),
					('order_placed_successfully','admin','Order placed successfully','Order placed successfully'),
					('a_new_order_is_placed','admin','A new order is placed','A new order is placed'),
					('by_kds','admin','From KDS','From KDS'),
					('by_staff','admin','By staff','by staff'),
					('email_already_exists','admin','The email is already Exists','The email already Exists'),
					('this_job_already_handle_by_someone','admin','Sorry this job already handle by someone','Sorry, this job is already handled by someone'),
					('order_amount','admin','Ordered amount','Ordered amount'),
					('pos_font_size','admin','Pos invoice font size','pos invoice font size'),
					('add_new_order','admin','Create New Order','Create a new order'),
					('mark_as_served','admin','Mark as served','Mark as served'),
					('week','admin','Week','Week'),
					('shipping_address_not_found','admin','Delivery area/shipping address not found','Delivery area/shipping address not found'),
					('sign_up_text','admin','Signup to discover your shop','Signup to discover your shop'),
					('reject_reasons','admin','Reject Reasons','Reject Reasons'),
					('question_list','admin','Question List','Question List'),
					('new_cart','admin','New Cart','New Cart'),
					('draft','admin','Draft','Draft'),
					('excel','admin','EXCEL','EXCEL'),
					('pdf','admin','PDF','PDF'),
					('csv','admin','CSV','CSV'),
					('xreport','admin','Xreport','Xreport'),
					('summaryreport','admin','Summary Report','Summary Report'),
					('expensereport','admin','Expense Report','Expense Report'),
					('category','admin','Category','Category'),
					('ready','admin','Ready','Ready'),
					('preparing','admin','Preparing','Preparing'),
					('value','admin','Value','Value'),
					('new_code','admin','New Code','New Code'),
					('referal_discount','admin','Referral Discount','Referral Discount'),
					('language','admin','Language','Language'),
					('start_new_cart','admin','Start a new cart?','Start a new cart?');");

				endif;


				if (isset($addColumn['st']) && $addColumn['st'] == 0) :
					$data = ["st" => 0, "msg" => $addColumn['msg'], 'version' => $new_version];
				else :

					$data = ['st' => 1, "msg" => 'Updated Successfully', 'version' => $new_version];
				endif;

				break;
			}

			if ($version < '3.1.9') {
				$new_version = '3.1.9';

				$addColumnQueries = [
					'reservation_date' => [
						'is_multiple' => "INT(2) NOT NULL DEFAULT 0",
						'time_config' => "LONGTEXT NULL",
						'is_default' => "INT(2) NOT NULL DEFAULT 0",
					],

					'staff_list' => [
						'city_id' => "INT(5) NOT NULL DEFAULT 0",
						'shop_id' => "INT(5) NOT NULL DEFAULT 0",
						'is_default' => "INT(2) NOT NULL DEFAULT 0",
					],

					'restaurant_list' => [
						'city_id' => "INT NOT NULL DEFAULT 0",
						'moyasar_config' => "LONGTEXT NULL",
						'is_moyasar' => "INT NOT NULL DEFAULT 0",
						'moyasar_status' => "INT NOT NULL DEFAULT 0",
						'is_default' => "INT(2) NOT NULL DEFAULT 0",
					],

					'settings' => [
						'moyasar_config' => "LONGTEXT NULL",
						'is_moyasar' => "INT NOT NULL DEFAULT 0",
						'moyasar_status' => "INT NOT NULL DEFAULT 0",
						'is_default' => "INT(2) NOT NULL DEFAULT 0",
					],

					'call_waiter_list' => [
						'staff_id' => "INT NOT NULL DEFAULT 0",
						'is_default' => "INT(2) NOT NULL DEFAULT 0",
					],

					'customer_list' => [
						'dial_code' => "VARCHAR(20) NULL",
						'is_default' => "INT(2) NOT NULL DEFAULT 0",
					],

					'coupon_list' => [
						'is_menu' => "INT(2) NOT NULL DEFAULT 1",
						'is_default' => "INT(2) NOT NULL DEFAULT 0",
					],

					'item_extras' => [
						'extra_title_id' => "INT(2) NOT NULL DEFAULT 0",
						'is_default' => "INT(2) NOT NULL DEFAULT 0",
					],

					'order_item_list' => [
						'extra_qty' => "LONGTEXT NULL",
						'is_default' => "INT(2) NOT NULL DEFAULT 0",
					],

					'item_packages' => [
						'tax_fee' => "VARCHAR(50) NULL DEFAULT 0",
						'tax_status' => "VARCHAR(2) NULL DEFAULT '+'",
						'is_default' => "INT(2) NOT NULL DEFAULT 0",
					],

					'items' => [
						'is_default' => "INT(2) NOT NULL DEFAULT 0",
					],

					'item_list' => [
						'is_default' => "INT(2) NOT NULL DEFAULT 0",
					],

					'menu_type' => [
						'is_default' => "INT(2) NOT NULL DEFAULT 0",
						'shop_id' => "INT NOT NULL DEFAULT 0",
					],
					'item_category_list' => [
						'is_default' => "INT(2) NOT NULL DEFAULT 0",
					],
					'extra_title_list' => [
						'is_default' => "INT(2) NOT NULL DEFAULT 0",
					],

					'item_extras' => [
						'is_default' => "INT(2) NOT NULL DEFAULT 0",
						'extra_title_id' => "INT(11) NOT NULL DEFAULT 0",
					],


				];




				if (!$this->db->table_exists('staff_order_activity_logs')) :
					$this->db->query("CREATE TABLE staff_order_activity_logs (
						`id` INT PRIMARY KEY AUTO_INCREMENT,
						`order_id` INT,
						`staff_id` INT,
						`shop_id` INT NULL,
						`staff_role` VARCHAR(50) NOT NULL DEFAULT 'staff',
						`action_type` VARCHAR(50) NULL,
						`is_pos` INT NOT NULL DEFAULT 0,
						`created_at` datetime NULL, 
						FOREIGN KEY (order_id) REFERENCES order_user_list(id),
						FOREIGN KEY (staff_id) REFERENCES staff_list(id)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");
				endif;

				if (!$this->db->table_exists('restaurant_city_list')) :
					$this->db->query("CREATE TABLE restaurant_city_list (
						`id` INT PRIMARY KEY AUTO_INCREMENT,
						`city_name` VARCHAR(100) NULL,
						`zip_code` VARCHAR(50) NULL,
						`created_at` datetime NULL
					) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");
				endif;


				if (!$this->db->table_exists('extra_title_list')) :
					$this->db->query("CREATE TABLE extra_title_list (
						`id` INT PRIMARY KEY AUTO_INCREMENT,
						`title` VARCHAR(250) NULL,
						`shop_id` INT(11)  NOT NULL DEFAULT 0,
						`user_id` INT(11)  NOT NULL DEFAULT 0,
						`item_id` INT(11) NOT NULL,
						`is_required` INT(2) NOT NULL DEFAULT 0,
						`is_single_select` INT(2) NOT NULL DEFAULT 1,
						`orders` INT(11)  NULL DEFAULT 0,
						`is_radio_btn` INT(11)  NULL DEFAULT 1,
						`select_limit` INT(11) NOT  NULL DEFAULT 1,
						`select_max_limit` INT(11) NOT  NULL DEFAULT 1,
						`language` VARCHAR(20) NOT NULL DEFAULT 'english',
						`created_at` datetime NULL
					) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");
				endif;


				if (!$this->db->table_exists('vendor_slider_list')) :
					$this->db->query("CREATE TABLE vendor_slider_list (
						`id` INT PRIMARY KEY AUTO_INCREMENT,
						`title` VARCHAR(250) NULL,
						`shop_id` INT(11)  NOT NULL,
						`details` LONGTEXT NOT NULL,
						`thumb` VARCHAR(250) NULL,
						`images` VARCHAR(250) NULL,
						`status` TINYINT NOT NULL DEFAULT 1,
						`language` VARCHAR(20) NOT NULL DEFAULT 'english',
						`created_at` datetime NULL
					) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");
				endif;


				if (!$this->db->table_exists('vendor_menu_list')) :
					$this->db->query("CREATE TABLE vendor_menu_list (
						`id` INT PRIMARY KEY AUTO_INCREMENT,
						`user_id` INT(11) NOT NULL DEFAULT 0,
						`shop_id` INT(11) NOT NULL DEFAULT 0,
						`title` VARCHAR(100) NULL,
						`url` VARCHAR(255) NULL,
						`is_dropdown` INT(2) NOT NULL DEFAULT 0,
						`dropdown_id` INT(11) NOT NULL DEFAULT 0,
						`is_quick_link` INT(2) NOT NULL DEFAULT 0,
						`is_extranal_url` INT(2) NOT NULL DEFAULT 0,
						`status` INT(2) NOT NULL DEFAULT 1,
						`language` VARCHAR(50) NOT NULL DEFAULT 'english',
						`created_at` datetime NULL
					) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");
				endif;



				if (!$this->db->table_exists('data_queue')) :
					$this->db->query("CREATE TABLE IF NOT EXISTS `data_queue` (
						`id` INT AUTO_INCREMENT PRIMARY KEY,
						`old_user_id` INT NOT NULL DEFAULT 0,
						`new_user_id` INT NOT NULL DEFAULT 0,
						`old_shop_id` INT NOT NULL DEFAULT 0,
						`new_shop_id` INT NOT NULL DEFAULT 0,
						`action_id` INT NOT NULL DEFAULT 0,
						`action_role` VARCHAR(255) NOT NULL DEFAULT 'shop',
						`table_name` VARCHAR(255),
						`type` VARCHAR(50),
						`status` ENUM('pending', 'completed') DEFAULT 'pending',
						`created_at` DATETIME) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");
				endif;


				$check_slug = $this->check_slug('moyasar', 'payment_method_list');
				if ($check_slug == 0) :
					$this->db->query('INSERT INTO payment_method_list(name,slug,active_slug,status_slug,status) VALUES ("Moyasar","moyasar","moyasar_status","is_moyasar",1)');
				endif;





				$keywords = ['select_max_limit', 'add_breaks', 'select_minimum', 'add_new_extra_title'];
				$check_keywords = $this->check_keywords($keywords);
				if ($check_keywords == 0) :
					$this->db->query("INSERT INTO `language_data` (`keyword`, `type`, `details`, `english`) VALUES
					('payment_with_cash','admin','Cash Payment','Cash Payment'),
					('total_sales','admin','Total sales','Total sales'),
					('moyasar','admin','moyasar','Moyasar'),
					('is_dropdown','admin','Is Dropdown','Is Dropdown'),
					('menu_list','admin','Menu List','Menu List'),
					('select_max_limit','admin','Maximum choose/select limit','Maximum choose/select limit'),
					('variant_name_details','admin','Variant Name, i.e size, color etc','Variant Name, i.e size, color etc'),
					('variant_description','admin','Variant Options separated by|pipe symbol, i.e Black|Blue|Red','Variant Options separated by|pipe symbol, i.e Black|Blue|Red'),
					('slider','admin','Slider','Slider'),
					('show_customer_comments_invoice','admin','Show customer comments invoice','Show customer comments invoice'),
					('required_fields','admin','Please fill-up the required fields','Please fill-up the required fields'),
					('only_for_package','admin','Only for package','Only for package'),
					('addons','admin','addons','addons'),
					('list','admin','List','List'),
					('select_minimum','admin','Select minumum','select minimum'),
					('optional','admin','Optional','Optional'),
					('at_least','admin','At least','at least'),
					('options','admin','Option(s)','option(s)'),
					('please_select_at_least','admin','Please select at least','Please select at least'),
					('select_limit','admin','Select Limit','Select Limit'),
					('required','admin','Required','Required'),
					('is_required','admin','Is required','Is required'),
					('multiple_select','admin','Multiple select','Multiple select'),
					('single_select','admin','Single select','single select'),
					('add_new_extra_title','admin','Add new extra / addons title','Add new extra / addons title'),
					('show_in_landing_page','admin','Show in the landing page','Show in the landing page'),
					('confirmed','admin','Confirmed','Confirmed'),
					('custom','admin','Custom','Custom'),
					('restaurant','admin','Restaurant','vendor'),
					('payment_link','admin','Payment Link','Payment Link'),
					('slogan','admin','Slogan','Slogan'),
					('enable_push_for_call_waiter','admin','Enable push for call waiter','Enable push for call waiter'),
					('variant_options','admin','Variant options','Variant options'),
					('zip_code','admin','Zip code','Zip code'),
					('city_name','admin','City Name','City Name'),
					('cities','admin','Cities','Cities'),
					('create_order','admin','Order Create','Order Create'),
					('moyasar','admin','Moyasar','Moyasar'),
					('you_have_some_test_data_like_items','admin','You have some test/default data like items, categories','You have some test/default data like items, categories'),
					('do_you_want_to_remove_them','admin','Do you want to remove them?','Do you want to remove them?'),
					('add_breaks','admin','Add Breaks','Add Breaks');");

				endif;

				$this->db->query('ALTER TABLE restaurant_list MODIFY about LONGTEXT NULL');
				$this->db->query('ALTER TABLE items MODIFY price LONGTEXT NULL');



				$addColumn = $this->sql_command($addColumnQueries);

				// move staff activities from order_user_list 3.1.9
				$this->get_staff_from_order();

				$this->indexNumbers();

				$this->add_user_permissions();

				if (isset($addColumn['st']) && $addColumn['st'] == 0) :
					$data = ["st" => 0, "msg" => $addColumn['msg'], 'version' => $new_version];
				else :

					$data = ['st' => 1, "msg" => 'Updated Successfully', 'version' => $new_version];
				endif;

				break;
			}


			if ($version < '3.2.0') {
				$new_version = '3.2.0';

				$addColumnQueries = [
					'extra_title_list' => [
						'max_qty' => "INT(11) NOT NULL DEFAULT 0",
					],


					'settings' => [
						'tax_system' => "VARCHAR(20) NOT NULL DEFAULT 'percentage'",
					],

				];


				$keywords = ['max_qty','tax_system'];
				$check_keywords = $this->check_keywords($keywords);
				if ($check_keywords == 0) :
					$this->db->query("INSERT INTO `language_data` (`keyword`, `type`, `details`, `english`) VALUES
						('including_tax','admin','Tax including Formula (EU)','Tax including Formula (EU)'),
						('tax_system','admin','Tax System','Tax System'),
						('default','admin','default','Default'),
						('max_qty','admin','Maximum quantity selecting limit','Maximum quantity selecting limit');");

				endif;


				$addColumn = $this->sql_command($addColumnQueries);


				if (isset($addColumn['st']) && $addColumn['st'] == 0) :
					$data = ["st" => 0, "msg" => $addColumn['msg'], 'version' => $new_version];
				else :

					$data = ['st' => 1, "msg" => 'Updated Successfully', 'version' => $new_version];
				endif;
			}



			/*----------------------------------------------
		  				VERSION END 
		----------------------------------------------*/
		} while ($version == NEW_VERSION);


		return $data;
	} //install_version


	/*----------------------------------------------
	  		ADD Fields SQL Comments
	----------------------------------------------*/
	public function sql_command($addColumnQueries = [])
	{
		if (!empty($addColumnQueries)) :
			try {
				foreach ($addColumnQueries as $tableName => $queryValue) {
					foreach ($queryValue as $fieldName => $attribute) {
						if ($this->checkExistFields($tableName, $fieldName) == 0) {
							$this->dbforge->add_column($tableName, $fieldName . ' ' . $attribute);;
						}
					}
				}
			} catch (Exception $e) {
				return ['st' => 0, 'msg' => $e->getMessage()];
			}


		endif;
	}


	public function add_user_permissions()
	{
		$permissionList = [
			'kds' => [
				'title' => 'KDS',
				'slug' => 'kds',
				'role' => 'user',
			],

			'reports' => [
				'title' => 'Reports',
				'slug' => 'reports',
				'role' => 'user',
			],

			'menu' => [
				'title' => 'Menu',
				'slug' => 'menu',
				'role' => 'user',
			],

			'affiliate' => [
				'title' => 'Affiliate',
				'slug' => 'affiliate',
				'role' => 'user',
			],


			'coupon' => [
				'title' => 'Coupon List',
				'slug' => 'coupon',
				'role' => 'user',
			],

			'customer' => [
				'title' => 'Customer List',
				'slug' => 'customer',
				'role' => 'user',
			],

			'pages' => [
				'title' => 'Page List',
				'slug' => 'pages',
				'role' => 'user',
			],
		];

		foreach ($permissionList as $key => $value) :
			$check_slug = $this->check_slug($key, 'permission_list');
			if ($check_slug == 0) :
				$data = [
					'title' => $value['title'],
					'slug' => $key,
					'status' => 1,
					'role' => $value['role'],
				];
				$this->db->insert('permission_list', $data);
			endif;
		endforeach;
	}


	public function indexNumbers()
	{

		$data = [
			'order_user_list' => ['uid', 'order_type', 'shop_id'],

			'users' => [
				"username", "account_type",
			],

			'order_item_list' => [
				"order_id", "shop_id", "item_id"
			],

			'staff_list' => [
				"uid", "shop_id"
			]
		];

		$this->makeIndex($data);
	}

	protected function makeIndex($data)
	{

		foreach ($data as  $tableName => $tableNameValue) :
			foreach ($tableNameValue as  $key => $columnName) :
				if ($this->checkIndex($tableName, $columnName) == 0) {
					$this->db->query("CREATE INDEX {$columnName} 
		 			ON {$tableName} ({$columnName})");
				}
			endforeach;
		endforeach;
	}


	protected function checkIndex($tableName, $columnName)
	{
		// Check if the desired index is present
		$query = $this->db->query("SHOW INDEX FROM $tableName WHERE Column_name = '$columnName'");
		if ($query->num_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}


	public function  checkExistFields($table, $fields)
	{
		if ($this->db->field_exists($fields, $table)) {
			return 1;
		} else {
			return 0;
		}
	}


	public function check_keywords($keywords)
	{
		$this->db->select();
		$this->db->from('language_data');
		$this->db->or_where_in('keyword', $keywords);
		$query = $this->db->get();
		if ($query->num_rows() > 1) {
			return 1;
		} else {
			return 0;
		}
	}

	function activeFeatures()
	{
		$data = ['is_header' => 0];
		$ids = ['5', '6', '7', '10', '11', '12', '13'];
		$this->admin_m->in_update($data, $ids, 'features');
	}

	public function check_slug($slug, $table)
	{
		$this->db->select();
		$this->db->from($table);
		$this->db->where('slug', $slug);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return 1;
		} else {
			return 0;
		}
	}

	public function check_id($id, $table)
	{
		$this->db->select();
		$this->db->from($table);
		$this->db->where('id', $id);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return 1;
		} else {
			return 0;
		}
	}

	public function add_permissions($type = null)
	{
		if ($type == 'qpos') :
			$check_slug = $this->check_slug('pos-order', 'permission_list');
			if ($check_slug == 0) :
				$this->db->query("INSERT INTO permission_list(id,title,slug,status) VALUES 
					('9','POS Order','pos-order','1')");
			endif;

			$check_slug = $this->check_slug('pos-settings', 'permission_list');
			if ($check_slug == 0) :
				$this->db->query("INSERT INTO permission_list(id,title,slug,status) VALUES 
					('10','POS Settings','pos-settings','1')");
			endif;
		endif;
	}

	public function add_features($type = null)
	{
		if ($type == 'qpos') :
			$check_slug = $this->check_slug('pos', 'features');
			if ($check_slug == 0) :
				$this->db->query("INSERT INTO features(id,features,slug,status,is_features,created_at) VALUES 
						('12','POS','pos','1','1','2022-11-25 23:04:31')");
			endif;
		endif;

		if ($type == 'affiliate') :
			$check_slug = $this->check_slug('affiliate', 'features');
			if ($check_slug == 0) :
				$this->db->query("INSERT INTO features(id,features,slug,status,is_features,created_at) VALUES 
						('13','Affiliate','affiliate','1','1','2024-01-05 23:04:31')");
			endif;
		endif;
	}




	public function transfer_pacakge()
	{
		$this->load->model('custom_m');
		$this->custom_m->transfer_data('item_packages', 'item_packages_list', 'package_id');
		return true;
	}



	public function change_price()
	{
		$payment_info = $this->admin_m->select('payment_info');
		foreach ($payment_info as $key => $value) {
			@$this->admin_m->update(['package_price' => $value['price']], $value['id'], 'payment_info');
		}

		return true;
	}


	public function get_customers()
	{
		$customer_list = $this->admin_m->get_customers();
		$data = [];
		if (sizeof($customer_list) > 1) :
			foreach ($customer_list as $key => $row) :
				$data[] = [
					'old_id' => $row->id,
					'user_id' => $row->user_id,
					'customer_name' => $row->name,
					'phone' => $row->phone,
					'email' => $row->email,
					'password' => $row->password,
					'country_id' => $row->country_id,
					'thumb' => $row->thumb,
					'images' => $row->images,
					'address' => $row->address,
					'gmap_link' => $row->gmap_link,
					'question' => $row->question,
					'is_pos' => 0,
				];
			endforeach;

			$insert = $this->admin_m->insert_all($data, 'customer_list');
		else :
			$insert = 1;
		endif;

		if ($insert) {
			$new_customer_list = $this->admin_m->get_new_customers();  // new customers

			foreach ($new_customer_list as $key => $row) :
				$new_customer_data = 	$this->admin_m->get_order_by_customer_id($row->old_id);  // order list by old customer id


				foreach ($new_customer_data as $key => $c) :
					if (!empty($c)) {
						$is_order_update = $this->admin_m->update(['customer_id' => $row->id], $c->id, 'order_user_list'); //update cusotmer id in order table	

					}

				endforeach;


				$update_customer = 	$this->admin_m->update(['is_update' => 1], $row->id, 'customer_list');  // update new custoemr table after


				if ($update_customer) {
					$del_id = 	$this->admin_m->delete($row->old_id, 'staff_list');
				}

			endforeach;
		}
	}

	public function get_staff_from_order()
	{
		$order_details = $this->common_m->get_orders_for_staff_details();
		if (!empty($order_details)) :
			foreach ($order_details as $key => $row) {
				$data[] = [
					'order_id' => $row->id,
					'staff_id' => $row->staff_id,
					'shop_id' => $row->shop_id,
					'staff_role' => 'staff',
					'action_type' => $row->staff_action == 0 ? 'accept' : $row->staff_action,
					'created_at' => $row->created_at,
				];
			}
			$check = $this->default_m->select('staff_order_activity_logs');
			if (empty($check)) {
				$this->default_m->insert_all($data, 'staff_order_activity_logs');
			}
		endif;
	}
}
