<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180801030720 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE clinic_doctor');
        $this->addSql('ALTER TABLE clinic ADD doctor_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE clinic ADD CONSTRAINT FK_783F8B487F4FB17 FOREIGN KEY (doctor_id) REFERENCES app_users (id)');
        $this->addSql('CREATE INDEX IDX_783F8B487F4FB17 ON clinic (doctor_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE clinic_doctor (clinic_id INT NOT NULL, doctor_id INT NOT NULL, INDEX IDX_A09916D5CC22AD4 (clinic_id), INDEX IDX_A09916D587F4FB17 (doctor_id), PRIMARY KEY(clinic_id, doctor_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE clinic_doctor ADD CONSTRAINT FK_A09916D587F4FB17 FOREIGN KEY (doctor_id) REFERENCES app_users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE clinic_doctor ADD CONSTRAINT FK_A09916D5CC22AD4 FOREIGN KEY (clinic_id) REFERENCES clinic (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE clinic DROP FOREIGN KEY FK_783F8B487F4FB17');
        $this->addSql('DROP INDEX IDX_783F8B487F4FB17 ON clinic');
        $this->addSql('ALTER TABLE clinic DROP doctor_id');
    }
}
