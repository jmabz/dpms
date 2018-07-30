<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180730073106 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE clinic_doctor (clinic_id INT NOT NULL, doctor_id INT NOT NULL, INDEX IDX_A09916D5CC22AD4 (clinic_id), INDEX IDX_A09916D587F4FB17 (doctor_id), PRIMARY KEY(clinic_id, doctor_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE clinic_doctor ADD CONSTRAINT FK_A09916D5CC22AD4 FOREIGN KEY (clinic_id) REFERENCES clinic (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE clinic_doctor ADD CONSTRAINT FK_A09916D587F4FB17 FOREIGN KEY (doctor_id) REFERENCES app_users (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE clinic_user');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE clinic_user (clinic_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_DA1105D0CC22AD4 (clinic_id), INDEX IDX_DA1105D0A76ED395 (user_id), PRIMARY KEY(clinic_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE clinic_user ADD CONSTRAINT FK_DA1105D0A76ED395 FOREIGN KEY (user_id) REFERENCES app_users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE clinic_user ADD CONSTRAINT FK_DA1105D0CC22AD4 FOREIGN KEY (clinic_id) REFERENCES clinic (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE clinic_doctor');
    }
}
