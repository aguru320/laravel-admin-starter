<?php

use App\Models\PageContent;
use App\Models\PageGallery;
use App\Models\PageMedia;
use App\Models\PageSection;
use App\Models\Photo;
use Illuminate\Database\Seeder;
use App\Models\Page;

class PageTableSeeder extends Seeder
{
    /**
     * @param \Faker\Generator $faker
     */
    public function run(Faker\Generator $faker)
    {
        Page::truncate();
        PageMedia::truncate();
        PageSection::truncate();
        PageContent::truncate();
        PageGallery::truncate();

        $csvPath = database_path() . '\seeds\csv\\' . 'pages.csv';
        $items = csv_to_array($csvPath);

        foreach ($items as $key => $item) {
            $row = Page::create([
                'id'            => $item['id'],
                'name'          => $item['name'],
                'title'         => $item['title'],
                'description'   => $item['description'],
                'slug'          => $item['slug'],
                'url'           => $item['url'],
                'icon'          => $item['icon'],
                'is_header'     => $item['is_header'],
                'header_order'  => intval($item['header_order']),
                'is_footer'     => $item['is_footer'],
                'footer_order'  => intval($item['footer_order']),
                'is_hidden'     => $item['is_hidden'],
                'is_featured'   => intval($item['is_featured']),
                'parent_id'     => $item['parent_id'],
                'url_parent_id' => $item['url_parent_id'],
                'views'         => 0,
                'social_shares' => 0,
                'created_by'    => 1,
                'updated_by'    => 1,
            ]);
        }

        /*$items = Page::all();
        $items->map(function ($item) use ($faker) {
            for ($i = 0; $i < $faker->numberBetween(2, 4); $i++) {

                $component = PageContent::create([
                    'heading'         => $faker->sentence(2),
                    'heading_element' => 'h2',
                    'content'         => $faker->paragraph(3),
                ]);

                $item->attachComponent($component);
            }
        });*/

        // only generate content for the following pages

        $page = Page::where('name', 'Privacy Policy')->first();
        $this->pageContent($page, $faker);
        $this->pageContent($page, $faker);
        $this->pageContent($page, $faker);

        $page = Page::where('name', 'Terms and Conditions')->first();
        $this->pageContent($page, $faker);
        $this->pageContent($page, $faker);
        $this->pageContent($page, $faker);

        $page = Page::where('name', 'About Us')->first();
        $this->pageContent($page, $faker);
        $this->pageContent($page, $faker);
        $this->pageContent($page, $faker);

        $page = Page::where('name', 'Content Only')->first();
        $this->pageContent($page, $faker);
        $this->pageContent($page, $faker);
        $this->pageContent($page, $faker);
        $this->pageContent($page, $faker);

        $page = Page::where('name', 'Content and Photo')->first();
        $this->pageContent($page, $faker);
        $this->pageMedia($page, $faker);
        $this->pageMedia($page, $faker, 'right');
        $this->pageContent($page, $faker);

        $page = Page::where('name', 'Content and Gallery')->first();
        $this->pageContent($page, $faker);
        $this->pageMedia($page, $faker);
        $this->pageContent($page, $faker);
        $this->pageGallery($page, $faker);
    }

    private function pageContent($page, $faker)
    {
        $component = PageContent::create([
            'heading'         => $faker->sentence(2),
            'heading_element' => 'h2',
            'content'         => "<p>{$faker->paragraph(5)}</p>",
        ]);

        $page->attachComponent($component);
    }

    private function pageMedia($page, $faker, $align = 'left')
    {
        $component = PageMedia::create([
            'heading'         => $faker->sentence(2),
            'heading_element' => 'h2',
            'media'           => 'square.png',
            'media_align'     => $align,
            'content'         => "<p>{$faker->paragraph(5)}</p>",
        ]);

        $page->attachComponent($component);
    }

    private function pageGallery($page, $faker)
    {
        $component = PageGallery::create([
            'heading'         => $faker->sentence(2),
            'heading_element' => 'h2',
            'content'         => "<p>{$faker->paragraph(5)}</p>",
        ]);

        for ($i = 0; $i < 10; $i++) {
            $photo = Photo::create([
                'filename'       => 'gallery.png',
                'photoable_id'   => $component->id,
                'photoable_type' => get_class($component),
                'name'           => 'Photo Name',
            ]);
        }

        $page->attachComponent($component);
    }
}