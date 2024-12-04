<?php
/**
 * @package Payment Handler
 * @method createToken - @param Array $_DATA - Create Payment Token 
 * @method verifyPaymentToken - @param String $_PAYTOKEN_ - Verify The Payment Transaction Status - Return Data 
 * @method 
 */
class RFLPaymentHandler
{
	private $_PAYTOKEN_;
  	private $_COMPANY_ID_ = '9F416C11-127B-4DE2-AC7F-D5710E4C5E0A'; # Test Env
   	// private $_COMPANY_ID_ = 'A4769E66-4FAD-44C0-83E9-D474440D0A4A'; # Production Env
    
	private $_ServiceType = 5525; # Test Service | 3854 Test Product 
	// private $_ServiceType = 48325; # Test Service | 3854 Production Event Registration 

    private $_url;
	private $_urlPayment;
    
    public function __construct($user = null){
		$this->_PAYTOKEN_      = NULL;
		$this->_url			   = "https://secure.3gdirectpay.com/API/v6/ ";
	}
    
    public function createToken($_DATA,  $DefaultPayment = 'BANK_TRANSFERT', $BlockPayment = array('XP', 'BT')){
        $response_array 	= array();
		$_DATA				= (Object) $_DATA;

		$_REQUEST_ 		 	= 'createToken';

		$PaymentAmount   	= $_DATA->pay_amount;
		$PaymentCurrency 	= $_DATA->pay_currency;
		$CompanyRef      	= $_DATA->pay_transactionID;

		$CompanyRefUnique   = 0;
		$PTL				= 5;
		$PTLtype         	= 'hours';
		$customerEmail      = $_DATA->customer_email;
		$customerFirstName	= $_DATA->customer_firstname;
		$customerLastName   = $_DATA->customer_lastname;

		$userToken 		= $_DATA->customer_token;
		$DefaultPayment = self::getPaymentMethodCode($DefaultPayment);

		$ServiceDescription	= $_DATA->service_description; # Ex. Pay My Event Entracy 
		$ServiceDate        = $_DATA->service_date; # Ex. 2020/12/27 19:00

		$RedirectURL     	= Config::get('api/payment_callback')."/"."response/";
		$BackURL         	= Config::get('api/payment_callback')."/"."cancelled/";
		$DeclinedURL     	= Config::get('api/payment_callback')."/"."declined/";
                
        $_CURL_ = curl_init();
        curl_setopt( $_CURL_, CURLOPT_URL, $this->_url );
        curl_setopt( $_CURL_, CURLOPT_POST, true );
        curl_setopt( $_CURL_, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
        curl_setopt( $_CURL_, CURLOPT_RETURNTRANSFER, true );

        $xml_data= '
		<?xml version="1.0" encoding="utf-8"?>
<API3G>
    <CompanyToken>'.$this->_COMPANY_ID_.'</CompanyToken>
    <Request>'.$_REQUEST_.'</Request>
    <Transaction>
        <PaymentAmount>'. $PaymentAmount .'</PaymentAmount>
        <PaymentCurrency>'. $PaymentCurrency .'</PaymentCurrency>
        <CompanyRef>'. $CompanyRef .'</CompanyRef>
        <RedirectURL>'. $RedirectURL .'</RedirectURL>
        <BackURL>'. $BackURL .'</BackURL>
        <DeclinedURL>'. $DeclinedURL .'</DeclinedURL>
        <CompanyRefUnique>'. $CompanyRefUnique .'</CompanyRefUnique>
        <PTL>'. $PTL .'</PTL>
        <PTLtype>'. $PTLtype .'</PTLtype>
        <customerEmail>'. $customerEmail .'</customerEmail>
        <customerFirstName>'. $customerFirstName .'</customerFirstName>
        <customerLastName>'. $customerLastName .'</customerLastName>
    </Transaction>
    <Services>
        <Service>
            <ServiceType>'. $this->_ServiceType .'</ServiceType>
            <ServiceDescription>'. $ServiceDescription .'</ServiceDescription>
            <ServiceDate>'. $ServiceDate .'</ServiceDate>
        </Service>
    </Services>
</API3G>
';

@curl_setopt( $_CURL_, CURLOPT_POSTFIELDS, $xml_data);
$result = @curl_exec($_CURL_);
@curl_close($_CURL_);

$responseData = array();
if(($responseData = json_decode(json_encode((array) @simplexml_load_string($result)), 1))):
$responseData = (Object) $responseData;
if($responseData):
$responseData->Success = false;
if(array_key_exists("Result", $responseData)):
if($responseData->Result == 000):
$responseData->Success = true;
$this->_PAYTOKEN_ = $responseData->TransToken;
$this->_urlPayment = "https://secure.3gdirectpay.com/payv2.php?ID=".$this->_PAYTOKEN_;
endif;
endif;
endif;
endif;
return empty($responseData)?false:$responseData;
}

public function verifyPaymentToken($_PAYTOKEN_){
$_REQUEST_ = 'verifyToken';

$_CURL_ = curl_init();
curl_setopt( $_CURL_, CURLOPT_URL, $this->_url );
curl_setopt( $_CURL_, CURLOPT_POST, true );
curl_setopt( $_CURL_, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
curl_setopt( $_CURL_, CURLOPT_RETURNTRANSFER, true );

$xml_data= '
<?xml version="1.0" encoding="utf-8"?>
<API3G>
    <CompanyToken>'.$this->_COMPANY_ID_.'</CompanyToken>
    <Request>'.$_REQUEST_.'</Request>
    <TransactionToken>'.$_PAYTOKEN_.'</TransactionToken>
</API3G>
';

@curl_setopt( $_CURL_, CURLOPT_POSTFIELDS, $xml_data);
$result = @curl_exec($_CURL_);
@curl_close($_CURL_);

$responseData = array();
if(($responseData = json_decode(json_encode((array) @simplexml_load_string($result)), 1))):
$responseData = (Object) $responseData;
if($responseData):
$responseData->callback_cmd = json_encode((array) @simplexml_load_string($result));
// if(array_key_exists("Result", $responseData)):
// if($responseData->Result == 000):
// $responseData->Success = true;
// $this->_PAYTOKEN_ = $responseData->TransToken;
// $this->_urlPayment = "https://secure.3gdirectpay.com/payv2.php?ID=".$this->_PAYTOKEN_;
// endif;
// endif;
endif;
endif;
return empty($responseData)?false:$responseData;
}

public function initiatePaymentRequest(){
if($this->_PAYTOKEN_ != NULL)
print('<script>
window.location.href = "'.$this->_urlPayment.'";
</script>');
}

public function getInitiatedPaymentRequestUrl(){
return $this->_urlPayment;
}

public static function data_cleaner($data){
if( is_array($data) )
return '';
return $data;
}
public function pay($_DATA) {
return $_DATA;
}

public static function telephone_cleaner($telephone){
$telephone = preg_replace("/[^0-9]/", "", $telephone );
return @substr($telephone, -9);
}

public static function XMLtoJSON($xml) {
$xml = str_replace(array("\n", "\r", "\t","ns2:"), '', $xml);
$xml = str_replace("ns1:", '', $xml);
$xml = trim(str_replace('"', "'", $xml));
$simpleXml = @simplexml_load_string($xml);
return json_decode(json_encode((array) $simpleXml), 1);
}

public static function getPaymentMethodCode($name){
switch($name):
case 'CREDIT_CARD':
return 'CC';
break;
case 'MOBILE':
return 'MO';
break;
case 'PAYPAL':
return 'PP';
break;
case 'BANK_TRANSFERT':
return 'BT';
break;
case 'XPAY':
return 'XP';
break;
default:
return 'CC';
break;
endswitch;
}

}


?>