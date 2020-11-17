<?php namespace App\Services\Image;

use App\Services\Image\Facades\Intervention;

class Image implements ImageInterface
{
    public $imageServer;
    protected $imagePath;
    protected $watermarkPath;

    public $size;
    public $mime;


    public function __construct()
    {
        $this->watermarkPath = public_path('i/app/watermark_small.png');
    }


    /**
     * @param string $path
     * @return Image
     */
    public function open($path){
        $this->imagePath = $path;
        $this->imageServer = Intervention::make($this->imagePath)->encode('jpg', 85);
        $this->mime = $this->imageServer->mime();
        $this->size = $this->imageServer->filesize();
        return $this;
    }


    /**
     * @param string $url
     * @return Image
     */
    public function openFromUrl($url)
    {
        $this->imagePath = tempnam(sys_get_temp_dir(), 'doli');
        $contextOptions = [
            'ssl' => [ 'verify_peer' => false ]
        ];
        $sslContext = stream_context_create($contextOptions);
        copy($url, $this->imagePath, $sslContext);
        $this->open($this->imagePath);

        return $this;
    }


    public function getPath()
    {
        return $this->imagePath;
    }


    public function response()
    {
        return $this->imageServer->response();
    }


    /**
     * @return Image
     */
    public function getSize(){
        $this->size = $this->imageServer->filesize();
        return $this;
    }


    /**
     * @param int $width
     * @param int|bool $height
     * @return Image
     */
    public function crop($width, $height = false){
        if ($height) {
            $this->imageServer->fit($width, $height, function ($constraint) {
                $constraint->upsize();
            });
        } else {
            $this->imageServer->fit($width, function ($constraint) {
                $constraint->upsize();
            });
        }
        return $this;
    }


    /**
     * @param int $width
     * @param int $height
     * @param bool $upscale
     * @return Image
     */
    public function resize($width, $height){
        $this->imageServer->resize($width, $height, function ($constraint){
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        return $this;
    }


    /**
     * @param int $width
     * @param int $height
     * @param string $fillColor
     * @return $this
     */
    public function resizeCanvas($width, $height, $fillColor = '#ffffff')
    {
        $this->imageServer
            ->resizeCanvas($width, $height, 'center', false, $fillColor);
        return $this;
    }


    public function canvas($width, $height, $background)
    {
        $this->imageServer->canvas($width, $height, $background);
        return $this;
    }


    public function widen($width)
    {
        $this->imageServer->widen($width);
        return $this;
    }

    public function heighten($height)
    {
        $this->imageServer->heighten($height);
        return $this;
    }

    public function setWatermark(){

    }


    public function save($quality = 80){
        $this->imageServer->save($this->imagePath, $quality);
        return $this;
    }



}