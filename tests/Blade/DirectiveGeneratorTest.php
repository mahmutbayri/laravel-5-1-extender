<?php

use Illuminate\View\Compilers\BladeCompiler;
use Mockery as m;

class DirectiveGeneratorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Illuminate\View\Compilers\BladeCompiler
     */
    protected $compiler;

    public function setUp()
    {
        parent::setUp();

        $this->compiler = new BladeCompiler($this->getFiles(), __DIR__);
        with(new \Bayri\LaravelExtender\Blade\DirectiveGenerator($this->compiler))->generate();
    }

    public function tearDown()
    {
        m::close();
    }

    public function testContinueStatementsAreCompiled()
    {
        $string = '@for ($i = 0; $i < 10; $i++)
test
@continue
@endfor';
        $expected = '<?php for($i = 0; $i < 10; $i++): ?>
test
<?php continue; ?>
<?php endfor; ?>';

        $this->assertEquals($expected, $this->compiler->compileString($string));
    }

    public function testBreakStatementsAreCompiled()
    {
        $string = '@for ($i = 0; $i < 10; $i++)
test
@break
@endfor';
        $expected = '<?php for($i = 0; $i < 10; $i++): ?>
test
<?php break; ?>
<?php endfor; ?>';
        $this->assertEquals($expected, $this->compiler->compileString($string));
    }

    public function testCanStatementsAreCompiled()
    {
        $string = '@can (\'update\', [$post])
breeze
@elsecan(\'delete\', [$post])
sneeze
@endcan';

        $expected = '<?php if (Gate::check(\'update\', [$post])): ?>
breeze
<?php elseif (app(\'Illuminate\Contracts\Auth\Access\Gate\')->check(\'delete\', [$post])): ?>
sneeze
<?php endif; ?>';
        $this->assertEquals($expected, $this->compiler->compileString($string));
    }

    public function testCannotStatementsAreCompiled()
    {
        $string = '@cannot (\'update\', [$post])
breeze
@elsecannot(\'delete\', [$post])
sneeze
@endcannot';

        $expected = '<?php if (Gate::denies(\'update\', [$post])): ?>
breeze
<?php elseif (app(\'Illuminate\Contracts\Auth\Access\Gate\')->denies(\'delete\', [$post])): ?>
sneeze
<?php endif; ?>';
        $this->assertEquals($expected, $this->compiler->compileString($string));
    }

    public function testHasSectionStatementsAreCompiled()
    {
        $string = '@hasSection("section")
breeze
@endif';
        $expected = '<?php if (! empty(trim($__env->yieldContent("section")))): ?>
breeze
<?php endif; ?>';
        $this->assertEquals($expected, $this->compiler->compileString($string));
    }

    public function testPhpStatementsWithExpressionAreCompiled()
    {
        $string = '@php($set = true)';
        $expected = '<?php ($set = true); ?>';
        $this->assertEquals($expected, $this->compiler->compileString($string));
    }

    public function testPhpStatementsWithoutExpressionAreCompiled()
    {
        $string = '@php';
        $expected = '<?php ';
        $this->assertEquals($expected, $this->compiler->compileString($string));
    }

    public function testEndphpStatementsAreCompiled()
    {
        $string = '@endphp';
        $expected = ' ?>';
        $this->assertEquals($expected, $this->compiler->compileString($string));
    }

    /**
     * @return Illuminate\Filesystem\Filesystem
     */
    protected function getFiles()
    {
        return m::mock('Illuminate\Filesystem\Filesystem');
    }
}
