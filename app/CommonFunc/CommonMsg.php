<?php
// Success case
define ( 'SUCCESS_CODE', '0' );
define ( 'SUCCESS_MSG', 'Success' );

// Error case for create card
// Guest name
define ( 'ERR_REQUIRED_NAME_CODE', '1' );
define ( 'ERR_REQUIRED_NAME_MSG', 'Empty guest name' );
define ( 'ERR_LENGTH_NAME_CODE', '2' );
define ( 'ERR_LENGTH_NAME_MSG', 'Guest name must less than 20 character' );
// Phone number
define ( 'ERR_REQUIRED_PHONE_CODE', '3' );
define ( 'ERR_REQUIRED_PHONE_MSG', 'Empty phone number' );
define ( 'ERR_NUMERIC_PHONE_CODE', '4' );
define ( 'ERR_NUMERIC_PHONE_MSG', 'Phone number must be numeric' );
define ( 'ERR_LENGTH_PHONE_CODE', '5' );
define ( 'ERR_LENGTH_PHONE_MSG', 'Phone number must be 9 or 10 character' );
define ( 'ERR_DUPLICATE_PHONE_CODE', '6' );
define ( 'ERR_DUPLICATE_PHONE_MSG', 'Duplicate phone number' );

// Error case for pay off
define ( 'ERR_NOT_EXIST_CARD_CODE', '7' );
define ( 'ERR_NOT_EXIST_CARD_MSG', 'Card not exist' );

define ( 'ERR_NOT_EXIST_PRODUCT_CODE', '8' );
define ( 'ERR_NOT_EXIST_PRODUCT_MSG', 'Product not exist' );

// header json

define ('JSON' , ' "Content-Type", "application/json" ' ); 

?>