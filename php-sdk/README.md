demo-trial-api
==============

PHP SDK

* * *

Config
---

 Param         | Type     | Default 
:--------------|---------:|:------------:
 client_id     | Required | None
 client_secret | Required | None
 verify_ssl    | Optional | True
 access_token  | Optional | None
 refresh_token | Optional | None
 

```
$dapi = new DemoApi(array(
    'client_id' => 'user@domain.com',
	'client_secret' => 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX',
	'verify_ssl' => false
));
```

# Login #
---

## Success Response ##

 Param         | Type     
:--------------|---------:
 access_token  | String
 refresh_token | String
 token_type    | String
 expires_in    | Integer
 
 
```
$token = $dapi->login();
```
```
["access_token"]=>
string(40) "F1loVnFTAGt5G09sKdVRy1BYws98UifPLXRsyRtm"
["token_type"]=>
string(6) "Bearer"
["expires_in"]=>
int(7200)
["refresh_token"]=>
string(40) "8hGuX9Q4YyF0f7J0K2TMfDbH2NgiYgWvaoqcGV8r"
```

## API Errors ##
API Errors raise a `DemoApiException` with a code and message

 
```
try
{
    $token = $dapi->login();
}
catch (DemoApiException $e)
{
	echo 'Error: '.$e->getCode().' -> '.$e->getMessage();
}
```
```
Error: 400 -> Bad Request. Parameter is missing
```

# Calls #
---
```
$response = $dapi->api('ListDatasets', array(
        'p_application' => 'joomla'
	));
  }
```
## Success Response ##

 Param         | Type     
:--------------|---------:
 result        | Array    
 
```
          ["result"]=>
		  array(2) {
			["datasets"]=>
			array(1) {
			  ["joomla"]=>
			  array(1) {
				[0]=>
				array(5) {
				  ["date_added"]=>
				  string(19) "2014-04-03 13:39:23"
				  ["version"]=>
				  string(3) "1.1"
				  ["app_family"]=>
				  string(10) "joomla-2.5"
				  ["datasetid"]=>
				  int(156)
				  ["name"]=>
				  string(4) "dset"
				}
			  }
			}
			["families"]=>
			array(1) {
			  ["joomla"]=>
			  array(4) {
				[0]=>
				string(3) "3.2"
				[1]=>
				string(3) "3.1"
				[2]=>
				string(3) "2.5"
				[3]=>
				string(3) "1.5"
			  }
			}
```

## Error Response ##

 Param         | Type     
:--------------|---------:
 error         | String   
 err_code      | Integer  
 

```
  ["error"]     =>  string(66) "datetime.datetime(2014, 4, 3, 13, 39, 23) is not JSON serializable"
  ["err_code"]  =>  int(1)
```
## API Errors ##
API Errors raise a `DemoApiException` with a code and message
