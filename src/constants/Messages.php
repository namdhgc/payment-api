<?php

namespace Src\Constants;

class Messages
{
    public const MESSAGE_INPUT_REQUIRED = "Please input required information";
    public const MESSAGE_CARD_EXPIRED = "Card expired";
    public const MESSAGE_EMAIL_WRONG_FORMAT = "Email wrong format";
    public const MESSAGE_CVV2_INVALID = "Invalid CVV2 code";
    public const MESSAGE_CREDIT_CARD_NUMBER_INVALID = "Credit card number invalid";
    public const MAX_LENGTH_CVV2 = 4;
    public const MESSAGE_CHOOSE_PAYMENT_TYPE = "Please select payment method by credit_card or mobile!";
    public const MESSAGE_MOBILE_WRONG_FORMAT = "Please enter correct format of the phone number!";
    public const WITH_JSON = "application/json";
    public const WITH_XML = "application/xml";
    public const MESSAGE_JSON_WRONG_FORMAT = "JSON data wrong format. Please try again!";
    public const MESSAGE_XML_WRONG_FORMAT = "XML data wrong format. Please try again!";
    public const MESSAGE_ONLY_ACCEPT_JSON_OR_XML = "Only accept JSON or XML";
    public const MESSAGE_WRONG_AUTHORIZATION = "Authorization failed!";

    public const STATUS_CODE_HEADER_SUCCESS = "HTTP/1.1 200 Success";
    public const STATUS_CODE_HEADER_NOT_FOUND = "HTTP/1.1 404 Not Found";
    public const STATUS_CODE_HEADER_UNPROCESSABLE_ENTITY = "HTTP/1.1 422 Unprocessable Entity";
    public const STATUS_CODE_WRONG_AUTHORIZATION = "HTTP/1.1 401 Unauthorized";
}