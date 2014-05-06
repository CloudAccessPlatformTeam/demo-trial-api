<?php
require_once 'php-phone-sanitizer/PhoneNumberSanitizer.php';

function demoregister_getparams()
{
$fields = array(
'sitename',
'firstname',
'lastname',
'companyname',
'email',
'postcode',
'country',
'city',
'state',
'address',
'address2',
'phonenumber',
'demoDescribes',
'demoTitle',
'reason',
'productid',
'companysize',
'billing_cycle',
'billing_paymentmethod',
'application',
'dataset'
);




/*
'demoReasonLearn',
'demoReasonCompany',
'demoReasonHelp',
'demoReasonYourself',
'demoReasonClients',
*/	

$params = array();
        $input = JFactory::getApplication()->input;
        $fullname = $input->get('fullname','','string');
        $nameParts = explode(' ',$fullname);
        if (count($nameParts) == 1 && empty($nameParts[1]))
        {
            $nameParts[1] = ' ';
        }
        

$post = JRequest::get();
foreach($fields as $field)
{
if($field == "reason")
{
$params[$field] = "";
$listreasons = JRequest::getVar($field, NULL, 'post', 'array');

if($listreasons != NULL)
{
$params[$field] = implode(" , ", $listreasons);
}

}
else
{
$params[$field] = JRequest::getVar($field, NULL, 'post', 'string');
}	
}

//lowercase and delete whitespaces if exists
    $params['sitename'] = strtolower($params['sitename']);
$params['sitename'] = str_replace(" ", "", $params['sitename']);

$params['username'] = $params['sitename'];
        if (empty($params['firstname']))
        {
            $params['firstname'] = $nameParts[0];
        }
        if (empty($params['lastname']))
        {
            $params['lastname'] = $nameParts[1];
        }

if ($params["phonenumber"] == 'PhoneNumber(Optional)')
{
$params["phonenumber"] = "";
}

/* Sanitize phone number if entered by user */
if(trim($params['phonenumber']) != "")
{
//replace ( ) - + from user input
$params['phonenumber'] = str_replace("(", "", $params['phonenumber']);
$params['phonenumber'] = str_replace(")", "", $params['phonenumber']);
$params['phonenumber'] = str_replace("-", "", $params['phonenumber']);
$params['phonenumber'] = str_replace(" ", "", $params['phonenumber']);	
$params['phonenumber'] = str_replace("+", "", $params['phonenumber']);

$sanitizer = new PhoneNumberSanitizer(false);
$number = $params['phonenumber'];
$params['rawPhone'] = $number;
try
{
$params['phonenumber'] = $sanitizer->Sanitize($params['country'], $params['phonenumber']);
}
catch(PhoneNumberSanitizerCountryException $ex)
{
$log = fopen(JPATH_ROOT.'/logs/domaincreate.log', 'a');
fwrite($f, $ex->getMessage() . "\n");
fclose($log);
}
catch(PhoneNumberSanitizerException $ex)
{
$log = fopen(JPATH_ROOT.'/logs/domaincreate.log', 'a');
fwrite($f, $ex->getMessage() . "\n");
fclose($log);
}
        }

/* Generate password */
$easy_pass = 'abcdfghjkmnpqrstvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ';
$pass_dict = "0123456789!abcdfghjkmnpqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
srand((double)microtime()*1000000);
$pass = '';
$pass .= $easy_pass[rand(0, strlen($easy_pass) - 1)];
for($i = 0; $i < rand(7, 9); $i++)
{
$pass .= $pass_dict[rand(0, strlen($pass_dict) - 1)];
}
$pass .= $easy_pass[rand(0, strlen($easy_pass) - 1)];
$params['password2'] = $pass;
$params['currency'] = 'USD';

return $params;
}