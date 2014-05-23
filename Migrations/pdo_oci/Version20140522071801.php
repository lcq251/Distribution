<?php

namespace Icap\DropzoneBundle\Migrations\pdo_oci;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated migration based on mapping information: modify it with caution
 *
 * Generation date: 2014/05/22 07:18:06
 */
class Version20140522071801 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("
            ALTER TABLE icap__dropzonebundle_drop 
            ADD (
                unlocked_drop NUMBER(1) DEFAULT '0' NOT NULL, 
                unlocked_user NUMBER(1) DEFAULT '0' NOT NULL
            )
        ");
        $this->addSql("
            ALTER TABLE icap__dropzonebundle_dropzone 
            DROP (notify_on_drop)
        ");
    }

    public function down(Schema $schema)
    {
        $this->addSql("
            ALTER TABLE icap__dropzonebundle_drop 
            DROP (unlocked_drop, unlocked_user)
        ");
        $this->addSql("
            ALTER TABLE icap__dropzonebundle_dropzone 
            ADD (
                notify_on_drop NUMBER(1) DEFAULT '0' NOT NULL
            )
        ");
    }
}