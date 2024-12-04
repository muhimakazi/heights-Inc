<?php
/**
 * Class for checking inputs in forms
 */

class Input {
    public static function exists($type = 'post') {
        switch($type) {
            case 'post':
                return (!empty($_POST)) ? true : false;
                break;
            case 'get':
                return (!empty($_GET)) ? true : false;
                break;
            default:
                return false;
                break;
        }
    }

    public static function get($item) {
        if(isset($_POST[$item])) {
            return $_POST[$item];
        } else if(isset($_GET[$item])) {
            return $_GET[$item];
        }

        return '';
    }

    
	public static function checkFieldSubmited($item,$source = 'get',$state = '0'){
		switch($source){
			case 'post':
				if(isset($_POST[$item]) && $state == '1'){
					return (!empty($_POST[$item]))? true : false;
				}else if(isset($_POST[$item])){
					return (empty($_POST[$item]))? true : false;
				}
			break;
			case 'get':
				if(isset($_GET[$item]) && $state == '1'){
					return (!empty($_GET[$item]))? true : false;
				}else if(isset($_GET[$item])){
					return (empty($_GET[$item]))? true : false;
				}
			break;
		}
		return false;
	}
	
	public static function checkInput($item,$source = 'get',$state = '0'){
		switch($source){
			case 'post':
				if(isset($_POST[$item]) && $state == '1'){
					return (!empty($_POST[$item]))? true : false;
				}else if(isset($_POST[$item])){
					return (empty($_POST[$item]))? true : false;
				}
			break;
			case 'get':
				if(isset($_GET[$item]) && $state == '1'){
					return (!empty($_GET[$item]))? true : false;
				}else if(isset($_GET[$item])){
					return (empty($_GET[$item]))? true : false;
				}
			break;
		}
		return false;
    }
}