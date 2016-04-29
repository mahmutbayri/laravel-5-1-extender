<?php

namespace Bayri\LaravelExtender\Blade;

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
     * @param BladeCompiler $bladeCompiler
     */
    public function __construct(BladeCompiler $bladeCompiler)
    {
        $this->bladeCompiler = $bladeCompiler;
    }

    public function generate()
    {
        $directives = [
            /**
             * Compile the break statements into valid PHP.
             */
            'break' => function ($expression) {
                return $expression ? "<?php if{$expression} break; ?>" : '<?php break; ?>';
            },
            /**
             * Compile the continue statements into valid PHP.
             */
            'continue' => function ($expression) {
                return $expression ? "<?php if{$expression} continue; ?>" : '<?php continue; ?>';
            },
            /**
             * Compile the else-can statements into valid PHP.
             */
            'elsecan' => function ($expression) {
                return "<?php elseif (app('Illuminate\\Contracts\\Auth\\Access\\Gate')->check{$expression}): ?>";
            },
            /**
             * Compile the else-can statements into valid PHP.
             */
            'elsecannot' => function ($expression) {
                return "<?php elseif (app('Illuminate\\Contracts\\Auth\\Access\\Gate')->denies{$expression}): ?>";
            },
            /**
             * Compile the has section statements into valid PHP.
             */
            'hasSection' => function ($expression) {
                return "<?php if (! empty(trim(\$__env->yieldContent{$expression}))): ?>";
            },
            /**
             * Compile the raw PHP statements into valid PHP.
             */
            'php' => function ($expression) {
                return $expression ? "<?php {$expression}; ?>" : '<?php ';
            },
            /**
             * Compile end-php statement into valid PHP.
             */
            'endphp' => function ($expression) {
                return ' ?>';
            },
            /**
             * Compile the unset statements into valid PHP.
             */
            'unset' => function ($expression) {
                return "<?php unset{$expression}; ?>";
            },
        ];

        foreach ($directives as $directiveName => $callable) {
            $this->bladeCompiler->directive($directiveName, $callable);
        }
    }
}
