<?php

namespace App\Services;

class Template_services
{
    private $template_params;

    public function __construct()
    {
        $this->template_params = array();
    }

    // Sets the content of a template position
    public function set(string $position, $data, $append = true)
    {
        if (!isset($this->template_params[$position]) || $append === false) {
            $this->template_params[$position] = $data;
        } else {
            $this->template_params[$position] .= $data;
        }
    }

    // Gets the content of a template position
    public function get(string $position)
    {
        if (isset($this->template_params[$position])) {
            return $this->template_params[$position];
        } else {
            return '';
        }
    }

    // Sets the title of the page
    public function title(string $title = '')
    {
        $this->template_params['title'] = $title;
    }

    // Sets the breadcrumbs of the page
    public function breadcrumbs(array $breadcrumbs = [])
    {
        $this->template_params['breadcrumbs'] = $breadcrumbs;
    }

    // Sets the content of the page
    public function content(string $view, array $params = array(), string $position = 'content', bool $append = true)
    {
        $params = array_merge($params, $this->template_params);
        $data = view($view, $params, ['saveData' => true]);
        $this->set($position, $data, $append);
    }

    // Displays the page using the parameters preset using the set methods of this class
    public function show(string $template_view = 'template', bool $return_string = true)
    {
        $complete_page = view($template_view, $this->template_params, ['saveData' => true]);

        if ($return_string == true) {
            echo $complete_page;
        } else {
            return $complete_page;
        }
    }

    public function blank(string $view, array $params = array())
    {
        return view($view, $params);
    }
}
