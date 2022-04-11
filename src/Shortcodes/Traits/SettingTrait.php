<?php

namespace Moovly\Shortcodes\Traits;


trait SettingTrait
{

    public $localeSettingKey = 'settings.locale';
    public $defaultLocale = 'en';


    public function saveUpdateSetting($key, $value)
    {
        update_option($key, $value);
    }

    public function getAllSettings()
    {
        return [
            'locale' => $this->getSetting($this->localeSettingKey, $this->defaultLocale)
        ];
    }


    public function getSetting($key, $default)
    {
        if (get_option($key)) {
            return get_option($key);
        }
        return $default;
    }
}