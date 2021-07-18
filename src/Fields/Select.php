<?php declare(strict_types=1);

namespace KiryaDev\Admin\Fields;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Select extends FieldElement
{
    /** @var iterable|callable */
    protected $options = [];

    protected bool $addNullOption = false;

    protected function boot(): void
    {
        $this->displayUsing(function (Model $object, $value) {
            return $this->options[$value] ?? $value;
        });
    }

    final public function options($options)
    {
        $this->options = $options;

        return $this;
    }

    final public function rangeOptions($from, $to, $step = 1)
    {
        return $this->options(array_combine($a = range($from, $to, $step), $a));
    }

    final public function addNullOption()
    {
        $this->addNullOption = true;

        return $this;
    }

    /**
     * @internal
     */
    final public function getOptions(Model $object): iterable
    {
        $options = \is_callable($fn = $this->options) ? $fn($object) : $this->options;

        if ($options instanceof Collection) {
            $options = $options->all();
        }

        if ($this->addNullOption) {
            $options = [null => '—'] + $options;
        }

        return $options;
    }
}
