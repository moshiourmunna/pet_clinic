<?php

namespace App\Models\Concerns;

/**
 * Bilingual columns: for each base name in $translatable the table has {name}_en and {name}_bn.
 * $model->name returns the current locale's value and falls back to the other language when empty,
 * so content entered in only one language is still shown.
 */
trait HasTranslations
{
    public function getAttribute($key)
    {
        if (in_array($key, $this->translatable ?? [], true)) {
            return $this->translate($key);
        }

        return parent::getAttribute($key);
    }

    public function translate(string $field, ?string $locale = null): mixed
    {
        $locale = $locale ?: app()->getLocale();
        $other = $locale === 'bn' ? 'en' : 'bn';
        $value = parent::getAttribute("{$field}_{$locale}");

        return $this->filled($value) ? $value : parent::getAttribute("{$field}_{$other}");
    }

    /** True when both languages are provided for this field. */
    public function hasBothLanguages(string $field): bool
    {
        return $this->filled(parent::getAttribute("{$field}_en")) && $this->filled(parent::getAttribute("{$field}_bn"));
    }

    private function filled(mixed $v): bool
    {
        return is_array($v) ? count(array_filter($v)) > 0 : trim((string) $v) !== '';
    }
}
