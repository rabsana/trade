<?php

namespace Rabsana\Trade\Tests\Unit;

use Rabsana\Trade\Helpers\Json;
use Rabsana\Trade\Tests\TestCase;
use stdClass;

class JsonHelpersUnitTest extends TestCase
{

    public function test_is_json_method_works_correctly()
    {
        $int = 5;
        $array = ['fo' => 'bar'];
        $string = 'fo bar';
        $object = new stdClass;
        $object->fo = 'bar';
        $null = null;
        $boolean = true;

        $this->assertEquals(Json::is($int), false);
        $this->assertEquals(Json::is($array), false);
        $this->assertEquals(Json::is($string), false);
        $this->assertEquals(Json::is($object), false);
        $this->assertEquals(Json::is($null), false);
        $this->assertEquals(Json::is($boolean), false);

        $this->assertEquals(Json::is(json_encode($int)), true);
        $this->assertEquals(Json::is(json_encode($array)), true);
        $this->assertEquals(Json::is(json_encode($string)), true);
        $this->assertEquals(Json::is(json_encode($object)), true);
        $this->assertEquals(Json::is(json_encode($null)), true);
        $this->assertEquals(Json::is(json_encode($boolean)), true);
    }

    public function test_encode_method()
    {
        $int = 5;
        $array = ['fo' => 'bar'];
        $string = 'fo bar';
        $object = new stdClass;
        $object->fo = 'bar';
        $null = null;
        $boolean = true;

        $this->assertEquals(Json::encode($int), json_encode($int));
        $this->assertEquals(Json::encode($array), json_encode($array));
        $this->assertEquals(Json::encode($string), json_encode($string));
        $this->assertEquals(Json::encode($object), json_encode($object));
        $this->assertEquals(Json::encode($null), json_encode($null));
        $this->assertEquals(Json::encode($boolean), json_encode($boolean));

        $this->assertEquals(Json::encode(Json::encode($array)), json_encode($array));
    }

    public function test_decode_method()
    {
        $int = json_encode(5);
        $array = json_encode(['fo' => 'bar']);
        $string = json_encode('fo bar');
        $object = new stdClass;
        $object->fo = 'bar';
        $object = json_encode($object);
        $null = json_encode(null);
        $boolean = json_encode(true);

        $this->assertEquals(Json::decode($int), json_decode($int));
        $this->assertEquals(Json::decode($array), json_decode($array));
        $this->assertEquals(Json::decode($string), json_decode($string));
        $this->assertEquals(Json::decode($object), json_decode($object));
        $this->assertEquals(Json::decode($null), json_decode($null));
        $this->assertEquals(Json::decode($boolean), json_decode($boolean));

        $this->assertEquals(Json::decode(['fo' => 'bar']), []);
        $this->assertEquals(Json::decode('fo bar'), '');
        $this->assertEquals(Json::decode(5), 0);
    }
}
