<?php
/**
 * Novactive Collection.
 *
 * @author    Luke Visinoni <l.visinoni@novactive.us, luke.visinoni@gmail.com>
 * @author    Sébastien Morel <s.morel@novactive.us, morel.seb@gmail.com>
 * @copyright 2017 Novactive
 * @license   MIT
 */

namespace Novactive\Tests;

use ArrayIterator;
use Faker\Factory;
use Novactive\Collection\Factory as NovaCollectionFactory;
use PHPUnit_Framework_TestCase;

/**
 * Class UnitTestCase.
 *
 * This allows us to set up shared fixtures that can be used between test cases...
 */
class UnitTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * @var array
     */
    protected $fixtures = [];

    public function setUp()
    {
        $faker = Factory::create();
        $faker->seed(4231986);

        $this->fixtures['array'] = ['first','second','third'];
        $this->fixtures['assoc'] = ['1st' => 'first','2nd' => 'second','3rd' => 'third'];
        $this->fixtures['digits'] = [];
        $this->fixtures['users']  = [];

        for ($i = 0; $i < 10; $i++) {
            $username                           = $faker->unique()->userName;
            $this->fixtures['digits'][]         = $faker->numberBetween(1, 10000);
            $this->fixtures['users'][$username] = [
                'username'    => $username,
                'email'       => $faker->unique()->safeEmail,
                'remote_addr' => $faker->ipv4,
                'homepage'    => $faker->domainName,
                'firstname'   => $faker->firstName,
                'lastname'    => $faker->lastName,
                'address'     => $faker->streetAddress,
                'city'        => $faker->city,
                'state'       => $faker->state,
                'zipcode'     => $faker->postcode,
                'phone'       => $faker->phoneNumber,
            ];
        }

        $this->fixtures['names']  = array_column($this->fixtures['users'], 'firstname');
        $this->fixtures['emails'] = array_column($this->fixtures['users'], 'email');
        $this->fixtures['phones'] = array_column($this->fixtures['users'], 'phone');
    }

    public function tearDown()
    {
        // nothing to do here...
    }

    /**
     * Returns a recorder that expectes to be "touched" $count times.
     *
     * This allows us to test that a certain thing happened $count number of times.
     *
     * @param int    $count
     * @param string $method
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMethodCallRecorderForCount($count, $method = 'touch')
    {
        $recorder = $this->getMockBuilder(stdClass::class)
            ->setMethods([$method])
            ->getMock();

        $recorder->expects($this->exactly($count))
            ->method($method);

        return $recorder;
    }

    protected function getIteratorForArray(array $arr = [])
    {
        return new ArrayIterator($arr);
    }
}
