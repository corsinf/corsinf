<?php
	/**
	 * ICD API client
	 * 
	 * @version		1.2
	 * @author    	mdonada	 
	 * @package		classes
	 * @since 		icd-api-playground 1.0
	 */

	if(isset($_GET['new_token']))
	{
		$ICD = new ICD_API_Client();

		echo json_encode($ICD->new_token_json());
	}
	 
	class ICD_API_Client {
		
		const TOKEN_ENPOINT = "https://icdaccessmanagement.who.int/connect/token";		
		const CLIENT_ID =  "0664289c-a2cb-4261-bae6-09e6f735b534_48c53bcf-71cb-49c2-bc00-fcb52aec8155";
		const CLIENT_SECRET = "1BL84l4xGdEVaw296yFRkqCcZgqoOmaB4SzN4sd0scc=";		
		const SCOPE = "icdapi_access";
		const GRANT_TYPE = "client_credentials";
		
		
		private $token;
		private $uri;
		private $api_response;

		
		/**
		 * ICDAPIClient constructor - need session_start()
 		 * @param string $uri
		 */	
		public function __construct() {
			
		}		
		
		
		/**
		 * Make the get request
		 * @return json
		 */
		public function get() {
		
			if($this->makeRequest() == 401) { // unauthorized token 
				$this->newToken();
				$this->makeRequest();
			}			 
			return json_decode($this->api_response);
		}

		public function new_token_json()
		{
			return  $this->newToken_jso();
		}	
			
		
		/**
		 * Make the curl request
		 * @return int $http_code
		 */
		private function makeRequest() {
		
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $this->uri);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
					'Authorization: Bearer '.$this->token,
					'Accept: application/json',
					'Accept-Language: es',
					'API-Version: v2'
			));			
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // set curl without result echo
			$this->api_response = curl_exec($ch);
			$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);		
			curl_close($ch);
						
			return $http_code;		
		}
		
		
		/**
		 * Request an OAUTH 2.0 token from the server
		 */
		private function newToken() {
		
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, self::TOKEN_ENPOINT);
			curl_setopt($ch, CURLOPT_POST, TRUE);
			curl_setopt($ch, CURLOPT_POSTFIELDS, array(
					'client_id' => self::CLIENT_ID,
					'client_secret' => self::CLIENT_SECRET,
					'scope' => self::SCOPE,
					'grant_type' => self::GRANT_TYPE
			));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // set curl without result echo
			$result = curl_exec($ch);
			curl_close($ch);
			
			$json_array = (json_decode($result, true));
			$this->token = $json_array['access_token'];
			$_SESSION['token'] = $this->token;
		}		

		private function newToken_jso() {
		
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, self::TOKEN_ENPOINT);
			curl_setopt($ch, CURLOPT_POST, TRUE);
			curl_setopt($ch, CURLOPT_POSTFIELDS, array(
					'client_id' => self::CLIENT_ID,
					'client_secret' => self::CLIENT_SECRET,
					'scope' => self::SCOPE,
					'grant_type' => self::GRANT_TYPE
			));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // set curl without result echo
			$result = curl_exec($ch);
			curl_close($ch);
			
			$json_array = (json_decode($result, true));
			$this->token = $json_array['access_token'];
			return array('token'=>$this->token);
		}		
			
	}

?>
