<?php

namespace Mindme\MarkdownBundle\Migrations\pdo_mysql;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated migration based on mapping information: modify it with caution
 *
 * Generation date: 2018/08/04 11:34:30
 */
class Version20180804113427 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("
            CREATE TABLE mindme_markdown_display_widget_config (
                id INT AUTO_INCREMENT NOT NULL, 
                markdown_id INT DEFAULT NULL, 
                default_mode INT DEFAULT 0 NOT NULL, 
                extra LONGTEXT DEFAULT NULL COMMENT '(DC2Type:json_array)', 
                widgetInstance_id INT DEFAULT NULL, 
                UNIQUE INDEX UNIQ_760D8E12AB7B5A55 (widgetInstance_id), 
                INDEX IDX_760D8E1212662797 (markdown_id), 
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
        ");
        $this->addSql("
            CREATE TABLE mindme_markdown (
                id INT AUTO_INCREMENT NOT NULL, 
                version INT NOT NULL, 
                resourceNode_id INT DEFAULT NULL, 
                UNIQUE INDEX UNIQ_66D008DEB87FAB32 (resourceNode_id), 
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
        ");
        $this->addSql("
            CREATE TABLE mindme_simple_markdown_widget_config (
                id INT AUTO_INCREMENT NOT NULL, 
                content LONGTEXT NOT NULL, 
                widgetInstance_id INT DEFAULT NULL, 
                INDEX IDX_F63AB822AB7B5A55 (widgetInstance_id), 
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
        ");
        $this->addSql("
            CREATE TABLE mindme_markdown_revision (
                id INT AUTO_INCREMENT NOT NULL, 
                markdown_id INT DEFAULT NULL, 
                user_id INT DEFAULT NULL, 
                version INT NOT NULL, 
                content LONGTEXT NOT NULL, 
                htmlcontent LONGTEXT NOT NULL, 
                INDEX IDX_39ED3EB012662797 (markdown_id), 
                INDEX IDX_39ED3EB0A76ED395 (user_id), 
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
        ");
        $this->addSql("
            ALTER TABLE mindme_markdown_display_widget_config 
            ADD CONSTRAINT FK_760D8E12AB7B5A55 FOREIGN KEY (widgetInstance_id) 
            REFERENCES claro_widget_instance (id) 
            ON DELETE CASCADE
        ");
        $this->addSql("
            ALTER TABLE mindme_markdown_display_widget_config 
            ADD CONSTRAINT FK_760D8E1212662797 FOREIGN KEY (markdown_id) 
            REFERENCES mindme_markdown (id) 
            ON DELETE SET NULL
        ");
        $this->addSql("
            ALTER TABLE mindme_markdown 
            ADD CONSTRAINT FK_66D008DEB87FAB32 FOREIGN KEY (resourceNode_id) 
            REFERENCES claro_resource_node (id) 
            ON DELETE CASCADE
        ");
        $this->addSql("
            ALTER TABLE mindme_simple_markdown_widget_config 
            ADD CONSTRAINT FK_F63AB822AB7B5A55 FOREIGN KEY (widgetInstance_id) 
            REFERENCES claro_widget_instance (id) 
            ON DELETE CASCADE
        ");
        $this->addSql("
            ALTER TABLE mindme_markdown_revision 
            ADD CONSTRAINT FK_39ED3EB012662797 FOREIGN KEY (markdown_id) 
            REFERENCES mindme_markdown (id) 
            ON DELETE CASCADE
        ");
        $this->addSql("
            ALTER TABLE mindme_markdown_revision 
            ADD CONSTRAINT FK_39ED3EB0A76ED395 FOREIGN KEY (user_id) 
            REFERENCES claro_user (id) 
            ON DELETE SET NULL
        ");
    }

    public function down(Schema $schema)
    {
        $this->addSql("
            ALTER TABLE mindme_markdown_display_widget_config 
            DROP FOREIGN KEY FK_760D8E1212662797
        ");
        $this->addSql("
            ALTER TABLE mindme_markdown_revision 
            DROP FOREIGN KEY FK_39ED3EB012662797
        ");
        $this->addSql("
            DROP TABLE mindme_markdown_display_widget_config
        ");
        $this->addSql("
            DROP TABLE mindme_markdown
        ");
        $this->addSql("
            DROP TABLE mindme_simple_markdown_widget_config
        ");
        $this->addSql("
            DROP TABLE mindme_markdown_revision
        ");
    }
}