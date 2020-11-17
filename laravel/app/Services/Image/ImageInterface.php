<?php namespace App\Services\Image;

interface ImageInterface {

    public function open($path);

    public function openFromUrl($url);

    public function getSize();

    public function crop($width, $height);

    public function resize($width, $height);

    public function getPath();

    public function setWatermark();

    public function save();

}