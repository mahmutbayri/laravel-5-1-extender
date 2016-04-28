<?php

namespace Bayri\LaravelExtender\Blade;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\View\Compilers\BladeCompiler;

class DirectiveGenerator
{
    /**
     * The application instance.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * @var BladeCompiler
     */
    protected $bladeCompiler;

    /**
     * @param Application $app
     */
    public function __construct(BladeCompiler $bladeCompiler)
    {
        $this->bladeCompiler = $bladeCompiler;
    }

    public function generate()
    {
        $directives = [
            'break' => function ($expression) {
                return $expression ? "<?php if{$expression} break; ?>" : '<?php break; ?>';
            },
            'continue' => function ($expression) {
                return $expression ? "<?php if{$expression} continue; ?>" : '<?php continue; ?>';
            },
        ];

        foreach ($directives as $directiveName => $callable) {
            $this->bladeCompiler->directive($directiveName, $callable);
        }
    }
}
