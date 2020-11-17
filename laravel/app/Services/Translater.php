<?php

namespace App\Services;

class Translater 
{
	public static  $converter = [
		'а' => 'a',    'б' => 'b',    'в' => 'v',    'г' => 'g',    'д' => 'd',
		'е' => 'e',    'ё' => 'e',    'ж' => 'zh',   'з' => 'z',    'и' => 'i',
		'й' => 'y',    'к' => 'k',    'л' => 'l',    'м' => 'm',    'н' => 'n',
		'о' => 'o',    'п' => 'p',    'р' => 'r',    'с' => 's',    'т' => 't',
		'у' => 'u',    'ф' => 'f',    'х' => 'h',    'ц' => 'c',    'ч' => 'ch',
		'ш' => 'sh',   'щ' => 'sch',  'ь' => '',     'ы' => 'y',    'ъ' => '',
		'э' => 'e',    'ю' => 'yu',   'я' => 'ya',
	];

	public static function translateCHPU($text)
	{
		$text = mb_strtolower($text);
		$text = strtr($text, self::$converter);
		$text = mb_ereg_replace('[^-0-9a-z]', '-', $text);
		$text = mb_ereg_replace('[-]+', '-', $text);
		$text = trim($text, '-');	
	 
		return $text;
	}
}
