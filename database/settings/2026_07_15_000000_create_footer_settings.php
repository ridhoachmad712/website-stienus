<?php

declare(strict_types=1);

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('footer.show_description', true);

        $this->migrator->add('footer.show_links_column', true);
        $this->migrator->add('footer.links_column_title', 'Tautan');
        $this->migrator->add('footer.use_custom_links', false);
        $this->migrator->add('footer.custom_links', []);

        $this->migrator->add('footer.show_contact_column', true);
        $this->migrator->add('footer.contact_column_title', 'Kontak');

        $this->migrator->add('footer.show_social_column', true);
        $this->migrator->add('footer.social_column_title', 'Ikuti Kami');

        $this->migrator->add('footer.extra_columns', []);
    }
};
