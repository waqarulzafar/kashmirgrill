<?php

namespace Database\Seeders;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MenuRarCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $imports = [
            [
                'name' => 'Antipasti',
                'slug' => 'antipasti',
                'folder' => 'assets/images/menu/antipasti',
                'base_price' => 6.90,
                'tags' => 'Starter, Imported',
            ],
            [
                'name' => 'Griglia',
                'slug' => 'griglia',
                'folder' => 'assets/images/menu/griglia',
                'base_price' => 13.90,
                'tags' => 'Grill, Imported',
            ],
            [
                'name' => 'Primi Piati',
                'slug' => 'primi-piati',
                'folder' => 'assets/images/menu/primi-piati',
                'base_price' => 14.90,
                'tags' => 'Main Course, Imported',
            ],
        ];

        foreach ($imports as $config) {
            $category = MenuCategory::query()->updateOrCreate(
                ['slug' => $config['slug']],
                ['name' => $config['name']]
            );

            $absoluteFolder = public_path($config['folder']);
            if (!File::isDirectory($absoluteFolder)) {
                continue;
            }

            $files = collect(File::files($absoluteFolder))
                ->filter(function (\SplFileInfo $file): bool {
                    return in_array(strtolower($file->getExtension()), ['jpg', 'jpeg', 'png', 'webp'], true);
                })
                ->sortBy(fn (\SplFileInfo $file) => strtolower($file->getFilename()))
                ->values();

            foreach ($files as $index => $file) {
                $filename = $file->getFilename();
                $basename = pathinfo($filename, PATHINFO_FILENAME);
                $name = Str::of($basename)
                    ->replace(['-', '_'], ' ')
                    ->squish()
                    ->title()
                    ->toString();

                $price = number_format($config['base_price'] + ($index * 0.40), 2, '.', '');
                $description = sprintf(
                    '%s from our %s selection, prepared with authentic house spices.',
                    $name,
                    $config['name']
                );

                MenuItem::query()->updateOrCreate(
                    [
                        'menu_category_id' => $category->id,
                        'name' => $name,
                    ],
                    [
                        'description' => $description,
                        'price' => $price,
                        'tags' => $config['tags'],
                        'image_path' => $config['folder'] . '/' . $filename,
                        'is_available' => true,
                    ]
                );
            }
        }
    }
}
