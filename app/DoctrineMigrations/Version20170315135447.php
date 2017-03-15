<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170315135447 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE order_detail (id INT AUTO_INCREMENT NOT NULL, order_id INT DEFAULT NULL, product_id INT DEFAULT NULL, product_set_id INT DEFAULT NULL, quantity SMALLINT NOT NULL, INDEX IDX_ED896F4672874CBC (product_set_id), INDEX fk_order_has_product1_order1_idx (order_id), INDEX fk_order_detail_product1_idx (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, brand_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description MEDIUMTEXT NOT NULL, price DOUBLE PRECISION NOT NULL, quantity INT DEFAULT NULL, INDEX fk_product_category_idx (category_id), INDEX fk_product_brand1_idx (brand_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product2attribute (id INT AUTO_INCREMENT NOT NULL, attribute_id INT DEFAULT NULL, attribute_value_id INT DEFAULT NULL, product_id INT DEFAULT NULL, INDEX IDX_B0C3D6B6B6E62EFA (attribute_id), INDEX IDX_B0C3D6B665A22152 (attribute_value_id), INDEX IDX_B0C3D6B64584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE brand (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(127) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE attribute (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(45) NOT NULL, description MEDIUMTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE attribute2category (attribute_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_37E2E5C2B6E62EFA (attribute_id), INDEX IDX_37E2E5C212469DE2 (category_id), PRIMARY KEY(attribute_id, category_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, tag VARCHAR(127) NOT NULL, full_name VARCHAR(45) NOT NULL, email VARCHAR(45) NOT NULL, phone VARCHAR(45) NOT NULL, comment TEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE attribute_value (id INT AUTO_INCREMENT NOT NULL, attribute_id INT DEFAULT NULL, value VARCHAR(45) NOT NULL, INDEX fk_atribute_detail_atribute1_idx (attribute_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_set (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, name VARCHAR(45) NOT NULL, quantity INT DEFAULT NULL, INDEX IDX_63B71C34584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product2option (id INT AUTO_INCREMENT NOT NULL, attribute_id INT DEFAULT NULL, product_set_id INT DEFAULT NULL, attribute_value_id INT DEFAULT NULL, price DOUBLE PRECISION DEFAULT NULL, INDEX IDX_D29B6C31B6E62EFA (attribute_id), INDEX IDX_D29B6C3172874CBC (product_set_id), INDEX IDX_D29B6C3165A22152 (attribute_value_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE brand2category (category_id INT NOT NULL, brand_id INT NOT NULL, INDEX IDX_6C50EC2312469DE2 (category_id), INDEX IDX_6C50EC2344F5D008 (brand_id), PRIMARY KEY(category_id, brand_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, username_canonical VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, email_canonical VARCHAR(180) NOT NULL, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', UNIQUE INDEX UNIQ_8D93D64992FC23A8 (username_canonical), UNIQUE INDEX UNIQ_8D93D649A0D96FBF (email_canonical), UNIQUE INDEX UNIQ_8D93D649C05FB297 (confirmation_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE order_detail ADD CONSTRAINT FK_ED896F468D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE order_detail ADD CONSTRAINT FK_ED896F464584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE order_detail ADD CONSTRAINT FK_ED896F4672874CBC FOREIGN KEY (product_set_id) REFERENCES product_set (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD44F5D008 FOREIGN KEY (brand_id) REFERENCES brand (id)');
        $this->addSql('ALTER TABLE product2attribute ADD CONSTRAINT FK_B0C3D6B6B6E62EFA FOREIGN KEY (attribute_id) REFERENCES attribute (id)');
        $this->addSql('ALTER TABLE product2attribute ADD CONSTRAINT FK_B0C3D6B665A22152 FOREIGN KEY (attribute_value_id) REFERENCES attribute_value (id)');
        $this->addSql('ALTER TABLE product2attribute ADD CONSTRAINT FK_B0C3D6B64584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE attribute2category ADD CONSTRAINT FK_37E2E5C2B6E62EFA FOREIGN KEY (attribute_id) REFERENCES attribute (id)');
        $this->addSql('ALTER TABLE attribute2category ADD CONSTRAINT FK_37E2E5C212469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE attribute_value ADD CONSTRAINT FK_FE4FBB82B6E62EFA FOREIGN KEY (attribute_id) REFERENCES attribute (id)');
        $this->addSql('ALTER TABLE product_set ADD CONSTRAINT FK_63B71C34584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product2option ADD CONSTRAINT FK_D29B6C31B6E62EFA FOREIGN KEY (attribute_id) REFERENCES attribute (id)');
        $this->addSql('ALTER TABLE product2option ADD CONSTRAINT FK_D29B6C3172874CBC FOREIGN KEY (product_set_id) REFERENCES product_set (id)');
        $this->addSql('ALTER TABLE product2option ADD CONSTRAINT FK_D29B6C3165A22152 FOREIGN KEY (attribute_value_id) REFERENCES attribute_value (id)');
        $this->addSql('ALTER TABLE brand2category ADD CONSTRAINT FK_6C50EC2312469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE brand2category ADD CONSTRAINT FK_6C50EC2344F5D008 FOREIGN KEY (brand_id) REFERENCES brand (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE order_detail DROP FOREIGN KEY FK_ED896F464584665A');
        $this->addSql('ALTER TABLE product2attribute DROP FOREIGN KEY FK_B0C3D6B64584665A');
        $this->addSql('ALTER TABLE product_set DROP FOREIGN KEY FK_63B71C34584665A');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD44F5D008');
        $this->addSql('ALTER TABLE brand2category DROP FOREIGN KEY FK_6C50EC2344F5D008');
        $this->addSql('ALTER TABLE product2attribute DROP FOREIGN KEY FK_B0C3D6B6B6E62EFA');
        $this->addSql('ALTER TABLE attribute2category DROP FOREIGN KEY FK_37E2E5C2B6E62EFA');
        $this->addSql('ALTER TABLE attribute_value DROP FOREIGN KEY FK_FE4FBB82B6E62EFA');
        $this->addSql('ALTER TABLE product2option DROP FOREIGN KEY FK_D29B6C31B6E62EFA');
        $this->addSql('ALTER TABLE order_detail DROP FOREIGN KEY FK_ED896F468D9F6D38');
        $this->addSql('ALTER TABLE product2attribute DROP FOREIGN KEY FK_B0C3D6B665A22152');
        $this->addSql('ALTER TABLE product2option DROP FOREIGN KEY FK_D29B6C3165A22152');
        $this->addSql('ALTER TABLE order_detail DROP FOREIGN KEY FK_ED896F4672874CBC');
        $this->addSql('ALTER TABLE product2option DROP FOREIGN KEY FK_D29B6C3172874CBC');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD12469DE2');
        $this->addSql('ALTER TABLE attribute2category DROP FOREIGN KEY FK_37E2E5C212469DE2');
        $this->addSql('ALTER TABLE brand2category DROP FOREIGN KEY FK_6C50EC2312469DE2');
        $this->addSql('DROP TABLE order_detail');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product2attribute');
        $this->addSql('DROP TABLE brand');
        $this->addSql('DROP TABLE attribute');
        $this->addSql('DROP TABLE attribute2category');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE attribute_value');
        $this->addSql('DROP TABLE product_set');
        $this->addSql('DROP TABLE product2option');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE brand2category');
        $this->addSql('DROP TABLE `user`');
    }
}
