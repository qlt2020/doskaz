<?php
declare(strict_types=1);

namespace App\Blog;

use Goodwix\DoctrineJsonOdm\Annotation\ODM;

/**
 * @ODM()
 */
final class Image
{
    /**
     * @var string
     */
    public $image;

    /**
     * @var array
     */
    public $cropData = [];

    /**
     * @var string
     */
    public $name;

    private function initPipeline()
    {
        if ($this->cropData) {
            return [
                [
                    'operation' => 'extract',
                    'params' => [
                        'quality' => 100,
                        'top' => $this->cropData['y'],
                        'left' => $this->cropData['x'],
                        'areawidth' => $this->cropData['width'],
                        'areaheight' => $this->cropData['height'],
                    ]
                ]
            ];
        }
        return [];
    }

    public function link()
    {
        $pipeline = $this->initPipeline();
        if (count($pipeline)) {
            return "/pipeline?" . http_build_query([
                    'file' => $this->image,
                    'operations' => json_encode($pipeline),
                ]);
        }
        return $this->image;
    }

    public function fit(int $width, int $height)
    {
        $opts = [
            '%signature' => 'test',
            '%processing_options' => "resize:fit:$width:$height:no:0",
            '%source_url' => "local:///{$this->image}"
        ];

        return str_replace(
            array_keys($opts),
            array_values($opts),
            '/img/%signature/%processing_options/plain/%source_url@jpg'
        );

        /*$pipeline = $this->initPipeline();
        $pipeline[] = [
            'operation' => 'fit',
            'params' => [
                'width' => $width,
                'height' => $height
            ]
        ];
        return "/pipeline?" . http_build_query([
                'file' => $this->image,
                'operations' => json_encode($pipeline),
            ]);*/
    }

    public function resize(int $width, ?int $height = null)
    {
        $h = $height ?? 0;

        $opts = [
            '%signature' => 'test',
            '%processing_options' => "resize:fit:$width:$h:no:0",
            '%source_url' => "local:///{$this->image}"
        ];

        if ($this->cropData) {
            $opts['%processing_options'] .= '/crop:'.round($this->cropData['width']).':'.round($this->cropData['height']).':nowe:'.$this->cropData['x'].':'.$this->cropData['y'];
        }

        return str_replace(
            array_keys($opts),
            array_values($opts),
            '/img/%signature/%processing_options/plain/%source_url@jpg'
        );

        $pipeline = $this->initPipeline();

        $operation = [
            'operation' => 'resize',
            'params' => [
                'quality' => 100,
                'width' => $width,
            ]
        ];

        if ($height) {
            $operation['params']['height'] = $height;
        }

        $pipeline[] = $operation;
        return "/pipeline?" . http_build_query([
                'file' => $this->image,
                'operations' => json_encode($pipeline),
            ]);
    }
}
