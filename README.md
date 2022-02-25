# Payment-api

<div id="top"></div>
<!--
*** Thanks for checking out the Best-README-Template. If you have a suggestion
*** that would make this better, please fork the repo and create a pull request
*** or simply open an issue with the tag "enhancement".
*** Don't forget to give the project a star!
*** Thanks again! Now go create something AMAZING! :D
-->



<!-- PROJECT SHIELDS -->
<!--
*** I'm using markdown "reference style" links for readability.
*** Reference links are enclosed in brackets [ ] instead of parentheses ( ).
*** See the bottom of this document for the declaration of the reference variables
*** for contributors-url, forks-url, etc. This is an optional, concise syntax you may use.
*** https://www.markdownguide.org/basic-syntax/#reference-style-links
-->
[![Issues][issues-shield]][issues-url]
[![MIT License][license-shield]][license-url]
[![LinkedIn][linkedin-shield]][linkedin-url]

<!-- ABOUT THE PROJECT -->
## About The Project
* PHP Test Task - Payment API
* Build API to validate payment information



<!-- Requirement -->
## Requirement
- [x] Rest API
- [x] OOP, MVC
- [x] Support data in 2 formats:
    - [x] JSON
    - [x] XML
- [x] Authorize request hash from the key, data and timestamp



### Setup

1. Clone the repo
   ```sh
   https://github.com/namdhgc/payment-api.git
   ```
2. Install composer packages
   ```sh
   cd payment-api
   composer install
   ```
3. Enter configuration into `.env` 
   ```
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=payment-api
    DB_USERNAME=root
    DB_PASSWORD=root
   ```



### Postman collection
```
{
	"info": {
		"_postman_id": "c72d16fe-8ee1-4855-97d1-6c2ace4d0c66",
		"name": "ocubator-payment-api",
		"schema": "https://schema.getpostman.com/json/collection/v2.1.0/collection.json"
	},
	"item": [
		{
			"name": "Validate card",
			"protocolProfileBehavior": {
				"disabledSystemHeaders": {
					"accept": true
				}
			},
			"request": {
				"method": "POST",
				"header": [
					{
						"key": "Accept",
						"value": "application/json",
						"type": "text"
					},
					{
						"key": "Authorization",
						"value": "579dbd06b5fd8b850ebb618b0bc60ad9eb5d07aaa8c95184dd251c5cd7b6d674.ac56d2314f002b1bb8e72dcace35b1805d7e58bda6033ebfbf7c35f35df4d24e.b90f92fa3b6acb8c9b0124989d587dfad3bb7c31b526a7b3eac17bc593d44300",
						"type": "text"
					}
				],
				"body": {
					"mode": "raw",
					"raw": "{\n    \"type\": \"credit_card\",\n    \"mobile\": \"+84836651233\",\n    \"credit_card_number\": \"5421-7202-5866-1852\",\n    \"expiration_date\": \"07/23\",\n    \"CVV2\": \"ILU\",\n    \"email\": \"namdhgc@gmail.com\"\n}",
					"options": {
						"raw": {
							"language": "json"
						}
					}
				},
				"url": {
					"raw": "127.0.0.1:8000/api/v1/validate-payment-information",
					"host": [
						"127",
						"0",
						"0",
						"1"
					],
					"port": "8000",
					"path": [
						"api",
						"v1",
						"validate-payment-information"
					]
				}
			},
			"response": []
		}
	]
}
```



### Sample data for testing
1. XML data
```
<?xml version="1.0" encoding="UTF-8" ?>
<root>
  <type>credit_card</type>
  <mobile>+84836651233</mobile>
  <credit_card_number>5421-7202-5866-1852</credit_card_number>
  <expiration_date>07/23</expiration_date>
  <CVV2>ILU</CVV2>
  <email>namdhgc@gmail.com</email>
</root>
```

2. JSON data
```
{
    "type": "credit_card",
    "mobile": "+84836651233",
    "credit_card_number": "5421-7202-5866-1852",
    "expiration_date": "07/23",
    "CVV2": "ILU",
    "email": "namdhgc@gmail.com"
}
```



### Command line generate swagger
```sh
php ./vendor/bin/swagger --bootstrap ./bootstrap.php --output ./public/swagger ./swagger-v1.php ./src/Controllers
```



### Command line start local built-in server
```sh
php -S 127.0.0.1:8000 -t public
```



### Sample image
![Alt text](./images/postman_example.png?raw=true "postman_example")
![Alt text](./images/swagger_example.png?raw=true "swagger_example")



<p align="right">(<a href="#top">back to top</a>)</p>

[contributors-url]: https://github.com/namdhgc
[issues-url]: https://github.com/namdhgc
[license-url]: https://github.com/othneildrew/Best-README-Template/blob/master/LICENSE.txt
[linkedin-url]: https://www.linkedin.com/in/%C4%91inh-nam-52b3b711a/


[issues-shield]: https://img.shields.io/github/issues/othneildrew/Best-README-Template.svg?style=for-the-badge
[license-shield]: https://img.shields.io/github/license/othneildrew/Best-README-Template.svg?style=for-the-badge
[contributors-shield]: https://img.shields.io/github/contributors/othneildrew/Best-README-Template.svg?style=for-the-badge
[linkedin-shield]: https://img.shields.io/badge/-LinkedIn-black.svg?style=for-the-badge&logo=linkedin&colorB=555
