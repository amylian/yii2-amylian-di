<?php


namespace Amylian\Yii\DI\Test\Unit;

/**
 * Description of ContainerTest
 *
 * @author Andreas Prucha, Abexto - Helicon Software Development <andreas.prucha@gmail.com>
 */
class ContainerTest
        extends \yiiunit\framework\di\ContainerTest
{

    public function testNumberedParameters()
    {
        $defaultPropString = 'DefaultString';
        $defaultPropArray = ['Default1', 'Default2'];
        $overridePropString = 'OverridePropString';
        $overridePropArray = ['Override1', 'Override2'];


        $container = new \Amylian\Yii\DI\Container(); /* @var \Amylian\Yii\DI\Container $container */
        $container->setDefinitions([\Amylian\Yii\DI\Test\TestClass\TestClassA::class =>
            [
                [\Amylian\Yii\DI\Test\TestClass\TestClassA::class],
                [
                    0 => $defaultPropString,
                    2 => $defaultPropArray
                ]
            ]
        ]);
        $container->setSingletons([
            'test\TestClassA' => [
                ['class' => \Amylian\Yii\DI\Test\TestClass\TestClassA::class],
                [
                    0 => $overridePropString,
                    2 => $overridePropArray
                ]
            ],
        ]);
        $container->setSingletons([]);

        $o1 = $container->get('test\TestClassA');
        $o2 = $container->get('test\TestClassA');
        $this->assertSame($o1, $o2);

        $this->assertEquals($overridePropString, $o1->propString);
        $this->assertEquals($overridePropArray, $o1->propArray);
        $this->assertInstanceOf(\Amylian\Yii\DI\Test\TestClass\TestClassB::class, $o1->propClassB);
    }

    public function testNamedParameters()
    {
        $defaultPropString = 'DefaultString';
        $defaultPropArray = ['Default1', 'Default2'];
        $defaultOptString = 'DefaultOptString';
        $overridePropString = 'OverridePropString';
        $overridePropArray = ['Override1', 'Override2'];
        $overrideOptString = 'OverrideOptString';


        $container = new \Amylian\Yii\DI\Container(); /* @var \Amylian\Yii\DI\Container $container */
        $container->setDefinitions([\Amylian\Yii\DI\Test\TestClass\TestClassA::class =>
            [
                [\Amylian\Yii\DI\Test\TestClass\TestClassA::class],
                [
                    0 => $defaultPropString,
                    'paramArray' => $defaultPropArray
                ]
            ]
        ]);
        $container->setSingletons([
            'test\TestClassA' => [
                ['class' => \Amylian\Yii\DI\Test\TestClass\TestClassA::class,
                    'setPropOptString()' => [$overrideOptString]
                ],
                [
                    'paramString' => $overridePropString,
                    2 => $overridePropArray
                ]
            ],
        ]);
        $container->setSingletons([]);

        $o1 = $container->get('test\TestClassA');
        $o2 = $container->get('test\TestClassA');
        $this->assertSame($o1, $o2);

        $this->assertEquals($overridePropString, $o1->propString);
        $this->assertEquals($overridePropArray, $o1->propArray);
        $this->assertEquals($overrideOptString, $o1->getPropOptString());
        $this->assertInstanceOf(\Amylian\Yii\DI\Test\TestClass\TestClassB::class, $o1->propClassB);
    }

}
