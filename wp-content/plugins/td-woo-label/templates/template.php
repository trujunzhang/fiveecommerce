<?php
/**
 * Created by PhpStorm.
 * User: tagdiv
 * Date: 20.08.2015
 * Time: 15:27
 *
 * @package td-woo-label\templates
 */

?>
<!DOCTYPE html>
<html>
	<head>
		<style>
			@import url(http://fonts.googleapis.com/css?family=Roboto:400,500,700,400italic,500italic,700italic);

			body {
				margin: 20px;
				font-family: 'Roboto', sans-serif;
				font-size: 14px;
				line-height: 19px;
				width: 65%;
			}

			/* text info */
			#label_info {
				width: 70%;
				display: inline-block;
				float: left;
			}
			#label_info span {
				font-weight: 500;
				margin-right: 10px;
			}

			/* hide some labels */
			#label_info .shipping_first_name span,
			#label_info .shipping_last_name span,
			#label_info .shipping_city span {
				display: none;
			}

			/* display info on one line */
			#label_info .shipping_state,
			#label_info .shipping_city,
			#label_info .shipping_full_name,
			#label_info .shipping_first_name,
			#label_info .shipping_last_name {
				display: inline;
			}
			#label_info .shipping_first_name {
				margin-right: 5px;
			}
			#label_info .shipping_state {
				margin-right: 10px;
			}



			/* image/qr code info */
			#label_image {
				width: 30%;
				display: inline-block;
				text-align: right;
			}
			#label_image img {
				float: right;
				width: 150px;
			}

			/* print button */
			.print-button {
				background-color: #222;
				color: #fff;
				padding: 16px 40px;
				display: table;
				text-decoration: none;
				margin: 20px 0 20px auto;
			}
			.print-button:hover {
				opacity: 0.9;
			}

			/* hide the print button on print */
			@media print {
				.print-button {
					display: none;
				}
			}
		</style>
		<meta charset="UTF-8">
		<title>Shipping Label</title>
	</head>
	<body>
		<a id="printing" class="print-button" href="#print" onclick="window.print(); return false;">Print the label</a>
		<div id="label_info"></div>
		<div id="label_image"></div>
	</body>
</html>
