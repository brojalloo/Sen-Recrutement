<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * Onze pages imposaient leur fond en clair via un attribut
 * `style="background: linear-gradient(… #f9fafb …)"`.
 *
 * Un style inline l'emporte sur toute feuille : en thème sombre, le fond
 * restait donc clair pendant que `theme.css` passait les titres en clair.
 * Résultat, du texte quasi blanc sur un fond quasi blanc — illisible.
 *
 * Le fond doit venir d'une classe, pour que le thème puisse le redéfinir.
 */
class DarkThemeContrastTest extends TestCase
{
    /**
     * @return list<string>
     */
    private function bladeFiles(): array
    {
        $files = [];

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator(resource_path('views'))
        );

        foreach ($iterator as $file) {
            if ($file->isFile() && str_ends_with($file->getFilename(), '.blade.php')) {
                $files[] = $file->getPathname();
            }
        }

        return $files;
    }

    public function test_no_view_hardcodes_a_light_page_background(): void
    {
        $offenders = [];

        foreach ($this->bladeFiles() as $file) {
            $contents = (string) file_get_contents($file);

            if (preg_match('/style="[^"]*background:[^"]*#f9fafb/i', $contents)) {
                $offenders[] = str_replace(resource_path('views').DIRECTORY_SEPARATOR, '', $file);
            }
        }

        $this->assertSame(
            [],
            $offenders,
            "Ces vues figent un fond clair en style inline, que le thème sombre ne peut pas surcharger :\n".
            implode("\n", $offenders)
        );
    }

    public function test_the_shared_page_surface_follows_the_theme(): void
    {
        $css = (string) file_get_contents(resource_path('css/layout.css'));

        $this->assertStringContainsString('.page-surface', $css);
        $this->assertMatchesRegularExpression(
            '/\[data-theme="dark"\]\s+\.page-surface/',
            $css,
            'La surface de page doit avoir une déclinaison sombre.'
        );
    }

    public function test_the_light_utility_backgrounds_flip_in_dark_mode(): void
    {
        $css = (string) file_get_contents(resource_path('css/theme.css'));

        // Le theme passe .text-dark en clair ; sans ces deux regles, un
        // `badge bg-light text-dark` sort en clair sur clair.
        $this->assertMatchesRegularExpression('/\[data-theme="dark"\]\s+\.bg-light/', $css);
        $this->assertMatchesRegularExpression('/\[data-theme="dark"\]\s+\.bg-white/', $css);
    }
}
