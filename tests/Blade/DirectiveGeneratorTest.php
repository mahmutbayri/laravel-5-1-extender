<?php

use Mockery as m;

use Illuminate\View\Compilers\BladeCompiler;

class DirectiveGeneratorTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function testContinueStatementsAreCompiled()
    {
        $compiler = new BladeCompiler($this->getFiles(), __DIR__);
        $generator = with(new \Bayri\LaravelExtender\Blade\DirectiveGenerator($compiler))->generate();

        $string = '@for ($i = 0; $i < 10; $i++)
test
@continue
@endfor';
$expected = '<?php for($i = 0; $i < 10; $i++): ?>
test
<?php continue; ?>
<?php endfor; ?>';

        $this->assertEquals($expected, $compiler->compileString($string));
    }

    public function testBreakStatementsAreCompiled()
    {
        $compiler = new BladeCompiler($this->getFiles(), __DIR__);
        $generator = with(new \Bayri\LaravelExtender\Blade\DirectiveGenerator($compiler))->generate();


        $string = '@for ($i = 0; $i < 10; $i++)
test
@break
@endfor';
        $expected = '<?php for($i = 0; $i < 10; $i++): ?>
test
<?php break; ?>
<?php endfor; ?>';
        $this->assertEquals($expected, $compiler->compileString($string));
    }

    /**
     * @return Illuminate\Filesystem\Filesystem
     */
    protected function getFiles()
    {
        return m::mock('Illuminate\Filesystem\Filesystem');
    }
}