<?php

  /*****************************************************\
  * Adresse e-mail => direction@codi-one.fr             *
  * La conception est assujettie à une autorisation     *
  * spéciale de codi-one.com. Si vous ne disposez pas de*
  * cette autorisation, vous êtes dans l'illégalité.    *
  * L'auteur de la conception est et restera            *
  * codi-one.fr                                         *
  * Codage, script & images (all contenu) sont réalisés * 
  * par codi-one.fr                                     *
  * La conception est à usage unique et privé.          *
  * La tierce personne qui utilise le script se porte   *
  * garante de disposer des autorisations nécessaires   *
  *                                                     *
  * Copyright ... Tous droits réservés auteur (Fabien B)*
  \*****************************************************/

class Paypal{

	private $user      = identifiant_api_paypal;
	private $pwd       = private_pwd_paypal;
	private $signature = signature_api_paypal;
	private $endpoint = url_api_paypal;
	public $errors    = array();
	public $prod = "oui";

	public function __construct($user = false, $pwd = false, $signature = false, $prod = false){
		if($user){
			$this->user = $user;
		}
		if($pwd){
			$this->pwd = $pwd;
		}
		if($signature){
			$this->signature = $signature;
		}
			//$this->endpoint = str_replace('sandbox.','', $this->endpoint);
		
	}
	public function request($method, $params){
		$params = array_merge($params, array(
				'METHOD' => $method,
				'VERSION' => '84.0',
				'USER'	 => $this->user,
				'SIGNATURE' => $this->signature,
				'PWD'	 => $this->pwd
		));
		$params = http_build_query($params);
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => $this->endpoint,
			CURLOPT_POST=> 1,
			CURLOPT_POSTFIELDS => $params,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_SSL_VERIFYHOST => false,
			CURLOPT_VERBOSE => 1
		));
		$response = curl_exec($curl);
		$responseArray = array();
		parse_str($response, $responseArray);
		if(curl_errno($curl)){
			$this->errors = curl_error($curl);
			curl_close($curl);
			return false;
		}else{
			if($responseArray['ACK'] == 'Success'){
				curl_close($curl);
				return $responseArray;
			}else{
				$this->errors = $responseArray;
				curl_close($curl);
				return false;
			}
		}
	}

}

?>