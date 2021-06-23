<?php

namespace Kanboard\Helper;

use Kanboard\Core\Base;

/**
 * Application Helper
 *
 * @package helper
 * @author  Frederic Guillot
 */
class AppHelper extends Base
{
    public function tooltipMarkdown($markdownText, $icon = 'fa-info-circle')
    {
        return '<span class="tooltip"><i class="fa '.$icon.'"></i><script type="text/template"><div class="markdown">'.$this->helper->text->markdown($markdownText).'</div></script></span>';
    }

    public function tooltipHTML($htmlText, $icon = 'fa-info-circle')
    {
        return '<span class="tooltip"><i class="fa '.$icon.'"></i><script type="text/template"><div class="markdown">'.$htmlText.'</div></script></span>';
    }

    public function tooltipLink($label, $link)
    {
        return '<span class="tooltip" data-href="'.$link.'">'.$label.'</span>';
    }

    public function getToken()
    {
        return $this->token;
    }

    public function isAjax()
    {
        return $this->request->isAjax();
    }

    /**
     * Render Javascript component
     *
     * @param  string $name
     * @param  array  $params
     * @return string
     */
    public function component($name, array $params = array())
    {
        return '<div class="js-'.$name.'" data-params=\''.json_encode($params, JSON_HEX_APOS).'\'></div>';
    }

    /**
     * Get config variable
     *
     * @access public
     * @param  string $param
     * @param  mixed  $default
     * @return mixed
     */
    public function config($param, $default = '')
    {
        return $this->configModel->get($param, $default);
    }

    /**
     * Make sidebar menu active
     *
     * @access public
     * @param  string $controller
     * @param  string $action
     * @param  string $plugin
     * @return string
     */
    public function checkMenuSelection($controller, $action = '', $plugin = '')
    {
        $result = strtolower($this->getRouterController()) === strtolower($controller);

        if ($result && $action !== '') {
            $result = strtolower($this->getRouterAction()) === strtolower($action);
        }

        if ($result && $plugin !== '') {
            $result = strtolower($this->getPluginName()) === strtolower($plugin);
        }

        return $result ? 'class="active"' : '';
    }

    /**
     * Get plugin name from route
     *
     * @access public
     * @return string
     */
    public function getPluginName()
    {
        return $this->router->getPlugin();
    }

    /**
     * Get router controller
     *
     * @access public
     * @return string
     */
    public function getRouterController()
    {
        return $this->router->getController();
    }

    /**
     * Get router action
     *
     * @access public
     * @return string
     */
    public function getRouterAction()
    {
        return $this->router->getAction();
    }

    /**
     * Get javascript language code
     *
     * @access public
     * @return string
     */
    public function jsLang()
    {
        return $this->languageModel->getJsLanguageCode();
    }

    /**
     * Get date format for Jquery DatePicker
     *
     * @access public
     * @return string
     */
    public function getJsDateFormat()
    {
        $format = $this->dateParser->getUserDateFormat();
        $format = str_replace('m', 'mm', $format);
        $format = str_replace('Y', 'yy', $format);
        $format = str_replace('d', 'dd', $format);

        return $format;
    }

    /**
     * Get time format for Jquery Plugin DateTimePicker
     *
     * @access public
     * @return string
     */
    public function getJsTimeFormat()
    {
        $format = $this->dateParser->getUserTimeFormat();
        $format = str_replace('H', 'HH', $format);
        $format = str_replace('i', 'mm', $format);
        $format = str_replace('g', 'h', $format);
        $format = str_replace('a', 'tt', $format);

        return $format;
    }

    /**
     * Get current timezone
     *
     * @access public
     * @return string
     */
    public function getTimezone()
    {
        return $this->timezoneModel->getCurrentTimezone();
    }

    /**
     * Get session flash message
     *
     * @access public
     * @return string
     */
    public function flashMessage()
    {
        $success_message = $this->flash->getMessage('success');
        $failure_message = $this->flash->getMessage('failure');

        if (! empty($success_message)) {
            return '<div class="alert alert-success alert-fade-out">'.$this->helper->text->e($success_message).'</div>';
        }

        if (! empty($failure_message)) {
            return '<div class="alert alert-error">'.$this->helper->text->e($failure_message).'</div>';
        }

        return '';
    }

    /**
     * Chech if the given language is a Right-to-Left Language
     *
     * @access public
     * @param  string $lang
     * @return boolean
     */
    public function isRTL(String $lang = '')
    {
        $rtl_languages_list = [
            'ar',
            'arc',
            'dv',
            'fa',
            'ha',
            'he',
            'khw',
            'ks',
            'ku',
            'ps',
            'ur',
            'yi'
        ];

        return in_array($lang, $rtl_languages_list);
    }
}
