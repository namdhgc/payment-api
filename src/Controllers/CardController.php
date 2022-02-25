<?php

namespace Src\Controllers;

use Src\Constants\Messages;

class CardController extends BaseController
{
    private string $requestMethod;
    private string $requestWith;

    public function __construct($requestMethod, $requestWith)
    {
        $this->requestMethod = $requestMethod;
        $this->requestWith = $requestWith;
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'POST':
                $response = $this->validatePaymentInformation();
                break;
            default:
                $response = $this->notFoundResponse();
                break;
        }

        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }
    }

    /**
     * @SWG\Post(
     *     path="/api/v1/validate-payment-information",
     *     description="Validate card",
     *     produces={"application/xml", "application/json"},
     *     @SWG\ Parameter(
     *      name="Authorization",
     *      in="header",
     *      description="Authorization",
     *      default="579dbd06b5fd8b850ebb618b0bc60ad9eb5d07aaa8c95184dd251c5cd7b6d674.ac56d2314f002b1bb8e72dcace35b1805d7e58bda6033ebfbf7c35f35df4d24e.b90f92fa3b6acb8c9b0124989d587dfad3bb7c31b526a7b3eac17bc593d44300",
     *     ),
     *     @SWG\Parameter(
     *         name="type",
     *         in="body",
     *         description="Input data",
     *         required=true,
     *          @SWG\Schema(
     *              @SWG\Property(property="type", type="string", example="credit_card"),
     *              @SWG\Property(property="credit_card_number", type="string", example="4221-4986-7928-6717"),
     *              @SWG\Property(property="expiration_date", type="string", example="07/23"),
     *              @SWG\Property(property="CVV2", type="string", example="169"),
     *              @SWG\Property(property="email", type="string", example="email@example.com"),
     *              @SWG\Property(property="mobile", type="string", example="+(84) 83 665 1233"),
     *          )
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="OK",
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="Unprocessable Entity"
     *     )
     * )
     */
    public function validatePaymentInformation(): array
    {
        $inputString = file_get_contents('php://input');
        $input = $this->parseInputData($inputString);

        if (isset($input['status_code_header'])
            && $input['status_code_header'] === Messages::STATUS_CODE_HEADER_UNPROCESSABLE_ENTITY) {
            return $input;
        }

        if (!isset($input['type'])) {
            return $this->unprocessableEntityResponse(Messages::MESSAGE_CHOOSE_PAYMENT_TYPE);
        }

        if ($input['type'] === "credit_card") {
            return $this->validateCard($input);
        }

        if ($input['type'] === "mobile") {
            return $this->validateMobile($input);
        }

        return $this->unprocessableEntityResponse(Messages::MESSAGE_CHOOSE_PAYMENT_TYPE);
    }

    private function parseInputData(string $inputString): array
    {
        if ($this->requestWith === Messages::WITH_JSON) {
            $input = json_decode($inputString, TRUE);

            if ($input === null) {
                return $this->unprocessableEntityResponse(Messages::MESSAGE_JSON_WRONG_FORMAT);
            }
        } elseif ($this->requestWith === Messages::WITH_XML) {
            libxml_use_internal_errors(true);
            $xml = simplexml_load_string($inputString, "SimpleXMLElement", LIBXML_NOCDATA);

            if ($xml === false) {
                return $this->unprocessableEntityResponse(Messages::MESSAGE_XML_WRONG_FORMAT);
            }

            $json = json_encode($xml);
            $input = json_decode($json, TRUE);
        } else {
            return $this->unprocessableEntityResponse(Messages::MESSAGE_ONLY_ACCEPT_JSON_OR_XML);
        }

        return $input;
    }

    private function validateMobile(array $input): array
    {
        if (!isset($input['mobile'])) {
            return $this->unprocessableEntityResponse(Messages::MESSAGE_INPUT_REQUIRED);
        }
        if ($this->isValidTelephoneNumber($input['mobile'])) {
            return $this->successResponse();
        }

        return $this->unprocessableEntityResponse(Messages::MESSAGE_MOBILE_WRONG_FORMAT);
    }

    private function isValidTelephoneNumber(string $mobile, int $minDigits = 9, int $maxDigits = 14): bool
    {
        $mobile = str_replace([' ', '.', '-', '(', ')'], '', $mobile);

        if (preg_match('/^[+][0-9]/', $mobile)) {
            $count = 1;
            $mobile = str_replace(['+'], '', $mobile, $count);
        }

        return $this->isDigits($mobile, $minDigits, $maxDigits);
    }

    private function isDigits(string $s, int $minDigits = 9, int $maxDigits = 14): bool
    {
        return preg_match('/^[0-9]{' . $minDigits . ',' . $maxDigits . '}\z/', $s);
    }

    private function validateCard(array $input): array
    {
        $flag = true;
        $message = '';

        if (!isset(
            $input['credit_card_number'],
            $input['expiration_date'],
            $input['CVV2'],
            $input['email'])
        ) {
            return $this->unprocessableEntityResponse(Messages::MESSAGE_INPUT_REQUIRED);
        }

        if (!$this->validateExpirationDate($input['expiration_date'])) {
            $flag = false;
            $message = Messages::MESSAGE_CARD_EXPIRED;
        }

        if (!$this->validateEmail($input['email'])) {
            $flag = false;
            $message = Messages::MESSAGE_EMAIL_WRONG_FORMAT;
        }

        if (!$this->validateCVV2($input['CVV2'])) {
            $flag = false;
            $message = Messages::MESSAGE_CVV2_INVALID;
        }

        if (!$this->validateCreditCardNUmber($input['credit_card_number'])) {
            $flag = false;
            $message = Messages::MESSAGE_CREDIT_CARD_NUMBER_INVALID;
        }

        if ($flag) {
            return $this->successResponse();
        }

        return $this->unprocessableEntityResponse($message);
    }

    private function validateExpirationDate(string $expiration_date): bool
    {
        $expires = \DateTime::createFromFormat('my', str_replace("/", "", $expiration_date));
        $now = new \DateTime();

        return $expires >= $now;
    }

    private function validateEmail(string $email): bool
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        }

        return false;
    }

    private function validateCVV2(string $CVV2): bool
    {
        return strlen($CVV2) <= Messages::MAX_LENGTH_CVV2;
    }

    private function validateCreditCardNUmber(string $creditCardNumber): bool
    {
        return $this->luhnCheck($creditCardNumber);
    }

    /*
     *
     * Luhn's algorithm
     *
     * Step 1 – Starting from the rightmost digit, double the value of every second digit,
     * Step 2 – If doubling of a number results in a two digit number i.e greater than 9(e.g., 6 × 2 = 12),
     *      then add the digits of the product (e.g., 12: 1 + 2 = 3, 15: 1 + 5 = 6), to get a single digit number.
     * Step 3 – Take the sum of all the digits.
     * Step 4 – If the total modulo 10 is equal to 0 (if the total ends in zero)
     *      then the number is valid according to the Luhn formula; else it is not valid.
     *
     * */
    private function luhnCheck($number): bool
    {
        $number = preg_replace('/\D/', '', $number);

        $number_length = strlen($number);
        $parity = $number_length % 2;

        $total = 0;
        for ($i = 0; $i < $number_length; $i++) {
            $digit = $number[$i];
            if ($i % 2 == $parity) {
                $digit *= 2;
                if ($digit > 9) {
                    $digit -= 9;
                }
            }
            $total += $digit;
        }

        return $total % 10 === 0;
    }
}
