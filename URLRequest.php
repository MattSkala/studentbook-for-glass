<?php
class URLRequest{

	protected $post_data;
	protected $get_data;
	protected $request_url;
	protected $headers = Array();

	public static function request($url, array $post=null,array $get=null,$ret=true){
		$ret = new URLRequest($url, $post, $get);
		return $ret->exec();

	}

	public function __construct($request_url,array $post_data=null, $get_data=null){
		$this->post_data = $post_data;
		$this->request_url = $request_url;
		$this->get_data = $get_data;
	}

	public function addPostField($name, $value){
		if(!is_array($this->post_data)){
			$this->post_data = Array();
		}
		$this->post_data[$name] = $value;
	}

	public function addGetField($name, $value){
		if(!is_array($this->get_data)){
			$this->get_data = Array();
		}
		$this->get_data[$name] = $value;
	}

	public function setHeader($name, $value){
		$this->headers[$name] = $value; 
	}

	public static function formatURLData($data){
		if($data==null){
			return "";
		}
			$fields = "";
			foreach($data as $k=>$v){
				$fields .= $k."=".urlencode($v)."&";
			}
			return substr($fields, 0, -1);
	}

	public function exec(){
		$ch = curl_init($this->request_url."?".self::formatURLData($this->get_data));
		curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
		if($this->post_data!=null){
			curl_setopt($ch, CURLOPT_POST, true);
			
			curl_setopt($ch, CURLOPT_POSTFIELDS, self::formatURLData($this->post_data));
		}
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$ret = curl_exec($ch);
		curl_close($ch);
		return $ret;
	}
}
?>