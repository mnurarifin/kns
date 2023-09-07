<?php

namespace App\Libraries;

use App\Libraries\Securimage\Securimage;
use App\Libraries\Securimage\Securimage_Color;

class Captcha extends Securimage
{

    public function generate_image($arr_config = array())
    {
        $background_image = '';

        if (is_array($arr_config)) {
            foreach ($arr_config as $key => $value) {
                if (isset($this->$key)) {
                $this->$key = $value;
                }
            }

            if (array_key_exists('background_image', $arr_config)) {
                $background_image = __DIR__ . '/Securimage/bg/' . $arr_config['background_image'];
            }
        }

        $this->ttf_file = __DIR__ . '/Securimage/fonts/mangalb.ttf';
        $this->charset = 'abcdefghkmnprstuvwyz';
        $this->code_length = rand(4, 4);
        $this->use_transparent_text = true;
        $this->text_transparency_percentage = 40;
        $this->text_angle_minimum = 0;
        $this->text_angle_maximum = 10;
        $this->perturbation = 0;
        $this->iscale = 10;
        $this->num_lines = 0;
        $this->use_multi_text = true;

        if (!isset($arr_config['multi_text_color'])) {
            $this->multi_text_color = array(
                new Securimage_Color("#3776c3"),
                new Securimage_Color("#56bebe"),
                new Securimage_Color("#e15a5a"),
                new Securimage_Color("#c4a137"),
                new Securimage_Color("#4cc843"),
            );
        }

        $this->show($background_image);
    }

    public function verify($string = '')
    {
        return $this->check($string);
    }
}
