<?php

namespace App\Filament\Forms\Components;

use Filament\Forms\Components\Field;

class BootstrapIconPicker extends Field
{
    protected string $view = 'filament.forms.components.bootstrap-icon-picker';

    /**
     * @var array<string, string>
     */
    protected array $icons = [];

    /**
     * @param  array<string, string>  $icons  key=value bi-class; value=label (optional)
     */
    public function icons(array $icons): static
    {
        $this->icons = $icons;

        return $this;
    }

    /**
     * @return array<string, string>
     */
    public function getIcons(): array
    {
        return $this->evaluate($this->icons);
    }
}
