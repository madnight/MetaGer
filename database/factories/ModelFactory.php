<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
 */

$factory->define(app\Models\parserSkripte\Base::class, function (Faker\Generator $faker) {
    return [
        "provider"              => null,
        "titel"                 => "Hotels in L'Alpe-d'Huez",
        "link"                  => "http://r.search.yahoo.com/cbclk/dWU9QUIxMzdDQTVGRUE1NDk4QiZ1dD0xNDc2NDMwMTM0MDY3JnVvPTUzNjEzNzI2OTYmbHQ9MiZlcz01LmtfeFdJR1BTOWpkdU95/RV=2/RE=1476458934/RO=10/RU=http%3a%2f%2f1616540.r.msn.com%2f%3fld%3dd3L21YyadK5JKikZbdYGL_WTVUCUwdKsA4ixeD_Iy-fyOol49ZUDwtvNaQrgo9_hrBZE29ZA8_kcVZvUh8zA3VBZsCAZK8rx10IhFt_RT45dthIWzATMhQpwXLvlSEiWjbX3oR-mghf0LQjcyYIqYSxSurjZ0%26u%3dhttp%253a%252f%252fwww.booking.com%252fcity%252ffr%252fl-alpe-d-huez.de.html%253faid%253d349014%2526label%253dmsn-w0pcQaojEix%2al5%2ajWskE5Q-5361372696%2526utm_campaign%253dFrance%2526utm_medium%253dcpc%2526utm_source%253dbing%2526utm_term%253dw0pcQaojEix%2al5%2ajWskE5Q/RK=0/RS=OWBpMcIG_XjwGHTMqrfc7GScvPg-",
        "anzeigeLink"           => "www.booking.com",
        "descr"                 => "Schnell und sicher ein Hotel buchen. Alle Hotels mit Spezial-Angeboten.",
        "gefVon"                => "Yahoo",
        "sourceRank"            => 0,
        "partnershop"           => false,
        "image"                 => "",
        "proxyLink"             => "https://proxy.suma-ev.de/cgi-bin/nph-proxy.cgi/en/I0/http/r.search.yahoo.com/cbclk/dWU9QUIxMzdDQTVGRUE1NDk4QiZ1dD0xNDc2NDMwMTM0MDY3JnVvPTUzNjEzNzI2OTYmbHQ9MiZlcz01LmtfeFdJR1BTOWpkdU95/RV=2/RE=1476458934/RO=10/RU=http%3a%2f%2f1616540.r.msn.com%2f%3fld%3dd3L21YyadK5JKikZbdYGL_WTVUCUwdKsA4ixeD_Iy-fyOol49ZUDwtvNaQrgo9_hrBZE29ZA8_kcVZvUh8zA3VBZsCAZK8rx10IhFt_RT45dthIWzATMhQpwXLvlSEiWjbX3oR-mghf0LQjcyYIqYSxSurjZ0%26u%3dhttp%253a%252f%252fwww.booking.com%252fcity%252ffr%252fl-alpe-d-huez.de.html%253faid%253d349014%2526label%253dmsn-w0pcQaojEix%2al5%2ajWskE5Q-5361372696%2526utm_campaign%253dFrance%2526utm_medium%253dcpc%2526utm_source%253dbing%2526utm_term%253dw0pcQaojEix%2al5%2ajWskE5Q/RK=0/RS=OWBpMcIG_XjwGHTMqrfc7GScvPg-",
        "engineBoost"           => 1.2,
        "valid"                 => true,
        "host"                  => "r.search.yahoo.com",
        "strippedHost"          => "booking.com",
        "strippedDomain"        => "booking.com",
        "strippedLink"          => "booking.com",
        "rank"                  => 0,
        "price"                 => 0,
        "additionalInformation" => [],
    ];
});
