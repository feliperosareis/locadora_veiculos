<?php

class compact_image {

	private $key = '';
	
	public function __construct() {
		
		require_once(dirname(dirname(__FILE__)) . "/libraries/tinypng/vendor/autoload.php");	
		require_once(dirname(dirname(__FILE__)) . "/libraries/SimpleImage.php");

	}

	public function compactar($input, $output, $width = 0, $height = 0, $method_tinypng = null, $method_resize = null) {	
		
		if($this->simple_image($input, $output, $width, $height, $method_resize)){

			$this->tinypng($output, $width, $height, $method_tinypng);
			return true;
		}

		return false;
		
	}

	public function simple_image($input, $output, $width = 0, $height = 0, $type = null){

		$img = new SimpleImage($input);

		if ($type == 'crop'){
			
			if ($img->best_fit(($width+200), ($height+200))->save($output, 90)){

				$imagem = getimagesize($output);
				
				$x = 0;
				$y = 0;

				if ($imagem[0] > $width) {
					$x = (($imagem[0] - $width)/2);
					$width += $x;
				}

				if ($imagem[1] > $height) {
					$y = (($imagem[1] - $height)/2);
					$height += $y;
				}

				return $img->crop($x, $y, $width, $height)->save($output, 90);			
			}				

		}

		return $img->best_fit($width, $height)->save($output, 90);

	}

	public function tinypng($output, $width = 0, $height = 0, $method = null){

		try {

			\Tinify\setKey($this->key);				
			\Tinify\validate();

			$source = \Tinify\fromFile($output);

			if($height > 0 && $width > 0){

				$resized = $source->resize(array(
					"method" => ($method ? $method : "fit"),
					"width" => $width,
					"height" => $height
					));

				return $resized->toFile($output);

			}

			return $source->toFile($output);

		} catch(\Tinify\Exception $e) {}

		return false;

	}

}

?>