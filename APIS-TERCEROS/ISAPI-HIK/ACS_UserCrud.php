<?php
    require_once(dirname(__DIR__,2) . '/APIS-TERCEROS/ISAPI-HIK/HikvisionISAPI.php');

class ACS_UserCrud {
    private $hik;
    
    function __construct() {

    }

    function setDeviceData($ip,$username,$password)
    {       
        $this->hik = new HikvisionISAPI($ip, $username, $password);
    }
    
    function checkConnection() {
        return $this->hik->checkConnection();
    }
    
    // Verifica si el dispositivo está en línea
    function isOnline() {
        return $this->hik->isOnline();
    }
    
    // ==================== GESTIÓN DE USUARIOS ====================
    
    // * Crea un usuario en el dispositivo */
    function createUser($employeeNo, $name, $userType = 'normal', $validDays = 3650) {
        if (!$this->isOnline()) {
            return ['success' => false, 'message' => 'Dispositivo no conectado'];
        }
        
        $beginTime = date('Y-m-d\TH:i:s', strtotime('now'));
        $endTime = date('Y-m-d\TH:i:s', strtotime("+$validDays days"));
        
        $data = [
            "UserInfo" => [
                "employeeNo" => (string)$employeeNo,
                "name" => $name,
                "userType" => $userType,
                "Valid" => [
                    "enable" => true,
                    "beginTime" => $beginTime,
                    "endTime" => $endTime
                ]
            ]
        ];
        
        return $this->hik->post("ISAPI/AccessControl/UserInfo/Record", $data);
    }
    
    /**
     * Obtiene información de un usuario
     */
    function getUser($employeeNo) {
        if (!$this->isOnline()) {
            return ['success' => false, 'message' => 'Dispositivo no conectado'];
        }
        $data = [
            "UserInfoSearchCond" => [
            "searchID" => uniqid('search_', true),  // ID único de la sesión
            "maxResults" => 100,
            "searchResultPosition" => 0,
            "EmployeeNoList" => [
                    [
                        "employeeNo" => (string)$employeeNo
                    ]
                ]
            ]
        ];

        $dataUser = $this->hik->post("ISAPI/AccessControl/UserInfo/Search?format=json",$data);
        if(isset($dataUser['success']) && $dataUser['success']==1)
        {
            return $dataUser['data']['UserInfoSearch']['UserInfo'][0];
        }else
        {
            return -1;
        }
    }
    
    /**
     * Lista todos los usuarios
     */
    function listUsers($start = 0, $count = 100) {
        if (!$this->isOnline()) {
            return ['success' => false, 'message' => 'Dispositivo no conectado'];
        }
        
         $data = [
            "UserInfoSearchCond" => [
            "searchID" => uniqid('search_', true),  // ID único de la sesión
            "maxResults" => $count,
            "searchResultPosition" => 0,
            ]
        ];

        $dataUser = $this->hik->post("ISAPI/AccessControl/UserInfo/Search?format=json",$data);
        if(isset($dataUser['success']) && $dataUser['success']==1)
        {
            return $dataUser['data']['UserInfoSearch']['UserInfo'];
        }else
        {
            return -1;
        }

    }
    
    // * Elimina un usuario */
    function deleteUser($employeeNo) {
        if (!$this->isOnline()) {
            return ['success' => false, 'message' => 'Dispositivo no conectado'];
        }
        $data = [
            "UserInfoDetail" => [
                "mode" => "byEmployeeNo",
                "EmployeeNoList" => [
                    [
                        "employeeNo" => "175"
                    ]
                ]
            ]
        ];


        return $this->hik->put("ISAPI/AccessControl/UserInfoDetail/Delete?format=json",$data);
    }
    
    // ==================== GESTIÓN DE TARJETAS ====================
    
    /**
     * Agrega una tarjeta a un usuario
     */
    function addCard($employeeNo, $cardNo, $cardType = 'normalCard') {
        if (!$this->isOnline()) {
            return ['success' => false, 'message' => 'Dispositivo no conectado'];
        }
        
        $data = [
            "CardInfo" => [
                "employeeNo" => (string)$employeeNo,
                "cardNo" => $cardNo,
                "cardType" => $cardType
            ]
        ];
        
        return $this->hik->post("ISAPI/AccessControl/CardInfo/Record", $data);
    }
    
