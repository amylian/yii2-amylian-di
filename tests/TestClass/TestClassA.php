

namespace Amylian\Yii\DI\Test\TestClass;

/**
 * Description of TeastClassA
 *
 * @author Andreas Prucha, Abexto - Helicon Software Development <andreas.prucha@gmail.com>
 */
class TestClassA
        implements TestClassAInterface
{

    public $propString;
    public $propClassB;
    public $propArray;
    private $propOptString;

    public function __construct(String $paramString, TestClassB $paramClassB, Array $paramArray, ?String $paramOptString = null)
    {
        $this->propString = $paramString;
        $this->propClassB = $paramClassB;
        $this->propArray = $paramArray;
        $this->setPropOptString($paramOptString);
    }

    public function setPropOptString($value)
    {
        $this->propOptString = $value;
    }

    public function getPropOptString()
    {
        return $this->propOptString;
    }

}
