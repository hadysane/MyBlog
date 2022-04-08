<?php 

namespace App\Twig;

use Twig\TwigFunction;
use Symfony\Component\Intl\Locales;
use Twig\Extension\AbstractExtension;

class Application extends AbstractExtension {
    
    private $_localeCodes;
    private $_locales;

    public function __construct($locales, $defaultLocale) {
        $localeCodes =explode('|', $locales); 
        sort($localeCodes);
        $this->_localeCodes = $localeCodes; 
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('locales', [$this, 'getLocales']),
        ];
    }


    public function getLocales(){
        $this->_locales = [];
        foreach ($this->_localeCodes as $localCode) {
            $this->_locales[] =[
                'code' => $localCode, 
                'name' => Locales::getName($localCode)
            ];
        }

        return $this->_locales;
    }
}