    //  * Lista las tarjetas de un usuario //
    function getUserCards($employeeNo) {
        if (!$this->isOnline()) {
            return ['success' => false, 'message' => 'Dispositivo no conectado'];
        }

        $data = [
            "CardInfoSearchCond" => [
                "searchID" => uniqid('search_', true),  // ID único de la sesión
                "searchResultPosition" => 0,
                "maxResults" => 100,
                "employeeNo" => (string)$employeeNo
            ]
        ];
        
        $dataCard =  $this->hik->post("ISAPI/AccessControl/CardInfo/Search?format=json",$data);
        if(isset($dataCard['success']) && $dataCard['success']==1)
        {
            return $dataCard['data']['CardInfoSearch']['CardInfo'];
        }else
        {
            return -1;
        }
    }
    
    /**
     * Elimina una tarjeta
     */
    function deleteCard($cardNo) {
        if (!$this->isOnline()) {
            return ['success' => false, 'message' => 'Dispositivo no conectado'];
        }

        $data = [
            "CardInfoDelCond" => [
                "CardNoList" => [
                    [
                        "cardNo" => $cardNo
                    ]
                ]
            ]
        ];
        
        return $this->hik->put("ISAPI/AccessControl/CardInfo/Delete?format=json",$data);
    }
    
    // ==================== GESTIÓN DE ROSTROS ====================
    
    // /**
    //  * Agrega un rostro a un usuario (por URL de imagen)
    //  */
    // function addFaceByUrl($employeeNo, $imageUrl, $faceLibType = 'blackFD') {
    //     if (!$this->isOnline()) {
    //         return ['success' => false, 'message' => 'Dispositivo no conectado'];
    //     }
        
    //     $data = [
    //         "faceLibType" => $faceLibType,
    //         "FDID" => 1,
    //         "FPID" => (string)$employeeNo,
    //         "faceURL" => $imageUrl,
    //         "featurePointType" => "face"
    //     ];
        
    //     return $this->hik->post("ISAPI/Intelligent/FDLib/FaceDataRecord", $data);
    // }
    
    /**
     * Agrega un rostro a un usuario (con imagen local)
     */
    function addFaceFromFile($employeeNo, $imagePath, $faceLibType = 'blackFD') {
        if (!$this->isOnline()) {
            return ['success' => false, 'message' => 'Dispositivo no conectado'];
        }
        if (!file_exists($imagePath)) {
            return ['success' => false, 'message' => 'Archivo de imagen no encontrado'];
        }
        $data = [
            'FaceDataRecord' => json_encode([
                "faceLibType" => "blackFD",
                "FDID" => "1",
                "FPID" => (string)$employeeNo
            ], JSON_UNESCAPED_SLASHES),
            'faceImage' => new CURLFile($imagePath, "image/jpeg", "face.jpg")
        ];        
        return $this->hik->postMultipart("ISAPI/Intelligent/FDLib/FaceDataRecord", $data, $imagePath);
    }

     //  * Lista las face de un usuario //
    function getUserFaces($employeeNo) {
        if (!$this->isOnline()) {
            return ['success' => false, 'message' => 'Dispositivo no conectado'];
        }
         $data = [
            "maxResults"=> 100,
            "searchResultPosition"=> 0,
            "faceLibType"=> "blackFD",
            "FDID"=> "1",
            "FPID"=>(string)$employeeNo
        ];
        $listFace =  $this->hik->post("ISAPI/Intelligent/FDLib/FDSearch?format=json",$data);
        if(isset($listFace['success']) && $listFace['success']==1)
        {
            return $listFace['data']['MatchList'];
        }else
        {
            return -1;
        }
    }


     //  * Lista las all face //
    function listFaces() {
        if (!$this->isOnline()) {
            return ['success' => false, 'message' => 'Dispositivo no conectado'];
        }
        $data = [
            "maxResults"=> 100,
            "searchResultPosition"=> 0,
            "faceLibType"=> "blackFD",
            "FDID"=> "1"
        ];
        $listFace =  $this->hik->post("ISAPI/Intelligent/FDLib/FDSearch?format=json",$data);
        if(isset($listFace['success']) && $listFace['success']==1)
        {
            return $listFace['data']['MatchList'];
        }else
        {
            return -1;
        }
    }
    
    
    /**
     * Elimina un rostro
     */
     function deleteFace($employeeNo, $faceLibType = 'blackFD') {
        if (!$this->isOnline()) {
            return ['success' => false, 'message' => 'Dispositivo no conectado'];
        }
        $url  = "ISAPI/Intelligent/FDLib/FDSetUp?format=json";
        $data = [
                "faceLibType"=>"blackFD",
                "FDID"=>"1",
                "FPID"=>(string)$employeeNo,
                "deleteFP"=>true

        ];
    return $this->hik->put($url, $data);

    }
    
