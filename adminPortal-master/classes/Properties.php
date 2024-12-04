<?php
/**
 * Properties Settings class
 * This Class is used for Properties Settings Related:
 * (Change Preferred Language : Initialy[French, English]
 * i18n of Each String Ouptut in the Software using JSON Properties.json
 * @author Ezechiel Kalengya Ezpk [ ezechielkalengya@gmail.com ]
 */
class Properties
{
	private $readJson;
	private $Prop;
	private $Software_Language;
	function __construct($lang='')
	{
		$this->readJson = Config::get('server/name').'/config/json/properties.json';
		$this->Prop=$this->parseJson();
		$lang=$lang?$lang:$this->selectedLang();
		$this->Software_Language=$lang;
	}

	private function parseJson(){
		$jsonData=file_get_contents(''.$this->readJson);
		$json=json_decode($jsonData,true);
		//return the decoded to php array from json
		return $json;
	}

	public function selectedLang(){
		$key=$this->string_key("software-lang");
		if($key)  foreach($this->Prop['properties'][$key] as $key=>$value) return $value['lang'];
	  else return 'fr-lang';
	}

	private function string_key($map_word){
		foreach($this->Prop['properties'] as $key => $value):
			if(key($this->Prop['properties'][$key])==$map_word): return $key;
		  else: false;
			endif;
		endforeach;
	}

	/**
	 * Returns the i18n String Output setted in properties.json Depending on the selected Lang
	 * @param string name of the string key setted in properties.json
	 */
	public function string($map_word){
		$key=$this->string_key($map_word);
		if($key)  foreach($this->Prop['properties'][$key] as $key=>$value) return $value[$this->Software_Language];
	  else return 'fr-lang';
	}

	/**
	 * Returns the i18n String Output setted in properties.json Depending on the selected Lang
	 * @param string name of the string key setted in properties.json
	 */
	public function words($map_word){
		$array    = $this->regroupRecognizedWords($map_word);
		$array    = explode(' ', $array);
		$results  = array();
		if(!empty($array)):
			foreach($array as $key => $value):
				$results[] = $this->string(strtolower($value))=='fr-lang'?$value:$this->string(strtolower($value));
			endforeach;
		endif;
		return implode(' ', $results);
	}

	public function regroupRecognizedWords($map_word){
		$array_rec = array(
			'Early bird' => 'early-bird',
			'Early Bird' => 'early-bird',
			'first name' => 'first-name',
			'First name' => 'first-name',
			'last name' => 'last-name',
			'Last name' => 'last-name',
			'enter your' => 'enter-your',
			'job title'  => 'job-title',
			'job category'  => 'job-category',
			'discounted rate' => 'discounted-rate'
			
		);
		foreach($array_rec As $recognized_key => $recognized_value)
			if(strpos($map_word, $recognized_key) !== false)
				$map_word = str_replace($recognized_key, $recognized_value, ($map_word));
		
		return $map_word;
	}
	
	/**
	 * Returns the i18n String Output setted in properties.json Depending on the selected Lang
	 * @param string name of the string key setted in properties.json
	 */
	public function content($map_word){
		$array    = explode(' ', trim($map_word));
		$array    = 'content-'.implode('-', $array);
		$array    = strtolower($array);
		$array    = str_replace(['--'], '', $array);
		// var_dump(($array));
		$results  = array();
		if(!empty($array)):
				$results[] = $this->string(strtolower($array))=='fr-lang'?$map_word:$this->string(strtolower($array));
		endif;
		return empty(implode(' ', $results))?'':implode(' ', $results);
	}
		
	/**
	 * Returns the i18n String Output setted in properties.json Depending on the selected Lang
	 * @param string name of the string key setted in properties.json
	 */
	public function translate($map_word){
		$map_word = str_replace(['  ', '   '], ' ', $map_word);
		$array    = explode(' ', trim($map_word));
		$array    = implode('-', $array);
		$array    = strtolower($array);
		$array    = str_replace(['--'], '', $array);
		$results  = array();
		if(!empty($array)):
				$results[] = $this->string(strtolower($array))=='fr-lang'?$map_word:$this->string(strtolower($array));
		endif;
		return empty(implode(' ', $results))?$map_word:implode(' ', $results);
	}

	/**
	 * Returns true or false if selected Language has changed
	 * @param string new_value lange ex. fr-lang, eng-lang
	 */
	public function changeLang($new_value){
		$key=$this->string_key("software-lang");
		$data = $this->Prop;
		if($key):
			$data['properties'][$key]['software-lang']['lang']=$new_value;
			$newJsonString = json_encode($data);
			if(file_put_contents($this->readJson, $newJsonString)) return true; else false;
		else: return false;
		endif;
	}

}



?>
