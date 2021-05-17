<?php declare(strict_types=1);

namespace BAL\Contact\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\InheritanceUpdaterTrait;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1617110354bal_contact extends MigrationStep
{
    use InheritanceUpdaterTrait;
    public function getCreationTimestamp(): int
    {
        return 1617110354;
    }

    public function update(Connection $connection): void
    {
        $connection->executeUpdate('
                CREATE TABLE IF NOT EXISTS `bal_contact` (
                  `id` BINARY(16) NOT NULL,
                  `firstname` VARCHAR(60) NOT NULL,
                  `lastname` VARCHAR(55) NOT NULL,
                  `phonenumber` VARCHAR(55) NOT NULL,
                  `email` VARCHAR(100) NOT NULL,
                  `created_at` DATETIME(3) NOT NULL,
                  `updated_at` DATETIME(3) NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
            ');
        $connection->executeUpdate('
            CREATE TABLE IF NOT EXISTS `bal_contact_product` (
              `contact_id` BINARY(16) NOT NULL,
              `product_id` BINARY(16) NOT NULL,
              `product_version_id` BINARY(16) NOT NULL,
              `created_at` DATETIME(3) NOT NULL,
              PRIMARY KEY (`contact_id`, `product_id`, `product_version_id`),
              CONSTRAINT `fk.contact_product.contact_id` FOREIGN KEY (`contact_id`)
                REFERENCES `bal_contact` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
              CONSTRAINT `fk.contact_product.product_id__product_version_id` FOREIGN KEY (`product_id`, `product_version_id`)
                REFERENCES `product` (`id`, `version_id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ');
        $this->deleteContactColumn($connection);
        $this->updateInheritance($connection, 'product', 'contacts');

    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
    public function deleteContactColumn($connection){
        $connection->executeUpdate("set @exist_Check := (
            select count(*) from information_schema.columns
            where TABLE_NAME='product'
            and COLUMN_NAME='contacts'
            and TABLE_SCHEMA='shopware'
            );
            set @sqlstmt := if(@exist_Check>0,'alter table product drop column contacts', 'select ''''') ;
            prepare stmt from @sqlstmt ;
            execute stmt ;");
    }
}
