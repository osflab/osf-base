<?php
namespace Osf\Stream\TwigLight;

use Osf\Test\Runner as OsfTest;
use Osf\Stream\TwigLight;
use Osf\Crypt\Crypt;

/**
 * Twiglight tests
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 */
class Test extends OsfTest
{
    public static function run()
    {
        self::reset();
        
        // Jeu de données simple
        $template = 'Bonjour {{contact.nom}}, vous avez {{contact.age}} ans.';
        $values = ['contact' => ['nom' => 'Guillaume Ponçon', 'age' => 30]];
        $expected = 'Bonjour Guillaume Ponçon, vous avez 30 ans.';
        
        // Utilisation simple sans cache
        $tl = new TwigLight($template);
        self::assertEqual($tl->render($values), $expected);
        
        // Test de performances
        for ($i = 0; $i <= 10; $i++) {
            for ($j = 0; $j <= 100; $j++) {
                $values[Crypt::hash($i)][Crypt::hash($j)] = 'test';
            }
        }
        $template = str_repeat($template, 500);
        $expected = str_repeat($expected, 500);
        $time = microtime(true);
        self::assertEqual(TwigLight::quickRender($template, $values), $expected);
        $duration = round((microtime(true) - $time) * 1000);
        self::assert($duration < 20);
        
        return self::getResult();
    }
}