<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230523083349 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Initial OpenMarketplace migration';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE bitbag_open_marketplace_conversation (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, shop_user_id INT DEFAULT NULL, status VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, rejected_listing_url VARCHAR(255) CHARACTER SET utf8mb3 DEFAULT NULL COLLATE `utf8mb3_unicode_ci`, INDEX IDX_A8BC415912469DE2 (category_id), INDEX IDX_A8BC4159A45D93BF (shop_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');

        $this->addSql('CREATE TABLE bitbag_open_marketplace_conversation_category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');

        $this->addSql('CREATE TABLE bitbag_open_marketplace_conversation_message (id INT AUTO_INCREMENT NOT NULL, shop_user_id INT DEFAULT NULL, vendor_user_id INT DEFAULT NULL, admin_user_id INT DEFAULT NULL, conversation_id INT DEFAULT NULL, content VARCHAR(500) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, created_at DATETIME NOT NULL, filename VARCHAR(255) CHARACTER SET utf8mb3 DEFAULT NULL COLLATE `utf8mb3_unicode_ci`, INDEX IDX_47BF407B9AC0396 (conversation_id), INDEX IDX_47BF407BA45D93BF (shop_user_id), INDEX IDX_47BF407BB919EBFA (vendor_user_id), INDEX IDX_47BF407B6352511C (admin_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');

        $this->addSql('CREATE TABLE bitbag_open_marketplace_draft_attribute (id INT AUTO_INCREMENT NOT NULL, product_attribute_id INT DEFAULT NULL, vendor_id INT DEFAULT NULL, type VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, storage_type VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, configuration LONGTEXT CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci` COMMENT \'(DC2Type:array)\', created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, position INT NOT NULL, translatable TINYINT(1) DEFAULT \'1\' NOT NULL, uuid CHAR(36) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci` COMMENT \'(DC2Type:uuid)\', code VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, UNIQUE INDEX UNIQ_3D38CB8FD17F50A6 (uuid), UNIQUE INDEX UNIQ_3D38CB8F3B420C91 (product_attribute_id), INDEX IDX_3D38CB8FF603EE73 (vendor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');

        $this->addSql('CREATE TABLE bitbag_open_marketplace_draft_attribute_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, uuid CHAR(36) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci` COMMENT \'(DC2Type:uuid)\', locale VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, INDEX IDX_DE55497D2C2AC5D3 (translatable_id), UNIQUE INDEX UNIQ_DE55497DD17F50A6 (uuid), UNIQUE INDEX bitbag_open_marketplace_draft_attribute_translation_uniq_trans (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');

        $this->addSql('CREATE TABLE bitbag_open_marketplace_draft_attribute_value (id INT AUTO_INCREMENT NOT NULL, subject_id INT DEFAULT NULL, attribute_id INT DEFAULT NULL, locale_code VARCHAR(255) CHARACTER SET utf8mb3 DEFAULT NULL COLLATE `utf8mb3_unicode_ci`, text_value LONGTEXT CHARACTER SET utf8mb3 DEFAULT NULL COLLATE `utf8mb3_unicode_ci`, boolean_value TINYINT(1) DEFAULT NULL, integer_value INT DEFAULT NULL, float_value DOUBLE PRECISION DEFAULT NULL, datetime_value DATETIME DEFAULT NULL, date_value DATE DEFAULT NULL, json_value LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_bin`, uuid CHAR(36) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci` COMMENT \'(DC2Type:uuid)\', UNIQUE INDEX UNIQ_FF406DE2D17F50A6 (uuid), INDEX IDX_FF406DE223EDC87 (subject_id), INDEX IDX_FF406DE2B6E62EFA (attribute_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');

        $this->addSql('CREATE TABLE bitbag_open_marketplace_product_draft (id INT AUTO_INCREMENT NOT NULL, product_listing_id INT DEFAULT NULL, main_taxon_id INT DEFAULT NULL, shipping_category_id INT DEFAULT NULL, uuid CHAR(36) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci` COMMENT \'(DC2Type:uuid)\', code VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, shipping_required TINYINT(1) NOT NULL, is_verified TINYINT(1) NOT NULL, verified_at DATETIME DEFAULT NULL, published_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, status VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, version_number INT NOT NULL, INDEX IDX_2C636C239E2D1A41 (shipping_category_id), UNIQUE INDEX UNIQ_2C636C23D17F50A6 (uuid), INDEX IDX_2C636C234706C231 (product_listing_id), INDEX IDX_2C636C23731E505 (main_taxon_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');

        $this->addSql('CREATE TABLE bitbag_open_marketplace_product_draft_channels (product_id INT NOT NULL, channel_id INT NOT NULL, INDEX IDX_2BDC3F714584665A (product_id), INDEX IDX_2BDC3F7172F5A1AA (channel_id), PRIMARY KEY(product_id, channel_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');

        $this->addSql('CREATE TABLE bitbag_open_marketplace_product_listing (id INT AUTO_INCREMENT NOT NULL, latest_draft_id INT DEFAULT NULL, vendor_id INT DEFAULT NULL, product_id INT DEFAULT NULL, uuid CHAR(36) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci` COMMENT \'(DC2Type:uuid)\', code VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, published_at DATETIME DEFAULT NULL, last_verified_at DATETIME DEFAULT NULL, created_at DATETIME DEFAULT NULL, deleted TINYINT(1) NOT NULL, enabled TINYINT(1) NOT NULL, verification_status VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, INDEX IDX_3DEC01B64584665A (product_id), UNIQUE INDEX UNIQ_3DEC01B6D17F50A6 (uuid), INDEX code (code), UNIQUE INDEX UNIQ_3DEC01B6B92E09BA (latest_draft_id), INDEX IDX_3DEC01B6F603EE73 (vendor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');

        $this->addSql('CREATE TABLE bitbag_open_marketplace_product_listing_image (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, type VARCHAR(255) CHARACTER SET utf8mb3 DEFAULT NULL COLLATE `utf8mb3_unicode_ci`, path VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, uuid CHAR(36) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci` COMMENT \'(DC2Type:uuid)\', UNIQUE INDEX UNIQ_EECB958D17F50A6 (uuid), INDEX IDX_EECB9587E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');

        $this->addSql('CREATE TABLE bitbag_open_marketplace_product_listing_price (id INT AUTO_INCREMENT NOT NULL, product_draft_id INT NOT NULL, uuid CHAR(36) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci` COMMENT \'(DC2Type:uuid)\', price DOUBLE PRECISION NOT NULL, minimum_price DOUBLE PRECISION DEFAULT \'0\', original_price DOUBLE PRECISION DEFAULT NULL, channel_code VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, UNIQUE INDEX UNIQ_1199FDED17F50A6 (uuid), INDEX IDX_1199FDE2943AF46 (product_draft_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');

        $this->addSql('CREATE TABLE bitbag_open_marketplace_product_listing_taxons (id INT AUTO_INCREMENT NOT NULL, product_draft_id INT NOT NULL, taxon_id INT NOT NULL, uuid CHAR(36) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci` COMMENT \'(DC2Type:uuid)\', position INT NOT NULL, INDEX IDX_C4FE96562943AF46 (product_draft_id), UNIQUE INDEX UNIQ_C4FE9656D17F50A6 (uuid), INDEX IDX_C4FE9656DE13F470 (taxon_id), UNIQUE INDEX product_draft_taxon_idx (product_draft_id, taxon_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');

        $this->addSql('CREATE TABLE bitbag_open_marketplace_product_translation (id INT AUTO_INCREMENT NOT NULL, product_draft_id INT DEFAULT NULL, uuid CHAR(36) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci` COMMENT \'(DC2Type:uuid)\', name VARCHAR(255) CHARACTER SET utf8mb3 DEFAULT NULL COLLATE `utf8mb3_unicode_ci`, slug VARCHAR(255) CHARACTER SET utf8mb3 DEFAULT NULL COLLATE `utf8mb3_unicode_ci`, description LONGTEXT CHARACTER SET utf8mb3 DEFAULT NULL COLLATE `utf8mb3_unicode_ci`, meta_keywords VARCHAR(255) CHARACTER SET utf8mb3 DEFAULT NULL COLLATE `utf8mb3_unicode_ci`, meta_description VARCHAR(255) CHARACTER SET utf8mb3 DEFAULT NULL COLLATE `utf8mb3_unicode_ci`, short_description VARCHAR(255) CHARACTER SET utf8mb3 DEFAULT NULL COLLATE `utf8mb3_unicode_ci`, locale VARCHAR(255) CHARACTER SET utf8mb3 DEFAULT NULL COLLATE `utf8mb3_unicode_ci`, UNIQUE INDEX UNIQ_FC182761D17F50A6 (uuid), INDEX IDX_FC1827612943AF46 (product_draft_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');

        $this->addSql('CREATE TABLE bitbag_open_marketplace_vendor (id INT AUTO_INCREMENT NOT NULL, vendor_address_id INT DEFAULT NULL, user_id INT DEFAULT NULL, uuid CHAR(36) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci` COMMENT \'(DC2Type:uuid)\', company_name LONGTEXT CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, tax_identifier LONGTEXT CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, phone_number LONGTEXT CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, status VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, enabled TINYINT(1) NOT NULL, edited_at DATETIME DEFAULT NULL, slug VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, description VARCHAR(2048) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, UNIQUE INDEX UNIQ_9FA05F8AD17F50A6 (uuid), UNIQUE INDEX UNIQ_9FA05F8A6FC27B12 (vendor_address_id), UNIQUE INDEX UNIQ_9FA05F8AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');

        $this->addSql('CREATE TABLE bitbag_open_marketplace_vendor_address (id INT AUTO_INCREMENT NOT NULL, country_id INT DEFAULT NULL, city VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, street LONGTEXT CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, postal_code LONGTEXT CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, INDEX IDX_242A2F9FF92F3E70 (country_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');

        $this->addSql('CREATE TABLE bitbag_open_marketplace_vendor_address_update (id INT AUTO_INCREMENT NOT NULL, country_id INT DEFAULT NULL, city VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, street LONGTEXT CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, postal_code LONGTEXT CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, INDEX IDX_2A4D6C01F92F3E70 (country_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');

        $this->addSql('CREATE TABLE bitbag_open_marketplace_vendor_background_image (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, uuid CHAR(36) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci` COMMENT \'(DC2Type:uuid)\', path LONGTEXT CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, UNIQUE INDEX UNIQ_86DFF17ED17F50A6 (uuid), UNIQUE INDEX UNIQ_86DFF17E7E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');

        $this->addSql('CREATE TABLE bitbag_open_marketplace_vendor_image (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, uuid CHAR(36) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci` COMMENT \'(DC2Type:uuid)\', path LONGTEXT CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, UNIQUE INDEX UNIQ_F14961AFD17F50A6 (uuid), UNIQUE INDEX UNIQ_F14961AF7E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');

        $this->addSql('CREATE TABLE bitbag_open_marketplace_vendor_profile_update (id INT AUTO_INCREMENT NOT NULL, vendor_address_id INT DEFAULT NULL, vendor_id INT DEFAULT NULL, company_name LONGTEXT CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, tax_identifier LONGTEXT CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, phone_number LONGTEXT CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, token LONGTEXT CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, description VARCHAR(2048) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, UNIQUE INDEX UNIQ_793E8ED96FC27B12 (vendor_address_id), UNIQUE INDEX UNIQ_793E8ED9F603EE73 (vendor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');

        $this->addSql('CREATE TABLE bitbag_open_marketplace_vendor_profile_update_background_image (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, path LONGTEXT CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, UNIQUE INDEX UNIQ_B564BE8E7E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');

        $this->addSql('CREATE TABLE bitbag_open_marketplace_vendor_profile_update_image (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, path LONGTEXT CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, UNIQUE INDEX UNIQ_3F36779A7E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');

        $this->addSql('CREATE TABLE bitbag_open_marketplace_vendor_shipping_method (id INT AUTO_INCREMENT NOT NULL, vendor_id INT NOT NULL, shipping_method_id INT NOT NULL, channel_code VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, INDEX IDX_78DA976F603EE73 (vendor_id), UNIQUE INDEX vendor_shipping_method_channel_idx (vendor_id, shipping_method_id, channel_code), INDEX IDX_78DA9765F7D6850 (shipping_method_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');

        //overwrites
        $this->addSql('ALTER TABLE sylius_order ADD primary_order_id INT DEFAULT NULL, ADD vendor_id INT DEFAULT NULL, ADD mode VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`;');
        $this->addSql('ALTER TABLE sylius_order ADD INDEX IDX_6196A1F9F603EE73 (vendor_id);');

        $this->addSql('ALTER TABLE sylius_product ADD vendor_id INT DEFAULT NULL, ADD deleted TINYINT(1) NOT NULL;');
        $this->addSql('ALTER TABLE sylius_product ADD INDEX IDX_677B9B74F603EE73 (vendor_id);');

        $this->addSql('ALTER TABLE sylius_shipment ADD vendor_id INT DEFAULT NULL;');
        $this->addSql('ALTER TABLE sylius_shipment ADD INDEX IDX_FD707B33F603EE73 (vendor_id);');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE bitbag_open_marketplace_conversation');

        $this->addSql('DROP TABLE bitbag_open_marketplace_conversation_category');

        $this->addSql('DROP TABLE bitbag_open_marketplace_conversation_message');

        $this->addSql('DROP TABLE bitbag_open_marketplace_draft_attribute');

        $this->addSql('DROP TABLE bitbag_open_marketplace_draft_attribute_translation');

        $this->addSql('DROP TABLE bitbag_open_marketplace_draft_attribute_value');

        $this->addSql('DROP TABLE bitbag_open_marketplace_product_draft');

        $this->addSql('DROP TABLE bitbag_open_marketplace_product_draft_channels');

        $this->addSql('DROP TABLE bitbag_open_marketplace_product_listing');

        $this->addSql('DROP TABLE bitbag_open_marketplace_product_listing_image');

        $this->addSql('DROP TABLE bitbag_open_marketplace_product_listing_price');

        $this->addSql('DROP TABLE bitbag_open_marketplace_product_listing_taxons');

        $this->addSql('DROP TABLE bitbag_open_marketplace_product_translation');

        $this->addSql('DROP TABLE bitbag_open_marketplace_vendor');

        $this->addSql('DROP TABLE bitbag_open_marketplace_vendor_address');

        $this->addSql('DROP TABLE bitbag_open_marketplace_vendor_address_update');

        $this->addSql('DROP TABLE bitbag_open_marketplace_vendor_background_image');

        $this->addSql('DROP TABLE bitbag_open_marketplace_vendor_image');

        $this->addSql('DROP TABLE bitbag_open_marketplace_vendor_profile_update');

        $this->addSql('DROP TABLE bitbag_open_marketplace_vendor_profile_update_background_image');

        $this->addSql('DROP TABLE bitbag_open_marketplace_vendor_profile_update_image');

        $this->addSql('DROP TABLE bitbag_open_marketplace_vendor_shipping_method');

        //overwrites
        $this->addSql('ALTER TABLE sylius_order DROP INDEX IDX_6196A1F9F603EE73;');
        $this->addSql('ALTER TABLE sylius_order DROP COLUMN primary_order_id, DROP COLUMN vendor_id, DROP COLUMN mode;');

        $this->addSql('ALTER TABLE sylius_product DROP INDEX IDX_677B9B74F603EE73;');
        $this->addSql('ALTER TABLE sylius_product DROP COLUMN vendor_id, DROP COLUMN deleted;');

        $this->addSql('ALTER TABLE sylius_shipment DROP INDEX IDX_FD707B33F603EE73;');
        $this->addSql('ALTER TABLE sylius_shipment DROP COLUMN vendor_id;');
    }
}
