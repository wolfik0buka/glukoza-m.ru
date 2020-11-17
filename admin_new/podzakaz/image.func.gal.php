<?php
/**
 * ������� �������� ����������� � ������ ���������
 * @param $file_name  string ���� � ��������� �����������
 * @param $new_name  string ���� � ������ �����������, ���� ����� null, �� ���������� ��������
 * @param $thumb_width  integer ������ ������ �����������
 * @param $thumb_height  integer ������ ������ �����������
 * @param $quality integer  �������� �������� JPEG-�����������
 * @return true - ���� ������ �� ��������, false - � ��������� ������
 */
function makeImage($file_name, $new_name, $thumb_width, $thumb_height, $quality){
	// �������� ���������� �� �������� �����������
	$image_info = @getimagesize($file_name); 
	// ���������� mime-��� �����������
	switch ($image_info['mime']) {         
		case 'image/gif':             
			if (imagetypes() & IMG_GIF) {
				$image = imagecreatefromGIF($file_name);
				$opacity = true;
			} else {
				return false;
			}
			break;
		case 'image/jpeg':
			if (imagetypes() & IMG_JPG) {
				$image = imagecreatefromJPEG($file_name);
				$opacity = false;
			} else {
				return false;
			}
			break;
		case 'image/png':
			if (imagetypes() & IMG_PNG) {
				$image = imagecreatefromPNG($file_name);
				$opacity = true;
			} else {
				return false;
			}
			break;
		default:
			return false;
	}
	
	// ����� �������� ������ � ������ �����������
	$image_width = imagesx($image);
	$image_height = imagesy($image);
	// ���������� �������� ������ � ������ ������ ����������� � ������ ���������
	if($thumb_width && $thumb_height){
		if ($image_width < $image_height){
			$thumb_width = round($thumb_height * $image_width / $image_height);
		} else {
			$thumb_height = round($thumb_width * $image_height / $image_width);
		}
	} elseif($thumb_width && !$thumb_height){
		$thumb_height = round($thumb_width * $image_height / $image_width);
	} elseif(!$thumb_width && $thumb_height){
		$thumb_width = round($thumb_height * $image_width / $image_height);
	} else {
		$thumb_width = $image_width;
		$thumb_height = $image_height;
	}
	// ������� ����� �����������
	if(!$thumb = @imagecreatetruecolor($thumb_width, $thumb_height)){
		return false;
	}
	// �������� ����� ������ � �����-������� ��� ���������� ������������, ���� ��� �����
	if($opacity){
		imagealphablending($thumb, false);
		imagesavealpha($thumb, true);
	}
	// �������� �������� ����������� � �����
	if(!imagecopyresampled($thumb, $image, 0, 0, 0, 0, $thumb_width, $thumb_height, $image_width, $image_height)){
		return false;							
	}

	if(!$new_name){
		$new_name = $file_name;
	}
	// ���� ����� ��������� ������������ ��������� � PNG, ���� ��� �� � JPEG � ��������� ��������� ��������.
	// ������ PNG ������ "�����", ��� GIF, �� �������� ������ ���������, ������� ������ ������ �� ��� ������ � �������������.
	if($opacity){
		if(!imagePNG($thumb, $new_name.".png")){
			return false;
		}
	} else {
		if(!imageJPEG($thumb, $new_name.".jpg", $quality)){
			return false;
		}
	}
	//����������� ������     
	imagedestroy($image);
	imagedestroy($thumb);
	return true;
}
/**
 * ������� �������� ������������ ����������� �� ������
 * @param $form  string ��� ���� ����� ��� �������� ����� 
 * @param $dir  string ��� ���������� �� ������� ��� �������� �����
 * @param $width  integer ������ �����������
 * @param $height  integer ������ �����������
 * @param $quality integer  �������� �������� �����������
 * @param $prefix  string ������� ������
 * @return true - ���� ������ �� ��������, false - � ��������� ������
 */
function uploadImage($form, $dir, $width, $height, $quality, $name){
	// ��������� �������� �� ���� �� ������ � ��� ������ ������ ����
	if (@is_uploaded_file($_FILES[$form]['tmp_name']) && $_FILES[$form]['size'] > 0)
	    {
		// ��������� ����� ��� �����, � ������� md5() �������� ���������� ������������� �����
		// $name = date("H-i-d-m-Y-").substr(md5($_FILES[$form]['name']), 0, 6);
		// $name = "face".$name;
		$tmp = $_FILES[$form]['tmp_name'];
		// ��������� ����� �� �������� ������� ��������� �����������
		if($width || $height){
			/*
            if(!makeImage($tmp, $dir.$name, $quality, $width, $height))	return false;
		} else {
			// ��������� ��� �����*/
			switch ($_FILES[$form]['type']){         
				case 'image/gif':
					$ext = ".gif";
					break;
				case 'image/jpeg':
					$ext = ".jpg";
					break;
				case 'image/png':
					$ext = ".png";
					break;
				default:
					return false;
			}
			if(!copy($tmp, $dir.$name.$ext)){
				return false;
			}
//			if(!makeImage($tmp, $dir.$name, 100)){
//				return false;
//			}
		}
	    return true;	
	} else {
		return false;
	}
} 
?>