<?php declare(strict_types=1);

namespace BAL\DreiDimBild\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\InheritanceUpdaterTrait;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1605897155threes extends MigrationStep
{
    use InheritanceUpdaterTrait;
    public function getCreationTimestamp(): int
    {
        return 1605897155;
    }

    public function update(Connection $connection): void
    {
            $connection->executeUpdate('
                CREATE TABLE IF NOT EXISTS `bal_three` (
                  `id` BINARY(16) NOT NULL,
                  `path` VARCHAR(30) NOT NULL,
                  `name` VARCHAR(55) NOT NULL,
                  `created_at` DATETIME(3) NOT NULL,
                  `updated_at` DATETIME(3) NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
            ');
            $connection->executeUpdate('
            CREATE TABLE IF NOT EXISTS `bal_three_product` (
              `three_id` BINARY(16) NOT NULL,
              `product_id` BINARY(16) NOT NULL,
              `product_version_id` BINARY(16) NOT NULL,
              `created_at` DATETIME(3) NOT NULL,
              PRIMARY KEY (`three_id`, `product_id`, `product_version_id`),
              CONSTRAINT `fk.three_product.three_id` FOREIGN KEY (`three_id`)
                REFERENCES `bal_three` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
              CONSTRAINT `fk.three_product.product_id__product_version_id` FOREIGN KEY (`product_id`, `product_version_id`)
                REFERENCES `product` (`id`, `version_id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ');
           $this->deleteThreeColumn($connection);
           $this->updateInheritance($connection, 'product', 'threes');

        }

    public function updateDestructive(Connection $connection): void
    {

    }
    public function deleteThreeColumn($connection){
        $connection->executeUpdate("set @exist_Check := (
            select count(*) from information_schema.columns
            where TABLE_NAME='product'
            and COLUMN_NAME='threes'
            and TABLE_SCHEMA='shopware'
            );
            set @sqlstmt := if(@exist_Check>0,'alter table product drop column threes', 'select ''''') ;
            prepare stmt from @sqlstmt ;
            execute stmt ;");
    }
}