    // ==================== GESTIÓN DE HUELLAS ====================
    
   
     function addFingerprint($employeeNo, $fingerPrintID) {
        if (!$this->isOnline()) {
            return ['success' => false, 'message' => 'Dispositivo no conectado'];
        }        
        $data = '<CaptureFingerPrintCond version="2.0" xmlns="http://www.isapi.org/ver20/XMLSchema">
                  <fingerNo>1</fingerNo>
                </CaptureFingerPrintCond>';
        
        $fingerData = $this->hik->postXML("ISAPI/AccessControl/CaptureFingerPrint?format=json", $data);
        if(isset($fingerData['success'])  && $fingerData['success']==1)
        {
            if (is_string($fingerData['raw'])) {
                $xml = simplexml_load_string($fingerData['raw']);
            } else {
                $xml = $fingerData['raw'];
            }
            
            // Extraer datos
            $fingerData = (string)$xml->fingerData;
            $fingerNo = (int)$xml->fingerNo;
            $fingerPrintQuality = (int)$xml->fingerPrintQuality;
    
            $data = [
                "FingerPrintCfg" => [
                    "employeeNo" => (string)$employeeNo,
                    "fingerPrintID" => $fingerPrintID,
                    "fingerType" => "normalFP",
                    "fingerData" => $fingerData,
                    "enableCardReader" => [1,2]
                ]
            ];

           return $this->hik->post("ISAPI/AccessControl/Fingerprint/SetUp", $data);

        }else
        {
            return -1;
        }
    }
    
    //  Lista las huellas de un usuario
    
     function getUserFingerprints($employeeNo) {
        if (!$this->isOnline()) {
            return ['success' => false, 'message' => 'Dispositivo no conectado'];
        }
        
        return $this->hik->get("ISAPI/AccessControl/FingerPrintInfo/{$employeeNo}?format=json");
    }
    
    /**
     * Elimina una huella digital
     */
     function deleteFingerprint($employeeNo, $fingerPrintID) {
        if (!$this->isOnline()) {
            return ['success' => false, 'message' => 'Dispositivo no conectado'];
        }
        
        return $this->hik->delete("ISAPI/AccessControl/FingerPrintInfo/{$employeeNo}/{$fingerPrintID}?format=json");
    }
    
    // ==================== FUNCIÓN COMPLETA ====================
    
    /**
     * Registra un empleado completo con todas sus credenciales
     */
     function registerFullEmployee($employeeNo, $name, $cardNo = null, $faceImagePath = null, $fingerprintData = null) {
        $results = [];
        
        // 1. Verificar conexión
        $connection = $this->checkConnection();
        if (!$connection['connected']) {
            return ['success' => false, 'message' => $connection['message']];
        }
        
        // 2. Crear usuario
        $userResult = $this->createUser($employeeNo, $name);
        $results['user'] = $userResult;
        
        if (!$userResult['success']) {
            return ['success' => false, 'message' => 'Error al crear usuario', 'details' => $userResult];
        }
        
        // 3. Agregar tarjeta (si se proporcionó)
        if ($cardNo) {
            $results['card'] = $this->addCard($employeeNo, $cardNo);
        }
        
        // 4. Agregar rostro (si se proporcionó)
        if ($faceImagePath && file_exists($faceImagePath)) {
            $results['face'] = $this->addFaceFromFile($employeeNo, $faceImagePath);
        }
        
        // 5. Agregar huella (si se proporcionó)
        if ($fingerprintData) {
            $results['fingerprint'] = $this->addFingerprint($employeeNo, 1, $fingerprintData);
        }
        
        return [
            'success' => true,
            'message' => 'Empleado registrado exitosamente',
            'employeeNo' => $employeeNo,
            'results' => $results
        ];
    }
}
?>